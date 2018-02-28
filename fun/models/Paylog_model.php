<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 付费modle模型
* ==============================================
* @date: 2016-3-4
* @author: luoxue
* @version:
*/
//date_default_timezone_set('PRC');
// include __DIR__.'/base_model.php';
class Paylog_model extends CI_Model {
	protected $db1;
	protected $db_sdk;
	protected $appid;
	protected $bt;
	protected $et;
	public function __construct() {
		parent::__construct();
		$this->db1    = $this->load->database('default', TRUE);
        $this->db_sdk = $this->load->database('sdk', TRUE);
        $sql = "select openbt from auth_config where appid=10002";
        $query = $this->db1->query($sql);
        $result = array();
        if ($query) $result = $query->result_array();
        if(isset($result[0]['openbt'])){
        	$this->isbt = $result[0]['openbt'];
        }
	}
	public function init($appid,$bt, $et)
	{
		$this->appid = $appid;
		$this->bt = $bt;
		$this->et = $et;
	}
	/**
	 * 统计付费等级分布
	 * 
	 * @author 王涛 -- 20170208
	 */
	public function getPayLevelData($where = array() , $field = '*' ,$group = '' ,$order ='')
	{
		$sql = "SELECT $field FROM u_paylog where is_pay=0 and created_at BETWEEN {$where['begintime']} AND {$where['endtime']}";
		if($where['serverids']){
			$sql .= " AND serverid IN(".implode(',', $where['serverids']).")";
		}
		if($where['channels']){
			$sql .= " AND channel IN(".implode(',', $where['channels']).")";
		}
		if($where['accountid']){
			$sql .= " AND accountid in ({$where['accountid']})";
		}
		
		if(in_array($this->isbt, array('0','1'))){
			if($this->isbt == 0){
				$sql .= " AND isbt=0";
			}else{
				$sql .= " AND isbt in(0,1,6)";
			}
		}
		if($group){
			$sql .= " group by $group";
		}
		if($order){
			$sql .= " order by $order";
		}
		$query = $this->db_sdk->query($sql);
		if ($query) return $query->result_array();
		return array();;
	}
	public function get_list($serverId=0, $channelId=0) {
		$sql    = <<<SQL
SELECT * FROM u_paylog
WHERE appid=? AND created_at BETWEEN ? AND ?
SQL;
		
		if (is_numeric($serverId) && $serverId>0) 
			$sql .= " AND serverid=$serverId";
		elseif (is_array($serverId) && count($serverId)>0) 
			$sql .= " AND serverid IN(".implode(',', $serverId).")";
		if (is_numeric($channelId) && $channelId>0)
			$sql .= " AND channel=$channelId";
		elseif (is_array($channelId) && count($channelId)>0)
			$sql .= " AND channel IN(".implode(',', $channelId).")";

	if(in_array($this->isbt, array('0','1'))){
			if($this->isbt == 0){
				$sql .= " AND isbt=0";
			}else{
				$sql .= " AND isbt in(0,1)";
			}
		}

		$query= $this->db_sdk->query($sql, array($this->appid, $this->bt, $this->et));
		return $query->result_array();
	}
	
	public function getData($serverId=0, $channelId=0) {
		$sql    = <<<SQL
SELECT DATE_FORMAT(FROM_UNIXTIME(`created_at`),'%Y-%m-%d') as day, sum(money) as allmoney,count(distinct accountid ) as countAccountid, count(*) as count FROM u_paylog
WHERE appid=? AND created_at BETWEEN ? AND ?
SQL;
		$sql .= " AND is_pay=0";
		if (is_numeric($serverId) && $serverId>0) 
			$sql .= " AND serverid=$serverId";
		elseif (is_array($serverId) && count($serverId)>0) 
			$sql .= " AND serverid IN(".implode(',', $serverId).")";
		if (is_numeric($channelId) && $channelId>0)
			$sql .= " AND channel=$channelId";
		elseif (is_array($channelId) && count($channelId)>0)
			$sql .= " AND channel IN(".implode(',', $channelId).")";

		
	if(in_array($this->isbt, array('0','1'))){
			if($this->isbt == 0){
				$sql .= " AND isbt=0";
			}else{
				$sql .= " AND isbt in(0,1)";
			}
		}

		$sql .=" GROUP BY day";
		$query= $this->db_sdk->query($sql, array($this->appid, $this->bt, $this->et));
		return $query->result_array();
	}
	
