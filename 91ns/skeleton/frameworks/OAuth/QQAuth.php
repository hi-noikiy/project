<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午1:00
 *
 */
namespace Micro\Frameworks\OAuth;

use Micro\Frameworks\OAuth\OAuthBase;
use Phalcon\DI\FactoryDefault;

class QQAuth extends OAuthBase
{
    private $authorizeURL = 'https://graph.qq.com/oauth2.0/authorize';
    private $tokenURL = 'https://graph.qq.com/oauth2.0/token';
    private $meURL = 'https://graph.qq.com/oauth2.0/me';
    private $userURL = 'https://graph.qq.com/user/get_user_info';

    public function __construct() 
    {
        $di = FactoryDefault::getDefault();
        $config = $di->get('config');
        $this->clientId = $config->oauth->qq->appId;
        $this->clientSecret = $config->oauth->qq->appKey;

        $request = $di->get('request');
        $serverName = $request->getServerName();
        $this->callback = "http://".$serverName.$config->oauth->callback;
        $this->type = 'qqdenglu';             
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

            $response = $this->request($this->tokenURL, $params);
            $result = array();
            parse_str($response, $result); 

            $this->accessToken = $result['access_token']; //save

            ////////////////////////////////////////////////////////////////

            $params = array(
                'access_token' => $this->accessToken
            );

            $response = $this->request($this->meURL, $params);     
            if (strpos($response, "callback") !== false)
            {
                $lpos = strpos($response, "(");
                $rpos = strrpos($response, ")");
                $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            }
    
            $user = json_decode($response, true);
            $this->openId = $user['openid']; //save
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
                'oauth_consumer_key' => $this->clientId,
                'openid' => $openid,
                'format' => 'json'
            );

            $response = $this->request($this->userURL, $params); 
            $user = json_decode($response, true);            

            //不同平台的适配
            $result = array();
            $result['name'] = $user['nickname'];
            $result['image'] = $user['figureurl_qq_2'];
            $result['type'] = $this->type;
            $result['uid'] = $openid;
            return $result;
        }
        return null;
    }


}
