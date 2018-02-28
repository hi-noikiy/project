<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/2
 * Time: 22:26
 */
ini_set ( 'display_errors', 'On' );
class Player_analysis_new_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	
		$this->db = $this->load->database ( 'sdk', TRUE );
	}
	
	/**
	 * 获取活跃数据
	 *
	 * @param string $appid        	
	 * @param int $date1        	
	 * @param int $date2        	
	 * @param null $serverid        	
	 * @param null $channel        	
	 * @return mixed
	 */
	public function pvpCombat($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
		$sql = "select $field from ueseeud  where 1=1";
		if ($where ['userid']) {
			$sql .= " AND userid =" . $where ['userid'];
		}
		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		
		if ($where ['vip_level']) {
		    $sql .= " AND (viplev>={$where ['viplev_min']} and viplev<={$where ['viplev_max']} )";
		}
		
		if ($where ['channels']) {
		    $sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}
		if( $where['date'] &&  $where['date2'] ){
		    $sql .= " AND (logdate>={$where ['date']} and logdate<={$where ['date2']})";
		}
	
		if ($group) {
			$sql .= " group by $group";
		}
		//echo $sql;die;
		
		
		$sql_2 = "select count(*) as bout_total from ueseeud  where 1=1";
		if ($where ['userid']) {
		    $sql_2 .= " AND userid =" . $where ['userid'];
		}
		if ($where ['serverids']) {
		    $sql_2 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		
		if ($where ['vip_level']) {
		    $sql_2 .= " AND (viplev>={$where ['viplev_min']} and viplev<={$where ['viplev_max']} )";
		}
		
		if ($where ['channels']) {
		    $sql_2 .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}
		if( $where['date'] &&  $where['date2'] ){
		    $sql_2 .= " AND (logdate>={$where ['date']} and logdate<={$where ['date2']})";
		}
		$sql_2 .= " group by bout_flag";
		
		
		
		
		
		
		
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			$result= $query->result_array ();
		}
		
		$query_2 = $this->db_sdk->query ( $sql_2 );
		if ($query_2) {
		    $result['bout']= $query_2->result_array ();
		}
		
		if ($result) {
		 return    $result;
		}
		 
		
		return false;
	}
	/*
	 * userVip
	 */
	public function userVip($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
	    if (! $field) {
	        $field = '*';
	    }
	    $sql = "select $field from user_vip  where 1=1";
	    
	    if ($where ['userid']) {
	        $sql .= " AND userid =" . $where ['userid'];
	    }
	    if ($where ['serverids']) {
	        $sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	    }
	
	    if ($where ['vip_level']) {
	        $sql .= " AND (viplev>={$where ['viplev_min']} and viplev<={$where ['viplev_max']} )";
	    }
	
	    if ($where ['channels']) {
	        $sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
	    }
	    if( $where['date']){
	        $sql .= " AND logdate={$where ['date']} ";
	    }
	
	    if ($group) {
	        $sql .= " group by $group";
	    }
	    $this->db_sdk = $this->load->database ( 'sdk', TRUE );
	    
	    
	    
	 //  $sql_0="SELECT  user_vip inner  FROM u_behavior_{$where['date']} i inner join item_trading_{$where['date']} u on i.behavior_id=u.id";
	   
	   
	   $sql_0="select sum(if(i.item_id=3 && i.type=1,i.item_num,null)) p3,sum(if(i.item_id=3 && i.type=0,i.item_num,null)) p39,
sum(if(i.item_id=1 && i.type=1,i.item_num,null)) p4,sum(if(i.item_id=1 && i.type=0,i.item_num,null)) p40,
sum(if(i.item_id=2 && i.type=1,i.item_num,null)) p5,sum(if(i.item_id=2 && i.type=0,i.item_num,null)) p41

from  u_behavior_{$where['date']} b inner JOIN item_trading_{$where['date']} i on i.behavior_id=b.id ";
	   
	   if ($where ['serverids']) {
	       $sql_0 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	   }
	    
	    
	    

	$sql_1="select count(if(number1=1,true,null)) p7,count(if(number1=1 || number1=3,true,null)) p7_2,
count(if(number2=2,true,null)) p43,count(if(number2=2 || number2=4,true,null)) p43_2,max(position1) p8,min(position1) p44 from user_vip  where type=1";
	if ($where ['serverids']) {
	    $sql_1 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	}
	
	
	
	
	$sql_2="select max(score1) p9,min(score1) p45,max(position1) p10,min(position1) p46,max(number1) p10_2,min(number1) P46_2 from user_vip  where type=2";
	if ($where ['serverids']) {
	    $sql_2 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	}
	
	
	$sql_3="select sum(score1) p11, max(position1) p12 from user_vip  where type=3";
	if ($where ['serverids']) {
	    $sql_3 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	}
	

	$sql_4="select sum(score1) p13 from user_vip  where type=4";
	if ($where ['serverids']) {
	    $sql_4 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	}
	
	 $sql_4 .= " group by accountid";
	 
	 
	 $sql_5="select count(*) p15,sum(score1) p16 from user_vip  where type=5";
	 if ($where ['serverids']) {
	     $sql_5 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	 }
	 
// $sql_6   $sql_7

	
	 $sql_8="select  avg(score1) p21,avg(score2) p22 from user_vip  where type=8";
	 if ($where ['serverids']) {
	     $sql_8 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	 }
	 
	 $sql_9="select  count(*) p23,max(position1) p24 from user_vip  where type=9";
	 if ($where ['serverids']) {
	     $sql_9 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	 }
	 
	 $sql_10="select  count(*) p25,max(position2) p26 from user_vip  where type=10";
	 if ($where ['serverids']) {
	     $sql_10 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	 }
	 
	
	 $sql_15="select param  from  user_vip v left JOIN   u_behavior_{$where['date']} b on v.accountid=b.accountid  WHERE b.vip_level=12 and v.logdate={$where['date']} and b.param<100";
	 $sql_15.=" GROUP BY b.param";
	
	 
	 
	   $query = $this->db_sdk->query ( $sql );
	if ($query) {
	    $result= $query->result_array ();
	}
	
	
	$query_0 = $this->db_sdk->query ( $sql_0 );
	if ($query_0) {
	    $result['type0']= $query_0->result_array ();
	}
	
	    $query_1 = $this->db_sdk->query ( $sql_1 );
	    if ($query_1) {
	        $result['type1']= $query_1->result_array ();
	    }
	    
	    $query_2 = $this->db_sdk->query ( $sql_2 );
	    if ($query_2) {
	        $result['type2']= $query_2->result_array ();
	    }
	
	    $query_3 = $this->db_sdk->query ( $sql_3 );
	    if ($query_3) {
	        $result['type3']= $query_3->result_array ();
	    }
	    
	    $query_4 = $this->db_sdk->query ( $sql_4 );
	    if ($query_4) {
	        $result['type4']= $query_4->result_array ();
	    }
	    
	    $query_5 = $this->db_sdk->query ( $sql_5 );
	    if ($query_5) {
	        $result['type5']= $query_5->result_array ();
	    }
	    
	    $query_8 = $this->db_sdk->query ( $sql_8 );
	    if ($query_8) {
	        $result['type8']= $query_8->result_array ();
	    }
	    $query_9 = $this->db_sdk->query ( $sql_9 );
	    if ($query_9) {
	        $result['type9']= $query_9->result_array ();
	    }
	    
	    $query_10 = $this->db_sdk->query ( $sql_10 );
	    if ($query_10) {
	        $result['type10']= $query_10->result_array ();
	    }
	     
	    
	    $sql_15 = $this->db_sdk->query ( $sql_15 );
	    if ($sql_15) {
	        $result['type15']= $sql_15->result_array ();
	    }
	    
	  
	    if ($result) {
	        return    $result;
	    }
	    	
	
	    return array();
	}
	
	
	/**
	 * adventure  20171207
	 *
	 */
	public function adventure($table, $where, $field, $group, $order, $limit) {
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
	    
	    
	    
	    $sql_2="select $field FROM u_userinfo where 1=1";
	    if($where['serverids']){
	        $sql_2 .= " and serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
	    }
	    if($where['channels']){
	        $sql_2 .= " and channel IN(" . implode ( ',', $where ['channels'] ) . ")";
	    }
	    if($where['date'] && $where['date2']){
	        $sql_2 .= " and (logdate>={$where['date']} and logdate<={$where['date2']})";
	    }
	    
	    if ($group) {
	        $sql_2 .= " group by vip_level,adventure_lev";
	    }
	     

	 
	    $query = $this->db->query ( $sql);
	     
	    if ($query) {
	       $result=$query->result_array ();
	    }
	    $query_2 = $this->db->query ( $sql_2);
	    if ($query_2) {
	        $result['more']=$query_2->result_array ();
	    }
	    return $result;
	  
	     
	}
	
	/*
	 * 	每日冒险结果统计  zzl 20171213
	 */
	public function everyAdventure($table, $where, $field, $group, $order, $limit) {

	    $date = $where['date'];
	    $itable   = "item_trading_$date";
	    $utable   = "u_behavior_$date";
	    $sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where act_id=99";
	    
	    if($where['params']){
	        $sql .= " AND param IN(".implode(',', $where['params']).")";
	    }
	    if($where['viplev_min']){
	        $sql .= " AND vip_level >=".$where['viplev_min'];
	    }
	    if($where['viplev_max']){
	        $sql .= " AND vip_level <=".$where['viplev_max'];
	    }
	    if($where['itemid']){
	        $sql .= "  AND item_id in ({$where['itemid']})";
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
	   // echo $sql;
	    $query = $this->db->query($sql);
	    
	    if ($query) return $query->result_array();
	    return false;	    
	}
	
	/*
	 * 冒险奖励结果统计
	 */
	public function adventureAward ($table, $where, $field, $group, $order, $limit) {
	
	    $date = $where['date'];
	    $itable   = "item_trading_$date";
	    $utable   = "u_behavior_$date";
	    $sql = "SELECT $field FROM $itable i inner join $utable u on i.behavior_id=u.id where act_id=99 and i.item_id=10036";
	     
	    if($where['params']){
	        $sql .= " AND param IN(".implode(',', $where['params']).")";
	    }
	    if($where['viplev_min']){
	        $sql .= " AND vip_level >=".$where['viplev_min'];
	    }
	    if($where['viplev_max']){
	        $sql .= " AND vip_level <=".$where['viplev_max'];
	    }
	    if($where['itemid']){
	        $sql .= "  AND item_id in ({$where['itemid']})";
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
	    
	// echo $sql;
	    $query = $this->db->query($sql);
	     
	    if ($query) return $query->result_array();
	    return false;
	}
}