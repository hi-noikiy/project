<?php

namespace Micro\Controllers;

//客服后台--结算模块
class SettleController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        //待结算数
        $this->view->setVar('accountApplyNum', $this->invMgrBase->getApplyNum(5));

    }

    /*public function waitsettleAction() {
        if ($this->request->getPost('upload')) {// 上传结算图片
            $id = $this->request->getPost('id');
            $remark = $this->request->getPost('remark');
            if ($this->request->hasFiles()) {                
                $result = $this->invMgr->uploadSettlePic($this->request->getUploadedFiles(), $id,$remark);
            }
        }
        //待结算数
        $this->view->setVar('accountApplyNum', $this->invMgrBase->getApplyNum(5));
    }*/

    public function settledAction() {
        if ($this->request->getPost('isexcel')) {// 导出excel
            $excelTime = $this->request->getPost('excelTime');
            $beginDate = substr($excelTime, 0, 10);
            $endDate = substr($excelTime, 12, 23);
            $this->invMgr->getSettleSuccessList($beginDate, $endDate, 1, 10, 1);
        }
        //待结算数
        $this->view->setVar('accountApplyNum', $this->invMgrBase->getApplyNum(2));
    }

    public function settleremindAction() {
        //结算提醒列表
        $list = $this->invMgr->getSettleRemindList()['data'];
        $this->view->setVar('remindList', $list);
        //待结算数
        $this->view->setVar('accountApplyNum', $this->invMgrBase->getApplyNum(2));
    }

    public function settledinfoAction() {
        //结算详情
        $this->view->setVar('info', $this->invMgr->getOneSettleApply($_GET['id']));
    }

    function waitsettledinfoAction() {
        if ($this->request->getPost('upload')) {// 上传结算图片
            $id = $this->request->getPost('id');
            $remark = $this->request->getPost('remark');
            if ($this->request->hasFiles()) {                
                $result = $this->invMgr->uploadSettlePic($this->request->getUploadedFiles(), $id,$remark);
            }
        }
        //待结算详情
        $this->view->setVar('info', $this->invMgr->getOneSettleApply($_GET['id']));
    }

    /**
     * 结算
     * @param $id
     * @param $files
     * @param $remark
     * @param $type
     */
    public function waitsettleAction(){
        if($this->request->isPost() && $this->request->getPost('upload')){
            $id = intval($this->request->getPost('id'));
            $files = $this->request->getUploadedFiles();
            $remark = $this->request->getPost('remark');
            $type = $this->request->getPost('type');
            if(!$type){
                $type = 2;
            }
            $result = $this->invMgr->updateSettleLog($id, $files, $remark, $type);
            // return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        //待结算数
        $this->view->setVar('accountApplyNum', $this->invMgrBase->getApplyNum(2));
    }
    

}
