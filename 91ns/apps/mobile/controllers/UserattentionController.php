<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserattentionController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '用户';
            $this->view->ns_active = 'userattention';
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->redirect('login');
        }

    }

    public function addAttenAction()
    {
        if ($this->request->isPost()) {

            $targetId = $this->request->getPost('uid');
            $roomId = $this->request->getPost('roomId');
            
            if(!empty($targetId)){
                $result = $this->userMgr->addFollow($targetId, $roomId);
                 $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }

    public function getFocusListAction()
    {
        if ($this->request->isPost()) {

            $type = $this->request->getPost('type');
            $sortType = $this->request->getPost('sortType');
            $findUid = $this->request->getPost('findUid');

            $result = $this->userMgr->getFocusList($type, $sortType, $findUid);
            if ($result['code'] == $this->status->getCode('OK')) {

                $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function delAttenAction()
    {
        if ($this->request->isPost()) {

            $targetId = $this->request->getPost('uid');
            $roomId = $this->request->getPost('roomId');

            if(!empty($targetId)){
                $result = $this->userMgr->delFollow($targetId, $roomId);
                 $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }

    public function getRecommFocusListAction(){
        if($this->request->isPost()){
            $result =  $this->roomModule->getRoomMgrObject()->getRecommFocusList();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

}