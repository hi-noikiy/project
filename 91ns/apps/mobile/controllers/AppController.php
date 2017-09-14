<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class AppController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = 'appPage';
            $this->view->ns_name = 'appPage';
            //imgSuffix：手机尺寸--2x或3x
            $imgSuffix = $this->request->get('_imgSuffix_');
            if($imgSuffix){
                $this->view->imgSuffix = '@'.$imgSuffix;
            }else{
                $this->view->imgSuffix = '@3x';
            }
            //url：接口地址
            $restURL = $this->request->get('_restURL_');
            if($restURL){
                $this->view->restURL = $restURL;
            }else{
                $this->view->restURL = 'http://rest.91ns.com/';
            }
            //获取url后缀参数
            $req = $this->request->get();
            $reqPara = '';
            foreach ($req as $key => $value) {
                if($key != '_url'){
                    $reqPara .= $key.'='.$value.'&';
                }
            }
            $this->view->urlPara = $reqPara;
            //获取用户信息
            /*$uid = $this->request->get('uid');
            $user = $this->userAuth->getUser();
            if($user != NULL){
                $uid_ = $user->getUid();
                if($uid_ != $uid){
                    $this->userAuth->userLogout();
                    $this->userAuth->userAutoLogin($uid);
                    $user = $this->userAuth->getUser();
                }
            }else{
                $this->userAuth->userAutoLogin($uid);
                $user = $this->userAuth->getUser();
            }
            if($user != NULL){
                $uid = $user->getUid();
                $this->view->ns_userUid = $uid;
            }*/
        }
        parent::initialize();
    }

    public function indexAction()
    {

    }

    /*
     * 发红包页面
     */
    public function redAction()
    {

    }

    /*
     * 抢红包页面
     */
    public function vieAction()
    {

    }

    /*
     * 充值页面
     */
    public function chargingAction()
    {

    }

     /*
     * 充值回调页面
     */
    public function paycallbackAction($type) {
        if ($type == 'alipay') {//支付宝
            $result = $this->payPushs->alipayVerifyReturn();
        }
//        elseif ($type == 'shenzhoufu') {//神州付
//            $result = $this->payPushs->shenzhoufuVerifyReturn();
//        } elseif ($type == 'wxpay') {//微信支付
//            $result = $this->payPushs->wxpayVerifyReturn();
//        }

        if (!$result) {
            return $this->redirect('app/chargingcallback?type=' . $type);
        }
          return $this->redirect('app/chargingcallback?orderId=' . $result['orderId'] . '&type=' . $type.'&cash='.$result['cash']);
    }
    /*
     * 充值跳转页面
     */
    public function chargingcallbackAction() {
    }
    /*
     * 红包明细页面
     */
    public function reddetailAction() {
    }

}