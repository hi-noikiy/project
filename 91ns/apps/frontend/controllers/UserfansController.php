<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserFansController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'userfans';
        }
    }

    public function indexAction()
    {
        //粉丝总榜
        $result = $this->userMgr->getFansList(0);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->view->fansData = $result['data'];
        }else{
            $this->view->fansData = array();
        }

         //30天粉丝
        $result = $this->userMgr->getFansList(1);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->view->FData = $result['data'];
        }else{
            $this->view->FData = array();
        }

        $user=$this->userAuth->getUser();
        $result = $this->userMgr->getFansCount($user->getUid());
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->view->fanscount = $result['data'];
        }else{
            $this->view->fanscount = array(
                'totalNum' => 0,
                'nearNum' => 0,
            );
        }

        //房间守护
        $result = $this->userMgr->getBeGuardedList();
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->view->fansGuardData = $result['data'];
        }else{
            $this->view->fansGuardData = array();
        }

        //房间守护个数 
        $user=$this->userAuth->getUser();
        $result = $this->userMgr->getBeGuardedCount($user->getUid());
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->view->guardcount = $result['data'];
        }else{
            $this->view->guardcount = array(
                'count' => 0,
            );
        }
    }
    public function getFansListAction()
    {
        if ($this->request->isPost()) {

            $type = $this->request->getPost('type');

            if(!empty($type)){
                $result = $this->userMgr->getFansList($type);
                if ($result['code'] == $this->status->getCode('OK')) {
                    $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }

    public function getFansCountAction()
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

    public function getBeGuardedListAction()
    {
        if ($this->request->isPost()) {

            $uid = $this->request->getPost('uid');
            if(!empty($uid)){
                $result = $this->userMgr->getBeGuardedList();
                if ($result['code'] == $this->status->getCode('OK')) {

                    $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }

    public function getBeGuardedCountAction()
    {
        if ($this->request->isPost()) {

            $uid = $this->request->getPost('uid');

            if(!empty($uid)){
                $result = $this->userMgr->getBeGuardedCount();
                if ($result['code'] == $this->status->getCode('OK')) {

                    $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }
    //判断是否为指定uid的粉丝
    public function isFansAction()
    {
        if ($this->request->isPost()) {

            $uid = $this->request->getPost('uid');

            if(!empty($uid)){
                $result = $this->userMgr->isFans($uid);
                if ($result['code'] == $this->status->getCode('OK')) {

                    $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }

}