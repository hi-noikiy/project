<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 官网注册接口 兼容普通账号注册 手机、邮箱验证码注册
* 暂时邮箱没有 预留着.
* ==============================================
* @date: 2016-5-6
* @author: Administrator
* @return:
*/
include_once 'config.php';
global $mdString;

$post = serialize($_POST);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$gameId = intval($_POST['game_id']);
$sign = trim($_POST['sign']);

$params = array(
		'username',
		'password',
		'game_id',
		'sign'
);
for ($i = 0; $i< count($params); $i++){
	if (!isset($_POST[$params[$i]])) {
		exit(json_encode(array('status'=>1, 'msg'=>'Missing '.$params[$i])));
	} else {
		if(empty($_POST[$params[$i]]))
			exit(json_encode(array('status'=>1, 'msg'=>$params[$i].' should not be empty.')));
	}
}
if (eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$', $username)){
	//邮箱注册
	$email = $username;
} else if(strlen($username) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $username)){
	//手机注册
	$phone = $username;
} else
	exit(json_encode(array('status'=>1, 'msg'=>'账号格式错误.')));


if(!preg_match("/^[A-Za-z0-9]{6,16}$/", $password))
	exit(json_encode(array('status'=>1, 'msg'=>'密码长度只能在6-16之间.'))); //密码长度或者格式不对

$appKey = $key_arr['appKey'];
$array['username'] = $username;
$array['password'] = $password;
$array['game_id'] = $gameId;
$mySign = httpBuidQuery($array, $appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'验证失败.')));

$password_my=md5($password.$mdString);
$reg_time=date("ymdHi");
$accountConn = $accountServer[$gameId];

$conn = SetConn($accountConn);
$sql = " select id from account where NAME = '$username'";
if(false == $query = mysqli_query($conn, $sql))
	exit(json_encode(array('status'=>1, 'msg'=>'数据异常.')));

$result = @mysqli_fetch_assoc($query);
if(isset($result['id']))
	exit(json_encode(array('status'=>1, 'msg'=>'账号已被注册.')));
	
$sql_game = "insert into account (NAME,phone,email,password,reg_date) VALUES ('$username','$phone', '$email', '$password_my', '$reg_time')";
if(false == mysqli_query($conn, $sql_game))
	exit(json_encode(array('status'=>1, 'msg'=>'数据异常.')));
$insert_id = mysqli_insert_id($conn);
if($insert_id)
	exit(json_encode(array('status'=>0, 'msg'=>'success', 'data'=>array('account_id'=>$insert_id, 'username'=>$username, 'reg_date'=>$reg_time))));
else 
	exit(json_encode(array('status'=>0, 'msg'=>'fail')));