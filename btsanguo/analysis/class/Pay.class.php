<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-10
 * Time: 下午3:40
 */

class Pay extends Base{
    /**
     * 支付统计
     * TODO:付费用户数(在当前时间段有提交过订单的用户数)
     */
    public function run()
    {

        //$payNOPAll = 0;//付费总人数
        //$payNOPNew = 0;//新增付费人数
        //$payCnt    = 0;//付费次数
        //$incomeCnt = 0;//付费总金额
        $insertData = array();
        echo '------cnt_pay------' . PHP_EOL;
        $startPayTime = date('Y-m-d 00:00:00', $this->timestamp);
        $endPayTime   = date('Y-m-d 23:59:59', $this->timestamp);

        //TODO:新增充值人数(首次充值)，充值金额
        //payemoney记录的是元宝数，转换成rmb需要除以10.
        $sqlFirst = <<<SQL
    SELECT COUNT(accountid) as cnt,SUM(payemoney) AS payemoney,serverid,fenbaoid
    FROM first_rmb
    WHERE daytime>=? AND daytime<=?
    GROUP BY serverid,fenbaoid
SQL;
        $stmtFirst = $this->_souce_db->prepare($sqlFirst);
        $timeArray = array(
            date('ymd0000',$this->timestamp ),
            date('ymd2359', $this->timestamp)
        );

        $stmtFirst->execute($timeArray);
        $FirstRmb = $stmtFirst->fetchAll(PDO::FETCH_ASSOC);
        if (count($FirstRmb)) {
            $strinsert = '';
            foreach ($FirstRmb as $rmb) {
                $rmb_money = $rmb['payemoney']/10;
                $strinsert .= "({$this->sday},{$rmb['serverid']},{$this->gameid},{$rmb['fenbaoid']},"
                    ."{$rmb['cnt']},{$rmb_money}),";
//            $key = $rmb['serverid'].'_'.$rmb['fenbaoid'];
//            $insertData[$key]['paynopnew'] = $rmb['cnt'];//首次充值人数
//            $insertData[$key]['paynopnew_money'] = $rmb['payemoney'];//首次充值金额
            }
            $strinsert = rtrim($strinsert, ',');
            $sql_first_rmb = <<<SQL
    INSERT INTO sum_pay_daily(sday,serverid,gameid,fenbaoid,paynopnew,paynopnew_money)
    VALUES $strinsert ON DUPLICATE KEY UPDATE `paynopnew`=VALUES(`paynopnew`),
    `paynopnew_money`=VALUES(`paynopnew_money`)
SQL;
//            echo $sql_first_rmb;
            $rowCount = $this->_sum_db->exec($sql_first_rmb);
            if($rowCount===false) {
                echo date('Y-m-d H:i:s')
                    . '|FAIL|Insert Into sum_pay_daily(First RMB)|msg='
                    .$rowCount .'date=' .$this->sday. PHP_EOL;
                writeLog('FAIL|Insert Into sum_player_lost(First RMB)|msg='.
                    $rowCount.'date='
                    .$this->sday, LOG_PATH.'/sum_pay_daily.log');
            }
            else {
                writeLog('OK|Insert Into sum_pay_daily(First RMB)|rowCount='
                    .$rowCount.'date='
                    .$this->sday, LOG_PATH.'/sum_pay_daily.log');
            }
        }

//        return false;
        //TODO::充值次数,充值人数,充值总金额
        $sqlPayCnt = <<<SQL
    SELECT COUNT(id) AS cnt,COUNT(DISTINCT(PayID)) as paynopall,
    SUM(PayMoney) as sum_money,
    ServerID AS serverid, dwFenBaoID AS fenbaoid
    FROM pay_log WHERE rpCode in ('1','10')
    AND Add_Time >=? AND Add_Time <=?
    GROUP BY serverid,fenbaoid,CAST(add_Time AS date)
    ORDER BY NULL
SQL;

//        echo $sqlPayCnt;exit;
        if (defined('TW')) {
            $dbPay = db('pay_237');
        }
        else {
            $dbPay = $this->_sum_db;
        }
        $stmt = $dbPay->prepare($sqlPayCnt);
        $stmt->execute(array($startPayTime, $endPayTime));
        $payCntArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $strinsert = '';
        $money = array();
        foreach ($payCntArr as $rmb) {
            $fenbao = $rmb['fenbaoid']> 0 ? $rmb['fenbaoid'] : 0;
            $money[$rmb['serverid']][$fenbao] += $rmb['sum_money'];//处理fenbaoid为空或为0时的bug
            $arpu      = round($rmb['sum_money'] / $rmb['paynopall'], 2);//充值arpu:总收入/付费用户数
            $strinsert .= "({$this->sday},{$rmb['serverid']},{$this->gameid},{$fenbao},"
                ."{$rmb['paynopall']},{$rmb['cnt']},{$money[$rmb['serverid']][$fenbao]},{$arpu}),";
        }
        $strinsert = rtrim($strinsert, ',');
        $sql_total_rmb = <<<SQL
    INSERT INTO sum_pay_daily(sday,serverid,gameid,fenbaoid,paynopall,paycnt,income,arpu)
    VALUES $strinsert ON DUPLICATE KEY UPDATE `paynopall`=VALUES(`paynopall`),
    `paycnt`=VALUES(`paycnt`),`income`=VALUES(`income`),`arpu`=VALUES(`arpu`)
SQL;
        //echo $sql_total_rmb . PHP_EOL;
        $rowCount = $this->_sum_db->exec($sql_total_rmb);
        if($rowCount===false) {
            echo date('Y-m-d H:i:s')
                . '|FAIL|Insert Into sum_pay_daily(Total RMB)|msg='
                .$rowCount .'date=' .$this->sday. PHP_EOL;
            writeLog('FAIL|Insert Into sum_player_lost(First RMB)|msg='.
                $rowCount.'date='
                .$this->sday, LOG_PATH.'/sum_pay_daily.log');
        }
        else {
            writeLog('OK|Insert Into sum_pay_daily(Total RMB)|rowCount='
                .$rowCount.'date='
                .$this->sday, LOG_PATH.'/sum_pay_daily.log');
        }
        $this->month_pay_count();
    }

