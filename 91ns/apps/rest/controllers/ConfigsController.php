<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class ConfigsController extends ControllerBase
{
    public function getGift(){
        if($this->request->isGet()){
            $uid = $this->request->get('uid');
            !$uid && $uid = 0;
            $result = $this->configMgr->getAllGiftConfigList($uid,1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getVip(){
        if($this->request->isGet()){
            $result = $this->configMgr->getVipConfigList(-1, -1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getVipRight(){
        if($this->request->isGet()){
            $result = $this->configMgr->getVipRightConfigList(0, 100);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getGuardRight(){
        if($this->request->isGet()){
            $result = $this->configMgr->getGuardRightConfigList(0, 100);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getVipRights(){
        if($this->request->isPost()){
            $type = $this->request->getPost('type');
            $result = $this->configMgr->getVipRights($type);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getGuardRights(){
        if($this->request->isPost()){
            $result = $this->configMgr->getGuardRights();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getCar(){
        if($this->request->isGet()){
            $result = $this->configMgr->getCarConfigList(-1, -1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getGuard(){
        if($this->request->isGet()){
            $result = $this->configMgr->getAllGuardConfigList(0, 100);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取物品基本配置
    public function getItemConfigList() {
        if ($this->request->isGet()) {
            $result = $this->roomModule->getRoomOperObject()->getMobileItemsBaseConfiglist();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取守护
    public function getGuardConfigs() {
        if ($this->request->isGet()) {
            $result = $this->configMgr->getGuardConfigs();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取守护
    public function getVipConfigs() {
        if ($this->request->isGet()) {
            $result = $this->configMgr->getVipConfigs();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 获取是否开启其他支付方式
    public function isOpenOther(){
        if ($this->request->isGet()) {
            $return['isOpenInsidePurchases'] = $this->config->isOpenInsidePurchases;
            $return['isOpenOther'] = $this->config->isOpenOther;
            return $this->status->mobileReturn($this->status->getCode('OK'), $return);
        }

        return $this->proxyError();
    }

    // 获取是否显示二维码
    public function isShowQrCode(){
        if ($this->request->isGet()) {
            $isShowQrCode = 0;
            $res = \Micro\Models\ConfigsApp::findfirst('key = "isShowQrCode"');
            if(!empty($res)){
                $isShowQrCode = $res->value;
            }
            return $this->status->mobileReturn($this->status->getCode('OK'), array('isShowQrCode'=>$isShowQrCode));
        }

        return $this->proxyError();
    }

    // 根据版本号获取ip地址
    public function getHostIp(){
        if ($this->request->isPost()) {
            $hostIp = $this->config->hostIp;
            $version = $this->request->getPost('version');
            $res = \Micro\Models\ConfigsApp::findfirst('key = "' . $version . '"');
            if(!empty($res)){
                $hostIp = $res->value;
            }
            return $this->status->mobileReturn($this->status->getCode('OK'), array('hostIp'=>$hostIp));
        }

        return $this->proxyError();
    }
    
    //appstore内购产品标识
    public function appstoreProSign(){
        if ($this->request->isGet()) {
            $appstoreProSign = $this->config->appstoreProSign->toArray();
            return $this->status->mobileReturn($this->status->getCode('OK'), array('appstoreProSign'=>$appstoreProSign));
        }

        return $this->proxyError();
    }
    
    
    public function getRicherConfig(){
        if ($this->request->isGet()) {
            $Configs = $this->config->richerConfigs;
            $data['privateChatLevelLimit'] = $Configs->privateChatLevelLimit;
            $data['privateChatLevelName'] = $Configs->privateChatLevelName;
            $data['expressionLevelLimit'] = $Configs->expressionLevelLimit;
            $data['forbidLevelLimit'] = $Configs->forbidLevelLimit;
            $data['forbidLevelInterval'] = $Configs->forbidLevelInterval;
            $data['kickLevelLimit'] = $Configs->kickLevelLimit;
            $data['kickLevelInterval'] = $Configs->kickLevelInterval;
            $data['expressionLevelName'] = $Configs->expressionLevelName;
            return $this->status->mobileReturn($this->status->getCode('OK'),$data);
        }

        return $this->proxyError();
    }
}

