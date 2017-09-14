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

    
    //导用户信息到mysql
    public function userInfoToMysqlAction() {
        return;
//         $collection = $this->mongo->collection('account');
//            $result = array();
//           $cursor = $collection->find();
//            while ($ret = $cursor->getNext()) {
//               array_push($result, $ret);
//           }
//           print_R($result);exit;
//       


        if ($this->request->getPost('act')) {
            /*$collection1 = $this->mongo->collection('account');
            $result1 = array();
            $col1 = $collection1->find();
            $start = 0;
            $end = 5000;
            $index = 0;
            while ($ret1 = $col1->getNext()) {
                if ($index < $start) {
                    $index = $index + 1;
                    continue;
                }
                array_push($result1, $ret1);
                $index = $index + 1;
                if ($index >= $end)
                    break;
            }
            echo "query time = ".time();
            foreach ($result1 as $val1) {
                $info1 = \Micro\Models\Users::findfirst("accountId='" . $val1['uid'] . "' and password=''");
                if ($info1) {
                    $info1->password = $val1['psw'];
                    $info1->key=$val1['key'];
                    $info1->save();
                }
            }
            echo "result time = ".time();
            echo "ok1";
            exit;*/
 
            $collection2 = $this->mongo->collection('account_qq');
            $result2 = array();
            $col2 = $collection2->find();
            $start = 4999;
            $end = 10000;
            $index = 0;
            echo "begin time = ".time();
            while ($ret2 = $col2->getNext()) {
                if ($index < $start) {
                    $index = $index + 1;
                    continue;
                }
                array_push($result2, $ret2);
                $index = $index + 1;
                if ($index >= $end)
                    break;
            }
            echo ", query time = ".time();
            foreach ($result2 as $val2) {
                $info2 = \Micro\Models\Users::findfirst("accountId='" . $val2['uid'] . "' and password=''");
                if ($info2) {
                    $str = $val2['openid'];
                    $arr = explode('_', $str);
                    $openId = $arr[0];
                    $type = $arr[1];
                    $typeArr = array('qqdenglu' => 1, 'QQ' => 1, 'sinaweibo' => 2, 'douzi' => 3);
                    $rand = mt_rand(10000000, 99999999);
                     //生成8位随机码
                    $key=$this->userAuth->makeCode(8);
                    $info2->password = md5($key.md5($rand));
                    $info2->key = $key;
                    $info2->openId = $openId;
                    $info2->userType = $typeArr[$type];
                    $info2->canSetUserName = 1;
                    $info2->canSetPassword = 1;
                    $info2->save();
                }
            }
            echo ",result time = ".time();
            echo " ok";
            exit;
        }

        $str = "</br><form action='' method='post'>"
                . "<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='go'></form>";
        echo $str;
        exit;
    }

    
    //更改mongodb关注表里的accountId
    public function updateFollowDbAction() {
        //echo phpinfo();exit;
        if ($this->request->getPost('act')) {
            try {
                $list = \Micro\Models\Users::find();
                foreach ($list as $key => $val) {
                    $userArray[$val->accountId] = $val->uid;
                }

               // ini_set('mongo.long_as_object', 1);
               
                
                $collection = $this->mongo->collection('follow');
                $result = array();
                $cursor = $collection->find();
                while ($ret = $cursor->getNext()) {
                    array_push($result, $ret);
                }
                // print_R($result);
                //  exit;
                foreach ($result as $val) {
                    //accountId转成uid
                    $uid = isset($userArray[$val['uid']])?$userArray[$val['uid']]:'';
                    if ($uid) {
                        $collection->update(function($query)use($val, $uid) {
                            $query->whereId($val['_id']->{'$id'})->set(array('uid' => $uid));
                        });
                    }

                    //fid转成extfid
                    $fids = $val['fids'];

                    foreach ($fids as &$v) {
                        $v['fid'] = $v['extfid'];
                    }
//                var_dump($val['_id']->{'$id'});die;
                    $result = $collection->update(function($query)use($val, $fids) {
                        $query->whereId($val['_id']->{'$id'})->set(array('fids' => $fids));
                    });
                }
                
                $collection = $this->mongo->collection('ownfollow');
                $result = array();
                $cursor = $collection->find();
                while ($ret = $cursor->getNext()) {
                    array_push($result, $ret);
                }
                // print_R($result);
                //  exit;
                foreach ($result as $val) {
                    //accountId转成uid
                    $uid = isset($userArray[$val['uid']])?$userArray[$val['uid']]:'';
                    if ($uid) {
                        $collection->update(function($query)use($val, $uid) {
                            $query->whereId($val['_id']->{'$id'})->set(array('uid' => $uid));
                        });
                    }

                    //fid转成extfid
                    $fids = $val['fids'];

                    foreach ($fids as &$v) {
                        $v['fid'] = $v['extfid'];
                    }
//                var_dump($val['_id']->{'$id'});die;
                    $result = $collection->update(function($query)use($val, $fids) {
                        $query->whereId($val['_id']->{'$id'})->set(array('fids' => $fids));
                    });
                }
                 
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
             echo "ok";
            exit;
        }

        $str = "</br><form action='' method='post'>"
                . "<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='go'></form>";
        echo $str;
        exit;
    }

    public function getGiftStarAction() {
        if ($this->request->isPost()) {
            $date = $this->request->getPost('date');
            $this->cornMgr->getGiftStar($date);
        }
        $day = date("Ymd");
        $str = "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />礼物周星</br><form action='' method='post'>"
                . "date:<input type='text' size='12' name='date' value='{$day}'>"
                . "&nbsp;&nbsp;<input type='submit' value='run'></form>";
        echo $str;
        exit;
    }

    /**
     * 中秋排行榜送礼
     */
    public function midAutumnRankAction(){
        header("Content-Type: text/html; charset=utf-8");
        $act = $this->request->getPost('act');
        if($act){
            $result = $this->activityMgr->midAutumnRank();
            echo 'OK' . json_encode($result);
            exit;
        }else{
            $str = "</br>中秋活动.</br></br><form action='' method='post'>";
            $str.="<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='点击执行'></form>";
            echo $str;
            exit;
        }
    }

    //用户推荐url生成
    public function recommendUrlAction() {
         header("Content-Type: text/html; charset=utf-8");
        if ($this->request->getPost('act')) {
            try {
                $uid = $this->request->getPost('uid');
                $info = \Micro\Models\Recommend::findfirst("uid=" . $uid);
                $userInfo = \Micro\Models\UserInfo::findfirst($uid);
                if ($info) {
                    $url = $info->url;
                    echo "uid:" . $uid;
                    echo "<br/>nickName:" . $userInfo->nickName;
                    echo "<br/>";
                    echo "<a target='_blank' href='{$url}'>".$url."</a>";
                    $newImage = $this->pathGenerator->getRecommendqrcodePath('qrcode_' . $uid . ".png");
                    if (!file_exists($newImage)) {//二维码不存在
                        //生成二维码
                        $filename = $this->pathGenerator->getRecommendqrcodePath('qrcode_' . $uid . ".png");
                        $logo = $this->pathGenerator->getRecommendqrcodePath("logo.png");
                        $newImage = $this->normalLib->getQrcode($url, $filename, $logo);
                        $imagePath = $this->url->getStatic($newImage);
                    } else {//二维码已存在
                        $imagePath = $this->url->getStatic($newImage);
                    }
                    echo "<br/><img src='{$imagePath}'>";
                    exit;
                }
                if ($userInfo == false) {
                    echo 'uid no exist';
                    exit;
                }
                $domin = 'http://m.91ns.com';
                $key = "91ns.com_";
                $str = urlencode(base64_encode($key . $uid));
                $url = $domin . '/activities/recommendReceive?str=' . $str;
                $new = new \Micro\Models\Recommend();
                $new->uid = $uid;
                $new->url = $url;
                $new->createTime = time();
                $new->save();
                echo "uid:" . $uid;
                echo "<br/>nickName:" . $userInfo->nickName;
                echo "<br>";
                echo "<a target='_blank' href='{$url}'>".$url."</a>";
                //生成二维码
                $filename = $this->pathGenerator->getRecommendqrcodePath('qrcode_' . $uid . ".png");
                $logo = $this->pathGenerator->getRecommendqrcodePath("logo.png");
                $newImage = $this->normalLib->getQrcode($url, $filename, $logo);
                $imagePath = $this->url->getStatic($newImage);
                echo "<br/><img src='{$imagePath}'>";
                exit;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $str = "</br><form action='' method='post'>"
                . "uid:<input type='text' name='uid' value=''>"
                . "<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='go'></form>";
        echo $str;
        exit;
    }

    //刷表
    public function updateConsumesAction() {
        return;
        if ($this->request->isPost()) {
            // $startDate = $this->request->getPost('startDate');
            $type = $this->request->getPost('type');
            $start = $this->request->getPost('start');
            $num = $this->request->getPost('num');
            // $endDate = $this->request->getPost('endDate');
            $type = $type ? $type : 1;
            $start = $start ? $start : 1;
            $num = $num ? $num : 10000;
            // $startDate = $startDate ? strtotime($startDate) : strtotime(date('Ymd'));
            // $endDate = $endDate ? strtotime($endDate) : strtotime(date('Ymd')) + 86400;
            try {
                if($type == 1){//礼物（包括聊豆）
                    /*$total = \Micro\Models\ConsumeLog::count('(type = 5 or type = 1001) and createTime < 1438790400');
                    if($start > $total){
                        echo 'no data left!';die;
                    }
                    $left = $total - $start;
                    $num = ($left > $num) ? $num : $left;
                    $giftData = \Micro\Models\GiftConfigs::find();
                    $giftConfigs = array();
                    foreach ($giftData as $k => $v) {
                        $tmp['id'] = $v->id;
                        $tmp['name'] = $v->name;
                        $tmp['configName'] = $v->configName;
                        $tmp['typeId'] = $v->typeId;
                        $giftConfigs[$v->id] = $tmp;
                        unset($tmp);
                    }*/
                    $sql = 'select nickName,uid from Micro\Models\UserInfo';
                    $query = $this->modelsManager->createQuery($sql);
                    $users = $query->execute();
                    $userArr = array();
                    if($users->valid()){
                        foreach ($users as $k => $v) {
                            $tmp = array(
                                'nickName' => $v->nickName,
                                // 'isTuo' => $v->internalType == 2 ? 1 : 0,
                                'uid' => $v->uid
                            );
                            $userArr[$v->uid] = $tmp;
                        }
                    }
                    
                    
                    // for ($i=0; $i < 100; $i++) {
                    for ($i=0; $i < 5; $i++) {
                        $start = $i*10000;
                        $num = 10000;
                        $conn = $this->di->get('db');
                        $sql_ = 'select cl.uid,cl.createTime,cl.familyId,cl.anchorId,cl.type,cl.amount,cl.income,gl.giftId,gl.count from pre_consume_log as cl ' . 
                            ' left join pre_gift_log as gl on gl.consumeLogId = cl.id where (cl.type = 5 or cl.type = 1001) and cl.createTime >= 1438790400 limit ' . $start . ',' . $num;//0,50000
                        $res = $conn->fetchAll($sql_);
                        $sql = 'insert into pre_consume_detail_log(uid,nickName,receiveUid,familyId,type,itemId,count,amount,income,remark,createTime,isTuo) values';
                        if($res){
                            foreach ($res as $k => $v) {
                                $nickName = isset($userArr[$v['uid']]) ? $userArr[$v['uid']]['nickName'] : '';
                                $receiveName = isset($userArr[$v['anchorId']]) ? $userArr[$v['anchorId']]['nickName'] : '';
                                $sql .= "({$v['uid']},'{$nickName}',{$v['anchorId']},{$v['familyId']},{$v['type']}";
                                $sql .= ",{$v['giftId']},{$v['count']},{$v['amount']},{$v['income']},'{$receiveName}',{$v['createTime']},0),";
                            }
                        }
                        $sql = substr($sql, 0, -1);
                        $james=fopen("{$this->config->directory->logsDir}/d/gift_{$start}.sql","w+");
                        fwrite($james,$sql);
                        fclose($james);
                    }
                    die;
                }elseif($type == 2){//魅力星
                    /*$total = \Micro\Models\RoomGiftLog::count('type = 3 and createTime >= 1438790400');
                    if($start > $total){
                        echo 'no data left!';die;
                    }
                    $left = $total - $start;
                    $num = ($left > $num) ? $num : $left;*/
                    $sql = 'select nickName,uid from Micro\Models\UserInfo';
                    $query = $this->modelsManager->createQuery($sql);
                    $users = $query->execute();
                    $userArr = array();
                    if($users->valid()){
                        foreach ($users as $k => $v) {
                            $tmp = array(
                                'nickName' => $v->nickName,
                                'uid' => $v->uid
                            );
                            $userArr[$v->uid] = $tmp;
                        }
                    }
                    $rooms = \Micro\Models\Rooms::find(
                        array(
                            'columns' => 'uid,roomId'
                        )
                    );
                    $roomArr = array();
                    if($rooms->valid()){
                        foreach ($rooms as $k => $v) {
                            $tmp = array(
                                'roomId' => $v->roomId,
                                'uid' => $v->uid
                            );
                            $roomArr[$v->roomId] = $tmp;
                        }
                    }
                    for ($i=0; $i < 5; $i++) {
                        $start = $i*10000;
                        $num = 10000;
                        $conn = $this->di->get('db');
                        $sql_ = 'select uid,createTime,roomId from pre_room_gift_log where type = 3 and createTime >= 1438790400 limit ' . $start . ',' . $num;//0,50000
                        $res = $conn->fetchAll($sql_);
                        $sql = 'insert into pre_consume_detail_log(uid,nickName,receiveUid,familyId,type,itemId,count,amount,income,remark,createTime,isTuo) values';
                        foreach ($res as $k => $v) {
                            $sql .= "({$v['uid']},'{$userArr[$v['uid']]['nickName']}','{$roomArr[$v['roomId']]['uid']}',0,{$this->config->consumeType->sendStar}";
                            $sql .= ",0,1,0,0,'{$userArr[$roomArr[$v['roomId']]['uid']]['nickName']}',{$v['createTime']},0),";
                        }
                        $sql = substr($sql, 0, -1);
                        $james=fopen("{$this->config->directory->logsDir}/d/star_{$start}.sql","w+");
                        fwrite($james,$sql);
                        fclose($james);
                    }
                    die;
                }else if($type == 3){//抢座
                    $sql = 'select nickName,uid from Micro\Models\UserInfo';
                    $query = $this->modelsManager->createQuery($sql);
                    $users = $query->execute();
                    $userArr = array();
                    if($users->valid()){
                        foreach ($users as $k => $v) {
                            $tmp = array(
                                'nickName' => $v->nickName,
                                'uid' => $v->uid
                            );
                            $userArr[$v->uid] = $tmp;
                        }
                    }
                    // for ($i=0; $i < 100; $i++) {
                    $start = 0;
                    $num = 10000;
                    $conn = $this->di->get('db');
                    $sql_ = 'select cl.uid,cl.createTime,cl.familyId,cl.anchorId,cl.type,cl.amount,cl.income,gl.seatPos,gl.count from pre_consume_log as cl ' . 
                        ' left join pre_grab_log as gl on gl.consumeLogId = cl.id where (cl.type = 4) and cl.createTime >= 1438790400 limit ' . $start . ',' . $num;//0,50000
                    $res = $conn->fetchAll($sql_);
                    $sql = 'insert into pre_consume_detail_log(uid,nickName,receiveUid,familyId,type,itemId,count,amount,income,remark,createTime,isTuo) values';
                    if($res){
                        foreach ($res as $k => $v) {
                            $nickName = isset($userArr[$v['uid']]) ? $userArr[$v['uid']]['nickName'] : '';
                            $receiveName = isset($userArr[$v['anchorId']]) ? $userArr[$v['anchorId']]['nickName'] : '';
                            $sql .= "({$v['uid']},'{$nickName}',{$v['anchorId']},{$v['familyId']},{$v['type']}";
                            $sql .= ",0,{$v['count']},{$v['amount']},{$v['income']},'{$receiveName}',{$v['createTime']},0),";
                        }
                    }
                    $sql = substr($sql, 0, -1);
                    $james=fopen("{$this->config->directory->logsDir}/d/grabSeat_{$start}.sql","w+");
                    fwrite($james,$sql);
                    fclose($james);
                    die;
                    // }
                }else if($type == 4){//座驾
                    $sql = 'select nickName,uid from Micro\Models\UserInfo';
                    $query = $this->modelsManager->createQuery($sql);
                    $users = $query->execute();
                    $userArr = array();
                    if($users->valid()){
                        foreach ($users as $k => $v) {
                            $tmp = array(
                                'nickName' => $v->nickName,
                                'uid' => $v->uid
                            );
                            $userArr[$v->uid] = $tmp;
                        }
                    }
                    // for ($i=0; $i < 100; $i++) {
                    $start = 0;
                    $num = 10000;
                    $conn = $this->di->get('db');
                    $sql_ = 'select cl.uid,cl.createTime,cl.familyId,cl.anchorId,cl.type,cl.amount,cl.income,gl.carId from pre_consume_log as cl ' . 
                        ' left join pre_car_log as gl on gl.consumeLogId = cl.id where (cl.type = 2) and cl.createTime >= 1438790400 limit ' . $start . ',' . $num;//0,50000
                    $res = $conn->fetchAll($sql_);
                    $sql = 'insert into pre_consume_detail_log(uid,nickName,receiveUid,familyId,type,itemId,count,amount,income,remark,createTime,isTuo) values';
                    if($res){
                        foreach ($res as $k => $v) {
                            $nickName = isset($userArr[$v['uid']]) ? $userArr[$v['uid']]['nickName'] : '';
                            // $receiveName = isset($userArr[$v['anchorId']]) ? $userArr[$v['anchorId']]['nickName'] : '';
                            $sql .= "({$v['uid']},'{$nickName}',{$v['anchorId']},{$v['familyId']},{$v['type']}";
                            $sql .= ",{$v['carId']},1,{$v['amount']},{$v['income']},'',{$v['createTime']},0),";
                        }
                    }
                    $sql = substr($sql, 0, -1);
                    $james=fopen("{$this->config->directory->logsDir}/d/car_{$start}.sql","w+");
                    fwrite($james,$sql);
                    fclose($james);
                    die;
                }else if($type == 5){//守护
                    $sql = 'select nickName,uid from Micro\Models\UserInfo';
                    $query = $this->modelsManager->createQuery($sql);
                    $users = $query->execute();
                    $userArr = array();
                    if($users->valid()){
                        foreach ($users as $k => $v) {
                            $tmp = array(
                                'nickName' => $v->nickName,
                                'uid' => $v->uid
                            );
                            $userArr[$v->uid] = $tmp;
                        }
                    }
                    // for ($i=0; $i < 100; $i++) {
                    $start = 0;
                    $num = 10000;
                    $conn = $this->di->get('db');
                    $sql_ = 'select cl.uid,cl.createTime,cl.familyId,cl.anchorId,cl.type,cl.amount,cl.income,gl.guardType from pre_consume_log as cl ' . 
                        ' left join pre_guard_log as gl on gl.consumeLogId = cl.id where (cl.type = 3) and cl.createTime >= 1438790400 limit ' . $start . ',' . $num;//0,50000
                    $res = $conn->fetchAll($sql_);
                    $sql = 'insert into pre_consume_detail_log(uid,nickName,receiveUid,familyId,type,itemId,count,amount,income,remark,createTime,isTuo) values';
                    if($res){
                        foreach ($res as $k => $v) {
                            $nickName = isset($userArr[$v['uid']]) ? $userArr[$v['uid']]['nickName'] : '';
                            $receiveName = isset($userArr[$v['anchorId']]) ? $userArr[$v['anchorId']]['nickName'] : '';
                            $sql .= "({$v['uid']},'{$nickName}',{$v['anchorId']},{$v['familyId']},{$v['type']}";
                            $sql .= ",{$v['guardType']},1,{$v['amount']},{$v['income']},'{$receiveName}',{$v['createTime']},0),";
                        }
                    }
                    $sql = substr($sql, 0, -1);
                    $james=fopen("{$this->config->directory->logsDir}/d/guard_{$start}.sql","w+");
                    fwrite($james,$sql);
                    fclose($james);
                    die;
                }else if($type == 6){//赠送VIP和car
                    $sql = 'select nickName,uid from Micro\Models\UserInfo';
                    $query = $this->modelsManager->createQuery($sql);
                    $users = $query->execute();
                    $userArr = array();
                    if($users->valid()){
                        foreach ($users as $k => $v) {
                            $tmp = array(
                                'nickName' => $v->nickName,
                                'uid' => $v->uid
                            );
                            $userArr[$v->uid] = $tmp;
                        }
                    }
                    // for ($i=0; $i < 100; $i++) {
                    $start = 0;
                    $num = 10000;
                    $conn = $this->di->get('db');
                    $sql_ = 'select cl.uid,cl.createTime,cl.familyId,cl.type,cl.amount,cl.income,gl.type as sendType,gl.itemId,gl.itemTime,gl.receiveUid from pre_consume_log as cl ' . 
                        ' left join pre_user_give_log as gl on gl.consumeLogId = cl.id where (cl.type = 9 or cl.type = 10) and cl.createTime >= 1438790400 limit ' . $start . ',' . $num;//0,50000
                    $res = $conn->fetchAll($sql_);
                    $sql = 'insert into pre_consume_detail_log(uid,nickName,receiveUid,familyId,type,itemId,count,amount,income,remark,createTime,isTuo) values';
                    if($res){
                        foreach ($res as $k => $v) {
                            $nickName = isset($userArr[$v['uid']]) ? $userArr[$v['uid']]['nickName'] : '';
                            $receiveName = isset($userArr[$v['receiveUid']]) ? $userArr[$v['receiveUid']]['nickName'] : '';
                            $sql .= "({$v['uid']},'{$nickName}',{$v['receiveUid']},{$v['familyId']},{$v['type']}";
                            $sql .= ",{$v['itemId']},{$v['itemTime']},{$v['amount']},{$v['income']},'{$receiveName}',{$v['createTime']},0),";
                        }
                    }
                    $sql = substr($sql, 0, -1);
                    $james=fopen("{$this->config->directory->logsDir}/d/send_{$start}.sql","w+");
                    fwrite($james,$sql);
                    fclose($james);
                    die;
                }else if($type == 7){//喇叭、购买vip
                    $start = 0;
                    $num = 10000;
                    $sql = 'select nickName,uid from Micro\Models\UserInfo';
                    $query = $this->modelsManager->createQuery($sql);
                    $users = $query->execute();
                    $userArr = array();
                    if($users->valid()){
                        foreach ($users as $k => $v) {
                            $tmp = array(
                                'nickName' => $v->nickName,
                                'uid' => $v->uid
                            );
                            $userArr[$v->uid] = $tmp;
                        }
                    }
                    $conn = $this->di->get('db');
                    $sql_ = 'select cl.uid,cl.createTime,cl.familyId,cl.type,cl.amount,cl.income,cl.anchorId from pre_consume_log as cl ' . 
                        ' where (cl.type = 1 or cl.type = 6 or cl.type = 7) and cl.createTime >= 1438790400 limit ' . $start . ',' . $num;//0,50000
                    $res = $conn->fetchAll($sql_);
                    $sql = 'insert into pre_consume_detail_log(uid,nickName,receiveUid,familyId,type,itemId,count,amount,income,remark,createTime,isTuo) values';
                    if($res){
                        foreach ($res as $k => $v) {
                            $nickName = isset($userArr[$v['uid']]) ? $userArr[$v['uid']]['nickName'] : '';
                            $sql .= "({$v['uid']},'{$nickName}',{$v['anchorId']},{$v['familyId']},{$v['type']}";
                            $sql .= ",1,1,{$v['amount']},{$v['income']},'',{$v['createTime']},0),";
                        }
                    }
                    $sql = substr($sql, 0, -1);
                    $james=fopen("{$this->config->directory->logsDir}/d/others_{$start}.sql","w+");
                    fwrite($james,$sql);
                    fclose($james);
                    die;
                }else{
                    echo 'Error Type!!!!';
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        $startDate = date("Ymd");
        $endDate = date("Ymd");
        $str = "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />刷消费日志表<form action='' method='post'>"
               . "</br>【1-礼物，2-魅力星，3-抢座，4-座驾，5-守护，6-赠送（vip,car），7-喇叭和购买vip】"
               . "</br>类型：<input type='text' size='12' name='type' value='1'>"
               // . "</br>startDate:<input type='text' size='12' name='startDate' value='{$startDate}'>"
               // . "</br>endDate:<input type='text' size='12' name='endDate' value='{$endDate}'>"
               . "</br>start：<input type='text' size='12' name='start' value='0'>"
               . "</br>num：<input type='text' size='12' name='num' value='10000'>"
               . "&nbsp;&nbsp;<input type='submit' value='run'></form>";
        echo $str;
        exit;
    }

    //各渠道、平台 充值统计
    public function chargeTotalAction() {
        header("Content-Type: text/html; charset=utf-8");
        $act = $this->request->getPost('act');
        if ($act) {
            try {
                $startDate = $this->request->getPost('startDate');
                $endDate = $this->request->getPost('endDate');
                !$endDate && $endDate = time();

                $result = array();
                $startTime = strtotime($startDate);
                $endTime = strtotime($endDate);

                $conn = $this->di->get('db');


                if ($act == 2) {//查询各平台的充值
                    //充值明细
                    $payType = $this->config->payType->toArray();
                    $finalpay = 0;
                    $platpays = array();
                    foreach ($payType as $v) {
                        $paysql = "select totalFee,payTime"
                                . " from pre_order"
                                . " where status=1 and payType=" . $v['id'] . " and payTime>=" . $startTime . " and payTime<" . $endTime;
                        $payres = $conn->fetchAll($paysql);

                        //总充值金额
                        $paytotalsql = "select sum(totalFee) as total "
                                . " from pre_order"
                                . " where status=1 and payType=" . $v['id'] . " and payTime>=" . $startTime . " and payTime<" . $endTime;
                        $paytotalres = $conn->fetchOne($paytotalsql);
                        $paytotal = $paytotalres['total'];
                        $paytotal = $paytotal ? $paytotal : 0;
                        $list['sheetName'] = $v['name'];
                        $list['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
                        $list['list'][] = array('', '');
                        $list['list'][] = array('总额', $paytotal);
                        $list['list'][] = array('', '');
                        $list['list'][] = array('时间', '充值金额');
                        foreach ($payres as $val) {
                            $data[] = date('Y-m-d H:i:s', $val['payTime']);
                            $data[] = $val['totalFee'];
                            $list['list'][] = $data;
                            unset($data);
                        }
                        $finalpay+=$paytotal;
                        $platpay['name'] = $v['name'];
                        $platpay['total'] = $paytotal;
                        $platpays[] = $platpay;

                        $result[] = $list;
                        unset($list);
                        unset($platpay);
                    }

                    $result0['sheetName'] = '总数据';
                    $result0['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
                    $result0['list'][] = array('', '');
                    $result0['list'][] = array('总额', $finalpay);
                    $result0['list'][] = array('', '');
                    foreach ($platpays as $val) {
                        $result0['list'][] = array($val['name'], $val['total']);
                    }
                    $result1[] = $result0;
                    $excelResult = array_merge($result1, $result);

                    //生成excel
                    $fileName = '平台充值数据_' . $startDate . ' -- ' . $endDate; //excel文件名
                    $normalLib = $this->di->get('normalLib');
                    $normalLib->toExcel($fileName, $excelResult);
                    exit;
                }


                //查询渠道
                $typesql = "select parentType,subType from pre_register_log group by parentType, subType";
                $typeres = $conn->fetchAll($typesql);
                $finalpay = 0;
                $platpays = array();
                foreach ($typeres as $v) {
                    //充值明细
                    $chargesql = "select o.totalFee,l.parentType,o.payTime "
                            . "from pre_order o "
                            . "left join pre_register_log l on o.uid=l.uid "
                            . "where o.status=1 and o.payType<1000 and o.payTime>=" . $startTime . " and o.payTime<" . $endTime;
                    if (!$v['parentType']) {
                        $chargesql.= " and (l.parentType is null or l.parentType='')";
                    } else {
                        $chargesql.= " and l.parentType='{$v['parentType']}'";
                        if ($v['subType']) {
                            $chargesql.= " and l.subType='{$v['subType']}'";
                        }
                    }
                    $chargeres = $conn->fetchAll($chargesql);

                    //总充值金额
                    $totalsql = "select sum(o.totalFee) as total "
                            . "from pre_order o "
                            . "left join pre_register_log l on o.uid=l.uid "
                            . "where o.status=1 and o.payType<1000 and o.payTime>=" . $startTime . " and o.payTime<" . $endTime;
                    if (!$v['parentType']) {
                        $totalsql.= " and (l.parentType is null or l.parentType='')";
                    } else {
                        $totalsql.= " and l.parentType='{$v['parentType']}'";
                        if ($v['subType']) {
                            $totalsql.= " and l.subType='{$v['subType']}'";
                        }
                    }
                    $totalres = $conn->fetchOne($totalsql);
                    $total = $totalres['total'];

                    $name = $v['parentType'] ? $v['parentType'] : "91ns";
                    $subName = $v['subType'] ? $v['subType'] . "渠道" : "渠道";
                    $total = $total ? $total : 0;

                    $list['sheetName'] = $name.'-'.$subName;
                    $list['list'][] = array('查询时间区间：', $startDate . '-' . $endDate);
                    $list['list'][] = array('', '');
                    $list['list'][] = array('总额', $total);
                    $list['list'][] = array('', '');
                    $list['list'][] = array('时间', '充值金额');
                    foreach ($chargeres as $val) {
                        $data[] = date('Y-m-d H:i:s', $val['payTime']);
                        $data[] = $val['totalFee'];
                        $list['list'][] = $data;
                        unset($data);
                    }

                    $finalpay+=$total;
                    $platpay['name'] = $name.'-'.$subName;
                    $platpay['total'] = $total;
                    $platpays[] = $platpay;

                    $result[] = $list;
                    unset($list);
                    unset($platpay);
                }

                $result0['sheetName'] = '总数据';
                $result0['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
                $result0['list'][] = array('', '');
                $result0['list'][] = array('总额', $finalpay);
                $result0['list'][] = array('', '');
                foreach ($platpays as $val) {
                    $data = array($val['name'], $val['total']);
                    $result0['list'][] = $data;
                }
                $result1[] = $result0;
                $excelResult = array_merge($result1, $result);


                //生成excel
                $fileName = '渠道充值数据_' . $startDate . ' -- ' . $endDate; //excel文件名
                $normalLib = $this->di->get('normalLib');
                $normalLib->toExcel($fileName, $excelResult);
                exit;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $date = date("Y-m-d");
        $startDate = date('Y-m-01', strtotime($date));
        $endDate = date('Y-m-d', strtotime("$startDate +1 month -1 day"));

        $str = "</br>充值记录数据导出（渠道（网吧，棋牌迷...））：</br></br><form action='' method='post'>"
                . "时间:<input size=10 value='{$startDate}' name='startDate'>-<input size=10 value='{$endDate}' name='endDate'>";
        $str.="<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='导出excel'></form>";
        echo $str;

        $str1 = "</br>充值记录数据导出（平台（支付宝，微信支付...））：</br></br><form action='' method='post'>"
                . "时间:<input size=10 value='{$startDate}' name='startDate'>-<input size=10 value='{$endDate}' name='endDate'>";
        $str1.="<input type='hidden' name='act' value='2'>"
                . "&nbsp;&nbsp;<input type='submit' value='导出excel'></form>";
        echo $str1;
        exit;
    }

    public function getRoomAdminAction() {
        header("Content-Type: text/html; charset=utf-8");
        $act = $this->request->getPost('act');
        if ($act) {
            try {
                // 获取房间列表
                $phql = "SELECT ui.uid,r.roomId,ui.nickName,up.level2".
                " FROM \Micro\Models\Rooms r ".
                " LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid = r.uid ".
                " LEFT JOIN \Micro\Models\UserProfiles up ON up.uid = r.uid";

                $query = $this->modelsManager->createQuery($phql);
                $rooms = $query->execute();

                $roomList = array();
                if ($rooms->valid()) {
                    foreach ($rooms as $room) {
                        $roomData['uid'] = $room->uid;
                        $roomData['roomId'] = $room->roomId;
                        $roomData['nickName'] = $room->nickName;
                        $roomData['anchorLevel'] = $room->level2;
                        $roomData['userList'] = array();
                        array_push($roomList, $roomData);
                    }
                }

                // 获取每个房间的管理员的个数
                foreach ($roomList as $key=>$roomInfo) {
                    $roomId = $roomInfo['roomId'];
                    $phql1 = "SELECT uid FROM \Micro\Models\RoomUserStatus WHERE roomId=".$roomId." AND level>1";
                    $query1 = $this->modelsManager->createQuery($phql1);
                    $roomUsers = $query1->execute();

                    $roomUserList = array();
                    if ($roomUsers->valid()) {
                        foreach ($roomUsers as $roomUser) {
                            if ($roomUser->uid == $roomInfo['uid']) {
                                continue;
                            }
                            $data=array();
                            $data['adminUid'] = $roomUser->uid;
                            $phql2 = "SELECT u.updateTime, ui.nickName".
                            " FROM \Micro\Models\Users u".
                            " LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid=u.uid".
                            " WHERE u.uid=".$roomUser->uid;
                            $query2 = $this->modelsManager->createQuery($phql2);
                            $adminDatas = $query2->execute();
                            if ($adminDatas->valid()) {
                                /*$records = $adminDatas->toArray();
                                //$channelSum2 = floatval($records11[0]['total']);
                                $data['lastUpdateTime'] = date('Y-m-d H:i:s',$records[0]['updateTime']);
                                $data['nickName'] = $records[0]['nickName'];*/
                                foreach ($adminDatas as $adminData) {
                                    $data['lastUpdateTime'] = date('Y-m-d H:i:s',$adminData->updateTime);
                                    $data['nickName'] = $adminData->nickName;
                                }
                            }

                            array_push($roomUserList, $data);
                        }
                    }
                    //$roomInfo['userList'] = $roomUserList;
                    $roomList[$key]['userList'] = $roomUserList;
                }

                //$result = json_encode($roomList, JSON_UNESCAPED_UNICODE);
                //echo $result;die;

                $result = array();
                $result['sheetName'] = '总数据';
                $result['list'][] = array('主播Id', '主播房间号', '主播昵称', '主播等级', '管理员个数', '管理员Id', '管理员昵称', '管理员最后登录时间');
                foreach ($roomList as $roomListData) {
                    $list = array();
                    $uid = $roomListData['uid'];
                    $roomId = $roomListData['roomId'];
                    $nickName = $roomListData['nickName'];
                    $anchorLevel = $roomListData['anchorLevel'];
                    $adminCount = count($roomListData['userList']);
                    $list[] = $uid;
                    $list[] = $roomId;
                    $list[] = $nickName;
                    $list[] = $anchorLevel;
                    $list[] = $adminCount;
                    $result['list'][] = $list;
                    unset($list);

                    if ($adminCount > 0) {
                        foreach($roomListData['userList'] as $roomListUserData) {
                            $list[] = '';
                            $list[] = '';
                            $list[] = '';
                            $list[] = '';
                            $list[] = '';
                            $adminUid = $roomListUserData['adminUid'];
                            $adminNickName = '';
                            $lastUpdateTime = '';
                            if (isset($roomListUserData['nickName'])) {
                                $adminNickName = $roomListUserData['nickName'];
                            }
                            if (isset($roomListUserData['lastUpdateTime'])) {
                                $lastUpdateTime = $roomListUserData['lastUpdateTime'];
                            }
                            $list[] = $adminUid;
                            $list[] = $adminNickName;
                            $list[] = $lastUpdateTime;

                            $result['list'][] = $list;
                            unset($list);
                        }
                    }
                }

                //$excelResult = array_merge($result1, $result);
                 $excelResult[] = $result;
                 //var_dump($excelResult);die;

                //生成excel
                $fileName = '管理员数据'; //excel文件名
                $normalLib = $this->di->get('normalLib');
                $normalLib->toExcel($fileName, $excelResult);
                exit;
            }
            catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $str = "</br>获取房间管理员信息</br></br><form action='' method='post'>";
        $str.="<input type='hidden' name='act' value='1'>"
                . "&nbsp;&nbsp;<input type='submit' value='导出excel'></form>";
        echo $str;
        exit;
    }
    
    public function addWineAction(){
        header("Content-Type: text/html; charset=utf-8");
        if ($this->request->isPost()) {
            $price = $this->request->getPost('price');
            $result = $this->invMgr->addWine($price);
            echo '结果：' . json_encode($result);
            exit;
        }
        $str = "添加一元嗨的酒水商品</br><form action='' method='post'>"
                . "酒水价格（price）：<input type='text' name='price' value=''></br>"
                . "&nbsp;&nbsp;<input type='submit' value='添加酒水'></form>";
        echo $str;
        exit;
    }
}
