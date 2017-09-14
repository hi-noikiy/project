<?php
/**
 * Desc:骰子游戏--管理类
 * Date: 2016/2/18
 */

namespace Micro\Frameworks\Logic\DiceGame;

use Micro\Frameworks\Logic\User\UserFactory;
use Phalcon\DI\FactoryDefault;

use Micro\Models\DiceDetail;
use Micro\Models\DiceLog;
use Micro\Models\UserProfiles;


class DiceGameMgr extends DiceGameBase
{
    protected $diceGameConfig=null;
    protected $diceGameData=null;


    public function __construct(){
        parent::__construct();
        $this->diceGameData= $this->getDiceGameDataObject($this->diceGameData);
        $this->diceGameConfig= $this->getDiceConfig($this->diceGameConfig);
    }



    /**
     * @desc 获得房间游戏情况
     * @param int $roomId
     * @return array
     */
    public function getDiceGameInfo($roomId=0){
        //参数验证
        $validator=$this->validateParam(array('roomId'=>$roomId));
        if($validator['code']!=$this->status->getCode('OK')){
            return $validator;
        }

        $return=array();

        //查询当前房间的游戏状态
        $info = $this->diceGameData->getCurrentGameInfo($roomId);

        if (!$info) {//没有游戏数据
            $initReturn = $this->initData(1);
            return $this->status->retFromFramework($this->status->getCode('OK'), $initReturn);
        }

        //游戏详细信息
        $infoDetail = $this->diceGameData->getGameDetailInfo($roomId, $info->round);

        if (!$infoDetail) {//没有数据
            $initReturn = $this->initData(1);
            return $this->status->retFromFramework($this->status->getCode('OK'), $initReturn);
        }

        //庄家信息
        $declarerInfo = $infoDetail->declarer ? $this->diceGameData->getDeclarerInfo($infoDetail->declarer) : (object)null;
        $declarerInfo != (object)null && $declarerInfo['cash'] = $infoDetail->cash;


        $stakeList = array();//每个押注区域的押注数量统计
        $stakeInfo = array();//自己的押注情况
        $cashSum=0;//本轮总投注聊币

        // if ($infoDetail->status == $this->diceGameConfig->dice_game_status_01)//游戏进行中
        // {
        //查询本轮游戏的押注情况
        $listres=$this->diceGameData->getStakeList($infoDetail->id);
        $stakeList=$listres['stakeList'];
        $cashSum=$listres['cashSum'];

        //如果用户登录，查询用户的押注情况
        $user = $this->userAuth->getUser();
        if($user){
            $uid = $user->getUid();
            $stakeInfo=$this->diceGameData->getStakeInfo($infoDetail->id,$uid);
        }
        // }


        //返回数据
        $return['declarerInfo']=$declarerInfo;//庄家信息
        $return['stakeInfo']=$stakeInfo;//自己投注情况
        $return['stakeList']=$stakeList;//每个押注区域的押注数量统计
        $return['cashSum']=$cashSum;//本轮总投注聊币
        $return['status']=$infoDetail->status;//游戏状态

        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }

    private function initData($type = 1){
        $return = array();
        switch ($type) {
            case '1':
                $initlist = array();
                for($i = 1; $i <= $this->diceGameConfig->dice_game_type_num; $i++){
                    $data['type'] = $i;
                    $data['sumCash'] = 0;
                    $initlist[] = $data;
                    unset($data);
                }
                $return['stakeList'] = $initlist;
                $return['declarerInfo'] = (object)null;
                $return['stakeInfo'] = array();
                $return['cashSum'] = 0;
                $return['status'] = 0;
                break;

            case '2':
                $return['list'] = array();
                $return['declarerInfo'] = (object)null;
                break;

            default:
                # code...
                break;
        }
        return $return;
    }


