<?php

namespace Micro\Frameworks\Pay\Alipay;

use Micro\Frameworks\Pay\PayGatewayFactory as Payment;
use Micro\Frameworks\Pay\OrderMgr as OrderMgr;
use Micro\Frameworks\Pay\Alipay\AlipayNotify as AlipayNotify;
use Phalcon\DI\FactoryDefault;

//支付宝支付接口
class AlipayPush {

    protected $payment;
    protected $request;
    protected $di;
    protected $config;
    protected $orderMgr;

    public function __construct() {
        $this->payment = new Payment();
        $this->di = FactoryDefault::getDefault();
        $this->request = $this->di->get('request');
        $this->config = $this->di->get('config');
        $this->orderMgr = new OrderMgr();
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【alipay】 error : ' . $errInfo);
    }

    //支付宝即时支付
    public function alipay($totalFee, $orderType = 0, $receiveUid = 0) {
        $directPay = $this->payment->getGateway('AlipayExpress');
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->alipay->id, $orderType, $receiveUid);
        $options = array(
            'out_trade_no' => $orderId,
            'subject' => $this->config->webType[$this->config->channelType]['paySubject'],
            'total_fee' => $totalFee,
        );

        $response = $directPay->purchase($options)->send();
        $return['orderId'] = $orderId;
        $return['url'] = $response->getRedirectUrl();
        return $return;
//        if ($response->isRedirect()) {
//            $response->redirect();
//        }
    }

    //支付宝网银支付
    public function alipay2($totalFee, $bankname, $orderType = 0, $receiveUid = 0) {
        $directPay = $this->payment->getGateway('AlipayBank');
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->alipay2->id, $orderType, $receiveUid);
        $bankcode = $this->payment->getBankCode($bankname);
        !$bankcode && $bankcode = 'BOCB2C';
        $options = array(
            'out_trade_no' => $orderId,
            'subject' => $this->config->webType[$this->config->channelType]['paySubject'],
            'total_fee' => $totalFee,
            'defaultbank' => $bankcode
        );

        $response = $directPay->purchase($options)->send();
        $return['orderId'] = $orderId;
        $return['url'] = $response->getRedirectUrl();
        return $return;
//return $response->getRedirectUrl();
//        if ($response->isRedirect()) {
//            $response->redirect();
//        }
    }

    //支付宝扫码支付
    public function alipay3($totalFee, $orderType = 0, $receiveUid = 0) {
        $directPay = $this->payment->getGateway('AlipayQrcode');
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->alipay3->id, $orderType, $receiveUid);
        $options = array(
            'out_trade_no' => $orderId,
            'subject' => $this->config->webType[$this->config->channelType]['paySubject'],
            'total_fee' => $totalFee
        );

        $response = $directPay->purchase($options)->send();
        return $response->getRedirectUrl();
