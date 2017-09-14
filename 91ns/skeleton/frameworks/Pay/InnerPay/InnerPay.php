<?php

namespace Micro\Frameworks\Pay\InnerPay;

use Micro\Frameworks\Pay\PayGatewayFactory as Payment;
use Micro\Frameworks\Pay\OrderMgr as OrderMgr;
use Micro\Frameworks\Logic\User\UserData\UserCash as UserCash;
use Phalcon\DI\FactoryDefault;
use Micro\Models\Order;

//推广充值
class InnerPay {

	protected $di;
	protected $request;
    protected $config;
    protected $orderMgr;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->request = $this->di->get('request');
        $this->config = $this->di->get('config');
        $this->orderMgr = new OrderMgr();
        $this->userCash = new UserCash();
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【alipay】 error : ' . $errInfo);
    }

    public function pay($RMB, $uid){

    	//插入数据
    	$orderId = date('YmdHis') . mt_rand(100000, 999999);
        $order = new Order();
        $order->uid = $uid;
        $order->orderId = $orderId;
        $order->createTime = time();
        $order->payTime = time();
        $order->cashNum = $RMB * $this->config->cashScale;
        $order->totalFee = $RMB;
        $order->payType = $this->config->payType->innerpay->id;
        $order->status = $this->config->payStatus->ing;
        $result = $order->save();

    	if($result){//操作成功

    		$totalFee = $order->totalFee; //金额
		    $cashNum = $order->cashNum; //聊币数量
		    $vipNum = $order->totalFee; //经验值

            //给用户添加聊币、经验值
            $this->userCash->addUserCash($cashNum, $uid, $vipNum);
            //写入聊币记录表
            $this->userCash->addCashLog($cashNum, $this->config->cashSource->innerpay, $orderId, $uid);
	    	// $uid = $this->orderMgr->editOrder($orderId, $tradeNo);

	        echo json_encode(array('code'=>'0','info'=>'充值成功'));
	        exit;
    	}

    	echo json_encode(array('code'=>'1','info'=>'充值失败'));
    	exit;
	    	
    }
}