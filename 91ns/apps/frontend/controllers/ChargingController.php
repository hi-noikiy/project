<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ChargingController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '充值';
            $this->view->ns_name = 'charging';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction() {
          
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;
        $user = $this->userAuth->getUser();
        $this->view->vipLevel = 0;

        /*$ingotNum = $this->payPushs->douziIngotNum();
        if ($this->status->getCode('OK') != $ingotNum['code']) {
            $ingotNum['data'] = 0;
        }
        $this->view->ingotNum = $ingotNum['data'];

        $douziExchangeData = $this->payPushs->douziIngotExchangeList();
        $this->view->douziExchangeData = $douziExchangeData['data'];*/

        $vipLevel = $user->getUserInfoObject()->getUserProfiles();
        if ($vipLevel['vipExpireTime'] > time()) {
            $this->view->vipLevel = $vipLevel['vipLevel'];
            $this->view->nextVipLevel = $this->view->vipLevel + 1;

            //顶级判断
            if ($vipLevel['vipLevel'] == $this->configMgr->getMaxVipLevel()) {
                $this->view->nextVipLevel = $this->view->nextVipLevel - 1;
            }

            //获取VIP信息
            $result = $this->configMgr->getVipInfo($vipLevel['vipLevel']);
            if ($result['code'] == $this->status->getCode('OK')) {
                $vipInfo = $result['data'];
                $vipInfo['vipExpireTime'] = $vipLevel['vipExpireTime'];
                $vipInfo['current'] = $vipLevel['vipExp'];
                $this->view->vipInfo = $vipInfo;
            }
        }
        
         //获取用户基础信息
        $userAccountInfo = $user->getUserInfoObject()->getUserAccountInfo();
        $this->view->userAccountInfo = $userAccountInfo;
                
        //获取用户
        $userInfo = $user->getUserInfoObject()->getUserInfo();
        $this->view->userInfo = $userInfo;

        //银行列表
        $bankListTemp = $this->config->banckcode;
        $bankList = array();
        foreach ($bankListTemp as $key => &$val) {
            $bankList[] = array(
                'name' => $key,
                'val' => $val,
            );
        }
        $this->view->bankList = $bankList;
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
            $orderType1 = $this->request->getPost('orderType');
            $orderType = $orderType1 ? $orderType1 : 1;
            $receiveUid = $this->request->getPost('receiveUid');        //接收者的uid
            $receiveUid=$receiveUid?$receiveUid:0;
            switch ($type) {
                case 'zfb':
                    $result = $this->payPushs->alipay($RMB, $orderType, $receiveUid);
                    break;
                case 'zfbsm':
                    $result = $this->payPushs->alipay3($RMB, $orderType, $receiveUid);
                    break;
                case 'wy':
                    $bankName = $this->request->getPost('bankName');
                    $result = $this->payPushs->alipay2($RMB, $bankName, $orderType, $receiveUid);
                    break;
                case 'szf'://神州付
                    $result = $this->payPushs->shenzhoufuPay($RMB, $orderType, $receiveUid);
                    break;
                case 'wx'://微信支付
                    $result = $this->payPushs->wxpay($RMB, $orderType, $receiveUid);
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
            return $this->redirect('userbill?sonType=recharge&type='.$type);
        }
        if ($result['payType'] == $this->config->payType->alipay3->id) {//扫码支付
            return $this->redirect('static/parentfun.html?orderId=' . $result['orderId']);
            //return $this->redirect('static/parentfun.html?url=/userbill?sonType=recharge&orderId='.$result['orderId']);
        } else {
            return $this->redirect('userbill?sonType=recharge&orderId=' . $result['orderId'].'&type='.$type);
        }
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
