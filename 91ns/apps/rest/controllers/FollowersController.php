<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class FollowersController extends ControllerBase
{
    //添加关注
    public function add() {
        if ($this->request->isPost()) {
            $targetId = $this->request->getPost('uid');
            $roomId = $this->request->getPost('roomId');

            if(!empty($targetId)){
                $result = $this->userMgr->addFollow($targetId, $roomId);
                return $this->status->mobileReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    //删除关注
    public function del() {
        if ($this->request->isPost()) {

            $targetId = $this->request->getPost('uid');
            $roomId = $this->request->getPost('roomId');

            if(!empty($targetId)){
                $result = $this->userMgr->delFollow($targetId, $roomId);
                return $this->status->mobileReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    // 批量删除
    public function delMultipleFollows()
    {
        if ($this->request->isPost()) {
            $targetIdList = $this->request->getPost('uidList');
            if(!empty($targetIdList)){
                $result = $this->userMgr->delMultipleFollow($targetIdList);
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->mobileReturn($this->status->getCode('OK'));
                }

                return $this->status->mobileReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }
    
    //获取粉丝关注数量
    public function getFansCount()
    {
        if ($this->request->isGet()) {
            $uid = $this->request->get('uid');
            if(!empty($uid)){
                $result = $this->userMgr->getFansCount($uid);
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
                }

                return $this->status->mobileReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    public function getFocusList()
    {
        if ($this->request->isGet()) {
            $type = $this->request->get('type');
            $sortType = $this->request->get('sortType');
            $nickName = $this->request->get('nickName');
            $uid = $this->request->get('uid');
            $p = intval($this->request->get('p'));
            $limit = intval($this->request->get('limit'));
            $result = $this->userMgr->getMobileFocusList($type, $sortType, $nickName, $uid, $p ? $p : 1 , $limit ? $limit : 100000);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getFansList()
    {
        if ($this->request->isGet()) {
            $type = $this->request->get('type');
            $uid = $this->request->get('uid');
            $nickName = $this->request->get('nickName');
            $p = intval($this->request->get('p'));
            $limit = intval($this->request->get('limit'));
            $result = $this->userMgr->getMobileFansList($type, $nickName,  $uid, $p ? $p : 1 , $limit ? $limit : 100000);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getNickNameList(){
        if ($this->request->isGet()) {
            $nickName = $this->request->get('nickName');
            $result = $this->userMgr->getUidByNickname($nickName);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function setFocusImportant()
    {
        if ($this->request->isPost()) {
            $targetId = $this->request->getPost('uid');
            $focus = $this->request->getPost('focus');
            $result = $this->userMgr->setFocusImportant($targetId, $focus);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function setFocusUserData()
    {
        if ($this->request->isPost()) {
            $targetId = $this->request->getPost('uid');
            $userData = $this->request->getPost('userData');
            $result = $this->userMgr->setFocusUserData($targetId, $userData);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function isFans(){
        if ($this->request->isGet()) {
            $targetId = $this->request->get('uid');
            $result = $this->userMgr->isFans($targetId);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
}