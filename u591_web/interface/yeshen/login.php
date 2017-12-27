<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 夜神登陆接口
* ==============================================
* @date: 2016-7-28
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","yeshen_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$accessToken = $_REQUEST['accessToken'];
$appUid = $_REQUEST['uid'];
$gameId = intval($_REQUEST['game_id']);

if(!$accessToken || !$gameId || !$appUid){
	write_log(ROOT_PATH."log","yeshen_login_error_"," parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$appId = $key_arr[$gameId]['appId'];
$url = "https://passport.bignox.com/sso/o2/validation?accessToken=$accessToken&uid=$appUid&appId=$appId";

$result = https_post($url,array());

$resultArr = json_decode($result, true);
if(isset($resultArr['errNum']) && $resultArr['errNum'] == 0){
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	$channel_account = mysqli_real_escape_string($conn, $appUid.'@yeshen');
	$sql = "select id from account where channel_account='$channel_account' limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","yeshen_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
	@mysqli_query($conn, $sql_game);
	$insert_id = @mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_yeshen_log_"," yeshen_ new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
} else {
	write_log(ROOT_PATH."log","yeshen_login_error_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
exit('999 0');
?>