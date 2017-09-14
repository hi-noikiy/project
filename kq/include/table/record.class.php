<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 打卡类 record
* ==============================================
* @date: 2015-5-26
* @author: Administrator
* @return:
*/
class record extends getList {
    public function __construct(){
    	$this->tableName = '_web_record';
    	$this->key = 'id';
    	$this->wheres = ' 1=1';
    	$this->orders = 'recorddate desc';
    	$this->pageReNum = 15;                
    }

    public function setGroup($group){
    	$this->wheres .= $group;
    }
    
    public function setOrder($order){
    	$this->orders = 'recorddate desc';
    }
    
    public function setKw($array){
    	if($array['fromTime'])
    		$this->setWhere("recorddate>='".$array['fromTime']."'");
    	if($array['toTime'])
    		$this->setWhere("recorddate<='".$array['toTime']."'");
    	if($array['card_id'])
    		$this->setWhere("card_id='".$array['card_id']."'");
    }
    
    public function getOneRow($name, $recorddate){
    	global $webdb;
    	$sql="select addtime, addtime_ex from $this->tableName where name='$name' and recorddate='$recorddate' limit 1";
    	
    	return $webdb->getList($sql);
    }
}
?>