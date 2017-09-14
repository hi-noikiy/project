<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 机器码查询
* ==============================================
* @date: 2016-5-9
* @author: Administrator
* @return:
*  0 $accountId 未绑定
*  1 0 已绑定
*  2 0 参数错误
*  3 0 sql连接异常
*  5 0 账号不存在
*/
include_once 'config.php';

$encoding = strtolower(trim($_POST['encoding']));
$post = serialize($_POST);
write_log(ROOT_PATH."log","guangwang_encoding_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

if(!$encoding){
	write_log(ROOT_PATH."log","guangwang_login_error_log_"," params error!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$conn = SetConn('81');
$channel_account = mysqli_real_escape_string($conn,$encoding.'@u591');

$sql = " select id,NAME from account where channel_account='$channel_account' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","guangwang_encoding_error_log_","mysql error! sql=$sql, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$result = @mysqli_fetch_assoc($query);
if(!isset($result['id'])){
	write_log(ROOT_PATH."log","guangwang_encoding_error_log_","account is not exist!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('5 0');
}
$accountid = $result['id'];
$name = $result['NAME'];
$nameArr = explode('@', $name);
if(isset($nameArr[1]) && $nameArr[1] == 'u591'){
	exit("0 $accountid");
}
exit("1 0");
write_log(ROOT_PATH."log","guangwang_encoding_bind_log_","account=$accountid, post=$post, ".date("Y-m-d H:i:s")."\r\n");

