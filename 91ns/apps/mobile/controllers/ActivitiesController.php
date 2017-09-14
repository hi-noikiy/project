<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ActivitiesController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '活动';
            $this->view->ns_name = 'activities';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction() {
        return $this->forward("activities/charge");
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

    public function recommendAction() {
        
    }

    public function recommendReceiveAction() {
        $str = $this->request->get('str');
        if($str){
            $string = base64_decode(urldecode($str));
            $arr = explode('_', $string);
            $uid = $arr[1];
        }else{
            $uid = $this->request->get('uid');
        }

        $this->view->recUserData = $this->userMgr->getRecUidData($uid ? $uid : 0);

        /*//判断是否PC
        $normalLib = $this->di->get('normalLib');
        $isMobile = $normalLib->isMobile();
        if (!$isMobile) {//PC
            $str = $this->request->get('str');
            header("Location:http://www.91ns.com/activities/recommendReceive?str=" . $str);
            exit;
        }*/
    }

    //领取推荐礼包
    public function getRecGiftAction() {
        if ($this->request->isPost()) {
            $str = $this->request->getPost('str');
            $uid = $this->request->getPost('uid');
            $telephone = $this->request->getPost('telephone');
            $result = $this->userMgr->getRecommendGiftLog($str, $uid, $telephone);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //七夕活动
    public function loveAction() {
        $rankList = $this->userMgr->getQixiRank();
        $this->view->rankList = $rankList;
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

    public function boxAction(){

    }

    public function redAction(){

    }
    public function luckyAction(){

    }
    public function monthAction(){
        $this->view->anchorList = $this->rankMgr->getStarRank('month');
        $this->view->richerList = $this->rankMgr->getRichRank('month');
    }

    public function indianaAction(){
        $result = $this->roomModule->getRoomOperObject()->getBetResList();
        if($result['code'] == $this->status->getCode('OK')){
            $this->view->betResult = $result['data']['list'];
        }else{
            $this->view->betResult = array();
        }
    }

    public function christmasAction(){

    }
    public function newyear2016Action(){
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
