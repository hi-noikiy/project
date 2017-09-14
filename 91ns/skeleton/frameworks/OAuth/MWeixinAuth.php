<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午1:00
 *
 */
namespace Micro\Frameworks\OAuth;

use Micro\Frameworks\OAuth\OAuthBase;
use Phalcon\DI\FactoryDefault;

class MWeixinAuth extends OAuthBase
{
    //private $authorizeURL = 'https://open.weixin.qq.com/connect/qrconnect';
    private $authorizeURL = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    private $tokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    private $userURL = 'https://api.weixin.qq.com/sns/userinfo';

    public function __construct() 
    {
        $di = FactoryDefault::getDefault();
        $config = $di->get('config');
        $this->clientId = $config->oauth->mweixin->appId;
        $this->clientSecret = $config->oauth->mweixin->appKey;

        $request = $di->get('request');
        $serverName = $request->getServerName();
        //$serverName = 'm.91ns.com';
        $this->callback = "http://".$serverName.$config->oauth->callback;
        $this->type = 'weixin';             
    }  

    public function getAuthorizeURL($state = NULL) 
    {        
        $this->state = $state; 
        $params = array();
        $params['appid'] = $this->clientId;
        $params['redirect_uri'] = $this->callback;
        $params['response_type'] = 'code';
        $params['scope'] = 'snsapi_userinfo';
        $params['state'] = $state;
        $url = $this->authorizeURL . "?" . http_build_query($params)."#wechat_redirect";
        return $url;
    }

    public function accessToken($state, $code)
    {
        if($this->isStateValid($state)){
            $params = array();
            $params['appid'] = $this->clientId;
            $params['secret'] = $this->clientSecret;
            $params['code'] = $code;
            $params['grant_type'] = 'authorization_code';
            $response = $this->request($this->tokenURL, $params);
            $result = json_decode($response, true);            
            $this->accessToken = $result['access_token']; //save
            $this->openId = $result['openid']; //save
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
                'openid' => $openid,
            	'lang'=>'zh_CN',
            );

            $response = $this->request($this->userURL, $params); 
            $user = json_decode($response, true);            

            //不同平台的适配
            $result = array();
            $result['name'] = $user['nickname'];
            $result['image'] = $user['headimgurl'];
            $result['type'] = $this->type;
            $result['uid'] =$openid; //$user['unionid'];//
            return $result;
        }
        return null;
    }


}
