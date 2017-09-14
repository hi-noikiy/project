<?php

namespace Micro\Frameworks\Logic\Treasure;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Models\UserInfo;

class TreasureMgr {

    protected $di;
    protected $status;
    protected $config;
    protected $validator;
    protected $comm;
    protected $userAuth;
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
        $this->db = $this->di->get('db');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->roomModule = $this->di->get('roomModule');
        $this->normalLib = $this->di->get('normalLib');
        $this->logger = $this->di->get('logger');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->url = $this->di->get('url');
    }

    public function errLog($errInfo) {
        $this->logger->error('【TreasureMgr】 error : '.$errInfo);
    }

    //获取最近开奖
    public function getRecent($num = 3){
        try {
            !$num && $num = 3;
            $sql = 'select gc.totalNums,gc.id,bpr.times,bpr.openTime,gc.name,gc.price,gc.img,gc.type,ui.nickName,ui.uid '
                . ' from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bpr.uid '
                . ' left join \Micro\Models\GoodsConfigs as gc on bpr.type = gc.id '
                . ' where gc.type < 10 and bpr.status = 1 order by bpr.openTime desc limit ' . $num;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['totalNums'] = $v->totalNums;
                    $tmp['type'] = $v->type;
                    $tmp['times'] = $v->times;
                    $tmp['id'] = $v->id;
                    $tmp['name'] = $v->name;
                    $tmp['price'] = $v->price;
                    $tmp['img'] = $v->img;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['uid'] = $v->uid;
                    $tmp['openTime'] = $v->openTime;
                    $tmp['winnerBetNums'] = $this->getWinnerBetNums($v->id, $v->times, $v->uid);
                    array_push($data, $tmp);
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取该用户投的注数
    private function getWinnerBetNums($type = 0, $times = 0, $uid = 0){
        try {
            $betNumRes = \Micro\Models\BetPointsLog::sum(
                array('column'=>'nums','conditions'=>'times = ' . $times . ' and type = ' . $type . ' and uid = ' . $uid)
            );

            $betNums = $betNumRes ? $betNumRes : 0;
            return $betNums;
        } catch (\Exception $e) {
            $this->errLog('getWinnerBetNums errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //所有商品
    public function getAllGoodsList($type = 1, $page = 1, $pageSize = 20){
        try {
            !$page && $page = 1;
            !$pageSize && $pageSize = 20;
            $limit = ($page - 1) * $pageSize;

            $sqlCommon = ' from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\BetPointsLog as bp on bp.type = bpr.type and bpr.times = bp.times '
                . ' left join \Micro\Models\GoodsConfigs as gc on gc.id = bpr.type '
                . ' where bpr.status = 0 and gc.isShow = 0 and (gc.type > 0 and gc.type < 10) group by bpr.type ';
            switch ($type) {
                case 1://即将开奖
                    $selectFields = 'select bpr.times,(gc.totalNums - ifnull(sum(bp.nums), 0)) as leftNums,gc.name,ifnull(gc.description, "") as description,gc.id,gc.price,gc.type,gc.totalNums,gc.img,bpr.times';
                    $orderFields = ' order by gc.orderType desc,leftNums asc ';
                    break;

                case 2://最热商品
                    $selectFields = 'select bpr.times,(gc.totalNums - ifnull(sum(bp.nums), 0)) as leftNums,gc.name,ifnull(gc.description, "") as description,gc.id,gc.price,gc.type,gc.totalNums,gc.img,bpr.times';
                    $orderFields = ' order by gc.orderType desc,bpr.times desc,leftNums asc ';
                    break;
                
                default://即将开奖
                    $selectFields = 'select bpr.times,(gc.totalNums - ifnull(sum(bp.nums), 0)) as leftNums,gc.name,ifnull(gc.description, "") as description,gc.id,gc.price,gc.type,gc.totalNums,gc.img,bpr.times';
                    $orderFields = ' order by gc.orderType desc,leftNums asc ';
                    break;
            }

            $sql = $selectFields . $sqlCommon . $orderFields . ' limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['totalNums'] = $v->totalNums;
                    $tmp['type'] = $v->type;
                    $tmp['id'] = $v->id;
                    $tmp['name'] = $v->name;
                    $tmp['description'] = $v->description;
                    $tmp['price'] = $v->price;
                    $tmp['img'] = $v->img;
                    $tmp['leftNums'] = $v->leftNums;
                    $tmp['hasBetNums'] = $v->totalNums - $v->leftNums;                    
                    $tmp['times'] = $v->times;
                    array_push($data, $tmp);
                }
            }

            $sqlCount ='select count(1) as count from \Micro\Models\BetPointsResultLog as bpr left join \Micro\Models\GoodsConfigs as gc on bpr.type = gc.id where bpr.status = 0 and gc.isShow = 0 and (gc.type > 0 and gc.type < 10)';
            $queryCount = $this->modelsManager->createQuery($sqlCount);
            $resCount = $queryCount->execute();
            $count = $resCount->valid() ? $resCount->toArray()[0]['count'] : 0;

            return $this->status->retFromFramework($this->status->getCode('OK'), array('list'=>$data,'count'=>$count));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //商品详情
    public function getGoodsInfo($id = 0, $times = 0){
        $isValid = $this->validator->validate(array('id'=>$id));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $cond = $times ? ' and bpr.times = ' . $times : ' and bpr.status = 0 ';
            $sql = 'select GROUP_CONCAT(imgUrl) as imgs,bpr.status,bpr.times,gc.name,ifnull(gc.description, "") as description,bpr.id as gid,gc.id,gc.price,gc.type,gc.totalNums,gc.img,gc.perPoint,gc.perCash,bpr.uid,bpr.openTime '
                . ' from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\GoodsConfigs as gc on bpr.type = gc.id '
                . ' left join \Micro\Models\GoodsImages as gi on gi.goodsId = gc.id '
                . ' where gc.isShow = 0 and bpr.type = ' . $id . $cond . ' limit 1';
                // echo $sql;die;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            if(!$res->valid()){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $goodsInfo = $res->toArray()[0];
            $anchorUid = $goodsInfo['type'];
            $gid = $goodsInfo['gid'];
            if($anchorUid == 0 || !$gid){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $goodsInfo['imgs'] = $goodsInfo['imgs'] ? explode(',', $goodsInfo['imgs']) : array();
            $openingData = array();
            $openedInfo = array();

            if($goodsInfo['status'] == 0){//未开奖
                $times = $goodsInfo['times'];
                //进行中期数
                $openingTimes = $times;
                $isOpening = 1;
                $hasBetNums = \Micro\Models\BetPointsLog::sum(
                    array("column" => "nums", "conditions" => "type = " . $id . ' and times = ' . $times)
                );
                $openingData['hasBetNums'] = $hasBetNums ? $hasBetNums : 0;
                $openingData['times'] = $times;
                $openingData['totalNums'] = $goodsInfo['totalNums'];
                $openingData['leftNums'] = $goodsInfo['totalNums'] - $openingData['hasBetNums'];

                //上期开奖结果待定

            }else{//已开奖
                //获取进行中数据
                $sqlOpen = 'select bpr.times,ifnull(sum(bp.nums), 0) as hasBetNums '
                    . ' from \Micro\Models\BetPointsResultLog as bpr '
                    . ' left join \Micro\Models\BetPointsLog as bp on bp.type = bpr.type and bpr.times = bp.times '
                    . ' where bpr.type = ' . $id . ' and bpr.status = 0 limit 1';
                $queryOpen = $this->modelsManager->createQuery($sqlOpen);
                $resOpen = $queryOpen->execute();

                if($resOpen->valid()){
                    $tmp = $resOpen->toArray()[0];
                    $openingData['hasBetNums'] = $tmp['hasBetNums'];
                    $openingData['times'] = $tmp['times'];
                    $openingData['totalNums'] = $goodsInfo['totalNums'];
                    $openingData['leftNums'] = $goodsInfo['totalNums'] - $tmp['hasBetNums'];
                    //进行中期数
                    $openingTimes = $tmp['times'];
                }

                $isOpening = 0;

                //开奖结果
                $openedInfo = $this->getOpenedInfo($id, $times, $goodsInfo['uid']);
                $openedInfo['openTime'] = $goodsInfo['openTime'] ? date('Y-m-d H:i:s',$goodsInfo['openTime']) : '';

            }

            $bettingLog = $this->getBettingLog($id, $times);
            $user = $this->userAuth->getUser();
            if(!$user){
                $myBettingLog = array();
            }else{
                $uid = $user->getUid();
                $myBettingLog = $this->getMyBettingLog($id, $times, $uid);
            }

            //
            $anchorData = '';
            if($anchorUid > 10){
                $anchorInfo = \Micro\Models\UserInfo::findFirst('uid = ' . $anchorUid);
                if(!empty($anchorInfo)){
                    $anchorData = array(
                        'uid' => $anchorInfo->uid,
                        'nickName' => $anchorInfo->nickName,
                        'avatar' => $anchorInfo->avatar
                    );
                }
            }
            

            return $this->status->retFromFramework(
                $this->status->getCode('OK'), 
                array(
                    'openedInfo' => $openedInfo,
                    'openingData' => $openingData,
                    'goodsInfo' => $goodsInfo,
                    'bettingLog' => $bettingLog,
                    'myBettingLog' => $myBettingLog,
                    'openingTimes' => $openingTimes,
                    'isOpening' => $isOpening,
                    'thisTimes' => $times,
                    'anchorData' => $anchorData
                )
            );

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取中奖用户信息
    private function getOpenedInfo($id = 0, $times = 0, $uid = 0){
        try {
            $sql = 'select bp.uid,ui.nickName,sum(bp.nums) as hisBetNums,ui.avatar '
                . ' from \Micro\Models\BetPointsLog as bp '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bp.uid '
                . ' where bp.type = ' . $id . ' and bp.times = ' . $times . ' and bp.uid = ' . $uid . ' limit 1 ';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $openedInfo = $res->valid() ? $res->toArray()[0] : array();

            return $openedInfo;
        } catch (\Exception $e) {
            $this->errLog('getOpenedInfo errorMessage = ' . $e->getMessage());
            return array();
        }
    }

    //某期商品的投注记录
    public function getBettingLog($id = 0, $times = 0){
        try {
            $sql = 'select ui.nickName,bp.uid,bp.nums,createTime,ui.avatar from \Micro\Models\BetPointsLog as bp '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bp.uid '
                . ' where bp.type = ' . $id . ' and bp.times = ' . $times . ' order by bp.createTime desc,bp.id desc ';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $data = array();
            $time = time();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['nickName'] = $v->nickName;
                    $tmp['uid'] = $v->uid;
                    $tmp['nums'] = $v->nums ? $v->nums : 0;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $tmp['createTime'] = $this->dealTime($time, $v->createTime);
                    array_push($data, $tmp);
                }
            }

            return $data;
        } catch (\Exception $e) {
            $this->errLog('getBettingLog errorMessage = ' . $e->getMessage());
            return array();
        }
    }

    //
    private function dealTime($time, $createTime){
        $intervalTime = $time - $createTime;
        if($intervalTime < 60){
            return '刚刚';
        }else if($intervalTime < 3600){
            return floor($intervalTime / 60) . '分钟前';
        }else if($intervalTime < 86400){
            return floor($intervalTime / 3600) . '小时前';
        }else{
            return floor($intervalTime / 86400) . '天前';
        }
    }

    //某期某个用户投注记录
    public function getMyBettingLog($id = 0, $times = 0, $uid = 0){
        try {
            $sql = 'select uid,nums,createTime from \Micro\Models\BetPointsLog '
                . ' where type = ' . $id . ' and times = ' . $times . ' and uid = ' . $uid . ' order by createTime desc,id desc';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $data = array();
            $time = time();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['nums'] = $v->nums ? $v->nums : 0;
                    $tmp['createTime'] = $this->dealTime($time, $v->createTime);
                    array_push($data, $tmp);
                }
            }

            return $data;
        } catch (\Exception $e) {
            $this->errLog('getMyBettingLog errorMessage = ' . $e->getMessage());
            return array();
        }
    }

    //投注
    public function doBetting($type = 0, $times = 0, $nums = 0, $kind = 1, $platform = 1){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $userData = $user->getUserInfoObject()->getUserAccountInfo();
        if($userData['internalType'] == 1 || $userData['internalType'] == 2){
            return $this->status->retFromFramework($this->status->getCode('NOT_ALLOWED_TO_SEND'));
        }
        $isValid = $this->validator->validate(array('id'=>$type,'betTimes'=>$times,'betNum'=>$nums));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            //判断记录是否存在
            $sql = 'select gc.totalNums,bpr.status,gc.perPoint,gc.perCash,gc.id,gc.price,gc.name,gc.description,gc.img,gc.type '
                . ' from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\GoodsConfigs as gc on gc.id = bpr.type '
                . ' where gc.isShow = 0 and bpr.times = ' . $times . ' and bpr.type = ' . $type;
            $query = $this->modelsManager->createQuery($sql);
            $betRes = $query->execute();
            if(!$betRes->valid() || empty($betRes)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $betData = $betRes->toArray()[0];

            //判断是否已开奖
            if($betData['status'] != 0){
                return $this->status->retFromFramework($this->status->getCode('BET_POINT_HAS_OPENED'));
            }

            //用户信息
            $userRes = \Micro\Models\UserProfiles::findFirst('uid = ' . $uid);
            if(empty($userRes)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            } 

            if($kind == 1){
                $needs = $nums * $betData['perPoint'];
                $tip = 'NOT_ENOUGH_POINT';
                $has = $userRes->points;
                $updateSql = 'update pre_user_profiles set points = points - ' . $needs . ' where uid = ' . $uid;
            }else{
                $needs = $nums * $betData['perCash'];
                $tip = 'NOT_ENOUGH_CASH';
                $has = $userRes->cash;
                $updateSql = 'update pre_user_profiles set cash = cash - ' . $needs . ' where uid = ' . $uid;
            }

            if($needs > $has){
                return $this->status->retFromFramework($this->status->getCode($tip));
            }

            //判断投注数是否足够
            $betNumRes = \Micro\Models\BetPointsLog::sum(array('column'=>'nums','conditions'=>'times = ' . $times . ' and type = ' . $type));
            $betNums = $betNumRes ? $betNumRes : 0;
            $nowBetNums = $betNums + $nums;
            $totalNums = $betData['totalNums'];
            if($totalNums < $nowBetNums){
                return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_NOT_ENOUGH'));
            }

            //扣除积分或者聊币【原生】
            $connection = $this->di->get('db');
            $connection->execute($updateSql);
            //添加投注日志
            $sqlNew1 = "insert into pre_bet_points_log (uid,times,type,nums,createTime,platform,kind) values ($uid,$times,$type,$nums,".time().",$platform,$kind)";
            $connection->execute($sqlNew1);

            //判断是否开奖
            if($nowBetNums >= $totalNums){
                //更新开奖信息
                $winUid = $this->openBetPoints($type, $times);
                $userInfo = \Micro\Models\UserInfo::findFirst('uid = ' . $winUid);
                $updatasql = '';  //判断是否有手机号
                if(!empty($userInfo->telephone)){
                	$updatasql .= ',mobile="' . $userInfo->telephone . '"';
                }
                $upsql = 'update pre_bet_points_result_log set uid=' . $winUid . $updatasql . ',status=1,openTime=' . time() . ',remark="' . $type . '" where status = 0 and times = ' . $times . ' and type = ' . $type;
                $this->errLog('----upsql errorMessage = ' . $upsql);
                $connection->execute($upsql);

                //新增新的期数
                $newTimes = $times + 1;
                $sqlNew2 = "insert into pre_bet_points_result_log (uid,times,type,createTime,remark,status,openTime) values (0,$newTimes,$type,".time().",'',0,0)";
                $connection->execute($sqlNew2);

                //广播
                $this->broadOpenBet($type, $times, $winUid, $betData);
            }else{
                //广播
                if($betData['type'] > 10){
                    $showBroad = array();
                    $showBroad['controltype'] = "updateBet";
                    $data = array();
                    $roomRes = \Micro\Models\Rooms::findFirst('uid = ' . $betData['type']);
                    if($roomRes){
                        $data['id'] = $type;
                        $data['totalNums'] = $totalNums;
                        $data['hasBetNums'] = $nowBetNums;
                        $data['myBetNum'] = $this->getMyBettingNum($type, $times, $uid);
                        $data['times'] = $times;
                        $data['perPoint'] = $betData['perPoint'];
                        $data['perCash'] = $betData['perCash'];
                        $showBroad['data'] = $data;
                        $this->comm->roomBroadcast($roomRes->roomId, $showBroad);
                    }
                }
            }

            $userRes = \Micro\Models\UserProfiles::findFirst('uid = ' . $uid);
            $myPoints = $userRes->points;
            $myCash = $userRes->cash;

            return $this->status->retFromFramework(
                $this->status->getCode('OK'),
                array('myPoints'=>$myPoints,'myCash'=>$myCash)
            );

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
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
                $tmpArr = array();
                $tmpArr = array_fill($startNum, $nums, $v->uid);
                $startNum += $nums;
                $list = array_merge($list, $tmpArr);
            }

            shuffle($list);

            $randNum = rand(1,$sum);
            $winUid = $list[$randNum];

            return $winUid;
        } catch (\Exception $e) {
            $this->errLog('openBetPoints errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //全服广播
    public function broadOpenBet($type = 0, $times = 0, $winUid = 0, $betData){
        //中奖用户
        $winNickName = '';
        $userInfo = \Micro\Models\UserInfo::findFirst('uid = ' . $winUid);
        if($userInfo){
            $winNickName = $userInfo->nickName;
        }
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
        $data['configName'] = $betData['img'];
        $data['imgUrl'] = $this->url->getStatic($this->config->websiteinfo->goodspath . $betData['img'].'.jpg');
        $data['rewardName'] = $betData['name'];
        $data['rewardMoney'] = $betData['price'];
        $data['rewardDesc'] = $betData['description'] ? $betData['description'] : '';
        $ArraySubData['data'] = $data;
        $this->roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData);
        //发送消息
        $sendUser = UserFactory::getInstance($winUid);
        $sendUser->getUserInformationObject()->addUserInformation(
            $this->config->informationType->system,
            array(
                'content' => '恭喜你获得 第' . $times . '期'. $betData['price'] .'元的“一元夺宝”奖品：' . $betData['name'] . '。未绑定手机用户请绑定手机，客服人员会在未来7个工作日内联系你。',
                'link' => '',
                'operType' => ''
            )
        );
    }

    //获取往期结果
    public function getBetResults($id = 0, $page = 1, $pageSize = 10){
        try {
            !$id && $id = 1;
            !$page && $page = 1;
            !$pageSize && $pageSize = 10;
            $limit = ($page - 1) * $pageSize;
            $sql = 'select bpr.times,bpr.openTime,ui.nickName,ui.avatar,bpr.uid '
                . ' from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bpr.uid '
                . ' where bpr.status = 1 and bpr.type = ' . $id . ' order by bpr.times desc limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['times'] = $v->times;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $tmp['uid'] = $v->uid;
                    $tmp['openTime'] = $v->openTime ? date('Y-m-d H:i:s', $v->openTime) : '';
                    $tmp['winnerBetNums'] = $this->getWinnerBetNums($id, $v->times, $v->uid);
                    array_push($data, $tmp);
                }
            }

            // $count = \Micro\Models\BetPointsResultLog::count('type = ' . $id . ' and status = 1');
// array('list'=>$data,'count'=>$count)
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取直播间一元夺宝列表
    public function getRoomsBetList($anchorUid = 0){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            $sql = 'select bpr.times,(gc.totalNums - ifnull(sum(bp.nums), 0)) as leftNums,gc.name,ifnull(gc.description, "") as description,gc.id,gc.perPoint,gc.perCash,gc.price,gc.type,gc.totalNums,gc.img,bpr.times'
                . ' from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\BetPointsLog as bp on bp.type = bpr.type and bpr.times = bp.times '
                . ' left join \Micro\Models\GoodsConfigs as gc on gc.id = bpr.type '
                . ' where bpr.status = 0 and gc.isShow = 0 and gc.type = ' . $anchorUid . ' group by bpr.type order by gc.price asc';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp = $v->toArray();
                    $tmp['myBetNum'] = $this->getMyBettingNum($v->id, $v->times, $uid);
                    array_push($data, $tmp);
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取用户的夺宝次数总计
    private function getMyBettingNum($id = 0, $times = 0, $uid = 0){
        try {
            $betNumRes = \Micro\Models\BetPointsLog::sum(
                array('column'=>'nums','conditions'=>'times = ' . $times . ' and type = ' . $id . ' and uid = ' . $uid)
            );

            $betNums = $betNumRes ? $betNumRes : 0;
            return $betNums;
        } catch (\Exception $e) {
            $this->errLog('getMyBettingNum errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //检查直播间是否有夺宝商品
    public function checkRoomBet($uid = 0){
        try {
            $res = \Micro\Models\GoodsConfigs::findFirst('isShow = 0 and type = ' . $uid);
            $hasRoomBet = 0;
            if(!empty($res)){
                $hasRoomBet = 1;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('hasRoomBet'=>$hasRoomBet));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    //
    /*public function addWine($price = 100){
        try {
            for($i = 0; $i < 100; $i++){
                $new = new \Micro\Models\GoodsConfigs();
                $new->name = $price . '元酒水券';
                $new->price = $price;
                $new->description = $price . '元酒水券';
                $new->totalNums = $price;
                $new->totalNums = $price;
                $new->perPoint = 500;
                $new->perCash = 100;
                $new->type = 0;
                $new->isShow = 0;
                $new->createTime = time();
                $new->img = 'wine'.$price;
                $new->orderType = 0;
                $new->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //
    public function allocateWine($id = 0, $uid = 0){
        $isValid = $this->validator->validate(array('id'=>$id,'uid'=>$uid));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $res = \Micro\Models\GoodsConfigs::findFirst('isShow = 0 and id = ' . $id);
            if(empty($res)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $price = $res->price;
            $countRes = \Micro\Models\GoodsConfigs::count('isShow = 0 and price = ' . $price . ' and type = ' . $uid);

            if($countRes > 0){
                return $this->status->retFromFramework($this->status->getCode('EVERY_ANCHOR_HAS_ONE'));
            }

            $res->type = $uid;
            $res->save();
            $connection = $this->di->get('db');
            $newSql = "insert into pre_bet_points_result_log (uid,times,type,createTime,remark,status,openTime) values (0,1,$id,".time().",'',0,0)";
            $connection->execute($newSql);

            return $this->status->retFromFramework($this->status->getCode('OK'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }*/

}