    /**
     *
     *
     */
    public function month_pay_count($time=0)
    {
        $time = $time>0 ? $time : time();
        //由于自动执行的程序是第二天执行的，所以这里的时间用前一天（或可以成为当天）
        $tm = strtotime('-1 days', $time);
        $day = date('j', $tm);
        $sday = date('Ymd', $tm);
        $startPayTime = date('Y-m-01 00:00:00', $tm);
        $endPayTime   = date('Y-m-d 23:59:59',  $tm);
        $total_pay    = $first_pay = $first_money = $total_money = 0;

        //月新付费用户累积付费，每日累积统计，下月一日重置。
        //payemoney记录的是元宝数，转换成rmb需要除以10.
        $t1 =  date('ym010000', $tm );
        $t2 =  date('ymd2359', $tm );
        $sqlFirst = <<<SQL
    SELECT accountid FROM first_rmb WHERE daytime>=$t1 AND daytime<=$t2
SQL;
        echo $sqlFirst,PHP_EOL;
        $stmtFirst = $this->_souce_db->prepare($sqlFirst);
        $timeArray = array();
        $stmtFirst->execute($timeArray);
        $first_account_id = $stmtFirst->fetchAll(PDO::FETCH_COLUMN);
        $first_pay = count($first_account_id);//首次充值人数
        $first_account_id_str = implode(',', $first_account_id);

        //首次充值金额
        $val_first_pay = $this->sum_values($startPayTime, $endPayTime, "PayID in($first_account_id_str) AND ");
//        echo $first_pay,'---',$first_money;exit;
        //月付费人数（不重复），每日累积统计，下月一日重置。
//        $sqlPayCnt = <<<SQL
//    SELECT COUNT(DISTINCT(PayID)) as paynopall,SUM(PayMoney) as sum_money,ServerID
//    FROM pay_log WHERE rpCode in ('1','10')
//    AND Add_Time >=? AND Add_Time <=?
//    GROUP BY ServerID ORDER BY NULL
//SQL;
//        echo $sqlPayCnt, PHP_EOL;
//        $stmt = $this->_sum_db->prepare($sqlPayCnt);
//        $stmt->execute(array($startPayTime, $endPayTime));
//        while($tmp_row=$stmt->fetch(PDO::FETCH_ASSOC)) {
//            $vals[$tmp_row['ServerID']]['paynoall']  = $tmp_row['paynopall'];
//            $vals[$tmp_row['ServerID']]['sum_money'] = $tmp_row['sum_money'];
//        }
        $val_pay = $this->sum_values($startPayTime, $endPayTime);
        $val_str = '';
        foreach($val_pay as $serverId=>$val) {
            $fist_pay       = isset($val_first_pay[$serverId]['paynoall'])  ? $val_first_pay[$serverId]['paynoall']:0;
            $fist_pay_money = isset($val_first_pay[$serverId]['sum_money']) ? $val_first_pay[$serverId]['sum_money']:0;
            $val_str .= "($serverId,{$val['paynoall']}, {$val['sum_money']},$fist_pay,$fist_pay_money,$sday, now()),";
        }
        $sql = "INSERT INTO sum_month_pay(server_id,total_pay,total_money,first_pay,first_money,sday,run_time) VALUES " . rtrim($val_str, ',') ;
//        $sql = <<<SQL
//        INSERT INTO sum_month_pay SET total_pay=$total_pay,total_money=$total_money,
//first_pay=$first_pay, first_money=$first_money, sday=$sday,run_time=now()
//SQL;
        return $this->_sum_db->exec($sql);
    }

    private function sum_values($startPayTime, $endPayTime, $where='')
    {
        $sqlPayCnt = <<<SQL
    SELECT COUNT(DISTINCT(PayID)) as paynopall,SUM(PayMoney) as sum_money,ServerID
    FROM pay_log WHERE %ac% rpCode in ('1','10')
    AND Add_Time >=? AND Add_Time <=?
    GROUP BY ServerID ORDER BY NULL
SQL;
        $sqlPayCnt = str_replace('%ac%', $where, $sqlPayCnt);
        echo $sqlPayCnt, PHP_EOL;
        $stmt = $this->_sum_db->prepare($sqlPayCnt);
        $stmt->execute(array($startPayTime, $endPayTime));
        while($tmp_row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $vals[$tmp_row['ServerID']]['paynoall']  = $tmp_row['paynopall'];
            $vals[$tmp_row['ServerID']]['sum_money'] = $tmp_row['sum_money'];
//            $total_pay      += $tmp_row['paynopall'];
//            $total_money    += $tmp_row['sum_money'];
        }
        return $vals;
    }
    /**
     *
     *
     */
} 