<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Micro\Models\Users;

class SessionController extends ControllerBase
{
    public function auth() {
        if ($this->request->isPost()) {
            $validData = array();
            $validData['deviceid'] = $this->request->getPost('deviceid');
            $validData['devicetoken'] = $this->request->getPost('devicetoken');
            $validData['platform'] = $this->request->getPost('platform');
            $validData['devicename'] = $this->request->getPost('devicename');
            $validData['version'] = $this->request->getPost('version');
            $validData['apiversion'] = $this->request->getPost('apiversion');

            $isValid = $this->validator->validate($validData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->mobileReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            $this->session->set($this->config->websiteinfo->mobileauthkey, $validData); //设备信息数据，储存在这里
            $data = array(
                'sessionId' => $this->session->getId(),
                'appCarUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/car'),
                'appSmallCarUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/car_s'),
                'appGiftUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/gift'),
                'appBigGiftUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/biggift'),// 大礼物图标路径
                'appItemUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/item'),
                'appSmallItemUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/smallitem'),//徽章小图标路径
                'appGiftConfigUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/giftconfig'),
                'appRightsConfigUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/shop'),//特权图片
                'appGoodsUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/goods'),//一元夺宝商品图片
//                'forbidtxt' => 'http://cdn.91ns.com/' . $this->config->url->forbiddenwordtxt,
                'isNeedLogin' => $this->config->isNeedLogin,
                'insidePurchases' => $this->config->insidePurchases,
                'wakeupTokenTime' => $this->config->wakeupTokenTime,
                //app动态更新下字段
                'dyUpdateFileUrl' => $this->config->dyUpdateFileUrl,
                'dyUpdateVersion' => $this->config->dyUpdateVersion,
                'sayInterval' => $this->config->sayInterval->toArray(),
                'appOtherUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/other'),
                'carEffectsUrl' => $this->url->getStatic($this->config->urlConfig->appImgUrl . '/careffects'),
            );
            return $this->status->mobileReturn($this->status->getCode('OK'), $data);
        }

        return $this->proxyError();
    }

    public function getSession(){
        $session = $this->session->get($this->config->websiteinfo->mobileauthkey);
        $this->session->set($this->config->websiteinfo->mobileauthkey, $session);
        return $this->status->mobileReturn($this->status->getCode('OK'), $session);
    }

    public function getLoginStatus(){
        $user = $this->userAuth->getUser();
        if($user == NULL){
            $session = 0;
        }else{
            $session = 1;
        }

        return $this->status->mobileReturn($this->status->getCode('OK'), $session);
    }

    public function getVersion(){
        $type = $this->request->get('type');
        switch($type){
            case 'ios':
                $data['version'] = '1.2.3';
                $data['forced'] = TRUE;
                $data['url'] = $this->config->downloadConfig->downloadUrl->ios;
                $data['updateContent'] = $this->url->getStatic($this->config->downloadConfig->appUpdateContent->ios);
                break;
            case 'android':
            default:
                $data['version'] = '1.1.5';
                $data['forced'] = TRUE;
                $data['url'] = $this->config->downloadConfig->downloadUrl->android;
                $data['updateContent'] = $this->url->getStatic($this->config->downloadConfig->appUpdateContent->android);
                break;
        }

        return $this->status->mobileReturn($this->status->getCode('OK'), $data);
    }

    public function getNewVersion(){
        $type = $this->request->get('type');
        switch($type){
            case 'ios':
                $data = $this->configMgr->getAppdownloadConfig(2);
                break;
            case 'android':
            default:
                  $data = $this->configMgr->getAppdownloadConfig(1);
                break;
        }

        return $this->status->mobileReturn($this->status->getCode('OK'), $data);
    }
}