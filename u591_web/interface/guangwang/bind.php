<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 绑定账号
* ==============================================
* @date: 2016-5-9
* @author: Administrator
* @return:
*  0 $accountId  绑定成功
*  1 0 绑定的账号id错误
*  2 0 参数错误
*  3 0 sql连接异常
*  4 0 验证失败
*  5 0 账号存在,不能绑定
*  6 0 已经绑定
*  7 0 密码错误
*  8 0 验证码错误
*  9 0 验证码失效//超过15分钟
*/
include_once 'config.php';
global $mdString;

$post = serialize($_POST);
write_log(ROOT_PATH."log","guangwang_bind_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$accountid = $_POST['accountid'];
$phone = trim($_POST['phone']);
$encoding =trim($_POST['encoding']);
$password = trim($_POST['password']);
$sign = trim($_POST['sign']);

if(!$encoding || !$phone || !$password || !$sign){
	write_log(ROOT_PATH."log","guangwang_bind_error_log_","params error!,encoding=$encoding, phone=$phone, pass=$password, sign=$sign, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$key = $key_arr['key'];

if(strlen($phone) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $phone)){
	$code = trim($_POST['code']);
	if(!$code){
		write_log(ROOT_PATH."log","guangwang_bind_error_log_","params(code) error!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
		exit('2 0');
	}
	$conn = SetConn('88');
	$sql = "select * from web_message where username='$phone' order by id desc limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","guangwang_register_error_log_","mysql error! sql=$sql, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	$rs = mysqli_fetch_assoc($query);
	$nowTime = time();
	if(empty($rs)) {
		write_log(ROOT_PATH."log","guangwang_register_error_log_"," code is not exist!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
		exit('8 0');
	}
	if($nowTime-$rs['addtime'] > 900){
		write_log(ROOT_PATH."log","guangwang_register_error_log_"," code is invalid!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
		exit('9 0');//验证码失效
	}
	
	$mySign = md5($accountid.'&'.$code.'&'.$encoding.'&'.$password.'&'.$phone.'&'.$key);
	
} else {
	if(!preg_match("/^[A-Za-z0-9]{4,13}$/", $phone))
		exit('10 0'); //账号格式不对
	if(!preg_match("/^[A-Za-z0-9]{6,13}$/", $password))
		exit('11 0'); //密码长度或者格式不对
	
	$mySign = md5($accountid.'&'.$encoding.'&'.$password.'&'.$phone.'&'.$key);
}

if($mySign != $sign){
	write_log(ROOT_PATH."log","guangwang_bind_error_log_"," sign error!, mySign=$mySign, mySignStr=$accountid.&.$code.&.$encoding.&.$password.&.$phone.&.$key, sign=$sign, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
$conn = SetConn('81');
$sql = " select id,NAME, password from account where NAME = '$phone' limit 1";

if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","guangwang_bind_error_log_","mysql error! sql=$sql, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$rs = mysqli_fetch_assoc($query);
if(!empty($rs)) {
	write_log(ROOT_PATH."log","guangwang_bind_error_log_","account is not exist!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('5 0');
}
$channel_account = strtolower($encoding).'@u591';
$sql2 = "select id,NAME from account where channel_account='$channel_account' limit 1";
if(false == $query2 = mysqli_query($conn,$sql2)){
	write_log(ROOT_PATH."log","guangwang_bind_error_log_","mysql error! sql=$sql2, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$rs2 = mysqli_fetch_assoc($query2);

if($rs2['id'] != $accountid){
	write_log(ROOT_PATH."log","guangwang_bind_error_log_","accountid error! accountid=$accountid, result_id={$rs2['id']}, ".date("Y-m-d H:i:s")."\r\n");
	exit('1 0');
}

$nameArr = explode('@', $rs2['NAME']);
if(isset($nameArr[1]) && $nameArr[1] != 'u591'){
	write_log(ROOT_PATH."log","guangwang_bind_error_log_","already bind! result_id={$rs2['NAME']}, ".date("Y-m-d H:i:s")."\r\n");
	exit('6 0');
}

$password = md5($password.$mdString);
$update_sql = " update account set NAME='$phone', password='$password' where id ='$accountid'";
if(false == mysqli_query($conn,$update_sql)){
	write_log(ROOT_PATH."log","guangwang_bind_error_log_","mysql error! sql=$update_sql, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
exit("0 $accountid");