<?php

namespace Micro\Frameworks\Geetest;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Geetest\GeetestLib;

class Manager
{
	protected $di;
    protected $config;

	public function __construct() {
		$this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
	}


	public function getGeetest()
	{
		$geetest = new GeetestLib();
		$geetest->set_captchaid($this->config->geetest->appId);
		if ($geetest->register()) {
			return $geetest->get_widget("float");//若采用弹出式，要添加第二个参数（提交按钮的id）
		} else {
			return '';
		}
	}

	public function checkGeetest($request)
	{		
		if($request->isPost())
		{
			$geetest = new GeetestLib();
			$geetest->set_privatekey($this->config->geetest->appKey);
			if( $request->hasPost('geetest_challenge') && $request->hasPost('geetest_validate') && $request->hasPost('geetest_seccode') ) 
			{
				$challenge = $request->getPost('geetest_challenge');
				$validate = $request->getPost('geetest_validate');
				$seccode = $request->getPost('geetest_seccode');
				$result = $geetest->validate($challenge, $validate, $seccode);
				if ($result == TRUE) {
					return true;
				}
			}
		}
		return false;		
	}


}