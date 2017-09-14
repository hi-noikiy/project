<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/2
 * Time: 22:26
 */
define('LOG_QUERY_SQL', 'play_lost');
class Player_lost_model extends CI_Model
{
    private $begin_timestamp = 0;
    public function __construct()
    {
        $this->load->database();
        $this->timestamp = strtotime('-1 days');
        $this->db_sdk = $this->load->database('sdk', TRUE);
    }

    /**
     * 统计角色数
     *
     * @param $appid
     * @param $day
     * @param $day_cnt
     */
    public function getLoginCount($appid, $day)
    {
    	/*$sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,MAX(lev) AS lev,serverid,channel
FROM u_login_new WHERE appid=$appid AND created_at BETWEEN $begin_timestamp AND $end_timestamp
GROUP BY serverid,channel,accountid
ORDER BY lev ASC
SQL;*/
        $sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,MAX(lev) AS lev,serverid,channel
FROM u_login_$day WHERE appid=$appid
GROUP BY serverid,channel,accountid
ORDER BY lev ASC
SQL;
        //echo date('Y-m-d H:i:s', $begin_timestamp) ,'---',$sql,'---',date('Y-m-d H:i:s', $end_timestamp),PHP_EOL;
        echo 'getLoginCount:',$sql,"\n";
        $query = $this->db_sdk->query($sql);
        $new_yestoday = array();
        if($query) $new_yestoday = $query->result_array();
        if (count($new_yestoday)) {
            $strValue = '';
            //$day = date('Ymd', $begin_timestamp);
            $lvl_cnt = [];
            foreach ($new_yestoday as $values) {
                $_key  = $values['serverid'].'_'.$values['channel'];
                if (!isset($lvl_cnt[$_key])) $lvl_cnt[$_key][$values['lev']] = $values['cnt'];
                elseif (!isset($lvl_cnt[$_key][$values['lev']])) $lvl_cnt[$_key][$values['lev']] = $values['cnt'];
                else $lvl_cnt[$_key][$values['lev']] += $values['cnt'];
            }
            foreach ($lvl_cnt as $key=>$val) {
                list($serverid,$channel) = explode('_', $key);
                foreach ($val as $lev=>$cnt) {
                    $strValue .= "($day, {$serverid},'{$appid}',{$channel},{$cnt}, {$lev}),";
                }
            }
            return rtrim($strValue,',');
        }
    }
    //按userID来算

