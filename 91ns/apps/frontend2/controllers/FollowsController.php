<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class FollowsController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'follows';
        }
    }

    public function indexAction()
    {

    }

    /**
     * 添加关注
     * @param uid 目标uid
     * @param roomId 目标房间id
     */
    public function addAttenAction()
    {
        if ($this->request->isPost()) {
            $targetId = $this->request->getPost('uid');
            $roomId = $this->request->getPost('roomId');
            if(!empty($targetId)){
                $result = $this->userMgr->addFollow($targetId, $roomId);
                return $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    /**
     * 删除关注
     * @param uid 目标uid
     * @param roomId 目标房间id
     */
    public function delAttenAction()
    {
        if ($this->request->isPost()) {
            $targetId = $this->request->getPost('uid');
            $roomId = $this->request->getPost('roomId');
            if(!empty($targetId)){
                $result = $this->userMgr->delFollow($targetId, $roomId);
                return $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    /**
     * 批量删除关注
     * @param uidList 目标uid列表
     */
    public function delMultipleAttenAction()
    {
        if ($this->request->isPost()) {
            $targetIdList = $this->request->getPost('uidList');
            if(!empty($targetIdList)){
                $result = $this->userMgr->delMultipleFollow($targetIdList);
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

                return $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    /**
     * 获得关注数
     * @param uid 目标uid
     */
    public function getAttenAction()
    {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            if(!empty($uid)){
                $result = $this->userMgr->getFansCount($uid);
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

                return $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    /**
     * 获得关注列表
     * @param type 类型 0-全部关注,1-重点关注,2-互相关注
     * @param sortType 0-关注时间,1-主播等级,2-富豪等级
     * @param orderType 排序类型0-降序，1-升序
     * @param nickName 目标用户
     * @param p 当前页
     * @param perCount 每页显示数
     */
    public function getFocusListAction()
    {
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $sortType = $this->request->getPost('sortType');
//            $uid = $this->request->getPost('uid');
            $orderType = intval($this->request->getPost('orderType'));
            $nickName = $this->request->getPost('nickName');
            $p = intval($this->request->getPost('p'));
            $perCount = intval($this->request->getPost('perCount'));
            $result = $this->userMgr->getNewFocusList($type, $sortType, $nickName, $orderType, '',$p ? $p : 1, $perCount ? $perCount : 1000000);
//            $count = $this->userMgr->getFocusCount();
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->ajaxReturn($this->status->getCode('OK'), array('data' => $result['data']['list'], 'p' => $p, 'count' => intval($result['data']['count']), 'recData' => $result['data']['recList']));
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 设置重点关注
     * @param targetId 关注的用户id
     * @param focus 是否重点关注 1 是 0 否
     */
    public function setFocusImportantAction()
    {
        if ($this->request->isPost()) {
            $targetId = $this->request->getPost('uid');
            $focus = $this->request->getPost('focus');
            $result = $this->userMgr->setFocusImportant($targetId, $focus);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 设置用户userdata
     * @param uid 目标uid
     * @param userData 备注内容
     */
    public function setFocusUserDataAction()
    {
        if ($this->request->isPost()) {
            $targetId = $this->request->getPost('uid');
            $userData = $this->request->getPost('userData');
            $result = $this->userMgr->setFocusUserData($targetId, $userData);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取粉丝贡献
    public function getFansContributeAction(){
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');

            $result = $this->userMgr->getNewFansList($type, '', $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 粉丝贡献排行榜【NEW】
    public function getFansConsumeAction(){
        if ($this->request->isPost()) {
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $uid = $this->request->getPost('uid');

            $result = $this->userMgr->getFansConsume($uid, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得粉丝列表
     * @param type : 0-总榜 1-近30天
     * @param p 当前页
     * @param perCount 每页显示数
     */
    public function getFansListAction()
    {
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            if(!empty($type) || $type == 0){
                $p = intval($this->request->getPost('p'));
                $perCount = intval($this->request->getPost('perCount'));
                $result = $this->userMgr->getNewFansList($type, '', $p ? $p : 1, $perCount ? $perCount : 6);
//                $count = $this->userMgr->getFansCount();
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->ajaxReturn($this->status->getCode('OK'), array('data' => $result['data']['list'], 'p' => $p, 'count' => $result['data']['count']));
                }
                return $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }


    /**
     * 获得粉丝数
     * @param uid 目标用户uid
     */
    public function getFansCountAction()
    {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            if(!empty($uid)){
                $result = $this->userMgr->getFansCount($uid);
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

                return $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return  $this->proxyError();
    }

    /**
     * 获得守护列表
     * @param uid 目标uid
     */
    public function getBeGuardedListAction()
    {
        if ($this->request->isPost()) {
            $p = intval($this->request->getPost('p'));
            $result = $this->userMgr->getNewBeGuardedList($p ? $p : 1);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得守护数
     * @param uid 目标uid
     */
    public function getBeGuardedCountAction()
    {
        if ($this->request->isPost()) {
//            $uid = $this->request->getPost('uid');
            $result = $this->userMgr->getBeGuardedCount();
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 判断是否是粉丝
     * @param uid 目标uid
     */
    public function isFansAction()
    {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            if(!empty($uid)){
                $result = $this->userMgr->isFans($uid);
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }

                return $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }
}