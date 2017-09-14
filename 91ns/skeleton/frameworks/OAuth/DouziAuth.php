<?php
/**
 * Created by lcn.
 * CreateTime: 15-04-14 16:43
 *
 */
namespace Micro\Frameworks\OAuth;

use Micro\Frameworks\OAuth\OAuthBase;
use Phalcon\DI\FactoryDefault;

class DouziAuth extends OAuthBase
{
    private $authorizeURL = '/static/douzilogin';
    private $tokenURL = 'http://activity.7pmi.com/xiuba/get_user_info.do';
    private $userURL = '';
    private $loginURL = 'http://activity.7pmi.com/xiuba/login_accounts.do';
    private $regURL = 'http://activity.7pmi.com/xiuba/reg_accounts.do';
    private $checkNickNameURL = 'http://activity.7pmi.com/xiuba/check_nick_name_exists2.do';
    private $checkUserExistURL = 'http://activity.7pmi.com/xiuba/check_accounts_exists.do';
    private $codeObject;
    private $status;
    private $config;
    private $pathGenerator;

    public function __construct()
    {
        $di = FactoryDefault::getDefault();
        $this->config = $di->get('config');
        $this->status = $di->get('status');
        //$this->pathGenerator = $di->get('pathGenerator');
        //$this->clientId = $config->oauth->sina->appId;
        $this->clientSecret = $this->config->oauth->douzi->appKey;
        $request = $di->get('request');
        $serverName = $request->getServerName();
        $this->callback = "http://".$serverName.$this->config->oauth->douzi->callback;
        $this->authorizeURL = "http://".$serverName.$this->authorizeURL;
        //$this->callback = $config->oauth->douzi->callback;
        $this->type = 'douzi';
    }
    public function login($username, $password){
        $userID = 0;
        $timestamp = time();
        $key = $this->clientSecret;
        $params = array(
            'userID' => $userID,
            'timestamp' => $timestamp,
            'sign' => md5($userID.md5($userID.$key).$key.$timestamp),
            'accounts' => $username,
            'md5_password' => $password,
        );

        $response = $this->request($this->loginURL, $params);
        $user = json_decode($response, true);

        if($user['result_code'] == 1){
            if($user['userID']){
                $params['userID'] = $user['userID'];
                $params['sign'] = md5($user['userID'].md5($user['userID'].$key).$key.$timestamp);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $params);
        }else{
            return $this->status->retFromFramework($this->status->getCode('DOUZI_ERR'), $user['result_message']);
        }
    }
    public function register($userName, $password, $nickName){
        $userID = 0;
        $timestamp = time();
        $params = array(
            'userID' => $userID,
            'timestamp' => $timestamp,
            'sign' => $this->getKey($userID, $timestamp),
            'accounts' => $userName,
            'nick_name' => $nickName,
            'md5_password' => $password,
        );

        $response = $this->request($this->regURL, $params);
        $user = json_decode($response, true);

        if($user['result_code'] == 1){
            //$userID = $user['reg_userID'];
            //$sign = $this->getKey($userID, $timestamp);
            return $this->status->retFromFramework($this->status->getCode("OK"));
        }else{
            return $this->status->retFromFramework($this->status->getCode("DOUZI_ERR"), $user['result_message']);
        }
    }

    public function checkNickName($nickName){
        $userID = 0;
        $timestamp = time();
        $params = array(
            'userID' => $userID,
            'timestamp' => $timestamp,
            'sign' => $this->getKey($userID, $timestamp),
            'nick_name' => $nickName,
        );

        $response = $this->request($this->checkNickNameURL, $params);
        $user = json_decode($response, true);

        if($user['result_code'] == 1){
            //$userID = $user['reg_userID'];
            //$sign = $this->getKey($userID, $timestamp);
            return true;
        }else{
            return false;
        }
    }
    public function checkUserExist($userName){
        $userID = 0;
        $timestamp = time();
        $params = array(
            'userID' => $userID,
            'timestamp' => $timestamp,
            'sign' => $this->getKey($userID, $timestamp),
            'accounts' => $userName,
        );

        $response = $this->request($this->checkUserExistURL, $params);
        $user = json_decode($response, true);
        if($user['result_code'] == 1){
            //$userID = $user['reg_userID'];
            //$sign = $this->getKey($userID, $timestamp);
            return true;
        }else{
            return false;
        }
    }
    public function getAuthorizeURL($state = NULL)
    {
        $this->state = $state;
        $params = array();
        $params['type'] = '1';
        $params['state'] = $state;
        //$params['state'] = ;
        return $this->authorizeURL . "?" . http_build_query($params);
    }

    public function accessToken($state, $code)
    {
        if($this->isStateValid($state)){
            $this->codeObject = $code; //save
            //$this->openId = $result['uid']; //save
            return $code['sign'] === $this->getKey($code['userID'], $code['timestamp']);
        }
        return false;
    }

    public function getKey($userID, $timestamp){
        $key = $this->clientSecret;
        return md5($userID.md5($userID.$key).$key.$timestamp);
    }
    public function getUserInfo()
    {
        $params = array(
            'userID' => $this->codeObject['userID'],
            'timestamp' => $this->codeObject['timestamp'],
            'sign' => $this->codeObject['sign'],
        );

        $response = $this->request($this->tokenURL, $params);//http://www.91ns.com/user/a
        $user = json_decode($response, true);

        //不同平台的适配
        if($user['result_code'] == 1){
            $result = array();
            $result['name'] = $user['nick_name'];
            $result['image'] = NULL;//$this->pathGenerator->getFullDefaultAvatarPath();
            if (!empty($user['avatar'])) {
                $result['image'] = $user['avatar'];
            }
            $result['type'] = $this->type;
            $result['uid'] = $this->codeObject['userID'];
            return $result;
        }else{
            return false;
        }

    }


}
