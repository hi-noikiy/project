<?php

namespace Micro\Frameworks\Logic\Room;

use Micro\Models\GiftLog;
use Micro\Models\GrabLog;
use Micro\Models\RoomLog;
use Micro\Models\UserLog;
use Phalcon\DI\FactoryDefault;
use Micro\Models\Rooms;
use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Models\GiftConfigs;
use Micro\Models\TypeConfig;
use Micro\Models\GrabseatLog;
use Micro\Models\UserProfiles;
use Micro\Models\RicherConfigs;
use Micro\Models\ConsumeLog;
use Respect\Validation\Rules\Date;

use Phalcon\Logger\Adapter\File as FileLogger;

class RoomOper extends RoomBase {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 用户进入房间
     * @param uid 房间拥有者的uid
     */
    public function enterRoom($uid) {
         // 数据验证
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 判断房间表中是否有该房间数据
        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("uid = " . $uid);
            $roomInfo = array();

            $roomId = 0;
            if (empty($roomData)) {   //如果是进入本人房间，则创建一条记录，否则提示错误
                if ($this->isOwnRoom($uid) == false) {
                    return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
                }

                $signAnchor = \Micro\Models\SignAnchor::findFirst('uid = '.$uid);

                $roomData = new Rooms();
                $roomData->uid = $uid;
                $roomData->isRecommend = 0;
                $roomData->title = '开播了! 欢迎观看~';
                $roomData->announcement = '';
                $roomData->publicTime = 0;
                $roomData->syncTime = 0;
                $roomData->liveStatus = 0;
                $roomData->onlineNum = 0;
                // $roomData->showStatus = 1;
                $roomData->robotNum = 0;
                $roomData->roomType = 0;
                $roomData->publishRoute = 0;
                $roomData->useAccelarate = 0;
                $roomData->nextTime = 0;
                $roomData->pushTime = 0;
                $roomData->streamName = '';
                $roomData->isOpenVideo = 0;
                $roomData->videoName = '';
                //若未签约且第一次进入房间则设置为调试为调试状态
                $signArr = array(
                    $this->config->signAnchorStatus->apply,
                    $this->config->signAnchorStatus->refuse,
                    $this->config->signAnchorStatus->unbind
                );
                if(empty($signAnchor) || in_array($signAnchor->status, $signArr)){
                    $roomData->showStatus = 0;
                }else{
                    $roomData->showStatus = 1;
                }
                $roomData->save();

                $roomId = $roomData->roomId;
                $roomInfo['roomId'] = $roomId;
                $roomInfo['roomTitle'] = $roomData->title;
                $roomInfo['liveStatus'] = $roomData->liveStatus;
                $roomInfo['showStatus'] = $roomData->showStatus;
                $roomInfo['publicTime'] = $roomData->publicTime;
                $roomInfo['announcement'] = $roomData->announcement;
                $roomInfo['roomType'] = $roomData->roomType;
                $roomInfo['publishRoute'] = $roomData->publishRoute;
                // $roomInfo['useAccelarate'] = $roomData->useAccelarate;
                $roomInfo['useAccelarate'] = $this->config->radioType;
                $roomInfo['nextTime'] = $roomData->nextTime;
                $roomInfo['streamName'] = $roomData->streamName;
                $roomInfo['isOpenVideo'] = $roomData->isOpenVideo;
                $roomInfo['videoName'] = $roomData->videoName;
            } else {
                $roomId = $roomData->roomId;
                
                $signAnchor = \Micro\Models\SignAnchor::findFirst('uid = '.$uid);
                //若未签约非第一次进入房间则下发调试状态
                $signArr = array(
                    $this->config->signAnchorStatus->apply,
                    $this->config->signAnchorStatus->refuse,
                    $this->config->signAnchorStatus->unbind
                );

                if(empty($signAnchor) || in_array($signAnchor->status, $signArr)){
                    return $this->status->retFromFramework($this->status->getCode('NOT_SIGN_USER'));
                    $roomData->showStatus = 0;
                    // $roomData->save();
                }

                $roomInfo['roomId'] = $roomId;
                $roomInfo['roomTitle'] = $roomData->title;
                $roomInfo['liveStatus'] = $roomData->liveStatus;
                $roomInfo['showStatus'] = $roomData->showStatus;
                $roomInfo['publicTime'] = $roomData->publicTime;
                $roomInfo['announcement'] = $roomData->announcement;
                $roomInfo['publishRoute'] = $roomData->publishRoute;
                // $roomInfo['useAccelarate'] = $roomData->useAccelarate;
                $roomInfo['useAccelarate'] = $this->config->radioType;
                $roomInfo['nextTime'] = $roomData->nextTime;
                $roomInfo['roomType'] = $roomData->roomType;
                $roomInfo['streamName'] = $roomData->streamName ? $roomData->streamName : '';
                $roomInfo['isOpenVideo'] = $roomData->isOpenVideo;
                $roomInfo['videoName'] = '';
                if($roomData->isOpenVideo == 1){
                    $usingVideo = \Micro\Models\Videos::findFirst('isUsing = 1 and status = 0 and uid = ' . $uid);
                    if(!empty($usingVideo)){
                        $roomInfo['videoName'] = $usingVideo->streamName ? ($this->config->RECInfo->url . $usingVideo->streamName . $this->config->RECInfo->format) : '';
                    }
                }
//                $roomInfo['poster'] = $roomData->poster;
                $posterUrl = $roomData->poster;
                $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($posterUrl);
                $roomInfo['poster'] = $posterUrls['poster'];
                $roomInfo['small-poster'] = $posterUrls['small-poster'];           
            }

            // $rewardConfig = $this->config->rewardConfig;
            // $rewardConfig['chargeExtraNum'] = 0;

            $user = $this->userAuth->getUser();
            if($user != NULL){
                $userId = $user->getUid();
                // $rewardConfig['chargeExtraNum'] = $this->di->get('roomModule')->getRoomMgrObject()->getDayChargeNum($userId);
            }
            $rewardConfig['getCoinTime'] = $this->di->get('normalLib')->getBasicConfigs('getCoinTime');
            if(!$rewardConfig['getCoinTime']){
                $rewardConfig['getCoinTime'] = 300;
            }
            $rewardConfig['rewardBoxUrl'] = $this->di->get('normalLib')->getBasicConfigs('rewardBoxUrl');
            if(!$rewardConfig['rewardBoxUrl']){
                $rewardConfig['rewardBoxUrl'] = 'http://m.91ns.com/activities/box';
            }

            $roomInfo['rewardConfig'] = $rewardConfig;

            // 判断这个用户是否被踢出
            if ($user != NULL) {
                //重置魅力时间 
                $resetResult = $user->getUserItemsObject()->resetOnlineGiftTime();
                //添加session数据。。。
                if ($this->session->get("watchTime") == NULL) {//累计观看直播时间 session
                    $this->session->set("watchTime", time());
                }
                if ($this->session->get("enterRoom") == NULL) {//用户进入直播间 session
                    $this->session->set("enterRoom",1);
                }
                //添加USERLOG,以及房间的ROOMLOG
                $userLog = UserLog::findFirst("uid = " . $userId . " AND roomId = " . $roomId);
                //需要加入ROOMLOG
                $roomAdd = 0;
                if (empty($userLog)) {//如果记录不存在
                    $userLog = new UserLog();
                    $userLog->uid = $userId;
                    $userLog->roomId = $roomId;
                    $userLog->updateTime = time();
                    $userLog->count = 1;
                    $roomAdd = 1;
                } else {//记录存在则需要做判断
                    if ($userLog->updateTime < $roomData->publicTime) {//如果已经是不同场次了，则添加新的记录
                        $userLog->count++;

                        $roomAdd = 1;
                    }
                    $userLog->updateTime = time(); //更新记录时间
                }
                $userLog->save();

                if ($roomAdd) {
                    //同时也要添加房间的记录
                    $roomLog = RoomLog::findFirst("roomId = " . $roomId);
                    if (!empty($roomLog) && $roomData->liveStatus == 1) {//如果房间记录不存在，则添加新的记录
                        $roomLog->count++;
                        $roomLog->save();
                    }
                }

                $kickLeftTime = $this->checkUserIsKicked($roomId,$userId);
                if ($kickLeftTime > 0) {
                    $returnData['kickLeftTime'] = $kickLeftTime;
                    return $this->status->retFromFramework($this->status->getCode('USER_IS_KICKED_FROM_ROOM'), $returnData);
                }
            }

            $hoster = UserFactory::getInstance($uid);
            // 获取访问者信息
            $visitorInfo = array();
            if ($user == NULL) {
                $guid = $this->di->get('uid');
                $uuid = $guid->fguid();
                $name = 'visitor_' . $uuid;
                //$time = time();
                //$name = 'visitor_' . $time;
                $userData = $this->userAuth->getSessionData();
                if ($userData['accountId'] == NULL) {
                    $userData['accountId'] = $name;
                }
                $userData['uid'] = '';
                // $userData['name'] = $name;
                // $userData['level'] = 0;
                // $userData['isvisitor'] = 1;
                $this->userAuth->setSessionData($userData);

                $visitorInfo['isLogin'] = false;
                $visitorInfo['points'] = 0;
                $visitorInfo['userId'] = '-' . $uuid;
                $visitorInfo['name'] = $name;
                $visitorInfo['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                //渠道信息
                $cookies = $this->di->get('cookies');
                $mt_source = trim($cookies->get($this->config->websitecookies->utm_source)->getValue());
                if ($mt_source && !in_array($mt_source, $this->config->mt_source->toArray())) {//查询渠道值是否正确
                    $mt_source = '';
                }
                $visitorInfo['source'] = $mt_source;
                //新手引导完成情况
                $visitorInfo['guideStatus'] = $this->config->taskStatus->undone;
                //新手引导完成到第几步、完成情况
                $taskDetail['data']['taskId'] = $this->config->taskIds->login;
                $taskDetail['data']['status'] = $this->config->taskStatus->undone;
                $visitorInfo['guide'] = $taskDetail['data'];
            } else {
                // $space = getuserbyuid($_G['uid']);
                // space_merge($space, 'count');
                // space_merge($space, 'profile');
                // $vistorInfo = getVistorInfo($roomid, $space);
                $visitorInfo = $this->getVisitorInfo($roomId, $hoster, $user);
                $visitorInfo['isLogin'] = true;
                //渠道信息
                $source = $user->getUserInfoObject()->getUserSource();
                $visitorInfo['source'] = $source['ns_source'];

                //新手引导完成情况
                $task = new \Micro\Frameworks\Logic\Task\TaskData();
                $taskInfo = $task->getOneTaskStatus($this->config->taskIds->userGuide, $userId);
                $visitorInfo['guideStatus'] = $taskInfo['status'];
                if ($taskInfo['status'] != $this->config->taskStatus->received) {//新手引导未完成
                    //新手引导完成到第几步、完成情况
                    $taskDetail = $task->getUserGuideTaskInfo($userId);
                    $visitorInfo['guide'] = $taskDetail['data'];
                }
              
            }

            // 获取房主信息
            // $createInfo = getuserbyuid($uid);// 获得房主信息
            // space_merge($createInfo, 'count');
            // space_merge($createInfo, 'profile');
            // space_merge($createInfo, 'status');
            // $hostInfo = getHostInfo($roomid, $createInfo);

            $hosterInfo = $this->getHosterInfo($roomId, $hoster, $user);

            if($this->config->robotVersion == '0.0.2'){
                $roomInfo['roomRobotCount'] = 0;
            }else{
                $roomInfo['roomRobotCount'] = $this->getRoomRobotCount($roomId);
            }
            
            $activity=array();
                    
            //查询是否春节活动
            if (time() > $this->config->springFestival->startTime && time() < $this->config->springFestival->endTime) {
                $activity['springFestival'] = 1;
            }
            //查询是否开启宝箱
            if (time() > $this->config->rewardBox->startTime && time() < $this->config->rewardBox->endTime) {
                $activity['rewardBox'] = 1;
            }
            //夺宝活动
            if (time() >= $this->config->pointsGiftConfigs->activityTime->start || time() <= $this->config->pointsGiftConfigs->activityTime->end) {
                $activity['betPoints'] = 1;
            }
                    
            //是否有红包
            $activityMgr = $this->di->get('activityMgr');
            $redPacketInfo = $activityMgr->isHasRedPacket($roomId);
            $activity['vieUrl'] = $this->config->webType[$this->config->channelType]->mDomain . $this->config->redPacketConfigs->vieUrl;
            $activity['reddetailUrl'] = $this->config->webType[$this->config->channelType]->mDomain . $this->config->redPacketConfigs->reddetailUrl;
            if ($redPacketInfo['code'] == $this->status->getCode('OK') && $redPacketInfo['data']['count']) {
                $activity['redPacket'] = $redPacketInfo['data']['count'];
            }
            $activity['checkRedPacket'] = $this->config->redPacketConfigs->checkRedPacket;


            $roomInfo['activity'] = $activity;
            
            $data = array(
                'sessionID' => $this->validator->authcode($this->session->getId(), 'ENCODE'),
                'host' => "http://" . $this->di->get('request')->getHttpHost(),
                'resHost' => $this->url->getStatic(""),
                'roomInfo' => $roomInfo,
                'hosterInfo' => $hosterInfo,
                'visitorInfo' => $visitorInfo,
                'urlConfig' => $this->config->urlConfig,
                'forbiddenword' =>$this->config->url->forbiddenwordtxt,
                'tips'  => $this->config->url->tips,
                'QQNumber' => $this->config->GMConfig->QQNumber,
                'robotChatDelay' => $this->config->robotConfig->robotChatDelay,
                'robotIncrementDelay' => $this->config->robotConfig->robotIncrementDelay,
                'robotname' => $this->config->url->robotname,
                'robotchat' => $this->config->url->robotchat,
            );

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function sendBroadcast($roomId, $content, $type){
        switch($type){
            case 1:
                $result = $this->sendRoomBroadcast($roomId, $content);
                break;
            case 2:
                $result = $this->sendAllRoomBroadcast($roomId, $content);
                break;
        }

        if($result && $result['code'] == $this->status->getCode('OK')){
            return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
        }else{
            return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
        }
    }

    /**
     * 用户进入NodeJS房间
     * @param roomId 房间Id
     */
    public function enterNodeJSRoom($roomId, $nodejsToken, $isRepeat=0) {
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            $hoster = UserFactory::getInstance($roomData->uid);

            $userData = $this->userAuth->getSessionData();

            // 获取进入nodejs的等级和是否被禁言的标识
            $level = 0;
            $isForbid = 0;
            $user = $this->userAuth->getUser();
            if ($user != NULL) {
                $level = $this->getUserLevelInRoom($roomId, $hoster, $user);
                $isForbid = $this->checkUserIsForbidden($roomId, $user->getUid());
            }

            //设置广播和广播抛弃掩码,后面要处理
            $broadcastMask = 0;
            $broadcastThrowMask = 0;
            $callbackMask = 0;
            $userType = $this->config->roomUserType->guest;          //0表示游客
            if ($user != NULL) {
                $userType = $this->config->roomUserType->user;      //1表示用户
                if ($user->getUid() == $roomData->uid) {
                    $userType = $this->config->roomUserType->hoster;  //2表示主播
                }
            }

            // 获取座驾信息
            $hasCar = 0;
            $userDataInRoom = '';   //json_encode($retData)
            //$nodejsToken = $userData['roomToken'][$roomId];

            $accountId = $userData['accountId'];
            $name = '';   //$_G['uid'] ? $_G['member']['username'] : $_SESSION['accountid'];
            if ($user != NULL) {
                $name = $user->getUserInfoObject()->getNickName();
            } else {
                $name = $accountId;
            }

            //获取nodejs的userData
            $nodejsUserData = array();
            $nodejsUserData['isFamilyLeader'] = 0;
            $nodejsUserData['guardLevel'] = 0;
            $nodejsUserData['manageType'] = 0;
            $nodejsUserData['platform'] = 'pc';
            $nodejsUserData['isForbid'] = $isForbid;
            $nodejsUserData['userId'] = 0;
            $nodejsUserData['name'] = '';
            $nodejsUserData['avatar'] = '';
            $nodejsUserData['vipLevel'] = 0;
            $nodejsUserData['anchorLevel'] = 0;
            $nodejsUserData['richerLevel'] = 0;
            $nodejsUserData['points'] = 0;
            $nodejsUserData['level'] = $level;
            $nodejsUserData['streamName'] = $roomData->streamName ? $roomData->streamName : '';
            // $nodejsUserData['isOpenVideo'] = $roomData->isOpenVideo;
            if ($user != NULL) {
                //$nodejsUserData = $this->getRoomUserData($roomId, $hoster, $user);  
                // $nodejsUserData = array();

                // accountId
                $userInfo = $user->getUserInfoObject()->getData();

                // 昵称、头像
                $nodejsUserData['userId'] = $user->getUid();
                $nodejsUserData['name'] = $userInfo['nickName'];
                $nodejsUserData['avatar'] = $userInfo['avatar'];
                $nodejsUserData['points'] = $userInfo['points'];

                //是否超级管理员
                $nodejsUserData['manageType'] = $userInfo['manageType'];

                // 进入房间的用户的主播富豪等级、VIP等级
                $nodejsUserData['vipLevel'] = $userInfo['vipLevel'];
                if ($userInfo['vipExpireTime'] < time()) { //vip过期
                    $nodejsUserData['vipLevel'] = 0;
                }
                $nodejsUserData['anchorLevel'] = $userInfo['anchorLevel'];
                $nodejsUserData['richerLevel'] = $userInfo['richerLevel'];

                // 获取是否禁言状态
                //$nodejsUserData['isForbid'] = $this->checkUserIsForbidden($roomId, $user->getUid());
                $nodejsUserData['isForbid'] = $isForbid;

                //用户家族信息
                $nodejsUserData['isFamilyLeader'] = $this->di->get('userMgr')->checkUserIsHeader($roomData->uid, $user->getUid());
                
                //查询用户属于哪个军团
                $groupres = $this->di->get('groupMgr')->checkUserGroup($user->getUid());
                if ($groupres['code'] == $this->status->getCode('OK') && $groupres['data']) {
                    $nodejsUserData['group'] = $groupres['data'];
                }
                

                // 获取守护信息
                $nodejsUserData['guardLevel'] = $user->getUserItemsObject()->getGuardLevel($user->getUid(), $hoster->getUid());
 
                // 座驾信息
                $carInfo = $user->getUserItemsObject()->getActiveCarData();
                if ($carInfo) {
                    if ($carInfo['itemLeftTime'] > 0) {
                        $nodejsUserData['carInfo'] = $carInfo;
                    }
                }

                if (!empty($nodejsUserData['carInfo'])) {
                    $hasCar = 1;
                }
                
                // 获取徽章列表
                $badge = $user->getUserItemsObject()->getUserBadge();
                if ($badge['code'] == $this->status->getCode('OK')) {
                    $nodejsUserData['badge'] = $badge['data'];
                }

                $broadcastMask = $broadcastMask |
                    $this->config->roomBroadcastMask->kickUser |
                    $this->config->roomBroadcastMask->forbidTalk |
                    $this->config->roomBroadcastMask->levelUp;
            }

            $nodejsUserData['isRepeat'] = $isRepeat;

            /*// 获取是否断线重连配置
            var_dump($nodejsUserData);die;
            $normalLib = $this->di->get('normalLib');
            $res = $normalLib->getBasicConfigs('isRepeat');
            var_dump($res);die;
            $nodejsUserData['isRepeat'] = $normalLib->getBasicConfigs('isRepeat');*/

            // 有登陆而且3富以上广播，守护要广播，有座驾要广播，vip要广播
            // if (($nodejsUserData['richerLevel'] > 3) ||
            //         ($guardData != NULL) ||
            //         ($nodejsUserData['vipLevel'] > 0) ||
            //         ($hasCar == 1)) {
                // if ($isRepeat == 1) {   //是前端重连
                //     $broadcastMask = $broadcastMask | 
                //         $this->config->roomBroadcastMask->leaveRoom;
                // }
                // else {
                    $broadcastMask = $broadcastMask | 
                        $this->config->roomBroadcastMask->enterRoom |
                        $this->config->roomBroadcastMask->leaveRoom;
                // }
            // }
            $callbackMask = $this->config->callbackMask->leaveRoom;

            //var_dump($broadcastMask);die;
            // 获得设备
            /*$deviceSession = $this->session->get($this->config->websiteinfo->mobileauthkey);
            if(!empty($deviceSession)){
                $platformType = intval($deviceSession['platform']);
                switch($platformType){
                    case 1:
                        $platform = 'pc';
                        break;
                    case 2:
                        $platform = 'ios';
                        break;
                    case 3:
                        $platform = 'android';
                    break;
                    default:
                        $platform = 'pc';
                        break;
                }
            }else{
                $platform = 'pc';
            }*/
            
            //平台信息
            $platform = $this->getPlatform();
            $nodejsUserData['platform'] = $platform;

            $extUid = $userData['uid'];
            $result = $this->enterRoomBase($nodejsToken, $roomId, $accountId, $extUid, $name, $level, $platform ? $platform : 'pc', rand(1, 9999), //这里的rank应该是有问题的!!(可以考虑用uid+roomId或者什么值)
                    json_encode($nodejsUserData), $broadcastMask, $broadcastThrowMask, $callbackMask, $userType,
                    $isForbid, $hasCar, $hasCar ? $userDataInRoom : '');

            if($result['code'] != $this->status->getCode('OK')){
                return $result;
            }

            //进房间成功之后，要将在线人数+1
            $phql = "UPDATE \Micro\Models\Rooms SET onlineNum = onlineNum+1,totalNum=totalNum+1 WHERE roomId = ?0";
            $valueArray = array(
                0 => $roomId
            );
            $this->modelsManager->executeQuery($phql, $valueArray);

            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 提升降低权限
     * @param roomId 房间id
     * @param uid 用户id
     * @param level 等级
     * @param isTemporary 是否临时管理员
     * @return
     */
    public function levelUpPermission($roomId, $uid, $level, $nodejsToken,$isTemporary=0) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $postData['roomid'] = $roomId;
        $postData['uid'] = $uid;
        $postData['level'] = $level;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
            if(UserFactory::isRobot($uid)){
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
            }
            //被操作的用户信息
            $levelUpUser = UserFactory::getInstance($uid);
            $levelUpUserData = $levelUpUser->getUserInfoObject()->getUserAccountInfo();
            
            if ($levelUpUserData['manageType'] == 1) {//超级管理员
                return $this->status->retFromFramework($this->status->getCode('IS_SUPER_ADMIN'));
            }

            //房主
            $hoster = UserFactory::getInstance($roomData->uid);

            //操作者
            $operateUser = UserFactory::getInstance($user->getUid());
            $operateUserData = $operateUser->getUserInfoObject()->getUserAccountInfo();

            $levelUpUserLevel = $this->getUserLevelInRoom($roomId, $hoster, $levelUpUser);
            $operateLevel = $this->getUserLevelInRoom($roomId, $hoster, $user);

            if($levelUpUserLevel == 2 && $level == 3){
                return $this->status->retFromFramework($this->status->getCode('USER_BEOPER_IS_CURRENT_LEVEL'));
            }

            if($operateUserData['manageType'] == 1 || $user->getUid() != $roomData->uid){
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
            }
            
            //皇冠以上的主播管理上限为：20（包括皇冠1）,皇冠以下的主播管理上限为：10
            // 管理员个数50【20151028】
            if ($level == 2 || $level == 3) {
                // $mgrNum = 0;
                $mgrLimit = 50;
                /*$hosterProfiles = $hoster->getUserInfoObject()->getUserProfiles();
                $anchorLevel = $hosterProfiles['anchorLevel'];
                if ($anchorLevel >= 11) {
                    $mgrLimit = 50;
                }*/
                $nums = \Micro\Models\RoomUserStatus::count(
                    "uid <> " . $roomData->uid . " and roomId = " . $roomId . " and level = 2 and " . 
                    "( (levelTimeLine > " . time() . ") or (levelTimeLine = 0 or isnull(levelTimeLine)) )"
                );
                $mgrNum = $nums ? $nums : 0;
                if ($mgrNum >= $mgrLimit) {
                    return $this->status->retFromFramework($this->status->getCode('MGR_NUM_BEYOND_LIMIT'));
                }
            }

            // 提升降低权限成功之后，需要将信息刷到数据库中
            $this->updateUserLevelInRoom($roomId, $uid, $level,$isTemporary);
            //写入日志
            if ($level == 1) {//卸房间管理员
               $logType = $this->config->roomAdminOperType->roomLevelDown;
               $this->setRoomAdminLog($roomId, $uid, $user->getUid(), $logType);
            } elseif ($level == 2 || $level == 3) {//设房间管理员
               $logType = $this->config->roomAdminOperType->roomLevelUp;
               $this->setRoomAdminLog($roomId, $uid, $user->getUid(), $logType);
            }

            // 更新userdata
            $newUserData = $this->setBroadcastParam($levelUpUser, $roomData->uid);
            $this->comm->updateUserData($roomId, $uid, json_encode($newUserData));

            $result = $this->comm->levelUpPermission($nodejsToken, $roomId, $levelUpUserData['accountId'], $level, $levelUpUserLevel);
            if ($result === false) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
            }

            $errorCode = $result['code'];
            if ($errorCode != 0) {
                return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //禁言
    public function forbidTalk($roomId, $uid, $isForbid, $nodejsToken) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $postData['roomid'] = $roomId;
        $postData['uid'] = $uid;
        $postData['isforbid'] = $isForbid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 需要先判断是否可被禁言，或者是被解禁
            # ......
            # ......
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
            //被操作的用户信息
            $isRobot = false;
            if(UserFactory::isRobot($uid)){
                $isRobot = true;
                $forbidUserData = UserFactory::getRobotData($uid);
            }else{
                $forbidUser = UserFactory::getInstance($uid);
                $forbidUserData = $forbidUser->getUserInfoObject()->getUserAccountInfo();
            }

            
            //房主
            $roomUid = $roomData->uid;
            $hoster = UserFactory::getInstance($roomUid);

            //操作者
            $operateUid=$user->getUid();
            $operateUser = UserFactory::getInstance($operateUid);
            $operateUserData = $operateUser->getUserInfoObject()->getUserAccountInfo();

            if($operateUserData['manageType'] != 1){//非超管
                $userMgr = $this->di->get('userMgr');
                //操作者是否直播间家族长
                $operIsRoomLeader = $userMgr->checkUserIsHeader($roomUid, $operateUid);
                if($operIsRoomLeader == 1){//家族长不能操作超管
                    if($forbidUserData['manageType'] == 1 || $uid == $roomUid){
                        return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                    }
                }else if ($operateUid == $roomUid) {//主播不能操作超管和自己的家族长
                    //被踢者是否直播间家族长
                    $kickIsRoomLeader = $userMgr->checkUserIsHeader($roomUid, $uid);
                    if($forbidUserData['manageType'] == 1 || $kickIsRoomLeader == 1){
                        return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                    }
                }else {//
                    //操作者是否房间管理员
                    $operIsRoomManage = $userMgr->checkIsManage($operateUid, $roomId);
                    if($operIsRoomManage == 1){//管理员
                        //被踢者是否直播间家族长
                        $kickIsRoomLeader = $userMgr->checkUserIsHeader($roomUid, $uid);
                        //是否管理
                        $kickIsRoomManage = $userMgr->checkIsManage($uid, $roomId);
                        //铂金守护状态
                        $boGurad = $userMgr->checkGuardByLevel($uid, $roomUid, 3);
                        //vip
                        if($isRobot){
                            $vip = $forbidUserData['vipLevel'];
                        }else{
                            $vip = $forbidUser->getUserInfoObject()->getVipLevel();
                        }
                        if($forbidUserData['manageType'] == 1 || $kickIsRoomLeader == 1 || $uid == $roomUid
                         || $kickIsRoomManage == 1 || $boGurad == 1 || $vip == 2){
                            //临时增加管理员不能互相操作
                            return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                        }
                    }else{//不是管理员
                        //操作者富豪等级
                        $operRicherLevel = $operateUser->getUserInfoObject()->getRicherLevel();
                        if ($operRicherLevel >= $this->config->richerConfigs->forbidLevelLimit) {
                            //被踢者是否直播间家族长
                            $kickIsRoomLeader = $userMgr->checkUserIsHeader($uid, $roomId);
                            //被踢者是否是直播间管理员
                            $kickIsRoomManage = $userMgr->checkIsManage($uid, $roomId);
                            //铂金守护状态
                            $boGurad = $userMgr->checkGuardByLevel($uid, $roomUid, 3);
                            //vip
                            if($isRobot){
                                $vip = $forbidUserData['vipLevel'];
                            }else{
                                $vip = $forbidUser->getUserInfoObject()->getVipLevel();
                            }
                            if ($forbidUserData['manageType'] == 1 || $kickIsRoomLeader == 1 || $uid == $roomUid || $kickIsRoomManage == 1 || $boGurad == 1 || $vip == 2) {
                                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                            }
                            //判断两个用户富豪等级相差几级
                            if($isRobot){
                                $forbidRicherLevel = $forbidUserData['richerLevel'];
                            }else{
                                $forbidRicherLevel = $forbidUser->getUserInfoObject()->getRicherLevel(); //被操作者的富豪等级
                            }

                            if ($operRicherLevel - $forbidRicherLevel < $this->config->richerConfigs->forbidLevelInterval) {
                                 return $this->status->retFromFramework($this->status->getCode('FORBID_LEVEL_LIMIT'));
                            }
                            //查询用户今日操作次数是否达到上限
                            $today=strtotime(date("Ymd"));
                            $opreTimesCount=\Micro\Models\RoomAdminLog::count("operateUid=".$operateUid." and (type=".$this->config->roomAdminOperType->forbitTalk." or type=".$this->config->roomAdminOperType->cacanlForbitTalk.") and createTime>=".$today);
                            if ($opreTimesCount > 0) {
                                $operTimes=0;
                                foreach ($this->config->richerConfigs->forbidLimit as $key => $val) {
                                    if($operRicherLevel>=$key){
                                        $operTimes=$val;
                                    }
                                }
                                if($opreTimesCount>=$operTimes){
                                     return $this->status->retFromFramework($this->status->getCode('FORBID_TIMES_LIMIT'));
                                }
                            }
                        } else {
                            return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                        }
                    }
                }

                // 禁言成功之后，需要将信息刷到数据库中
                if(!$isRobot){
                    $this->updateUserForbidden($roomId, $uid, $isForbid);
                    // 更新userdata
                    $newUserData = $this->setBroadcastParam($forbidUser, $roomUid);
                    $this->comm->updateUserData($roomId, $uid, json_encode($newUserData));
                }

                // 广播
                $result = $this->comm->forbidTalk($nodejsToken, $roomId, $forbidUserData['accountId'], $isForbid);
            }else{
            	//超管不能禁言超管
            	if($forbidUserData['manageType'] == 1 ){
            		return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
            	}
                // 禁言成功之后，需要将信息刷到数据库中
                if(!$isRobot){
                    $this->updateUserForbidden($roomId, $uid, $isForbid);
                    // 更新userdata
                    $newUserData = $this->setBroadcastParam($forbidUser, $roomUid);
                    $this->comm->updateUserData($roomId, $uid, json_encode($newUserData));
                }
                // 广播
                $result = $this->comm->forbidTalk($nodejsToken, $roomId, $forbidUserData['accountId'], $isForbid, $this->status->getCodeInfo('FORBIDDEN_BY_SUPER_ADMIN'));
            }
            
            if ($result === false) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
            }

            $errorCode = $result['code'];
            if ($errorCode != 0) {
                return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
            }
            // 禁言成功之后，需要将信息刷到数据库中
            // $this->updateUserForbidden($roomId, $uid, $isForbid);
            
            //if ($operateUserData['manageType'] == 1) {//超级管理员
            //写入日志
            if ($isForbid == 1) {//禁言
                $this->setRoomAdminLog($roomId, $uid, $user->getUid(), $this->config->roomAdminOperType->forbitTalk);
            } else {//取消禁言
                $this->setRoomAdminLog($roomId, $uid, $user->getUid(), $this->config->roomAdminOperType->cacanlForbitTalk);
            }
            //    }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //踢人
    public function kickUser($roomId, $uid, $nodejsToken) {
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $postData['roomid'] = $roomId;
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 需要先判断是否可被踢
            # ......
            # ......
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
            //被操作的用户信息
            $isRobot = false;
            if(UserFactory::isRobot($uid)){
                $isRobot = true;
                $kickUserData = UserFactory::getRobotData($uid);
            }else{
                $kickUser = UserFactory::getInstance($uid);
                $kickUserData = $kickUser->getUserInfoObject()->getUserAccountInfo();
            }

            //房主
            $roomUid = $roomData->uid;
            $hoster = UserFactory::getInstance($roomUid);
            
            //操作者
            $operateUid = $user->getUid();
            $operateUser = UserFactory::getInstance($operateUid);
            $operateUserData = $operateUser->getUserInfoObject()->getUserAccountInfo();

            if($operateUserData['manageType'] != 1){//非超管
                $userMgr = $this->di->get('userMgr');
                //操作者是否直播间家族长
                $operIsRoomLeader = $userMgr->checkUserIsHeader($roomUid, $operateUid);
                if($operIsRoomLeader == 1){//家族长不能操作超管
                    if($kickUserData['manageType'] == 1 || $uid == $roomUid){
                        return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                    }
                }else if ($operateUid == $roomUid) {//主播不能操作超管和自己的家族长
                    //被踢者是否直播间家族长
                    $kickIsRoomLeader = $userMgr->checkUserIsHeader($roomUid, $uid);
                    if($kickUserData['manageType'] == 1 || $kickIsRoomLeader == 1){
                        return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                    }
                }else {//
                    //操作者是否房间管理员
                    $operIsRoomManage = $userMgr->checkIsManage($operateUid, $roomId);
                    if($operIsRoomManage == 1){//管理员
                        //被踢者是否直播间家族长
                        $kickIsRoomLeader = $userMgr->checkUserIsHeader($roomUid, $uid);
                        //是否管理
                        $kickIsRoomManage = $userMgr->checkIsManage($uid, $roomId);
                        //铂金守护状态
                        $boGurad = $userMgr->checkGuardByLevel($uid, $roomUid, 3);
                        //黄金守护状态
                        $goldenGurad = $userMgr->checkGuardByLevel($uid, $roomUid, 1);
                        //vip
                        if($isRobot){
                            $vip = $kickUserData['vipLevel'];
                        }else{
                            $vip = $kickUser->getUserInfoObject()->getVipLevel();
                        }
                        if($kickUserData['manageType'] == 1 || $kickIsRoomLeader == 1 || $uid == $roomUid
                         || $kickIsRoomManage == 1 || $boGurad == 1 || $goldenGurad == 1 || $vip == 2){
                            //临时增加管理员不能互相操作
                            return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                        }
                    }else{//普通用户
                        //操作者富豪等级
                        $operRicherLevel = $operateUser->getUserInfoObject()->getRicherLevel();
                        if ($operRicherLevel >= $this->config->richerConfigs->kickLevelLimit) {
                            //被踢者是否直播间家族长
                            $kickIsRoomLeader = $userMgr->checkUserIsHeader($uid, $roomId);
                            //被踢者是否是直播间管理员
                            $kickIsRoomManage = $userMgr->checkIsManage($uid, $roomId);
                            //铂金守护状态
                            $boGurad = $userMgr->checkGuardByLevel($uid, $roomUid, 3);
                            //黄金守护状态
                            $goldenGurad = $userMgr->checkGuardByLevel($uid, $roomUid, 1);
                            //vip
                            if($isRobot){
                                $vip = $kickUserData['vipLevel'];
                            }else{
                                $vip = $kickUser->getUserInfoObject()->getVipLevel();
                            }
                            if ($kickUserData['manageType'] == 1 || $kickIsRoomLeader == 1 || $uid == $roomUid || $kickIsRoomManage == 1 || $boGurad == 1 || $goldenGurad == 1 || $vip == 2) {
                                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                            }
                            //判断两个用户富豪等级相差几级
                            if($isRobot){
                                $forbidRicherLevel = $kickUserData['richerLevel'];
                            }else{
                                $forbidRicherLevel = $kickUser->getUserInfoObject()->getRicherLevel(); //被操作者的富豪等级
                            }
                            if ($operRicherLevel - $forbidRicherLevel < $this->config->richerConfigs->kickLevelInterval) {
                                 return $this->status->retFromFramework($this->status->getCode('KICK_LEVEL_LIMIT'));
                            }
                            //查询用户今日操作次数是否达到上限
                            $today=strtotime(date("Ymd"));
                            $opreTimesCount = \Micro\Models\RoomAdminLog::count("operateUid=" . $operateUid . " and type=" . $this->config->roomAdminOperType->kickUser . " and createTime>=" . $today);
                            if ($opreTimesCount > 0) {
                                $operTimes=0;
                                foreach ($this->config->richerConfigs->kickLimit as $key => $val) {
                                    if($operRicherLevel>=$key){
                                        $operTimes=$val;
                                    }
                                }
                                if($opreTimesCount>=$operTimes){
                                     return $this->status->retFromFramework($this->status->getCode('KICK_TIMES_LIMIT'));
                                }
                            }
                        } else {
                            return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                        }
                    }
                }

                // 踢人成功之后，需要将信息刷到数据库中
                if(!$isRobot){
                	
                    $expireTime = strtotime('+30 minute');
                    $this->updateUserKicked($roomId, $uid, 1, $expireTime);
                    // 更新userdata
                    $newUserData = $this->setBroadcastParam($kickUser, $roomUid);
                    $this->comm->updateUserData($roomId, $uid, json_encode($newUserData));
                }

                // 广播
                $result = $this->comm->kickUser($nodejsToken, $roomId, $kickUserData['accountId']);
            }else{//超级管理员操作
            	//超管不能踢超管
            	if($kickUserData['manageType'] == 1){
            		return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
            	}
                // 踢人成功之后，需要将信息刷到数据库中
                if(!$isRobot){
                    $expireTime = strtotime('+30 minute');
                    $this->updateUserKicked($roomId, $uid, 1, $expireTime);
                    // 更新userdata
                    $newUserData = $this->setBroadcastParam($kickUser, $roomUid);
                    $this->comm->updateUserData($roomId, $uid, json_encode($newUserData));
                }
                // 广播
                $result = $this->comm->kickUser($nodejsToken, $roomId, $kickUserData['accountId'], $this->status->getCodeInfo('FORBIDDEN_BY_SUPER_ADMIN'));
            }
            
            if ($result === false) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
            }

            $errorCode = $result['code'];
            if ($errorCode != 0) {
                return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
            }
            // 踢人成功之后，需要将信息刷到数据库中
            // $expireTime = strtotime('+30 minute');
            // $this->updateUserKicked($roomId, $uid, 1, $expireTime);
            
             //if ($operateUserData['manageType'] == 1) {//超级管理员
            //写入日志
            $this->setRoomAdminLog($roomId, $uid, $user->getUid(), $this->config->roomAdminOperType->kickUser);
            // }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function startPublishFromFlash($roomId, $streamName = '', $isREC = 0, $rType = 0) {
        // 添加开播Log
        $logger = new FileLogger($this->config->directory->logsDir.'/roomstatus.log');
        $logger->error('【startPublishFromFlash - request】 : roomId = '.$roomId.' publicTime = '.date("Y:m:d-H:i:s") . ' streamName : ' . $streamName);
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            $lastPubTime = $roomData->pushTime;
            if ($roomData->liveStatus == $this->config->roomLiveStatus->start) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_PUBLISHED'));
            }

            $currentTime = time();
            $liveStatus = $this->config->roomLiveStatus->start;
            $roomData->liveStatus = $liveStatus;
            $roomData->publicTime = $currentTime;
            $roomData->syncTime = $currentTime;
            $roomData->streamName = $streamName;
            $roomData->rType = $rType;
//            $roomData->save();    // 移至下面保存

            //添加开播ROOMLOG
            $roomLog = new RoomLog();
            $roomLog->roomId = $roomData->roomId;
            $roomLog->count = 0;
            $roomLog->endTime = $currentTime;
            $roomLog->publicTime = $roomData->publicTime;
            $roomLog->status = $roomData->showStatus;
            $roomLog->rType = $roomData->rType;
            $roomLog->save();

            //保存视频录像
            if($isREC == 1){
                $videos = new \Micro\Models\Videos();
                $videos->uid = $roomData->uid;
                $videos->createTime = $currentTime;
                $videos->publicTime = $currentTime;
                if($streamName){
                    $arr = explode('_', $streamName);
                    $startTime = isset($arr[1]) ? $arr[1] : $currentTime;
                    $videos->publicTime = $startTime;
                }
                $videos->streamName = $streamName;
                //视频封面【到时file格式】
                $videos->videoPic = '';
                $videos->isUsing = 0;
                $videos->status = 0;
                $videos->save();
            }
                

            $logger->error('【startPublishFromFlash】 : roomId = '.$roomId.' publicTime = '.date("Y:m:d-H:i:s") . ' streamName : ' . $streamName);

            $ArraySubData['controltype'] = "room";
            $broadData['status'] = "play";
            $broadData['publishRoute'] = $roomData->publishRoute;
            // $broadData['useAccelarate'] = $roomData->useAccelarate;
            $broadData['useAccelarate'] = $this->config->radioType;
            $broadData['nextTime'] = $roomData->nextTime;
            $broadData['serverTime'] = $currentTime;
            $broadData['creatorid'] = $roomData->uid;
            $broadData['streamName'] = $streamName;
            $broadData['rType'] = $rType;
            $ArraySubData['data'] = $broadData;
            $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
            //启动机器人
            $this->comm->updateRoomStatus($roomId, 1);

            // 开始直播的时候，初始化抢座信息
            $this->initGrabSeatList($roomData->uid);

            $roomData->save();

            // 推送所有关注用户,与上次发布时间大于半小时再推送
            if(time() - $lastPubTime > 1800){
                $roomData->pushTime = $currentTime;
                $this->di->get('roomModule')->getRoomMgrObject()->pushToUsersByRoomPublish($roomData->uid);
            }
            
            return $this->status->retFromFramework($this->status->getCode('OK'),array('startTime'=>$streamName));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function pausePublishFromFlash($roomId) {
        // 添加停播Log
        $logger = new FileLogger($this->config->directory->logsDir.'/roomstatus.log');
        $logger->error('【pausePublishFromFlash - request】 : roomId = '.$roomId.' stopTime = '.date("Y:m:d-H:i:s"));

        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            if ($roomData->liveStatus == $this->config->roomLiveStatus->stop) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_NOT_PUBLISHED'));
            }
            if ($roomData->liveStatus == $this->config->roomLiveStatus->pause) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_PAUSE'));
            }