    public function lost($appid, $timestamp=0)
    {
        $timestamp = $timestamp> 0 ? $timestamp : strtotime(date('Y-m-d 00:00:00', strtotime("-1 days")));
        $date = date('Ymd', $timestamp);
        $this->begin_timestamp = $timestamp;
        $this->end_timestamp   = strtotime(date('Y-m-d 23:59:59', $timestamp));
        //统计当天登录账号数
        //$strValue = $this->getLoginCount($appid, $this->begin_timestamp, $this->end_timestamp);
        $strValue = $this->getLoginCount($appid, $date);
        echo $strValue,PHP_EOL;
        //return false;
        if (strlen($strValue)) {
            $sql = <<<SQL
    INSERT INTO sum_player_lost(`sday`, `serverid`, `appid`,`channel`, `usercount`,`lev`)
    VALUES $strValue
    ON DUPLICATE KEY UPDATE usercount=VALUES(usercount)
SQL;
            echo $sql;
            $this->db->query($sql);
            $rowCount = $this->db->affected_rows();
            if($rowCount!==false) {
            //    echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_player_lost|rowCount='.$rowCount .'date=' .$date. PHP_EOL;
             //   log_message('info', 'OK|Insert Into sum_reserveusers_daily|rowCount='.$rowCount.'date=' .$date);
            }
            else {
            //    echo date('Y-m-d H:i:s') . '|FAIL|Insert Into sum_reserveusers_daily|sql='.$sql . 'date=' .$date.PHP_EOL;
            //    log_message('info','FAIL|Insert Into sum_reserveusers_daily|sql='.$sql.'date=' .$date);
            }
            echo 'rowCount:' . $rowCount . PHP_EOL;
        }
        $this->day_lost($appid);
    }
    public function day_lost($appid='10001')
    {
        //$dayList = [1, 3, 7, 14,30];
        $dayList = [1, 3, 7];
        $log_path = APPPATH . "/logs/controller/debug_".date('Ymd').".log";
        foreach ($dayList as $i) {
            parent::log("UserLost:day_lost;appid={$appid};day={$i};", $log_path);
            $tm              = strtotime("- $i days", $this->begin_timestamp);
            $sday            = date('Ymd', $tm);//20140519,18
            echo "\ntoday=" . date('Y-m-d', $this->begin_timestamp);
            echo ";i=$i,sday=$sday\n";
            $col             = "lost_{$i}";//day8
            $loginDate_begin = date('Ymd', strtotime("+1 days", $tm));
            $loginDate_end   = date('Ymd', strtotime("+$i days", $tm));//20,20
            //次日未登录人数:（4月1号在当天有登录，但是4月2号没登录的accountid数量）
            //三日未登录人数：（4月1号在当天有登录，但是4月2号 - 4月4号之间都没登录的accountid数量）
            //TODO::获取当日有登录的玩家账号id
            //$sql_login_before = "SELECT DISTINCT accountid FROM u_login_new WHERE appid=$appid AND logindate=$sday";
            $sql_login_before = "SELECT accountid FROM u_login_$sday WHERE appid=$appid";
            echo $sql_login_before,PHP_EOL;
            $query = $this->db_sdk->query($sql_login_before);
            $account_id_before = $account_id_now = $account_id_unlogin = [];
            if($query)
            foreach ($query->result() as $item) {
                $account_id_before[] = $item->accountid;
            }
            if (!count($account_id_before)) {
                //全部流失了
                //$all_lost_day = date('Ymd', strtotime("-$i days", strtotime($sday)));
                $sql = "UPDATE sum_player_lost SET $col=`usercount` WHERE appid=$appid AND sday=$sday";
                echo 'lost all:' . $sql,"\n";
                $this->db->query($sql);
                continue;
            }
            //print_r($account_id_before);
            $account_id_before_str = implode(',', $account_id_before);
            //TODO::获取有N天后登录的账号ID
            /*$sql = "SELECT DISTINCT accountid FROM u_login_new WHERE appid=$appid AND accountid IN ($account_id_before_str)";
            //$sql .= $loginDate_begin==$loginDate_end ? "  AND logindate=$loginDate_begin"
            //            : " AND logindate>=$sday AND logindate<=$loginDate_end";
            $sql .= " AND logindate>$sday AND logindate<=$loginDate_end";*/
            $sql = "SELECT DISTINCT accountid FROM (select accountid from u_login_$loginDate_begin";
            if($i>1){
            	for ($di=2;$di<$i;$di++){
            		$ddate =  date('Ymd', strtotime("+$di days", $tm));
            		$sql .= " union select accountid from u_login_$ddate";
            	}
            }
            $sql .= ")a;";
            echo "LOGIN_AFTER $i days:$sql", PHP_EOL;
            $query = $this->db_sdk->query($sql);
            if($query)
            foreach ($query->result() as $item) {
                $account_id_now[] =$item->accountid;
            }
            //对比返回在 $account_id_before 中但是不在 $account_id_now 及任何其它参数数组中的值
            $account_id_unlogin = array_diff($account_id_before, $account_id_now);
            //查询得到流失前最大的等级
            if (count($account_id_unlogin)) {
                $account_id_unlogin_str = implode(',', $account_id_unlogin);
                /*$sql_lost = <<<SQL
SELECT COUNT(DISTINCT accountid) as cnt,MAX(lev) AS lev,serverid,channel FROM u_login_new
WHERE accountid IN ($account_id_unlogin_str) AND logindate=$sday GROUP BY serverid,channel,accountid ORDER BY lev asc
SQL;*/
                $sql_lost = <<<SQL
SELECT COUNT(DISTINCT accountid) as cnt,MAX(lev) AS lev,serverid,channel FROM u_login_$sday
WHERE accountid IN ($account_id_unlogin_str)  GROUP BY serverid,channel,accountid ORDER BY lev asc
SQL;
                echo "Lost SQL:$sql_lost\n";
                $query = $this->db_sdk->query($sql_lost);
                $strValues = '';
                $lost_data = array();
                if($query) $lost_data = $query->result_array();
                //print_r($lost_data);
                $lvl_cnt = [];
                foreach ($lost_data as $values) {
                    $_key  = $values['serverid'].'_'.$values['channel'];
                    //echo "$_key:LEV:{$values['lev']}\n";
                    if (!isset($lvl_cnt[$_key])) $lvl_cnt[$_key][$values['lev']] = $values['cnt'];
                    elseif (!isset($lvl_cnt[$_key][$values['lev']])) $lvl_cnt[$_key][$values['lev']] = $values['cnt'];
                    else $lvl_cnt[$_key][$values['lev']] += $values['cnt'];
                }
                //print_r($lvl_cnt);
                foreach ($lvl_cnt as $key=>$val) {
                    list($serverid,$channel) = explode('_', $key);
                    foreach ($val as $lev=>$cnt) {
                        $strValues .= "({$sday},$serverid,$channel,{$appid},$lev,{$cnt}),";
                        //$strValue .= "($day, {$serverid},'{$appid}',{$channel},{$cnt}, {$lev}),";
                    }
                }
                $strValues = rtrim($strValues, ',');
                $sql = " INSERT INTO sum_player_lost(sday,serverid,channel,appid,lev,$col) "
                    ." VALUES $strValues ON DUPLICATE KEY UPDATE `$col`=VALUES($col)";
                echo "Lost SQL Add:$sql\n";
                $this->db->query($sql);
            }
        }
    }

