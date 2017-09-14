<?php

namespace Micro\Controllers;

class RoomController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        header("Location:/room/robot");
        exit;
    }

    public function robotAction(){
        if($this->config->robotVersion != '0.0.2'){
            $this->redirect('room/oldrobot');
        }
    }
    public function oldrobotAction(){

    }

    public function autoskipAction(){
    	
    }

    public function contentAction(){
        
         $result = $this->invMgr->RobotMessageList();
         $this->view->message = $result;
    }

    //更新ID获取到消息详情
    public function getIdInfoAction(){

         $id = $this->request->get('id');
        $info = $this->invMgr->getIdInfo($id);
  
    }
    public function noticeAction(){
        $result = $this->configMgr->getAnnouncementData();
        $this->view->announcementData = $result;
    }
    public function playbackAction(){
        
    }


    
   
}
