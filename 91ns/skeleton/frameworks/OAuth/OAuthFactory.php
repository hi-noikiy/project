<?php

namespace Micro\Frameworks\OAuth;

use Phalcon\DI\FactoryDefault;

use Micro\Frameworks\OAuth\QQAuth;
use Micro\Frameworks\OAuth\SinaAuth;
use Micro\Frameworks\OAuth\DouziAuth;
use Micro\Frameworks\OAuth\WeixinAuth;

class OAuthFactory 
{
    protected $di;
    protected $config;
    protected $session;

	public function __construct() 
	{
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->session = $this->di->get('session');
	}

    public function createOAuth($type)
    {
        $oauth = null;
        switch ($type) {
            case 'QQ':
                $oauth = new QQAuth();
                break;
            case 'Sina':
                $oauth = new SinaAuth();
                break;
            case 'douzi':
                $oauth = new DouziAuth();
                break;
			case 'weixin':
	            $oauth = new WeixinAuth();
				break;
			case 'mweixin':
				$oauth = new MWeixinAuth();
				break;
            default:
                $oauth = null;
        }
        $this->session->set('oauth_state', $oauth);
        return $oauth;
    }

    public function getOAuth()
    {
        if($this->di->get('session')->has('oauth_state')) {
            return $this->di->get('session')->get('oauth_state');  
        }
        return null;        
    }
}