    /**
     * 根据VIP等级统计流失
     *
     * @param $appid
     * @param $date
     */
    public function lost_vip_lev($appid, $date)
    {
        if (!$this->db_sdk) $this->db_sdk = $this->load->database('sdk', TRUE);
        //获取当日登录的玩家
        /*$sql = <<<SQL
SELECT DISTINCT accountid FROM u_login_new
WHERE appid=$appid AND logindate=$date
AND viplev>0
SQL;*/
        $sql = <<<SQL
SELECT DISTINCT accountid FROM u_login_$date
WHERE appid=$appid
AND viplev>0
SQL;
        //echo $sql,"\n";
        $query = $this->db_sdk->query($sql);
        $account_cur = array();
        if($query)
        foreach ($query->result() as $acc) {
            $account_cur[] = $acc->accountid;
        }
        if (!count($account_cur)) return false;
        //print_r($account_cur);exit;
        $date_lost_arr = [1, 3, 7, 14,30];
        $account_str = implode(',', $account_cur);

        foreach ($date_lost_arr as $day)
        {
            $countLogin = array();
            //echo $day,"\n";
            //统计有登录的账号
            $login_date = date('Ymd', strtotime("+ $day days", strtotime($date)));
            /*$sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,viplev,serverid, channel FROM u_login_new
WHERE appid=?
AND accountid IN($account_str)
AND logindate=$login_date
AND viplev>0
GROUP BY serverid,channel,viplev
SQL;*/
            $sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,viplev,serverid, channel FROM u_login_$login_date
WHERE appid=?
AND accountid IN($account_str)
AND viplev>0
GROUP BY serverid,channel,viplev
SQL;
            //echo $sql,"\n";
            $query = $this->db_sdk->query($sql, [$appid]);
            if (!$query) return false;
            $strValues = '';
            foreach ($query->result() as $res)
            {
                $strValues .= "({$date}, {$res->viplev},{$res->serverid}, {$res->channel}, '{$appid}', {$res->cnt}),";
            }
            if (empty($strValues)) continue;

            $strValues = rtrim($strValues, ',');
            $col = "lost_$day";
            $sql = " INSERT INTO sum_player_lost_vip(sday,viplev,serverid,channel,appid,$col) "
                ." VALUES $strValues ON DUPLICATE KEY UPDATE `$col`=VALUES($col)";
            $this->db->query($sql);
        }
    }

