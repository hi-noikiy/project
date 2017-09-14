<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ShopController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '商城';
            $this->view->ns_active = 'shop';
        }
        parent::initialize();
    }

    //index
    public function indexAction()
    {
        $this->redirect('shop/vip');
    }

    //vip
    public function vipAction()
    {
        $this->view->vipConfig = $this->config->buyVipConfig;
        $this->view->ns_type = 'vip';
    }

    //car
    public function carAction()
    {
        // 1 获取所有的座驾列表  configMgr
        // 2 判断用户如果没有登录，直接返回
        // 3 用户如果登录，result = getCarItemList
        // 4 遍历result itemId,
        $result = $this->configMgr->getAllCarConfigList();
        if ($result['code'] == $this->status->getCode('OK')) {
            //登录过用户
            $user = $this->userAuth->getUser();
            if($user){
                $userItemArray = array();
                $itemDataList = $result['data']['list'];

                $userData = $user->getUserItemsObject()->getCarItemList();
                foreach($userData as $val){
                    if ($val['itemExpireTime'] > time()) {
                        array_push($userItemArray, $val['itemId']);
                    }
                }

                foreach($itemDataList as $key => $val2) {
                    foreach($val2['info'] as $k=>$info) {
                        if(in_array($info['id'], $userItemArray)){
                            $result['data']['list'][$key]['info'][$k]['hasCar'] = 1;
                        }
                    }
                }
            }
            $this->view->carInfo = $result['data']['list'];
        }
        $this->view->ns_type = 'car';
    }

    public function buyVipAction(){
         if($this->request->isPost()){
            $post = $this->request->getPost();
            $data = $post['buyVip'];                //获取聊币
            $buyType = $post['buyType'];            //1：普通vip 2：至尊vip

            $user = $this->userAuth->getUser();
            if(!$user){
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result = $user->getUserItemsObject()->buyVip($data,$buyType);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //购买座驾
    public function getCarAction(){
        if($this->request->isPost()){
            $post = $this->request->getPost();
            $carID = $post['carId'];                //获取座驾ID

            $user = $this->userAuth->getUser();
            if(!$user){
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result = $user->getUserItemsObject()->buyCar($carID);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //守护
    public function guardAction()
    {
        $guardConfig['1'] = $this->config->goldGuard;
        $guardConfig['2'] = $this->config->silverGuard;
        $this->view->guardConfig = $guardConfig;
        $guardedUid = $this->request->get('uid');
        $result = $this->configMgr->getAllGuardConfigList(0,500);
        if ($result['code'] == $this->status->getCode('OK')) {
            
           $resultData = $result['data']['list'];
            $this->view->guardInfo = $resultData;

        }
        if (!empty($guardedUid)) {
            $beGuardedUser = UserFactory::getInstance($guardedUid);
            $beGuardedNickName = $beGuardedUser->getUserInfoObject()->getNickName();
            $this->view->nickName = $beGuardedNickName;
            $this->view->guid = $guardedUid;
        }

        $this->view->ns_type = 'guard';

    }

    //守护 === 购买
    public function buyGuardAction(){
        $user = $this->userAuth->getUser();
        if($this->request->isPost()){
            $post = $this->request->getPost();
            $guardId = $post['guardId'];           //购买信息
            $type = $post['type'];                   //类型
            $GuardedUid = $post['GuardedUid'];      //要守护的ID

            $user = $this->userAuth->getUser();
            if(!$user){
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result = $user->getUserItemsObject()->buyGuard($guardId,$GuardedUid,$type);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
}