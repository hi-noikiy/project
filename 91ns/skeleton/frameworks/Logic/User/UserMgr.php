<?php

namespace Micro\Frameworks\Logic\User;


use Micro\Frameworks\Logic\User\UserAuth\UserReg;
use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Activation\Activator;
use Micro\Models\Users;
use Micro\Models\UserInfo;
use Micro\Models\ConsumeLog;
use Micro\Models\UserProfiles;
use Micro\Models\CashLog;
use Micro\Models\Order;
use Micro\Models\GiftLog;
use Micro\Models\GiftConfigs;
use Micro\Frameworks\Logic\Investigator\InvAnchor;
use Micro\Frameworks\Logic\Investigator\InvAgent;
use Micro\Frameworks\Logic\Sign\SignData;
class UserMgr {

    protected $di;
    protected $session;
    protected $config;
    protected $status;
    protected $validator;
    protected $comm;
    protected $userAuth;
    protected $pathGenerator;
    protected $request;
    protected $lbs;
    protected $pushMgr;
    protected $roomModule;
    protected $signData;
    protected $logger;
    protected $db;
    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->session = $this->di->get('session');
        $this->db = $this->di->get('db');
        $this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->validator = $this->di->get('validator');
        $this->request = $this->di->get('request');
        $this->comm = $this->di->get('comm');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->userAuth = $this->di->get('userAuth');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->configMgr=$this->di->get('configMgr');
        $this->lbs=$this->di->get('lbs');
        $this->pushMgr = $this->di->get('pushMgr');
        $this->roomModule=$this->di->get('roomModule');
        $this->roomModule=$this->di->get('roomModule');
        $this->logger=$this->di->get('logger');
        $this->signData = new SignData();
    }

    /**
     * 获取用户对象
     */
    /* static public function getUser() {
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
      } */

    // 注：目前只在后台使用
    public function getUserList($skip, $limit) {
        try {
            $count = Users::count();
            $cond = '';
            if ($skip >= 0) {
                $cond = "OFFSET ".$skip;
            }
            if ($limit >= 0) {
                $cond = "LIMIT ".$limit." ".$cond;
            }
//            $phql = "SELECT a.*, b.*, c.* FROM Micro\Models\Users a, Micro\Models\UserInfo b, Micro\Models\UserProfiles c WHERE".
//                    " a.uid = b.uid AND b.uid = c.uid";
           $phql = "SELECT a.*, b.* ,c.* FROM Micro\Models\Users a LEFT JOIN Micro\Models\UserInfo b ON a.uid = b.uid LEFT JOIN Micro\Models\UserProfiles c ON b.uid = c.uid ".$cond;
                    
            $query = $this->modelsManager->createQuery($phql);
            $userLists = $query->execute();
            $dataList = array();
            if ($userLists->valid()) {
                foreach ($userLists as $userData) {
                    $data['uid'] = $userData->a->uid;
                    $data['userName'] = $userData->a->userName;
                    $data['nickName'] = $userData->b->nickName;
                    $data['createTime'] = $userData->a->createTime;
                    $userType = $userData->a->userType;
                    if ($userType == 0) {
                        $data['userType'] = "网站";
                    }
                    else if ($userType == 1) {
                        $data['userType'] = "QQ登录";
                    }
                    else if ($userType == 2) {
                        $data['userType'] = "新浪微博";
                    }
                    $data['status'] = $userData->a->status;
                    $data['internalType'] = $userData->a->internalType; //贵宾号
                    // $data['tuoType'] = $userData->a->internalType==2?2:0; //账托号
                    $data['isChatRecord'] = $userData->a->isChatRecord;
                    $data['manageType'] = $userData->a->manageType;//是否为超级管理员

                    array_push($dataList, $data);
                }
            }
            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得某用户的密保问题
     */
    public function getsecurelist(){
        if ($this->request->isPost()) {
            $securityCode = $this->request->getPost('securityCode');
            $captchaId = $this->request->getPost('captchaId');

            if($this->baseCode->checkSecurityCode($captchaId, $securityCode)){
                $username = $this->request->getPost('username');
                if(!empty($username)){
                    // 判断用户是否存在
                    $usernameResult = $this->checkUserExist($username);
                    if ($usernameResult['code'] != $this->status->getCode('OK')) {
                        return $this->status->retFromFramework($this->status->getCode('USER_NAME_EXISTS'));
                    }
                    $result = $this->getSecure($username);

                    if ($result['code'] == $this->status->getCode('OK')) {
                        $this->session->set($this->config->websiteinfo->securityuser, $username);
                        if(!empty($result['data']['telephone'])){
                            $result['data']['telephone'] = substr_replace($result['data']['telephone'], '****', 3, 4);
                        }

                        if(!empty($result['data']['email'])){
                            $result['data']['email'] = substr_replace($result['data']['email'], '****', 4, strrpos($result['data']['email'], '@') - 4);
                        }

                        $questions = $this->configMgr->getQuestionsConfigs();
                        $data = array('thisquestion' => $result['data'], 'questions' => $questions);
                        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
                    }

                    return $this->status->retFromFramework($result['code'], $result['data']);
                }
            }

            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
        }

        $this->proxyError();
    }

    // 后台使用
    public function setUserInternalType($uid, $internalType) {
        try {
            $user = Users::findFirst("uid=" . $uid);
            if (!$user) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            $user->internalType = $internalType;
            $user->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 后台使用
    // 设置用户是否记录聊天信息
    public function setUserChatRecord($uid, $isChatRecord) {
        try {
            $user = Users::findFirst("uid=" . $uid);
            if (!$user) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            $user->isChatRecord = $isChatRecord;
            $user->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //开关-座驾
    public function updateCarStatus($carId, $status){
        $postData['itemid'] = $carId;
        $postData['status'] = $status;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        //登录验证
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $result = $user->getUserItemsObject()->updateCarStatus($carId, $status);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->retFromFramework($this->status->getCode('OK'));
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function activeUser($tokenSec, $token) {
        $key = $this->config->active_mailer->active_key;
        $t = md5($token . $key);
        if ($t != $tokenSec) {
            return $this->status->retFromFramework($this->status->getCode('USER_EXPIRE_VALIDITY'));
        }

        $tokenInfo = json_decode(base64_decode($token), TRUE);
        if (time() - $tokenInfo['time'] > 600) {
            return $this->status->retFromFramework($this->status->getCode('USER_EXPIRE_VALIDITY'));
        }

        $username = $tokenInfo['user'];
        $user = Users::findFirst("userName='" . $this->validator->qs($username) . "'");
        $user->status = 1;
        $data['accountId'] = $user->accountId;
        $user->save();

        //记录--用户验证完毕
        $this->session->set($this->config->websiteinfo->s_user_be_active, TRUE);
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    public function checkUserName($username) {
        $postData['username'] = $username;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $user = Users::findFirst("userName='" . $this->validator->qs($username) . "'");
        if ($user) {
            return $this->status->retFromFramework($this->status->getCode('USER_NAME_EXISTS'));
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * 获取用户安全类别
     */
    public function getSecure($userName, $type = 0) {
        $result = $this->getUidByUserName($userName);
        if ($result['code'] != $this->status->getCode('OK')) {
            return $result;
        }

        $uid = $result['data']['uid'];
        $user = UserFactory::getInstance($uid);
        $userSecurity = $user->getUserSecurityObject();
        $result = $userSecurity->getSecures();
        if($type > 0){
            switch($type){
                case 1:
                    if(empty($result['data']['issues'])){
                        // 未设置密保
                        return $this->status->retFromFramework($this->status->getCode('NOT_SET_SECURE'), $result['data']);
                    }

                    break;
                case 2:
                    if(empty($result['data']['telephone'])){
                        // 未绑定手机
                        return $this->status->retFromFramework($this->status->getCode('NOT_BIND_PHONE'), $result['data']);
                    }

                    break;
            }
        }

        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /*
      * 找回密码，获取验证码
      * */
    public function getPasswordSendCode($userName, $phone = '',$checkCaptcha=0){

        $postData['username'] = $userName;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        
        $baseCode = $this->di->get('baseCode');
        if ($checkCaptcha && !$baseCode->checkCaptcha()) {//验证验证码是否正确
            //验证码错误
            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
        }
                
        $result = $this->getUidByUserName($userName);
        if ($result['code'] != $this->status->getCode('OK')) {
            return $result;
        }

        $uid = $result['data']['uid'];
        $user = UserFactory::getInstance($uid);
        $userSecurity = $user->getUserSecurityObject();
        $sec = $userSecurity->getSecures();
        if(!isset($sec['data']['telephone'])){
            return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
        }

        //$result = UserReg::sendSmsVerify($phone ? $phone : $sec['data']['telephone'], $this->config->webType[$this->config->channelType]['smsType']->getPassword);
        
        //改用bmob的短信通道 edit by 2015/07/21
        $result = UserReg::sendSmsVerify($phone ? $phone : $sec['data']['telephone'], $this->config->sms_template->getPassword);
        
        if($result['code'] == $this->status->getCode('OK')){
            //设置session
            $this->session->set($this->config->websiteinfo->user_get_password_key, $result['data']['smsCode']);
            $this->session->set($this->config->websiteinfo->user_get_password_time, time());
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($result['code'], $result['data']);
    }
    /*
     * 手机忘记密码验证码：验证
     * */

    public function checkSecSMSCode($code) {
        $rightCode = $this->session->get($this->config->websiteinfo->user_get_password_key);
        $outTime = $this->session->get($this->config->websiteinfo->user_get_password_time);
        if(time() - $outTime > 180){
            return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
        }

        if ($rightCode == $code) {
            $this->userWillResetPwd();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
    }

    /*
     * 邮件验证码：发送
     * */

    public function sendEmailVerifyCode($email) {
        $postData['email'] = $email;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $activator = new Activator();
        $emailCode = $activator->genSMSCode();
        $this->session->set($this->config->websiteinfo->emailcodekey, $emailCode);
        $activator->sendCodeMail($email, $emailCode);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * 邮件验证码：验证
     * */

    public function checkEmailVerifyCode($code) {
        $code_right = $this->session->get($this->config->websiteinfo->emailcodekey);
        $this->session->remove($this->config->websiteinfo->emailcodekey);
        if ($code_right == $code) {
            return TRUE;
        }
        return FALSE;
    }

    /*
    * 找回密码第三步
    */
    public function userWillResetPwd(){
        $this->session->set($this->config->websiteinfo->user_get_password_reset, TRUE);
    }

    /*
    * 找回密码第三步(是否满足)
    */
    public function checkUseResetPwd(){
        return $this->session->get($this->config->websiteinfo->user_get_password_reset);
    }

    /*
     * 重置用户密码
     * */

    public function userResetPwd($newPwd) {
        $postData['newpassword'] = $newPwd;
        $isValid = $this->validator->validate($postData);

        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $userName = $this->session->get($this->config->websiteinfo->securityuser);
        //修改
//        $result = $this->comm->userResetPwd($userName, $newPwd);
        $user = Users::findFirst("userName='{$userName}'");
        if(!$user){
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        $md5newPass = md5($user->key . $newPwd);
        $user->password = $md5newPass;
        $result = $user->save();
        if ($result === FALSE) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }

//        if ($result['code'] != 0) {
//            return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
//        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * GET: uid
     * BY: username
     * */

    public function getUidByUserName($userName) {
        /*$postData['username'] = $userName;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }*/

        try {
//            $parameters = array(
//                "userName" => $userName,
//            );
//            $userInfo = Users::findFirst(array(
//                        "userName = :userName:",
//                        "bind" => $parameters,
//                        "columns" => "uid"
//            ));
//
//            if ($userInfo) {
//                if (!empty($userInfo->uid)) {
//                    //return $userInfo->uid;
//                    $data['uid'] = $userInfo->uid;
//                    return $this->status->retFromFramework($this->status->getCode('OK'), $data);
//                }
//            }
            $sql = "select * from pre_users where binary userName  = '{$userName}'";
            $connection = $this->di->get('db');
            $userInfo = $connection->fetchOne($sql);
            if($userInfo){
                $data['uid'] = $userInfo['uid'];
                return $this->status->retFromFramework($this->status->getCode('OK'), $data);
            }

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

        return null;
    }

    /*
     * GET：uid
     * BY: accountId
     * */

    public function getUidByAccountId($accountId) {
        $postData['accountid'] = $accountId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $parameters = array(
                "accountId" => $accountId,
            );
            $userInfo = Users::findFirst(array(
                        "accountId = :accountId:",
                        "bind" => $parameters,
                        "columns" => "uid"
            ));

            if ($userInfo) {
                if (!empty($userInfo->uid)) {
                    return $userInfo->uid;
                }
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * GET: email
     * BY: userName
     * */

    public function getEmailByUserName($userName) {
        $postData['username'] = $userName;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $parameters = array(
                "userName" => $userName,
            );
            $user = Users::findFirst(array(
                        "userName = :userName:",
                        "bind" => $parameters,
                        "columns" => "uid"
            ));

            if ($user) {
                if (!empty($user->uid)) {
                    $parameters = array(
                        "uid" => $user->uid,
                    );
                    $userInfo = UserInfo::findFirst(array(
                                "uid = :uid:",
                                "bind" => $parameters,
                                "columns" => "email"
                    ));
                    if ($userInfo) {
                        if (!empty($userInfo->email)) {
                            return $userInfo->email;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 验证用户昵称是否存在
     */
    public function checkNickNameExist($nickName) {
        $postData['nickname'] = $nickName;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
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

    public function getUidByNickname($nickName = ''){
        $data = array();
//        $postData['nickname'] = $nickName;
//        $isValid = $this->validator->validate($postData);
//        if (!$isValid) {
//            $errorMsg = $this->validator->getLastError();
//            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
//        }

        try {
            if(strlen($nickName) > 0){
                $list = UserInfo::find("nickName like '%{$nickName}%'");
                if($result = $list->toArray()){
                    foreach($result as $val){
                        $data[$val['uid']] = $val['nickName'];
                    }
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 验证用户名是否存在
     */
    public function checkUserExist($userName) {
        $postData['username'] = $userName;//用户名
        //用户名格式验证
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //豆子账号
        if ($this->config->channelType == 2) {
            return $this->checkUserExistOfDouzi($userName);
        }
        //普通账号
        try {
//            $count = Users::count("userName = '{$userName}'");
//            if ($count == 0) {
//                return $this->status->retFromFramework($this->status->getCode('OK'), FALSE);
//            } else {
//                return $this->status->retFromFramework($this->status->getCode('OK'), TRUE);
//            }
            //查询用户名是否存在,区分大小写
            $sql = "select uid from pre_users where binary userName  = '{$userName}' limit 1";
            $connection = $this->di->get('db');
            $result = $connection->fetchOne($sql);
            if ($result) {//用户名已存在
                return $this->status->retFromFramework($this->status->getCode('USER_NAME_EXISTS'));
            }
            //用户名不存在
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //查询豆子账号是否存在
    public function checkUserExistOfDouzi($userName) {
        try {
            $result = $this->di->get('oauth')->createOAuth('douzi')->checkUserExist($userName);
            if ($result) {//用户名已存在
                return $this->status->retFromFramework($this->status->getCode('USER_NAME_EXISTS'));
            }
            //用户名不存在
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DOUZI_ERR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 粉丝关注操作接口
    //
    ////////////////////////////////////////////////////////////////////////

    public function addFollowTest() {
        //注意uid和accountId的使用方式

        $user = UserFactory::getInstance(1);
        $time = time();
        //$result = $user->getUserFoucusObject()->addFollow(1,"20", 3, $time);          //continue ...
        //$result = $user->getUserFoucusObject()->delFollow(1, "20");                   //continue ...
        //$result = $user->getUserFoucusObject()->getFollowList(1);                     //continue ...
        //$result = $user->getUserFoucusObject()->getFollowListEx(1, 1, 0, 10);         //continue ...
        //$result = $user->getUserFoucusObject()->getFollowCount(1);                    //OK
        //$result = $user->getUserFoucusObject()->isFollow(1, "23");                    //OK
        //$result = $user->getUserFoucusObject()->addOwnFollow("1", "5", 3, $time);     //OK
        //$result = $user->getUserFoucusObject()->delOwnFollow("1","2");                //OK
        //$result = $user->getUserFoucusObject()->isOwnFollow("1","2");                 //OK
        //$result = $user->getUserFoucusObject()->getOwnFollowCount("1");               //OK
        //$result = $user->getUserFoucusObject()->getOwnFollowList("1");                //continue ...
        //$result = $user->getUserFoucusObject()->getOwnFollowListEx("1");              //continue ...
        //$result = $user->getUserFoucusObject()->updateOwnFollowFocus("1", "5", 0);            //continue ...
        //$result = $user->getUserFoucusObject()->updateOwnFollowEachFollow("1", "5", 1);       //continue ...
        //$result = $user->getUserFoucusObject()->updateOwnFollowUserData("1","5",'userddd');   //continue ...
        //$result = $user->getUserFoucusObject()->getFollowCountByTime(1, $time);
        //$beginTime = strtotime('-30 days');
        $beginTime = strtotime('-1 years');
        $endTime = time();
        $result = $user->getUserFoucusObject()->getAllFansList($beginTime, $endTime);
        var_dump($result);
        die;
    }

    /**
     * 添加关注接口
     * @param targetId 当前uid想要关注的用户uid(成为targetId的粉丝)
     */
    public function addFollow($targetId, $roomId) {
        $postData['uid'] = $targetId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        try {
            $result = $user->getUserFoucusObject()->addFollow($targetId);
            if ($result['code'] == $this->status->getCode('OK')) {
                 if (!empty($roomId)) {
                    $roomId = intval($roomId);
                    //获取粉丝数，粉丝等级，进行广播
                    $targetUser = UserFactory::getInstance($targetId);
                    $fansCount = $targetUser->getUserFoucusObject()->getFansCount();
                    $phql = "SELECT name,level,lower,higher FROM \Micro\Models\FansConfigs WHERE higher >= " . $fansCount . " AND lower <= " . $fansCount . " LIMIT 1";
                    $query = $this->modelsManager->createQuery($phql);
                    $fansConfigs = $query->execute();
                    $fansConfigs = $fansConfigs->toArray();
                    if (!empty($fansConfigs)) {
                        foreach ($fansConfigs as $configData) {
                            $name = $configData['name'];
                            $level = $configData['level'];
                            $lower = $configData['lower'];
                            $higher = $configData['higher'];

                            $broadData['uid'] = $user->getUid();
                            $broadData['fansCount'] = $fansCount;
                            $broadData['lower'] = $lower;
                            $broadData['higher'] = $higher;
                            $broadData['level'] = $level;

                            $broadData['fansExp'] = $result['data']['fansExp'];
                            $broadData['fansLevel'] = $result['data']['fansLevel'];
                            $broadData['fansNextExp'] = $result['data']['fansNextExp'];
                            $broadData['fansNextNeedExp'] = $result['data']['fansNextNeedExp'];

                            $ArraySubData['controltype'] = "follow";
                            $ArraySubData['data'] = $broadData;
                            $this->comm->roomBroadcast($roomId, $ArraySubData);
                            break;
                        }
                    }
                }
            }

            // 获得关注总数
            $res = $this->getFocusCount($uid);
            if($res['code'] == $this->status->getCode('OK')){
                $data = $res['data'];
                $fansCount = $data['count'];
            }else{
                $fansCount = 0;
            }

            $result['data']['fansCount'] = $fansCount;
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 删除关注接口
     * @param fid 想删除关注的用户uid
     * @param roomId 在房间中的操作,需要广播
     */
    public function delFollow($targetId, $roomId) {
        $postData['uid'] = $targetId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $result = $user->getUserFoucusObject()->delFollow($targetId);
            if ($result['code'] == $this->status->getCode('OK')) {
                if (!empty($roomId)) {
                    $roomId = intval($roomId);
                    //获取粉丝数，粉丝等级，进行广播
                    $targetUser = UserFactory::getInstance($targetId);
                    $fansCount = $targetUser->getUserFoucusObject()->getFansCount();

                    $phql = "SELECT name,level,lower,higher FROM \Micro\Models\FansConfigs WHERE higher >= " . $fansCount . " AND lower <= " . $fansCount . " LIMIT 1";
                    $query = $this->modelsManager->createQuery($phql);
                    $fansConfigs = $query->execute();
                    $fansConfigs = $fansConfigs->toArray();
                    if (!empty($fansConfigs)) {
                        foreach ($fansConfigs as $configData) {
                            $name = $configData['name'];
                            $level = $configData['level'];
                            $lower = $configData['lower'];
                            $higher = $configData['higher'];

                            $broadData['uid'] = $user->getUid();
                            $broadData['fansCount'] = $fansCount;
                            $broadData['lower'] = $lower;
                            $broadData['higher'] = $higher;
                            $broadData['level'] = $level;

                            $broadData['fansExp'] = $result['data']['fansExp'];
                            $broadData['fansLevel'] = $result['data']['fansLevel'];
                            $broadData['fansNextExp'] = $result['data']['fansNextExp'];
                            $broadData['fansNextNeedExp'] = $result['data']['fansNextNeedExp'];

                            $ArraySubData['controltype'] = "follow";
                            $ArraySubData['data'] = $broadData;
                            $this->comm->roomBroadcast($roomId, $ArraySubData);
                            break;
                        }
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 批量删除关注的人
     */
    public function delMultipleFollow($targetIdList) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        if (empty($targetIdList)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $delArr = array();
        if (strpos($targetIdList, ',') != FALSE) {
            $delArr = explode(',', $targetIdList);
            //$delArr = array_map('intval', $delArr);
        } else {
            array_push($delArr, $targetIdList);
        }

        try {
            //return $user->getUserFoucusObject()->delFollow($targetId);
            foreach ($delArr as $targetId) {
                if($targetId){
                    $user->getUserFoucusObject()->delFollow($targetId);
                }
            }

            // 获得关注总数
            $res = $this->getFocusCount($uid);
            if($res['code'] == $this->status->getCode('OK')){
                $data = $res['data'];
                $fansCount = $data['count'];
            }else{
                $fansCount = 0;
            }

            $result['fansCount'] = $fansCount;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取粉丝数量
     */
    public function getFansCount($uid = 0) {
        if($uid){
            $user = UserFactory::getInstance($uid);
        }else{
            // 用户必须登录
            $user = $this->userAuth->getUser();
        }

        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $totalNum = $user->getUserFoucusObject()->getFansCount();
            $data['totalNum'] = $totalNum;

            $timeline = strtotime('-30 days');
            $nearNum = $user->getUserFoucusObject()->getFansCountByTime($timeline);
            $data['nearNum'] = $nearNum;
            $phql = "SELECT name,level,lower,higher FROM \Micro\Models\FansConfigs WHERE higher > " . $totalNum . " AND lower <= " . $totalNum . " LIMIT 1";
            $query = $this->modelsManager->createQuery($phql);
            $fansConfigs = $query->execute();
            $fansConfigs = $fansConfigs->toArray();
            $level = 0;
            if (!empty($fansConfigs)) {
                foreach ($fansConfigs as $configData) {
                    $level = $configData['level'];
                    break;
                }
            }
            $data['level'] = $level;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取粉丝列表
     * @param type : 0-总榜 1-近30天
     */
    public function getFansList($type, $uid = 0, $p = 1, $perCount = 20) {
        $postData['type'] = $type;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        if($uid > 0){
            $user = UserFactory::getInstance($uid);
            if(empty($user)){
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
        }else {
            // 用户必须登录
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        if($p > 0){
            $offset = ($p - 1) * $perCount;
        }else{
            $offset = 0;
        }

        try {
            return $user->getUserFoucusObject()->getFansListEx($type, 6, '', $offset, $perCount);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取粉丝列表
     * @param type : 0-总榜 1-近30天
     */
    public function getNewFansList($type = 0, $uid = 0, $page, $pageSize) {
        $postData['type'] = $type;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        if($uid > 0){
            $user = UserFactory::getInstance($uid);
            if(empty($user)){
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
        }else {
            // 用户必须登录
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        /*if($p > 0){
            $offset = ($p - 1) * $perCount;
        }else{
            $offset = 0;
        }*/

        try {
            // return $user->getUserFoucusObject()->getNewFansListEx($type, 6, '', $offset, $perCount);
            return $user->getUserFoucusObject()->getNewFansListEx($type, '', $page, $pageSize);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取粉丝贡献
    public function getFansConsume($uid, $page, $pageSize){
        !$uid && $uid = 0;
        $isValid = $this->validator->validate(array('uid'=>$uid));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $res = \Micro\Models\Users::findfirst('uid = ' . $uid);
            if(empty($res)){
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
            $user = UserFactory::getInstance($uid);
            return $user->getUserFoucusObject()->getNewFans($page, $pageSize);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取粉丝列表
     * @param type : 0-总榜 1-近30天
     */
    public function getMobileFansList($type, $nickName, $uid = 0, $p, $limit) {
        $postData['type'] = $type;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        if($uid > 0){
            $user = UserFactory::getInstance($uid);
            if(empty($user)){
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
        }else {
            // 用户必须登录
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        try {
            if($p > 1){
                $offset = ($p - 1) * $limit;
            }else{
                $offset = 0;
            }

            if(strlen($nickName) > 0){
                $res = $this->getUidByNickname($nickName);
                if($res['code'] == $this->status->getCode('OK')){
                    if($res['data']){
                        $uidList = $res['data'];
                    }else{
                        $uidList = array(-1);
                    }
                }else{
                    $uidList = array(-1);
                }
            }else{
                $uidList = array();
            }
//            $res = $this->getUidByNickname($nickName);
//            if($res['code'] == $this->status->getCode('OK')){
//                $uidList = $res['data'];
//            }else{
//                if($nickName){
//                    $uidList = array(-1);
//                }else{
//                    $uidList = array();
//                }
//            }
//            if($nickName){
//                $res = $this->getUidByNickname($nickName);
//                if($res['code'] == $this->status->getCode('OK')){
//                    $uidList = $res['data'];
//                }else{
//                    $uidList = array();
//                }
//            }

            return $user->getUserFoucusObject()->getMobileFansListEx($uidList, '', $offset, $limit);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 判断是否是目标用户的粉丝
     */
    public function isFans($targetId) {
        $postData['uid'] = $targetId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        try {
            $flag = $user->getUserFoucusObject()->isFans($targetId);
            $data['result'] = $flag;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得当前用户粉丝uid列表
     *
     * @return mixed
     */
    public function getUserFansUidList($uid){
        // 用户必须登录
        if($uid){
            $user = UserFactory::getInstance($uid);
        }

        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        return $user->getUserFoucusObject()->getFansUidList();
    }

    /**
     * 获取关注的人数
     */
    public function getFocusCount($uid = 0) {
        if($uid){
            $user = UserFactory::getInstance($uid);
        }else{
            // 用户必须登录
            $user = $this->userAuth->getUser();
        }

        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $count = $user->getUserFoucusObject()->getOwnFollowCount();
            $data['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取关注的信息列表
     * @param type 0-全部关注,1-重点关注,2-互相关注
     * @param sort 0-关注时间,1-主播等级,2-富豪等级
     */
    public function getFocusList($type, $sortType, $findUid, $uid = 0, $p =1, $perCount = 20) {
        $postData['type'] = $type;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        if($uid > 0){
            $user = UserFactory::getInstance($uid);
            if(empty($user)) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
        }else{
            // 用户必须登录
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        try {
            $focus = '';
            $eachFollow = '';
            if ($type == 1) {
                $focus = 1;
            }

            if ($type == 2) {
                $eachFollow = 1;
            }

            if($p > 0){
                $offset = ($p - 1) * $perCount;
            }else{
                $offset = 0;
            }

            return $user->getUserFoucusObject()->getOwnFollowListEx($sortType, $findUid, $focus, $eachFollow, '', $offset, $perCount);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取关注的信息列表
     * @param type 0-全部关注,1-重点关注,2-互相关注
     * @param sort 0-关注时间,1-主播等级,2-富豪等级
     */
    public function getNewFocusList($type, $sortType, $nickName,$orderType, $uid = 0, $p =1, $perCount = 20) {
        $postData['type'] = $type;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        if($uid > 0){
            $user = UserFactory::getInstance($uid);
            if(empty($user)) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
        }else{
            // 用户必须登录
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        try {
            $focus = '';
            $eachFollow = '';
            if ($type == 1) {
                $focus = 1;
            }

            if ($type == 2) {
                $eachFollow = 1;
            }

            if($p > 0){
                $offset = ($p - 1) * $perCount;
            }else{
                $offset = 0;
            }

            return $user->getUserFoucusObject()->getNewOwnFollowListEx($sortType, $nickName, $orderType, $focus, $eachFollow, '', $offset, $perCount);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取关注的信息列表
     * @param type 0-全部关注,1-重点关注,2-互相关注
     * @param sort 0-关注时间,1-主播等级,2-富豪等级
     */
    public function getMobileFocusList($type, $sortType, $nickName, $uid = 0, $p = 1, $limit = '') {
        $uidList = array();
        $postData['type'] = $type;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        if($uid > 0){
            $user = UserFactory::getInstance($uid);
            if(empty($user)) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
        }else{
            // 用户必须登录
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        try {
            $focus = '';
            $eachFollow = '';
            if ($type == 1) {
                $focus = 1;
            }

            if ($type == 2) {
                $eachFollow = 1;
            }

            if($p > 1){
                $offset = ($p - 1) * $limit;
            }else{
                $offset = 0;
            }

//            if($nickName){
//                $res = $this->getUidByNickname($nickName);
//                if($res['code'] == $this->status->getCode('OK')){
//                    $uidList = $res['data'];
//                }else{
//                    $uidList = array();
//                }
//            }
//            $res = $this->getUidByNickname($nickName);
//            if($res['code'] == $this->status->getCode('OK')){
//                $uidList = $res['data'];
//            }else{
//                if($nickName){
//                    $uidList = array(-1);
//                }else{
//                    $uidList = array();
//                }
//
//            }
            if(strlen($nickName) > 0){
                $res = $this->getUidByNickname($nickName);
                if($res['code'] == $this->status->getCode('OK')){
                    if($res['data']){
                        $uidList = $res['data'];
                    }else{
                        $uidList = array(-1);
                    }
                }else{
                    $uidList = array(-1);
                }
            }else{
                $uidList = array();
            }


            return $user->getUserFoucusObject()->getOwnMobileFollowListEx($sortType, $uidList, $focus, $eachFollow, '', $offset, $limit);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 更新当前用户所关注的fid为是否重点关注
     * @param targetId 关注的用户id
     * @param focus 是否重点关注 1 是 0 否
     */
    public function setFocusImportant($targetId, $focus) {
        $postData['uid'] = $targetId;
        $postData['type'] = $focus;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $flag = $user->getUserFoucusObject()->updateOwnFollowFocus($targetId, $focus);
            $data['result'] = $flag;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 更新当前用户所关注的targetId的UserData
     * @param targetId 关注的用户id
     * @param userdata 需要修改的内容
     */
    public function setFocusUserData($targetId, $userData) {
        $postData['uid'] = $targetId;
        $postData['remarks'] = $userData;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $flag = $user->getUserFoucusObject()->updateOwnFollowUserData($targetId, $userData);
            $data['result'] = $flag;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取本人被守护的信息列表
     */
    public function getBeGuardedList() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $data = $user->getUserItemsObject()->getBeGuardedList();
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取本人被守护的信息列表
     */
    public function getNewBeGuardedList($p) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $data = $user->getUserItemsObject()->getNewBeGuardedList($p);
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取本人被守护的个数
     */
    public function getBeGuardedCount() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $count = $user->getUserItemsObject()->getBeGuardedCount();
            $data['count'] = $count;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 收益模块
    //
    ////////////////////////////////////////////////////////////////////////

    /*
     * 用户收益计算
     * 家族/非家族
     * */
    public function getIncome($type, $startTime, $endTime, $page, $pageSize){
        //是否结算历史
        if($type == 'history'){
            $timeArr = array(
                'thisMonth' => 1,
                'lastMonth' => 2,
                'total' => 3,
            );
            // $result = $this->getUserSettleList($timeArr[$time], $page + 1, $pageSize);
            $result = $this->getUserSettleList($startTime, $endTime, $page + 1, $pageSize);
            if($result['code'] != $this->status->getCode('OK')){
                return $this->status->retFromFramework($result['code'], $result['data']);
            }
            $data['data']['data'] = $result['data']['list'];
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            /*$weekDay = date('N'); // 获得当前是周几
            $timeDiff = $weekDay - 1;
            $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
            $lastWeekStar = strtotime("-7 days", $weekStar); //上周一
            $monthStar = strtotime(date('Y-m') . "-01");
            $lastMonthStar = strtotime('-1 month', $monthStar);
            switch($time){
                case 'thisWeek':
                    $timeBegin = $weekStar;
                    $timeEnd = time();
                    break;
                case 'lastWeek':
                    $timeBegin = $lastWeekStar;
                    $timeEnd = $weekStar;
                    break;
                case 'thisMonth':
                    $timeBegin = $monthStar;
                    $timeEnd = time();
                    break;
                case 'lastMonth':
                    $timeBegin = $lastMonthStar;
                    $timeEnd = $monthStar;
                    break;
                case 'total':
                    $timeBegin = 0;
                    $timeEnd = time();
                    break;
                default:
                    return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
                    break;
            }*/
            // $result = $user->getUserConsumeObject()->countConsume($type == 'inFamily', $timeBegin, $timeEnd, $page, $pageSize);
            // echo $type;
            // die;
            $result = $user->getUserConsumeObject()->getConsumeDayByDay($type, $startTime, $endTime, $page, $pageSize);
            $data['data'] = $result;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getRoomLog($startTime, $endTime, $page, $pageSize){
        try {
            $user = $this->userAuth->getUser();
            $uid =  $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getAnchorWorkingData($uid, $startTime, $endTime, $page, $pageSize, false);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    ////////////////////////////////////////////////////////////////////////
    //
    // 申请审批操作接口
    //
    ////////////////////////////////////////////////////////////////////////

    /**
     * 家族申请
     */
    public function familyApply($targetId, $description) {
        $postData['uid'] = $targetId;
        $postData['accountid'] = $description;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->familyApply($targetId, $description);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 申请创建家族
     */
    public function createFamilyApply($targetId, $description) {
        $postData['uid'] = $targetId;
        $postData['accountid'] = $description;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->createFamilyApply($targetId, $description);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 签约申请
     */
    public function signApply($description) {
        $postData['accountid'] = $description;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->signApply($description);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 撤销申请
     */
    public function cancelApply($applyId) {
        $postData['uid'] = $applyId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->cancelApply($applyId);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 申请通过
     */
    public function passApply($applyId) {
        $postData['uid'] = $applyId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->passApply($applyId);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取申请和审批未读数据
    public function getTipNumber(){
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $applyObject = $user->getUserApplyObject();

        $data['apply'] = $applyObject->getApplyCount();
        $data['auditing'] = $applyObject->getAuditingCount();
        try {
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getUndoTipNumber(){
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $applyObject = $user->getUserApplyObject();
        $data['apply'] = $applyObject->getApplyUndoCount();
        $data['auditing'] = $applyObject->getAuditingUndoCount();
        try {
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 我的申请
     */
    public function getApplyList($currentPage, $pageSize, $status = -1) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->getApplyList($currentPage, $pageSize, $status);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 我的审核
     */
    public function getAuditingList($currentPage, $pageSize, $status = -1) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->getAuditingList($currentPage, $pageSize, $status);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 我的审核NUm
     */
    public function getAuditingNum() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->getAuditingNum();
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 查看某个申请的信息
     */
    public function getApplyInfo($applyUid) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserApplyObject()->getApplyInfo($applyUid);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取在线礼物
    public function getOnlineGift() {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        try {
            $result = $user->getUserItemsObject()->getOnlineGift();
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function sendOnlineGift($roomId) {
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //用户登陆验证
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        try {

            $result = $user->getUserItemsObject()->sendOnlineGift($roomId);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 安全问题操作接口
    //
    ////////////////////////////////////////////////////////////////////////

    /**
     * 是否设置过安全问题
     */
    public function isSetQuestion() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = $user->getUserSecurityObject()->getSecurityQuestionId();
        $data['result'] = $result;
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    /*
     * 更新安全问题
     * */

    public function updateIssues($oldQuestion, $question) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $result = $user->getUserSecurityObject()->updateIssues($oldQuestion['id'], $oldQuestion['answer'], $question['id'], $question['answer']);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($this->status->getCode('UPDATE_ISSUES_NO_SUCCESS'));
    }

    /*
     * 更新安全问题(回答问题)
     * */

    public function answerIssues($question) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $result = $user->getUserSecurityObject()->answerIssues($question['id'], $question['answer']);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($this->status->getCode('QUSETION_OR_ANSWER_ERR'));
    }

    /*
     * 检查安全问题。。找回密码
     * */

    public function checkQusetionAnswer($questionId, $answer) {

        $userName = $this->session->get($this->config->websiteinfo->securityuser);

        $result = $this->getUidByUserName($userName);
        if ($result['code'] != $this->status->getCode('OK')) {
            return $result;
        }

        $uid = $result['data']['uid'];
        $user = UserFactory::getInstance($uid);
        $userSecurity = $user->getUserSecurityObject();
        $sec = $userSecurity->getSecures();

        $userProfiles = UserProfiles::count("uid={$uid} AND questionId={$questionId} AND answer='{$answer}'");
        if($userProfiles > 0){
            $this->userWillResetPwd();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }

        return $this->status->retFromFramework($this->status->getCode('QUSETION_OR_ANSWER_ERR'));
    }

    /*
     * 设置安全问题
     * */

    public function setIssues($question) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $result = $user->getUserSecurityObject()->setIssues($question['id'], $question['answer']);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($this->status->getCode('SET_ISSUES_NO_SUCCESS'));
    }

    /**
     * 设置安全问题
     */
    public function setQuestion($questionId, $answer) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = $user->getUserSecurityObject()->editSecurityQuestion($questionId, $answer);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    /**
     * 查询原安全问题答案是否正确
     */
    public function checkAnswer($questionId, $answer) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $flag = $user->getUserSecurityObject()->checkSecurityAnswer($questionId, $answer);
        $data['result'] = $flag;
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 用户活动接口
    //
    ////////////////////////////////////////////////////////////////////////

    /**
     * 查询用户首充情况
     */
    public function getChargeInfo() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $data = $user->getUserActivityObject()->getPayActivityInfo();

        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    /**
     * 用户领取首充礼包
     */
    public function getChargeGift($key) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = $user->getUserActivityObject()->getPayActivityGift($key);
         return $this->status->retFromFramework($result['code'], $result['data']);
    }

    /*
     * 分享活动
     */
    public function shareActivity($type,$anchorId) {
        $postData['uid'] = $anchorId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
         // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = $user->getUserActivityObject()->shareActivity($type, $anchorId);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }
    


    //我的账单
	public function consumeList($type, $times){
			
			$user = $this->userAuth->getUser();
		   if(!$user){
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }	
			try {

    			$result = $user->getUserConsumeObject()->consumeList($type,$times);		
                return $this->status->retFromFramework($this->status->getCode('OK'), $result);
				 
			} catch (\Exception $e) {
				return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
			}
		
	}

    public function newconsumeList($type, $start = 0, $end = 0, $p = 1, $limit = 10){
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $result = $user->getUserConsumeObject()->newconsumeList($type, $start, $end, '', $p, $limit);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    //获取默认头像
	public function getCustomDefaultAvatar(){
        $user = $this->userAuth->getUser();
	    if(!$user){
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
		}
		try {
            $filePath = $this->pathGenerator->getCustomDefaultAvatarPath();
            $number = $this->config->websiteinfo->customatatarnum;
			$result = array();
            for($i=1; $i<=$number; $i++ ) {
                $data['id'] = $i;
                $data['path'] = $filePath.$i.'.jpg';
                array_push($result, $data);
            }

			return $this->status->retFromFramework($this->status->getCode('OK'), $result);
			
		} catch (\Exception $e) {
				return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
		}
	}

    //修改昵称
    public function updateNickName($nickName){
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $nicknameResult = $this->checkNickNameExist($nickName);
            if ($nicknameResult['code'] != $this->status->getCode('OK')) {
                return $this->status->retFromFramework($this->status->getCode('NICKNAME_HAS_EXISTS'));
            }

            return $user->getUserInfoObject()->updateNickName($nickName);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //修改签名
    public function updateSignature($signature){
        $user = $this->userAuth->getUser();
        if(!$user){
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            return $user->getUserInfoObject()->updateSignature($signature);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    /**
     * 手机登录用户基本信息获取
     *
     * @return mixe
     */
    public function getMobileUserInfo($uid){
        try {
            //机器人
            if(UserFactory::isRobot($uid)){
                $return = UserFactory::getRobotData_detail($uid);
                return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            }
            $user = UserFactory::getInstance($uid);
            if(empty($user)){
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
            $userBaseProfiles = $user->getUserInfoObject()->getUserProfiles();
            $userBaseData = $user->getUserInfoObject()->getData();
            if(empty($userBaseInfo) || empty($userBaseProfiles) || empty($userBaseData)){
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }


            $userRoomInfo = $this->roomModule->getRoomMgrObject()->getRoomInfo(NULL, $uid);
            $fansInfo = $this->getFansCount($userBaseInfo['uid']);
            $focusInfo = $this->getFocusCount($userBaseInfo['uid']);
            // 获得粉丝数
            $userBaseInfo['fanscount'] = isset($fansInfo['data']['totalNum']) ? $fansInfo['data']['totalNum'] : 0;
            $userBaseInfo['focuscount'] = isset($focusInfo['data']['count']) ? $focusInfo['data']['count'] : 0;
            $userBaseInfo['coin'] = $userBaseProfiles['coin'];
            $userBaseInfo['vipLevel'] = $userBaseProfiles['vipLevel'];
//            $userBaseInfo['cash'] = $userBaseProfiles['cash'] + $userBaseProfiles['money'];
            $userBaseInfo['cash'] = $userBaseProfiles['cash'];
            $userBaseInfo['anchorLevel'] = $userBaseData['anchorLevel'];
            $userBaseInfo['anchorExp'] = $userBaseProfiles['anchorExp'];
            $userBaseInfo['richerExp'] = $userBaseProfiles['richerExp'];
            $userBaseInfo['fansExp'] = $userBaseProfiles['fansExp'];
            $userBaseInfo['charmExp'] = $userBaseProfiles['charmExp'];
            $userBaseInfo['anchorLevel'] = $userBaseProfiles['anchorLevel'];
            $userBaseInfo['richerLevel'] = $userBaseProfiles['richerLevel'];
            $userBaseInfo['canSetPassword'] = $userBaseData['canSetPassword'];
            $userBaseInfo['canSetUserName'] = $userBaseData['canSetUserName'];
            $userBaseInfo['userName'] = $userBaseData['userName'];
            $userBaseInfo['fansLevel'] = $userBaseProfiles['fansLevel'];
            $userBaseInfo['charmLevel'] = $userBaseProfiles['charmLevel'];
            $userBaseInfo['isOpenSign'] = $userBaseProfiles['isOpenSign'];
            $userBaseInfo['points'] = $userBaseProfiles['points'];
            $userBaseInfo['isSignAnchor'] = $this->checkIsAnchor($uid);;
            $userBaseInfo['roomId'] = $userRoomInfo ? $userRoomInfo->roomId : 0;
            $userBaseInfo['liveStatus'] = $userRoomInfo ? $userRoomInfo->liveStatus : 0;
            $userBaseInfo['isOpenVideo'] = $userRoomInfo ? $userRoomInfo->isOpenVideo : 0;
            $userBaseInfo['showStatus'] = $userRoomInfo ? $userRoomInfo->showStatus : 0;
            if(!empty($userBaseInfo['telephone'])){
                $userBaseInfo['telephone'] = substr_replace($userBaseInfo['telephone'], '****', 3, 4);
            }

            if($user != NULL){
                $signLevel = $user->getUserItemsObject()->getUserSignLevel();
                $userBaseInfo['signLevel'] = $signLevel;
            }else{
                $userBaseInfo['signLevel'] = 0;
            }

            //是否签到
            $signRes = $this->signData->getOneSignStatus($uid);
            if ($signRes['code'] != $this->status->getCode('OK')) {
                $userBaseInfo['signStatus'] = 1;
            }else{
                $userBaseInfo['signStatus'] = $signRes['data']['status'];
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $userBaseInfo);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

	//设置默认头像
	public function setCustomDefaultAvatar($id){
        $user = $this->userAuth->getUser();
        if(!$user){
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $filePath = $this->pathGenerator->getCustomDefaultAvatarPath();
            $filePath = $filePath.$id.'.jpg';

            return $user->getUserInfoObject()->setAvatarPath($filePath);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
	}

    //获取用户的所有等级信息
    //@param uid 如果为空，则获取自己的
    public function getUserLevelInfo($uid=NULL){
        try {
            if($uid==NULL)//获取自己的信息
            {
                $user=$this->userAuth->getUser();
                if(!$user)
                    return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }else{
                $user=UserFactory::getInstance($uid);
                if(!$user)
                    return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            //机器人
            if($uid != NULL && UserFactory::isRobot($uid)){
                $return = UserFactory::getRobotData_levelInfo($uid);
                return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            }
            //$userInfo = $user->getUserInfoObject()->getData();
            $userInfo = $user->getUserInfoObject()->getUserProfiles();

            $resultArray['anchorLevel']=$userInfo['anchorLevel'];
            $resultArray['richerLevel']=$userInfo['richerLevel'];
            $resultArray['fansLevel']=$userInfo['fansLevel'];
            $resultArray['vipLevel']=$userInfo['vipLevel'];
            if($userInfo['vipExpireTime']<=time())
            {
                $resultArray['vipLevel']=0;
            }
            $resultArray['anchorExp']=$userInfo['anchorExp'];
            $resultArray['richerExp']=$userInfo['richerExp'];
            //$resultArray['vipExp']=$userInfo['vipExp'];
            $resultArray['fansExp']=$userInfo['fansExp'];
            // 得到主播等级上下限配置值
            $conditions = "level = :level:";
            $parameters = array("level" => $resultArray['anchorLevel']);
            $result = $this->configMgr->getAnchorConfigInfoEx($conditions, $parameters);
            if ($result['code'] == $this->status->getCode('OK')) {
                $resultArray['anchorLevelHigher'] = $result['data']['higher']+1;
                $resultArray['anchorLevelLower'] = $result['data']['lower'];
            }
            $conditions= "level = :level:";
            $parameters=array("level"=>($resultArray['anchorLevel']+1));
            $result=$this->configMgr->getAnchorConfigInfoEx($conditions,$parameters);
            if ($result['code'] == $this->status->getCode('OK')) {
                $resultArray['nextAnchorLevel'] = $result['data']['level'];
                $resultArray['nextAnchorName'] = $result['data']['name'];
            } else if ($result['code'] == $this->status->getCode('DATA_IS_NOT_EXISTED')) {//满级
                $resultArray['nextAnchorLevel'] = $resultArray['anchorLevel'];
            }
            // 得到主播粉丝上下限配置值
            $conditions = "level = :level:";
            $parameters = array("level" => $resultArray['fansLevel']);
            $result = $this->configMgr->getFansConfigInfoEx($conditions, $parameters);
            if ($result['code'] == $this->status->getCode('OK')) {
                $resultArray['fansLevelHigher'] = $result['data']['higher']+1;
                $resultArray['fansLevelLower'] = $result['data']['lower'];
            }
            $conditions= "level = :level:";
            $parameters=array("level"=>($resultArray['fansLevel']+1));
            $result=$this->configMgr->getFansConfigInfoEx($conditions,$parameters);
            if($result['code'] == $this->status->getCode('OK')){
                $resultArray['nextfansLevel']=$result['data']['level'];
                $resultArray['nextFansName']=$result['data']['name'];
            } else if ($result['code'] == $this->status->getCode('DATA_IS_NOT_EXISTED')) {//满级
                $resultArray['nextfansLevel'] = $resultArray['fansLevel'];
            }
            // 得到富豪等级上下限配置值
            $conditions = "level = :level:";
            $parameters = array("level" => $resultArray['richerLevel']);
            $result = $this->configMgr->getRicherConfigInfoEx($conditions, $parameters);
            if ($result['code'] == $this->status->getCode('OK')) {
                $resultArray['richerLevelHigher'] = $result['data']['higher']+1;
                $resultArray['richerLevelLower'] = $result['data']['lower'];
            }
            $conditions= "level = :level:";
            $parameters=array("level"=>($resultArray['richerLevel']+1));
            $result=$this->configMgr->getRicherConfigInfoEx($conditions,$parameters);
            if($result['code'] == $this->status->getCode('OK')){
                $resultArray['nextRicherLevel']=$result['data']['level'];
                $resultArray['nextRicherName']=$result['data']['name'];
            } else if ($result['code'] == $this->status->getCode('DATA_IS_NOT_EXISTED')) {//满级
                $resultArray['nextRicherLevel'] = $resultArray['richerLevel'];
            }
            /*if($resultArray['vipLevel']>0)
            {
                // 得到VIP等级上下限配置值
                $conditions = "level = :level:";
                $parameters = array("level" => $resultArray['vipLevel']);
                $result = $this->configMgr->getVipConfigInfoEx($conditions, $parameters);
                if ($result['code'] == $this->status->getCode('OK')) {
                    $resultArray['vipLevelHigher'] = $result['data']['higher']+1;
                    $resultArray['vipLevelLower'] = $result['data']['lower'];
                }
                $conditions= "level = :level:";
                $parameters=array("level"=>($resultArray['vipLevel']+1));
                $result=$this->configMgr->getVipConfigInfoEx($conditions,$parameters);
                if($result['code'] == $this->status->getCode('OK')){
                    $resultArray['nextVipLevel']=$result['data']['level'];
                }
            }*/

            return $this->status->retFromFramework($this->status->getCode('OK'), $resultArray);
        }catch(\Exception $e) {
            $this->logger->error('getUserPhoto error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //用户结算记录
    public function getUserSettleList($startTime, $endTime, $currentPage = 1, $pageSize = 20) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getUserConsumeObject()->getUserAccountList($user->getUid(), $startTime, $endTime, $currentPage, $pageSize);
    }
    
       //用户通知列表
    public function getInformationList($type=0, $status=0, $currentPage=1, $pageSize=10) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = $user->getUserInformationObject()->getUserInformationList($type, $status, $currentPage, $pageSize);
         return $result;
    }

    //未读通知数
    public function getUnReadInfoNum($type =0) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = $user->getUserInformationObject()->getUnReadInformationNum($type);
        return $result;
    }
    
    //是否有未读通知
    public function isHasUnRead() {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = $user->getUserInformationObject()->isHasUnReadInformation();
        return $result;
    }
    
    //删除通知
    public function delInformation($ids) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = $user->getUserInformationObject()->delUserInformation($ids);
        return $result;
    }
    
     //阅读通知
    public function readInformation($informationIds='',$applyIds='') {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        //阅读通知
        $informationIds&& $user->getUserInformationObject()->readUserInformation($informationIds);
        //阅读申请
        $applyIds&& $user->getUserApplyObject()->readUserApply($applyIds);
        return $this->status->retFromFramework($this->status->getCode('OK'),'');
    }

    //我担任的房管
    public function condoList($roomId,$times,$p=1){
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getManagementObject()->getCondoList($roomId,$times,$p);
    }

    //添加房管
    public function addHisCondo($roomId,$uid){
          // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getManagementObject()->addHisCondo($roomId,$uid);
    }

    //我自己的房管
    public function hisCondoList($roomId,$times, $p = 1){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getManagementObject()->getHisCondoList($roomId,$times, $p);

    }

    //我自己的房管New
    public function getHisCondoListNew($type, $page, $pageSize, $search){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getManagementObject()->getHisCondoListNew($type, $page, $pageSize, $search);
    }

    //
    public function checkAccountByUid($uid, $roomId){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getManagementObject()->checkAccountByUid($uid, $roomId);
    }

    //删除房管
    public function delCondo($id,$type,$uid){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getManagementObject()->delCondo($id,$type,$uid);
    }

    //添加备注
    public function getRemarks($id,$remarks,$type){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getManagementObject()->getRemarks($id,$remarks,$type);
    }

    /**
     * 定位用户坐标
     */

    public function setUserPositions(){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
//        $uid = $this->request->getPost('uid');
        $longitude = $this->request->getPost('longitude');
        $latitude = $this->request->getPost('latitude');
        $res = $this->lbs->updateCoordinate($uid, floatval($longitude), floatval($latitude));
        return $this->status->retFromFramework($this->status->getCode('OK'), $res);
    }

    // 设置超级管理员
    public function setManageType($uid, $manageType) {
        try {
            $user = Users::findFirst("uid=" . $uid);
            if (!$user) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            $user->manageType = $manageType;
            $user->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    // 设置托账号
    public function setTuoType($uid, $manageType) {
        try {
            $user = Users::findFirst("uid=" . $uid);
            if (!$user) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            $user->internalType = $manageType;
            $user->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //礼物明细
    public function getDayRecvGifts($date, $page, $pageSize){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid = $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getDayRecvGifts($uid, $date, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
        
    }

    //获取礼物收入
    public function getDayGiftsLog($date, $type, $page, $pageSize){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid =  $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getDayGiftsLog($uid, $date, $type, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取收益流水
    public function getDayIncomeLog($date, $type, $page, $pageSize){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid =  $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getDayIncomeLog($uid, $date, $type, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取佣金流水
    public function getMonthIncomeLog($date, $type, $page, $pageSize){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid =  $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getMonthIncomeLog($uid, $date, $type, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getChangeLog($startTime, $endTime, $page, $pageSize){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid =  $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getChangeLog($uid, $startTime, $endTime, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //验证是否为家族长
    public function checkIsFamilyHeader(){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid =  $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->checkIsFamilyHeader($uid);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取用户账户信息
    public function getUserAccount(){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid =  $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getUserAccount($uid);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //判断银行卡等信息是否合法
    public function checkAccount($uid = 0){
        try {
            $res = \Micro\Models\UserInfo::findFirst('uid = ' . $uid);
            if($res->bank || $res->realName || $res->cardNumber || $res->ID){
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }else{
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
        } catch (Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //提现
    public function addSettleLog($money, $type = 2){//, $accountArr
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid = $user->getUid();
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->addSettleLog($uid, $money, $type);//, $accountArr
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取提现详情
    public function getChangeDetail($id){
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getChangeDetail($id);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取概况信息
    public function getBasicInfo(){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid = $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getBasicInfo($uid);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //修改账号名
    public function setUsername($username){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $postData['username'] = $username;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            //不允许使用以ns开头的用户名；（这类用户名为系统预留，按照用户名不可用处理）
            if (substr($username, 0, 2) == 'ns') {
                return $this->status->retFromFramework($this->status->getCode('USERNAME_CANNOT_USE'));
            }
            //不允许使用手机号
            if ($this->validator->isTelephone($username)) {//验证是否是手机号
                return $this->status->retFromFramework($this->status->getCode('CANNOT_USE_TELEPHONE'));
            }
            //检查用户名是否存在
            $usernameResult = $this->checkUserExist($username);
            if ($usernameResult['code'] != $this->status->getCode('OK')) {
                return $this->status->retFromFramework($this->status->getCode('USER_NAME_EXISTS'));
            }
            $uid = $user->getUid();
            $info = Users::findfirst($uid);
            //查询用户名是否可修改
            if ($info->canSetUserName != 1) {
                return $this->status->retFromFramework($this->status->getCode('USERNAME_CANNOT_EDIT'));
            }
            $info->userName = $username;
            $info->canSetUserName = 2;
            $info->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //设置初始密码
    public function setInitPassword($password){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $postData['password'] = $password;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $uid = $user->getUid();
            $info = Users::findfirst($uid);
            //查询密码是否可修改
            if ($info->canSetPassword != 1) {
                return $this->status->retFromFramework($this->status->getCode('PASSWORD_CANNOT_INIT'));
            }
            $info->password = md5($info->key.$password);
            $info->canSetPassword = 2;
            $info->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function ifSetPwd($uid){
        try {
            $user = UserFactory::getInstance($uid);
//            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $uid = $user->getUid();
            $info = Users::findfirst($uid);
            return $this->status->retFromFramework($this->status->getCode('OK'), $info->canSetPassword);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询用户安全中心信息
    public function getUserSecurityInfo(){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $info = $user->getUserInfoObject()->getData();
            $return['canSetPassword'] = $info['canSetPassword'] == 1 ? 1 : 0; //是否可设置密码
            $return['telephone'] = $info['telephone'] ? substr_replace($info['telephone'], '****', 3, 4) : ''; //手机号
            $return['questionId'] = $info['questionId']; //安全问题id
            if ($info['questionId']) {
                $configData = \Micro\Models\QuestionConfigs::findfirst($info['questionId']);
                $return['questionName'] = $configData->name; //安全问题描述
            } else {
                $return['questionName'] = '';
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取礼物周星排行
    public function getWeekStar($giftId = 0){
        try {
            /*$user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }*/

            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getWeekStar($giftId);

            return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取主播的周星数据
    public function getMyWeekStar($giftId = 0){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid = $user->getUid();
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getMyWeekStar($uid, $giftId);

            return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //推荐领取礼包记录手机号
    public function getRecommendGiftLog($str = '', $uid = 0, $telephone = '') {
        /*if (!$str) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }*/
        if($str){
            $string = base64_decode(urldecode($str));
            $arr = explode('_', $string);
            $uid = $arr[1];
        }
        $postData['uid'] = $uid;

        /*$string = base64_decode(urldecode($str));
        $arr = explode('_', $string);
        $uid = $arr[1];
        $postData['uid'] = $uid;*/
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        if (!$this->validator->isTelephone($telephone)) {//不是手机号
            return $this->status->retFromFramework($this->status->getCode('MOBILEPHONE_IS_ERROR'));
        }
        try {
            $rinfo = \Micro\Models\Recommend::findfirst("uid=" . $uid);
            if ($rinfo == false) {//uid不在推荐表中
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            $info = \Micro\Models\RecommendLog::findfirst("telephone='" . $telephone . "'");
            if ($info) {//此号码已领取过
                return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'));
            }

            //判断手机号是否绑定过
            if (!$this->userAuth->checkPhone($telephone)) {
                return $this->status->retFromFramework($this->status->getCode('THE_PHONE_HAS_GET_REWARD'));
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

            //写入数据库
            $new = new \Micro\Models\RecommendLog();
            $new->beRecUid = 0;
            $new->telephone = $telephone;
            $new->recUid = $uid;
            $new->createTime = time();
            $new->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取推荐活动的新手礼包
    public function getRecommendPackageGift($telephone,$uid=0) {
        try {
            if (time() > strtotime($this->config->recommendConfig->endTime)) {//活动已结束
                return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
            }
            
            if ($uid) {
                $user = UserFactory::getInstance($uid);
            } else {
                $user = $this->userAuth->getUser();

                if ($user == NULL) {// 用户必须登录
                    return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
                }
            }
            $info = \Micro\Models\RecommendLog::findfirst("telephone='" . $telephone . "'");
            if ($info == false) {//无奖励
                return $this->status->retFromFramework($this->status->getCode('REWARD_IS_NOT_EXISTED'));
            }
            if ($info->beRecUid) {//已领取过奖励
                return $this->status->retFromFramework($this->status->getCode('THE_PHONE_HAS_GET_REWARD'));
            }
            //给用户礼包
            $giftPackageId=  $this->config->recommendGiftId;
            $user->getUserItemsObject()->giveGiftPackage($giftPackageId);
            $uid = $user->getUid();
            $rinfo= \Micro\Models\RecommendLog::findfirst("telephone='".$telephone."'");
            if($rinfo){
                $rinfo->beRecUid=$uid;
                $rinfo->save();
            }
            $return['reward']=array();
            //查询礼包具体信息
            $configResult = $this->configMgr->getgiftPackageBaseConfig($giftPackageId, 1);
            if ($configResult['code'] == $this->status->getCode('OK')) {
                $return['reward'] = $configResult['data'];
            }
            return $this->status->retFromFramework($this->status->getCode('OK'),$return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    
    //获取活动奖励明细
    public function getActivityIncomeDayLog($date, $page, $pageSize) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getActivityIncomeDayLog($uid, $date, $page, $pageSize);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取游戏提成明细
    public function getGameDeductDetail($date, $page, $pageSize) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getGameDeductDetail($uid, $date, $page, $pageSize);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取游戏提成每日收入
    public function getGameDeductDay($date, $page, $pageSize) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getGameDeductDay($uid, $date, $page, $pageSize);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //七夕活动排行榜
    public function getQixiRank() {
        
        //读取缓存
        $normalLib = $this->di->get('normalLib');
        $cacheKey = 'qixi_rank';
        $cacheResult = $normalLib->getCache($cacheKey);
        if (isset($cacheResult)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $cacheResult);
        }

        try {
            $result=array();
            $uids = $this->config->qixi->uids;
            $j=0;
            foreach ($uids as $val) {
                $user = UserFactory::getInstance($val);
                $userInfo = $user->getUserInfoObject()->getUserInfo();
                $result[$j]['uid'] = $val;
                $result[$j]['nickName'] = $userInfo['nickName'];
                $result[$j]['avatar'] = $userInfo['avatar'];

                //主播收到莲花灯的总数
                $sumsql = "select sum(count) sum "
                        . "from \Micro\Models\ConsumeDetailLog "
                        . "where receiveUid=" . $val . " and type=" . $this->config->consumeType->sendGift
                        . " and itemId=" . $this->config->qixi->giftId . " and createTime<" . $this->config->qixi->endTime;
                $sumquery = $this->modelsManager->createQuery($sumsql);
                $sumres = $sumquery->execute();
                $sumres = $sumres->toArray();
                $result[$j]['sum'] = $sumres[0]['sum'] ? $sumres[0]['sum'] : 0;
                $sort[] = $result[$j]['sum'];

                //主播下贡献前十的用户信息
                $listsql = "select uid,sum(count) sum "
                        . "from \Micro\Models\ConsumeDetailLog "
                        . "where receiveUid=" . $val . " and type=" . $this->config->consumeType->sendGift
                        . " and itemId=" . $this->config->qixi->giftId . " and createTime<" . $this->config->qixi->endTime
                        . " group by uid order by sum desc limit 10";
                $listquery = $this->modelsManager->createQuery($listsql);
                $listres = $listquery->execute();
                $listres = $listres->toArray();
                $list = array();
                if ($listres) {
                    $i = 0;
                    foreach ($listres as $v) {
                        $user = UserFactory::getInstance($v['uid']);
                        $userInfo = $user->getUserInfoObject()->getUserInfo();
                        $list[$i]['nickName'] = $userInfo['nickName'];
                        $list[$i]['avatar'] = $userInfo['avatar'];
                        $list[$i]['sum'] = $v['sum'];
                        $i++;
                    }
                }
                $result[$j]['list'] = $list;
                $j++;
            }
            array_multisort($sort, SORT_DESC, $result);
            //设置缓存
            $liftTime = 300; //有效期5分钟
            $normalLib->setCache($cacheKey, $result, $liftTime);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取是否为主播
    public function checkIsAnchor($uid = 0){
        try {
            // 判断是否签约主播
            $signRes = \Micro\Models\SignAnchor::findFirst('uid = ' . $uid . ' and status not in (0,3,4)');
            $roomRes = \Micro\Models\Rooms::findfirst('uid = ' . $uid);
            if(!empty($signRes) && !empty($roomRes)){
                return $roomRes->roomId;
            }else{
                return 0;
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取直播间封面
    public function getRoomPic($uid = 0){
        try {
            $sql = 'select r.roomId,r.uid,ui.avatar,r.poster from \Micro\Models\Rooms as r left join \Micro\Models\UserInfo as ui on r.uid = ui.uid where r.uid = ' . $uid . ' limit 1';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            
            if($res->valid()){
                $room = $res->toArray()[0];
                $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($room['poster'], $room['avatar']);
                $roomData['poster'] = $posterUrls['poster'];
                $roomData['small_poster'] = $posterUrls['small-poster'];
                return $roomData;
            }else{
                return array();
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获得所有允许推送的pushMgr
    public function sendSignPush(){
        // 获得所有要推送的用户
        $userList = array();
        $res = UserProfiles::find("isOpenSign=1");
        if($res){
            foreach($res as $val){
                // 判断这个用户是否已经签到过了
                $result = $this->signData->getOneSignStatus($val->uid);
                if($result['code'] == $this->status->getCode('OK') && $result['data']['status'] == 1){
                    continue;
                }

                $userList[] = $val->uid;
            }
        }

        if($userList){
            $message = array(
                'action' => 'sign',
            );

            $content = "你今天还没有签到哦，马上去签到领奖品吧!";
            return $this->pushMgr->sendMessage($userList, $message, $content);
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    // 获得所有允许推送的pushMgr
    public function sendLoginPush(){
        try {
            $today = time();
            // 推送开始执行时间
            $nowDate = strtotime(date('Y-m-d 18:00:00'));
            $lastDay = $today - 86400;
            $lastDate = $nowDate - 86400;
            $sql = 'select di.pushUid,di.pushTime,u.updateTime from \Micro\Models\DeviceInfo as di '
                . ' left join Micro\Models\Users as u on u.uid = di.pushUid where di.pushUid != 0 and u.updateTime <= ' . $lastDate;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $userList = array();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $updateTime = $v->updateTime;
                    $pushTime = $v->pushTime;
                    if(($nowDate - $updateTime) < (86400 * 3)){//1-3
                        $days = floor(($pushTime - $updateTime) / 86400);
                        if($pushTime && (($days >= 1 && $days < 3) || $pushTime == $nowDate)){
                            continue;
                        }
                        $userList['one'][] = $v->pushUid;
                        $content['one'] = '想你的第一天，度日如年，来看看我吧';

                    }else if(($nowDate - $updateTime) < 86400 * 7){//3-7
                        $days = floor(($pushTime - $updateTime) / 86400);
                        if($pushTime && (($days >= 3 && $days < 7) || $pushTime == $nowDate)){
                            continue;
                        }
                        $userList['three'][] = $v->pushUid;
                        $content['three'] = '想你的第三天，因为你不在我心不在焉';
                    }else{//7-
                        $days = floor(($pushTime - $updateTime) / 86400);
                        if($pushTime && (($days >= 7) || $pushTime == $nowDate)){
                            continue;
                        }
                        $userList['seven'][] = $v->pushUid;
                        $content['seven'] = '想你的第七天，今天我穿得美美的就是为了等你哦';
                    }
                    $pushUids[] = $v->pushUid;
                }
            }
            if($userList){
                $message = array(
                    'action' => 'login',
                );
                foreach ($userList as $key => $val) {
                    $this->pushMgr->sendMessage($userList[$key], $message, $content[$key], 1);
                }

                $updateSql = 'update \Micro\Models\DeviceInfo set pushTime = ' . $nowDate . ' where pushUid in (' . implode(',', $pushUids) . ')';
                $updateQuery = $this->modelsManager->createQuery($updateSql);
                $res = $updateQuery->execute();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), 'sendLoginPush---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    //获取用户基本信息 add by 2015/08/24
    public function getUserBaseInfo() {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $sql = "select ui.uid,ui.nickName,ui.avatar,up.coin,up.cash,up.points "
                    . "from \Micro\Models\UserInfo ui inner join \Micro\Models\UserProfiles up on ui.uid=up.uid "
                    . "where ui.uid=" . $uid;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $result = $res->toArray();
            if(!$result[0]['avatar']){
                 $result[0]['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();//默认头像
            } 
            return $this->status->retFromFramework($this->status->getCode('OK'), $result[0]);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //用户兑换礼包 add by 2015/08/25
    public function exchangeGiftPackage($code) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $now = time();
            $info = \Micro\Models\ExchangeGiftLog::findfirst("code='" . $code . "' and expireTime>" . $now);
            if ($info == false) {//兑换码无效或已过期
                return $this->status->retFromFramework($this->status->getCode('EXCHANGE_CODE_ERROR'));
            }
            if($info->uid>0){//已兑换过
                return $this->status->retFromFramework($this->status->getCode('EXCHANGE_FAIL'));
            }
            //给用户送礼包
            $user->getUserItemsObject()->giveGiftPackage($info->giftPackageId);
            $info->uid = $uid;
            $info->getTime = $now;
            $info->save();
            $return = array();
            $configResult = $this->configMgr->getgiftPackageBaseConfig($info->giftPackageId, 1);
            if ($configResult['code'] == $this->status->getCode('OK')) {
                $return=$configResult['data'];
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getLiveData($familyId, $startTime, $endTime, $page, $pageSize){
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getLiveData($familyId, $startTime, $endTime, $page, $pageSize);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getFamilyAnchorNew($familyId, $page, $pageSize, $search){
        try {
            $InvAgent = new InvAgent();
            $result =  $InvAgent->getFamilyAnchorNew($familyId, $page, $pageSize, $search);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delAnchorNew($id){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->delAnchorNew($id);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getFamilyAnchorInfo($uid){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->getFamilyAnchorInfo($uid);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    //PC端推荐记录 add by 2015/09/10
    public function setPcRecommendLog($str, $regUid) {
        if (!$str) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        $string = base64_decode(urldecode($str));
        $arr = explode('_', $string);
        $uid = $arr[1];
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $rinfo = \Micro\Models\Recommend::findfirst("uid=" . $uid);
            if ($rinfo == false) {//uid不在推荐表中
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            //写入数据库
            $new = new \Micro\Models\RecommendLog();
            $new->beRecUid = $regUid;
            $new->telephone = 0;
            $new->recUid = $uid;
            $new->createTime = time();
            $new->save();

            /*//给用户礼包
            $giftPackageId=  $this->config->recommendGiftId;
            $user = UserFactory::getInstance($regUid);
            $user->getUserItemsObject()->giveGiftPackage($giftPackageId);*/

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delOrder($id = 0){
        try {
            $isValid = $this->validator->validate(array('id'=>$id));
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            $res = \Micro\Models\Order::findFirst('id = ' . $id);
            if(empty($res)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $res->isDelete = 1;

            $result = $res->save();

            if($result){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }else{
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }

             
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取守护个数

    public function getGuardCount($uid = 0){
        try {
            $user = UserFactory::getInstance($uid);
            $result = $user->getUserItemsObject()->getBeGuardedListNew();
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    //绑定手机时判断是否有推荐礼包 add by 2015/09/22
    public function bindPhoneRecommend($telephone) {
        $postData['telephone'] = $telephone;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $rinfo = \Micro\Models\RecommendLog::findfirst("telephone='" . $telephone . "'");
            if ($rinfo == false || $rinfo->beRecUid) {//不在推荐领奖表中 或者 已领奖过
               return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
            $orinfo = \Micro\Models\RecommendLog::findfirst("beRecUid=" . $uid);
            if ($orinfo != false) {//已绑定了推荐者
                return $this->status->retFromFramework($this->status->getCode('HAS_BIND_RECOMMOND'));
            }
            
            //查询注册时间
            $reginfo = \Micro\Models\RegisterLog::findfirst("uid=" . $uid);
            if ($reginfo == false || $reginfo->createTime < $rinfo->createTime) {//如果是在领奖前就注册的
                return $this->status->retFromFramework($this->status->getCode('THIS_TELEPHONE_HAS_REG'));
            }

            $rinfo->beRecUid = $uid;
            $rinfo->save();

            $recUid = $rinfo->recUid; //推荐人id
            //给用户礼包
            $giftPackageId = $this->config->recommendGiftId;
            $user->getUserItemsObject()->giveGiftPackage($giftPackageId);
                
            //把之前的充值返还给推荐者
            $this->getRecommendPay($recUid, $uid);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //把充值返还给推荐人 add by 2015/09/22
    public function getRecommendPay($recUid, $beRecUid) {
        try {
            $today = strtotime(date("Y-m-d"));
            $sql1 = 'delete from \Micro\Models\ActivityIncomeLog where createTime = ' . $today . " and uid=".$recUid." and remark='" . $beRecUid . "' and type=3";
            $qry1 = $this->modelsManager->createQuery($sql1);
            $qry1->execute();
            $sql = "select sum(totalFee) as sum from \Micro\Models\Order where uid=" . $beRecUid . " and status=1 and payType < 1000 and payTime<" . $today;
            $qry = $this->modelsManager->createQuery($sql);
            $res = $qry->execute();
            $res = $res->toArray();
            if ($res && $res[0]['sum']) {
                // 插入数据
                $activityIncomeLog = new \Micro\Models\ActivityIncomeLog();
                $activityIncomeLog->uid = $recUid;
                $activityIncomeLog->remark = $beRecUid;
                //记录聊币【因此金额*比例（整数无%）即可抵消聊币与人民币的比例】
                $orinfo = \Micro\Models\Recommend::findfirst("uid=" . $recUid);
                if ($orinfo == false) {
                    return $this->status->retFromFramework($this->status->getCode('RECOMMOND_UID_ERROR'));
                }
                $proportion = $orinfo->proportion; //返还比例
                $activityIncomeLog->money = $res[0]['sum'] * $proportion; //聊币
                $activityIncomeLog->proportion = $proportion;
                $activityIncomeLog->type = 3;
                $activityIncomeLog->createTime = $today;
                $activityIncomeLog->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 推广用户列表
    public function getRecDetailList($page, $pageSize) {
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid = $user->getUid();

            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->getRecDetailList($uid, $page, $pageSize);

            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 抽成记录
    public function getBonusList($startTime, $endTime, $search, $page, $pageSize){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $uid = $user->getUid();

            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->getBonusList($uid, $startTime, $endTime, $search, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取推广信息
    public function getRecData($uid = 0){
        try {
            $res = \Micro\Models\Recommend::findFirst('type = 1 and uid = ' . $uid);
            if(empty($res)){
                return array('url'=>'','img'=>'');
            }

            return array(
                'url' => $res->tinyUrl ? $res->tinyUrl : $res->url,
                // 'img' => 'http://121.41.37.5:8088' . ($res->imgPath ? $res->imgPath : '/'.$this->pathGenerator->getRecommendqrcodePath('qrcode_' . $uid . ".png"))
                'img' => $res->imgPath ? $res->imgPath : '/'.$this->pathGenerator->getRecommendqrcodePath('qrcode_' . $uid . ".png")
            );
        } catch (Exception $e) {
            $this->logger->error('getRecData error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return array('url'=>'','img'=>'');
        }
    }
    
    
    //app端 注册时有推荐人 add by 2015/09/28
    public function regRecommend($recUid) {
        $postData['uid'] = $recUid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $rinfo = \Micro\Models\Recommend::findfirst("uid=" . $recUid);
            if ($rinfo == false) {//uid不在推荐表中
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            //写入数据库
            $new = new \Micro\Models\RecommendLog();
            $new->beRecUid = $uid;
            $new->telephone = 0;
            $new->recUid = $recUid;
            $new->createTime = time();
            $new->save();
            
            $return['reward']=array();
            //给用户礼包
            $giftPackageId = $this->config->recommendGiftId;
            $user->getUserItemsObject()->giveGiftPackage($giftPackageId);

            //查询礼包具体信息
            $configResult = $this->configMgr->getgiftPackageBaseConfig($giftPackageId, 1);
            if ($configResult['code'] == $this->status->getCode('OK')) {
                $return['reward'] = $configResult['data'];
            }

            return $this->status->retFromFramework($this->status->getCode('OK'),$return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //修改用户所在地 add by 2015/09/29
    public function updateUserCity($city) {
        $postData['content'] = $city;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if (!$user) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $info = \Micro\Models\UserInfo::findfirst($uid);
            $info->city = $city;
            $info->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 活动页绑定绑定推荐者信息
    public function getRecUidData($uid = 0){
        try {
            $res = \Micro\Models\UserInfo::findFirst('uid = ' . $uid);
            if(!empty($res)){
                return array(
                    'nickName' => $res->nickName,
                    'avatar' => $res->avatar ? $res->avatar : $this->pathGenerator->getFullDefaultAvatarPath(),
                    'uid' => $uid
                );
            }
            return array('nickName' => '', 'avatar' => '', 'uid' => $uid);

        } catch (\Exception $e) {
            $this->logger->error('getRecUidData error errorMessage = ' . $e->getMessage());
            return array('nickName' => '', 'avatar' => '', 'uid' => $uid);
        }
    }
    
    //上传主播海报接口 add by 2015/10/20
    public function uploadAnchorRoomPoster() {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $pathGenerator = $this->di->get('pathGenerator');
        $storage = $this->di->get('storage');
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                try {
                    foreach ($this->request->getUploadedFiles() as $file) {
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        $filePath = $pathGenerator->getAnchorPosterPath($uid);
                        $fileName = time() . '.' . $fileExt;
                        $storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        try {
                            $poster = $pathGenerator->getFullAnchorPosterPath($uid,$fileName);
                            //添加到用户海报表
                            $new = new \Micro\Models\AnchorPoster();
                            $new->uid = $uid;
                            $new->imageUrl = $poster;
                            $new->isShow = 1;
                            $new->createTime = time();
                            $new->status = 0;
                            $new->auditor = '';
                            $new->auditTime = 0;
                            $new->isUsed = 0;
                            $new->save();
                            return $this->status->retFromFramework($this->status->getCode('OK'), $poster);
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }
        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }
    
    //主播海报删除 add by 2015/10/20
    public function delAnchorRoomPoster($id) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $info = \Micro\Models\AnchorPoster::findfirst("id=" . $id . " and uid=" . $uid." and isShow=1");
            if ($info == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($info->isUsed) {//使用中不能删除
                return $this->status->retFromFramework($this->status->getCode('ACTION_NO_ALLOW'));
            }
            $info->isShow = 0;
            $info->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //主播使用海报 add by 2015/10/20
    public function useAnchorRoomPoster($id) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $info = \Micro\Models\AnchorPoster::findfirst("id=" . $id . " and uid=" . $uid . " and isShow=1");
            if ($info == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($info->status != 1) {//未审核通过不能使用
                return $this->status->retFromFramework($this->status->getCode('ACTION_NO_ALLOW'));
            }
            
            $sql = "update \Micro\Models\AnchorPoster set isUsed=0 where uid=" . $uid;
            $qry = $this->modelsManager->createQuery($sql);
            $qry->execute();

            $info->isUsed = 1;
            $info->save();
            
            $roomInfo=  \Micro\Models\Rooms::findfirst("uid=".$uid);
            if($roomInfo!=false){
                $roomInfo->poster=$info->imageUrl;
                $roomInfo->save();
            }
                
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //主播用海报列表 add by 2015/10/20
    public function getAnchorRoomPosterList($page=1, $pageSize = 20) {
        $postData['id'] = $pageSize;
        $postData['uid'] = $page;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $limit = ($page - 1) * $pageSize;
            $list = \Micro\Models\AnchorPoster::find("uid=" . $uid . " and isShow=1 and status!=2 order by status desc limit " . $limit . " ," . $pageSize);
            $return = array();
            $result = array();
            foreach ($list as $val) {
                $data['id'] = $val->id;
                $data['isUsed'] = $val->isUsed;
                $data['imageUrl'] = $val->imageUrl;
                $data['createTime'] = $val->createTime;
                $data['status'] = $val->status;
                $result[] = $data;
                unset($data);
            }
            $count = \Micro\Models\AnchorPoster::count("uid=" . $uid . " and isShow=1 and status!=2");
            $return['list'] = $result;
            $return['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //查询主播海报是否上传 add by 2015/10/20
    public function checkAnchorRoomPoster() {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $uid);
            $status = 0;
            if ($roomInfo && $roomInfo->poster) {
                $status = 1;
            }

//            $info = \Micro\Models\AnchorPoster::findfirst("uid=" . $uid . " and isShow=1 and status=1 and isUsed=1");
//            if ($info == false) {
//                $status = 0;
//            } else {
//                $status = 1;
//            }
            $return['status'] = $status;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getAnchorInfo($uid = 0){
        try {
            $sql = 'select ui.uid,ui.nickName,ui.signature,sa.gender,sa.location,sa.birthday,f.shortName,f.name as familyName,rl.publicTime,ui.avatar,r.roomId from \Micro\Models\UserInfo as ui '
                . ' left join \Micro\Models\SignAnchor as sa on ui.uid = sa.uid '
                . ' left join \Micro\Models\Rooms as r on r.uid = ui.uid '
                . ' left join \Micro\Models\RoomLog as rl on r.roomId = rl.roomId '
                . ' left join \Micro\Models\Family as f on f.id = sa.familyId '
                . ' where ui.uid = ' . $uid . ' order by rl.publicTime desc limit 1 ';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                $tmp = $res->toArray()[0];
                $data['uid'] = $tmp['uid'];
                $data['roomId'] = $tmp['roomId'];
                $data['nickName'] = $tmp['nickName'];
                $data['gender'] = $tmp['gender'];
                $data['birthday'] = $tmp['birthday'];
                $data['shortName'] = $tmp['shortName'] ? $tmp['shortName'] : '';
                $data['familyName'] = $tmp['familyName'] ? $tmp['familyName'] : '';
                $data['signature'] = $tmp['signature'] ? $tmp['signature'] : '';
                $data['avatar'] = $tmp['avatar'] ? $tmp['avatar'] : $this->pathGenerator->getFullDefaultAvatarPath();
                $location = $tmp['location'];
                if(empty($location)){
                    $location = $this->config->signAnchorCityDefault;
                }
                $data['location'] = $this->config->location[$location]['name'];
                $lastPublicTime = $tmp['publicTime'];
                $data['publicTime'] = '';
                if($lastPublicTime){
                    $times = time() - $lastPublicTime;
                    if($times < 3600){
                        $data['publicTime'] = floor($times / 60) . '分钟前';
                    }else if($times < 86400){
                        $data['publicTime'] = floor($times / 3600) . '小时前';
                    }else{
                        $data['publicTime'] = date('Y-m-d H:i:s',$lastPublicTime);
                    }
                }
                $isFansRes = $this->isFans($uid);
                if($isFansRes['code'] == $this->status->getCode('OK')){
                    $data['isFans'] = $isFansRes['data']['result'];
                }else{
                    $data['isFans'] = false;
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
        }
    }

    // 判断是否家族长
    public function checkIsFamily($uid = 0){
        try {
            $info = \Micro\Models\Family::findfirst("creatorUid = " . $uid . " and status = 1");
            if ($info != false) {
                return 1;
            }
            return 0;
        } catch (\Exception $e) {
            $this->logger->error('checkIsFamily errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    // 判断是否管理员
    public function checkIsManage($uid = 0, $roomId = 0){
        try {
            $info = \Micro\Models\RoomUserStatus::findfirst(
                "uid = " . $uid . " and roomId = " . $roomId . " and level = 2 and " . 
                "( (levelTimeLine > " . time() . ") or (levelTimeLine = 0 or levelTimeLine = '') )"
            );
            if ($info != false) {
                return 1;
            }
            return 0;
        } catch (\Exception $e) {
            $this->logger->error('checkIsManage errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    // 获取富豪等级
    public function getRicherRanks(){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        try {
            $richerRanks =array();
            $info = \Micro\Models\RicherConfigs::find(
                array(
                    'order' => 'level asc',
                    'columns' => 'level,name',
                    'conditions' => 'level > 0'
                )
            );
            if(!empty($info)){
                $richerRanks = $info->toArray();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $richerRanks);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 直播间限制接口
    public function getRoomLimits(){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        try {
            $limitData =array();
            $info = \Micro\Models\RoomPrivilege::findFirst(
                'uid = ' . $uid
            );
            if(!empty($info)){
                $limitData = $info->toArray();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $limitData);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //检测是否某个直播间的家族长
    public function checkUserIsHeader($roomUid = 0, $userId = 0){
        try {
            $sql = 'select f.creatorUid from \Micro\Models\SignAnchor as sa '
                . ' left join \Micro\Models\Family as f on sa.familyId = f.id '
                . ' left join \Micro\Models\Rooms as r on f.creatorUid = r.uid '
                . ' where sa.uid = ' . $roomUid;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            if($res->valid() && $res->toArray()[0]['creatorUid'] == $userId){
                return 1;
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    //按类型检测守护是否到期
    public function checkGuardByLevel($uid = 0, $beUid = 0, $level = 1){
        try {
            $res = \Micro\Models\GuardList::findFirst(
                'guardUid = ' . $uid . ' and beGuardedUid = ' . $beUid . ' and guardLevel = ' . $level . ' and expireTime > ' . time()
            );
            if(!empty($res)){
                return 1;
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    //获取相册
    public function getMyGallery($uid = 0, $type = 1, $page = 1, $pageSize = 20){
        try {
            $type = intval($type);
            if (!in_array($type, array(1,2))) {
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }

            if(!$page){
                $page = 1;
            }

            if(!$pageSize){
                $pageSize = 20;
            }

            $limit = $pageSize * ($page - 1);

            $sql = 'select ui.id,ui.uid,ui.imgUrl,ui.imgWidth,ui.imgHeight,ui.type,ui.createTime from \Micro\Models\UserImages as ui '
                . ' where uid = ' . $uid . ' and type = ' . $type . ' and status = 0 '
                . ' order by orderType asc,createTime desc ' . ' limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                $data = $res->toArray();
            }

            $count = 0;
            $count = \Micro\Models\UserImages::count('uid = ' . $uid . ' and type = ' . $type . ' and status = 0 ');
            $count = $count ? $count : 0;

            /*$res = \Micro\Models\UserImages::find(
                array(
                    'conditions' => 'uid = ' . $uid . ' and type = ' . $type . ' and status = 0',
                    'order' => 'createTime desc',
                    'limit' => array('number' => $pageSize, 'offset' => $limit)
                )
            );

            $data = array();

            if($res->valid()){
                $data = $res->toArray();
            }*/

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取相册数目
    public function getMyGalleryNum($uid = 0){
        try {
            $myGalleryNum = \Micro\Models\UserImages::count(
                'uid = ' . $uid . ' and type = 1 and status = 0'
            );

            $myGalleryNum = $myGalleryNum ? $myGalleryNum : 0;

            $myDynamicNum = \Micro\Models\UserImages::count(
                'uid = ' . $uid . ' and type = 2 and status = 0'
            );

            $myDynamicNum = $myDynamicNum ? $myDynamicNum : 0;

            return $this->status->retFromFramework($this->status->getCode('OK'), array('myGalleryNum' => $myGalleryNum,'myDynamicNum' => $myDynamicNum));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    // 找回密码时，验证用户名是否存在
    public function checkUserFindPassword($userName) {
        $postData['username'] = $userName; //用户名
        //用户名格式验证
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //豆子账号
        if ($this->config->channelType == 2) {
            return $this->checkUserExistOfDouzi($userName);
        }
        //普通账号
        try {
            //查询用户名是否存在,区分大小写
            $sql = "select uid,canSetPassword from pre_users where binary userName  = '{$userName}' limit 1";
            $connection = $this->di->get('db');
            $result = $connection->fetchOne($sql);
            if (!$result) {//用户不存在
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }
            if ($result['canSetPassword']) {//未设置过密码
                return $this->status->retFromFramework($this->status->getCode('NOT_SET_LOGIN_PWD'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //添加相册

    //获取开播时间
    /*public function getServerTimeNew($roomId = 0){
        try {
            $res = \Micro\Models\RoomLog::findFirst('roomId = ' . $roomId . ' order by publicTime desc');
            $data = array('startTime'=>0);
            if(!empty($res)){
                $data['startTime'] = $res->toArray()['publicTime'];
                
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }*/

    //上传相册接口 add by 2015/10/20
    public function uploadPhotoAlbum() {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $pathGenerator = $this->di->get('pathGenerator');
        $storage = $this->di->get('storage');
        if ($this->request->isPost()) {
            $width = $this->request->getPost('width');
            $height = $this->request->getPost('height');
            if(!$width || !$height){
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }
            if ($this->request->hasFiles()) {
                try {
                    $file = $this->request->getUploadedFiles()[0];
                    // $i = 1;
                    // foreach ($this->request->getUploadedFiles() as $file) {
                        
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        $filePath = $pathGenerator->getAnchorAlbumPath($uid);
                        $fileName = time() . rand(10000,99999) . '.' . $fileExt;
                        $storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        // $i++;
                        try {
                            $albumPath = $pathGenerator->getFullAnchorAlbumPath($uid,$fileName);
                            //添加到用户海报表
                            $new = new \Micro\Models\UserImages();
                            $new->uid = $uid;
                            $new->imgUrl = $albumPath;
                            $new->imgWidth = $width;
                            $new->imgHeight = $height;
                            $new->type = 1;
                            $new->createTime = time();
                            $new->dynamicId = 0;
                            $new->status = 0;
                            $new->orderType = 0;
                            $new->save();
                            // $i++;
                            return $this->status->retFromFramework($this->status->getCode('OK'), $albumPath);
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                        
                    // }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }
        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }


    // 获取用户名片
    public function getUserCardInfo($uid = 0){
        try {
            $isValid = $this->validator->validate(array('uid'=>$uid));
            if (!$isValid) {
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }
            $user = UserFactory::getInstance($uid);

            $return = array();

            $userInfo = $user->getUserInfoObject()->getData();

            if(empty($userInfo)){
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            $return['uid'] = $userInfo['uid'];
            $return['nickName'] = $userInfo['nickName'];
            $return['avatar'] = $userInfo['avatar'] ? $userInfo['avatar'] : $this->pathGenerator->getFullDefaultAvatarPath();
            $return['richerLevel'] = $userInfo['richerLevel'];
            $return['vipLevel'] = $userInfo['vipLevel'];
            $return['signature'] = $userInfo['signature'];

            $item = $user->getUserItemsObject();
            $result = $item->getUserBadge();
            if ($result['code'] == $this->status->getCode('OK')) {
                $return['badge'] = $result['data'];
            }else{
                $return['badge'] = array();
            }

            $return['isAnchor'] = 0;

            $isAnchor = $this->checkIsAnchor($uid);

            if($isAnchor){
                $return['isAnchor'] = 1;
                $return['fansLevel'] = $userInfo['fansLevel'];
                $return['roomId'] = $isAnchor;
                $return['anchorLevel'] = $userInfo['anchorLevel'];
                $isFansRes = $this->isFans($uid);
                if($isFansRes['code'] == $this->status->getCode('OK')){
                    $return['isFans'] = $isFansRes['data']['result'];
                }else{
                    $return['isFans'] = false;
                }
                $signAnchor = \Micro\Models\SignAnchor::findFirst('uid = ' . $uid);
                if(!empty($signAnchor)){
                    $signData = $signAnchor->toArray();
                    $return['gender'] = $signData['gender'];
                    $return['birthday'] = $signData['birthday'];
                    $location = $signData['location'];
                    if(empty($location)){
                        $location = $this->config->signAnchorCityDefault;
                    }
                    $return['location'] = $this->config->location[$location]['name'];
                }

                $result = $this->di->get('familyMgr')->getFamilyInfoByUid($uid);
                if ($result['code'] == $this->status->getCode('OK')) {
                    $return['familyName'] = $result['data']['shortName'];
                }else{
                    $return['familyName'] = '';
                }

            }

            return $this->status->retFromFramework($this->status->getCode('OK'),$return);

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //检查录像个数
    public function checkVideoNum(){
        try {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $uid = $user->getUid();

            $num = \Micro\Models\Videos::count('status = 0 and uid = ' . $uid);

            $maxNum = $this->config->maxVideoNum;
            if($num >= $this->config->maxVideoNum){
                return $this->status->retFromFramework($this->status->getCode('VIDEO_NUM_IS_LIMITED'),array('hasNum'=>$num,'maxNum'=>$maxNum));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'),array('hasNum'=>$num,'maxNum'=>$maxNum));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //批量删除相册照片 add by 2015/11/10
    public function delMyGalleryImages($ids) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        try {
            if ($ids) {
                $uid = $user->getUid();
                $idArray = explode(',', $ids);
                foreach ($idArray as $key => $val) {
                    if (intval($val)) {
                        $delIdsArr[] = $val;
                    }
                }
                if ($delIdsArr) {
                    $delIdsStr = implode(',', $delIdsArr);
                    $updatesql = "update pre_user_images set status=1 where uid=" . $uid . " and id in(" . $delIdsStr . ")";
                    $this->db->execute($updatesql);
                }
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    //修改照片墙照片顺序 add by 2015/11/11
    public function sortMyGalleryImages($ids) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        try {
            if ($ids) {
                $uid = $user->getUid();
                $idArray = explode(',', $ids);//转成数组
                foreach ($idArray as $key => $val) {
                    if (intval($val)) {//过滤
                        $idsArr[] = $val;//存放id的数组
                    }
                }
                if ($idsArr) {
                    $i = 1;//顺序
                    foreach ($idsArr as $val) {
                        $cashArr[] = "when " . $val . " then " . $i;
                        $i++;
                    }
                    $cashStr=implode(' ', $cashArr); //sql语句拼接
                    $ids = implode(',', $idsArr); //需要修改的id
                    $updatesql = "update pre_user_images set orderType=CASE id " . $cashStr. " END where uid=" . $uid . " and id in(" . $ids . ")";
                    $this->db->execute($updatesql);
                }
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取录像列表
    public function getRECList(){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {

            //获取房间
            $isOpenVideo = 0;
            $roomData = \Micro\Models\Rooms::findFirst('uid = ' . $uid);
            if(empty($roomData)){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            $isOpenVideo = $roomData->isOpenVideo;

            $res = \Micro\Models\Videos::find(
                'status = 0 and uid = ' . $uid . ' order by createTime asc'
            );

            $data = array();
            if(!empty($res)){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['streamName'] = $v->streamName ? ($this->config->RECInfo->url . $v->streamName . $this->config->RECInfo->format) : '';
                    $tmp['isUsing'] = $v->isUsing;
                    $tmp['publicTime'] = $v->publicTime;
                    $tmp['createTime'] = $v->createTime;
                    $tmp['videoPic'] = $v->videoPic ? $v->videoPic : '';
                    $data[] = $tmp;
                    unset($tmp);
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'isOpenVideo'=>$isOpenVideo,'swfUrl'=>$this->config->url->swfUrl));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //删除录像
    public function delREC($id = 0){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        $isValid = $this->validator->validate(array('id'=>$id));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $res = \Micro\Models\Videos::findFirst(
                'status = 0 and uid = ' . $uid . ' and id = ' . $id
            );

            //数据是否存在
            if(empty($res)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            //检查是否使用中
            if($res->isUsing == 1){
                return $this->status->retFromFramework($this->status->getCode('VIDEO_IS_USING'));
            }

            /*//检查是否在保存期限内
            $saveTimes = time() - $res->createTime;
            if($saveTimes <= $this->config->RECSavePeriod){
                return $this->status->retFromFramework($this->status->getCode('VIDEO_IS_IN_SAVE_PERIOD'));
            }*/

            // 修改数据
            $res->status = 1;
            $res->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //修改录像开关状态
    public function setRECStatus($status = 0){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            //查询房间
            $roomData = \Micro\Models\Rooms::findFirst('uid = ' . $uid);
            if(empty($roomData)){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            if($status == 1){
                $res = \Micro\Models\Videos::findFirst(
                    'isUsing = 1 and status = 0 and uid = ' . $uid
                );
                if(empty($res)){
                    return $this->status->retFromFramework($this->status->getCode('PLEASE_CHOOSE_PLAY_VIDEO'));
                }
            }
            
            //修改数据
            $roomData->isOpenVideo = $status;
            $roomData->save();

            /*if($roomData->liveStatus == 0){
                //广播
            }*/

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 修改播放录像
    public function setPlayREC($id = 0, $status = 0){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        $isValid = $this->validator->validate(array('id'=>$id));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            //查询房间
            $roomData = \Micro\Models\Rooms::findFirst('uid = ' . $uid);
            if(empty($roomData)){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            if($roomData->isOpenVideo == 1 && $status == 0){
                return $this->status->retFromFramework($this->status->getCode('PLEASE_CLOSE_PLAY_VIDEO'));
            }

            //修改数据
            if($status == 1){
                $sql = 'update \Micro\Models\Videos set isUsing = 0 where uid = ' . $uid;
                $query = $this->modelsManager->createQuery($sql);
                $query->execute();
            }

            $sql = 'update \Micro\Models\Videos set isUsing = ' . $status . ' where uid = ' . $uid . ' and id = ' . $id;
            $query = $this->modelsManager->createQuery($sql);
            $query->execute();

            /*if($roomData->liveStatus == 0){
                //广播
            }*/

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