    /**
     * 根据玩家等级统计
     *
     * @param $appid
     * @param $date
     */
    public function lost_lev($appid, $date)
    {
        if (!$this->db_sdk) $this->db_sdk = $this->load->database('sdk', TRUE);
        //获取当日登录的玩家
        /*$sql = <<<SQL
SELECT DISTINCT accountid FROM u_login_new
WHERE appid=$appid AND logindate=$date
AND viplev>0
SQL;*/
        $sql = <<<SQL
SELECT DISTINCT accountid FROM u_login_$date
WHERE appid=$appid
AND viplev>0
SQL;
        //echo $sql,"\n";
        $query = $this->db_sdk->query($sql);
        //$account_cur = $query->result_array();
        $account_cur = array();
        if($query)
        foreach ($query->result() as $acc) {
            $account_cur[] = $acc->accountid;
        }
        if (!count($account_cur)) return false;

        //print_r($account_cur);exit;
        $date_lost_arr = [1, 3, 7, 14,30];
        $account_str = implode(',', $account_cur);

        foreach ($date_lost_arr as $day)
        {
            //echo $day,"\n";
            //统计有登录的账号
            $login_date = date('Ymd', strtotime("+ $day days", strtotime($date)));
            /*$sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,lev,serverid, channel FROM u_login_new
WHERE appid=?
AND accountid IN($account_str)
AND logindate=$login_date
GROUP BY serverid,channel,lev
SQL;*/
            $sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,lev,serverid, channel FROM u_login_$login_date
WHERE appid=?
AND accountid IN($account_str)
GROUP BY serverid,channel,lev
SQL;
            //echo $sql,"\n";
            $query = $this->db_sdk->query($sql, [$appid]);
            if (!$query) continue;
            $strValues = '';
            foreach ($query->result() as $res)
            {
                $strValues .= "({$date}, {$res->lev},{$res->serverid}, {$res->channel}, '{$appid}', {$res->cnt}),";
            }
            if (empty($strValues)) return false;
            $strValues = rtrim($strValues, ',');
            $col = "lost_$day";
            $sql = " INSERT INTO sum_player_lost_normal(sday,lev,serverid,channel,appid,$col) "
                ." VALUES $strValues ON DUPLICATE KEY UPDATE `$col`=VALUES($col)";
            $this->db->query($sql);
        }
    }

    public function getLostData($appid, $date, $lost_type,$serverid=0, $channel=0)
    {
        $sql = "SELECT ";
        $col = '';
        switch($lost_type) {
            case 1:
                $col = ",viplev";
                $table = 'sum_player_lost_vip';
                break;
            case 2:
                $col = ",lev";
                $table = 'sum_player_lost_normal';
                break;
            default:
                $col = ",lev";
                $table = 'sum_player_lost';
                break;
        }
        $sql .= <<<SQL
SUM(usercount) AS usercount,sum(lost_1) as lost_1,sum(lost_3) as lost_3,
sum(lost_7) as lost_7,SUM(lost_14) as lost_14,
sum(lost_30) as lost_30,sday $col
 FROM $table
 WHERE appid=$appid AND sday=$date AND usercount>0
SQL;
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN (".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";

        $sql .= " GROUP BY sday $col ORDER BY sday ASC$col ASC";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getLostBackData($appid, $date1, $date2, $serverid=0, $channel=0)
    {
        $sql = <<<SQL
SELECT sum(lost_8) as lost_8, SUM(lost_15) as lost_15, sum(lost_31) as lost_31,sday
 FROM sum_player_lost_back
 WHERE appid=$appid AND sday BETWEEN $date1 AND $date2
SQL;
        if ($serverid>0) {
            $serverid = intval($serverid);
            $sql .= " AND serverid=$serverid";
        }
        if ($channel>0) {
            $channel = intval($channel);
            $sql .= " AND channel=$channel";
        }
        $sql .= " GROUP BY sday ORDER BY sday ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}