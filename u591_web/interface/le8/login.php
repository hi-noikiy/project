<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 乐8陆接口
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","le8_login_info_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$uid = $_REQUEST['uid'];
$token = $_REQUEST['token'];
$game_id = $_REQUEST['game_id'];

$appid = $arr_key[$game_id]['appid'];
//$appkey = $arr_key[$game_id]['appkey'];

if(!$token||!$game_id || !$uid){
    write_log(ROOT_PATH."log","le8_login_error_log_","param error! post=$post, get=$get, appid=$appid, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}
$url = "http://api.le890.com/index.php?m=api&a=validate_token";
$data = array();
$data['appid'] = $appid;
$data['t'] = $token;
$data['uid'] = $uid;
$result = https_post($url,$data);
write_log(ROOT_PATH."log","le8_login_result_log_"," url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");

if($result != 'success'){
	write_log(ROOT_PATH."log","le8_login_error_log_"," url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$channel_account = mysqli_real_escape_string($conn,$uid.'@le8');
$username = $channel_account;
$sql = " select id from account where channel_account = '$channel_account'";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH.'log', 'le8_login_error_log_',"mysql error! ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$result=mysqli_fetch_assoc($query);
if(!empty($result)){
	$insert_id = $result['id'];
	write_log(ROOT_PATH."log","old_account_le8_log_","old account login, get=$get, post=$post, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("0 $insert_id");
}

$insert_id='';
$password=random_common();
$reg_time=date("ymdHi");
$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
if(mysqli_query($conn,$sql_game) == false){
	write_log(ROOT_PATH.'log', 'le8_login_error_log_',"mysql error! ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$insert_id = mysqli_insert_id($conn);
if($insert_id){
	write_log(ROOT_PATH."log","new_account_le8_log_"," new account login! get=$get, post=$post, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("1 $insert_id");
}
exit('999 0');
?>