            $liveStatus = $this->config->roomLiveStatus->pause;
            $roomData->liveStatus = $liveStatus;
            $roomData->save();

            //记录停播ROOMLOG
            $phql = "UPDATE \Micro\Models\RoomLog SET endTime=?0 WHERE roomId = ?1 AND publicTime = ?2";
            $valueArray = array(
                0 => time(),
                1 => $roomId,
                2 => $roomData->publicTime
            );
            $this->modelsManager->executeQuery($phql, $valueArray);

            $logger->error('【pausePublishFromFlash】 : roomId = '.$roomId.' stopTime = '.date("Y:m:d-H:i:s"));

            $ArraySubData['controltype'] = "room";
            $broadData['status'] = "pause";
            $broadData['creatorid'] = $roomData->uid;
            $ArraySubData['data'] = $broadData;
            $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
            //停止机器人
            //$this->comm->updateRoomStatus($roomId, -1);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function stopPublishFromNodeJs($roomId) {
        // 添加停播Log
        $logger = new FileLogger($this->config->directory->logsDir.'/roomstatus.log');
        $logger->error('【stopPublishFromNodeJs - request】 : roomId = '.$roomId.' stopTime = '.date("Y:m:d-H:i:s"));

        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            if ($roomData->liveStatus == $this->config->roomLiveStatus->stop) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_NOT_PUBLISHED'));
            }

            $roomData->liveStatus = $this->config->roomLiveStatus->stop;
            $roomData->save();

            //记录停播ROOMLOG
            $phql = "UPDATE \Micro\Models\RoomLog SET endTime=?0 WHERE roomId = ?1 AND publicTime = ?2";
            $valueArray = array(
                0 => time(),
                1 => $roomId,
                2 => $roomData->publicTime
            );
            $this->modelsManager->executeQuery($phql, $valueArray);

            $logger->error('【stopPublishFromNodeJs】 : roomId = '.$roomId.' stopTime = '.date("Y:m:d-H:i:s"));

            $ArraySubData['controltype'] = "room";
            $broadData['status'] = "stop";
            $broadData['creatorid'] = $roomData->uid;
            $broadData['isOpenVideo'] = $roomData->isOpenVideo;
            $broadData['videoName'] = '';
            $broadData['rType'] = 0;
            if($roomData->liveStatus == $this->config->roomLiveStatus->start || $roomData->liveStatus == $this->config->roomLiveStatus->pause){
                $broadData['rType'] = $roomData->rType;
            }
            if($broadData['isOpenVideo'] == 1){
                $res = \Micro\Models\Videos::findFirst(
                    'status = 0 and uid = ' . $roomData->uid . ' and isUsing = 1'
                );
                if(!empty($res)){
                    $broadData['videoName'] = $res->streamName ? ($this->config->RECInfo->url . $res->streamName . $this->config->RECInfo->format) : '';
                }
            }
            $broadData['audienceNums'] = 0;
            $broadData['anchorIncomes'] = 0;
            $roomLog = \Micro\Models\RoomLog::findFirst('roomId = ' . $roomId . ' and publicTime = ' . $roomData->publicTime);
            if($roomLog){
                $broadData['audienceNums'] = $this->getAudienceNum($roomLog->id);
                $broadData['anchorIncomes'] = $this->getLiveIncomes($roomData->uid, $roomLog->publicTime, $roomLog->endTime);
            }


            $ArraySubData['data'] = $broadData;
            $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
            //停止机器人
            $this->comm->updateRoomStatus($roomId, -1);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取观众人数
    private function getAudienceNum($logId = 0){
        try {
            $count = \Micro\Models\LiveAudienceLog::count('logId = ' . $logId);
            return $count ? $count : 0;
        } catch (\Exception $e) {
            $logger = new FileLogger($this->config->directory->logsDir.'/roomstatus.log');
            $logger->error('【getAudienceNum】 : logId = '.$logId);
            return 0;
        }
    }

    //获取本场直播收益
    private function getLiveIncomes($receiveUid = 0, $startTime = 0, $endTime = 0){
        try {
            $res = \Micro\Models\ConsumeDetailLog::sum(
                array(
                    'column' => 'income',
                    'conditions' => 'isTuo = 0 and type < ' . $this->config->consumeType->coinType . ' and createTime >= ' . $startTime
                        . ' and createTime < ' . $endTime . ' and income > 0 and receiveUid = ' . $receiveUid
                )
            );
            return $res ? $res : 0;
        } catch (\Exception $e) {
            $logger = new FileLogger($this->config->directory->logsDir.'/roomstatus.log');
            $logger->error('【getLiveIncomes】 : receiveUid = '.$receiveUid . ' publicTime = ' . $startTime);
            return 0;
        }
    }

    // 注：该接口是没有依赖session的操作
    public function startPublish($roomId) { //好像有差一个验证...
        return;
        // 添加开播Log
        $logger = new FileLogger($this->config->directory->logsDir.'/roomstatus.log');
        $logger->error('【startPublish - request】 : roomId = '.$roomId.' publicTime = '.date("Y:m:d-H:i:s"));
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            if ($roomData->liveStatus == 1) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_PUBLISHED'));
            }

            $currentTime = time();
            $liveStatus = 1;
            /* $phql = "UPDATE \Micro\Models\Rooms SET liveStatus = ?0, publicTime=?1, syncTime=?2 WHERE roomId = ?3";        
              $valueArray = array(
              0 => $liveStatus,
              1 => $currentTime,
              2 => $currentTime,
              3 => $roomId
              );
              $this->modelsManager->executeQuery($phql, $valueArray); */
            $roomData->liveStatus = $liveStatus;
            $roomData->publicTime = $currentTime;
            $roomData->syncTime = $currentTime;
            $roomData->save();

            //添加开播ROOMLOG
            $roomLog = new RoomLog();
            $roomLog->roomId = $roomData->roomId;
            $roomLog->count = 0;
            $roomLog->endTime = $currentTime;
            $roomLog->publicTime = $roomData->publicTime;
            $roomLog->save();

            $logger->error('【startPublish】 : roomId = '.$roomId.' publicTime = '.date("Y:m:d-H:i:s"));

            $ArraySubData['controltype'] = "room";
            $broadData['status'] = "play";
            // $broadData['roomtitle'] = $roomData->title;
            $broadData['publishRoute'] = $roomData->publishRoute;
            // $broadData['useAccelarate'] = $roomData->useAccelarate;
            $broadData['useAccelarate'] = $this->config->radioType;
            $broadData['nextTime'] = $roomData->nextTime;
            $broadData['serverTime'] = $currentTime;
            $broadData['creatorid'] = $roomData->uid;
            $ArraySubData['data'] = $broadData;
            $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
            //启动机器人
            $this->comm->updateRoomStatus($roomId, 1);
            /*if ($result === false) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
            }

            $errorCode = $result['code'];
            if ($errorCode != 0) {
                return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
            }*/

            // 开始直播的时候，初始化抢座信息
            $this->initGrabSeatList($roomData->uid);
            // 推送所有关注用户
            $this->di->get('roomModule')->getRoomMgrObject()->pushToUsersByRoomPublish($roomData->uid);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function stopPublish($roomId) {
        return;
        // 添加停播Log
        $logger = new FileLogger($this->config->directory->logsDir.'/roomstatus.log');
        $logger->error('【stopPublish - request】 : roomId = '.$roomId.' stopTime = '.date("Y:m:d-H:i:s"));

        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            if ($roomData->liveStatus == 0) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_NOT_PUBLISHED'));
            }

            $liveStatus = 0;
            $roomData->liveStatus = $liveStatus;
            $roomData->save();

            //记录停播ROOMLOG
            $phql = "UPDATE \Micro\Models\RoomLog SET endTime=?0 WHERE roomId = ?1 AND publicTime = ?2";
            $valueArray = array(
                0 => time(),
                1 => $roomId,
                2 => $roomData->publicTime
            );
            $this->modelsManager->executeQuery($phql, $valueArray);

            $logger->error('【stopPublish】 : roomId = '.$roomId.' stopTime = '.date("Y:m:d-H:i:s"));

            $ArraySubData['controltype'] = "room";
            $broadData['status'] = "stop";
            $broadData['creatorid'] = $roomData->uid;
            $ArraySubData['data'] = $broadData;
            $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
            //停止机器人
            $this->comm->updateRoomStatus($roomId, -1);
            /*if ($result === false) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
            }

            $errorCode = $result['code'];
            if ($errorCode != 0) {
                return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
            }*/

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updatePublish($roomId) {
        return;
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            if ($roomData->liveStatus == 0) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_NOT_PUBLISHED'));
            }

            $currentTime = time();
            $roomData->syncTime = $currentTime;
            $roomData->save();

            $phql = "UPDATE \Micro\Models\RoomLog SET endTime=?0 WHERE roomId = ?1 AND publicTime = ?2";
            $valueArray = array(
                0 => time(),
                1 => $roomId,
                2 => $roomData->publicTime
            );
            $this->modelsManager->executeQuery($phql, $valueArray);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function updatePublishFromFlash($roomId) {
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            if ($roomData->liveStatus == 0) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_NOT_PUBLISHED'));
            }

            $currentTime = time();
            $roomData->syncTime = $currentTime;
            $roomData->save();

            $phql = "UPDATE \Micro\Models\RoomLog SET endTime=?0 WHERE roomId = ?1 AND publicTime = ?2";
            $valueArray = array(
                0 => time(),
                1 => $roomId,
                2 => $roomData->publicTime
            );
            $this->modelsManager->executeQuery($phql, $valueArray);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 抢座
    public function grabSeat($roomId, $seatPos, $seatCount) {
        // 参数判断
        $postData['roomid'] = $roomId;
        $postData['seatpos'] = $seatPos;
        $postData['seatcount'] = $seatCount;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
            
        try {
            // 房间是否存在
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            // 判断必须直播中
            if ($roomData->liveStatus == 0) {
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_NOT_PUBLISHED'));
            }

            $anchorUid = $roomData->uid;

            //判断要比之前的座位数大
            $grabLogData = GrabseatLog::findFirst("anchorUid = " . $anchorUid . " AND seatPos = " . $seatPos);
            if (!empty($grabLogData)) {
                if ($seatCount <= $grabLogData->seatCount) {
                    return $this->status->retFromFramework($this->status->getCode('SEATCOUNT_IS_LITTLE'));
                }
            }

            //判断金钱是否足够
            $price = 100;
//            if ($seatPos == 0) {
//                $price = 200;
//            }
            $price = $price * $seatCount;
            $userProfile = $user->getUserInfoObject()->getUserProfiles();
            $userData = $user->getUserInfoObject()->getData();
            // var_dump($userData['nickName']);die;

            //检查主播能否接收礼物
            if($userData['internalType'] == 1){//条件：送礼者为推广员且主播收益方式为为保底底薪
                $salaryNum = $this->checkAnchorType($anchorUid);
                //检查推广员当日送礼额度是否足够
                $checkRes = $this->checkDayCounsume($price);
                if(!$checkRes){
                    return $this->status->retFromFramework($this->status->getCode('DAY_LIMIT_IS_NOT_ENOUGH'));
                }
                if($salaryNum === false || $salaryNum <= 0){
                    return $this->status->retFromFramework($this->status->getCode('TUO_CANNOT_SEND_TO_ANCHOR'));
                }else{   
                    $leftBonus = $this->checkAnchorDayIncome($anchorUid, $salaryNum, $user->getUid());
                    if($leftBonus < 0 || $leftBonus < $price){
                        return $this->status->retFromFramework($this->status->getCode('LEFT_BONUS_NOT_ENOUGH'));
                    }
                }
                    
            }

            if ($userProfile['cash']< $price) {
                return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));
            }

            // 获取更新消费记录之前的总消费排行
            //$consumRankUids = $this->rankMgr->getTopRoomConsumeUsers($roomId);
            //更新抢座表
            if (!empty($grabLogData)) {
                $grabLogData->seatUid = $user->getUid();
                $grabLogData->seatCount = $seatCount;
                $grabLogData->updateTime = time();
            } else {
                $grabLogData = new GrabseatLog();
                $grabLogData->anchorUid = $anchorUid;
                $grabLogData->seatUid = $user->getUid();
                $grabLogData->seatPos = $seatPos;
                $grabLogData->seatCount = $seatCount;
                $grabLogData->updateTime = time();
            }
            if ($grabLogData->save() == false) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            }

            $income = floor($price * 0.5);
            //更新消费记录
            $familyId = 0;
            $familyResult = $this->familyMgr->getFamilyInfoByUid($anchorUid);
            if ($familyResult['code'] == $this->status->getCode("OK")) {
                $familyId = $familyResult['data']['id'];
            }

