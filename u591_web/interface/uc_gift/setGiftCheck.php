<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 礼包内容校验
* ==============================================
* @date: 2016-8-20
* @author: luoxue
* @version:
*/
include_once 'config.php';
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","uc_gift_check_all_log_","json=$file_in ".date("Y-m-d H:i:s")."\r\n");
$data = json_decode($file_in, true);


if(isset($data['data']['params'])){
	$apiKey = $key_arr['apiKey'];
	$caller = $key_arr['caller'];
	$aesKey = $key_arr['aesKey'];

	$id = $data['id'];
	$aesText = $data['data']['params'];
	$sign = $data['sign'];

	$signStr = $caller.'params='.$aesText.$apiKey;
	$mySign = md5($signStr);

	if($mySign != $sign){
		write_log(ROOT_PATH."log","uc_gift_check_error_log_","code=5000011,signStr=$signStr, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000011, 'msg'=>'签名错误'))));
	}
	$json = decryptText($aesText, $aesKey, $aesKey);
	$result = json_decode($json, true);
	
	$accountId = $result['count']; //礼包
	$kaId = $result['kaId']; //我们的游戏礼包ID
	if(!isset($giftList[$kaId])){
		write_log(ROOT_PATH."log","uc_gift_check_error_log_","code=5000032,signStr=$signStr, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000032, 'msg'=>'礼包ID错误'))));
	}
	$jsonData = json_encode(array('result'=>true));
	$data = encyptText($jsonData, $aesKey, $aesKey);

	exit(json_encode(array('id'=>$id, 'state'=>array('code'=>2000000, 'msg'=>'成功'), 'data'=>$data)));
} else{
	write_log(ROOT_PATH."log","uc_gift_check_error_log_","code=5000020, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000020, 'msg'=>'业务参数无效、错误'))));
}