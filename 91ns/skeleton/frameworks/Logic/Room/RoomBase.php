<?php

namespace Micro\Frameworks\Logic\Room;

use Phalcon\DI\FactoryDefault;

use Micro\Models\RoomUserStatus;
use Micro\Models\Rooms;
use Micro\Frameworks\Logic\User\UserFactory;

class RoomBase{
    protected $di;
    protected $config;
    protected $url;
    protected $status;
    protected $modelsManager;
    protected $validator;
    protected $mongo;
    protected $collection;
    protected $comm;
    protected $session;
    protected $userAuth;
    protected $configMgr;
    protected $storage;
    protected $pathGenerator;
    protected $baseCode;
    protected $rankMgr;
    protected $familyMgr;
    protected $taskMgr;
    protected $userMgr;
    protected $request;
    protected $lbs;
    protected $pushserver;
    protected $pushMgr;
    protected $activityMgr;
    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->url = $this->di->get('url');
        $this->status = $this->di->get('status');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->validator = $this->di->get('validator');
        $this->mongo = $this->di->get('mongo');
        $this->collection = $this->mongo->collection('rooms');
        $this->comm = $this->di->get('comm');
        $this->session = $this->di->get('session');
        $this->userAuth = $this->di->get('userAuth');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->configMgr = $this->di->get('configMgr');
        $this->storage = $this->di->get('storage');
        $this->baseCode = $this->di->get('baseCode');
        $this->rankMgr = $this->di->get('rankMgr');
        $this->familyMgr=$this->di->get('familyMgr');
        $this->taskMgr=$this->di->get('taskMgr');
        $this->userMgr=$this->di->get('userMgr');
        $this->request=$this->di->get('request');
        $this->lbs = $this->di->get('lbs');
        $this->pushserver = $this->di->get('pushserver');
        $this->pushMgr = $this->di->get('pushMgr');
        $this->activityMgr = $this->di->get('activityMgr');
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【Room】 error : '.$errInfo);
    }

    public function isOwnRoom($uid) {
        $user = $this->userAuth->getUser();
        if ($user == NULL)
            return false;

        if ($uid == $user->getUid())
            return true;

        return false;
    }

    public function getHosterInfo($roomId, $hoster, $user=null) {
        $hosterInfo = $hoster->getUserInfoObject()->getData();

        $resultArray = array();

        // 获取主播基本信息
        $resultArray['userId'] = $hoster->getUid();
        $resultArray['accountId'] = $hosterInfo['accountId'];
        $resultArray['name'] = $hosterInfo['nickName'];
        $resultArray['coin'] = $hosterInfo['coin'];
        $resultArray['cash'] = $hosterInfo['cash'];
        $resultArray['avatar'] = $hosterInfo['avatar'];

        //$resultArray['gender'] = $hosterInfo['gender'];
        //获取主播签约信息
        $signInfo = \Micro\Models\SignAnchor::findfirst("uid=" . $hoster->getUid());
        if ($signInfo != false) {
            $resultArray['gender'] = $signInfo->gender;
            $resultArray['location'] = $this->config->location[$signInfo->location]['name'] ? $this->config->location[$signInfo->location]['name'] : $this->config->location[$this->config->signAnchorCityDefault]['name'];
        }else{
            $resultArray['gender'] = 0;
            $resultArray['location'] = $this->config->location[$this->config->signAnchorCityDefault]['name'];
        }



        // 取出用户等级信息
        $resultArray['vipExp'] = $hosterInfo['vipExp'];
        $resultArray['anchorExp'] = $hosterInfo['anchorExp'];
        $resultArray['richerExp'] = $hosterInfo['richerExp'];
        $resultArray['charmExp'] = $hosterInfo['charmExp'];
        $resultArray['vipLevel'] = $hosterInfo['vipLevel'];
        if($hosterInfo['vipExpireTime']<=time())
        {
            $resultArray['vipLevel']=0;
        }
        $resultArray['anchorLevel'] = $hosterInfo['anchorLevel'];
        $resultArray['richerLevel'] = $hosterInfo['richerLevel'];
        $resultArray['charmLevel'] = $hosterInfo['charmLevel'];

        // 得到相关等级上下限配置值
        $conditions = "level = :level:";
        $parameters = array("level" => $resultArray['anchorLevel']);
        $result = $this->configMgr->getAnchorConfigInfoEx($conditions, $parameters);
        if ($result['code'] == $this->status->getCode('OK')) {
            $resultArray['anchorLevelHigher'] = $result['data']['higher']+1;
            $resultArray['anchorLevelLower'] = $result['data']['lower'];
        }

        $conditions = "level = :level:";
        $parameters = array("level" => $resultArray['richerLevel']);
        $result = $this->configMgr->getRicherConfigInfoEx($conditions, $parameters);
        if ($result['code'] == $this->status->getCode('OK')) {
            $resultArray['richerLevelHigher'] = $result['data']['higher']+1;
            $resultArray['richerLevelLower'] = $result['data']['lower'];
        }

        // 获取主播和当前观看者之间的关系
        if ($user != null) {

        }

        // 获得粉丝数，判定当前等级
        $resultArray['fansExp'] = $hosterInfo['fansExp'];
        $resultArray['fansLevel'] = $hosterInfo['fansLevel'];
        //查询下一个等级经验
        $nextFansConfig = \Micro\Models\FansConfigs::findfirst("level>" . $hosterInfo['fansLevel'] . " order by level asc");
        $resultArray['fansNextExp'] = $nextFansConfig != false ? $nextFansConfig->lower : 0;
        $resultArray['fansNextNeedExp'] = $resultArray['fansNextExp'] ? $resultArray['fansNextExp'] - $resultArray['fansExp'] : 0; //距离下一级还需要多少经验

        $resultArray['manageType'] = $hosterInfo['manageType'];   //管理员类型

        // 获得观看人数

        // 获得家族信息
        $familyData=$this->familyMgr->getFamilyInfoByUid($hoster->getUid());
        if ($familyData['code'] == $this->status->getCode('OK')) {
            $resultArray['familyInfo']=$familyData['data'];
        }

        return $resultArray;
    }

    public function getVisitorInfo($roomId, $hoster, $user) {
        $userInfo = $user->getUserInfoObject()->getData();

        $resultArray = array();
        // 取出用户信息
        $resultArray['userId'] = $user->getUid();
        $resultArray['name'] = $userInfo['nickName'];
        $resultArray['coin'] = $userInfo['coin'];
        $resultArray['cash'] = $userInfo['cash'];
        $resultArray['avatar'] = $userInfo['avatar'];
        $resultArray['gender'] = $userInfo['gender'];
        $resultArray['points'] = $userInfo['points'];

        // 取出用户等级信息
        $resultArray['vipExp'] = $userInfo['vipExp'];
        $resultArray['anchorExp'] = $userInfo['anchorExp'];
        $resultArray['richerExp'] = $userInfo['richerExp'];
        $resultArray['vipLevel'] = $userInfo['vipLevel'];
        $resultArray['anchorLevel'] = $userInfo['anchorLevel'];
        $resultArray['richerLevel'] = $userInfo['richerLevel'];
        // $guardData = $user->getUserItemsObject()->getGuardData($hoster->getUid());
        // $resultArray['guardLevel'] = !empty($guardData) ? $guardData['level'] : 0;
        $resultArray['guardLevel'] = $user->getUserItemsObject()->getGuardLevel($user->getUid(), $hoster->getUid());
        $resultArray['isChatRecord'] = isset($userInfo['isChatRecord']) ? $userInfo['isChatRecord'] : '';   //当前用户是否上传聊天记录
        $resultArray['manageType'] = $userInfo['manageType'];   //管理员类型
        $resultArray['forbid']=$this->checkUserIsForbidden($roomId,$user->getUid());
        //添加accountId
        //$resultArray['accountId']=$user->getUserInfoObject()->getAccountId();
        $resultArray['accountId']=$userInfo['accountId'];

        //签到徽章等级
        $signLevel =$user->getUserItemsObject()->getUserSignLevel();
        $resultArray['signLevel'] = $signLevel;

        //消息
        $result = $this->userMgr->isHasUnRead();
        $userInfo['news'] = 0;
        if($result['code'] == $this->status->getCode('OK')){
            $userInfo['news'] = $result['data'];
        }
        $resultArray['news'] = $userInfo['news'];
        // 这两个字段是做什么用的，为什么需要下一级的level值，或者我给一个最高的level值出来
        //$resultArray['nextRicherLevel'];
        //$resultArray['nextAncherLevel'];

        // 获取是否是管理员等级
        // $level = $this->getUserLevelInRoom($roomId, $hoster, $user);
        // $resultArray['isAdmin'] = $level >= 4 ? 1 : 0;

        // 获得发言时间间隔
        // $resultArray['chatTime'] = $richerLimitLevel = intval($this->configMgr->getBaseConfigValue('chatTime'));

        // 新手任务： 检测这个人是否有对主播发言过
        // 暂时未实现
        // $sql = "select taskStatus from " . DB::table('yshow_novice_task_log') . " where taskType=1 and taskId=1003 and uid={$uid}";
        // $res = DB::fetch_first($sql);
        // $retArr['taskStatusForTalk'] = $res['taskStatus'];

        return $resultArray;
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 用户在房间中的状态信息接口集合
    //
    ////////////////////////////////////////////////////////////////////////

    // 获取房间用户等级接口
    public function getUserLevelInRoom($roomId, $hoster, $user) {
        $uid = $user->getUid();

        //判断是不是超级管理员
        $usersInfo = \Micro\Models\Users::findfirst('uid = ' . $uid . ' and manageType = 1');
        if(!empty($usersInfo)){
            return 2;
        }

        $roomStatus = $this->getRoomUserStatus($roomId, $uid);

        $level = 1;
        if($roomStatus){
            $level = $roomStatus->level;

            if($level == 2 && $roomStatus->levelTimeLine > 0 && $roomStatus->levelTimeLine < time()){//临时管理员已过期
                $level = 1;
            }

            if($level == 2 && $roomStatus->levelTimeLine > time()){//临时管理员
                $level = 3;
            }
        }
        else {
            $level = $this->addUserLevelInRoom($roomId, $hoster, $user);
        }

        return $level;
    }

    public function countUserLevelInRoom($roomId, $hoster, $user) {
        $level = 0;
        if ($hoster->getUid() == $user->getUid()) {
            $level = 2;
        }
        else {
            $level = 1;
        }
        return $level;

        // 获得配置的富豪等级
        /*$richerLimitLevel = intval($this->configMgr->getBaseConfigValue('richerLimitLevel'));

        // 获得这个用户的富豪等级
        $userRicherLevel = $user->getUserInfoObject()->getRicherLevel();

        // 根据roomId获取到Room的uid，考虑将room的uid加到room_status表中,这样可能不合理，待考虑**，或者使用join的方式

        // 判断是否守护，暂时先不做判断
        //$guardData = $user->getUserItemsObject()->getGuardData($hoster->getUid());

        $guard = 0;
        // $sql = "select * from " . DB::table('yshow_guardlist') . " where guid={$uid} and huid={$roomInfo['uid']} and expireTime>" . time();
        // $ret = DB::fetch_first($sql);
        // if($ret){
        //      $guard = $ret['type'];
        // }else{
        //      $guard = 0;
        // }

        // 判断是否有座驾
        // $nowTime = date('Y-m-d H:i:s');
        // $sql = 'select xi.* from ychat_xiu_interaction as xi,' . DB::table('ychat_xiu_item') . " as item where item.xi_item_expire>='{$nowTime}' and item.xi_status=1 and item.uid=" . intval($_G['uid']) . " and item.xi_item_tag=xi.xi_id order by xi.xi_price desc limit 1";
        // $carInfo = DB::fetch_first($sql);
        $carInfo = $user->getUserItemsObject()->getActiveCarData();

        // 计算房间中的level, 业务逻辑还需要完整化
        if ($hoster->getUid() == $user->getUid()) {
            $level = 2;
        }
        else if ($userRicherLevel >= $richerLimitLevel) {
            $level = 3;
        }
        else if (!empty($carInfo) || $guard > 0) { // || $_G['member']['vip'] > 0){
            $level = 2;
        }
        else {
            $level = 1;
        }
        // if($roomInfo['uid'] == $uid){
        // // 主播自己
        //     $level = 6;
        // }elseif($vgroupid >= $levelLimit){
        //     $level = 3;
        // }elseif(!empty($carInfo) || $guard > 0 || $_G['member']['vip'] > 0){
        //     $level = 2;
        // }else if($uid){
        //     $level = 1;
        // }

        return $level;*/
    }

    public function addUserLevelInRoom($roomId, $hoster, $user) {
        $level = $this->countUserLevelInRoom($roomId, $hoster, $user);

        $data = array(
            'roomId' => $roomId,
            'uid' => $user->getUid(),
            'level' => $level,
            'forbid' => 0,
            'kick' => 0,
            'kickTimeLine' => 0,
            'levelTimeLine' => 0,
            'hisRemarks' => '',
            'remarks' => '',
        );

        $this->addRoomUserStaus($data);

        return $level;
    }

    public function addRoomUserStaus($userArr)
    {
        try {
            $user = new RoomUserStatus();
            $user->roomId = $userArr['roomId'];
            $user->uid = $userArr['uid'];
            $user->level = $userArr['level'];
            $user->forbid = $userArr['forbid'];
            $user->kick = $userArr['kick'];
            $user->createTime = time();
            $user->kickTimeLine = $userArr['kickTimeLine'];
            $user->levelTimeLine = $userArr['levelTimeLine'];
            $user->hisRemarks = $userArr['hisRemarks'];
            $user->remarks = $userArr['remarks'];
            $user->save();
            return true;
        } catch (\Exception $e) {
            $this->errLog('addRoomUserStaus errorMessage = '.$e->getMessage());
            return false;
        }
    }

    // 等级变化的时候，更新用户在房间的等级信息(*关键？)
    // (1) 成为主播
    // (2) vip升级
    // (3) 购买守护
    // (4) 购买座驾
    // public function updateUserLevelInRoom($user/*, $level*/) {
    //     $uid = $user->getUid();

    //     // 存在则修改，不存在则不需要添加
    //     $phql = "UPDATE \Micro\Models\RoomUserStatus SET level = ".$level." WHERE uid = '".$uid."'";
    //     try {
    //         $this->modelsManager->executeQuery($phql, $valueArray);
    //         return $this->status->retFromFramework($this->status->getCode('OK'));
    //     }
    //     catch (Exception $e) {
    //         return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
    //     }
    // }

    // 更新用户在房间内的等级信息
    public function updateUserLevelInRoom($roomId, $uid, $level, $isTemporary) {
        $roomStatus = $this->getRoomUserStatus($roomId, $uid);

        if ($roomStatus) {
            $roomStatus->level = $level;
            if ($level == 3) {//设置临时管理员$isTemporary == 1 && 
                $roomStatus->levelTimeLine = time() + 86400;
                $roomStatus->level = 2;
                // $roomStatus->levelTimeLine = strtotime(date('Y-m-d 23:59:59'));// 当日有效
            } else {
                $roomStatus->levelTimeLine = 0;
            }
            $roomStatus->save();
        }
    }

    // 获取用户是否还处于被T状态
    public function checkUserIsKicked($roomId, $uid) {
        $roomStatus = $this->getRoomUserStatus($roomId, $uid);

        if($roomStatus){
            if ($roomStatus->kick > 0 && $roomStatus->kickTimeLine > time()) {  //还差一个VIP防T的判断**
                $diffTime = ceil(($roomStatus->kickTimeLine - time()) / 60);
                return $diffTime;
            }
        }

        return 0;
    }

    // 设置用户被T状态
    protected function updateUserKicked($roomId, $uid, $isKicked, $expireTime) {
        $roomStatus = $this->getRoomUserStatus($roomId, $uid);

        if($roomStatus){
            $roomStatus->kick = $isKicked;
            $roomStatus->kickTimeLine = $expireTime;
            $roomStatus->save();
        }
    }

    // 获取用户是否还处于被禁言状态
    public function checkUserIsForbidden($roomId, $uid) {
        $roomStatus = $this->getRoomUserStatus($roomId, $uid);

        if($roomStatus){
            return $roomStatus->forbid;
        }

        return 0;
    }


    // 设置用户当前是否禁言状态
    protected function updateUserForbidden($roomId, $uid, $isForbid) {
        $roomStatus = $this->getRoomUserStatus($roomId, $uid);
        if($roomStatus){
            $roomStatus->forbid = $isForbid;
            $roomStatus->save();
        }
    }

    // 获取用户在房间中的状态信息
    private function getRoomUserStatus($roomId, $uid) {
        //Bind parameters
        $parameters = array(
            "roomId" => $roomId,
            "uid" => $uid
        );
        $roomStatus = RoomUserStatus::findFirst(array(
            "roomId = :roomId: AND uid = :uid:",
            "bind" => $parameters
        ));

        return $roomStatus;
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 获取用户进入房间时候需要传入的UserData
    //
    ////////////////////////////////////////////////////////////////////////

    // 外层已经做了处理
    /*protected function getRoomUserData($roomId, $hoster, $user) {
        $resultArray = array();

        // accountId
        $userInfo = $user->getUserInfoObject()->getData();

        // 昵称、头像
        $resultArray['userId'] = $user->getUid();
        $resultArray['name'] = $userInfo['nickName'];
        $resultArray['avatar'] = $userInfo['avatar'];

        // 进入房间的用户的主播富豪等级、VIP等级
        $resultArray['vipLevel'] = $userInfo['vipLevel'];
        $resultArray['anchorLevel'] = $userInfo['anchorLevel'];
        $resultArray['richerLevel'] = $userInfo['richerLevel'];

        // 获取是否禁言状态
        $resultArray['isForbid'] = $this->checkUserIsForbidden($roomId, $user->getUid());

        // 获取守护信息
        $guardData = $user->getUserItemsObject()->getGuardData($hoster->getUid());
        if ($guardData != NULL) {
            $resultArray['guardLevel'] = $guardData['level'];
        }
        else {
            $resultArray['guardLevel'] = '';
        }

        // 座驾信息
        $carInfo = $user->getUserItemsObject()->getActiveCarData();
        if ($carInfo) {
            $resultArray['carInfo'] = $carInfo;
        }

        // 房间前3名消费记录处理

        return $resultArray;
        //return json_encode($resultArray);
    }*/

    ////////////////////////////////////////////////////////////////////////
    //
    // mongodb相关的房间操作相关接口集合
    //
    ////////////////////////////////////////////////////////////////////////

    public function enterRoomBase($nodejsToken, $roomId, $uid, $extUid, $name, $level, 
        $devicetype, $deviceid, $userDataToNodeJS, $broadcastMask, $broadcastThrowMask,
        $callbackMask, $userType, $forbid = 0,  $driverFlag = 0, $userDataInRoom = '') {

        try {
            $roomData = $this->getUserInfoInRoom($roomId, $uid);
            if ($roomData != false) {
                $devicetypeInDB = $roomData['devicetype'];
                $beKickToken = $roomData['token'];

                //不管是手机还是PC，只要是同一个号，都T出
                //if ($devicetypeInDB == $devicetype) {
                    //调用NodeJS接口，同一个房间挤人
                    //NodeJS提供一个接口，在Room的Remote中，然后调用该接口，接口实现参照deviceEnterHandler，重新实现deviceleaveHandler
                    $chatServerResult = $this->comm->roomRepeatKick($nodejsToken, $beKickToken, $roomId, $devicetype, $deviceid);
                    if ($chatServerResult === false) {
                        return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
                    }
                    //对Code做判断，某些情况下报错，此时需要在php端做删除对应记录的操作
                    //注：是否是改成如果!=0，均要进行删除操作？和nodejs内部的业务逻辑有关系*
                    $errorCode = $chatServerResult['code'];
                    if ($errorCode == 1001 || //房间未创建    Invalid_RoomId
                        $errorCode == 2007) { //找不到该用户  Can_Not_Find_User
                        $this->delUserInRoom($roomId, $uid, $devicetype);
                    }
                //}
            }
            $result = $this->addUserInRoom($roomId, $uid, $extUid, $devicetype, $nodejsToken, $driverFlag, $userDataInRoom);
            if ($result != $this->status->getCode('OK')) {
                return $this->status->retFromFramework($result);
            }

            $chatServerResult = $this->comm->enterNodeJSRoom($nodejsToken, (int)$roomId, $name, (int)$level, $devicetype, $deviceid, 
                $broadcastMask, $broadcastThrowMask, $callbackMask, $userType, $forbid, $userDataToNodeJS);
            if ($chatServerResult === false) {
                $this->delUserInRoom($roomId, $uid, $devicetype);
                return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
            }
            $chatServerResultArray = $chatServerResult;
            $errorCode = $chatServerResultArray['code'];
            if ($errorCode != 0)    //非0表示失败
            {
                $this->delUserInRoom($roomId, $uid, $devicetype);
                return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($chatServerResult));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch(\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 添加用户到房间的记录
     * @param roomId 房间id
     * @param uid 进房间的用户uid
     * @return 返回操作结果$RoomErrorCode[]
     */
    private function addUserInRoom($roomId, $uid, $extUid, $devicetype, $nodejstoken, $driverflag, $userdatainroom) {
        /*$roomData = new \Micro\Models\MongoRooms();
        $roomData->roomid = $roomId;
        $roomData->uid = $uid;
        $roomData->devicetype = $devicetype;
        $roomData->token = $nodejstoken;
        $roomData->driverflag = $driverflag;
        $roomData->userdatainroom = $userdatainroom;
        if ($roomData->save() == true) {
            return $this->status->getCode('OK');
        }
        return $this->status->getCode('DB_OPER_ERROR');*/
        $document["roomid"]=$roomId."";
        $document["uid"]=$uid;
        $document['extUid'] = $extUid;
        $document["devicetype"]=$devicetype;
        $document["token"]=$nodejstoken;
        $document["driverflag"]=$driverflag;
        $document["userdata"]=$userdatainroom;

        $result = $this->collection->insert($document);

//        $result = mongoInstance()->insert(TAB_ROOM_COLLECTION, $document);

        if ($result) {
            return $this->status->getCode('OK');
        }
        return $this->status->getCode('DB_OPER_ERROR');
    }

    /**
     * 获取用户在房间中的记录信息
     * @param roomId 房间id
     * @param uid 用户uid
     * @return array 查询的结果
     */
    public function getUserInfoInRoom($roomId, $uid)
    {
        //return Micro\Models\MongoRooms::findFirst(array('roomid'=>$roomId, 'uid'=>$uid));
        $user = $this->collection->findOne(function($query) use($roomId, $uid) {
            $query->where("roomid", $roomId."")->andWhere("uid", $uid);
        });
        return $user;
    }

    /**
     * 删除用户在房间的记录
     * @param roomId 房间id
     * @param uid 用户uid
     * @return 无返回操作结果
     */
    private function delUserInRoom($roomId, $uid, $devicetype) {
        $this->collection->remove(function($query) use($roomId, $uid, $devicetype) {
            $query->where("roomid", $roomId."")->andWhere("uid", $uid)->andWhere("devicetype", $devicetype);
        });


        /*$roomData = Micro\Models\MongoRooms::findFirst(array('roomid'=>$roomId, 'uid'=>$uid, 'devicetype'=>$devicetype));
        if ($roomData != false) {
            if ($roomData->delete() != false) {
                //return $this->status->getRoomErrorCode('OK');
                return $this->status->getCode('OK');
            }
        }
        //return $this->status->getRoomErrorCode('DB_OPER_ERROR');
        return $this->status->getCode('DB_OPER_ERROR');*/
    }

    /**
     * 获取房间中的人数
     * @param roomId 房间Id
     * @return
     */
    protected function getCountInRoomBase($roomId) {
        try {
            $data['totalCount'] = $this->collection->count(array('roomid'=>$roomId.""));
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }
        catch(\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取房间中的机器人数量
     * @param roomId 房间Id
     * @return
     */
    public function getRoomRobotCount($roomId) {
        $roomData = Rooms::findFirst("roomId = " . $roomId);
        if (empty($roomData)) {
            return 0;
        }

        return $roomData->robotNum;
    }

    /**
     * 根据座驾信息获取房间的用户信息列表
     * @param roomId 房间Id
     * @param driverflag 0/1 是否带座驾
     * @return json字符串，用户信息列表
     */
    protected function getUserListInRoomByDriverFlag($roomId, $driverflag) {
        $arrayResult = $this->collection->find(array("roomid"=>$roomId, "driverflag"=>$driverflag));
        return $arrayResult;
    }

    /**
     * 获取房间的用户信息列表
     * @param roomId 房间Id
     * @return json字符串，用户信息列表
     */
    private function getUserListInRoom($roomId) {

        $arrayResult = $this->collection->find(array("roomid"=>$roomId));
        return $arrayResult;
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 房间信息表操作相关接口集合
    //
    ////////////////////////////////////////////////////////////////////////

    public function getRoomInfo($roomId=null, $hosterId=null) {
        if (($roomId == null) && ($hosterId == null)) {
            return null;
        }

        $parameters = array();
        $conditions = '';

        if ($roomId != null) {
            $parameters["roomId"] = $roomId;
            $conditions = "roomId = :roomId:";
        }
        if ($hosterId != null) {
            $parameters["uid"] = $hosterId;
            if (strlen($conditions) > 0) {
                $conditions = $conditions." AND uid = :uid:";
            }
            else {
                $conditions = "uid = :uid:";
            }
        }

        $roomInfo = Rooms::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));

        return $roomInfo;
    }

    public function updateRoomTitle($hosterId, $title, $announcement, $publishRoute = 0, $useAccelarate = 1, $nextTime = 0) {
        try {
            //$phql = "UPDATE \Micro\Models\Rooms SET title = ".$title." WHERE uid = '".$hosterId."'";
            // 存在则修改，不存在则不需要添加
//            $phql = "UPDATE \Micro\Models\Rooms SET title = ?0 WHERE uid = ?1";
//            $valueArray = array(0 => $title, 1 => $hosterId);
//            $result=$this->modelsManager->executeQuery($phql, $valueArray);
            $roomData=Rooms::findFirst("uid = ".$hosterId);
            if(empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
            $roomData->title=$title;
            $roomData->announcement=$announcement;
            $roomData->publishRoute=$publishRoute;
            $roomData->useAccelarate=$useAccelarate;
            $roomData->nextTime=$nextTime;
            $roomData->save();

            //广播
            $ArraySubData['controltype']="title";
            $data['roomTitle']=$title;
            $data['announcement']=$announcement;
            $data['publishRoute']=$publishRoute;
            $data['useAccelarate']=$useAccelarate;
            $data['nextTime']=$nextTime;
            $ArraySubData['data']=$data;
            $this->comm->roomBroadcast($roomData->roomId, $ArraySubData);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }



    ////////////////////////////////////////////////////////////////////////
    //
    // 房间守护信息操作接口
    //
    ////////////////////////////////////////////////////////////////////////

    protected function getGuardDataListBase($roomId, $beGuardedUid) {  // 未测试
        $resultData = array();

        // 前面需要得到房间里面的用户列表, mongodb中得到
        $result = $this->getUserListInRoom($roomId);
        $onlineUids = array();
        if ($result) {
            foreach($result as $key => $val) {
                array_push($onlineUids, $val['extUid']);
            }
        }
        $dataOnline = array();
        $dataOffline = array();
        $time = time();

        //先取铂金守护
        $phql = "SELECT a.*, b.*,c.* FROM \Micro\Models\GuardList a, \Micro\Models\UserInfo b,\Micro\Models\UserProfiles c WHERE a.guardUid = b.uid AND b.uid = c.uid AND a.expireTime > ?0 AND a.beGuardedUid = ?1 AND a.guardLevel = 3";
//        $phql = "SELECT a.guardLevel, b.uid,b.nickName,c.level3,a.expireTime"
//                . " FROM \Micro\Models\GuardList a"
//                . "LEFT JOIN \Micro\Models\UserInfo b ON a.guardUid = b.uid"
//                . "LEFT JOIN \Micro\Models\UserProfiles c ON b.uid = c.uid"
//                . "WHERE a.expireTime > ?0 AND a.beGuardedUid = ?1 AND a.guardLevel = 1";
        $valueArray = array(0 => $time, 1 => $beGuardedUid);
        $boList = $this->modelsManager->executeQuery($phql, $valueArray);
        //铂金的id
        $hasBo=0;
        $boArr="(";
        foreach($boList as $val){
            $hasBo=1;
            $boArr.=($val->b->uid.",");
        }
        $boArr=substr($boArr,0,strlen($boArr)-1);
        $boArr.=")";

        //先取黄金守护
        $phql = "SELECT a.*, b.*,c.* FROM \Micro\Models\GuardList a, \Micro\Models\UserInfo b,\Micro\Models\UserProfiles c WHERE a.guardUid = b.uid AND b.uid = c.uid AND a.expireTime > ?0 AND a.beGuardedUid = ?1 AND a.guardLevel = 1";
//        $phql = "SELECT a.guardLevel, b.uid,b.nickName,c.level3,a.expireTime"
//                . " FROM \Micro\Models\GuardList a"
//                . "LEFT JOIN \Micro\Models\UserInfo b ON a.guardUid = b.uid"
//                . "LEFT JOIN \Micro\Models\UserProfiles c ON b.uid = c.uid"
//                . "WHERE a.expireTime > ?0 AND a.beGuardedUid = ?1 AND a.guardLevel = 1";
        if($hasBo)
        {
            $phql.=" AND b.uid NOT IN ".$boArr;
        }
        $valueArray = array(0 => $time, 1 => $beGuardedUid);
        $goldList = $this->modelsManager->executeQuery($phql, $valueArray);
        //黄金的id
        $hasGold=0;
        $goldArr="(";
        foreach($goldList as $val){
            $hasGold=1;
            $goldArr.=($val->b->uid.",");
        }
        $goldArr=substr($goldArr,0,strlen($goldArr)-1);
        $goldArr.=")";

        //再取白银守护
        $phql = "SELECT a.*, b.*,c.* FROM \Micro\Models\GuardList a, \Micro\Models\UserInfo b,\Micro\Models\UserProfiles c WHERE a.guardUid = b.uid AND b.uid = c.uid AND a.expireTime > ?0 AND a.beGuardedUid = ?1 AND a.guardLevel = 2 ";
//        $phql = "SELECT a.guardLevel, b.uid,b.nickName,c.level3,a.expireTime"
//                . " FROM \Micro\Models\GuardList a"
//                . "LEFT JOIN \Micro\Models\UserInfo b ON a.guardUid = b.uid"
//                . "LEFT JOIN \Micro\Models\UserProfiles c ON b.uid = c.uid "
//                . "WHERE a.expireTime > ?0 AND a.beGuardedUid = ?1 AND a.guardLevel = 2 ";
        if($hasBo)
        {
            $phql.=" AND b.uid NOT IN ".$boArr;
        }

        if($hasGold)
        {
            $phql.=" AND b.uid NOT IN ".$goldArr;
        }

        $valueArray = array(0 => $time, 1 => $beGuardedUid);
        $silverList = $this->modelsManager->executeQuery($phql, $valueArray);
        //所有符合的条件
        if ($boList) {
            foreach($boList as $val){
                $guardData['guardLevel'] = $val->a->guardLevel;
                $userdata['guardLevel'] = $val->a->guardLevel;
                $user = UserFactory::getInstance($val->b->uid);
                $vip = $user->getUserInfoObject()->getVipLevel();

                //$user=UserFactory::getInstance($val->b->uid);
                //$guardData['userdata']=$this->setBroadcastParam($user,$beGuardedUid);
                $userdata['uid']=$val->b->uid;
                $userdata['nickName']=$val->b->nickName;
                $userdata['anchorLevel']=$val->c->level2;
                $userdata['vipLevel'] = $vip;
                $userdata['richerLevel']=$val->c->level3;
                $userdata['fansLevel']=$val->c->level4;
                $userdata['avatar']=$val->b->avatar;
                if (empty($userdata['avatar'])) {
                    $userdata['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }

                $guardData['userdata'] = $userdata;
                $guardData['online'] = in_array($val->b->uid, $onlineUids) ? 1 : 0; //1;   //需要增加判断。。。。。。。
                if($guardData['online']){
                    array_push($dataOnline, $guardData);
                }else{
                    array_push($dataOffline, $guardData);
                }
            }
        }
        if ($goldList) {
            foreach($goldList as $val){
                $guardData['guardLevel'] = $val->a->guardLevel;
                $userdata['guardLevel'] = $val->a->guardLevel;
                $user = UserFactory::getInstance($val->b->uid);
                $vip = $user->getUserInfoObject()->getVipLevel();

                //$user=UserFactory::getInstance($val->b->uid);
                //$guardData['userdata']=$this->setBroadcastParam($user,$beGuardedUid);
                $userdata['uid']=$val->b->uid;
                $userdata['nickName']=$val->b->nickName;
                $userdata['anchorLevel']=$val->c->level2;
                $userdata['vipLevel'] = $vip;
                $userdata['richerLevel']=$val->c->level3;
                $userdata['fansLevel']=$val->c->level4;
                $userdata['avatar']=$val->b->avatar;
                if (empty($userdata['avatar'])) {
                    $userdata['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }

                $guardData['userdata'] = $userdata;
                $guardData['online'] = in_array($val->b->uid, $onlineUids) ? 1 : 0; //1;   //需要增加判断。。。。。。。
                if($guardData['online']){
                    array_push($dataOnline, $guardData);
                }else{
                    array_push($dataOffline, $guardData);
                }
            }
        }
        if ($silverList) {
            foreach($silverList as $val){
                $guardData['guardLevel'] = $val->a->guardLevel;
                $userdata['guardLevel'] = $val->a->guardLevel;
                $user = UserFactory::getInstance($val->b->uid);
                $vip = $user->getUserInfoObject()->getVipLevel();
                //$user=UserFactory::getInstance($val->b->uid);
                // $guardData['userdata']=$this->setBroadcastParam($user,$beGuardedUid);

                $userdata['uid']=$val->b->uid;
                $userdata['nickName']=$val->b->nickName;
                $userdata['anchorLevel']=$val->c->level2;
                $userdata['vipLevel']=$vip;
                $userdata['richerLevel']=$val->c->level3;
                $userdata['fansLevel']=$val->c->level4;
                $userdata['avatar']=$val->b->avatar;
                if (empty($userdata['avatar'])) {
                    $userdata['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }

                $guardData['userdata'] = $userdata;
                $guardData['online'] = in_array($val->b->uid, $onlineUids) ? 1 : 0; //1;   //需要增加判断。。。。。。。
                if($guardData['online']){
                    array_push($dataOnline, $guardData);
                }else{
                    array_push($dataOffline, $guardData);
                }
            }
        }
        // 按时间排序
        if($dataOnline){
            $dataOnline = $this->baseCode->arrayMultiSort($dataOnline, 'guardLevel');
        }

        if($dataOffline){
            $dataOffline = $this->baseCode->arrayMultiSort($dataOffline, 'guardLevel');
        }

        $resultData = array_merge($dataOnline, $dataOffline);

        return $resultData;
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 房间抢座信息操作接口
    //
    ////////////////////////////////////////////////////////////////////////

    protected function initGrabSeatList($anchorUid) {
        $phql = "DELETE FROM Micro\Models\GrabseatLog WHERE anchorUid = ".$anchorUid;
        $query = $this->modelsManager->createQuery($phql);
        $query->execute();
    }

    protected function getGrabSeatListBase($roomData) {
        $resultData = array();
        if ($roomData->liveStatus != 0) {
            /*             * $phql = "select g.seatUid,g.seatPos,g.seatCount,u.uid,u.accountId,u.manageType,ui.nickName,ui.avatar,up.level1,up.level6,up.vipExpireTime,up.vipExpireTime2,up.level3,gl.guardLevel,gl2.guardLevel as guardLevel2,ru.level as roomLevel,ru.levelTimeLine,uit.itemCount as signLevel "
              . " from \Micro\Models\GrabseatLog g "
              . " inner join  \Micro\Models\Users u on g.seatUid=u.uid and g.anchorUid = ?0 "
              . " inner join  \Micro\Models\UserInfo ui on u.uid=ui.uid "
              . " inner join  \Micro\Models\UserProfiles up on u.uid=up.uid "
              . " left join \Micro\Models\RoomUserStatus ru on ru.roomId= ?1 and ru.uid=u.uid "
              . " left join \Micro\Models\UserItem uit on u.uid=uit.uid and uit.itemType=" . $this->config->itemType->item . " and uit.itemId=3 "
              . " left join \Micro\Models\GuardList gl on gl.guardUid=u.uid and gl.beGuardedUid=g.anchorUid and gl.guardLevel=1 and gl.expireTime>" . time()
              . " left join \Micro\Models\GuardList gl2 on gl2.guardUid=u.uid and gl2.beGuardedUid=g.anchorUid and gl2.guardLevel=2 and gl2.expireTime>" . time();
              $valueArray = array(0 => $roomData->uid, 1 => $roomData->roomId);
              $grabSeatList = $this->modelsManager->executeQuery($phql, $valueArray);
              if ($grabSeatList) {
              foreach ($grabSeatList as $val) {
              $data['seatUid'] = $val->seatUid;
              $data['seatPos'] = $val->seatPos;
              $data['seatCount'] = $val->seatCount;
              $data['avatar'] = $val->avatar;
              if (empty($data['avatar'])) {
              $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
              }
              $data['nickName'] = $val->nickName;
              if (empty($data['nickName'])) {
              $data['nickName'] = $val->uid;
              }
              //$data['vipLevel'] = $val->level1;
              $data['richLevel'] = $val->level3;
              $data['guardLevel'] = $val->guardLevel;
              if($val->roomLevel==2&&$val->levelTimeLine>0&&$val->levelTimeLine<time()){//临时管理员已过期
              $data['level']=1;
              }else{
              $data['level'] = $val->roomLevel;//房管等级
              }

              $data['signLevel'] = $val->signLevel;//签到徽章等级
              $data['manageType'] = $val->manageType;//管理员类型
              if ($val->level6 > 0 && $val->vipExpireTime2 > time()) {//至尊vip
              $data['vipLevel'] = 2;
              } elseif ($val->level1 > 0 && $val->vipExpireTime > time()) {//普通vip
              $data['vipLevel'] = 1;
              } else {
              $data['vipLevel'] = 0;
              }
              if ($val->guardLevel) {//黄金守护
              $data['guardLevel'] = 1;
              } elseif ($val->guardLevel2) {//白银守护
              $data['guardLevel'] = 2;
              } else {
              $data['guardLevel'] = 0;
              }

              array_push($resultData, $data);
              }
              }
              }* */
            $phql = "select g.seatUid,g.seatPos,g.seatCount,g.anchorUid,ui.nickName,ui.avatar,up.level3"
                    . " from \Micro\Models\GrabseatLog g "
                    . " inner join  \Micro\Models\UserInfo ui on g.seatUid=ui.uid "
                    . " inner join  \Micro\Models\UserProfiles up on g.seatUid=up.uid "
                    . " where g.anchorUid=?0";
            $valueArray = array(0 => $roomData->uid);
            $grabSeatList = $this->modelsManager->executeQuery($phql, $valueArray);
            if ($grabSeatList) {
                $roomModule = $this->di->get('roomModule');
                foreach ($grabSeatList as $val) {
                    $data['seatUid'] = $val->seatUid;
                    $data['seatPos'] = $val->seatPos;
                    $data['seatCount'] = $val->seatCount;
                    // $user=UserFactory::getInstance($val->seatUid);
                    //   $userData = $roomModule->getRoomOperObject()->setBroadcastParam($user,$val->anchorUid);
                    $userData['nickName'] = $val->nickName;
                    $userData['richerLevel'] = $val->level3;
                    $userData['avatar'] = $val->avatar;
                    $userData['uid'] = $val->seatUid;
                    if (empty($userData['avatar'])) {
                        $userData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath(); //默认头像
                    }
                    $data['userdata'] = $userData;
                    array_push($resultData, $data);
                }
            }
        }

        return $resultData;
    }

    //构造广播需要的userdata数据
    public function setBroadcastParam($user, $roomUid = 0) {
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
        $return['manageType'] = intval($userData['manageType']); //超级管理员
        $return['badge'] = array(); //
        $return['guardLevel'] = 0; //
        $return['isForbid'] = 0; //
        $return['level'] = 0; //
        //是否禁言
        //$isForbid = 0;
        $return['isFamilyLeader'] = 0;
        if ($roomUid) {
            // 获取守护信息
            /*$guardData = $user->getUserItemsObject()->getGuardData($roomUid);
            if ($guardData != NULL) {
                $return['guardLevel'] = $guardData['level'];
            } else {
                $return['guardLevel'] = '';
            }*/
            //守护状态
            $uid = $user->getUid();
            $return['guardLevel'] = $user->getUserItemsObject()->getGuardLevel($uid, $roomUid);

            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $roomUid);
            $hoster = UserFactory::getInstance($roomUid);
            $return['level'] = $this->getUserLevelInRoom($roomInfo->roomId, $hoster, $user);
            $return['isForbid'] = $this->checkUserIsForbidden($roomInfo->roomId, $uid);
            //用户家族信息
            $return['isFamilyLeader'] = $this->userMgr->checkUserIsHeader($roomInfo->uid, $uid);
            //查询用户属于哪个军团
            $groupres = $this->di->get('groupMgr')->checkUserGroup($uid);
            if ($groupres['code'] == $this->status->getCode('OK') && $groupres['data']) {
                $return['group'] = $groupres['data'];
            }

            //平台信息
            $roomData = $this->getUserInfoInRoom($roomInfo->roomId, $uid);
            if ($roomData != false) {
                $return['platform'] = $roomData['devicetype'];
            }else{
                $return['platform'] = 'pc';
            }
            // $return['platform'] = $this->di->get('roomModule')->getRoomOperObject()->getPlatform();
        }
        //$return['isForbid'] = $isForbid;
        // 获取徽章列表
        $badge = $user->getUserItemsObject()->getUserBadge();
        if ($badge['code'] == $this->status->getCode('OK')) {
            $return['badge'] = $badge['data'];
        }

        // 座驾信息
        $carInfo = $user->getUserItemsObject()->getActiveCarData();
        if ($carInfo) {
            if ($carInfo['itemLeftTime'] > 0) {
                $return['carInfo'] = $carInfo;
            }
        }
        


        return $return;
    }

    /**
     * 获得用户所在的房间
     *
     * @param int $uid
     * @return array]
     */
    public function getUsersWhereIn($uid = 0){
        $userRoom = array();
        if($uid > 0){
            $mongo = $this->di->get('mongo');
            $collection = $mongo->collection('rooms');
            $user = UserFactory::getInstance($uid);
            $accountId = $user->getUserInfoObject()->getAccountId();
            $userRoom = $collection->find(function($query) use($accountId) {
                $query->where('uid', $accountId);
            });
        }

        return $userRoom;
    }
    
    
    //给用户所在的房间发送广播
    public function sendBroadcastInUserRooms($user, $controlType) {
        //查询用户在哪些房间
        $uid = $user->getUid();
        $userRoom = $this->getUsersWhereIn($uid);

        if ($userRoom) {
            $userData = $this->setBroadcastParam($user);

            $accountId = $userData['accountId'];

            //广播内容
            $broadData['userdata'] = $userData;
            $ArraySubData['controltype'] = $controlType;
            $ArraySubData['data'] = $broadData;

            //用户信息nodejs更新
            $nodejsUserData = array();
            $nodejsUserData['userId'] = $userData['uid'];
            $nodejsUserData['uid'] = $userData['uid'];
            $nodejsUserData['name'] = $userData['nickName'];
            $nodejsUserData['nickName'] = $userData['nickName'];
            $nodejsUserData['avatar'] = $userData['avatar'];

            //是否超级管理员
            $nodejsUserData['manageType'] = $userData['manageType'];

            // 进入房间的用户的主播富豪等级、VIP等级
            $nodejsUserData['vipLevel'] = $userData['vipLevel'];
            $nodejsUserData['anchorLevel'] = $userData['anchorLevel'];
            $nodejsUserData['richerLevel'] = $userData['richerLevel'];
            // 座驾信息
            $carInfo = $user->getUserItemsObject()->getActiveCarData();
            if ($carInfo) {
                $nodejsUserData['carInfo'] = $carInfo;
            }
            // 平台信息
            // $nodejsUserData['platform'] = $this->di->get('roomModule')->getRoomOperObject()->getPlatform();
            foreach ($userRoom as $val) {
                // 获取是否禁言状态
                $isForbid = $this->checkUserIsForbidden($val['roomid'], $uid);
                $nodejsUserData['isForbid'] = $isForbid;
                $ArraySubData['data']['userdata']['isForbid'] = $isForbid;

                $roomData = \Micro\Models\Rooms::findfirst($val['roomid']);
                /*$guardData = $user->getUserItemsObject()->getGuardData($roomData->uid);
                if ($guardData != NULL) {
                    $nodejsUserData['guardLevel'] = $guardData['level'];
                    $ArraySubData['data']['userdata']['guardLevel'] = $guardData['level'];
                } else {
                    $nodejsUserData['guardLevel'] = '';
                    $ArraySubData['data']['userdata']['guardLevel'] = '';
                }*/
                //守护状态
                $guardLevel = $user->getUserItemsObject()->getGuardLevel($uid, $roomData->uid);
                $nodejsUserData['guardLevel'] = $guardLevel;
                $ArraySubData['data']['userdata']['guardLevel'] = $guardLevel;
                //平台信息
                $userRoomData = $this->getUserInfoInRoom($roomInfo->roomId, $uid);
                if ($userRoomData != false) {
                    $nodejsUserData['platform'] = $roomData['devicetype'];
                    $ArraySubData['data']['userdata']['platform'] = $roomData['devicetype'];
                }else{
                    $nodejsUserData['platform'] = 'pc';
                    $ArraySubData['data']['userdata']['platform'] = 'pc';
                }
                //管理员
                $hoster = UserFactory::getInstance($roomData->uid);
                $level = $this->getUserLevelInRoom($val['roomid'], $hoster, $user);
                $nodejsUserData['level'] = $level;
                $ArraySubData['data']['userdata']['level'] = $level;
                // 用户家族信息
                $isFamilyLeader = $this->di->get('userMgr')->checkUserIsHeader($roomData->uid, $uid);
                $ArraySubData['data']['userdata']['isFamilyLeader'] = $isFamilyLeader;
                $nodejsUserData['isFamilyLeader'] = $isFamilyLeader;
                
                //查询用户属于哪个军团
                $groupres = $this->di->get('groupMgr')->checkUserGroup($uid);
                if ($groupres['code'] == $this->status->getCode('OK') && $groupres['data']) {
                    $nodejsUserData['group'] = $groupres['data'];
                }


                //更新nodejs用户信息
                $this->comm->updateUserData($val['roomid'], $accountId, json_encode($nodejsUserData));

                //更新广播
                $this->comm->roomBroadcast($val['roomid'], $ArraySubData);
                
                
            }
        }
    }


    // 批量更新用户所在房间的userdata
    public function updateUserdataInRooms($user){
        // 获取用户所在房间
        $uid = $user->getUid();
        $userRoom = $this->getUsersWhereIn($uid);

        if ($userRoom) {
            foreach ($userRoom as $k => $v) {
                $roomData = \Micro\Models\Rooms::findfirst($v['roomid']);
                if(empty($roomData)){
                    continue;
                }
                $roomUid = $roomData->uid;
                $userData = $this->setBroadcastParam($user, $roomUid);
                $accountId = $userData['accountId'];

                //更新nodejs用户信息
                $this->comm->updateUserData($v['roomid'], $accountId, json_encode($userData));
            }
        }
    }

}