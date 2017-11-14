<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 自动登录
* token md5(gameId+accountId+token(第三方token))_accountId_sign(自定义sign)
* ==============================================
* @date: 2016-10-8
* @author: luoxue
* @version:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","storage_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$token = $_REQUEST['token'];

$channel = trim($_REQUEST['channel']);
$game_id = intval($_REQUEST['game_id']);
$mac = trim($_REQUEST['mac']);
if(!$token || !$channel)
	exit(json_encode(array('status'=>1, 'msg'=>'missing params')));
$tokenArr = explode('_', $token);
if(!isset($tokenArr[1]) || !isset($tokenArr[2])){
	exit(json_encode(array('status'=>1, 'msg'=>'missing params')));
}
$token = $tokenArr[0];
$accountId = $tokenArr[1];
$sign = $tokenArr[2];
/*
 * 验证sign
 */
$array = array();
if($mac)
    $array['mac'] = $mac;
$array['game_id'] = $game_id;
$array['account_id'] = $accountId;
$array['token'] = $token;
ksort($array);
global $key_arr;
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);

$mySign = md5($md5Str.$appKey);
if($mySign != $sign){
	write_log(ROOT_PATH."log","storage_error_"," sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));
}
/*
 * 验证账号
 */
global $accountServer;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql = "select id from account where id='$accountId' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","storage_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$info = @mysqli_fetch_assoc($query);
if(!isset($info['id'])){
	write_log(ROOT_PATH."log","storage_error_"," 账号ID不存在, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'账号ID不存在.')));
}
$sql = "select game_id, channel, account_id from web_login_auto where token='$token' limit 1";
$conn = SetConn(88);
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","storage_error_"," sql=$sql, 'sql error., ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'sql error.')));
}
	
$result = @mysqli_fetch_assoc($query);
if($result){
		exit(json_encode(array('status'=>0, 'msg'=>'success')));
} else {
	//web没有记录
	$addtime = time();
	$expiredTime = $addtime+7200;
	$addSql = "insert into web_login_auto (game_id, channel, account_id, token, expired_time, addtime,mac)";
	$addSql .=" values('$game_id', '$channel', '$accountId', '$token', '$expiredTime', '$addtime','$mac')";
	if(false == mysqli_query($conn,$addSql)){
		write_log(ROOT_PATH."log","storage_error_"," sql=$sql, insert sql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('status'=>1, 'msg'=>'insert sql error.'.mysqli_error($conn))));
	}
		
	exit(json_encode(array('status'=>0, 'msg'=>'success')));
}
?>