<?php
namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserOnlineGiftController extends ControllerBase
{
    //获取在线礼物（魅力）数量
    public function getOnlineGiftAction()
    {
        if($this->request->isPost())
        {
            $result=$this->userMgr->getOnlineGift();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    //赠送在线礼物（魅力）
    public function sendOnlineGiftAction()
    {
        if($this->request->isPost())
        {
            $roomId=$this->request->getPost("roomId");
            $result=$this->userMgr->sendOnlineGift($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
}