#调用存储过程Start#
            //富豪数据
            $richerData = array(
                'richer' => $user,
                'richerCash' => $price,
                'richerExp' => $price
            );
            //主播数据
            $anchor = UserFactory::getInstance($anchorUid);
            $anchorData = array(
                'anchor' => $anchor,
                'anchorCash' => $income,
                'anchorExp' => $income
            );
            $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, $anchorData, $roomId, 0, 0);
            if($resCode['code'] != 'OK'){
                return $resCode;
            }
#调用存储过程End#

            $isTuo = $userData['internalType'] == 2 ? 1 : 0;
            $anchorData = $anchor->getUserInfoObject()->getData();
            // $consumeLog = $user->getUserConsumeObject()->addConsumeLog($this->config->consumeType->grabSeat, $price, $income, $anchorUid, $familyId);
            $consumeLog = $user->getUserConsumeObject()->addConsumeDetailLog($this->config->consumeType->grabSeat, $price, $income, 0, $seatCount, $anchorUid, $anchorData['nickName'], $familyId, $userData['nickName'], $isTuo);

            //添加抢座记录
            /*$grabLog = new GrabLog();
            $grabLog->count = $seatCount;
            $grabLog->seatPos = $seatPos;
            $grabLog->consumeLogId = $consumeLog->id;
            $grabLog->save();*/

            /*// 更新发送者的信息表，富豪等级经验值更新，是否升级，有升级要广播
            $user->getUserConsumeObject()->processConsumeData($user, $price, $price, $roomId);

            // 更新接受者的信息表，主播的经验值更新，是否有升级、有升级要进行广播
            $anchor = UserFactory::getInstance($anchorUid);
            $anchor->getUserConsumeObject()->processIncomeData($anchor, $income, $price, $roomId, $user->getUid());*/

            // 获取更新消费记录之后的总消费排行，判断是否有改变榜单
            //$this->rankMgr->checkRoomConsumeChange($roomId, $consumRankUids);
            
            //日常任务--抢座
            $taskRes = $this->taskMgr->setUserTask($user->getUid(), $this->config->taskIds->seat);
            if ($taskRes['code'] == $this->status->getCode("OK") && isset($taskRes['data']['hasreward'])) {//完成任务
                //领取奖励
                $taskRewardRes=$this->taskMgr->getNewTaskReward($this->config->taskIds->seat);
                if($taskRewardRes['code'] == $this->status->getCode("OK")){
                    $result['taskReward']=$taskRewardRes['data'];
                }
            }

            $userBaseData = $user->getUserInfoObject()->getUserAccountInfo();
            // 抢座成功的广播
            $userInfo = $user->getUserInfoObject()->getUserInfo();
            $userProfiles = $user->getUserInfoObject()->getUserProfiles();
            
            $broadData['userdata']=$this->setBroadcastParam($user);
            $broadData['roomId'] = $roomId;
            $broadData['seatPos'] = $seatPos;
            $broadData['seatCount'] = $seatCount;
            $broadData['createTime'] = date("H:i");
            
            $ArraySubData['controltype'] = "grabSeat";
            $ArraySubData['data'] = $broadData;
            $this->comm->roomBroadcast($roomId, $ArraySubData);


            $result['cash'] = $userProfiles['cash'];    //发给客户端的聊币是充值聊币+收益聊币的总和
            $result['coin'] = $userProfiles['coin'];
            $result['richerExp'] = $userProfiles['richerExp'];
            $levelData = RicherConfigs::findFirst("level=" . $userProfiles['richerLevel']);
            $result['richerHigher'] = $levelData ? $levelData->higher + 1 : 0;
            $result['richerLower'] = $levelData ? $levelData->lower : 0;
            $result['vipLevel'] = $userProfiles['vipLevel'];
            $result['richLevel'] = $userProfiles['richerLevel'];
            $result['points'] = $userProfiles['points'];//积分
            
            
            //删除直播间送礼记录缓存
            $normalLib = $this->di->get('normalLib');
            $cacheKey = 'room_send_gift_' . $roomId;
            $normalLib->delCache($cacheKey);

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //发送房间广播
    public function sendRoomBroadcast($roomId, $content,$isUseItem=0) {
        // 参数判断
        $postData['roomid'] = $roomId;
        $postData['content'] = $content;
        $postData['type'] = $isUseItem;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            // 房间是否存在
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
            
            /*
             * 获得房间广播价格(临时数据)
             */
            $price = 200;
            $consume=$price;//实际扣除的聊币

            $hornType=0;
            
            if ($isUseItem) {//如果用户选择使用道具
                //查询用户道具是否有银喇叭
                $itemInfo = \Micro\Models\UserItem::findfirst("uid=" . $user->getUid() . " and itemType=" . $this->config->itemType->item . " and itemId=1 and itemCount>0");
                if ($itemInfo != false) {//有
                    // $consume = 0;
                    // $price = 0;
                    //扣除银喇叭数量
                    $itemInfo->itemCount-=1;
                    $itemInfo->save();
                }else{
                    return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_BAG_ITEM'));
                }
                $hornType=1;//使用道具里的银喇叭
            } else {//不使用道具， 判断聊币是否够
                $userProfile = $user->getUserInfoObject()->getUserProfiles();
                if ($userProfile['cash']< $price) {
                    return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));
                }
            }
            // 获取更新消费记录之前的总消费排行
            //$consumRankUids = $this->rankMgr->getTopRoomConsumeUsers($roomId);
            //  $income = $price;
            //更新消费记录
            $familyId = 0;
            $familyResult = $this->familyMgr->getFamilyInfoByUid($roomData->uid);
            if ($familyResult['code'] == $this->status->getCode("OK")) {
               $familyId = $familyResult['data']['id'];
            }
            // $consumeLog = $user->getUserConsumeObject()->addConsumeLog($this->config->consumeType->sendRoomBroadcast, $price, $income, $roomData->uid, $familyId);
            //发送喇叭不增加主播收益
            // 富豪等级经验值
