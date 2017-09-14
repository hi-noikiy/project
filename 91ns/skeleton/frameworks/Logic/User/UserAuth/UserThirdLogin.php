<?php

namespace Micro\Frameworks\Logic\User\UserAuth;

use Micro\Frameworks\Logic\User\UserAuth;
use Micro\Frameworks\Logic\User\UserAuth\ThirdParty\AuthFactory;
use Micro\Frameworks\Logic\User\UserAuth\ThirdParty\ThirdPartyUser;
use Micro\Frameworks\Logic\User\UserAuth\UserLogin;
use Micro\Frameworks\Logic\User\UserAuth\UserReg;
use Micro\Frameworks\Logic\User\UserData\UserInfo;
use Micro\Frameworks\Logic\User\UserMgr;
use Micro\Models\Users;
use Phalcon\DI\FactoryDefault;
use Micro\Models\BaseConfigs;

class UserThirdLogin{

    static public function request($url, $params = array()){

        $ch = curl_init();     
        $curl_opts = array(
            CURLOPT_CONNECTTIMEOUT  => 3,
            CURLOPT_TIMEOUT         => 5,
            CURLOPT_USERAGENT       => 'baidu-apiclient-php-2.0',
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_FOLLOWLOCATION  => false,
        );
        if (stripos($url, 'https://') === 0) {
            $curl_opts[CURLOPT_SSL_VERIFYPEER] = false;
        }   
        $query = http_build_query($params, '', '&');
        $delimiter = strpos($url, '?') === false ? '?' : '&';
        $curl_opts[CURLOPT_URL] = $url . $delimiter . $query;
        $curl_opts[CURLOPT_POST] = false;
     
        curl_setopt_array($ch, $curl_opts);
        $result = curl_exec($ch);
 
        if ($result === false) {
            curl_close($ch);
            return false;
        } 
        else if (empty($result)) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code != 200) {
                curl_close($ch);
                return false;
            }
        }  
        curl_close($ch);
        return $result;
    }

    /**
     * 需要放到配置中,或者放到BaseCode中
     */
    static public function getUserType($mediaType){
        switch($mediaType){
            case 'qqdenglu':
                return 1;
            case 'sinaweibo':
                return 2;
            case 'douzi':
                return 3;
        }
        return 0;
    }

    static public function share($access_token)
    {
        $url = 'https://openapi.baidu.com/social/api/2.0/share';
        //分享的网页信息
        $share_url = 'http://developer.baidu.com/soc/share';
        //分享内容描述
        $content = '使用百度社会化服务进行分享噢';
        //分享配图的url
        $pic_url = 'http://developer.baidu.com/static/assets/v3/banner_default.jpg';
        $params = array(
            'access_token'    =>  $access_token,
            'url'             =>  $share_url,
            'content'         =>  $content,
            'pic_url'         =>  $pic_url,
        );
     
        $ret = self::request($url, $params);
        do {
            if(!$ret) {
                break;
            }
            $ret = json_decode($ret, true);
            if(isset($ret['error_code'])) {
                break;
            }
            var_export($ret);
            echo "分享成功鸟~~";
            exit(0);
        } while (false);
        echo "噢噢，分享失败鸟";
    }

    static public function getUserInfo($access_token)
    {
        $url = 'https://openapi.baidu.com/social/api/2.0/user/info';
        $params = array(
            'access_token'    =>  $access_token
        );

        $ret = self::request($url, $params);
        if(!$ret) {
            return null;
        }

        $ret = json_decode($ret, true);
        if(isset($ret['error_code'])) {
            return null;
        }
        return $ret;
    }

    static public function getUserHeader($access_token)
    {
        $userInfo = self::getUserInfo($access_token);
        if($userInfo){
            return $userInfo['headurl'];
        }
        return '';
    }

    static public function loginCallback($state, $code) {
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        $config = $di->get('config');
        $session = $di->get('session');
        $oauth = $di->get('oauth');
            
        $myAuth = $oauth->getOAuth();
        if ($myAuth != null) {
            $access = $myAuth->accessToken($state, $code);
            if ($access) {
                $ret = $myAuth->getUserInfo();
                $uid = $ret['uid'];
                $type = $ret['type'];
                 if (strlen($type) == 0 || strlen($uid) == 0) {
                    return $status->retFromFramework($status->getCode('THIRD_LOGIN_RETURN_ERROR'), array('info' => 'THIRD_LOGIN_RETURN_ERROR'));
                }
                try {
                    $result = self::newLoginCallbackForSame($type, $uid, $ret);
                    if ($result['code'] != $status->getCode('OK')) {//登录失败
                        return $status->retFromFramework($result['code'], $result['data']);
                    }

                    //登录成功
                    $session->set($config->websiteinfo->user_auto_login_password, $result['data']['password']);
                    $session->set($config->websiteinfo->user_auto_login_username, $result['data']['username']);
                    // 走正常登录流程
                    $loginResult = UserLogin::setLoginStatus($result['data']['uid']);
                    if ($loginResult['code'] == $status->getCode('OK')) {
                        $result['data']['ns_source'] = $loginResult['data']['ns_source']; //渠道
                        $result['data']['utm_medium'] = $loginResult['data']['utm_medium'];
                    }

                    $result['data']['type'] = $type;    //google 分析使用
                    //$data['state'] = $ret['state'];
                    return $status->retFromFramework($result['code'], $result['data']);
                } catch (\Exception $e) {
//                    $err->error('【UserThirdLogin】 error : loginCallback DB_OPER_ERROR : '.$e->getMessage());
                    return $status->retFromFramework($status->getCode('DB_OPER_ERROR'), array('info' => $e->getMessage()));
                }
            }
        }
        return $status->retFromFramework($status->getCode('THIRD_LOGIN_RETURN_ERROR'), array('info'=>'THIRD_LOGIN_RETURN_ERROR'));
    }

    static function loginCallbackFromMobile($type, $openId, $nickName, $avatar){
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        $session = $di->get('session');
        $config = $di->get('config');
        $roomModule = $di->get('roomModule');
        $ret['name'] = $nickName;
        $ret['image'] = $avatar;

        $result = self::newLoginCallbackForSame($type, $openId, $ret);
        if ($result['code'] == $status->getCode('OK')) {
            $uid = $result['data']['uid'];

            //app第三方登录不再直接登录 edit by 2015/09/28
            if (!$result['data']['telephone']) {//未绑定手机
                $session->set($config->websiteinfo->user_third_login_uid, $uid);
            }

            $result['data']['isRec'] = 1;
            
            // 走正常登录流程
            $tmpRes = UserLogin::setLoginStatus($uid);
            if($tmpRes['code'] == $status->getCode('OK')){
                $result['data']['isRec'] = $tmpRes['data']['isRec'];
            }
            $userDevice = $session->get($config->websiteinfo->mobileauthkey);
            $roomModule->getRoomMgrObject()->setDeviceInfoSession($userDevice);
            return $status->retFromFramework($status->getCode('OK'), $result['data']);
        }else{
            return $status->retFromFramework($result['code'], $result['data']);
        }
    }

    static function loginCallbackForSame($type, $openId, $ret){
        $di = FactoryDefault::getDefault();
        $comm = $di->get('comm');
        $status = $di->get('status');
        $config = $di->get('config');
        $session = $di->get('session');
        $validator = $di->get('validator');
        $oauth = $di->get('oauth');
        $err = $di->get('logger');
        $openId = $openId.'_'.$type.'_xhb'; //平台type+平台id
        try {
            $count = Users::count("userName = '".$openId."'");
            
            $nickName = $ret['name'];
            //$uid = $ret['uid'];
            //$type = $ret['type'];
            $avatar = $ret['image'];

            //$nickName = $openId; // 昵称，需要传
            //$avatar = ''; // 头像
            $first=0;//是否首次登录
            $resultData = array();
            if($count == 0){
                //统一用一个接口来注册，后面要合下
                $result = $comm->QQReg($openId);

                if ($result === FALSE) {
//                    $err->error('【UserThirdLogin】 error : loginCallback CANNOT_CONNECT_CHATSERVER');
                    return $status->retFromFramework($status->getCode('CANNOT_CONNECT_CHATSERVER'), array('info'=>'CANNOT_CONNECT_CHATSERVER'));
                }

                $errorCode = $result['code'];
                if ($errorCode != 0) {
//                    $err->error('【UserThirdLogin】 error : loginCallback CHATSERVER_RETURN_ERROR code : '.$result['code']);
                    return $status->retFromFramework($status->getCode('CHATSERVER_RETURN_ERROR'), array('info'=>'CHATSERVER_RETURN_ERROR:'.$result['code']));
                }

                $accountId = $result['uid'];

                // 走注册的自动刷表流程, status ?= 0
                $userData = array(
                    'accountId' => $accountId,
                    'username' => $openId,
                    'email' => '',
                    'telephone' => '',
                    'status' => 1,
                    'userType' => self::getUserType($type),
                );

                $resultData = UserReg::initUserData($userData);
                if ($resultData['code'] != $status->getCode('OK')) {
                    return $status->retFromFramework($resultData['code'], array('info'=>$resultData['data']));
                }

                $userWebUid = $resultData['data'];
                //设置该用户的nickname
                // 判断昵称是否存在
                //$uid = UserMgr::getUidByAccountId($accountId);
                $postData['accountid'] = $accountId;
                $isValid = $validator->validate($postData);
                if (!$isValid) {
                    $errorMsg = $validator->getLastError();
//                    $err->error('【UserThirdLogin】 error : loginCallback VALID_ERROR : '.$errorMsg);
                    return $status->retFromFramework($status->getCode('VALID_ERROR'), array('info'=>$errorMsg));
                }

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
                        $res = \Micro\Models\UserInfo::count("nickName='" . $nickName . "'");
                        if ($res > 0 || mb_strlen($nickName) > 10 || mb_strlen($nickName) < 2) {
                            $nickName = $userInfo->uid;
                        }

                        $user = \Micro\Models\UserInfo::findFirst("uid =" . $userInfo->uid);
                        $user->nickName = $nickName;
                        $user->avatar = $avatar;
                        $user->save();
                    }
                }
                $first=1;//注册
            }else{
                //统一用一个接口来登录，后面要合下
                $result = $comm->QQLogin($openId);

                if ($result === FALSE) {
//                    $err->error('【UserThirdLogin】 error : loginCallback CANNOT_CONNECT_CHATSERVER ');
                    return $status->retFromFramework($status->getCode('CANNOT_CONNECT_CHATSERVER'), array('info'=>'CANNOT_CONNECT_CHATSERVER'));
                }

                $errorCode = $result['code'];
                if ($errorCode != 0) {
//                    $err->error('【UserThirdLogin】 error : loginCallback CHATSERVER_RETURN_ERROR ');
                    return $status->retFromFramework($status->getCode('CHATSERVER_RETURN_ERROR'), array('info'=>'CHATSERVER_RETURN_ERROR:'.$result['code']));
                }

                $accountId = $result['uid'];

            }

            // 获得uid
            $parameters = array(
                "accountId" => $accountId,
            );

            $uidres = Users::findFirst(array(
                "accountId = :accountId:",
                "bind" => $parameters,
                "columns" => "uid"
            ));

            $userWebUid = $uidres->uid;
            $resultData['data']['accountId'] = $accountId;
            $resultData['data']['openId'] = $openId;
            $resultData['data']['uid'] = $userWebUid;
            $resultData['data']['first'] = $first;
            //$data = array('accountId' => $accountId, 'openId' => $openId, 'uid' => $userWebUid, 'first' => $first);
            return $status->retFromFramework($status->getCode('OK'), $resultData['data']);
