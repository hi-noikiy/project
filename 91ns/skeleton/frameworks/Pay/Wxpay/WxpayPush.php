<?php

namespace Micro\Frameworks\Pay\Wxpay;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Pay\OrderMgr as OrderMgr;
use Micro\Frameworks\Logic\User\UserData\UserCash as UserCash;
use Micro\Frameworks\Pay\Pingpp\Pingpp as Pingpp;
//use Micro\Frameworks\Pay\Wxpay\WxPayException as SDKRuntimeException;
//use Micro\Frameworks\Pay\Wxpay\WxPayConfig;
//use Micro\Frameworks\Pay\Wxpay\Common_util_pub;
//use Micro\Frameworks\Pay\Wxpay\Wxpay_client_pub;
//use Micro\Frameworks\Pay\Wxpay\UnifiedOrder_pub;
//use Micro\Frameworks\Pay\Wxpay\OrderQuery_pub;
//use Micro\Frameworks\Pay\Wxpay\Wxpay_server_pub;
//use Micro\Frameworks\Pay\Wxpay\Notify_pub;
//use Micro\Frameworks\Pay\Wxpay\NativeCall_pub;

//微信支付接口
class WxpayPush {

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
        $this->payUrl = "http://www.zhifuka.net/gateway/weixin/weixinpay.asp";
        require_once($this->config->miscellaneous->wxpay);
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
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->wxpay->id, $orderType,$receiveUid);
        $customerid = $this->config->pay->wxpay->partner; //商户号
        $sdcustomno = $orderId; //订单号
        $orderAmount = $totalFee * 100; //订单支付金额；单位:分(人民币)
        $cardno = 32; //固定值32为（微信）  36为（手机QQ）
        $noticeurl = $this->config->webType[$this->config->channelType]['domain'] . $this->config->pay->wxpay->notify; //异步通知
        $backurl = $this->config->webType[$this->config->channelType]['domain'] . $this->config->pay->wxpay->return . "?orderId=" . $orderId; //同步通知
        $key = $this->config->pay->wxpay->key;
        $mark = '132'; //商户自定义信息,例如 数字+英文字母 不能存在中文 例如：test123
        $subject = $this->config->webType[$this->config->channelType]['paySubject'];
        $subject = iconv("UTF-8", "gb2312//IGNORE", $subject);
        $remarks = urlencode($subject); //支付商品名称

        $Md5str = 'customerid=' . $customerid . '&sdcustomno=' . $sdcustomno . '&orderAmount=' . $orderAmount . '&cardno=' . $cardno . '&noticeurl=' . $noticeurl . '&backurl=' . $backurl . $key;
        $sign = strtoupper(md5($Md5str));

        $gourl = $this->payUrl . '?customerid=' . $customerid . '&sdcustomno=' . $sdcustomno . '&orderAmount=' . $orderAmount . '&cardno=' . $cardno . '&noticeurl=' . $noticeurl . '&backurl=' . $backurl . '&sign=' . $sign . '&mark=' . $mark . '&remarks=' . $remarks;

        $return['url'] = $gourl;
        $return['orderId'] = $orderId;
        return $return;
    }

    //同步回调
    public function verifyReturn() {
        //写入日志start
        $logger = $this->di->get('payLogs');
        $logger->info(json_encode($_GET));
        $orderId = trim($_GET["orderId"]);
        $data['payType'] = $this->config->payType->wxpay->id; //支付方式
        $data['orderId'] = $orderId; //订单号
        return $data;
    }

    //异步回调
    public function verifyNotify() {
        header("Content-type: text/html; charset=gb2312"); 
            
        //写入日志start
        $logger = $this->di->get('payLogs');
        $logger->info(json_encode($_GET));
        //end
        $state = isset($_GET["state"])?trim($_GET["state"]):0;            // 1:充值成功 2:充值失败
        $customerid = isset($_GET["customerid"])?trim($_GET["customerid"]):0; //商户注册的时候，网关自动分配的商户ID
        $sd51no = isset($_GET["sd51no"])?trim($_GET["sd51no"]):0;          //该订单在网关系统的订单号
        $orderId = isset($_GET["sdcustomno"])?trim($_GET["sdcustomno"]):0;  //该订单在商户系统的流水号
        $ordermoney = isset($_GET["ordermoney"])?trim($_GET["ordermoney"]):0;  //商户订单实际金额单位：（元）
        $cardno = isset($_GET["cardno"])?trim($_GET["cardno"]):0;         //支付类型，为固定值 32
        $mark = isset($_GET["mark"])?trim($_GET["mark"]):'';              //未启用暂时返回空值
        $sign = isset($_GET["sign"])?trim($_GET["sign"]):'';           //发送给商户的签名字符串
        $resign = isset($_GET["resign"])?trim($_GET["resign"]):'';      //发送给商户的签名字符串
        $des = isset($_GET["des"])?trim($_GET["des"]):'';       //描述订单支付成功或失败的系统备注
        //*验证处理
        //*根据自己需要验证参数（1.验证是否为星启天通知过来的（可做IP限制）[必须] 2.验证参数合法性[可选]）
        //**************************************************************************************************
        $key = $this->config->pay->wxpay->key; //key可从星启天网关客服处获取
        $sign2 = strtoupper(md5("customerid=" . $customerid . "&sd51no=" . $sd51no . "&sdcustomno=" . $orderId . "&mark=" . $mark . "&key=" . $key));
        $resign2 = strtoupper(md5("sign=" . $sign . "&customerid=" . $customerid . "&ordermoney=" . $ordermoney . "&sd51no=" . $sd51no . "&state=" . $state . "&key=" . $key));
        if ($sign != $sign2) {
            echo "签名不正确";
            //记录日志
            exit();
        }
        if ($resign != $resign2) {
            echo "签名不正确";
            //记录日志
            exit();
        }

        //**************************************************************************
        //*商户系统业务逻辑处理
        //**************************************************************************
        //商户在接受到网关通知时，应该打印出<result>1</result>标签，以供接口程序抓取信息，以便于我们获取是否通知成功的信息，否则订单会显示没有通知商户
            
        if ($state == "1") {
            //当充值成功后同步商户系统订单状态
            //此处编写商户系统处理订单成功流程
            //写入数据库，修改订单状态
            $result = $this->orderMgr->editOrder($orderId, $sd51no);
            if ($result) {//修改订单状态成功
                $this->orderMgr->paySuccessOperation($orderId);
             }
        }
        echo "OK";   
    }

    public function mobileVerifyNotify() {
        $logger = $this->di->get('payLogs');
        $post = file_get_contents("php://input");
        $logger->info($post);
        $sign = $_SERVER['HTTP_X_PINGPLUSPLUS_SIGNATURE'];
        if (!empty($post)) {
            $input = new Pingpp();
            $res = $input->checkOrder($post, $sign);
            if ($res == 1) {
                $post = json_decode($post, TRUE);
                $paid = $post['data']['object']['paid'];
                if ($paid) {
                    $orderId = $post['data']['object']['order_no'];
                    $sd51no = $post['data']['object']['transaction_no'];
                    $result = $this->orderMgr->editOrder($orderId, $sd51no);
                    if ($result) {//修改订单状态成功
                        $this->orderMgr->paySuccessOperation($orderId);
                        echo '2xx';
                        exit;
                    }
                }
            } else if ($res == 0) {
                echo 'verification failed';
            } else {
                echo 'verification error';
            }
        }
    }

    public function mobilePingOrder($data) {
        if (empty($data) || empty($data['channel']) || empty($data['amount'])) {
            return FALSE;
        }

        switch ($data['channel']) {
            case 'wx':
                $id = $this->config->payType->mobilewxpay->id;
                break;
            case 'alipay':
                $id = $this->config->payType->alipay4->id;
                break;
        }
        $orderId = $this->orderMgr->addOrder($data['amount'], $id, 0);

        if (empty($orderId)) {
            return FALSE;
        }

        $input = new Pingpp();
        $data['amount'] = $data['amount'] * 100.0;
        $result = $input->addWxOrder($data, $orderId);
        return json_encode(json_decode($result, TRUE));
    }

    public function wxMobileOrder($totalFee){
        $orderId = $this->orderMgr->addOrder($totalFee, $this->config->payType->mobilewxpay->id, 0);
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
