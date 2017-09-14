<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-4
 * Time: 上午9:02
 * 用户流失统计——在留存程序之后执行
 */

class UserLost extends Analysis{
    private $log;

    /**
     * @param PDO $souceDb
     * @param PDO $sumDb
     * @param null $bt
     * @param int $gameid
     * @param Log $log
     */
    public function __construct(PDO $souceDb, PDO $sumDb,$bt=null, $gameid=5, Log $log)
    {
        parent::__construct($souceDb, $sumDb, $bt, $gameid);
        $this->log = $log;
    }

    /**
     * 根据区服、渠道、等级统计玩家流失
     * 今日注册数=今日登陆数
     *
     * @param int $serverid 区服id
     * @return bool
     */
    public function lost($serverid)
    {
        //AND daytime>=? AND daytime<=?
        $sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,lev,serverid,fenbaoid
FROM dayonline WHERE serverid=? AND `createtime`>=? AND `createtime`<=?
GROUP BY fenbaoid,lev
ORDER BY NULL
SQL;
        $stmt = $this->_souce_db->prepare($sql);

        $stmt->execute(
            array(
                $serverid,
                date('ymd0000', $this->timestamp),
                date('ymd2359', $this->timestamp),
            )
        );
        //
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($data)) {
            //print_r($data);
            $strinsert = '';
            foreach ($data as $nl) {
                $strinsert .= "({$this->bt},{$nl['serverid']},{$nl['fenbaoid']},"
                    ."{$nl['lev']},{$this->gameid},{$nl['cnt']}),";
            }
            //记录今天数据  ON DUPLICATE KEY UPDATE `nop`=VALUES(`nop`)
            $strinsert = rtrim($strinsert, ',');
            $sql_lost_insert = <<<SQL
    INSERT INTO sum_player_lost(sday,serverid,fenbaoid,lev,gameid,`nop`)
    VALUES $strinsert
SQL;
//            echo $sql_lost_insert;
            $rowCount = $this->_sum_db->exec($sql_lost_insert);
            if($rowCount===false) {
                echo date('Y-m-d H:i:s')
                    . '|FAIL|Insert Into sum_player_lost|msg='
                    .$rowCount .'date=' .$this->bt. PHP_EOL;
                $this->log->write('FAIL|Insert Into sum_player_lost|msg='.
                    $rowCount.'date='
                    .$this->bt, LOG_PATH.'/sum_player_lost_fail.log');
            }
            else {
                $this->log->write('OK|Insert Into sum_player_lost|rowCount='
                    .$rowCount.'date='
                    .$this->bt, LOG_PATH.'/sum_player_lost.log');
            }
        }
//
        $this->_lost($serverid);
    }

    /**
     * 统计1日、3日流失
     * @param $serverid
     * @return bool
     */
    public function _lost($serverid)
    {
        $dayList = array(1, 3);
        foreach ($dayList as $day) {
            $sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,lev,serverid,fenbaoid FROM dayonline
WHERE serverid=? AND createtime>=? AND createtime<=? AND daytime=?
GROUP BY fenbaoid,lev ORDER BY NULL
SQL;
            // AND daytime<=?
            $tm   = strtotime("- $day days", $this->timestamp);
            $sday = date('Ymd', $tm);//20140607
            $col  = "lost_day{$day}";//lost_day1
            $dt   = strtotime("+$day days", $tm);
            //$eday = date('Ymd', strtotime("+$day days", $tm));//0608
            $stmt = $this->_souce_db->prepare($sql);
            $stmt->execute(array(
                    $serverid,
                    date('ymd0000', $tm),
                    date('ymd2359', $tm),
                    date('1ymd', $dt),
//                    date('ymd2359', $dt),
                )
            );
            $sumAcc = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!count($sumAcc)) {
                echo 'No Data'.$serverid.PHP_EOL;
                $this->log->write('OK|No Data TO Insert Into sum_player_lost|$serverid='.$serverid.'$day=' .$day, LOG_PATH.'/sum_player_lost.log');
                return false;
            }
            $strValues = '';
            foreach ($sumAcc as $sa) {
                $strValues .= "({$sday},{$sa['serverid']},{$sa['fenbaoid']},"
                    ."{$sa['lev']},{$this->gameid},{$sa['cnt']}),";
            }
            $strValues = rtrim($strValues, ',');
            $sql_lost_insert = <<<SQL
        INSERT INTO sum_player_lost(sday,serverid,fenbaoid,lev,gameid,$col)
        VALUES $strValues ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
//            echo $sql_lost_insert;
            $rowCount = $this->_sum_db->exec($sql_lost_insert);
            if($rowCount!==false) {
                $this->log->write('OK|Insert Into sum_player_lost|rowCount='
                    .$rowCount.'date=' .$day, LOG_PATH.'/sum_player_lost.log');
            }
            else {
                $this->log->write('FAIL|Insert Into sum_player_lost|sql='
                    .$sql_lost_insert.'date='
                    .$sday, LOG_PATH.'/sum_player_lost_fail.log');
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
            $this->log->write('OK|Insert Into sum_player_lost_all|rowCount='.$rowCount.'date=' .$this->bt, LOG_PATH.'/sum_player_lost_all.log');
        }
        else {
//            echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_player_lost|msg='.$rowCount .'date=' .$this->bt. PHP_EOL;
            $this->log->write('FAIL|Insert Into sum_player_lost|msg='.$rowCount.'date=' .$this->bt, LOG_PATH.'/sum_player_lost_all.log');
        }
    }
}