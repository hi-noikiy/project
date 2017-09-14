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
	
}