<?php

namespace Micro\Controllers;

class AnchorController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        $this->redirect('anchor/sign');
    }

    //签约主播
    public function signAction() {
        //待申请数
        $this->view->setVar('anchorApplyNum', $this->invMgrBase->getApplyNum(4));
        //主播封面待审核数
        $this->view->setVar('coverAuditNum', $this->invMgrBase->getApplyNum(6));
    }

    //未签约主播
    public function noSignAction() {
        //待申请数
        $this->view->setVar('anchorApplyNum', $this->invMgrBase->getApplyNum(4));
         //主播封面待审核数
        $this->view->setVar('coverAuditNum', $this->invMgrBase->getApplyNum(6));
        //富豪等级配置表
        $list = \Micro\Models\RicherConfigs::find();
        foreach ($list as $val) {
            $data['level'] = $val->level;
            $data['name'] = $val->name;
            $result[] = $data;
        }
        // print_r($result);echo "sssssss";die();
        $this->view->setVar('richerList', $result);
    }

    //分成比例
    public function bonusAction() {
        //待申请数
        $this->view->setVar('anchorApplyNum', $this->invMgrBase->getApplyNum(4));
         //主播封面待审核数
        $this->view->setVar('coverAuditNum', $this->invMgrBase->getApplyNum(6));
    }

    //兑换限制
    public function exchangeAction() {
        //待申请数
        $this->view->setVar('anchorApplyNum', $this->invMgrBase->getApplyNum(4));
         //主播封面待审核数
        $this->view->setVar('coverAuditNum', $this->invMgrBase->getApplyNum(6));
        //兑换下限的值
        $this->view->setVar('limitNum', $this->invMgr->checkExchange(1));
    }

    //签约申请
    public function signApplyAction() {
        //待申请数
        $this->view->setVar('anchorApplyNum', $this->invMgrBase->getApplyNum(4));
         //主播封面待审核数
        $this->view->setVar('coverAuditNum', $this->invMgrBase->getApplyNum(6));
    }

    //主播信息
    public function anchorinfoAction() {
       /* if ($this->session->get($this->config->investigator->authkey) != 'has login') {
            $this->status->ajaxReturn($this->status->getCode('AUTH_ERROR'));
        }*/
        $uid = $this->request->get('uid');
        $result = $this->invMgr->checkSign($uid)['data'];
        $this->view->info = $result;

        $status = $this->invMgr->getUidStatus($uid);
        $this->view->statusInfo = $status;

        //判断是否有家族
        $this->view->familyId = $this->invMgr->getAnchorIncomeInfo($uid, 4);

        //判断是否为家族长
        // $this->view->familyHeader = $this->invMgr->checkIsFamilyHeader($uid);

        

    }

    public function incomeAction() {
        $uid = $this->request->get('uid');
        //底薪
        $ASResult = $this->invMgr->AnchorSalaryInfo($uid);
        $this->view->salaryInfo = $ASResult ? $ASResult[0] : array();

        //个人收益
        $incomeInfo = $this->invMgr->getAnchorIncomeInfo($uid);
        $this->view->incomeInfo = $incomeInfo;
        // exit;

        //判断是否有家族
        $this->view->familyId = $this->invMgr->getAnchorIncomeInfo($uid, 4);

        //判断是否为家族长
        $this->view->familyHeader = $this->invMgr->checkIsFamilyHeader($uid);

        //主播的家族收益、排行
        $familyInfo = $this->invMgr->getAnchorIncomeInfo($uid, 3);
        $this->view->familyInfo = $familyInfo;

        $resultInfo = $this->invMgr->getAnchorInfo($uid)['data'];
        $this->view->anchorInfo = $resultInfo;

              

    }

    //签约详情
    public function applyinfoAction() {
        $id = $this->request->get('id');
        $result = $this->invMgr->getSignApplyInfo($id);
        $this->view->info = $result['data'];
    }

    // 趋势分析
    public function trendAction() {
        $uid = $this->request->get('uid');
        if ($this->request->getPost('isexcel')) {// 导出excel
            $excelTime = $this->request->getPost('excelTime');
            $beginDate = substr($excelTime, 0, 10);
            $endDate = substr($excelTime, 12, 23);
            $type=$this->request->getPost('type');
            $this->invMgr->FamilyBroadcastTime($uid, $type , $beginDate, $endDate,1);
        }
        
        $resultInfo = $this->invMgr->getAnchorInfo($uid)['data'];
        $this->view->anchorInfo = $resultInfo;

        //判断是否有家族
        $this->view->familyId = $this->invMgr->getAnchorIncomeInfo($uid, 4);
    }

    // 贡献
    public function contributeAction() {
        $uid = $this->request->get('uid');
        $resultInfo = $this->invMgr->getAnchorInfo($uid)['data'];
        $this->view->anchorInfo = $resultInfo;
        //判断是否有家族
        $this->view->familyId = $this->invMgr->getAnchorIncomeInfo($uid, 4);
        /*var_dump($this->view->anchorInfo);
        exit;*/
    }

    // 粉丝
    public function fansAction() {
        $uid = $this->request->get('uid');
        $resultInfo = $this->invMgr->getAnchorInfo($uid)['data'];
        $this->view->anchorInfo = $resultInfo;
        //判断是否有家族
        $this->view->familyId = $this->invMgr->getAnchorIncomeInfo($uid, 4);
    }

    //粉丝图
    public function anchorFansInfoAction(){

        $uid = $this->request->get('uid');
        $result = $this->invMgr->getAnchorFansInfo($uid)['data'];
        $this->view->anchorInfo = $result;        
    }

     //导出工作情况表
    public function workTableAction(){

        $uid = $this->request->get('uid');
        $resultInfo = $this->invMgr->getAnchorInfo($uid)['data'];
        $this->view->anchorInfo = $resultInfo;    
        //判断是否有家族
        $this->view->familyId = $this->invMgr->getAnchorIncomeInfo($uid, 4);   
    }

         //消息通知
    public function noticeAction(){

        //待申请数
        $this->view->setVar('anchorApplyNum', $this->invMgrBase->getApplyNum(4));
         //主播封面待审核数
        $this->view->setVar('coverAuditNum', $this->invMgrBase->getApplyNum(6));
    }


        //用户信息
    public function userinfoAction() {      

         $uid = $this->request->get('uid');
        $result = $this->invMgr->checkSign($uid)['data'];
        $this->view->info = $result;

        $status = $this->invMgr->getUidStatus($uid);
        $this->view->statusInfo = $status;

        
         $uid = $this->request->get('uid');
            $user = \Micro\Frameworks\Logic\User\UserFactory::getInstance($uid);
            $showData = $user->getUserInfoObject()->getData();
            $showData['createTime']=date("Y-m-d H:i:s",$showData['createTime']);
            $showData['noLoginDay']=floor((time()-$showData['updateTime'])/86400);
            $showData['updateTime']=date("Y-m-d H:i:s",$showData['updateTime']);
            //徽章
            $result = $user->getUserItemsObject()->getUserBadge();
            if ($result['code'] == $this->status->getCode('OK')) {
                $showData['badge'] = $result['data'];
            } else {
                $showData['badge'] = array();
            }
            $this->view->setVar('userInfo',$showData);

    }


    public function coverAuditAction(){
        //待申请数
        $this->view->setVar('anchorApplyNum', $this->invMgrBase->getApplyNum(4));
         //主播封面待审核数
        $this->view->setVar('coverAuditNum', $this->invMgrBase->getApplyNum(6));
    }


}
