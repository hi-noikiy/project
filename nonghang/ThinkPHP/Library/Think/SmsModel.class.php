<?php
namespace Think;
class SmsModel{	
	protected $sms = '';
	public function __construct($config){

		if ($config['smsType'] == 'weiw') {
			$this->sms =  new \Think\Sms\Weiw($config);
		}elseif ($config['smsType'] == 'ihyi') {
			$this->sms = new \Think\Sms\Ihyi($config);
		}
	}

	public function sendSms($mobile, $content)
	{
		return $this->sms->sendSms($mobile, $content);
	}
}
?>