<?php
/**
 * Desc: 骰子游戏-数据操作类
 * Date: 2016/2/22
 */

namespace Micro\Frameworks\Logic\DiceGame;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;

use Micro\Models\Dice;
use Micro\Models\DiceDetail;
use Micro\Models\Rooms;





class DiceGameData extends DiceGameBase
{

    protected $diceGameConfig=null;
    public function __construct(){
        parent::__construct();
        $this->diceGameConfig= $this->getDiceConfig();
    }

    /**
     * @desc:查询当前房间游戏状态
     * @param int $roomId
     * @return array
     */
    public function getCurrentGameInfo($roomId = 0){
        $info = array();
        try {
            //查询该房间的游戏状态
            $info = Dice::findfirst('roomId=' . $roomId);
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
        }
        return $info;
    }


    /**
     * @desc 查询某轮游戏详细信息
     * @param $roomId 房间号
     * @param $round 场次
     * @return array
     */
    public function getGameDetailInfo($roomId = 0, $round = 0){
        $info = array();
        try {
            $info = DiceDetail::findfirst('roomId=' . $roomId . " and round=" . $round);
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
        }
        return $info;
    }


    /**
     * @desc 查询押注情况
     * @param int $gameId
     * @return mixed
     */
    public function getStakeList($gameId=0){
        $initlist=array();
        for($i=1;$i<=$this->diceGameConfig->dice_game_type_num;$i++){
            $data['type']=$i;
            $initlist[]=$data;
            unset($data);
        }

        $stakeList=array();
        $cashSum = 0;//本轮总投注聊币
        $userTypeList=array();//用户押注列表
        try {
            $sql = "SELECT type,sum(cash) as cashSum FROM Micro\Models\DiceLog WHERE gameId = :gameId: GROUP BY type";
            $query = $this->modelsManager->createQuery($sql);
            $list = $query->execute(array(
                'gameId' => $gameId
            ));
            foreach ($list as $val) {
                $cashSum += $val->cashSum;
                $userTypeList[$val->type]['cashSum']=$val->cashSum;
            }

        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
        }

        foreach($initlist as $key=> $val){
            $data['type']=$val['type'];//投注区域
            $data['sumCash']=isset($userTypeList[$val['type']]['cashSum'])?$userTypeList[$val['type']]['cashSum']:0;//投注聊币
            $stakeList[]=$data;
            unset($data);
        }
        $return['stakeList'] = $stakeList;
        $return['cashSum'] = $cashSum;

        return $return;
    }

    /**
     * @desc 查询用户押注情况
     * @param int $gameId
     * @param int $uid
     * @return mixed
     */
    public function getStakeInfo($gameId = 0, $uid = 0){
        $stakeInfo = array();
        try {
            $sql = "SELECT type,cash FROM Micro\Models\DiceLog WHERE gameId = :gameId: AND uid=:uid:";
            $query = $this->modelsManager->createQuery($sql);
            $list = $query->execute(array(
                'gameId' => $gameId,
                'uid' => $uid
            ));
            foreach ($list as $val) {
                $data['type'] = $val->type;//投注区域
                $data['cashSum'] = $val->cash;//投注聊币
                $stakeInfo[] = $data;
                unset($data);
            }
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
        }

        return $stakeInfo;
    }


    /**
     * @desc:查询游戏押注结果
     * @param int $gameId 游戏id
     * @return array
     */
    public function getGameResultInfo($gameId=0){
        $return=array();
        $result=array();//玩家输赢
        $declarerInfo=array();//庄家信息
        try{
            //查询该房间的游戏状态
            $sql = "SELECT dr.uid,ui.nickName,dr.stakeCash,dr.resultCash,dr.isDeclarer,dr.fax
                FROM Micro\Models\DiceResult dr INNER JOIN Micro\Models\UserInfo ui ON dr.uid=ui.uid
                WHERE gameId = :gameId: ORDER BY dr.resultCash DESC,dr.stakeCash DESC";
            $query = $this->modelsManager->createQuery($sql);
            $list = $query->execute(array(
                'gameId' => $gameId
            ));

            $outCash = 0;
            $inCash = 0;
            $declarerCash = 0;
            foreach($list as $val){
                $data['nickName']=$val->nickName;
                $data['stakeCash']=$val->stakeCash;
                $data['cash']=$val->resultCash;
                $data['uid']=$val->uid;
                if($val->isDeclarer){//庄家
                    $declarerInfo = $data;
                    $declarerCash = $val->resultCash;
                }else{
                    $result[]=$data;
                    $val->resultCash > 0 && $outCash += ($val->resultCash + $val->fax - $val->stakeCash);
                }
                unset($data);
            }
            $inCash = $declarerCash + $outCash;
            if($declarerInfo){
                $declarerInfo['inCash'] = $inCash;
                $declarerInfo['outCash'] = $outCash;
            }
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
        }
        $return['list']=$result;
        $return['declarerInfo']=$declarerInfo;
        return $return;
    }


