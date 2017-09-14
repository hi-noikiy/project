<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 请假报备
* ==============================================
* @date: 2015-5-25
* @author: Administrator
* @return:
*/
class leave_filing extends getList {
	public function __construct() {
		$this->tableName = '_web_leave_filing';
		$this->key = 'id';
		$this->wheres = ' 1=1 ';
		$this->orders = 'id desc';
		$this->pageReNum = 15;
	}
	public function add($array) {
		global $webdb;
		
		$leaveId = $this->addData ($array);
	}
	public function edit($array, $id) {
		global $webdb;
		$datet = date ( "Y-m-d H:i:s" );
		if ($array ['depTag'])
			$array ['depTime'] = $datet;
		if ($array ['perTag'])
			$array ['perTime'] = $datet;
		if ($array ['manTag'])
			$array ['manTime'] = $datet;
		$this->editData ( $array, $id );
	}
	public function delete($id) {
		global $webdb;
		if ($this->tableName && $this->permCheck && ! permission::check ( $this->tableName, 'd_tag' )) {
			permission::errMsg ();
			return false;
		}
		return $webdb->query ( "delete from " . $this->tableName . " where " . $this->key . "='" . $id . "'" );
	}
	// 撤销函数
	public function doCancle($tag, $id) {
		global $webdb;
		$overinfo = $this->getInfo($id);
		// 判断此单是否有效
		if ($overinfo ['available'] == '1') {
			$ary = array();
			$this->editData($ary, $id); // 回滚数据
		} else {
			echo "<script>alert('此单已作废')</script>";
		}
	}
	//是否报备
	public function isFiling($uid,$date){
		global $webdb;
		$sql="select * from $this->tableName where uid='$uid' and fromTime='$date' and toTime='$date' and available=1";
		return $webdb->getList($sql);
	}
	
	
}
?>