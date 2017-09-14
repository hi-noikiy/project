<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 门禁记录丢失问题
* ==============================================
* @date: 2016-3-31
* @author: Administrator
* @return:
*/
include_once('common.inc.php');
$textName = 'data/cardRecord.txt';
//打开上传的文件
$filename = $rootpath.$textName;
$fp=fopen($filename,'r');

while(!feof($fp)){
	$rs =fgets($fp,1024);
	$num = intval(mb_substr($rs, 22));  //11101 进门 11201出门
	if((strlen($rs) == 33)){
		$mj = intval(mb_substr($rs, 0, 8));
		$date = mb_substr($rs, 8, 14);
		$ymd = date('Y-m-d', strtotime($date));
		$his = date('H:i:s', strtotime($date));

		$his = ($num == '11101') ? ' ['.$his.' [进门]]' : ' ['.$his.' [出门]]';		
		$record = new record();
		$record->fieldList = 'id, addtime';
		$record->wheres = "card_id='$mj' and recorddate='$ymd'";
		$record->pageReNum = '1';
		$data = $record->getArray("pass");
		
		
		if(!empty($data)){
			$addtime = $data[0]['addtime'];
			if($addtime == '无门禁记录' || $addtime == ''){
				$addtime = '';
				$his = ltrim($his);
			}
			$pos = strpos($addtime, ltrim($his));
			if($pos === false){
				$addtime .= $his;
				$id = intval($data[0]['id']);
				$array = array();
				$array['addtime'] = $addtime;
				$record->editData($array, $id);
			}
		}
		
	}
}
fclose($fp);