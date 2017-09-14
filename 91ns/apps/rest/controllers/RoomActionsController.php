<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class RoomActionsController extends ControllerBase
{
    public function sendGifts($roomId){
        if ($this->request->isGet()) {
            $uid = $this->request->get('uid');
            $giftId = $this->request->get('giftId');
            $giftCount = $this->request->get('giftCount');
            $anonymous = $this->request->get('anonymous');
            $hitsNum = $this->request->get('hitsNum');

            //
            /*$logger = $this->di->get('logger');
            $session = $this->di->get('session');
            $authData = array();
            $mobileAuthData = array();
            if($session->get($this->config->websiteinfo->authkey) != NULL){
                $authData = $session->get($this->config->websiteinfo->authkey);
            }
            if($session->get($this->config->websiteinfo->mobileauthkey) != NULL){
                $mobileAuthData = $session->get($this->config->websiteinfo->mobileauthkey);
            }
            $data1 = json_encode($authData, JSON_UNESCAPED_UNICODE);
            $data2 = json_encode($mobileAuthData, JSON_UNESCAPED_UNICODE);
            $logger->error('-----APP test sendGift sessionId = '.$session->getId().' authdata : '.$data1.' mobileauthdata : '.$data2.'-----');*/
            ///
            $result = $this->roomModule->getRoomOperObject()->sendGift($roomId, $uid, $giftId, $giftCount, $anonymous, 0, $hitsNum);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

    //获取在线礼物（魅力）数量
    public function getCharm()
    {
        if($this->request->isGet())
        {
            /*$logger = $this->di->get('logger');
            $session = $this->di->get('session');
            $authData = array();
            $mobileAuthData = array();
            if($session->get($this->config->websiteinfo->authkey) != NULL){
                $authData = $session->get($this->config->websiteinfo->authkey);
            }
            if($session->get($this->config->websiteinfo->mobileauthkey) != NULL){
                $mobileAuthData = $session->get($this->config->websiteinfo->mobileauthkey);
            }
            $data1 = json_encode($authData, JSON_UNESCAPED_UNICODE);
            $data2 = json_encode($mobileAuthData, JSON_UNESCAPED_UNICODE);
            $logger->error('-----Test sessionId = '.$session->getId().' authdata : '.$data1.' mobileauthdata : '.$data2.'-----');*/
            $result = $this->userMgr->getOnlineGift();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return  $this->proxyError();
    }

    //赠送在线礼物（魅力）
    public function sendCharm($roomId)
    {
        if($this->request->isPost())
        {
            $result = $this->userMgr->sendOnlineGift($roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //发送房间广播
    public function sendRoomBroadcast($roomId) {
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $content = $this->request->getPost('content');
            $isUseItem = $this->request->getPost('isUseItem');//是否使用道具
            switch($type){
                case 1:
                    $result = $this->roomModule->getRoomOperObject()->sendRoomBroadcast($roomId, $content, $isUseItem);
                break;
                case 2:
                    $result = $this->roomModule->getRoomOperObject()->sendAllRoomBroadcast($roomId, $content,$isUseItem);
                break;
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取房间某个用户信息
    public function getUserData($roomId) {
        if ($this->request->isGet()) {
            $uid = $this->request->get('uid');
            $result = $this->roomModule->getRoomOperObject()->getRoomUserData($uid, $roomId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //使用包裹里礼物
    public function sendBagGift($roomId) {
        if ($this->request->isPost()) {
//            $roomId = $this->request->getPost('roomId');
            $uid = $this->request->getPost('uid');
            $id = $this->request->getPost('id');
            $giftCount = $this->request->getPost('giftCount');
            $anonymous = $this->request->getPost('anonymous');
            $type = $this->request->getPost('type');
            $hitsNum = $this->request->getPost('hitsNum');
            $result = $this->roomModule->getRoomOperObject()->sendBagGift($roomId, $uid, $id, $giftCount, $anonymous, $type, $hitsNum);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得直播推荐
     */
    /*public function getGuessRoom(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $limit = $this->request->getPost('limit');
            $result = $this->roomModule->getRoomMgrObject()->getGuessHoster($uid, $limit, 1);
            return $this->status->mobileReturn($result['code'], $result['data']['data']);
        }

        return $this->proxyError();
    }*/

    /**
     * 获得直播推荐
     */
    public function getMobileJumpAnchorId(){
        if ($this->request->isGet()) {
            $uid = $this->request->get('uid');
            $limit = $this->request->get('limit');
            $result = $this->roomModule->getRoomMgrObject()->getGuessHoster($uid, $limit, 1);
            return $this->status->mobileReturn($result['code'], $result['data']['data']);
        }

        return $this->proxyError();
    }

    //获取猜猜看
//    public function getGuessRoom() {
//        if ($this->request->isGet()) {
//            $uid = $this->request->get('uid');
//            $limit = $this->request->get('limit');
//            $result = $this->roomModule->getRoomMgrObject()->getGuessHoster($uid, $limit);
//            return $this->status->mobileReturn($result['code'], $result['data']);
//        }
//
//        return $this->proxyError();
//    }

    public function uploadSuggestionsPic() {
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomMgrObject()->uploadSuggestionsPic();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function saveSuggestion() {
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomMgrObject()->saveSuggestion('app');
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取在线的免费聊豆
    public function getOnlineCoin() {
        if ($this->request->isPost()) {
//            $result = $this->roomModule->getRoomMgrObject()->onlineActivities();
            $taskId = $this->config->taskIds->online;
            $result = $this->taskMgr->getTaskReward($taskId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取在线的免费聊豆
    public function setOnlineCoin() {
        if ($this->request->isPost()) {
            $result = $this->taskMgr->setwatchTask(2);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 礼物表情
     *
     * @return mixed
     */
    public function sendGameFace($roomId){
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $content = intval($this->request->getPost('content'));
            $result = $this->gameMgr->gamePush($roomId, $type, $content);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //  禁言
    public function forbidTalk($roomId) {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $isForbid = $this->request->getPost('isForbid');
            $token = $this->request->getPost('token');
            $result = $this->roomModule->getRoomOperObject()->forbidTalk($roomId, $uid, $isForbid, $token);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //  踢人
    public function kickUser($roomId) {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $token = $this->request->getPost('token');
            $result = $this->roomModule->getRoomOperObject()->kickUser($roomId, $uid, $token);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取用户喇叭拥有数量
    public function getUserHorn() {
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $result = $this->roomModule->getRoomOperObject()->checkUserHorn($type);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取用户的任务列表
    /**
     * @param $type 任务类型:1新手任务 2日常任务
     */
    public function getUserTask(){
        if($this->request->isPost())
        {
            $type=$this->request->getPost("type");
            $result=$this->taskMgr->getUserTask($type);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取某个任务的奖励
    public function getTaskReward(){
        if($this->request->isPost()) {
            $taskId = $this->request->getPost("taskId");
            $result = $this->taskMgr->getTaskReward($taskId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //完成观看的任务
    public function setWatchTask(){
        if($this->request->isPost()) {
            $type=$this->request->getPost("type");
            $result = $this->taskMgr->setwatchTask($type);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    public function getTaskStatus(){
        if($this->request->isPost()) {
            $taskId=$this->request->getPost("taskId");
            $result = $this->taskMgr->getTaskStatus($taskId);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //查询房间中秋博饼活动情况
    public function getBobing() {
        if ($this->request->isGet()) {
            $anchorUid = $this->request->get('uid');
            $result = $this->activityMgr->getRoomBobingInfo($anchorUid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获得博饼会游戏信息
    public function getBobingInfo() {
        if ($this->request->isGet()) {
            $result = $this->activityMgr->getUserBobingInfo();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //用户摇骰子
    public function shakeDice() {

        if ($this->request->isPost()) {
            $anchorUid = $this->request->getPost('uid');
            $times = $this->request->getPost('times');
            !isset($times) && $times == 1;
            $result = $this->activityMgr->boboingShakeDice($anchorUid, $times);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //查询状元排行榜
    public function getZhuangyuanRank() {
        if ($this->request->isGet()) {
            $num = $this->request->get('num');
            $result = $this->activityMgr->checkZhuangyuanRank($num);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //查询月光排行榜
    public function getEnergyRank() {
        if ($this->request->isGet()) {
            $num = $this->request->get('num');
            $result = $this->activityMgr->energyRank($num);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 添加宝箱可领取日志
     *
     */
    // public function addRewardLog(){
    //     if($this->request->isPost()){
    //         $result = $this->roomModule->getRoomMgrObject()->addRewardLog(1);// 手机
    //         return $this->status->mobileReturn($result['code'], $result['data']);
    //     }

    //     return $this->proxyError();
    // }

    /**
     * 开启宝箱
     */
    // public function openReward(){
    //     if($this->request->isPost()){
    //         $result = $this->roomModule->getRoomMgrObject()->openReward(1);
    //         return $this->status->mobileReturn($result['code'], $result['data']);
    //     }

    //     return $this->proxyError();
    // }

    /**
     * 检查是否存在未领取宝箱
     */
    public function checkReward(){
        if($this->request->isPost()){
            $result = $this->roomModule->getRoomMgrObject()->checkReward();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 开启宝箱New
     */
    public function openRewardBox(){
        if($this->request->isPost()){
            $result = $this->roomModule->getRoomMgrObject()->openRewardBox(1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取直播间用户列表
    public function getRoomUsers(){
        if($this->request->isPost()){
            $nodejstoken = $this->request->getPost('nodejstoken');
            $roomid = $this->request->getPost('roomId');
            $count = $this->request->getPost('count');
            $result = $this->roomModule->getRoomMgrObject()->getRoomUsers($nodejstoken, $roomid, $count);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //获取直播间管理列表
    public function getRoomManagers(){
        if($this->request->isPost()){
            $nodejstoken = $this->request->getPost('nodejstoken');
            $roomid = $this->request->getPost('roomId');
            $result = $this->roomModule->getRoomMgrObject()->getRoomManagers($nodejstoken, $roomid);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 详细页面搜索
     */
    public function searchDetail(){
        if($this->request->isPost()){
            $search = $this->request->getPost('search');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->roomModule->getRoomMgrObject()->searchAnchorsForMobile($search, $page, $pageSize);
            
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    
        
    //查询红包配置
    public function redPacketConfig() {
        $redPacketType = $this->request->get('redPacketType');
        $result = $this->activityMgr->getRedPacketConfigs($redPacketType);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    //查询某房间红包列表
    public function getRedPacketList() {
        $roomId = $this->request->get('roomId');
        $result = $this->activityMgr->getRoomRedPacketList($roomId);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    //撒红包
    public function startRedPacket() {
        $roomId = $this->request->get('roomId');
        $type = $this->request->get('type');
        $num = $this->request->get('num');
        $money = $this->request->get('money');
        $limit = $this->request->get('limit');
        $redPacketType = $this->request->get('redPacketType');
        //
        $logger = $this->di->get('logger');
        $session = $this->di->get('session');
        $authData = array();
        $mobileAuthData = array();
        if($session->get($this->config->websiteinfo->authkey) != NULL){
            $authData = $session->get($this->config->websiteinfo->authkey);
        }
        if($session->get($this->config->websiteinfo->mobileauthkey) != NULL){
            $mobileAuthData = $session->get($this->config->websiteinfo->mobileauthkey);
        }
        $data1 = json_encode($authData, JSON_UNESCAPED_UNICODE);
        $data2 = json_encode($mobileAuthData, JSON_UNESCAPED_UNICODE);
        $logger->error('-----APP test startRedPacket sessionId = '.$session->getId().' authdata : '.$data1.' mobileauthdata : '.$data2.'-----');
        ///
        $result = $this->activityMgr->startRedPacket($roomId, $type, $num, $money, $limit,$redPacketType);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    //用户领取红包
    public function getRedPacket() {
        $roomId = $this->request->get('roomId');
        $redPacketId = $this->request->get('id');
        $result = $this->activityMgr->getRoomRedPacket($roomId, $redPacketId);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    //查询某个红包详细信息
    public function getRedPacketInfo() {
        $roomId = $this->request->get('roomId');
        $redPacketId = $this->request->get('id');
        $result = $this->activityMgr->getRoomRedPacketInfo($roomId, $redPacketId);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }
    
    //查询某个房间近期红包列表
    public function getUserRedPacket() {
        $roomId = $this->request->get('roomId');
        $result = $this->activityMgr->getUserRedPacketList($roomId);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    //举报
    public function addInform(){
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomMgrObject()->addInform('app');
            return $this->status->mobileReturn($this->status->getCode('OK'),$result['data']);
        }
        $this->proxyError();
    }

    //积分下注
    public function betPoints(){
        if ($this->request->isPost()) {

            $times = $this->request->getPost('times');
            $type = $this->request->getPost('type');
            $nums = $this->request->getPost('nums');

            $userDevice = $this->session->get($this->config->websiteinfo->mobileauthkey);
            $platform = isset($userDevice['platform']) ? $userDevice['platform'] : 0;

            $result = $this->roomModule->getRoomOperObject()->betPoints($type, $times, $nums, $platform);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //获取用户投注记录
    public function getBetLog(){
        if ($this->request->isPost()) {

            $result = $this->roomModule->getRoomOperObject()->getBetLog(0);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //获取进行中的夺宝列表
    public function getBettingList(){
        if ($this->request->isPost()) {

            // $times = $this->request->getPost('times');
            // $type = $this->request->getPost('type');

            $result = $this->roomModule->getRoomOperObject()->getBettingList(0);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //检查对否有90%的夺宝
    public function checkHasWarningBet(){
        if ($this->request->isPost()) {

            $result = $this->roomModule->getRoomOperObject()->checkHasWarningBet();

            return $this->status->mobileReturn($this->status->getCode('OK'),$result);
        }
        return $this->proxyError();
    }
    
    //春节年味活动
    public function getSpringFestivalInfo() {
        $result = $this->activityMgr->getSpringFestivalInfo();
        return $this->status->mobileReturn($this->status->getCode('OK'), $result);
    }

    public function getAnchorMovieInfo(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $result = $this->activityMgr->getAnchorMovieInfo($uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
}