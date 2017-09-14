<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午1:00
 *
 */
namespace Micro\Frameworks\OAuth;

use Micro\Frameworks\OAuth\OAuthBase;
use Phalcon\DI\FactoryDefault;

class SinaAuth extends OAuthBase
{
    private $authorizeURL = 'https://api.weibo.com/oauth2/authorize';
    private $tokenURL = 'https://api.weibo.com/oauth2/access_token';
    private $userURL = 'https://api.weibo.com/2/users/show.json';

    public function __construct() 
    {
        $di = FactoryDefault::getDefault();
        $config = $di->get('config');
        $this->clientId = $config->oauth->sina->appId;
        $this->clientSecret = $config->oauth->sina->appKey;
        
        $request = $di->get('request');
        $serverName = $request->getServerName();
        $this->callback = "http://".$serverName.$config->oauth->callback;
        $this->type = 'sinaweibo';             
    }  

    public function getAuthorizeURL($state = NULL) 
    {        
        $this->state = $state; 
        $params = array();
        $params['response_type'] = 'code';
        $params['client_id'] = $this->clientId;
        //$params['redirect_uri'] = urlencode($this->callback);  
        $params['redirect_uri'] = $this->callback;  
        $params['state'] = $state; 
        return $this->authorizeURL . "?" . http_build_query($params);
    }

    public function accessToken($state, $code)
    {
        if($this->isStateValid($state)){
            $params = array();
            $params['grant_type'] = 'authorization_code';
            $params['client_id'] = $this->clientId;
            //$params['redirect_uri'] = urlencode($this->callback);  
            $params['redirect_uri'] = $this->callback;  
            $params['client_secret'] = $this->clientSecret; 
            $params['code'] = $code;

            $response = $this->post($this->tokenURL, $params);
            $result = json_decode($response, true);

            $this->accessToken = $result['access_token']; //save
            $this->openId = $result['uid']; //save
            return true;
        }
        return false;
    }

    public function getUserInfo()
    {   
        $access_token = $this->accessToken;
        if($access_token !=null)
        {
            $openid = $this->openId;            
            $params = array(
                'access_token' => $access_token,
                'uid' => $openid,
            );

            $response = $this->request($this->userURL, $params); 
            $user = json_decode($response, true);   

            //不同平台的适配
            $result = array();
            $result['name'] = $user['name'];
            $result['image'] = $user['avatar_large'];
            $result['type'] = $this->type;
            $result['uid'] = $openid;
            return $result;
        }
        return null;
    }


}
