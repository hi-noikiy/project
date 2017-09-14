<?php

namespace Micro\Frameworks\Pay\Wxpay;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Pay\OrderMgr as OrderMgr;

//微信支付appstore接口
class WxpayAppstorePush {

    protected $request;
    protected $di;
    protected $config;
    protected $orderMgr;
    protected $status;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->request = $this->di->get('request');
        $this->config = $this->di->get('config');
        $this->orderMgr = new OrderMgr();
        $this->status = $this->di->get('status');
        require_once($this->config->miscellaneous->wxpayappstore);
    }

    public function wxMobileOrder($totalFee){
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->wxpayappstore->id, 0);
        if (empty($orderId)) {
            return FALSE;
        }

        $result['orderId'] = $orderId;
        return $result;
    }

    public function wxMobileReturn(){
        $notify = new \Notify_pub();
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $logger = $this->di->get('payLogs');
        $logger->info($xml);
        $notify->saveData($xml);
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $data = $notify->getData();
            $orderId = $data['out_trade_no'];
            $sd51no = $data['transaction_id'];
            $totalFee = $data['total_fee'] / 100.0;
            $result = $this->orderMgr->editOrder($orderId, $sd51no, $totalFee);
            if ($result) {//修改订单状态成功
                $this->orderMgr->paySuccessOperation($orderId);
                $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
            }
        }

        $returnXml = $notify->returnXml();
        echo $returnXml;
    }

}
