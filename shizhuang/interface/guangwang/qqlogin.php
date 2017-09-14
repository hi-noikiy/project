<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* qq登陆接口
* ==============================================
* @date: 2016-7-5
* @author: Administrator
* @return:
*/
include_once 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","qq_login_all_log_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$sign = $_REQUEST['sign'];
$openid = $_REQUEST['openid'];

$appKey = $key_arr['qq'];
if(!$openid || !$appKey){
	write_log(ROOT_PATH."log","qq_login_error_log_","params error! openid=$openid, appKey=$appKey, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}
$mysign = md5('openid='.$openid.$appKey);

if($sign != $mysign){
	write_log(ROOT_PATH."log","qq_login_error_log_","sign error! mysign=$mysign, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}
$conn = SetConn(81);
$channel_account=mysqli_escape_string($conn, $openid.'@qq');
$username = rand(10000,99999).time().'@qq';
$sql = "select id from account where channel_account='$channel_account' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","qq_login_error_log_","sql error! , sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
}
$result = @mysqli_fetch_assoc($query);
if($result){
	$insert_id = $result['id'];
	exit("0 $insert_id");
}
$insert_id='';
$password=random_common();
$reg_time=date("ymdHi");
$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
if(mysqli_query($conn,$sql_game) == false){
	write_log(ROOT_PATH."log","qq_login_error_log_","sql error! , sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$insert_id = mysqli_insert_id($conn);
if($insert_id){
	write_log(ROOT_PATH."log","new_account_qq_login_log_","post=$post, get=$get, return=1 $insert_id, ".date("Y-m-d H:i:s")."\r\n");
	exit("1 $insert_id");
}
exit("999 0");
