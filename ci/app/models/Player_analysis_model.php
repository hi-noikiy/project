<?php
/**
 * Created by PhpStorm.
 * User: cgp
 * Date: 16/3/2
 * Time: 22:26
 */
ini_set ( 'display_errors', 'On' );
class Player_analysis_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
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
	public function getUserinfo($where = array(), $field = '', $group = '') {
		if (! $field) {
			$field = '*';
		}
		$sql = "select $field from u_last_login l left join u_register r on l.accountid=r.accountid where 1=1";
		if ($where ['userid']) {
			$sql .= " AND userid =" . $where ['userid'];
		}
		if ($where ['serverids']) {
			$sql .= " AND l.serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['accountid']) {
			$sql .= " AND l.accountid={$where['accountid']}";
		}
		if ($where ['username']) {
			$sql .= " AND username='{$where['username']}'";
		}
		if ($group) {
			$sql .= " group by $group";
		}
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql );
		if ($query) {
			return $query->result_array ();
		}
		return false;
	}
	/**
	 * 统计设备激活和新注册的数据
	 *
	 * @param
	 *        	$appid
	 * @param
	 *        	$date1
	 * @param
	 *        	$date2
	 * @return array
	 */
	public function RegisterDevice($appid, $date1, $date2, $server_id = 0, $channel_id = 0) {
		// r.serverid,r.channel,
		$data1 = $this->queryDay ( 'sum_register_day', $appid, $date1, $date2, $server_id, $channel_id );
		// $data1 = $this->queryDay('sum_newrole_day', $appid, $date1, $date2, $server_id);
		$data2 = $this->queryDay ( 'sum_device_active_day', $appid, $date1, $date2, $server_id, $channel_id );
		return [ 
				'register' => $data1,
				'device' => $data2 
		];
		$data = [ ];
		foreach ( $data1 as $_data ) {
			$date [] = $_data ['date'];
			$data ['register'] [$_data ['date']] = $_data ['total'];
		}
		foreach ( $data2 as $_data ) {
			$date [] = $_data ['date'];
			$data ['device'] [$_data ['date']] = $_data ['total'];
		}
		return [ 
				'data' => $data,
				'date' => array_unique ( $date ) 
		];
		// print_r($data2);
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
	public function getActiveData($appid, $date1, $date2, $serverid = null, $channel = null) {
		$sql = <<<SQL
SELECT SUM(new_role) as new_role,SUM(dau) AS dau,sum(wau) AS wau,sum(mau) AS mau,
sum(dau_ac) as dau_ac,sum(wau_ac) as wau_ac,sum(mau_ac) as mau_ac,
sum(vip_role) as vip_role,sday FROM sum_au
WHERE appid=? AND sday BETWEEN ? AND ?
SQL;
		if (is_numeric ( $serverid ) && $serverid > 0)
			$sql .= " AND serverid=$serverid";
		elseif (is_array ( $serverid ) && count ( $serverid ) > 0)
			$sql .= " AND serverid IN(" . implode ( ',', $serverid ) . ")";
		
		if (is_numeric ( $channel ) && $channel > 0)
			$sql .= " AND channel=$channel";
		elseif (is_array ( $channel ) && count ( $channel ) > 0)
			$sql .= " AND channel IN(" . implode ( ',', $channel ) . ")";
		$sql .= " GROUP BY sday";
		$query = $this->db->query ( $sql, [ 
				$appid,
				$date1,
				$date2 
		] );
		if ($query) {
			return $query->result_array ();
		}
		return array ();
	}
	
	/**
	 * 获取活跃数据新
	 *
	 * @param string $appid        	
	 * @param int $date1        	
	 * @param int $date2        	
	 * @param null $serverid        	
	 * @param null $channel        	
	 * @return mixed
	 *
	 * @author 王涛 --20170221
	 */
	public function getActiveDataNew($where = array(), $field = '*', $group = '') {
		$sql = "SELECT $field FROM sum_au where 1=1";
		if ($where ['sday']) {
			$sql .= " AND sday={$where['sday']}";
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
		$query = $this->db->query ( $sql );
		if ($query)
			return $query->result_array ();
		return false;
	}
	
	
	
	/**
	 * 获取活跃数据    以区服分组 
	 *
	 * @param string $appid
	 * @param int $date1
	 * @param int $date2
	 * @param null $serverid
	 * @param null $channel
	 * @return mixed
	 */
	public function getActiveDataDetail($appid, $date1, $date2, $serverid = null, $channel = null,$group) {
		$sql = <<<SQL
SELECT serverid,SUM(new_role) as new_role,SUM(dau) AS dau,sum(wau) AS wau,sum(mau) AS mau,
sum(dau_ac) as dau_ac,sum(wau_ac) as wau_ac,sum(mau_ac) as mau_ac,
sum(vip_role) as vip_role,sday FROM sum_au
WHERE appid=? AND sday BETWEEN ? AND ?
SQL;
		if (is_numeric ( $serverid ) && $serverid > 0)
			$sql .= " AND serverid=$serverid";
			elseif (is_array ( $serverid ) && count ( $serverid ) > 0)
			$sql .= " AND serverid IN(" . implode ( ',', $serverid ) . ")";
	
			if (is_numeric ( $channel ) && $channel > 0)
				$sql .= " AND channel=$channel";
				elseif (is_array ( $channel ) && count ( $channel ) > 0)
				$sql .= " AND channel IN(" . implode ( ',', $channel ) . ")";
				$sql .= " GROUP BY {$group}";
				$query = $this->db->query ( $sql, [
						$appid,
						$date1,
						$date2
				] );			
				if ($query) {
					return $query->result_array ();
				}
				return array ();
	}
	
	
	
	/**
	 * 获取活跃账号数据
	 * 
	 * @author Guangpeng Chen
	 *         @date 2016-11-19
	 *        
	 * @param
	 *        	$appid
	 * @param
	 *        	$date1
	 * @param
	 *        	$date2
	 * @param null $serverid        	
	 * @param null $channel        	
	 * @param bool $by_channel
	 *        	是否按渠道分组统计
	 * @return mixed
	 */
	public function getRealAuData($appid, $date1, $date2, $serverid = null, $channel = null, $by_channel = false) {
		$grp = 'sday';
		if ($by_channel == 1)
			$grp = 'channel';
		elseif ($by_channel == 2)
			$grp = 'serverid';
		$sql = <<<SQL
SELECT SUM(new_role) as new_role,SUM(dau) AS dau,sum(wau) AS wau,
sum(mau) AS mau,sum(clean_dau) as clean_dau,$grp FROM sum_real_au
WHERE appid=? AND sday BETWEEN ? AND ?
SQL;
		if (is_numeric ( $serverid ) && $serverid > 0)
			$sql .= " AND serverid=$serverid";
		elseif (is_array ( $serverid ) && count ( $serverid ) > 0)
			$sql .= " AND serverid IN(" . implode ( ',', $serverid ) . ")";
		
		if (is_numeric ( $channel ) && $channel > 0)
			$sql .= " AND channel=$channel";
		elseif (is_array ( $channel ) && count ( $channel ) > 0)
			$sql .= " AND channel IN(" . implode ( ',', $channel ) . ")";
		$sql .= " GROUP BY $grp";
	return 	 $this->db->query ( $sql, [ 
				$appid,
				$date1,
				$date2 
		] )->result_array ();
		
	}
	/**
	 * 统计新注册的数据
	 *
	 * @param
	 *        	$appid
	 * @param
	 *        	$date1
	 * @param
	 *        	$date2
	 * @return array
	 */
	public function getRegisterData($appid, $date1, $date2, $serverid = null, $channel = null) {
		$sql = <<<SQL
        SELECT sum(cnt) as total from sum_register_day
        WHERE appid=? AND date BETWEEN ? AND ?
SQL;
		if (is_numeric ( $serverid ) && $serverid > 0)
			$sql .= " AND serverid=$serverid";
		elseif (is_array ( $serverid ) && count ( $serverid ) > 0)
			$sql .= " AND serverid IN(" . implode ( ',', $serverid ) . ")";
		
		if (is_numeric ( $channel ) && $channel > 0)
			$sql .= " AND channel=$channel";
		elseif (is_array ( $channel ) && count ( $channel ) > 0)
			$sql .= " AND channel IN(" . implode ( ',', $channel ) . ")";
		$returnData = array ();
		// 当天
		$dayData = $this->db->query ( $sql, [ 
				$appid,
				$date1,
				$date2 
		] )->result_array ();
		
		$returnData ['dayTotal'] = $dayData [0] ['total'];
		// 七天
		$weekDate = date ( 'Ymd', strtotime ( $date1 . '-7 day' ) );
		$weekData = $this->db->query ( $sql, [ 
				$appid,
				$weekDate,
				$date1 
		] )->result_array ();
		$returnData ['weekTotal'] = $weekData [0] ['total'];
		// 30天
		$monthDate = date ( 'Ymd', strtotime ( $date1 . '-30 day' ) );
		$monthData = $this->db->query ( $sql, [ 
				$appid,
				$monthDate,
				$date1 
		] )->result_array ();
		$returnData ['monthTotal'] = $monthData [0] ['total'];
		
		return $returnData;
	}
	/**
	 * 获取留存数据
	 *
	 * @param string $appid        	
	 * @param int $date1        	
	 * @param int $date2        	
	 * @param int $serverid        	
	 * @param int $channel        	
	 */
	public function getRemainData($appid, $date1, $date2, $serverid = null, $channel = null, $by_channel = false, $type = 0) {
		$grp = 'sday';
		if ($by_channel == 1)
			$grp = 'channel';
		elseif ($by_channel == 2)
			$grp = 'serverid';
		$sql = <<<SQL
SELECT $grp, sum(usercount) as usercount, sum(day1) as day1, sum(day2) as day2, sum(day3) as day3,
 sum(day4) as day4, sum(day5) as day5, sum(day6) as day6,
 sum(day7) as day7 , sum(day8) as day8, sum(day14) as day14,sum(day15) as day15,
  sum(day30) as day30 FROM sum_reserveusers_daily_new
  WHERE appid=? AND sday BETWEEN ? AND ?
SQL;
		$sql .= " AND channel>0";
		/*if (! $type && ! $channel) {
			$sql .= " AND channel=0";
		}*/
		if (is_numeric ( $serverid ) && $serverid > 0)
			$sql .= " AND serverid=$serverid";
		elseif (is_array ( $serverid ) && count ( $serverid ) > 0)
			$sql .= " AND serverid IN(" . implode ( ',', $serverid ) . ")";
		
		if (is_numeric ( $channel ) && $channel > 0)
			$sql .= " AND channel=$channel";
		elseif (is_array ( $channel ) && count ( $channel ) > 0)
			$sql .= " AND channel IN(" . implode ( ',', $channel ) . ")";
		$sql .= " group by $grp order by $grp asc";
		// echo $sql;
		// print_r( [$appid,$date1, $date2]);
		$query = $this->db->query ( $sql, [ 
				$appid,
				$date1,
				$date2 
		] );
		$data = $query->result_array ();
		// print_r($data);
		// exit;
		return $data;
	}
	
	/**
	 * 机型统计
	 *
	 * @param
	 *        	$appid
	 * @param
	 *        	$date1
	 * @param
	 *        	$date2
	 * @param int $dataType
	 *        	数据类型:0活跃玩家,1新增用户
	 * @return mixed
	 */
	public function getDeviceData($appid, $date1, $date2, $dataType = 0, $serverid) {
		// if (empty($this->db_sdk))
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$table = 'u_device_unique';
		if ($dataType == 1) {
			$table = 'u_register';
		}
		$sql = <<<SQL
SELECT COUNT(*) AS cnt,client_type FROM $table
WHERE appid=$appid
SQL;
		if ($date1 > 0 && $date2 > 0)
			$sql .= " AND created_at BETWEEN $date1 AND $date2";
		if (is_numeric ( $serverid ) && $serverid > 0)
			$sql .= " AND serverid=$serverid";
		$sql .= " group by client_type order by cnt asc";
		// echo $sql; //, [$appid,$date1, $date2]
		$query = $this->db_sdk->query ( $sql );
		$data = $query->result_array ();
		return $data;
	}
	public function Life($appid, $accountid = 0) {
		// if (empty($this->db_sdk))
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$where = '';
		if ($accountid > 0 && is_numeric ( $accountid )) {
			$where .= " AND accountid=$accountid";
		}
		// $sql_total_roles = "SELECT COUNT(*) as total FROM u_roles WHERE appid=? $where";
		// $query = $this->db_sdk->query($sql_total_roles, [$appid]);
		// $total = $query->row()->total;
		$sql_roles = "SELECT * FROM u_roles WHERE appid=$appid $where ORDER BY id DESC";
		// echo $sql_roles;
		$query = $this->db_sdk->query ( $sql_roles );
		$results = $query->result_array ();
		$userid_list = [ ];
		$common_data = [ ];
		foreach ( $results as $row ) {
			$k = $row ['serverid'] . '_' . $row ['channel'];
			$userid_list [$k] = $row ['userid'];
			$accounts [$row ['userid']] = $row;
		}
		$query->free_result ();
		// print_r($accounts);
		if (empty ( $accounts )) {
			return false;
		}
		// $accountid_str = implode(',', array_keys($accounts));
		// 注册IP
		$sql_reg_ip = "SELECT ip,channel,client_type,mac FROM u_register WHERE accountid=$accountid";
		// echo $sql_reg_ip;
		$query = $this->db_sdk->query ( $sql_reg_ip );
		$results = $query->result_array ();
		foreach ( $results as $row ) {
			$common_data ['reg_ip'] = $row ['ip'] == 0 ? '无法获取到IP' : long2ip ( $row ['ip'] );
			$common_data ['client'] = $row ['client_type'];
			$common_data ['reg_channel'] = $row ['channel'];
			$common_data ['mac'] = $row ['mac'];
		}
		// print_r($accounts);
		// exit;
		// 最后登录IP
		/*
		 * $sql_last_ip = "SELECT max(id) as id FROM u_login_new WHERE accountid=$accountid GROUP BY serverid,channel ORDER BY logindate DESC";
		 * $query = $this->db_sdk->query($sql_last_ip);
		 * $results = $query->result();
		 * foreach ($results as $row) {
		 * $tmp_login_id[] = $row->id;
		 * }
		 * $login_id_str = implode(',', $tmp_login_id);
		 * unset($tmp_login_id);
		 * $sql_last_ip = "SELECT serverid,channel,ip FROM u_login_new WHERE id IN($login_id_str)";
		 * //echo $sql_last_ip;
		 * //print_r($userid_list);
		 */
		$sql_last_ip = "SELECT serverid,channel,last_login_ip as ip FROM u_last_login WHERE accountid=$accountid";
		$query = $this->db_sdk->query ( $sql_last_ip );
		if ($query) {
			$results = $query->result_array ();
			foreach ( $results as $row ) {
				$k = $row ['serverid'] . '_' . $row ['channel'];
				$userid = $userid_list [$k];
				$accounts [$userid] ['last_ip'] = $row ['ip'] == 0 ? '无法获取到IP' : long2ip ( $row ['ip'] );
			}
		}
		$query->free_result ();
		// 登陆时间
		$sql_login = <<<SQL
SELECT channel,serverid,min(logindate) as min_logindate,max(logindate) as max_logindate,
count(distinct logindate) as login_times
FROM u_login_new WHERE appid=$appid AND accountid=$accountid
GROUP BY channel,serverid
SQL;
		// echo $sql_login;
		$query = $this->db_sdk->query ( $sql_login );
		if ($query) {
			$results = $query->result_array ();
			foreach ( $results as $row ) {
				$k = $row ['serverid'] . '_' . $row ['channel'];
				$userid = $userid_list [$k];
				$accounts [$userid] ['first_logindate'] = $row ['min_logindate'];
				$accounts [$userid] ['last_logindate'] = $row ['max_logindate'];
				$accounts [$userid] ['login_times'] = $row ['login_times']; // 总登录次数
			}
		}
		$query->free_result ();
		// 总在线天数
		$sql_online = <<<SQL
SELECT max(viplev) as viplev,max(lev) as lev,sum(online) as online_time,
COUNT(DISTINCT online_date) as online_days,
userid
FROM u_dayonline WHERE appid=$appid AND accountid=$accountid
group by userid
SQL;
		$query = $this->db_sdk->query ( $sql_online );
		if ($query) {
			$results = $query->result_array ();
			foreach ( $results as $row ) {
				$accounts [$row ['userid']] ['viplev'] = $row ['viplev'];
				$accounts [$row ['userid']] ['lev'] = $row ['lev'];
				$accounts [$row ['userid']] ['online_days'] = $row ['online_days'];
				$accounts [$row ['userid']] ['online_time'] = $row ['online_time'];
			}
		}
		$query->free_result ();
		unset ( $db_pay );
		$gameid = 7;
		// 首付/末付时间,付费金额
		$db_pay = $this->load->database ( 'pay_log', TRUE );
		$sql_pay = <<<SQL
SELECT PayID,min(Add_Time) as first_paytime,max(Add_Time) as last_paytime,
SUM(PayMoney) as total_pay FROM web_pay_log
WHERE game_id=$gameid AND PayID=$accountid
group by PayID
SQL;
		$query = $db_pay->query ( $sql_pay );
		if ($query) {
			$results = $query->result_array ();
			foreach ( $results as $row ) {
				$common_data ['first_paytime'] = $row ['first_paytime'];
				$common_data ['last_paytime'] = $row ['last_paytime'];
				$common_data ['total_pay'] = $row ['total_pay'];
			}
		}
		$query->free_result ();
		// return $accounts;
		// print_r($accounts);
		// print_r($common_data);
		return [ 
				'data' => $accounts,
				'common_data' => $common_data 
		];
	}
	/**
	 *
	 * @param
	 *        	$table
	 * @param
	 *        	$appid
	 * @param
	 *        	$date1
	 * @param
	 *        	$date2
	 * @return mixed
	 */
	private function queryDay($table, $appid, $date1, $date2, $serverid = 0, $channel = 0) {
		$sql = <<<SQL
        SELECT date,sum(cnt) as total from {$table}
        WHERE appid=? AND date BETWEEN ? AND ?
SQL;
		if (is_numeric ( $serverid ) && $serverid > 0)
			$sql .= " AND serverid=$serverid";
		elseif (is_array ( $serverid ) && count ( $serverid ) > 0)
			$sql .= " AND serverid IN (" . implode ( ',', $serverid ) . ")";
		if (is_numeric ( $channel ) && $channel > 0)
			$sql .= " AND channel=$channel";
		elseif (is_array ( $channel ) && count ( $channel ) > 0)
			$sql .= " AND channel IN(" . implode ( ',', $channel ) . ")";
		$sql .= " GROUP BY date ORDER BY `date` ASC";
		// echo $sql;
		// print_r([$appid, $date1, $date2]);
		// print_r([$appid, $date1, $date2]);
		$data = $this->db->query ( $sql, [ 
				$appid,
				$date1,
				$date2 
		] )->result_array ();
		return $data;
		// return $this->db->query($sql, [$appid, $date1, $date2])->result_array();
	}
	/*
	 * 付费玩家统计,每日登陆的玩家有过付费的玩家数量
	 */
	public function saveVipPlayer($appid, $tm = 0) {
		$tm = $tm ? $tm : strtotime ( '-1 days' );
		$date = date ( 'Ymd', $tm );
		$sql = <<<SQLCODE
        SELECT DISTINCT accountid FROM u_dayonline WHERE appid=? AND online_date=?
SQLCODE;
		// if (empty($this->db_sdk))
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		$query = $this->db_sdk->query ( $sql, [ 
				$appid,
				$date 
		] );
		echo $this->db_sdk->last_query ();
		echo json_encode ( $this->db_sdk->error () );
		$accounts = [ ];
		if ($query)
			$data = $query->result_array ();
		foreach ( $data as $account_id ) {
			$accounts [] = $account_id ['accountid'];
		}
		// print_r($accounts);exit;
		if ($accounts) {
			$account_str = implode ( ',', $accounts );
			$sql = "SELECT count(DISTINCT accountid) as cnt, serverid, channel FROM u_paylog WHERE appid=? AND accountid IN($account_str) GROUP BY serverid, channel";
			$query_2 = $this->db_sdk->query ( $sql, [ 
					$appid 
			] );
			if ($data = $query_2->result_array ()) {
				$this->saveAU ( $appid, $data, 'vip_role', $date );
			}
		}
		$query->free_result ();
	}
	public function saveRolesCount($appid, $tm = 0, $type = 0) {
		$tm = $tm ? $tm : strtotime ( '-1 days' );
		$date1 = date ( 'Ymd000000', $tm );
		$date2 = date ( 'Ymd235959', $tm );
		if ($type == 0) {
			$sql = "SELECT COUNT(*) AS cnt,serverid,channel FROM u_roles WHERE appid=? AND ";
			$sql .= " created_at BETWEEN ? AND ? GROUP BY serverid,channel";
		} else {
			$sql = "SELECT COUNT(*) AS cnt,channel FROM u_roles WHERE appid=? AND ";
			$sql .= " created_at BETWEEN ? AND ? GROUP BY channel";
		}
		// if (empty($this->db_sdk))
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		
		// echo $sql;
		// print_r( [$appid, strtotime($date1), strtotime($date2)]);
		$query = $this->db_sdk->query ( $sql, [ 
				$appid,
				strtotime ( $date1 ),
				strtotime ( $date2 ) 
		] );
	//	echo $this->db_sdk->last_query ();
		echo json_encode ( $this->db_sdk->error () );
		if ($query)
			$data = $query->result_array ();
			// print_r($data);
		if (! $data)
			return false;
		if ($type == 0) {
			$this->saveAU ( $appid, $data, 'new_role', date ( 'Ymd', $tm ) );
		} else {
			$this->saveRealAU ( $appid, $data, 'new_role', date ( 'Ymd', $tm ) );
		}
		return true;
	}
	
	/**
	 * 保存数据
	 *
	 * @param
	 *        	$appid
	 * @param
	 *        	$data
	 * @param
	 *        	$col
	 * @param
	 *        	$sday
	 * @return mixed
	 */
	private function saveAU($appid, $data, $col, $sday) {
		$sql = <<<SQL
INSERT INTO sum_au(serverid,channel,appid,`$col`,sday) VALUES %REPLACE%
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
		$str = '';
		foreach ( $data as $row ) {
			$str .= "({$row['serverid']}, {$row['channel']}, '$appid', {$row['cnt']}, {$sday}),";
		}
		$str = rtrim ( $str, ',' );
		$sql = str_replace ( '%REPLACE%', $str, $sql );
		echo $sql, "\n";
		// exit;
		return $this->db->query ( $sql );
	}
	private function saveRealAU($appid, $data, $col, $sday) {
		$sql = <<<SQL
INSERT INTO sum_real_au(channel,appid,`$col`,sday) VALUES %REPLACE%
ON DUPLICATE KEY UPDATE `$col`=VALUES($col)
SQL;
		$str = '';
		foreach ( $data as $row ) {
			$str .= "( {$row['channel']}, '$appid', {$row['cnt']}, {$sday}),";
		}
		$str = rtrim ( $str, ',' );
		$sql = str_replace ( '%REPLACE%', $str, $sql );
		echo $sql, "\n";
		// exit;
		return $this->db->query ( $sql );
	}
	private function au($appid, $date1, $date2, $col, $sday, $auType = 1) {
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
		
		/*
		 * $sql = <<<SQL
		 * SELECT count(*) as cnt,t.serverid,t.channel FROM (
		 * SELECT COUNT(accountid) as cnt,serverid,channel FROM u_dayonline
		 * WHERE appid=$appid AND online_date BETWEEN $date1 AND $date2
		 * GROUP BY serverid, channel,accountid)
		 * t WHERE
		 * SQL;
		 */
		// 旧模式
		if ($auType >= 1 && $auType <= 3) {
			$sql = <<<SQL
SELECT count(*) as cnt,serverid,channel FROM u_dayonline
WHERE appid=$appid AND online_date BETWEEN $date1 AND $date2
SQL;
			// 在线15分钟才算是活跃玩家，数据库中一条记录是指在线300秒（5分钟）
			if ($auType == 1)
				$sql .= " and online>300";
			elseif ($auType == 2)
				$sql .= " and online>7200"; // 120*60
			elseif ($auType == 3)
				$sql .= " and online>30000"; // 500*60
			$sql .= " GROUP BY serverid,channel";
			
			echo $sql, "\n";
			// return false;
			// exit;
			$query = $this->db_sdk->query ( $sql );
			if ($query) {
				$data = $query->result_array ();
				if (! $data)
					return false;
				return $this->saveAU ( $appid, $data, $col, $sday );
			}
		} else {
			
			if ($auType == 7) {
				$tm_start = strtotime ( $date1 . '000000' );
				$tm_end = strtotime ( $date1 . '235959' );
				// 净dau
				/*
				 * $sql = <<<SQL
				 * SELECT count(DISTINCT accountid) as cnt, channel FROM u_login_new
				 * WHERE appid=$appid AND logindate=$date1
				 * and accountid not in (select accountid from u_register where created_at>=$tm_start and created_at<=$tm_end)
				 * GROUP BY channel
				 * SQL;
				 */
				$sql = <<<SQL
SELECT count(DISTINCT accountid) as cnt, channel FROM u_login_$date1
WHERE appid=$appid
and accountid not in (select accountid from u_register where created_at>=$tm_start and created_at<=$tm_end)
GROUP BY channel
SQL;
			} else {
				/*
				 * $sql = <<<SQL
				 * SELECT count(DISTINCT accountid) as cnt, channel FROM u_login_new
				 * WHERE appid=$appid AND logindate BETWEEN $date1 and $date2
				 * GROUP BY channel
				 * SQL;
				 */
				$tm_start = strtotime ( $date1 . '000000' );
				$tm_end = strtotime ( $date2 . '235959' );
				$dcount = floor ( ($tm_end - $tm_start) / (24 * 60 * 60) );
				$sql = "SELECT count(DISTINCT accountid) as cnt,channel FROM (select accountid,channel from u_login_$date1";
				if ($dcount > 0) {
					for($di = 1; $di <= $dcount; $di ++) {
						$ddate = date ( 'Ymd', strtotime ( "+$di days", $tm_start ) );
						$sql .= " union select accountid,channel from u_login_$ddate";
					}
				}
				$sql .= ")a group by channel;";
			}
			echo $sql, "\n";
			$query = $this->db_sdk->query ( $sql );
			if ($query) {
				$data = $query->result_array ();
				if (! $data)
					return false;
				return $this->saveRealAU ( $appid, $data, $col, $sday );
			}
		}
		return false;
	}
	
	/**
	 * DAU:每日成功登录游戏的玩家数量
	 */
	public function saveDau($appid, $tm = 0, $type = 1) {
		$tm = $tm ? $tm : strtotime ( '-1 days' );
		$date1 = date ( 'Ymd', $tm );
		$date2 = date ( 'Ymd', $tm );
		$this->au ( $appid, $date1, $date2, 'dau', date ( 'Ymd', $tm ), $type );
	}
	
	/**
	 * WAU:当日往前推7日（当日计入天数）期间内，登陆过游戏的玩家总数量，按照玩家ID排重
	 *
	 * @param
	 *        	$appid
	 */
	public function saveWau($appid, $tm = 0, $type = 2) {
		$tm = $tm ? $tm : strtotime ( '-1 days' );
		$date1 = date ( 'Ymd', strtotime ( '-7 days', $tm ) );
		$date2 = date ( 'Ymd', $tm );
		$this->au ( $appid, $date1, $date2, 'wau', date ( 'Ymd', $tm ), $type );
	}
	/**
	 * MAU:当日往前推7日（当日计入天数）期间内，登陆过游戏的玩家总数量，按照玩家ID排重
	 *
	 * @param
	 *        	$appid
	 */
	public function saveMau($appid, $tm, $type = 3) {
		$tm = $tm ? $tm : strtotime ( '-1 days' );
		$date1 = date ( 'Ymd', strtotime ( '-30 days', $tm ) );
		$date2 = date ( 'Ymd', $tm );
		$this->au ( $appid, $date1, $date2, 'mau', date ( 'Ymd', $tm ), $type );
	}
	public function saveCleanDau($appid, $tm) {
		$tm = $tm ? $tm : strtotime ( '-1 days' );
		$date1 = date ( 'Ymd', $tm );
		$date2 = date ( 'Ymd', $tm );
		$this->au ( $appid, $date1, $date2, 'clean_dau', date ( 'Ymd', $tm ), 7 );
	}
	
	/*
	 * 活跃玩家钻石途径
	 * @author zzl 20170628
	 *
	 */
	public function diamandDistribute($where = array()) {
		$sql = '';
		$sql_pre = '';
		if ($where ['serverids']) {
			$sql_pre .= " AND a.serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['channels']) {
			$sql_pre .= " AND a.channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}
		
		$this->db_sdk = $this->load->database ( 'sdk', TRUE );
	
		$sql = "SELECT a.vip_level,sum(if(b.type=0,b.item_num,0)) as type0,sum(if(b.type=1,b.item_num,0)) as type1 FROM u_behavior_{$where['date']} a,item_trading_{$where['date']} b WHERE b.item_id=3 AND a.id=b.behavior_id " . $sql_pre . " GROUP BY a.vip_level";
	//	$sql = "SELECT s.serverid,s.serverdate,a.vip_level,sum(if(b.type=0,b.item_num,0)) as type0,sum(if(b.type=1,b.item_num,0)) as type1 FROM u_behavior_{$where['date']} a,item_trading_{$where['date']} b, server_date s WHERE  b.item_id=3 AND a.id=b.behavior_id and s.serverid=a.serverid  " . $sql_pre . " GROUP BY a.vip_level";
		
		$result = $this->db_sdk->query ( $sql );
		
		if ($result) {
			$data ['type0'] = $result->result_array ();
		}
		
		$sql = "SELECT vip_level,sum(emoney) as surplus_money FROM `u_server_emoney_vip` WHERE  logdate={$where['date']}" . $sql_pre . " GROUP BY vip_level";
		$result = $this->db_sdk->query ( $sql );
		if ($result) {
			$data ['surplus_money'] = $result->result_array ();
		}
		
		return $data;
	}
	
	// 当天登陆的的活跃用户
	public function partVipLogin($where = array()) {
		$date0 = $where ['date'];
		$date1 = date ( 'Ymd', strtotime ( "$date0 +1 days" ) );
		$date3 = date ( 'Ymd', strtotime ( "$date0 +2 days" ) );
		$date7 = date ( 'Ymd', strtotime ( "$date0 +6 days" ) );
		$data ['day0'] = $data ['day1'] = $data ['day3'] = $data ['day7'] = array ();
		$wsql = '';
		if ($where ['serverids']) {
			$wsql .= " AND a.serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
		}
		if ($where ['channels']) {
			$wsql .= " AND a.channel IN(" . implode ( ',', $where ['channels'] ) . ")";
		}
		$wsql .= " group by a.viplev";
		$sql = "SELECT COUNT(DISTINCT a.serverid,a.accountid) c,a.viplev FROM u_login_{$date0} a WHERE 1=1 " . $wsql; // 当天登录数
		$result = $this->db->query ( $sql );
		if ($result) {
			$data ['day0'] = $result->result_array ();
		}
		
		return $data;
	}
	
	//  时间段内的新用户 
	public function newUser	($appid,$date1, $date2, $serverid = null, $channel = null) {
	
		$sql="select count(*) as new_user,reg_date from u_register where 1=1";			
		if($date1){
			$sql .= " AND reg_date>={$date1}";
		}
		if($date2){
			$sql .= " AND reg_date<={$date2}";
		}
			if (is_numeric ( $serverid ) && $serverid > 0)
				$sql .= " AND serverid=$serverid";
				elseif (is_array ( $serverid ) && count ( $serverid ) > 0)
				$sql .= " AND serverid IN(" . implode ( ',', $serverid ) . ")";
		
				if (is_numeric ( $channel ) && $channel > 0)
					$sql .= " AND channel=$channel";
					elseif (is_array ( $channel ) && count ( $channel ) > 0)
					$sql .= " AND channel IN(" . implode ( ',', $channel ) . ")";
					$sql .= " group by reg_date";
				
					$this->db_sdk = $this->load->database ( 'sdk', TRUE );
					$query = $this->db_sdk->query ( $sql );					
					$data = $query->result_array ();					
					return $data;		
	}
	
	
	/*
	 *    邀请好友统计需求  zzl 0901
	 */
  public function 	inviteFriend( $table, $where, $field, $group, $order, $limit){
        $group = 'viplev';
        
        $sql = "select accountid,userid,serverid,channel,viplev,viplev,lev,p_accountid,p_userid,p_serverid,p_channel,p_viplev,p_lev,created_at from u_invite where 1=1";
        if ($where['begindate'] && $where['enddate']) {
            $sql .= " AND (date>={$where ['begindate']} and  date<={$where ['enddate']})";
        }
        
        if ($group) {
            $sql .= " group by {$group}";
        }
        
        $this->db_sdk = $this->load->database('sdk', TRUE);
        $query = $this->db_sdk->query($sql);
        $data = $query->result_array();
        return $data;      
      
  }
  
  /*
   * 活跃玩家充值积分统计  zzl 0901
   */
  public function bonusPoint($table, $where, $field, $group, $order, $limit){
    
      if (! $field) {
          $field = '*';
      }
      $date0 = $where ['date']; 
      
      $sql = "select $field from u_behavior_{$where['date']} a inner join item_trading_{$where['date']} b on a.id=b.behavior_id and b.item_id=10034  where 1=1";      
   
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
    //  echo $sql;die;
      $this->db_sdk = $this->load->database('sdk', TRUE);
      $query = $this->db_sdk->query ( $sql );
      
      $result = array ();
      if ($query) {
          $result = $query->result_array ();
      }
      
      return $result;
      
      
  }
  /*
   * 当天的总账号数 & 前一天的总账号数 20170915 zzl
   */
  public function totalAccount($table, $where, $field, $group, $order, $limit){
      if (! $field) {
          $field = '*';
      }      
   
      $sql="select count(DISTINCT accountid) as total,channel from u_register where reg_date<={$where['date1']} GROUP BY channel";
      $this->db_sdk = $this->load->database('sdk', TRUE);
      $query = $this->db_sdk->query ( $sql );
      
      $result = array ();
      if ($query) {
          $result['date1'] = $query->result_array ();
      }
      
      $sql2="select count(DISTINCT accountid) as total,channel from u_register where reg_date<={$where['date_pre']} GROUP BY channel";
      $query2 = $this->db_sdk->query ( $sql2 );
      if ($query2) {
          $result['date_pre'] = $query2->result_array ();
      }
      
      return $result;
      
  }
  
  /*
   * 得到 一天的 android与ios的dau      20170915
   */
  public function getRealDau($appid, $where,$group) {
      $sql="SELECT sum(wau) AS wau,sum(clean_dau) as clean_dau,sday,channel FROM sum_real_au
      WHERE appid={$appid} AND sday={$where['date1']} group by channel";
    
      
     $query= $this->db->query ( $sql);
      $result = array ();
      if ($query) {
          $result = $query->result_array ();
      }      
    return $result;
  }
  
  
//  典型玩家数据 zzl  20171025
  public function tipical($table, $where, $field, $group, $order, $limit) {
    
      if (! $field) {
          $field = '*';
      }
  
      
      $sql = "select $field from u_userinfo   where 1=1";      
   
      if ($where ['serverids']) {
          $sql .= " AND serverid IN(" . implode ( ',', $where ['serverids'] ) . ")";
      }
      if( $where ['date']){
          $sql .= " and  logdate={$where ['date']}";
      }
      if( $where ['days']){
          $sql .= " and total_days>={$where ['days']}";
      }
      if( $where ['days']){
          $sql .= " and total_days<={$where ['days2']}";
      }
      if( $where ['vip_level']){
          $sql .= " and vip_level={$where ['vip_level']}";
      }
      if ($where ['channels']) {
          $sql .= " AND channel IN(" . implode ( ',', $where ['channels'] ) . ")";
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
 
      $this->db_sdk = $this->load->database('sdk', TRUE);
      $query = $this->db_sdk->query ( $sql );
      
      
      
      $sql_2="SELECT sum(money) as sum_money,AVG(money) avg_money,count(*) as total,count(DISTINCT(u.accountid)) cnt,p.id,p.accountid,u.total_days from u_userinfo u  inner JOIN u_paylog p on  u.accountid=p.accountid  where 1=1 "; 
     
     if( $where ['date']){
          $sql_2 .= " and  u.logdate={$where ['date']}";
      }
      if ($where ['channels']) {
          $sql_2 .= " AND u.channel IN(" . implode ( ',', $where ['channels'] ) . ")";
      }
      if( $where ['vip_level']){
          $sql_2 .= " and vip_level={$where ['vip_level']}";
      }
      if( $where ['days'] && $where ['days2']  ){
          $sql_2 .= " and u.total_days>={$where ['days']} and u.total_days<={$where ['days2']} group by u.total_days order by u.total_days";
      }
 
      
      $sql_3 = "SELECT c.total_days,d.id,d.accountid,d.vip_level,d.item_num,AVG(d.item_num) as consume from u_userinfo c,(select a.id,a.accountid,a.vip_level,item_num from   u_behavior_{$where ['date']} a  inner join item_trading_{$where ['date']} b 
      on a.id=b.behavior_id  and b.item_id=3 and b.type=1)d WHERE c.accountid=d.accountid ";
      
     
      if( $where ['days']  && $where ['days2']){
          $sql_3 .= " and total_days>={$where ['days']} and total_days<={$where ['days2']}";
      }
      
      if( $where ['vip_level']){
          $sql_3 .= " and  c.vip_level={$where ['vip_level']}";
      }
      if( $where ['channels']){
       $sql_3 .= " and  a.channels={$where ['channels']} ";
      }
     
        $sql_3 .= " and  c.logdate={$where ['date']} group by c.total_days order by c.total_days";
    

      if ($query) {
          $result = $query->result_array ();
      }
      
      $query_2 = $this->db_sdk->query ( $sql_2 );
      if ($query_2) {
          $result['data2'] = $query_2->result_array ();
      }
      
      $query_3 = $this->db_sdk->query ( $sql_3 );
      if ($query_3) {
          $result['data3'] = $query_3->result_array ();
      }
        return $result;
      
      
  }
  
}