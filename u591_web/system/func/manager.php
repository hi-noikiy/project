<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* ERP管理系统
* 账号管理公用函数
* ==============================================
* @date: 2015-12-11
* @author: luoxue
* @version:
*/
function getDepName($id){
	$model = Department::model();
	$rs = $model->findByPk($id);
	if($rs)
		return $rs->name;
}

function getJobName($id){
	$model = Job::model();
	$rs = $model->findByPk($id);
	if($rs)
		return $rs->name;
}

function getRoleUserName($uid){
	$tablePrefix = Yii::app()->db->tablePrefix;
	$sql = 'select b.name from '.$tablePrefix.'role_user a left join '.$tablePrefix.'role b on a.role_id=b.id where a.user_id='.$uid;
	$row = YII::app()->db->createCommand($sql)->queryRow();
	if($row)
		return $row['name'];
}

function getManagerRealname($id){
	$sql = 'select real_name from {{manager}} where id='.$id;
	
	$row = YII::app()->db->createCommand($sql)->queryRow();
	if($row)
		return $row['real_name'];
}