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

    
   
}
