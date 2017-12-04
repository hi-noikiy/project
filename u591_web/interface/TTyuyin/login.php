<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 爱普登陆接口
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
require_once 'service/SDKServices.php';
require_once 'model/UserInfo.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","TTyuyin_login_info_all_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$token = $_REQUEST["sid"];
$appUid = $_REQUEST["uid"];
$game_id = trim($_REQUEST['game_id']);

if(!$token || !$game_id || !$appUid){
	write_log(ROOT_PATH."log","TTyuyin_login_error_","params error! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}
$appUidArr = explode('_', $appUid);
global $key_arr;
$type = isset($appUidArr[1])?$appUidArr[1]:'android';
$userInfo = new UserInfo();
$userInfo->sid = $_REQUEST["sid"];
$userInfo->userId = $appUidArr[0];
$msg = SDKServices::verifySession($userInfo, $key_arr[$type]);

$msgArr = json_decode($msg, true);
write_log(ROOT_PATH."log","TTyuyin_login_url_","result=$msg, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(isset($msgArr['head']['result']) && $msgArr['head']['result'] == 0){
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$channel_account=mysqli_real_escape_string($conn,$appUid.'@TTyuyin');
	$username = rand(10000,99999).time().'@TTyuyin';
	$sql = " select id from account where channel_account = '$channel_account'";
	$query=mysqli_query($conn,$sql);
	$result=array();
	if($query){
		$result=mysqli_fetch_assoc($query);
	}else{
		write_log(ROOT_PATH."log","TTyuyin_login_error_"," get=$get, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	if($result){
		$insert_id = $result['id'];
		write_log(ROOT_PATH."log","old_account_TTyuyin_log_","new account login, get=$get, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("0 $insert_id");
	}
	$insert_id='';
	$password = random_common();
	$reg_time=date("ymdHi");
	$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
	mysqli_query($conn, $sql_game);
	$insert_id = @mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_TTyuyin_log_","old account login, get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
} else {
	write_log(ROOT_PATH."log","TTyuyin_login_error_","result=$msg, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
exit("999 0");
?>