########start
            //富豪数据
            $richerData = array(
                'richer' => $user,
                'richerCash' => $isUseItem ? 0 : $consume,
                'richerExp' => $isUseItem ? 0 : $consume
            );
            //主播数据
            $anchor = UserFactory::getInstance($roomData->uid);
            $anchorData = array(
                'anchor' => $anchor,
                'anchorCash' => 0,//$isUseItem ? 0 : 100
                'anchorExp' => 0
            );
            $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, $anchorData, $roomId, 0, 0);
            if($resCode['code'] != 'OK'){
                return $resCode;
            }
########end
            /*$res_code = $user->getUserConsumeObject()->processConsumeData($user, $consume, $price, $roomId);
            if($res_code['code'] != 'OK'){
                return $res_code;//$this->status->retFromFramework($this->status->getCode('OPER_USER_MONEY_ERROR'));
            }*/
            $userInfo = $user->getUserInfoObject()->getData();
            $nickName = $userInfo['nickName'];
            $isTuo = $userInfo['internalType'] == 2 ? 1 : 0;
            $anchorInfo = $anchor->getUserInfoObject()->getData();
            $consumeLog = $user->getUserConsumeObject()->addConsumeDetailLog($this->config->consumeType->sendRoomBroadcast, $price, 0, 1, 1, $roomData->uid, $anchorInfo['nickName'], $familyId, $nickName, $isTuo);
            // $consumeLog = $user->getUserConsumeObject()->addConsumeLog($this->config->consumeType->sendRoomBroadcast, $price, 0, 0, 0);

            // 更新发送者的信息表
            // 主播的经验值，是否有升级、有升级要进行广播
            // $anchor = UserFactory::getInstance($roomData->uid);
            // $anchor->getUserConsumeObject()->processIncomeData($anchor, $income, $price, $roomId, $user->getUid());
            // 获取更新消费记录之后的总消费排行，判断是否有改变榜单
            //$this->rankMgr->checkRoomConsumeChange($roomId, $consumRankUids);
            // 发送飞屏成功的广播
            //$userInfo = $user->getUserInfoObject()->getUserInfo();
           // $broadData['uid'] = $user->getUid();
            $broadData['roomId'] = $roomId;
            //$broadData['nickName'] = $userInfo['nickName'];
            $broadData['content'] = $content;
            $broadData['userdata']=$this->setBroadcastParam($user);
            $ArraySubData['controltype'] = "fly";
            $ArraySubData['data'] =$broadData;
            $result = $this->comm->roomBroadcast($roomId, $ArraySubData);

            $userData = UserProfiles::findFirst("uid = " . $user->getUid());

            $returnData['hornType'] = $hornType;
            $returnData['cash'] = $userData->cash;
            $returnData['coin'] = $userData->coin;
            $returnData['richerExp'] = $userData->exp3;
            $levelData = RicherConfigs::findFirst("level=" . $userData->level3);
            $result['richerHigher'] = $levelData->higher;
            $result['richerLower'] = $levelData->lower;

            return $this->status->retFromFramework($this->status->getCode('OK'), $returnData);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //发送全服广播
    public function sendAllRoomBroadcast($roomId, $content,$isUseItem=0) {
        // 参数判断
        $postData['content'] = $content;
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            // 房间是否存在
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
            
            /*
             * 获得全服广播价格(临时数据)
             */
            $price = 500;
            $consume = $price; //实际扣除的聊币
            
            $hornType=0;
            
            if ($isUseItem) {//如果用户选择使用道具
                //查询用户道具是否有金喇叭
                $itemInfo = \Micro\Models\UserItem::findfirst("uid=" . $user->getUid() . " and itemType=" . $this->config->itemType->item . " and itemId=2 and itemCount>0");
                if ($itemInfo != false) {//有
                    // $consume = 0;
                    // $price = 0;
                    //扣除金喇叭数量
                    $itemInfo->itemCount-=1;
                    $itemInfo->save();
                }else{
                     return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_BAG_ITEM'));
                }
                $hornType=2;//使用道具里的金喇叭
            } else {//不使用道具 判断聊币是否够
                $userProfile = $user->getUserInfoObject()->getUserProfiles();
                if ($userProfile['cash']< $price) {
                    return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));
                }
            }
            // 获取更新消费记录之前的总消费排行
            //$consumRankUids = $this->rankMgr->getTopRoomConsumeUsers($roomId);
            // $income = $price;
            //更新消费记录
            $familyId = 0;
            $familyResult = $this->familyMgr->getFamilyInfoByUid($roomData->uid);
            if ($familyResult['code'] == $this->status->getCode("OK")) {
                $familyId = $familyResult['data']['id'];
            }
            //  $user->getUserConsumeObject()->addConsumeLog($this->config->consumeType->sendAllRoomBroadcast, $price, $income, $roomData->uid, $familyId);
            //发送喇叭不增加主播收益
            // 富豪等级经验值
            /*$res_code = $user->getUserConsumeObject()->processConsumeData($user, $consume, $price, $roomId);
            if($res_code['code'] != 'OK'){
                return $res_code;//$this->status->retFromFramework($this->status->getCode('OPER_USER_MONEY_ERROR'));
            }
            $user->getUserConsumeObject()->addConsumeLog($this->config->consumeType->sendAllRoomBroadcast, $price, 0, 0, 0);*/
########start
            //富豪数据
            $richerData = array(
                'richer' => $user,
                'richerCash' => $isUseItem ? 0 : $consume,
                'richerExp' => $isUseItem ? 0 : $consume
            );
            //主播数据
            $anchor = UserFactory::getInstance($roomData->uid);
            $anchorData = array(
                'anchor' => $anchor,
                'anchorCash' => 0,
                'anchorExp' => 0
            );
            $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, $anchorData, $roomId, 0, 0);
            if($resCode['code'] != 'OK'){
                return $resCode;
            }
