<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;

class UserbillController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'userbill';
        }
    }
   //我的账单
    public function indexAction()
    {        
        //是否首充活动弹窗判断
        $isFirstCharge = 0;
        $orderId = $this->request->get('orderId');
        $payType = $this->request->get('type');
        if ($orderId) {
            $result = $this->payPushs->checkPayStatus($orderId);
            if ($result['code'] == $this->status->getCode('OK')) {
                //查询用户渠道
                $loginUser = UserFactory::getInstance($this->userAuth->getUser()->getUid());
                $ns_sources = $loginUser->getUserInfoObject()->getUserSource();
                $this->view->chargingSourceType = $payType;
                $this->view->totalFee = $result['data']['totalFee'];
                $isFirstCharge = $result['data']['isFirstCharge'];
            }
            $this->redirect('personal/mybill?secondpage=payrecord&sonType=recharge&orderId=' . $orderId .'&type=' . $payType);
        }

        $this->view->isFirstCharge = $isFirstCharge;
    }
	
	public function getBillAction(){
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $start =  $this->request->getPost('start');
            if($start){
                $start = strtotime($start);
            }else{
                $start = strtotime(date('Y-m-d',strtotime('-1 year')));
//                $start = strtotime(date('Y-m-d') . " 00:00:00");
            }

            $end =  $this->request->getPost('end');
            if($end){
                $end = strtotime($end . "23:59:59");
            }else{
//                $end = strtotime(date('Y-m-d') . "23:59:59");
            }

            $p = $this->request->getPost('p');
            $perCount = $this->request->getPost('perCount');
            // $status = $this->request->getPost('status');
            if(!empty($type)){
                // $result = $this->userMgr->newconsumeList($type, $start, $end, $status, $p, $perCount);
                $result = $this->userMgr->newconsumeList($type, $start, $end, $p, $perCount);
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }
               
                return $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        
        return $this->proxyError();
	 }

     public function delOrderAction(){
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $result = $this->userMgr->delOrder($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        
        return $this->proxyError();
     }

     
}