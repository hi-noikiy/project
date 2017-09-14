<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;
use Micro\Frameworks\Logic\User\UserFactory;

class HomeController extends ControllerBase
{
	/**
	 * 获取主播个人档案
	 */
	public function getAnchorInfo(){
		if ($this->request->isPost()) {
			$uid = $this->request->getPost('uid');
			!$uid && $uid = 0;

			$res = \Micro\Models\Users::findfirst('uid = ' . $uid);
			if(empty($res)){
				return $this->status->mobileReturn($this->status->getCode('USER_NOT_EXIST'));
			}

			$user = UserFactory::getInstance($uid);

            // 基础信息
            $result = $this->userMgr->getAnchorInfo($uid);
            if ($result['code'] == $this->status->getCode('OK')) {
                $anchorInfo = $result['data'];
            }else{
                $anchorInfo = array();
            }

            // 徽章
            $item = $user->getUserItemsObject();
            $result = $item->getUserBadge();
            if ($result['code'] == $this->status->getCode('OK')) {
                $anchorInfo['badge'] = $result['data'];
            }else{
                $anchorInfo['badge'] = array();
            }

            // 等级信息
            $result = $this->userMgr->getUserLevelInfo($uid);
            if($result['code'] == $this->status->getCode('OK')){
                $anchorInfo['levelInfo'] = $result['data'];
            }else{
                $anchorInfo['levelInfo'] = array();
            }
            return $this->status->mobileReturn($result['code'], $anchorInfo);
        }
        return $this->proxyError();
    }

    // 获取粉丝贡献榜
    public function getFansContribute(){
    	if ($this->request->isPost()) {
			$page = 1;//$this->request->getPost('page');
            $pageSize = 30;//$this->request->getPost('pageSize');
            $uid = $this->request->getPost('uid');

            $result = $this->userMgr->getFansConsume($uid, $page, $pageSize);
			return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
}