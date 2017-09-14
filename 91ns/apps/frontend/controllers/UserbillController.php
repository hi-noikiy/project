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
        }
        $this->view->isFirstCharge = $isFirstCharge;

        //if($isFirstCharge){
        //}
    }
	
	public function getBillAction(){

            
        if ($this->request->isPost()) {
           
            $type = $this->request->getPost('type');
            $time =  $this->request->getPost('time');            
            if(!empty($type) && !empty($time)){

                $result = $this->userMgr->consumeList($type,$time);
                if ($result['code'] == $this->status->getCode('OK')) {
                    // $this->status->ajaxReturn($result['code'], $result);\
                    $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                }
               
                // $this->status->ajaxReturn($result['code'], $result);
                $this->status->ajaxReturn($result['code'], $result['data']);
                
            }
        }
        
        $this->proxyError(); 


         //        if ($this->request->isPost()) {

         //            $type = $this->request->getPost('type');   //getGift收到的礼物,sendGift送出的礼物,recharge充值记录consumer消费记录
         //            $time = $this->request->getPost('time');            
         //            $result = $this->userMgr->consumeList($type,$time);
         //               if ($result['code'] == $this->status->getCode('OK')) {

         //                  $this->status->ajaxReturn($this->status->getCode('OK'));
         //               }               
         //                // $this->status->ajaxReturn($result['code'], $result['data']);
         //               $this->status->ajaxReturn($result['code'], $result);
                    
         //        }
         //        $this->proxyError();			 
	 }

     
}