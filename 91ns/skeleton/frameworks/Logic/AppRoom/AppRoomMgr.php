<?php

namespace Micro\Frameworks\Logic\AppRoom;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Models\UserInfo;

class AppRoomMgr {

    protected $di;
    protected $status;
    protected $config;
    protected $validator;
    protected $comm;
    protected $userAuth;
    protected $userMgr;
    protected $modelsManager;
    protected $db;
    protected $roomModule;
    protected $normalLib;
    protected $logger;
    protected $pathGenerator;
    protected $url;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->comm = $this->di->get('comm');
        $this->userAuth = $this->di->get('userAuth');
        $this->userMgr = $this->di->get('userMgr');
        $this->db = $this->di->get('db');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->roomModule = $this->di->get('roomModule');
        $this->normalLib = $this->di->get('normalLib');
        $this->logger = $this->di->get('logger');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->url = $this->di->get('url');
    }

    public function errLog($errInfo) {
        $this->logger->error('【AppRoomMgr】 error : ' . $errInfo);
    }

    /**
     * 检查用户直播条件
     * @author YH
     * @param $uid 用户uid
     * @return array
     */
    public function checkUserPublishInfo($uid = 0, $isFirst = 0){
        $isValid = $this->validator->validate(array('uid'=>$uid));
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            // 判断是否签约主播
            $signRes = \Micro\Models\SignAnchor::findFirst('uid = ' . $uid . ' and status not in (0,3,4)');
            $roomRes = \Micro\Models\Rooms::findfirst('uid = ' . $uid);

            if(!$signRes || !$roomRes){
                return $this->status->retFromFramework($this->status->getCode('NOT_SIGN_USER'));
            }

            // 判断是否设置房间封面
            if(!$roomRes->poster){
                return $this->status->retFromFramework($this->status->getCode('NOT_SET_ROOM_PIC'));
            }

            // 判断是不是第一次手机直播
            // $roomLog = \Micro\Models\RoomLog::findfirst('roomId = ' . $roomRes->roomId . ' and rType = 1 and status = 1');
            if($isFirst){
                return $this->status->retFromFramework($this->status->getCode('OK'),array('appLiveTip'=>'手机直播公约'));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 设置直播间title
     * @author YH
     * @param $uid 用户uid
     * @param $title 直播间title
     * @return array
     */
    public function setRoomTitle($uid = 0, $title = ''){
        !$title && $title = '';
        $isValid = $this->validator->validate(array('uid'=>$uid, 'roomtitle'=>$title));
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $roomRes = \Micro\Models\Rooms::findfirst('uid = ' . $uid);
            if(!$roomRes){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            $roomRes->title = $title;
            $roomRes->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 统计该直播场次的观众数
     * @author YH
     * @param $roomId 房间id
     * @param $uid 用户uid
     */
    public function addLiveAudienceLog($roomId = 0, $uid = 0){
        $isValid = $this->validator->validate(array('uid'=>$uid, 'roomId'=>$roomId));
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $roomRes = \Micro\Models\Rooms::findfirst('roomId = ' . $roomId);
            $userRes = \Micro\Models\Users::findfirst('uid = ' . $uid);
            $robotRes = \Micro\Models\UserRobot::findfirst('uid = ' . $uid);
            if(($userRes || $robotRes) && $roomRes && $uid != $roomRes->uid && ($roomRes->liveStatus == $this->config->roomLiveStatus->start || $roomRes->liveStatus == $this->config->roomLiveStatus->pause)){
                $roomLog = \Micro\Models\RoomLog::findfirst('roomId = ' . $roomId . ' order by id desc');
                if($roomLog){
                    $sqlAudience = "insert ignore into pre_live_audience_log(logId,uid) VALUES (".$roomLog->id.",{$uid})";
                    $this->db->execute($sqlAudience);
                }
            }else{
                return $this->status->retFromFramework($this->status->getCode('OPER_NOT_AFFACT'));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取直播间流信息
     * @author YH
     * @param $uid 用户uid
     * @return array
     */
    public function getRoomInfo($uid = 0){
        $isValid = $this->validator->validate(array('uid'=>$uid));
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $return = array();

            $roomRes = \Micro\Models\Rooms::findfirst('uid = ' . $uid);
            if(!$roomRes){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            $return['liveStatus'] = $roomRes->liveStatus;
            // 暂定未直播时直播标签设置为pc方式
            $return['rType'] = 0;
            if($roomRes->liveStatus == $this->config->roomLiveStatus->start || $roomRes->liveStatus == $this->config->roomLiveStatus->pause){
                $return['rType'] = $roomRes->rType;
            }
            $return['streamName'] = $roomRes->streamName ? $roomRes->streamName : '';
            $return['isOpenVideo'] = $roomRes->isOpenVideo;
            $return['videoName'] = '';
            if($roomRes->isOpenVideo == 1){
                $usingVideo = \Micro\Models\Videos::findFirst('isUsing = 1 and status = 0 and uid = ' . $uid);
                if(!empty($usingVideo)){
                    $return['videoName'] = $usingVideo->streamName ? ($this->config->RECInfo->url . $usingVideo->streamName . $this->config->RECInfo->format) : '';
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}