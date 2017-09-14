<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class AppRoomController extends ControllerBase
{
	/**
	 * 检查用户直播条件
	 * @author YH
	 * @param $uid 主播id
	 * @return array
	 */
	public function checkUserPublishInfo(){
		if($this->request->isPost()){
            $uid = $this->request->get('uid');
            $isFirst = $this->request->get('isFirst');
        	$result = $this->appRoomMgr->checkUserPublishInfo($uid, $isFirst);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
	}

	/**
	 * 设置房间title
	 * @author YH
	 * @param $uid 主播id
	 * @param $title 直播间title
	 * @return array
	 */
	public function setRoomTitle(){
		if($this->request->isPost()){
            $uid = $this->request->get('uid');
            $title = $this->request->get('title');
        	$result = $this->appRoomMgr->setRoomTitle($uid, $title);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
	}

	/**
	 * 上传房间封面
	 * @author YH
	 * @param $uid 主播id
	 * @param $title 直播间title
	 * @return array
	 */
	public function uploadAnchorPoster() {
        
    }

    /**
     * 开播通知
     * @author YH
     * @param $roomId 房间id
     * @param $streamName 流名
     * @param $isREC 是否录制录像
     * @return $array
     */
    public function startPublish(){
        if($this->request->isPost()){
            $roomId = $this->request->get('roomId');
            $streamName = $this->request->get('streamName');
            $isREC = $this->request->get('isREC');
        	$result = $this->roomModule->getRoomOperObject()->startPublishFromFlash($roomId, $streamName, $isREC, 1);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 关播通知
     * @author YH
     * @param $roomId 房间id
     * @return $array
     */
    public function stopPublish(){
        if($this->request->isPost()){
            $roomId = $this->request->get('roomId');
            $result = $this->roomModule->getRoomOperObject()->stopPublishFromNodeJs($roomId);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 统计该直播场次的观众数
     * @author YH
     * @param $roomId 房间id
     * @param $uid 用户uid
     * @return array
     */
    public function addLiveAudienceLog(){
    	if($this->request->isPost()){
            $roomId = $this->request->get('roomId');
            $uid = $this->request->get('uid');
        	$result = $this->appRoomMgr->addLiveAudienceLog($roomId, $uid);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获取直播间流信息
     * @author YH
     * @param $uid 用户uid
     * @return array
     */
    public function getRoomInfo(){
        if($this->request->isPost()){
            $uid = $this->request->get('uid');
            $result = $this->appRoomMgr->getRoomInfo($uid);

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
	
}