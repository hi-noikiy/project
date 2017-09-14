<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-4
 * Time: 上午9:02
 * 用户流失统计——在留存程序之后执行
 * 根据区服、渠道、等级统计玩家流失
 * 今日注册数=今日登陆数
 */

class OurUserLost extends Base{

    public function GetNewAccountId($time_query_begin, $time_query_end)
    {
        $newmac =  ($time_query_begin < 1509010000) ? 'newmac_20150901' : 'newmac';
        $sqlNewAccount = "SELECT accountid FROM $newmac"
            . " WHERE gameid=5 AND createtime>=$time_query_begin AND createtime<=$time_query_end";
//        echo $sqlNewAccount;
        $q = $this->_souce_db->prepare($sqlNewAccount);
//        print_r(array($time_query_begin, $time_query_end));
        $q->execute();
        $newNewArrountArr = $q->fetchAll(PDO::FETCH_COLUMN);
//        $accountids = implode(',', $newNewArrountArr);
        return $newNewArrountArr;
    }

    public function run()
    {
        $daytime = date('1ymd', $this->timestamp);
        $time_query_begin = date('ymd0000', $this->timestamp);
        $time_query_end   = date('ymd2359', $this->timestamp);
        $newNewArrountArr = $this->GetNewAccountId($time_query_begin, $time_query_end);
//        echo count($newNewArrountArr);exit;
        $accountids = implode(',', $newNewArrountArr);
        //TODO:统计当天注册的玩家的创建角色数量,daytime,只统计今天
        //AND `createtime`>=? AND `createtime`<=?
        $sql = <<<SQL
SELECT COUNT(DISTINCT userid) AS cnt,lev,serverid,fenbaoid
FROM dayonline WHERE daytime=$daytime AND accountid IN($accountids)
GROUP BY serverid,fenbaoid,lev
ORDER BY NULL
SQL;

        $stmt = $this->_souce_db->prepare($sql);
//daytime=? AND date('1ymd', $this->timestamp),
        $stmt->execute();
//        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        print_r($data);
//        exit;
        if (count($data)) {
            $strinsert = '';
            foreach ($data as $nl) {
                $strinsert .= "({$this->sday},{$nl['serverid']},{$nl['fenbaoid']},"
                    ."{$nl['lev']},{$this->gameid},{$nl['cnt']}),";
            }
//            exit;
            //记录今天数据
            $strinsert = rtrim($strinsert, ',');
            $sql_lost_insert = <<<SQL
    INSERT INTO sum_player_lost(sday,serverid,fenbaoid,lev,gameid,`nop`)
    VALUES $strinsert ON DUPLICATE KEY UPDATE `nop`=VALUES(nop)
SQL;
//            echo $sql_lost_insert;
            $rowCount = $this->_sum_db->exec($sql_lost_insert);
            if($rowCount===false) {
                echo date('Y-m-d H:i:s')
                    . '|FAIL|Insert Into sum_player_lost|msg='
                    .$rowCount .'date=' .$this->sday. PHP_EOL;
                writeLog('FAIL|Insert Into sum_player_lost|msg='.
                    $rowCount.'date='
                    .$this->sday, LOG_PATH.'/sum_player_lost_fail.log');
            }
            else {
                writeLog('OK|Insert Into sum_player_lost|rowCount='
                    .$rowCount.'date='
                    .$this->sday, LOG_PATH.'/sum_player_lost.log');
            }
        }
        $this->lost();
    }

