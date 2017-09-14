<?php

namespace Micro\Frameworks\Logic\Corn;

use Phalcon\DI\FactoryDefault;
use Micro\Models\RankLog;
use Micro\Models\WeekStarLog;
use Micro\Models\WeekStarInfoLog;
use Micro\Models\Rooms;
use Micro\Models\GiftConfigs;
use Micro\Frameworks\Logic\User\UserFactory;

define("DEFAULT_NUMBER", 100);

class Corn {

    protected $di;
    protected $mongo;
    protected $config;
    protected $status;
    protected $roomModule;
    protected $collectionFollow;
    protected $modelsManager;
    protected $baseCode;
    protected $pathGenerator;
    protected $storage;
    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->mongo = $this->di->get('mongo');
        $this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->roomModule = $this->di->get('roomModule');
        $this->collectionFollow = $this->mongo->collection('follow');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->baseCode = $this->di->get('baseCode');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->storage = $this->di->get('storage');
    }

    /**
     * 获取所有的被关注的信息列表
     */
    public function getAllFansList($beginTime, $endTime) {
        $result = array();
        $cursor = $this->collectionFollow->find(function($query) use($beginTime, $endTime) {
            $query->where("fids.time", array('$gt' => $beginTime, '$lt' => $endTime));
        });
        while ($ret = $cursor->getNext()) {
            array_push($result, $ret);
        }
        //$result = $this->collectionFollow->findOne();

        $resultData = array();
        if ($result != NULL) {
            if (count($result) > 0) {
                for ($i = 0; $i < count($result); $i++) {
                    $data['accountId'] = $result[$i]['uid'];
                    $data['count'] = count($result[$i]['fids']);
                    $resultData[$data['accountId']] = $data;
                }
            }
        }
        return $resultData;
    }

    //检查直播中的房间，检查更新的时间 是否超时
    public function checkRoomsLiveStatus() {
        try {
            $timeout = 9 * 60; //5分钟
            $time = time() - $timeout;
            $sql = 'UPDATE \Micro\Models\Rooms' .
                    ' SET liveStatus = 0 ' .
                    " WHERE liveStatus = 1 AND syncTime <" . $time;
            $query = $this->modelsManager->createQuery($sql);
            $query->execute();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //同步房间人数
    public function syncRoomNumberOfPeople() {
        try {
            $rooms = Rooms::find(array(
                        'columns' => 'roomId',
            ));
            $errLog = "";
            if ($rooms->valid()) {
                foreach ($rooms as $room) {
                    $result = $this->roomModule->getRoomMgrObject()->getCountInRoom($room->roomId);
                    if ($result['code'] == $this->status->getCode('OK')) {
                        $sql = "UPDATE Micro\Models\Rooms SET onlineNum={$result['data']['totalCount']} WHERE roomId={$room->roomId} ";
                        $query = $this->modelsManager->createQuery($sql);
                        $query->execute();
                    } else {
                        $errLog = "{$errLog}----roomid:{$room->roomId} code={$result['code']}";
                    }
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $errLog);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 排行榜:明星排行(根据主播收到聊币数量排名)
     * */

    public function genStarRank($type) {
        try {
            $time = $this->getTimeRange($type);
            /*$phql = 'SELECT ui.avatar,ui.nickName,ui.uid,up.level2,sum(cl.income)income' .
                    ' FROM \Micro\Models\UserInfo ui ' .
                    ' LEFT JOIN \Micro\Models\ConsumeLog cl ON ui.uid = cl.anchorId AND cl.createTime BETWEEN ' . $time['begin'] . ' AND ' . $time['end'] .
                    //' LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid = cl.anchorId '.
                    ' LEFT JOIN \Micro\Models\UserProfiles up ON up.uid = ui.uid ' .
                    ' WHERE cl.type < ' . $this->config->consumeType->coinType .
                    ' AND cl.income > 0 GROUP BY ui.uid ORDER BY income DESC LIMIT 0,' . DEFAULT_NUMBER;*/
            //托账号 cd.isTuo = 0 and
            $phql = 'select ui.avatar,ui.nickName,ui.uid,up.level2,ifnull(sum(cd.income), 0) as income from \Micro\Models\ConsumeDetailLog as cd ' . 
                    ' left join \Micro\Models\UserInfo as ui on ui.uid = cd.receiveUid ' . 
                    ' left join \Micro\Models\UserProfiles as up on up.uid = cd.receiveUid ' . 
                    ' where cd.receiveUid > 0 and cd.type < ' . $this->config->consumeType->coinType . 
                    ' and cd.income > 0 and cd.createTime BETWEEN ' . $time['begin'] . ' AND ' . $time['end'] .
                    ' GROUP BY cd.receiveUid order by income desc limit 0,' . DEFAULT_NUMBER;
            $query = $this->modelsManager->createQuery($phql);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                foreach ($data as $val) {
                    $dataData['uid'] = $val->uid;
                    $dataData['nickName'] = $val->nickName;
                    if (empty($val->avatar)) {
                        $dataData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    } else {
                        $dataData['avatar'] = $val->avatar;
                    }
                    $dataData['anchorLevel'] = $val->level2;
                    $dataData['income'] = $val->income;
                    array_push($result, $dataData);
                }
            }
            $result = $this->rankLogSave($this->config->rankLogType['star_' . $type], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 排行榜:富豪排行(根据用户所消耗的聊币总额排名)
     * */

    public function getRichRank($type) {
        try {
            $time = $this->getTimeRange($type);
            /*$phql = 'SELECT ui.avatar,ui.nickName,ui.uid,up.level3,sum(cl.amount) as consume' .
                    ' FROM \Micro\Models\UserInfo ui ' .
                    ' LEFT JOIN \Micro\Models\ConsumeLog cl ON ui.uid = cl.uid AND (cl.createTime BETWEEN ' . $time['begin'] . ' AND ' . $time['end'] . ') ' .
                    ' LEFT JOIN \Micro\Models\UserProfiles up ON up.uid = ui.uid ' .
                    ' WHERE cl.amount > 0 AND cl.type < ' . $this->config->consumeType->coinType .
                    ' GROUP BY ui.uid ORDER BY consume desc LIMIT 0,' . DEFAULT_NUMBER;*/
            //托账号 cd.isTuo = 0 and
            $phql = 'select ui.avatar,ui.nickName,ui.uid,up.level3,ifnull(sum(cd.amount), 0) as consume from \Micro\Models\ConsumeDetailLog as cd ' . 
                    ' left join \Micro\Models\UserInfo as ui on ui.uid = cd.uid ' . 
                    ' left join \Micro\Models\UserProfiles as up on up.uid = cd.uid ' . 
                    ' where cd.uid > 0 and cd.type < ' . $this->config->consumeType->coinType . 
                    ' and cd.amount > 0 and cd.createTime BETWEEN ' . $time['begin'] . ' AND ' . $time['end'] .
                    ' GROUP BY cd.uid order by consume desc limit 0,' . DEFAULT_NUMBER;
            $query = $this->modelsManager->createQuery($phql);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                foreach ($data as $val) {
                    $dataData['uid'] = $val->uid;
                    $dataData['nickName'] = $val->nickName;
                    if (empty($val->avatar)) {
                        $dataData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    } else {
                        $dataData['avatar'] = $val->avatar;
                    }
                    $dataData['richerLevel'] = $val->level3;
                    $dataData['consume'] = $val->consume;
                    array_push($result, $dataData);
                }
                if($type == 'day'){//日榜奖励
                    $len = count($result) > 3 ? 3 : count($result);
                    for($i = 1; $i <= $len; $i++){
                        //发放奖励
                        $reward = $this->config->dayRankConfigs->richRank[$i];
                        $user = UserFactory::getInstance($result[$i-1]['uid']);
                        if ($reward['carId']) {//发放座驾
                            $expireTime = $reward['expireDay'] * 86400;
                            $res = $user->getUserItemsObject()->giveCar($reward['carId'], $expireTime);
                        }
                        //给用户发送通知
                        if ($res) {
                            $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $reward['message'], 'link' => '', 'operType' => ''));
                        }
                    }
                }
            }

            $result = $this->rankLogSave($this->config->rankLogType['rich_' . $type], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 排行榜:人气排行(根据主播被关注数高低排名)
     * */

    public function getFansRank($type) {
        try {
            $time = $this->getTimeRange($type);
            $result = $this->followData($time, 10);

            $result = $this->rankLogSave($this->config->rankLogType['fans_' . $type], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

      /*
     * 排行榜:家族排行(根据家族主播获得总收益高低排名)
     * */
      public function getFamilysRank($type) {
        try {
            $time = $this->getTimeRange($type);
            /*$phpl = "SELECT a.familyId,SUM(a.income) as income,b.name,b.shortName,b.logo FROM \Micro\Models\Family b "
                    . "LEFT JOIN \Micro\Models\ConsumeLog a on a.familyId=b.id AND a.income > 0 AND a.type < " . $this->config->consumeType->coinType
                    . " WHERE a.createTime BETWEEN " . $time['begin'] . " AND " . $time['end'] .
                        " GROUP BY a.familyId ORDER BY income DESC LIMIT 0," . DEFAULT_NUMBER; */
            /*$phpl = 'select cd.familyId,sum(cd.income) as income,f.name,f.shortName,f.logo from \Micro\Models\ConsumeDetailLog as cd ' . 
                    ' left join \Micro\Models\Family as f on f.id = cd.familyId ' . 
                    ' where cd.familyId > 0 and cd.type < ' . $this->config->consumeType->coinType . ' and cd.income > 0 and cd.createTime between ' . $time['begin'] . ' and ' . $time['end'] . 
                    ' group by cd.familyId order by income desc limit 0,' . DEFAULT_NUMBER;
            $query = $this->modelsManager->createQuery($phpl);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                foreach ($data as $val) {
                    $dataData['familyId'] = $val->familyId;
                    $dataData['shortName'] = $val->shortName;
                    $dataData['name'] = $val->name;
                    $dataData['logo'] = $val->logo;
                    $dataData['income'] = $val->income;
                    array_push($result, $dataData);
                }
            }*/
            $phql = 'select ifnull(sum(cd.income), 0) as income,cd.familyId ' . 
                    ' from \Micro\Models\ConsumeDetailLog as cd ' . 
                    ' where cd.familyId > 0 and cd.type < ' . $this->config->consumeType->coinType . 
                    ' and cd.createTime between ' . $time['begin'] . ' and ' . $time['end'] . 
                    ' group by cd.familyId order by income desc limit 0,' . DEFAULT_NUMBER;
            $query = $this->modelsManager->createQuery($phql);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                //获取用户信息
                $sql1 = 'select name,id,logo,shortName from Micro\Models\Family';
                $query1 = $this->modelsManager->createQuery($sql1);
                $families = $query1->execute();
                $familyArr = array();
                if($families->valid()){
                    foreach ($families as $v) {
                        $tmp = array(
                            'name' => $v->name,
                            'id' => $v->id,
                            'logo' => $v->logo,
                            'shortName' => $v->shortName,
                        );
                        $familyArr[$v->id] = $tmp;
                    }
                }
                foreach ($data as $val) {
                    $dataData['familyId'] = $val->familyId;
                    $dataData['logo'] = $familyArr[$val->familyId]['logo'];
                    $dataData['name'] = $familyArr[$val->familyId]['name'];
                    $dataData['shortName'] = $familyArr[$val->familyId]['shortName'];
                    $dataData['income'] = $val->income;
                    array_push($result, $dataData);
                }
            }
            $result = $this->rankLogSave($this->config->rankLogType['family_' . $type], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 排行榜：礼物之星
     */

    public function getFirstGiftRank($type) {
        try {
            $time = $this->getTimeRange($type);// == 'lastWeek' ? 'week' : $type

            $sql = GiftConfigs::find(array(
                        'columns' => 'id,name,configName',
            ));
            if (!$sql->valid()) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), "valid err");
            }
            $result = array();
            foreach ($sql as $val) {
                /*$phql = 'SELECT ui.nickName,ui.uid,up.level2,sum(gl.count)count, sum(cl.income)income' .
                        ' FROM \Micro\Models\GiftLog gl ' .
                        ' LEFT JOIN \Micro\Models\ConsumeLog cl ON cl.id = gl.consumeLogId AND cl.type = ' . $this->config->consumeType->sendGift . ' AND (cl.createTime BETWEEN ' . $time['begin'] . ' AND ' . $time['end'] . ') ' . 
                        ' LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid = cl.anchorId ' .
                        ' LEFT JOIN \Micro\Models\UserProfiles up ON up.uid = ui.uid ' .
                        ' WHERE gl.giftId = ' . $val->id .
                        ' GROUP BY cl.anchorId ORDER BY income DESC,up.level2 DESC LIMIT 0,1';*/
                $phql = 'select cd.remark,cd.receiveUid as uid,up.level2,ifnull(sum(cd.count), 0) as count,ifnull(sum(cd.income), 0) as income from Micro\Models\ConsumeDetailLog as cd ' . 
                        ' left join Micro\Models\UserProfiles as up on cd.receiveUid = up.uid ' . 
                        ' where cd.type = ' . $this->config->consumeType->sendGift . ' and cd.itemId = ' . $val->id . ' and (cd.createTime between ' . $time['begin'] . ' and ' . $time['end'] . ') ' . 
                        ' group by cd.receiveUid order by income desc,up.level2 desc limit 1';
                $query = $this->modelsManager->createQuery($phql);
                $data = $query->execute();

                if ($data->valid()) {
                    foreach ($data as $key) {
                        $dataData['uid'] = $key->uid;
                        $dataData['nickName'] = $key->remark;
                        $dataData['anchorLevel'] = $key->level2;
                        $dataData['count'] = $key->count;
                        $dataData['income'] = $key->income;
                        $dataData['name'] = $val->name;
                        $dataData['configName'] = $val->configName;
                        array_push($result, $dataData);
                    }
                }
            }
            $sortResult = $this->baseCode->arrayMultiSort($result, 'income', TRUE);

            $result = $this->rankLogSave($this->config->rankLogType['gift_star_' . $type], $sortResult);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //礼物之星NEW
    public function getFirstGifts($type){
        try {
            $time = $this->getTimeRange($type);

            $sql = GiftConfigs::find(array(
                'columns' => 'id,name,configName',
            ));
            if (!$sql->valid()) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), "valid err");
            }

            $giftConfigs =array();
            foreach ($sql as $value) {
                $giftConfigs[$value->id] = array('name'=>$value->name, 'configName'=>$value->configName);
            }

            $phql = 'select cd.remark,cd.receiveUid as uid,up.level2,ifnull(sum(cd.count), 0) as count,ifnull(sum(cd.income), 0) as income,cd.itemId '
                . ' from Micro\Models\ConsumeDetailLog as cd '
                . ' left join Micro\Models\UserProfiles as up on cd.receiveUid = up.uid '
                . ' where cd.type = ' . $this->config->consumeType->sendGift
                . ' and (cd.createTime between ' . $time['begin'] . ' and ' . $time['end'] . ') '
                . ' group by cd.receiveUid,cd.itemId order by income desc,up.level2 desc';
            $query = $this->modelsManager->createQuery($phql);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                foreach ($data as $key) {
                    if(isset($result[$key->itemId]) && $result[$key->itemId]['income'] > $key->income) continue;
                    $dataData['uid'] = $key->uid;
                    $dataData['nickName'] = $key->remark;
                    $dataData['anchorLevel'] = $key->level2;
                    $dataData['count'] = $key->count;
                    $dataData['income'] = $key->income;
                    $dataData['name'] = $giftConfigs[$key->itemId]['name'];
                    $dataData['configName'] = $giftConfigs[$key->itemId]['configName'];
                    $result[$key->itemId] = $dataData;
                }
            }

            $sortResult = $this->baseCode->arrayMultiSort($result, 'income', TRUE);

            $result = $this->rankLogSave($this->config->rankLogType['gift_star_' . $type], $sortResult);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    /**
     * 魅力星排行
     */
    public function getCharmRank($type){
        try {
            $time = $this->getTimeRange($type);

            /*$sql = 'select sum(rl.giftNum) as num,ui.nickName,up.level2,ui.avatar,ui.uid from \Micro\Models\RoomGiftLog as rl ' . 
                   ' left join \Micro\Models\Rooms as r on r.roomId = rl.roomId' . 
                   ' left join \Micro\Models\UserInfo as ui on r.uid = ui.uid' . 
                   ' left join \Micro\Models\UserProfiles as up on up.uid = ui.uid' . 
                   ' where rl.type = 3 and (rl.createTime between ' . $time['begin'] . ' AND ' . $time['end'] .') group by rl.roomId order by num desc';*/
            $sql = 'select ifnull(sum(cd.count), 0) as num,cd.receiveUid,up.level2,ui.avatar,cd.remark from Micro\Models\ConsumeDetailLog as cd ' . 
                   ' left join \Micro\Models\UserInfo as ui on ui.uid = cd.receiveUid ' . 
                   ' left join \Micro\Models\UserProfiles as up on cd.receiveUid = up.uid ' . 
                   ' where cd.type = ' . $this->config->consumeType->sendStar . ' and cd.createTime between ' . $time['begin'] . ' AND ' . $time['end'] . 
                   ' group by cd.receiveUid order by num desc';
            $query = $this->modelsManager->createQuery($sql);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                foreach ($data as $val) {
                    $dataData['uid'] = $val->receiveUid;
                    $dataData['anchorLevel'] = $val->level2;
                    $dataData['nickName'] = $val->remark;
                    if (empty($val->avatar)) {
                        $dataData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    } else {
                        $dataData['avatar'] = $val->avatar;
                    }
                    $dataData['count'] = $val->num;
                    array_push($result, $dataData);
                }
            }

            $result = $this->rankLogSave($this->config->rankLogType['charm_' . $type], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 家族列表
     */

    public function getFamilyListByConsume($type) {
        try {
            $time = $this->getTimeRange($type);
            /*$phql = 'SELECT f.logo,f.name,f.id,sum(cl.income)income' .
                    ' FROM \Micro\Models\ConsumeLog cl ' .
                    ' LEFT JOIN \Micro\Models\Family f ON cl.familyId = f.id ' .
                    ' WHERE cl.familyId > 0 AND income > 0 AND cl.type < ' . $this->config->consumeType->coinType .
                    ' AND cl.createTime BETWEEN ' . $time['begin'] . ' AND ' . $time['end'] .
                    ' GROUP BY cl.familyId ORDER BY income DESC LIMIT 0,' . DEFAULT_NUMBER;*/
            $phql = 'select f.logo,f.name,f.id,ifnull(sum(cd.income), 0) as income from \Micro\Models\ConsumeDetailLog as cd ' . 
                    ' left join \Micro\Models\Family as f on cd.familyId = f.id ' . 
                    ' where cd.familyId > 0 and income > 0 and cd.type < ' . $this->config->consumeType->coinType . 
                    ' and cd.createTime between ' . $time['begin'] . ' AND ' . $time['end'] . 
                    ' group by cd.familyId order by income desc limit 0,' . DEFAULT_NUMBER;
                    // echo $phql;die;
            $query = $this->modelsManager->createQuery($phql);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                foreach ($data as $val) {
                    $dataData['id'] = $val->id;
                    $dataData['logo'] = $val->logo;
                    $dataData['name'] = $val->name;
                    $dataData['consume'] = $val->income;
                    array_push($result, $dataData);
                }
            }


            $result = $this->rankLogSave($this->config->rankLogType['consume_family'], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 家族列表(加入家族)
     */

    public function getFamilyRank() {
        try {
            /*$phql = 'SELECT f.logo,f.name,f.id,sum(cl.income)total' .
                    ' FROM \Micro\Models\ConsumeLog cl ' .
                    ' LEFT JOIN \Micro\Models\Family f ON cl.familyId = f.id ' .
                    ' WHERE cl.familyId > 0 AND cl.type < ' . $this->config->consumeType->coinType .
                    ' GROUP BY cl.familyId ORDER BY total DESC LIMIT 0,' . DEFAULT_NUMBER;*/
            $phql = 'select ifnull(sum(cd.income), 0) as total,cd.familyId ' . 
                    ' from \Micro\Models\ConsumeDetailLog as cd ' . 
                    ' where cd.familyId > 0 and cd.type < ' . $this->config->consumeType->coinType . 
                    ' group by cd.familyId order by total desc limit 0,' . DEFAULT_NUMBER;
            $query = $this->modelsManager->createQuery($phql);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                //获取用户信息
                $sql1 = 'select name,id,logo from Micro\Models\Family';
                $query1 = $this->modelsManager->createQuery($sql1);
                $families = $query1->execute();
                $familyArr = array();
                if($families->valid()){
                    foreach ($families as $v) {
                        $tmp = array(
                            'name' => $v->name,
                            'id' => $v->id,
                            'logo' => $v->logo,
                        );
                        $familyArr[$v->id] = $tmp;
                    }
                }
                foreach ($data as $val) {
                    $dataData['id'] = $val->familyId;
                    $dataData['logo'] = $familyArr[$val->familyId]['logo'];
                    $dataData['name'] = $familyArr[$val->familyId]['name'];
                    $dataData['consume'] = $val->total;
                    $result['id_' . $val->familyId] = $dataData;
                }
            }

            //家族列表
            $result = $this->rankLogSave($this->config->rankLogType['consume_family_day'], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 上周观众最多主播
     * */

    public function getLastWeekVisitorRankAnchor() {
        try {
            $time = $this->getTimeRange('week');
            $sql = 'SELECT ui.uid,up.level2,ui.nickName,ui.avatar,sum(rl.count)count' .
                    ' FROM \Micro\Models\RoomLog rl ' .
                    ' LEFT JOIN \Micro\Models\Rooms r ON r.roomId = rl.roomId ' .
                    ' LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid = r.uid ' .
                    ' LEFT JOIN \Micro\Models\UserProfiles up ON ui.uid = up.uid ' .
                    ' WHERE (rl.endTime BETWEEN ' . $time['begin'] . ' AND ' . $time['end'] . ')' .
                    ' GROUP BY rl.roomId ORDER BY count desc LIMIT 0,' . DEFAULT_NUMBER;
            $query = $this->modelsManager->createQuery($sql);
            $data = $query->execute();

            $result = array();
            if ($data->valid()) {
                foreach ($data as $val) {
                    $dataData['uid'] = $val->uid;
                    $dataData['anchorLevel'] = $val->level2;
                    $dataData['nickName'] = $val->nickName;
                    if (empty($val->avatar)) {
                        $dataData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    } else {
                        $dataData['avatar'] = $val->avatar;
                    }
                    array_push($result, $dataData);
                }
            }

            $result = $this->rankLogSave($this->config->rankLogType['visitor_anchor'], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 上周魅力最强主播
     * */

    public function getLastWeekFollowRankAnchor() {
        try {
            $time = $this->getTimeRange('week');
            $result = $this->followData($time, 3);

            $result = $this->rankLogSave($this->config->rankLogType['fans_anchor'], $result);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //近30天赠送率排名前20的礼物
    public function getHotGiftData() {
         //热门礼物取消计划任务 改成后台配置 by 2015/07/07.....//////////
        return;
      /*  try {
            $time = strtotime("-30 day");
            $sql = 'SELECT gl.giftId,sum(gl.count)count ' .
                    ' FROM \Micro\Models\GiftLog gl ' .
                    ' LEFT JOIN \Micro\Models\ConsumeLog cl ON cl.id = gl.consumeLogId AND cl.type = ' . $this->config->consumeType->sendGift .
                    ' LEFT JOIN \Micro\Models\GiftConfigs gc ON gc.id=gl.giftId ' .
                    ' where  cl.createTime>' . $time . ' and (gc.typeId=1 or gc.typeId=2) group by gl.giftId order by count desc limit 20';
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $idArray = array();
            if ($result->valid()) {
                foreach ($result as $key => $val) {
                    $idArray[] = $val->giftId;
                }
            }

            $result = $this->rankLogSave($this->config->rankLogType['hot_gift'], $idArray);
            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }*/
    }

    //过期座驾通知推送接口
    public function sendExpiredCarInfo() {
        try {
            //查询昨天过期的座驾
            $time1 = strtotime(date("Ymd", strtotime("-1 day")));
            $time2 = $time1 + 86400;
            $sql = "select i.id,i.uid,c.name,tc.sellStatus from \Micro\Models\UserItem i inner join \Micro\Models\CarConfigs c on i.itemId=c.id "
                   . " left join \Micro\Models\TypeConfig as tc on tc.typeId = c.typeId and tc.parentTypeId = " . $this->config->itemType->car
                   . " where i.itemType=" . $this->config->itemType->car . " and i.itemExpireTime>=" . $time1 . " and i.itemExpireTime<" . $time2;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                foreach ($result as $key => $val) {
                    //给用户发送通知
                    $sendUser = UserFactory::getInstance($val->uid);
                    $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->carHasExpired, array(0 => $val->name, 1 => intval($val->sellStatus)));
                    $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    //关闭座驾
                    $user = \Micro\Models\UserItem::findfirst($val->id);
                    $user->status = 0;
                    $user->save();
                }
            }
            //查询即将过期的座驾
            $time3 = strtotime(date("Ymd", strtotime("+ 3 day"))); //3天后过期的座驾
            $time4 = $time3 + 86400;
            $sql = "select i.id,i.uid,c.name,tc.sellStatus from \Micro\Models\UserItem i inner join \Micro\Models\CarConfigs c on i.itemId=c.id "
                   . " left join \Micro\Models\TypeConfig as tc on tc.typeId = c.typeId and tc.parentTypeId = " . $this->config->itemType->car
                   . " where i.itemType=" . $this->config->itemType->car . " and i.itemExpireTime>=" . $time3 . " and i.itemExpireTime<" . $time4;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                foreach ($result as $key => $val) {
                    //给用户发送通知
                    $sendUser = UserFactory::getInstance($val->uid);
                    $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->carAboutToExpire, array(0 => $val->name, 1 => intval($val->sellStatus)));
                    $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
   //过期vip通知推送接口
    public function sendExpiredVipInfo() {
        try {
            //查询昨天过期的vip
            $time1 = strtotime(date("Ymd", strtotime("-1 day")));
            $time2 = $time1 + 86400;
            $result= \Micro\Models\UserProfiles::find("(vipExpireTime>=".$time1." and vipExpireTime<".$time2.") or (vipExpireTime2>=".$time1." and vipExpireTime2<".$time2.")");
            if ($result->valid()) {
                foreach ($result as $key => $val) {
                    //给用户发送通知
                    $sendUser = UserFactory::getInstance($val->uid);
                    if ($val->vipExpireTime >= $time1 && $val->vipExpireTime < $time2) {//普通vip
                        $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->vipHasExpired, array(0 => '普通vip'));
                        $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    }
                    if ($val->vipExpireTime2 >= $time1 && $val->vipExpireTime2 < $time2) {//至尊vip
                        $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->vipHasExpired, array(0 => '至尊vip'));
                        $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    }
                }
            }
            //查询即将过期的vip
            $time3 = strtotime(date("Ymd", strtotime("+ 3 day"))); //3天后过期的vip
            $time4 = $time3 + 86400;
            $result = \Micro\Models\UserProfiles::find("(vipExpireTime>=" . $time3 . " and vipExpireTime<" . $time4 . ") or (vipExpireTime2>=" . $time3 . " and vipExpireTime2<" . $time4 . ")");
            if ($result->valid()) {
                foreach ($result as $key => $val) {
                    //给用户发送通知
                    $sendUser = UserFactory::getInstance($val->uid);
                    if ($val->vipExpireTime >= $time3 && $val->vipExpireTime < $time4) {//普通vip
                        $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->vipAboutToExpire, array(0 => '普通vip'));
                        $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    }
                    if ($val->vipExpireTime2 >= $time3 && $val->vipExpireTime2 < $time4) {//至尊vip
                        $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->vipAboutToExpire, array(0 => '至尊vip'));
                        $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    }
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //过期守护通知推送接口
    public function sendExpiredGuardInfo() {
        try {
            //查询昨天过期的守护
            $time1 = strtotime(date("Ymd", strtotime("-1 day")));
            $time2 = $time1 + 86400;
            $result= \Micro\Models\GuardList::find("expireTime>=".$time1." and expireTime<".$time2);
            if ($result->valid()) {
                foreach ($result as $key => $val) {
                    //被守护者的信息
                    $beGuardUser = UserFactory::getInstance($val->beGuardedUid);
                    $guardInfo = $beGuardUser->getUserInfoObject()->getUserInfo();
                    //给用户发送通知
                    $sendUser = UserFactory::getInstance($val->guardUid);
                    if ($val->guardLevel == 1) {//黄金守护
                        $guardName = '黄金守护';
                    } else {
                        $guardName = '白银守护';
                    }
                    $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->guardHasExpired, array(0 => $guardInfo['nickName'], 1 => $guardName, 2 => $val->beGuardedUid));
                    $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                }
            }
            //查询即将过期的守护
            $time3 = strtotime(date("Ymd", strtotime("+ 3 day"))); //3天后过期的守护
            $time4 = $time3 + 86400;
            $result = \Micro\Models\GuardList::find("expireTime>=" . $time3 . " and expireTime<" . $time4);
            if ($result->valid()) {
                foreach ($result as $key => $val) {
                    //被守护者的信息
                    $beGuardUser = UserFactory::getInstance($val->beGuardedUid);
                    $guardInfo = $beGuardUser->getUserInfoObject()->getUserInfo();
                    //给用户发送通知
                    $sendUser = UserFactory::getInstance($val->guardUid);
                    if ($val->guardLevel == 1) {//黄金守护
                        $guardName = '黄金守护';
                    } else {
                        $guardName = '白银守护';
                    }
                    $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->guardAboutToExpire, array(0 => $guardInfo['nickName'], 1 => $guardName, 2 => $val->beGuardedUid));
                    $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    
    //过期徽章通知推送接口
    public function sendExpiredBadgeInfo() {
        try {
            //查询昨天过期的徽章
            $time1 = strtotime(date("Ymd", strtotime("-1 day")));
            $time2 = $time1 + 86400;
            $sql = "select c.name,i.uid from \Micro\Models\UserItem i inner join \Micro\Models\ItemConfigs c on i.itemId=c.id"
                    . " where i.itemType=" . $this->config->itemType->item . " and i.itemExpireTime>=" . $time1 . " and i.itemExpireTime<" . $time2;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                foreach ($result as $key => $val) {
                    //给用户发送通知
                    $sendUser = UserFactory::getInstance($val->uid);
                    $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->badgeHasExpired, array(0 => $val->name));
                    $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 排行榜log存储
     * */

    private function rankLogSave($type, $data) {
        try {
            $content = json_encode($data);
            $rankLog = RankLog::findfirst("index={$type}");
            if (!$rankLog) {
                $rankLog = new RankLog();
                $rankLog->index = $type;
            }
            $rankLog->content = $content;
            $rankLog->lastTime = strtotime(date('Y-m-d') . " 23:59:59");
            $rankLog->save();
            $data = "{$type}---data:{$content}";
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 关注数据获取
     * */

    private function followData($time, $len) {
        $result = array();
        $cursor = $this->collectionFollow->find(function($query) use($time) {
            $query->where("fids.time", array('$gt' => $time['begin'], '$lt' => $time['end']));
        });
        while ($ret = $cursor->getNext()) {
            $result[$ret['uid']] = count($ret['fids']);
        }
        arsort($result);

        $data = array();
        if ($result != NULL) {

            $length = count($result);
            array_slice($result, 0, min($length, $len));

            $strArray = "";
            foreach ($result as $key => $val) {
                $strArray.="'" . $key . "',";
            }
            $strArray = substr($strArray, 0, -1);
            //获取数据
            $data = $this->getFollowData($strArray, $result);
        }
        return $data;
    }

    /*
     * 关注--（用户）数据获取
     * */

    private function getFollowData($strArray, $countData) {
        $sql = 'SELECT ui.uid,up.level2,ui.nickName,ui.avatar,u.accountId' .
                ' FROM \Micro\Models\Users u' .
                ' LEFT JOIN \Micro\Models\UserInfo ui ON u.uid = ui.uid ' .
                ' LEFT JOIN \Micro\Models\UserProfiles up ON ui.uid = up.uid ' .
                " WHERE u.accountId IN (" . $strArray . ") ORDER BY INSTR(\"" . $strArray . ",\",accountId)";
        $query = $this->modelsManager->createQuery($sql);
        $data = $query->execute();

        $result = array();
        if ($data->valid()) {
            foreach ($data as $val) {
                $rankLog = new RankLog();
                $dataData['uid'] = $val->uid;
                $dataData['anchorLevel'] = $val->level2;
                $dataData['nickName'] = $val->nickName;
                if (empty($val->avatar)) {
                    $dataData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                } else {
                    $dataData['avatar'] = $val->avatar;
                }
                $dataData['count'] = $countData[$val->accountId];
                array_push($result, $dataData);
            }
        }
        return $result;
    }

    /**
     * 每日游戏提成明细
     */
    public function getGameDeductDetail($day = 0){
        try {
            if(!$day){
                $startTime = strtotime(date('Y-m-d',strtotime('-1 day')));
            }else{
                $startTime = $day;
            }
            $endTime = $startTime + 86400;

            $sqlDetail = 'delete from pre_game_deduct_detail_log where gameType = 1 and createTime = ' . ($endTime);
            $this->di->get('db')->execute($sqlDetail);
            $sqlDay = 'delete from pre_game_deduct_day_log where (type = 1 or type = 2) and createTime = ' . ($endTime);
            $this->di->get('db')->execute($sqlDay);

            $res = \Micro\Models\DiceResult::find('createTime >= ' . $startTime . ' and createTime < ' . $endTime);
            $detailData = array();
            $anchorDeduct = $this->config->gameDeductConfig->dice->anchor;
            $familyDeduct = $this->config->gameDeductConfig->dice->family;
            /*if($res){
                foreach ($res as $val) {
                    if(array_key_exists($val->gameId, $detailData)){
                        $detailData[$val->gameId]['percentage'] += $val->fax * $anchorDeduct;
                        if($val->isDeclarer){
                            $detailData[$val->gameId]['dealerUid'] = $val->uid;
                        }
                    }else{
                        $detailData[$val->gameId] = array(
                            'percentage' => $val->fax * $anchorDeduct,
                            'dealerUid' => $val->isDeclarer ? $val->uid : 0,
                            'anchorUid' => $val->anchorUid,
                            'deductTime' => $val->createTime,
                        );
                    }
                }
            }*/

            //modified 20160309
            $sql = 'select dr.fax,dr.isDeclarer,dr.anchorUid,dr.uid,dr.createTime,dr.gameId,f.creatorUid from \Micro\Models\DiceResult as dr '
                . ' left join \Micro\Models\SignAnchor as sa on sa.uid = dr.anchorUid left join \Micro\Models\Family as f on f.id = sa.familyId '
                . ' where dr.createTime >= ' . $startTime . ' and dr.createTime < ' . $endTime;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            if($res->valid()){
                foreach ($res as $val) {
                    if(array_key_exists($val->gameId, $detailData)){
                        $detailData[$val->gameId]['percentage'] += $val->fax * $anchorDeduct;
                        $detailData[$val->gameId]['percentageF'] += $val->fax * $familyDeduct;
                        if($val->isDeclarer){
                            $detailData[$val->gameId]['dealerUid'] = $val->uid;
                        }
                    }else{
                        $detailData[$val->gameId] = array(
                            'percentage' => $val->fax * $anchorDeduct,
                            'percentageF' => $val->fax * $familyDeduct,
                            'dealerUid' => $val->isDeclarer ? $val->uid : 0,
                            'anchorUid' => $val->anchorUid,
                            'deductTime' => $val->createTime,
                            'familyUid' => $val->creatorUid ? $val->creatorUid : 0,
                        );
                    }
                }
            }

            if($detailData){
                $dayData = array();
                $dayData2 = array();
                $now=time();
                $insertDetail = "insert into pre_game_deduct_detail_log (`percentage`,`deductTime`,`gameType`,`dealerUid`,`anchorUid`,`createTime`,`remark`) values ";
                foreach ($detailData as $v) {
                    if($v['percentage'] <= 0) continue;
                    //主播
                    $remark = '骰宝游戏主播提成';
                    if(array_key_exists($v['anchorUid'], $dayData)){
                        $dayData[$v['anchorUid']]['cash'] += $v['percentage'];
                    }else{
                        $dayData[$v['anchorUid']] = array(
                            'cash' => $v['percentage'],
                            'uid' => $v['anchorUid']
                        );
                    }
                    $insertDetail .= "({$v['percentage']},{$v['deductTime']},1,{$v['dealerUid']},{$v['anchorUid']},{$endTime},'{$remark}'),";

                    //家族长
                    if($v['familyUid']){
                        $remark = '骰宝游戏家族长提成';
                        if(array_key_exists($v['familyUid'], $dayData2)){
                            $dayData2[$v['familyUid']]['cash'] += $v['percentageF'];
                        }else{
                            $dayData2[$v['familyUid']] = array(
                                'cash' => $v['percentageF'],
                                'uid' => $v['familyUid']
                            );
                        }
                        $insertDetail .= "({$v['percentageF']},{$v['deductTime']},1,{$v['dealerUid']},{$v['familyUid']},{$endTime},'{$remark}'),";
                    }
                }
                $this->di->get('db')->execute(substr($insertDetail,0,-1));

                $insertDay = "insert into pre_game_deduct_day_log (`uid`,`cash`,`type`,`remark`,`createTime`) values ";
                $values = '';
                if($dayData){
                    foreach ($dayData as $v1) {
                        $values .= "({$v1['uid']},{$v1['cash']},1,'骰宝游戏主播提成',{$endTime}),";
                    }
                }
                if($dayData2){
                    foreach ($dayData2 as $v2) {
                        $values .= "({$v2['uid']},{$v2['cash']},2,'骰宝游戏家族长提成',{$endTime}),";
                    }
                }

                $values && $this->di->get('db')->execute(substr($insertDay . $values,0,-1));
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), 'getGameDeductDetail---');

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 每日游戏提成收入表
     */
    public function getGameIncome($date = 0){
        try {
            if ($date) {
                $time['begin'] = strtotime(date('Y-m-12', strtotime('-1 month', strtotime(date('Y-m-01', $date)))));
                $time['end'] = strtotime(date('Y-m-11', $date));
            } else {
                $day = intval(date('d'));
                if ($day >= 11) {
                    $time['begin'] = strtotime(date('Y-m-12', strtotime('-1 month')));
                    $time['end'] = strtotime(date('Y-m-11', time()));
                } else {
                    $time['begin'] = strtotime(date('Y-m-12', strtotime('-2 month', strtotime(date('Y-m-01')))));
                    $time['end'] = strtotime(date('Y-m-11', strtotime('-1 month', strtotime(date('Y-m-01')))));
                }
            }

            $sql = 'select dg.uid,ifnull(sum(dg.cash), 0) as myIncome,dg.createTime,dg.type from \Micro\Models\GameDeductDayLog as dg '
                    . 'where dg.createTime >= ' . $time['begin'] . ' and dg.createTime <= ' . $time['end'] . ' group by dg.uid';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $addTime = $time['end'];
            if ($res->valid()) {
                $sql = 'delete from \Micro\Models\DayIncomeLog where createTime = ' . $addTime.' and type = ' . $this->config->moneyType[7]['type'];
                $query = $this->modelsManager->createQuery($sql);
                $query->execute();
                foreach ($res as $k => $v) {
                    $myIncome = $v->myIncome;
                    $dayIncome = new \Micro\Models\DayIncomeLog();
                    $dayIncome->uid = $v->uid;
                    $dayIncome->money = $myIncome;
                    $dayIncome->createTime = $addTime;
                    $dayIncome->type = $this->config->moneyType[7]['type'];
                    $dayIncome->description = $this->config->moneyType[7]['desc'];
                    $dayIncome->save();
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), 'getGameIncome---');
            
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    /**
     * 每日获取主播礼物收益
     * @param $day 需要更新的礼物收益日期，如传入10日时间戳则更新的数据为10日0-24的数据，否则默认为昨天数据
     */
    public function getDayGifts($day = 0){
        try {
            if(!$day){
                $startTime = strtotime(date('Y-m-d',strtotime('-1 day')));
            }else{
                $startTime = $day;
            }
            $endTime = $startTime + 86400;

            //cl.source
            /*$sql = 'select sum(cl.income) as incomes,cl.anchorId,cl.familyId,f.creatorUid from \Micro\Models\ConsumeLog as cl '
                   . ' left join \Micro\Models\Family as f on f.id = cl.familyId '
                   . ' where cl.type < ' . $this->config->consumeType->coinType . ' and cl.createTime >= ' . $startTime . ' and cl.createTime < ' . $endTime . ' and cl.income > 0 '
                   . ' group by cl.anchorId order by incomes desc';*/
            $sql = 'select ifnull(sum(cd.income), 0) as incomes,cd.receiveUid,cd.familyId,f.creatorUid from \Micro\Models\ConsumeDetailLog as cd '
                   . ' left join \Micro\Models\Family as f on f.id = cd.familyId '
                   . ' where cd.isTuo = 0 and cd.type < ' . $this->config->consumeType->coinType . ' and cd.createTime >= ' . $startTime . ' and cd.createTime < ' . $endTime . ' and cd.income > 0 '
                   . ' group by cd.receiveUid order by incomes desc';
            // echo $sql;die;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            // $content = json_encode($result->toArray());

            //暂定比例
            $platRatio = $this->config->incomeRatios->platRatio;
            $divideRatio = $this->config->incomeRatios->divideRatio;
            $createTime = $endTime;//strtotime(date('Y-m-d'));
            
            if($result->valid()){
                $sql = 'delete from \Micro\Models\DayGiftsLog where createTime = ' . ($endTime);
                $query = $this->modelsManager->createQuery($sql);
                $res = $query->execute();
                foreach ($result as $k => $v) {
                    $dayGifts = new \Micro\Models\DayGiftsLog();
                    $dayGifts->uid = $v->receiveUid;
                    $dayGifts->familyId = intval($v->familyId);
                    $dayGifts->creatorUid = intval($v->creatorUid);
                    $dayGifts->allIncome = $v->incomes * $platRatio / 100;
                    $dayGifts->myIncome = $v->incomes * $platRatio * (100 - $divideRatio) / 10000;
                    $dayGifts->platRatio = $platRatio;
                    $dayGifts->divideIncome = $v->incomes * $platRatio * $divideRatio / 10000;
                    $dayGifts->divideRatio = $divideRatio;
                    $dayGifts->createTime = $createTime;
                    $dayGifts->source = 0;
                    $dayGifts->save();
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), 'Day_Gifts_Log---');

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 每日收益流水表
     *
     */
    public function getDayIncomeLog($date = 0){
        try {
            if($date){
                $time['begin'] = strtotime(date('Y-m-12',strtotime('-1 month', strtotime(date('Y-m-01', $date)))));
                $time['end'] = strtotime(date('Y-m-11',$date));
            }else{
                $day = intval(date('d'));
                if($day >= 11){
                    $time['begin'] = strtotime(date('Y-m-12',strtotime('-1 month')));
                    $time['end'] = strtotime(date('Y-m-11',time()));
                }else{
                    $time['begin'] = strtotime(date('Y-m-12',strtotime('-2 month', strtotime(date('Y-m-01')))));
                    $time['end'] = strtotime(date('Y-m-11',strtotime('-1 month', strtotime(date('Y-m-01')))));
                }
            }   

            $sql = 'select dg.uid,dg.creatorUid,dg.myIncome,dg.divideIncome,dg.createTime from \Micro\Models\DayGiftsLog as dg '
                   . 'where dg.createTime >= ' . $time['begin'] . ' and dg.createTime <= ' . $time['end'] .' order by dg.createTime';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $addTime = $time['end'];
            if($res->valid()){
                $sql = 'delete from \Micro\Models\DayIncomeLog where createTime = ' . $addTime.' and (type = ' . $this->config->moneyType[1]['type'] . ' or type = ' . $this->config->moneyType[2]['type'].')';
                $query = $this->modelsManager->createQuery($sql);
                $result = $query->execute();
                foreach ($res as $k => $v) {
                    $myIncome = $v->myIncome;
                    $divideIncome = $v->divideIncome;
                    //主播礼物分成
                    $dayIncome = new \Micro\Models\DayIncomeLog();
                    $dayIncome->uid = $v->uid;
                    $dayIncome->money = $myIncome;
                    $dayIncome->createTime = $addTime;
                    $dayIncome->type = $this->config->moneyType[1]['type'];
                    $dayIncome->description = $this->config->moneyType[1]['desc'];
                    $dayIncome->save();
                    //家族主播分成
                    $dayIncome = new \Micro\Models\DayIncomeLog();
                    $dayIncome->uid = $v->creatorUid;
                    $dayIncome->money = $divideIncome;
                    $dayIncome->createTime = $addTime;
                    $dayIncome->type = $this->config->moneyType[2]['type'];
                    $dayIncome->description = $this->config->moneyType[2]['desc'];
                    $dayIncome->save();
                }
            }

            //清除上个月的佣金
            $sql_update = 'update pre_user_profiles set usefulMoney = 0';
            $connection = $this->di->get('db');
            $connection->execute($sql_update);

            return $this->status->retFromFramework($this->status->getCode('OK'), 'Day_Income_Log---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 佣金流水表
     *
     */
    public function getMonthIncomeLog($date = 0){
        try {
            if($date){
                $createTime = strtotime(date('Y-m-11',strtotime('this month', strtotime(date('Y-m-01', $date)))));
                $addTime = strtotime(date('Y-m-12',$date));
            }else{
                $day = intval(date('d'));
                if($day >= 12){
                    $createTime = strtotime(date('Y-m-11',strtotime('this month')));
                    $addTime = strtotime(date('Y-m-12'));
                }else{
                    $createTime = strtotime(date('Y-m-11',strtotime('-1 month', strtotime(date('Y-m-01')))));
                    $addTime = strtotime(date('Y-m-12',$createTime));
                }
            }                

            $sql = 'select di.uid,di.type,di.createTime,IFNULL(sum(di.money), 0) as ttl from \Micro\Models\DayIncomeLog as di '
                . ' where di.createTime = ' . $createTime
                . ' and (di.type = ' . $this->config->moneyType[1]['type'] . ' or di.type = ' . $this->config->moneyType[2]['type'] 
                . ' or di.type = ' . $this->config->moneyType[5]['type'] . ' or di.type = ' . $this->config->moneyType[7]['type'] . ') '
                . ' group by di.uid,di.type';
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            
           

            if($result->valid()){
             
                //删除原有数据【用于多次调用计划任务去除相同数据】
                $sql1 = 'delete from \Micro\Models\DayIncomeLog where '
                    . 'createTime = ' . $addTime 
                    . ' and (type = ' . $this->config->moneyType[3]['type'] . ' or type = ' . $this->config->moneyType[4]['type'] 
                    . ' or type = ' . $this->config->moneyType[6]['type'] . ' or type = ' . $this->config->moneyType[8]['type'] . ')';
                $sql2 = 'delete from \Micro\Models\MonthIncomeLog where createTime = ' . $addTime;
                $sql3 = 'delete from \Micro\Models\ChangeLog where createTime = ' . $addTime;
            
                $qry1 = $this->modelsManager->createQuery($sql1);
                $res1 = $qry1->execute();
                $qry2 = $this->modelsManager->createQuery($sql2);
                $res2 = $qry2->execute();
                $qry3 = $this->modelsManager->createQuery($sql3);
                $res3 = $qry3->execute();
                
              

                foreach ($result as $k => $v) {
                    $rmb = $v->ttl;
                    if ($v->type == 1) {
                        $type = $this->config->moneyType[3]['type'];
                        $desc = $this->config->moneyType[3]['desc'];
                        $rmb = $v->ttl / $this->config->cashScale;
                    } elseif ($v->type == 2) {
                        $type = $this->config->moneyType[4]['type'];
                        $desc = $this->config->moneyType[4]['desc'];
                        $rmb = $v->ttl / $this->config->cashScale;
                    } elseif ($v->type == 5) {
                        $type = $this->config->moneyType[6]['type'];
                        $desc = $this->config->moneyType[6]['desc'];
                        $rmb = $v->ttl / $this->config->cashScale;
                    } elseif ($v->type == 7) {
                        $type = $this->config->moneyType[8]['type'];
                        $desc = $this->config->moneyType[8]['desc'];
                        $rmb = $v->ttl / $this->config->cashScale;
                    }

                    //对应主播增加一条记录表示佣金结算
                    $dayIncome = new \Micro\Models\DayIncomeLog();
                    $dayIncome->uid = $v->uid;
                    $dayIncome->money = 0 - $v->ttl;
                    $dayIncome->createTime = $addTime;
                    $dayIncome->type = $type;
                    $dayIncome->description = $desc;
                    $dayIncome->save();

                    //佣金结算表增加记录
                    $monthIncomeLog = new \Micro\Models\MonthIncomeLog();
                    $monthIncomeLog->uid = $v->uid;
                    $monthIncomeLog->money = $rmb;
                    $monthIncomeLog->createTime = $addTime;
                    $monthIncomeLog->type = $v->type;
                    $monthIncomeLog->save();

                    //交易明细表增加记录，表示“发放”聊币=>rmb
                    $changeLog = new \Micro\Models\ChangeLog();
                    $changeLog->uid = $v->uid;
                    $changeLog->orderNum = date('YmdHis',$addTime) . rand(100000,999999);
                    $changeLog->money = $rmb;
                    $changeLog->createTime = $addTime;
                    $changeLog->type = $this->config->changeType[1]['type'];
                    $changeLog->status = 0;
                    $changeLog->save();

                    //修改可提现金额字段
                    $sql_update = 'update pre_user_profiles set usefulMoney = usefulMoney + ' . $rmb . ' where uid = ' . $v->uid;
                    $connection = $this->di->get('db');
                    $connection->execute($sql_update);
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), 'Month_Income_Log---');

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 礼物周星
     * @param $date 不传默认当前时间，否则如20150721则记录的是截止至2015-07-20 23:59:59
     * @param $giftId 不传默认为全部礼物
     **/
    public function getGiftStar($date = 0, $giftId = 0){
        try {
            if($date){
                $week = date('w',strtotime($date));
                if($week == 1){
                    $thisMondayTime = strtotime(date('Y-m-d',strtotime('this Monday', strtotime($date))));
                }else{
                    $thisMondayTime = strtotime(date('Y-m-d',strtotime('-1 Monday', strtotime($date))));
                }
                $nowTime = strtotime($date);
                $thisWeek['begin'] = $thisMondayTime;
                $thisWeek['end'] = $nowTime - 1;
                $lastWeek['begin'] = $thisMondayTime - 86400 * 7;
                $lastWeek['end'] = $thisMondayTime - 1;
            }else{
                $week = date('w');
                $thisWeek = $this->getTimeRange('thisWeek');
                $lastWeek['begin'] = $thisWeek['begin'] - 86400 * 7;
                $lastWeek['end'] = $thisWeek['begin'] - 1;
            }

            
            // var_dump($starGifts->toArray());die;
            //主播本周礼物周星
            $starAnchor = array();
            $starRicher = array();
            // $sqlStartA = 'select sum(gl.count) as num,ui.nickName,ui.avatar,gl.giftId,cl.anchorId from \Micro\Models\ConsumeLog as cl left join \Micro\Models\UserInfo as ui on ui.uid = cl.anchorId ';
            $sqlStartA = 'select ifnull(sum(cd.count), 0) as num,ui.nickName,ui.avatar,cd.itemId,cd.receiveUid as anchorId from \Micro\Models\ConsumeDetailLog as cd left join \Micro\Models\UserInfo as ui on ui.uid = cd.receiveUid ';
            // $sqlStartR = 'select sum(gl.count) as num,ui.nickName,ui.avatar,gl.giftId,cl.uid from \Micro\Models\ConsumeLog as cl left join \Micro\Models\UserInfo as ui on ui.uid = cl.uid ';
            $sqlStartR = 'select ifnull(sum(cd.count), 0) as num,ui.nickName,ui.avatar,cd.itemId,cd.uid from \Micro\Models\ConsumeDetailLog as cd left join \Micro\Models\UserInfo as ui on ui.uid = cd.uid ';
            // $sqlCom = ' left join \Micro\Models\GiftLog as gl on gl.consumeLogId = cl.id where cl.type = ' . $this->config->consumeType->sendGift;
            $sqlCom = ' where cd.type = ' . $this->config->consumeType->sendGift;
            $sqlEndA = ' group by cd.receiveUid order by num desc limit 5';
            $sqlEndR = ' group by cd.uid order by num desc limit 5';

            if($giftId){
                $starGifts[] = array('id'=>$giftId);
            }else{
                //获取周星礼物列表
                $starGifts = \Micro\Models\GiftConfigs::find('typeId = ' . $this->config->weekStarType);// . $this->config->consumeType->sendGift
                if($starGifts->valid()){
                    $starGifts = $starGifts->toArray();
                }else{
                    $starGifts = array();
                }
            }
            if($starGifts){
                foreach ($starGifts as $k => $v) {
                    $giftId = $v['id'];
                    //上周条件
                    $whereL = ' and cd.createTime >= ' . $lastWeek['begin'] . ' and cd.createTime <= ' . $lastWeek['end'] . ' and cd.itemId = ' . $giftId;
                    //本周条件
                    $whereT = ' and cd.createTime >= ' . $thisWeek['begin'] . ' and cd.createTime <= ' . $thisWeek['end'] . ' and cd.itemId = ' . $giftId;

                    $tmpData = array();
                    if($week == 1){
                        //上周主播礼物周星sql
                        $tmpSqlAL = $sqlStartA . $sqlCom . $whereL . $sqlEndA;
                        $queryAL = $this->modelsManager->createQuery($tmpSqlAL);
                        $resultAL = $queryAL->execute();
                        $tmpData['anchor'] = $resultAL->toArray();

                        //上周富豪礼物周星sql
                        $tmpSqlRL = $sqlStartR . $sqlCom . $whereL . $sqlEndR;
                        $queryRL = $this->modelsManager->createQuery($tmpSqlRL);
                        $resultRL = $queryRL->execute();
                        $tmpData['richer'] = $resultRL->toArray();
                    }else{
                        //本周主播礼物周星sql
                        $tmpSqlAT = $sqlStartA . $sqlCom . $whereT . $sqlEndA;
                        $queryAT = $this->modelsManager->createQuery($tmpSqlAT);
                        $resultAT = $queryAT->execute();
                        $tmpData['anchor'] = $resultAT->toArray();

                        //本周富豪礼物周星sql
                        $tmpSqlRT = $sqlStartR . $sqlCom . $whereT . $sqlEndR;
                        $queryRT = $this->modelsManager->createQuery($tmpSqlRT);
                        $resultRT = $queryRT->execute();
                        $tmpData['richer'] = $resultRT->toArray();
                    }

                    $this->starLogSave($giftId, $tmpData, $week, $date);
                    unset($tmpData);
                }
            }
                
            return $this->status->retFromFramework($this->status->getCode('OK'), 'Week_Star_Log---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 礼物周星log存储
     * */

    private function starLogSave($giftId = 0, $data, $week = 1, $date = 0) {
        try {
            
            $content = json_encode($data);
            $weekStarLog = WeekStarLog::findfirst('giftId = ' . $giftId);
            if (!$weekStarLog) {
                $weekStarLog = new WeekStarLog();
                $weekStarLog->giftId = $giftId;
            }
            if($week == 1){
                $weekStarLog->thisweekInfo = '';
                $weekStarLog->lastweekInfo = json_encode($data);
                $anchorId = !empty($data['anchor']) ? $data['anchor'][0]['anchorId'] : 0;
                $richerId = !empty($data['richer']) ? $data['richer'][0]['uid'] : 0;
                $getNum = !empty($data['anchor']) ? $data['anchor'][0]['num'] : 0;
                $sendNum = !empty($data['richer']) ? $data['richer'][0]['num'] : 0;
            }else{
                $weekStarLog->thisweekInfo = json_encode($data);
            }
            $weekStarLog->lastTime = $date ? strtotime($date) : strtotime(date('Y-m-d', time()));
            $weekStarLog->save();

            if($week == 1){
                $this->starInfoLogSave($giftId, $anchorId, $richerId, $getNum, $sendNum, $date);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 存储周星获得者记录
     **/
    public function starInfoLogSave($giftId = 0, $anchorId = 0, $richerId = 0, $getNum = 0, $sendNum = 0, $date = 0){
        try {
            if($date){
                $createTime = strtotime($date);
            }else{
                $createTime = strtotime(date('Y-m-d'));
            }


            // 周星奖励--主播增加聊币
            $weekAward = $this->config->weekAward;

            if($getNum >= $weekAward['anchorAward']['minNum']){
                $sql1 = 'delete from \Micro\Models\ActivityIncomeLog where createTime = ' . $createTime." and type=".$weekAward['anchorAward']['type'];
                $qry1 = $this->modelsManager->createQuery($sql1);
                $qry1->execute();
                $activityIncomeLog = new \Micro\Models\ActivityIncomeLog();
                $activityIncomeLog->uid = $anchorId;
                $activityIncomeLog->remark = $weekAward['anchorAward']['desc'];
                $activityIncomeLog->money = $weekAward['anchorAward']['awardCash'];
                $activityIncomeLog->type = $weekAward['anchorAward']['type'];
                $activityIncomeLog->createTime = $createTime;
                $activityIncomeLog->save();
            }

            // 周星奖励--富豪增加座驾和徽章
            $user = UserFactory::getInstance($richerId);
            $user->getUserItemsObject()->giveGiftPackage($weekAward['richerAward']['giftPackageId']);

            // 记录周星冠军
            $sql = 'delete from pre_week_star_info_log where giftId = ' . $giftId . ' and createTime = ' . $createTime;
            $connection = $this->di->get('db');
            $connection->execute($sql);

            $weekStarInfoLog = new WeekStarInfoLog();
            $weekStarInfoLog->giftId = $giftId;
            $weekStarInfoLog->anchorId = $anchorId;
            $weekStarInfoLog->getNum = $getNum;
            $weekStarInfoLog->richerId = $richerId;
            $weekStarInfoLog->sendNum = $sendNum;
            $weekStarInfoLog->createTime = $createTime;

            $weekStarInfoLog->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    /*
     * 推荐活动获得奖励定时更新
     * */
    public function recIncome($date = 0) {
        try {
            if($date){
                $createTime = strtotime($date);
            }else{
                $createTime = strtotime(date('Y-m-d'));
            }
            $begin = $createTime - 86400;
            $end = $createTime;

            $recData = \Micro\Models\Recommend::find('status = 0');
            if(empty($recData)){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }else{
                $sql1 = 'delete from \Micro\Models\ActivityIncomeLog where createTime = ' . $end." and type=2";
                $qry1 = $this->modelsManager->createQuery($sql1);
                $res1 = $qry1->execute();
                foreach ($recData as $k => $v) {
                    $sql = "select o.uid,ifnull(sum(o.totalFee), 0) as sum,r.recUid from \Micro\Models\Order o "
                    . " inner join \Micro\Models\Users u on o.uid=u.uid "
                    . " left join \Micro\Models\RecommendLog r on o.uid=r.beRecUid "
                    . " where r.recUid = " . $v->uid . " and (o.payTime - u.createTime) < " . ($v->validity * 86400)
                    . " and o.status = 1 and r.recUid > 0 and o.payType < 1000 and o.payTime >= " . $begin . " and o.payTime < " . $end . ' group by o.uid';
                    $query = $this->modelsManager->createQuery($sql);
                    $data = $query->execute();

                    if($data->valid()){
                        foreach ($data as $key => $val) {
                            // 插入数据
                            $activityIncomeLog = new \Micro\Models\ActivityIncomeLog();
                            $activityIncomeLog->uid = $val->recUid;
                            $activityIncomeLog->remark = $val->uid;
                            //记录聊币【因此金额*比例（整数无%）即可抵消聊币与人民币的比例】
                            $activityIncomeLog->money = $val->sum * $v->proportion;
                            $activityIncomeLog->proportion = $v->proportion;
                            $activityIncomeLog->type = 2;
                            $activityIncomeLog->createTime = $end;
                            $activityIncomeLog->save();
                        }
                    }
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 推荐活动获得奖励定时更新
     * */
    /*public function recIncome($date = 0) {
        try {
            if($date){
                $createTime = strtotime($date);
            }else{
                $createTime = strtotime(date('Y-m-d'));
            }
            $begin = $createTime - 86400;
            $end = $createTime;
            $sql = "select o.uid,sum(o.cashNum) as sum,r.recUid from \Micro\Models\Order o "
                    . " inner join \Micro\Models\Users u on o.uid=u.uid "
                    . " left join \Micro\Models\RecommendLog r on o.uid=r.beRecUid "
                    . " where o.payTime-u.createTime<".$this->config->recommendConfig->validity." and r.recUid>0 and  o.status=1 and o.payType<1000 and o.payTime>=" . $begin . " and o.payTime<" . $end . " group by o.uid";
            $query = $this->modelsManager->createQuery($sql);
            $data = $query->execute();
            $data = $data->toArray();
            if ($data) {
                $sql1 = 'delete from \Micro\Models\ActivityIncomeLog where createTime = ' . $end." and type=2";
                $qry1 = $this->modelsManager->createQuery($sql1);
                $res1 = $qry1->execute();
                foreach ($data as $val) {
                    $activityIncomeLog = new \Micro\Models\ActivityIncomeLog();
                    $activityIncomeLog->uid = $val['recUid'];
                    $activityIncomeLog->remark = $val['uid'];
                    $activityIncomeLog->money = $val['sum'] * $this->config->recommendConfig->proportion;
                    $activityIncomeLog->type = 2;
                    $activityIncomeLog->createTime = $end;
                    $activityIncomeLog->save();
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }*/
    
    /**
     * 活动奖励的每日收益流水表
     *
     */
    public function getActivityDayIncomeLog($date = 0) {
        try {
            if ($date) {
                $time['begin'] = strtotime(date('Y-m-12', strtotime('-1 month', strtotime(date('Y-m-01', $date)))));
                $time['end'] = strtotime(date('Y-m-11', $date));
            } else {
                $day = intval(date('d'));
                if ($day >= 11) {
                    $time['begin'] = strtotime(date('Y-m-12', strtotime('-1 month')));
                    $time['end'] = strtotime(date('Y-m-11', time()));
                } else {
                    $time['begin'] = strtotime(date('Y-m-12', strtotime('-2 month', strtotime(date('Y-m-01')))));
                    $time['end'] = strtotime(date('Y-m-11', strtotime('-1 month', strtotime(date('Y-m-01')))));
                }
            }

            $sql = 'select dg.uid,ifnull(sum(dg.money), 0) as myIncome,dg.createTime,dg.type from \Micro\Models\ActivityIncomeLog as dg '
                    . 'where dg.createTime >= ' . $time['begin'] . ' and dg.createTime <= ' . $time['end'] . ' group by dg.uid';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $addTime = $time['end'];
            if ($res->valid()) {
                $sql = 'delete from \Micro\Models\DayIncomeLog where createTime = ' . $addTime.' and type = ' . $this->config->moneyType[5]['type'];
                $query = $this->modelsManager->createQuery($sql);
                $query->execute();
                foreach ($res as $k => $v) {
                    $myIncome = $v->myIncome;
                    $dayIncome = new \Micro\Models\DayIncomeLog();
                    $dayIncome->uid = $v->uid;
                    $dayIncome->money = $myIncome;
                    $dayIncome->createTime = $addTime;
                    $dayIncome->type = $this->config->moneyType[5]['type'];
                    $dayIncome->description = $this->config->moneyType[5]['desc'];
                    $dayIncome->save();
                }
            }
            // $content = json_encode($res->toArray());
            return $this->status->retFromFramework($this->status->getCode('OK'), 'Activity_Day_Income_Log---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getFamilyAnchorRanks($date = 0){
        try {
            if ($date) {
                $time['begin'] = strtotime(date('Y-m-11', strtotime('-1 month', strtotime(date('Y-m-01', $date)))));
                $time['end'] = strtotime(date('Y-m-10', $date));
            } else {
                $day = intval(date('d'));
                if ($day >= 11) {
                    $time['begin'] = strtotime(date('Y-m-11'));
                    $time['end'] = strtotime(date('Y-m-d',time())) - 1;
                } else {
                    $time['begin'] = strtotime(date('Y-m-11', strtotime('-1 month', strtotime(date('Y-m-01')))));
                    $time['end'] = strtotime(date('Y-m-d',time())) - 1;
                }
                $week = date('w');
                $thisWeek = $this->getTimeRange('thisWeek');
            }


            $familys = \Micro\Models\Family::find('status = 1');
            if($familys->valid()){
                $userSql = 'select ui.uid,ui.avatar,up.level2 from \Micro\Models\UserInfo as ui left join Micro\Models\UserProfiles as up on ui.uid = up.uid';
                $query = $this->modelsManager->createQuery($userSql);
                $result = $query->execute();
                $users = array();
                if($result->valid()){
                    foreach ($result as $key => $value) {
                        $users[$value->uid] = array(
                            'avatar' => $value->avatar,
                            'level2' => $value->level2
                        );
                    }
                }
                $giftMonth = array();
                $giftWeek = array();
                $charmMonth = array();
                $charmWeek = array();
                foreach ($familys as $k => $v) {
                    //月榜
                    $time1 = ' and cd.createTime >= ' . $time['begin'] . ' AND cd.createTime <= ' . $time['end'];
                    //周榜
                    $time2 = ' and cd.createTime >= ' . $thisWeek['begin'] . ' AND cd.createTime <= ' . $thisWeek['end'];
                    //礼物
                    //托 cd.isTuo = 0 and
                    $sqlGift = 'select cd.remark,cd.receiveUid,ifnull(sum(cd.income), 0) as num from \Micro\Models\ConsumeDetailLog as cd '
                        . ' where cd.receiveUid > 0 and cd.type < ' . $this->config->consumeType->coinType 
                        . ' and cd.income > 0 and cd.familyId = ' . $v->id;
                    $otherCon1 = ' GROUP BY cd.receiveUid order by num desc limit 0,15';
                    $sql1 = $sqlGift . $time1 . $otherCon1;
                    $sql2 = $sqlGift . $time2 . $otherCon1;

                    /*//魅力星
                    $sqlCharm = 'select sum(cd.count) as num,cd.receiveUid,cd.remark from Micro\Models\ConsumeDetailLog as cd '
                        . ' where cd.type = ' . $this->config->consumeType->sendStar . ' and cd.familyId = ' . $v->id;
                    $otherCon2 = ' group by cd.receiveUid order by num desc';
                    $sql3 = $sqlCharm . $time1 . $otherCon2;
                    $sql4 = $sqlCharm . $time2 . $otherCon2;*/

                    $query1 = $this->modelsManager->createQuery($sql1);
                    $result1 = $query1->execute();
                    if($result1->valid()){
                        $tmp = array();
                        foreach ($result1 as $k1 => $v1) {
                            $tmp[] = array(
                                'uid' => $v1->receiveUid,
                                'nickName' => $v1->remark,
                                'num' => $v1->num,
                                'avatar' => $users[$v1->receiveUid]['avatar'],
                                'level2' => $users[$v1->receiveUid]['level2']
                            );
                        }
                        $giftMonth[$v->id] = $tmp;
                        unset($tmp);
                    }    

                    $query2 = $this->modelsManager->createQuery($sql2);
                    $result2 = $query2->execute();
                    if($result2->valid()){
                        $tmp = array();
                        foreach ($result2 as $k2 => $v2) {
                            $tmp[] = array(
                                'uid' => $v2->receiveUid,
                                'nickName' => $v2->remark,
                                'num' => $v2->num,
                                'avatar' => $users[$v2->receiveUid]['avatar'],
                                'level2' => $users[$v2->receiveUid]['level2']
                            );
                        }
                        $giftWeek[$v->id] = $tmp;
                        unset($tmp);
                    }

                    /*$query3 = $this->modelsManager->createQuery($sql3);
                    $result3 = $query3->execute();
                    if($result3->valid()){
                        foreach ($result3 as $k3 => $v3) {
                            $tmp[] = array(
                                'uid' => $v3->receiveUid,
                                'remark' => $v3->remark,
                                'num' => $v3->num,
                                'avatar' => $users[$v3->receiveUid]['avatar'],
                                'level3' => $users[$v3->receiveUid]['level2']
                            );
                        }
                        $charmMonth[$v->id] = $tmp;
                    }

                    $query4 = $this->modelsManager->createQuery($sql4);
                    $result4 = $query4->execute();
                    if($result4->valid()){
                        foreach ($result4 as $k4 => $v4) {
                            $tmp[] = array(
                                'uid' => $v4->receiveUid,
                                'remark' => $v4->remark,
                                'num' => $v4->num,
                                'avatar' => $users[$v4->receiveUid]['avatar'],
                                'level4' => $users[$v4->receiveUid]['level2']
                            );
                        }
                        $charmWeek[$v->id] = $tmp;
                    }*/
                }
            }
            $result = $this->rankLogSave($this->config->rankLogType['family_anchor_gift_month'], $giftMonth);
            $result = $this->rankLogSave($this->config->rankLogType['family_anchor_gift_week'], $giftWeek);
            // $result = $this->rankLogSave($this->config->rankLogType['family_anchor_charm_month'], $charmMonth);
            // $result = $this->rankLogSave($this->config->rankLogType['family_anchor_charm_week'], $charmWeek);
            return $this->status->retFromFramework($this->status->getCode('OK'), 'getFamilyAnchorRanksLog---');
            
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获取时间范围
     * */

    private function getTimeRange($date) {
        switch ($date) {
            case 'day'://日榜为昨天0-24时
                $nowTime = strtotime(date('Y-m-d',time()));
                $time['begin'] = $nowTime - 86400;
                $time['end'] = $nowTime - 1;
                /*$time['begin'] = strtotime("Today");
                $time['end'] = strtotime("Tomorrow") - 1;*/
                break;
            case 'week'://上周一0时至上周日24时=>改成若周一取上周数据，否则本周一到昨天
                $week = date('w');
                if($week == 1){
                    // $lastMondayTime = strtotime(date('Y-m-d',strtotime('this Monday')));
                    $lastMondayTime = strtotime(date('Y-m-d',strtotime('-1 Monday')));
                    $endTime = $lastMondayTime + 86400 * 7 - 1;
                    // $endTime = strtotime(date('Y-m-d',time())) - 1;
                    // $lastMondayTime = strtotime(date('Y-m-d',strtotime('-1 Monday')));
                }else{
                    $lastMondayTime = strtotime(date('Y-m-d',strtotime('-1 Monday')));
                    $endTime = strtotime(date('Y-m-d',time())) - 1;
                    // $lastMondayTime = strtotime(date('Y-m-d',strtotime('-2 Monday')));
                }
                $time['begin'] = $lastMondayTime;
                $time['end'] = $endTime;//$lastMondayTime + 86400 * 7 - 1;
                /*$time['begin'] = strtotime("last Monday");
                $time['end'] = strtotime("last Sunday") - 1;*/
                break;
            case 'month'://上月11日0时至本月10日数据=>改成12日前取上个月数据，否则本月11日到昨天
                $day = intval(date('d'));
                if($day > 11){
                    $time['begin'] = strtotime(date('Y-m-11',strtotime('this month')));
                    $time['end'] = strtotime(date('Y-m-d',time())) - 1;
                }else{
                    $time['begin'] = strtotime(date('Y-m-11',strtotime('-1 month', strtotime(date('Y-m-01')))));
                    $time['end'] = strtotime(date('Y-m-d',time())) - 1;
                }
                /*$compareTime = strtotime(date('Y-m-11'));
                if(time() > $compareTime){
                    $time['begin'] = strtotime(date('Y-m-11',strtotime('this month')));
                    $time['end'] = strtotime(date('Y-m-d',time())) - 1;
                    // $time['begin'] = strtotime(date('Y-m-11',strtotime('-1 month')));
                    // $time['end'] = $compareTime - 1;
                }else{
                    $time['begin'] = strtotime(date('Y-m-11',strtotime('-1 month')));
                    $time['end'] = strtotime(date('Y-m-d',time())) - 1;
                    // $time['begin'] = strtotime(date('Y-m-11',strtotime('-2 month')));
                    // $time['end'] = strtotime(date('Y-m-11',strtotime('-1 month'))) - 1;
                }*/
                /*$time['begin'] = strtotime('first day of -1 month');
                $time['end'] = strtotime('first day of this month') - 1;*/
                break;
            case 'total'://截止昨天
                $time['begin'] = 0;
                $time['end'] = strtotime(date('Y-m-d',time())) - 1;
                // $time['end'] = strtotime("Tomorrow");
                break;
            case 'thisWeek'://这周一0时至昨天24时
                $week = date('w');
                if($week == 1){
                    $thisMondayTime = strtotime(date('Y-m-d',strtotime('this Monday')));
                    $time['begin'] = $thisMondayTime;
                    $time['end'] = $thisMondayTime;
                }else{
                    $thisMondayTime = strtotime(date('Y-m-d',strtotime('-1 Monday')));
                    $nowTime = strtotime(date('Y-m-d',time()));
                    $time['begin'] = $thisMondayTime;
                    $time['end'] = $nowTime - 1;
                }
                break;
            case 'lastWeek'://上周一0时至上周日24时
                $week = date('w');
                if($week == 1){
                    $lastMondayTime = strtotime(date('Y-m-d',strtotime('-1 Monday')));
                }else{
                    $lastMondayTime = strtotime(date('Y-m-d',strtotime('-2 Monday')));
                }
                $time['begin'] = $lastMondayTime;
                $time['end'] = $lastMondayTime + 86400 * 7 - 1;
                break;
            default:
                $nowTime = strtotime(date('Y-m-d',time()));
                $time['begin'] = $nowTime - 86400;
                $time['end'] = $nowTime - 1;
                /*$time['begin'] = strtotime("Today");
                $time['end'] = strtotime("Tomorrow");*/
                break;
        }
        return $time;
    }

    public function syncGa(){
        ini_set("max_execution_time", "0");
        $url = "http://www.google-analytics.com/analytics.js";
        $ch = curl_init();
        $timeout = 600;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $contents = curl_exec($ch);
        curl_close($ch);
        $ret = $this->saveSyncGa($contents);
        return $this->status->retFromFramework($this->status->getCode('OK'), 'syncGa---' . $ret);
    }

    public function saveSyncGa($logData){
        if(empty($logData)){
            return FALSE;
        }

        $filePath = $this->pathGenerator->getSyncGa();
        $fileName = 'analytics.js';
        $this->storage->write($filePath . $fileName, $logData, TRUE);
        try {
            echo $this->pathGenerator->getFullSyncGa($fileName);
            return TRUE;
        } catch (\Exception $e) {
            return FALSE;
        }
    }
    
    
    /**
     * 直播间用户在线人数定时写入表 add by 2015/10/12
     *
     */
    public function roomUserCountCollect() {
        try {
            $platArray = array("pc" => 1, "ios" => 2, "android" => 3); //平台
            $now = time();

            //查询当前各平台在线数 ,不排重
            foreach ($platArray as $val) {
                $res = \Micro\Models\RoomUserCount::findfirst("platform=" . $val);
                //写入表
                $new = new \Micro\Models\RoomUserCountHour();
                $new->platform = $val;
                $new->createTime = $now;
                $new->count = $res ? $res->count : 0;
                $new->type = 1;
                $new->save();
            }

            //查询当前各平台在线数 去重 add by 2015/10/19
            foreach ($platArray as $val) {
                $sql = "select platform,count(DISTINCT uid) as count from pre_user_active_count where platform={$val} and endTime=0";
                $connection = $this->di->get("db");
                $res = $connection->fetchOne($sql);
                //写入表
                $new = new \Micro\Models\RoomUserCountHour();
                $new->platform = $val;
                $new->createTime = $now;
                $new->count = $res['count'] ? $res['count'] : 0;
                $new->type = 2;
                $new->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), 'Room_User_Count_Collect---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 直播间用户活跃人数定时写入表 add by 2015/10/12
     *
     */
    public function userActiveCountCollect($date='') {
        !$date && $date = date("Y-m-d"); //默认为今天
        //前一天
        $lastday = date("Ymd", strtotime($date) - 86400);
        $time = strtotime($date);
        try {
            //给前一天endTime=0的数据 记录临时在线时间
            $updatesql = "update pre_user_active_count set tempCount={$time}-createTime where date=" . $lastday . " and endTime=0";
            $connection = $this->di->get('db');
            $connection->execute($updatesql);


            $acttime = 180; //在线时间大于等于3分钟的为活跃用户
            $platArray = array("pc" => 1, "ios" => 2, "android" => 3); //平台
            //先删除记录
            $sql = "delete from \Micro\Models\UserActiveCountDay where date=" . $lastday;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            foreach ($platArray as $val) {
                $sql = "select count(1) as count "
                        . " from "
                        . " (select sum(timeCount+tempCount) as sum from pre_user_active_count where platform={$val} and date={$lastday} group by uid having sum>={$acttime}) "
                        . " a";
                $res = $connection->fetchOne($sql);
                $count = $res['count'] ? $res['count'] : 0;
            
                //写入表
                $new = new \Micro\Models\UserActiveCountDay();
                $new->platform = $val;
                $new->date = $lastday;
                $new->count = $count;
                $new->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), 'User_Active_Count_Collect---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //红包过期退还 add by 2015/10/27
    public function redPacketBeReturned($date = '') {
        !$date && $date = date("Y-m-d"); //默认为今天
        //前一天
        $thisday = strtotime($date);
        $time = $thisday - 3600;
        $now=time();
        try {
            //每天午夜12点，清算所有红包，存在时间超过1小时的红包全部过期。存在时间小于1个消失的红包，不过期。
            $sql = "select id,uid from pre_red_packet where status=1 and initTime<" . $time;
            $connection = $this->di->get("db");
            $res = $connection->fetchAll($sql);
            if ($res) {
                foreach ($res as $val) {
                    //查询未被领取的红包金额
                    $sumsql = "select ifnull(sum(money), 0) as sum from pre_red_packet_log where redPacketId=" . $val['id'] . " and uid=0 ";
                    $sumres = $connection->fetchOne($sumsql);
                    $sum = $sumres ? $sumres['sum'] : 0;
                    
                    if ($sum > 0) {
                        //修改红包状态
                        $updatesql = "update pre_red_packet set status=2,returnMoney=" . $sum . ",returnTime=" . $now . " where id=" . $val['id'];
                        $connection->execute($updatesql);
                        
            
                        //修改每个红包状态
                        $updatelogsql = "update pre_red_packet_log set status=0 where redPacketId=" . $val['id'] . " and uid=0";
                        $connection->execute($updatelogsql);

                        //增加聊币
                        $profilesql = "update pre_user_profiles set cash=cash+" . $sum . " where uid=" . $val['uid'];
                        $connection->execute($profilesql);
                        
                        //给用户发送通知
                        $user = UserFactory::getInstance($val['uid']);
                        $content = $user->getUserInformationObject()->getInfoContent($this->config->informationCode->redPacketReturn,array(0 => $sum));
                        $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    }
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), 'Red_Packet_Be_Returned---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //视频回放每日记录
    public function saveVideo($date = ''){
        try {
            !$date && $date = date("Y-m-d"); //默认为今天
            $endTime = strtotime($date);
            $startTime = $endTime - 86400;

            $ftpConfigs = $this->config->ftpConfigs;

            $conn = ftp_connect($ftpConfigs['host']);

            if(!$conn){
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            }

            if(ftp_login($conn, $ftpConfigs['username'], $ftpConfigs['password'])){
                $filenameList = ftp_nlist($conn, "/record");
                if(!empty($filenameList)){
                    $createTime = strtotime(date('Y-m-d'));
                    //先删除记录
                    $sql1 = "delete from \Micro\Models\VideoReview where createTime = " . $createTime;
                    $query1 = $this->modelsManager->createQuery($sql1);
                    $query1->execute();

                    $sql = 'insert into pre_video_review (uid, streamName, createTime, publicTime) values ';
                    $addData = '';
                    foreach($filenameList as $filename){
                        $len = strlen($filename);
                        $arr = explode('_', substr($filename, 10, -4));
                        $uid = $arr[0];
                        $publicTime = $arr[1];
                        if(strlen($uid) < 5 || $publicTime < $startTime || $publicTime >= $endTime){
                            continue;
                        }
                        $addData .= '(' . $uid . ',"' . substr($filename, 0, -4) . '",' . $createTime . ',' . $publicTime . '),';
                    }
                    // var_dump($sql . substr($addData, 0, -1));die;
                    if($addData != ''){
                        $connection = $this->di->get('db');
                        $connection->execute($sql . substr($addData,0, -1));
                    }
                }

                return $this->status->retFromFramework($this->status->getCode('OK'), 'Save_Video---');
            }

            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    
    
    //月榜大作战记录写表 add by 2015/11/18
    public function monthRankRecord() {
        try {
            //时间必须是11号
            if (date("d") != 11) {
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
            
            $month=date('Ym');
            
            //查询主播收益月榜
            $info = \Micro\Models\MonthRankLog::findfirst("month=" . $month . " and type=1"); //查询是否已写入过
            $rankMgr = $this->di->get('rankMgr');
            if (!$info) {
                 $result = $rankMgr->getStarRank('month');
                if ($result['code'] == $this->status->getCode('OK')) {
                    //写入记录
                    $rankList = $result['data'];
                    $new = new \Micro\Models\MonthRankLog();
                    $new->content = json_encode($rankList);
                    $new->type = 1;
                    $new->month = $month;
                    $new->isGet = 0;
                    $new->getTime = 0;
                    $new->save();
                }
            }


            //查询用户消费月榜
            $info = \Micro\Models\MonthRankLog::findfirst("month=" . $month . " and type=2"); //查询是否已写入过
            if (!$info) {
                $result = $rankMgr->getRichRank('month');
                if ($result['code'] == $this->status->getCode('OK')) {
                    //写入记录
                    $rankList = $result['data'];
                    $new = new \Micro\Models\MonthRankLog();
                    $new->content = json_encode($rankList);
                    $new->type = 2;
                    $new->month = $month;
                    $new->isGet = 0;
                    $new->getTime = 0;
                    $new->save();
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), 'monthRankRecord---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //月榜大作战奖励发放 add by 2015/11/18
    public function sendMonthRankReward() {
        try {
            //发放主播收益月榜奖励
            $month = date("Ym");
            $info = \Micro\Models\MonthRankLog::findfirst("month=" . $month . " and type=1 and isGet=0");
            if ($info) {
                $rankList = json_decode($info->content, TRUE);
                $count = count($this->config->monthRankConfigs->incomeRank->toArray()); //前几名发放奖励
                $result = array_slice($rankList, 0, $count);
                if ($result) {
                    $i = 1;
                    $createTime = strtotime(date('Y-m-d'));
                    foreach ($result as $val) {
                        $res = false;
                        //发放奖励
                        $reward = $this->config->monthRankConfigs->incomeRank[$i];
                        if ($reward['cash']) {//发放聊币奖励
                            $sql1 = 'delete from \Micro\Models\ActivityIncomeLog where createTime = ' . $createTime . " and type=5 and uid=" . $val['uid'];
                            $qry1 = $this->modelsManager->createQuery($sql1);
                            $qry1->execute();
                            $activityIncomeLog = new \Micro\Models\ActivityIncomeLog();
                            $activityIncomeLog->uid = $val['uid'];
                            $activityIncomeLog->remark = $this->config->activityIncomeType[5];
                            $activityIncomeLog->money = $reward['cash'];
                            $activityIncomeLog->type = 5;
                            $activityIncomeLog->createTime = $createTime;
                            $res = $activityIncomeLog->save();
                        }

                        //给用户发送通知
                        if ($res) {
                            $sendUser = UserFactory::getInstance($val['uid']);
                            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $reward['message'], 'link' => '', 'operType' => ''));
                        }
                        $i++;
                    }
                }
                //修改发放状态
                $info->isGet = 1;
                $info->getTime = time();
                $info->save();
            }


            //发放用户消费月榜奖励
            $info = \Micro\Models\MonthRankLog::findfirst("month=" . $month . " and type=2 and isGet=0");
            if ($info) {
                $rankList = json_decode($info->content, TRUE);
                $count = count($this->config->monthRankConfigs->richRank->toArray()); //前几名发放奖励
                $result = array_slice($rankList, 0, $count);

                if ($result) {
                    $i = 1;
                    $createTime = strtotime(date('Y-m-d'));
                    foreach ($result as $val) {
                        $res = false;
                        //发放奖励
                        $reward = $this->config->monthRankConfigs->richRank[$i];
                        $user = UserFactory::getInstance($val['uid']);
                        if ($reward['carId']) {//发放座驾
                            $expireTime = $reward['expireDay'] * 3600 * 24;
                            $res = $user->getUserItemsObject()->giveCar($reward['carId'], $expireTime);
                        }
                        //给用户发送通知
                        if ($res) {
                            $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $reward['message'], 'link' => '', 'operType' => ''));
                        }
                        $i++;
                    }
                }
                //修改发放状态
                $info->isGet = 1;
                $info->getTime = time();
                $info->save();
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), 'sendMonthRankReward---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //电影结算
    public function resultMovie($type = 1){
        if (time() < $this->config->anchorMovie->beginTime || time() > $this->config->anchorMovie->endTime) {//活动已结束
            return $this->status->retFromFramework($this->status->getCode('OK'), 'Activity Has End---');
        }
        try {
            $activityRound = \Micro\Models\ActivityRound::findfirst('type = ' . $type);
            if(!$activityRound){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $anchorMovieConfig = $this->config->anchorMovie->toArray();

            $startTime = $activityRound->startTime;
            $endTime = $startTime + $anchorMovieConfig['periodTime'];


            $sqldel = 'delete from \Micro\Models\ActivityResultLog where type = 1 and createTime = ' . $endTime;
            $qrydel = $this->modelsManager->createQuery($sqldel);
            $qrydel->execute();

            $sqlupdate = 'update \Micro\Models\ActivityRound set times = times + 1,startTime = ' . $endTime . ' where type = 1';
            $qryupdate = $this->modelsManager->createQuery($sqlupdate);
            $qryupdate->execute();

            $data = array();
            $res = \Micro\Models\ActivityAnchors::find();
            $j = 0;
            if($res){
                foreach ($res as $k => $v) {
                    $anchor = UserFactory::getInstance($v->uid);
                    $anchorInfo = $anchor->getUserInfoObject()->getUserInfo();
                    $data[$j]['uid'] = $v->uid;
                    $data[$j]['nickName'] = $anchorInfo['nickName'];
                    $data[$j]['avatar'] = $anchorInfo['avatar'];
                    $sumsql = "select sum(count) sum "
                        . "from \Micro\Models\ConsumeDetailLog "
                        . "where receiveUid=" . $v->uid . " and type=" . $this->config->consumeType->sendGift
                        . " and itemId=" . $anchorMovieConfig['giftId'] . " and createTime >= " . $startTime . " and createTime < " . $endTime;
                    $sumquery = $this->modelsManager->createQuery($sumsql);
                    $sumres = $sumquery->execute();
                    $sumres = $sumres->toArray();

                    $data[$j]['sum'] = $sumres[0]['sum'] ? $sumres[0]['sum'] : 0;
                    $sort[] = $data[$j]['sum'];

                    //主播下贡献前十的用户信息
                    $listsql = "select uid,sum(count) sum "
                        . "from \Micro\Models\ConsumeDetailLog "
                        . "where receiveUid=" . $v->uid . " and type=" . $this->config->consumeType->sendGift
                        . " and itemId=" . $anchorMovieConfig['giftId'] . " and createTime >= " . $startTime . " and createTime < " . $endTime
                        . " group by uid order by sum desc limit 10";
                    $listquery = $this->modelsManager->createQuery($listsql);
                    $listres = $listquery->execute();
                    $listres = $listres->toArray();
                    $list = array();
                    if ($listres) {
                        $i = 1;
                        foreach ($listres as $val) {
                            $user = UserFactory::getInstance($val['uid']);
                            $userInfo = $user->getUserInfoObject()->getUserInfo();
                            $tmp = array(
                                'avatar' => $userInfo['avatar'],
                                'nickName' => $userInfo['nickName'],
                                'sum' => $val['sum']
                            );
                            array_push($list, $tmp);
                            if($i == 1 && $data[$j]['sum'] > $anchorMovieConfig['finishNum']){
                                $user->getUserInformationObject()->addUserInformation(
                                    $this->config->informationType->system,
                                    array(
                                        'content' => date('Y-m-d', $startTime) . '~' . date('Y-m-d', $endTime) . $anchorMovieConfig['anchorMessage'][0] . $anchorInfo['nickName'] . $anchorMovieConfig['anchorMessage'][1],
                                        'link' => '',
                                        'operType' => ''
                                    )
                                );
                                $anchor->getUserInformationObject()->addUserInformation(
                                    $this->config->informationType->system,
                                    array(
                                        'content' => date('Y-m-d', $startTime) . '~' . date('Y-m-d', $endTime) . $anchorMovieConfig['userMessage'][0] . $anchorInfo['nickName'] . $anchorMovieConfig['userMessage'][1],
                                        'link' => '',
                                        'operType' => ''
                                    )
                                );
                            }
                            $i++;
                        }
                    }
                    $data[$j]['list'] = $list;
                    $j++;
                }

                array_multisort($sort, SORT_DESC, $data);
            }
            $new = new \Micro\Models\ActivityResultLog();
            $new->times = $activityRound->times;
            $new->startTime = $startTime;
            $new->endTime = $endTime;
            $new->rankInfo = json_encode($data);
            $new->createTime = $endTime;
            $new->type = $type;
            $new->save();

            return $this->status->retFromFramework($this->status->getCode('OK'), 'resultMovie---');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
