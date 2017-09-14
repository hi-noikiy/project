<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 官网注册接口
* ==============================================
* @date: 2016-5-6
* @author: Administrator
* @return:
*  0 $accountId 注册成功
*  1 0 注册失败
*  2 0 参数错误
*  3 0 sql连接异常
*  4 0 验证失败
*  5 0 验证码错误
*  6 0 验证码失效//超过15分钟
*  7 0 账号已被注册
*/
include_once 'config.php';
global $mdString;

$post = serialize($_POST);
write_log(ROOT_PATH."log","guangwang_register_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$phone = trim($_POST['phone']);
//$encoding = trim($_POST['encoding']);

$code = trim($_POST['code']);
$password = trim($_POST['password']);
$sign = trim($_POST['sign']);

if(!$phone || !$code || !$password){
	write_log(ROOT_PATH."log","guangwang_register_error_log_"," params error!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$key = $key_arr['key'];
$mySign = md5($code."&".$password."&".$phone.'&'.$key);

if($mySign != $sign){
	write_log(ROOT_PATH."log","guangwang_register_error_log_"," sign error!, mySign=$mySign, sign=$sign, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
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
	exit('5 0');
}
if($nowTime-$rs['addtime'] > 900){
	write_log(ROOT_PATH."log","guangwang_register_error_log_"," code is invalid!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('6 0');//验证码失效
}
$password_my=md5($password.$mdString);
$reg_time=date("ymdHi");
//$encoding = strtolower($encoding);
//$channel_account = mysqli_real_escape_string($conn,$encoding.'@u591');
$conn = SetConn('81');
$sql = " select id from account where NAME = '$phone'";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","guangwang_register_error_log_","mysql error! sql=$sql, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$result = @mysqli_fetch_assoc($query);
if(isset($result['id'])){
	write_log(ROOT_PATH."log","guangwang_register_error_log_","account is registered!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('7 0');
}
	
$sql_game = "insert into account (NAME,phone,password,reg_date) VALUES ('$phone','$phone','$password_my','$reg_time')";
if(false == mysqli_query($conn,$sql_game)){
	write_log(ROOT_PATH."log","guangwang_register_error_log_","mysql error! sql=$sql_game, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$insert_id = mysqli_insert_id($conn);
if($insert_id)
	exit("0 $insert_id");
write_log(ROOT_PATH."log","guangwang_register_error_log_","register error!, post=$post, ".date("Y-m-d H:i:s")."\r\n");
exit('1 0');
