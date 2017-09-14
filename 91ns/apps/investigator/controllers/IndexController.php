<?php

namespace Micro\Controllers;

class IndexController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        $indexData = $this->invMgrBase->getIndexCount();
        //房间总数
        $this->view->setVar('roomNum', $indexData['roomNum']);
        //新签约数
        $this->view->setVar('newInchorNum', $indexData['newInchorNum']);
        //聊币消耗数
        $this->view->setVar('newCashNum', $indexData['newCashNum']);
        //聊币实际消耗数
        $this->view->setVar('newRealCashNum', $indexData['newRealCashNum']);
        //聊币托账号消耗数
        $this->view->setVar('newTuoCashNum', $indexData['newTuoCashNum']);
		//正在直播的房间数
        $this->view->setVar('liveRoomNum', $indexData['liveRoomNum']);
		//新创建家族数
        $this->view->setVar('newFamilyNum', $indexData['newFamilyNum']);
    }
    
    public function allroomAction(){
        

    }

    

}
