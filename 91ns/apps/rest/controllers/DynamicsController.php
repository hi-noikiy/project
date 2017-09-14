<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class DynamicsController extends ControllerBase
{
    /**
     * 添加动态
     *
     * @return mixed]
     */
    public function addDynamics(){
        if ($this->request->isPost()) {
            $content = $this->request->getPost('content');
            $pos = $this->request->getPost('pos');
            $addtime = time();
            $result = $this->dynamicsMgr->addDynamics(0, $content, 0, 0, 0, array(), array() , array(), $pos, $addtime);;
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }


    /**
     * 获得动态列表
     *
     * @return mixed
     */
    public function getDynamicsList(){
        if ($this->request->isGet()) {
            $uid = $this->request->get('uid');
            $type = $this->request->get('type');
            $result = $this->dynamicsMgr->getDynamicsList($uid, $type);;
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }


    /**
     * 回复动态
     *
     * @return mixed
     */
    public function replyDynamics(){
        if ($this->request->isPost()) {
            $content = $this->request->getPost('content');
            $pos = $this->request->getPost('pos');
            $toUid = $this->request->getPost('toUid');
            $did = trim($this->request->getPost('did'));
            $addtime = time();
            $result = $this->dynamicsMgr->replyDynamics($did, $toUid, $content, $pos, $addtime);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得动态评论
     *
     * @return mixed
     */
    public function getDynamicsReply(){
        if ($this->request->isGet()) {
            $did = trim($this->request->get('did'));
            $result = $this->dynamicsMgr->getDynamicsReply($did);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得动态赞
     *
     * @return mixed
     */
    public function getDynamicsPraises(){
        if ($this->request->isGet()) {
            $did = trim($this->request->get('did'));
            $result = $this->dynamicsMgr->getDynamicsPraise($did);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得动态转发
     *
     * @return mixed
     */
    public function getDynamicsForwards(){
        if ($this->request->isGet()) {
            $did = trim($this->request->get('did'));
            $result = $this->dynamicsMgr->getDynamicsForwards($did);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 删除动态
     *
     * @return mixed
     */
    public function delDynamics(){
        if ($this->request->isPost()) {
            $did = trim($this->request->getPost('did'));
            $result = $this->dynamicsMgr->delDynamics($did);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }


    /**
     * 赞
     *
     * @return mixed
     */
    public function praiseDynamics(){
        if ($this->request->isPost()) {
            $did = trim($this->request->getPost('did'));
            $result = $this->dynamicsMgr->praiseDynamics($did);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }


    /**
     * 转发
     *
     * @return mixed
     */
    public function forwardDynamics(){
        if ($this->request->isPost()) {
            $did = trim($this->request->getPost('did'));
            $content = trim($this->request->getPost('content'));
            $pos = trim($this->request->getPost('pos'));
            $result = $this->dynamicsMgr->forwardDynamics($did, $pos, $content);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 计算动态热值
     *
     * @return mixed
     */
    public function execDynamicsHotPoint(){
        if ($this->request->isPost()) {
            $did = trim($this->request->getPost('did'));
            $result = $this->dynamicsMgr->execDynamicsHotPoint($did);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得赞列表
     *
     * @return mixed
     */
    public function getPraiseList(){
        if ($this->request->isGet()) {
            $result = $this->dynamicsMgr->getPraiseList();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得评论列表
     *
     * @return mixed
     */
    public function getReplyList(){
        if ($this->request->isGet()) {
            $result = $this->dynamicsMgr->getReplyList();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得转发列表
     *
     * @return mixed
     */
    public function getForwardList(){
        if ($this->request->isGet()) {
            $result = $this->dynamicsMgr->getForwardList();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
}