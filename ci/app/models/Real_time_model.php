<?php
//define('LOG_QUERY_SQL', 'real_time');

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/7
 * Time: 22:04
 */
class Real_time_model extends CI_Model
{
    private $appid;
    private $t1;
    private $t2;
    private $hour;
    private $db_sdk;
    private $db;

    const TBL_NEW_ROLES = 'sum_newroles_hour';
    const TBL_NEW_PLAYERS = 'sum_newplayer_hour';
    const TBL_REGISTER = 'sum_register_hour';
    const TBL_LOGIN    = 'sum_login_hour';
    const TBL_ONLINE   = 'sum_online_hour';
    const TBL_DEVICE   = 'sum_device_active_hour';
    const TBL_INCOME   = 'sum_income_hour';
    const TBL_DAY_ONLINE = 'sum_active_hour';

    public function init($appid, $t1, $t2, $hour, $date, $db_sdk=null, $db=null)
    {
        $this->appid    = $appid;
        $this->t1       = $t1;
        $this->t2       = $t2;
        $this->hour     = $hour;
        $this->date     = $date;
        $this->db_sdk   = $db_sdk;
        $this->db       = $db;
    }

    /**
     * 新获取时间段内最高在线
     *
     * @param $appid
     * @param $date1
     * @param $date2
     * @param int $serverid
     * @return mixed
     * @author 王涛 --20170118
     */
    public function online_new($where, $field = '', $group='') {
    	if(!$field){
    		$field = '*';
    	}
    	$db_sdk = $this->load->database('sdk', TRUE);
    	$sql = <<<SQL
    SELECT $field FROM `online`  WHERE appid='{$where['appid']}'
SQL;
    	if($where['daytime']){
    		$sql .= " and daytime like '{$where['daytime']}%'";
    	}
    	if($where['serverid']){
    		$sql .= " and serverid = {$where['serverid']}";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	$query = $db_sdk->query($sql);
    	return $query->result_array();
    }
    /**
     * 获取注册数据
     *
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function get_perhour($date1, $date2, $serverid, $channel, $table)
    {
        $sql = "SELECT SUM(cnt) as cnt,`hour`,`date` FROM `{$table}`";
        if ($table==self::TBL_INCOME) {
            $sql = "SELECT SUM(money) as cnt,`hour`,`date` FROM `{$table}`";
        }
        else if ($table==self::TBL_ONLINE) {
            $sql = "SELECT sum(cnt) as cnt,`hour`,`date` FROM `{$table}`";
        }
        $sql .= " WHERE appid=? AND `date` BETWEEN ? AND ?";

        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN (".implode(',', $serverid).")";
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";

        $sql .= " GROUP BY `date`,`hour` ORDER BY `date` ASC,`hour` ASC";
        //echo $sql;
        $query = $this->db->query($sql,[
            $this->appid,
            $date1,
            $date2,
        ]);
        return $query->result_array();
    }

    public function TransRate()
    {

    }

    /**
     * 服务器实时在线数据获取
     *
     * @param $appid
     * @param $date1
     * @param $date2
     * @param int $serverid
     * @return mixed
     */
    public function online_rt($appid, $date1,$date2,$serverid=0) {
        $db_sdk = $this->load->database('sdk', TRUE);
        $sql = <<<SQL
    SELECT * FROM `online`  WHERE appid=$appid
    AND daytime BETWEEN $date1 AND $date2
SQL;
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";
        $sql .= "  GROUP BY serverid order by serverid asc";
        $query = $db_sdk->query($sql);
        return $query->result_array();
    }
    public function online($appid, $date1, $date2, $serverid=0)
    {
        $db_sdk = $this->load->database('sdk', TRUE);
        $sql = <<<SQL
    SELECT MAX(MaxOnline) AS max_online, MAX(WorldMaxOnline) as max_world_online
    FROM `online`
    WHERE appid='$appid' AND daytime BETWEEN $date1 AND $date2
SQL;
        if ($serverid>0) $sql .= " AND serverid=$serverid";
        $sql .= "  GROUP BY serverid";
        //echo $sql;
        $query = $db_sdk->query($sql);
        return $query->result();
    }

    public function hour_count($table)
    {
        $t1 = $this->t1;
        $t2 = $this->t2;
        $time_col = 'created_at';
        $cnt = '*';
        switch ($table) {
            case self::TBL_NEW_ROLES:
                //$time_col = 'role_create_time';
                $time_col = 'created_at';
                $sdk_table = 'u_roles';
                break;
            case self::TBL_NEW_PLAYERS:
                //$time_col = 'role_create_time';
                $time_col = 'created_at';
                $sdk_table = 'u_players';
                break;
            case self::TBL_REGISTER:
                //$time_col = 'created_at';
                $sdk_table = 'u_register';
                $cnt       = 'DISTINCT accountid';
                break;
            case self::TBL_DEVICE:
                $sdk_table = 'u_device_active';
                break;
            case self::TBL_INCOME:
                $sdk_table = 'u_paylog';
                break;
            case self::TBL_LOGIN:
                $sdk_table = 'u_login_'.$this->date;
                break;
            case self::TBL_DAY_ONLINE:
                $sdk_table = 'u_dayonline';
                $cnt       = 'DISTINCT accountid';
                break;
            case self::TBL_ONLINE:
                //$sdk_table = 'u_dayonline';
                $sdk_table = 'online';
                $t1 = date('ymdHi',$this->t1);
                $t2 = date('ymdHi',$this->t2);
                break;
            default:
                break;
        }

        $sql = <<<SQL
        SELECT appid,COUNT($cnt) AS cnt,serverid,channel,{$this->hour} as 'hour',{$this->date} as 'date' FROM $sdk_table
        WHERE appid=? AND `$time_col` BETWEEN ? AND ?
        GROUP BY serverid,channel
SQL;
        if ($table==self::TBL_INCOME) {
            $sql = <<<SQL
        SELECT appid,SUM(money) AS money,serverid,channel,{$this->hour} as 'hour',{$this->date} as 'date' FROM u_paylog
        WHERE appid=? AND created_at BETWEEN ? AND ?
        GROUP BY serverid,channel
SQL;
        }
        elseif ($table == self::TBL_ONLINE)
        {
            $sql = <<<SQL
    SELECT appid,serverid,  MAX(online) AS cnt, {$this->hour} as 'hour',{$this->date} as 'date'
    FROM `online`
    WHERE appid=? AND daytime BETWEEN ? AND ?
    GROUP BY serverid
SQL;
        }

        $query = $this->db_sdk->query($sql, [
            (int)$this->appid,
            $t1,
            $t2,
        ]);
        if($query)
        $data = $query->result_array();
        if (!$data) return false;
        echo $sql;
        print_r([
            $this->appid,
            $t1,
            $t2,
        ]);
        print_r($data);
        parent::log( "table:$table,data=".json_encode($data),  APPPATH . "/logs/hour_count.log");
        $this->db->insert_batch($table, $data);
    }
    /**
     * 设备激活数——根据mac去重
     */
    public function DeviceActiveData($appid, $timestamp, $timestamp2, $channel='' ,$by_channel=false)
    {
    	error_reporting(0);
    	$grp = 'date';
    	$field='';
    	if($by_channel){
    		$field = ',a.channel';
    		$grp = 'a.channel';
    	}
        $db_sdk = $this->load->database('sdk', TRUE);
        $where = "WHERE appid=$appid AND created_at BETWEEN $timestamp AND $timestamp2";
        /*$sql = <<<SQL
SELECT COUNT(*) AS cnt,from_unixtime(`created_at`, '%Y%m%d%k') as gdate,from_unixtime(`created_at`, '%Y%m%d') as date,from_unixtime(`created_at`, '%k') as hour FROM u_device_unique
SQL;*/
        $sql = <<<SQL
SELECT COUNT(*) AS cnt,from_unixtime(`created_at`, '%Y%m%d') as date $field FROM u_device_unique a
SQL;
        if (is_numeric($channel) && $channel>0) $where .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $where .= " AND channel IN(".implode(',', $channel).")";
        //$sql .= " $where GROUP BY gdate";
        $sql .= " $where GROUP BY $grp";
        $query_device = $db_sdk->query($sql);
        $sql_reg_uk_mac = <<<SQL
SELECT COUNT(distinct u.mac) AS cnt,from_unixtime(a.`created_at`, '%Y%m%d') as date $field from u_register u inner join  u_device_unique a on u.mac=a.mac 
        		and  from_unixtime(u.`created_at`, '%Y%m%d')=from_unixtime(a.`created_at`, '%Y%m%d')
 where u.created_at BETWEEN $timestamp and $timestamp2 and a.created_at BETWEEN $timestamp and $timestamp2     		
SQL;
        if (is_numeric($channel) && $channel>0) $sql_reg_uk_mac .= " AND u.channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql_reg_uk_mac .= " AND u.channel IN(".implode(',', $channel).")";
        $sql_reg_uk_mac .= " GROUP by $grp";
        $output = array();
        $query_reg = $db_sdk->query($sql_reg_uk_mac);
        if ($query_reg) $output['register'] = $query_reg->result_array();
        if ($query_device) $output['device'] = $query_device->result_array();
        return $output;
    }
}