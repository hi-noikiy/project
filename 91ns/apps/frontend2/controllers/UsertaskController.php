<?php
namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserTaskController extends ControllerBase
{
    //获取用户的任务列表
    public function getUserTaskAction() {
        if ($this->request->isPost()) {
            $result = $this->taskMgr->getUserTask(2);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取某个任务的奖励
    public function getTaskRewardAction(){
        if($this->request->isPost()) {
            $taskId = $this->request->getPost("taskId");
            $result = $this->taskMgr->getTaskReward($taskId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    //完成打招呼的接口
    public function setTalkTaskAction(){
        if($this->request->isPost()) {
            $result = $this->taskMgr->setTalkTask();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    //完成观看的任务
    public function setWatchTaskAction(){
        if($this->request->isPost()) {
            $type=$this->request->getPost("type");
            $result = $this->taskMgr->setwatchTask($type);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    public function getTaskStatusAction(){
        if($this->request->isPost()) {
            $taskId=$this->request->getPost("taskId");
            $result = $this->taskMgr->getTaskStatus($taskId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    //累计观看直播任务
    public function setTotalWatchAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost("roomId");
            $result = $this->taskMgr->totalWatchTask($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //累计发言任务
    public function setTotalTalkAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost("roomId");
            $result = $this->taskMgr->getTotalTalkTask($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

}