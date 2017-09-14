<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserhmanagementController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'userhmanagement';
        }
    }

    public function indexAction()
    {

    }

    public function condoListAction(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();          
            $uid = $post['uid'];        
            $times = $post['times'];  //1最近7天登录 2 最近七天伟登录           
           $result = $this->userMgr->condoList($uid,$times);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
   
    //添加房管
    public function addCondoAction(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $postData['roomId'] = $post['roomId'];  
            if(!$post['roomId']){
                return $this->status->ajaxReturn($this->status->getCode('ROOM_NOT_EXIST'));
            }  
            $postData['uid'] = $post['uid'];   
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->userMgr->addHisCondo($postData['roomId'],$postData['uid']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //我自己的房管
    public function hisCondoListAction(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $uid = $post['uid'];        
            $times = $post['times'];  //1最近30天登录 2 最近30天未登录                
            $result = $this->userMgr->hisCondoList($uid,$times);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //删除
    public function delCondoAction(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $id = $post['id'];
            $type = $post['type'];
            $uid = $post['uid'];                       
            $result = $this->userMgr->delCondo($id,$type,$uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //添加备注
    public function remarksAction(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $id = $post['id'];    
            $type = $post['type'];      
            $remarks = $post['remarks'];         //备注      
            $result = $this->userMgr->getRemarks($id,$remarks,$type);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
   
}