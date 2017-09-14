<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 工作日表
* ==============================================
* @date: 2015-5-26
* @author: Administrator
* @return:
*/
class workday extends getList {
	
	public function __construct(){
		$this->tableName = '_web_workday';
		$this->key = 'id';
		$this->wheres = ' 1=1 ';
		$this->orders = 'id asc';
		$this->pageReNum = 15;
		$this->unread = '';
	}
	
	
	public function getOneRow($workDay){
		global $webdb;
		$sql="select workday from $this->tableName where workday<'$workDay' order by id desc limit 1";		
		$row=$webdb->getValue($sql,'workday');
		
		return $row;
	}
}
?>