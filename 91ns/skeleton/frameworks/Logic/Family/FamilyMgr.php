<?php

namespace Micro\Frameworks\Logic\Family;

use Micro\Models\ApplyLog;
use Phalcon\DI\FactoryDefault;
use Micro\Models\Family;
use Micro\Models\ConsumeLog;
use Micro\Models\SignAnchor;
use Micro\Models\FamilyLog;
use Micro\Models\UserPhoto;
use Micro\Models\RankLog;
use Micro\Models\FamilySkin;

use Micro\Frameworks\Logic\User\UserData\UserInfo;
use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Frameworks\Logic\User\UserAuth\UserReg as UserReg;

class FamilyMgr{

    protected $di;
    protected $status;
    protected $session;
    protected $config;
    protected $validator;
    protected $logger;
    protected $userAuth;
    protected $pathGenerator;
    protected $storage;
    protected $modelsManager;
    protected $request;
    protected $userMgr;
    protected $url;
    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->session = $this->di->get('session');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->logger = $this->di->get('logger');
        $this->userAuth = $this->di->get('userAuth');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->storage = $this->di->get('storage');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->request = $this->di->get('request');
        $this->userMgr = $this->di->get('userMgr');
        $this->url = $this->di->get('url');
    }
    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【Room】 error : '.$errInfo);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  家族信息
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getFamilyList($skip, $limit) {
        try {
            $count = Family::count();
            $configDataList = Family::find(
                array(
                    "limit" => array("number"=>$limit, "offset"=>$skip),
                    "conditions" => "status <> 0",
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['name'] = $configData->name;
                    $data['shortName'] = $configData->shortName;
                    $data['announcement'] = $configData->announcement;
                    $data['description'] = $configData->description;
                    $data['logo'] = $configData->logo;
                    $data['status'] = $configData->status;
                    $data['updateTime'] = $configData->updateTime;

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     *检测家族是否正常状态
     */
    public function checkFamilyAvailable($id){
        try{
            $postData['id'] = $id;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            $family = Family::findfirst("id={$id}");
            if($family){
                if($family->status == 1){
                    return true;
                }
            }
            return false;
        }catch (\Exception $e) {
            return false;
        }
    }

    public function addFamily($name, $shortName, $announcement, $description, $logo, $status) {
        try {
            $dbdata = new Family();
            $dbdata->name = $name;
            $dbdata->shortName = $shortName;
            $dbdata->announcement = $announcement;
            $dbdata->description = $description;
            $dbdata->logo = $logo;
            $dbdata->status = $status;
            $dbdata->updateTime = time();
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delFamily($id) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = Family::findFirst($id);
            if(empty($configData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 注：这里需要做一下修改信息的验证，不是所有的信息都可以修改
    public function updateFamily($id, $updateData) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = Family::findFirst($id);
            if(empty($configData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach($list as $key => $val){
                if(isset($updateData[$key])){
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    public function getFamilyInfo($id) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = Family::findFirst($id);
            if(empty($configData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            /*//获取旗下主播数量
            $count = \Micro\Models\SignAnchor::count(
                'familyId = ' . $id
                . ' and status != ' . $this->config->signAnchorStatus->apply
                . ' and status != ' . $this->config->signAnchorStatus->refuse
                . ' and status != ' . $this->config->signAnchorStatus->unbind
            );

            //获取主播家族长信息
            $creatorInfo = */

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['shortName'] = $configData->shortName;
            $data['announcement'] = $configData->announcement;
            $data['description'] = $configData->description;
            $data['logo'] = $configData->logo;
            $data['status'] = $configData->status;
            $data['createTime'] = $configData->createTime;
            $data['address'] = $configData->address;
            $data['creatorUid'] = $configData->creatorUid;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取家族信息
    public function getFamilyInfoNew($familyId){
        try {
            $inWhere = '(' . $this->config->signAnchorStatus->apply . ',' . $this->config->signAnchorStatus->refuse . ',' . $this->config->signAnchorStatus->unbind . ')';
            $sql = 'select f.id,f.shortName,ui.avatar,f.creatorUid,f.name,f.createTime,f.announcement,f.logo,ifnull(count(sa.id),0) as num,ac.level,ui.nickName from \Micro\Models\Family as f '
                . ' left join \Micro\Models\SignAnchor as sa on sa.familyId = f.id and sa.status not in ' . $inWhere 
                . ' left join \Micro\Models\UserProfiles as up on f.creatorUid = up.uid '
                . ' left join \Micro\Models\UserInfo as ui on f.creatorUid = ui.uid '
                . ' left join \Micro\Models\AnchorConfigs ac on up.level2 = ac.level '
                . ' where f.id = ' . $familyId . ' limit 0,1';
            $data = array();
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $data['id'] = $v->id;
                    $data['name'] = $v->name;
                    $data['shortName'] = $v->shortName;
                    $data['announcement'] = $v->announcement;
                    $data['logo'] = $v->logo;
                    $data['createTime'] = $v->createTime ? date('Y年m月d日',$v->createTime) : 0;
                    $data['num'] = $v->num;
                    $data['level'] = $v->level;
                    $data['nickName'] = $v->nickName;
                    $data['creatorUid'] = $v->creatorUid;
                    $data['avatar'] = $v->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                }
                
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getAnchorsByFamilyId($familyId = 0, $page = 1, $pageSize = 20){
        try {
            $limit = $pageSize * ( $page - 1);
            $sql  = 'SELECT a.uid,a.avatar,a.nickName,b.level2,c.poster,c.roomId,c.isOpenVideo,c.liveStatus,ifnull((c.onlineNum + c.robotNum), 0) as onlineNum, d.location,f.shortName'
                . ' FROM \Micro\Models\UserInfo a '
                . ' LEFT JOIN \Micro\Models\UserProfiles b ON a.uid = b.uid '
                . ' LEFT JOIN \Micro\Models\Rooms c ON c.uid = b.uid'
                . ' LEFT JOIN \Micro\Models\SignAnchor d ON d.uid = a.uid '
                . ' LEFT JOIN \Micro\Models\Family f ON f.id = d.familyId '
                . ' WHERE d.familyId = ' . $familyId . ' order by c.liveStatus desc,c.totalNum desc';
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $list =array();
            $count = 0;
            $newRoomList1 = array();
            $newRoomList2 = array();
            $newRoomList3 = array();
            $newRoomList0 = array();

            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['anchorLevel'] = $v->level2 ? intval($v->level2) : 0;
                    $tmp['roomId'] = $v->roomId ? intval($v->roomId) : 0;
                    $tmp['liveStatus'] = $v->liveStatus ? intval($v->liveStatus) : 0;
                    $tmp['isOpenVideo'] = $v->isOpenVideo;
                    $tmp['onlineNum'] = $v->onlineNum;
                    $tmp['location'] = $v->location;
                    $tmp['familyShortName'] = $v->shortName;

                    if(empty($tmp['location'])){
                        $tmp['location'] = $this->config->signAnchorCityDefault;
                    }
                    $tmp['location'] = $this->config->location[$v['location']]['name'];

                    if (empty($tmp['nickName'])) {
                        $tmp['nickName'] = $v->uid;
                    }

                    if (empty($roomData['avatar'])) {
                        $roomData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }

                    //设置海报, 多种分辨率
                    $posterUrl = $v->poster;
                    $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($posterUrl, $v->avatar);
                    $tmp['poster'] = $posterUrls['poster'];
//                    $tmp['small-poster'] = $posterUrls['small-poster'];
                    $tmp['small_poster'] = $posterUrls['small-poster'];
                    $list[] = $tmp;
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
                            $newRoomList0[] = $tmp;
                            break;
                    }

                    unset($tmp);
                }

                $roomList = array_merge($newRoomList1, $newRoomList3);
                $roomList = array_merge($roomList, $newRoomList0);
                $roomList = array_merge($roomList, $newRoomList2);
                $list = array_slice($roomList, $limit, $pageSize);
                $count = \Micro\Models\SignAnchor::count('familyId = ' . $familyId);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$list,'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getAnchorStatus($uid = 0, $familyId = 0){
        ##0-禁用1-可用2-审批中##
        try {
            //判断是否已经签约
            $isSign = \Micro\Models\SignAnchor::findFirst('uid = ' . $uid);
            $notSignArr = array(
                $this->config->signAnchorStatus->apply,
                $this->config->signAnchorStatus->refuse,
                $this->config->signAnchorStatus->unbind
            );
            if(empty($isSign) || in_array($isSign->status, $notSignArr)){//未签约
                return 0;
            }else{//已签约
                $signFamilyId = $isSign->familyId;
                if(!$signFamilyId){// 还未加入家族
                    $applyLog = \Micro\Models\ApplyLog::findFirst("uid='{$uid}' and type = 1 order by id desc");
                    if(empty($applyLog)){//没有申请记录
                        return 1;
                    }else{
                        if($applyLog->targetId == $familyId && $applyLog->status == 0){//申请该家族审批中
                            return 2;
                        }else{//申请其他家族审批中
                            return 1;
                        }
                    }
                }else{//加入家族
                    return 0;
                }
            }
        } catch (\Exception $e) {
            $this->errLog('getAnchorStatus errorMessage = ' . $e->getMessage());
            return 0;
            // return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取家族列表
     */
    public function getFamilyListNew($orderType = 0, $page = 1, $pageSize = 25){
        try {
            $limit = $pageSize * ( $page - 1);
            $inWhere = '(' . $this->config->signAnchorStatus->apply . ',' . $this->config->signAnchorStatus->refuse . ',' . $this->config->signAnchorStatus->unbind . ')';
            if($orderType == 0){
                $sql = 'select f.id,f.shortName,f.logo,f.name,ifnull(count(sa.id), 0) as num from Micro\Models\Family as f '
                    . ' left join \Micro\Models\SignAnchor as sa on f.id = sa.familyId and sa.status not in ' . $inWhere
                    . ' where f.status = 1 group by f.id order by num desc limit ' . $limit . ',' .$pageSize;
            }else{
                $sql1 = 'select f.id,ifnull(count(sa.id), 0) as num from Micro\Models\Family as f '
                    . ' left join \Micro\Models\SignAnchor as sa on f.id = sa.familyId and sa.status not in ' . $inWhere
                    . ' where f.status = 1 group by f.id';
                $query1 = $this->modelsManager->createQuery($sql1);
                $result1 = $query1->execute();
                $fanchors = array();
                if($result1->valid()){
                    foreach ($result1 as $key => $value) {
                        $fanchors[$value->id] = $value->num;
                    }
                }
                    
                $sql = 'select f.id,f.shortName,f.logo,f.name,ifnull(sum(cl.amount), 0) as sum from Micro\Models\Family as f '
                    . ' left join \Micro\Models\ConsumeDetailLog as cl on f.id = cl.familyId and cl.amount > 0 and cl.type < ' . $this->config->consumeType->coinType
                    . ' where f.status = 1 group by f.id order by sum desc limit ' . $limit . ',' .$pageSize;
            }
            $count = 0;
            $list = array();
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['shortName'] = $v->shortName;
                    $tmp['logo'] = $v->logo;
                    $tmp['name'] = $v->name;
                    $tmp['num'] = $orderType == 0 ? $v->num : $fanchors[$v->id];
                    $tmp['sum'] = $orderType == 0 ? 0 : $v->sum;
                    $list[] = $tmp;
                    unset($tmp);
                }
                $count = \Micro\Models\Family::count('status = 1');
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$list,'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获取家族名称列表
     * */
    public function getFamilyInfoList(){
        try{
            $familyList = Family::find(
                array(
                    'columns' => 'name,id',
                    'conditions' => 'status = 1',
                )
            )->toArray();
            if(empty($familyList)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            //家族总列表
            $familyArray = array();
            foreach ($familyList as $value) {
                $familyArray['id_'.$value['id']] = $value;
            }

            //家族排行数据
            $arrData = RankLog::findFirst("index=".$this->config->rankLogType['consume_family_day']);
            if(!$arrData){
                return $this->status->retFromFramework($this->status->getCode('OK'), $familyArray);
            }

            $revert = array();
            $arrData = $arrData->toArray();
            $result = json_decode($arrData['content'], TRUE);
            //获取排行里有的顺序
            foreach ($result as $key => $value) {
                $revert[$key] = $value;
                unset($familyArray[$key]);
            }

            //合并排行里没有的家族
            foreach ($familyArray as $key => $value) {
                $revert[$key] = $value;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $revert);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获取家族信息
     * */
    public function getFamilyInfoByUid($uid) {
        try {
            $signAnchor = SignAnchor::findFirst('uid = '.$uid.' and familyId > 0');
            if(empty($signAnchor)){
                return $this->status->retFromFramework($this->status->getCode('NO_HAS_FAMILY'));
            }

            $configData = Family::findFirst("id={$signAnchor->familyId} AND status<>0");
            if(empty($configData)){
                return $this->status->retFromFramework($this->status->getCode('NO_HAS_FAMILY'));
            }

            if($configData->status == 0){
                return $this->status->retFromFramework($this->status->getCode('FAMILY_BE_APPLY_STATUS'));
            }

            $count = \Micro\Models\SignAnchor::count(
                'familyId = ' . $signAnchor->familyId
                . ' and status != ' . $this->config->signAnchorStatus->apply
                . ' and status != ' . $this->config->signAnchorStatus->refuse
                . ' and status != ' . $this->config->signAnchorStatus->unbind
            );

            $data['id'] = $configData->id;
            $data['name'] = $configData->name;
            $data['shortName'] = $configData->shortName;
            $data['announcement'] = $configData->announcement;
            $data['description'] = $configData->description;
            $data['logo'] = $configData->logo;
            $data['status'] = $configData->status;
            $data['createTime'] = $configData->createTime;
            $data['address'] = $configData->address;
            $data['creatorUid'] = $configData->creatorUid;
            $data['count'] = $count;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 家族长信息
     * */
    public function getFamilyCreatorInfo($uid){
        $user = UserFactory::getInstance($uid);
        if(!$user){
            return $this->status->retFromFramework($this->status->getCode('CREATOR_NO_FOUND'));
        }

        $userInfo = $user->getUserInfoObject()->getUserInfo();
        if(!$userInfo){
            return $this->status->retFromFramework($this->status->getCode('CREATOR_NO_FOUND'));
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $userInfo);
    }

    /*
     * 修改公告
     * */
    public function updateAnnouncement($announcement){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $uid = $user->getUid();
        $postData['uid'] = $uid;
        $postData['announcement'] = $announcement;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        if(mb_strlen($announcement) > 100){
            return $this->status->retFromFramework($this->status->getCode('ANNOUNCEMENT_LENGTH_OVER'));
        }
        try {
            $family = Family::findfirst("creatorUid={$uid}");
            if(empty($family)){
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_UPDATE_FAMILY'));
            }
            $family->announcement = $announcement;
            $family->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 修改介绍
     * */
    public function updateFamilyDescription($description){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $uid = $user->getUid();
        $postData['uid'] = $uid;
        $postData['description'] = $description;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $family = Family::findfirst(array(
                'conditions' => "creatorUid={$uid}",
            ));
            if(empty($family)){
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_UPDATE_FAMILY'));
            }
            $family->description = $description;
            $family->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 同意加入家族
     * */
    public function agreeJoinFamily($applyId){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $creatorUid = $user->getUid();
        $postData['id'] = $applyId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $applyLog = ApplyLog::findfirst($applyId);
            if(empty($applyLog)){
                return $this->status->retFromFramework($this->status->getCode('EMPTY_APPLY'));
            }
            if($applyLog->status != $this->config->applyStatus->ing){
                return $this->status->retFromFramework($this->status->getCode('APPLY_OVER_TIME'));
            }

            $family = Family::findfirst("creatorUid={$creatorUid} AND id={$applyLog->targetId} AND status=1");
            if(empty($family)){
                return $this->status->retFromFramework($this->status->getCode('NO_FAMILY_CREATOR'));
            }
            $signAnchor = SignAnchor::findfirst('uid = '.$applyLog->uid);
            if(empty($signAnchor)){
                return $this->status->retFromFramework($this->status->getCode('NO_SIGN_ANCHOR_CAN_NOT_JOIN'));
            }

            $signAnchor->familyId = $family->id;
            $signAnchor->save();

            //log
            $this->familyLogJoin($applyLog->uid, $family->id);

            //log_apply
            $apply = $user->getUserApplyObject();
            $result = $apply->passApply($applyId);

            //给用户发送通知
            $sendUser = UserFactory::getInstance($applyLog->uid);
            $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->passJoinFamily, array(0 => $family->name));
            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);


            return $this->status->retFromFramework($result['code']);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 加入家族log
     * */
    public function familyLogJoin($uid, $familyId){
        $familyLog = new FamilyLog();
        $familyLog->uid = $uid;
        $familyLog->familyId = $familyId;
        $familyLog->joinTime = time();
        $familyLog->status = 1;
        $familyLog->save();
    }

    /*
     * 退出家族
     * */
    public function exitFamily(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $uid = $user->getUid();
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $signAnchor = SignAnchor::findfirst('uid = '.$uid);
            if(empty($signAnchor)){
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_UPDATE_FAMILY'));
            }
            $count = Family::count('creatorUid = '.$signAnchor->uid.' and id = '.$signAnchor->familyId);
            if($count > 0){
                return $this->status->retFromFramework($this->status->getCode('CREATOR_CAN_NOT_EXIT'));
            }
            $oldFamilyId = $signAnchor->familyId;
            $signAnchor->familyId = 0;
            $signAnchor->save();

            //给家族长发送通知
            $userInfo= \Micro\Models\UserInfo::findfirst("uid=".$uid);
            $nickName=$userInfo->nickName;
            $familyInfo = Family::findfirst($oldFamilyId);
            $sendUser = UserFactory::getInstance($familyInfo->creatorUid);
            $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->outFamily, array(0 => $nickName));
            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);


            $this->familyLogExit($uid);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 退出家族log
     * */
    public function familyLogExit($uid){
        //log
        $familyLog = FamilyLog::findfirst('uid = '.$uid.' AND status = 1');
        $familyLog->outOfTime = time();
        $familyLog->status = 0;
        $familyLog->save();
    }

    /*
     * 申请加入家族（判断）
     * */
    public function applyFamilyPre(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        try {
            $familyCount = Family::count();
            if($familyCount == 0){
                return $this->status->retFromFramework($this->status->getCode('NO_FAMILY_CAN_BE_APPLY'));
            }

            $parameters = array(
                "uid" => $uid,
                "status1" => $this->config->signAnchorStatus->apply,
                "status2" => $this->config->signAnchorStatus->refuse,
                "status3" => $this->config->signAnchorStatus->unbind,
            );
            $signAnchor = SignAnchor::findfirst(array(
                "conditions" => "uid=:uid: AND status!=:status1: AND status!=:status2: AND status!=:status3:",
                "bind" => $parameters,
            ));
            if(empty($signAnchor)){
                return $this->status->retFromFramework($this->status->getCode('NO_SIGN_ANCHOR_CAN_NOT_JOIN'));
            }

            //已经有家族
            if($signAnchor->familyId > 0){
                return $this->status->retFromFramework($this->status->getCode('ALREADY_IN_FAMILY'));
            }else if($signAnchor->status == $this->config->signAnchorStatus->forzen){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }else if($signAnchor->status == $this->config->signAnchorStatus->apply || $signAnchor->status == $this->config->signAnchorStatus->refuse){
                return $this->status->retFromFramework($this->status->getCode('NO_SIGN_ANCHOR_CAN_NOT_JOIN'));
            }
//            $parameters = array(
//                "creatorUid" => $signAnchor->uid,
//            );
//            $count = Family::count(array(
//                "conditions" => "creatorUid=:creatorUid: AND status=0",
//                "bind" => $parameters,
//            ));
//            if($count > 0){
//                return $this->status->retFromFramework($this->status->getCode('APPLY_FAMILY_STATUS'));
//            }

//            $parameters = array(
//                "type" => $this->config->applyType->family,
//                "uid" => $signAnchor->uid,
//                "status1" => $this->config->applyStatus->ing
//            );
//            $count = ApplyLog::count(array(
//                "conditions" => "uid=:uid: AND type=:type: AND status=:status1:",
//                "bind" => $parameters,
//            ));
//            if($count > 0){
//                return $this->status->retFromFramework($this->status->getCode('APPLY_FAMILY_STATUS'));
//            }
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * 检测家族名字
     * */
    public function checkFamilyName($name){
        //数据验证
        $postData['name'] = $name;

        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        //用户验证
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $count = Family::count("name='{$name}'");
            if($count > 0){
                return $this->status->retFromFramework($this->status->getCode('FAMILY_NAME_HAS'));
            }
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * 检测家族名字
     * */
    public function checkFamilyShortName($shortName){
        //数据验证
        $postData['shortName'] = $shortName;

        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        //用户验证
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $count = Family::count("shortName='{$shortName}'");
            if($count > 0){
                return $this->status->retFromFramework($this->status->getCode('FAMILY_SHORT_NAME_HAS'));
            }
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * 申请加入家族
     * */
    public function applyToJoinFamily($data){

        /*$check = $this->applyFamilyPre();
        if($check['code'] != $this->status->getCode('OK')){
            return $this->status->retFromFramework($check['code'], $check['data']);
        }*/
        //数据验证
        $postData['familyid'] = $data['familyId'];
        /*$postData['realname'] = $data['realName'];
        $postData['birth'] = $data['birth'];
        $postData['idcard'] = $data['idCard'];
        $postData['telephone'] = $data['telephone'];
        $postData['address'] = $data['address'];
        $postData['bank'] = $data['bank'];
        $postData['accountname'] = $data['accountName'];
        $postData['cardnumber'] = $data['cardNumber'];
        $postData['qq'] = $data['qq'];*/

        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        //用户验证
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $nickName = $user->getUserInfoObject()->getNickName();
        //更新数据
        try {
            $sql = 'update \Micro\Models\ApplyLog set status = 2 where uid = ' . $user->getUid() . ' and type = 1';
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $signAnchor = SignAnchor::findfirst('uid = '.$user->getUid()." AND status!={$this->config->signAnchorStatus->apply} AND status!={$this->config->signAnchorStatus->refuse}");
            if(!$signAnchor){
                return $this->status->retFromFramework($this->status->getCode('ACTION_NO_ALLOW'));
            }
            /*$signAnchor->realName = $data['realName'];
            $signAnchor->birth = $data['birth'];
            $signAnchor->idCard = $data['idCard'];
            $signAnchor->telephone = $data['telephone'];
            $signAnchor->address = $data['address'];
            $signAnchor->bank = $data['bank'];
            $signAnchor->accountName = $data['accountName'];
            $signAnchor->cardNumber = $data['cardNumber'];
            $signAnchor->qq = $data['qq'];
            $signAnchor->save();*/

            //log_apply
            $apply = $user->getUserApplyObject();
            $result = $apply->joinFamilyApply($data['familyId'], '');
            
            if ($this->status->getCode('OK') == $result['code']) {
                //给家族长发送通知
                $familyInfo = $this->getFamilyInfo($data['familyId']);
                $createUid = $familyInfo['data']['creatorUid'];
                $createUser = UserFactory::getInstance($createUid);
                $content = $createUser->getUserInformationObject()->getInfoContent($this->config->informationCode->applyFamily, array($nickName));
                $createUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);

                //给申请者发送通知
                $applycontent = $user->getUserInformationObject()->getInfoContent($this->config->informationCode->applyJoinFamily, array(0 => $familyInfo['data']['name']));
                $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, $applycontent);
            }

            return $this->status->retFromFramework($result['code']);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 解散家族
     * */
    public function dropFamily(){

    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  签约主播信息
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function getSignAnchorList($skip, $limit) {
        try {
            $count = SignAnchor::count();
            $configDataList = SignAnchor::find(
                array(
                    "limit" => array("number"=>$limit, "offset"=>$skip)
                )
            );

            $dataList = array();
            if ($configDataList->valid()) {
                foreach ($configDataList as $configData) {
                    $data['id'] = $configData->id;
                    $data['uid'] = $configData->uid;
                    $data['familyId'] = $configData->familyId;
                    $data['isFamilyCreator'] = $configData->isFamilyCreator;
                    $data['realName'] = $configData->realName;
                    $data['gender'] = $configData->gender;
                    $data['photo'] = $configData->photo;
                    $data['bank'] = $configData->bank;
                    $data['birth'] = $configData->birth;
                    $data['cardNumber'] = $configData->cardNumber;
                    $data['accountName'] = $configData->accountName;
                    $data['idCard'] = $configData->idCard;
                    $data['telephone'] = $configData->telephone;
                    $data['qq'] = $configData->qq;
                    $data['birthday'] = $configData->birthday;
                    $data['address'] = $configData->address;
                    $data['status'] = $configData->status;
                    $data['createTime'] = $configData->createTime;

                    array_push($dataList, $data);
                }
            }

            $result['count'] = $count;
            $result['list'] = $dataList;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addSignAnchor($uid, $familyId, $isFamilyCreator, $realName, $gender, $photo, $bank, $birth, $cardNumber,
                                  $accountName, $idCard, $telephone, $qq, $birthday, $address, $status) {
        try {
            $dbdata = new SignAnchor();
            $dbdata->uid = $uid;
            $dbdata->familyId = $familyId;
            $dbdata->isFamilyCreator = $isFamilyCreator;
            $dbdata->realName = $realName;
            $dbdata->gender = $gender;
            $dbdata->photo = $photo;
            $dbdata->bank = $bank;
            $dbdata->birth = $birth;
            $dbdata->cardNumber = $cardNumber;
            $dbdata->accountName = $accountName;
            $dbdata->idCard = $idCard;
            $dbdata->telephone = $telephone;
            $dbdata->qq = $qq;
            $dbdata->birthday = $birthday;
            $dbdata->address = $address;
            $dbdata->status = $status;
            $dbdata->createTime = time();
            $dbdata->money = 0.000;
            $dbdata->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function delSignAnchor($id) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = SignAnchor::findFirst($id);
            if(empty($configData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($configData->delete() == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DELETE_DATA_FAILED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 注：这里需要做一下修改信息的验证，不是所有的信息都可以修改
    public function updateSignAnchor($id, $updateData) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = SignAnchor::findFirst($id);
            if(empty($configData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $list = $configData->toArray();
            foreach($list as $key => $val){
                if(isset($updateData[$key])){
                    $configData->$key = $updateData[$key];
                }
            }

            $configData->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getAccountInfo() {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        try {
            $configData = SignAnchor::findFirst("uid={$uid}");
            if(empty($configData)){
                $data['realName'] = '';
                $data['idCard'] = '';
                $data['bank'] = '';
                $data['cardNumber'] = '';
            }else{
                $data['realName'] = $configData->realName;
                $data['idCard'] = $configData->idCard;
                $data['bank'] = $configData->bank;
                $data['cardNumber'] = $configData->cardNumber;
            }

            $userInfo = $user->getUserInfoObject()->getUserInfo();
            $data['bank'] = $userInfo['bank'] ? $userInfo['bank'] : $data['bank'];
            $data['cardNumber'] = $userInfo['cardNumber'] ? $userInfo['cardNumber'] : $data['cardNumber'];
            if($data['cardNumber']){
                $data['cardNumber'] = substr_replace($data['cardNumber'], '*****', 6, 5);
            }

            if($data['idCard']){
                $data['idCard'] = substr_replace($data['idCard'], '********', 5, 8);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getSignAnchorInfo($id) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = SignAnchor::findFirst($id);
            if(empty($configData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $data['id'] = $configData->id;
            $data['uid'] = $configData->uid;
            $data['familyId'] = $configData->familyId;
            $data['isFamilyCreator'] = $configData->isFamilyCreator;
            $data['realName'] = $configData->realName;
            $data['gender'] = $configData->gender;
            $data['photo'] = $configData->photo;
            $data['bank'] = $configData->bank;
            $data['birth'] = $configData->birth;
            $data['cardNumber'] = $configData->cardNumber;
            $data['accountName'] = $configData->accountName;
            $data['idCard'] = $configData->idCard;
            $data['telephone'] = $configData->telephone;
            $data['qq'] = $configData->qq;
            $data['birthday'] = $configData->birthday;
            $data['address'] = $configData->address;
            $data['status'] = $configData->status;
            $data['createTime'] = $configData->createTime;

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 签约主播逻辑判断
     */
    public function joinAnchorPre($uid){
        $userInfo = new UserInfo($uid);
        if($userInfo->isSignFamilyAnchor($uid)){
            return $this->status->retFromFramework($this->status->getCode('IS_ANCHOR_USER'));
        }

        if($userInfo->isSignAnchor($uid)){
            return $this->status->retFromFramework($this->status->getCode('IS_SIGN_USER'));
        }

        if($userInfo->isSignAnchoring($uid)){
            return $this->status->retFromFramework($this->status->getCode('IS_SIGNING_USER'));
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * 签约主播状态获取
     */
    public function getApplySignStatus(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $userInfo = $user->getUserInfoObject();
        if($userInfo->isSignFamilyAnchor($user->getUid())){
            return $this->status->retFromFramework($this->status->getCode('IS_ANCHOR_USER'));
        }

        if($userInfo->isSignAnchor($user->getUid())){
            return $this->status->retFromFramework($this->status->getCode('IS_SIGN_USER'));
        }

        if($userInfo->isSignAnchoring($user->getUid())){
            return $this->status->retFromFramework($this->status->getCode('IS_SIGNING_USER'));
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }


    /**
     * 加入家族判断
     */

    public function joinFamilyPre($uid){
        $userInfo = new UserInfo($uid);
        if($userInfo->isSignFamilyAnchor($uid)){
            return $this->status->retFromFramework($this->status->getCode('IS_ANCHOR_USER'));
        }

        if(!$userInfo->isSignAnchor($uid)){
            return $this->status->retFromFramework($this->status->getCode('NOT_SIGN_USER'));
        }

        if($userInfo->isSignFamilyAnchoring($uid)){
            return $this->status->retFromFramework($this->status->getCode('IS_SIGNING_FAMILY_USER'));
        }

        if(!$userInfo->isFrozen($uid)){
            return $this->status->retFromFramework($this->status->getCode('NOT_FROZEN'));
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    public function clearFamily($uid) {
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        try {
            $configData = SignAnchor::find(array(
                "uid" => $uid
            ));
            if(empty($configData)){
                return $this->status->retFromFramework($this->status->getCode('NOT_SIGN_USER'));
            }
            $configData->delete();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    /**
     * 根据家族长uid获得成员列表
     *
     * @param $uid
     * @return mixed
     */
    public function getFamilyMemberByCreatorId($uid){
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $familyInfo = Family::findFirst("creatorUid={$uid}");
        if(empty($familyInfo)){
            return $this->status->retFromFramework($this->status->getCode('FAMILY_NOT_EXISTS'));
        }

        return $result = $this->getFamilyMemberInfo($familyInfo->familyId);
    }

    /*
     * 获取家族用户列表
     * */
    public function getFamilyMemberInfo($id, $isNew = 0) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

       /* $phql = 'SELECT a.*, b.*, c.*, f.*, d.*'.
            ' FROM \Micro\Models\UserInfo a,'.
            ' \Micro\Models\UserProfiles b,'.
            ' \Micro\Models\Rooms c,'.
            ' \Micro\Models\SignAnchor d,'.
            ' \Micro\Models\Family f'.
            ' WHERE d.familyId = '.$id.' AND d.uid = a.uid AND a.uid = b.uid AND b.uid = c.uid AND f.id = d.familyId order by c.onlineNum desc';
        * */

        $phql = 'SELECT a.uid,a.nickName,a.gender,a.avatar,b.level2,b.level4,'.
                  'c.publicTime,c.poster,c.roomId,c.liveStatus,c.isOpenVideo,c.title,(c.onlineNum + c.robotNum) as onlineNum,f.shortName, d.location'.
            ' FROM \Micro\Models\UserInfo a'.
            ' LEFT JOIN  \Micro\Models\UserProfiles b ON a.uid=b.uid'.
            ' LEFT JOIN \Micro\Models\Rooms c ON c.uid=b.uid'.
            ' LEFT JOIN  \Micro\Models\SignAnchor d ON d.uid=a.uid'.
            ' LEFT JOIN  \Micro\Models\Family f ON f.id=d.familyId'.
            ' WHERE d.familyId = '.$id.' order by c.liveStatus desc,c.isOpenVideo desc,(c.onlineNum + c.robotNum) desc';
        $phql .= $isNew ? ' limit 0, 2' : '';
        try {
            $query = $this->modelsManager->createQuery($phql);
            $rooms = $query->execute();

            $roomList = array();
            if ($rooms->valid()) {
                foreach ($rooms as $room) {
                    $roomData['uid'] = $room->uid;
                    $roomData['roomId'] = $room->roomId;
                    $roomData['liveStatus'] = $room->liveStatus;
                    $roomData['isOpenVideo'] = $room->isOpenVideo;
                    $roomData['nickName'] = $room->nickName;
                    $roomData['gender'] = $room->gender;
                    $roomData['avatar'] = $room->avatar;
                    $roomData['title'] = $room->title;
                    $roomData['familyShortName'] = $room->shortName;
                    $roomData['location'] = $room->location;
                    if(empty($roomData['location'])){
                        $roomData['location'] = $this->config->signAnchorCityDefault;
                    }
                    $roomData['location'] = $this->config->location[$roomData['location']]['name'];

                    if (empty($roomData['avatar'])) {
                        $roomData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    if (empty($roomData['nickName'])) {
                        $roomData['nickName'] = $room->uid;
                    }
                    $roomData['anchorLevel'] = $room->level2;

                    //获取是否是主播
                    $roomData['isSignAnchor'] = $this->userMgr->checkIsAnchor($room->uid);



                    /*$roomData['poster'] = $room->poster;
                    if (empty($roomData['poster'])) {
                        $roomData['poster'] = $room->avatar;
                        if (empty($roomData['poster'])) {
                            $roomData['poster'] = $this->pathGenerator->getFullDefaultAvatarPath();
                        }
                    }*/

                    //设置海报, 多种分辨率
                    $posterUrl = $room->poster;
                    $posterUrls = $this->di->get('thumbGenerator')->getPosterUrl($posterUrl, $room->avatar);
                    $roomData['poster'] = $posterUrls['poster'];
//                    $roomData['small-poster'] = $posterUrls['small-poster'];
                    $roomData['small_poster'] = $posterUrls['small-poster'];
                    /*if (empty($posterUrl)) {        //不存在,取默认数据
                        $posterUrl = $room->avatar;
                        if (empty($posterUrl)) {
                            $posterUrl = $this->pathGenerator->getFullDefaultAvatarPath();
                        }

                        $roomData['poster'] = $posterUrl;
                        $roomData['small-poster'] = $posterUrl;
                    }
                    else {                          //存在,做图片处理
                        $roomData['poster'] = $posterUrl;

                        $posterArray = explode("?", $posterUrl);
                        $posterUrl = $posterArray[0];
                        $posterUrl = str_replace("cdn", "image", $posterUrl);
                        $posterUrl = $this->di->get('thumbGenerator')->getThumbnail($posterUrl, 'poster-small');

                        $roomData['small-poster'] = $posterUrl;
                    }*/

                    $roomData['onlineNum'] = $room->onlineNum;
                    $roomData['fansLevel'] = $room->level4;
                    $roomData['publicTime']=$room->publicTime;
                    array_push($roomList, $roomData);
                }
            }

            $result['data'] = $roomList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    /*
     * 获取家族成员详细信息（族长权限）
     * */
    public function anchorInfoInFamily($anchorId){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $family = Family::findfirst("creatorUid={$user->getUid()}");
        if(!$family){
            return $this->status->retFromFramework($this->status->getCode('NO_ANCHOR_FAMILY_CREATOR'));
        }

        $postData['anchorId'] = $anchorId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $phql = "SELECT sa.realName,sa.gender,sa.birthday,sa.birth,sa.idCard,sa.telephone,sa.address,sa.accountName,sa.cardNumber,sa.qq,ui.avatar,ui.nickName,up.level2,sa.uid " .
                    " FROM \Micro\Models\SignAnchor sa ".
                    " LEFT JOIN \Micro\Models\Family f ON sa.familyId=f.id " .
                    " LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid=sa.uid " .
                    " LEFT JOIN \Micro\Models\UserProfiles up ON up.uid=sa.uid " .
                    " WHERE sa.uid={$anchorId} AND f.creatorUid = {$user->getUid()} AND sa.status <> {$this->config->applyStatus->ing} LIMIT 1";
            $query = $this->modelsManager->createQuery($phql);
            $signAnchorInfo = $query->execute();

            if ($signAnchorInfo->valid()) {
                $photo = UserPhoto::find("uid={$signAnchorInfo[0]['uid']}");
                if ($photo->valid()) {
                    $info['avatar'] = $signAnchorInfo[0]['avatar'];
                    if (empty($info['avatar'])) {
                        $info['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    $info['nickName'] = $signAnchorInfo[0]['nickName'];
                    $info['anchorLevel'] = $signAnchorInfo[0]['level2'];
                    $info['realName'] = $signAnchorInfo[0]['realName'];
                    $info['gender'] = $signAnchorInfo[0]['gender'];
                    $info['birthday'] = $signAnchorInfo[0]['birthday'];
                    $info['birth'] = $signAnchorInfo[0]['birth'];
                    $info['idCard'] = $signAnchorInfo[0]['idCard'];
                    $info['telephone'] = $signAnchorInfo[0]['telephone'];
                    $info['address'] = $signAnchorInfo[0]['address'];
                    $info['accountName'] = $signAnchorInfo[0]['accountName'];
                    $info['cardNumber'] = $signAnchorInfo[0]['cardNumber'];
                    $info['qq'] = $signAnchorInfo[0]['qq'];
                    foreach ($photo as $val) {
                        if ($val->type == $this->config->photoType->lifePhoto) {
                            $info['photo']['lifePhoto'][] = $val->photoUrl;
                        } elseif ($val->type == $this->config->photoType->idPhoto) {
                            $info['photo']['idPhoto'][] = $val->photoUrl;
                        }
                    }
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $info);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 申请签约
     */
    public function SignAnchorApply($userData) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        //图片获取验证
        $picArray = $this->config->websiteinfo->apply_sign_pic;
        $pic = array();
        foreach($picArray as $v=>$vKey){
            foreach($vKey as $val=>$valKey){
                $pic[$v][$val] = $this->session->get($valKey);
                if(empty($pic[$v][$val])){
                    return $this->status->retFromFramework($this->status->getCode('APPLY_PIC_NO_ENOUGH'));
                }
            }
        }

        try {
            $uid = $user->getUid();
            $parameters = array(
                "uid" => $uid,
            );
            $signAnchor = SignAnchor::findFirst(array(
                "conditions" => "uid=:uid:",
                "bind" => $parameters,
            ));
            if(empty($signAnchor)){
                $signAnchor = new SignAnchor();
            }else{
                if($signAnchor->status == $this->config->signAnchorStatus->apply){//申请状态
                    return $this->status->retFromFramework($this->status->getCode('IS_SIGNING_USER'));
                }else if($signAnchor->status != $this->config->signAnchorStatus->refuse && $signAnchor->status != $this->config->signAnchorStatus->unbind){//不是拒绝且不是解约状态，则是正常。
                    return $this->status->retFromFramework($this->status->getCode('IS_SIGN_ANCHOR'));
                }
            }

            $signAnchor->realName = $userData['realName'];
            $signAnchor->birth = $userData['city'];
            $signAnchor->idCard = $userData['idCard'];
            $signAnchor->telephone = $userData['telephone'];
            $signAnchor->address = $userData['address'];
            $signAnchor->accountName = $userData['accountName'];
            $signAnchor->cardNumber = $userData['cardNumber'];
            $signAnchor->bank = $userData['bank'];
            $signAnchor->qq = $userData['qq'];
            $signAnchor->location = $userData['birth'];
            $signAnchor->gender = $userData['sex'];
            $signAnchor->email = $userData['email'];
            //$signAnchor->constellation = $userData['constellation'];
            $signAnchor->familyId = 0;
            $signAnchor->status = $this->config->signAnchorStatus->apply;
            $signAnchor->createTime = time();
            $signAnchor->money = 0.000;
            $signAnchor->uid = $uid;
            // $signAnchor->birthday = mktime(0, 0, 0, $userData['birthMonth'], $userData['birthDay'], $userData['birthYear']);
            $signAnchor->birthday = $userData['birthDay'] ? strtotime($userData['birthDay']) : 0;
            $signAnchor->save();

            //删除session//图片
            $parameters = array(
                "uid" => $uid,
            );
            $userPhoto = UserPhoto::find(array(
                "conditions" => "uid=:uid:",
                "bind" => $parameters,
            ));
            if($userPhoto->valid()){
                $userPhoto->delete();
            }
            foreach($picArray as $v=>$vKey){
                foreach($vKey as $val=>$valKey){
                    $userPhoto = new UserPhoto();
                    $userPhoto->uid = $uid;
                    $userPhoto->photoUrl = $pic[$v][$val];
                    if($v == 'live'){
                        $userPhoto->type = $this->config->photoType->lifePhoto;
                    }else{
                        $userPhoto->type = $this->config->photoType->idPhoto;
                    }
                    $userPhoto->save();
                    $this->session->remove($valKey);
                }
            }

            // 更新账号信息
            $this->updateAccountInfo($userData['bank'], $userData['cardNumber'], $userData['realName'], $userData['idCard'], '', 1);
            //log_apply
            $apply = $user->getUserApplyObject();
            $result = $apply->signApply('');
            
            //给用户发送通知
            $content = $user->getUserInformationObject()->getInfoContent($this->config->informationCode->applySignAnchor);
            $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);


            return $this->status->retFromFramework($result['code']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
    }

    /*
     * 上传头像
     */
    public function SignAnchorApplyPic($file, $type, $id) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        try {
            //$fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION)); //$pathinfos['extension']);
            $fileNameArray = explode('.', strtolower($file->getName()));
            $fileExt = $fileNameArray[count($fileNameArray)-1];
            $filePath = $this->pathGenerator->getLivePicPath($user->getUid());
            $fileName = $type.$id.'.' . $fileExt;

            $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
            $result['url'] = $this->pathGenerator->getFullLivePicPath($user->getUid(), $fileName);
            $this->session->set($this->config->websiteinfo->apply_sign_pic->$type->$id, $result['url']);

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 上传家族图标
     * */
    public function uploadFamilyPoster($file){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        try {
            //图片格式为jpg
            $fileExt="jpg";
            $uid=$user->getUid();
            $filePath = $this->pathGenerator->getFamilyPosterPath($uid);
            // $fileName = '0.' . $fileExt;
            $fileName = time() . '.' . $fileExt;
            $this->storage->write($filePath . $fileName,$file,TRUE);
//          $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
            $result['url'] = $this->pathGenerator->getRelFamilyPosterPath($uid, $fileName);
            $this->session->set($this->config->websiteinfo->familyapplyurlkey, $result['url']);

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 申请创建家族
     */
    public function applyCreateFamily($family) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        //判断是否符合要求
        $check = $this->createFamilyRequest();
        if($check['code'] != $this->status->getCode('OK')){
            return $this->status->retFromFramework($check['code'], $check['data']);
        }

        $count = Family::count("name='{$family['name']}'");
        if($count > 0){
            return $this->status->retFromFramework($this->status->getCode('FAMILY_NAME_HAS'));
        }

        $count = Family::count("shortName='{$family['shortName']}'");
        if($count > 0){
            return $this->status->retFromFramework($this->status->getCode('FAMILY_SHORT_NAME_HAS'));
        }

        $family['logo'] = $this->session->get($this->config->websiteinfo->familyapplyurlkey);
        if(!empty($family['logo'])){
            try {
                $postData['name'] = $family['name'];
                $postData['shortName'] = $family['shortName'];
                $postData['companyName'] = $family['companyName'];
                $postData['address'] = $family['address'];
                $postData['logo'] = $family['logo'];
                $isValid = $this->validator->validate($postData);
                if (!$isValid) {
                    $errorMsg = $this->validator->getLastError();
                    return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
                }

                $familyList = new Family();
                $familyList->creatorUid = $user->getUid();
                $familyList->name = $family['name'];
                $familyList->shortName = $family['shortName'];
                $familyList->logo = $family['logo'];
                $familyList->address = $family['address'];
                $familyList->companyName = $family['companyName'];
                $familyList->createTime = time();
                $familyList->status = 0;
                $familyList->save();

                //log_apply
                $apply = $user->getUserApplyObject();
                $result = $apply->createFamilyApply($familyList->id, '');
                
                //给用户发送通知
                $content = $user->getUserInformationObject()->getInfoContent($this->config->informationCode->applyCreateFamily, array(0 => $family['name']));
                $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);

                //删除session
                $this->session->remove($this->config->websiteinfo->familyapplyurlkey);
                return $this->status->retFromFramework($result['code']);
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
        }
        return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
    }

    public function getFamilyApplyStatus(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return FALSE;
        }

        $uid = $user->getUid();
        $applyLog = \Micro\Models\ApplyLog::findFirst("uid='{$uid}' and type = 3 and status=0");
        if(empty($applyLog)){//没有申请记录
            return FALSE;
        }else{
            return TRUE;
        }

        return FALSE;
    }

    /*
     * 创建家族请求
     * */
    public function createFamilyRequest(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        try {
            //判断是否在审核中家族
//            $family = Family::findfirst('creatorUid = '. $uid);
//            if(!empty($family)&&$family->status == 0 ){
//                return $this->status->retFromFramework($this->status->getCode('FAMILY_BE_APPLY_STATUS'), $family->status);
//            }elseif(!empty($family)&&$family->status == 1){
//                return $this->status->retFromFramework($this->status->getCode('HAS_FAMILY'), $family->status);
//            }

            $family = \Micro\Models\ApplyLog::findFirst("uid='{$uid}' and type = 3 and status=0");
            if(!empty($family)){
                return $this->status->retFromFramework($this->status->getCode('FAMILY_BE_APPLY_STATUS'), $family->status);
            }

            $family = \Micro\Models\ApplyLog::findFirst("uid='{$uid}' and type = 3 and status=1");
            if(!empty($family)){
                return $this->status->retFromFramework($this->status->getCode('FAMILY_BE_APPLY_STATUS'), $family->status);
            }


            //是否家族主播
            $count = SignAnchor::count('uid = '. $uid . ' and familyId > 0');
            if($count > 0) {
                return $this->status->retFromFramework($this->status->getCode('IS_FAMILY_ANCHOR'));
            }

            //是否主播
            $signAnchor = SignAnchor::findfirst('uid = '. $uid . ' and familyId = 0');
            if(empty($signAnchor)||$signAnchor->status == $this->config->signAnchorStatus->apply||$signAnchor->status == $this->config->signAnchorStatus->refuse){
                return $this->status->retFromFramework($this->status->getCode('NOT_SIGN_USER'));
            }
//
//            //是否冻结状态/结算状态-------------------[结算状态未清楚流程]
//            if($signAnchor->status != $this->config->signAnchorStatus->forzen){
//                return $this->status->retFromFramework($this->status->getCode('SIGN_ANCHOR_STATUS_NOT_FORZEN'));
//            }
            return $this->status->retFromFramework($this->status->getCode('OK'));

        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 冻结
     * */
    public function applyFamilyFrozen(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        try {
            $signAnchor = SignAnchor::findfirst('uid = '. $uid . ' and status != '.$this->config->signAnchorStatus->apply);
            if(empty($signAnchor)) {
                return $this->status->retFromFramework($this->status->getCode('NOT_SIGN_USER'));
            }
            if($signAnchor->status == $this->config->signAnchorStatus->forzen){
                return $this->status->retFromFramework($this->status->getCode('SIGN_ANCHOR_STATUS_FORZEN'));
            }

            //[冻结具体操作][待解决]
            $signAnchor->status = $this->config->signAnchorStatus->forzen;
            $signAnchor->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));

        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    /**
     * 家族收益ConsumeLog
     */
    public function getFamilyConsume($fid){

        $postData['id'] = $fid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $familyInfo = Family::findFirst($fid);
        if(empty($familyInfo)){
            return $this->status->retFromFramework($this->status->getCode('FAMILY_NOT_EXISTS'));
        }

        $data = ConsumeLog::sum(
            array(
                "column"     => "amount",
                "conditions" => "familyId = $fid",
                "group"  => "anchorUid"
            )
        )->toArray();

        if($data){
            foreach($data as &$val){
                $userInfo = new UserInfo($val['anchorUid']);
                $val['userData'] = $userInfo->getData();
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    /**
     * 主播收益
     */

    public function getAnchorConsume($uid){
        $postData['id'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $data = ConsumeLog::sum(
            array(
                "column"     => "amount",
                "conditions" => "anchorUid = $uid",
                "group"  => "consumeUid"
            )
        )->toArray();

        if($data){
            foreach($data as &$val){
                $userInfo = new UserInfo($val['consumeUid']);
                $val['userData'] = $userInfo->getData();
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //  家族收益
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////

    //获得本周收益
    public function getThisWeekAnchorConsume($familyId){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        $weekDay = date('N'); // 获得当前是周几
        $timeDiff = $weekDay - 1;
        $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
        $lastWeekStar = strtotime("-7 days", $weekStar); //上周一
        $monthStar = strtotime(date('Y-m') . "-01");
        $lastMonthStar = strtotime('-1 month', $monthStar);
        //$this->getAnchorConsumeByTime($weekStar, time(), $uid);
        //个人获取还是家族长查看
        if($familyId){
            return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByAnchor($weekStar, time(), $familyId));
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByDay($weekStar, time(), $uid));

    }

    //获得上周收益
    public function getLastWeekAnchorConsume($familyId){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        $weekDay = date('N'); // 获得当前是周几
        $timeDiff = $weekDay - 1;
        $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
        $lastWeekStar = strtotime("-7 days", $weekStar); //上周一
        $monthStar = strtotime(date('Y-m') . "-01");
        $lastMonthStar = strtotime('-1 month', $monthStar);
        //个人获取还是家族长查看
        if($familyId){
            return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByAnchor($lastWeekStar, $weekStar, $familyId));
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByDay($lastWeekStar, $weekStar-1, $uid));
    }

    //获得本月收益
    public function getThisMonthAnchorConsume($familyId){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        $weekDay = date('N'); // 获得当前是周几
        $timeDiff = $weekDay - 1;
        $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
        $lastWeekStar = strtotime("-7 days", $weekStar); //上周一
        $monthStar = strtotime(date('Y-m') . "-01");
        $lastMonthStar = strtotime('-1 month', $monthStar);
        //个人获取还是家族长查看
        if($familyId){
            return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByAnchor($monthStar, time(), $familyId));
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByDay($monthStar, time(), $uid));
    }

    //获得上月收益
    public function getLastMonthAnchorConsume($familyId){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        $weekDay = date('N'); // 获得当前是周几
        $timeDiff = $weekDay - 1;
        $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
        $lastWeekStar = strtotime("-7 days", $weekStar); //上周一
        $monthStar = strtotime(date('Y-m') . "-01");
        $lastMonthStar = strtotime('-1 month', $monthStar);
        //个人获取还是家族长查看
        if($familyId){
            return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByAnchor($lastMonthStar, $monthStar-1, $familyId));
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByDay($lastMonthStar, $monthStar-1, $uid));
    }

    //获得总收益
    public function getTotalAnchorConsume($familyId){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        $weekDay = date('N'); // 获得当前是周几
        $timeDiff = $weekDay - 1;
        $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
        $lastWeekStar = strtotime("-7 days", $weekStar); //上周一
        $monthStar = strtotime(date('Y-m') . "-01");
        $lastMonthStar = strtotime('-1 month', $monthStar);
        //个人获取还是家族长查看
        if($familyId){
            return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByAnchor(0, time(), $familyId));
        }
        return $this->status->retFromFramework($this->status->getCode('OK'), $this->getAnchorConsumeByDay(0, time(), $uid));
    }

    /*
     * 家族长获取主播收益信息
     * */
    public function getAnchorConsumeByAnchor($timeBegin, $timeEnd, $familyId){
        $postData['familyId'] = $familyId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try{
            $sum = 0;
            //获取主播列表信息
            $sql = 'SELECT cl.anchorId, sum(income) as thisSum '.
                ' FROM \Micro\Models\ConsumeLog cl'.
                ' LEFT JOIN \Micro\Models\Family f ON f.id = cl.familyId'.
                ' WHERE cl.familyId = '.$familyId.
                " AND cl.type < {$this->config->consumeType->coinType} ".
                ' AND cl.createTime BETWEEN '.$timeBegin.' AND '.$timeEnd.
                ' GROUP BY cl.anchorId ORDER BY thisSum DESC';

            $query = $this->modelsManager->createQuery($sql);
            $records = $query->execute();

            $data['record'] = array();
            if($records->valid()){
                foreach($records as $record){
                    $sum += $record->thisSum;
                    $consume['amount'] = $record->thisSum;
                    $consume['anchorId'] = $record->anchorId;
                    $data['record'][] = $consume;
                }
            }

            //获取家族主播信息列表
            $sql = 'SELECT u.uid, u.userName, ui.avatar, ui.nickName, up.level2 '.
                ' FROM \Micro\Models\Users u'.
                ' LEFT JOIN \Micro\Models\SignAnchor sa ON sa.uid = u.uid'.
                ' LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid = u.uid'.
                ' LEFT JOIN \Micro\Models\UserProfiles up ON up.uid = u.uid'.
                ' LEFT JOIN \Micro\Models\FamilyLog fl ON fl.uid = u.uid AND fl.familyId = sa.familyId AND fl.status = 1'.
                ' WHERE sa.familyId = '.$familyId.' AND fl.status = 1';

            $query = $this->modelsManager->createQuery($sql);
            $users = $query->execute();

            $data['user'] = array();
            if($users->valid()){
                foreach($users as $user){
                    $userData['nickName'] = $user->nickName;
                    $userData['anchorLevel'] = $user->level2;
                    $userData['avatar'] = $user->avatar;
                    $data['user'][$user->uid] = $userData;
                }
            }
            $data['sum'] = $sum;
        }catch (\Exception $e) {
            $this->errLog('getAnchorConsumeByAnchor errorMessage = ' . $e->getMessage());
            return array('sum'=>0,'record'=>array());
        }
        return $data;
    }

    //获取收益信息
    public function getAnchorConsumeByDay($timeBegin, $timeEnd, $uid){
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        //var_dump($timeBegin,$timeEnd,$uid);die;
        try{
            $sum = 0;
            $sql = 'SELECT f.*, cl.*, sum(cl.income) as thisSum '.
                ' FROM \Micro\Models\ConsumeLog cl '.
                ' INNER JOIN \Micro\Models\Family f ON f.id = cl.familyId '.
                " WHERE cl.anchorId = {$uid} AND cl.type < {$this->config->consumeType->coinType} ".
                " AND cl.createTime BETWEEN {$timeBegin} AND {$timeEnd} ".
                " GROUP BY from_unixtime(cl.createTime, '%Y%M%D'),cl.familyId ORDER BY cl.createTime DESC";

            $query = $this->modelsManager->createQuery($sql);
            $records = $query->execute();

            $data['record'] = array();
            if($records->valid()){
                foreach($records as $record){
                    $sum += $record->thisSum;
                    $consume['familyId'] = $record->cl->familyId;
                    $consume['uid'] = $record->cl->uid;
                    $consume['amount'] = $record->thisSum;
                    $consume['createTime'] = $record->cl->createTime;
                    $consume['familyName'] = $record->f->name;
                    $consume['familyShortName'] = $record->f->shortName;
                    $consume['familyLogo'] = $record->f->logo;
                    $data['record'][] = $consume;
                }
            }
            $data['sum'] = $sum;

        }catch (\Exception $e) {
            $this->errLog('getAnchorConsumeByDay errorMessage = ' . $e->getMessage());
            return array('sum'=>0,'record'=>array());
        }
        return $data;
    }

    /**
     * 获得家族皮肤配置
     *
     * @param $fid
     * @return mixed
     */
    public function getFamilySkinInfo($fid){
        try{
            $familySkinInfo = FamilySkin::findFirst($fid);
            if(empty($familySkinInfo)){
                // 获得默认配置
                $familySkin = $this->config->familySkin;
                if($familySkin){
                    foreach($familySkin as &$val){
                        $val['backgroundImg'] = $this->url->getStatic($val['backgroundImg']);
                        $val['smallBackgroundImg'] = $this->url->getStatic($val['smallBackgroundImg']);
                    }
                }else{
                    $familySkin = array();
                }

                if($familySkin){
                    $familySkinInfo = $familySkin[0];
                }else{
                    return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $familySkinInfo);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 更新家族皮肤配置
     *
     * @param $fid
     * @param $backgroudColor
     * @param $styleType
     * @return mixed
     */
    public function updateFamilySkin($backgroundColor, $styleType, $backgroundImg = ''){
        $postData['id'] = $styleType;
        $postData['content'] = $backgroundColor;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        // 获得家族信息
        $familyRes = $this->getFamilyInfoByUid($uid);
        if($familyRes['code'] == $this->status->getCode('OK')){
            $familyInfo = $familyRes['data'];
            if($familyInfo['creatorUid'] != $uid){
                return $this->status->retFromFramework($this->status->getCode('CREATOR_NO_FOUND'));
            }

            $fid = $familyInfo['id'];
        }else{
            return $this->status->retFromFramework($familyRes['code'], $familyRes['data']);
        }

        if(empty($backgroundImg)){
            $picRes = $this->uploadFamilySkinPic($fid);
            if($picRes['code'] == $this->status->getCode('OK')){
                $backgroundImg = $picRes['data'];
            }else{
                $backgroundImg = '';
            }
        }


        try {
            $familySkin = FamilySkin::findFirst($fid);
            if($familySkin){
                // 更新数据
                $familySkin->backgroundColor = $backgroundColor;
                $familySkin->backgroundImg = $backgroundImg;
                $familySkin->styleType = $styleType;
                $ret = $familySkin->save();
            }else{
                $familySkin = new FamilySkin();
                $familySkin->fid = $fid;
                $familySkin->backgroundColor = $backgroundColor;
                $familySkin->backgroundImg = $backgroundImg;
                $familySkin->styleType = $styleType;
                $ret = $familySkin->save();
            }

            if($ret){
                return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
            }else{
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 上传家族皮肤
     *
     * @param string $fid
     * @return mixed
     */

    public function uploadFamilySkinPic($fid = ''){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();

        if ($this->request->hasFiles()) {
            try {
                foreach ($this->request->getUploadedFiles() as $key => $file) {
                    $fileNameArray = explode('.', strtolower($file->getName()));
                    $fileExt = $fileNameArray[count($fileNameArray) - 1];
                    $dirName = $fid;
                    $filePath = $this->pathGenerator->getFamilySkinPath($uid, $dirName);
                    $fileName = time() . '.'  . $fileExt;
                    $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                    try {
                        $skin = $this->pathGenerator->getFullFamilySkinPath($uid, $dirName, $fileName);
                    } catch (\Exception $e) {
                        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                    }
                }

                return $this->status->retFromFramework($this->status->getCode('OK'), $skin);
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
            }
        } else {
            return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
        }
    }

    /**
     * 上传家族图标
     *
     * @param string $fid
     * @return mixed
     */

    public function uploadFamilyLogoPic($fid = ''){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        if(empty($fid)){
            $fid = $uid;
        }

        if ($this->request->hasFiles()) {
            try {
                foreach ($this->request->getUploadedFiles() as $key => $file) {
                    $fileNameArray = explode('.', strtolower($file->getName()));
                    $fileExt = $fileNameArray[count($fileNameArray) - 1];
                    $dirName = $fid;
                    $filePath = $this->pathGenerator->getFamilyLogoPath($uid, $dirName);
                    $fileName = time() . '.'  . $fileExt;
                    $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                    try {
                        $logo = $this->pathGenerator->getFullFamilyLogoPath($uid, $dirName, $fileName);
                        $this->session->set($this->config->websiteinfo->familyapplyurlkey, $logo);
                    } catch (\Exception $e) {
                        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                    }
                }

                return $this->status->retFromFramework($this->status->getCode('OK'), $logo);
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
            }
        } else {
            return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
        }
    }

    public function getFamilyAnchorRank($familyId = 0, $type = 'gift', $dtype = 'week'){
        try {
            $type = 'family_anchor_' . $type . '_' . $dtype;
            $index = $this->config->rankLogType[$type];
            $result = \Micro\Models\RankLog::findFirst('index = ' . $index);
            $data = array();
            if(!empty($result)){
                $content = $result->content;
                $content = json_decode($content, true);
                $data = isset($content[$familyId]) ? $content[$familyId] : array();
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getFamilyStatus($uid = 0){
        try {
            $res = \Micro\Models\SignAnchor::findFirst('uid = ' . $uid . ' order by id desc');
            if($res && !$res->familyId && !in_array($res->status, array(0,3,4))){
                return 1;
            }
            return 0;
        } catch (\Exception $e) {
            $this->errLog('getFamilyStatus errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * 更新银行账号信息
     * @param bank       银行
     * @param cardNumber 卡号
     */

    public function updateAccountInfo($bank = '', $cardNumber = '', $realName = '', $ID = '', $smsCode = '', $sys = 0){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        if($sys == 0){
            // 非系统更新，则需要验证手机验证码
            $userData = $user->getUserInfoObject()->getUserInfo();
            if(empty($userData['telephone'])){
                return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
            }

//            $smsCode_right = $this->session->get($this->config->websiteinfo->user_bank_account);
//            $time = $this->session->get($this->config->websiteinfo->user_bank_account_time);
//            if (time() - $time > 180) {
//                return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//            }
//
//            if($smsCode_right != $smsCode){
//                return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//            }
             //验证验证码是否输入正确
            //改为从数据库验证 edit by 2015/10/20
            $smsCheckResult = UserReg::checkSmsCaptcha($userData['telephone'], $this->config->sms_template->updateAccount, $smsCode);
            if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
                return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
            }
        }


        try {
            $userInfo = \Micro\Models\UserInfo::findFirst("uid={$uid}");
            if($bank){
                $userInfo->bank = $bank;
            }

            if($cardNumber){
                $userInfo->cardNumber = $cardNumber;
            }

            if($realName){
                $userInfo->realName = $realName;
            }

            if($ID){
                $userInfo->ID = $ID;
            }

            $ret = $userInfo->save();
            if($ret){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }else {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            }
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    /**
     * 获得银行账号信息
     * @param bank       银行
     * @param cardNumber 卡号
     */
    public function getAccountBankInfo(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        try {
            $userInfo = \Micro\Models\UserInfo::findFirst("uid={$uid}")->toArray();
            $data['uid'] = $userInfo['uid'];
            $data['realName'] = $userInfo['realName'];
            $data['bank'] = $userInfo['bank'];
            $data['idCard'] = strlen($userInfo['ID']) > 8 ? substr_replace($userInfo['ID'], str_repeat('*', strlen($userInfo['ID']) - 8), 5, strlen($userInfo['ID']) - 8) : $userInfo['ID'];
            $data['cardNumber'] = strlen($userInfo['cardNumber']) > 7 ? substr_replace($userInfo['cardNumber'], str_repeat('*', strlen($userInfo['cardNumber']) - 7), 6, strlen($userInfo['cardNumber']) - 7) : $userInfo['cardNumber'];
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 新上传家族海报接口
     *
     * @return mixed
     */
    public function uploadNewFamilyPoster($fid = 0) {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                // 自身业务的验证
                $userdata = $this->session->get($this->config->websiteinfo->authkey);
                $uid = $userdata['uid'];
                if (empty($uid)) {
                    return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
                }

                $postData['id'] = $fid;
                $isValid = $this->validator->validate($postData);
                if (!$isValid) {
                    $errorMsg = $this->validator->getLastError();
                    return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
                }

                $familyInfo = Family::findFirst("id=" . $fid);
                if(empty($familyInfo)){
                    return $this->status->retFromFramework($this->status->getCode('FAMILY_NOT_EXISTS'));
                }

                if($familyInfo->creatorUid != $uid){
                    return $this->status->retFromFramework($this->status->getCode('CREATOR_NO_FOUND'));
                }

                try {
                    foreach ($this->request->getUploadedFiles() as $file) {
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        $filePath = $this->pathGenerator->getFamilyPosterPath($fid);
                        $fileName = time() . '.' . $fileExt;
                        $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        try {
                            $poster = $this->pathGenerator->getFullFamilyPosterPath($fid, $fileName);
                            // 更新家族海报
                            $familyInfo->logo = $poster;
                            $familyInfo->save();
                            return $this->status->retFromFramework($this->status->getCode('OK'), $poster);
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }

        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }
}