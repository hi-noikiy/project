<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class TreasureController extends ControllerBase
{
    public function getBanners(){
    	if ($this->request->isPost()) {
            $result = $this->configMgr->getBannerList(3, 0, 5);//2代表pc的一元夺宝banner
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 最近揭晓
     * @param $num
     */
    public function getRecent(){
    	if ($this->request->isPost()) {
    		$num = $this->request->getPost('num');
            $result = $this->TreasureMgr->getRecent($num);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 即将开奖
     * @param $page
     * @param $pageSize
     */
    public function getOpening(){
    	if ($this->request->isPost()) {
            $result = $this->TreasureMgr->getAllGoodsList(1, 1, 10);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 最热商品
     * @param $num
     */
    public function getHottest(){
    	if ($this->request->isPost()) {
            $result = $this->TreasureMgr->getAllGoodsList(2, 1, 10);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 所有商品
     * @param $page
     * @param $pageSize
     * @param $type 类型
     */
    public function getAllGoodsList(){
    	if ($this->request->isPost()) {
    		$page = $this->request->getPost('page');
    		$pageSize = $this->request->getPost('pageSize');
    		$type = $this->request->getPost('type');
    		!$type && $type = 1;

            $result = $this->TreasureMgr->getAllGoodsList($type, $page, $pageSize);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 获取商品信息
     * @param $id
     * @param $times 期数
     */
    public function getGoodsInfo(){
    	if ($this->request->isPost()) {
    		$id = $this->request->getPost('id');
    		$times = $this->request->getPost('times');
    		!$times && $times = 0;

            $result = $this->TreasureMgr->getGoodsInfo($id, $times);
            return $this->status->mobileReturn($result['code'], $result['data']);
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
    public function doBetting(){
    	if ($this->request->isPost()) {
    		$type = $this->request->getPost('id');
    		$times = $this->request->getPost('times');
    		$nums = $this->request->getPost('nums');
    		$kind = $this->request->getPost('kind');
	        if ($kind != 1 && $kind != 2) {
	            return $this->status->mobileReturn($this->status->getCode('VALID_ERROR'));
	        }

    		$userDevice = $this->session->get($this->config->websiteinfo->mobileauthkey);
            $platform = isset($userDevice['platform']) ? $userDevice['platform'] : 2;

            $result = $this->TreasureMgr->doBetting($type, $times, $nums, $kind, $platform);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 获取商品信息
     * @param $id
     * @param $page 
     * @param $pageSize 
     */
    public function getBetResults(){
    	if ($this->request->isPost()) {
    		$id = $this->request->getPost('id');
    		$page = $this->request->getPost('page');
    		$pageSize = $this->request->getPost('pageSize');
    		$isValid = $this->validator->validate(array('id'=>$id));
            if (!$isValid) {
                return $this->status->mobileReturn($this->status->getCode('VALID_ERROR'));
            }

            $result = $this->TreasureMgr->getBetResults($id, $page, $pageSize);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /**
     * 直播间获取一元夺宝列表
     * @param $uid 主播uid
     */
    public function getRoomsBetList(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');

            !$uid && $uid = 0;

            $result = $this->TreasureMgr->getRoomsBetList($uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    public function checkRoomBet(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');

            !$uid && $uid = 0;

            $result = $this->TreasureMgr->checkRoomBet($uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

}