    /**
     * @desc 添加游戏信息
     * @param int $roomId
     * @param int $declarer
     * @param int $cash
     */
    public function addNewGameInfo($roomId=0,$declarer=0,$cash=0){
        try{
            $infosql="insert into pre_dice(roomId,round)values({$roomId},1)";
            $this->db->execute($infosql);
            $infores = $this->db->affectedRows(); //判断更新是否成功
            if (!$infores) {//添加失败
                return $this->status->retFromFramework($this->status->getCode('HAS_DECLARE_GAME'));
            }

            $now=time();
            $detailinfosql="insert into pre_dice_detail(roomId,round,createTime,declarer,declareTime,cash)values({$roomId},1,{$now},{$declarer},{$now},{$cash})";
            $this->db->execute($detailinfosql);
            $detailinfores=$this->db->affectedRows();//判断更新是否成功
            if (!$detailinfores) {//添加失败
                return $this->status->retFromFramework($this->status->getCode('HAS_DECLARE_GAME'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'),$e->getMessage());
        }

    }


    /**
     * @desc 修改庄家
     * @param int $gameId
     * @param int $declarer
     * @param int $cash
     * @return mixed
     */
    public function editDeclarer($gameId=0,$declarer=0,$cash=0){
        if($declarer){//上庄
            $now=time();
            // and declarer=0
            $updatesql="update pre_dice_detail set declarer={$declarer},declareTime={$now},cash={$cash} where id={$gameId} and status={$this->diceGameConfig->dice_game_status_00}";
        }else{//下庄
            $updatesql="update pre_dice_detail set declarer=0,declareTime=0,cash=0,times=0 where id={$gameId} and status={$this->diceGameConfig->dice_game_status_00}";
        }

        try{
            $lastDeclarerUid = 0;
            $lastCash = 0;
            $lastDeclarer = \Micro\Models\DiceDetail::findfirst('id = ' . $gameId);
            if($lastDeclarer && $lastDeclarer->declarer){
                $updateCashSql = 'update pre_user_profiles set cash=cash+' . $lastDeclarer->cash . ' where uid=' . $lastDeclarer->declarer;
                $this->db->execute($updateCashSql);
                $updateCashRes = $this->db->affectedRows();
                $lastDeclarerUid = $lastDeclarer->declarer;
                $lastCash = $lastDeclarer->cash;
            }
            $this->db->execute($updatesql);
            $updateres = $this->db->affectedRows(); //判断更新是否成功
            if (!$updateres) {//更新失败
                return $this->status->retFromFramework($this->status->getCode('STATUS_HAS_CHANGED'));//游戏状态改变了：已有其他人上庄 或者 游戏已开始
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('lastDeclarerUid'=>$lastDeclarerUid,'lastCash'=>$lastCash));
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'),$e->getMessage());
        }
    }

    /**
     * @desc:获得庄家的信息：头像、昵称、富豪等级
     * @param int $declarer 庄家uid
     * @return array
     */
    public function getDeclarerInfo($declarer=0)
    {
        $declarerInfo=array();
        try{
            $declarerObject = UserFactory::getInstance($declarer);
            $userinfoResult = $declarerObject->getUserInfoObject()->getUserInfo();
            $userprofilesResult = $declarerObject->getUserInfoObject()->getUserProfiles();
            $declarerInfo['nickName']=$userinfoResult['nickName'];//昵称
            $declarerInfo['uid']=$declarer;//用户id
            $declarerInfo['avatar']=$userinfoResult['avatar'];//用户头像
            $declarerInfo['richerLevelName']=$this->normalLib->getRicherConfigs($userprofilesResult['richerLevel']);//用户富豪等级名称
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
        }
        return $declarerInfo;
    }


    /**
     * @desc 扣除用户聊币
     * @param int $uid
     * @param int $cash
     * @return mixed
     */
    public function deductCash($uid=0,$cash=0){
        try{
            //扣除用户聊币
            $updateCashSql = 'update pre_user_profiles set cash=cash-' . $cash . ' where uid=' . $uid . ' and cash>='.$cash;
            $this->db->execute($updateCashSql);
            $updateCashRes = $this->db->affectedRows(); //判断更新是否成功
            if (!$updateCashRes) {//更新失败
                return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));//聊币不足
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'),$e->getMessage());
        }
    }

    /**
     * @desc 给用户加聊币
     * @param int $uid
     * @param int $cash
     */
    public function addCash($uid=0,$cash=0){
        try{
            $updateCashSql = 'update pre_user_profiles set cash=cash+' . $cash . ' where uid=' . $uid;
            $this->db->execute($updateCashSql);
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
        }
        return;
    }


    /**
     * @desc 生成骰子点数
     * @return array
     */
    public function setRandPoints(){
        $return = array();
        $num1 = mt_rand(1, 6);//骰子1
        $num2 = mt_rand(1, 6);//骰子2
        $num3 = mt_rand(1, 6);//骰子3

        //全围
        if ($num1 == $num2 && $num2 == $num3) {//三个骰子大小一样
            $type = $this->diceGameConfig->dice_game_type_02;
        }else{//猜大小
            if ($num1 + $num2 + $num3 <= 17 && $num1 + $num2 + $num3 >= 11) {//结果点数之和为11~17
                $type= $this->diceGameConfig->dice_game_type_01;//大
            }else{
                $type = $this->diceGameConfig->dice_game_type_03;//小
            }
        }

        $points=array($num1,$num2,$num3);//3个骰子点数
        $return['type']=$type;
        $return['points']=$points;
        $return['result']=$num1.$num2.$num3;
        return $return;
    }


    /**
     * @desc 结算押注
     * @param $gameInfo 游戏信息
     * @param array $pointsRes 骰子点数结果
     * @param array $dicelist 用户投注记录
     * @param int $isDeclarer 是否庄家操作
     * @return mixed
     */
    public function stakeResultDeal($gameInfo=NULL,$pointsRes=array(),$dicelist=array(),$isDeclarer=1){
        $gameId=$gameInfo->id;
        $declarer=$gameInfo->declarer;
        $roomId=$gameInfo->roomId;
        try{
            //修改结算状态
            $updatesql='UPDATE pre_dice_detail SET status='.$this->diceGameConfig->dice_game_status_02.',result='.$pointsRes['result'].',resultTime='.time().' WHERE id='.$gameId;
            $this->db->execute($updatesql);

            //更新游戏场次
            $udpatedicesql='UPDATE pre_dice SET round=round+1 WHERE roomId='.$roomId;
            $this->db->execute($udpatedicesql);

            //添加新一轮游戏信息
            $newRound=$gameInfo->round+1;
            $now=time();
            $insertdicesql="INSERT INTO pre_dice_detail(roomId,round,createTime)VALUES ({$roomId},{$newRound},{$now})";
            $this->db->execute($insertdicesql);
            $newGameId=$this->db->lastInsertId();

            //处理用户输赢
            $sqlArr=array();
            $declarerFaxCash=0;//庄家最终税收总和
            $declarerResultCash=0;//庄家输赢聊币总和

            $list=$dicelist->toArray();
            foreach($list as $val){
                $multiple=0;//聊币赢的倍率
                //判断是否押中大小或者全围
                if($val['type']<4){
                    if($val['type']==$pointsRes['type']){//押中
                        if($val['type']==$this->diceGameConfig->dice_game_type_02){//全围
                            $multiple=$this->diceGameConfig->dice_game_stake_all_same;
                        }else{
                            $multiple=1;
                        }
                    }

                }else{
                    //判断是否押中单押
                    if($this->diceGameConfig->points_list[$val['type']]==$pointsRes['points'][0]){
                        $multiple+=1;
                    }
                    if($this->diceGameConfig->points_list[$val['type']]==$pointsRes['points'][1]){
                        $multiple+=1;
                    }
                    if($this->diceGameConfig->points_list[$val['type']]==$pointsRes['points'][2]){
                        $multiple+=1;
                    }
                }

                if($multiple<=0){
                    $multiple=-1;//输
                }
                $finalcash=$val['cash']*$multiple;//每个投注最终聊币输赢情况


                //处理税收:赢得的聊币超过100聊币，则需要收取3%的税收
                $fax=0;//税收
                $resultCash=0;//玩家最终聊币
                $abs=abs($finalcash);//取绝对值
                $faxcash=0;//税收的聊币值
                if($abs>=$this->diceGameConfig->dice_game_fax_limit){//如果获得的聊币达到税收下限
                    $num=floor($abs/$this->diceGameConfig->dice_game_fax_limit);//税收的份数
                    $faxcash=$num*$this->diceGameConfig->dice_game_fax_limit*$this->diceGameConfig->dice_game_fax_percent;//税收的聊币值
                }
                if($finalcash>0){//如果是用户赢
                    $fax=$faxcash;//玩家税收
                    $resultCash=$finalcash+$val['cash'];//-$fax;//用户最终聊币数：赢的聊币数+本钱-税收
                    $declarerGetCash=0-$finalcash;//庄家输的聊币=用户赢的聊币
                }else{//如果是庄家赢
                    $declarerGetCash=$val['cash'];//-$faxcash;//庄家赢的钱：用户输的聊币 - 税收
                    //$declarerFaxCash+=$faxcash;//庄家最终扣除的税收值
                }

                //更新投注日志表
                $updatelogsql='UPDATE pre_dice_log SET result='.$resultCash.',resultTime='.time().',fax='.$fax.' WHERE id='.$val['id'];
                $this->db->execute($updatelogsql);

                $declarerResultCash+=$declarerGetCash;//庄家输赢聊币总和


                if(isset($sqlArr[$val['uid']])){
                    $sqlArr[$val['uid']]['uid']=$val['uid'];
                    $sqlArr[$val['uid']]['stakeCash']+=$val['cash'];
                    $sqlArr[$val['uid']]['fax']+=$fax;
                    $sqlArr[$val['uid']]['resultCash']+=$resultCash;//最终赢取的聊币，用于客户端展示
                    $sqlArr[$val['uid']]['finalCash']+=$resultCash;//用户最终聊币数,添加到用户聊币
                }else{
                    $sqlArr[$val['uid']]['uid']=$val['uid'];
                    $sqlArr[$val['uid']]['stakeCash']=$val['cash'];
                    $sqlArr[$val['uid']]['fax']=$fax;
                    $sqlArr[$val['uid']]['resultCash']=$resultCash;//最终赢取的聊币，用于客户端展示
                    $sqlArr[$val['uid']]['finalCash']=$resultCash;//用户最终聊币数,添加到用户聊币
                }

            }

            $declarerStakeCash=$gameInfo->cash;//庄家携带的聊币数
            $declarerFinalCash=$declarerResultCash+$declarerStakeCash;//庄家最终的钱：用户输的聊币 - 税收 + 本钱

            $isContinue=0;//是否继续坐庄
            $cashEnough=1;//聊币是否足够
            if($isDeclarer){//如果是客户端请求的 ,则需要判断是否可以继续坐庄
                //判断庄家携带聊币剩余数量 是否够开启新的一轮
                if($declarerFinalCash>=$this->diceGameConfig->dice_game_declare_continue_cash_limit && $gameInfo->times < 10){//携带聊币够 ,继续坐庄
                    $isContinue=1;
                }else{//携带聊币不足
                    $cashEnough=0;//聊币不足
                }

                if($isContinue){//用户继续坐庄
                    $return['declarerUid']=$declarer;
                    $updatedecsql='update pre_dice_detail set declarer='.$declarer.',declareTime='.time().',cash='.$declarerFinalCash.',times='.($gameInfo->times).' where id='.$newGameId;
                    $this->db->execute($updatedecsql);
                    $return['declarer']=$declarer;//庄家uid
                }
            }

            //处理聊币，写入投注结果表
            $vArray = array();
            $now=time();
            $broadcast=array();//需要广播的用户中奖

            //查询主播uid
            $roomInfo=Rooms::findfirst($roomId);
            $anchorUid=$roomInfo->uid;

            foreach ($sqlArr as $k => $v) {
                $fax = 0;
                if($v['finalCash'] > $v['stakeCash']){
                    $winNum = $v['finalCash'] - $v['stakeCash'];
                    $num = floor($winNum / $this->diceGameConfig->dice_game_fax_limit);
                    $fax = $num * $this->diceGameConfig->dice_game_fax_limit * $this->diceGameConfig->dice_game_fax_percent;
                    $v['finalCash'] -= $fax;
                    $v['resultCash'] -= $fax;
                }
                if($v['finalCash']>0){//赢得聊币的用户 添加聊币
                    $updatecashsql='UPDATE pre_user_profiles set cash=cash+'.$v['finalCash'].' WHERE uid='.$v['uid'];
                    $this->db->execute($updatecashsql);
                }
                
                $vArray[] = "({$gameId},{$v['uid']},{$now},{$v['stakeCash']},{$v['resultCash']},{$v['finalCash']},{$fax},{$anchorUid})";//构造sql

                //一次获得1000聊币以上的用户，会有系统广播
                if($v['resultCash']>=$this->diceGameConfig->dice_game_win_broadcast_limit){
                    $data['uid']=$v['uid'];//需要广播的用户数组
                    $data['resultCash']=$v['resultCash'];
                    $broadcast[]=$data;
                    unset($data);
                }
            }
            $values = implode(',', $vArray);


            //写入投注结果表 批量插入数据库表
            if($values){
                $insertsql='INSERT INTO pre_dice_result(gameId,uid,createTime,stakeCash,resultCash,finalCash,fax,anchorUid)VALUES '.$values;
                $this->db->execute($insertsql);
            }

            //添加庄家的聊币
            if(!$isContinue&&$declarerFinalCash>0){//如果庄家没有继续坐庄 ，则把聊币加给用户
                $updatecashsql='UPDATE pre_user_profiles SET cash=cash+'.$declarerFinalCash.' WHERE uid='.$declarer;
                $this->db->execute($updatecashsql);
            }

            //庄家输赢结果 写入数据库
            $declarerFaxCash = 0;//$declarerResultCash > 0 ? $declarerResultCash * $this->diceGameConfig->dice_game_fax_percent : 0;
            $values="({$gameId},{$declarer},{$now},{$declarerStakeCash},1,{$declarerResultCash},{$declarerFinalCash},{$declarerFaxCash},{$anchorUid})";
            $declarersql='INSERT INTO pre_dice_result(gameId,uid,createTime,stakeCash,isDeclarer,resultCash,finalCash,fax,anchorUid)VALUES '.$values;
            $this->db->execute($declarersql);


            $return['declarerCash']=$declarerFinalCash;//庄家携带聊币
            $return['cashEnough']=$cashEnough;//庄家携带聊币是否足够
            $return['broadcast']=$broadcast;//广播数组
            $return['anchorUid']=$anchorUid;//主播uid
            return $return;
        } catch (\Exception $e) {
            $this->setLog('DB_OPER_ERROR:' . $e->getMessage());
            return;
        }
    }


    /**
     * @desc 最大可押注值
     * @param int $type 押注类型
     * @param int $declarerCash 庄家聊币
     * @param $stakeArr 押注记录
     * @return float|int 最大可押注值
     */
    public function getMaxStakeCashLimit($type=1,$declarerCash=0,$stakeArr){
        /**
         * 假设 骰宝可押注区域有：一、二、三、四、五、六、大、小、全围。
         * 用户压在这些区域的聊币总和为：A1.A2.A3.A4.A5.A6.A7.A8.A9。
         * 庄家聊币额为：S
         */

        //单押数组
        $signleStakeArr=array($stakeArr[$this->diceGameConfig->dice_game_type_04],
            $stakeArr[$this->diceGameConfig->dice_game_type_05],
            $stakeArr[$this->diceGameConfig->dice_game_type_06],
            $stakeArr[$this->diceGameConfig->dice_game_type_07],
            $stakeArr[$this->diceGameConfig->dice_game_type_08],
            $stakeArr[$this->diceGameConfig->dice_game_type_09],);
        //大小数组
        $sizeStakeArr=array($stakeArr[$this->diceGameConfig->dice_game_type_01],$stakeArr[$this->diceGameConfig->dice_game_type_03]);

        switch($type){
            case $this->diceGameConfig->dice_game_type_01://大 可押注额为：S-2(A1+A2+A3+A4+A5+A6)-A7
            case $this->diceGameConfig->dice_game_type_03://小 可押注额为：S-2(A1+A2+A3+A4+A5+A6)-A8
                $otherSum=$this->skateAdd($signleStakeArr);
                $maxStakeCashLimit=$declarerCash-2*($otherSum)-$stakeArr[$type];
                break;
            case $this->diceGameConfig->dice_game_type_02://全围 可押注额为：(S-MAX(A1，A2，A3，A4，A5，A6))/32-A9
                $maxStakeCashLimit=floor(($declarerCash-$this->cmp($signleStakeArr,'max'))/32)-$stakeArr[$type];
                break;

            case $this->diceGameConfig->dice_game_type_04://单押1 可押注数额为：MIN[(S-2(A2+A3+A4+A5+A6)-max（A7，A8）)/2,(S-32*A9)/3]-A1
            case $this->diceGameConfig->dice_game_type_05://单押2 可押注数额为：MIN[ (S-2(A1+A3+A4+A5+A6)-max（A7，A8）)/2,  (S-32*A9)/3]-A2
            case $this->diceGameConfig->dice_game_type_06://单押3 可押注数额为：MIN[ (S-2(A2+A1+A4+A5+A6)-max（A7，A8）)/2,  (S-32*A9)/3]-A3
            case $this->diceGameConfig->dice_game_type_07://单押4 可押注数额为：MIN[ (S-2(A2+A3+A1+A5+A6)-max（A7，A8）)/2,  (S-32*A9)/3]-A4
            case $this->diceGameConfig->dice_game_type_08://单押5 可押注数额为：MIN[ (S-2(A2+A3+A4+A1+A6)-max（A7，A8）)/2,  (S-32*A9)/3]-A5
            case $this->diceGameConfig->dice_game_type_09://单押6 可押注数额为：MIN[ (S-2(A2+A3+A4+A5+A1)-max（A7，A8）)/2,  (S-32*A9)/3]-A6
                $n1=floor(($declarerCash-2*$this->skateAdd($stakeArr,$stakeArr[$type])-$this->cmp($sizeStakeArr,'max'))/2);
                $n2=floor(($declarerCash-32*$stakeArr[$this->diceGameConfig->dice_game_type_02])/3);
                $maxStakeCashLimit=$this->cmp(array($n1,$n2))-$stakeArr[$type];
                break;
            default:
                $maxStakeCashLimit=0;
        }
        return $maxStakeCashLimit;
    }


    /**
     * @desc 比较数的大小，取出最值
     * @param array $array 需要比较的数组成的数组
     * @param string $type
     * @return int
     */
    private  function cmp($array=array(),$type='min'){
        if(!$array){
            return 0;
        }
        if($type=='min'){//取最小值
            sort($array);//升序
        }else{//取最大值
            rsort($array);//降序
        }
        return $array[0];
    }

    /**
     * @desc 数相加
     * @param array $stakeArr 需要相加的数组
     * @param int $except 减去某个值
     * @return int|number
     */
    private function skateAdd($stakeArr=array(),$except=0){
        if(!$stakeArr){
            return 0;
        }
        $otherSum=array_sum($stakeArr)-$except;
        return $otherSum;
    }





}