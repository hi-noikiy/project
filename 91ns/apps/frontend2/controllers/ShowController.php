<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ShowController extends ControllerBase
{
	/**
	 * 获取主播节目列表
	 */
	public function getShowListAction(){
		if ($this->request->isPost()) {
			$uid = $this->request->getPost('uid');
			!$uid && $uid = 0;
            $result = $this->ShowMgr->getShowList($uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 添加节目
	 */
	public function addShowAction(){
		if ($this->request->isPost()) {
			$showName = $this->request->getPost('showName');
			$showPrice = $this->request->getPost('showPrice');
            $result = $this->ShowMgr->addShow($showName, $showPrice);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 编辑节目
	 */
	public function editShowAction(){
		if ($this->request->isPost()) {
			$id = $this->request->getPost('id');
			$showName = $this->request->getPost('showName');
			$showPrice = $this->request->getPost('showPrice');
            $result = $this->ShowMgr->editShow($id, $showName, $showPrice);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 删除节目
	 */
	public function delShowAction(){
		if ($this->request->isPost()) {
			$id = $this->request->getPost('id');
            $result = $this->ShowMgr->delShow($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 获取用户节目列表
	 */
	public function getBuyShowListAction(){
		if ($this->request->isPost()) {
			$uid = $this->request->getPost('uid');
            $result = $this->ShowMgr->getBuyShowList($uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 用户点节目
	 */
	public function buyShowAction(){
		if ($this->request->isPost()) {
			$uid = $this->request->getPost('uid');
			// $id = $this->request->getPost('id');
			$showName = $this->request->getPost('showName');
			$showPrice = $this->request->getPost('showPrice');
			$showType = $this->request->getPost('showType');
			// $buyMethod = $this->request->getPost('buyMethod');
			//, $id, $buyMethod
            $result = $this->ShowMgr->buyShow($uid, $showType, $showName, $showPrice);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 主播审核点播节目
	 */
	public function verifyBuyShowAction(){
		if ($this->request->isPost()) {
			$id = $this->request->getPost('id');
			$status = $this->request->getPost('status');//0-拒绝2-同意1-未处理
			!$status && $status = 0;
            $result = $this->ShowMgr->verifyBuyShow($id, $status);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 主播删除点播节目
	 */
	public function delBuyShowAction(){
		if ($this->request->isPost()) {
			$id = $this->request->getPost('id');
            $result = $this->ShowMgr->delBuyShow($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 获取用户的点歌卡
	 */
	public function getShowCardsAction(){
		if ($this->request->isPost()) {
            $result = $this->ShowMgr->getShowCards();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 获取自选节目价格
	 */
	public function getOptionShowAction(){
		if ($this->request->isPost()) {
			$optionShow = $this->config->showConfigs->optionShow;
            return $this->status->ajaxReturn($this->status->getCode('OK'), array('optionShow'=>$optionShow));
        }
        return $this->proxyError();
	}
}