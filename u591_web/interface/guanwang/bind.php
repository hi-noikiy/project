<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 官网账号绑定(快速登陆)
* 先支持邮箱绑定
* ==============================================
* @date: 2016-7-19
* @author: luoxue
* @version:
*/
include_once 'config.php';
global $mdString;
$post = serialize($_POST);
write_log(ROOT_PATH."log","guanwang_bind_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$encoding = $_POST['encoding'];
if(empty($encoding))
	exit(json_encode(array('status'=>1, 'msg'=>'encoding should not empty.')));
$username = $_POST['username'];
if(empty($username))
	exit(json_encode(array('status'=>1, 'msg'=>'email should not empty.')));
if (!eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$',$username))
	exit(json_encode(array('status'=>1, 'msg'=>'email format error.')));
$password = trim($_POST['password']);
if(empty($password))
	exit(json_encode(array('status'=>1, 'msg'=>'password should not empty.')));
if(strlen($password) < 6)
	exit(json_encode(array('status'=>1, 'msg'=>'password length can not be less than six.')));

$gameId = $_POST['game_id'];
$accountConn = $accountServer[$gameId];
if(empty($gameId) || empty($accountConn))
	exit(json_encode(array('status'=>1, 'msg'=>'gameId or conn config should not empty.')));
$sign = trim($_POST['sign']);
if(empty($sign))
	exit(json_encode(array('status'=>1, 'msg'=>'sign should not empty.')));

$array['game_id'] = $gameId;
$array['username'] = $username;
$array['password'] = $password;
$array['encoding'] =$encoding;

ksort($array);
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));

$conn = SetConn($accountConn);
$sql = "select id from account where NAME = '$username' limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'check account is exists  sql error.')));

$rs = mysqli_fetch_assoc($query);
if(isset($rs['id'])) 
	exit(json_encode(array('status'=>1, 'msg'=>'account  exists.')));

$channel_account = $encoding.'@u591';
$sql2 = "select id,NAME from account where channel_account='$channel_account' limit 1";
if(false == $query2 = mysqli_query($conn,$sql2))
	exit(json_encode(array('status'=>1, 'msg'=>'sql error.')));
$rs2 = mysqli_fetch_assoc($query2);
if(empty($rs2))
	exit(json_encode(array('status'=>1, 'msg'=>'account does not exist.')));

$nameArr = explode('@', $rs2['NAME']);
if(isset($nameArr[1]) && $nameArr[1] != 'u591')
	exit(json_encode(array('status'=>1, 'msg'=>'account already bind.')));
$accountId = $rs2['id'];
$password = md5($password.$mdString);
$update_sql = "update account set NAME='$username', email='$username', password='$password', channel_account='' where id ='$accountId'";
if(false == mysqli_query($conn,$update_sql))
	exit(json_encode(array('status'=>1, 'msg'=>'fail')));
exit(json_encode(array('status'=>0, 'msg'=>'success')));