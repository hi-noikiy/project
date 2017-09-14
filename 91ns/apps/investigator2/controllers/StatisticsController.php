<?php

namespace Micro\Controllers;

use Phalcon\Logger\Adapter\File as FileLogger;

class StatisticsController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        $this->redirect('statistics/consumer');
    }

    public function consumerAction() {
        $result = $this->invMgr->consumptionTrendInfo();
        $this->view->info = $result;
    }

    public function sellingAction() {
        
    }

    //导出留存
    public function exportAction() {
        if ($this->request->getPost('isexcel')) {// 导出excel
            $startTime = $this->request->getPost('startTime');
            $endTime = $this->request->getPost('endTime');
            $channel = $this->request->getPost('channel');
            $this->invMgr->getStatisticsExport($startTime,$endTime,$channel);
        }
        $today =date("Ymd",  strtotime("-1 day"));
        $lastweek=date("Ymd",  strtotime("-6 day"));
        $str = "</br><form action='' method='post'>"
                . "date:<input type='text' size='12' name='startTime' value='{$lastweek}'>-<input type='text' size='12' name='endTime' value='{$today}'><br>"
                . "channel:<input type='text' size='12' name='channel' value=''>"
                . "<input type='hidden' name='isexcel' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='toExcel'></form>";
        echo $str;
        exit;
    }

    public function exportArpuAction() {
         try {
            //1432634901开始计算
            $createTime = 1432634901;   //20150526开始计算

            //初始化默认信息
            $str = "</br><form action='' method='post'>"
                    . "<input type='hidden' name='getarpu' value='1'>"
                    . "&nbsp;&nbsp;<input type='submit' value='arpu'></form>";

            $arpu = "";
            $totalSum = 0;
            $num = 0;
            $totalNum = 0;
            $channelInfoArray = array();

            //获取出所有的渠道列表
            $sql = "SELECT * FROM \Micro\Models\RegisterLog WHERE createTime>=$createTime GROUP BY parentType";
            $query = $this->modelsManager->createQuery($sql);
            $typeResult = $query->execute();
            $typeArray = array();
            if ($typeResult->valid()) {
                foreach ($typeResult as $data) {
                    $parentType = $data->parentType;
                    if (strlen($parentType) > 0) {
                        array_push($typeArray, $parentType);
                    }
                }
            }

            if ($this->request->getPost('getarpu')) {
                //总ARPU值=收入÷付费用户
                $arpu = 1;
                $sql = "SELECT SUM(totalFee) AS total FROM \Micro\Models\Order WHERE payType!=1000 AND status=1 AND payTime>=$createTime";
                $query = $this->modelsManager->createQuery($sql);
                $records = $query->execute();
                if ($records->valid()) {
                    $records = $records->toArray();
                    $totalSum = $records[0]['total'];
                }

                $sql = "SELECT count(1) AS count FROM \Micro\Models\Order WHERE payType!=1000 AND status=1 AND payTime>=$createTime group by uid";
                $query = $this->modelsManager->createQuery($sql);
                $records = $query->execute();
                if ($records->valid()) {
                    $records = $records->toArray();
                    $num = count($records);
                }
                if ($num > 0) {
                    $arpu = floatval($totalSum)/$num;
                }

                $sql = "SELECT count(1) AS count FROM \Micro\Models\Users WHERE createTime>=$createTime";
                $query = $this->modelsManager->createQuery($sql);
                $records = $query->execute();
                if ($records->valid()) {
                    $records = $records->toArray();
                    $totalNum = $records[0]['count'];
                }

                //计算总渠道
                $str = $str. "<B><label> all : totalPeople=$totalNum,  arpu=".$arpu."($totalSum/$num)</label></B><br>";
                $str = $str. "<label>-----------------------------------------------------</label><br><br>";

                $leftTotalNum = $totalNum;  //标记剩余人数
                $leftSum = $totalSum;       //标记剩余充值数
                $leftNum = $num;            //标记剩余充值人数

                //单渠道ARPU值计算
                foreach ($typeArray as $value) {
                    $channelSum = 0;
                    $channelNum = 0;
                    $channelArpu = 0;
                    $channelTotalNum = 0;

                    $sql = "SELECT SUM(o.totalFee) AS total FROM \Micro\Models\Order o, \Micro\Models\RegisterLog rl"
                           ." WHERE rl.uid = o.uid AND o.payType!=1000 AND o.status=1 AND o.payTime>=$createTime AND rl.parentType = '".$value."'";
                    $query = $this->modelsManager->createQuery($sql);
                    $records = $query->execute();
                    if ($records->valid()) {
                        $records = $records->toArray();
                        $channelSum = floatval($records[0]['total']);
                    }

                    $sql = "SELECT count(1) AS count FROM \Micro\Models\Order o, \Micro\Models\RegisterLog rl"
                           ." WHERE rl.uid = o.uid AND payType!=1000 AND status=1 AND o.payTime>=$createTime AND rl.parentType = '".$value."' group by o.uid";
                    $query = $this->modelsManager->createQuery($sql);
                    $records = $query->execute();
                    if ($records->valid()) {
                        $records = $records->toArray();
                        $channelNum = count($records);
                    }
                    if ($channelNum > 0) {
                        $channelArpu = floatval($channelSum)/$channelNum;
                    }

                    $sql = "SELECT count(1) AS count FROM \Micro\Models\Users u, \Micro\Models\RegisterLog rl"
                           ." WHERE rl.uid = u.uid AND u.createTime>=$createTime AND rl.parentType = '".$value."'";
                    $query = $this->modelsManager->createQuery($sql);
                    $records = $query->execute();
                    if ($records->valid()) {
                        $records = $records->toArray();
                        $channelTotalNum = $records[0]['count'];
                    }

                    $label = "<B><label> channel [".$value."] :  channelTotalNum=$channelTotalNum, arpu = ".$channelArpu."($channelSum/$channelNum)</label></B><br><br>";
                    $str = $str.$label;
                    
                    //该渠道子渠道
                    $sql1 = "SELECT * FROM \Micro\Models\RegisterLog WHERE parentType='{$value}' and createTime>={$createTime} GROUP BY subType";
                    $query1 = $this->modelsManager->createQuery($sql1);
                    $records1 = $query1->execute();
                    $channelSum2 = 0;
                    $channelNum2 = 0;
                    $channelArpu2 = 0;
                    $channelTotalNum2 = 0;
                    if ($records1 != false) {
                        foreach ($records1 as $val) {
                            if ($val->subType != '') {
                                $sql11 = "SELECT SUM(o.totalFee) AS total FROM \Micro\Models\Order o, \Micro\Models\RegisterLog rl"
                                        . " WHERE rl.uid = o.uid AND o.payType!=1000 AND o.status=1 AND o.payTime>=$createTime AND rl.subType = '" . $val->subType . "'";
                                $query11 = $this->modelsManager->createQuery($sql11);
                                $records11 = $query11->execute();
                                if ($records11->valid()) {
                                    $records11 = $records11->toArray();
                                    $channelSum2 = floatval($records11[0]['total']);
                                }


                                $sql2 = "SELECT count(1) AS count FROM \Micro\Models\Order o, \Micro\Models\RegisterLog rl"
                                       ." WHERE rl.uid = o.uid AND payType!=1000 AND status=1 AND o.payTime>=$createTime AND rl.subType = '". $val->subType ."' group by o.uid";
                                $query2 = $this->modelsManager->createQuery($sql2);
                                $records2 = $query2->execute();
                                if ($records2->valid()) {
                                    $records2 = $records2->toArray();
                                    $channelNum2 = count($records2);
                                }
                                if ($channelNum2 > 0) {
                                    $channelArpu2 = floatval($channelSum2)/$channelNum2;
                                }

                                /*$sql2 = "SELECT SUM(o.totalFee) AS total FROM \Micro\Models\Order o, \Micro\Models\RegisterLog rl"
                                        . " WHERE rl.uid = o.uid AND o.payType!=1000 AND o.status=1 AND o.payTime>=$createTime AND rl.subType = '" . $val->subType . "'";
                                $query2 = $this->modelsManager->createQuery($sql2);
                                $records2 = $query2->execute();
                                if ($records2->valid()) {
                                    $records2 = $records2->toArray();
                                    $channelNum2 = count($records2);
                                }
                                if ($channelNum2 > 0) {
                                    $channelArpu2 = floatval($channelSum2) / $channelNum2;
                                }*/

                                $sql3 = "SELECT count(1) AS count FROM \Micro\Models\Users u, \Micro\Models\RegisterLog rl"
                                        . " WHERE rl.uid = u.uid AND u.createTime>=$createTime AND rl.subType = '" . $val->subType . "'";
                                $query3 = $this->modelsManager->createQuery($sql3);
                                $records3 = $query3->execute();
                                if ($records3->valid()) {
                                    $records3 = $records3->toArray();
                                    $channelTotalNum2 = $records3[0]['count'];
                                }
                                $label2 = "&nbsp;&nbsp;&nbsp;&nbsp;<label> childrenChannel [" . $val->subType . "] :  childrenChannelTotalNum=$channelTotalNum2, arpu = " . $channelArpu2 . "($channelSum2/$channelNum2)</label><br><br>";
                            }
                            $str = $str . $label2;
                        }
                    }

                    $leftSum -= $channelSum;
                    $leftTotalNum -= $channelTotalNum;
                    $leftNum -= $channelNum;
                }

                //计算非渠道
                {
                    $leftArpu = 0;
                    if ($leftNum > 0) {
                        $leftArpu = floatval($leftSum)/$leftNum;
                    }
                    $str = $str. "<B><label> Others : leftTotalNum=$leftTotalNum,  arpu=".$leftArpu."($leftSum/$leftNum)</label></B><br>";
                }
            }
            else {
                $str = $str. " Please press button";
            }

            echo $str;
        }
        catch (\Exception $e) {
            echo "error : ".$e->getMessage();
        }
        exit;
    }

    public function exportRegAction() {
        if ($this->request->getPost('getreg')) {// 导出excel
            $startTime = $this->request->getPost('startTime');
            $endTime = $this->request->getPost('endTime');
            $this->invMgr->getRegStatisticsExport($startTime,$endTime);
        }
        $today =date("Ymd",  strtotime("-1 day"));
        $lastweek=date("Ymd",  strtotime("-6 day"));
        $str = "</br><form action='' method='post'>"
                . "date:<input type='text' size='12' name='startTime' value='{$lastweek}'>-<input type='text' size='12' name='endTime' value='{$today}'>"
                . "<input type='hidden' name='getreg' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='toExcel'></form>";
        echo $str;
        exit;
    }

    //修正由于抢座数据错误引起的问题
    public function fixBugsAction() {
        return;
        if ($this->request->getPost('fix')) {// 修正错误
            $uid = $this->request->getPost('uid');
            $createTime = $this->request->getPost('createTime');

            //$this->delConsumeLog($number, $uid, $val->createTime);
            $sql = "SELECT * FROM \Micro\Models\ConsumeLog  WHERE uid=$uid AND createTime>=$createTime AND type<1000";
            $query = $this->modelsManager->createQuery($sql);
            $records = $query->execute();
            $number=1;
            if ($records != false) {
                foreach ($records as $val) {
                    $this->delConsumeLog($number, $uid, $val->createTime);
                    $number++;
                }
            }
        }

        $str = "</br><form action='' method='post'>"
                . "uid:       <input type='text' size='12' name='uid' value=''></br></br>
                   createTime:<input type='text' size='12' name='createTime' value=''>"
                . "<input type='hidden' name='fix' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='fix'></form>";
        echo $str;
        exit;
    }

    private function delConsumeLog($number, $uid, $createTime) {
        try {
            $hasRecord = false;
            //写到一个单独的文件中，做记录
            $fixBugs = new FileLogger("{$this->config->directory->logsDir}/fixBugs.log");

            // 根据消费记录，删除对应的数据
            $sql = "SELECT * FROM \Micro\Models\ConsumeLog  WHERE uid=$uid AND createTime=$createTime AND type<1000";
            $query = $this->modelsManager->createQuery($sql);
            $records = $query->execute();
            if ($records != false) {
                foreach ($records as $val) {
                    if ($val->type != 3 && $val->type != 4 && $val->type != 5) {
                        continue;
                    }

                    $hasRecord = true;
                    $consumeLog = $val->id;

                    // 获取主播Id
                    $anchorId = $val->anchorId;

                    // 根据主播Id获取房间Id
                    $roomData = \Micro\Models\Rooms::findFirst("uid = " . $anchorId);
                    $roomId = $roomData->roomId;
                    $fixBugs->error("number : $number -----------------------------");
                    $fixBugs->error("consumeLog=$consumeLog, uid=$val->uid, type=$val->type, anchorId=$val->anchorId, amount=$val->amount, income=$val->income, createTime=".date("Y-m-d H:i:s", $val->createTime));

                    //送礼的经验值是一半，抢座和守护的经验值是100%
                    $anchorExp = $val->amount;

                    if ($val->type == 3) {  //守护
                        //一条记录，直接手动删除相关的表
                    }
                    else if ($val->type == 4) { //抢座
                        $fixBugs->error("抢座收益：".$val->amount);
                        $subSql = "DELETE FROM \Micro\Models\GrabLog WHERE consumeLogId=$consumeLog";
                        $subQuery = $this->modelsManager->createQuery($subSql);
                        $subQuery->execute();

                        //将这时间之后的数据都删除(临时性做法)
                        $subSql = "DELETE FROM \Micro\Models\RoomGiftLog WHERE roomId=$roomId AND uid = $uid AND createTime>$createTime AND type=2";
                        $subQuery = $this->modelsManager->createQuery($subSql);
                        $subQuery->execute();
                    }
                    else if ($val->type == 5) { //送礼
                        $fixBugs->error("送礼收益：".$val->amount);
                        $anchorExp = floor($val->amount * 0.5);
                        $subSql = "DELETE FROM \Micro\Models\GiftLog WHERE consumeLogId=$consumeLog";
                        $subQuery = $this->modelsManager->createQuery($subSql);
                        $subQuery->execute();

                        //将这时间之后的数据都删除(临时性做法)
                        $subSql = "DELETE FROM \Micro\Models\RoomGiftLog WHERE roomId=$roomId AND uid = $uid AND createTime>$createTime AND type=1";
                        $subQuery = $this->modelsManager->createQuery($subSql);
                        $subQuery->execute();
                    }

                    // 回滚主播的收益
                    $income = $val->income;
                    if ($val->familyId > 0) {
                        $sqlA = "UPDATE \Micro\Models\SignAnchor SET money=money-$income WHERE uid=$anchorId";
                        $queryA = $this->modelsManager->createQuery($sqlA);
                        $queryA->execute();

                        $sqlA = "UPDATE \Micro\Models\UserProfiles SET exp2=exp2-$anchorExp WHERE uid=$anchorId";
                        $queryA = $this->modelsManager->createQuery($sqlA);
                        $queryA->execute();
                    }
                    else {
                        $sqlA = "UPDATE \Micro\Models\UserProfiles SET money=money-$income, exp2=exp2-$anchorExp WHERE uid=$anchorId";
                        $queryA = $this->modelsManager->createQuery($sqlA);
                        $queryA->execute();
                    }

                    // 回滚用户的消费
                    $amount = $val->amount;
                    $sqlB = "UPDATE \Micro\Models\UserProfiles SET cash=cash+$amount, exp3=exp3-$amount WHERE uid=$uid";
                    $queryB = $this->modelsManager->createQuery($sqlB);
                    $queryB->execute();
                }
            }

            // 做删除消费记录的操作
            if($hasRecord) {
                $delSql = "DELETE FROM \Micro\Models\ConsumeLog  WHERE uid=$uid AND createTime=$createTime AND type<1000";
                $delQuery = $this->modelsManager->createQuery($delSql);
                $delQuery->execute();
            }

            if($hasRecord) {
                echo "operate OK: uid=$uid, createTime=".date("Y-m-d H:i:s", $createTime)."</br>";
            }
            else {
                echo "not exist record: uid=$uid, createTime=".date("Y-m-d H:i:s", $createTime)."</br>";
            }
        }
        catch (\Exception $e) {
            echo "error : ".$e->getMessage();
        }
    }

    public function getDayGiftsAction() {
        if ($this->request->isPost()) {
            $date = $this->request->getPost('date');
            if($date){
                $time = strtotime($date);
            }
            $this->cornMgr->getDayGifts($time);
        }
        $today =date("Ymd",  strtotime("-1 day"));
        $lastweek=date("Ymd",  strtotime("-6 day"));
        $str = "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />每日礼物定时任务</br><form action='' method='post'>"
                . "date:<input type='text' size='12' name='date' value='{$lastweek}'>"
                . "&nbsp;&nbsp;<input type='submit' value='run'></form>";
        echo $str;
        exit;
    }

    public function getDayIncomeAction() {
        if ($this->request->isPost()) {
            $date = $this->request->getPost('date');
            if($date){
                $time = strtotime($date);
            }
            $this->cornMgr->getDayIncomeLog($time);
        }
        $lastweek=date("Ym");
        $str = "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />每日收益流水定时任务</br><form action='' method='post'>"
                . "date:<input type='text' size='12' name='date' value='{$lastweek}'>"
                . "&nbsp;&nbsp;<input type='submit' value='run'></form>";
        echo $str;
        exit;
    }

    public function getMonthIncomeAction() {
        if ($this->request->isPost()) {
            $date = $this->request->getPost('date');
            if($date){
                $time = strtotime($date);
            }
            $this->cornMgr->getMonthIncomeLog($time);
        }
        $lastweek=date("Ym");
        $str = "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />每余额佣金定时任务</br><form action='' method='post'>"
                . "date:<input type='text' size='12' name='date' value='{$lastweek}'>"
                . "&nbsp;&nbsp;<input type='submit' value='run'></form>";
        echo $str;
        exit;
    }



}
