<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Faker\Factory;
use Phalcon\Mailer\Manager;
class PushsController extends ControllerBase
{
    public function pushAPNMessageToSingle(){
        if($this->request->isPost()){
            $deviceToken = $this->request->getPost('deviceToken');
            $title = $this->request->getPost('title');
            $content = $this->request->getPost('content');
            $result = $this->pushserver->pushAPNMessageToSingle($deviceToken, $title, $content);
            if($result){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }
        }

        return $this->proxyError();
    }

    public function pushAPNMessageToList(){
        if($this->request->isPost()){
            $deviceTokenList = $this->request->getPost('deviceTokenList');
            $title = $this->request->getPost('title');
            $content = $this->request->getPost('content');
            $result = $this->pushserver->pushAPNMessageToList($deviceTokenList, $title, $content);
            if($result){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }
        }

        return $this->proxyError();
    }

    public function pushMessageToSingle(){
        if($this->request->isPost()){
            $clientId = $this->request->getPost('clientId');
            $content = $this->request->getPost('content');
            $message = $this->request->getPost('message');
            $result = $this->pushserver->pushMessageToSingle($clientId,$message, $content);
            if($result){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }
        }

        return $this->proxyError();
    }

    public function createTransmissionTemplate(){
        if($this->request->isPost()){
            $content = $this->request->getPost('content');
            $result = $this->pushserver->createTransmissionTemplate($content);
            if($result){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }
        }

        return $this->proxyError();
    }

    /**
     * 设置设备信息
     */
    public function setDeviceInfo(){
        if($this->request->isPost()){
            $result = $this->roomModule->getRoomMgrObject()->setDeviceInfo();
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
}