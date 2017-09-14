<?php

namespace Micro\Frameworks\PushService;

use Phalcon\DI\FactoryDefault;
use Micro\Models\DeviceInfo;
class PushServer {
	protected $di;
	protected $config;
	protected $host;
	protected $appKey;
	protected $appId;
	protected $masterSecret;
    protected $status;
    protected $request;
    protected $validator;
    protected $userAuth;
    protected $modelsManager;
	public function __construct($type = 0) {
        $this->di = FactoryDefault::getDefault();
		$this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->request = $this->di->get('request');
        $this->validator = $this->di->get('validator');
        $this->userAuth = $this->di->get('userAuth');
        $this->modelsManager = $this->di->get('modelsManager');
		require_once $this->config->miscellaneous->pushservice;

        if($type){
            // appstore 配置
            $this->host = $this->config->pushserviceappstore->host;
            $this->appKey = $this->config->pushserviceappstore->appKey;
            $this->appId = $this->config->pushserviceappstore->appId;
            $this->masterSecret = $this->config->pushserviceappstore->masterSecret;
        }else{
            $this->host = $this->config->pushservice->host;
            $this->appKey = $this->config->pushservice->appKey;
            $this->appId = $this->config->pushservice->appId;
            $this->masterSecret = $this->config->pushservice->masterSecret;
        }
    }

    // 单用户的APNS推送方式，最大支持256Byte
    public function pushAPNMessageToSingle($deviceToken, $message, $content) {
    	$igt = new \IGeTui($this->host, $this->appKey, $this->masterSecret);
        $template = new \IGtAPNTemplate();
//        $template->set_pushInfo("", 0, $title, "", $content, "", "", "");
        $template->set_pushInfo("", 0, $message, "", "", $content, "", "");
        $message = new \IGtSingleMessage();
        $message->set_data($template);
        $ret = $igt->pushAPNMessageToSingle($this->appId, $deviceToken, $message);
        return $ret;
    }

    // 多用户的APNS推送方式，最大支持256Byte
    public function pushAPNMessageToList($deviceTokenList, $message, $content) {
    	$igt = new \IGeTui($this->host, $this->appKey, $this->masterSecret);
        $template = new \IGtAPNTemplate();
        //$template->set_pushInfo("", 0, $title, "", $content, "", "", "");
		$template->set_pushInfo("", 1, $content, "", $message, $content, "", "");
        
        putenv("needDetails=true");
        $listmessage = new \IGtListMessage();
        $listmessage->set_data($template);
        $contentId = $igt->getAPNContentId($this->appId, $listmessage);
        $ret = $igt->pushAPNMessageToList($this->appId, $contentId, $deviceTokenList);
        return $ret;
    }

    //透传消息方式
    public function pushMessageToSingle($clientId, $message, $content) {
        $igt = new \IGeTui($this->host, $this->appKey, $this->masterSecret);
        $template = $this->createTransmissionTemplate($content);
//        $template ->set_pushInfo("", 0, "", "", "", "", "", "");    //注：android需要注释掉这行代码
        $template->set_pushInfo("", 0, $content, "", $message, $content, "", "");
        //个推信息体
        $message = new \IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        
        //接收方
        $target = new \IGtTarget();
        $target->set_appId($this->appId);
        $target->set_clientId($clientId);

        $rep = $igt->pushMessageToSingle($message, $target);
        return $rep;
    }

    public function pushMessageToList($clientIdList, $message) {
        $targetList = array();
        $igt = new \IGeTui($this->host, $this->appKey, $this->masterSecret);
        $template = $this->createTransmissionTemplate($message);
//        $template ->set_pushInfo("", 0, "", "", "", "", "", "");    //注：android需要注释掉这行代码
//        $template->set_pushInfo("", 0, $message, "", '', '', "", "");
        putenv("needDetails=true");
        //个推信息体
        $message = new \IGtListMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);	//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        $contentId = $igt->getContentId($message);
        //$contentId = $igt->getContentId($message,"toList任务别名功能");	//根据TaskId设置组名，支持下划线，中文，英文，数字

        //接收方1
        foreach($clientIdList as $clientId){
            $target1 = new \IGtTarget();
            $target1->set_appId($this->appId);
            $target1->set_clientId($clientId);
            $targetList[] = $target1;
        }

        $rep = $igt->pushMessageToList($contentId, $targetList);
        return $rep;
//        if($clientIdList && is_array($clientIdList)){
//            foreach($clientIdList as $clientId){
//                $res = $this->pushMessageToSingle($clientId, $message, $content);
//            }
//        }
//
//        return $res;
    }

    private function createTransmissionTemplate($content){
        $template =  new \IGtTransmissionTemplate();
        $template->set_appId($this->appId);//应用appid
        $template->set_appkey($this->appKey);//应用appkey
        $template->set_transmissionType(2);//透传消息类型
        $template->set_transmissionContent($content);//透传内容
        //iOS推送需要设置的pushInfo字段
        //$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
        //$template ->set_pushInfo("", 0, "", "", "", "", "", "");
        return $template;
    }




}