<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/2/21
 * Time: 10:21
 *
 * 用户注册模型
 */

include_once __DIR__.'/base_model.php';

class register_model extends Base_model
{
    private $table = 'u_register';
    const TBL_LOGIN = 'u_login_new';
    const TBL_REG   = 'u_register';

    public function getRegisterDay($appid, $date1, $date2, $serverid=null, $channel=null, $by_channel=false)
    {
        return $this->getRegisterAndRole($appid, $date1, $date2, $serverid, $channel, 'sum_register_day', $by_channel);
    }
    public function getRoleDay($appid, $date1, $date2, $serverid=null, $channel=null, $by_channel=false)
    {
        return $this->getRegisterAndRole($appid, $date1, $date2, $serverid, $channel, 'sum_newrole_day', $by_channel);
    }

    /**
     * 获取注册或者角色创建数据
     *
     * @param int $appid
     * @param int $date1
     * @param int $date2
     * @param null $serverid
     * @param null $channel
     * @param string $table
     * @param bool $by_channel
     * @return mixed
     */
    public function getRegisterAndRole($appid, $date1, $date2, $serverid=null, $channel=null, $table='sum_register_day', $by_channel=false)
    {
        $grp = 'date';
        if ($by_channel==1) $grp = 'channel';
        elseif($by_channel==2)$grp = 'serverid';
        $sql = <<<SQL
SELECT `$grp`, sum(cnt) as cnt FROM $table
WHERE appid=$appid AND `date` BETWEEN $date1 AND $date2
SQL;

        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";

        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        $sql .= " group by `$grp` order by `$grp` asc";
        //echo $sql;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * 获取充值总金额
     *
     *@author 王涛 20170401
     */
    public function register_pay_data($where=array(),$field='*',$group='',$order='')
    {
    	$sql = "SELECT a.channel,SUM(money) s FROM `u_register` a inner JOIN u_paylog b on a.accountid=b.accountid where 
    			a.created_at between {$where['begintime']} and {$where['endtime']} AND b.created_at between {$where['paybegintime']} and {$where['payendtime']}";
    	if($where['channels']){
    		$sql .= " AND a.channel IN(".implode(',', $where['channels']).")";
    	}
    	$sql .= " group by a.channel";
    	$query = $this->db_sdk->query($sql);
    	if($query){
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 获取注册的账号ID新
     *
     * @author 王涛 20170401
     */
    public function register_account_new($where=array(),$field='*',$group='',$order='')
    {
    	$sql = "SELECT $field FROM u_register WHERE 1=1";
    	if($where['begintime']){
    		$sql .= " and created_at>={$where['begintime']}";
    	}
    	if($where['endtime']){
    		$sql .= " and created_at<={$where['endtime']}";
    	}
    	if($where['channels']){
    		$sql .= " AND channel IN(".implode(',', $where['channels']).")";
    	}
    	if($group){
    		$sql .= " group by $group";
    	}
    	$query = $this->db_sdk->query($sql);
    	if($query){
    		return $query->result_array();
    	}
    	return array();
    }
    /**
     * 获取注册的账号ID
     *
     * @return mixed
     */
    public function register_account()
    {
        $sql = "SELECT accountid FROM {$this->table} WHERE appid=? AND created_at BETWEEN ? AND ?";
        $query = $this->db_sdk->query($sql, [$this->appid, $this->bt, $this->et]);
        $accounts = [];
        foreach ( $query->result_array() as $row ) {
            $accounts[] = $row['accountid'];
        }
        return $accounts;
    }

    /**
     * 获取注册的账号ID
     *
     * @return mixed
     */
    public function roles_create_account()
    {
        //$sql = "SELECT accountid FROM u_roles WHERE appid=? AND role_create_time BETWEEN ? AND ?";
        //2016年9月19日：留存统计原来是通过u_roles来统计新增角色数量，现在要改成从u_players来统计
//        $sql = "SELECT accountid FROM u_players WHERE appid=? AND role_create_time BETWEEN ? AND ?";
        $sql = "SELECT accountid FROM u_register WHERE appid=? AND created_at BETWEEN ? AND ?";
        $query = $this->db_sdk->query($sql, [$this->appid, $this->bt, $this->et]);
        $accounts = [];
        if($query){
        	foreach ( $query->result_array() as $row ) {
        		$accounts[] = $row['accountid'];
        	}
        }
        
        return $accounts;
    }

    /**
     * 每天登录的玩家数--用于统计留存
     *
     *@author 王涛 --20170112
     */
    public function total_login($table_name='u_login_new', $accountid=null, $bt=0, $et=0)
    {
    	$where = "appid=? ";
    	$params = [$this->appid, ];
    	if (!is_null($accountid)) {
    		$where .= " AND accountid in ?";
    		$params[] = $accountid;
    	}
    	/*$bt = $bt>0 ? $bt : $this->bt;
    	$et = $et>0 ? $et : $this->et;
    	$params = array_merge($params, [$bt, $et]);
    	$where .= " AND created_at BETWEEN ? AND ?";*/
    	$sql = <<<SQL
SELECT count(DISTINCT accountid) AS cnt,serverid,channel FROM {$table_name}
WHERE $where
GROUP BY channel
SQL;
    	//print_r($params);
    	//echo $sql;
    	//exit;
    	$query = $this->db_sdk->query($sql, $params);
    	if($query)$data = $query->result_array();
    	$sql = <<<SQL
SELECT count(DISTINCT accountid) AS cnt,serverid,0 as channel FROM {$table_name}
WHERE $where
SQL;
    	$this->db_sdk->reconnect();
    	$query1 = $this->db_sdk->query($sql, $params);
    	$data1 = array();
    	if($query1)$data1 = $query1->result_array();
    	$data = array_merge($data,$data1);
    	//print_r($data);
    	return $data;
    }
    /**
     * 每天新注册的玩家总数
     *
     */
    public function total_register_or_login($table_name='u_register', $accountid=null, $bt=0, $et=0)
    {
        $where = "appid=? ";
        $params = [$this->appid, ];
        if (!is_null($accountid)) {
            $where .= " AND accountid in ?";
            $params[] = $accountid;
        }
        $bt = $bt>0 ? $bt : $this->bt;
        $et = $et>0 ? $et : $this->et;
        $params = array_merge($params, [$bt, $et]);
        $where .= " AND created_at BETWEEN ? AND ?";
        $sql = <<<SQL
SELECT count(DISTINCT accountid) AS cnt,serverid,channel FROM {$table_name}
WHERE $where
GROUP BY serverid,channel
ORDER BY serverid ASC
SQL;
        //print_r($params);
        //echo $sql;
        //exit;
        $query = $this->db_sdk->query($sql, $params);
        $data = $query->result_array();
        //print_r($data);
        return $data;
    }

    /**
     * 新注册帐号创建数(注册转化数量=新注册账号数-未登录游戏账号数量)
     */
    public function total_create()
    {
        $registers_id = $this->register_account();
        $transData = $logData = $regData = [];

        //总注册量
        $reg_count = $this->total_register_or_login('u_register');
        //总登录数
        $log_count = $this->total_register_or_login('u_login', $registers_id);

//        $log_count = $this->mongo_db->aggregate('login', $opts);
        if (count($reg_count)) {
            foreach ($reg_count as $item) {
                $_key = $item['serverid'].'_'.$item['channel'];
                $regData[$_key] = [
                    'serverid'=> $item['serverid'],
                    'channel' => $item['channel'],
                    'cnt'     => $item['cnt'],
                ];
            }
        }
        if (count($log_count)) {
            foreach ($log_count as $item) {
                $_key = $item['serverid'].'_'.$item['channel'];
                $logData[$_key] = [
                    'serverid'=> $item['serverid'],
                    'channel' => $item['channel'],
                    'cnt'     => $item['cnt'],
                ];
            }
        }
        $createRate = [];//创建率=注册转化率=新注册账号创建数/日新注册账号数
        foreach ($regData as $key=>$list) {
            //注册量-登录量=未登录量
            //注册量-未登录量=转化量

            $log_cnt = isset($logData[$key]['cnt']) ? $logData[$key]['cnt'] : 0;
            $trans = $list['cnt'] - ($list['cnt']- $log_cnt);
            $transData[$key]['cnt'] = $trans;
            $createRate[$key]['create'] = $trans;
            $createRate[$key]['signup'] = $list['cnt'];
        }
        return [$transData, $createRate];
    }

    /**
     * 获取注册数据
     *
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function get_perhour($date1, $date2)
    {
        $sql = <<<SQL
        SELECT SUM(cnt) as cnt,`hour`,`date` FROM sum_register_hour
        WHERE appid=? AND `date` BETWEEN ? AND ? GROUP BY `date`,`hour`
        ORDER BY `date` DESC,`hour` ASC
SQL;
        //echo $sql;exit;

        $query = $this->db1->query($sql,[
            $this->appid,
            $date1,
            $date2,
        ]);
        return $query->result_array();

    }
    private function c($table, $save_table, $date)
    {
        echo "$table Day Counts:\n";
        $tm_b = strtotime($date . '000000');
        $tm_e = strtotime($date . '235959');
        $sql = <<<SQL
SELECT COUNT(DISTINCT accountid) AS cnt,serverid,channel,appid,$date as date FROM $table
WHERE appid={$this->appid} and created_at>=$tm_b and created_at<=$tm_e GROUP BY serverid,channel
SQL;
//        $sql = <<<SQL
//SELECT SUM(cnt) AS cnt,serverid,channel,appid,`date` FROM $table
//WHERE appid={$this->appid} AND `date`=$date GROUP BY serverid,channel
//SQL;
        //echo $sql;
        //return false;
        //$query = $this->db->query($sql);
        echo $sql,"\n";
        $query = $this->db_sdk->query($sql);
        if (!$query) return false;
        $data = $query->result_array();
        //print_r($data);
        if (count($data)) {
        	$this->insert_batch($save_table, $data);
        }
    }
    public function insert_batch($table,$savedatas)
    {
    	if($savedatas){
    		$this->data_multi = $savedatas;
    	}
    	$sql = "insert into $table(".implode(',', array_keys($this->data_multi[0])).") values";
    	foreach ($this->data_multi as $key=>$value){
    		//$sql .= "(".implode(',', array_values($value))."),";
    		$sql .= "(".$this->implode_new(',', array_values($value))."),";
    	}
    	//$result = $this->db->query();
    	$msql = rtrim($sql,',') . " ON DUPLICATE KEY UPDATE ";
    	foreach ($this->data_multi[0] as $k=>$v){
    		$msql .= "$k=values($k),";
    	}
    	$result = $this->db1->query(rtrim($msql,','));
    	if($result){
    		return true;
    	}
    	return false;
    }
    private function implode_new($sp , $data){
    	$str = '';
    	foreach ($data as $v){
    		$str .= (is_numeric($v)?$v:"'{$v}'")."$sp";
    	}
    	return rtrim($str,"$sp");
    }
    public function day_counts($date)
    {
        //$this->c('sum_register_hour', 'sum_register_day',$date);//账号注册数
        //$this->c('sum_newroles_hour', 'sum_newrole_day', $date);//角色创建数
        //$this->c('sum_newplayer_hour', 'sum_newplayer_day', $date);//玩家创建数

        $this->c('u_register', 'sum_register_day',$date);//账号注册数
        $this->c('u_roles', 'sum_newrole_day', $date);//角色创建数
        $this->c('u_players', 'sum_newplayer_day', $date);//玩家创建数
        return true;
    }
    
    /*
     * 获取详细的注册数 
     */
    public function getRegDetail($appid, $date1, $date2, $serverid=null, $channel=null, $table='sum_register_day', $by_channel=false,$group="date")
    {      
     
        $sql = <<<SQL
SELECT  sum(cnt) as cnt,channel FROM $table
WHERE appid=$appid AND `date` BETWEEN $date1 AND $date2
SQL;
    
        if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
        elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";
    
        if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
        elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";
        $sql .= " group by `$group` order by `$group` asc";       
        $query = $this->db->query($sql);  
        return $query->result_array();
    }
    
}