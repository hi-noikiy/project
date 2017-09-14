<?php

namespace Micro\Controllers;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class ChargingController extends ControllerBase
{
    public function alipayReturn(){
        if ($this->request->isPost()) {
            return $result = $this->payPushs->alipayFromMobile();
//            $data['url'] = $result;
//            return $this->status->mobileReturn($this->status->getCode('OK'), $data);
        }

        return $this->proxyError();
    }

    public function alipayAddOrder(){
        if ($this->request->isPost()) {
            $RMB = $this->request->getPost('rmb');
            $RMB = floatval($RMB);
            $user = $this->userAuth->getUser();
            if($user==NULL){
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result = $this->payPushs->alipay4($RMB);
            $data['orderId'] = $result;
            return $this->status->mobileReturn($this->status->getCode('OK'), $data);
        }

        return $this->proxyError();
    }

    public function pingAddOrder(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $RMB = floatval($post['rmb']);
            $data = array(
                'channel' => $post['channel'],
                'amount' => $RMB,
            );
            $user = $this->userAuth->getUser();
            if($user==NULL){
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result = $this->payPushs->mobilePingPay($data);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function pingReturn(){
        if ($this->request->isPost()) {
            return $result = $this->payPushs->pingFromMobile();
        }

        return $this->proxyError();
    }

    public function iosReturn(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $result = $this->iosPay->payReturn($post);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function iosAddOrder(){
        //屏蔽appstore的充值
        return $this->status->mobileReturn($this->status->getCode('AUTH_ERROR'));
        
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $RMB = floatval($post['rmb']);
            $result = $this->payPushs->iosAddOrder($RMB);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function wxPayAddOrder(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $RMB = floatval($post['rmb']);
            $user = $this->userAuth->getUser();
            if($user==NULL){
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result = $this->payPushs->wxPayAddOrder($RMB);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function wxPayReturn(){
        if ($this->request->isPost()) {
            $result = $this->payPushs->wxPayReturn();
            return $this->status->mobileReturn($result['code'], $result);
        }

        return $this->proxyError();
    }

    //appstore微信支付
    public function wxPayAppstoreAddOrder(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $RMB = floatval($post['rmb']);
            $user = $this->userAuth->getUser();
            if($user==NULL){
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result = $this->payPushs->wxPayAppstoreAddOrder($RMB);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // appstore微信支付回调
    public function wxPayAppstoreReturn(){
        if ($this->request->isPost()) {
            $result = $this->payPushs->wxPayAppstoreReturn();
            return $this->status->mobileReturn($result['code'], $result);
        }

        return $this->proxyError();
    }


    /*
     * H5 充值接口
     */

    public function pay() {
        $type = $this->request->get('type');
        $RMB = $this->request->get('rmb');
        $RMB = intval($RMB);
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        switch ($type) {
            case 'zfb':
                $result = $this->payPushs->alipay6($RMB);
                break;
//            case 'zfbsm':
//                $result = $this->payPushs->alipay3($RMB);
//                break;
//            case 'wy':
//                $bankName = $this->request->get('bankName');
//                $result = $this->payPushs->alipay2($RMB, $bankName);
//                break;
//            case 'szf'://神州付
//                $result = $this->payPushs->shenzhoufuPay($RMB);
//                break;
//            case 'wx'://微信支付
//                $result = $this->payPushs->wxpay($RMB);
//                break;
            default:
                $this->proxyError();
                break;
        }
        $data['url'] = $result;
        return $this->status->mobileReturn($this->status->getCode('OK'), $data);
    }
    
    
    //APP银联支付:生成订单
    public function unionPayAddOrder() {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $RMB = floatval($post['rmb']);
            $result = $this->payPushs->unionPayAddOrder($RMB);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //APP银联支付:支付回调
    public function unionPayReturn() {
        if ($this->request->isPost()) {
            $result = $this->payPushs->unionPayReturn();
            return $this->status->mobileReturn($result['code'], $result);
        }
        return $this->proxyError();
    }

}