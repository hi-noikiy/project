<?php

/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/7
 * Time: 22:04
 */
class Data_analysis_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
	}

	/*
	 * 生命周期价值统计 之 创建角色总数 zzl 20170630
	 */
	public function lifePeriod($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}

		$where ['begindate'] = strtotime ( $where ['begindate'] );
		$where ['enddate'] = strtotime ( $where ['enddate'] ) + 86400;

		if (empty ( $where ['beginserver'] ) || empty ( $where ['endserver'] )) {

			$sql = "select $field from u_last_login b,(SELECT accountid,serverid from u_roles where role_create_time>={$where['begindate']} and role_create_time<{$where['enddate']}) a where a.accountid=b.accountid AND a.serverid=b.serverid AND a.accountid>1000 and b.last_login_time>={$where['begindate']} and 1=1";
		} else {
			$sql = "select b.viplev,COUNT(*) as total_role from u_last_login b,
		(SELECT accountid,e.serverid from u_roles e,(select s.serverid,serverdate from server_date s where s.serverdate>={$where['beginserver']} and s.serverdate<={$where['endserver']})d
				where e.serverid=d.serverid and role_create_time>={$where['begindate']} and role_create_time<{$where['enddate']}) a
				where a.accountid=b.accountid AND a.serverid=b.serverid AND a.accountid>1000 ";

			/*
			 * $sql="SELECT b.viplev,COUNT(*) FROM `u_roles` a,u_last_login b
			 * WHERE a.serverid in (SELECT serverid from server_date s where s.serverdate>={$where['beginserver']} and s.serverdate<={$where['endserver']} ) and a.role_create_time>={$where['begindate']} and a.role_create_time<={$where['enddate']} AND a.accountid=b.accountid and a.serverid=b.serverid
			 * AND b.last_login_time>={$where['begindate']} and a.accountid>1000";
			 */
		}

		/*
		 * if($where['begindate']){
		 * $sql .= " and a.role_create_time >= {$where['begindate']}";
		 * }
		 * if($where['enddate']){
		 * $sql .= " and arole_create_time <= {$where['enddate']}";
		 * }
		 */
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
echo $sql;
		$query = $this->db_sdk->query ( $sql );

		if ($query) {
			return $query->result_array ();
		}
		return array ();
	}

	/*
	 * 生命周期价值统计 之 各个VIP留存人数 zzl 20170630
	 */
	public function wastageNum($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
		$group = 'b.viplev';

		$where ['begindate'] = strtotime ( $where ['begindate'] );
		$where ['enddate'] = strtotime ( $where ['enddate'] ) + 86400;
		$where ['login_time'] = strtotime ( date ( 'Ymd', strtotime ( "-6 days" ) ) );

		if (empty ( $where ['beginserver'] ) || empty ( $where ['endserver'] )) {

			$sql = "select b.viplev,COUNT(*) leave_num from u_last_login b,
		(SELECT accountid,serverid from u_roles where role_create_time>={$where['begindate']} and role_create_time<{$where['enddate']}) a 
		where a.accountid=b.accountid AND a.serverid=b.serverid and b.last_login_time<={$where['login_time']} AND a.accountid>1000 and 1=1";
		} else {
			$sql = "select b.viplev,COUNT(*) leave_num from u_last_login b,
			(SELECT accountid,e.serverid from u_roles e,(select s.serverid,s.serverdate from server_date s where s.serverdate>={$where['beginserver']} and s.serverdate<={$where['endserver']}) d where d.serverid=e.serverid and role_create_time>={$where['begindate']} and role_create_time<{$where['enddate']}) a 
			where a.accountid=b.accountid AND a.serverid=b.serverid and b.last_login_time<{$where['login_time']} AND a.accountid>1000  and 1=1";
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

		$query = $this->db_sdk->query ( $sql );

		if ($query) {
			return $query->result_array ();
		}
		return array ();
	}
	/*
	 * 生命周期价值统计 之 在选择的时间段内各vip充值总额 zzl 20170630
	 */
	public function totalPay($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
		$group = 'b.viplev';
		$where ['begindate'] = strtotime ( $where ['begindate'] );
		$where ['enddate'] = strtotime ( $where ['enddate'] ) + 86400;
		$where ['login_time'] = strtotime ( date ( 'Ymd', strtotime ( "-6 days" ) ) );
		if (empty ( $where ['beginserver'] ) || empty ( $where ['endserver'] )) {

			$sql = "select viplev,sum(c.money) total_pay,COUNT(DISTINCT a.accountid) pay_user_num from u_last_login b, u_paylog c,
			 (SELECT accountid,serverid from u_roles  where role_create_time>={$where['begindate']} and role_create_time<{$where['enddate']}) a
			 where a.accountid=b.accountid AND a.serverid=b.serverid  and a.accountid>1000 and a.serverid=c.serverid and b.accountid=c.accountid and b.last_login_time<={$where['login_time']}  and 1=1";
		} else {

			$sql = "select viplev,sum(c.money) total_pay,COUNT(DISTINCT a.accountid) pay_user_num from u_last_login b, u_paylog c,
			(SELECT accountid,e.serverid from u_roles e,(select s.serverid,s.serverdate from server_date s where s.serverdate>={$where['beginserver']} and s.serverdate<={$where['endserver']}) d  where d.serverid=e.serverid and role_create_time>={$where['begindate']} and role_create_time<{$where['enddate']}) a
			where a.accountid=b.accountid AND a.serverid=b.serverid  and a.accountid>1000 and a.serverid=c.serverid and b.accountid=c.accountid and b.last_login_time<={$where['login_time']} and 1=1";
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

		$query = $this->db_sdk->query ( $sql );

		if ($query) {
			return $query->result_array ();
		}
		return array ();
	}

	/*
	 * 生命周期价值统计 之 在选择的时间段内各vip存在天数 zzl 20170630
	 */
	public function totalDay($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
		$group = 'b.viplev';
		$where ['begindate'] = strtotime ( $where ['begindate'] );
		$where ['enddate'] = strtotime ( $where ['enddate'] ) + 86400;
		$where ['login_time'] = strtotime ( date ( 'Ymd', strtotime ( "-6 days" ) ) );

		if (empty ( $where ['beginserver'] ) || empty ( $where ['endserver'] )) {

			$sql = "select b.viplev,sum(b.last_login_time-a.role_create_time) total_day,COUNT(DISTINCT a.accountid) total_user from u_last_login b,
			 (SELECT role_create_time,accountid,serverid from u_roles  where role_create_time>={$where['begindate']} and role_create_time<{$where['enddate']}) a
			 	where a.accountid=b.accountid AND a.serverid=b.serverid and b.last_login_time<={$where['login_time']} and a.accountid>1000 ";
		} else {
			$sql = "select b.viplev,sum(b.last_login_time-a.role_create_time) total_day,COUNT(DISTINCT a.accountid) total_user from u_last_login b,
			(SELECT role_create_time,accountid,e.serverid from u_roles e,(select s.serverid,s.serverdate from server_date s where s.serverdate>={$where['beginserver']} and s.serverdate<={$where['endserver']}) d  where d.serverid=e.serverid and role_create_time>={$where['begindate']} and role_create_time<{$where['enddate']}) a	
			where a.accountid=b.accountid AND a.serverid=b.serverid and a.accountid>1000 and b.last_login_time<={$where['login_time']}";
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

		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return array ();
	}

	/**
	 * vip登录情况 加上区服
	 *
	 * @param unknown $where
	 * @param string $field
	 * @param string $group
	 * @return multitype:
	 */
	public function viplogin($where = array()) {
		$date0 = $where ['date'];
		$date1 = date ( 'Ymd', strtotime ( "$date0 +1 days" ) );
		$date3 = date ( 'Ymd', strtotime ( "$date0 +2 days" ) );
		$date7 = date ( 'Ymd', strtotime ( "$date0 +6 days" ) );
		$dateminus7 = date ( 'Ymd', strtotime ( "$date0 -6 days" ) );
		$data ['day0'] = $data ['day1'] = $data ['day3'] = $data ['day7'] = array ();
		$wsql = '';
		if ($where ['serverids']) {
			$wsql .= " AND a.serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['channels']) {
			$wsql .= " AND a.channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}


		if ($where ['beginserver'] && $where ['endserver']) {
			$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );

			if ($server_list) {
				foreach ( $server_list->result_array () as $k => $v ) {

					$server_list_new .= $v ['serverid'] . ',';
				}
				$server_list_new = rtrim ( $server_list_new, ',' );
			}
		}

		if ($server_list) {
			$wsql .= " AND a.serverid IN($server_list_new)";
		}

		if ( $where ['lev_min']  && $where ['lev_max']) {
			$wsql .= " AND lev >= {$where['lev_min']} and lev <= {$where['lev_max']}";
		}

		$wsql .= " group by a.viplev";
		$sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a WHERE 1=1 " . $wsql; // 当天登录数

		$result = $this->db_sdk->query ( $sql );

		if ($result) {
			$data ['day0'] = $result->result_array ();
		}
		/*
		 * $sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a inner join u_login_{$date1} b on a.accountid=b.accountid WHERE 1=1 ".$wsql;//次日登录数
		 * $result = $this->db->query($sql);
		 * if($result){
		 * $data['day1'] = $result->result_array();
		 * }
		 * $sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a inner join u_login_{$date3} b on a.accountid=b.accountid WHERE 1=1 ".$wsql;//3日登录数
		 * $result = $this->db->query($sql);
		 * if($result){
		 * $data['day3'] = $result->result_array();
		 * }
		 * $sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a inner join u_login_{$date7} b on a.accountid=b.accountid WHERE 1=1 ".$wsql;//7日登录数
		 * $result = $this->db->query($sql);
		 * if($result){
		 * $data['day7'] = $result->result_array();
		 * }
		 */
		/*
		 * $sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a inner join u_login_{$dateminus7} b on a.accountid=b.accountid WHERE 1=1 ".$wsql;//7日登录数
		 * $result = $this->db->query($sql);
		 * if($result){
		 * $data['dayminus7'] = $result->result_array();
		 * }
		 */

		return $data;
	}
	public function wavePurchase($table = '', $where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
		$date0 = $where ['date'];

		if ($where ['beginserver'] && $where ['endserver']) {
			$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );

			if ($server_list) {
				foreach ( $server_list->result_array () as $k => $v ) {

					$server_list_new .= $v ['serverid'] . ',';
				}
				$server_list_new = rtrim ( $server_list_new, ',' );
			}
		}

		$sql = "select $field from u_behavior_{$where['date']} a inner join item_trading_{$where['date']} b on a.id=b.behavior_id and b.item_id=3 and b.type=1 and a.act_id=121 where 1=1";

		if ($server_list) {
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

		$query = $this->db_sdk->query ( $sql );

		$result = array ();
		if ($query) {
			$result = $query->result_array ();
		}

		return $result;
	}
	public function spirit($table = '', $where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {
		if (! $field) {
			$field = '*';
		}
		$date0 = $where ['date'];

		if ($where ['beginserver'] && $where ['endserver']) {
			$server_list = $this->db_sdk->query ( "select serverid from server_date where serverdate>={$where['beginserver']} and  serverdate<={$where['endserver']}" );

			if ($server_list) {
				foreach ( $server_list->result_array () as $k => $v ) {

					$server_list_new .= $v ['serverid'] . ',';
				}
				$server_list_new = rtrim ( $server_list_new, ',' );
			}
		}

		$sql = "select $field from u_behavior_{$where['date']}  where act_id=123";

		if ($server_list) {
			$sql .= " AND serverid IN($server_list_new)";
		}
		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['vip_level']) {
			$sql .= " and vip_level={$where['vip_level']}";
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

		$query = $this->db_sdk->query ( $sql );

		$result = array ();
		if ($query) {
			$result ['act_id_123'] = $query->result_array ();
		}

		$sql_param = "select id,count(*) as param_total,count(IF((RIGHT(param,1))=1,true,null))  as total_10001,count(IF((RIGHT(param,1))=2,true,null))  as total_10002,count(IF((RIGHT(param,1))=3,true,null))  as total_10003,count(*),count(IF((RIGHT(param,1))=4,true,null))  as total_10004,count(IF((RIGHT(param,1))=5,true,null))  as total_10005,count(*) from u_behavior_{$where['date']}  where act_id=123";

		if ($server_list) {
			$sql_param .= " AND serverid IN($server_list_new)";
		}
		if ($where ['serverids']) {
			$sql_param .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}

		if ($order) {
			$sql_param .= " order by $order";
		}
		if ($limit) {
			$sql_param .= " limit $limit";
		}

		if ($where ['vip_level']) {
			$sql_param .= " and vip_level={$where['vip_level']}";
		}

		$query = $this->db_sdk->query ( $sql_param );

		if ($query) {
			$result ['act_id_123_param'] = $query->result_array ();
			$result ['act_id_123_param'] = $result ['act_id_123_param'] [0];
		}

		$sql_117 = "select count(*) as count_num from u_behavior_{$where['date']}  where act_id=117";

		if ($server_list) {
			$sql_117 .= " AND serverid IN($server_list_new)";
		}
		if ($where ['serverids']) {
			$sql_117 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['vip_level']) {
			$sql_117 .= " and vip_level={$where['vip_level']}";
		}
		if ($group) {
			$sql_117 .= " group by $group";
		}
		if ($order) {
			$sql_117 .= " order by $order";
		}
		if ($limit) {
			$sql_117 .= " limit $limit";
		}

		$query = $this->db_sdk->query ( $sql_117 );

		if ($query) {
			$result ['act_117'] = $query->result_array ();
		}

		$sql_113 = "select count(*) as count_num from u_behavior_{$where['date']}  where act_id=113";
		if ($server_list) {
			$sql_113 .= " AND serverid IN($server_list_new)";
		}
		if ($where ['serverids']) {
			$sql_113 .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['vip_level']) {
			$sql_113 .= " and vip_level={$where['vip_level']}";
		}
		if ($group) {
			$sql_113 .= " group by $group";
		}
		if ($order) {
			$sql_113 .= " order by $order";
		}
		if ($limit) {
			$sql_113 .= " limit $limit";
		}

		$query = $this->db_sdk->query ( $sql_113 );

		if ($query) {
			$result ['act_113'] = $query->result_array ();
		}

		return $result;
	}
	public function genesis($where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {
		$group = 'vip_level';
		if (! $field) {
			$field = '*';
		}

	$sql = "select id,count(if(stonestep=0,true,null)) as stonestep0,count(if(stonestep=1,true,null)) as stonestep1,count(if(stonestep=2,true,null)) as stonestep2,count(if(stonestep=3,true,null)) as stonestep3,count(if(stonestep=4,true,null)) as stonestep4,count(if(stonestep=5,true,null)) as stonestep5,count(if(stonestep=6,true,null)) as stonestep6,count(if(stonestep=7,true,null)) as stonestep7,count(if(stonestep=8,true,null)) as stonestep8,count(if(stonestep=9,true,null)) as stonestep9,count(if(stonestep=10,true,null)) as stonestep10,stonetype,count(*) as total,sum(stonestep) as total_stonestep,stonestep,logdate,vip_level,account_id from
	    (select id,stonetype,account_id,stonestep,logdate,vip_level from game_stone where logdate>={$where['begindate']} and logdate<={$where['enddate']} and account_id>1000 and stonetype=0 GROUP BY account_id) g2 where 1=1";
		
		
		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
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

	
		$query = $this->db_sdk->query ( $sql );

		$result = array ();
		if ($query) {
			$result = $query->result_array ();
		}

		return $result;
	}


	//  亲密度珍肴养成统计   zzl  20170807
	public function intimacyCultivate($where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {
		$group = 'vip_level';
		if (! $field) {
			$field = '*';
		}

		$sql = "select vip_level,sum(attack_avg) as attack_avg,sum(defend_avg) as defend_avg,sum(special_attack_avg) as special_attack_avg,sum(life_avg) as life_avg,sum(special_defend_avg) as special_defend_avg,sum(speed_avg) as speed_avg,logdate from intimacy_{$where ['Ym']} where logdate>={$where['begindate']} and logdate<={$where['enddate']}";

		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
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
		$result = array ();
		$query = $this->db_sdk->query($sql);
		if ($query) {
			$result['intimacy'] = $query->result_array ();
		}
		$query_reset_num = $this->db_sdk->query ("select vip_level,count(*) as reset_num from u_behavior_{$where['date']}  where  act_id=130 GROUP BY vip_level");

		if ($query_reset_num) {
			$result['reset_num'] = $query_reset_num->result_array ();
		}


		$query_reset_cost = $this->db_sdk->query ("select a.vip_level,sum(b.item_num) as reset_cost,a.id from u_behavior_{$where['date']} a inner join item_trading_{$where['date']} b on a.id=b.behavior_id and b.type=1 and b.item_id=3 and  a.act_id=130 GROUP BY a.vip_level");

		if ($query_reset_cost) {
			$result['reset_cost'] = $query_reset_cost->result_array ();
		}



		return $result;
	}

	/*
	 * 福利活动各档次活动点击  zzl 20170809
	 */
	public function activityClick($where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {
		$group = 'viplev';
		if (! $field) {
			$field = '*';
		}
		$sql = "select viplev,COUNT(DISTINCT userid,accountid) as total_user,count(*) as total_time,logdate from activity_click_{$where['date']} where logdate>={$where['begindate']} and logdate<={$where['enddate']}";

		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}

		if ($where ['channels']) {
			$sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}

		if ( $where ['lev_min']  && $where ['lev_max']) {
			$sql .= " AND lev >= {$where['lev_min']} and lev <= {$where['lev_max']}";
		}


		if($where ['type']!=''){
			$sql .= " AND type={$where ['type']}";
		}
		if(!empty($where ['param'])){
			$sql .= " AND param={$where ['param']}";
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

		$query = $this->db_sdk->query ( $sql );

		$result = array ();
		if ($query) {
			$result = $query->result_array ();
		}

		return $result;
	}


	/*
	 * 洛托姆养成统计   zzl  20170811
	 */

	public function rotomCultivate($where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {

		$group = 'vip_level';
		if (! $field) {
			$field = '*';
		}

		$sql = "SELECT id,vip_level,count(*) as total_user,sum(rotom_grade) as total_grade,AVG(rotom_grade) as avg_grade,MAX(rotom_grade) as max_grade from intimacy_{$where['Ym']} where logdate>={$where['begindate']} and logdate<={$where['begindate']}";

		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
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
		$result = array ();
		$query = $this->db_sdk->query($sql);
		if ($query) {
			$result['first'] = $query->result_array ();
		}
		$query_second = $this->db_sdk->query("SELECT id,vip_level,rotom_grade from intimacy_{$where['Ym']} where logdate>={$where['begindate']} and logdate<={$where['enddate']}");
		if ($query_second) {
			$result['second'] = $query_second->result_array ();
		}

		return $result;

	}

	/*
	 * 社团每日捐献统计  zzl  20170811
	 */
	public function donate($where = array(), $field = '*', $group = 'vip_level', $order = '', $limit = '') {

		if (! $field) {
			$field = '*';
		}

		$sql = "select $field from u_behavior_{$where['date']} a inner join item_trading_{$where['date']} b on a.id=b.behavior_id and a.act_id=21 where 1=1";

		if ($server_list) {
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
		$query = $this->db_sdk->query ( $sql );

		$result = array ();
		if ($query) {
			$result = $query->result_array ();
		}

		return $result;


	}

	/*
	 * 活跃玩家社团VIP分布   20170829 zzl
	 */
	public  function  community($where,$field){



		$date0 = $where ['date'];

		$data ['day0'] = $data ['day1'] = $data ['day3'] = $data ['day7'] = array ();
		$wsql = '';
		if ($where ['serverids']) {
			$wsql .= " AND a.serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['channels']) {
			$wsql .= " AND a.channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}



		$wsql .= " group by serverid";
		$sql = "SELECT {$field} FROM u_login_{$date0} a WHERE 1=1 " . $wsql; // 当天登录数

		$result = $this->db_sdk->query ( $sql );

		if ($result) {
			$data = $result->result_array ();
		}


		return $data;

	}



	/**
	 * 每个服务器的活跃VIP分布
	 */
	public function activeVip($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '')  {
		$date0 = $where ['date'];
		$date1 = date ( 'Ymd', strtotime ( "$date0 +1 days" ) );
		$date3 = date ( 'Ymd', strtotime ( "$date0 +2 days" ) );
		$date7 = date ( 'Ymd', strtotime ( "$date0 +6 days" ) );
		$dateminus7 = date ( 'Ymd', strtotime ( "$date0 -6 days" ) );
		$data ['day0'] = $data ['day1'] = $data ['day3'] = $data ['day7'] = array ();
		$wsql = '';
		if ($where ['serverids']) {
			$wsql .= " AND a.serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['channels']) {
			$wsql .= " AND a.channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}

		if ($group) {
			$wsql .= " group by $group";
		}


		$sql = "SELECT $field FROM u_login_{$date0} a WHERE 1=1 " . $wsql; // 当天登录数
		//echo $sql;
		$result = $this->db_sdk->query ( $sql );

		if ($result) {
			$data = $result->result_array ();
		}

		return $data;
	}

	
	public function soul($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		$sql = "SELECT $field FROM u_userinfo  WHERE 1=1 "; // 当天登录数
		
		if ($where ['begindate'] && $where ['enddate']) {
			
			$sql .= " AND (logdate>={$where ['begindate']} and logdate<={$where ['enddate']})";
		}
		
		if ($where ['serverids']) {
			$sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		
		if ($where ['channels']) {
			$sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}
		
		if ($group) {
			$sql .= " group by $group";
		}
		
		$result = $this->db_sdk->query ( $sql );
		
		if ($result) {
			$data = $result->result_array ();
		}
		
		return $data;
	}
	
	/*
	 * g_servermerge
	 */
	public function idServerlist($table = '', $where = array(), $field = '*', $group = '', $order = '', $limit = '') {
		$sql = "SELECT id,idserverlist	FROM g_servermerge  WHERE 1=1 "; 
	

		$result = $this->db_sdk->query ( $sql );
	
		if ($result) {
			$data = $result->result_array ();
		}
	
		return $data;
	}
	
}