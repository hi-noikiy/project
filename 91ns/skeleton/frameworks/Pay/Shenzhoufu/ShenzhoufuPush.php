<?php

namespace Micro\Frameworks\Pay\Shenzhoufu;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Pay\OrderMgr as OrderMgr;
use Micro\Frameworks\Logic\User\UserData\UserCash as UserCash;

//神州付支付接口
class ShenzhoufuPush {

    protected $request;
    protected $di;
    protected $config;
    protected $orderMgr;
    protected $validator;
    protected $userCash;
    protected $taskMgr;
    protected $status;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->request = $this->di->get('request');
        $this->config = $this->di->get('config');
        $this->orderMgr = new OrderMgr();
        $this->validator = $this->di->get('validator');
        $this->taskMgr = $this->di->get('taskMgr');
        $this->status = $this->di->get('status');
        $this->userCash = new UserCash();
        $this->payUrl = "http://pay3.shenzhoufu.com/interface/version3/entry.aspx";
    }

    //生成参数
    public function setParam($totalFee, $orderType = 0,$receiveUid = 0) {
        $postData['money'] = $totalFee;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //生产订单id
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->shenzhoufu->id, $orderType,$receiveUid);
        $version = 3;
        $merId = $this->config->pay->shenzhoufu->partner;
        $payMoney = $totalFee * 100;
        $pageReturnUrl = $this->config->webType[$this->config->channelType]['domain'] . $this->config->pay->shenzhoufu->return;
        $serverReturnUrl = $this->config->webType[$this->config->channelType]['domain'] . $this->config->pay->shenzhoufu->notify;
        $privateKey = $this->config->pay->shenzhoufu->key;
        $privateField = '';
        $verifyType = 1;
        $returnType = 3;
        $isDebug = 0;

        $combineString = $version . "|" . $merId . "|" . $payMoney . "|" . $orderId . "|" . $pageReturnUrl . "|" . $serverReturnUrl . "|" . $privateField . "|" . $privateKey . "|" . $verifyType . "|" . $returnType . "|" . $isDebug;
        $md5String = md5($combineString);
        $params = array(
            'version' => $version, //版本号
            'merId' => $merId, //商户 ID
            'payMoney' => $payMoney, //订单金额,单位：分
            'orderId' => $orderId, //订单号
            'pageReturnUrl' => $pageReturnUrl, //页面返回地址
            'serverReturnUrl' => $serverReturnUrl, //服务器返回地址
            'merUserName' => '', //支付用户名
            'merUserMail' => '', //支付用户 Email
            'itemName' => $this->config->webType[$this->config->channelType]['paySubject'], //产品名称
            'itemDesc' => $this->config->webType[$this->config->channelType]['paySubject'], //产品描述
            'bankId' => '', //平台银行 ID
            'privateField' => $privateField, //商户私有数据
            'md5String' => $md5String, //MD5 校验串
            'gatewayId' => 0, //支付方式 id
            // 'cardTypeCombine' => $cardType ? $cardType : 0, //充值卡类型
            'verifyType' => $verifyType, //数据校验方式
            'returnType' => $returnType, //返回结果方式
            'isDebug' => $isDebug, //是否调试
        );
        $return['url'] = $this->payUrl . '?' . http_build_query($params);
        $return['orderId'] = $orderId;
        return $return;
    }

    //同步回调
    public function verifyReturn() {
        //写入日志start
        $logger = $this->di->get('payLogs');
        $logger->info(json_encode($_REQUEST));
        //end

        $privateKey = $this->config->pay->shenzhoufu->key;
        $certFile = APP_PATH . "/skeleton/frameworks/Pay/Shenzhoufu/ShenzhoufuPay.cer"; //神州付证书文件
        //获得服务器返回数据
        $version = $_REQUEST['version']; //版本号
        $merId = $_REQUEST['merId']; //商户ID
        $payMoney = $_REQUEST['payMoney']; //支付金额
        $orderId = $_REQUEST['orderId']; //订单号
        $payResult = $_REQUEST['payResult']; //支付结果 1:支付成功 0：支付失败
        $privateField = $_REQUEST['privateField']; //商户私有数据
        $payDetails = $_REQUEST['payDetails']; //支付详情
        $md5String = $_REQUEST['md5String']; //MD5校验串
        $signString = $_REQUEST['signString']; //证书签名
        //进行MD5校验
        $myCombineString = $version . $merId . $payMoney . $orderId . $payResult . $privateField . $payDetails . $privateKey;
        $myMd5String = md5($myCombineString);
        if ($myMd5String == $md5String) {
            //echo "MD5校验成功!</br>";
            //校验证书签名
            $fp = fopen($certFile, "r");
            $cert = fread($fp, 8192);
            fclose($fp);
            $pubkeyid = openssl_get_publickey($cert);

            If (openssl_verify($myMd5String, base64_decode($signString), $pubkeyid, OPENSSL_ALGO_MD5) == 1) {
                //echo $orderId; //响应服务器
                // echo "二级签名校验成功！";
                //todo...商户业务逻辑
                if ($payResult == 1) {
                    //todo...支付成功
                    //写入数据库，修改订单状态
                    $result = $this->orderMgr->editOrder($orderId, 0);
                    $data = array();
                    if ($result) {//修改订单状态成功
                        $data = $this->orderMgr->paySuccessOperation($orderId);
                        return $data;
                    } else {//前端展示
                        //查询订单
                        $orderInfo = \Micro\Models\Order::findFirst("orderId='{$orderId}' and status=" . $this->config->payStatus->success);
                        if ($orderInfo != false) {
                            $data['payType'] = $orderInfo->payType; //支付方式
                            $data['orderId'] = $orderId; //订单号
                            return $data;
                        }
                    }
                } else {
                    //todo...支付失败
                }
            } else {
                // echo "二级签名校验失败！";
                // while ($msg = openssl_error_string()) {
                // echo $msg . "<br/>\n";
                //  }
            }
        } else {
            //echo 'MD5校验失败';
        }
        return;
    }

    //异步回调
    public function verifyNotify() {
        //写入日志start
        $logger = $this->di->get('payLogs');
        $logger->info(json_encode($_REQUEST));
        //end
        $privateKey = $this->config->pay->shenzhoufu->key; //密钥
        $certFile = APP_PATH . "/skeleton/frameworks/Pay/Shenzhoufu/ShenzhoufuPay.cer"; //神州付证书文件
        //获得服务器返回数据
        $version = $_REQUEST['version']; //版本号
        $merId = $_REQUEST['merId']; //商户ID
        $payMoney = $_REQUEST['payMoney']; //支付金额
        $orderId = $_REQUEST['orderId']; //订单号
        $payResult = $_REQUEST['payResult']; //支付结果 1:支付成功 0：支付失败
        $privateField = $_REQUEST['privateField']; //商户私有数据
        $payDetails = $_REQUEST['payDetails']; //支付详情
        $md5String = $_REQUEST['md5String']; //MD5校验串
        $signString = $_REQUEST['signString']; //证书签名
        //进行MD5校验
        $myCombineString = $version . $merId . $payMoney . $orderId . $payResult . $privateField . $payDetails . $privateKey;
        $myMd5String = md5($myCombineString);

        if ($myMd5String == $md5String) {
            // echo "MD5校验成功!</br>";
            //校验证书签名
            $fp = fopen($certFile, "r");
            $cert = fread($fp, 8192);
            fclose($fp);
            $pubkeyid = openssl_get_publickey($cert);

            If (openssl_verify($myMd5String, base64_decode($signString), $pubkeyid, OPENSSL_ALGO_MD5) == 1) {
                echo $orderId; //响应服务器
                // echo "二级签名校验成功！";
                //todo...商户业务逻辑
                if ($payResult == 1) {
                    //todo...支付成功
                    //..............逻辑处理..............
                    //写入数据库，修改订单状态
                    $result = $this->orderMgr->editOrder($orderId, 0);
                    if ($result) {//操作成功
                        $this->orderMgr->paySuccessOperation($orderId);
                        exit;
                    }
                } else {
                    //todo...支付失败
                }
            } else {
                echo "二级签名校验失败！";
                while ($msg = openssl_error_string()) {
                    echo $msg . "<br/>\n";
                }
            }
        } else {
            echo 'MD5校验失败';
        }
        exit;
    }

}