//            $resultData['data']['openId']=$openId;
//            $resultData['data']['first']=$first;
//            return $status->retFromFramework($resultData['code'], $resultData['data']);
        }
        catch (\Exception $e) {
//            $err->error('【UserThirdLogin】 error : loginCallback DB_OPER_ERROR : '.$e->getMessage());
            return $status->retFromFramework($status->getCode('DB_OPER_ERROR'), array('info'=>'DB_OPER_ERROR'));
        }
    }

    
    //第三方登录回调处理 add by 2015/07/20
    static function newLoginCallbackForSame($type, $openId, $ret) {
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        $config = $di->get('config');
        $userMgr = $di->get('userMgr');
        $userAuth = $di->get('userAuth');
        try {
            $userType = $config->userType[$type];
            $userInfo = Users::findfirst("userType=" . $userType . " and openId = '" . $openId . "'");
            if ($userInfo && $userInfo->uid) {//用户已注册
                if ($userInfo->status != 1) {//被禁用
                    return $status->retFromFramework($status->getCode('USER_NOT_ACTIVE'));
                }
                $userInfos=  \Micro\Models\UserInfo::findfirst("uid=".$userInfo->uid);
                $first = 0; //不是第一次登录
                $resultData['data']['accountId'] = $userInfo->accountId;
                $resultData['data']['openId'] = $userInfo->openId;
                $resultData['data']['uid'] = $userInfo->uid;
                $resultData['data']['password'] = $userInfo->password;
                $resultData['data']['username'] = $userInfo->userName;
                $resultData['data']['first'] = $first;
                $resultData['data']['telephone'] = $userInfos->telephone;
                return $status->retFromFramework($status->getCode('OK'), $resultData['data']);
            }

            //用户是首次登录
            $first = 1;
            $nickName = $ret['name']; //昵称
            $avatar = $ret['image']; //头像
            $username = $userAuth->setRandCode(10);//用户名
            $rand = $userAuth->setRandCode(6);
            $password = md5($rand); //密码

            //昵称新规则--王涛
            if (mb_strlen($nickName) > 10){
        		$nickName=mb_substr($nickName, 0,10,'utf-8');
            }
            $namelen=mb_strlen($nickName);
            while($namelen>1){
            	$nicknameResult = $userMgr->checkNickNameExist($nickName);
            	if($nicknameResult['code'] != $status->getCode('OK')){
            		--$namelen;
            		$nickName=mb_substr($nickName, 0,$namelen,'utf-8');
            	}else{
            		break;
            	}
            }
            unset($namelen);
            if(mb_strlen($nickName) < 2){ //使用新规则
            	$bc=BaseConfigs::findfirst('id=30');
            	$flag=true;
            	while($flag){
            		$bc->value += 1;
            		$nickName = '粉丝';
            		$b=0;
            		$bcl = strlen($bc->value);
            		while($b<(5-$bcl)){
            			$nickName.='0';
            			++$b;
            		}
            		$nickName.=$bc->value;
            		$nicknameResult = $userMgr->checkNickNameExist($nickName);
            		if($nicknameResult['code'] == $status->getCode('OK')){
            			$bc->save();
            			break;
            		}
            	}
            	unset($b,$bcl,$bc);
            }
           
           /* if (mb_strlen($nickName) > 10 || mb_strlen($nickName) < 2 || $nicknameResult['code'] != $status->getCode('OK')) {//昵称已存在、或者昵称长度不符合
                $nickName = $userAuth->setRandCode(8); //昵称
            }*/
            
            $canSetUserName = 1; //第三方注册的用户可修改用户名
            $canSetPassword = 1; //第三方注册的用户可设置密码
            $userData = array(
                'accountId' => time(), ///TESTTESTESTSET、、、、、、、、、、、、、
                'username' => $username,
                'password' => $password,
                'nickname' => $nickName,
                'avatar' => $avatar,
                'openId' => $openId,
                'telephone' => '',
                'status' => 1,
                'userType' => $config->userType[$type],
                'canSetUserName'=>$canSetUserName,
                'canSetPassword'=>$canSetPassword,
            );

            $resultData = UserReg::initUserData($userData);
            if ($resultData['code'] != $status->getCode('OK')) {//注册失败
                return $status->retFromFramework($resultData['code'], array('info' => $resultData['data']));
            }
            //注册成功
            $resultData['data']['accountId'] = $resultData['data']['accountId'];
            $resultData['data']['openId'] = $openId;
            $resultData['data']['uid'] = $resultData['data']['uid'];
            $resultData['data']['password'] = $password;
            $resultData['data']['username'] = $username;
            $resultData['data']['first'] = $first;
            $resultData['data']['isRecommend'] = $resultData['data']['isRecommend'];
            $resultData['data']['telephone'] = '';

            return $status->retFromFramework($status->getCode('OK'), $resultData['data']);
        } catch (\Exception $e) {
            return $status->retFromFramework($status->getCode('DB_OPER_ERROR'), array('info' => $e->getMessage()));
        }
    }

    //获取第三方登录密码
    static function getThirdUserPassword($userName){
        return md5($userName.'thirdLoginPassword');
    }

    //第三方登录
    static function userLogin($userName, $password){
        return self::getThirdUserPassword($userName) == $password;
    }

    /*
     * 豆子登录key。规则
     */
    static function checkSign($userID, $timestamp, $sign){
        $key = FactoryDefault::getDefault()->get('config')->douziKey;
        return $sign === md5($userID.md5($userID.$key).$key.$timestamp);
    }

    /*
     * 用户登录(豆子)
     */
    static function userLoginByDZ($userID, $timestamp, $sign){
        $di = FactoryDefault::getDefault();
        $status = $di->get('status');
        $err = $di->get('logger');
        $comm = $di->get('comm');
        $config = $di->get('config');
        $validator = $di->get('validator');
        $oauth = $di->get('oauth');

        if(!self::checkSign($userID, $timestamp, $sign)){
            $err->error('【douzi login】 error : userLoginByDZ sign error');
            return $status->retFromFramework($status->getCode('DOUZI_KEY_ERROR'), array('info'=>'DOUZI_KEY_ERROR'));
        }

        $oauth->createOAuth('douzi');
        $myAuth = $oauth->getOAuth();
        if($myAuth == null){
            $err->error('【UserThirdLogin】 error : loginCallback GET_USER_MESSAGE_ERROR');
            return $status->retFromFramework($status->getCode('CANNOT_CONNECT_CHATSERVER'), array('info'=>'GET_USER_MESSAGE_ERROR'));
        }
        $ret = $myAuth->getUserInfo($userID, $timestamp, $sign);
        if(!$ret){
            $err->error('【UserThirdLogin】 error : loginCallback GET_USER_MESSAGE_ERROR');
            return $status->retFromFramework($status->getCode('CANNOT_CONNECT_CHATSERVER'), array('info'=>'GET_USER_MESSAGE_ERROR'));
        }
        $nickName = $ret['name'];
        $uid = $ret['uid'];
        $type = $ret['type'];
        $avatar = $ret['image'];
        $openId = $uid.'_'.$type.'_xhb'; //平台type+平台id
        try {
            $count = Users::count("userName = '".$openId."'");
            if($count == 0){
                //统一用一个接口来注册，后面要合下
                $result = $comm->QQReg($openId);

                if ($result === FALSE) {
                    $err->error('【UserThirdLogin】 error : loginCallback CANNOT_CONNECT_CHATSERVER');
                    return $status->retFromFramework($status->getCode('CANNOT_CONNECT_CHATSERVER'), array('info'=>'CANNOT_CONNECT_CHATSERVER'));
                }

                $errorCode = $result['code'];
                if ($errorCode != 0) {
                    $err->error('【UserThirdLogin】 error : loginCallback CHATSERVER_RETURN_ERROR code : '.$result['code']);
                    return $status->retFromFramework($status->getCode('CHATSERVER_RETURN_ERROR'), array('info'=>'CHATSERVER_RETURN_ERROR:'.$result['code']));
                }

                $accountId = $result['uid'];

                // 走注册的自动刷表流程, status ?= 0
                $userData = array(
                    'accountId' => $accountId,
                    'username' => $openId,
                    'email' => '',
                    'telephone' => '',
                    'status' => 1,
                    'userType' => self::getUserType($type),
                );

                $resultData = UserReg::initUserData($userData);
                if ($resultData['code'] != $status->getCode('OK')) {
                    return $status->retFromFramework($resultData['code'], array('info'=>$resultData['data']));
                }

                //设置该用户的nickname
                // 判断昵称是否存在
                //$uid = UserMgr::getUidByAccountId($accountId);
                $postData['accountid'] = $accountId;
                $isValid = $validator->validate($postData);
                if (!$isValid) {
                    $errorMsg = $validator->getLastError();
                    $err->error('【UserThirdLogin】 error : loginCallback VALID_ERROR : '.$errorMsg);
                    return $status->retFromFramework($status->getCode('VALID_ERROR'), array('info'=>$errorMsg));
                }

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
                        $res = \Micro\Models\UserInfo::count("nickName='" . $nickName . "'");
                        if ($res > 0 || mb_strlen($nickName) > 10 || mb_strlen($nickName) < 3) {
                            $nickName = $userInfo->uid;
                        }

                        $user = \Micro\Models\UserInfo::findFirst("uid =" . $userInfo->uid);
                        $user->nickName = $nickName;
                        $user->avatar = $avatar;
                        $user->save();
                    }
                }
            }else{
                //统一用一个接口来登录，后面要合下
                $result = $comm->QQLogin($openId);

                if ($result === FALSE) {
                    $err->error('【UserThirdLogin】 error : loginCallback CANNOT_CONNECT_CHATSERVER ');
                    return $status->retFromFramework($status->getCode('CANNOT_CONNECT_CHATSERVER'), array('info'=>'CANNOT_CONNECT_CHATSERVER'));
                }

                $errorCode = $result['code'];
                if ($errorCode != 0) {
                    $err->error('【UserThirdLogin】 error : loginCallback CHATSERVER_RETURN_ERROR ');
                    return $status->retFromFramework($status->getCode('CHATSERVER_RETURN_ERROR'), array('info'=>'CHATSERVER_RETURN_ERROR:'.$result['code']));
                }

                $accountId = $result['uid'];
            }
            // 走正常登录流程
            UserLogin::setLoginStatus($accountId);

            $data['state'] = $state;
            return $status->retFromFramework($status->getCode('OK'));
        }
        catch (\Exception $e) {
            $err->error('【UserThirdLogin】 error : loginCallback DB_OPER_ERROR : '.$e->getMessage());
            return $status->retFromFramework($status->getCode('DB_OPER_ERROR'), array('info'=>'DB_OPER_ERROR'));
        }
        return $status->retFromFramework($status->getCode('THIRD_LOGIN_RETURN_ERROR'), array('info'=>'THIRD_LOGIN_RETURN_ERROR'));
    }
}