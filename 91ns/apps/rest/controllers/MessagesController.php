<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class MessagesController extends ControllerBase
{
    public function sendMessage(){
        if($this->request->isPost()){
            $result = $this->messageMgr->sendMessage();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }


        return $this->proxyError();
    }

    public function uploadMessageImg(){
        if($this->request->isPost()){
            $result = $this->messageMgr->uploadMessageImg();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getMessage(){
        if($this->request->isGet()){
            $limit = $this->request->get('limit');
            $result = $this->messageMgr->getMessageList($limit? $limit : 500);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getMessageInfo(){
        if($this->request->isGet()){
            $toUid = $this->request->get('toUid');
            $result = $this->messageMgr->getMessageInfo($toUid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function setMessageRead(){
        if($this->request->isPost()){
            $result = $this->messageMgr->setMessageRead();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function setMessageTop(){
        if($this->request->isPost()){
            $result = $this->messageMgr->setMessageTop();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function delMessage(){
        if($this->request->isPost()){
            $result = $this->messageMgr->delMessage();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
}