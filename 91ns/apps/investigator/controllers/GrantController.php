<?php

namespace Micro\Controllers;

class GrantController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        $this->redirect('grant/sendCar');
    }

    public function sendCarAction() {
        if ($this->request->isPost()) {
            $result = $this->invMgr->sendUserCar($this->request->getPost('uid'), $this->request->getPost('carId'), $this->request->getPost('day'));
            $this->status->ajaxReturn($result['code'], $result);
        }else{
        	$cars=\Micro\Models\CarConfigs::find(
        			array(
                    "conditions" => "typeId not in(4,1000,6,7,8,9)",
                ));
        	$this->view->setVar('cars', $cars->toArray());
        }
    }
	
    /**
     * 发送vip
     */
    public function sendVipAction() {
    	if ($this->request->isPost()) {
    		$day = $this->request->getPost('day') ;
    		if(!is_numeric($day) || $day < 0){
    			$result['code'] = 1;
    			$result['info'] = '请输入正整数日期';
    			return $this->status->newAjaxReturn($result);
    		}else if($day > 100){
    			$result['code'] = 1;
    			$result['info'] = '最多一次发放100天VIP';
    			return $this->status->newAjaxReturn($result);
    		}
    		$result = $this->invMgr->sendUserVip($this->request->getPost('uid'), $this->request->getPost('vipId'), $day);
    		$this->status->ajaxReturn($result['code'], $result);
    	}else{
    		$vips=\Micro\Models\VipConfigs::find();
    		$vipsarr = $vips->toArray();
    		foreach ($vipsarr as $k =>$v){
    			if($v['level'] == '1'){
    				$vipsarr[$k]['name'] = '普通VIP';
    			}else if($v['level'] == '2'){
    				$vipsarr[$k]['name'] = '至尊VIP';
    			}
    		}
    		$this->view->setVar('vips', $vipsarr);
    	}
    }
    
    
    public function sendBadgeAction() {
        if ($this->request->isPost()) {
            $result = $this->invMgr->sendUserBadge($this->request->getPost('uid'), $this->request->getPost('itemId'), $this->request->getPost('day'));
            $this->status->ajaxReturn($result['code'], $result);
        }
    }

    public function groupAction() {
        
    }

    //徽章发放/撤销
    public function sendChargeBadgeAction() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $itemId = $this->request->getPost('itemId');
            $day = $this->request->getPost('day');
            $type = $this->request->getPost('type');
            !$type && $type = 1;
            $result = $this->invMgr->sendChargeBadge($uid, $itemId, $day, $type);
            $this->status->ajaxReturn($result['code'], $result);
        }
    }

}
