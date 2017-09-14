<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class DynamicsController extends ControllerBase
{
    /**
     * ��Ӷ�̬
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
     * ��ö�̬�б�
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
     * �ظ���̬
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
     * ��ö�̬����
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
     * ��ö�̬��
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
     * ��ö�̬ת��
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
     * ɾ����̬
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
     * ��
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
     * ת��
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
     * ���㶯̬��ֵ
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
     * ������б�
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
     * ��������б�
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
     * ���ת���б�
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