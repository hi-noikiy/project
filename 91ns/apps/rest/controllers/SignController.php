<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class SignController extends ControllerBase
{
    //获取用户签到内容
    public function getUserSign(){
        if ($this->request->isGet()) {
            $result = $this->signMgr->getUserSign();;
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    //用户签到
    public function setUserSign(){
        if ($this->request->isPost()) {
            $result = $this->signMgr->setSign();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    //获取用户签到奖励
    public function getUserSignReward(){
        if ($this->request->isPost()) {
            $type=$this->request->getPost("type");
            $result = $this->signMgr->getSignReward($type);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    //获取用户签到状态
    public function getUserSignStatus(){
        if ($this->request->isPost()) {
            $result = $this->signMgr->getSignStatus();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 设置签到推送开关
    public function setSignStatus(){
        if ($this->request->isPost()) {
            $status = intval($this->request->getPost("status"));
            $result = $this->signMgr->setUserSignStatus($status);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
}