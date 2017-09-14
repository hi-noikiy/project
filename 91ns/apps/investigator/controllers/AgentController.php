<?php

namespace Micro\Controllers;

class AgentController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        $this->redirect('agent/family');
    }

    //家族
    public function familyAction() {
        //待申请数
        $this->view->setVar('familyApplyNum', $this->invMgrBase->getApplyNum(3));
    }

    //分成比例
    public function familyBonusAction() {
        //待申请数
        $this->view->setVar('familyApplyNum', $this->invMgrBase->getApplyNum(3));
    }

    //创建申请
    public function familyApplyAction() {
        //待申请数
        $this->view->setVar('familyApplyNum', $this->invMgrBase->getApplyNum(3));
    }

    public function familyanchorAction() {
        $id = $this->request->get('id');
        $result = $this->invMgr->checkFamilyInfo($id)['data'];
        $this->view->info = $result;
    }

    //家族收益
    public function familyincomeAction() {
        $id = $this->request->get('id');
        $result = $this->invMgr->checkFamilyInfo($id)['data'];
        $this->view->info = $result;

        $info = $this->invMgr->getFamileyInfo($id);
        $this->view->familyInfo = $info;


        $settlement = $this->invMgr->getFamliySettlement($id);
        $this->view->settlement = $settlement;

        //家族收益结算日
    }

    //趋势分析
    public function familytrendAction() {
        $id = $this->request->get('id');
        $result = $this->invMgr->checkFamilyInfo($id)['data'];
        $this->view->info = $result;
    }

    //
    public function familyinfoAction() {
        $id = $this->request->get('id');
        $result = $this->invMgr->checkFamilyInfo($id)['data'];
        $this->view->info = $result;
    }

    public function ajaxfamilylistAction() {
        if ($this->request->isPost()) {
            $familyName = $this->request->getPost('familyName') ? $this->request->getPost('familyName') : '';
            $orderType = $this->request->getPost('orderType'); //1 收益升序 ,2 收益降序
            $page = $this->request->getPost('page'); //当前页
            $pageSize = $this->request->getPost('pageSize'); //每页的条数

            $familyData = $this->invMgr->AllFamilyList($familyName, $orderType, $page, $pageSize);
            $this->status->ajaxReturn($familyData['code'], $familyData['data']);
        }
        $this->proxyError();
    }

    public function ajaxfamilyapplylistAction() {
        //$result = $this->invMgr->checkFamilyApplyList();
        //$this->view->applyList = $result['data'];


        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page'); //当前页
            $pageSize = $this->request->getPost('pageSize'); //每页的条数

            $result = $this->invMgr->checkFamilyApplyList($type, $page, $pageSize);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //某主播信息
    public function anchorinfoAction() {

        $anchorUid = $this->request->get('uid');
        if (!empty($anchorUid)) {
            $result = $this->invMgr->checkSign($anchorUid);
            $this->view->anchorInfo = $result;
        }
    }

    public function incomeAction() {
        
    }

    public function applyinfoAction() {
        $id = $this->request->get('id');
        $result = $this->invMgr->checkFamilyApply($id);
        $this->view->applyInfo = $result['data'];
    }

    //签约申请
    public function contractApplicationAction() {

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $status = $post['status'];                //
            $nickName = $post['nickName'];                //		
            $result = $this->invMgr->getContractApplication($status, $nickName);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->view->anchor = $result['data']['list'];
            }
        }
    }

    //播出时长
    public function broadcastTimeAction() {

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $familyId = $post['familyId'];                //
            $timeType = $post['timeType'];                //thisDay日, thisWeek周, thisMonth月
            $startTime = $post['startTime'];                //		
            $stopTime = $post['stopTime'];                //		
            $result = $this->invMgr->broadcastTime($familyId, $timeType, $startTime, $stopTime);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->view->broadcast = $result;
            }
        }
        //$familyData = $this->invMgr->broadcastTime(4,'thisWeek','1428465547','1428472145');
    }

}
