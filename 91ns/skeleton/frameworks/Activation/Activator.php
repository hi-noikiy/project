<?php

namespace Micro\Frameworks\Activation;

use Phalcon\DI\FactoryDefault;

class Activator{

	protected $config;
	protected $activeSMS;
	protected $activeMailer;

	public function __construct()
    {
    	$this->config = FactoryDefault::getDefault()->get('config');    	
    	require_once $this->config->miscellaneous->ucpaas;

        $options['accountsid']=$this->config->active_sms->accountId;
        $options['token']=$this->config->active_sms->token;
        $this->activeSMS = new \Ucpaas($options);

        $transport = \Swift_SmtpTransport::newInstance( $this->config->active_mailer->host,
                                                        $this->config->active_mailer->port,
                                                        $this->config->active_mailer->secure?'ssl':'');
        $transport->setUsername($this->config->active_mailer->username);
        $transport->setPassword($this->config->active_mailer->password);  
        $this->activeMailer = \Swift_Mailer::newInstance($transport);
    }

    public function genSMSCode($length = 6)
    {
        $min = pow(10 , ($length - 1));
        $max = pow(10, $length) - 1;
        return rand($min, $max);
    }

    public function sendSMS($number, $smsCode, $templateId, $minute = 3) {
        $appId = $this->config->active_sms->appId;

        //$param= $this->genSMSCode().','.$minute;
        $param = $smsCode . ',' . $minute;

        $response = $this->activeSMS->templateSMS($appId, $number, $templateId, $param);
        $object = json_decode($response);
        return intval($object->resp->respCode) == 0;
    }

    //Bmob云验证短信发送通道 add by 2015/07/21 
    public function bmobSendSms($telephone, $content) {
        $data['mobilePhoneNumber'] = $telephone;
        $data['content'] = $content;
        $url = 'https://api.bmob.cn/1/requestSms';
        $id = $this->config->bmob_sms->id;
        $key = $this->config->bmob_sms->key;
        $header = array(
            'X-Bmob-Application-Id:' . $id,
            'Content-Type:' . 'application/json',
            'X-Bmob-REST-API-Key:' . $key,
        );
        $data = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        $return=  json_decode($result,1);
        return $return;
    }

    //短信验证码模板 add by 2015/07/21
    public function smsTemplate($templateId, $param = array()) {
        if ($this->config->channelType == 2) {
            $prefix = '秀吧社区';
        } else {
            $prefix = '91NS社区';
        }
        switch ($templateId) {
            case $this->config->sms_template->bindPhone:
                $content = $prefix . "提醒您：您刚申请的绑定手机的验证码为：{$param[0]}, 此码在3分钟内有效，请及时验证，谢谢！";
                break;
            case $this->config->sms_template->unbindPhone:
                $content = $prefix . "提醒您：您刚申请的解绑手机的验证码为：{$param[0]}, 此码在3分钟内有效，请及时验证，谢谢！";
                break;
            case $this->config->sms_template->register:
                $content = $prefix . "提醒您：欢迎注册，您的验证码是：{$param[0]}, 请在3分钟内输入，感谢注册！";
                break;
            case $this->config->sms_template->getPassword:
                $content = $prefix . "提醒您：您刚申请的找回密码已通过，验证码：{$param[0]}, 此码在3分钟内有效，请及时验证，谢谢！";
                break;
            case $this->config->sms_template->accountNotice:
                $content = $prefix . "提醒您：{$param[0]}于{$param[1]}申请结算收益，请尽快登入客服后台系统处理。";
                break;
            case $this->config->sms_template->settleCode:
                $content = $prefix . "提醒您：亲爱的主播，您正在申请佣金提现，需要进行验证，验证码{$param[0]}，此码在3分钟内有效！请勿向任何人提供您收到的短信验证码";
                break;
            case $this->config->sms_template->giveCode:
                $content = $prefix . "提醒您：您的验证码是{$param[0]}，请在3分钟内输入，谢谢";
                break;
            case $this->config->sms_template->smsLogin:
                $content = $prefix . "提醒您：您正在登录，验证码是{$param[0]}，请在3分钟内输入，谢谢";
                break;
            case $this->config->sms_template->setQuestion:
                $content = $prefix . "提醒您：您正在设置密保问题，验证码{$param[0]}，请在3分钟按页面提示提交验证码，切勿将验证码泄露于他人。";
                break;
            case $this->config->sms_template->updateAccount:
                $content = $prefix . "提醒您：亲爱的小伙伴，您正在添加银行卡，需要进行验证，验证码{$param[0]}，(此码在3分钟内有效！请勿向任何人提供您收到的短信验证码)";
                break;
        }
        return $content;
    }

    /*
     * 发送邮件：链接
     * */
	public function sendMail($address, $redirectUrl, $redirectUrlDisplay="", $sync=true)
	{
        $key = $this->config->active_mailer->active_key;
        $data = array(
            'time' => time(),
            'user' => $address,
        );

        $token = base64_encode(json_encode($data));
        $tokenSec = md5($token . $key);
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $redirectUrl  . '?token=' . $token . '&tokenSec=' . $tokenSec;
        if (strlen($redirectUrlDisplay) == 0) {
            $redirectUrlDisplay = $url;
        }
        $body=sprintf(file_get_contents(__DIR__.'/index.html'), $address, $url, $redirectUrlDisplay);

	    if($sync){
	        $message = \Swift_Message::newInstance();
	        $message->setFrom(array($this->config->active_mailer->fromaddr => $this->config->active_mailer->fromname));
	        $message->setTo($address);
	        $message->setSubject( $this->config->active_mailer->subject);
	        $message->setBody($body, 'text/html', 'utf-8');
	        //$message->attach( \Swift_Attachment::fromPath('pic.jpg', 'image/jpeg')->setFilename('rename_pic.jpg'));     
	        $this->activeMailer->send($message); 
	    }
	    else{

	        $data = array('task'    => 'active_mail',
	                      'content' => array('config' => $this->config->active_mailer,
	                                         'mailto'   => $address,
	                                         'mailBody' => $body) );
	        $this->redis->LPUSH('message', json_encode($data));  
	    }
	}