########end
            $userInfo = $user->getUserInfoObject()->getData();
            $nickName = $userInfo['nickName'];
            $isTuo = $userInfo['internalType'] == 2 ? 1 : 0;
            $consumeLog = $user->getUserConsumeObject()->addConsumeDetailLog($this->config->consumeType->sendAllRoomBroadcast, $price, 0, 1, 1, $roomData->uid, $roomId, $familyId, $nickName, $isTuo);
            // $consumeLog = $user->getUserConsumeObject()->addConsumeLog($this->config->consumeType->sendAllRoomBroadcast, $price, 0, 0, 0);
            
            /*// 富豪等级经验值
            $user->getUserConsumeObject()->processConsumeData($user, $consume, $price, $roomId);*/

            // 更新发送者的信息表
            // 主播等级经验值，是否升级，有升级要广播
            //   $anchor = UserFactory::getInstance($roomData->uid);
            //  $anchor->getUserConsumeObject()->processIncomeData($anchor, $income, $price, $roomId, $user->getUid());
            // 获取更新消费记录之后的总消费排行，判断是否有改变榜单
            //$this->rankMgr->checkRoomConsumeChange($roomId, $consumRankUids);
            // 发送飞屏成功的广播
            
            //查询主播信息
            $anchorUserInfo=$anchor->getUserInfoObject()->getUserInfo();
            $hostName=$anchorUserInfo['nickName'];
            
            $broadData['hostUid'] = $roomData->uid;
            $broadData['roomId'] = $roomId;
            $broadData['hostName'] = $hostName;//主播昵称
            $broadData['content'] = $content;
            $broadData['userdata'] = $this->setBroadcastParam($user);
            $ArraySubData['controltype'] = "subbroadcast";
            $ArraySubData['data'] =$broadData;

            $this->allRoomBroadcast($ArraySubData);

            $userData = UserProfiles::findFirst("uid = " . $user->getUid());

            $returnData['hornType'] = $hornType;
            $returnData['cash'] = $userData->cash;
            $returnData['coin'] = $userData->coin;
            $returnData['richerExp'] = $userData->exp3;
            $levelData = RicherConfigs::findFirst("level=" . $userData->level3);
            $result['richerHigher'] = $levelData->higher;
            $result['richerLower'] = $levelData->lower;

            return $this->status->retFromFramework($this->status->getCode('OK'), $returnData);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //送礼
    public function sendGift($roomId, $uid, $giftId, $giftCount, $anonymous, $isFromBag = 0, $hitsNum = 0) {
        // 参数判断
        $postData['giftcount'] = $giftCount;
        $postData['roomid'] = $roomId;
        $postData['giftid'] = $giftId;
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        ///
        //$logger = $this->di->get('logger');
        //$session = $this->di->get('session');
        //$logger->error('-----APP test sendGift sessionId = '.$session->getId());
        ///
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        try {
            // 房间是否存在
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if (empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
			
	    $uid = $roomData->uid;  //只能给主播送礼
            
            // 判断必须非本人操作
            /* if ($user->getUid() == $roomData->uid) {
              return $this->status->retFromFramework($this->status->getCode('CANNOT_OPER_OWNER'));
              } */
            $userProfile = $user->getUserInfoObject()->getUserProfiles();
            $userData = $user->getUserInfoObject()->getData();
            $nickName=$userData['nickName'];


            //获取礼物信息
            $giftInfo = GiftConfigs::findFirst("id = " . $giftId);
            
            if (empty($giftInfo)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            //计算总价
            $price = $giftInfo->cash * $giftCount;
            $recvIncome = $giftInfo->recvCoin * $giftCount;
            $coin = $giftInfo->coin * $giftCount;

            if ($coin > 0) {//使用聊豆
                $richerExp = 0; //富豪经验值
                $anchorExp = 0; //主播等级
            } else {
                $richerExp = $price; //富豪经验值
                $anchorExp = $recvIncome; //主播等级 * 0.5
                // $recvExp = $recvIncome; //主播等级 * 0.5
            }
            
            $isFree = false;//是否为免费礼物

            $userId = $user->getUid();

            //检查主播能否接收礼物
            if($price > 0){
                if($userData['internalType'] == 1){//条件：送礼者为推广员且主播收益方式为为保底底薪
                    $isFlower = 0;
                    if ($giftId == $this->config->luckyGiftConfigs->flowerConfigs->giftId || $giftId == $this->config->luckyGiftConfigs->jhConfigs->giftId || $giftId == $this->config->luckyGiftConfigs->qgConfigs->giftId) {
                        $isFlower = 1;//幸运桃花/幸运菊花
                    }
                    if(($giftInfo->typeId == 7) && (!$isFlower)){
                        return $this->status->retFromFramework($this->status->getCode('TUO_CANNOT_SEND_TO_ANCHOR'));
                    }
                    //检查推广员当日送礼额度是否足够
                    $checkRes = $this->checkDayCounsume($price);
                    if(!$checkRes){
                        return $this->status->retFromFramework($this->status->getCode('DAY_LIMIT_IS_NOT_ENOUGH'));
                    }

                    $salaryNum = $this->checkAnchorType($uid);
                    if($salaryNum === false || $salaryNum <= 0){
                        return $this->status->retFromFramework($this->status->getCode('TUO_CANNOT_SEND_TO_ANCHOR'));
                    }else{   
                        $leftBonus = $this->checkAnchorDayIncome($uid, $salaryNum, $userId);
                        if($leftBonus <= 0 || $leftBonus < $price){
                            return $this->status->retFromFramework($this->status->getCode('LEFT_BONUS_NOT_ENOUGH'));
                        }
                    }
                }else if($userData['internalType'] == 2){
                    //检查托当日送礼额度是否足够
                    $checkRes = $this->checkDayTuoCounsume($price);
                    if(!$checkRes){
                        return $this->status->retFromFramework($this->status->getCode('DAY_LIMIT_IS_NOT_ENOUGH'));
                    }
                }
            }

            //普通红包、托/推广员不能送
            if($giftId == $this->config->redPacketConfigs->redGiftId && $userData['internalType'] != 0){
                return $this->status->retFromFramework($this->status->getCode('NOT_ALLOWED_TO_SEND'));
            }
            
            //猴年春节红包、托/推广员不能送
            if($giftId == $this->config->redPacketConfigs->monkeyRedPacket->giftId && $userData['internalType'] != 0){
                return $this->status->retFromFramework($this->status->getCode('NOT_ALLOWED_TO_SEND'));
            }
            
            

            if ($isFromBag) {//如果是背包里的礼物
                 //查询用户物品表
                $itemInfo = \Micro\Models\UserItem::findfirst("uid=" . $user->getUid() . " and itemType=".$this->config->itemType->gift." and itemId=" . $giftId . " and itemCount>0 and status=1 ");
                if ($itemInfo == false || $itemInfo->itemCount < $giftCount) {//背包物品不足
                    return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_BAG_ITEM'));
                }
                //背包里该物品剩余数
                $bagGiftCount = $itemInfo->itemCount - $giftCount;

                //扣除用户物品表的礼物
                $itemInfo->itemCount = $bagGiftCount;
                $itemInfo->save();
                $isFree=true;
                if ($giftInfo->coin) {//聊豆物品
                    $type = $this->config->consumeType->sendGiftByCoin;
                    $amount = $coin;
                    $income = 0;
                } else {//聊币物品
                    $type = $this->config->consumeType->sendGift;
                    $amount = $price;
                    $income = $recvIncome;
                }
                
            } else {//不是背包里的礼物

                //vip要求
                $giftVip = $giftInfo->vipLevel;
                if ($giftVip > 0) {
                    $memberVip = $userData["vipLevel"];
                    if ($memberVip < $giftVip) {
                        $errorData['type'] = "viplevel";
                        $errorData['value'] = $giftVip;
                        return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_VIPLEVEL'), $errorData);
                    }
                }
                //富豪要求
                $giftRicher = $giftInfo->richerLevel;
                if ($giftRicher > 0) {
                    $memberRicher = $userData["richerLevel"];
                    if ($memberRicher < $giftRicher) {
                        $errorData['type'] = "richerlevel";
                        $errorData['value'] = $giftRicher;
                        return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_RICHERLEVEL'), $errorData);
                    }
                }

                $guardData = $user->getUserItemsObject()->getGuardData($roomData->uid);
                //守护要求
                $guardFlag = $giftInfo->guardFlag;
                if ($guardFlag && !$guardData) {
                    return $this->status->retFromFramework($this->status->getCode('NOT_GUARD'));
                }

                //免费(待续)
                $freeCount = $giftInfo->freeCount;
                if ($freeCount > 0) {
                    $total = 0;
                    $timeBegin = strtotime(date("Y-m-d"));
                    $timeEnd = $timeBegin + 24 * 60 * 60;
                    // 免费礼物，判断是达到免费上限
                   
                    $sql = "SELECT sum(count)count FROM Micro\Models\ConsumeLog cl " .
                            "INNER JOIN Micro\Models\GiftLog gl " .
                            "WHERE cl.createTime BETWEEN {$timeBegin} AND {$timeEnd} AND gl.consumeLogId = cl.id AND cl.uid={$userId} AND gl.giftId={$giftId}";
                    $query = $this->modelsManager->createQuery($sql);
                    $records = $query->execute();
                    if ($records->valid()) {
                        $records = $records->toArray()[0];
                        $total = $records['count'];
                    }

                    if ($total + $giftCount > $freeCount) {
                        return $this->status->retFromFramework($this->status->getCode('FREE_GIFT_USE_OUT'));
                    }
                 }

            
                //折扣
                $discount = $giftInfo->discount;

                if ($discount > 0 && $guardData) {
                    $discount = $giftInfo->discount;
                } else {
                    $discount = 10;
                }


                $income = 0;
                $amount = 0;
                $type = $this->config->consumeType->sendGiftByCoin;
                if ($coin > 0) {//使用聊豆
                    if ($userProfile['coin'] < floor($coin * $discount / 10.0)) {//聊豆不够
                        return $this->status->retFromFramework($this->status->getCode('COIN_NOT_ENOUGH'));
                    }
                    $amount = floor($coin * $discount / 10.0);
                } elseif ($price > 0) {//使用聊币
                    if ($userProfile['cash'] < $price * $discount / 10.0) {
                        return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));
                    }
                    $type = $this->config->consumeType->sendGift;
                    $income = $recvIncome;
                    $amount = $price * $discount / 10.0;
                }


                //如果是免费礼物
                if ($freeCount > 0) {
                    $richerExp = 4 * $giftCount;
                    $anchorExp = 2 * $giftCount;
                    $income = 0;
                    $price = 0;
                    $type = $this->config->consumeType->sendGift;
                    $isFree=true;
                }
            }

            // 获取更新消费记录之前的总消费排行
            //$consumRankUids = $this->rankMgr->getTopRoomConsumeUsers($roomId);
            //更新消费记录
            $familyId = 0;
            $familyResult = $this->familyMgr->getFamilyInfoByUid($roomData->uid);
            if ($familyResult['code'] == $this->status->getCode("OK")) {
                $familyId = $familyResult['data']['id'];
            }

#调用存储过程Start#
            $anchor = UserFactory::getInstance($uid);
            if($giftInfo->coin){
                //判断是否增加富豪经验
                $equalRicherExp = 0;//$user->getUserItemsObject()->setConsumeCountLog(1, $amount);//取消聊豆和魅力星富豪经验
                //判断是否增加主播经验
                $equalAnchorExp = $anchor->getUserItemsObject()->setConsumeCountLog(3, $amount);
                //富豪总经验
                $richerExp += $equalRicherExp;
                //主播总经验
                $anchorExp += floor($equalAnchorExp * 0.5);
                //是否聊豆
                $isCoin = 1;
            }else{
                $isCoin = 0;
            }
            //富豪数据
            $richerData = array(
                'richer' => $user,
                'richerCash' => $isFromBag ? 0 : $amount,
                'richerExp' => $richerExp
            );
            //主播数据
            $anchorData = array(
                'anchor' => $anchor,
                'anchorCash' => $income,
                'anchorExp' => $anchorExp
            );

            $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, $anchorData, $roomId, $isCoin, $isFree);
            if($resCode['code'] != 'OK'){
                return $resCode;//$this->status->retFromFramework($this->status->getCode('OPER_USER_MONEY_ERROR'));
            }
#调用存储过程End#

            $receiveData = $anchor->getUserInfoObject()->getData();
            $receivenickName = $receiveData['nickName'];
            $isTuo = $userData['internalType'] == 2 ? 1 : 0;
            $consumeLog = $user->getUserConsumeObject()->addConsumeDetailLog($type, $amount, $income, $giftId, $giftCount, $roomData->uid, $receivenickName, $familyId, $nickName, $isTuo);
            // $consumeLog = $user->getUserConsumeObject()->addConsumeLog($type, $amount, $income, $roomData->uid, $familyId);
            //添加送礼记录
            /*$giftLog = new GiftLog();
            $giftLog->count = $giftCount;
            $giftLog->giftId = $giftId;
            $giftLog->consumeLogId = $consumeLog->id;
            $giftLog->save();*/

            // 获取更新消费记录之后的总消费排行，判断是否有改变榜单
            // $this->rankMgr->checkRoomConsumeChange($roomId, $consumRankUids);
            // 发送礼物成功的广播
            $broadData['uid'] = $userId;
            $broadData['roomId'] = $roomId;
            $broadData['hostUid'] = $uid;
            $broadData['nickName'] = $nickName;
            $broadData['giftCount'] = $giftCount;
            $broadData['giftId'] = $giftInfo->id;
            $broadData['name'] = $giftInfo->name;
            $broadData['configName'] = $giftInfo->configName;
            $broadData['littleFlag'] = $giftInfo->littleFlag;
            $broadData['littleSwf'] = $giftInfo->littleSwf;
            !$hitsNum && $hitsNum = 0;
            $broadData['hitsNum'] = is_numeric($hitsNum) ? $hitsNum : 0;
            if ($giftInfo->coin > 0) {//使用聊豆
                $price = -1;
            }
            $broadData['price'] = $price;
            $broadData['createTime'] = date("H:i");
            $recvUser = UserFactory::getInstance($roomData->uid);

            $broadData['userdata'] = $this->setBroadcastParam($recvUser, $roomData->uid);
            $broadData['oper_userdata'] = $this->setBroadcastParam($user, $roomData->uid);
            $ArraySubData['controltype'] = "gift";


            //判断是否全服广播
            if ($price >= 10000) {
                $broadData['top'] = 1;
                $ArraySubData['data'] = $broadData;
                $this->allRoomBroadcast($ArraySubData);
            } else {
                $ArraySubData['data'] = $broadData;
                $this->comm->roomBroadcast($roomId, $ArraySubData);
            }

            //判断是否幸运礼物中奖
            if ($giftInfo->typeId == 7 && !$isFromBag) {//幸运礼物
                //edit by 2015/11/03
                $activityMgr = $this->di->get('activityMgr');
                $activityMgr->sendLuckyGift($giftInfo,$giftCount,$nickName,$uid,$roomId);
            }
            
            //判断是否是春节礼物 add by 2016/01/22
            if (in_array($giftId, $this->config->springFestival->giftIds->toArray())&& time() > $this->config->springFestival->startTime && time() < $this->config->springFestival->endTime) {
                $activityMgr = $this->di->get('activityMgr');
                $activityMgr->springFestival($roomId, $receiveData, $userData, $giftId, $giftCount);
            }

            //判断是否电影票added by 20160324
            if($giftId == $this->config->anchorMovie->giftId && time() > $this->config->anchorMovie->beginTime && time() < $this->config->anchorMovie->endTime){
                $activityMgr = $this->di->get('activityMgr');
                $activityMgr->anchorMovieBroad($roomId, $uid, $giftId);
            }
                        
            
            //判断是否积分礼物 added by 2015/11/23
            $pointsGiftConfigs = $this->config->pointsGiftConfigs->toArray();
            if(in_array($giftId, $pointsGiftConfigs['pointsGiftIds'])){
                //增加积分
                $perPoints = $pointsGiftConfigs['sendPointsConfigs'][$giftId];
                $getPoints = floor($perPoints * $giftCount);
                
                $user->getUserItemsObject()->addPoints($getPoints, $this->config->pointsType->sendGift); 
            }
            
            //判断是否是红包礼物 add by 2015/10/26
            if ($giftId == $this->config->redPacketConfigs->redGiftId || $giftId == $this->config->redPacketConfigs->monkeyRedPacket->giftId) {
                $activityMgr = $this->di->get('activityMgr');
                $redPacketRes = $activityMgr->setRedPacket($roomId, $giftId);
                if ($redPacketRes['code'] == $this->status->getCode('OK')) {
                    $result['redPacket'] = 1;
                    $result['redPacketType'] = $redPacketRes['data']['redPacketType'];
                }
            }

            if ($isFromBag) {//如果是背包里的礼物
                $result['type'] = $this->config->itemType->gift;
                $result['id'] = $giftId;
                $result['num'] = $bagGiftCount; ///  剩余数量 
            } else {
                //送聊币礼物，触发任务 add by 2015/12/1
                if ($price > 0) {
                    $taskRewardRes=$this->taskMgr->getTotalGiftTask($giftCount, $roomId);
                    if($taskRewardRes['code']==$this->status->getCode("OK")){
                        $result['taskReward']=$taskRewardRes['data'];
                    }
                }
            }


            $userData = UserProfiles::findFirst("uid = " .$userId);
            $result['cash'] = $userData->cash;
            $result['coin'] = $userData->coin;
            $result['richerExp'] = $userData->exp3;
            $result['points'] = $userData->points;
            $levelData = RicherConfigs::findFirst("level=" . $userData->level3);
            $result['richerHigher'] = $levelData->higher;
            $result['richerLower'] = $levelData->lower;
            $result['price'] = $price;
            $result['sendTime'] = date("H:i");
            $result['configName'] = $giftInfo->configName;

        
            
            
            //删除直播间送礼记录缓存
            $normalLib = $this->di->get('normalLib');
            $cacheKey = 'room_send_gift_' . $roomId;
            $normalLib->delCache($cacheKey);

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //向全服广播内容
    //目前只广播开播的房间，加上强制需要广播的房间数组
    public function allRoomBroadcast($data, $roomId=0) {
        //$sql = "showStatus=1 and (liveStatus=1 or liveStatus = 3 or isOpenVideo=1)";
        $sql = "showStatus=1 and liveStatus!=2";
        // if ($roomId != 0) {
        //     $sql = "(".$sql.")"." or roomId=".$roomId;
        // }
        
        $rooms = Rooms::find($sql);
        //$rooms = Rooms::find();
        foreach ($rooms as $room) {
            $this->comm->roomBroadcast($room->roomId, $data);
        }
    }

   //获取直播间包裹信息
    public function getRoomBag() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $result = array();
        try {
            //用户的礼物
            $itemList = \Micro\Models\UserItem::find("uid=" . $user->getUid() . " and itemType=".$this->config->itemType->gift." and itemCount>0 and status=1");
            if ($itemList->valid()) {
                //$giftIdArr = array();
              //  foreach ($itemList as $ki => $vi) {
                   // $giftIdArr[] = $vi->itemId;
               // }
               // $giftIds = implode(',', $giftIdArr);
//                $configList = \Micro\Models\GiftConfigs::find("id in (" . $giftIds . ")");
//                $newConfigList = array();
//                if ($configList->valid()) {
//                    foreach ($configList as $kn => $vn) {
//                        $newConfigList[$vn->id]['name'] = $vn->name; //礼物名称
//                        $newConfigList[$vn->id]['cash'] = $vn->cash; //礼物价钱
//                        $newConfigList[$vn->id]['coin'] = $vn->coin; //礼物价钱
//                        $newConfigList[$vn->id]['configName'] = $vn->configName; //配置名称，索引图片别名用
//                    }
//                }

                foreach ($itemList as $key => $val) {
                    $data['id'] = $val->itemId;
                    $data['type'] = $this->config->itemType->gift;
                   // $data['name'] = $newConfigList[$val->itemId]['name'];
                      $data['num'] = $val->itemCount;
                    //$data['coin'] = $newConfigList[$val->itemId]['coin'];
                   // $data['cash'] = $newConfigList[$val->itemId]['cash'];
                   // $data['configName'] = $newConfigList[$val->itemId]['configName'];
                    array_push($result, $data);
                }
            }
            $return['list'] = $result;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //使用包裹里的礼物
    public function sendBagGift($roomId, $uid, $id, $giftCount, $anonymous, $typeId = 1) {
        switch ($typeId) {
            case $this->config->itemType->gift:
                return $this->sendGift($roomId, $uid, $id, $giftCount, $anonymous, 1); //发送礼物
        }
        return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
    }

    //会员领取vip奖品
    public function getVipReward() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        return $user->getUserItemsObject()->getVipRose();
    }

    //修改房间标题、公告
    public function setRoomContent($title, $announcement) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        if (!$title && !$announcement) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }
        try {
            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $user->getUid());
            if ($roomInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($title) {//修改标题
                $roomInfo->title = $title;
            }
            if ($announcement) {//修改公告
                $roomInfo->announcement = $announcement;
            }
            $result = $roomInfo->save();
            if ($result) {
                //房间广播
                $ArraySubData['controltype'] = "title";
                $data = array();
                if ($title) {//修改标题
                    $data['roomTitle'] = $title;
                }
                if ($announcement) {//修改公告
                    $data['announcement'] = $announcement;
                }
                $ArraySubData['data'] = $data;
                $this->comm->roomBroadcast($roomInfo->roomId, $ArraySubData);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取直播间用户送的最近$num个礼物 、本场送礼的最高记录
    public function getLastSendGiftList($roomId, $num = 20) {
        $postData['roomid'] = $roomId;
        $postData['number'] = $num;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        
        //读取缓存
        $normalLib = $this->di->get('normalLib');
        $cacheKey = 'room_send_gift_' . $roomId;
        $cacheResult = $normalLib->getCache($cacheKey);
        if (isset($cacheResult)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $cacheResult);
        }

        try {
            $list = array();
            $topInfo = array();
            $roomInfo = \Micro\Models\Rooms::findfirst("roomId=" . $roomId);
            if ($roomInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $today = strtotime(date("Ymd"));
            
            //最近的记录$this->config->consumeType->sendRoomBroadcast . ',' . 
            $types = $this->config->consumeType->grabSeat . "," . $this->config->consumeType->sendGift . "," . $this->config->consumeType->sendStar . "," . $this->config->consumeType->sendGiftByCoin;
            $sql = "select cl. uid,cl.type,cl.createTime,cl.itemId,cl.count,cl.amount,ui.nickName,up.level3 "
                    . " from \Micro\Models\ConsumeDetailLog cl "
                    . " inner join  \Micro\Models\UserInfo ui on cl.uid=ui.uid "
                    . " inner join  \Micro\Models\UserProfiles up on cl.uid=up.uid "
                    . " where cl.receiveUid=" . $roomInfo->uid . " and cl.createTime>" . $today . " and cl.type in ({$types})"
                    . " order by cl.createTime desc limit " . $num;
                  

            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid() && $result->toArray()) {
                
                $itemConfigs = $normalLib->getConfigs();
                //, $this->config->consumeType->sendRoomBroadcast => '4'
                $giftArr = array($this->config->consumeType->sendGift => '1', $this->config->consumeType->sendGiftByCoin => '1', $this->config->consumeType->grabSeat => '2', $this->config->consumeType->sendStar => '3');
                foreach ($result as $val) {
                    $data['type'] = $giftArr[$val->type];
                    $data['giftId'] = $val->itemId;
                    $data['giftName'] = $itemConfigs[$val->type][$val->itemId]['name'];
                    $data['giftNum'] = $val->count;
                    $data['configName'] = $itemConfigs[$val->type][$val->itemId]['configName'];
                    if ($val->type == $this->config->consumeType->sendGiftByCoin) {//聊豆
                        $data['priceType'] = 2;
                        $data['price'] = -1;
                    } else {
                        $data['price'] = $val->amount;
                        $data['priceType'] = 1;
                    }
                    $data['createTime'] = date("H:i", $val->createTime);
                    $data['orderTime'] = $val->createTime;

                    $userData['nickName'] = $val->nickName;
                    $userData['richerLevel'] = $val->level3;
                    $userData['uid'] = $val->uid;
                    $data['userdata'] = $userData;
                    array_push($list, $data);
                }

                $sort = array();
                foreach ($list as $v) {
                    $sort[] = $v['orderTime'];
                }
                array_multisort($sort, SORT_ASC, $list);

                //最高记录$this->config->consumeType->sendRoomBroadcast . ',' . 
                $types1 = $this->config->consumeType->grabSeat . "," . $this->config->consumeType->sendGift . "," . $this->config->consumeType->sendStar;
                $sql1 = "select cl. uid,cl.type,cl.createTime,cl.itemId,cl.count,cl.amount,ui.nickName,up.level3 "
                        . " from \Micro\Models\ConsumeDetailLog cl "
                        . " inner join  \Micro\Models\UserInfo ui on cl.uid=ui.uid "
                        . " inner join  \Micro\Models\UserProfiles up on cl.uid=up.uid "
                        . " where cl.receiveUid=" . $roomInfo->uid . " and cl.createTime>" . $today . " and cl.type in ({$types1})"
                        . " order by amount desc, cl.createTime asc limit 1";
                $query1 = $this->modelsManager->createQuery($sql1);
                $result1 = $query1->execute();
                foreach ($result1 as $val) {
                    $data['type'] = $giftArr[$val->type];
                    $data['giftId'] = $val->itemId;
                    $data['giftName'] = $itemConfigs[$val->type][$val->itemId]['name'];
                    $data['giftNum'] = $val->count;
                    $data['configName'] = $itemConfigs[$val->type][$val->itemId]['configName'];

                    $data['price'] = $val->amount;
                    $data['priceType'] = 1;
                    $data['createTime'] = date("H:i", $val->createTime);
                    $data['orderTime'] = $val->createTime;

                    $userData['nickName'] = $val->nickName;
                    $userData['richerLevel'] = $val->level3;
                    $userData['uid'] = $val->uid;
                    $data['userdata'] = $userData;
                    array_push($topInfo, $data);
                    break;
                }
            }

            $return['list'] = $list;
            $return['topInfo'] = $topInfo;
            
            //设置缓存
            $liftTime = 10; //有效期10秒
            $normalLib->setCache($cacheKey, $return, $liftTime);
            
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //管理员操作日志记录
    public function setRoomAdminLog($roomId, $beOperateUid, $operateUid,$type) {
        $info = new \Micro\Models\RoomAdminLog();
        $info->beOperateUid = $beOperateUid;
        $info->operateUid = $operateUid;
        $info->roomId = $roomId;
        $info->type = $type;
        $info->createTime = time();
        $info->save();
    }
    
    //判断vip礼物是否已领取
    public function getVipGiftStatus() {
        try {
            // 用户必须登录
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result['status'] = 0;
            $userInfo = array();
            $uid = $user->getUid();
            $userObject = UserFactory::getInstance($uid);
            $userResult = $userObject->getUserInfoObject()->getUserVip();
            if ($userResult['code'] == $this->status->getCode('OK')) {
                $userInfo = $userResult['data'];
            } else {
                return $this->status->retFromFramework($userResult['code'], $userResult['data']);
            }
            if ($userInfo['vipLevel1'] || $userInfo['vipLevel2']) {//是vip
                $task = new \Micro\Frameworks\Logic\Task\TaskData();
                if ($userInfo['vipLevel1'] && $userInfo['vipLevel2']) {//至尊vip、普通vip
                    //查询今天是否领取过了
                    $taskInfo1 = $task->getOneTaskStatus($this->config->taskIds->vipReward1,$uid);
                    $taskInfo2 = $task->getOneTaskStatus($this->config->taskIds->vipReward2,$uid);
                    if ($taskInfo1['status'] == $this->config->taskStatus->received && $taskInfo2['status'] ==  $this->config->taskStatus->received) {//已领取
                        $result['status'] = 1;
                        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
                    }
                   $task->editUserTask($uid, $this->config->taskIds->vipReward1, $this->config->taskStatus->done);
                   $task->editUserTask($uid, $this->config->taskIds->vipReward2, $this->config->taskStatus->done);
                } elseif ($userInfo['vipLevel2']) {//至尊vip
                    $taskInfo2 = $task->getOneTaskStatus($this->config->taskIds->vipReward2,$uid);
                    if ($taskInfo2['status'] ==  $this->config->taskStatus->received) {//已领取
                        $result['status'] = 1;
                        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
                    }
                    $task->editUserTask($uid, $this->config->taskIds->vipReward2, $this->config->taskStatus->done);
                } elseif ($userInfo['vipLevel1']) {//普通vip
                    $taskInfo1 = $task->getOneTaskStatus($this->config->taskIds->vipReward1,$uid);
                    if ($taskInfo1['status'] ==  $this->config->taskStatus->received) {//已领取
                        $result['status'] = 1;
                        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
                    }
                    $task->editUserTask($uid, $this->config->taskIds->vipReward1, $this->config->taskStatus->done);
                }
            } else {//不是vip
                return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_VIPLEVEL'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //物品接口
    public function getItemsBaseConfiglist() {
        try {
            $items = array();
            $giftList= \Micro\Models\GiftConfigs::find();
            if ($giftList->valid()) {
                foreach ($giftList as $val) {
                    $data = array();
                    $data['id'] = $val->id;
                    $data['type'] = $this->config->itemType->gift;
                    $data['name'] = $val->name;
                    $data['coin'] = $val->coin;
                    $data['cash'] = $val->cash;
                    $data['configName'] = $val->configName;
                    $data['description'] = $val->description;
                    //$data['typeId'] = $val->typeId;
                    $data['littleFlag'] = $val->littleFlag;
                    array_push($items, $data);
                    
                }
            }
            $carList = \Micro\Models\CarConfigs::find();
            if ($carList->valid()) {
                foreach ($carList as $val) {
                    $data = array();
                    $data['id'] = $val->id;
                    $data['type'] = $this->config->itemType->car;
                    $data['name'] = $val->name;
                    $data['cash'] = $val->price;
                    $data['coin'] = 0;
                    $data['configName'] = $val->configName;
                    //$data['typeId'] = $val->typeId;
                    array_push($items, $data);
                }
            }
            
            $itemList = \Micro\Models\ItemConfigs::find();
            if ($itemList->valid()) {
                foreach ($itemList as $val) {
                    $data = array();
                    $data['id'] = $val->id;
                    $data['type'] = $this->config->itemType->item;
                    $data['name'] = $val->name;
                    $data['cash'] = $val->cash;
                    $data['coin'] = 0;
                    $data['configName'] = $val->configName;
                    $data['description'] = $val->description;
                    //$data['typeId'] = $val->type;
                    array_push($items, $data);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $items);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    //物品接口
    public function getMobileItemsBaseConfiglist() {
        try {
            $items = array();
            $giftList= \Micro\Models\GiftConfigs::find();
            if ($giftList->valid()) {
                foreach ($giftList as $val) {
                    $data = array();
                    $data['id'] = $val->id;
                    $data['type'] = $this->config->itemType->gift;
                    $data['name'] = $val->name;
                    $data['coin'] = $val->coin;
                    $data['cash'] = $val->cash;
                    $data['configName'] = $val->configName;
                    $data['description'] = $val->description;
                    //$data['typeId'] = $val->typeId;
                    $data['littleFlag'] = $val->littleFlag;
                    array_push($items, $data);

                }
            }
//            $carList = \Micro\Models\CarConfigs::find();
//            if ($carList->valid()) {
//                foreach ($carList as $val) {
//                    $data = array();
//                    $data['id'] = $val->id;
//                    $data['type'] = $this->config->itemType->car;
//                    $data['name'] = $val->name;
//                    $data['cash'] = $val->price;
//                    $data['coin'] = 0;
//                    $data['configName'] = $val->configName;
//                    //$data['typeId'] = $val->typeId;
//                    array_push($items, $data);
//                }
//            }

            $itemList = \Micro\Models\ItemConfigs::find();
            if ($itemList->valid()) {
                foreach ($itemList as $val) {
                    $data = array();
                    $data['id'] = $val->id;
                    $data['type'] = $this->config->itemType->item;
                    $data['name'] = $val->name;
                    $data['cash'] = $val->cash;
                    $data['coin'] = 0;
                    $data['configName'] = $val->configName;
                    $data['description'] = $val->description;
                    //$data['typeId'] = $val->type;
                    array_push($items, $data);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $items);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 计算推广员给底薪主播送礼
     * 【根据新的计算方式不需要考虑是否家族和分成比例】
     */
    public function checkAnchorDayIncome($uid, $salaryNum, $userId = 0){
        //获取保底底薪比例
        $ratioNum = $this->getRatioNum($uid) / 100;
        if(!$ratioNum){
            return 0;
        }
        //最多收益金额
        $mostMoney = $ratioNum * $salaryNum;

        //获取当日可收取剩余收益
        
        // $days = date('t');//本月天数
        
        $nowDay = date('d');//本月日期
        
        // $leftDays = $days - $nowDay + 1;//本月剩余天数

        $startTime = strtotime(date('Y-m-d',time()));//今天开始时间戳

        // $startMonthTime = strtotime(date('Y-m',time()));//月初时间戳

        if ($nowDay >= 11) {
            $days = date('t');
            $leftDays = $days - $nowDay + 11;
            $startMonthTime = strtotime(date('Y-m-11',time()));//月初时间戳
        } else {
            $days = date('t',strtotime('-1 month', strtotime(date('Y-m-01'))));
            $leftDays = 11 - $nowDay;
            $startMonthTime = strtotime(date('Y-m-11', strtotime('-1 month', strtotime(date('Y-m-01')))));
        }

        $endTime = $startTime + 3600 * 24;//今天截止时间戳

        $table = " \Micro\Models\ConsumeDetailLog a LEFT JOIN \Micro\Models\Users b ON a.uid = b.uid ";
        $field = " SUM(a.income) AS income ";

        //截止今天前收益
        $whereHaving = " a.income > 0 and a.receiveUid = " . $uid . " AND a.createTime BETWEEN " . $startMonthTime . " AND " . $startTime . " AND b.internalType=1";//' and a.uid = ' . $userId;// . 
        $sqlHaving = "SELECT " . $field . " FROM " . $table . " WHERE " . $whereHaving;
        $queryHaving = $this->modelsManager->createQuery($sqlHaving);
        $resultHaving = $queryHaving->execute();
        if ($resultHaving->valid()) {
            foreach ($resultHaving as $val) {
                $havingBonus = $val->income;
            }
        }else{
            $havingBonus = 0;
        }

        //今天已收益
        $whereToday = " a.income > 0 and a.receiveUid = " . $uid . " AND a.createTime BETWEEN " . $startTime . " AND " . $endTime . " AND b.internalType=1";//. ' and a.uid = ' . $userId;// 
        $sqlToday = "SELECT " . $field . " FROM " . $table . " WHERE " . $whereToday;
        $queryToday = $this->modelsManager->createQuery($sqlToday);
        $resultToday = $queryToday->execute();
        if ($resultToday->valid()) {
            foreach ($resultToday as $val) {
                $todayBonus = $val->income;
            }
        }else{
            $todayBonus = 0;
        }

        $leftBonus = ceil((($mostMoney * 100 - $havingBonus) / $leftDays - $todayBonus));

        return $leftBonus;

    }


    //检查主播当日以获取的收益是否已经达到上限
    public function checkIncomeUp($uid, $salaryNum){
        //获取保底底薪比例
        $ratioNum = $this->getRatioNum($uid) / 100;        

        //获取分成比例
        $bonusRatio = $this->getBonusRatio($uid) / 100;

        //最多收益金额
        $mostMoney = $ratioNum * $salaryNum;

        //获取当日可收取剩余收益
        $leftBonus = $this->getLeftMoney($uid, $mostMoney, $bonusRatio);

        return $leftBonus;
    }

    /**
     * 获取底薪比例
     * @return 返回底薪比例
     */
    public function getRatioNum($uid){
        try {
            /*$anchorInfo = \Micro\Models\SignAnchor::findFirst('uid = '.$uid.' and familyId > 0');
            if(empty($anchorInfo)){
                $parameters = array(
                    "key" => $this->config->websiteinfo->ratioconfig['key']
                );
                $ratioRes = \Micro\Models\BaseConfigs::findfirst(array(
                    "conditions" => "key=:key:",
                    "bind" => $parameters,
                ));
                return !empty($ratioRes) ? $ratioRes->value : $this->config->websiteinfo->ratioconfig['default'];
            }else{
                return 50;
            }*/
            $parameters = array(
                "key" => $this->config->websiteinfo->ratioconfig['key']
            );
            $ratioRes = \Micro\Models\BaseConfigs::findfirst(array(
                "conditions" => "key=:key:",
                "bind" => $parameters,
            ));
            return !empty($ratioRes) ? intval($ratioRes->value) : 0;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    /**
     * 获取分成比例
     * @param $anchorId 主播ID
     * @return 返回分成比例
     */
    public function getBonusRatio($anchorId){
        //查询是否为例外用户
        $exInfo = \Micro\Models\InvUserException::findfirst("type=" . $this->config->exceptionType->bonus . " and uid=" . $anchorId);
        if(!empty($exInfo)){
            return $exInfo->value;
        }

        $invMgrBase = new \Micro\Frameworks\Logic\Investigator\InvMgrBase();
        $result = $invMgrBase->getAnchorRuleResult($anchorId);

        return $result;
    }

    /**
     * 检查主播是否为“保底”底薪方式
     * @param $anchorId 主播ID
     * @return 返回底薪或false
     */
    public function checkAnchorType($anchorId){

        //调用自动变更接口
        $invBase = new \Micro\Frameworks\Logic\Investigator\InvBase();
        $result = $invBase->checkAnchorChange($anchorId);

        if(!$result){
            $basicSalary = \Micro\Models\BasicSalary::findfirst('uid = ' . $anchorId . ' AND type=' . $this->config->salaryType->keepLow . ' ORDER BY id DESC');

            if(!empty($basicSalary)){
                //判断底薪时间是否过期
                if($basicSalary->status == 1 || $basicSalary->expirationTime > time()){
                    return $basicSalary->money;
                }
            }
        }else{
            return $result->money;
        }
            

        return false;

        //返回底薪
        // return !empty($basicSalary) ? $basicSalary->money : false;

    }

    //
    /**
     * 获取当天剩余收益值
     * @param $anchorId 主播ID
     * @param $mostMoney  主播最多收益金额
     * @param $bonusRatio 分成比例
     * @return $leftBonus 剩余可接收聊币
     */
    public function getLeftMoney($anchorId, $mostMoney, $bonusRatio){
        //本月天数
        $days = date('t');
        //本月月份
        $nowMonth = date('m');
        //本月日期
        $nowDay = date('d');
        //本月剩余天数
        $leftDays = $days - $nowDay + 1;

        //每天最大收益(聊币)
        // $dayMaxBonus = ceil(100 * $mostMoney / $bonusRatio / $days);

        $startTime = strtotime(date('Y-m-d',time()));
        $startMonthTime = strtotime(date('Y-m',time()));
        $endTime = $startTime + 3600*24;

        //判断主播是否是家族内的成员
        //(1)不是，走正常的流程
        //(2)是，获取主播加入家族的时间
        //   1.1 如果不是本月内的时间，则按正常的主播分成比例进行计算
        //   1.2 如果是本月内的时间，不是当天的，先获取非家族的时候的收益，然后获取家族的时候的收益，并相加
        //   1.3 如果是当天内的时间，先获取当天之前的收益，然后再算当天的收益（家族+非家族）
        //(3)最终结果，得到当天之前的收益和当天的收益
        //接口：(1)获取非家族的主播收益;(2)获取家族情况的主播收益
        
        //查询收益
        $table = " \Micro\Models\ConsumeLog a LEFT JOIN \Micro\Models\Users b ON a.uid = b.uid ";
        $field = " SUM(a.income) AS income ";
        //当日收益金额
        $whereToday = " a.anchorId=".$anchorId." AND a.createTime BETWEEN ".$startTime." AND ".$endTime." AND b.internalType=1";
        $sqlToday = "SELECT ".$field." FROM ".$table." WHERE ".$whereToday;
        $queryToday = $this->modelsManager->createQuery($sqlToday);
        $resultToday = $queryToday->execute();
        if ($resultToday->valid()) {
            foreach ($resultToday as $val) {
                $todayBonus = $val->income;
            }
        }else{
            $todayBonus = 0;
        }
        /*//截止当日已得到收益金额
        $whereHaving = " a.anchorId=".$anchorId." AND a.createTime BETWEEN ".$startMonthTime." AND ".$startTime." AND b.internalType=1";
        $sqlHaving = "SELECT ".$field." FROM ".$table." WHERE ".$whereHaving;
        $queryHaving = $this->modelsManager->createQuery($sqlHaving);
        $resultHaving = $queryHaving->execute();
        if ($resultHaving->valid()) {
            foreach ($resultHaving as $val) {
                $havingBonus = $val->income;
            }
        }else{
            $havingBonus = 0;
        }*/

        //判断是否为家族
        $anchorInfo = \Micro\Models\SignAnchor::findFirst('uid = '.$anchorId.' and familyId > 0');
        $incomeRatio = 1;//收益正常计算
        if(empty($signAnchor)){//若为非家族
            $incomeRatio = 1;//收益正常计算
            $familyAnchor = false;
        }else{//家族成员
            $incomeRatio = 0.5;//收益需要乘以默认系数0.5
            $bonusRatio = 0.5;//分成比例设为0.5默认值
            $familyAnchor = true;
        }

        $havingBonus = $this->getBeforeBonus($anchorId, $startTime, $startMonthTime, $familyAnchor);

        $leftBonus = ceil((($mostMoney * 100 - $havingBonus) / $leftDays - $todayBonus * $incomeRatio) / $bonusRatio);

        return $leftBonus;

    }

    //获取之前收益值
    private function getBeforeBonus($anchorId, $startTime, $startMonthTime, $familyAnchor = true){
        $familyLogData = \Micro\Models\FamilyLog::findFirst('uid = ' . $anchorId . ' order by id DESC');
        if(!empty($familyLogData)){
            if($familyAnchor){
                $logTime = $familyLogData->joinTime;
                $beforeRatio = 1;//加入或退出家族前的分成比例
                $nowRatio = 0.5;//加入或退出家族后的的分成比例
            }else{
                $logTime = $familyLogData->outOfTime;
                $beforeRatio = 0.5;
                $nowRatio = 1;
            }
            // $logTime = $familyAnchor ? $familyLogData->joinTime : $familyLogData->outTime;
            if($logTime >= $startTime){
                //*Before
                $sql = "SELECT SUM(a.income) AS income FROM \Micro\Models\ConsumeLog a LEFT JOIN \Micro\Models\Users b ON a.uid = b.uid WHERE a.anchorId=".$anchorId." AND a.createTime BETWEEN ".$startMonthTime." AND ".$startTime." AND b.internalType=1";
                $queryHaving = $this->modelsManager->createQuery($sql);
                $resultHaving = $queryHaving->execute();
                if ($resultHaving->valid()) {
                    foreach ($resultHaving as $val) {
                        $havingBonus = $val->income * $beforeRatio;
                    }
                }else{
                    $havingBonus = 0;
                }
                return $havingBonus;
            }elseif($logTime > $startMonthTime){
                //*Before
                $sql1 = "SELECT SUM(a.income) AS income FROM \Micro\Models\ConsumeLog a LEFT JOIN \Micro\Models\Users b ON a.uid = b.uid WHERE a.anchorId=".$anchorId." AND a.createTime BETWEEN ".$startMonthTime." AND ".$logTime." AND b.internalType=1";
                $queryHaving1 = $this->modelsManager->createQuery($sql1);
                $resultHaving1 = $queryHaving1->execute();
                if ($resultHaving1->valid()) {
                    foreach ($resultHaving1 as $val) {
                        $havingBonus1 = $val->income * $beforeRatio;
                    }
                }else{
                    $havingBonus1 = 0;
                }
                //*Now
                $sql2 = "SELECT SUM(a.income) AS income FROM \Micro\Models\ConsumeLog a LEFT JOIN \Micro\Models\Users b ON a.uid = b.uid WHERE a.anchorId=".$anchorId." AND a.createTime BETWEEN ".$logTime." AND ".$startTime." AND b.internalType=1";
                $queryHaving2 = $this->modelsManager->createQuery($sql2);
                $resultHaving2 = $queryHaving2->execute();
                if ($resultHaving2->valid()) {
                    foreach ($resultHaving2 as $val) {
                        $havingBonus2 = $val->income * $nowRatio;
                    }
                }else{
                    $havingBonus2 = 0;
                }
                return $havingBonus1 + $havingBonus2;
            }else{
                //*Now
                $sql = "SELECT SUM(a.income) AS income FROM \Micro\Models\ConsumeLog a LEFT JOIN \Micro\Models\Users b ON a.uid = b.uid WHERE a.anchorId=".$anchorId." AND a.createTime BETWEEN ".$startMonthTime." AND ".$startTime." AND b.internalType=1";
                $queryHaving = $this->modelsManager->createQuery($sql);
                $resultHaving = $queryHaving->execute();
                if ($resultHaving->valid()) {
                    foreach ($resultHaving as $val) {
                        $havingBonus = $val->income * $nowRatio;
                    }
                }else{
                    $havingBonus = 0;
                }
                return $havingBonus;
            }
        }else{
            //此时为非家族主播
            $sql = "SELECT SUM(a.income) AS income FROM \Micro\Models\ConsumeLog a LEFT JOIN \Micro\Models\Users b ON a.uid = b.uid WHERE a.anchorId=".$anchorId." AND a.createTime BETWEEN ".$startMonthTime." AND ".$startTime." AND b.internalType=1";
            $queryHaving = $this->modelsManager->createQuery($sql);
            $resultHaving = $queryHaving->execute();
            if ($resultHaving->valid()) {
                foreach ($resultHaving as $val) {
                    $havingBonus = $val->income;
                }
            }else{
                $havingBonus = 0;
            }
            return $havingBonus;
        }

    }
    
   //获取主播某时间段内的播出时长，按天计算
    public function getAnchorBroadcastTime($roomId, $startTime, $endTime, $page=0, $pageSize=10,$order='') {
        try {
             $newTimeResult = array();
            /*             * $sql = "SELECT l.publicTime, l.endTime FROM \Micro\Models\RoomLog l "
              . " INNER JOIN \Micro\Models\Rooms r on r.roomId=l.roomId "
              . " WHERE r.uid=" . $uid
              . " AND l.endTime>0 AND (( l.publicTime>=" . $startTime . " AND l.publicTime<=" . $endTime . " ) OR ( l.endTime>=" . $startTime . " AND l.endTime<=" . $endTime . "))"
              . " AND r.showStatus <> 0  ORDER BY l.publicTime ASC";
              $query = $this->modelsManager->createQuery($sql);
              $timeResult = $query->execute();
              if ($timeResult->valid()) {
              foreach ($timeResult as $kt => $vt) {
              $thisStart = $vt->publicTime;
              $thisEnd = $vt->endTime;
              $thisStartDate = date("Ymd", $thisStart);
              $thisEndDate = date("Ymd", $thisEnd);
              !isset($newTimeResult[$thisStartDate]) && $newTimeResult[$thisStartDate] = 0;
              if ($thisStartDate == $thisEndDate) {//同一天
              $newTimeResult[$thisStartDate]+= $thisEnd - $thisStart; //用结束时间减去开播时间
              } elseif ($thisEndDate - $thisStartDate == 1) {//差一天
              //计算第二天的时间
              $tomorrowDate = date("Ymd", $thisStart + 86400);
              !isset($newTimeResult[$tomorrowDate]) && $newTimeResult[$tomorrowDate] = 0;

              //判断当前这条记录的开始时间不是在搜索范围之内
              if ($thisStart < $startTime) {
              $newTimeResult[$tomorrowDate] += $thisEnd - strtotime(date("Ymd", $thisEnd)); //用结束时间减去0点
              }
              //判断当前这条记录的的结束时间不是在搜索范围之内
              else if ($thisEnd > $endTime) {
              $newTimeResult[$thisStartDate] += strtotime(date("Ymd", $thisEnd)) - $thisStart; //用0点减去开播时间
              }
              // 开始和结束时间都在搜索范围之间
              else {
              $newTimeResult[$tomorrowDate] += $thisEnd - strtotime(date("Ymd", $thisEnd));       //用结束时间减去0点
              $newTimeResult[$thisStartDate] += strtotime(date("Ymd", $thisEnd)) - $thisStart;    //用0点减去开播时间
              }
              }
              }
              }* */
            /*$sql = "select sum(endTime-publicTime) as sum ,from_unixTime(publicTime, '%Y%m%d') as time,min(publicTime) as publicTime,max(endTime) as endTime "
                    . " from \Micro\Models\RoomLog where roomId=" . $roomId . " and  publicTime>=" . $startTime . " and endTime<=" . $endTime
                    . " group by time";*/
                    //,min(rl.publicTime) as publicTime,max(rl.endTime) as endTime
            /* $sql = "select sum(tt.t1) as t2,tt.time from (select (rl.endTime - rl.publicTime) as t1,FROM_UNIXTIME(rl.publicTime, '%Y%m%d') as time from pre_room_log as rl "
                    . " where rl.roomId = {$roomId}  and  publicTime>=" . $startTime . " and endTime<=" . $endTime." and not exists "
                    . " (select sl.roomId from pre_show_room_log as sl where rl.publicTime > sl.startTime and (rl.publicTime < sl.endTime or isnull(sl.endTime)) and sl.roomId = rl.roomId)) as tt group by tt.time";
            */// select rl.*,FROM_UNIXTIME(rl.publicTime,'%Y-%m-%d %H-%i-%s'),FROM_UNIXTIME(rl.endTime,'%Y-%m-%d %H-%i-%s') from pre_room_log as rl 
// where rl.roomId=85 and not exists(select roomId from pre_show_room_log as sl where rl.publicTime > sl.startTime and rl.publicTime<sl.endTime and sl.roomId=rl.roomId);
            $sql = "select sum(tt.t1) as t2,tt.time from (select (rl.endTime - rl.publicTime) as t1,FROM_UNIXTIME(rl.publicTime, '%Y%m%d') as time from pre_room_log as rl "
                    . " where rl.roomId = {$roomId}  and  publicTime>=" . $startTime . " and endTime<=" . $endTime." and rl.status = 1) as tt group by tt.time";
            if ($order) {
                $sql.=" order by " . $order;
            }
            if ($page) {
                $limit = ($page - 1) * $pageSize;
                $sql.=" limit " . $limit . "," . $pageSize;
            }
            $connection = $this->di->get('db');
            $timeResult = $connection->fetchAll($sql);
            // echo $sql;
            // var_dump($timeResult);
            // exit;
            foreach ($timeResult as $kt => $vt) {
                $newTimeResult[$vt['time']]['sum'] = $vt['t2'];
                $newTimeResult[$vt['time']]['time'] = $vt['time'];
                $newTimeResult[$vt['time']]['publicTime'] = strtotime($vt['time']);
                $newTimeResult[$vt['time']]['endTime'] = strtotime($vt['time']) + 3600 * 24;
            }
            /*$query = $this->modelsManager->createQuery($sql);
            $timeResult = $query->execute();*/
            /*if ($timeResult->valid()) {
                foreach ($timeResult as $kt => $vt) {
                    $newTimeResult[$vt->time]['sum'] = $vt->sum;
                    $newTimeResult[$vt->time]['time'] = $vt->time;
                    $newTimeResult[$vt->time]['publicTime'] = $vt->publicTime;
                    $newTimeResult[$vt->time]['endTime'] = $vt->endTime;
                }
            }*/
            return $this->status->retFromFramework($this->status->getCode('OK'), $newTimeResult);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //判断是否有喇叭
    public function checkUserHorn($type=0) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $return['num'] = 0; //喇叭数量
        try {
            $result = \Micro\Models\UserItem::findfirst("uid=" . $uid . " and itemType=" . $this->config->itemType->item . " and itemId=" . $type . " and itemCount>0");
            if ($result != false) {
                if ($type == 1) {//银喇叭
                    $return['num'] = $result->itemCount;
                } elseif ($type == 2) {//金喇叭
                    $return['num'] = $result->itemCount;
                }
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }


    //获取跳转主播ID
    public function getJumpAnchorId($type = 0){
        try {
            $sql = 'select aj.type,aj.uid,r.roomId,r.title from \Micro\Models\AnchorJump as aj left join \Micro\Models\Rooms as r on aj.uid = r.uid'.
                   ' where r.liveStatus = 1 and r.showStatus=1';
            $query = $this->modelsManager->createQuery($sql);
            $jumpAnchorIds = $query->execute();
            if ($jumpAnchorIds->valid()) {//推荐池的主播又在直播中
                $jumpRatioArr = $this->config->jumpRatio;
                $sum = 0;
                $startNum = 1;
                $list = array();

                foreach ($jumpAnchorIds as $k => $val) {
                    $tmpRatio = $jumpRatioArr[$val['type']][1];
                    $sum += $tmpRatio;
                    $tmpArr = array_fill($startNum, $tmpRatio, $val['uid']);
                    $startNum += $tmpRatio;
                    /*$anchorInfo[$val['uid']] = array(
                        'uid' => $val['uid'],
                        'roomId' =>$val['roomId']
                    );*/
                    $list += $tmpArr;
                }
                $randNum = rand(1,$sum);
                $jumpAnchorId = $list[$randNum];
            }else{
                //$jumpAnchorId = 0;
                // 随机获取开播的主播
                $sql1 = 'select r.uid,r.roomId,r.title from \Micro\Models\Rooms as r where r.liveStatus = 1 and r.showStatus = 1';
                $query1 = $this->modelsManager->createQuery($sql1);
                $liveAnchorIds = $query1->execute();
                if($liveAnchorIds->valid()){
                    $idsArr = $liveAnchorIds->toArray();
                    $sum = count($idsArr);
                    $randNum = rand(0,$sum-1);
                    $jumpAnchorId = $idsArr[$randNum]['uid'];
                }else{
                    if($type){
                        return 0;
                    }
                    //按概率跳转推荐池里的主播
                    $sql = 'select aj.type,aj.uid,r.roomId,r.title from \Micro\Models\AnchorJump as aj left join \Micro\Models\Rooms as r on aj.uid = r.uid'.
                       ' where r.liveStatus != 1 and r.liveStatus != 2 and r.showStatus=1';
                    $query = $this->modelsManager->createQuery($sql);
                    $jumpAnchorIds = $query->execute();
                    if ($jumpAnchorIds->valid()) {
                        $jumpRatioArr = $this->config->jumpRatio;
                        $sum = 0;
                        $startNum = 1;
                        $list = array();

                        foreach ($jumpAnchorIds as $k => $val) {
                            $tmpRatio = $jumpRatioArr[$val['type']][1];
                            $sum += $tmpRatio;
                            $tmpArr = array_fill($startNum, $tmpRatio, $val['uid']);
                            $startNum += $tmpRatio;
                            /*$anchorInfo[$val['uid']] = array(
                                'uid' => $val['uid'],
                                'roomId' =>$val['roomId']
                            );*/
                            $list += $tmpArr;
                        }
                        $randNum = rand(1,$sum);
                        $jumpAnchorId = $list[$randNum];
                    }else{
                        $jumpAnchorId = 0;
                    }
                }
                    
            }
            return $jumpAnchorId;
        } catch (\Exception $e) {
            return 0;
        }
            
    }

    public function getMobileJumpAnchorId(){
        try {
            $jumpAnchorId = array();
            $roomData = array();
            $roomList = array();
            $sql = 'select aj.type,aj.uid,r.roomId,r.title from \Micro\Models\AnchorJump as aj left join \Micro\Models\Rooms as r on aj.uid = r.uid where r.liveStatus = 1 and r.showStatus = 1';
            $query = $this->modelsManager->createQuery($sql);
            $jumpAnchorIds = $query->execute();
            if ($jumpAnchorIds->valid()) {
                $list = array();
                foreach ($jumpAnchorIds as $k => $val) {
                    $list[] = $val['uid'];
                }

                if($list && is_array($list) && count($list) > 3){
                    $randTmp = array_rand($list, 3);
                }else{
                    $randTmp = array_keys($list);
                }

                if($randTmp){
                    foreach($randTmp as $val){
                        $jumpAnchorId[] = $list[$val];
                    }
                }

                if($jumpAnchorId){
                    foreach ($jumpAnchorId as $uid) {
                        $roomInfo = $this->getRoomInfo(NULL, $uid);
                        $user = UserFactory::getInstance($uid);
                        $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
                        $userProfile = $user->getUserInfoObject()->getUserProfiles();
//                        $userData = $user->getUserInfoObject()->getData();
                        $roomData['uid'] = $uid;
                        //accoundId;
                        $roomData['roomId'] = $roomInfo->roomId;
                        $roomData['liveStatus'] = $roomInfo->liveStatus;
                        $roomData['nickName'] = isset($userBaseInfo['nickName']) ? $userBaseInfo['nickName'] : '';
                        $roomData['avatar'] = isset($userBaseInfo['avatar']) ? $userBaseInfo['avatar'] : '';
                        $roomData['roomId'] = isset($roomInfo->roomId) ? $roomInfo->roomId : 0;
                        $roomData['gender'] = $userBaseInfo['gender'];
                        $roomData['anchorLevel'] = $userProfile['anchorLevel'];
                        $posterUrl = $roomInfo->poster;
                        $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($posterUrl, $roomData['avatar']);
                        $roomData['poster'] = $posterUrls['poster'];
                        $roomData['small_poster'] = $posterUrls['small-poster'];
                        /*$roomData['poster'] = $roomInfo->poster;
                        if (empty($roomData['poster'])) {
                            $roomData['poster'] = $userBaseInfo['avatar'];
                            if (empty($roomData['poster'])) {
                                $roomData['poster'] = $this->pathGenerator->getFullDefaultAvatarPath();
                            }
                        }*/

//                        $roomData['onlineNum'] = $roomInfo->onlineNum + $roomInfo->robotNum;
                        $roomData['onlineNum'] = $roomInfo->totalNum;
                        $roomData['fansLevel'] = $userProfile['fansLevel'];
                        $roomData['publicTime']= $roomInfo->publicTime;
                        array_push($roomList, $roomData);
                    }
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $roomList);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    //房间里获取某个用户信息
    public function getRoomUserData($uid, $roomId=0) {
        // 数据验证
        $postData['uid'] = $uid;
        $postData['roomId'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //机器人
        if(UserFactory::isRobot($uid)){
            $return = UserFactory::getRobotData($uid);
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        }

        $user = UserFactory::getInstance($uid);
        $roomInfo = \Micro\Models\Rooms::findfirst($roomId);
 
        $userData = $user->getUserInfoObject()->getData();
        $return['accountId'] = $userData['accountId'];
        $return['uid'] = $userData['uid'];
        $return['userId'] = $userData['uid'];
        $return['nickName'] = $userData['nickName'];
        $return['name'] = $userData['nickName'];
        $return['avatar'] = $userData['avatar'];
        $return['richerLevel'] = $userData['richerLevel'];
        $return['vipLevel'] = $userData['vipLevel'];
        $return['anchorLevel'] = $userData['anchorLevel'];
        $return['superManageType'] = $userData['manageType']; //超级管理员
        $return['points'] = $userData['points']; //积分
        //是否禁言
        $return['isForbid'] = 0;
        $return['isFamilyLeader'] = 0;
        if ($roomId) {
            $roomInfo = \Micro\Models\Rooms::findfirst($roomId);
            // 获取守护信息
            /*$guardData = $user->getUserItemsObject()->getGuardData($roomInfo->uid);
            if ($guardData != NULL) {
                $return['guardLevel'] = $guardData['level'];
            } else {
                $return['guardLevel'] = '';
            }*/
            /*$goldenGurad = $this->di->get('userMgr')->checkGuardByLevel($uid, $roomInfo->uid, 1);
            $silverGurad = $this->di->get('userMgr')->checkGuardByLevel($uid, $roomInfo->uid, 2);
            $boGurad = $this->di->get('userMgr')->checkGuardByLevel($uid, $roomInfo->uid, 3);
            $return['guardLevel'] = $boGurad == 1 ? 3 : ($goldenGurad == 1 ? 1 : ($silverGurad == 1 ? 2 : 0));*/
            $return['guardLevel'] = $user->getUserItemsObject()->getGuardLevel($uid, $roomInfo->uid);

            $hoster = UserFactory::getInstance($roomInfo->uid);
            $return['level'] = $this->getUserLevelInRoom($roomId, $hoster, $user);
            $return['isForbid'] = $this->checkUserIsForbidden($roomId, $user->getUid());
            //用户家族信息
            $return['isFamilyLeader'] = $this->di->get('userMgr')->checkUserIsHeader($roomInfo->uid, $uid);
            //查询用户属于哪个军团
            $groupres = $this->di->get('groupMgr')->checkUserGroup($uid);
            if ($groupres['code'] == $this->status->getCode('OK') && $groupres['data']) {
                $return['group'] = $groupres['data'];
            }

            //平台信息
            // $return['platform'] = $this->getPlatform();
            $roomData = $this->getUserInfoInRoom($roomId, $uid);
            if ($roomData != false) {
                $return['platform'] = $roomData['devicetype'];
            }else{
                $return['platform'] = 'pc';
            }
        }
        // $return['isForbid'] = $isForbid;
        // 获取徽章列表
        $badge = $user->getUserItemsObject()->getUserBadge();
        if ($badge['code'] == $this->status->getCode('OK')) {
            $return['badge'] = $badge['data'];
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }
    
    
    //查询用户房间状态信息
    public function getRoomUserStatusData($roomId) {
        // 数据验证
        $postData['roomId'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $roomStatusData = \Micro\Models\RoomUserStatus::findfirst("roomId=" . $roomId . " and uid=" . $uid);
            if ($roomStatusData == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $kick = 0; //是否被踢状态
            $forbid = $roomStatusData->forbid; //是否被禁言状态
            if ($roomStatusData->kick && $roomStatusData->kickTimeLine > time()) {
                $kick = 1;
            }
            $return['level'] = $roomStatusData->level;
            $return['forbit'] = $forbid;
            $return['kick'] = $kick;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 设置直播间密码
    public function setRoomPwd($roomPwd = ''){
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        
        $isValid = $this->validator->validate(array('roompwd'=>$roomPwd));
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $uid = $user->getUid();

            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $uid);
            if ($roomInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $roomId = $roomInfo->roomId;
            
            $roomPrivilege = \Micro\Models\RoomPrivilege::findfirst("uid=" . $uid . ' and roomId = ' . $roomId);
            if (empty($roomPrivilege)) {
                $roomPrivilege = new \Micro\Models\RoomPrivilege();
                $roomPrivilege->uid = $uid;
                $roomPrivilege->roomId = $roomId;
                $roomPrivilege->roleType = '';
                $roomPrivilege->minRank = 0;
            }
            $roomPrivilege->roomPwd = $roomPwd;
            $roomPrivilege->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 设置直播间角色
    public function setRoomRoles($roleType = 0, $roleMems = ''){
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $roomRoleTypes = $this->config->roomRoleTypes->types->toArray();
        if(!in_array($roleType, $roomRoleTypes)){
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }

        try {
            
            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $user->getUid());
            if ($roomInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $roomInfo->roleType = $roleType;
            if($roleType == 4){//类型为4的时候才更新角色成员字段[符号等级]
                $roomInfo->roleMems = $roleMems;
            }
            
            $roomInfo->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 设置直播间访问权限
    public function setRoomPrivileges($useRole = 0, $isAnchor = 0, $isFamily = 0, $isManage = 0, $minRicherRank = 0, $usePwd = 0, $roomPwd = ''){
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $uid = $user->getUid();

            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $uid);
            if ($roomInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $roomId = $roomInfo->roomId;

            $roomPrivilege = \Micro\Models\RoomPrivilege::findfirst("uid=" . $uid . ' and roomId = ' . $roomId);
            if (empty($roomPrivilege)) {
                $roomPrivilege = new \Micro\Models\RoomPrivilege();
                $roomPrivilege->uid = $uid;
                $roomPrivilege->roomId = $roomId;
                $roomPrivilege->useRole = 0;
                $roomPrivilege->isAnchor = 0;
                $roomPrivilege->isFamily = 0;
                $roomPrivilege->isManage = 0;
                $roomPrivilege->minRicherRank = 0;
                $roomPrivilege->usePwd = 0;
                $roomPrivilege->roomPwd = '';
            }

            if($useRole == 1){
                $roomPrivilege->isAnchor = $isAnchor ? 1 : 0;
                $roomPrivilege->isFamily = $isFamily ? 1 : 0;
                $roomPrivilege->isManage = $isManage ? 1 : 0;
                $roomPrivilege->minRicherRank = intval($minRicherRank);
            }
            $roomPrivilege->useRole = $useRole ? 1 : 0;

            if($usePwd == 1){
                $isValid = $this->validator->validate(array('roompwd'=>$roomPwd));
                if (!$isValid) {
                    $errorMsg = $this->validator->getLastError();
                    return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
                }
                $roomPrivilege->roomPwd = $roomPwd;
            }
            $roomPrivilege->usePwd = $usePwd ? 1 : 0;

            $roomPrivilege->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 进入房间前检查房间限制
    public function checkRoomLimit($uid = 0){
        try {
            $isValid = $this->validator->validate(array('uid'=>$uid));
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            ###判断房间是否存在
            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $uid);
            if ($roomInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
            $roomId = $roomInfo->roomId;

            ###判断是否主播或者超管
            $user = $this->userAuth->getUser();
            $userId = 0;
            if($user != NULL){
                //是否主播
                $userId = $user->getUid();
                if($userId == $uid){
                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }
                //是否超管
                $userInfo = \Micro\Models\Users::findFirst('uid = ' . $userId . ' and manageType = 1');
                if($userInfo != false){
                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }
                $isFamilyLeader = $this->di->get('userMgr')->checkUserIsHeader($uid, $userId);
                if($isFamilyLeader == 1){
                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }
            }

            ###判断房间是否有限制
            $roomPrivilege = \Micro\Models\RoomPrivilege::findfirst("uid=" . $uid . ' and roomId = ' . $roomId . ' and (useRole = 1 or usePwd = 1)');
            if($roomPrivilege == false){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }


            if($roomPrivilege->useRole == 0){// 无角色限制
                if($roomPrivilege->usePwd == 0){// 无密码限制
                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }else{// 有密码限制
                    return $this->status->retFromFramework($this->status->getCode('PLEASE_ENTER_PWD'));
                }
            }else{// 有密码限制
                ###检查用户是否符合角色要求
                $flag = $this->checkRoles($roomPrivilege, $userId, $roomId);
                if($flag == 1){
                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }
                if($roomPrivilege->usePwd == 0){
                    return $this->status->retFromFramework($this->status->getCode('PERMISSION_DENIED'));
                }else{
                    return $this->status->retFromFramework($this->status->getCode('PLEASE_ENTER_PWD'));
                }
            }

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 检查用户是否符合进房间要求
    public function checkRoles($roomPrivilege = NULL, $userId = 0, $roomId = 0){
        try {
            $userMgr = $this->di->get('userMgr');
            if($roomPrivilege == NULL){
                return 0;
            }
            if($roomPrivilege->isAnchor == 1){
                $isAnchor = $userMgr->checkIsAnchor($userId);
                if($isAnchor){
                    return 1;
                }
            }
            if($roomPrivilege->isFamily == 1){
                $isFamily = $userMgr->checkIsFamily($userId);
                if($isFamily){
                    return 1;
                }
            }
            if($roomPrivilege->isManage == 1){
                $isManage = $userMgr->checkIsManage($userId, $roomId);
                if($isManage){
                    return 1;
                }
            }
            if($roomPrivilege->minRicherRank){
                $userInfo = \Micro\Models\UserProfiles::findFirst('uid = ' . $userId . ' and level3 >= ' . $roomPrivilege->minRicherRank);
                if($userInfo != false){
                    return 1;
                }
            }
            return 0;
        } catch (\Exception $e) {
            $this->errLog('checkRoles error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    // 检查密码是否合法
    public function checkRoomPwd($uid = 0, $roomPwd = ''){
        try {
            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $uid);
            if ($roomInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $roomId = $roomInfo->roomId;

            $roomPrivilege = \Micro\Models\RoomPrivilege::findfirst("uid=" . $uid . ' and roomId = ' . $roomId);
            if($roomPrivilege == false){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            if($roomPrivilege->roomPwd === $roomPwd){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }

            return $this->status->retFromFramework($this->status->getCode('ROOM_PWD_ERROR'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取设备信息
    public function getPlatform(){
        try {
            $platform = 'pc';
            $deviceSession = $this->session->get($this->config->websiteinfo->mobileauthkey);
            if(!empty($deviceSession)){
                $platformType = intval($deviceSession['platform']);
                switch($platformType){
                    case 1:
                        $platform = 'pc';
                        break;
                    case 2:
                        $platform = 'ios';
                        break;
                    case 3:
                        $platform = 'android';
                    break;
                    default:
                        $platform = 'pc';
                        break;
                }
            }else{
                $platform = 'pc';
            }
            return $platform;
        } catch (Exception $e) {
            $this->errLog('getPlatform error errorMessage = ' . $e->getMessage());
            return 'pc';
        }
    }

    // 返回用户家族信息
    public function getFamilyData($uid = 0){
        try {
            $info = \Micro\Models\Family::findfirst("creatorUid = " . $uid . " and status = 1");
            if ($info != false) {
                return array('isFamily'=>1,'shortName'=>$info->shortName);
            }
            return array('isFamily'=>0,'shortName'=>'');
        } catch (\Exception $e) {
            $this->errLog('getFamilyData errorMessage = ' . $e->getMessage());
            return array('isFamily'=>0,'shortName'=>'');
        }
    }

    
    // 获取流名
    public function getStreamName($uid = 0){
        $isValid = $this->validator->validate(array('uid'=>$uid));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $liveStatus = 0;
            $isOpenVideo = 0;
            $streamName = '';
            $videoName = '';

            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $uid);
            if ($roomInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            $liveStatus = $roomInfo->liveStatus;
            $isOpenVideo = $roomInfo->isOpenVideo;
            $streamName = $roomInfo->streamName;
            
            if($liveStatus == 0 && $isOpenVideo == 1){
                $res = \Micro\Models\Videos::findFirst(
                    'status = 0 and uid = ' . $uid . ' and isUsing = 1'
                );
                if(!empty($res)){
                    $videoName = $res->streamName ? ($this->config->RECInfo->url . $res->streamName . $this->config->RECInfo->format) : '';
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'),
                array('liveStatus'=>$liveStatus,'isOpenVideo'=>$isOpenVideo,'streamName'=>$streamName,'videoName'=>$videoName)
            );
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //积分投注
    public function betPoints($type = 1, $times = 0, $nums = 0, $platform = 1){
        if (time() < $this->config->pointsGiftConfigs->activityTime->start || time() > $this->config->pointsGiftConfigs->activityTime->end) {//活动不再时间范围内
            return $this->status->retFromFramework($this->status->getCode('NOT_IN_ACTIVITY_PERIOD'));
        }
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $userData = $user->getUserInfoObject()->getUserAccountInfo();
        if($userData['internalType'] == 1){
            return $this->status->retFromFramework($this->status->getCode('TUO_HAS_NOT_AUTH'));
        }
        $isValid = $this->validator->validate(array('betTimes'=>$times, 'betNum'=>$nums));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $betConfigs = $this->config->pointsGiftConfigs->typeConfigs->toArray();

            //判断记录是否存在
            $betRes = \Micro\Models\BetPointsResultLog::findFirst('times = ' . $times . ' and type = ' . $type);
            if(empty($betRes)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            //判断是否已开奖
            if($betRes->status != 0){
                return $this->status->retFromFramework($this->status->getCode('BET_POINT_HAS_OPENED'));
            }

            //
            $userRes = \Micro\Models\UserProfiles::findFirst('uid = ' . $uid);
            if(empty($userRes)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }          

            //判断用户积分是否足够
            $needPoints = $nums * $betConfigs[$type]['perPoints'];
            if($needPoints > $userRes->points){
                return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_POINT'));
            }

            //判断投注数是否足够
            $betNumRes = \Micro\Models\BetPointsLog::sum(array('column'=>'nums','conditions'=>'times = ' . $times . ' and type = ' . $type));
            $betNums = $betNumRes ? $betNumRes : 0;
            if(($betConfigs[$type]['totalNum'] - $betNums) < $nums){
                return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_NOT_ENOUGH'));
            }

            //扣除积分【原生】
            $connection = $this->di->get('db');
            $pointsSql = 'update pre_user_profiles set points = points - ' . $needPoints . ' where uid = ' . $uid;
            $connection->execute($pointsSql);
            //添加投注日志
            $sqlNew1 = "insert into pre_bet_points_log (uid,times,type,nums,createTime,platform) values ($uid,$times,$type,$nums,".time().",$platform)";
            $connection->execute($sqlNew1);

            //判断是否投注完成
            $hasBetNums = $betNums + $nums;
            if($hasBetNums >= $betConfigs[$type]['totalNum']){
                //更新开奖信息
                $winUid = $this->openBetPoints($type, $times);
                $userInfo = \Micro\Models\UserInfo::findFirst('uid = ' . $winUid);
                $winNickName = '';
                if($userInfo){
                    $winNickName = $userInfo->nickName;
                }
                $upsql = 'update pre_bet_points_result_log set uid='.$winUid.',status=1,openTime='.time().',remark="'.$betConfigs[$type]['rewardId'].'" where status = 0 and times = ' . $times . ' and type = ' . $type;
                $connection->execute($upsql);

                //新增新的期数
                $newTimes = $times + 1;
                $sqlNew2 = "insert into pre_bet_points_result_log (uid,times,type,createTime,remark,status,openTime) values (0,$newTimes,$type,".time().",'',0,0)";
                $connection->execute($sqlNew2);

                //获取投注用户
                $sql = 'select uid from \Micro\Models\BetPointsLog where type = ' . $type . ' and times = ' . $times . ' group by uid';
                $query = $this->modelsManager->createQuery($sql);
                $res = $query->execute();
                $bettingUids = array();
                if($res->valid()){
                    foreach ($res as $k => $v) {
                        array_push($bettingUids, $v->uid);
                    }
                }
                //发广播
                $ArraySubData = array();
                $ArraySubData['controltype'] = "bettingResult";
                $data = array();
                $data['winUid'] = $winUid;
                $data['winNickName'] = $winNickName;
                $data['bettingUids'] = $bettingUids;
                $data['times'] = $times;
                $data['type'] = $type;
                $data['configName'] = $betConfigs[$type]['configName'];
                $data['rewardName'] = $betConfigs[$type]['rewardName'];
                $data['rewardMoney'] = $betConfigs[$type]['rewardMoney'];
                $data['rewardDesc'] = $betConfigs[$type]['rewardDesc'];
                $ArraySubData['data'] = $data;
                $this->allRoomBroadcast($ArraySubData);
                //发送消息
                $sendUser = UserFactory::getInstance($winUid);
                $sendUser->getUserInformationObject()->addUserInformation(
                    $this->config->informationType->system,
                    array('content' => $betConfigs[$type]['message1'] . $times . $betConfigs[$type]['message2'],
                        'link' => '',
                        'operType' => ''
                    )
                );
            }
            
            //发广播
            $ArraySubData = array();
            $ArraySubData['controltype'] = "bettingChange";
            $data = array();
            $data['hasBetNums'] = $hasBetNums;
            $data['times'] = $times;
            $data['type'] = $type;
            $data['hasWarningBet'] = $this->checkHasWarningBet();
            $data['totalNum'] = $betConfigs[$type]['totalNum'];
            $ArraySubData['data'] = $data;
            $this->allRoomBroadcast($ArraySubData);

            $myBetNum = $this->getMyBetting($type, $times, $uid);
            $userRes = \Micro\Models\UserProfiles::findFirst('uid = ' . $uid);
            $myPoints = $userRes->points;

            return $this->status->retFromFramework(
                $this->status->getCode('OK'),
                array('myBetNum' => $myBetNum, 'myPoints' => $myPoints)
            );
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //检查对否有90%的夺宝
    public function checkHasWarningBet(){
        try {
            $sql = 'select ifnull(sum(bp.nums), 0) / 1000 as rate from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\BetPointsLog as bp on bpr.type = bp.type and bpr.times = bp.times '
                . ' where bpr.status = 0 group by bpr.type order by rate desc limit 1 ';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $warn = 0;
            if($res->valid()){
                foreach ($res as $k => $v) {
                    if($v->rate >= 0.9){
                        $warn = 1;
                    }
                }
            }
            return $warn;
        } catch (\Exception $e) {
            $this->errLog('getBetPointLog errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //开奖
    public function openBetPoints($type, $times){
        try {
            $res = \Micro\Models\BetPointsLog::find('type = ' . $type . ' and times = ' . $times);
            if(empty($res)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $sum = 0;
            $startNum = 1;
            $list = array();
            foreach ($res as $k => $v) {
                $nums = $v->nums;
                $sum += $nums;
                $tmpArr = array_fill($startNum, $nums, $v->uid);
                $startNum += $nums;
                $list += $tmpArr;
            }

            $randNum = rand(1,$sum);
            $winUid = $list[$randNum];

            return $winUid;
        } catch (\Exception $e) {
            $this->errLog('openBetPoints errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //获取用户投注记录
    private function getBetPointLog($type = 0, $times = 0){
        /*$user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $isValid = $this->validator->validate(array('number'=>$type,'betTimes'=>$times));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }*/
        try {
            $sql = 'select ui.nickName,bp.uid,ifnull(sum(bp.nums), 0) as totalNum from \Micro\Models\BetPointsLog as bp '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bp.uid '
                . ' where bp.type = ' . $type . ' and bp.times = ' . $times . ' group by bp.uid order by totalNum desc ';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $data = array();
            if($res->valid()){
                $data = $res->toArray();
            }

            return $data;

            // return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            $this->errLog('getBetPointLog errorMessage = ' . $e->getMessage());
            return array();
            // return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //
    //获取用户投注记录New
    public function getBetLog($isApp = 0){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        try {
            $bettingRes = \Micro\Models\BetPointsResultLog::find('status = 0 order by type asc');
            $data = array();
            $hasType = array();
            if(!empty($bettingRes)){
                $betConfigs = $this->config->pointsGiftConfigs->typeConfigs->toArray();
                foreach ($bettingRes as $k => $v) {
                    if(in_array($v->type, $hasType)) continue;
                    $hasType[] = $v->type;
                    $tmp = array();
                    $tmp['type'] = $v->type;
                    $tmp['times'] = $v->times;
                    $tmp['rewardMoney'] = $betConfigs[$v->type]['rewardMoney'];
                    if($isApp == 0){
                        $tmp['rewardName'] = $betConfigs[$v->type]['rewardName'];
                    }
                    $tmp['list'] = $this->getBetPointLog($v->type, $v->times);
                    array_push($data, $tmp);
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取进行中的夺宝列表
    public function getBettingList($isApp = 0){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $sql = 'select ifnull(sum(bp.nums), 0) as hasBetNums,bpr.type,bpr.times from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\BetPointsLog as bp on bpr.type = bp.type and bpr.times = bp.times '
                . ' where bpr.status = 0 group by bpr.type order by bpr.type asc ';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                $betConfigs = $this->config->pointsGiftConfigs->typeConfigs->toArray();
                foreach ($res as $k => $v) {
                    $tmp['hasBetNums'] = $v->hasBetNums;
                    $tmp['type'] = $v->type;
                    $tmp['times'] = $v->times;
                    $tmp['totalNum'] = $betConfigs[$v->type]['totalNum'];
                    $tmp['perPoints'] = $betConfigs[$v->type]['perPoints'];
                    $tmp['rewardName'] = $betConfigs[$v->type]['rewardName'];
                    if($isApp == 0){
                        $tmp['rewardMoney'] = $betConfigs[$v->type]['rewardMoney'];
                    }
                    $tmp['configName'] = $betConfigs[$v->type]['configName'];
                    $tmp['myBetNum'] = $this->getMyBetting($v->type, $v->times, $uid);
                    array_push($data, $tmp);
                    unset($tmp);
                }
            }

            $userRes = \Micro\Models\UserProfiles::findFirst('uid = ' . $uid);
            $myPoints = $userRes->points;

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'myPoints'=>$myPoints));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取该用户投的注数
    private function getMyBetting($type = 0, $times = 0, $uid = 0){
        try {
            $betNumRes = \Micro\Models\BetPointsLog::sum(
                array('column'=>'nums','conditions'=>'times = ' . $times . ' and type = ' . $type . ' and uid = ' . $uid)
            );

            $betNums = $betNumRes ? $betNumRes : 0;
            return $betNums;
        } catch (\Exception $e) {
            $this->errLog('getMyBetting errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //开奖记录
    public function getBetPointsResList($type = 0, $page = 1, $pageSize = 10){
        $isValid = $this->validator->validate(array('number'=>$type));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            !$page && $page = 1;
            !$pageSize && $pageSize = 10;
            $limit = ($page - 1) * $pageSize;
            $sql = 'select bpr.uid,ui.nickName,bpr.times from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bpr.uid '
                . ' where bpr.status = 1 and bpr.type = ' . $type . ' order by bpr.times desc limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                $data = $res->toArray();
            }

            $count = \Micro\Models\BetPointsResultLog::count('status = 1 and type = ' . $type);

            return $this->status->retFromFramework($this->status->getCode('OK'), array('list'=>$data,'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //开奖记录NEW
    public function getBetResList(){
        try {
            $sql = 'select bpr.uid,ui.nickName,bpr.times,bpr.type,bpr.remark from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bpr.uid '
                . ' where bpr.status = 1 order by bpr.type desc,bpr.times asc';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                $betConfigs = $this->config->pointsGiftConfigs->typeConfigs->toArray();
                foreach ($res as $k => $v) {
                    $tmp['uid'] = $v->uid;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['times'] = $v->times;
                    $tmp['type'] = $v->type;
                    $tmp['rewardName'] = $betConfigs[$v->type]['rewardName'];
                    $tmp['rewardMoney'] = $betConfigs[$v->type]['rewardMoney'];
                    array_push($data, $tmp);
                    unset($tmp);
                }
            }

            $count = \Micro\Models\BetPointsResultLog::count('status = 1');

            return $this->status->retFromFramework($this->status->getCode('OK'), array('list'=>$data,'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取推广员当日消费聊币总额
    public function checkDayCounsume($giftPrice = 0){
        try {
            $dayMaxLimit = 0;
            $res = \Micro\Models\BaseConfigs::findFirst('key = "dayMaxLimit"');
            if($res){
                $dayMaxLimit = $res->value;
            }

            if($dayMaxLimit == ''){
                return 1;
            }

            $startTime = strtotime(date('Y-m-d'));
            $endTime = $startTime + 86399;
            $sql = 'select ifnull(sum(cd.amount),0) as ttl from \Micro\Models\ConsumeDetailLog as cd '
                . ' left join \Micro\Models\Users as u on u.uid = cd.uid '
                . ' where u.internalType = 1 and cd.createTime >= ' . $startTime . ' and cd.createTime < ' . $endTime;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $flag = 0;
            if($result->valid()){
                $left = $dayMaxLimit - $result->toArray()[0]['ttl'];
                if($left >= $giftPrice){
                    $flag = 1;
                }
            }
            return $flag;
        } catch (Exception $e) {
            $this->errLog('checkDayCounsume errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //获取推广员当日消费聊币总额
    public function checkDayTuoCounsume($giftPrice = 0){
        try {
            $dayMaxLimit = 0;
            $res = \Micro\Models\BaseConfigs::findFirst('key = "dayMaxLimitTuo"');
            if($res){
                $dayMaxLimit = $res->value;
            }

            if($dayMaxLimit == ''){
                return 1;
            }

            $startTime = strtotime(date('Y-m-d'));
            $endTime = $startTime + 86399;
            $sum = \Micro\Models\ConsumeDetailLog::sum(
                array(
                    'column' => 'amount',
                    'conditions' => 'isTuo = 1 and createTime >= ' . $startTime . ' and createTime < ' . $endTime
                )
            );
            $hasSend = $sum ? $sum : 0;
            $flag = 0;
            if(($hasSend + $giftPrice) <= $dayMaxLimit){
                $flag = 1;
            }
            return $flag;
        } catch (Exception $e) {
            $this->errLog('checkDayCounsume errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    /**
    * 更新机器人配置
    * createTime: 2015-01-04
    */
    public function updateRobotConfig($data){
        return $this->comm->setRobotConfig(json_encode($data));
    }

    /**
    * 重新随机机器人配置
    * createTime: 2015-01-15
    */
    public function resetRobot($data){
        return $this->comm->resetRobot($data);
    }
    
    
    //世界广播
    public function sendWorldBroadcast($type = 0, $roomId = 0, $anchorData = array(), $userData = array(),$extends=array()) {
        $broadData['hostUid'] = $anchorData['uid'];
        $broadData['roomId'] = $roomId;
        $broadData['hostName'] = $anchorData['nickName']; //主播昵称
        $broadData['type'] = $type;
        $broadData['userdata'] = array('nickName' => $userData['nickName']);
        $extends&&$broadData=array_merge($broadData,$extends);
        $ArraySubData['controltype'] = "worldbroadcast";
        $ArraySubData['data'] = $broadData;
        $this->allRoomBroadcast($ArraySubData);
        return;
    }

}
