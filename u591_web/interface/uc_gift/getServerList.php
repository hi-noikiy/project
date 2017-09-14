<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 获取CP所有区服列表
* ==============================================
* @date: 2016-8-20
* @author: luoxue
* @version:
*/
include_once 'config.php';
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","uc_gift_server_all_log_","json=$file_in ".date("Y-m-d H:i:s")."\r\n");
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
		write_log(ROOT_PATH."log","uc_gift_server_error_log_","code=5000011,signStr=$signStr, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000011, 'msg'=>'签名错误'))));
	}
	$json = decryptText($aesText, $aesKey, $aesKey);
	$result = json_decode($json, true);
	
	$page = $result['page'];
	$count = $result['count'];
	$conn = SetConn('88');
	
	$sql = "select count(*) as count from web_game_server where game_id=8 limit 1";
	$query = mysqli_query($conn, $sql);
	$result_count = @mysqli_fetch_assoc($query);
	$serverList = array();
	$recordCount = intval($result_count['count']);
	
	if($recordCount){
		$offset = $count*($page-1); 
		$sql = "select server_id, server_name from web_game_server where game_id=8 limit $offset,$count";
		if(false == $query = mysqli_query($conn,$sql)){
			write_log(ROOT_PATH."log","uc_gift_server_error_log_","code=5000000, sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
			exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000000, 'msg'=>'服务器内部错误'))));
		}	
		while (@$rows = mysqli_fetch_assoc($query)){
			$serverList[] = array('serverId'=>$rows['server_id'], 'serverName'=>$rows['server_name']);
		}
	}
	$jsonData = json_encode(array('recordCount'=>$recordCount, 'list'=>$serverList));
	$data = encyptText($jsonData, $aesKey, $aesKey);
	
	exit(json_encode(array('id'=>$id, 'state'=>array('code'=>2000000, 'msg'=>'成功'), 'data'=>$data)));
} else{
	write_log(ROOT_PATH."log","uc_gift_server_error_log_","code=5000020, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000020, 'msg'=>'业务参数无效、错误'))));
}