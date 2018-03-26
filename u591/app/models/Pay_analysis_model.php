<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/2
 * Time: 22:26
 */
ini_set ( 'display_errors', 'On' );
class Pay_analysis_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}


	/*
	 * 活跃玩家充值积分统计  zzl 20170907
	 */
	public function bonusPoint($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = ''){
		if (! $field) {
			$field = '*';
		}
		$table="u_playerdata";
		$sql = "select $field from u_playerdata where 1=1";

		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['begindate']) {
			$sql .= " AND logdate={$where['begindate']}";
		}

		if ($group) {
			$sql .= " group by $group";
		}
		//echo $sql;
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return false;
	}

	/*
	 * 获取充值档位 chenyanbin 2017/11/20
	 */
	public function getGearPosition($bengin, $end)
	{
		$sql = "select
					s.`serverid`,
					s.`money`,
					FROM_UNIXTIME(s.`created_at`, '%Y-%m-%d') AS date,
					count(s.`money`) sum
				FROM
				 u_paylog AS s
				 WHERE
					s.`created_at`
					between $bengin and $end
				GROUP BY
					s.`money`, date
				ORDER BY date DESC
			";
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return false;
	}

	/*
	 * 获取充值档位 chenyanbin 2017/11/20
	 */
	public function getGearIframeRecord($bengin, $end)
	{
		$sql = "select
					s.`serverid`,
					s.`money`,
					count(s.`money`) sum
				FROM
				 u_paylog AS s
				 WHERE
					s.`created_at`
					between $bengin and $end
				GROUP BY
					s.`serverid`
				ORDER BY s.`serverid` DESC
			";

		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return false;
	}

	/*
	 * 首冲数据统计 chenyanbin 2017/11/20
	 */
	public function getFirstRecord($bengin, $end)
	{
		$sql = "SELECT
				FROM_UNIXTIME(s.`created_at`, '%Y-%m-%d') AS date,
				count(distinct(s.`accountid`)) total,
				sum(s.`money`) sum
			FROM
				u_paylog AS s
			WHERE
				s.`is_new` = 1
				and
				s.`created_at`
				between $bengin and $end
			GROUP BY
				date
			ORDER BY
		date DESC
		";
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return false;
	}

	/*
	 * 首冲数据统计iframe chenyanbin 2017/11/20
	 */
	public function getFirstIframeRecord($bengin, $end)
	{
		$sql = "SELECT
				lev,
				count(distinct(s.`accountid`)) total,
				sum(s.`money`) sum
			FROM
				paylog AS s
			WHERE
				s.`is_new` = 1
				and
				s.`created_at`
				between $bengin and $end
			GROUP BY
				lev
		";
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return false;
	}

	/*
	 * 新增账号付费 chenyanbin 2017/11/21
	 */
	public function getPayNew($bengin, $end,$where="")
	{
		$sql = "SELECT a.reg_date as date,COUNT(DISTINCT a.accountid) as ctotal,sum(b.money) as money from 
(SELECT reg_date,accountid,channel from u_register WHERE reg_date>=$bengin and reg_date<=$end) a,
(SELECT from_unixtime(`created_at`, '%Y%m%d') s,accountid,money FROM u_paylog 
WHERE from_unixtime(`created_at`, '%Y%m%d')>=$bengin and from_unixtime(`created_at`, '%Y%m%d')<=$end) b

WHERE a.reg_date=b.s and a.accountid=b.accountid $where

GROUP BY a.reg_date";
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return false;
	}

	/*
	 * 日期 付费总数 chenyanbin 2017/11/21
	 */
	public function getPayNewTotal($bengin, $end,$where="")
	{
		$sql = "SELECT
					count(distinct(`accountid`)) AS total,
					FROM_UNIXTIME(created_at, '%Y%m%d') AS date
				FROM
					u_register
				WHERE
					created_at BETWEEN $bengin AND $end $where
				GROUP BY
					FROM_UNIXTIME(created_at, '%Y%m%d')
				";
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return false;
	}
	
	/*
	 * 付费分析-任务栏扩容情况统计 zzl 2017 1211
	 */
	public function dilatation($table, $where, $field, $group, $order, $limit){
	    
	    

	    $sql="select $field FROM u_userinfo where 1=1";
	    if($where['serverids']){
	        $sql .= " and serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	    }
	    if($where['channels']){
	        $sql .= " and channel IN(" . implode ( ',', $where ['channels'] ) . ")";
	    }
	    if($where['date'] && $where['date2']){
	        $sql .= " and (logdate>={$where['date']} and logdate<={$where['date2']})";
	    }
	    
	    if ($group) {
	        $sql .= " group by $group";
	    }
	     
	    $this->db_sdk = $this->load->database ( 'sdk', TRUE );
	    $query = $this->db_sdk->query ( $sql);

	    if ($query) {
	        $result=$query->result_array ();
	    }

	    return $result;
		}
		
		
		
		public function refresh($table, $where, $field, $group, $order, $limit) {
		
		    $date = $where['date'];
		    $itable   = "item_trading_$date";
		    $utable   = "u_behavior_$date";
		    $sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where act_id=52";
		     
		    if($where['params']){
		        $sql .= " AND param IN(".implode(',', $where['params']).")";
		    }
	
		    if($where['userid']){
		        $sql .= " AND userid =".$where['userid'];
		    }
		     
		    if($where['serverids']){
		        $sql .= " AND u.serverid IN(".implode(',', $where['serverids']).")";
		    }
		    if($where['channels']){
		        $sql .= " AND u.channel IN(".implode(',', $where['channels']).")";
		    }

		    
		    if($group){
		        $sql .= " group by $group";
		    }
		    if($order){
		        $sql .= " order by $order";
		    }
		    
		   
		    $sql_2 = "SELECT u.vip_level,count(*) cnt,u.accountid FROM $itable i inner join $utable u on i.behavior_id=u.id where act_id=52 and param=3";
		    if($where['params']){
		        $sql_2 .= " AND param IN(".implode(',', $where['params']).")";
		    }
		    
		    if($where['userid']){
		        $sql_2 .= " AND userid =".$where['userid'];
		    }
		     
		    if($where['serverids']){
		        $sql_2 .= " AND u.serverid IN(".implode(',', $where['serverids']).")";
		    }
		    if($where['channels']){
		        $sql_2 .= " AND u.channel IN(".implode(',', $where['channels']).")";
		    }
		    
		    
		    if($group){
		        $sql_2 .= " group by u.accountid";
		    }
		    if($order){
		        $sql_2 .= " order by $order";
		    }
		    
		    
		    $sql_3 = "SELECT u.vip_level,sum(i.item_num) total FROM $itable i inner join $utable u on i.behavior_id=u.id where i.item_id=3 and i.type=1 and act_id=52 and param=2";
		    if($where['params']){
		    	$sql_3 .= " AND param IN(".implode(',', $where['params']).")";
		    }
		    
		    if($where['userid']){
		    	$sql_3 .= " AND userid =".$where['userid'];
		    }
		     
		    if($where['serverids']){
		    	$sql_3 .= " AND u.serverid IN(".implode(',', $where['serverids']).")";
		    }
		    if($where['channels']){
		    	$sql_3 .= " AND u.channel IN(".implode(',', $where['channels']).")";
		    }
	
		    
		    if($group){
		    	$sql_3 .= " group by u.vip_level";
		    }
		    if($order){
		    	$sql_3 .= " order by $order";
		    }
		    //i.item_id=3 and i.type=1 and param=2 group by u.vip_level
		  //  echo $sql_3;
		 
		     $this->db = $this->load->database ( 'sdk', TRUE );
		    $query = $this->db->query($sql);
		     
		    if ($query) {
		      $result=  $query->result_array();
		    }
		    
		    
		    $query_2 = $this->db->query($sql_2);
		     
		    if ($query_2) {
		        $result['more']=  $query_2->result_array();
		    }
		    
		    

		    $query_3 = $this->db->query($sql_3);
		     
		    if ($query_3) {
		    	$result['more_3']=  $query_3->result_array();
		    }
		    return  $result;
		    return false;
		     
		}

}