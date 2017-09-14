<?php

namespace Micro\Controllers;

class IndexController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
         //房间总数
        $this->view->setVar('roomNum', $this->invMgrBase->getIndexCount()['roomNum']);
        //新签约数
        $this->view->setVar('newInchorNum', $this->invMgrBase->getIndexCount()['newInchorNum']);
        //聊币消耗数
        $this->view->setVar('newCashNum', $this->invMgrBase->getIndexCount()['newCashNum']);
        //聊币实际消耗数
        $this->view->setVar('newRealCashNum', $this->invMgrBase->getIndexCount()['newRealCashNum']);
		//正在直播的房间数
        $this->view->setVar('liveRoomNum', $this->invMgrBase->getIndexCount()['liveRoomNum']);
		//新创建家族数
        $this->view->setVar('newFamilyNum', $this->invMgrBase->getIndexCount()['newFamilyNum']);
    }
    
    public function allroomAction(){
        

    }

    

}
