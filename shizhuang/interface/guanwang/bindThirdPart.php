<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 官网账号绑定(快速登陆)
* 先支持手机绑定
* ==============================================
* @date: 2016-7-26
* @author: luoxue
* @version:
*/
include_once 'config.php';
global $mdString;
$post = serialize($_POST);
write_log(ROOT_PATH."log","guanwang_bindThirdPart_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$accountId = intval($_POST['account_id']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$gameId = intval($_POST['game_id']);
$code = $_POST['code'];
$sign = trim($_POST['sign']);
if(!empty($code)){
	$array['code'] = $code;
	if (eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$', $username)){
		//邮箱绑定
		$email = $username;
	} else if(strlen($username) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $username)){
		//手机手机绑定
		$phone = $username;
	} else
		exit(json_encode(array('status'=>2, 'msg'=>'手机格式错误.')));
} else 
	exit(json_encode(array('status'=>2, 'msg'=>'验证码不能为空.')));
$params = array(
		'username',
		'password',
		'account_id',
		'game_id',
		'sign'
);
for ($i = 0; $i< count($params); $i++){
	if (!isset($_POST[$params[$i]])) {
		exit(json_encode(array('status'=>2, 'msg'=>'缺失参数'.$params[$i])));
	} else {
		if(empty($_POST[$params[$i]]))
			exit(json_encode(array('status'=>2, 'msg'=>$params[$i].'参数不能为空.')));
	}
}
if(!preg_match("/^[A-Za-z0-9]{6,13}$/", $password))
	exit(json_encode(array('status'=>2, 'msg'=>'密码格式为字母数字且6-13位之间.')));

$accountConn = $accountServer[$gameId];
if(empty($gameId) || empty($accountConn))
	exit(json_encode(array('status'=>1, 'msg'=>'gameId or conn config should not empty.')));

$conn = SetConn('88');
$sql = "select addtime from web_message where username='$username' and code='$code'  order by id desc limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
$rs = @mysqli_fetch_assoc($query);
if(empty($rs) || (time()-$rs['addtime'] > 900))
	exit(json_encode(array('status'=>2, 'msg'=>'验证码不存在或已过期.')));

$array['game_id'] = $gameId;
$array['username'] = $username;
$array['password'] = $password;
$array['code'] = $code;
$array['account_id'] =$accountId;

ksort($array);
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>2, 'msg'=>'验证错误.')));

$conn = SetConn($accountConn);
$sql = "select id from account where NAME = '$username' limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'check account is exists  sql error.')));

$rs = mysqli_fetch_assoc($query);
if(isset($rs['id'])) 
	exit(json_encode(array('status'=>2, 'msg'=>'账号已存在.')));

$sql2 = "select id,NAME, phone from account where id='$accountId' limit 1";
if(false == $query2 = mysqli_query($conn,$sql2))
	exit(json_encode(array('status'=>1, 'msg'=>'sql error.')));
$rs2 = mysqli_fetch_assoc($query2);
if(empty($rs2))
	exit(json_encode(array('status'=>1, 'msg'=>'account does not exist.')));

if($rs['phone'])
	exit(json_encode(array('status'=>2, 'msg'=>'账号已绑定.')));
$accountId = $rs2['id'];
$password = md5($password.$mdString);
$update_sql = "update account set NAME='$username', phone='$phone', password='$password' where id ='$accountId'";
if(false == mysqli_query($conn,$update_sql))
	exit(json_encode(array('status'=>1, 'msg'=>'fail')));
exit(json_encode(array('status'=>0, 'msg'=>'success')));
?>