<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 2016/11/12
 * Time: 15:19
 *
 * 统计游戏服务器发送过来的数据,汇总等
 */
class PropertyData extends CI_Model {
	protected $db_sdk = null;
	public function __construct() {
		parent::__construct ();
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
	}
	/**
	 * 技能属性精华购买统计
	 *
	 * @author zzl 20170627
	 */
	public function buynumber($table = '', $where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
		$date0 = $where ['date'];
		
		if($where['beginserver'] && $where['endserver']){
			$server_list= $this->db_sdk->query("select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}");		
			 
			if($server_list){
				foreach ($server_list->result_array() as $k=>$v ){
		
					$server_list_new.=$v['serverid'].',';
				}
				$server_list_new=rtrim($server_list_new,',');
			}
			 
		}
		
	
		
		
		$sql = "select $field from u_behavior_{$where['date']} a inner join item_trading_{$where['date']} b on a.id=b.behavior_id and b.item_id=3 and a.act_id=118 where 1=1";
		//$sql = "select $field,COUNT(DISTINCT u.serverid,u.accountid) as active  FROM u_login_{$date0} u inner join u_behavior_{$where['date']} a on u.accountid=a.accountid inner join item_trading_{$where['date']} b on a.id=b.behavior_id  and b.item_id=3 and a.act_id=118  where 1=1";
		
		/*
		 * if($where['begindate']){
		 * $sql .= " and a.created_at >= {$where['begindate']}";
		 * }
		 * if($where['enddate']){
		 * $sql .= " and a.created_at <= {$where['enddate']}";
		 * }
		 */
		if($server_list){
			$sql .= " AND a.serverid IN($server_list_new)";
		}
		if ($where ['serverids']) {
			$sql .= " AND a.serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($group) {
			$sql .= " group by $group";
		}
		if ($order) {
			$sql .= " order by $order";
		}
		if ($limit) {
			$sql .= " limit $limit";
		}
	
		$query = $this->db_sdk->query ($sql);
	
		$result = array ();
		if ($query) {
			$result = $query->result_array ();
		}
		
		return $result;
	}
}
