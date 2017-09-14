<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class ShowController extends ControllerBase
{
	/**
	 * 获取主播节目列表
	 */
	public function getShowList(){
		if ($this->request->isPost()) {
			$uid = $this->request->getPost('uid');
			!$uid && $uid = 0;
            $result = $this->ShowMgr->getShowList($uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 获取用户节目列表
	 */
	public function getBuyShowList(){
		if ($this->request->isPost()) {
			$uid = $this->request->getPost('uid');
            $result = $this->ShowMgr->getBuyShowList($uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 用户点节目
	 */
	public function buyShow(){
		if ($this->request->isPost()) {
			$uid = $this->request->getPost('uid');
			// $id = $this->request->getPost('id');
			$showName = $this->request->getPost('showName');
			$showPrice = $this->request->getPost('showPrice');
			$showType = $this->request->getPost('showType');
			// $buyMethod = $this->request->getPost('buyMethod');
			//, $id, $buyMethod
            $result = $this->ShowMgr->buyShow($uid, $showType, $showName, $showPrice);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
	}

	/**
	 * 获取自选节目价格
	 */
	public function getOptionShow(){
		if ($this->request->isPost()) {
			$optionShow = $this->config->showConfigs->optionShow;
            return $this->status->mobileReturn($this->status->getCode('OK'), array('optionShow'=>$optionShow));
        }
        return $this->proxyError();
	}

}