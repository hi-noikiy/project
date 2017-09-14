<?php
namespace Micro\Frameworks\Logic\User\UserData;

use Phalcon\DI\FactoryDefault;

use Micro\Models\ConsumeLog;
use Micro\Models\Rooms;
use Micro\Frameworks\Logic\User\UserFactory;

class UserFocus extends UserFocusDb
{
    protected $collection;

    protected $user;

    public function __construct($uid, $user)
    {
        parent::__construct($uid);
        $this->user = $user;
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 被关注(粉丝)集合，操作接口
    // 接口都是只有uid，但是需要转换成accountId，这样在聊天服务器中可以做关注转发
    //
    ////////////////////////////////////////////////////////////////////////

    /**
     * 添加关注
     * @param targetId 当前uid想要关注的用户uid(成为targetId的粉丝)
     * @return 返回操作结果
     */
    public function addFollow($targetId) {
        if ($this->checkIsOwnOper($targetId)) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_OPER_OWNER'));
        }

        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $target = UserFactory::getInstance($targetId);
        $targetAccountId = $target->getUserInfoObject()->getAccountId();

        //echo $targetAccountId.$accountId;
        // 判断是否已关注
        if ($this->isFollowDb($targetAccountId, $accountId) || $this->isOwnFollowDb($accountId, $targetAccountId)) {
            return $this->status->retFromFramework($this->status->getCode('IS_FOLLOWED'));
        }
        // 判断是否需要修改为互相关注
        $eachFollow = 0;
        if ($this->isOwnFollowDb($targetAccountId, $accountId)) {
            $eachFollow = 1;
        }

        $time = time();
        //如何使用事务?
        $result = $this->addFollowDb($targetAccountId, $accountId, $this->uid, $time);
        if ($result != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($result);
        }

        $result = $this->addOwnFollowDb($accountId, $targetAccountId, $targetId, $time, $eachFollow);
        if ($result != $this->status->getCode('OK')) {
            $this->delFollowDb($targetAccountId, $accountId);              //添加记录失败，回退
            return $this->status->retFromFramework($this->status->getCode('MULTIOPER_FAILED'));
        }

        if ($eachFollow == 1) {                             //是互相关注的用户、需要修改另外一端
            $result = $this->updateOwnFollowEachFollowDb($targetAccountId, $accountId, $eachFollow);
            if (!$result) {                                 //添加记录失败，回退
                $this->delFollowDb($targetAccountId, $accountId); 
                $this->delOwnFollowDb($accountId, $targetAccountId);
                return $this->status->retFromFramework($this->status->getCode('MULTIOPER_FAILED'));
            }
        }
            
	    //添加新手任务
        //$this->taskMgr->setUserTask($this->uid, $this->config->taskIds->setFocus);
        
       
        //修改主播粉丝经验、粉丝等级
        $return=$target->getUserFoucusObject()->setFocusExp($target);



        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }

    /**
     * 删除关注
     * @param targetId 想删除关注的用户uid
     * @return 返回操作结果
     */
    public function delFollow($targetId) {
        if ($this->checkIsOwnOper($targetId)) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_OPER_OWNER'));
        }

        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $target = UserFactory::getInstance($targetId);
        $targetAccountId = $target->getUserInfoObject()->getAccountId();

        // 判断原来是否是互相关注
        $eachFollow = 0;
        if ($this->isOwnFollowDb($targetAccountId, $accountId)) {
            $eachFollow = 1;
        }

        $result = $this->delFollowDb($targetAccountId, $accountId);
        if ($result != $this->status->getCode('OK')) {
            return $this->status->retFromFramework($result);
        }

        $result = $this->delOwnFollowDb($accountId, $targetAccountId);
        if ($result != $this->status->getCode('OK')) {
            $time = time();
            $result = $this->addFollowDb($targetAccountId, $accountId, $this->uid, $time);        //删除记录失败，需要回退
            return $this->status->retFromFramework($this->status->getCode('MULTIOPER_FAILED'));
        }

        if ($eachFollow == 1) {
            $result = $this->updateOwnFollowEachFollowDb($targetAccountId, $accountId, 0);
            if (!$result) {
                $time = time();
                $this->addFollowDb($targetAccountId, $accountId, $this->uid, $time);
                $this->addOwnFollowDb($accountId, $targetAccountId, $targetId, $time, $eachFollow);  //回退成互相关注
                return $this->status->retFromFramework($this->status->getCode('MULTIOPER_FAILED'));
            }
        }
        
