<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ActivitiesController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '活动';
            $this->view->ns_active = 'activities';
        }
        parent::initialize();
    }

    public function indexAction() {
        // return $this->forward("activities/charge");
    }

    public function chargeAction() {
        if ($this->request->isPost()) {
            $result = $this->userMgr->getChargeInfo();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
    }

    /**
     * 领取首充礼包
     * */
    public function getGiftAction() {
        if ($this->request->isPost()) {
            $key = $this->request->getPost('key');
            $result = $this->userMgr->getChargeGift($key);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function shareAction() {

    }

    public function onlineAction() {

    }

    public function starAction() {

        /* $user = $this->userAuth->getUser();
          if($user == NULL){
          $this->pageError();
          return;
          } */

        // 获取抢星结束时间戳
        $endTime = strtotime('next monday') - 1; // + 86399;
        // $endTime = strtotime('1 sunday') + 86399;
        $countTime = intval($endTime - time());
        $this->view->countTime = $countTime;


        $giftId = $this->request->getPost('giftId');
        $giftId = $giftId ? intval($giftId) : 0;

        // 获取用户本周抢星数据
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $myWeekStar = array('data' => array('getNum' => 0, 'sendNum' => 0));
        } else {
            $myWeekStar = $this->userMgr->getMyWeekStar($giftId);
        }

        $this->view->myWeekStar = $myWeekStar['data'];

        // 获取周星数据
        $weekStar = $this->userMgr->getWeekStar($giftId)['data'];
        $this->view->lastweekStarAnchor = isset($weekStar['lastweekInfo']['anchor']) ? $weekStar['lastweekInfo']['anchor'] : '';
        $this->view->lastweekStarRicher = isset($weekStar['lastweekInfo']['richer']) ? $weekStar['lastweekInfo']['richer'] : '';
        $this->view->thisweekStarAnchor = isset($weekStar['thisweekInfo']['anchor']) ? $weekStar['thisweekInfo']['anchor'] : '';
        $this->view->thisweekStarRicher = isset($weekStar['thisweekInfo']['richer']) ? $weekStar['thisweekInfo']['richer'] : '';
        $this->view->configName = $weekStar['configName'];
    }

    public function getWeekStarAction() {
        if ($this->request->isPost()) {
            $giftId = $this->request->getPost('giftId');
            $result = $this->userMgr->getWeekStar($giftId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //七夕活动
    public function loveAction() {
        $rankList = $this->userMgr->getQixiRank();
        $this->view->rankList = $rankList;
    }

    //
    public function versionAction() {

    }

    //推荐人推荐链接
    public function recommendReceiveAction() {
        /*$str = $this->request->get('str');
        //设置cookie
        if ($str) {
            $this->cookies->set($this->config->websitecookies->recommendStr, $str, time() + 2592000);
        }*/

        $jumpUid = $this->normalLib->getHotRoom();

        if (!$jumpUid) {
            return $this->redirect('');
        } else {
            return $this->redirect('' . $jumpUid);
        }
    }

    public function midAutumnAction() {

    }
    //查询房间中秋博饼活动情况
    public function getBobingAction() {
        if ($this->request->isPost()) {
            $anchorUid = $this->request->getPost('uid');
            $result = $this->activityMgr->getRoomBobingInfo($anchorUid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获得博饼会游戏信息
    public function getBobingInfoAction() {
        if ($this->request->isPost()) {
            $result = $this->activityMgr->getUserBobingInfo();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //用户摇骰子
    public function shakeDiceAction() {

        if ($this->request->isPost()) {
            $anchorUid = $this->request->getPost('uid');
            $times = $this->request->getPost('times');
            !isset($times) && $times == 1;
            $result = $this->activityMgr->boboingShakeDice($anchorUid, $times);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //查询状元排行榜
    public function getZhuangyuanRankAction() {
        if ($this->request->isPost()) {
            $num = $this->request->getPost('num');
            $result = $this->activityMgr->checkZhuangyuanRank($num);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //查询月光排行榜
    public function getEnergyRankAction() {
        if ($this->request->isPost()) {
            $num = $this->request->getPost('num');
            $uid = intval($this->request->getPost('uid'));
            $result = $this->activityMgr->energyRank($num, $uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }
    //宝箱
    public function boxAction() {

    }
    //红包
    public function redAction() {

    }

    //查询红包配置
    public function redPacketConfigAction() {
        if ($this->request->isPost()) {
            $redPacketType = $this->request->getPost('redPacketType');
            $result = $this->activityMgr->getRedPacketConfigs($redPacketType);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //查询某房间红包列表
    public function getRedPacketListAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $result = $this->activityMgr->getRoomRedPacketList($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //撒红包
    public function startRedPacketAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $type = $this->request->getPost('type');
            $num = $this->request->getPost('num');
            $money = $this->request->getPost('money');
            $limit = $this->request->getPost('limit');
            $redPacketType = $this->request->getPost('redPacketType');
            $result = $this->activityMgr->startRedPacket($roomId, $type, $num, $money, $limit, $redPacketType);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //用户领取红包
    public function getRedPacketAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $redPacketId = $this->request->getPost('id');
            $result = $this->activityMgr->getRoomRedPacket($roomId, $redPacketId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //查询某个红包详细信息
    public function getRedPacketInfoAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $redPacketId = $this->request->getPost('id');
            $result = $this->activityMgr->getRoomRedPacketInfo($roomId, $redPacketId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function luckyAction(){

    }

    //查询某个房间近期红包列表
    public function getUserRedPacketAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $result = $this->activityMgr->getUserRedPacketList($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //月榜
    public function monthAction() {
        $this->view->anchorList = $this->rankMgr->getStarRank('month');
        $this->view->richerList = $this->rankMgr->getRichRank('month');
    }
    //疯狂星期五
    public function crazyfridayAction() {

    }
    //夺宝
    public function indianaAction() {
        $result = $this->roomModule->getRoomOperObject()->getBetResList();
        if($result['code'] == $this->status->getCode('OK')){
            $this->view->betResult = $result['data']['list'];
        }else{
            $this->view->betResult = array();
        }
    }

    //获取圣诞树状态
    public function getChristmasInfoAction() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $result = $this->activityMgr->getRoomChristmasInfo($uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //圣诞
    public function christmasAction() {

    }
    //2016元旦
    public function newyear2016Action() {
    }
    //一元嗨
    public function onedollarAction(){
    }
    //军团
    public function LegionAction(){
    }
    //猴年
    public function monkeyAction(){
    }

    //获取春节年味活动状态
    public function getSpringFestivalAction() {
        $result = $this->activityMgr->getSpringFestivalInfo();
        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    //猴年春节活动-抢红包、派红包最多的用户列表
    public function getMoneyRedListAction() {
        $result = $this->activityMgr->getMoneyRedPacketList();
        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    //获得自己的抢红包发红包的个数
    public function getMyRedCountAction(){
        $result = $this->activityMgr->getMyRedPacketCount();
        $this->status->ajaxReturn($result['code'], $result['data']);
    }
    public function trickyAction(){
    }

    public function crowdfundingmovieAction(){
    }
    public function activitySummaryAction(){
        if ($this->request->isPost()) {
            $times = $this->request->getPost('times');
            $type = $this->request->getPost('type');
            $result = $this->activityMgr->activitySummary($times, $type);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }
}