    /**
     * @desc 获取上一轮游戏的信息
     * @param int $roomId
     * @return array
     */
    public function getLastDiceGameInfo($roomId=0){
        //参数验证
        $validator=$this->validateParam(array('roomId'=>$roomId));
        if($validator['code']!=$this->status->getCode('OK')){
            return $validator;
        }

        $return=array();

        try {
            //查询最近一轮已结束的游戏信息
            $infoDetail = DiceDetail::findfirst('roomId='.$roomId.' and status='.$this->diceGameConfig->dice_game_status_02.' order by id desc');
            if(!$infoDetail){//数据不存在
                $initReturn = $this->initData(2);
                return $this->status->retFromFramework($this->status->getCode('OK'), $initReturn);
            }

            //查询最近一轮投注结果
            $diceResult=$this->diceGameData->getGameResultInfo($infoDetail->id);

            return $this->status->retFromFramework($this->status->getCode('OK'),$diceResult);

        }catch (\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    /**
     * @desc 玩家上庄
     * @param int $roomId 房间号
     * @param int $cash 庄家携带的聊币
     * @return mixed
     */
    public function toBeDeclarer($roomId=0,$cash=0){
        $user = $this->userAuth->getUser();
        //参数验证
        $validator=$this->validateParam(array('roomId'=>$roomId,'declarerCash'=>$cash,'user'=>$user));
        if($validator['code']!=$this->status->getCode('OK')){
            return $validator;
        }
        $uid = $user->getUid();

        try{
            //查询游戏状态
            $info=$this->diceGameData->getCurrentGameInfo($roomId);

            if($info){//有数据
                $round=$info->round;
                //查询当前游戏详细信息
                $infoDetail=$this->diceGameData->getGameDetailInfo($roomId,$round);
                if($infoDetail->declarer) {
                    if($infoDetail->status > 0){//庄上有人
                        return $this->status->retFromFramework($this->status->getCode('HAS_DECLARE_GAME'));
                    }
                    if(($infoDetail->declareTime + 5) < time()) {//抢庄已结束
                        return $this->status->retFromFramework($this->status->getCode('GRAB_DECLARER_HAS_END'));
                    }
                    if(($infoDetail->cash + 100) > $cash) {//抢庄聊币不足
                        return $this->status->retFromFramework($this->status->getCode('GRAB_CASH_NOT_ENOUGH'));
                    }
                }
                    
            }else{//没有游戏数据
                $roomInfo=\Micro\Models\Rooms::findfirst($roomId);
                if(!$roomInfo){
                    return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));//数据不存在
                }
            }

            //判断聊币是否够
            $userprofiles=UserProfiles::findfirst($uid);
            if(!$userprofiles||$userprofiles->cash<$cash){
                return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));//聊币不足
            }



            //更新庄家信息
            if($info){//有数据
                $declareRes=$this->diceGameData->editDeclarer($infoDetail->id,$uid,$cash);
            }else{//没有游戏数据
                $declareRes=$this->diceGameData->addNewGameInfo($roomId,$uid,$cash);//添加游戏数据
            }
            if($declareRes['code']!=$this->status->getCode('OK')){
                return $declareRes;
            }

            //扣除用户聊币
            $cashRes=$this->diceGameData->deductCash($uid,$cash);
            if($cashRes['code']!=$this->status->getCode('OK')){
                //聊币扣除失败，则取消庄家上庄
                $this->diceGameData->editDeclarer($infoDetail->id);
                return $cashRes;
            }

            //广播上庄信息
            // $broadData['cash'] = $cash;//携带聊币
            $tmp = $this->diceGameData->getDeclarerInfo($uid);
            $tmp['cash'] = $cash;
            if(isset($declareRes['data']['lastDeclarerUid']) && $declareRes['data']['lastDeclarerUid']){
                $lastInfo = $this->diceGameData->getDeclarerInfo($declareRes['data']['lastDeclarerUid']);
                $tmp['lastDeclarerUid'] = $declareRes['data']['lastDeclarerUid'];
                $tmp['lastCash'] = $declareRes['data']['lastCash'];
                $tmp['lastNickName'] = $lastInfo['nickName'];
            }
                
