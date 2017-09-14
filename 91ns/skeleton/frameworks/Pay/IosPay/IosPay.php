<?php

namespace Micro\Frameworks\Pay\IosPay;

use Micro\Frameworks\Pay\PayGatewayFactory as Payment;
use Micro\Frameworks\Pay\OrderMgr as OrderMgr;
use Micro\Frameworks\Logic\User\UserData\UserCash as UserCash;
use Phalcon\DI\FactoryDefault;
use Micro\Models\Order;

//推广充值
class IosPay {

	protected $di;
	protected $request;
    protected $config;
    protected $orderMgr;
    protected $status;
    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->request = $this->di->get('request');
        $this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->orderMgr = new OrderMgr();
        $this->userCash = new UserCash();
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【alipay】 error : ' . $errInfo);
    }

    public function payReturn($post){
    	//插入数据
        $this->errLog('isotest:');
        $this->errLog('iosLog:' . json_encode($post));
        $apple_receipt = $post['apple_receipt'];
        $jsonData = array('receipt-data' => $apple_receipt);
        $jsonData = json_encode($jsonData);
        $url = 'https://buy.itunes.apple.com/verifyReceipt'; // 正式用
        $sandboxurl = 'https://sandbox.itunes.apple.com/verifyReceipt'; // 测试用
        $response = $this->http_post_data($url, $jsonData);
        $this->errLog($response->{'status'});
//        var_dump($response->{'status'});die;
        if($response->{'status'} == 21007){
            $response = $this->http_post_data($sandboxurl, $jsonData);
        }
//var_dump($jsonData);die;
        if($response->{'status'} == 0){
            $orderId = $post['out_trade_no']; //订单号
            $tradeNo = $post['trade_no'] ? $post['trade_no'] : $post['out_trade_no']; //交易流水号
            $result = $this->orderMgr->editOrder($orderId, $tradeNo);
            $data = array();
            if ($result) {//修改订单状态成功
                $data = $this->orderMgr->paySuccessOperation($orderId);
                return $this->status->retFromFramework($this->status->getCode('OK'), $response->{'status'});
            } else {//前端展示
                //查询订单
                $orderInfo = \Micro\Models\Order::findFirst("orderId = '{$orderId}' and status = " . $this->config->payStatus->success);
                if ($orderInfo != false) {
                    $data['payType'] = $orderInfo->payType; //支付方式
                    $data['orderId'] = $orderId; //订单号
                    return $this->status->retFromFramework($this->status->getCode('OK'), $response->{'status'});
                }
            }
        }else{
            return $this->status->retFromFramework($this->status->getCode('IOS_PAY_FAIL'), $response->{'status'});
        }
    }

    public function http_post_data($url, $data_string) {
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL, $url);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle,CURLOPT_HEADER, 0);
        curl_setopt($curl_handle,CURLOPT_POST, true);
        curl_setopt($curl_handle,CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl_handle,CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER, 0);
        $response_json = curl_exec($curl_handle);
        $response = json_decode($response_json);
        curl_close($curl_handle);
        return $response;
    }

}