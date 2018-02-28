<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/7
 * Time: 22:04
 */
class online_analysis_model extends CI_Model
{
    private $appid;
    private $t1;
    private $t2;
    private $date;
    private $db_sdk;
    private $db;


    public function init($appid, $t1, $t2, $date, $db_sdk=null, $db=null)
    {
        $this->appid    = $appid;
        $this->t1       = $t1;
        $this->t2       = $t2;
        $this->date     = $date;
        $this->db_sdk   = $db_sdk;
        $this->db       = $db;
    }

    const ONLINE_NEW    = 1;//新注册玩家
    const ONLINE_ACTIVE = 2;//活跃玩家
    const ONLINE_VIP    = 3;//付费玩家

    
    public function getOnlineData($where=array(),$field='',$group='')
    {
    	if (!$field)$field='*';
    	$this->db  = $this->load->database('sdk', TRUE);
    	$sql = <<<SQL
SELECT $field
FROM u_dayonline
WHERE 1=1
SQL;
    	if($where['begindate']){
    		$sql .= " AND online_date between {$where['begindate']} AND {$where['enddate']}";
    	}
    	if($where['serverid']){
    		$sql .= " AND serverid IN(".implode(',', $where['serverid']).")";
    	}
    	if($where['channel']){
    		$sql .= " AND channel IN(".implode(',', $where['channel']).")";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	$result = $this->db->query($sql);
    	if($result){
    		return $result->result_array();
    	}
    	return array();
    }
    public function getPlayerOnline($appid, $bt, $et, $serverid=0, $channel=0)
    {
        //if (!$this->db)
        $this->db  = $this->load->database('default', TRUE);
        $sql = <<<SQL
SELECT date,
SUM(vip_online) AS vip_online,SUM(vip_cnt) as vip_cnt,
 SUM(active_online) as active_online,SUM(active_cnt) as active_cnt,
SUM(new_online) AS new_online,SUM(new_cnt) as new_cnt
FROM sum_online_time
WHERE appid=$appid AND date BETWEEN $bt AND $et
SQL;
        if ($serverid>0)    $sql .= " AND serverid=$serverid";
        if ($channel>0)     $sql .= " AND channel=$channel";
        $sql .= " GROUP BY date ORDER BY date ASC";
        return $this->db->query($sql)->result_array();
    }
    public function getSumPlayOnline( $appid, $date1, $date2, $serverid,$channel)
    {
        $this->db  = $this->load->database('default', TRUE);
        $where = " WHERE `appid`='$appid'"; // AND `date`<=$date2
        if($date1!=$date2 && $date1<$date2) {
            $where .= " AND `date`>={$date1} AND `date`<=$date2";
        }
        else {
            $where .= "  AND `date`={$date1}";
        }

        if (is_numeric($serverid) && $serverid>0) $where .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $where .= " AND serverid IN(".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $where .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $where .= " AND channel IN(".implode(',', $channel).")";

        $sql = "SELECT sum(rmb) as rmb, sum(not_rmb) as not_rmb,"
            ." sum(player) as player,online_lvl,online_lvl_txt, `date` FROM sum_playeronline "
            .$where ." GROUP BY online_lvl ORDER BY online_lvl ASC";
//        echo $sql;
        $q = $this->db->query($sql);
        $data = $q->result_array();
        $ret  = array();
        foreach ($data as $d) {
            $ret[$d['online_lvl']] = $d;
        }

        $sql_sum = "SELECT SUM(rmb)+sum(not_rmb) AS players FROM sum_playeronline ".$where;
//        echo $sql_sum;
        $q = $this->db->query($sql_sum);
        $sum_player = $q->row_array();
        return array('list'=>$ret, 'total'=>$sum_player);
    }
    /**
     * 计算新增玩家的游戏时长
     *
     * @return bool
     */
    public function online_time($data_type)
    {
        switch ($data_type) {
            case self::ONLINE_NEW:
                $col    = 'new_online';
                $col2   = 'new_cnt';
                $sql = <<<SQL
SELECT SUM(d.online) AS online,COUNT(DISTINCT d.accountid) AS new_cnt,d.serverid,d.channel FROM u_dayonline d
LEFT JOIN u_register r ON r.accountid=d.accountid AND r.appid=d.appid
WHERE d.appid=? AND r.created_at BETWEEN ? AND ?
AND d.created_at BETWEEN ? AND ?
GROUP BY d.serverid,d.channel
SQL;
                $query_params = [
                    $this->appid,
                    $this->t1,
                    $this->t2,
                    $this->t1,
                    $this->t2,
                    ];
                break;
            case self::ONLINE_ACTIVE:
                $col    = 'active_online';
                $col2   = 'active_cnt';

                //活跃玩家不统计今天创建角色的玩家
                $sql = <<<SQL
SELECT SUM(d.online) AS online,d.serverid,d.channel,COUNT(DISTINCT accountid) as active_cnt FROM u_dayonline d
WHERE d.appid=? AND d.created_at BETWEEN ? AND ?
AND d.create_time<?
GROUP BY d.serverid,d.channel
SQL;
                $query_params = [
                    $this->appid,
                    $this->t1,
                    $this->t2,
                    $this->t1,
                ];
                break;
            case self::ONLINE_VIP:
                $col    = 'vip_online';
                $col2   = 'vip_cnt';

                //VIP玩家
                $sql = <<<SQL
SELECT SUM(d.online) AS online,d.serverid,d.channel,COUNT(DISTINCT accountid) as vip_cnt
FROM u_dayonline d
WHERE d.appid=? AND d.create_time BETWEEN ? AND ? AND viplev>0
GROUP BY d.serverid,d.channel
SQL;
                $query_params = [
                    $this->appid,
                    $this->t1,
                    $this->t2,
                ];
                break;
        }
        //print_r($query_params);
        //echo $sql;
        //exit;
        $query = $this->db_sdk->query($sql, $query_params);
        //$data = [];
        $replace = '';
        foreach ($query->result_array() as $row) {
            $replace .= "({$row['serverid']}, {$row['channel']},'{$this->appid}', {$row['online']},{$row[$col2]},{$this->date}),";
        }
        if (!empty($replace)) {
            $replace = rtrim($replace, ',');
            $sql = <<<SQL
INSERT INTO sum_online_time(serverid,channel,appid,`$col`,`$col2`,`sday`) VALUES $replace
ON DUPLICATE KEY UPDATE `$col`=VALUES($col),`$col2`=VALUES($col2)
SQL;
            $this->db->query($sql);
        }
        return true;
    }

    public function player_online_level()
    {
        //先获取登录的数据
        //$sql_login = "SELECT accountid,serverid,channel,viplev FROM u_login_new WHERE appid=$this->appid AND logindate={$this->date} GROUP BY accountid,serverid,channel";
        $sql_login = "SELECT accountid,serverid,channel,viplev FROM u_login_{$this->date} WHERE appid=$this->appid GROUP BY accountid,serverid,channel";
        $query_login    = $this->db_sdk->query($sql_login);
        $data  = array();
        if (!$query_login || !count($query_login)) {
            return false;
        }
        $login_data = $query_login->result_array();
        $zero_online = [];
        foreach ( $login_data as $list) {
            $key = $list['serverid'].'_'.$list['channel'].'_' . $list['accountid'];
            if ($list['viplev']>0) {
                //@$data[$key]['rmb'] += 1;
                $zero_online[$key]['rmb'] = 1;

            }
            else {
                $zero_online[$key]['notrmb'] = 1;
                //@$data[$key]['notrmb'] += 1;
            }
        }
        //print_r($zero_online);
        //return false;
        //TODO:viplev是否为付费玩家->修正：total_rmb大于0为付费玩家
        //$sum_players = 0;
        $timeLvlArea = array(0, 4, 10, 20, 30, 40, 50,
            60, 70, 80, 90, 100, 110,
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
        $len = count($timeLvlArea);
        $sql_q = <<<SQL
SELECT serverid,channel,SUM(online) AS online,accountid,MAX(viplev) AS viplev,max(total_rmb) as total_rmb FROM u_dayonline
WHERE appid=$this->appid AND online_date={$this->date} GROUP BY accountid ORDER BY NULL
SQL;
        //echo $sql_q . PHP_EOL;
        $query_online    = $this->db_sdk->query($sql_q);
        $data  = array();
        if (!count($query_online)) {
            return false;
        }
        $online_account_id = [];
        //print_r($query_online->result_array());return true;
//        $rmb = $notrmb = 0;
        foreach ($query_online->result_array() as $list) {
            $online_account_id[] = $list['accountid'];
            $onlineMins = ceil($list['online']/60);
            if ($onlineMins>480) {
                $lvl = $len-1;
            }
            elseif ($onlineMins==0) {
                $lvl = 0;
            }
            else {
                $lvl = self::halfSearch($timeLvlArea, $onlineMins);
            }
            $_sky = $list['serverid'].'_'.$list['channel'].'_'.$list['accountid'];
            //$zero_key = array_search($_sky, $zero_online);
            //echo 'zero-key:' . $_sky,'--',$zero_key,"\n";
            if (isset($zero_online[$_sky])) unset($zero_online[$_sky]);
            $key = $list['serverid'].'_'.$list['channel'].'_'.$lvl;
            if ($list['total_rmb']>0) {
                @$data[$key]['rmb'] += 1;
            }
            else {
                @$data[$key]['notrmb'] += 1;
            }
        }
        //print_r($zero_online);
        if (count($zero_online)) {
            foreach ($zero_online as $key=>$items) {
                list($serverid, $channel, $accountid) = explode('_', $key);
                foreach ($items as $isrmb=>$item) {
                    @$data["{$serverid}_{$channel}_0"][$isrmb] += 1;
                }
            }
        }
        if (!count($data)) {
            return false;
        }

        //print_r($data);
        //return;
        $insdata = array();
        foreach($data as $keys=>$player) {
            list($serverid, $channel, $lv) = explode('_', $keys);
            $insdata[] = array(
                'appid'             => $this->appid,
                'serverid'          => $serverid,
                'channel'           => $channel,
                'rmb'               => isset($player['rmb']) ? $player['rmb'] : 0,
                'not_rmb'           => isset($player['notrmb']) ? $player['notrmb'] : 0,
                'player'            => $player['rmb'] + $player['notrmb'],
                'online_lvl'        => $lv,
                'online_lvl_txt'    => $lvl_list[$lv],
                'date'              => $this->date,
            );
        }
        echo count($insdata);
        $rowCount = $this->db->insert_batch('sum_playeronline', $insdata);
        if(is_numeric($rowCount) && $rowCount>0) {
            echo date('Y-m-d H:i:s') . '|OK|Insert Into sum_rmbused|rowCount='.$rowCount . PHP_EOL;
            parent::log('OK|Insert Into sum_playeronline|rowCount='
                .$rowCount.'date='
                .$this->date, APPPATH.'/logs/sum_playeronline.log');
        }
        else {
            parent::log('FAIL|Insert Into sum_playeronline|msg='.
                $rowCount.'date='
                .$this->date, LOG_PATH.'sum_playeronline.log');

        }
    }

    public function GetOnlineTimeAvg($serverid, $channel)
    {
        $sql = <<<SQL
SELECT date,SUM(total_online_time) AS total_online_time,SUM(total_online_num) AS total_online_num
FROM sum_online_avg_day WHERE appid=$this->appid AND `date` BETWEEN $this->t1 AND $this->t2
SQL;
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN (".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        $sql .= " GROUP by `date` ORDER BY `date` ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * 平均在线
     * 平均在线=当天所有玩家总上线时间/当天上线玩家总数
     */
    public function CalOnlineTimeAvg($date)
    {
        //
        /*$sql_login = <<<SQL
SELECT appid,serverid,channel,COUNT(distinct accountid) as total_online_num FROM u_login_new
WHERE appid=? AND logindate=?
GROUP BY serverid,channel
SQL;
    	$query = $this->db_sdk->query($sql_login, [$this->appid, $date]);*/
        $sql_login = <<<SQL
SELECT appid,serverid,channel,COUNT(distinct accountid) as total_online_num FROM u_login_$date
WHERE appid=?
GROUP BY serverid,channel
SQL;
        $this->db_sdk->reconnect();
        $query = $this->db_sdk->query($sql_login, [$this->appid]);
        $data1 = array();
        if($query) $data1 = $query->result_array();
        $data = [];
        foreach ($data1 as $item) {
            $key = $item['appid'].'_'.$item['serverid'].'_'.$item['channel'];
            $data[$key]['total_online_num'] = $item['total_online_num'];
        }

        $sql = <<<SQL
SELECT appid,serverid,channel,online_date as date,SUM(online) AS total_online_time,COUNT(distinct accountid) as total_online_num FROM u_dayonline
WHERE appid=? AND online_date=?
GROUP BY serverid,channel
SQL;
        $this->db_sdk->reconnect();
        $query = $this->db_sdk->query($sql, [$this->appid, $date]);
        $data2 = $query->result_array();
        foreach ($data2 as $item) {
            $key = $item['appid'].'_'.$item['serverid'].'_'.$item['channel'];
            $data[$key]['total_online_time'] = $item['total_online_time'];
        }

        if (empty($data)) return false;
        foreach ($data as $key=>$val) {
            list($appid, $serverid, $channel) = explode('_', $key);
            $save_data[] = [
                'appid'=>$appid,
                'serverid'=>$serverid,
                'channel'=>$channel,
                'date'=>$date,
                'total_online_time'=>isset($val['total_online_time']) ? $val['total_online_time'] : 0,
                'total_online_num'=>isset($val['total_online_num']) ? $val['total_online_num'] : 0,
            ];
        }
        //print_r($save_data);
        //return;
        if (count($save_data)) {
            $this->db->insert_batch('sum_online_avg_day', $save_data);
        }
        return true;
    }

    public static function halfSearch($array ,$search) {
        $len = count($array);
        $low = 0 ;
        $high= $len - 1 ;
        if($search <$array[$low] || $search > $array[$high]){
            return false ;
        }
        while ($low <= $high){
            $mid = floor(($high + $low)/2) ;
            //echo $mid . "\n";
            if($search>$array[$mid] && $search<=$array[$mid+1] ) {
                return $mid ;
            }
            else if ($array[$mid]>=$search){
                $high = $mid - 1 ;
            }
            else if($array[$mid]<$search){
                $low  = $mid + 1 ;
            }
            else {
                return 0 ;
            }
        }
        return false ;
    }
}