<?php

namespace Micro\Frameworks\Logic\Push;
use Phalcon\DI\FactoryDefault;
use Micro\Models\PrivateMessage;
use Micro\Models\PrivateMessageConfig;
use Micro\Frameworks\Logic\User\UserFactory;

class PushMgr{
    
    protected $di;
    protected $status;
    protected $validator;
    protected $typeConfigData;
    protected $config;
    protected $userAuth;
    protected $request;
    protected $baseCode;
    protected $comm;
    protected $modelsManager;
    protected $pushserver;
    protected $roomModule;
    protected $session;
    protected $pathGenerator;
    protected $storage;
    protected $pushserverappstore;
    public function __construct()
    {   
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->comm = $this->di->get('comm');
        $this->pushserver = $this->di->get('pushserver');
        $this->pushserverappstore = $this->di->get('pushserverappstore');
        $this->roomModule = $this->di->get('roomModule');
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【PushMgr】 error : '.$errInfo);
    }

    /**
     * 推送
     *
     * @param $uidsList
     * @param $message
     * @param $content
     * @return mixed
     */
    public function sendMessage($uidsList, $message, $content, $loginPush = 0){
        if($uidsList){
            // 获得ios推送列表
            $tokenListRes = $this->roomModule->getRoomMgrObject()->getTokenByUid($this->config->pushservice->type->ios, $uidsList, 'devicetoken', $loginPush);
            if($tokenListRes['code'] == $this->status->getCode('OK') && !empty($tokenListRes['data'])){
                //$message['badge'] = 1;
                $iosMessage = $message;
                unset($iosMessage['time']);
                $iosMessageJson = json_encode($iosMessage);
                // $this->errLog('mb_length1 = ' . $iosMessageJson . mb_strlen($iosMessageJson) . ' and  strlen1 = ' . strlen($iosMessageJson));
                $this->pushserver->pushAPNMessageToList($tokenListRes['data'], $iosMessageJson, $content);
                $this->pushserverappstore->pushAPNMessageToList($tokenListRes['data'], $iosMessageJson, $content);
            }

            // 获得安卓推送列表
            $tokenListRes = $this->roomModule->getRoomMgrObject()->getTokenByUid($this->config->pushservice->type->android, $uidsList, 'clientID', $loginPush);
            if($tokenListRes['code'] == $this->status->getCode('OK') && !empty($tokenListRes['data'])){
                $andMessage = $message;
                $andMessage['content'] = $content;
                $andMessageJson = json_encode($andMessage);
                // $this->errLog('mb_length = ' . $andMessageJson . mb_strlen($andMessageJson) . ' and  strlen = ' . strlen($andMessageJson));
                $this->pushserver->pushMessageToList($tokenListRes['data'], $andMessageJson);
                $this->pushserverappstore->pushMessageToList($tokenListRes['data'], $andMessageJson);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }

        return $this->status->retFromFramework($this->status->getCode('URI_ERROR'));
    }


}