    /*
     * 发送邮件：验证码
     * */
    public function sendCodeMail($address, $code, $sync=true)
    {
        $body=sprintf(file_get_contents(__DIR__.'/mailCode.html'), $code);

        if($sync){
            $message = \Swift_Message::newInstance();
            $message->setFrom(array($this->config->active_mailer->fromaddr => $this->config->active_mailer->fromname));
            $message->setTo($address);
            $message->setSubject( $this->config->active_mailer->subject);
            $message->setBody($body, 'text/html', 'utf-8');
            //$message->attach( \Swift_Attachment::fromPath('pic.jpg', 'image/jpeg')->setFilename('rename_pic.jpg'));
            $this->activeMailer->send($message);
        }
        else{

            $data = array('task'    => 'active_mail',
                    'content' => array('config' => $this->config->active_mailer,
                    'mailto'   => $address,
                    'mailBody' => $body) );
            $this->redis->LPUSH('message', json_encode($data));
        }
    }
    
    /**
     * 发送短信接口--云通讯平台 add by 2015/10/19
     *
     */
    public function yuntongxunSendSms($telephone,$templateType,$datas=array()){
        require_once APP_PATH . "/skeleton/library/SDK/CCPRestSDK.php";
        $serverIP = 'app.cloopen.com'; //生产环境
        //帐号信息
        $configsArr = array(
            1 => array('accountSid' => 'aaf98f89506fc2f001507e1b19aa0acb', 'accountToken' => '3f1fdc092fcc41fb99aad7f4d7bb1a12', 'appId' => '8a48b551506fd26f01508436c0cf3628',
                'templateConfig' => array($this->config->sms_template->bindPhone => 43911,
                    $this->config->sms_template->unbindPhone => 43912,
                    $this->config->sms_template->register => 43916,
                    $this->config->sms_template->getPassword => 43932,
                    $this->config->sms_template->settleCode => 43950,
                    $this->config->sms_template->giveCode => 43951,
                    $this->config->sms_template->smsLogin => 43952,
                    $this->config->sms_template->setQuestion => 43956,
                    $this->config->sms_template->updateAccount => 43958),
            ),
            2 => array('accountSid' => '8a48b551506fd26f01507dddf99f1e40', 'accountToken' => '5fd6b33a9c0f45ff8b836d5e5f396a41', 'appId' => '8a48b551506fd26f01507f8c91cc27de',
                'templateConfig' => array($this->config->sms_template->bindPhone => 43384,
                    $this->config->sms_template->unbindPhone => 43160,
                    $this->config->sms_template->register => 43382,
                    $this->config->sms_template->getPassword => 43383,
                    $this->config->sms_template->settleCode => 43167,
                    $this->config->sms_template->giveCode => 43164,
                    $this->config->sms_template->smsLogin => 43380,
                    $this->config->sms_template->setQuestion => 43166,
                    $this->config->sms_template->updateAccount => 43163),
            ),
            3 => array('accountSid' => '8a48b551506fd26f01507ef58a4b2417', 'accountToken' => '3089cafe3c04421db39ac5e8923c0586', 'appId' => '8a48b551506fd26f015084207e0c3586',
                'templateConfig' => array($this->config->sms_template->bindPhone => 43963,
                    $this->config->sms_template->unbindPhone => 43964,
                    $this->config->sms_template->register => 43965,
                    $this->config->sms_template->getPassword => 43968,
                    $this->config->sms_template->settleCode => 43971,
                    $this->config->sms_template->giveCode => 43974,
                    $this->config->sms_template->smsLogin => 43976,
                    $this->config->sms_template->setQuestion => 43979,
                    $this->config->sms_template->updateAccount => 43980),
            ),
        );
            
        

        //请求端口 
        $serverPort = '8883';
        //REST版本号
        $softVersion = '2013-12-26';

        //查询上一次使用的发送账号类型
        $today = strtotime(date("Ymd"));
        $res = \Micro\Models\SmsLog::findfirst("createTime>" . $today . " and telephone=" . $telephone . " and sidType<1000 order by id desc");
        if ($res == false || count($configsArr) == $res->sidType) {//如果为空 或者已是最后一个 ,则用第一个账号发送
            $sidType = 1;
            $accountSid = $configsArr[1]['accountSid'];
            $accountToken = $configsArr[1]['accountToken'];
            $appId = $configsArr[1]['appId'];
            //模板id
            $tempId = $configsArr[1]['templateConfig'][$templateType];
        } else {//使用下一个账号发送
            $sidType = $res->sidType + 1;
            $accountSid = $configsArr[$sidType]['accountSid'];
            $accountToken = $configsArr[$sidType]['accountToken'];
            $appId = $configsArr[$sidType]['appId'];
            //模板id
            $tempId = $configsArr[$sidType]['templateConfig'][$templateType];
        }

        // 初始化REST SDK
        $logdir=$this->config->directory->logsDir.'/sms.log';//日志记录
        $rest = new \REST($serverIP, $serverPort, $softVersion,$logdir);
        $rest->setAccount($accountSid, $accountToken);
        $rest->setAppId($appId);

        // 发送模板短信
        $result = $rest->sendTemplateSMS($telephone, $datas, $tempId);
        $return['sidType'] = $sidType;
        $return['templateId'] = $tempId;
        $return['result'] = $result;
        return $return;
    }

}