    /**
     * 统计1日、3日流失
     * @return bool
     */
    public function lost()
    {
        $dayList = array(1, 3);
        foreach ($dayList as $day) {
            $tm   = strtotime("- $day days", $this->timestamp);
            $sday = date('Ymd', $tm);//20140607
            $col  = "lost_day{$day}";//lost_day1
            $dt_time_stamp = strtotime("+$day days", $tm);
            $dt   = date('1ymd', $dt_time_stamp);
            $dt_m = date('m', $dt_time_stamp);

            $ymd =  date('1ymd', $tm);

            $month  = date('m', $tm);
            $current_month = date('m', $_SERVER['REQUEST_TIME']);
            $month_table = $dt_table = '';
            if ($month!=$current_month) {
                $month_table = "_{$month}";
            }
            if ($dt_m!=$current_month) {
                $dt_month_table = "_{$dt_m}";
            }


            $ymdhiB = date('ymd0000', $tm);
            $ymdhiE = date('ymd2359', $tm);
            $queryArgs     = array(
                date('1ymd', $tm),
                date('ymd0000', $tm),
                date('ymd2359', $tm),
            );
            //获取N天前注册的玩家账号
            $newNewArrountArr = $this->GetNewAccountId($ymdhiB, $ymdhiE);
            $accountids = implode(',', $newNewArrountArr);
            //获取N天前的所有等级
//            $getLevDaysAgo = "SELECT lev FROM dayonline WHERE daytime=? AND createtime>=? AND createtime<=? GROUP BY lev ORDER BY lev ASC";
            $getLevDaysAgo = "SELECT lev FROM dayonline{$month_table} WHERE daytime=$ymd AND accountid IN($accountids) GROUP BY lev ORDER BY lev ASC";
//            echo $getLevDaysAgo, PHP_EOL;
            $stmt = $this->_souce_db->prepare($getLevDaysAgo);
            $stmt->execute();
            $levList = $stmt->fetchAll(PDO::FETCH_COLUMN);
            //根据等级循环统计玩家的登录数
            foreach ($levList as $lev) {
                //获取相应等级在N天前的角色id
//                $getUseridDaysAgo = "SELECT userid FROM dayonline WHERE lev=$lev AND daytime=? AND createtime>=? AND createtime<=?";
                $getUseridDaysAgo = "SELECT userid FROM dayonline{$month_table} WHERE lev=$lev AND daytime=$ymd AND accountid IN($accountids)";
//                echo $getUseridDaysAgo, PHP_EOL;
                $stmt = $this->_souce_db->prepare($getUseridDaysAgo);
                $stmt->execute();
                $useridList = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $useridStr  = implode(',', $useridList);
                //根据角色ID统计相应等级在当天的登陆数
                $getTodayonline = "SELECT count(*) as cnt,serverid,fenbaoid FROM dayonline{$dt_month_table} WHERE userid IN($useridStr) AND daytime=$dt GROUP BY serverid,fenbaoid";
//                echo $getTodayonline;
                $stmt = $this->_souce_db->prepare($getTodayonline);
                $stmt->execute();
                $todayOnline = $stmt->fetchAll(PDO::FETCH_ASSOC);
//                print_r($todayOnline);
                if (!count($todayOnline)) {
                    return false;
                }
                $strValues = '';
                foreach ($todayOnline as $ol) {
                    $strValues .= "({$sday},{$ol['serverid']},{$ol['fenbaoid']},"
                        ."{$lev},{$this->gameid},{$ol['cnt']}),";
                }
                $strValues = rtrim($strValues, ',');
                $sql_lost_insert = <<<SQL
        INSERT INTO sum_player_lost(sday,serverid,fenbaoid,lev,gameid,$col)
        VALUES $strValues ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
//                echo $sql_lost_insert, PHP_EOL;
                $rowCount = $this->_sum_db->exec($sql_lost_insert);
                if($rowCount!==false) {
                    writeLog("OK|Insert Into sum_player_lost Lev {$lev}|rowCount={$rowCount}date=$day", LOG_PATH.'/sum_player_lost.log');
                }
                else {
                    writeLog("FAIL|Insert Into sum_player_lost Lev {$lev}|sql={$sql_lost_insert}date=$day", LOG_PATH.'/sum_player_lost_fail.log');
                }
            }
        }

    }
    /**
     * 统计总数，不按区服、渠道区分
     */
    public function sum()
    {
        $st = date('Ymd', strtotime('-7 days', $this->timestamp));
//        $st = date('Ymd', strtotime('-4 days', $this->timestamp));
        $et = date('Ymd', strtotime('+1 days', $this->timestamp));
        //$et = date('Ymd', strtotime('-3 days', $this->timestamp));
        $sql = <<<SQL
        SELECT sday, lev,gameid, SUM(nop) as nop,SUM(lost_day1) as lost_day1,
        SUM(lost_day3) as lost_day3 FROM sum_player_lost
        where sday>? AND sday<? AND gameid=?
        GROUP BY sday,lev
SQL;
//        echo $sql;
        $stmt = $this->_sum_db->prepare($sql);
        $stmt->execute(array($st, $et, $this->gameid));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$data) return false;
        //$rowCount = $this->Insert($data, 'sum_player_lost_all');
        $strValues = '';
        foreach ($data as $lg) {
            $strValues .= "({$lg['sday']},{$lg['lev']},{$this->gameid},{$lg['nop']},"
                ."{$lg['lost_day1']},{$lg['lost_day3']}),";
        }
        $strValues = rtrim($strValues, ',');
        $sql_lost_insert = <<<SQL
        INSERT INTO sum_player_lost_all(sday,lev,gameid,nop,lost_day1,lost_day3)
        VALUES $strValues ON DUPLICATE KEY UPDATE `lost_day1`=VALUES(lost_day1),`lost_day3`=VALUES(lost_day3)
SQL;
        echo 'sql_lost_insert'.PHP_EOL;
        echo $sql_lost_insert.PHP_EOL;;
        $rowCount = $this->_sum_db->exec($sql_lost_insert);

        if(is_numeric($rowCount) AND $rowCount>0) {
//            echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_player_lost|rowCount='.$rowCount .'date=' .$this->bt. PHP_EOL;
            writeLog('OK|Insert Into sum_player_lost_all|rowCount='.$rowCount.'date=' .$this->sday, LOG_PATH.'/sum_player_lost_all.log');
        }
        else {
//            echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_player_lost|msg='.$rowCount .'date=' .$this->bt. PHP_EOL;
            writeLog('FAIL|Insert Into sum_player_lost|msg='.$rowCount.'date=' .$this->sday, LOG_PATH.'/sum_player_lost_all.log');
        }
    }


    public function vip_lost_info()
    {
        /*
         * 流失玩家：距离最后一次登录超过10天的玩家
         * 1、通过first_rmb表字段`daytime`查询出流失玩家的名单，玩家名单注意区分区服
         * 2、然后从palyeronline表中找出该部分玩家的人数，统计出平均付费（`total_rmb`字段，是元宝要除以10），统计出平均等级（`lev`字段）
         * 3、其中要特别注意下在取palyeronline表数据时要关联`daytime`字段，每个玩家取`daytime`字段值最大的那条数据
         */
        $tm   = strtotime("- 10 days", $this->timestamp);//10天前

        $ymd =  date('1ymd', $tm);
        $ymdhiB = date('ymd0000', $tm);
        $ymdhiE = date('ymd2359', $tm);

        //获取10天前注册的玩家账号
        $newNewArrountArr = $this->GetNewAccountId($ymdhiB, $ymdhiE);
        //根据角色ID统计相应等级在当天的登陆数

        //流失数=10天前总数-今日登录数
        $getUseridDaysAgo = "SELECT accountid FROM dayonline WHERE daytime=? AND accountid IN($accountids)";
        $stmt = $this->_souce_db->prepare($getUseridDaysAgo);
        $stmt->execute(array($ymd));
        $useridList = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $useridStr  = implode(',', $useridList);
    }
}