<?php

namespace Micro\Frameworks\Logic\Room;

use Micro\Models\Rooms;
use Micro\Models\UserProfiles;
use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Models\DeviceInfo;
use Micro\Models\SongList;
use Micro\Models\FamilyRoom;
use Micro\Frameworks\Logic\User\UserData\UserCash;
class RoomMgr extends RoomBase{
	public function __construct()
    {   
        parent::__construct();
    }



    /**
     * 获得附近的人
     *
     * @return mixed
     */
    public function getNearByUsers(){
        $long = $this->request->get('long') ? $this->request->get('long') : 0.5;
        $max = $this->request->get('max') ? $this->request->get('max') : 100;
        $user = $this->userAuth->getUser();
        $loginuid = 0;
        if (!$user) {
            $longitude = $this->request->get('longitude');
            $latitude  = $this->request->get('latitude');
            $postData['longitude'] = $longitude;
            $postData['latitude'] = $latitude;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
        }else{
            $loginuid = $user->getUid();
            $pos = $this->lbs->getCoordinate($loginuid);
            if(empty($pos)){
                return $this->status->retFromFramework($this->status->getCode('NOT_SET_POSITIONS'));
            }

            $longitude = $pos[0];
            $latitude  = $pos[1];
        }

        $res = $this->lbs->getNearby([floatval($longitude),floatval($latitude)], intval($long), $max);
        if(!empty($res)){
            foreach($res as $key => $val){
                $uid = isset($val['obj']['userid']) ? $val['obj']['userid'] : '';

                unset($val['obj']);
                $user = UserFactory::getInstance($uid);
                $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
                $userBaseProfiles = $user->getUserInfoObject()->getUserProfiles();
                $roomInfo = $this->getRoomInfo(NULL, $uid);
                $val['roomId'] = isset($roomInfo->roomId) ? $roomInfo->roomId : 0;
                $val['uid'] = $uid;
                $val['poster']  = isset($roomInfo->poster) ? $roomInfo->poster : '';
//                $val['onlineNum']  = !empty($roomInfo) ? $roomInfo->onlineNum + $roomInfo->robotNum : 0;
                $val['onlineNum']  = !empty($roomInfo) ? $roomInfo->totalNum : 0;
                $val['liveStatus'] = !empty($roomInfo) ? $roomInfo->liveStatus : 0;
                $val['showStatus'] = !empty($roomInfo) ? $roomInfo->showStatus : 0;
                $val['nickName'] = isset($userBaseInfo['nickName']) ? $userBaseInfo['nickName'] : '';
                $val['avatar'] = isset($userBaseInfo['avatar']) ? $userBaseInfo['avatar'] : '';
                $val['distance'] = $val['dis'];
                $val['fansLevel'] = isset($userBaseProfiles['fansLevel']) ? intval($userBaseProfiles['fansLevel']) :0;
                unset($val['dis']);
                $res[$key] = $val;
            }
        }else{
            $res = array();
        }

        if($loginuid > 0){
            if($res){
                foreach($res as $k => $val){
                    if($val['uid'] == $loginuid){
                        unset($res[$k]);
                    }
                }
            }
        }

        $data['data'] = $this->nearBySort($res, $uid);
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    /**
     * 附近的人排序
     *
     * @param $data
     * @return array
     */
    public function nearBySort($data){
        $newData = array();
        // 关12个人按主粉等级降序
        $data = $this->baseCode->arrayMultiSort($data, 'fansLevel', TRUE);
        if(count($data) <= 12){
            return $data;
        }else{
            foreach($data as $key => $val){
                if($key <= 11){
                    $newData[$key] = $val;
                    unset($data[$key]);
                }
            }

            // 新data按3公里排序
            $dataTmp = $this->soryByDis($data, 3);
            if($dataTmp){
                foreach($dataTmp as $v){
                    $newData[] = $v;
                }
            }
        }

        return $newData;
    }

    /**
     * 附近的人按3公里为半径的排序
     *
     * @param $data
     * @param int $dis
     * @return array
     */
    public function soryByDis($data, $dis = 3){
        $tmpData = array();
        $newTmpData = array();
        $fansLevelArr = array();
        $distanceArr = array();
        $newDis = 0;
        // 过滤出$dis一倍的数据
        if($data){
            foreach($data as $key => $val){
                $distance = $val['distance'];
                if($distance <= $dis){
                    $tmpData[] = $val;
                }else{
                    // 有两倍的数据，则获得下一级的
                    $newDis = $dis + 3;
                }
            }

            // 排序
            if($tmpData){
                foreach($tmpData as $key => $val){
                    $fansLevelArr[$key] = $val['fansLevel'];
                }

                foreach($tmpData as $key => $val){
                    $distanceArr[$key] = $val['distance'];
                }

                if($fansLevelArr && $distanceArr && $tmpData){
                    array_multisort($fansLevelArr, SORT_DESC, $distanceArr, SORT_ASC, $tmpData);
                }
            }

            if($newDis){
                foreach($data as $key => $val){
                    $distance = $val['distance'];
                    if($distance > $dis){
                        $newTmpData[] = $val;
                    }
                }

                $newTmpData = $this->soryByDis($newTmpData, $newDis);
            }

            if($newTmpData){
                foreach($newTmpData as $v){
                    $tmpData[] = $v;
                }
            }
        }

        return $tmpData;
    }

    /**
     * 获得广场数据
     */
    public function getSquareList($limit = 500, $roomType = 0){
        try{
            $sql = "select u.uid,r.roomId,r.poster,r.liveStatus,r.showStatus,p.level4,totalNum as num from \Micro\Models\Users u ".
                   "left join \Micro\Models\Rooms r on u.uid = r.uid ".
                   "left join \Micro\Models\UserProfiles p on u.uid = p.uid where r.roomType={$roomType} ".
                   "order by r.liveStatus desc,num desc,p.level2 desc, p.level4 desc limit {$limit}";
//            $connection = $this->di->get('db');
//            $result = $connection->fetchAll($sql);
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();//var_dump($result);die;
            $result = $result->toArray();
            if($result){
                foreach($result as $key => $val){
                    $uid = $val["uid"];
                    //echo "uid = ".$uid;die;
                    $user = UserFactory::getInstance($uid);
                    $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
                    $v['roomId'] = isset($val['roomId']) ? $val['roomId'] : 0;
                    $v['uid'] = $uid;
                    $v['poster']  = isset($val['poster']) ? $val['poster'] : '';
                    $v['onlineNum']  = isset($val['num']) ? $val['num'] : 0;
                    $v['liveStatus'] = isset($val['liveStatus']) ? $val['liveStatus'] : 0;
                    $v['showStatus'] = isset($val['showStatus']) ? $val['showStatus'] : 0;
                    $v['nickName'] = isset($userBaseInfo['nickName']) ? $userBaseInfo['nickName'] : '';
                    $v['avatar'] = isset($userBaseInfo['avatar']) ? $userBaseInfo['avatar'] : '';
                    $v['distance'] = 0;
                    $v['fansLevel'] = isset($val['level4']) ? $val['level4'] : 0;
                    $res[$key] = $v;
                }
            }else{
                $res = array();
            }

            $data['data'] = $res;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getRoomListByType($type = 1, $limit = 300){
        $res= array('code' => '0', 'data' => array());
        switch($type){
            case 1:
//                $order = $this->request->get('order');
//                $res = $this->getSquareList($limit);
//                    $res = $this->getRoomList(2, '', '', $limit);
                $res = $this->getRoomListForMobile(2, '', '', $limit);
                break;
            case 2:
                $res = $this->getNearByUsers();
                break;
        }

        return $this->status->retFromFramework($res['code'], $res['data']);

    }

    /**
     * @param $sortType : 0-开播时间、1-主播等级、2-观众人数 3-是否为推荐
     * @param $uid : 排除的用户uid
     * @param $skip : 跳过头部条数
     * @param $limit : 总条数限制
     * @param $roomType 0-美女主播 1- 家族主播 1000-莆仙戏
     */
    public function getNewRoomList($uid = NULL, $skip = NULL, $limit = NULL, $roomType = 0, $isMobile = 1, $useCache = 0) {
        if($uid != NULL) {
            $postData['uid'] = $uid;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
        }

        //读取缓存
        if($useCache == 0){
            $normalLib = $this->di->get('normalLib');
            $cacheKey = 'newRoomList';
            $cacheResult = $normalLib->getCache($cacheKey);
            if (isset($cacheResult)) {
                return $this->status->retFromFramework($this->status->getCode('OK'), $cacheResult);
            }
        }
            

        if(empty($limit)){
            $limit = 500;
        }

        if(empty($skip)){
            $skip = 0;
        }

        try{
            $phql = "SELECT ui.uid,r.roomId,r.rType,r.streamName,r.isOpenVideo,r.liveStatus,r.isRecommend,ui.nickName,ui.gender,r.title,f.shortName,sa.location,up.level2,r.poster,ui.avatar,r.onlineNum,r.robotNum,r.totalNum,up.level4,r.publicTime ".
                " FROM \Micro\Models\Rooms r ".
                " LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid = r.uid ".
                " LEFT JOIN \Micro\Models\UserProfiles up ON up.uid = r.uid ".
                " LEFT JOIN \Micro\Models\SignAnchor sa ON sa.uid = r.uid AND sa.status <> {$this->config->signAnchorStatus->apply} AND sa.status <> {$this->config->signAnchorStatus->refuse} AND sa.status <> {$this->config->signAnchorStatus->unbind}".
                " LEFT JOIN \Micro\Models\Family f ON f.id = sa.familyId ".
                " WHERE ui.uid = r.uid AND up.uid = r.uid ";
            if($uid != NULL){
                $phql .= " AND ui.uid != ".$uid;
            }

            if($roomType > 0){
                $phql .= " AND r.roomType=$roomType";
            }else{
                $phql .= " AND r.roomType!=" . $this->config->roomType->puxianxi; // 默认屏蔽莆田戏
            }

            //显示状态为1
            $phql .= " AND r.showStatus = 1 ";
            $sql = $phql; // 拷一份查询用
            if(empty($skip)) {
                // 第一页特殊处理,获得前九在开播的,统计开播总数
//                $onlineCount = Rooms::count("liveStatus=1 AND showStatus = 1 ");
                $phql .= " AND r.isRecommend > 0";
                $phql .= " order by ";
                $orderBy = " r.isRecommend asc";
                $limitOffset = " LIMIT " . $this->config->isRecommend;
                $query = $this->modelsManager->createQuery($phql . $orderBy . $limitOffset);
                $rooms = $query->execute();
                $roomList = $this->formatRoomList($rooms, $isMobile);
                $recData = array();
                if($roomList){
                    foreach($roomList as $room){
                        if($room){
                            $recData[] = $room;
                        }
                    }
                }

                $limit = $limit - count($roomList) > 0 ? $limit - count($roomList) : 0;
                $sql .= " AND r.isRecommend = 0";
                $sql .= " order by ";
                $orderBy = " r.totalNum desc,up.level2 desc,r.isOpenVideo desc ";
                $limitOffset = " LIMIT " . $limit;
                $query = $this->modelsManager->createQuery($sql . $orderBy . $limitOffset);
                $rooms = $query->execute();
                $roomList = $this->formatRoomList($rooms, $isMobile);
            }

            $result['recData'] = $recData;
            $result['otherData'] = $roomList;
            //设置缓存
            if($useCache == 0){
                $liftTime = 25; //有效期30秒
                $normalLib->setCache($cacheKey, $result, $liftTime);
            }
            
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }catch (\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function formatRoomList($rooms, $isMobile = 0){
        $roomList = array();
        if($rooms->valid()){
            $newRoomList1 = array();
            $newRoomList2 = array();
            $newRoomList3 = array();
            $newRoomList0 = array();
            $newRoomList4 = array();
            foreach ($rooms as $room) {
                $roomData['uid'] = $room->uid;
                //accoundId;
                $roomData['roomId'] = $room->roomId;
                $roomData['isRecommend'] = $room->isRecommend;
                $roomData['liveStatus'] = $room->liveStatus;
                $roomData['nickName'] = $room->nickName;
                $roomData['gender'] = $room->gender;
                $roomData['title'] = $room->title;
                $roomData['familyShortName'] = $room->shortName;
                $roomData['streamName'] = $room->streamName;
                $roomData['isOpenVideo'] = $room->isOpenVideo;
                // $roomData['rType'] = $room->rType;
                $roomData['rType'] = 0;
                if($room->liveStatus == $this->config->roomLiveStatus->start || $room->liveStatus == $this->config->roomLiveStatus->pause){
                    $return['rType'] = $room->rType;
                }
                $roomData['videoName'] = '';
                if($room->liveStatus == 0 && $room->isOpenVideo == 1){
                    $res = \Micro\Models\Videos::findFirst(
                        'status = 0 and uid = ' . $room->uid . ' and isUsing = 1'
                    );
                    if(!empty($res)){
                        $roomData['videoName'] = $res->streamName ? ($this->config->RECInfo->url . $res->streamName . $this->config->RECInfo->format) : '';
                    }
                }
                $location = $room->location;
                if(empty($location)){
                    $location = $this->config->signAnchorCityDefault;
                }

                $roomData['location'] = $this->config->location[$location]['name'];
                if (empty($roomData['nickName'])) {
                    $roomData['nickName'] = $room->uid;
                }

                $roomData['anchorLevel'] = $room->level2;
                // $roomData['poster'] = $room->poster;
                $roomData['avatar'] = $room->avatar;
                $roomData['distance'] = 0;
                $posterUrl = $room->poster;
                $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($posterUrl, $room->avatar);
                $roomData['poster'] = $posterUrls['poster'];
                $roomData['small_poster'] = $posterUrls['small-poster'];
                $roomData['onlineNum'] = $room->totalNum;
                $roomData['fansLevel'] = $room->level4;
                $roomData['publicTime'] = $room->publicTime;
                $roomData['bobing'] = 0;
                //判断是否开启博饼活动
                if (time() > $this->config->midAutumn->startTime && time() <= $this->config->midAutumn->endTime) {//中秋活动期间
                    //查询博饼活动状态
                    $bobingData = \Micro\Models\MoonEnergy::findFirst("uid=" . $room->uid . " and type=2");
                    if ($bobingData && $bobingData->totalNum >= $this->config->midAutumn->energyLimit) {
                        $roomData['bobing'] = 1;
                    }
                }

                //array_push($roomList, $roomData);
                switch ($room->liveStatus) {
                    case 1:
                        $newRoomList1[] = $roomData;
                        break;
                    case 2:
                        $newRoomList2[] = $roomData;
                        break;
                    case 3:
                        $newRoomList3[] = $roomData;
                        break;
                    default :
                        if($room->isOpenVideo == 1){
                            $newRoomList4[] = $roomData;
                        }else{
                            $newRoomList0[] = $roomData;
                        }
                        break;
                }

            }

            $roomList = array_merge($newRoomList1,$newRoomList3);
            $roomList = array_merge($roomList,$newRoomList4);
            $roomList = array_merge($roomList,$newRoomList0);
            $roomList = array_merge($roomList,$newRoomList2);

        }

        return $roomList;
    }

    /**
     * @param $sortType : 0-开播时间、1-主播等级、2-观众人数 3-是否为推荐
     * @param $uid : 排除的用户uid
     * @param $skip : 跳过头部条数
     * @param $limit : 总条数限制
     * @param $roomType 0-美女主播 1000-莆仙戏
     */
    public function getRoomList($sortType,$uid=NULL,$skip=NULL,$limit=NULL,$roomType=0) {
        $postData['sorttype'] = $sortType;
        $skip=$skip?$skip:0;
        $limit=$limit?$limit:500;
        if($uid!=NULL) {
            $postData['uid']=$uid;
        }
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $rooms = NULL;

            $phql = "SELECT ui.uid,r.roomId,r.liveStatus,ui.nickName,ui.gender,r.title,f.shortName,sa.location,up.level2,r.poster,ui.avatar,r.onlineNum,r.robotNum,r.totalNum,up.level4,r.publicTime ".
                    " FROM \Micro\Models\Rooms r ".
                    " LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid = r.uid ".
                    " LEFT JOIN \Micro\Models\UserProfiles up ON up.uid = r.uid ".
                    " LEFT JOIN \Micro\Models\SignAnchor sa ON sa.uid = r.uid AND sa.status <> {$this->config->signAnchorStatus->apply} AND sa.status <> {$this->config->signAnchorStatus->refuse} AND sa.status <> {$this->config->signAnchorStatus->unbind}".
                    " LEFT JOIN \Micro\Models\Family f ON f.id = sa.familyId ".
                    " WHERE ui.uid = r.uid AND up.uid = r.uid AND r.roomType=".$roomType;

            if($uid!=NULL){
                $phql.=" AND ui.uid != ".$uid;
            }
            //显示状态为1
            $phql.=" AND r.showStatus = 1 ";
            
            
            $phql.=" order by ";
            $liveStatusOrderBy=" r.liveStatus desc";
            switch ($sortType) {
                case 0:{
                    $orderBy = $liveStatusOrderBy.",r.publicTime desc";
                    break;
                }
                case 1:{
                    $orderBy = $liveStatusOrderBy.",up.exp2 desc";
                    break;
                }
                case 2:{
                    $orderBy = $liveStatusOrderBy.",r.totalNum desc";
                    break;
                }
                case 3:{
                    $orderBy ="r.isRecommend desc,".$liveStatusOrderBy;
                    break;
                }
                case 4:
                    $orderBy=$liveStatusOrderBy;
                    break;
                case 5:
                    $orderBy=$liveStatusOrderBy;
                    break;
                default:
                    return $this->status->retFromFramework($this->status->getCode('GET_ROOM_LIST_PARAM_ERROR'));
            }
            $limitOffset= " LIMIT ".$limit." OFFSET ".$skip;
            
            $query = $this->modelsManager->createQuery($phql.$orderBy.$limitOffset);
            $rooms = $query->execute();

            $roomList = array();
            $newRoomList1 = array();
            $newRoomList2 = array();
            $newRoomList3 = array();
            $newRoomList0 = array();
            if ($rooms->valid()) {
                foreach ($rooms as $room) {
                
                    $roomData['uid'] = $room->uid;
                    //accoundId;
                    $roomData['roomId'] = $room->roomId;
                    $roomData['liveStatus'] = $room->liveStatus;
                    $roomData['nickName'] = $room->nickName;
                    $roomData['gender'] = $room->gender;
                    $roomData['title'] = $room->title;
                    $roomData['familyShortName'] = $room->shortName;
                    $location = $room->location;
                    if(empty($location)){
                        $location = $this->config->signAnchorCityDefault;
                    }
                    $roomData['location'] = $this->config->location[$location]['name'];
                    if (empty($roomData['nickName'])) {
                        $roomData['nickName'] = $room->uid;
                    }
                    $roomData['anchorLevel'] = $room->level2;
                    // $roomData['poster'] = $room->poster;
                    $roomData['avatar'] = $room->avatar;
                    $roomData['distance'] = 0;
                    $posterUrl = $room->poster;
                    $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($posterUrl, $room->avatar);
                    $roomData['poster'] = $posterUrls['poster'];
                    $roomData['small-poster'] = $posterUrls['small-poster'];
                    /*if (empty($roomData['poster'])) {
                        $roomData['poster'] = $room->avatar;
                        if (empty($roomData['poster'])) {
                            $roomData['poster'] = $this->pathGenerator->getFullDefaultAvatarPath();
                        }
                    }*/
//                    $roomData['onlineNum'] = $room->onlineNum+$room->robotNum;
                    $roomData['onlineNum'] = $room->totalNum;
                    $roomData['fansLevel'] = $room->level4;
                    $roomData['publicTime']=$room->publicTime;
                    
//                    //判断是否参加七夕活动
//                    if (in_array($room->uid, $this->config->qixi->uids->toArray())&&time()<$this->config->qixi->endTime) {
//                        $roomData['qixi'] = 1;
//                    }else{
//                        $roomData['qixi'] = 0;
//                    }

                    //array_push($roomList, $roomData);
                    switch ($room->liveStatus) {
                        case 1:
                            $newRoomList1[] = $roomData;
                            break;
                        case 2:
                            $newRoomList2[] = $roomData;
                            break;
                        case 3:
                            $newRoomList3[] = $roomData;
                            break;
                        default :
                            $newRoomList0[] = $roomData;
                            break;
                    }
                 }
            }
            $roomList=  array_merge($newRoomList1,$newRoomList3);
            $roomList=  array_merge($roomList,$newRoomList0);
            $roomList=  array_merge($roomList,$newRoomList2);

            $result['data'] = $roomList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * @param $sortType : 0-开播时间、1-主播等级、2-观众人数 3-是否为推荐
     * @param $uid : 排除的用户uid
     * @param $skip : 跳过头部条数
     * @param $limit : 总条数限制
     * @param $roomType 0-美女主播 1000-莆仙戏
     */
    public function getRoomListForMobile($sortType,$uid=NULL,$skip=NULL,$limit=NULL,$roomType=0) {
        $postData['sorttype'] = $sortType;
        $skip=$skip?$skip:0;
        $limit=$limit?$limit:500;
        if($uid!=NULL) {
            $postData['uid']=$uid;
        }
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $rooms = NULL;

            $phql = "SELECT ui.uid,r.roomId,r.liveStatus,r.showStatus,ui.nickName,ui.gender,r.title,f.shortName,sa.location,up.level2,r.poster,ui.avatar,r.onlineNum,r.robotNum,r.totalNum,up.level4,r.publicTime ".
                " FROM \Micro\Models\Rooms r ".
                " LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid = r.uid ".
                " LEFT JOIN \Micro\Models\UserProfiles up ON up.uid = r.uid ".
                " LEFT JOIN \Micro\Models\SignAnchor sa ON sa.uid = r.uid AND sa.status <> {$this->config->signAnchorStatus->apply} AND sa.status <> {$this->config->signAnchorStatus->refuse} AND sa.status <> {$this->config->signAnchorStatus->unbind}".
                " LEFT JOIN \Micro\Models\Family f ON f.id = sa.familyId ".
                " WHERE ui.uid = r.uid AND up.uid = r.uid AND r.roomType=".$roomType;

            if($uid!=NULL){
                $phql.=" AND ui.uid != ".$uid;
            }
            //显示状态为1
            $phql.=" AND r.showStatus = 1 ";


            $phql.=" order by ";
            $liveStatusOrderBy=" r.liveStatus desc";
            switch ($sortType) {
                case 0:{
                    $orderBy = $liveStatusOrderBy.",r.publicTime desc";
                    break;
                }
                case 1:{
                    $orderBy = $liveStatusOrderBy.",up.exp2 desc";
                    break;
                }
                case 2:{
                    $orderBy = $liveStatusOrderBy.",totalNum desc";
                    break;
                }
                case 3:{
                    $orderBy ="r.isRecommend desc,".$liveStatusOrderBy;
                    break;
                }
                case 4:
                    $orderBy=$liveStatusOrderBy;
                    break;
                case 5:
                    $orderBy=$liveStatusOrderBy;
                    break;
                default:
                    return $this->status->retFromFramework($this->status->getCode('GET_ROOM_LIST_PARAM_ERROR'));
            }
            $limitOffset= " LIMIT ".$limit." OFFSET ".$skip;

            $query = $this->modelsManager->createQuery($phql.$orderBy.$limitOffset);
            $rooms = $query->execute();

            $roomList = array();
            $newRoomList1 = array();
            $newRoomList2 = array();
            $newRoomList3 = array();
            $newRoomList0 = array();
            if ($rooms->valid()) {
                foreach ($rooms as $room) {

                    $roomData['uid'] = $room->uid;
                    //accoundId;
                    $roomData['roomId'] = $room->roomId;
                    $roomData['liveStatus'] = $room->liveStatus;
                    $roomData['showStatus'] = $room->showStatus;
                    $roomData['nickName'] = $room->nickName;
                    $roomData['gender'] = $room->gender;
                    $roomData['title'] = $room->title;
                    $roomData['familyShortName'] = $room->shortName;
                    $location = $room->location;
                    if(empty($location)){
                        $location = $this->config->signAnchorCityDefault;
                    }
                    $roomData['location'] = $this->config->location[$location]['name'];
                    if (empty($roomData['nickName'])) {
                        $roomData['nickName'] = $room->uid;
                    }
                    $roomData['anchorLevel'] = $room->level2;
                    // $roomData['poster'] = $room->poster;
                    $roomData['avatar'] = $room->avatar;
                    $roomData['distance'] = 0;
                    $posterUrl = $room->poster;
                    $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($posterUrl, $room->avatar);
                    $roomData['poster'] = $posterUrls['poster'];
                    $roomData['small_poster'] = $posterUrls['small-poster'];
                    /*if (empty($roomData['poster'])) {
                        $roomData['poster'] = $room->avatar;
                        if (empty($roomData['poster'])) {
                            $roomData['poster'] = $this->pathGenerator->getFullDefaultAvatarPath();
                        }
                    }*/
                    //设置海报, 多种分辨率
                    /*$posterUrl = $room->poster;
                    if (empty($posterUrl)) {        //不存在,取默认数据
                        $posterUrl = $room->avatar;
                        if (empty($posterUrl)) {
                            $posterUrl = $this->pathGenerator->getFullDefaultAvatarPath();
                        }

                        $roomData['poster'] = $posterUrl;
                        $roomData['small_poster'] = $posterUrl;
                    }
                    else {                          //存在,做图片处理
                        $roomData['poster'] = $posterUrl;

                        $posterArray = explode("?", $posterUrl);
                        $posterUrl = $posterArray[0];
                        $posterUrl = str_replace("cdn", "image", $posterUrl);
                        $posterUrl = $this->di->get('thumbGenerator')->getThumbnail($posterUrl, 'poster-small');

                        $roomData['small_poster'] = $posterUrl;
                    }*/
//                    $roomData['onlineNum'] = $room->onlineNum+$room->robotNum;
                    $roomData['onlineNum'] = $room->totalNum;
                    $roomData['fansLevel'] = $room->level4;
                    $roomData['publicTime']=$room->publicTime;
                    
//                    //判断是否参加七夕活动
//                    if (in_array($room->uid, $this->config->qixi->uids->toArray())&&time()<$this->config->qixi->endTime) {
//                        $roomData['qixi'] = 1;
//                    }else{
//                        $roomData['qixi'] = 0;
//                    }
                    
                    //array_push($roomList, $roomData);
                    switch ($room->liveStatus) {
                        case 1:
                            $newRoomList1[] = $roomData;
                            break;
                        case 2:
                            $newRoomList2[] = $roomData;
                            break;
                        case 3:
                            $newRoomList3[] = $roomData;
                            break;
                        default :
                            $newRoomList0[] = $roomData;
                            break;
                    }
                }
            }
            $roomList=  array_merge($newRoomList1,$newRoomList3);
            $roomList=  array_merge($roomList,$newRoomList0);
            $roomList=  array_merge($roomList,$newRoomList2);

            $result['data'] = $roomList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    public function getGuessHoster($uid,$limit, $isMobile = 0)
    {
        $limit=$limit?$limit:3;
        $postData['uid']=$uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $rooms = array();
            $secondRooms=array();
            $exp=' AND a.uid !='.$uid." and c.roomType<".$this->config->roomType->puxianxi;
             //$phql = 'SELECT '.implode(',', $SelectArr).' FROM \Micro\Models\UserInfo a, \Micro\Models\UserProfiles b, \Micro\Models\Rooms c  WHERE a.uid = b.uid AND b.uid = c.uid order by c.liveStatus desc,';
            $phql = 'SELECT a.*, b.*, c.* FROM \Micro\Models\UserInfo a, \Micro\Models\UserProfiles b, \Micro\Models\Rooms c  WHERE a.uid = b.uid AND b.uid = c.uid AND c.liveStatus=1 AND c.showStatus=1';
//            $phql = 'SELECT a.*, b.*, c.*'
//                    . ' FROM \Micro\Models\UserInfo a'
//                    . 'LEFT JOIN \Micro\Models\UserProfiles b ON a.uid = b.uid'
//                    . 'LEFT JOIN \Micro\Models\Rooms c ON b.uid = c.uid'
//                    . ' WHERE c.liveStatus=1 AND c.showStatus=1';
            
            $phql.=$exp;
            $phql.=" ORDER BY RAND() LIMIT ".$limit;
            $query = $this->modelsManager->createQuery($phql);
            $firstRooms = $query->execute()->toArray();
            $firstCount=count($firstRooms);
            //如果数量不够
            if($firstCount<$limit)
            {
                $phql = 'SELECT a.*, b.*, c.* FROM \Micro\Models\UserInfo a, \Micro\Models\UserProfiles b, \Micro\Models\Rooms c  WHERE a.uid = b.uid AND b.uid = c.uid AND c.liveStatus=0 AND c.showStatus=1';
//                $phql = 'SELECT a.*, b.*, c.* '
//                        . 'FROM \Micro\Models\UserInfo a'
//                        . 'LEFT JOIN \Micro\Models\UserProfiles b ON a.uid = b.uid'
//                        . 'LEFT JOIN \Micro\Models\Rooms c ON b.uid = c.uid '
//                        . 'WHERE  c.liveStatus=0 AND c.showStatus=1';
                
                $phql.=$exp;
                $phql.=" ORDER BY RAND() LIMIT ".($limit-$firstCount);
                $query = $this->modelsManager->createQuery($phql);
                $secondRooms = $query->execute()->toArray();
             }
            $rooms=array_merge($firstRooms,$secondRooms);
            $roomList = array();
            //if ($rooms->valid()) {
                foreach ($rooms as $room) {
                    $roomData['uid'] = $room->a->uid;
                    //accoundId;
                    $roomData['roomId'] = $room->c->roomId;
                    $roomData['liveStatus'] = $room->c->liveStatus;
                    $roomData['isOpenVideo'] = $room->c->isOpenVideo;
                    $roomData['nickName'] = $room->a->nickName;
                    $roomData['gender'] = $room->a->gender;
                    if (empty($roomData['nickName'])) {
                        $roomData['nickName'] = $room->a->uid;
                    }
                    $roomData['anchorLevel'] = $room->b->level2;
                    $posterUrl = $room->c->poster;
                    $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($posterUrl, $room->a->avatar);
                    $roomData['poster'] = $posterUrls['poster'];
                    $roomData['small_poster'] = $posterUrls['small-poster'];
                    /*$roomData['poster'] = $room->c->poster;
                    if (empty($roomData['poster'])) {
                        $roomData['poster'] = $room->a->avatar;
                        if (empty($roomData['poster'])) {
                            $roomData['poster'] = $this->pathGenerator->getFullDefaultAvatarPath();
                        }
                    }*/
                    $roomData['onlineNum'] = $room->c->onlineNum;
                    $roomData['fansLevel'] = $room->b->level4;
                    $roomData['publicTime']=$room->c->publicTime;
                    array_push($roomList, $roomData);
                }
           // }
            $result['data'] = $roomList;
            // $roomModule = $this->di->get('roomModule');
            // $result['jumpUid'] = $roomModule->getRoomOperObject()->getJumpAnchorId(1);
            if(!$isMobile){
                $normalLib = $this->di->get('normalLib');
                $result['jumpUid'] = $normalLib->getHotRoom();
            }
            
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
	public function setRoomTitle($title, $announcement, $publishRoute = 0, $useAccelarate = 1, $nextTime = 0) {
        //房间标题长度不能超过15个字符，如何判断
        if ($title != NULL) {   //能否对空字符串也做判断
            $postData['roomtitle'] = $title;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
        }

        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        if (($title == NULL) || (strlen($title) == 0)) {
            $title = $user->getUserInfoObject()->getNickName() . '的房间';
        }

        try {
            // 更新房间标题
            return $this->updateRoomTitle($user->getUid(), $title,$announcement, $publishRoute, $useAccelarate, $nextTime);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function uploadPoster($file) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        try {
            //$fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION)); //$pathinfos['extension']);
            $fileNameArray = explode('.', strtolower($file->getName()));
            $fileExt = $fileNameArray[count($fileNameArray)-1];
            $filePath = $this->pathGenerator->getPosterPath($user->getUid());
            $fileName = time() . '.' . $fileExt;
            $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);

            try {
                $room = Rooms::findFirst("uid = " .$user->getUid());
                $room->poster = $this->pathGenerator->getRelPosterPath($user->getUid(), $fileName);//. '?' . time();
                $room->save();

                return $this->status->retFromFramework($this->status->getCode('OK'));
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getCountInRoom($roomId) {
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        if($this->config->robotVersion == '0.0.2'){
            $roomRobotCount = 0;
        }else{
            $roomRobotCount = $this->getRoomRobotCount($roomId);
        }
        $result = $this->getCountInRoomBase($roomId);
        if ($result['code'] == $this->status->getCode('OK')) {
            $result['data']['robotCount'] = $roomRobotCount;
        }
        return $result;
    }

    /*
     * 检查用户是否已创建过房间
     * */
    public function checkRoomExist($uid){
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            return FALSE;
        }
        try{
            $count = Rooms::count("uid = ".$uid);
            if($count > 0){
                return TRUE;
            }
            return FALSE;
        }catch (Exception $e) {
            $this->errLog('checkRoomExits errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    // 未测试
    public function getCarsInRoom($roomId) {
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $result = $this->getUserListInRoomByDriverFlag($roomId, 1);
            $resultData = array();
            if ($result) {
                foreach($result as $key => $val){
                    $uid = $val['extUid'];
                    $accountId = $val['uid'];
                    $user = UserFactory::getInstance($uid);
                    $userData = $user->getUserInfoObject()->getData();

                    $carData = $user->getUserItemsObject()->getActiveCarData();

                    if ($carData != NULL) {
                        $resultData[$key]['uid'] = $uid;
                        $resultData[$key]['avatar'] = $userData['avatar'];
                        $resultData[$key]['vipLevel'] = $userData['vipLevel'];
                        $resultData[$key]['anchorLevel'] = $userData['anchorLevel'];
                        $resultData[$key]['richerLevel'] = $userData['richerLevel'];
                        $resultData[$key]['nickName'] = $userData['nickName'];
                        $resultData[$key]['accountId'] = $userData['accountId'];
                        //$result[$key]['guard'] = isGuard($uid, $result[$key]['uid']); //守护不知道要做什么用，因此先不进行查询操作

                        // $xi_id = intval($result[$key]['xi_id']);
                        // $res = DB::fetch_first("select tips,xi_content,xi_price from ychat_xiu_interaction where xi_id={$xi_id}");
                        // $result[$key]['xi_content'] =$res['xi_content'];
                        // $result[$key]['xi_price'] =$res['xi_price'];
                        // $result[$key]['tips'] =$res['tips'];
                    
                        $resultData[$key]['carName'] = $carData['carName'];
                        $resultData[$key]['description'] = $carData['description'];
                        $resultData[$key]['configName'] = $carData['configName'];
                        $resultData[$key]['price'] = $carData['price'];
                        $resultData[$key]['typeId'] = $carData['typeId'];
                        $resultData[$key]['sort'] = $carData['sort'];
                    }
                }
                $resultData = $this->baseCode->arrayMultiSort($resultData, 'price', TRUE);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $resultData);
        }
        catch(\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 未测试
    public function getGuardDataList($roomId) {
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if(empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            $resultData = $this->getGuardDataListBase($roomId, $roomData->uid);
            return $this->status->retFromFramework($this->status->getCode('OK'), $resultData);
        }
        catch(\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 未测试
    public function getGrabSeatList($roomId) {
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        
        try {
            // 获取房间信息
            $roomData = Rooms::findFirst("roomId = " . $roomId);
            if(empty($roomData)) {
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }

            $resultData = $this->getGrabSeatListBase($roomData);
            return $this->status->retFromFramework($this->status->getCode('OK'), $resultData);
        }
        catch(\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
	
	//在线活动
	public function onlineActivities(){
		// 用户必须登录
		$user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
		$time = strtotime("+ 5 minutes", $this->session->get("getCashTime"));
        $currentTime = time();
		if($currentTime > $time){
            $addCoin=25;
			//获取用户信息
			$userProfile = UserProfiles::findFirst("uid = " . $user->getUid());
			$userProfile->coin = $userProfile->coin + $addCoin;				//聊豆
			$userProfile->save();
            $this->session->set("getCashTime", $currentTime);

            $result['coin']=$userProfile->coin;
            $result['addCoin']=$addCoin;
            return $this->status->retFromFramework($this->status->getCode("OK"),$result);
		}else{
            $result['leftTime'] = $time - $currentTime;
			return $this->status->retFromFramework($this->status->getCode('FREE_COIN_NOT_AVAILABLE'), $result);
		}
		
	} 
        
     /*
     * 检查是否禁播
     * */
    public function checkLiveStatus($roomId) {
        $result = array('liveStatus' => 0);
        try {
            $info = Rooms::findfirst($roomId);
            if ($info) {
                $result['liveStatus'] = intval($info->liveStatus);
            }
            return $this->status->retFromFramework($this->status->getCode("OK"), $result);
        } catch (Exception $e) {
            $this->errLog('checkLiveStatus errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $result);
        }
    }

     /*
     * 获取当前最热门主播
     * */
    public function getHotRoom() {
        try {
            $sql = "SELECT uid FROM \Micro\Models\Rooms WHERE liveStatus=1 AND showStatus=1 ORDER BY totalNum DESC LIMIT 0,1";
            $query = $this->modelsManager->createQuery($sql);
            $data = $query->execute();
            if (!$data->valid()) {
                return $this->status->retFromFramework($this->status->getCode("NO_PUBLISHED_ROOM"));
            }
            $result['uid'] = $data->toArray()[0]['uid'];
            return $this->status->retFromFramework($this->status->getCode("OK"), $result);
        } catch (Exception $e) {
            $this->errLog('getHotRoom errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $result);
        }
    }

    // 更新房间的机器人人数
    public function updateRobotCount($roomId, $totalCount, $robotCount) {
        try {
            $robotCount = $robotCount > 0 ? $robotCount : 0;
            if($this->config->robotVersion == '0.0.2'){
                $totalCount = $totalCount > 0 ? $totalCount : 0;
                $totalCount += $robotCount;
                //totalCount包括【机器人用户】+【真实用户】
                $sql = "UPDATE Micro\Models\Rooms SET robotNum={$robotCount},totalNum={$totalCount} WHERE roomId={$roomId} ";//////
                //$sql = "UPDATE Micro\Models\Rooms SET robotNum={$robotCount},totalNum=onlineNum + {$robotCount} WHERE roomId={$roomId} ";
            }else{
                $robotCount = $totalCount;
                $sql = "UPDATE Micro\Models\Rooms SET robotNum={$robotCount},totalNum=onlineNum + {$robotCount} WHERE roomId={$roomId} ";
            }
            $query = $this->modelsManager->createQuery($sql);
            $query->execute();

            /*$roomData = Rooms::find("roomId = " . $roomId);
            if ($roomData->valid()) {
                $roomData->robotNum = $robotCount;
                $robotNum->save();
            }
            else {
                $this->errLog('updateRobotCount room not exist roomId = ' . $roomId);
            }*/
            return $this->status->retFromFramework($this->status->getCode("OK"));
        } catch (Exception $e) {
            $this->errLog('updateRobotCount errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $result);
        }
    }

    /**
     * 获得关注的推荐列表
     */
    public function getRecommFocusList(){
        try {
            $user = $this->userAuth->getUser();
            if($user){
                $uid = $user->getUid();
            }else{
                $uid = 0;
            }

            $defCount = 0;
            $sql = "SELECT uid FROM \Micro\Models\Rooms WHERE liveStatus=1 AND showStatus=1 AND uid !={$uid} ORDER BY totalNum DESC LIMIT 6";
            $query = $this->modelsManager->createQuery($sql);
            $data = $query->execute();
//            if (!$data->valid()) {
//                return $this->status->retFromFramework($this->status->getCode("NO_PUBLISHED_ROOM"));
//            }

            $data = $data->toArray();
            if(count($data) < 6){
                $defCount = 6 - count($data);
            }

            if($defCount > 0){
                $sql = "SELECT uid FROM pre_user_profiles where uid !={$uid} AND  uid not in(SELECT uid FROM pre_rooms WHERE liveStatus=1 AND showStatus=1) ORDER BY exp4 DESC LIMIT $defCount";
                $connection = $this->di->get('db');
                $fansData = $connection->fetchAll($sql);
                if($fansData){
                    foreach($fansData as $val){
                        $data[] = $val;
                    }
                }
            }

            // 获得用户头像昵称
            if($data){
                foreach($data as &$val){
                    $uid = $val['uid'];
                    $roomInfo = $this->getRoomInfo(NULL, $uid);
                    $user = UserFactory::getInstance($uid);
                    $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
                    $val['nickName'] = isset($userBaseInfo['nickName']) ? $userBaseInfo['nickName'] : '';
                    $val['avatar'] = isset($userBaseInfo['avatar']) ? $userBaseInfo['avatar'] : '';
                    $val['roomId'] = isset($roomInfo->roomId) ? $roomInfo->roomId : 0;
                }
            }

            return $this->status->retFromFramework($this->status->getCode("OK"), $data);
        } catch (Exception $e) {
            $this->errLog('getHotRoom errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $data);
        }
    }

    // 举报接口
    public function addInform($type = 'pc'){
        if($this->request->isPost()){
            $user = $this->userAuth->getUser();
            if(empty($user)){
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $dataArray['uid'] = $user->getUid();
            $dataArray['targetId'] = $this->request->getPost('targetId');
            $timePre = date('YmdHis',time());
            $dirName = $timePre . "_" . $dataArray['uid'];
            $picRes = $this->uploadSuggestionsPic($timePre, $type, $kind = 'inform');
            if($picRes['code'] == $this->status->getCode('OK')){
                $picRes = $picRes['data'];
            }else{
                $picRes = array();
            }

            for($i = 0; $i <= 2; $i++){
                if(isset($picRes[$i])){
                    $dataArray['pic' . ($i + 1)] = $picRes[$i];
                }else{
                    $dataArray['pic' . ($i + 1)] = '';
                }
            }

            $dataArray['type'] = $this->request->getPost("type");
            $dataArray['content'] = $this->request->getPost("content");
            if($dataArray['content']){
                $this->saveLog($dirName, '举报内容：'.$dataArray['content'], 'content.txt', $timePre, $type, $kind = 'inform');
            }

            $InvBaseClass = new \Micro\Frameworks\Logic\Investigator\InvBase();
            $result = $InvBaseClass->addInform($dataArray);
            return $this->status->retFromFramework($this->status->getCode('OK'), $dirName);
        }

        return $this->proxyError();
    }

    // 储存聊天日志
    public function addChatLog($streamName = '_', $chatData = ''){
        try {
            $chatData = urldecode($chatData);
            $date = date('Ymd',time());
            $filePath = $this->config->websiteinfo->chatdatapath;//$this->pathGenerator->getChatDataPath($date);
            $fileName = $streamName . '.txt';
            $this->di->get('storageCdn')->write($filePath . $fileName, $chatData, TRUE);
            return true;
        } catch (\Exception $e) {
            $this->errLog('addChatLog : errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    //意见反馈接口【新增】
    public function saveSuggestion($sugType = 'pc'){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if(empty($user)){
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $dataArray['uid'] = $user->getUid();
            $timePre = date('YmdHis',time());
            $dirName = $timePre . "_" . $dataArray['uid'];
            $picRes = $this->uploadSuggestionsPic($timePre, $sugType);
            if($picRes['code'] == $this->status->getCode('OK')){
                $picRes = $picRes['data'];
            }else{
                $picRes = array();
            }

            for($i = 0; $i <= 2; $i++){
                if(isset($picRes[$i])){
                    $dataArray['pic' . ($i + 1)] = $picRes[$i];
                }else{
                    $dataArray['pic' . ($i + 1)] = '';
                }
            }

            $logdata =  urldecode($this->request->getPost("log"));
            if($logdata){
                $dataArray['log'] = $this->saveLog($dirName, $logdata, 'log.txt', $timePre, $sugType, $kind = 'sug');
            }else{
                $dataArray['log'] = '';
            }

            $dataArray['type'] = $this->request->getPost("type");
            $dataArray['content'] = $this->request->getPost("content");
            if($dataArray['content']){
                $this->saveLog($dirName, '反馈内容：'.$dataArray['content'], 'content.txt', $timePre, $sugType, $kind = 'sug');
            }

            $dataArray['mobile'] = $this->request->getPost("mobile");
            $dataArray['email'] = $this->request->getPost("email");
            $dataArray['qq'] = $this->request->getPost("qq");
            $dataArray['devInfo'] = $this->request->getPost("devInfo");
            $InvBaseClass = new \Micro\Frameworks\Logic\Investigator\InvBase();
            $result = $InvBaseClass->addSuggestion($dataArray);
            return $this->status->retFromFramework($this->status->getCode('OK'), $dirName);
        }

        return $this->proxyError();
    }

    public function uploadSuggestionsPic($timePre = '', $sugType = 'pc', $kind = 'sug'){
        if ($this->request->hasFiles()) {
            // 自身业务的验证
            $userdata = $this->session->get($this->config->websiteinfo->authkey);
            $uid = $userdata['uid'];
            $dirName = $this->request->getPost("dirName");
            try {
//                $timePre = date('YmdHis',time());
                foreach ($this->request->getUploadedFiles() as $key => $file) {
                    $fileNameArray = explode('.', strtolower($file->getName()));
                    $fileExt = $fileNameArray[count($fileNameArray) - 1];
                    $dirName = $timePre . "_".$uid;

                    $filePath = $this->pathGenerator->getSuggestionsPath($dirName, $sugType, $kind);
                    $fileName = $timePre .  "_pic{$key}." . $fileExt;
                    $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                    try {
                        $avatar[] = $this->pathGenerator->getFullSuggestionsPath($dirName, $fileName, $sugType, $kind);
                    } catch (\Exception $e) {
                        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                    }
                }

                return $this->status->retFromFramework($this->status->getCode('OK'),$avatar);
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
            }
        } else {
            return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
        }
    }

    public function saveLog($dirName, $logData, $type = 'log.txt', $timePre, $sugType = 'pc', $kind = 'sug'){
        $user = $this->userAuth->getUser();
        $uid = $user->getUid();
        if(!$dirName){
            $dirName = $timePre . '_' . $uid;
        }

        $filePath = $this->pathGenerator->getSuggestionsPath($dirName, $sugType, $kind);
        $fileName = $timePre . '_' . $type;
        $this->storage->write($filePath . $fileName, $logData, TRUE);
        try {
            $avatar = $this->pathGenerator->getFullSuggestionsPath($dirName, $fileName, $sugType, $kind);
            return $avatar;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 设置设备信息
     *
     * @param $data
     * @return mixed
     */
    public function setDeviceInfoSession($data){
        $platform = isset($data['platform']) ? $data['platform'] : '';
        $deviceid = isset($data['deviceid']) ? $data['deviceid'] : '';
        $devicetoken = isset($data['devicetoken']) ? $data['devicetoken'] : '';
        $clientID = isset($data['clientID']) ? $data['clientID'] : '';
        $user = $this->userAuth->getUser();
        if($user){
            $uid = $user->getUid();
        }else{
            $uid = 0;
        }

        $postData['platform'] = $platform;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            switch ($platform) {
                case 1:
                    // pc

                    break;
                case 2:
//                        break;
                case 3:
                    // andriod，只存clientID,先判断是否有记录，否则插入
                    $devInfo = DeviceInfo::findFirst("platform={$platform} and clientID='{$clientID}'");
                    if ($devInfo) {
                        // 更新最后时间
                        if ($deviceid) {
                            $devInfo->deviceid = $deviceid;
                        }

                        if($devicetoken){
                            $devInfo->devicetoken = $devicetoken;
                        }

                        if ($uid > 0) {
                            $devInfo->uid = $uid;
                            $devInfo->pushUid = $uid;
                        }

                        $devInfo->lasttime = time();
                        $res = $devInfo->save();
                        if(empty($res)){
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                        }

                    } else {
                        $devInfo = new DeviceInfo();
                        $devInfo->deviceid = $deviceid;
                        $devInfo->platform = $platform;
                        $devInfo->devicetoken = $devicetoken;
                        $devInfo->clientID = $clientID;
                        $devInfo->uid = $uid;
                        $devInfo->pushUid = $uid;
                        $devInfo->lasttime = time();
                        $devInfo->pushTime = 0;
                        $devInfo->save();
                        $id = $devInfo->id;
                        if(empty($id)){
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                        }
                    }

                    break;
                default:
                    return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
                    break;
            }
        }catch (\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    public function setDeviceInfo(){
        if($this->request->isPost()) {
            $platform = intval($this->request->getPost('platform'));
            $deviceid = $this->request->getPost('deviceid');
            $devicetoken = $this->request->getPost('devicetoken');
            $clientID = $this->request->getPost('clientID');
            $data = array(
                'platform' => $platform,
                'deviceid' => $deviceid,
                'devicetoken' => $devicetoken,
                'clientID'  => $clientID,
            );

            $this->session->set($this->config->websiteinfo->mobileauthkey, $data);
            $user = $this->userAuth->getUser();
            if($user){
                $this->setDeviceInfoSession($data);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }

        return $this->status->retFromFramework($this->status->getCode('URI_ERROR'));
    }

    /**
     * 退出登录前清空设备uid
     * @return bool
     */
    public function clearUsserDevice(){
        $user = $this->userAuth->getUser();
        if(empty($user)){
            return FALSE;
        }

        $uid = $user->getUid();
        $phql = "update Micro\Models\DeviceInfo set uid=0 where uid=?0";
        $this->modelsManager->executeQuery($phql,
            array(
                0   => $uid,
            )
        );
        
       // 更新token信息 
        $phql = "update Micro\Models\MobileToken set expireTime=0 where uid=" . $uid;
        $this->modelsManager->executeQuery($phql);

        return TRUE;
    }

    /**
     * 根据设备类型和uid列表，获得对应的token,安卓的为clientID
     *
     * @param $platform
     * @param array $uidList
     */
    public function getTokenByUid($platform, $uidList = array(), $field = 'devicetoken', $loginPush = 0){

        if(empty($uidList)){
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $resData = array();
        $whereKey = $loginPush ? " pushUid " : " uid ";
        switch($platform){
            case $this->config->pushservice->type->pc:

                break;
            case $this->config->pushservice->type->ios:
                $sql = "SELECT {$field} FROM (SELECT * FROM pre_device_info ORDER BY lasttime DESC) as a  WHERE platform=2 AND " . $whereKey . " in(" . implode(',', $uidList) . ") GROUP BY uid ";
                break;
            case $this->config->pushservice->type->android:
                $sql = "SELECT {$field} as devicetoken FROM (SELECT * FROM pre_device_info  ORDER BY lasttime DESC) as a WHERE platform=3 AND " . $whereKey . " in(" . implode(',', $uidList) . ") GROUP BY uid";
                break;
            default:
                return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
                break;
        }

        $connection = $this->di->get('db');
        $result = $connection->fetchAll($sql);
        if($result){
            foreach($result as $val){
                $resData[] = $val['devicetoken'];
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $resData);
    }

    /**
     * 当前用户开播，推送粉丝
     */
    public function pushToUsersByRoomPublish($uid){
        $arrayResult = $this->userMgr->getUserFansUidList($uid);
        $user = UserFactory::getInstance($uid);
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $userRes = $user->getUserInfoObject()->getUserInfo();
        $userData['nickName']=$userRes['nickName'];
        $userData['avatar']=$userRes['avatar'];
        $userData['uid']=$userRes['uid'];
       // $nickName = $user->getUserInfoObject()->getNickName();
//        $title = 'test';
        $message = array(
            'action' => 'startPublish',
            'uid' => $uid,
            'time' => time()
        );

        $content = "你关注的主播{$userRes['nickName']}开播啦！";
        $uidsList = $arrayResult['code'] == $this->status->getCode('OK') ? $arrayResult['data'] : array();
        if($uidsList){
            $this->pushMgr->sendMessage($uidsList, $message, $content);
//            // 获得ios推送列表
//            $tokenListRes = $this->getTokenByUid($this->config->pushservice->type->ios, $uidsList, 'devicetoken');
//            if($tokenListRes['code'] == $this->status->getCode('OK') && !empty($tokenListRes['data'])){
//                 $this->pushserver->pushAPNMessageToList($tokenListRes['data'], json_encode($message), $content);
//            }
//
//
//            // 获得安卓推送列表
//            $tokenListRes = $this->getTokenByUid($this->config->pushservice->type->android, $uidsList, 'clientID');
//            if($tokenListRes['code'] == $this->status->getCode('OK') && !empty($tokenListRes['data'])){
//                $message['content'] = $content;
//                $this->pushserver->pushMessageToList($tokenListRes['data'], json_encode($message));
//            }
//
            $ArraySubData['controltype'] = "anchorPublish";
            $broadData['userData'] = $userData;
            $ArraySubData['data'] = $broadData;
            // 网页单点广播
            foreach($uidsList as $uid){
                $user = UserFactory::getInstance($uid);
                $accountId = $user->getUserInfoObject()->getAccountId();
                $roomList = $this->getUsersWhereIn($uid);
                if($roomList){
                    foreach($roomList as $roomVal){
                        $this->comm->roomNotify($roomVal['roomid'], $accountId, $ArraySubData);
                    }
                }
            }
        }

        return $this->status->retFromFramework($this->status->getCode("OK"));
    }

    public function getSongList($uid){
        if($uid > 0){
            $user = UserFactory::getInstance($uid);
        }else{
            $user = $this->userAuth->getUser();
        }

        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        $uid = $user->getUid();
        $data = SongList::find(
            array(
                'conditions' => "uid={$uid} and status=1",
                'columns' => 'id,name'
            )
        )->toArray();

        return $this->status->retFromFramework($this->status->getCode("OK"), $data ? $data : array());
    }

    public function addSong($name){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $postData['content'] = $name;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $uid = $user->getUid();
        $res = SongList::findFirst("uid={$uid} and status=1 and name='{$name}'");
        if($res){
            return $this->status->retFromFramework($this->status->getCode('SONG_HAS_EXISTS'));
        }

        $model = new SongList();
        $model->name = $name;
        $model->uid = $uid;
        $model->addtime = time();
        $model->status = 1;
        $ret = $model->save();
        if($ret){
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }else{
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    public function delSong($id){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $uid = $user->getUid();
        $robot = SongList::findFirst("uid={$uid} and status=1 and id={$id}");
        if(!$robot){
            return $this->status->retFromFramework($this->status->getCode('SONG_HAS_NOT_EXISTS'));
        }else{
            if ($robot->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
        }

        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    public function getRoomAnnouncement(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        $data = Rooms::findFirst(
            array(
                'conditions' => "uid={$uid}",
                'columns' => 'announcement'
            )
        )->toArray();

        return $this->status->retFromFramework($this->status->getCode('OK'), $data ? $data : array());
    }

    public function getRoomTitle(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        $data = Rooms::findFirst(
            array(
                'conditions' => "uid={$uid}",
                'columns' => 'title'
            )
        )->toArray();

        return $this->status->retFromFramework($this->status->getCode('OK'), $data ? $data : array());
    }

    public function roomType($uid){
        $postData['uid'] = $uid;
        $result = array();
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $data = Rooms::findFirst("uid={$uid} AND roomType={$this->config->roomType->family}");
        if(empty($data)){
            $result['isFamily'] = 0;
        }else{
            $result['isFamily'] = 1;
        }

        // 判断是不是家族长
//        $result['isCreator'] = $this->isOwnFamilyRoom($uid);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function setPublishPos($fromPos = 0, $toPos = 0, $fromUid = 0, $toUid = 0) {
        if($fromUid > 0){
            $postData['uid'] = $fromUid;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
        }

        if($toUid > 0){
            $postData['uid'] = $toUid;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
        }

        $config = $this->config->familyPos;
        if(!in_array($toPos, $config) || !in_array($fromPos, $config) || (empty($fromUid) && empty($toUid))){
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }

        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        // 判断是不是家族长
        if(!$this->isOwnFamilyRoom($uid)){
            return $this->status->retFromFramework($this->status->getCode('NO_FAMILY_CREATOR'));
        }

        // 获得房间id
        $roomInfo = Rooms::findFirst("uid={$uid} AND roomType={$this->config->roomType->family}");
        if(empty($roomInfo)){
            return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }

        // 判断被操作人是否属于家族
        if($fromUid > 0){
            $result = $this->familyMgr->getFamilyInfoByUid($fromUid);
            if($result['code'] == $this->status->getCode('OK')){
                if($result['data']['creatorUid'] != $uid){
                    return $this->status->retFromFramework($this->status->getCode('NO_ANCHOR_FAMILY'));
                }
            }else{
                return $this->status->retFromFramework($this->status->getCode('NO_ANCHOR_FAMILY'));
            }

            $signAnchor = SignAnchor::findFirst('uid = '.$fromUid.' and familyId > 0');
            if(empty($signAnchor)){
                return $this->status->retFromFramework($this->status->getCode('NO_HAS_FAMILY'));
            }
        }

        if($toUid > 0){
            $result = $this->familyMgr->getFamilyInfoByUid($toUid);
            if($result['code'] == $this->status->getCode('OK')){
                if($result['data']['creatorUid'] != $uid){
                    return $this->status->retFromFramework($this->status->getCode('NO_ANCHOR_FAMILY'));
                }
            }else{
                return $this->status->retFromFramework($this->status->getCode('NO_ANCHOR_FAMILY'));
            }

            $signAnchor = SignAnchor::findFirst('uid = '.$toUid.' and familyId > 0');
            if(empty($signAnchor)){
                return $this->status->retFromFramework($this->status->getCode('NO_HAS_FAMILY'));
            }
        }

        // 获得家族id
        $familyId = $signAnchor->familyId;
        if($fromPos > 0 && $toPos > 0){
            // 交换位置
            $ret1 = $this->upMicrophone($familyId, $toPos, $fromUid);
            $ret2 = $this->upMicrophone($familyId, $fromPos, $toUid);
            if(!$ret1 || !$ret2){
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            }
        }else{
            if($fromPos > 0){
                // 下麦
                $ret = $this->upMicrophone($familyId, $fromPos, 0);
                if(!$ret){
                    return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                }
            }

            if($toPos > 0){
                // 上麦
                $ret = $this->upMicrophone($familyId, $toPos, $fromUid);
                if(!$ret){
                    return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                }
            }
        }

        $broadData['controltype'] = "exchangeFamilyPos";
        $userDataArray['fromUid'] = $fromUid;
        $userDataArray['fromPos'] = $fromPos;
        $userDataArray['toUid'] = $toUid;
        $userDataArray['toPos'] = $toPos;
        $broadData['data'] = $userDataArray;
        $this->comm->roomBroadcast($roomInfo->roomId, $broadData);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /**
     * 设置麦位
     *
     * @param $familyId
     * @param $pos
     * @param $uid
     */
    public function upMicrophone($familyId, $pos, $uid){
        // 判断是否有该位置了
        $posInfo = FamilyRoom::findFirst("familyId={$familyId} AND pos={$pos}");
        if($posInfo){
            // 更新数据
            $posInfo->uid = $uid;
            $posInfo->lasttime = time();
        }else{
            $posInfo = new FamilyRoom();
            $posInfo->familyId = $familyId;
            $posInfo->pos = $pos;
            $posInfo->uid = $uid;
            $posInfo->lasttime = time();
        }

        $ret = $posInfo->save();
        return $ret;
    }

    // 查询当日累计充值记录
    public function getDayChargeNum($uid = 0){
        $startTime = strtotime(date('Y-m-d'));
        $endTime = $startTime + 86399;
        $ttlFee = \Micro\Models\Order::sum(
            array(
                'column' => 'totalFee',
                'conditions' => 'uid = ' . $uid . ' and status = 1 and payTime >= ' . $startTime . ' and payTime <= ' . $endTime
            )
        );

        $extraNum = $ttlFee ? floor($ttlFee / $this->config->rewardCashConfig->rate) : 0;

        return $extraNum > 10 ? 10 : $extraNum;
    }

    // 获取宝箱最大领取次数
    public function getMaxNum($isMobile = 0){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return 0;
        }
        $uid = $user->getUid();
        $vipInfo = $user->getUserInfoObject()->getVipInfo();
        $maxNum = 5;
        //普通vip+1
        $maxNum += $vipInfo['vip1'] == 1 ? 1 : 0;
        //普通vip+2
        $maxNum += $vipInfo['vip2'] == 1 ? 2 : 0;
        // $maxNum = $vipLevel ? ($vipLevel == 2 ? 9 : 7) : 5;

        // 判断是否手机
        $maxNum += $isMobile ? 2 : 0;

        // 判断充值记录
        $chargeExtraNum = $this->getDayChargeNum($uid);
        $maxNum += $chargeExtraNum;

        return $maxNum;
    }

    // 添加宝箱可领取日志
    public function addRewardLog($isMobile = 0){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            // 判断vip
            $canOpenNum = $this->getMaxNum($isMobile);

            $startTime = strtotime(date('Y-m-d'));
            $endTime = $startTime + 86399;
            $count = \Micro\Models\RewardLog::count('uid = ' . $uid . ' and addTime >= ' . $startTime . ' and addTime < ' . $endTime . ' and status = 1');

            //  判断是否有足够的宝箱开启次数
            if($count >= $canOpenNum){
                return $this->status->retFromFramework($this->status->getCode('OK'),array('times'=>$count,'canOpenNum'=>$canOpenNum));
            }

            // 判断开启时间是否足够五分钟
            $lastData = \Micro\Models\RewardLog::findFirst('uid = ' . $uid . ' and addTime >= ' . $startTime . ' and addTime < ' . $endTime . ' order by addTime desc');
            $addTime = time();
            $getRewardTime = $this->config->rewardConfig->getCoinTime;
            if(empty($lastData) && ($addTime - $startTime < $getRewardTime)){//第二日第一次领取时判断是否大于00:05:00
                $leftTime = $getRewardTime + $startTime - $addTime;
                return $this->status->retFromFramework($this->status->getCode('TIME_IS_NOT_COMING'),array('leftTime'=>$leftTime));
            }
            if($lastData && ($addTime - $lastData->addTime < $getRewardTime)){//非第一次  判断上次间隔超过5分钟
                $leftTime = $getRewardTime + $lastData->addTime - $addTime;
                return $this->status->retFromFramework($this->status->getCode('TIME_IS_NOT_COMING'),array('leftTime'=>$leftTime));
            }

            if($lastData && $lastData->status == 0){//判断是否存在未开启宝箱
                // return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }

            // 添加数据
            $rewardLog = new \Micro\Models\RewardLog();
            $rewardLog->uid = $uid;
            $rewardLog->num = $count + 1;
            $rewardLog->addTime = $addTime;
            $rewardLog->status = 0;
            $normalLib = $this->di->get('normalLib');
            $rewardLog->rewardId = $normalLib->getRewardIdRandom();

            if($rewardLog->save()){
                return $this->status->retFromFramework($this->status->getCode('OK'),array('times'=>$count + 1,'canOpenNum'=>$canOpenNum));
            }else{
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 开启宝箱
    public function openReward($isMobile = 0){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $canOpenNum = $this->getMaxNum($isMobile);

            $startTime = strtotime(date('Y-m-d'));
            $endTime = $startTime + 86399;

            $count = \Micro\Models\RewardLog::count('uid = ' . $uid . ' and addTime >= ' . $startTime . ' and addTime < ' . $endTime . ' and status = 1');

            //  判断是否有足够的宝箱开启次数
            if($count >= $canOpenNum){
                return $this->status->retFromFramework($this->status->getCode('OK'),array('times'=>$count,'canOpenNum'=>$canOpenNum));
            }

            $nowDate = strtotime(date('Y-m-d'));
            $rewardData = \Micro\Models\RewardLog::findFirst('uid = ' . $uid . ' and addTime >= ' . $startTime . ' and addTime < ' . $endTime . ' order by addTime desc');
            if(empty($rewardData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            if($rewardData->status != 0){
                return $this->status->retFromFramework($this->status->getCode('REWARD_IS_OPENED'),array('times' => $rewardData->num));
            }

            $returnData = array();

            switch ($rewardData->rewardId) {//type:1-聊豆2-礼物3-道具4-座驾
                case $this->config->liveBagConfig->ld->id://聊豆
                    $returnData = $this->config->liveBagConfig->ld->data;
                    $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
                    $userCash->sendUserCoin($returnData->num, $uid);
                    $returnData['type'] = 1;
                    break;

                case $this->config->liveBagConfig->cm->id://草莓
                    $returnData = $this->config->liveBagConfig->cm->data;
                    $item = $user->getUserItemsObject();
                    $item->giveGift($returnData->itemId, $returnData->num);
                    $returnData['type'] = 2;
                    break;

                case $this->config->liveBagConfig->ttq->id://甜甜圈
                    $returnData = $this->config->liveBagConfig->ttq->data;
                    $item = $user->getUserItemsObject();
                    $item->giveGift($returnData->itemId, $returnData->num);
                    $returnData['type'] = 2;
                    break;

                case $this->config->liveBagConfig->bsyjx->id://白色郁金香
                    $returnData = $this->config->liveBagConfig->bsyjx->data;
                    $item = $user->getUserItemsObject();
                    $item->giveGift($returnData->itemId, $returnData->num);
                    $returnData['type'] = 2;
                    break;

                case $this->config->liveBagConfig->ylbk->id://银喇叭卡
                    $returnData = $this->config->liveBagConfig->ylbk->data;
                    $item = $user->getUserItemsObject();
                    $item->giveItem($returnData->itemId, $returnData->num, 0);
                    $returnData['type'] = 3;
                    break;

                case $this->config->liveBagConfig->jlbk->id://金喇叭卡
                    $returnData = $this->config->liveBagConfig->jlbk->data;
                    $item = $user->getUserItemsObject();
                    $item->giveItem($returnData->itemId, $returnData->num, 0);
                    $returnData['type'] = 3;
                    break;

                case $this->config->liveBagConfig->black_bear->id://黑熊座驾
                    $returnData = $this->config->liveBagConfig->black_bear->data;
                    $item = $user->getUserItemsObject();
                    $item->giveCar($returnData->itemId, 172800);
                    $returnData['type'] = 4;
                    break;
                default:
                    break;
            }

            $returnData['times'] = $rewardData->num;
            $returnData['canOpenNum'] = $canOpenNum;

            // 修改状态
            $rewardData->status = 1;
            $rewardData->save();

            return $this->status->retFromFramework($this->status->getCode('OK'), $returnData);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 检查是否存在未领取宝箱
    public function checkReward(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $startTime = strtotime(date('Y-m-d'));
            $endTime = $startTime + 86399;
            $count = \Micro\Models\RewardLog::count('uid = ' . $uid . ' and addTime >= ' . $startTime . ' and addTime < ' . $endTime . ' and status = 1');
            $times = $count ? $count : 0;
            $maxNum = $this->di->get('normalLib')->getBasicConfigs('maxNum');
            if(!$maxNum){
                $maxNum = 20;
            }

            return $this->status->retFromFramework($this->status->getCode('OK'),array('times'=>$times,'maxNum'=>$maxNum));
        } catch (Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 查询房间用户列表所有
    public function getRoomUsers($nodejstoken, $roomid, $count){
        try {

            //读取缓存
            $normalLib = $this->di->get('normalLib');
            $cacheKey = 'room_users_' . $roomid;
            $cacheResult = $normalLib->getCache($cacheKey);
            if ($cacheResult) {
                $userList = array_slice($cacheResult['userList'], 0, $count);
                return $this->status->retFromFramework(
                    $this->status->getCode('OK'), 
                    array('userList'=>$userList,'visitors'=>$cacheResult['visitors'])
                );
            }

            $userListNum = $normalLib->getBasicConfigs('userListNum');

            $result = $this->di->get('comm')->collectMembers($nodejstoken, $roomid, $userListNum);
            if ($result === false) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
            }
            $errorCode = $result['code'];
            if ($errorCode != 0) {
                return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
            }

            // 获取主播id
            $res = \Micro\Models\Rooms::findFirst('roomId = ' . $roomid);
            $anchorId = !empty($res) ? $res->uid : 0;

            $users = $result['data'];
            $data = array();
            $sort = array();
            $visitors = 0;
            if($this->config->robotVersion == '0.0.2'){
                $visitors = $result['visitors'];
            }
            if(!empty($users)){
                $existUids = array();
                foreach ($users as $k => $v) {
                    if(in_array($v['uid'], $existUids)){
                        $this->errLog('getRoomUsers---RoomId:' . $roomid . ',Repeat Uid Error : ' . $v['uid']);
                        continue;
                    }
                    $existUids[] = $v['uid'];
                    $num = 0;
                    if($v['uid'] == $anchorId){//主播
                        $num = 10000;
                        $userdata = json_decode($v['userdata'], true);
                        $userdata['nickName'] = $userdata['name'];
                    }else{
                        $userdata = json_decode($v['userdata'], true);
                        if(isset($userdata['manageType']) && $userdata['manageType'] == 1){
                            $num = 9000;
                            $userdata['nickName'] = $userdata['name'];
                        }else if(isset($userdata['isFamilyLeader']) && $userdata['isFamilyLeader'] == 1){
                            $num = 8000;
                            $userdata['nickName'] = $userdata['name'];
                        }else if($v['level'] == 2 || $v['level'] == 3){
                            $num = 1000 * ($v['level'] == 2 ? 2 : 1);
                            $num += intval($userdata['vipLevel']) * 100;
                            $num += intval($userdata['richerLevel']);//富豪等级
                            $userdata['nickName'] = $userdata['name'];
                        }else if($v['level'] == 1){
                            $num = 100;
                            $num += intval($userdata['vipLevel']) * 100;
                            $num += intval($userdata['richerLevel']);
                            $userdata['nickName'] = $userdata['name'];
                        }else if($v['level'] == 0){
                            $visitors++;
                            continue;
                        }
                    }/*else if($v['level'] == 2 || $v['level'] == 3){//管理员
                        $num = 1000 * ($v['level'] == 2 ? 2 : 1);
                        $userdata = json_decode($v['userdata'], true);
                        $num += $userdata['manageType'] == 1 ? 2000 : 0;
                        $num += $userdata['isFamilyLeader'] == 1 ? 1000 : 0;
                        $num += intval($userdata['vipLevel']) * 100;
                        $num += intval($userdata['richerLevel']);//富豪等级
                    }else if($v['level'] == 1){//普通用户
                        $num = 100;
                        $userdata = json_decode($v['userdata'], true);
                        $num += intval($userdata['vipLevel']) * 100;
                        $num += intval($userdata['richerLevel']);
                    }else if($v['level'] == 0){
                        $visitors++;
                        continue;
                    }*/
                    
                    if(isset($userdata)){
                        $tmp = $v;
                        $tmp['userdata'] = json_encode($userdata);
                        unset($userdata);
                    }
                    
                    $sort[] = $num;
                    $data[] = $tmp;
                    unset($tmp);
                }
                array_multisort($sort, SORT_DESC, $data);
            }
            $cacheData = array('userList'=>$data,'visitors'=>$visitors);
            $userList = array_slice($data, 0, $count);
            //设置缓存
            $liftTime = 5; //有效期10秒
            $normalLib->setCache($cacheKey, $cacheData, $liftTime);

            return $this->status->retFromFramework($this->status->getCode('OK'), array('visitors'=>$visitors,'userList'=>$userList));
        } catch (Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 查询房间用户列表管理员
    public function getRoomManagers($nodejstoken, $roomid){
        try {
            //读取缓存
            $normalLib = $this->di->get('normalLib');
            $cacheKey = 'room_managers_' . $roomid;
            $cacheResult = $normalLib->getCache($cacheKey);
            if ($cacheResult) {
                return $this->status->retFromFramework($this->status->getCode('OK'), $cacheResult);
            }

            $result = $this->di->get('comm')->collectAdmins($nodejstoken, $roomid);
            if ($result === false) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_CONNECT_CHATSERVER'));
            }
            $errorCode = $result['code'];
            if ($errorCode != 0) {
                return $this->status->retFromFramework($this->status->getCode('CHATSERVER_RETURN_ERROR'), $this->status->genCharServerError($result));
            }
            // 获取主播id
            $res = \Micro\Models\Rooms::findFirst('roomId = ' . $roomid);
            $anchorId = !empty($res) ? $res->uid : 0;

            $users = $result['data'];
            $data = array();
            $sort = array();
            if(!empty($users)){
                $existUids = array();
                foreach ($users as $k => $v) {
                    if(in_array($v['uid'], $existUids)){
                        $this->errLog('getRoomManagers---RoomId:' . $roomid . ',Repeat Uid Error : ' . $v['uid']);
                        continue;
                    }
                    $existUids[] = $v['uid'];
                    $num = 0;
                    if($v['uid'] == $anchorId){//主播
                        $num = 10000;
                        $userdata = json_decode($v['userdata'], true);
                        $userdata['nickName'] = $userdata['name'];
                    }else{
                        $userdata = json_decode($v['userdata'], true);
                        if(isset($userdata['manageType']) && $userdata['manageType'] == 1){
                            $num = 9000;
                            $userdata['nickName'] = $userdata['name'];
                        }else if(isset($userdata['isFamilyLeader']) && $userdata['isFamilyLeader'] == 1){
                            $num = 8000;
                            $userdata['nickName'] = $userdata['name'];
                        }else if($v['level'] == 2 || $v['level'] == 3){
                            $num = 1000 * ($v['level'] == 2 ? 2 : 1);
                            $num += intval($userdata['vipLevel']) * 100;
                            $num += intval($userdata['richerLevel']);//富豪等级
                            $userdata['nickName'] = $userdata['name'];
                        }
                        /*if($v['level'] == 2 || $v['level'] == 3){//管理员
                            $num = 1000;
                            $userdata = json_decode($v['userdata'], true);
                            $num += $userdata['manageType'] == 1 ? 1000 : 0;
                            $num += intval($userdata['vipLevel']) * 100;//vip
                            $num += intval($userdata['richerLevel']);//富豪等级
                        }*/
                    }
                    if(isset($userdata)){
                        $tmp = $v;
                        $tmp['userdata'] = json_encode($userdata);
                        unset($userdata);
                    }
                    $sort[] = $num;
                    $data[] = $tmp;
                    unset($tmp);
                }
                array_multisort($sort, SORT_DESC, $data);
            }
            //设置缓存
            $liftTime = 5; //有效期10秒
            $normalLib->setCache($cacheKey, $data, $liftTime);

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 开宝箱New
    public function openRewardBox($isMobile = 0){
        if (time() > $this->config->rewardBox->endTime) {//活动已结束
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }

        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $canOpenNum = $this->getMaxNum($isMobile);

            $nowTime = time();
            $startTime = strtotime(date('Y-m-d'));
            $endTime = $startTime + 86399;

            $count = \Micro\Models\RewardLog::count('uid = ' . $uid . ' and addTime >= ' . $startTime . ' and addTime < ' . $endTime . ' and status = 1');

            //  判断是否有足够的宝箱开启次数
            if($count >= $canOpenNum){
                return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_NUM_LEFT'),array('times'=>$count,'canOpenNum'=>$canOpenNum));
            }

            // 判断开启时间是否足够五分钟
            $lastData = \Micro\Models\RewardLog::findFirst('uid = ' . $uid . ' and addTime >= ' . $startTime . ' and addTime < ' . $endTime . ' order by addTime desc');
            $getRewardTime = $this->di->get('normalLib')->getBasicConfigs('getCoinTime');
            if(!$getRewardTime){
                $getRewardTime = 300;
            }
            if($lastData && ($nowTime - $lastData->addTime < $getRewardTime)){//判断上次间隔超过5分钟
                $leftTime = $getRewardTime + $lastData->addTime - $nowTime;
                return $this->status->retFromFramework($this->status->getCode('TIME_IS_NOT_COMING'),array('times'=>$count,'leftTime'=>$leftTime));
            }

            // 开启宝箱处理
            $rewardLog = new \Micro\Models\RewardLog();
            $rewardLog->uid = $uid;
            $rewardLog->num = $count + 1;
            $rewardLog->addTime = $nowTime;
            $rewardLog->status = 1;
            $normalLib = $this->di->get('normalLib');
            $rewardId = $normalLib->getRewardIdRandom();
            $rewardLog->rewardId = $rewardId;

            $returnData = array();
            switch ($rewardId) {//type:1-聊豆2-礼物3-道具4-座驾5-聊币
                case $this->config->liveBagConfig->ld->id://聊豆
                    $returnData = $this->config->liveBagConfig->ld->data;
                    $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
                    $userCash->sendUserCoin($returnData->num, $uid);
                    $returnData['type'] = 1;
                    $userInfo = \Micro\Models\UserProfiles::findFirst("uid=" . $uid);
                    $returnData['coin'] = !empty($userInfo) ? $userInfo->coin : 0;
                    $returnData['cash'] = !empty($userInfo) ? $userInfo->cash : 0;
                    break;

                case $this->config->liveBagConfig->lb->id://聊币
                    $returnData = $this->config->liveBagConfig->lb->data;
                    $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
                    $res = $userCash->addUserCash($returnData->num, $uid);
                    if($res){
                        $userCash->addCashLog($returnData->num, $this->config->cashSource->rewardBox, $rewardId, $uid);
                    }
                    $returnData['type'] = 5;
                    $userInfo = \Micro\Models\UserProfiles::findFirst("uid=" . $uid);
                    $returnData['coin'] = !empty($userInfo) ? $userInfo->coin : 0;
                    $returnData['cash'] = !empty($userInfo) ? $userInfo->cash : 0;
                    break;

                case $this->config->liveBagConfig->cm->id://草莓
                    $returnData = $this->config->liveBagConfig->cm->data;
                    $item = $user->getUserItemsObject();
                    $item->giveGift($returnData->itemId, $returnData->num);
                    $returnData['type'] = 2;
                    break;

                case $this->config->liveBagConfig->ttq->id://甜甜圈
                    $returnData = $this->config->liveBagConfig->ttq->data;
                    $item = $user->getUserItemsObject();
                    $item->giveGift($returnData->itemId, $returnData->num);
                    $returnData['type'] = 2;
                    break;

                case $this->config->liveBagConfig->bsyjx->id://白色郁金香
                    $returnData = $this->config->liveBagConfig->bsyjx->data;
                    $item = $user->getUserItemsObject();
                    $item->giveGift($returnData->itemId, $returnData->num);
                    $returnData['type'] = 2;
                    break;

                case $this->config->liveBagConfig->ylbk->id://银喇叭卡
                    $returnData = $this->config->liveBagConfig->ylbk->data;
                    $item = $user->getUserItemsObject();
                    $item->giveItem($returnData->itemId, $returnData->num, 0);
                    $returnData['type'] = 3;
                    break;

                case $this->config->liveBagConfig->jlbk->id://金喇叭卡
                    $returnData = $this->config->liveBagConfig->jlbk->data;
                    $item = $user->getUserItemsObject();
                    $item->giveItem($returnData->itemId, $returnData->num, 0);
                    $returnData['type'] = 3;
                    break;

                case $this->config->liveBagConfig->black_bear->id://黑熊座驾
                    $returnData = $this->config->liveBagConfig->black_bear->data;
                    $item = $user->getUserItemsObject();
                    $item->giveCar($returnData->itemId, 432000);
                    $returnData['type'] = 4;
                    break;
                case $this->config->liveBagConfig->xcqs->id://星辰骑士座驾
                    $returnData = $this->config->liveBagConfig->xcqs->data;
                    $item = $user->getUserItemsObject();
                    $item->giveCar($returnData->itemId, 172800);
                    $returnData['type'] = 4;
                    break;
                default:
                    break;
            }
            $returnData['times'] = $rewardLog->num;
            $returnData['canOpenNum'] = $canOpenNum;
            $rewardLog->save();

            return $this->status->retFromFramework($this->status->getCode('OK'), $returnData);

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取宝箱领取记录
    public function getRewardLog($lastId = 0){
        try {
            $sql = 'select rl.id,ui.nickName,ui.uid,rl.rewardId,rl.addTime from \Micro\Models\RewardLog as rl left join \Micro\Models\UserInfo as ui on ui.uid = rl.uid ';
            $condition = $lastId ? (' where rl.id > ' . $lastId . ' order by rl.id desc ') : ' order by rl.id desc limit 0,20 ';
            $query = $this->modelsManager->createQuery($sql . $condition);
            $res = $query->execute();
            $liveBagConfig = $this->config->liveBagConfig;
            $rewardArr = $this->config->rewardArr;
            $data = array();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['uid'] = $v->uid;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['addTime'] = $v->addTime;
                    $tmp['rewardInfo'] = $liveBagConfig[$rewardArr[$v->rewardId]]['data']['tip'];
                    $data[] = $tmp;
                }
            }            
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 搜索主播
    public function searchAnchors($search){
        try {
            //获取主播搜索列表
            $condition = ' r.showStatus = 1 ';
            if($search != ''){
                $condition .= ' and ui.nickName like "%' . $search . '%" ';
            }
            $signStr = '(' . $this->config->signAnchorStatus->apply . ',' . $this->config->signAnchorStatus->refuse . ',' . $this->config->signAnchorStatus->unbind . ')';
            $sql = 'select ui.nickName,up.level2,ui.avatar,r.roomId,r.uid,r.liveStatus,r.isOpenVideo from \Micro\Models\Rooms as r '
                . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                . ' left join \Micro\Models\UserProfiles as up on r.uid = up.uid '
                . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                . ' where ' . $condition
                . ' order by r.liveStatus desc, up.level2 desc limit 0,6';
            $query = $this->modelsManager->createQuery($sql);
            $anchorResult = $query->execute();
            // $anchorCount = 0;
            $anchorData = array();
            $newRoomList1 = array();
            $newRoomList2 = array();
            $newRoomList3 = array();
            $newRoomList0 = array();
            $newRoomList4 = array();
            if($anchorResult->valid()){
                foreach ($anchorResult as $k => $v) {
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['roomId'] = $v->roomId;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $tmp['level2'] = $v->level2;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['liveStatus'] = $v->liveStatus;
                    // $anchorData[] = $tmp;
                    switch ($v->liveStatus) {
                        case 1:
                            $newRoomList1[] = $tmp;
                            break;
                        case 2:
                            $newRoomList2[] = $tmp;
                            break;
                        case 3:
                            $newRoomList3[] = $tmp;
                            break;
                        default :
                            if($v->isOpenVideo == 1){
                                $newRoomList4[] = $tmp;
                            }else{
                                $newRoomList0[] = $tmp;
                            }
                            break;
                    }
                    unset($tmp);
                }
                /*// 统计结果总数
                $countSql = 'select count(1) as count from \Micro\Models\Rooms as r '
                    . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                    . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                    . ' where ' . $condition;
                $countQuery = $this->modelsManager->createQuery($countSql);
                $anchorCount = $countQuery->execute();
                $count = $anchorCount->valid() ? $anchorCount->toArray()[0]['count'] : 0;*/
            }
            $anchorData = array_merge($newRoomList1,$newRoomList3);
            $anchorData = array_merge($anchorData,$newRoomList4);
            $anchorData = array_merge($anchorData,$newRoomList0);
            $anchorData = array_merge($anchorData,$newRoomList2);

            //获取房间号搜索结果
            $roomData = array();
            if(is_numeric($search)){// && strlen($search) == 5
                $roomSql = 'select ui.nickName,up.level2,ui.avatar,r.roomId,r.uid from \Micro\Models\Rooms as r '
                    . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                    . ' left join \Micro\Models\UserProfiles as up on r.uid = up.uid '
                    . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                    . ' where r.showStatus = 1 and r.uid = "' . $search . '" limit 1';
                $roomQuery = $this->modelsManager->createQuery($roomSql);
                $roomResult = $roomQuery->execute();
                if($roomResult->valid()){
                    foreach ($roomResult as $key => $val) {
                        $roomData[] = array(
                            'uid' => $val->uid,
                            'roomId' => $val->roomId,
                            'avatar' => $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath(),
                            'level2' => $val->level2,
                            'nickName' => $val->nickName
                        );
                    }
                }
            }
            //,'anchorCount'=>$count
            return $this->status->retFromFramework($this->status->getCode('OK'), array('anchorData'=>$anchorData,'roomData'=>$roomData));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 搜索页昵称搜索
    public function searchByNickname($search, $page, $pageSize){
        try {
            $condition = ' r.showStatus = 1 ';

            if($search != ''){
                $condition .= ' and ui.nickName like "%' . $search . '%" ';
            }

            if(!intval($page)){
                $page = 1;
            }

            if(!intval($pageSize)){
                $pageSize = 16;
            }

            $limit = ($page - 1) * $pageSize;
            $limitWhere = ' limit ' . $limit . ',' . $pageSize;

            $signStr = '(' . $this->config->signAnchorStatus->apply . ',' . $this->config->signAnchorStatus->refuse . ',' . $this->config->signAnchorStatus->unbind . ')';
            $sql = 'select r.totalNum,r.liveStatus,r.isOpenVideo,f.shortName,sa.location,ui.nickName,up.level2,ui.avatar,r.roomId,r.uid,r.poster from \Micro\Models\Rooms as r '
                . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                . ' left join \Micro\Models\UserProfiles as up on r.uid = up.uid '
                . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                . ' left join \Micro\Models\Family as f on f.id = sa.familyId '
                . ' where ' . $condition
                . ' order by r.liveStatus desc, up.level2 desc ' . $limitWhere;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $anchorData = array();
            $newRoomList1 = array();
            $newRoomList2 = array();
            $newRoomList3 = array();
            $newRoomList4 = array();
            $newRoomList0 = array();
            $count = 0;
            if($result->valid()){
                $thumbGenerator = $this->di->get('thumbGenerator');
                foreach ($result as $k => $v) {
                    $tmp['uid'] = $v->uid;
                    $tmp['roomId'] = $v->roomId;
                    $tmp['liveStatus'] = $v->liveStatus;
                    $tmp['isOpenVideo'] = $v->isOpenVideo;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['familyShortName'] = $v->shortName;
                    $location = $v->location;
                    if(empty($location)){
                        $location = $this->config->signAnchorCityDefault;
                    }
                    $tmp['location'] = $this->config->location[$location]['name'];
                    $tmp['anchorLevel'] = $v->level2;
                    $posterUrls = $thumbGenerator->getPosterUrl($v->poster, $v->avatar);
                    $tmp['poster'] = $posterUrls['poster'];
                    $tmp['small_poster'] = $posterUrls['small-poster'];
                    $tmp['onlineNum'] = $v->totalNum ? intval($v->totalNum) : 0;
                    // $anchorData[] = $tmp;
                    switch ($v->liveStatus) {
                        case 1:
                            $newRoomList1[] = $tmp;
                            break;
                        case 2:
                            $newRoomList2[] = $tmp;
                            break;
                        case 3:
                            $newRoomList3[] = $tmp;
                            break;
                        default :
                            if($v->isOpenVideo == 1){
                                $newRoomList4[] = $tmp;
                            }else{
                                $newRoomList0[] = $tmp;
                            }
                            break;
                    }
                    unset($tmp);
                }
                $anchorData = array_merge($newRoomList1,$newRoomList3);
                $anchorData = array_merge($anchorData,$newRoomList4);
                $anchorData = array_merge($anchorData,$newRoomList0);
                $anchorData = array_merge($anchorData,$newRoomList2);
                $sqlCount = 'select count(1) as count from \Micro\Models\Rooms as r '
                    . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                    . ' left join \Micro\Models\UserProfiles as up on r.uid = up.uid '
                    . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                    . ' where ' . $condition;
                $countQuery = $this->modelsManager->createQuery($sqlCount);
                $countRes = $countQuery->execute();
                $count = $countRes->valid() ? $countRes->toArray()[0]['count'] : 0;
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$anchorData,'count'=>$count));
            
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 搜索页房间号搜索
    public function searchByRoomid($search){
        try {
            $condition = ' r.showStatus = 1 ';
            if($search != ''){
                $condition .= ' and r.uid = "' . $search . '" ';
            }
            $signStr = '(' . $this->config->signAnchorStatus->apply . ',' . $this->config->signAnchorStatus->refuse . ',' . $this->config->signAnchorStatus->unbind . ')';
            $sql = 'select r.totalNum,r.liveStatus,r.isOpenVideo,f.shortName,sa.location,ui.nickName,up.level2,ui.avatar,r.roomId,r.uid,r.poster from \Micro\Models\Rooms as r '
                . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                . ' left join \Micro\Models\UserProfiles as up on r.uid = up.uid '
                . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                . ' left join \Micro\Models\Family as f on f.id = sa.familyId '
                . ' where ' . $condition
                . ' limit 1 ';
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $count = 0;
            $data = array();
            if($result->valid()){
                $thumbGenerator = $this->di->get('thumbGenerator');
                foreach ($result as $k => $v) {
                    $tmp['uid'] = $v->uid;
                    $tmp['roomId'] = $v->roomId;
                    $tmp['liveStatus'] = $v->liveStatus;
                    $tmp['isOpenVideo'] = $v->isOpenVideo;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['familyShortName'] = $v->shortName;
                    $location = $v->location;
                    if(empty($location)){
                        $location = $this->config->signAnchorCityDefault;
                    }
                    $tmp['location'] = $this->config->location[$location]['name'];
                    $tmp['anchorLevel'] = $v->level2;
                    $posterUrls = $thumbGenerator->getPosterUrl($v->poster, $v->avatar);
                    $tmp['poster'] = $posterUrls['poster'];
                    $tmp['small_poster'] = $posterUrls['small-poster'];
                    $tmp['onlineNum'] = $v->totalNum ? intval($v->totalNum) : 0;
                    $data[] = $tmp;
                    unset($tmp);
                }
                $count = 1;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    // 搜索房间For APP
    public function searchAnchorsForMobile($search, $page, $pageSize){
        try {
            $existUid = 0;
            $signStr = '(' . $this->config->signAnchorStatus->apply . ',' . $this->config->signAnchorStatus->refuse . ',' . $this->config->signAnchorStatus->unbind . ')';
            //获取房间号搜索结果
            $anchorData = array();
            $newRoomList1 = array();
            $newRoomList2 = array();
            $newRoomList3 = array();
            $newRoomList4 = array();
            $newRoomList0 = array();
            if(is_numeric($search)){
                $roomSql = 'select ui.nickName,up.level2,ui.avatar,r.roomId,r.uid,r.liveStatus,r.isOpenVideo from \Micro\Models\Rooms as r '
                    . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                    . ' left join \Micro\Models\UserProfiles as up on r.uid = up.uid '
                    . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                    . ' where r.showStatus = 1 and r.uid = "' . $search . '" limit 1';
                $roomQuery = $this->modelsManager->createQuery($roomSql);
                $roomResult = $roomQuery->execute();
                if($roomResult->valid()){
                    foreach ($roomResult as $key => $val) {
                        $tmp = array(
                            'uid' => $val->uid,
                            'roomId' => $val->roomId,
                            'avatar' => $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath(),
                            'level2' => $val->level2,
                            'liveStatus' => $val->liveStatus,
                            'isOpenVideo' => $val->isOpenVideo,
                            'nickName' => $val->nickName
                        );
                        $existUid = $val->uid;
                        // $anchorData[] = $tmp;
                        switch ($val->liveStatus) {
                            case 1:
                                $newRoomList1[] = $tmp;
                                break;
                            case 2:
                                $newRoomList2[] = $tmp;
                                break;
                            case 3:
                                $newRoomList3[] = $tmp;
                                break;
                            default :
                                if($val->isOpenVideo == 1){
                                    $newRoomList4[] = $tmp;
                                }else{
                                    $newRoomList0[] = $tmp;
                                }
                                break;
                        }
                        unset($tmp);
                    }
                }
            }


            //获取主播搜索列表
            $condition = ' r.showStatus = 1 ';
            if($search != ''){
                $condition .= ' and ui.nickName like "%' . $search . '%" ';
            }
            /*if(!intval($page)){
                $page = 1;
            }

            if(!intval($pageSize)){
                $pageSize = 25;
            }

            $limit = ($page - 1) * $pageSize;
            $limitWhere = ' limit ' . $limit . ',' . $pageSize;*/

            $sql = 'select ui.nickName,up.level2,ui.avatar,r.roomId,r.uid,r.liveStatus,r.isOpenVideo from \Micro\Models\Rooms as r '
                . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                . ' left join \Micro\Models\UserProfiles as up on r.uid = up.uid '
                . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                . ' where ' . $condition
                . ' order by r.liveStatus desc, up.level2 desc ';// . $limitWhere;
            $query = $this->modelsManager->createQuery($sql);
            $anchorResult = $query->execute();
            if($anchorResult->valid()){
                foreach ($anchorResult as $k => $v) {
                    if($v->uid == $existUid) continue;
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['roomId'] = $v->roomId;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $tmp['level2'] = $v->level2;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['liveStatus'] = $v->liveStatus;
                    $tmp['isOpenVideo'] = $v->isOpenVideo;
                    // $anchorData[] = $tmp;
                    switch ($v->liveStatus) {
                        case 1:
                            $newRoomList1[] = $tmp;
                            break;
                        case 2:
                            $newRoomList2[] = $tmp;
                            break;
                        case 3:
                            $newRoomList3[] = $tmp;
                            break;
                        default :
                            if($v->isOpenVideo == 1){
                                $newRoomList4[] = $tmp;
                            }else{
                                $newRoomList0[] = $tmp;
                            }
                            break;
                    }
                    unset($tmp);
                }
            }
            $anchorData = array_merge($newRoomList1,$newRoomList3);
            $anchorData = array_merge($anchorData,$newRoomList4);
            $anchorData = array_merge($anchorData,$newRoomList0);
            $anchorData = array_merge($anchorData,$newRoomList2);
            // 统计结果总数
            /*$countSql = 'select count(1) as count from \Micro\Models\Rooms as r '
                . ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid '
                . ' left join \Micro\Models\SignAnchor as sa on sa.uid = r.uid and sa.status not in ' . $signStr
                . ' where ' . $condition . '';
            $countQuery = $this->modelsManager->createQuery($countSql);
            $anchorRes = $countQuery->execute();
            $anchorCount = $anchorRes->valid() ? $anchorRes->toArray()[0]['count'] : 0;*/

            
            //,'anchorCount'=>$count
            return $this->status->retFromFramework(
                $this->status->getCode('OK'), 
                $anchorData
            );
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    
    
    /**
     * 主播召集粉丝，推送粉丝
     */
    public function pushFansByConvene($roomId) {
        $postData['roomid'] = $roomId; //房间id
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        //查询是该房间的主播
        $roomInfo = Rooms::findfirst($roomId);
        if (!$roomInfo || $roomInfo->uid != $uid) {
            return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
        }
        
        //查询今日召集次数是否用完
        $today = strtotime(date("Ymd"));
        $count = \Micro\Models\ConveneLog::count("uid=" . $uid . " and createTime>=" . $today);
        if ($count >= $this->config->conveneLimit) {
            return $this->status->retFromFramework($this->status->getCode('CONVENE_LIMIT'));
        }
        
        //查询间隔时间
        $info = \Micro\Models\ConveneLog::findfirst("uid=" . $uid . " order by createTime desc");
        if ($info && time() - $info->createTime < $this->config->conveneTimeLimt) {
            return $this->status->retFromFramework($this->status->getCode('CONVENE__TIME_LIMIT'));
        }
                        

        //记录召集日志
        $new = new \Micro\Models\ConveneLog();
        $new->uid = $uid;
        $new->createTime = time();
        $new->save();



        //获取粉丝列表
        $arrayResult = $this->userMgr->getUserFansUidList($uid);

        $userRes = $user->getUserInfoObject()->getUserInfo();
        $userData['nickName']=$userRes['nickName'];
        $userData['avatar']=$userRes['avatar'];
        $userData['uid']=$userRes['uid'];
        $message = array(
            'action' => 'conveneFans',
            'uid' => $uid,
        );

        $content = "您关注的主播：{$userData['nickName']} 邀请你过去一起玩耍~~~";
        $uidsList = $arrayResult['code'] == $this->status->getCode('OK') ? $arrayResult['data'] : array();
        if ($uidsList) {
            $this->pushMgr->sendMessage($uidsList, $message, $content);

            $ArraySubData['controltype'] = "conveneFans";
            $broadData['userData'] = $userData;
            $ArraySubData['data'] = $broadData;
            // 网页单点广播
            foreach ($uidsList as $uid) {
                $user = UserFactory::getInstance($uid);
                $accountId = $user->getUserInfoObject()->getAccountId();
                $roomList = $this->getUsersWhereIn($uid);
                if ($roomList) {
                    foreach ($roomList as $roomVal) {
                        $this->comm->roomNotify($roomVal['roomid'], $accountId, $ArraySubData);
                    }
                }
            }
        }

        return $this->status->retFromFramework($this->status->getCode("OK"));
    }
    
    
     /**
     * 主播是否可召集粉丝
     */
    public function isCanConveneFans($roomId) {
        $postData['roomid'] = $roomId; //房间id
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        //查询是该房间的主播
        $roomInfo = Rooms::findfirst($roomId);
        if (!$roomInfo || $roomInfo->uid != $uid) {
            return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
        }
        
        //查询今日召集次数是否用完
        $today = strtotime(date("Ymd"));
        $count = \Micro\Models\ConveneLog::count("uid=" . $uid . " and createTime>=" . $today);
        if ($count >= $this->config->conveneLimit) {
            return $this->status->retFromFramework($this->status->getCode('CONVENE_LIMIT'));
        }

        //查询间隔时间
        $info = \Micro\Models\ConveneLog::findfirst("uid=" . $uid . " order by createTime desc");
        if ($info && time() - $info->createTime < $this->config->conveneTimeLimt) {
            return $this->status->retFromFramework($this->status->getCode('CONVENE__TIME_LIMIT'));
        }

        
        return $this->status->retFromFramework($this->status->getCode("OK"));
    }

}