	//获取1天的付费区服分布情况  zzl 20170729 
	public function getDataDetail($serverId=0, $channelId=0,$where) {
		$sql    = <<<SQL
SELECT serverid,DATE_FORMAT(FROM_UNIXTIME(`created_at`),'%Y-%m-%d') as day, sum(money) as allmoney,count(distinct accountid ) as countAccountid, count(*) as count FROM u_paylog
WHERE appid=? AND created_at BETWEEN ? AND ?
SQL;
		
		$sql .= " AND is_pay=0";
		if (is_numeric($serverId) && $serverId>0)
			$sql .= " AND serverid=$serverId";
			elseif (is_array($serverId) && count($serverId)>0)
			$sql .= " AND serverid IN(".implode(',', $serverId).")";
			if (is_numeric($channelId) && $channelId>0)
				$sql .= " AND channel=$channelId";
				elseif (is_array($channelId) && count($channelId)>0)
				$sql .= " AND channel IN(".implode(',', $channelId).")";
				$sql .=" GROUP BY serverid";
			
				$query= $this->db_sdk->query($sql, array($where['appid'],$where['begindate'], $where['enddate']));
	
				return $query->result_array();
	}
	
	public function getDayData($serverId=0, $channelId=0){
		$sql    = <<<SQL
SELECT money,accountid FROM u_paylog
WHERE appid=? AND created_at BETWEEN ? AND ?
SQL;
		$sql .= " AND is_pay=0";
		if (is_numeric($serverId) && $serverId>0)
			$sql .= " AND serverid=$serverId";
		elseif (is_array($serverId) && count($serverId)>0)
		$sql .= " AND serverid IN(".implode(',', $serverId).")";
		if (is_numeric($channelId) && $channelId>0)
			$sql .= " AND channel=$channelId";
		elseif (is_array($channelId) && count($channelId)>0)
		$sql .= " AND channel IN(".implode(',', $channelId).")";

	if(in_array($this->isbt, array('0','1'))){
			if($this->isbt == 0){
				$sql .= " AND isbt=0";
			}else{
				$sql .= " AND isbt in(0,1)";
			}
		}

		$this->bt = strtotime(date('Y-m-d 00:00:00', $this->et));
		$query= $this->db_sdk->query($sql, array($this->appid, $this->bt, $this->et));
		return $query->result_array();
	}
	
	public function getWeekData($serverId=0, $channelId=0){
		$sql    = <<<SQL
SELECT money,accountid FROM u_paylog
WHERE appid=? AND created_at BETWEEN ? AND ?
SQL;
		$sql .= " AND is_pay=0";
		if (is_numeric($serverId) && $serverId>0)
			$sql .= " AND serverid=$serverId";
		elseif (is_array($serverId) && count($serverId)>0)
		$sql .= " AND serverid IN(".implode(',', $serverId).")";
		if (is_numeric($channelId) && $channelId>0)
			$sql .= " AND channel=$channelId";
		elseif (is_array($channelId) && count($channelId)>0)
		$sql .= " AND channel IN(".implode(',', $channelId).")";

	if(in_array($this->isbt, array('0','1'))){
			if($this->isbt == 0){
				$sql .= " AND isbt=0";
			}else{
				$sql .= " AND isbt in(0,1)";
			}
		}

		$date = strtotime('Y-m-d', $this->et);
		 $this->bt = strtotime($date.'-7 day');
		$query= $this->db_sdk->query($sql, array($this->appid, $this->bt, $this->et));
		return $query->result_array();
	}
	