        //修改主播粉丝经验、粉丝等级
        $return=$target->getUserFoucusObject()->setFocusExp($target);

        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }

    /**
     * 获取粉丝数，也就是被关注人数
     * @return int 数量
     */
    public function getFansCount() {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $totalNum = $this->getFollowCountDb($accountId);

        return $totalNum;
    }

    /**
     * 根据时间获取被关注人数
     * @param time 大于或者小于该时间
     * @return int 数量
     */
    public function getFansCountByTime($time, $isBefore=false) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $totalNum = $this->getFollowCountByTimeDb($accountId, $time, $isBefore);

        return $totalNum;
    }

    /**
     * 根据时间间隔获取被关注人数
     * @param beginTime 大于该时间
     * @param endTime 小于该时间
     * @return int 数量
     */
    public function getFansCountBetweenTime($beginTime, $endTime) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $totalNum = $this->getFollowCountBetweenTimeDb($accountId, $beginTime, $endTime);

        return $totalNum;
    }

    /**
     * 获取所有的被关注的信息
     */
    /*public function getAllFansList($beginTime, $endTime) {
        $result = $this->getAllFollowListDb($beginTime, $endTime);
        return $result;
    }*/

    /**
     * 获取粉丝的信息列表
     * @param type : 0-总榜 1-近30天
     * @param topCount : 排名前几的显示，默认为6
     * @return json字符串，uid和关注id列表
     */
    public function getFansList($type, $topCount=6) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        $resultFollow = $this->getFollowListDb($accountId);
        $uidsList = array();
        if (count($resultFollow) > 0)
        {
            for ($i=0; $i<count($resultFollow["fids"]); $i++) {
                // 代码不要删除，也许后面会有用**
                // $data['uid'] = $resultFollow["fids"][$i]['fid'];
                // $data['extuid'] = $resultFollow["fids"][$i]['extfid'];
                // $data['time'] = $resultFollow["fids"][$i]['time'];
                // array_push($uidsList, $data);
                array_push($uidsList, $resultFollow["fids"][$i]['extfid']);
            }
        }
        return $this->processFansList($type, $topCount, $uidsList);
    }

    /**
     * 获取粉丝的信息列表
     * @param type : 0-总榜 1-近30天
     * @param topCount : 排名前几的显示，默认为6
     * @return json字符串，uid和关注id列表
     */
    public function getFansUidList() {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $resultFollow = $this->getFollowListDb($accountId);
        $uidsList = array();
        if (count($resultFollow) > 0)
        {
            for ($i=0; $i<count($resultFollow["fids"]); $i++) {
                array_push($uidsList, $resultFollow["fids"][$i]['extfid']);
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $uidsList);
    }

    /**
     * 根据时间获取当前用户的粉丝列表
     * @param type : 0-总榜 1-近30天
     * @param topCount : 排名前几的显示，默认为6
     * @param timesort 按时间排序方式 1 正序，-1 倒序
     * @param skip 分页用，跳过指定个数
     * @param limit 分页用，获取指定个数记录
     * @return json字符串，uid和关注id列表
     */
    public function getFansListEx($type, $topCount=6, $timesort='', $offset='', $num='') {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        $arrayResult = $this->getFollowListExDb($accountId, $timesort, $offset, $num);
        $uidsList = array();
        if ($arrayResult['ok'] == 1) {
            if (count($arrayResult['result']) > 0) {
                for ($i = 0; $i<count($arrayResult['result']); $i++) {
                    // $data['uid'] = $arrayResult['result'][$i]['fids']['fid'];
                    // $data['extuid'] = $arrayResult['result'][$i]['fids']['extfid'];
                    // $data['time'] = $arrayResult['result'][$i]['fids']['time'];
                    // array_push($uidsList, $data);
                    array_push($uidsList, $arrayResult['result'][$i]['fids']['extfid']);
                }
            }
        }

        return $this->processFansList($type, $topCount, $uidsList);
    }

    /**
     * 根据时间获取当前用户的粉丝列表
     * @param type : 0-总榜 1-近30天
     * @param topCount : 排名前几的显示，默认为6
     * @param timesort 按时间排序方式 1 正序，-1 倒序
     * @param skip 分页用，跳过指定个数
     * @param limit 分页用，获取指定个数记录
     * @return json字符串，uid和关注id列表
     */
    public function getNewFansListEx($type = 0, $timesort = '', $page = 1, $pageSize = 10) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        $arrayResult = $this->getFollowListExDb($accountId, $timesort);
        $uidsList = array();
        $data = $newData = array();
        if ($arrayResult['ok'] == 1) {
            if (count($arrayResult['result']) > 0) {
                for ($i = 0; $i<count($arrayResult['result']); $i++) {
                    array_push($uidsList, $arrayResult['result'][$i]['fids']['extfid']);
                }
            }
        }

        $res = $this->getFansContribute($type, $uidsList, $page, $pageSize);
        return $this->status->retFromFramework($this->status->getCode('OK'), $res['data']);

        /*$result = $this->processFansList($type, $topCount, $uidsList);
        if($result['code'] = $this->status->getCode('OK')){
            $data = $result['data'];
            if($data){
                $newData = array_slice($data, $offset, $num);
            }
        }

        $data = array(
            'list' => $newData,
            'count' => count($data),
        );
        return $this->status->retFromFramework($this->status->getCode('OK'), $data);*/
    }

    public function getNewFans($page = 1, $pageSize = 10) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        $arrayResult = $this->getFollowListExDb($accountId, '');
        $uidsList = array();
        $data = $newData = array();
        if ($arrayResult['ok'] == 1) {
            if (count($arrayResult['result']) > 0) {
                for ($i = 0; $i<count($arrayResult['result']); $i++) {
                    array_push($uidsList, $arrayResult['result'][$i]['fids']['extfid']);
                }
            }
        }

        $res = $this->getFansContributeNew($uidsList, $page, $pageSize);
        return $this->status->retFromFramework($res['code'], $res['data']);
    }

    // 获取粉丝贡献
    public function getFansContributeNew($uidsList = array(), $page = 1, $pageSize = 6){
        try {
            if(count($uidsList) < 1){
                return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>array(),'count'=>0));
            }
            // 公用sql
            !$page && $page = 1;
            !$pageSize && $pageSize = 6;
            $types = $this->config->consumeTypeAnchor;
            $limit = ($page - 1) * $pageSize;
            $sql = 'select cl.uid,ifnull(sum(cl.amount), 0) as total,ui.avatar,ui.nickName,up.level3 from \Micro\Models\ConsumeDetailLog as cl '
                . ' left join \Micro\Models\UserInfo as ui on cl.uid = ui.uid '
                . ' left join \Micro\Models\UserProfiles as up on cl.uid = up.uid '
                . ' where cl.receiveUid = ' . $this->uid . ' and cl.type in (' . $types . ') and cl.uid in ( ' . implode(',', $uidsList) . ')'
                . ' group by cl.uid order by total desc, up.level3 desc '
                . ' limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $resData = array();
            $count = 0;
            if($res->valid()){
                foreach ($res as $k => $val) {
                    $data['uid'] = $val->uid;
                    $data['amount'] = floor($val->total);
                    $data['nickName'] = $val->nickName;
                    $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $data['richerLevel'] = $val->level3;
                    array_push($resData, $data);
                }
            }
            $sqlCount = 'select cl.uid from \Micro\Models\ConsumeDetailLog as cl '
                . ' where cl.receiveUid = ' . $this->uid . ' and cl.type in (' . $types . ') and cl.uid in ( ' . implode(',', $uidsList) . ')'
                . ' group by cl.uid';
            $queryCount = $this->modelsManager->createQuery($sqlCount);
            $countRes = $queryCount->execute();
            $count = count($countRes);

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$resData,'count'=>$count));
        } catch (\Exception $e) {
            $this->errLog('getFansContributeNew error errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 根据时间获取当前用户的粉丝列表
     * @param type : 0-总榜 1-近30天
     * @param topCount : 排名前几的显示，默认为6
     * @param timesort 按时间排序方式 1 正序，-1 倒序
     * @param skip 分页用，跳过指定个数
     * @param limit 分页用，获取指定个数记录
     * @return json字符串，uid和关注id列表
     */
    public function getMobileFansListEx($uidList, $timesort='', $offset='', $num='') {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $arrayResult = $this->getFollowListExDb($accountId, $timesort, $offset, $num);
        $uidsList = array();
        if ($arrayResult['ok'] == 1) {
            if (count($arrayResult['result']) > 0) {
                for ($i = 0; $i<count($arrayResult['result']); $i++) {
                    array_push($uidsList, $arrayResult['result'][$i]['fids']['extfid']);
                }
            }
        }

        return $this->processMobileList($uidsList, $uidList);
    }

    /**
     * @param $uidsList
     */
    private function processMobileList($uidsList, $uidList){
        $data = array();
        if($uidsList){
            foreach($uidsList as $val){
                if(!empty($uidList) && !array_key_exists($val, $uidList)){
                    continue;
                }

                $tmpData = $this->userMgr->getMobileUserInfo($val);
                $res = $tmpData['data'];
                $result['uid'] = $res['uid'];
                $result['nickName'] = $res['nickName'];
                $result['avatar'] = $res['avatar'];
                $result['vipLevel'] = $res['vipLevel'];
                $result['anchorLevel'] = $res['anchorLevel'];
                $result['richerLevel'] = $res['richerLevel'];
                $result['fansLevel'] = $res['fansLevel'];
                $result['charmLevel'] = $res['charmLevel'];
                $result['roomId'] = $res['roomId'];
                $result['liveStatus'] = $res['liveStatus'];
                $result['isOpenVideo'] = $res['isOpenVideo'];
                $result['showStatus'] = $res['showStatus'];
                $fansRes = $this->userMgr->isFans($val);
                $result['isFans'] = $fansRes['code'] == $this->status->getCode('OK') ? $fansRes['data']['result'] : 0;
                $data[] = $result;
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }

    // 获取粉丝贡献
    public function getFansContribute($type = 0, $uidsList = array(), $page = 1, $pageSize = 10){
        try {
            // 条件
            $condition = $type == 1 ? ' and cl.createTime >= ' . strtotime(date('Y-m-d',strtotime('-30 days'))) : '';
            // 公用sql
            $sql = 'select cl.uid,ifnull(sum(cl.amount), 0) as total,ui.avatar,ui.nickName,up.level3 from \Micro\Models\ConsumeDetailLog as cl '
                . ' left join \Micro\Models\UserInfo as ui on cl.uid = ui.uid '
                . ' left join \Micro\Models\UserProfiles as up on cl.uid = up.uid '
                . ' where cl.receiveUid = ' . $this->uid . $condition . ' and cl.type in (3,4,5) and cl.uid in ( ' . implode(',', $uidsList) . ')'
                . ' group by cl.uid order by total desc, up.level3 desc ';
            //前三
            $sqlTop = $sql . ' limit 0,3';
            $queryTop = $this->modelsManager->createQuery($sqlTop);
            $topRes = $queryTop->execute();
            $topData = array();
            if($topRes->valid()){
                foreach ($topRes as $k => $val) {
                    $data['uid'] = $val->uid;
                    $data['amount'] = floor($val->total);
                    $data['nickName'] = $val->nickName;
                    $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $data['richerLevel'] = $val->level3;
                    array_push($topData, $data);
                }
            }

            // 其他数据  分页
            $resultData = array();
            $count = 0;
            if(count($topRes) >= 3){
                $limit = ($page - 1) * $pageSize + 3;
                $sqlOther = $sql . ' limit ' . $limit . ',' . $pageSize;
                $queryOther = $this->modelsManager->createQuery($sqlOther);
                $listDatas = $queryOther->execute();
                if ($listDatas->valid()) {
                    $start = $limit;
                    $i = 1;
                    foreach ($listDatas as $val) {
                        $data['uid'] = $val->uid;
                        $data['amount'] = floor($val->total);
                        $data['nickName'] = $val->nickName;
                        $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                        $data['richerLevel'] = $val->level3;
                        $data['rankNum'] = $limit + $i;
                        array_push($resultData, $data);
                        $i++;
                    }
                    $sqlCount = 'select cl.uid from \Micro\Models\ConsumeDetailLog as cl '
                        . ' where cl.receiveUid = ' . $this->uid . $condition . ' and cl.type in (3,4,5) and cl.uid in ( ' . implode(',', $uidsList) . ')'
                        . ' group by cl.uid';
                    $queryCount = $this->modelsManager->createQuery($sqlCount);
                    $countRes = $queryCount->execute();
                    $total = count($countRes);
                    $count = $total > 3 ? $total - 3 : 0;
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('topData'=>$topData,'data'=>$resultData,'count'=>$count));
        } catch (\Exception $e) {
            $this->errLog('getFansContribute error username= errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    private function processFansList($type, $topCount, $uidsList, $mobile = 0) {
        $resultData = array();
        //查找符合条件的记录
        $having = "total>0";
        if($mobile > 0){
            $having = "total>=0";
        }

        $phql = '';
        if(!empty($uidsList)){
            switch ($type) {
                case 0: {
                    $phql = "SELECT a.uid, sum(a.amount) as total, b.nickName, b.avatar, c.level1,c.level2,c.level3,c.level4 FROM \Micro\Models\ConsumeDetailLog a, \Micro\Models\UserInfo b, \Micro\Models\UserProfiles c".
                        " WHERE a.uid = b.uid AND b.uid = c.uid  AND a.receiveUid = ".$this->uid. " and a.type in (3,4,5) ".
                        " AND a.uid in(" . implode(',', $uidsList) . ')'.
                        " group by a.uid having({$having}) order by total desc, a.uid desc limit ".$topCount;
                    break;
                }
                case 1: {
                    $timeline = strtotime('-30 days');
                    $phql = "SELECT a.uid, sum(a.amount) as total, b.nickName, b.avatar, c.level1,c.level2,c.level3,c.level4 FROM \Micro\Models\ConsumeDetailLog a, \Micro\Models\UserInfo b, \Micro\Models\UserProfiles c".
                        " WHERE a.uid = b.uid AND b.uid = c.uid AND a.receiveUid = ".$this->uid. " and a.type in (3,4,5) ".
                        " AND a.uid in(" . implode(',', $uidsList) . ')'.
                        " AND a.createTime > ".$timeline.
                        " group by a.uid having({$having}) order by total desc, a.uid desc limit ".$topCount;
                    break;
                }
                default:
                    $this->errLog("processFansList type error : type = ".$type);
                    return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
            }

            $query = $this->modelsManager->createQuery($phql);
            $listDatas = $query->execute();
            if ($listDatas->valid()) {
                foreach ($listDatas as $val) {
                    $userRoomInfo = $this->roomModule->getRoomMgrObject()->getRoomInfo(NULL, $val->uid);
                    $data['uid'] = $val->uid;
                    $data['amount'] = $val->total;
                    $data['nickName'] = $val->nickName;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }

                    $data['vipLevel'] = $val->level1;
                    $data['anchorLevel'] = $val->level2;
                    $data['richerLevel'] = $val->level3;
                    $data['fansLevel'] = $val->level4;
                    $fansRes = $this->userMgr->isFans($val->uid);
                    $data['isFans'] = $fansRes['code'] == $this->status->getCode('OK') ? $fansRes['data']['result'] : 0;
                    $data['liveStatus'] = $userRoomInfo ? $userRoomInfo->liveStatus : 0;
                    $data['roomId'] = $userRoomInfo ? $userRoomInfo->roomId : 0;
                    array_push($resultData, $data);
                }
            }
        }


        return $this->status->retFromFramework($this->status->getCode('OK'), $resultData);
    }

    /**
     * 判断当前用户是否是目标用户的粉丝
     * @param targetId 判断当前用户是否是目标用户的粉丝
     * @return bool 返回操作结果是否存在
     */
    public function isFans($targetId) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $target = UserFactory::getInstance($targetId);
        $targetAccountId = $target->getUserInfoObject()->getAccountId();

        return $this->isFollowDb($targetAccountId, $accountId);
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 关注集合，操作接口
    // 接口都是只有uid，没有需要关注accountId
    //
    ////////////////////////////////////////////////////////////////////////

    /**
     * 获取关注的人数
     * @return int 数量
     */
    public function getOwnFollowCount() {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        return $this->getOwnFollowCountDb($accountId);
    }

    /**
     * 根据时间获取关注人数
     * @param time 大于该时间
     * @return int 数量
     */
    public function getOwnFollowCountByTime($time) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        return $this->getOwnFollowCountByTimeDb($accountId, $time);
    }

    /**
     * 获取关注的信息列表
     * @return json字符串，uid和关注id列表
     */
    public function getOwnFollowList($sortType) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        $resultFollow = $this->getOwnFollowListDb($accountId);
        $uidsList = array();
        if (count($resultFollow) > 0)
        {
          for ($i=0; $i<count($resultFollow["fids"]); $i++) {
            // $data['uid'] = $resultFollow["fids"][$i]['fid'];
            // $data['extuid'] = $resultFollow["fids"][$i]['extfid'];
            // $data['time'] = $resultFollow["fids"][$i]['time'];
            // $data['focus'] = $resultFollow["fids"][$i]['focus'];
            // $data['userdata'] = $resultFollow["fids"][$i]['userdata'];
            // array_push($resultData, $data);
            array_push($uidsList, $resultFollow["fids"][$i]['extfid']);
          }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $uidsList);
//        return $this->processFollowList($sortType, $uidsList);
    }
    /**
     * 根据时间获取当前用户关注的列表
     * @param sortType 0-关注时间,1-主播等级,2-富豪等级
     * @param findUid 需要查找的uid
     * @param foucs 按照是否重点关注标示获取数据 0表示非重点关注、1表示重点关注 默认为空，表示全部获取
     * @param eachFollow 按照是否互相关注标示获取数据 0表示非互相关注、1表示互相关注 默认为空，表示全部获取
     * @param timesort 按时间排序方式 1 正序，-1 倒序
     * @param skip 分页用，跳过指定个数
     * @param limit 分页用，获取指定个数记录
     * @return json字符串，uid和关注id列表
     */
    public function getOwnMobileFollowListEx($sortType, $uidList = array(), $focus='', $eachFollow='', $timesort='', $offset='', $num='') {
        if ($sortType == 0) {
            $timesort = -1;
        }

        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        $IsAfterProcess = FALSE;
        if (($focus === '') && ($eachFollow === '')) {
            $arrayResult = $this->getOwnFollowListExDb($accountId, $timesort, $offset, $num);
        }
        else {
            $arrayResult = $this->getOwnFollowListExDb($accountId, $timesort); //后处理 跳过和总数记录
            $IsAfterProcess = TRUE;
        }

        $uidsList = array();
        $currOffset = 0;
        $currTotalNum = 0;
        $dataList = array();
        if ($arrayResult['ok'] == 1) {
            if (count($arrayResult['result']) > 0) {
                for ($i = 0; $i<count($arrayResult['result']); $i++) {
                    if ($IsAfterProcess) {
                        if ($focus !== '') {
                            if ($focus != $arrayResult['result'][$i]['fids']['focus']) {
                                continue;
                            }
                        }
                        if ($eachFollow !== '') {
                            if ($eachFollow != $arrayResult['result'][$i]['fids']['eachfollow']) {
                                continue;
                            }
                        }
                        if ($offset !== '') {
                            if ($currOffset < $offset) {
                                $currOffset++;
                                continue;
                            }
                        }
                        if ($num !== '') {
                            if ($currTotalNum >= $num) {
                                break;      //已经收集足够数量，跳出
                            }
                        }
                    }

                    $extUid = $arrayResult['result'][$i]['fids']['extfid'];
                    array_push($uidsList, $extUid);
                    $dataList[$extUid] = array(
                        "focus" => $arrayResult['result'][$i]['fids']['focus'],
                        "userdata" => $arrayResult['result'][$i]['fids']['userdata']
                    );

                    $currTotalNum++;
                }
            }
        }

        return $this->processMobileList($uidsList, $uidList);
    }
    /**
     * 根据时间获取当前用户关注的列表
     * @param sortType 0-关注时间,1-主播等级,2-富豪等级
     * @param findUid 需要查找的uid
     * @param foucs 按照是否重点关注标示获取数据 0表示非重点关注、1表示重点关注 默认为空，表示全部获取
     * @param eachFollow 按照是否互相关注标示获取数据 0表示非互相关注、1表示互相关注 默认为空，表示全部获取
     * @param timesort 按时间排序方式 1 正序，-1 倒序
     * @param skip 分页用，跳过指定个数
     * @param limit 分页用，获取指定个数记录
     * @return json字符串，uid和关注id列表
     */
    public function getOwnFollowListEx($sortType, $findUid='', $focus='', $eachFollow='', $timesort='', $offset='', $num='') {
        if ($sortType == 0) {
            $timesort = -1;
        }

        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();

        $IsAfterProcess = FALSE;
        if (($focus === '') && ($eachFollow === '')) {
            $arrayResult = $this->getOwnFollowListExDb($accountId, $timesort, $offset, $num);
        }
        else {
            $arrayResult = $this->getOwnFollowListExDb($accountId, $timesort); //后处理 跳过和总数记录
            $IsAfterProcess = TRUE;
        }

        $uidsList = array();
        $currOffset = 0;
        $currTotalNum = 0;
        $dataList = array();
        if ($arrayResult['ok'] == 1) {
            if (count($arrayResult['result']) > 0) {
                for ($i = 0; $i<count($arrayResult['result']); $i++) {
                    if ($IsAfterProcess) {
                        if ($focus !== '') {
                            if ($focus != $arrayResult['result'][$i]['fids']['focus']) {
                                continue;
                            }
                        }
                        if ($eachFollow !== '') {
                            if ($eachFollow != $arrayResult['result'][$i]['fids']['eachfollow']) {
                                continue;
                            }
                        }
                        if ($offset !== '') {
                            if ($currOffset < $offset) {
                                $currOffset++;
                                continue;
                            }
                        }
                        if ($num !== '') {
                            if ($currTotalNum >= $num) {
                                break;      //已经收集足够数量，跳出
                            }
                        }
                    }

                    // $data['uid'] = $arrayResult['result'][$i]['fids']['fid'];
                    // $data['extuid'] = $arrayResult['result'][$i]['fids']['extfid'];
                    // $data['time'] = $arrayResult['result'][$i]['fids']['time'];
                    // $data['focus'] = $arrayResult['result'][$i]['fids']['focus'];
                    // $data['userdata'] = $arrayResult['result'][$i]['fids']['userdata'];
                    // array_push($uidsList, $data);
                    $extUid = $arrayResult['result'][$i]['fids']['extfid'];
                    array_push($uidsList, $extUid);
                    $dataList[$extUid] = array(
                        "focus" => $arrayResult['result'][$i]['fids']['focus'],
                        "userdata" => $arrayResult['result'][$i]['fids']['userdata']
                        );

                    $currTotalNum++;
                }
            }
        }

        return $this->processFollowList($sortType, $uidsList, $dataList, $findUid);
    }

    /**
     * 根据时间获取当前用户关注的列表
     * @param sortType 0-关注时间,1-主播等级,2-富豪等级
     * @param findUid 需要查找的uid
     * @param foucs 按照是否重点关注标示获取数据 0表示非重点关注、1表示重点关注 默认为空，表示全部获取
     * @param eachFollow 按照是否互相关注标示获取数据 0表示非互相关注、1表示互相关注 默认为空，表示全部获取
     * @param timesort 按时间排序方式 1 正序，-1 倒序
     * @param skip 分页用，跳过指定个数
     * @param limit 分页用，获取指定个数记录
     * @return json字符串，uid和关注id列表
     */
    public function getNewOwnFollowListEx($sortType, $nickName='', $orderType = 0, $focus='', $eachFollow='', $timesort='', $offset='', $num='') {
        if ($sortType == 0) {
            if($orderType == 0){
                $timesort = -1;
            }else{
                $timesort = 1;
            }

        }

        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $IsAfterProcess = FALSE;
        if (($focus === '') && ($eachFollow === '')) {
            $arrayResult = $this->getOwnFollowListExDb($accountId, $timesort);
        }else {
            $arrayResult = $this->getOwnFollowListExDb($accountId, $timesort); //后处理 跳过和总数记录
            $IsAfterProcess = TRUE;
        }

        $uidsList = array();
        $dataList = array();
        $data = $newData = $recList = array();
        if ($arrayResult['ok'] == 1) {
            if (count($arrayResult['result']) > 0) {
                for ($i = 0; $i<count($arrayResult['result']); $i++) {
                    if ($IsAfterProcess) {
                        if ($focus !== '') {
                            if ($focus != $arrayResult['result'][$i]['fids']['focus']) {
                                continue;
                            }
                        }

                        if ($eachFollow !== '') {
                            if ($eachFollow != $arrayResult['result'][$i]['fids']['eachfollow']) {
                                continue;
                            }
                        }
                    }

                    $extUid = $arrayResult['result'][$i]['fids']['extfid'];
                    array_push($uidsList, $extUid);
                    $dataList[$extUid] = array(
                        "focus" => $arrayResult['result'][$i]['fids']['focus'],
                        "userdata" => $arrayResult['result'][$i]['fids']['userdata'],
                        "time" => $arrayResult['result'][$i]['fids']['time']
                    );
                }
            }
        }

        $result = $this->processFollowList($sortType, $uidsList, $dataList, '', $orderType);
        if($result['code'] == $this->status->getCode('OK')){
            $data = $result['data'];
            if($data){
                // 如果昵称不空，则表示有按昵称搜索
                if(!empty($nickName)){
                    foreach($data as $key => $val){
                        if(strpos($val['nickName'], $nickName) === FALSE){
                            unset($data[$key]);
                        }
                    }

                    sort($data);
                }

                $newData = array_slice($data, $offset, $num);
            }else{
                $uid = $this->user->getUid();
                // 获得推荐主播,最多8个
                $recUidList = array();
                $recRes = Rooms::find("liveStatus=1 and showStatus=1 and uid!={$uid} order by totalNum desc limit 8");
                if($recRes){
                    foreach($recRes as $room){
                        $recUidList[] = $room->uid;
                    }
                }

                if(count($recUidList) < 8){
                    $leftLimit = 8 - count($recUidList);
                    // 获得未开播的粉丝数最多的主播
                    $sql = "select r.uid from Micro\Models\Rooms as r ,Micro\Models\UserProfiles as up where r.uid=up.uid and r.uid!={$uid} and r.liveStatus = 0 and r.showStatus=1 order by up.level4 desc limit " . $leftLimit;
                    $query = $this->modelsManager->createQuery($sql);
                    $res = $query->execute();
                    if($res){
                        foreach($res as $room){
                            $recUidList[] = $room->uid;
                        }
                    }
                }

                if($recUidList){
                    // 获得推荐列表信息
                    foreach($recUidList as $uid){
                        $tmpData = array();
                        $user = UserFactory::getInstance($uid);
                        if($user){
                            $userBaseInfo = $user->getUserInfoObject()->getUserInfo();
                            $userBaseData = $user->getUserInfoObject()->getData();
                            $tmpData['uid'] = $uid;
                            $tmpData['avatar'] = $userBaseInfo['avatar'];
                            $tmpData['nickName'] = $userBaseInfo['nickName'];
                            $tmpData['anchorLevel'] = $userBaseData['anchorLevel'];
                            $tmpData['fansLevel'] = $userBaseData['fansLevel'];
                            $recList[] = $tmpData;
                        }
                    }
                }
            }
        }

        $data = array(
            'recList' => $recList,
            'list' => $newData,
            'count' => count($data),
        );

        return $this->status->retFromFramework($this->status->getCode('OK'), $data);
    }


    private function processFollowList($sortType, $uidsList, $dataList, $findUid, $orderType = 0) {
        $resultData = array();
        //查找符合条件的记录
        $phql = '';
        if (count($uidsList) == 0) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $resultData);
        }

        if($orderType > 0){
            $orderPre = 'asc';
        }else{
            $orderPre = 'desc';
        }

        switch ($sortType) {
            case 0: {    //直接处理返回 
                $phql = "SELECT a.uid, a.nickName, a.avatar,b.level1, b.level2, b.level3,b.level4,c.liveStatus,c.roomId FROM \Micro\Models\UserInfo a, \Micro\Models\UserProfiles b, \Micro\Models\Rooms c".
                        " WHERE a.uid = b.uid and a.uid=c.uid"." AND a.uid in(" . implode(',', $uidsList) . ')';
                        //" order by instr(',".implode(',', $uidsList).",',',CONCAT(',',a.uid,','))";
                break;
            }
            case 1: {    //主播等级
                $phql = "SELECT a.uid, a.nickName, a.avatar, b.level1, b.level2, b.level3,b.level4,c.liveStatus,c.roomId FROM \Micro\Models\UserInfo a, \Micro\Models\UserProfiles b, \Micro\Models\Rooms c".
                        " WHERE a.uid = b.uid and a.uid=c.uid"." AND a.uid in(" . implode(',', $uidsList) . ')'.
                        " order by b.level2 $orderPre";
                break;
            }
            case 2: {    //富豪等级
                $phql = "SELECT a.uid, a.nickName, a.avatar, b.level1, b.level2, b.level3,b.level4,c.liveStatus,c.roomId FROM \Micro\Models\UserInfo a, \Micro\Models\UserProfiles b, \Micro\Models\Rooms c".
                        " WHERE a.uid = b.uid and a.uid=c.uid"." AND a.uid in(" . implode(',', $uidsList) . ')'.
                        " order by b.level3 $orderPre";
                break;
            }
            
            default:
                $this->errLog("processFollowList sortType error : sortType = ".$sortType);
                return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $query = $this->modelsManager->createQuery($phql);
        $listDatas = $query->execute();
        if ($listDatas->valid()) {
            foreach ($listDatas as $val) {
                if(strlen($findUid) > 0 && strpos($val->uid."", $findUid) === FALSE){
                    continue;
                }

                $data['uid'] = $val->uid;
                $data['vipLevel'] = $val->level1;
                $data['anchorLevel'] = $val->level2;
                $data['richerLevel'] = $val->level3;
                $data['fansLevel'] = $val->level4;
                $data['nickName'] = $val->nickName;
                $data['avatar'] = $val->avatar;
                $data['liveStatus'] = $val->liveStatus;
                $data['roomId'] = $val->roomId;
                if (empty($data['avatar'])) {
                    $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }

                $data['focus'] = $dataList[$data['uid']]['focus'];
                $data['userData'] = $dataList[$data['uid']]['userdata'];
                $data['addtime'] = $dataList[$data['uid']]['time'];
                $fansRes = $this->userMgr->isFans($val->uid);
                $data['isFans'] = $fansRes['code'] == $this->status->getCode('OK') ? $fansRes['data']['result'] : 0;
                array_push($resultData, $data);
            }

            // 如果按时间排序
            if($sortType == 0){
                if($orderType == 0){
                    // 降序
                    $resultData = $this->baseCode->arrayMultiSort($resultData, 'addtime', TRUE);
                }else{
                    // 升序
                    $resultData = $this->baseCode->arrayMultiSort($resultData, 'addtime', FALSE);
                }
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'), $resultData);
    }

    /**
     * 更新当前用户所关注的targetId为是否重点关注
     * @param targetId 关注的用户id
     * @param focus 是否重点关注 1 是 0 否
     * @return bool 返回修改结果成功失败
     */
    public function updateOwnFollowFocus($targetId, $focus) {
        $focus = intval($focus);
        //初始化信息，免得进数据库数据错误
        if (!(($focus === 1) || ($focus === 0)))
            return false;

        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $target = UserFactory::getInstance($targetId);
        $targetAccountId = $target->getUserInfoObject()->getAccountId();

        $result = $this->updateOwnFollowFocusDb($accountId, $targetAccountId, $focus);
        return $result;
    }

    /**
     * 更新当前用户所关注的targetId为是否为互相关注
     * @param targetId 关注的用户idf
     * @param focus 是否互相关注 1 是 0 否
     * @return bool 返回修改结果成功失败
     */
    /*protected function updateOwnFollowEachFollow($targetId, $eachFollow) {
        $eachFollow = intval($eachFollow);
        //初始化信息，免得进数据库数据错误
        if (!(($eachFollow === 1) || ($eachFollow === 0)))
            return false;

        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $target = UserFactory::getInstance($targetId);
        $targetAccountId = $target->getUserInfoObject()->getAccountId();

        $result = $this->updateOwnFollowEachFollowDb($accountId, $targetAccountId, $eachFollow);
        return $result;
    }*/

    /**
     * 更新当前用户所关注的targetId的UserData
     * @param targetId 关注的用户id
     * @param userdata 需要修改的内容
     * @return bool 返回修改结果成功失败
     */
    public function updateOwnFollowUserData($targetId, $userdata) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $target = UserFactory::getInstance($targetId);
        $targetAccountId = $target->getUserInfoObject()->getAccountId();

        $result = $this->updateOwnFollowUserDataDb($accountId, $targetAccountId, $userdata);
        return $result;
    }

    /**
     * 判断当前用户是否已关注targetId
     * @param targetId 被关注的用户uid
     * @return bool 返回操作结果是否存在
     */
    public function isOwnFollow($targetId) {
        // 转换成accountId
        $accountId = $this->user->getUserInfoObject()->getAccountId();
        $target = UserFactory::getInstance($targetId);
        $targetAccountId = $target->getUserInfoObject()->getAccountId();

        return $this->isOwnFollowDb($accountId, $targetAccountId);
    }

    //test begin ***************************************************************************
    /*public function addOwnFollow($uid, $fid, $extFid, $time, $eachFollow=0, $focus=0) {
        return $this->addOwnFollowDb($uid, $fid, $extFid, $time, $eachFollow, $focus);
    }

    public function delOwnFollow($uid, $fid) {
        return $this->delOwnFollowDb($uid, $fid);
    }*/
    //test end ***************************************************************************
    
    
    //修改用户的粉丝经验、粉丝等级
    public function setFocusExp($user) {
        $uid = $user->getUid();
        $userinfo = \Micro\Models\UserProfiles::findfirst("uid=" . $uid);
        if ($userinfo != false) {
            $fansNum = $user->getUserFoucusObject()->getFansCount();
            $fansConfig = \Micro\Models\FansConfigs::findfirst("lower<=" . $fansNum . " and higher>=" . $fansNum); //查询属于哪个等级
            $userinfo->exp4 = $fansNum;
            $userinfo->level4 = $fansConfig->level;
            $userinfo->save();
            $result['fansExp'] = $fansNum;
            $result['fansLevel'] = $fansConfig->level;
            //查询下一个等级经验
            $nextFansConfig = \Micro\Models\FansConfigs::findfirst("level>" . $fansConfig->level . " order by level asc");
            $result['fansNextExp'] = $nextFansConfig != false ? $nextFansConfig->lower : 0;
            $result['fansNextNeedExp'] = $result['fansNextExp'] ? $result['fansNextExp'] - $result['fansExp'] : 0; //距离下一级还需要多少经验
            return $result;
        }
        return;
    }

}