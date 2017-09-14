<?php

namespace Micro\Controllers;

use Micro\Models\Order;
use Micro\Models\AppCount;
class CountController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        $this->redirect('count/analysis');
    }
    
    public function analysisAction(){
        

    }

        public function  getUserInfoAction(){
        if($this->request->isPost()){
            $user = $this->userAuth->getUser();
            if($user != NULL){
                $userInfo = $user->getUserInfoObject()->getData();
                $result = $this->userMgr->getUserLevelInfo($userInfo['uid']);
                if($result['code'] == $this->status->getCode('OK')){
                    $userInfo['levelInfo'] = $result['data'];
                }
                $userInfo['cash'] = $userInfo['cash'];
                //消息
                $result = $this->userMgr->isHasUnRead();
                $userInfo['news'] = 0;
                if($result['code'] == $this->status->getCode('OK')){
                    $userInfo['news'] = $result['data'];
                }
                $this->status->ajaxReturn($this->status->getCode('OK'), $userInfo);
            }else{
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }
    }

    public function activityStatisticsAction(){
        

    }
    public function rechargeAnalysisAction(){
        

    }


    /**
     * 导出充值记录
     *
     * @author 王涛
     */
    public function getRechargeExcelAction() {
    	$return = array();
    	try {
    		$startDate = $_POST['startDate'];
    		$endDate = $_POST['endDate'];
    		$namelike = $_POST['namelike'];
    		
    		$exp = ' o.status=1 ';
    		if ($startDate) {
    			$exp.=" and o.payTime>=" . strtotime($startDate);
    		}
    		if ($endDate) {
    			$endtime = strtotime($endDate) + 86400;
    			$exp.=" and o.payTime<" . $endtime;
    		}
    		if ($namelike) {
    			$exp.=" and ((o.uid like '%" . $namelike . "%' OR ui.nickName like'%" . $namelike . "%' ))";
    		}
    		$sql = "select o.uid,ui.nickName,o.payTime,o.orderId,o.cashNum,o.totalFee,o.payType"
    				. " from pre_order o inner join pre_user_info ui on o.uid=ui.uid "
    						. " where " . $exp;
    		$sql.=" order by o.payTime desc";
    		$res = $this->db->fetchAll($sql);
    		$payTypes=$this->config->payType->toArray();
    		foreach ($payTypes as $key => $payType){
    			$payTypess[$payType['id']] = $payType['name'];
    		}
    		$list = array();
    		foreach ($res as $val) {
    			$data['orderId'] = $val['orderId'];
    			$data['payTime'] = date('Y-m-d H:i:s',$val['payTime']);
    			$data['nickName'] =$val['nickName'];
    			$data['uid'] = $val['uid'];
    			$data['cash'] = $val['cashNum'];
    			$data['money'] = $val['totalFee'];
    			$data['payType'] = $payTypess[$val['payType']];
    			$list[] = $data;
    			unset($data);
    		}
    		$title=array("0"=>"交易号","1"=>"充值时间","2"=>"用户","3"=>"ID","4"=>"充值聊币","5"=>"支付金额","6"=>"充值渠道");
    		$this->exportexcel($list,$title);
    	} catch (\Exception $e) {
    		return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
    	}
    }
    
    
    public function appCountAction(){
    	
    }
   

}
