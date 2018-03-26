<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 修改
* ==============================================
* @date: 2015-5-25
* @author: Administrator
* @return:
*/
class user {
	var $db_table = "_sys_admin";
	var $idField = "id";
	var $nameFiele = "login_name";
	var $passFiele = "login_pass";
	var $session_id_name = "ADMIN_ID";
	public function check($uname, $upass) {
		$userAry = $this->getUser ( null, $uname );
		if ($userAry [$this->idField] > 0) {
			if (md5 ( $upass ) == $userAry [$this->passFiele]) {
				$uid = $this->register ( $userAry );
				return $uid;
			} else
				return - 2;
		} else
			return - 1;
	}
	public function getUser($uid, $uname = false, $field = null) {
		global $webdb;
		if ($uid)
			$sql = "select * from " . $this->db_table . " where " . $this->idField . " = '" . $uid . "'; ";
		elseif($uname)
			$sql = "select * from " . $this->db_table . " where " . $this->nameFiele . " = '" . $uname . "'; ";
		$reAry = $webdb->getValue($sql);
		if($field)
			return $reAry [$field];
		else
			return $reAry;
	}
	
	public function getDepertmentUser($depId){
		global $webdb;
		$sql="select id,real_name from _sys_admin where depId='$depId' order by id asc";
		$list=$webdb->getList($sql);
		return $list;
	}
	
	public function register($uAry) {
		global $topDepId;
		$ADMIN_ID = $uAry[$this->idField];
		@session_register($this->session_id_name);
		$_SESSION [$this->session_id_name] = $ADMIN_ID;
		
		$role = "";
		$admin = new admin();
		$userinfo = $admin->getInfo($ADMIN_ID, '', 'pass');
		$sqlstr = '';
		// print_r($userinfo);exit;
		if ($userinfo['depId'] == $topDepId && $userinfo['depMax'] == '1')
			$role = '1'; // 总经理
		elseif ($userinfo ['depId'] != $topDepId && $userinfo ['depMax'] == '1')
			$role = '2'; // 主管
		else
			$role = '3'; // 普通员工
		@session_register('role');
		$_SESSION['role'] = $role;
		@session_register('first');
		$_SESSION['first'] = 'y';
		$_SESSION['depId'] = $userinfo['depId'];
		$_SESSION['gpid'] = $userinfo['gpid'];
		return $ADMIN_ID;
	}
}
?>