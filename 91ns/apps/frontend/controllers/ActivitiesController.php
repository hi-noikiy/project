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

        /*$user = $this->userAuth->getUser();
        if($user == NULL){
            $this->pageError();
            return;
        }*/

        // 获取抢星结束时间戳
        $endTime = strtotime('next monday') - 1;// + 86399;
        // $endTime = strtotime('1 sunday') + 86399;
        $countTime = intval($endTime - time());
        $this->view->countTime = $countTime;


        $giftId = $this->request->getPost('giftId');
        $giftId = $giftId ? intval($giftId) : 0;

        // 获取用户本周抢星数据
        $user = $this->userAuth->getUser();
        if($user == NULL){
            $myWeekStar = array('data'=>array('getNum'=>0,'sendNum'=>0));
        }else{
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
    public function qixiAction() {
        $rankList = $this->userMgr->getQixiRank();
        $this->view->rankList = $rankList;
    }
    //七夕活动
    public function loveAction() {
        $rankList = $this->userMgr->getQixiRank();
        $this->view->rankList = $rankList;
    }
}
