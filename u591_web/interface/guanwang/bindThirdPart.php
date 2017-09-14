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
write_log(ROOT_PATH."log","guanwang_bind_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$accountId = intval($_POST['account_id']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$gameId = intval($_POST['game_id']);
$code = intval($_POST['code']);

if(!empty($code)){
	$array['code'] = $code;
	if (eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$', $username)){
		//邮箱绑定
		$email = $username;
	} else if(strlen($username) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $username)){
		//手机手机绑定
		$phone = $username;
	} else
		exit(json_encode(array('status'=>1, 'msg'=>'phone or email format error.')));
} else 
	exit(json_encode(array('status'=>1, 'msg'=>'code should not empty.')));
$params = array(
		'username',
		'password',
		'account_id',
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
if(strlen($password) < 6)
	exit(json_encode(array('status'=>1, 'msg'=>'password length can not be less than six.')));
$accountConn = $accountServer[$gameId];
if(empty($gameId) || empty($accountConn))
	exit(json_encode(array('status'=>1, 'msg'=>'gameId or conn config should not empty.')));
$sign = trim($_POST['sign']);
if(empty($sign))
	exit(json_encode(array('status'=>1, 'msg'=>'sign should not empty.')));

$conn = SetConn('88');
$sql = "select addtime from web_message where username='$username' and code='$code'  order by id desc limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
$rs = @mysqli_fetch_assoc($query);
if(empty($rs) || (time()-$rs['addtime'] > 900))
	exit(json_encode(array('status'=>1, 'msg'=>'code not exist or code invalid.')));

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
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));

$conn = SetConn($accountConn);
$sql = "select id from account where NAME = '$username' limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'check account is exists  sql error.')));

$rs = mysqli_fetch_assoc($query);
if(isset($rs['id'])) 
	exit(json_encode(array('status'=>1, 'msg'=>'account  exists.')));

$sql2 = "select id,NAME, phone from account where id='$accountId' limit 1";
if(false == $query2 = mysqli_query($conn,$sql2))
	exit(json_encode(array('status'=>1, 'msg'=>'sql error.')));
$rs2 = mysqli_fetch_assoc($query2);
if(empty($rs2))
	exit(json_encode(array('status'=>1, 'msg'=>'account does not exist.')));

if($rs['phone'])
	exit(json_encode(array('status'=>1, 'msg'=>'account already bind.')));
$accountId = $rs2['id'];
$password = md5($password.$mdString);
$update_sql = "update account set NAME='$username', phone='$phone', password='$password', channel_account='' where id ='$accountId'";
if(false == mysqli_query($conn,$update_sql))
	exit(json_encode(array('status'=>1, 'msg'=>'fail')));
exit(json_encode(array('status'=>0, 'msg'=>'success')));
?>