//        if ($response->isRedirect()) {
//            $response->redirect();
//        }
    }

    /**
     * 支付宝手机支付
     *
     * @param $totalFee
     * @return int|string
     */
    public function alipay4($totalFee, $orderType = 0, $receiveUid = 0) {
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->alipay4->id, $orderType, $receiveUid);
        return $orderId;
    }

    /**
     * 支付宝wap
     *
     * @param $totalFee
     * @return int|string
     */
    public function alipay5($totalFee, $orderType = 0, $receiveUid = 0) {
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->alipay5->id, $orderType, $receiveUid);

        //字符编码格式 目前支持 gbk 或 utf-8
        $input_charset = strtolower('utf-8');
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.create.direct.pay.by.user",
            "partner" => $this->config->pay->alipay->partner,
            "seller_id" => $this->config->pay->alipay->seller_id,
            "payment_type" => 1,
            "notify_url" => $this->config->webType[$this->config->channelType]['domain'] . $this->config->pay->alipay->notify,
            "return_url" => $this->config->webType[$this->config->channelType]['domain'] . $this->config->pay->alipay->return,
            "out_trade_no" => $orderId,
            "subject" => $this->config->webType[$this->config->channelType]['paySubject'],
            "total_fee" => $totalFee,
            "_input_charset" => trim(strtolower($input_charset))
        );
        //建立请求
        $url = $this->buildRequestUrl($parameter);
        $return['orderId'] = $orderId;
        $return['url'] = $url;
        return $return;
    }

    /**
     * 支付宝app内嵌h5版
     *
     * @param $totalFee
     * @return int|string
     */
    public function alipay6($totalFee, $orderType = 0, $receiveUid = 0) {
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->alipay5->id, $orderType, $receiveUid);

        //字符编码格式 目前支持 gbk 或 utf-8
        $input_charset = strtolower('utf-8');
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.create.direct.pay.by.user",
            "partner" => $this->config->pay->alipay->partner,
            "seller_id" => $this->config->pay->alipay->seller_id,
            "payment_type" => 1,
            "notify_url" => $this->config->webType[$this->config->channelType]['mDomain'] . $this->config->pay->alipay->notify,
            "return_url" => $this->config->webType[$this->config->channelType]['mDomain'] . $this->config->pay->alipay->app_return,
            "out_trade_no" => $orderId,
            "subject" => $this->config->webType[$this->config->channelType]['paySubject'],
            "total_fee" => $totalFee,
            "_input_charset" => trim(strtolower($input_charset))
        );
        //建立请求
        $url = $this->buildRequestUrl($parameter);
        $return['orderId'] = $orderId;
        $return['url'] = $url;
        return $return;
    }

    //同步回调
    public function verifyReturn() {
        //写入日志start
        $logger = $this->di->get('payLogs');
        $logger->info(json_encode($this->request->getQuery()));
        //end
        if ($this->request->isGet()) {
            $options = array('request_params' => $this->request->getQuery());
            $complateGateway = $this->payment->getCompleteGateway('Alipay');
            try {
                $response = $complateGateway->complete($options)->send();
                if ($response->isSuccessful()) {
                    if ($response->isTradeStatusOk()) {//成功
                        //echo 'tradeOK';
                        //写入数据库，修改订单状态
                        $orderId = $this->request->get('out_trade_no'); //订单号
                        $tradeNo = $this->request->get('trade_no'); //交易流水号
                        $result = $this->orderMgr->editOrder($orderId, $tradeNo);
                        $data = array();
                        if ($result) {//修改订单状态成功
                            $data = $this->orderMgr->paySuccessOperation($orderId);
                            return $data;
                        } else {//前端展示
                            //查询订单
                            $orderInfo = \Micro\Models\Order::findFirst("orderId = '{$orderId}' and status = " . $this->config->payStatus->success);
                            if ($orderInfo != false) {
                                $data['payType'] = $orderInfo->payType; //支付方式
                                $data['orderId'] = $orderId; //订单号
                                return $data;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->errLog('alipayVerifyReturn error : errorMessage = ' . $e->getMessage());
            }
        }

        //失败
        // echo 'fail';  
        return;
    }

    //异步回调
    public function verifyNotify() {
        //写入日志start
        $logger = $this->di->get('payLogs');
        $logger->info(json_encode($this->request->getPost()));
        //end
        if ($this->request->isPost()) {
            $options = array('request_params' => $this->request->getPost());
            $complateGateway = $this->payment->getCompleteGateway('Alipay');
            $response = $complateGateway->complete($options)->send();

            if ($response->isSuccessful()) {
                if ($response->isTradeStatusOk()) {
                    $orderId = $this->request->getPost('out_trade_no'); //商户订单号
                    $tradeNo = $this->request->getPost('trade_no'); //支付宝交易号
                    //..............逻辑处理..............
                    //写入数据库，修改订单状态
                    $result = $this->orderMgr->editOrder($orderId, $tradeNo);
                    if ($result) {//操作成功
                        $this->orderMgr->paySuccessOperation($orderId);
                    }
                    echo "success"; //服务器后台逻辑处理，该值必须返回
                    exit(0);
                }
            }
        }
        echo 'fail'; //服务器后台逻辑处理，该值必须返回
    }

    public function AlipayNotify() {
        //写入日志start
        $logger = $this->di->get('payLogs');
        $logger->info(json_encode($this->request->getPost()));
        if ($this->request->isPost()) {
            $alipayNotify = new AlipayNotify();
            $verify_result = $alipayNotify->verifyNotify();
            if ($verify_result) {//验证成功
                //商户订单号
                $orderId = $this->request->getPost('out_trade_no');
                //支付宝交易号
                $tradeNo = $this->request->getPost('trade_no');
                //交易状态
                $trade_status = $this->request->getPost('trade_status');

                if ($trade_status == 'TRADE_FINISHED') {
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
                    //注意：
                    //该种交易状态只在两种情况下出现
                    //1、开通了普通即时到账，买家付款成功后。
                    //2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
                    //写入数据库，修改订单状态
                    $result = $this->orderMgr->editOrder($orderId, $tradeNo);
                    if ($result) {//操作成功
                        $this->orderMgr->paySuccessOperation($orderId);
                    }
                } else if ($trade_status == 'TRADE_SUCCESS') {
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
                    //注意：
                    //该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。

                    $result = $this->orderMgr->editOrder($orderId, $tradeNo);
                    if ($result) {//操作成功
                        $this->orderMgr->paySuccessOperation($orderId);
                    }
                }

                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

                echo "success";  //请不要修改或删除
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            } else {
                //验证失败
                echo "fail";

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
        }
    }

    /**
     * 建立请求,构造url
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return url
     */
    function buildRequestUrl($para_temp) {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);
        $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?'; //网关地址
        $str = '';
        foreach ($para as $key => $val) {
            $str.="&" . $key . "=" . $val;
        }
        $url = $alipay_gateway_new . $str;
        return $url;
    }

    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    function buildRequestPara($para_temp) {
        $sign_type = 'MD5';
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);
        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);
        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper(trim($sign_type));
        return $para_sort;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    function createLinkstring($para) {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg.=$key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    function buildRequestMysign($para_sort) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
        $key = $this->config->pay->alipay->key;
        $mysign = md5($prestr . $key);
        return $mysign;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each($para)) {
            if ($key == "sign" || $key == "sign_type" || $val == "")
                continue;
            else
                $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

}
