<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 汉风登陆接口
* ==============================================
* @date: 2016-7-28
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","hanfeng_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$p = $_REQUEST['p'];
$sid = $_REQUEST['sid'];
$game_id = intval($_REQUEST['game_id']);
if(!$p || !$sid || !$game_id){
	write_log(ROOT_PATH."log","hanfeng_login_error_"," parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$pArr = explode('_', $p);



if(!isset($pArr[0]) || !isset($pArr[1]) || !isset($pArr[2]))
	exit('2 0');
$channel = $pArr[0];
$userId = $pArr[1];
$version = $pArr[2];

$url = 'http://lysid.hjwan.com:4005/verifyToken';
//$url = 'http://123.207.92.126:5003/verifyToken'; test url
$appId = $key_arr[$game_id]['appid'];
$appkey = $key_arr[$game_id]['appkey'];
$mySignStr = "$appId|$channel|$userId|$sid|$version|$appkey";
$mySign = md5($mySignStr);

$data = array();
$data['gameId'] = $appId;
$data['channel'] = $channel;
$data['userId'] = $userId;
$data['sid'] = $sid;
$data['version'] = $version;
$data['sign'] = $mySign;
$result = common_json_post($url, json_encode($data));
$resultArr = json_decode($result, true);
if(isset($resultArr['status']) && $resultArr['status'] == 'YHYZ_000'){
	$userId = $resultArr['userId'];
	$accountConn = $accountServer[$game_id];
	$username = $channel.'#'.$userId;
	$conn = SetConn($accountConn);
	$channel_account = mysqli_real_escape_string($conn,$username.'@hanfeng');
	$sql = "select id from account where channel_account='$channel_account' limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","hanfeng_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	$result = @mysqli_fetch_assoc($query);
	if($result){
		$insert_id = $result['id'];
		exit("0 $insert_id");
	}
	$insert_id = '';
	$password = random_common();
	$reg_time = date("ymdHi");
	$sql_game = "insert into account (NAME,password,reg_date, channel_account) VALUES ('$channel_account','$password','$reg_time', '$channel_account')";
	mysqli_query($conn, $sql_game);
	$insert_id = mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_hanfeng_log_"," hanfeng new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
} else {
	write_log(ROOT_PATH."log","hanfeng_login_error_","result=$result, mySignStr=$mySignStr, post=$post, get=$get,, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
exit('999 0');
?>