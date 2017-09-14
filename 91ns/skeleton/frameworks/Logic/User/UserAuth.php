<?php

namespace Micro\Frameworks\Logic\User;

use Micro\Frameworks\Logic\User\UserData\UserSecurity;
use Phalcon\DI\FactoryDefault;

use Micro\Frameworks\Logic\User\UserAuth\UserLogin;
use Micro\Frameworks\Logic\User\UserAuth\UserReg;
use Micro\Frameworks\Logic\User\UserAuth\UserThirdLogin;
use Micro\Frameworks\Logic\User\UserAuth\ThirdParty\AuthFactory;
use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Frameworks\Logic\Investigator\InvAnchor;

use Micro\Models\Users;
use Micro\Models\UserInfo;
use Micro\Models\UserProfiles;
use Micro\Models\DeviceInfo;
use Micro\Models\UsersPostions;
class UserAuth
{
    protected $di;
    protected $session;
    protected $config;
    protected $status;
    protected $validator;
    protected $pathGenerator;
    protected $request;
    protected $storage;
    protected $user = null;
    protected $modelsManager;
    protected $roomModule;
    protected $baseCode;
    protected $configMgr;
    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->session = $this->di->get('session');
        $this->config = $this->di->get('config');
        $this->comm = $this->di->get('comm');
        $this->status = $this->di->get('status');
        $this->validator = $this->di->get('validator');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->request = $this->di->get('request');
        $this->storage = $this->di->get('storage');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->roomModule = $this->di->get('roomModule');
        $this->baseCode = $this->di->get('baseCode');
        $this->configMgr = $this->di->get('configMgr');
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【UserAuth】 error : '.$errInfo);
    }

    /**
     * 验证session中的字段
     */
    public function getSessionData()
    {
        if ($this->session->get($this->config->websiteinfo->authkey) != NULL) {
            $userData = $this->session->get($this->config->websiteinfo->authkey);
            return $userData;
        } else {
            return NULL;
        }
    }

    /**
     * 设置session字段
     */
    public function setSessionData($userData) {
        $this->session->set($this->config->websiteinfo->authkey, $userData);
    }

    /**
     * 获取用户对象
     */
    public function getUser() {
        if ($this->user != null)
            return $this->user;

        if ($this->session->get($this->config->websiteinfo->authkey) != NULL) {

            $userData = $this->session->get($this->config->websiteinfo->authkey);
            if (empty($userData['uid'])) {
                return NULL;
            }
            $this->user = UserFactory::getInstance($userData['uid']);
            return $this->user;
        } else {
            return NULL;
        }
    }
    
    /**
     * 用户注册
     */
    public function userReg($userName, $password, $nickName, $telephone = '') {
        return UserReg::regUser($userName, $password, $nickName, $telephone);
    }

    /**
     * 用户注册 add by 2015/07/20
     */
    public function newUserReg($username, $password, $nickname, $loginType=0, $checkCaptcha = 1) {
        if ($checkCaptcha && !$this->baseCode->checkCaptcha()) {//验证验证码是否正确
            //验证码错误
            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
        }
        //豆子注册
        if ($loginType == '2') {
            $result = $this->userRegOfDouzi($username, $password, $nickname);
            return $this->status->retFromFramework($result['code'], $result['data']);
        }

        //普通注册
        $postData['nickname'] = $nickname;
        $postData['username'] = $username;
        $postData['password'] = $password;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        //判断是否是字母和数字或字母数字的组合
        if (!ctype_alnum($username)) {
            return $this->status->retFromFramework($this->status->getCode('USERNAME_NOT_ALNUM'));
        }
        //不允许使用以ns开头的用户名；（这类用户名为系统预留，按照用户名不可用处理）
        if (substr($username, 0, 2) == 'ns') {
            return $this->status->retFromFramework($this->status->getCode('USERNAME_CANNOT_USE'));
        }
        //不允许使用手机号
        if ($this->validator->isTelephone($username)) {//验证是否是手机号
            return $this->status->retFromFramework($this->status->getCode('CANNOT_USE_TELEPHONE'));
        }

        //昵称不能为纯数字
        if (is_numeric($nickname)) {
            return $this->status->retFromFramework($this->status->getCode('NICKNAME_ALL_NUMBER'));
        }
        //不允许全空格；允许前后、中间空格，数量不限
        if (!trim($nickname)) {
            return $this->status->retFromFramework($this->status->getCode('NICKNAME_ALL_SPACE'));
        }
        //不允许使用以ns开头的昵称；（这类昵称为系统预留，按照昵称不可用处理）
        if (substr($nickname, 0, 2) == 'ns') {
            return $this->status->retFromFramework($this->status->getCode('NICKNAME_CANNOT_USE'));
        }

        $userMgr=  $this->di->get('userMgr');
        //检查用户名是否存在
        $usernameResult = $userMgr->checkUserExist($username);
        if ($usernameResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($this->status->getCode('USER_NAME_EXISTS'));
        }

        //检查昵称是否存在
        $nicknameResult = $userMgr->checkNickNameExist($nickname);
        if ($nicknameResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($this->status->getCode('NICKNAME_HAS_EXISTS'));
        }

         $userData = array(
            'accountId' => time(),///////////////teste///////////////
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
            'status' => 1,
            'telephone' => '',
            'userType' => $this->config->userType->default,
            'openId' => '',
            'canSetUserName' => 0,
            'canSetPassword' => 0,
        );

        $result = UserReg::initUserData($userData);

        if ($result['code'] == $this->status->getCode('OK')) {//注册成功
            $this->userAutoLogin($result['data']['uid']);
            //自动登录
            $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
            $this->session->set($this->config->websiteinfo->user_auto_login_username, $username);
        }

        return $this->status->retFromFramework($result['code'], $result['data']);


    }

    //手机app的注册
    public function userRegByMobile($userName, $password, $nickName, $loginType){
        $result = UserReg::regUserByMobile($userName, $password, $nickName, $loginType);
        if ($result['code'] == $this->status->getCode('OK')) {//注册成功
            $this->userAutoLogin($result['data']['uid']);
            //自动登录
            $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
            $this->session->set($this->config->websiteinfo->user_auto_login_username, $userName);
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /**
     * 手机注册
     */
    public function phoneReg($userName, $password, $nickName,$smsCode)
    {
//        //判断验证码是否正确
//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_reg_phone_sms);
//        $telephone = $this->session->get($this->config->websiteinfo->user_reg_phone);
//        $time = $this->session->get($this->config->websiteinfo->user_reg_phone_time);
//        if(time()-$time>600){
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//         if (!$smsCode_right||!$telephone) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//
//        if ($smsCode_right != $smsCode) {
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
//
//        if ($userName != $telephone) {
//            return $this->status->retFromFramework($this->status->getCode('MOBILEPHONE_IS_ERROR'));
//        }
//        
       
        //判断是否是手机号格式
         if(!$this->checkPhone($userName)){
            return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_BIND'));
        }

        //判断是否已经绑定过手机号
        $parameters = array(
            "telephone" => $userName,
        );
        $usernameBool = Users::count(array(
            "conditions" => "userName=:telephone:",
            "bind" => $parameters,
        ));
        if ($usernameBool > 0) {
            return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_REG'));
        }
        
        //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult=UserReg::checkSmsCaptcha($userName, $this->config->sms_template->register, $smsCode);
        if($smsCheckResult['code'] != $this->status->getCode('OK')){
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }
  
        //注册账号
        $result=UserReg::regUser($userName, $password, $nickName, $userName);
        if($result['code'] == $this->status->getCode('OK')){
            //移除session
           //$this->session->remove($this->config->websiteinfo->user_reg_phone_sms);
           // $this->session->remove($this->config->websiteinfo->user_reg_phone);
           // $this->session->remove($this->config->websiteinfo->user_reg_phone_time);

            $this->userAuth->userAutoLogin($result['data']['uid']);
            //自动登录
            $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
            $this->session->set($this->config->websiteinfo->user_auto_login_username, $userName);

            //新手任务-绑定手机
            $taskMgr = $this->di->get('taskMgr');
            $taskResult = $taskMgr->bindTelephoneTask();
            if ($taskResult['code'] == $this->status->getCode('OK')) {
                $result['data']['firstBindTelephone'] = 1; //首次绑定手机
            } else {
                $result['data']['firstBindTelephone'] = 0; //不是首次绑定手机
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /**
     * 手机注册 add by 2015/07/20
     */
    public function newPhoneReg($username, $password='', $nickname, $smsCode) {
        $postData['nickname'] = $nickname;
        if ($password) {
            $postData['password'] = $password;
        }
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

//        //判断验证码是否正确
//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_reg_phone_sms);
//        $telephone = $this->session->get($this->config->websiteinfo->user_reg_phone);
//        $time = $this->session->get($this->config->websiteinfo->user_reg_phone_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if (!$smsCode_right || !$telephone) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//
//        if ($smsCode_right != $smsCode||!$smsCode) {
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
//
//        if ($username != $telephone) {
//            return $this->status->retFromFramework($this->status->getCode('MOBILEPHONE_IS_ERROR'));
//        }
         
         
        //检查手机号格式是否正确
        if (!$this->checkPhone($username)) {
            return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_BIND'));
        }
 
        //检查是否绑定过手机号
        $parameters = array(
            "telephone" => $username,
        );
         $usernameBool = Users::count(array(
                    "conditions" => "userName=:telephone:",
                    "bind" => $parameters,
        ));
         if ($usernameBool > 0) {
            return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_REG'));
        }
         
        //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult=UserReg::checkSmsCaptcha($username, $this->config->sms_template->register, $smsCode);
        if($smsCheckResult['code'] != $this->status->getCode('OK')){
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }

        $userMgr = $this->di->get('userMgr');

        //检查昵称是否存在
        $nicknameResult = $userMgr->checkNickNameExist($nickname);
        if ($nicknameResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($this->status->getCode('NICKNAME_HAS_EXISTS'));
        }

        $canSetUserName = 1; //手机号注册的用户可修改用户名
        $canSetPassword = 0;
        
        //手机注册改为不设置密码 edit by 2015/09/28
        $newUsername = $this->setRandCode(10);
        if (!$password) {
            $rand = $this->setRandCode(6);
            $password = md5($rand); //密码
            $canSetPassword = 1;
        }

        $userData = array(
            'accountId' => time(), ///////////////testtesttestdddddddddddddddddddd///////////////
            'username' => $newUsername,
            'password' => $password,
            'nickname' => $nickname,
            'status' => 1,
            'telephone' => $username,
            'userType' => $this->config->userType->telephone,
            'openId' => '',
            'canSetUserName' => $canSetUserName,
            'canSetPassword' => $canSetPassword,
        );

        $result = UserReg::initUserData($userData);

        if ($result['code'] == $this->status->getCode('OK')) {
            //移除session
            //$this->session->remove($this->config->websiteinfo->user_reg_phone_sms);
           // $this->session->remove($this->config->websiteinfo->user_reg_phone);
            //$this->session->remove($this->config->websiteinfo->user_reg_phone_time);
            
            $this->userAutoLogin($result['data']['uid']);
            //自动登录
            $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
            $this->session->set($this->config->websiteinfo->user_auto_login_username, $newUsername);
            
            //新手任务-绑定手机
            $taskMgr = $this->di->get('taskMgr');
            $taskResult = $taskMgr->bindTelephoneTask();
            if ($taskResult['code'] == $this->status->getCode('OK')) {
                $result['data']['firstBindTelephone'] = 1; //首次绑定手机
            } else {
                $result['data']['firstBindTelephone'] = 0; //不是首次绑定手机
            }
            //推荐用户领取礼包
            $recResult = $userMgr->getRecommendPackageGift($username);
            if ($recResult['code'] == $this->status->getCode('OK')) {
                $result['data']['isRecommend'] = 1;
                $result['data']['reward'] = $recResult['code']['reward'];
            }/*else{
                 //注册赠送礼包 add by 2015/12/22
                $giftPacketId = $this->config->phoneRegGiftId;
                $user = UserFactory::getInstance($result['data']['uid']);
                $user->getUserItemsObject()->giveGiftPackage($giftPacketId);
                $configResult = $this->configMgr->getgiftPackageBaseConfig($giftPacketId, 1);
                if ($configResult['code'] == $this->status->getCode('OK')) {
                    $result['data']['reward'] = $configResult['data'];
                }
            }*/
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    //检测昵称是否存在
    public function checkNickName($nickName){
        $postData['nickname'] = $nickName;//昵称
        //昵称格式验证
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //豆子账号 
        if ($this->config->channelType == 2) {
            return $this->checkNickNameOfDouzi($nickName);
        }

        //普通账号
        try {
            //查询昵称是否存在，区分大小写
            $sql = "select uid from pre_user_info where binary nickName  = '{$nickName}' limit 1";
            $connection = $this->di->get('db');
            $result = $connection->fetchOne($sql);
            if ($result) {//昵称已存在
                return $this->status->retFromFramework($this->status->getCode('USER_NICK_NAME_EXIST'));
            }
            //昵称不存在
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询豆子昵称是否存在
    public function checkNickNameOfDouzi($nickName) {
        try {
            $result = $this->di->get('oauth')->createOAuth('douzi')->checkNickName($nickName);
            if ($result) {//昵称已存在
                return $this->status->retFromFramework($this->status->getCode('USER_NICK_NAME_EXIST'));
            }
            //昵称不存在
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DOUZI_ERR'), $e->getMessage());
        }
    }

    /**
     * 用户删除
     * （只在后台使用）
     */
    public function userUnreg($uid)
    {
        $user = Users::findFirst("uid = ".$uid);
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        $username = $user->userName;
        $result = FALSE;
        if ($user->userType == 0) {
            $result = $this->comm->userUnreg($username);
        }
        else if ($user->userType != 0){
            $result = $this->comm->qqUnreg($username);
        }
        else {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        if ($result === FALSE) {
            $this->errLog('user unreg error : userType = '.$user->userType);
            return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        $errorCode = $result['code'];
        if ($errorCode != 0) {
            return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
        }

        // delete 3
        $user->delete();
        $info = UserInfo::findFirst("uid = ".$uid);
        if ($info) {
            $info->delete();
        }
        
        $profile = UserProfiles::findFirst("uid = ".$uid);
        if ($profile) {
            $profile->delete();
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function resendEmail($email){
        UserReg::resendEmail($email);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }


    //判断用户是否频繁操作
    public function questCheck(){
        return true;
    }

    /*豆子注册*/
    public function userRegOfDouzi($userName, $password, $nickName){
        $result = $this->di->get('oauth')->createOAuth('douzi')->register($userName, $password, $nickName);
        if($result['code'] == $this->status->getCode('OK')){
            return $this->userLoginOfDouzi($userName, $password);
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /**
     * 用户登录
     */
    public function userLogin($username, $password, $type = 0) {
        if ($type == 0) {
            $postData['username'] = $username;
            $postData['password'] = $password;
            if ($this->getUser()) {
                //return $this->status->retFromFramework($this->status->getCode('USER_HAS_LOGIN'), '');
                UserLogin::userLogout();
            }
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
        }
        try {
            $username = $this->validator->qs($username);
            $sql = "select * from pre_users where binary userName='{$username}' limit 1";
            $connection = $this->di->get('db');
            $userInfo = $connection->fetchOne($sql);
            if ($userInfo) {
                $str = md5($userInfo['key'] . $password);
                if ($str != $userInfo['password']) {//密码不相同
                    return $this->status->retFromFramework($this->status->getCode('USER_PASSWORD_ERROR'));
                }
                if ($userInfo['status'] != 1) {//账号已被冻结
                    return $this->status->retFromFramework($this->status->getCode('USER_NOT_ACTIVE'));
                }
                return UserLogin::setLoginStatus($userInfo['uid']);
            }

            //取消手机号密码登录 edit by 2015/09/25
            //如果是手机号
            /**if ($this->validator->isTelephone($username)) {
                $ret = \Micro\Models\UserInfo::findfirst("telephone='" . $username . "'");
                if ($ret) {
                    $userRet = \Micro\Models\Users::findfirst($ret->uid);
                    $str = md5($userRet->key . $password);
                    if ($str != $userRet->password) {//密码不相同
                        return $this->status->retFromFramework($this->status->getCode('USER_PASSWORD_ERROR'));
                    }
                    if ($userRet->status != 1) {//账号已被冻结
                        return $this->status->retFromFramework($this->status->getCode('USER_NOT_ACTIVE'));
                    }
                    return UserLogin::setLoginStatus($ret->uid);
                }
            }**/

            return $this->status->retFromFramework($this->status->getCode('USER_PASSWORD_ERROR'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 手机用户登录
     */
    public function userLoginByMobile($username, $password)
    {
        $returnStatusCode = $this->status->getStatus('OK');
        if (empty($username) || empty($password)) {
            return $this->status->retFromFramework($returnStatusCode, $this->status->getCode('PARAM_ERROR'));
        }

        $result = $this->userLogin($username, $password);
        $errorCode = $result['code'];
        $resultData = $result['data'];

        if ($errorCode == $this->status->getCode('OK')) {
            $uid = $resultData['uid'];
            try {
                $phql = "SELECT * FROM Micro\Models\Users where uid = '".$uid."'"." LIMIT 1";
                $query = $this->modelsManager->createQuery($phql);
                $users = $query->execute();
                if ($users->valid()) {
                    foreach ($users as $user) {
                        $userDevice = $this->session->get($this->config->websiteinfo->mobileauthkey);
//                        $userDevice = $this->session->get($this->config->userSession->invDeviceInfo);
                        $this->roomModule->getRoomMgrObject()->setDeviceInfoSession($userDevice);
                        //更新设备信息表
//                        $phql = "UPDATE Micro\Models\DeviceInfo SET deviceid = ?0,devicetoken=?2 WHERE  uid= ?1";
//                        $this->modelsManager->executeQuery($phql,
//                            array(
//                                0   => $userDevice['deviceid'],
//                                1   => $user->uid,
//                                2   => $userDevice['devicetoken'],
//                            )
//                        );

                        
                        //写入用户token表，用于下次自动登录用
                        $deviceid = isset($userDevice['deviceid']) ? $userDevice['deviceid'] : '';
                        $token = $this->setTokenInfo($uid, $deviceid);

                        //写入session
                        $userdata['uid'] = $user->uid;
                        $userdata['name'] = $user->userName;
                        $this->session->set($this->config->websiteinfo->authkey, $resultData);

                        $userdata['token'] = $token;
                        $userdata['deviceid'] = $deviceid;
                        $userdata['isRec'] = $resultData['isRec'];

                        return $this->status->retFromFramework($this->status->getCode('OK'), $userdata/*, $resultData*/);
                    }
                }else {
                    return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'), $this->status->getCode('USER_NOT_EXIST'));
                }
            }catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
        }

        return $this->status->retFromFramework($errorCode, $resultData, $resultData);
    }
    //用户设置自动登录
    public function setAutoLogin(){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $userName = $this->session->get($this->config->websiteinfo->user_auto_login_username);
        $password = $this->session->get($this->config->websiteinfo->user_auto_login_password);
        //删除
        $this->session->remove($this->config->websiteinfo->user_auto_login_password);
        $this->session->remove($this->config->websiteinfo->user_auto_login_username);
        if(empty($userName)||empty($password)){
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $revert['userName'] = $userName;
        $revert['password'] = $password;
        //登录日志记录
        $userinfo = \Micro\Models\Users::findfirst("userName='" . $userName . "'");
        if ($userinfo != false) {
            $log = new \Micro\Frameworks\Logic\Base\BaseStatistics();
            $log->setLoginLog($userinfo->uid);
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $revert);
    }

    public function userAutoLogin($uid) {
        return UserLogin::setLoginStatus($uid);
    }

    public function userLogout(){
        UserLogin::userLogout();
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /**
     * 检测手机是否绑定过
     *
     * @param $telephone
     * @return bool
     */
    public function checkPhone($telephone){
        if($telephone){
            $parameters = array(
                "telephone" => $telephone,
            );

            $telephoneBool = UserInfo::count(array(
                "conditions" => "telephone=:telephone:",
                "bind" => $parameters,
            ));

            if($telephoneBool > 0){
                return FALSE;
            }else{
                return TRUE;
            }
        }

        return FALSE;
    }

    /*
      * 手机注册，获取验证码
      * */
    public function regPhoneSendCode($telephone,$checkCaptcha=0){

        $postData['telephone'] = $telephone;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        
        if ($checkCaptcha && !$this->baseCode->checkCaptcha()) {//验证验证码是否正确
            //验证码错误
            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
        }

        $parameters = array(
            "telephone" => $telephone,
        );
        
        $usernameBool = Users::count(array(
                    "conditions" => "userName=:telephone:",
                    "bind" => $parameters,
        ));
        
        if ($usernameBool > 0) {
            return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_REG'));
        }

//        $parameters = array(
//            "telephone" => $telephone,
//        );
//        $telephoneBool = UserInfo::count(array(
//            "conditions" => "telephone=:telephone:",
//            "bind" => $parameters,
//        ));
        if(!$this->checkPhone($telephone)){
            return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_BIND'));
        }

        //$result = UserReg::sendSmsVerify($telephone, $this->config->webType[$this->config->channelType]['smsType']->register);
        
        //改用bmob的短信通道 edit by 2015/07/21
        $result = UserReg::sendSmsVerify($telephone, $this->config->sms_template->register);
        
//        if($smsresult['code'] == $this->status->getCode('OK')){
//            //设置session
//            //$this->session->set($this->config->websiteinfo->user_reg_phone_sms, $result['data']['smsCode']);
//            //$this->session->set($this->config->websiteinfo->user_reg_phone, $telephone);
//            //$this->session->set($this->config->websiteinfo->user_reg_phone_time, time());
//            return $this->status->retFromFramework($this->status->getCode('OK'));
//        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /*
      * 绑定手机，获取验证码
      * */
    public function bindPhoneSendCode($telephone='',$checkCaptcha=0){
        $postData['telephone'] = $telephone;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        
        if ($checkCaptcha && !$this->baseCode->checkCaptcha()) {//验证验证码是否正确
            //验证码错误
            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
        }
 
        $userData = $user->getUserInfoObject()->getUserInfo();
        if(!empty($userData['telephone'])){
            return $this->status->retFromFramework($this->status->getCode('HAS_BIND_TELEPHONE'));
        }

        //改成一个手机号允许绑定多个账号 edit by 2015/09/28
        $phonecount = UserInfo::count("telephone='" . $telephone . "'");
        if ($phonecount >= $this->config->bindPhoneLimit) {
            return $this->status->retFromFramework($this->status->getCode('BIND_PHONE_LIMIT'));
        }

        //判断每次绑定/解绑次数是否已超过限制 add by 2015/08/24
        $smsTypes = $this->config->sms_template->bindPhone . "," . $this->config->sms_template->unbindPhone;
        $userSmsCount = $user->getUserInfoObject()->getUserSmsSendCount($smsTypes);
        if ($userSmsCount >= $this->config->bindPhoneSmsLimit) {
            return $this->status->retFromFramework($this->status->getCode('SMS_NUM_LIMITED'));
        }

        //改用bmob的短信通道 edit by 2015/07/21
        $result = UserReg::sendSmsVerify($telephone, $this->config->sms_template->bindPhone);
        
//        if($result['code'] == $this->status->getCode('OK')){
//            //设置session
//            $this->session->set($this->config->websiteinfo->user_bind_phone_sms, $result['data']['smsCode']);
//            $this->session->set($this->config->websiteinfo->user_bind_phone, $telephone);
//            $this->session->set($this->config->websiteinfo->user_bind_phone_time, time());
//        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /*
      * 提现，获取验证码
      * */
    public function sendTelSmsCode(){

        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userData = $user->getUserInfoObject()->getUserInfo();
        if(empty($userData['telephone'])){
            return $this->status->retFromFramework($this->status->getCode('HAS_NOT_BIND_TELEPHONE'));
        }

        $telephone = $userData['telephone'];

        //$result = UserReg::sendSmsVerify($telephone, $this->config->webType[$this->config->channelType]['smsType']->settleCode);
        $smsTypes = $this->config->sms_template->settleCode;
        $userSmsCount = $user->getUserInfoObject()->getUserSmsSendCount($smsTypes);
        if ($userSmsCount >= $this->config->exchangeCashSmsLimit) {
            return $this->status->retFromFramework($this->status->getCode('SMS_NUM_LIMITED'));
        }

        //改用bmob的短信通道 edit by 2015/07/21
        $result = UserReg::sendSmsVerify($telephone, $this->config->sms_template->settleCode);
        
//        if($result['code'] == $this->status->getCode('OK')){
//            //设置session
//            $this->session->set($this->config->websiteinfo->user_settle_phone_sms, $result['data']['smsCode']);
//            $this->session->set($this->config->websiteinfo->user_settle_phone, $telephone);
//            $this->session->set($this->config->websiteinfo->user_settle_phone_time, time());
//            return $this->status->retFromFramework($this->status->getCode('OK'));
//        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /*
     * 绑定手机
     * */
    public function bindPhone($smsCode, $telephone = ''){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

//        if(!empty($telephone)){
//            $sessiontelephone = $this->session->get($this->config->websiteinfo->user_bind_phone);
//            if($sessiontelephone != $telephone){
//                return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//            }
//        }else{
//            $telephone = $this->session->get($this->config->websiteinfo->user_bind_phone);
//        }
        
//        if (empty($telephone)) {
//            return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
//        }
        
        //查询手机号
        $uid = $user->getUid();
        $smsRes = \Micro\Models\SmsLog::findfirst("uid=" . $uid . " and type=" . $this->config->sms_template->bindPhone . " and status=1 order by id desc");
        if ($smsRes == false || !$smsRes->telephone) {
            return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
        }
        $telephone = $smsRes->telephone;
 
        $userData = $user->getUserInfoObject()->getUserInfo();
        if (!empty($userData['telephone'])) {
            return $this->status->retFromFramework($this->status->getCode('HAS_BIND_TELEPHONE'));
        }
        
        //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult = UserReg::checkSmsCaptcha($telephone, $this->config->sms_template->bindPhone, $smsCode);
        if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }

        //改成一个手机号允许绑定多个账号 edit by 2015/09/28
        $phonecount = UserInfo::count("telephone='" . $telephone . "'");
        if ($phonecount >= $this->config->bindPhoneLimit) {
            return $this->status->retFromFramework($this->status->getCode('BIND_PHONE_LIMIT'));
        }

//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_bind_phone_sms);
//        $telephone = $this->session->get($this->config->websiteinfo->user_bind_phone);
//        
//        $time = $this->session->get($this->config->websiteinfo->user_bind_phone_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//
//        if($smsCode_right != $smsCode){
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
        
       
 
        try {
            $user = \Micro\Models\UserInfo::findFirst($userData['uid']);
            $user->telephone = $telephone;
            $result = $user->save();
            $firstBindTelephone = 0;
            $isRecommend = 0;
            if ($result) {//修改成功
                //新手任务-绑定手机
                $taskMgr = $this->di->get('taskMgr');
                $taskResult = $taskMgr->bindTelephoneTask();
                if ($taskResult['code'] == $this->status->getCode('OK')) {
                    $firstBindTelephone = 1; //首次绑定手机
                }
                //是否有推荐礼包
                $userMgr = $this->di->get('userMgr');
                $recResult = $userMgr->bindPhoneRecommend($telephone);
                if ($recResult['code'] == $this->status->getCode('OK')) {
                    $isRecommend = 1;
                }
            }
            $return['isRecommend'] = $isRecommend;
            $return['firstBindTelephone'] = $firstBindTelephone;

            //移除session
            //$this->session->remove($this->config->websiteinfo->user_bind_phone_sms);
            //$this->session->remove($this->config->websiteinfo->user_bind_phone);
            //$this->session->remove($this->config->websiteinfo->user_bind_phone_time);
            
            $return['telephone'] = substr_replace($telephone, '****', 3, 4); //手机号
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('bindPhone error errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
        
    }

    /*
     * 提现：验证验证码
     * */
    public function checkTelSmsCode($smsCode, $money, $type, $arr){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userData = $user->getUserInfoObject()->getUserInfo();
        $telephone = $userData['telephone'];
        if (empty($telephone)) {
            return $this->status->retFromFramework($this->status->getCode('HAS_NOT_BIND_TELEPHONE'));
        }

//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_settle_phone_sms);
//        $telephone = $this->session->get($this->config->websiteinfo->user_settle_phone);
//        
//        $time = $this->session->get($this->config->websiteinfo->user_settle_phone_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//
//        if(!$smsCode_right || !$smsCode || $smsCode_right != $smsCode){
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
//
//        if(empty($telephone)){
//            return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
//        }
        
        //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult = UserReg::checkSmsCaptcha($telephone, $this->config->sms_template->settleCode, $smsCode);
        if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }

        //添加提现日志
        $uid =  $user->getUid();
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->addSettleLog($uid, $money, $type, $arr);
//        if($result['code'] == $this->status->getCode('OK')){
//            //移除session
//            $this->session->remove($this->config->websiteinfo->user_settle_phone_sms);
//            $this->session->remove($this->config->websiteinfo->user_settle_phone);
//            $this->session->remove($this->config->websiteinfo->user_settle_phone_time);
//
//            return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
//        }

        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /*
     * 更新银行账号信息，获取验证码
     * */
     public function updateAccountSendCode(){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userData = $user->getUserInfoObject()->getUserInfo();
        if(empty($userData['telephone'])){
            return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
        }
        // $result = UserReg::sendSmsVerify($userData['telephone'], $this->config->webType[$this->config->channelType]['smsType']->unbindPhone);

        //判断每次绑定/解绑次数是否已超过限制 add by 2015/08/24
        $smsTypes = $this->config->sms_template->updateAccount;
        $userSmsCount = $user->getUserInfoObject()->getUserSmsSendCount($smsTypes);
        if ($userSmsCount >= $this->config->updateAccountSmsLimit) {
            return $this->status->retFromFramework($this->status->getCode('SMS_NUM_LIMITED'));
        }

        //改用bmob的短信通道 edit by 2015/07/21
        $result = UserReg::sendSmsVerify($userData['telephone'], $this->config->sms_template->updateAccount);

        if($result['code'] == $this->status->getCode('OK')){
            //设置session
            //$this->session->set($this->config->websiteinfo->user_bank_account, $result['data']['smsCode']);
            //$this->session->set($this->config->websiteinfo->user_bank_account_time,time());
            $revert['telephone'] = $userData['telephone'];
            return $this->status->retFromFramework($this->status->getCode('OK'), $revert);
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /*
     * 解绑手机，获取验证码
     * */
    public function unbindPhoneSendCode(){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userData = $user->getUserInfoObject()->getUserInfo();
        if(empty($userData['telephone'])){
            return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
        }
         // $result = UserReg::sendSmsVerify($userData['telephone'], $this->config->webType[$this->config->channelType]['smsType']->unbindPhone);
        
         //判断每次绑定/解绑次数是否已超过限制 add by 2015/08/24
        $smsTypes = $this->config->sms_template->bindPhone . "," . $this->config->sms_template->unbindPhone;
        $userSmsCount = $user->getUserInfoObject()->getUserSmsSendCount($smsTypes);
        if ($userSmsCount >= $this->config->bindPhoneSmsLimit) {
            return $this->status->retFromFramework($this->status->getCode('SMS_NUM_LIMITED'));
        }
        
         //改用bmob的短信通道 edit by 2015/07/21
        $result = UserReg::sendSmsVerify($userData['telephone'], $this->config->sms_template->unbindPhone);
        
        if($result['code'] == $this->status->getCode('OK')){
            //设置session
           // $this->session->set($this->config->websiteinfo->user_unbind_phone_sms, $result['data']['smsCode']);
          // $this->session->set($this->config->websiteinfo->user_unbind_phone_time,time());
            $revert['telephone'] = $userData['telephone'];
            return $this->status->retFromFramework($this->status->getCode('OK'), $revert);
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /*
     * 解绑手机
     * */
    public function unbindPhone($smsCode){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userData = $user->getUserInfoObject()->getUserInfo();
        $telephone=$userData['telephone'];
        if(empty($telephone)){
            return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
        }

//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_unbind_phone_sms);
//        $time = $this->session->get($this->config->websiteinfo->user_unbind_phone_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if($smsCode_right != $smsCode){
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
        
        //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult = UserReg::checkSmsCaptcha($telephone, $this->config->sms_template->unbindPhone, $smsCode);
        if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }

        try{
            $user = \Micro\Models\UserInfo::findFirst($userData['uid']);
            $user->telephone = '';
            $user->save();
        }catch (\Exception $e) {
            $this->errLog('unbindPhone error errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());;
        }

        //移除session
        //$this->session->remove($this->config->websiteinfo->user_unbind_phone_sms);
        //$this->session->remove($this->config->websiteinfo->user_unbind_phone_time);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /**
     * 第三方用户返回
     */
    /*public function userLoginCallback($code)
    {
        return UserThirdLogin::loginCallback($code);
    }*/

    /**
     * 第三方用户返回
     */
    public function userLoginCallback($state, $code){
        return UserThirdLogin::loginCallback($state, $code);
    }

    /**
     * 第三方用户返回手机
     */
    public function userLoginCallbackFromMobile($type, $openId , $nickName, $avatar){
        return UserThirdLogin::loginCallbackFromMobile($type, $openId , $nickName, $avatar);
    }

    /**
     * 用户登录(豆子)
     */
    public function userLoginByDZ($state, $code){
        $this->di->get('oauth')->createOAuth('douzi')->getAuthorizeURL($state);
        return UserThirdLogin::loginCallback($state, $code);
    }
    /*豆子登录*/
    public function userLoginOfDouzi($username, $password){
        $state = NULL;
        $oauth = $this->di->get('oauth')->createOAuth('douzi');
        $oauth->getAuthorizeURL($state);
        $code = $oauth->login($username, $password);
        if($this->status->getCode('OK') == $code['code']){
            return UserThirdLogin::loginCallback($state, $code['data']);
        }
        return $this->status->retFromFramework($code['code'], $code['data']);
        //$this->loginToDouzi($username, $password);
    }

    /**
     * 修改密码(旧)
     *
     * @param $oldPwd
     * @param $newPwd
     */
    public function oldChangePassword($oldPwd, $newPwd){
        $postData['oldpassword'] = $oldPwd;
        $postData['newpassword'] = $newPwd;
        if (!$this->getUser()) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $userdata = $this->session->get($this->config->websiteinfo->authkey);
        $isValid = $this->validator->validate($postData);

        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

 
        // 修改
        $result = $this->comm->userChangePwd($userdata['name'], $oldPwd, $newPwd);
        if ($result === FALSE) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        $errorCode = $result['code'];
        if ($errorCode != 0) {
            return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
        }

        $this->userLogout();
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }
    /**
     * 修改密码(新)
     *
     * @param $oldPwd
     * @param $newPwd
     */
    public function changePassword($oldPwd, $newPwd){
        $postData['oldpassword'] = $oldPwd;
        $postData['newpassword'] = $newPwd;
        if (!$this->getUser()) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 获得用户信息
        $userData = $this->getUser()->getUserInfoObject()->getUserAccountInfo();
        $md5OldPass = md5($userData['key'] . $oldPwd);
        if($md5OldPass != $userData['password']){
            return $this->status->retFromFramework($this->status->getCode('OLD_PASSWORD_ERROR'));
        }
        $md5NewPass =  md5($userData['key'] . $newPwd);
        //判断新旧密码不能一样
        if ($md5OldPass == $md5NewPass) {
            return $this->status->retFromFramework($this->status->getCode('PASSWORDS_CANNOT_BE_SAME'));
        }

        $user = Users::findfirst($userData['uid']);
        $user->password = $md5NewPass;
        $ret = $user->save();
        if($ret){
            $this->userLogout();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }else{
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }

    }

    public function isSignAnchor(){

    }


    public function enterRoom(){
        $user = $this->getUser();
        if ($user == NULL) {
            $time = time();
            $name = 'visitor_' . $time;
            $userData = $this->getSessionData();
            $userData['accountId'] = $name;
            $userData['uid'] = '';
            $this->setSessionData($userData);
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /**
     * 登录nodejs获取token
     */
    public function loginToNodeJS($roomId) {
        /*$user = $this->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }*/

        $userData = $this->getSessionData();

        $result = $this->comm->loginToNodeJS($userData['accountId']);
        if ($result === false) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        $errorCode = $result['code'];
        if ($errorCode != 0) {
            return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
        }

        /*$userData = $this->getSessionData();
        if (empty($userData['roomToken'])) {
            $userData['roomToken'] = array();
        }
        $userData['roomToken'][$roomId] = $result['token'];
        $this->setSessionData($userData);*/

        $result['accountid'] = $userData['accountId'];
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }
    
    
    /*
     * 赠送vip、座驾时，获取验证码
     * */
    public function userGiveItemSendCode($type){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userData = $user->getUserInfoObject()->getUserInfo();
        if (empty($userData['telephone'])) {
            return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
        }
        $smsTypes = $this->config->sms_template->giveCode;
        $userSmsCount = $user->getUserInfoObject()->getUserSmsSendCount($smsTypes);
        if ($userSmsCount >= $this->config->purchasingForOtherLimit) {
            return $this->status->retFromFramework($this->status->getCode('SMS_NUM_LIMITED'));
        }

        if ($type == 'giveVip') {
            
        }

        //改用bmob的短信通道 edit by 2015/07/21
        $result = UserReg::sendSmsVerify($userData['telephone'], $smsTypes);

//        if ($result['code'] == $this->status->getCode('OK')) {
//            //设置session
//            if ($type == 'giveVip') {
//               // $this->session->set($this->config->websiteinfo->user_give_vip_phone_sms, $result['data']['smsCode']);
//               // $this->session->set($this->config->websiteinfo->user_give_vip_phone, $userData['telephone']);
//                $this->session->set($this->config->websiteinfo->user_give_vip_phone_time, time());
//            } elseif ($type == 'giveCar') {
//                $this->session->set($this->config->websiteinfo->user_give_car_phone_sms, $result['data']['smsCode']);
//                $this->session->set($this->config->websiteinfo->user_give_car_phone, $userData['telephone']);
//                $this->session->set($this->config->websiteinfo->user_give_car_phone_time, time());
//            }
//            return $this->status->retFromFramework($this->status->getCode('OK'));
//        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    
        
    /*
     * 用户手机验证码登录时，获取验证码
     * */
    public function phoneLoginSendCode($telephone, $mobile = 0){
        if($mobile == 0 && !$this->baseCode->checkCaptcha()){
            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
        }

        if (!$this->validator->isTelephone($telephone)) {//验证手机号是否正确
            return $this->status->retFromFramework($this->status->getCode('MOBILEPHONE_IS_ERROR'));
        }

        $result = UserReg::sendSmsVerify($telephone, $this->config->sms_template->smsLogin);
        if ($result['code'] == $this->status->getCode('OK')) {
            //$this->session->set($this->config->websiteinfo->user_phone_login_sms, $result['data']['smsCode']);
            //$this->session->set($this->config->websiteinfo->user_phone_login_phone, $telephone);
            //$this->session->set($this->config->websiteinfo->user_phone_login_time, time());
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }

        return $this->status->retFromFramework($result['code'], $result['data']);

    }
    
    
    /**
     * 手机验证码登录 add by 2015/07/20
     */
    public function phoneSmsCodeLogin($telephone, $smsCode) {
//        //判断验证码是否正确
//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_phone_login_sms);
//        $phone = $this->session->get($this->config->websiteinfo->user_phone_login_phone);
//        $time = $this->session->get($this->config->websiteinfo->user_phone_login_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if (!$smsCode_right || !$phone) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if ($smsCode_right != $smsCode || !$smsCode) {
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
//        if ($telephone != $phone) {
//            return $this->status->retFromFramework($this->status->getCode('MOBILEPHONE_IS_ERROR'));
//        }
        
        //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult = UserReg::checkSmsCaptcha($telephone, $this->config->sms_template->smsLogin, $smsCode);
        if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }

        //查询手机号是否存在
        $sql = "select u.uid,u.userName,u.password,u.status from \Micro\Models\Users u inner join \Micro\Models\UserInfo ui on u.uid=ui.uid where ui.telephone='{$telephone}'";
        $query = $this->modelsManager->createQuery($sql);
        $userResult = $query->execute();
        $userResult=$userResult->toArray();
        //已存在，直接登录
        if (isset($userResult[0]['uid']) && $userResult[0]['uid']) {
            if($userResult[0]['status']!=1){//用户被禁用
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_ACTIVE'));
            }
            $tmpRes = $this->userAutoLogin($userResult[0]['uid']);
            //自动登录
            $this->session->set($this->config->websiteinfo->user_auto_login_password, $userResult[0]['password']);
            $this->session->set($this->config->websiteinfo->user_auto_login_username, $userResult[0]['userName']);
                                                 
             //移除session
            //$this->session->remove($this->config->websiteinfo->user_phone_login_sms);
           // $this->session->remove($this->config->websiteinfo->user_phone_login_phone);
            //$this->session->remove($this->config->websiteinfo->user_phone_login_time);
           
            $data = array(
                'uid' => $userResult[0]['uid'],
                'isRec' => 1,
            );
            if($tmpRes['code'] == $this->status->getCode('OK')){
                $data['isRec'] = $tmpRes['data']['isRec'];
            }
 
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }

        //判断是否已被注册
        $parameters = array(
            "telephone" => $telephone,
        );

        $usernameBool = Users::count(array(
                    "conditions" => "userName=:telephone:",
                    "bind" => $parameters,
        ));

        if ($usernameBool > 0) {
            return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_REG'));
        }

        //未注册，则注册并登录
        $username = $this->setRandCode(10); //用户名
        $rand = $this->setRandCode(6);
        $password = md5($rand); //密码
        $nickname = $this->setRandCode(8); //昵称
        $canSetUserName = 1; //可修改用户名
        $canSetPassword = 1; //可设置密码
        $userData = array(
            'accountId' => time(), ///////////////teste///////////////
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
            'status' => 1,
            'telephone' => $telephone,
            'userType' => $this->config->userType->telephone,
            'openId' => '',
            'canSetUserName' => $canSetUserName,
            'canSetPassword' => $canSetPassword,
        );

        $result = UserReg::initUserData($userData);

        if ($result['code'] == $this->status->getCode('OK')) {//注册成功
            $tmpRes = $this->userAutoLogin($result['data']['uid']);
            $result['data']['isRec'] = 1;
            if($tmpRes['code'] == $this->status->getCode('OK')){
                $result['data']['isRec'] = $tmpRes['data']['isRec'];
            }
            //自动登录
            $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
            $this->session->set($this->config->websiteinfo->user_auto_login_username, $username);
            
             //移除session
            //$this->session->remove($this->config->websiteinfo->user_phone_login_sms);
            //$this->session->remove($this->config->websiteinfo->user_phone_login_phone);
            //$this->session->remove($this->config->websiteinfo->user_phone_login_time);
            //新手任务-绑定手机
            $taskMgr = $this->di->get('taskMgr');
            $taskResult = $taskMgr->bindTelephoneTask();
            if ($taskResult['code'] == $this->status->getCode('OK')) {
                $result['data']['firstBindTelephone'] = 1; //首次绑定手机
            } else {
                $result['data']['firstBindTelephone'] = 0; //不是首次绑定手机
            }
            //推荐用户领取礼包
            $userMgr = $this->di->get('userMgr');
            $recResult = $userMgr->getRecommendPackageGift($telephone);
            if ($recResult['code'] == $this->status->getCode('OK')) {
                $result['data']['isRecommend'] = 1;
                $result['data']['reward'] = $recResult['reward'];
            } /*else {
                //注册赠送礼包 add by 2015/12/22
                $giftPacketId = $this->config->phoneRegGiftId;
                $user = UserFactory::getInstance($result['data']['uid']);
                $user->getUserItemsObject()->giveGiftPackage($giftPacketId);
                $configResult = $this->configMgr->getgiftPackageBaseConfig($giftPacketId, 1);
                if ($configResult['code'] == $this->status->getCode('OK')) {
                    $result['data']['reward'] = $configResult['data'];
                }
            }*/
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /**
     * 手机验证码登录 多账号选择 add by 2015/09/25
     */
    public function phoneSmsUsers($telephone,$smsCode,$isMobile=0) {
//        //判断验证码是否正确
//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_phone_login_sms);
//        $phone = $this->session->get($this->config->websiteinfo->user_phone_login_phone);
//        $time = $this->session->get($this->config->websiteinfo->user_phone_login_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if (!$smsCode_right || !$phone) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if ($smsCode_right != $smsCode || !$smsCode) {
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
//        if ($telephone != $phone) {
//            return $this->status->retFromFramework($this->status->getCode('MOBILEPHONE_IS_ERROR'));
//        }
//        
//        //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult = UserReg::checkSmsCaptcha($telephone, $this->config->sms_template->smsLogin, $smsCode);
        if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }

        //查询手机号是否存在
        $sql = "select u.uid,u.userName,u.status,ui.avatar,ui.nickName from \Micro\Models\Users u inner join \Micro\Models\UserInfo ui on u.uid=ui.uid where ui.telephone='{$telephone}' order by u.updateTime desc";
        $query = $this->modelsManager->createQuery($sql);
        $userResult = $query->execute();
        //有账号，返回账号列表给前端
        $return = array();
        if ($userResult->toArray()) {
            foreach ($userResult as $val) {
                $data['uid'] = $val->uid;
                $data['userName'] = $val->userName;
                $data['nickName'] = $val->nickName;
                $data['status'] = $val->status;
                $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath(); //默认头像;
                $return[] = $data;
                unset($data);
            }
            //设置session
            $this->session->set($this->config->websiteinfo->user_phone_sms_users_phone, $telephone);
            $this->session->set($this->config->websiteinfo->user_phone_sms_users_time, time());
            
            //移除session
            //$this->session->remove($this->config->websiteinfo->user_phone_login_sms);
            //$this->session->remove($this->config->websiteinfo->user_phone_login_phone);
            //$this->session->remove($this->config->websiteinfo->user_phone_login_time);
            
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        }
 
        //未注册，则注册
        $username = $this->setRandCode(10); //用户名
        $rand = $this->setRandCode(6);
        $password = md5($rand); //密码
        $nickname = $this->setRandCode(8); //昵称
        $canSetUserName = 1; //可修改用户名
        $canSetPassword = 1; //可设置密码
        $userData = array(
            'accountId' => time(), ///////////////teste///////////////
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
            'status' => 1,
            'telephone' => $telephone,
            'userType' => $this->config->userType->telephone,
            'openId' => '',
            'canSetUserName' => $canSetUserName,
            'canSetPassword' => $canSetPassword,
        );

        $result = UserReg::initUserData($userData);

        if ($result['code'] == $this->status->getCode('OK')) {//注册成功
            //设置session
            $this->session->set($this->config->websiteinfo->user_phone_sms_users_phone, $telephone);
            $this->session->set($this->config->websiteinfo->user_phone_sms_users_time, time());
            //移除session
            $this->session->remove($this->config->websiteinfo->user_phone_login_sms);
            $this->session->remove($this->config->websiteinfo->user_phone_login_phone);
            $this->session->remove($this->config->websiteinfo->user_phone_login_time);
            
            $return[0]['uid']=$result['data']['uid'];
           
            
            //推荐用户领取礼包
            $userMgr = $this->di->get('userMgr');
            $recResult = $userMgr->getRecommendPackageGift($telephone, $result['data']['uid']);
            if ($recResult['code'] == $this->status->getCode('OK')) {
                $return[0]['isRecommend'] = 1;
                $return[0]['reward'] = $recResult['data']['reward'];
            } /*else {
                //app注册 送礼包 edit by 2015/12/22
                $giftPacketId = $this->config->phoneRegGiftId;
                $user = UserFactory::getInstance($result['data']['uid']);
                $user->getUserItemsObject()->giveGiftPackage($giftPacketId);
                $configResult = $this->configMgr->getgiftPackageBaseConfig($giftPacketId, 1);
                if ($configResult['code'] == $this->status->getCode('OK')) {
                    $return[0]['reward'] = $configResult['data'];
                }
            }*/

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /**
     * 手机多账号切换
     */
    public function changeAccount(){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid);
        if ($userInfo == false) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }
        $telephone = $userInfo->telephone;
        if(!$telephone){
            return $this->status->retFromFramework($this->status->getCode('HAS_NOT_BIND_TELEPHONE'));
        }

        // 查询手机号是否存在
        $sql = "select u.uid,u.userName,u.status,ui.avatar,ui.nickName from \Micro\Models\Users u inner join \Micro\Models\UserInfo ui on u.uid=ui.uid where ui.telephone='{$telephone}' order by u.updateTime desc";
        $query = $this->modelsManager->createQuery($sql);
        $userResult = $query->execute();
        // 有账号，返回账号列表给前端
        $return = array();
        if ($userResult->toArray()) {
            foreach ($userResult as $val) {
                if($val->uid == $uid) continue;
                $data['uid'] = $val->uid;
                $data['userName'] = $val->userName;
                $data['nickName'] = $val->nickName;
                $data['status'] = $val->status;
                $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath(); //默认头像;
                $return[] = $data;
                unset($data);
            }
        }
        if(empty($return)){
            return $this->status->retFromFramework($this->status->getCode('NONE_CHANGE_ACCOUNT'));
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }

    /**
     * 多账号登录
     */
    public function changeAccountLogin($uid){
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 判断当前用户是否存在
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        
        // 判断切换的用户是否存在
        $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid);
        if ($userInfo == false) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        // 获取当前用户手机号
        $nowUid = $user->getUid();
        $nowUserInfo = \Micro\Models\UserInfo::findfirst("uid=" . $nowUid);
        if ($nowUserInfo == false) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        // 判断当前用户的手机号和切换的手机号是否相同
        if(!$nowUserInfo->telephone || !$userInfo->telephone || $nowUserInfo->telephone != $userInfo->telephone){
            return $this->status->retFromFramework($this->status->getCode('CHANGE_ACCOUNT_ERROR'));
        }

        // 判断用户是否被禁用
        $users = \Micro\Models\Users::findfirst($uid);
        if ($users->status != 1) {//用户被禁用
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_ACTIVE'));
        }

        //登录
        $result = $this->userAutoLogin($uid);

        if($result['code'] == $this->status->getCode('OK')){
            //设备信息
            $userDevice = $this->session->get($this->config->websiteinfo->mobileauthkey);
            $this->roomModule->getRoomMgrObject()->setDeviceInfoSession($userDevice);

            //写入用户token表，用于下次自动登录用
            $deviceid = isset($userDevice['deviceid']) ? $userDevice['deviceid'] : '';
            $token = $this->setTokenInfo($uid, $deviceid);
            $return['token'] = $token;
            $return['deviceid'] = $deviceid;
            $return['uid'] = $uid;
            $return['isRec'] = $result['data']['isRec'];
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        }

        return $this->status->retFromFramework($result['code'], $result['data']);
    }
    
    
    /**
     * 手机验证码登录  add by 2015/09/25
     */
    public function newPhoneSmsCodeLogin($uid,$isMobile=0) {
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //判断session
        $telephone = $this->session->get($this->config->websiteinfo->user_phone_sms_users_phone);
        $time = $this->session->get($this->config->websiteinfo->user_phone_sms_users_time);
        if (time() - $time > 600) {
            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
        }
        if (!$telephone) {
            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
        }
        $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid . " and telephone='" . $telephone . "'");
        if ($userInfo == false) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }
        $users = \Micro\Models\Users::findfirst($uid);
        if ($users->status != 1) {//用户被禁用
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_ACTIVE'));
        }
        //登录
        $tmpRes = $this->userAutoLogin($uid);
        //移除session
        $this->session->remove($this->config->websiteinfo->user_phone_sms_users_phone);
        $this->session->remove($this->config->websiteinfo->user_phone_sms_users_time);
        //新手任务-绑定手机
        $taskMgr = $this->di->get('taskMgr');
        $taskResult = $taskMgr->bindTelephoneTask();
        $return = array();
        if ($taskResult['code'] == $this->status->getCode('OK')) {
            $return['firstBindTelephone'] = 1; //首次绑定手机
        } else {
            $return['firstBindTelephone'] = 0; //不是首次绑定手机
        }
        
        $return['uid'] = $uid;
        
        if ($isMobile) {
            //设备信息
            $userDevice = $this->session->get($this->config->websiteinfo->mobileauthkey);
            $this->roomModule->getRoomMgrObject()->setDeviceInfoSession($userDevice);

            //写入用户token表，用于下次自动登录用
            $deviceid = isset($userDevice['deviceid']) ? $userDevice['deviceid'] : '';
            $token = $this->setTokenInfo($uid, $deviceid);
            $return['token'] = $token;
            $return['deviceid'] = $deviceid;
            if($tmpRes['code'] == $this->status->getCode('OK')){
                $return['isRec'] = $tmpRes['data']['isRec'];
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }

    /*
     * 找回密保问题，获取验证码
     * */
    public function unsetQuestionSendCode(){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userData = $user->getUserInfoObject()->getUserInfo();
        if (empty($userData['telephone'])) {
            return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
        }

        $result = UserReg::sendSmsVerify($userData['telephone'], $this->config->sms_template->setQuestion);

        if ($result['code'] == $this->status->getCode('OK')) {
            //设置session
            $this->session->set($this->config->websiteinfo->user_question_phone_sms, $result['data']['smsCode']);
            $this->session->set($this->config->websiteinfo->user_question_phone, $userData['telephone']);
            $this->session->set($this->config->websiteinfo->user_question_phone_time, time());
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }
    
    /*
     * 找回密保问题，手机验证码验证
     * */
    public function unsetQuestionByPhoneSms($smsCode) {
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userData = $user->getUserInfoObject()->getUserInfo();
        $telephone=$userData['telephone'];
        if (empty($telephone)) {
            return $this->status->retFromFramework($this->status->getCode('HAS_NOT_BIND_TELEPHONE'));
        }

//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_question_phone_sms);
//        $telephone = $this->session->get($this->config->websiteinfo->user_question_phone);
//
//        $time = $this->session->get($this->config->websiteinfo->user_question_phone_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//
//        if (!$smsCode_right || !$smsCode || $smsCode_right != $smsCode) {
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
//
//        if (empty($telephone)) {
//            return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
//        }
        
         //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult = UserReg::checkSmsCaptcha($telephone, $this->config->sms_template->setQuestion, $smsCode);
        if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }
        //设置密保session
        $this->session->set($this->config->websiteinfo->unset_question,1);

        //移除session
        //$this->session->remove($this->config->websiteinfo->user_settle_phone_sms);
        //$this->session->remove($this->config->websiteinfo->user_settle_phone);
        //$this->session->remove($this->config->websiteinfo->user_settle_phone_time);

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //生成数据字母随机数
    public function setRandCode($num = 8) {
        return $this->makeCode($num, 'ns');
    }

    public function makeCode($num = 10, $pre = '') {
        $re = '';
        $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        while (strlen($re) < $num) {
            $re .= $s[rand(0, strlen($s) - 1)]; //从$s中随机产生一个字符 
        }
        return $pre . $re;
    }
    
     /**
     * app端手机验证码注册 add by 2015/09/28
     */
    public function phoneSmsCodeReg($telephone, $smsCode, $recUid = 0) {
        //判断验证码是否正确
//        $smsCode_right = $this->session->get($this->config->websiteinfo->user_reg_phone_sms);
//        $phone = $this->session->get($this->config->websiteinfo->user_reg_phone);
//        $time = $this->session->get($this->config->websiteinfo->user_reg_phone_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if (!$smsCode_right || !$phone) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if ($smsCode_right != $smsCode || !$smsCode) {
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
//        if ($telephone != $phone) {
//            return $this->status->retFromFramework($this->status->getCode('MOBILEPHONE_IS_ERROR'));
//        }
        
        //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult = UserReg::checkSmsCaptcha($telephone, $this->config->sms_template->register, $smsCode);
        if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }
         

        //检测手机号是否已绑定过
        if (!$this->checkPhone($telephone)) {
            return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_BIND'));
        }

        //则注册并登录
        $username = $this->setRandCode(10); //用户名
        $rand = $this->setRandCode(6);
        $password = md5($rand); //密码
        $nickname = $this->setRandCode(8); //昵称
        $canSetUserName = 1; //可修改用户名
        $canSetPassword = 1; //可设置密码
        $userData = array(
            'accountId' => time(), ///////////////teste///////////////
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
            'status' => 1,
            'telephone' => $telephone,
            'userType' => $this->config->userType->telephone,
            'openId' => '',
            'canSetUserName' => $canSetUserName,
            'canSetPassword' => $canSetPassword,
        );

        $result = UserReg::initUserData($userData);

        $deviceid = '';
        $token = '';
        if ($result['code'] == $this->status->getCode('OK')) {//注册成功
            $uid = $result['data']['uid'];
            $this->userAutoLogin($uid);

            //设备信息
            $userDevice = $this->session->get($this->config->websiteinfo->mobileauthkey);
            $this->roomModule->getRoomMgrObject()->setDeviceInfoSession($userDevice);

            //写入用户token表，用于下次自动登录用
            $deviceid = isset($userDevice['deviceid']) ? $userDevice['deviceid'] : '';
            $token = $this->setTokenInfo($uid, $deviceid);

            //自动登录
            $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
            $this->session->set($this->config->websiteinfo->user_auto_login_username, $username);

            //移除session
            $this->session->remove($this->config->websiteinfo->user_reg_phone_sms);
            $this->session->remove($this->config->websiteinfo->user_reg_phone);
            $this->session->remove($this->config->websiteinfo->user_reg_phone_time);
            //新手任务-绑定手机
            $taskMgr = $this->di->get('taskMgr');
            $taskResult = $taskMgr->bindTelephoneTask();
            if ($taskResult['code'] == $this->status->getCode('OK')) {
                $result['data']['firstBindTelephone'] = 1; //首次绑定手机
            } else {
                $result['data']['firstBindTelephone'] = 0; //不是首次绑定手机
            }
            //推荐用户领取礼包
            $userMgr = $this->di->get('userMgr');
            if ($recUid) {//推荐人
                $recResult = $userMgr->regRecommend($recUid);
            } else {//有用手机号领取过奖励
                $recResult = $userMgr->getRecommendPackageGift($telephone);
            }
            
            if ($recResult['code'] == $this->status->getCode('OK')) {//领取推荐礼包成功
                $result['data']['isRecommend'] = 1;
                $result['data']['reward'] = $recResult['data']['reward'];
            } else {//普通用户
                //app注册 送礼包 edit by 2015/12/22
                $giftPacketId = $this->config->phoneRegGiftId;
                $user = UserFactory::getInstance($result['data']['uid']);
                $user->getUserItemsObject()->giveGiftPackage($giftPacketId);
                $configResult = $this->configMgr->getgiftPackageBaseConfig($giftPacketId, 1);
                if ($configResult['code'] == $this->status->getCode('OK')) {
                    $result['data']['reward'] = $configResult['data'];
                }
            }
           
        }
        $result['data']['token'] = $token;
        $result['data']['deviceid'] = $deviceid;
        return $this->status->retFromFramework($result['code'], $result['data']);
    }
    
     /*
      * app 第三方登录 绑定手机，获取验证码 add by 2015/09/29
      * */
    public function thirdLoginBindPhoneCode($telephone){
        $postData['telephone'] = $telephone;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }


        //改成一个手机号允许绑定多个账号 edit by 2015/09/28
        $phonecount = UserInfo::count("telephone='" . $telephone . "'");
        if ($phonecount >= $this->config->bindPhoneLimit) {
            return $this->status->retFromFramework($this->status->getCode('BIND_PHONE_LIMIT'));
        }

        //改用bmob的短信通道 edit by 2015/07/21
        $result = UserReg::sendSmsVerify($telephone, $this->config->sms_template->bindPhone);

//        if ($result['code'] == $this->status->getCode('OK')) {
//            //设置session
//            $this->session->set($this->config->websiteinfo->third_login_bind_phone_sms, $result['data']['smsCode']);
//            $this->session->set($this->config->websiteinfo->third_login_bind_phone, $telephone);
//            $this->session->set($this->config->websiteinfo->third_login_bind_phone_time, time());
//        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    //app端 第三方登录时，绑定手机后登录 add by 2015/09/28
    public function thirdLoignBindPhone($telephone, $smsCode) {
        //判断验证码是否正确
//        $smsCode_right = $this->session->get($this->config->websiteinfo->third_login_bind_phone_sms);
//        $phone = $this->session->get($this->config->websiteinfo->third_login_bind_phone);
//        $time = $this->session->get($this->config->websiteinfo->third_login_bind_phone_time);
//        if (time() - $time > 600) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if (!$smsCode_right || !$phone) {
//            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//        }
//        if ($smsCode_right != $smsCode || !$smsCode) {
//            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//        }
//        if ($telephone != $phone) {
//            return $this->status->retFromFramework($this->status->getCode('MOBILEPHONE_IS_ERROR'));
//        }

         //验证验证码是否输入正确
        //改为从数据库验证 edit by 2015/10/20
        $smsCheckResult = UserReg::checkSmsCaptcha($telephone, $this->config->sms_template->bindPhone, $smsCode);
        if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
        }
        
        //判断第三方登录的标识是否还在
        $uid = $this->session->get($this->config->websiteinfo->user_third_login_uid);
        if (!$uid) {
            return $this->status->retFromFramework($this->status->getCode('LOGIN_OVERTIME'));
        }


        //改成一个手机号允许绑定多个账号 edit by 2015/09/28
        $phonecount = UserInfo::count("telephone='" . $telephone . "'");
        if ($phonecount >= $this->config->bindPhoneLimit) {
            return $this->status->retFromFramework($this->status->getCode('BIND_PHONE_LIMIT'));
        }

        try {
            //绑定手机
            $user = \Micro\Models\UserInfo::findFirst($uid);
            if ($user == false) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
            $user->telephone = $telephone;
            $user->save();
            $firstBindTelephone = 0;
            $isRecommend = 0;

            //新手任务-绑定手机
            $taskMgr = $this->di->get('taskMgr');
            $taskResult = $taskMgr->bindTelephoneTask();
            if ($taskResult['code'] == $this->status->getCode('OK')) {
                $firstBindTelephone = 1; //首次绑定手机
            }
            //是否有推荐礼包
            $userMgr = $this->di->get('userMgr');
            $recResult = $userMgr->bindPhoneRecommend($telephone);
            if ($recResult['code'] == $this->status->getCode('OK')) {
                $isRecommend = 1;
            }

            $return['isRecommend'] = $isRecommend;
            $return['firstBindTelephone'] = $firstBindTelephone;

            // 走正常登录流程
            $this->userAutoLogin($uid);
            //设备信息
            $userDevice = $this->session->get($this->config->websiteinfo->mobileauthkey);
            $this->roomModule->getRoomMgrObject()->setDeviceInfoSession($userDevice);
            
            //写入用户token表，用于下次自动登录用
            $deviceid = isset($userDevice['deviceid']) ? $userDevice['deviceid'] : '';
            $token=$this->setTokenInfo($uid, $deviceid);
 
            //移除session
            //$this->session->remove($this->config->websiteinfo->third_login_bind_phone_sms);
            //$this->session->remove($this->config->websiteinfo->third_login_bind_phone);
            //$this->session->remove($this->config->websiteinfo->third_login_bind_phone_time);

            $return['uid'] = $uid;
            $return['telephone'] = substr_replace($telephone, '****', 3, 4); //手机号
            
            $return['token'] = $token;
            $return['deviceid'] = $deviceid;

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //app 登录或注册成功后 更新token信息 add by 2015/09/29
    private function setTokenInfo($uid, $deviceid = '') {
        $info = \Micro\Models\MobileToken::findfirst("uid=" . $uid);
        $token = md5(md5($this->setRandCode())); //生成随机32位字符串
        $expireTime = time() + $this->config->mobileTokenTime;
        if ($info) {//编辑
            $info->token = $token;
            $info->device = $deviceid;
            $info->expireTime = $expireTime;
            $info->save();
            return $token;
        }
        //新增一条数据
        $new = new \Micro\Models\MobileToken();
        $new->uid = $uid;
        $new->token = $token;
        $new->device = $deviceid;
        $new->expireTime = $expireTime;
        $new->save();
        return $token;
    }

    //app端 使用token信息自动登录 add by 2015/09/29
    public function loginByToken($token, $device) {
        try {
            $info = \Micro\Models\MobileToken::findfirst("token='" . $token . "' and device='" . $device . "'");
            if ($info == false) {//数据不存在
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($info->expireTime < time()) {//已失效
                return $this->status->retFromFramework($this->status->getCode('USER_EXPIRE_VALIDITY'));
            }
            $uid = $info->uid;
            // 走正常登录流程
            $this->userAutoLogin($uid);
            $return['uid']=$uid;
            return $this->status->retFromFramework($this->status->getCode('OK'),$return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //拒绝填写推荐码
    public function refuseRec(){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $sql = 'insert ignore into pre_rec_refuse_log (`uid`) values (' . $user->getUid() . ') ';
            $connection = $this->di->get('db');
            $connection->execute($sql);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    //填写推荐码
    public function fillRec($recUid = 0){
        $user = $this->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $isValid = $this->validator->validate(array('uid'=>$recUid));
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $uid = $user->getUid();
            $beRecRes = \Micro\Models\RecommendLog::findFirst('beRecUid = ' . $uid);
            if($beRecRes){
                return $this->status->retFromFramework($this->status->getCode('HAS_BEEN_REC'));
            }
            $recUidRes = \Micro\Models\Recommend::findFirst('uid = ' . $recUid);
            if(!$recUidRes){
                return $this->status->retFromFramework($this->status->getCode('INVALID_REC_CODE'));
            }

            $t = time();
            $userData = $user->getUserInfoObject()->getData();
            $sqlInsert = "insert into pre_recommend_log (`beRecUid`,`recUid`,`createTime`,`telephone`) values ({$uid},{$recUid},{$t},'{$userData['telephone']}')";
            $connection = $this->di->get('db');
            $connection->execute($sqlInsert);

            $return = array();
            if($userData['richerLevel'] == 0 && ($userData['userType'] == $this->config->userType->telephone || $userData['userType'] == $this->config->userType->weixin)){
                $giftPackageId = $this->config->recommendGiftId;
                $user->getUserItemsObject()->giveGiftPackage($giftPackageId);
                $configResult = $this->configMgr->getgiftPackageBaseConfig($giftPackageId, 1);
                if ($configResult['code'] == $this->status->getCode('OK')) {
                    $return['reward'] = $configResult['data'];
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

}

