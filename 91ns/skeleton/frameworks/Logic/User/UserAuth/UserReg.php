<?php

namespace Micro\Frameworks\Logic\User\UserAuth;
use Phalcon\DI\FactoryDefault;
use Micro\Models\Users;
use Micro\Models\UserInfo;
use Micro\Models\UserProfiles;
use Micro\Frameworks\Activation\Activator;
use Micro\Frameworks\Logic\User\UserFactory;

class UserReg
{

    public static function regUser($userName, $password, $nickName, $telephone='')
    {
        $di = FactoryDefault::getDefault();
        $comm = $di->get('comm');
        $status = $di->get('status');
        $validator = $di->get('validator');
        $userMgr = $di->get('userMgr');
        $postData['username'] = $userName;
        $postData['password'] = $password;

        $regStatus = 1;
        $isValid = $validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $validator->getLastError();
            return $status->retFromFramework($status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户名验证
        if(!ctype_alnum($userName)){
            return $status->retFromFramework($status->getCode('USERNAME_NOT_ALNUM'));
        }

        if(is_numeric($userName) && (empty($telephone) || $userName != $telephone)){
            return $status->retFromFramework($status->getCode('USERNAME_IS_NUMERIC'));
        }

        if(strlen($userName) < 4 || strlen($userName) > 12){
            return $status->retFromFramework($status->getCode('USERNAME_LENGTH_ERROR'));
        }

        if(!empty($nickName)){
            // 检测昵称是否合法
            if(mb_strlen($nickName) > 10 || mb_strlen($nickName) < 2){
                return $status->retFromFramework($status->getCode('USERNAME_LENGTH_ERROR'));
            }

            if(is_numeric($nickName)){
                return $status->retFromFramework($status->getCode('NICKNAME_ALL_NUMBER'));
            }

            $nickName = trim($nickName);
            if(empty($nickName)){
                return $status->retFromFramework($status->getCode('NICKNAME_ALL_SPACE'));
            }

            if($userMgr->checkNickNameExist($nickName)['data']){
                return $status->retFromFramework($status->getCode('NICKNAME_HAS_EXISTS'));
            }
        }

        $result = $comm->userReg($userName, $password);

        if ($result === false) {
            return $status->retFromFramework($status->getCode('CANNOT_CONNECT_CHATSERVER'));
        }

        $errorCode = $result['code'];
        if ($errorCode != 0) {
            return $status->retFromFramework($status->getCode('CHATSERVER_RETURN_ERROR'), $status->genCharServerError($result));
        }

        $accountId = $result['uid'];

       
        $userData = array(
            'accountId' => $accountId,
            'username' => $userName,
            'status' => $regStatus,
            'email' => '',
            'telephone' => $telephone,
            'userType' => 0,
        );

        if(!empty($nickName)){
            $userData['nickName'] = $nickName;
        }

        $resultData = UserReg::initUserData($userData);
        return $status->retFromFramework($resultData['code'], $resultData['data']);
    }
    
  
    /**
     * 手机注册
     *
     * @param $userName
     * @param $password
     * @param $nickName
     */
    public static function regUserByMobile($userName, $password, $nickName, $loginType = 0){
        $di = FactoryDefault::getDefault();
        $userAuth = $di->get('userAuth');
        $session = $di->get('session');
        $status = $di->get('status');
        $config = $di->get('config');
        $roomModule = $di->get('roomModule');
        $result = $userAuth->newUserReg($userName, $password, $nickName, $loginType, 0);
        if ($result['code'] == $status->getCode('OK')) {
            // 输入用户设备信息
            $userDevice = $session->get($config->websiteinfo->mobileauthkey);
            $roomModule->getRoomMgrObject()->setDeviceInfoSession($userDevice);
            return $status->retFromFramework($status->getCode('OK'), $result['data']);
        }

        return $status->retFromFramework($result['code'], $result['data']);
    }

    public static function resendEmail($email){
        return self::sendEmailVerify($email);
    }

    public static function initUserData($userData)
    {
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        $userMgr = $di->get('userMgr');

        try {
            
            //检查是否可注册
            $checkResult=  self::checkReg();
            if ($checkResult['code'] != $status->getCode('OK')) {
                 return $status->retFromFramework($checkResult['code'], $checkResult['data']);
            }
                    
             
            $userArr = array(
                'accountId' => $userData['accountId'],
                'username' => $userData['username'],
                'password' => $userData['password'],
                'status' => $userData['status'],
                'userType' => $userData['userType'],
                'canSetUserName' => $userData['canSetUserName'],
                'canSetPassword' => $userData['canSetPassword'],
                'openId' => $userData['openId']
            );
            $tbl_uid = UserReg::addUser($userArr);
            
            //检查昵称是否存在
            $nicknameResult = $userMgr->checkNickNameExist($userData['nickname']);
            if ($nicknameResult['code'] != $status->getCode('OK')) {
                $userData['nickname'] = $tbl_uid;
            }

            $userInfoArr = array(
                'uid' => $tbl_uid,
                'nickname' => $userData['nickname'],
                'telephone' => $userData['telephone'],
                'avatar' => isset($userData['avatar'])?$userData['avatar']:'',
            );

            UserReg::addUserInfo($userInfoArr);

            $userProfileArr = array(
                'uid' => $tbl_uid,
            );
            UserReg::addUserProfile($userProfileArr);
            
            //渠道信息返回
            $cookies = $di->get('cookies');
            $config = $di->get('config');
            $ns_source = trim($cookies->get($config->websitecookies->utm_source)->getValue());
            if ($ns_source) {
                $utm_medium = trim($cookies->get($config->websitecookies->utm_medium)->getValue());
                $return['ns_source'] = $ns_source;
                $return['utm_medium'] = $utm_medium;
            }
            //是否有推荐人 add by 2015/09/10
            $isRecommend = 0;
            $recommendStr = trim($cookies->get($config->websitecookies->recommendStr)->getValue());
            if ($recommendStr && !$userData['telephone']) {
                $userMgr = $di->get('userMgr');
                $recResult = $userMgr->setPcRecommendLog($recommendStr, $tbl_uid);
                /*if ($recResult['code'] == $this->status->getCode('OK')) {
                    $isRecommend = 1;
                }*/
            }

            //送渠道徽章礼包
            if ($ns_source == 'qipaimi') {
                $giftPackageId=36;
                $user = UserFactory::getInstance($tbl_uid);
                $user->getUserItemsObject()->giveGiftPackage($giftPackageId);
            }
            
            $session = $di->get('session');
            $session->set($config->websitecookies->source_gift, 1);


            //写入注册日志
            $log = new \Micro\Frameworks\Logic\Base\BaseStatistics();
            $log->setRegisterLog($tbl_uid);

            $return['userName'] = $userData['username'];
            $return['uid'] = $tbl_uid;
            $return['accountId'] = $userData['accountId'];
            $return['isRecommend'] = $isRecommend;

            return $status->retFromFramework($status->getCode('OK'), $return);
        }
        catch (\Exception $e) {
            return $status->retFromFramework($status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public static function addUser($userArr)
    {
        try {
            $user = new Users();
            $user->accountId = $userArr['accountId'];
            $user->userName = $userArr['username'];
            $user->canSetUserName = $userArr['canSetUserName'];
            $di = FactoryDefault::getDefault();
            $userAuth = $di->get('userAuth');
            $key = $userAuth->makeCode(8);
            $user->key=$key;
            $user->password = md5($key.$userArr['password']);
            $user->canSetPassword = $userArr['canSetPassword'];
            $user->status = $userArr['status'];
            $user->createTime = time();
            $user->updateTime = time();
            $user->userType = $userArr['userType'];
            $user->internalType = 0;
            $user->isChatRecord = 0;
            $user->manageType = 0;
            $user->openId =$userArr['openId'];
            $user->save();
            return $user->uid;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function addUserInfo($userInfoArr)
    {
        try {
            $userInfo = new UserInfo();
            $userInfo->uid = $userInfoArr['uid'];
            $userInfo->nickName = $userInfoArr['nickname'];
            $userInfo->birthday = 0;
            $userInfo->gender = 0;

            // 默认头像添加
            $di = FactoryDefault::getDefault();
            $pathGenerator = $di->get('pathGenerator');
            $userInfo->avatar = (isset($userInfoArr['avatar']) && $userInfoArr['avatar']) ? $userInfoArr['avatar'] : $pathGenerator->getFullDefaultAvatarPath();

            $userInfo->email = '';
            $userInfo->telephone = $userInfoArr['telephone'];
            $userInfo->signature = "";
            //用户ip所在地
//            $normalLib=  $di->get('normalLib');
//            $ip=$normalLib->getip();
//            $place=$normalLib->getIpPlace($ip);
 //           $userInfo->city = isset($place['city'])?$place['city']:'';
            $userInfo->city='';
            $userInfo->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function addUserProfile($userProfileArr)
    {
        try {
            $userProfiles = new UserProfiles();
            $userProfiles->uid = $userProfileArr['uid'];
            $userProfiles->coin = 0;
            $userProfiles->cash = 0;
            $userProfiles->money = 0;
            $userProfiles->exp1 = 0;
            $userProfiles->exp2 = 0;
            $userProfiles->exp3 = 0;
            $userProfiles->exp4 = 0;
            $userProfiles->exp5 = 0;
            $userProfiles->level1 = 0;
            $userProfiles->level2 = 0;
            $userProfiles->level3 = 0;
            $userProfiles->level4 = 0;
            $userProfiles->level5 = 0;
            $userProfiles->vipExpireTime = 0;
            $userProfiles->level6= 0;//至尊vip
            $userProfiles->vipExpireTime2 = 0;//至尊vip过期时间
            $userProfiles->usefulMoney = 0;
            $userProfiles->isOpenSign = 0;
            $userProfiles->questionId = 0;
            $userProfiles->answer = '';
            $userProfiles->richRatio = 1;//富豪经验值倍数
            $userProfiles->points = 0;//积分
            $userProfiles->save();
            return TRUE;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
     * 邮件链接：发送
     * */
    static public function sendEmailVerify($email)
    {
        $activator = new Activator();
        $activator->sendMail($email, '/user/regMailRetrieve');
    }

    /*
     * 短信验证码：发送
     * */
    static public function sendSmsVerify($telephone, $type, $expireTime = '') {
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        if ($telephone == '15323816908') {//手机号黑名单，临时屏蔽
            return $status->retFromFramework($status->getCode('PARAM_ERROR'));
        }
        
        $normalLib = $di->get('normalLib');
        $ip = $normalLib->getip();
      
 
         /*if ($type == 3 || $type == 8) {//注册、登录
            //ip限制
            $today = strtotime(date("Ymd"));
            $ipcount = \Micro\Models\SmsLog::count("ip='" . $ip . "' and createTime>=" . $today);
            if ($ipcount && $ipcount >= 100) {
                return $status->retFromFramework($status->getCode('USER_CAN_NOT_OPER'));
            }

            //同一个手机号，3分钟内只能发送1次
            $time = time() - 180;
            $telcount = \Micro\Models\SmsLog::count("telephone='" . $telephone . "' and createTime>=" . $time . ' and (type=3 or type=8)');
            if ($telcount && $telcount >= 1) {
                return $status->retFromFramework($status->getCode('AUTH_ERROR'));
            }
        }*/
 
        //短信平台选择 edit by 2015/10/22
        $config = $di->get('config');
        set_time_limit(60);
        if ($config->sendSmsPlatform == 1) {//bomb平台
            return self::bombSendSmsVerify($telephone, $type, $expireTime,$ip);
        } elseif ($config->sendSmsPlatform == 2) {//云通讯平台
            return self::yuntongxunSendSmsVerify($telephone, $type, $expireTime,$ip);
        }
        return $status->retFromFramework($status->getCode('OK'));
    }

    /*
     * 短信验证码：验证
     * */
    static public function smsCodeVerify($smsCode)
    {

        $di = FactoryDefault::getDefault();
        $session = $di->get('session');
        $config = $di->get('config');

        $sessionSmsCode = $session->get($config->websiteinfo->smscodekey);
        if ($sessionSmsCode == $smsCode) {
            return TRUE;
        }
        return FALSE;
    }
    
    
    //查询用户是否频繁注册
    static public function checkReg() {
        $di = FactoryDefault::getDefault();
        $normalLib = $di->get('normalLib');
        $status = $di->get('status');
        $config = $di->get('config');
        $ip = $normalLib->getip();
        //限制条件  IP：连续注册3个，每天封顶500个；3分钟内算连续注册
        $now = time();
        $time = $now - 180;
        $count = \Micro\Models\RegisterLog::count("ip='" . $ip . "' and createTime>" . $time);
        if ($count > 2) {
            return $status->retFromFramework($status->getCode('REG_TOO_OFTEN'));
        }
        //每天封顶500个
        $today = strtotime(date('Y-m-d'));
        $todayCount = \Micro\Models\RegisterLog::count("ip='" . $ip . "' and createTime>" . $today);
        if ($todayCount >= $config->regDayLimit) {
            return $status->retFromFramework($status->getCode('REG_TOO_OFTEN'));
        }
        
        return $status->retFromFramework($status->getCode('OK'));
    }
    
     /*
     * 发送短信（云通讯平台）  add by 2015/10/19
     * */

    static public function yuntongxunSendSmsVerify($telephone, $templateType, $expireTime,$ip='') {
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        $config = $di->get('config');
        if (!isset($expireTime) || !$expireTime) {
            $expireTime = $config->smsExpireTime;
        }

        $activator = new Activator();

        //生成验证码 edit by 2015/10/28
        $smsCode = self::getSmsCode($telephone, $templateType); //先查询数据库是否有未过期的验证码
        if (!$smsCode) {
            $smsCode = $activator->genSMSCode(); //生成随机验证码
        }
 
        //参数
        $param = null;
        $smsCode && $param[] = $smsCode; //验证码
        $expireTime && $param[] = floor($expireTime / 60); //验证码有效期 转化成分钟
        $datas = $param;

        try {
            //发送短信
            $result = $activator->yuntongxunSendSms($telephone, $templateType, $datas);
            //var_dump($result);

            $smsResult = $result['result'];
            if ($smsResult == null) {
                $resultcode = -1;
            } else {
                $resultcode = $smsResult->statusCode;
            }
            $sendstatus = $resultcode === '000000' ? 1 : 0;
            $sidType = $result['sidType'];
            $templateId = $result['templateId'];

            $userAuth = $di->get('userAuth');
            $user = $userAuth->getUser();
            $uid = 0;
            if ($user != NULL) {
                $uid = $user->getUid();
            }

            //写入数据库
            $log = new \Micro\Models\SmsLog();
            $log->uid = $uid;
            $log->telephone = $telephone;
            $log->content = $templateId;
            $log->captcha = $smsCode;
            $log->type = $templateType;
            $log->sidType = $sidType;
            $log->resultcode = $resultcode;
            $log->createTime = time();
            $log->status = $sendstatus;
            $log->expireTime = time() + $expireTime;
            $log->ip=$ip;
            $log->save();

            if (!$sendstatus) {//短信发送失败
                return $status->retFromFramework($status->getCode('SMS_CODE_NO_SEND'), $smsResult->statusMsg);
            }
            if ($templateType == $config->sms_template->getPassword) {
                $return['smsCode'] = $smsCode;
            } else {
                $return = array();
            }

            return $status->retFromFramework($status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $status->retFromFramework($status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //验证短信验证码是否正确 add by 2015/10/20
    static public function checkSmsCaptcha($telephone, $templateType, $captcha) {
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        if (!$captcha || !$telephone) {
            return $status->retFromFramework($status->getCode('SECURITY_CODE_ERROR'));
        }
        try {
            $info = \Micro\Models\SmsLog::findfirst("telephone='" . $telephone . "' and type=" . $templateType . " and status>0 order by id desc");
            
            if ($info == false) {//数据不存在
                return $status->retFromFramework($status->getCode('SECURITY_CODE_ERROR'));
            }

            if ($info->expireTime < time() || $info->status != 1) {//验证码已过期
                return $status->retFromFramework($status->getCode('SMSCODE_IS_TIME_OUT'));
            }
            if ($info->captcha != $captcha) {//验证码输入错误
                return $status->retFromFramework($status->getCode('SECURITY_CODE_ERROR'));
            }
            //修改状态
            $info->status = 2;
            $info->save();
            return $status->retFromFramework($status->getCode('OK'));
        } catch (\Exception $e) {
            return $status->retFromFramework($status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    // bomb短信平台 edit by 2015/10/22
    static public function bombSendSmsVerify($telephone, $templateType, $expireTime,$ip='') {
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        $config = $di->get('config');
        
        if (!isset($expireTime) || !$expireTime) {
            $expireTime = $config->smsExpireTime;
        }
 
        $activator = new Activator();
        //生成验证码 edit by 2015/10/28
        $smsCode = self::getSmsCode($telephone, $templateType);//先查询数据库是否有未过期的验证码
        if (!$smsCode) {
            $smsCode = $activator->genSMSCode();//生成随机验证码
        }
        $param = array('0' => $smsCode);
        $content = $activator->smsTemplate($templateType, $param);
        $smsresult = $activator->bmobSendSms($telephone, $content);
        $resultcode = isset($smsresult['smsId']) ? $smsresult['smsId'] : $smsresult['code'];
        $sendstatus = 0;
        if ($resultcode != 10010) {
            $sendstatus = 1;
        }

        $userAuth = $di->get('userAuth');
        $user = $userAuth->getUser();
        $uid = 0;
        if ($user != NULL) {
            $uid = $user->getUid();
        }
        //写入数据库
        $log = new \Micro\Models\SmsLog();
        $log->uid = $uid;
        $log->telephone = $telephone;
        $log->content = $content;
        $log->captcha = $smsCode;
        $log->type = $templateType;
        $log->resultcode = $resultcode;
        $log->createTime = time();
        $log->sidType = 1000;
        $log->status = $sendstatus;
        $log->expireTime = time() + $expireTime;
        $log->ip = $ip;
        $log->save();


        if ($resultcode == 10010) {//该手机号发送短信达到限制(一天一个应用给同一手机号发送短信不能超过10条)
            return $status->retFromFramework($status->getCode('SEND_MESSAGE_LIMITED'));
        }
        if ($templateType == $config->sms_template->getPassword) {
            $return['smsCode'] = $smsCode;
        } else {
            $return = array();
        }
        return $status->retFromFramework($status->getCode('OK'), $return);
    }
    
    //查询验证码，add by 2015/10/27
    static public function getSmsCode($telephone, $templateType) {
        $di = FactoryDefault::getDefault();
        try {
            $now = time();
            $captcha = '';
            //查询数据库是否有未过期的验证码
            $sql = "select id,captcha,status from pre_sms_log where telephone=" . $telephone . " and type=" . $templateType . " and status>0 and expireTime>" . $now.' order by id desc';
            $connection = $di->get("db");
            $res = $connection->fetchOne($sql);
            if ($res&&$res['status']==1) {//验证码未过期 并且未被验证过
                $captcha = $res['captcha'];
            }
            return $captcha;
        } catch (\Exception $e) {
            $logger = $di->get('logger');
            $logger->error('makeSmsCode error: errorMessage = ' . $e->getMessage());
            return;
        }
    }

}