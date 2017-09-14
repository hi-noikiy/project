<?php
/**
 * 统计
 */
//日志路径
define('LOG_PATH', A_ROOT . 'logs');
class Analysis {

    protected $_souce_db;
    protected $_sum_db;

    const ConsumptionMarket = 1;
    const ConsumptionBehavior = 2;

    public $timestamp;
    public $bt;
    public $gameid;

    /**
     * @param PDO $souceDb 数据源pdo实例
     * @param PDO $sumDb   统计存档数据库pdo实例
     * @param null $bt      统计日期（格式2014-10-10)
     * @param int $gameId   游戏ID
     */
    public function __construct(PDO $souceDb,PDO $sumDb, $bt=null, $gameId=5 )
    {
        $this->_souce_db = $souceDb;
        $this->_sum_db   = $sumDb;
        if (is_null($bt)) {
            $this->timestamp = strtotime('-1 days');
        }
        else {
            $this->timestamp = strtotime($bt);
        }
        $this->bt = date('Ymd', $this->timestamp);
        $this->gameid = $gameId;
    }


    /**
     * 在线统计，统计前一天的最高在线、平均在线（总在线/24小时）、总在线
     *
     * @param runtime $runtime
     * @return bool
     */
    public function SumOnline(runtime $runtime)
    {
        $runtime->start();

        $btl = date('ymd0000', $this->timestamp);
        $etl = date('ymd2359', $this->timestamp);
//        fenbaoid,
        $sql = <<<SQL
      SELECT COUNT(*) as cnt, serverid, SUM(`online`) AS sum_online,
      MAX(`MaxOnline`) AS maxOnline,
      MAX(`WorldMaxOnline`) AS worldMaxOnline FROM `online`
      WHERE `gameid`=? AND daytime>? AND daytime<=?
      GROUP BY serverid
      ORDER BY NULL
SQL;
//        echo $sql;
//        exit;
        $q = $this->_souce_db->prepare($sql);
        $q->execute(array($this->gameid, $btl, $etl));
        $lists = $q->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach($lists as $list) {
            $data[] = array(
                'serverid'           => $list['serverid'],
                'gameid'             => $this->gameid,
                'sum_online'         => $list['sum_online'],
                'sum_maxOnline'      => $list['maxOnline'],
                'sum_worldOnline'    => 0,
                'sum_worldMaxOnline' => $list['worldMaxOnline'],
                'avg_online'         => round($list['sum_online']/$list['cnt'], 2),
                'avg_maxOnline'      => round($list['maxOnline']/$list['cnt'], 2),
                'avg_worldOnline'    => 0,
                'avg_worldMaxOnline' => round($list['worldMaxOnline']/$list['cnt'], 2),
                'sday'               => $this->bt,
            );
        }
//        print_r($data);
//        exit;
        $rowCount = $this->Insert($data, 'sum_online');
        $runtime->stop();
        $timeused = $runtime->spent();
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s')
                . '|OK|Insert Into sum_online|rowCount='.$rowCount
                . "|Time Used {$timeused} mics". PHP_EOL;
            writeLog('OK|Insert Into sum_online|rowCount='.$rowCount
                . "|Time Used {$timeused} mics", LOG_PATH.'/db_insert_sum_online.log');
        }
        else {
            echo date('Y-m-d H:i:s')
                . '|FAIL|Insert Into sum_online|MSG='.$rowCount . PHP_EOL;
            writeLog('|FAIL|Insert Into sum_online|MSG='.$rowCount,
                LOG_PATH.'/db_insert_sum_online.log');
        }
    }



    /**
     *
     * 统计玩家在线时长
     * (每天新增注册的在线数据),online单位是秒。
     *
     */
    public function SumPlayOnline()
    {
        //今天注册的玩家
        $sql_new = "SELECT accountid FROM newmac WHERE createtime>=? AND createtime<=?";
        $dbsmt = $this->_souce_db->prepare($sql_new);
        $dbsmt->execute(array(date('ymd0000', $this->timestamp),
            date('ymd2359', $this->timestamp)));
        $accountidsArr = $dbsmt->fetchAll(PDO::FETCH_COLUMN);
        //echo count($accountidsArr).PHP_EOL;
        if (!$accountidsArr) {
            writeLog('OK|Theres is NO New Player Exists.', LOG_PATH.'/db_sum_playeronline.log');
            return false;
        }
        //TODO:viplev是否为付费玩家->修正：total_rmb大于0为付费玩家
        $accountids = implode(',', $accountidsArr);
        // AND daytime>=? and daytime<=?
        $sql_q = <<<SQL
SELECT serverid,fenbaoid,online,total_rmb FROM dayonline
WHERE accountid IN($accountids) and  daytime=?  ORDER BY NULL
SQL;
//        echo $sql_q;exit;
        $dbsmt_4 = $this->_souce_db->prepare($sql_q);
        $dbsmt_4->execute(array(date('1ymd', $this->timestamp)));
        $lists = $dbsmt_4->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        $players = array();

        //$sum_players = 0;

        $time_diff = array(0, 4, 10, 20, 30, 40, 50,
                            60, 70, 80, 90,100,110,
                            120,240, 300, 360, 420, 480 );
        //时间等级
        $lvl_list = array(
            '0-4','5-10', '11-20', '21-30',
            '31-40',  '41-50', '51-60',
            '61-70', '71-80','81-90',
            '91-100','101-110','111-120',
            '121-240','241-300','301-360',
            '361-420', '421-480', '>=481',
        );
        //$lvl = halfSearch($time_diff, 34);
        $length = count($time_diff);
        //$total = 0;
        foreach($lists as $list) {
            //$total += 1;
            //$sum_players += 1;
            //二分法查找出在线时间所属等级
            //online单位是秒,需要除以60转换为分钟
            $online_mins = ceil($list['online']/60);
            if ($online_mins>480) {
                $lvl = $length-1;
            }
            elseif ($online_mins==0) {
                $lvl = 0;
            }
            else {
                $lvl = halfSearch($time_diff, $online_mins);
            }
//            echo $lvl . '-----'.$online_mins . '-----------'.PHP_EOL;
            $key = $list['serverid'].'_'.$list['fenbaoid'].'_'.$lvl;
            if($list['total_rmb']>0) {
                $players[$key]['rmb'] += 1;
            }
            else {
                $players[$key]['notrmb'] += 1;
            }
        }
        //echo $total;
        //print_r($players);
        //exit;
        $daytime = $_SERVER['REQUEST_TIME'];
        foreach($players as $keys=>$player) {
            list($serverid, $fenbaoid, $lv) = explode('_', $keys);
            $data[] = array(
                'serverid'          => $serverid,
                'fenbaoid'          => $fenbaoid,
                'rmb'               => isset($player['rmb']) ? $player['rmb'] : 0,
                'not_rmb'           => $player['notrmb'],
                'player'            => $player['rmb'] + $player['notrmb'],
                'online_lvl'        => $lv,
                'online_lvl_txt'    => $lvl_list[$lv],
                'sday'              => $this->bt,
                'daytime'           => $daytime,
            );
        }
        if (count($data)) {
            $insert_values = array();
            foreach($data as $d){
                $question_marks[] = '('  . placeholders('?', sizeof($d)) . ')';
                $insert_values = array_merge($insert_values, array_values($d));
            }
            $sql = "INSERT INTO sum_playeronline (" .
                implode(",", array_keys($data[0]) ) .
                ") VALUES " . implode(',', $question_marks) .
                "ON DUPLICATE KEY UPDATE `sday`=VALUES(`sday`),".
                "`serverid`=VALUES(`serverid`),`fenbaoid`=VALUES(`fenbaoid`),".
                "`online_lvl`=VALUES(`online_lvl`)";
            $stmt = $this->_sum_db->prepare ($sql);
            try {
                $stmt->execute($insert_values);
                echo "rows count:" . $stmt->rowCount() . PHP_EOL;
            } catch (PDOException $e){
                echo $e->getMessage();
            }
        }
        else {
            echo "No Data.\n";
        }

    }

    /**
     * 元宝消耗统计
     */
    public function SumRmbUsed()
    {
       // $date = date('ymd', strtotime('-1 days', $this->timestamp));

        $bt     = date('ymd0000', $this->timestamp);
        $et     = date('ymd2359', $this->timestamp);
        //$sday   = date('Ymd', $this->timestamp);
        $sday   = date('1ymd', $this->timestamp);//数据里面存的格式不一样
        $data   = array();
        //元宝消耗
        $sql_used = <<<SQL
    SELECT SUM(emoney) AS sum_used,serverid,COUNT(DISTINCT accountid) AS cnt FROM rmb
    WHERE daytime>=? AND daytime<=?
    GROUP BY serverid ORDER BY NULL
SQL;
        //echo $sql_used;
//        print_r(array($this->gameid,  $bt, $et));
        $stmt = $this->_souce_db->prepare($sql_used);
        $stmt->execute(array( $bt, $et));
        $money_used = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($money_used as $m) {
            $data[$m['serverid']]['used'] = $m['sum_used'];
            $data[$m['serverid']]['cnt']  = $m['cnt'];
        }
//        print_r($money_used);
//        exit;
        //当前系统元宝剩余
        $sql = "SELECT emoney,serverid FROM total_emoney WHERE daytime={$sday}";
        //echo $sql;
        $stmt = $this->_souce_db->prepare($sql);
        $stmt->execute();
        $moneyLeft = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($moneyLeft as $m) {
            $data[$m['serverid']]['rmb_left'] = $m['emoney'];
        }

        //系统赠送
        $sql = "SELECT SUM(emoney) AS emoney,serverid FROM give_emoney"
            ." WHERE daytime=$sday GROUP BY serverid ORDER BY NULL";
        $stmt = $this->_souce_db->prepare($sql);
        $stmt->execute();
        $moneyGiven = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($moneyGiven as $m) {
            $data[$m['serverid']]['given'] = $m['emoney'];
        }
        //删除5天前的数据
        $sqlDelete5daysAgo = "DELETE FROM give_emoney WHERE daytime<";
//        print_r($data);
//        exit;
        //充值产出
        $sql_recharge = "SELECT SUM(emoney) AS emoney,serverid FROM rmb_emoney"
                    . " WHERE daytime=$sday GROUP BY serverid ORDER BY NULL";
        echo '$sql_recharge=',$sql_recharge;
        $stmt = $this->_souce_db->prepare($sql_recharge);
        $stmt->execute();
        $moneyPay = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($moneyPay as $m) {
            $data[$m['serverid']]['payed'] = $m['emoney'];
        }
//        print_r($data);
//        exit;
        $now  = date('Y-m-d H:i:s');
        $sday = date('Ymd', $this->timestamp);
        foreach ($data as $key=>$d) {
            $data_insert[] = array(
                'serverid'  => $key,
                'sday'      => $sday,
                'daytime'   => $now,
                'rmb_sum'   => $d['given']+$d['payed']+$d['rmb_left'],
                'rmb_pay'   => $d['payed'] ? $d['payed'] :0,
                'rmb_sys'   => $d['given']? $d['given'] :0,
                'rmb_used'  => $d['used'] ? $d['used'] :0,
                'cnt'       => $d['cnt'] ? $d['cnt'] :0,
                'rmb_left'  => $d['rmb_left'] ? $d['rmb_left'] :0,
            );
        }
//        print_r($data_insert);exit;
        $rowCount = $this->Insert($data_insert, 'sum_rmbused');
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_rmbused|rowCount='.$rowCount . PHP_EOL;
            writeLog('OK|Insert Into sum_rmbused|rowCount='.$rowCount, LOG_PATH.'/db_insert.log');
        }
        else {
            echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_rmbused|MSG='.$rowCount . PHP_EOL;
            writeLog('FAIL|Insert Into sum_rmbused|MSG='.$rowCount, LOG_PATH.'/db_insert.log');
        }
    }


    /**
     * 商城消费&消费行为（按天统计）
     * rmb表
     * @param int $sum_type 统计类型
     * @param runtime $runtime
     */
    public function SumConsumption($sum_type, runtime $runtime)
    {
        $runtime->start();
        $bt = date('ymd0000', $this->timestamp);
        $et = date('ymd2359', $this->timestamp);

        //echo $bt . '==============' . $et . PHP_EOL;
        //商城消费
        if ($sum_type === self::ConsumptionMarket) {
            $table_name = 'sum_market_pay';
            $where = "WHERE type=10 AND daytime>=$bt and daytime<$et";
            $sql = <<<SQL
        SELECT SUM(emoney) AS sum_emoney,itemtype,serverid,fenbaoid
        FROM `rmb` {$where}
        GROUP BY itemtype,serverid,fenbaoid
SQL;
        }
        //消费行为
        elseif ($sum_type === self::ConsumptionBehavior) {
            $table_name = 'sum_behavior_pay';
            $where = "WHERE daytime>=$bt and daytime<$et";
            $sql = <<<SQL
        SELECT SUM(emoney) AS sum_emoney,`type` as stype,serverid,fenbaoid
        FROM `rmb`
        {$where}
        GROUP BY `type`,serverid,fenbaoid
SQL;
        }
        //计算总的元宝数
        $sql_sum = "SELECT SUM(emoney) FROM `rmb` $where";
        //$sql_sum = str_replace(':replace_me', $inQuery, $sql_sum);
        $qs = $this->_souce_db->prepare($sql_sum);
        $qs->execute();
        $sum = $qs->fetchAll(PDO::FETCH_COLUMN);
//        print_r($sum);exit;
        $q = $this->_souce_db->prepare($sql);
        $q->execute();
        $lists = $q->fetchAll(PDO::FETCH_ASSOC);
        //print_r($lists);
        //exit;
        foreach ($lists as $key=>$list) {
            $lists[$key]['sday'] = $this->bt;
            $lists[$key]['ratio'] = round($list['sum_emoney'] / $sum[0],4);
        }
        //print_r($lists);exit;
        if (!count($lists)) {
            echo date('Y-m-d H:i:s')
                . '|OK|No data to insert|'. PHP_EOL;
            return flase;
        }

        $rowCount = $this->Insert($lists, $table_name);
        $runtime->stop();
        $timeused = $runtime->spent();
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s')
                . '|OK|Insert Into sum_market_pay|rowCount='.$rowCount
                . "|Time Used {$timeused} mics". PHP_EOL;
            writeLog('OK|Insert Into sum_market_pay|rowCount='.$rowCount
                . "|Time Used {$timeused} mics", LOG_PATH.'/sum_market_pay.log');
        }
        else {
            echo date('Y-m-d H:i:s')
                . '|FAIL|Insert Into sum_market_pay|MSG='.$rowCount . PHP_EOL;
            writeLog('FAIL|Insert Into sum_market_pay|MSG='.$rowCount,
                LOG_PATH.'/sum_market_pay_fail.log');
        }
    }

    /**
     * 统计系统赠送的元宝
     */
    public function SumGiveEmoney()
    {
        $daytime = date('1ymd', $this->timestamp);
        $sday = date('Ymd', $this->timestamp);
        $getTodayTotalTypes = "SELECT type FROM `give_emoney` WHERE daytime=$daytime GROUP BY `type";
        $stmt = $this->_souce_db->prepare($getTodayTotalTypes);
        $stmt->execute();
        $types = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $sql = <<<SQL
        SELECT COUNT(DISTINCT idUser) AS cnt,SUM(emoney) AS sum_emoney,`type` as stype,serverid,fenbaoid,
        $sday as sday FROM `give_emoney`
        WHERE daytime=? and `type`=?
        GROUP BY serverid,fenbaoid
SQL;
        $rowCount = 0;
        foreach ($types as $type) {
            $q = $this->_souce_db->prepare($sql);
            $q->execute(array($daytime, $type));
            $lists = $q->fetchAll(PDO::FETCH_ASSOC);
            if (!count($lists)) {
                continue;
            }
            $result = $this->Insert($lists, 'sum_give_emoney');
            if(is_numeric($result) && $result>0) {
                $rowCount += $result;
            }
            else {
                writeLog('FAIL|Insert Into sum_market_pay|MSG='.$result,
                    LOG_PATH.'/sum_give_emoney_fail.log');
            }
        }
        writeLog('OK|Insert Into sum_give_emoney|rowCount='.$rowCount,
            LOG_PATH.'/sum_give_emoney.log');


    }
    /**
     * 注册转化
     *
     * @param runtime $runtime
     * @return bool
     */
    public function SumRegTrans( runtime $runtime )
    {
        $runtime->start();
        $time_query_begin = date('ymd0000', $this->timestamp);
        $time_query_end   = date('ymd2359', $this->timestamp);
        //TODO::获取时间段内的新注册的所有账户ID
        $sqlNewAccount = "SELECT accountid FROM newmac"
            . " WHERE gameid=? AND createtime>=? AND createtime<=?";
        //echo $sqlNewAccount;
        $q = $this->_souce_db->prepare($sqlNewAccount);
        $q->execute(array($this->gameid, $time_query_begin, $time_query_end));
        $newNewArrountArr = $q->fetchAll(PDO::FETCH_COLUMN);
//        echo count($newNewArrountArr);exit;
        //print_r($newNewArrountArr);
        if (!count($newNewArrountArr)) {
            writeLog('OK|No Data To Insert(没有新用户)',
                LOG_PATH.'/sum_reg_trans.log');
            return false;
        }
//        print_r($newNewArrountArr);
        $newNewArrountStr = implode(',', $newNewArrountArr);
//        echo $newNewArrountStr;exit;
        //TODO::按区服、渠道统计注册数量
        $sqlNewMac = <<<SQL
        SELECT count(*) as cnt, n.serverid,n.fenbaoid
        FROM newmac n
        WHERE n.gameid=?  AND n.createtime>=? AND n.createtime<=?
        GROUP BY n.serverid, n.fenbaoid
SQL;
//        echo $sqlNewMac;
        //TODO::新注册的玩家
        $q = $this->_souce_db->prepare($sqlNewMac);
        $q->execute(array($this->gameid, $time_query_begin, $time_query_end));
        $newMacArr = $q->fetchAll(PDO::FETCH_ASSOC);
        $formatNew = array();
        $cnt = 0;
        foreach ($newMacArr as $new) {
            $sf = $new['serverid'].'_'.$new['fenbaoid'];
            $cnt += $new['cnt'];
            $formatNew[$sf] = $new['cnt'];
        }

        //TODO::统计创建了角色的玩家,一个玩家可以创建若干个角色
       $sqlPlayer = <<<SQL
       SELECT count(*) as sum_cre, p.prof, p.serverid, p.fenbao as fenbaoid
       FROM player AS p
       WHERE p.accountid IN($newNewArrountStr)
       GROUP BY p.serverid, p.fenbao,p.prof
SQL;
//        echo $sqlPlayer;exit;
        $q2 = $this->_souce_db->prepare($sqlPlayer);
        $q2->execute();
        $data = $q2->fetchAll(PDO::FETCH_ASSOC);
        if (!count($data)) {
            return;
        }
//        print_r($data);exit;
//        var_export($formatNew);exit;
        $players = array();
        $strValues = '';
//        $sum_cre = 0;
        foreach ($data as $k=>$d) {
            $sf = $d['serverid'].'_'.$d['fenbaoid'];
            $players[$sf][$d['prof']] += $d['sum_cre'];
        }
        foreach ($players as $sf=>$profs) {
            list($serverid, $fenbaoid)  =explode('_', $sf);
            $sum_new = isset($formatNew[$sf]) ? $formatNew[$sf] : 0;
            foreach ($profs as $prof=>$sum_cre) {
                $strValues .= "({$serverid}, {$fenbaoid},5,"
                    ."{$this->bt},{$time_query_end},{$prof},{$sum_new},{$sum_cre}),";
            }
        }
        $strValues = rtrim($strValues, ',');
        $sql = <<<SQL
    INSERT INTO sum_reg_trans(`serverid`, `fenbaoid`, `gameid`, `sday`,`stime`,`prof`,`sum_new`, `sum_cre`)
    VALUES $strValues
SQL;
//        echo $sql;
//        ON DUPLICATE KEY UPDATE `sum_new`=VALUES(`sum_new`),`sum_new`=VALUES(`sum_cre`)
//        exit;
        try {
            $rowCount = $this->_sum_db->exec($sql);
            $runtime->stop();
            $timeused = $runtime->spent();
            if($rowCount===false) {
                echo date('Y-m-d H:i:s')
                    . '|OK|Insert Into sum_reg_trans|rowCount='.$rowCount
                    . "|Time Used {$timeused} mics". PHP_EOL;
                writeLog('OK|Insert Into sum_reg_trans|rowCount='.$rowCount
                    . "|Time Used {$timeused} mics", LOG_PATH.'/sum_reg_trans.log');
            }
            else {
                echo date('Y-m-d H:i:s')
                    . '|OK|Insert Into sum_reg_trans|rowCount='.$rowCount
                    . "|Time Used {$timeused} mics". PHP_EOL;
                writeLog('OK|Insert Into sum_reg_trans|rowCount='.$rowCount
                    . "|Time Used {$timeused} mics", LOG_PATH.'/sum_reg_trans.log');
            }
        } catch (PDOException $e) {
            writeLog('Fail|Insert Into sum_reg_trans|MSG='. $e->getMessage()
                . "|sql={$sql}", LOG_PATH.'/sum_reg_trans.log');
        }

    }



    public function Insert($data, $table)
    {
//        print_r($data);exit;
        $insert_values = array();
        foreach($data as $d){
            $question_marks[] = '('  . placeholders('?', sizeof($d)) . ')';
            $insert_values = array_merge($insert_values, array_values($d));
        }
        $sql = "INSERT INTO `{$table}` (" . implode(",", array_keys($data[0]) )
            . ") VALUES " . implode(',', $question_marks);
//        echo $sql;
//        exit;
        $stmt = $this->_sum_db->prepare ($sql);
        try {
            $stmt->execute($insert_values);
            return $stmt->rowCount();
        } catch (PDOException $e){
            return $e->getMessage();
        }

    }
//    public function __destruct()
//    {
//        $this->_souce_db = null;
//        $this->_sum_db = null;
//    }
}