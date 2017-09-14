<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ChargingController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '充值';
            $this->view->ns_active = 'charging';
        }
        parent::initialize();
    }

    public function indexAction() {
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->redirect('login');
        }
    }

    public function douziExchangeAction() {
        if ($this->request->isPost()) {
            $number = $this->request->getPost('rmb');
            $result = $this->payPushs->douziPay($number);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function getIngotDataAction() {
        if ($this->request->isPost()) {
            $result = $this->payPushs->douziIngotNum();
            if ($this->status->getCode('OK') != $result['code']) {
                $result['data'] = 0;
            }
            $revert['ingot'] = $result['data'];

            $result = $this->payPushs->douziIngotExchangeList();
            $revert['list'] = $result['data'];
            $this->status->ajaxReturn($result['code'], $revert);
        }
        $this->proxyError();
    }

    /*
     * 充值接口
     * */

    public function payAction($type) {
        if ($this->request->isPost()) {
            $RMB = $this->request->getPost('rmb');
            $RMB = intval($RMB);
            switch ($type) {
                case 'zfb':
                    $result = $this->payPushs->alipay5($RMB);
                    break;
                case 'zfbsm':
                    $result = $this->payPushs->alipay3($RMB);
                    break;
                case 'wy':
                    $bankName = $this->request->getPost('bankName');
                    $result = $this->payPushs->alipay2($RMB, $bankName);
                    break;
                case 'szf'://神州付
                    $result = $this->payPushs->shenzhoufuPay($RMB);
                    break;
                 case 'wx'://微信支付
                    $result = $this->payPushs->wxpay($RMB);
                    break;
                default:
                    $this->proxyError();
                    break;
            }
            $data['url'] = $result;
            $this->status->ajaxReturn($this->status->getCode('OK'), $data);
        }
        $this->proxyError();
    }

    /*
     * 中转页面
     * */

    public function callbackpageAction($type) {
        if ($type == 'alipay') {//支付宝
            $result = $this->payPushs->alipayVerifyReturn();
        } elseif ($type == 'shenzhoufu') {//神州付
            $result = $this->payPushs->shenzhoufuVerifyReturn();
        } elseif ($type == 'wxpay') {//微信支付
            $result = $this->payPushs->wxpayVerifyReturn();
        }
        if (!$result) {
            return $this->redirect('user?type='.$type);
        }
        return $this->redirect('user&orderId=' . $result['orderId'].'&type='.$type);
    }

    //支付宝异步回调地址
    public function alipaynoticeAction() {
        return $this->payPushs->alipayVerifyNotify();
    }

    //神州付异步回调地址
    public function shenzhoufunoticeAction() {
        return $this->payPushs->shenzhoufuVerifyNotify();
    }

    //微信支付异步回调地址
    public function wxpaynoticeAction() {
        return $this->payPushs->wxpayVerifyNotify();
    }

}