            $broadData['userdata'] = $tmp;//$this->diceGameData->getDeclarerInfo($uid);//庄家信息
            $this->broadcastPackaging('diceGameDeclarer',$roomId,$broadData);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }catch (\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }


    /**
     * @desc 庄家下庄
     * @param int $roomId 房间号
     * @return array
     */
    public function declarerLeave($roomId=0){
        $user = $this->userAuth->getUser();
        //参数验证
        $validator=$this->validateParam(array('roomId'=>$roomId,'user'=>$user));
        if($validator['code']!=$this->status->getCode('OK')) {
            return $validator;
        }
        $uid = $user->getUid();

        try{
            //查询游戏状态
            $info=$this->diceGameData->getCurrentGameInfo($roomId);
            if(!$info){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));//数据不存在
            }

            //查询当前游戏详细信息
            $infoDetail=$this->diceGameData->getGameDetailInfo($roomId,$info->round);
            if(!$infoDetail||$infoDetail->status!=$this->diceGameConfig->dice_game_status_00) {//游戏不是未开始状态
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));//没有操作权限
            }
            if($infoDetail->declarer!=$uid) {//庄家不是本人
                return $this->status->retFromFramework($this->status->getCode('OPER_NOT_AFFACT'));//操作无效
            }

            //更新庄家信息
            $declareRes=$this->diceGameData->editDeclarer($infoDetail->id);
            if($declareRes['code']!=$this->status->getCode('OK')){
                return $declareRes;
            }

            //退还聊币
            $this->diceGameData->addCash($uid,$infoDetail->cash);

            //广播庄家下庄信息
            $this->broadcastPackaging('diceGameDeclarerLeave',$roomId);


            return $this->status->retFromFramework($this->status->getCode('OK'));
        }catch (\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    /**
     * @desc 庄家开启押注
     * @param int $roomId
     * @return array
     */
    public function startDice($roomId=0){
        $user = $this->userAuth->getUser();
        //参数验证
        $validator=$this->validateParam(array('roomId'=>$roomId,'user'=>$user));
        if($validator['code']!=$this->status->getCode('OK')) {
            return $validator;
        }
        $uid = $user->getUid();

        try{
            //查询游戏状态
            $info=$this->diceGameData->getCurrentGameInfo($roomId);
            if(!$info){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));//数据不存在
            }

            //查询当前游戏详细信息
            $infoDetail=$this->diceGameData->getGameDetailInfo($roomId,$info->round);
            if($infoDetail->declarer!=$uid) {//庄家不是本人
                return $this->status->retFromFramework($this->status->getCode('OPER_NOT_AFFACT'));//操作无效
            }
            if($infoDetail->status!=$this->diceGameConfig->dice_game_status_00) {//游戏不是未开始状态
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));//没有操作权限
            }

            //修改游戏状态
            $infoDetail->status=$this->diceGameConfig->dice_game_status_01;
            $infoDetail->startTime=time();
            $infoDetail->times = $infoDetail->times + 1;
            $infoDetail->save();

            //广播庄家开庄
            $this->broadcastPackaging('diceGameStart',$roomId,array('times'=>$infoDetail->times));

            return $this->status->retFromFramework($this->status->getCode('OK'));

        }catch (\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    /**
     * @desc 玩家押注
     * @param int $roomId 房间号
     * @param int $type 押注类型
     * @param int $cash 押注聊币
     * @return array
     */
    public function playerStake($roomId=0,$type=0,$cash=0){
        $user = $this->userAuth->getUser();
        //参数验证
        $validator=$this->validateParam(array('roomId'=>$roomId,'user'=>$user,'stakeCash'=>$cash,'stakeType'=>$type));
        if($validator['code']!=$this->status->getCode("OK")) {
            return $validator;
        }
        $uid = $user->getUid();

        try{
            //查询游戏状态
            $info=$this->diceGameData->getCurrentGameInfo($roomId);
            if(!$info){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));//数据不存在
            }

            //查询当前游戏详细信息
            $infoDetail=$this->diceGameData->getGameDetailInfo($roomId,$info->round);

            if($infoDetail->status!=$this->diceGameConfig->dice_game_status_01) {//游戏不是进行状态
                return $this->status->retFromFramework($this->status->getCode('DICE_GAME_HAS_END'));//本次押注已经结束
            }

            //庄家不能押注
            if($infoDetail->declarer==$uid) {
                return $this->status->retFromFramework($this->status->getCode('OPER_NOT_AFFACT'));//操作无效
            }

            //判断聊币是否够
            $userprofiles=UserProfiles::findfirst($uid);
            if($userprofiles->cash<$cash){
                return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));//聊币不足
            }

            //判断单局下注不得超过限额
            /*$sumRes = \Micro\Models\DiceLog::Sum(array("column" => "cash", "conditions" => "gameId = " . $infoDetail->id . " and uid = " . $uid));
            $hasStakeNum = $sumRes ? $sumRes : 0;
            if($hasStakeNum + $cash > $this->diceGameConfig->dice_one_game_max_stake_limit){
                return $this->status->retFromFramework($this->status->getCode('ONE_GAME_STAKE_LIMIT'));//单局下注不得超过10000聊币
            }*/


            /**
             * 检测庄家聊币是否足够支付输赢
             */

            $listres=$this->diceGameData->getStakeList($infoDetail->id);//查询本轮已投注情况
            $stakeList=$listres['stakeList'];

            foreach($stakeList as $item){
                $stakeArr[$item['type']]=$item['sumCash'];
            }

            // $declarerCash=$infoDetail->cash;//庄家聊币
            //计算庄家可使用最大聊币
            $declarerCash = $this->getMaxUsefulCash($infoDetail->cash);
            //能押注的最大值
            $maxStakeLimit=$this->diceGameData->getMaxStakeCashLimit($type,$declarerCash,$stakeArr);
            if($maxStakeLimit<$cash){
                return $this->status->retFromFramework($this->status->getCode('DECLARER_CASH_NOT_ENOUGH'));//庄家聊币不足，无法下注
            }


            //扣除用户聊币
            $cashRes=$this->diceGameData->deductCash($uid,$cash);
            if($cashRes['code']!=$this->status->getCode('OK')){
                return $cashRes;
            }

            //写入用户押注记录
            $now=time();
            $updatesql="INSERT INTO pre_dice_log(gameId,uid,type,createTime,cash) VALUES ({$infoDetail->id},{$uid},{$type},{$now},{$cash})
                       ON DUPLICATE KEY UPDATE cash=cash+{$cash}";
            $this->db->execute($updatesql);

            $nickName=$user->getUserInfoObject()->getNickName();//用户昵称
            //广播押注信息
            $broadData=array('uid'=>$uid,'type'=>$type,'cash'=>$cash,'nickName'=>$nickName);
            $this->broadcastPackaging('diceGameStake',$roomId,$broadData);

            $return['stakeCash']=$cash;
            $return['type']=$type;
            return $this->status->retFromFramework($this->status->getCode('OK'),$return);
        }catch (\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    /**
     * 获取庄家可使用最大聊币数
     * @param $cash 庄家携带聊币
     */
    private function getMaxUsefulCash($cash = 0){
        $maxCash = 0;
        if($cash >= 100000){//大于等于100000返回50000
            $maxCash = 50000;
        }else if($cash >= 20000){//大于等于20000小于100000返回一半
            $maxCash = floor($cash / 2);
        }else if($cash > 10000){//大于10000小于20000返回10000
            $maxCash = 10000;
        }else{//小于等于10000返回所有
            $maxCash = $cash;
        }

        return $maxCash;
    }


    /**
     * @desc 庄家开盅
     * @param int $roomId
     * @return array
     */
    public function declarerOpenDice($roomId=0){
        $user = $this->userAuth->getUser();
        //参数验证
        $validator=$this->validateParam(array("roomId"=>$roomId,'user'=>$user));
        if($validator['code']!=$this->status->getCode("OK")) {
            return $validator;
        }
        $uid = $user->getUid();

        try{
            //查询游戏状态
            $info=$this->diceGameData->getCurrentGameInfo($roomId);
            if(!$info){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));//数据不存在
            }

            //查询当前游戏详细信息
            $infoDetail=$this->diceGameData->getGameDetailInfo($roomId,$info->round);

            if($infoDetail->status!=$this->diceGameConfig->dice_game_status_01) {//游戏不是进行状态
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));//没有操作权限
            }

            if($infoDetail->declarer!=$uid) {//不是庄家本人不能开盅
                return $this->status->retFromFramework($this->status->getCode('OPER_NOT_AFFACT'));//操作无效
            }

            //查询押注情况
            $dicelist=DiceLog::find("gameId=".$infoDetail->id);
            // if(!$dicelist){//没有人押注
            //    return $this->status->retFromFramework($this->status->getCode('EMPTY_DATA'));
            // }

            //处理押注
            $this->toDealStake($infoDetail,$dicelist);


            return $this->status->retFromFramework($this->status->getCode('OK'));
        }catch (\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    /**
     * 计划任务，定时处理不开庄、不开盅的游戏
     */
    public function autoDeal(){
        $timelimit=time()-60;
        try{
            //查询长时间未开庄的庄家
            $list=DiceDetail::find("status={$this->diceGameConfig->dice_game_status_00} and declarer>0 and declareTime<".$timelimit);
            //使庄家下庄
            foreach($list as $val){
                //更新庄家信息
                $declareRes=$this->diceGameData->editDeclarer($val->id);
                if($declareRes['code']!=$this->status->getCode('OK')){
                    continue;
                }

                //退还聊币
                $this->diceGameData->addCash($val->declarer,$val->cash);

                //广播庄家下庄信息
                $this->broadcastPackaging('diceGameDeclarerLeave',$val->roomId);
            }

            //查询长时间未开盅的庄家
            $list=DiceDetail::find("status={$this->diceGameConfig->dice_game_status_01} and startTime<".$timelimit);
            //结算
            foreach($list as $infoDetail){
                //处理押注
                $dicelist=DiceLog::find("gameId=".$infoDetail->id);
                //if(!$dicelist) {//没有人押注
                //    continue;
                // }
                $this->toDealStake($infoDetail,$dicelist,0);
            }


        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
        }
        return;

    }


    /**
     * @desc 结算
     * @param null $infoDetail 游戏详细信息
     * @param array $dicelist 用户下庄列表
     * @param int $isDeclarer 是否庄家
     */
    private function toDealStake($infoDetail=nuLl,$dicelist=array(),$isDeclarer=1){
        $roomId=$infoDetail->roomId;
        //生成骰子结果
        $randres=$this->diceGameData->setRandPoints();

        //处理输赢
        $dealres=$this->diceGameData->stakeResultDeal($infoDetail,$randres,$dicelist,$isDeclarer);

        //查询最近一轮投注结果
        $diceResult=$this->diceGameData->getGameResultInfo($infoDetail->id);


        //广播押注结果
        $broadData['points'] = $randres['points'];//骰子点数
        $broadData['type'] = $randres['type'];//获胜的押注区域
        $broadData['cashLimit'] = $this->diceGameConfig->dice_game_declare_continue_cash_limit;//庄家继续坐庄聊币限制
        $broadData['declarer'] = isset($dealres['declarer'])?$dealres['declarer']:0;//庄家uid
        $broadData['cashEnough']=isset($dealres['cashEnough'])?$dealres['cashEnough']:1;//聊币是否足够
        $broadData['remainCash'] = $diceResult['declarerInfo']['stakeCash']+$diceResult['declarerInfo']['cash'];//庄家剩余聊币
        $broadData['stakeResList'] = $diceResult;
        $this->broadcastPackaging('diceGameStakeResult',$roomId,$broadData);


        //世界喇叭:一次获得1000聊币以上的用户，会有系统广播。
        if(isset($dealres['broadcast'])&&$dealres['broadcast']){
            $roomModule = $this->di->get('roomModule');
            $anchor = UserFactory::getInstance($dealres['anchorUid']);
            $declarerData = $anchor->getUserInfoObject()->getUserInfo();
            foreach ($dealres['broadcast'] as $k => $bro) {
                $playerUser = UserFactory::getInstance($bro['uid']);
                $playerData = $playerUser->getUserInfoObject()->getUserInfo();
                $extends['cash']=$bro['resultCash'];
                $roomModule->getRoomOperObject()->sendWorldBroadcast($this->config->worldBroadcastType->diceGameGetCash, $roomId, $declarerData, $playerData,$extends);
            }
        }
        return;
    }
}