	public function getMonthData($serverId=0, $channelId=0){
		$sql    = <<<SQL
SELECT money,accountid FROM u_paylog
WHERE appid=? AND created_at BETWEEN ? AND ?
SQL;
		$sql .= " AND is_pay=0";
		if (is_numeric($serverId) && $serverId>0)
			$sql .= " AND serverid=$serverId";
		elseif (is_array($serverId) && count($serverId)>0)
		$sql .= " AND serverid IN(".implode(',', $serverId).")";
		if (is_numeric($channelId) && $channelId>0)
			$sql .= " AND channel=$channelId";
		elseif (is_array($channelId) && count($channelId)>0)
		$sql .= " AND channel IN(".implode(',', $channelId).")";

		
	if(in_array($this->isbt, array('0','1'))){
			if($this->isbt == 0){
				$sql .= " AND isbt=0";
			}else{
				$sql .= " AND isbt in(0,1)";
			}
		}

		$this->bt = strtotime(date('Y-m-01 00:00:00', $this->et));
		$query= $this->db_sdk->query($sql, array($this->appid, $this->bt, $this->et));
		if($query){
			return $query->result_array();
		}
		return array();
	}
	
	
	
	public function get_Behavior($serverId=0, $channelId=0){
		$sql    = <<<SQL
SELECT * FROM u_paylog
WHERE appid=? AND created_at BETWEEN ? AND ? 
SQL;
		$sql .= " AND is_pay=0";
		if (is_numeric($serverId) && $serverId>0)
			$sql .= " AND serverid=$serverId";
		elseif (is_array($serverId) && count($serverId)>0)
		$sql .= " AND serverid IN(".implode(',', $serverId).")";
		if (is_numeric($channelId) && $channelId>0)
			$sql .= " AND channel=$channelId";
		elseif (is_array($channelId) && count($channelId)>0)
		$sql .= " AND channel IN(".implode(',', $channelId).")";

	if(in_array($this->isbt, array('0','1'))){
			if($this->isbt == 0){
				$sql .= " AND isbt=0";
			}else{
				$sql .= " AND isbt in(0,1)";
			}
		}

        $query= $this->db_sdk->query($sql, array($this->appid, $this->bt, $this->et));
        return $query->result_array();
	}
	/*
	 * 统计付费玩家
	 */
	public function getPayAccountData($appid, $date1, $date2, $serverid=null, $channel=null){
		$bt = strtotime($date1.' 00:00:00');
		$en = strtotime($date2.' 23:59:59');
		$sql    = <<<SQL
SELECT count(distinct accountid ) as countAccount FROM u_paylog
WHERE appid=? AND created_at BETWEEN ? AND ?
SQL;
		if (is_numeric($serverid) && $serverid>0) $sql .= " AND serverid=$serverid";
		elseif (is_array($serverid) && count($serverid)>0) $sql .= " AND serverid IN(".implode(',', $serverid).")";
		 
		if (is_numeric($channel) && $channel>0) $sql .= " AND channel=$channel";
		elseif (is_array($channel) && count($channel)>0) $sql .= " AND channel IN(".implode(',', $channel).")";

		
	if(in_array($this->isbt, array('0','1'))){
			if($this->isbt == 0){
				$sql .= " AND isbt=0";
			}else{
				$sql .= " AND isbt in(0,1)";
			}
		}

		$returnData = array();
		//当天
		$dayData = $this->db_sdk->query($sql, [$appid, $bt, $en])->result_array();
	
		$returnData['dayCount'] = $dayData[0]['countAccount'];
		//七天
		$weekDate = strtotime($date1.'-7 day');
		$weekData = $this->db_sdk->query($sql, [$appid, $weekDate, $en])->result_array();
		$returnData['weekCount'] = $weekData[0]['countAccount'];
		//30天
		$monthDate = strtotime($date1.'-30 day');
		$monthData = $this->db_sdk->query($sql, [$appid, $monthDate, $en])->result_array();
		$returnData['monthCount'] = $monthData[0]['countAccount'];
		 
		return $returnData;
	}
	
}