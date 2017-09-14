<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserApplyController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'userapply';
        }
    }

    public function indexAction()
    {
        $num = $this->userMgr->getTipNumber();
        $this->pageCheckSuccess($num);
        $this->view->tipNumber = $num['data'];
    }

    //我的申请
    public function getApplyListAction(){
        if ($this->request->isPost()) {
            $currentPage = $this->request->getPost('currentPage');
            $pageSize = $this->request->getPost('pageSize');
            $status = $this->request->getPost('status');
            $result = $this->userMgr->getApplyList($currentPage, $pageSize, $status);
            $num = $this->userMgr->getTipNumber(); //获取未读数。
            if ($num['code'] == $this->status->getCode('OK')) {
                $result['data']['tip'] = $num['data']['apply'];
            }
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //我的审批
    public function getAuditingListAction(){
        if ($this->request->isPost()) {
            $currentPage = $this->request->getPost('currentPage');
            $pageSize = $this->request->getPost('pageSize');
            $status = $this->request->getPost('status');
            $result = $this->userMgr->getAuditingList($currentPage, $pageSize, $status);
            $num = $this->userMgr->getTipNumber();//获取未读数。
            if($num['code'] == $this->status->getCode('OK')){
                $result['data']['tip'] = $num['data']['auditing'];
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //我的消息
    public function getMessageListAction(){
        if ($this->request->isPost()) {
            $currentPage = $this->request->getPost('currentPage');
            $pageSize = $this->request->getPost('pageSize');
            $status = $this->request->getPost('status');
            $type = $this->request->getPost('type'); //1：系统消息 2：官方通知
            !isset($status) && $status = '';
            !isset($type) && $type = 1;
            $result = $this->userMgr->getInformationList($type, $status, $currentPage, $pageSize);
            $num = $this->userMgr->getUnReadInfoNum($type); //获取未读数。
            if ($num['code'] == $this->status->getCode('OK')) {
                $result['data']['tip'] = $num['data'];
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //删除通知
    public function deleteMessageAction(){
        if ($this->request->isPost()) {
            $ids = $this->request->getPost('ids');

            $result = $this->userMgr->delInformation($ids);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //删除通知
    public function readInformationAction(){
        if ($this->request->isPost()) {
            $informationIds = $this->request->getPost('message');
            $applyIds = $this->request->getPost('apply');
            $result = $this->userMgr->readInformation($informationIds, $applyIds);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    /*
     * 撤销申请
     * */
    public function delApplyAction(){
        if ($this->request->isPost()) {
            $applyId = $this->request->getPost('applyId');

            $result = $this->userMgr->cancelApply($applyId);
            if($result['code'] == $this->status->getCode('OK')){
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * 审批处理
     * */
    public function updateApplyAction(){
        if($this->request->isPost()){
            $status = $this->request->getPost('status');
            $applyId = $this->request->getPost('applyId');

            $user = $this->userAuth->getUser();
            if (!$user) {
                return $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $apply = $user->getUserApplyObject();

            if($status == 1){
                $result = $apply->updateApplyById($applyId, 1);
            }else if($status == 3){
                $result = $apply->refuseApply($applyId);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * 查看审批
     * */
    public function getApplyInfoAction(){
        if ($this->request->isPost()) {
            $applyId = $this->request->getPost('applyUid');
            $user = $this->userAuth->getUser();
            if (!$user) {
                return $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $apply = $user->getUserApplyObject();
            $result = $apply->getApplyInfo($applyId);
            if($result['code'] == $this->status->getCode('OK')){
                $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

}