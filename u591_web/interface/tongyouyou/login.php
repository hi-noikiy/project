<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 同游游登陆接口
* ==============================================
* @date: 2016-7-28
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","tongyouyou_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$p = $_REQUEST['p'];
$sign = $_REQUEST['sign'];
$game_id = intval($_REQUEST['game_id']);
if(!$p || !$sign || !$game_id){
	write_log(ROOT_PATH."log","tongyouyou_login_error_"," parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$pArr = explode('_', $p);

if(!isset($pArr[0]) || !isset($pArr[1]))
	exit('2 0');

$username = $pArr[0];
$logintime = $pArr[1];
$appkey = $key_arr[$game_id]['appkey'];
$mySignStr = "username=$username&appkey=$appkey&logintime=$logintime";
$mySign = md5($mySignStr);
if($sign != $mySign){
	write_log(ROOT_PATH."log","tongyouyou_login_error_","mySign=$mySign, mySignStr=$mySignStr, post=$post, get=$get,, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}

$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$channel_account = mysqli_real_escape_string($conn,$username.'@tongyouyou');
$sql = "select id from account where channel_account='$channel_account' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
    write_log(ROOT_PATH."log","tongyouyou_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
	write_log(ROOT_PATH."log","new_account_tongyouyou_log_"," tongyouyou new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("1 $insert_id");
}
exit('999 0');
?>