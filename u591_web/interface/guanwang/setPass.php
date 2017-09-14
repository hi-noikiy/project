<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 设置密码
* ==============================================
* @date: 2016-7-20
* @author: luoxue
* @version:
*/
include_once 'config.php';
include_once 'myEncrypt.php';
global $mdString;
$post = serialize($_POST);
global $mdString;
write_log(ROOT_PATH."log","setpass_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$token = trim($_REQUEST['token']);
$pass = trim($_REQUEST['password']);
$gameId = intval($_REQUEST['game_id']);
$appSecret = $key_arr['appSecret'];
$accountConn = $accountServer[$gameId];
if(!$appSecret)
	exit(json_encode(array('status'=>2, 'msg'=>'appSecret错误.')));
if(!$accountConn)
	exit(json_encode(array('status'=>2, 'msg'=>'游戏ID错误.')));

$params = array(
		'password',
		'token',
		'game_id',
);
for ($i = 0; $i< count($params); $i++){
	if (!isset($_POST[$params[$i]])) {
		exit(json_encode(array('status'=>2, 'msg'=>'缺失参数'.$params[$i])));
	} else {
		if(empty($_POST[$params[$i]]))
			exit(json_encode(array('status'=>2, 'msg'=>$params[$i].'参数不能为空.')));
	}
}
if(!preg_match("/^[A-Za-z0-9]{6,16}$/", $pass))
	exit(json_encode(array('status'=>2, 'msg'=>'密码格式错误.')));

$parseStr = myEncrypt::decrypt($token, $appSecret);
parse_str($parseStr, $parseArr);
write_log(ROOT_PATH."log","setpass_result_log_","parseStr=$parseStr, ".date("Y-m-d H:i:s")."\r\n");

if(isset($parseArr['username']) && isset($parseArr['account_id']) && isset($parseArr['game_id'])){
	
	$username = urldecode($parseArr['username']);
	$accountId = intval($parseArr['account_id']);
	$gameId = intval($gameId);
	$conn = SetConn('88');
	$sql = "select token,addtime from web_token where account_id='$accountId' and game_id='$gameId'  order by id desc limit 1";
	if(false == $query = mysqli_query($conn,$sql))
		exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
	$rs = @mysqli_fetch_assoc($query);
	if($rs['token'] != $token){ //非法token
		write_log(ROOT_PATH."log","setpass_token_log_","token=$token, trueToken={$rs['token']} ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode(array('status'=>2, 'msg'=>'token错误.')));
	}
	//if(time()-$rs['addtime'] > 300)
	//	exit(json_encode(array('status'=>2, 'msg'=>'校验超时！请重启游戏或重新登录账号！')));
	
	$conn = SetConn($accountConn);
	$accoutId = $parseArr['account_id'];
	$sql = "select id from account where id='$accoutId' limit 1";
	if(false == $query = mysqli_query($conn,$sql))
		exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
	$result = @mysqli_fetch_assoc($query);
	if(!isset($result['id']))
		exit(json_encode(array('status'=>2, 'msg'=>'账号错误.')));
	$password = md5($pass.$mdString);
	$sql = "update account set  password='$password' where id='$accoutId'";
	if(mysqli_query($conn,$sql))
		exit(json_encode(array('status'=>0, 'msg'=>'success')));
	else
		exit(json_encode(array('status'=>1, 'msg'=>'fail')));
	
} else {
	exit(json_encode(array('status'=>2, 'msg'=>'验证错误.')));
}
?>