<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 获取用户创建的角色及区服信息
* ==============================================
* @date: 2016-8-20
* @author: luoxue
* @version:
*/
include_once 'config.php';
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","uc_gift_player_all_log_","json=$file_in ".date("Y-m-d H:i:s")."\r\n");

$data = json_decode($file_in, true);

//$data['data']['params'] = 'nlWmH+dgx272l+oxCWOFHMG6llJljLNAvqPh5s7V3M4mkJ5PeWc1oxhsF0fgs4vmTRcIW5zR3yDBIkZIKPs8HbLhmU+wzrpg3fGfpVHan4c=';
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
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000011,signStr=$signStr, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000011, 'msg'=>'签名错误'))));	
	}
	$json = decryptText($aesText, $aesKey, $aesKey);
	$result = json_decode($json, true);
	if(!isset($result['accountId'])){
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000012,signStr=$signStr, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000012, 'msg'=>'解密错误'))));
	}
	$conn = SetConn(85); //口袋账号库
	$ucAccountId = $result['accountId'];
	$channel_account = $result['accountId'].'@uc';
	$sql = "select id from account where channel_account='$channel_account' limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000000, sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000000, 'msg'=>'服务器内部错误'))));
	}
	$result = @mysqli_fetch_assoc($query);
	if(!$result){
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000030, sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000030, 'msg'=>'accountId不存在'))));
	}
	$accountId = $result['id'];
	$conn = SetConn('88');
	$sql = "select server_id, server_name from web_game_server where game_id=8 and status=0";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000000, sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000000, 'msg'=>'服务器内部错误'))));
	}
	$serverList = array();
	while (@$rows = mysqli_fetch_assoc($query)){
		$serverList[] = array('server_id'=>$rows['server_id'], 'server_name'=>$rows['server_name']);
	}
	$roleInfoArr = array();
	if(!empty($serverList)){
		$table = subTable($accountId, 'u_player', 3);
		foreach ($serverList as $v){
			$serverId = $v['server_id'];
			$serverName = $v['server_name'];
			$conn = SetConn($serverId);
			$sql = "select id,name,level from $table where serverid=$serverId and account_id=$accountId";
			if(false == $query = mysqli_query($conn,$sql)){
				write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000000, sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
				break;
			}
			$rs = @mysqli_fetch_assoc($query);
			if($rs){
				$roleInfoArr[] = array(
						'serverId'=>$serverId, 
						'serverName'=>$serverName, 
						'roleId'=>$rs['id'], 
						'roleName'=>$rs['name'], 
						'roleLevel'=>$rs['level']
					);
			}
		}
	}
	$jsonData = json_encode(array('accountId'=>$ucAccountId, 'roleInfos'=>$roleInfoArr));
	$data = encyptText($jsonData, $aesKey, $aesKey);
	exit(json_encode(array('id'=>$id, 'state'=>array('code'=>2000000, 'msg'=>'成功'),  'data'=>$data)));
} else{
	write_log(ROOT_PATH."log","uc_gift_player_error_log_","code=5000020, json=$file_in ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('id'=>$id, 'state'=>array('code'=>5000020, 'msg'=>'业务参数无效、错误'))));
}
?>