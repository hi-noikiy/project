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

class OurUserLostTmp extends Base{

    public function GetNewAccountId($time_query_begin, $time_query_end)
    {
        $sqlNewAccount = "SELECT accountid FROM newmac_20140901"
            . " WHERE gameid=5 AND createtime>=? AND createtime<=?";
        $q = $this->_souce_db->prepare($sqlNewAccount);
//        print_r(array($time_query_begin, $time_query_end));
        $q->execute(array($time_query_begin, $time_query_end));
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
        //echo count($newNewArrountArr);exit;
        $cnt =  count($newNewArrountArr);
        if (!$cnt ) {
            writeLog('OK|Insert Into sum_player_lost|rowCount=0;date='
                .$this->sday, LOG_PATH.'/sum_player_lost.log');
            return false;
        }
        $accountids = implode(',', $newNewArrountArr);
        //TODO:统计当天注册的玩家的创建角色数量,daytime,只统计今天
        //AND `createtime`>=? AND `createtime`<=?
        $sql = <<<SQL
SELECT COUNT(DISTINCT userid) AS cnt,lev,serverid,fenbaoid
FROM dayonline_20140901 WHERE daytime=$daytime AND accountid IN($accountids)
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
            $tm   = strtotime("- $day days", $this->timestamp);//前 N 天
            $sday = date('Ymd', $tm);//20140607
            $col  = "lost_day{$day}";//lost_day1
            $dt   = date('1ymd', strtotime("+$day days", $tm));

            $ymd =  date('1ymd', $tm);
            $ymdhiB = date('ymd0000', $tm);
            $ymdhiE = date('ymd2359', $tm);

            //获取N天前注册的玩家账号
            $newNewArrountArr = $this->GetNewAccountId($ymdhiB, $ymdhiE);

//            echo "\n===========获取N天前注册的玩家账号===========\n";
//            var_export($newNewArrountArr);
//            echo "\n===========获取N天前注册的玩家账号===========\n";

            $cnt =  count($newNewArrountArr);
            if (!$cnt ) {
                writeLog('OK|Insert Into sum_player_lost|rowCount=0;date='
                    .$sday, LOG_PATH.'/sum_player_lost.log');
                return false;
            }
            $accountids = implode(',', $newNewArrountArr);

            //获取N天前的所有等级
//            $getLevDaysAgo = "SELECT lev FROM dayonline WHERE daytime=? AND createtime>=? AND createtime<=? GROUP BY lev ORDER BY lev ASC";
            $getLevDaysAgo = "SELECT lev FROM dayonline_20140901 WHERE daytime=$ymd AND accountid IN($accountids) GROUP BY lev ORDER BY lev ASC";
            $stmt = $this->_souce_db->prepare($getLevDaysAgo);
            $stmt->execute();
            $levList = $stmt->fetchAll(PDO::FETCH_COLUMN);
//            echo "\n===========获取N天前的所有等级===========\n";
//            echo $getLevDaysAgo;
//            var_export($levList);
//            echo "\n===========获取N天前的所有等级===========\n";
            //根据等级循环统计玩家的登录数
            foreach ($levList as $lev) {
                //获取相应等级在N天前的角色id
//                $getUseridDaysAgo = "SELECT userid FROM dayonline WHERE lev=$lev AND daytime=? AND createtime>=? AND createtime<=?";
                $getUseridDaysAgo = "SELECT userid FROM dayonline_20140901"
                    ." WHERE lev=$lev AND daytime=$ymd"
                    ." AND accountid IN($accountids)";

                $stmt = $this->_souce_db->prepare($getUseridDaysAgo);
                $stmt->execute();
                $useridList = $stmt->fetchAll(PDO::FETCH_COLUMN);
//                echo "\n===========获取相应等级在N天前的角色id===========\n";
//                echo $getUseridDaysAgo;
//                var_export($useridList);
//                echo "\n===========获取相应等级在N天前的角色id===========\n";
                $useridStr  = implode(',', $useridList);
                //根据角色ID统计相应等级在当天的登陆数
                $getTodayOnline = "SELECT count(*) as cnt,serverid,fenbaoid"
                    ." FROM dayonline_20140901 WHERE userid IN($useridStr)"
                    ." AND daytime=$dt GROUP BY serverid,fenbaoid";
                $stmt = $this->_souce_db->prepare($getTodayOnline);
                $stmt->execute();
                $todayOnline = $stmt->fetchAll(PDO::FETCH_ASSOC);
//                echo "\n===========根据角色ID统计相应等级在当天的登陆数===========\n";
//                echo $getTodayOnline;
//                var_export($todayOnline);
//                echo "\n===========根据角色ID统计相应等级在当天的登陆数===========\n";
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
//                echo "\n===========sql_lost_insert===========\n";
//                echo $sql_lost_insert;
//                echo "\n===========sql_lost_insert===========\n";

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
        $cnt = count($data);
        if (!$cnt ) {
            writeLog('OK|Insert Into sum_player_lost|rowCount=0;date='
                .$this->bt, LOG_PATH.'/sum_player_lost.log');
            return false;
        }
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
//        echo $sql_lost_insert;
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
}