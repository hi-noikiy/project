<?php

namespace Micro\Frameworks\Pay;

use Micro\Frameworks\Pay\Alipay\AlipayPush as Alipay;
use Micro\Frameworks\Pay\OrderMgr as OrderMgr;
use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Pay\Shenzhoufu\ShenzhoufuPush as shenzhoufu;
use Micro\Frameworks\Pay\Wxpay\WxpayPush as wxpay;
use Micro\Frameworks\Pay\Wxpay\WxpayAppstorePush as wxpayappstore;
use Micro\Frameworks\Pay\Baofoo\BaofooPay as baofoo;

//支付接口推送
class PayPushs {

    protected $di;
    protected $config;
    protected $status;
    protected $cashExchangeUrl;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        //$this->cashExchangeUrl = "http://twx.odao.com/odao-activity/xiuba/ exchange_liaobi_by_ingot.do"; //元宝兑换聊币接口 测试地址
        $this->cashExchangeUrl = "http://activity.7pmi.com/xiuba/exchange_liaobi_by_ingot.do"; //元宝兑换聊币接口 正式地址
        //$this->getIngotNumUrl = "http://twx.odao.com/odao-activity/xiuba/get_user_info.do"; //元宝数查询接口 测试地址
        $this->getIngotNumUrl = "http://activity.7pmi.com/xiuba/get_user_info.do"; //元宝数查询接口 正式地址
    }

    //支付宝即时支付
    public function alipay($totalFee, $orderType = 0, $receiveUid = 0) {
        $alipay = new Alipay();
        return $alipay->alipay($totalFee, $orderType, $receiveUid);
    }

    //支付宝网银支付
    public function alipay2($totalFee, $bankname, $orderType = 0, $receiveUid = 0) {
        $alipay = new Alipay();
        return $alipay->alipay2($totalFee, $bankname, $orderType, $receiveUid);
    }

    //支付宝扫码支付
    public function alipay3($totalFee, $orderType = 0, $receiveUid = 0) {
        $alipay = new Alipay();
        return $alipay->alipay3($totalFee, $orderType, $receiveUid);
    }

    //支付宝手机支付
    public function alipay4($totalFee, $orderType = 0, $receiveUid = 0) {
        $alipay = new Alipay();
        return $alipay->alipay4($totalFee, $orderType, $receiveUid);
    }

    //支付宝支付--wap版
    public function alipay5($totalFee, $orderType = 0, $receiveUid=0) {
        $alipay = new Alipay();
        return $alipay->alipay5($totalFee, $orderType, $receiveUid);
    }
    
    //支付宝支付--app内嵌h5版
    public function alipay6($totalFee, $orderType = 0, $receiveUid = 0) {
        $alipay = new Alipay();
        return $alipay->alipay6($totalFee, $orderType, $receiveUid);
    }

    public function alipayFromMobile() {
        $alipay = new Alipay();
        return $alipay->AlipayNotify();
    }

    //支付宝 同步通知
    public function alipayVerifyReturn() {
        $alipay = new Alipay();
        return $alipay->verifyReturn();
    }

    //支付宝 异步通知
    public function alipayVerifyNotify() {
        $alipay = new Alipay();
        return $alipay->verifyNotify();
    }

    //查询是否有首充
    public function checkPayStatus($orderId) {
        $orderMgr = new OrderMgr();
        return $orderMgr->getOrderStatus($orderId);
    }

    //豆子网站的元宝兑换聊币
    public function douziPay($totalFee) {
        $userAuth = $this->di->get('userAuth');
        $user = $userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        //$userID = $this->getThisUserID();
        $userInfo= \Micro\Models\Users::findfirst($uid);
        $userID=$userInfo->openId;
        $timestamp = time();
        $key = $this->config->oauth->douzi->appKey;
        $sign = md5($userID . md5($userID . $key) . $key . $timestamp);
        $params = array(
            'userID' => $userID, //用户ID
            'timestamp' => $timestamp, //时间戳
            'sign' => $sign, //加密签名
            'exchange_ingot_nums' => $totalFee, //要兑换的元宝数
        );
        $response = $this->curl($this->cashExchangeUrl, "get", $params);
        $result = json_decode($response, true);
        if ($result['result_code'] == 1) {//操作成功
            $addCashNum = $totalFee * $this->config->cashScale;
            try {
                //写入订单表
                $orderId = date('YmdHis') . mt_rand(100000, 999999);
                $order = new \Micro\Models\Order();
                $order->uid = $uid;
                $order->orderId = $orderId;
                $order->createTime = time();
                $order->cashNum = $addCashNum;
                $order->totalFee = $totalFee;
                $order->payType = $this->config->payType->ingot->id;
                $order->status = $this->config->payStatus->success;
                $order->payTime = time();
                $order->save();
                //增加聊币
                $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
                $userCash->addUserCash($addCashNum, $uid, 0);
                //写入聊币记录表
                $userCash->addCashLog($addCashNum, $this->config->cashSource->ingotExchange, $orderId, $uid);
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
            //返回
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } else {//操作失败
            return $this->status->retFromFramework($this->status->getCode('DOUZI_ERR'), $result['result_message']);
        }
    }

    //豆子网站的元宝数量查询
    public function douziIngotNum() {
        $userAuth = $this->di->get('userAuth');
        $user = $userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $userID = $this->getThisUserID();
        $timestamp = time();
        $key = $this->config->oauth->douzi->appKey;
        $sign = md5($userID . md5($userID . $key) . $key . $timestamp);
        $params = array(
            'userID' => $userID, //用户ID
            'timestamp' => $timestamp, //时间戳
            'sign' => $sign, //加密签名
        );
        $response = $this->curl($this->getIngotNumUrl, "get", $params);
        $result = json_decode($response, true);
        if ($result['result_code'] == 1) {//操作成功
            return $this->status->retFromFramework($this->status->getCode('OK'), $result['ingot_nums']);
        } else {//操作失败
            return $this->status->retFromFramework($this->status->getCode('DOUZI_ERR'), $result['result_message']);
        }
    }

    //豆子网站元宝兑换历史记录
    public function douziIngotExchangeList() {
        $userAuth = $this->di->get('userAuth');
        $user = $userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = array();
        try {
            $list = \Micro\Models\Order::find("uid=" . $user->getUid() . " and status=" . $this->config->payStatus->success . " and payType=" . $this->config->payType->ingot->id . " order by createTime desc");
            if ($list->valid()) {
                foreach ($list as $val) {
                    $data['cash'] = $val->cashNum; //聊币数
                    $data['ingot'] = $val->totalFee; //元宝数量
                    $data['createTime'] = $val->createTime; //时间戳
                    array_push($result, $data);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //神州付
    public function shenzhoufuPay($totalFee, $orderType = 0, $receiveUid = 0) {
        $shenzhoufu = new shenzhoufu();
        return $shenzhoufu->setParam($totalFee, $orderType, $receiveUid);
    }

    //神州付 同步通知
    public function shenzhoufuVerifyReturn() {
        $shenzhoufu = new shenzhoufu();
        return $shenzhoufu->verifyReturn();
    }

    //神州付 异步通知
    public function shenzhoufuVerifyNotify() {
        $shenzhoufu = new shenzhoufu();
        return $shenzhoufu->verifyNotify();
    }

    //微信支付
    public function wxpay($totalFee, $orderType = 0, $receiveUid = 0) {
        $pay = new wxpay();
        return $pay->setParam($totalFee, $orderType, $receiveUid);
    }

    //微信支付 同步通知
    public function wxpayVerifyReturn() {
        $pay = new wxpay();
        return $pay->verifyReturn();
    }

    //微信支付 异步通知
    public function wxpayVerifyNotify() {
        $pay = new wxpay();
        return $pay->verifyNotify();
    }

    /**
     * 微信手机支付
     *
     * @param $totalFee
     * @param int $orderType
     * @param int $receiveUid
     * @return mixed
     */
    public function wxPayAddOrder($totalFee) {
        $userAuth = $this->di->get('userAuth');
        $user = $userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $pay = new wxpay();
        $result = $pay->wxMobileOrder($totalFee);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function wxPayReturn() {
        $pay = new wxpay();
        return $pay->wxMobileReturn();
    }

    //appstore微信支付
    public function wxPayAppstoreAddOrder($totalFee) {
        $userAuth = $this->di->get('userAuth');
        $user = $userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $pay = new wxpayappstore();
        $result = $pay->wxMobileOrder($totalFee);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    // appstore微信支付回调
    public function wxPayAppstoreReturn() {
        $pay = new wxpayappstore();
        return $pay->wxMobileReturn();
    }

    public function mobilePingPay($data) {
        $userAuth = $this->di->get('userAuth');
        $user = $userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $pay = new wxpay();
        $result = $pay->mobilePingOrder($data);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    /**
     * ios内购生成订单
     *
     * @param $rmb
     * @return mixed
     */
    public function iosAddOrder($rmb) {
        $userAuth = $this->di->get('userAuth');
        $user = $userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $orderMgr = new OrderMgr();
        $orderId = $orderMgr->addOrder($rmb * 0.7, $this->config->payType->iosPayInner->id, 0);
        $data['orderId'] = $orderId;
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    public function pingFromMobile() {
        $pay = new wxpay();
        return $pay->mobileVerifyNotify();
    }

    //获得userID
    private function getThisUserID() {
        $session = $this->di->get('session');
        $username = $session->get($this->config->websiteinfo->authkey)['name']; //获取username
        $arr = explode("_", $username);
        $userID = $arr[0];
        return $userID;
    }

    public function curl($url, $methor, $params) {
        //初始化
        $ch = curl_init();
        switch ($methor) {
            case "get":
                $url = $url . '?' . http_build_query($params);
                break;
            case "post":
                // post数据
                curl_setopt($ch, CURLOPT_POST, 1);
                // post的变量
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
        }
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        //返回获得的数据
        return $output;
    }

    //APP银联支付:生成订单
    public function unionPayAddOrder($totalFee) {
        $userAuth = $this->di->get('userAuth');
        $user = $userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $orderMgr = new OrderMgr();
        $orderId = $orderMgr->addOrder($totalFee, $this->config->payType->baofoo->id);
        if (empty($orderId)) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
        $result['orderId'] = $orderId;

        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    //APP银联支付:支付回调
    public function unionPayReturn() {
        $pay = new baofoo();
        return $pay->verifyNotify();
    }

}
