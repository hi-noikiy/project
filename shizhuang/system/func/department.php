<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* ERP管理系统
* ==============================================
* @date: 2015-12-14
* @author: luoxue
* @version:
*/
function getDepartmentName($id){
	$tablePrefix = Yii::app()->db->tablePrefix;
	$sql = 'select name from '.$tablePrefix.'department where id='.$id;
	$row = YII::app()->db->createCommand($sql)->queryRow();
	if($row)
		return $row['name'];
}

function getAddGoodName($itemtypeId){
    $tablePrefix = Yii::app()->db->tablePrefix;
    $sql = 'select name from '.$tablePrefix.'add_good where itemtype_id='.$itemtypeId;
    $row = YII::app()->db->createCommand($sql)->queryRow();
    if($row)
        return $row['name'];
    return '物品错误';
}