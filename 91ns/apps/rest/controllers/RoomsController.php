<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class RoomsController extends ControllerBase
{
    public function enterRoom($uid){
        if ($this->request->isGet()) {
            $result = $this->roomModule->getRoomOperObject()->enterRoom($uid);
//            $result['data']['url'] = $this->config->urlConfig->mobileplay;
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 登录NodeJS
    public function loginToNodeJS($roomid) {
        if ($this->request->isPost()) {
            $result = $this->userAuth->loginToNodeJS($roomid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 进入NodeJS房间
    public function enterNodeJSRoom($roomid) {
        if ($this->request->isPost()) {
            $token = $this->request->getPost('token');
            $isRepeat = $this->request->getPost('repeat');
            $result = $this->roomModule->getRoomOperObject()->enterNodeJSRoom($roomid, $token, $isRepeat);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得守护列表
     *
     * @param $roomid
     * @return mixed
     */

    public function getGuardDataList($roomid) {
        if ($this->request->isGet()) {
            $result = $this->roomModule->getRoomMgrObject()->getGuardDataList($roomid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getRoomInfo(){

    }

    public function getRoomRank($roomId){
        if ($this->request->isGet()) {
            $rankType = $this->request->get('rankType');
            $topNum = $this->request->get('topNum');
            $result = $this->rankMgr->getRoomConsumeRank($rankType, $roomId, $topNum);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }




    // 主播客户端向服务器发起请求说明，当前房间开始直播
    public function startPublish($roomId) {
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomOperObject()->startPublish($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 主播客户端向服务器发起请求说明，当前房间停止直播
    public function stopPublish($roomId) {
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomOperObject()->stopPublish($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function updatePublish($roomId) {
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomOperObject()->updatePublish($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function validatePublish($roomId) {
        if ($this->request->isPost()) {
            //查询是否禁播
            $result = $this->roomModule->getRoomMgrObject()->checkLiveStatus($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //直播间包裹
    public function getRoomBag() {
        if ($this->request->isGet()) {
            $result = $this->roomModule->getRoomOperObject()->getRoomBag();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 获取房间总人数接口
    public function getTotalCount($roomId) {
        if ($this->request->isGet()) {
            $result = $this->roomModule->getRoomMgrObject()->getCountInRoom($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取敏感字txt文件
    public function forbiddenwordtxt() {
        if ($this->request->isGet()) {
            $result['url'] = 'http://cdn.91ns.com/' . $this->config->url->forbiddenwordtxt;
            return $this->status->mobileReturn($this->status->getCode('OK'), $result);
        }

        return $this->proxyError();
    }

    //房间系统配置
    public function sysConfig() {
        if ($this->request->isGet()) {
            $result['forbidurl'] = 'http://cdn.91ns.com/' . $this->config->url->forbiddenwordtxt;
            $result['tips'] = 'http://cdn.91ns.com/' . $this->config->url->mobiletips;
            $result['robotConfig'] = $this->config->robotConfig;
            $result['robotname'] = 'http://cdn.91ns.com/' . $this->config->url->robotname;
            $result['robotchat'] = 'http://cdn.91ns.com/' . $this->config->url->robotchat;
            $result['FetchIPTimeout'] = $this->config->appConfig->FetchIPTimeout;
            $result['PlayMediaTimeout'] = $this->config->appConfig->PlayMediaTimeout;
            $result['goldHorn'] = $this->config->appConfig->goldHorn;
            $result['silverHorn'] = $this->config->appConfig->silverHorn;
            $result['freeBean'] = $this->config->appConfig->freeBean;
            $result['coinTime'] = $this->config->getCoinTime;
            $result['giftConfig'] = $this->config->giftConfig->toArray();
            $result['roomRichRank'] = $this->normalLib->getBasicConfigs('roomRichRank');
            return $this->status->mobileReturn($this->status->getCode('OK'), $result);
        }

        return $this->proxyError();
    }

    /**
     * 设置麦
     *
     * @param fromPos 原位置 未上麦则为0
     * @param toPos  目标位置 0表示下麦
     * @param fromUid 原位置主播
     * @param toUid 目标位置主播
     */
    public function setPublishPos(){
        if($this->request->isPost()){
            $fromPos = $this->request->getPost('fromPos');
            $toPos = $this->request->getPost('toPos');
            $fromUid = $this->request->getPost('fromUid');
            $toUid = $this->request->getPost('toUid');
            $result = $this->roomModule->getRoomMgrObject()->setPublishPos($fromPos, $toPos, $fromUid, $toUid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 进入检测房间限制
    public function checkRoomLimit(){
        if($this->request->isPost()){
            
            $uid = $this->request->getPost('uid');
            $result = $this->roomModule->getRoomOperObject()->checkRoomLimit($uid);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 检测房间密码
    public function checkRoomPwd(){
        if($this->request->isPost()){
            
            $uid = $this->request->getPost('uid');
            $roomPwd = $this->request->getPost('roomPwd');
            $result = $this->roomModule->getRoomOperObject()->checkRoomPwd($uid, $roomPwd);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    // 检测房间密码
    public function getStreamName(){
        if($this->request->isPost()){
            
            $uid = $this->request->getPost('uid');
            $result = $this->roomModule->getRoomOperObject()->getStreamName($uid);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    
    
    //获取更多模块
    public function getMoreFunction() {
        if ($this->request->isGet()) {
            $uid = $this->request->get('uid');
            !$uid && $uid = 0;

            $result = array(
                0 => array('funcId' => 1, "name" => "日常任务", "configName" => 'task',"isMainFunc"=>0, "isHide"=>0),
                1 => array('funcId' => 2, "name" => "积分夺宝", "configName" => 'indiana',"isMainFunc"=>0, "isHide"=>1),
                2 => array('funcId' => 11, "name" => "一元夺宝", "configName" => 'crowdfunded',"isMainFunc"=>1, "isHide"=>0),
                3 => array('funcId' => 12, "name" => "一元嗨", "configName" => 'onedollar',"isMainFunc"=>0, "isHide"=>1),
                4 => array('funcId' => 13, "name" => "节目", "configName" => 'show',"isMainFunc"=>0, "isHide"=>0),
                5 => array('funcId' => 14, "name" => "个人空间", "configName" => 'home',"isMainFunc"=>0, "isHide"=>0),
                6 => array('funcId' => 15, "name" => "骰宝游戏", "configName" => 'toubao',"isMainFunc"=>0, "isHide"=>1),
            );

            if($uid){
                $res = \Micro\Models\GoodsConfigs::findFirst('isShow = 0 and type = ' . $uid);
                if(!empty($res)){
                    $result = array(
                        0 => array('funcId' => 1, "name" => "日常任务", "configName" => 'task',"isMainFunc"=>0, "isHide"=>0),
                        1 => array('funcId' => 2, "name" => "积分夺宝", "configName" => 'indiana',"isMainFunc"=>0, "isHide"=>1),
                        2 => array('funcId' => 11, "name" => "一元夺宝", "configName" => 'crowdfunded',"isMainFunc"=>0, "isHide"=>0),
                        3 => array('funcId' => 12, "name" => "一元嗨", "configName" => 'onedollar',"isMainFunc"=>1, "isHide"=>0),
                        4 => array('funcId' => 13, "name" => "节目", "configName" => 'show',"isMainFunc"=>0, "isHide"=>0),
                        5 => array('funcId' => 14, "name" => "个人空间", "configName" => 'home',"isMainFunc"=>0, "isHide"=>0),
                        6 => array('funcId' => 15, "name" => "骰宝游戏", "configName" => 'toubao',"isMainFunc"=>0, "isHide"=>1),
                    );
                }
            }
            
            return $this->status->mobileReturn($this->status->getCode('OK'), $result);
        }

        return $this->proxyError();
    }

}