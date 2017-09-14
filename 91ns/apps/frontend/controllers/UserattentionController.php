<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserAttentionController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'userattention';
        }
    }

    public function indexAction()
    {

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
    // 批量删除
    public function delMultipleAttenAction()
    {
        if ($this->request->isPost()) {

            $targetIdList = $this->request->getPost('uidList');

            if(!empty($targetIdList)){
                $result = $this->userMgr->delMultipleFollow($targetIdList);
                if ($result['code'] == $this->status->getCode('OK')) {

                    $this->status->ajaxReturn($this->status->getCode('OK'));
                }

                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }
    //获取粉丝关注数量
    public function getAttenAction()
    {
        if ($this->request->isPost()) {

            $uid = $this->request->getPost('uid');

            if(!empty($uid)){
                $result = $this->userMgr->getFansCount($uid);

                if ($result['code'] == $this->status->getCode('OK')) {

                    $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

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


    public function setFocusImportantAction()
    {
        if ($this->request->isPost()) {

            $targetId = $this->request->getPost('uid');
            $focus = $this->request->getPost('focus');

            $result = $this->userMgr->setFocusImportant($targetId, $focus);
            if ($result['code'] == $this->status->getCode('OK')) {

                $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function setFocusUserDataAction()
    {
        if ($this->request->isPost()) {

            $targetId = $this->request->getPost('uid');
            $userData = $this->request->getPost('userData');

            $result = $this->userMgr->setFocusUserData($targetId, $userData);
            if ($result['code'] == $this->status->getCode('OK')) {

                $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
}