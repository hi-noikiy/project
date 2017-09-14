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
write_log(ROOT_PATH."log","guanwang_register_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$username = trim($_POST['username']);
$code = trim($_POST['code']);
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
$email = $phone = '';
if(!empty($code)){
	$array['code'] = $code;
	if (eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$', $username)){
		//邮箱登陆
		$email = $username;
	} else if(strlen($username) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $username)){
		//手机登陆
		$phone = $username;
	} else
		exit(json_encode(array('status'=>1, 'msg'=>'phone or email format error.')));
} else { 
	/*普通账号注册
	 *用户名前两位包含yk,hn提醒已被注册，作为内部使用
	 *由于邮箱不需要验证码 所以放在这里
	*/
	if (eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$', $username)){
		//邮箱登陆
		$email = $username;
	} else {
		if(!ereg('^[0-9a-zA-Z\]*$',$username))
			exit(json_encode(array('status'=>1, 'msg'=>'account format wrong.')));
		
		if(substr(trim(strtolower($username)),0,2)=='yk' || substr(trim(strtolower($username)),0,2)=='hn')
			exit(json_encode(array('status'=>1, 'msg'=>'yk or hn already registered.')));
		if(strlen($username) > 13)
			exit(json_encode(array('status'=>1, 'msg'=>'can not account for more than 13 lengths.')));
	}
	
}

if(!preg_match("/^[A-Za-z0-9]{6,16}$/", $password))
	exit(json_encode(array('status'=>1, 'msg'=>'password length or wrong format.'))); //密码长度或者格式不对

$appKey = $key_arr['appKey'];
$array['username'] = $username;
$array['password'] = $password;
$array['game_id'] = $gameId;
$mySign = httpBuidQuery($array, $appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));


if($code){
	$conn = SetConn('88');
	$sql = "select * from web_message where username='$username' and game_id='$gameId' order by id desc limit 1";
	if(false == $query = mysqli_query($conn,$sql))
		exit(json_encode(array('status'=>1, 'msg'=>'web sql error.')));
	$rs = @mysqli_fetch_assoc($query);
	if(empty($rs))
		exit(json_encode(array('status'=>1, 'msg'=>'code does not exist.')));
	
	$nowTime = time();
	if($nowTime-$rs['addtime'] > 900)
		exit(json_encode(array('status'=>1, 'msg'=>'code is invalid.')));
}

$password_my=md5($password.$mdString);
$reg_time=date("ymdHi");
$accountConn = $accountServer[$gameId];

$conn = SetConn($accountConn);
$sql = " select id from account where NAME = '$username'";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'account sql error.')));

$result = @mysqli_fetch_assoc($query);
if(isset($result['id']))
	exit(json_encode(array('status'=>1, 'msg'=>'account is registered.')));
	
$sql_game = "insert into account (NAME,phone,email,password,reg_date) VALUES ('$username','$phone', '$email', '$password_my', '$reg_time')";
if(false == mysqli_query($conn,$sql_game))
	exit(json_encode(array('status'=>1, 'msg'=>'insert account sql error.')));
$insert_id = mysqli_insert_id($conn);
if($insert_id)
	exit(json_encode(array('status'=>0, 'msg'=>'success')));
else 
	exit(json_encode(array('status'=>0, 'msg'=>'fail')));
?>