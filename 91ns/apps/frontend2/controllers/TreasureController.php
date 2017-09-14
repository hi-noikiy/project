<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class TreasureController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '一元夺宝';
            $this->view->ns_active = 'treasure';
        }
        parent::initialize();
    }

    public function indexAction(){

    }

    public function getBannersAction(){
    	if ($this->request->isPost()) {
            $result = $this->configMgr->getBannerList(4, 0, 5);//4代表pc的一元夺宝banner
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 最近揭晓
     * @param $num
     */
    public function getRecentAction(){
    	if ($this->request->isPost()) {
    		$num = $this->request->getPost('num');
            $result = $this->TreasureMgr->getRecent($num);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 即将开奖
     * @param $page
     * @param $pageSize
     */
    public function getOpeningAction(){
    	if ($this->request->isPost()) {
            $result = $this->TreasureMgr->getAllGoodsList(1, 1, 10);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 最热商品
     * @param $num
     */
    public function getHottestAction(){
    	if ($this->request->isPost()) {
            $result = $this->TreasureMgr->getAllGoodsList(2, 1, 10);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 所有商品
     * @param $page
     * @param $pageSize
     * @param $type 类型
     */
    public function getAllGoodsListAction(){
    	if ($this->request->isPost()) {
    		$page = $this->request->getPost('page');
    		$pageSize = $this->request->getPost('pageSize');
    		$type = $this->request->getPost('type');
    		!$type && $type = 1;

            $result = $this->TreasureMgr->getAllGoodsList($type, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 获取商品信息
     * @param $id
     * @param $times 期数
     */
    public function getGoodsInfoAction(){
    	if ($this->request->isPost()) {
    		$id = $this->request->getPost('id');
    		$times = $this->request->getPost('times');
    		!$times && $times = 0;

            $result = $this->TreasureMgr->getGoodsInfo($id, $times);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 获取商品信息
     * @param $id
     * @param $times 期数
     * @param $nums 注数
     * @param $kind 投注方式
     */
    public function doBettingAction(){
    	if ($this->request->isPost()) {
    		$type = $this->request->getPost('id');
    		$times = $this->request->getPost('times');
    		$nums = $this->request->getPost('nums');
    		$kind = $this->request->getPost('kind');

    		if ($kind != 1 && $kind != 2) {
	            return $this->status->mobileReturn($this->status->getCode('VALID_ERROR'));
	        }

            $result = $this->TreasureMgr->doBetting($type, $times, $nums, $kind, $platform = 1);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 直播间获取一元夺宝列表
     * @param $uid 主播uid
     */
    public function getRoomsBetListAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');

            !$uid && $uid = 0;

            $result = $this->TreasureMgr->getRoomsBetList($uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    public function checkRoomBetAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');

            !$uid && $uid = 0;

            $result = $this->TreasureMgr->checkRoomBet($uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    public function openBetPointsAction(){
    	$result = $this->TreasureMgr->openBetPoints(1,46);
        return $this->status->ajaxReturn($result['code'], $result['data']);
    }

    //test
    /*public function addWineAction(){
        $price = $this->request->getPost('price');
        $result = $this->TreasureMgr->addWine($price);
        return $this->status->ajaxReturn($result['code'], $result['data']);
    }

    public function allocateWineAction(){
        $id = $this->request->getPost('id');
        $uid = $this->request->getPost('uid');
        // echo $id,$uid;die;
        !$uid && $uid = 0;
        !$id && $id = 0;
        $result = $this->TreasureMgr->allocateWine($id, $uid);
        return $this->status->ajaxReturn($result['code'], $result['data']);
    }*/

}
