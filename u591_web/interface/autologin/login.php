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
write_log(ROOT_PATH."log","autologin_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$token = $_REQUEST['token'];

$channel = trim($_REQUEST['channel']);
$game_id = intval($_REQUEST['game_id']);

if(!$token || !$channel){
	write_log(ROOT_PATH."log","autologin_error_","params error, post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$tokenArr = explode('_', $token);
if(!isset($tokenArr[1]) || !isset($tokenArr[2])){
	exit('2 0');
}
$token = $tokenArr[0];
$accountId = $tokenArr[1];
$sign = $tokenArr[2];
/*
 * 验证sign
 */
$array = array();
$array['game_id'] = $game_id;
$array['account_id'] = $accountId;
$array['token'] = $token;
ksort($array);
global $key_arr;
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);

$mySign = md5($md5Str.$appKey);
if($mySign != $sign){
	write_log(ROOT_PATH."log","autologin_error_","sign error, mySign=$mySign, sign=$sign, md5str=$md5Str,".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
/*
 * 验证账号
 */
global $accountServer;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql = "select id from account where id='$accountId' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","autologin_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$info = @mysqli_fetch_assoc($query);
if($info['id']){
	$sql = "select game_id, channel, account_id from web_login_auto where token='$token' limit 1";
	$conn = SetConn(88);
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","autologin_error_","sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	$result = @mysqli_fetch_assoc($query);
	if($result){
		exit("0 $accountId");
	} else {
		//web没有记录
		$addtime = time();
		$expiredTime = $addtime+7200;
		$addSql = "insert into web_login_auto (game_id, channel, account_id, token, expired_time, addtime)";
		$addSql .=" values('$game_id', '$channel', '$accountId', '$token', '$expiredTime', '$addtime')";
		if(mysqli_query($conn,$addSql)){
			exit("0 $accountId");
		}
		write_log(ROOT_PATH."log","autologin_error_"," sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	}
} else {
	exit('4 0');
}
exit('999 0');
?>