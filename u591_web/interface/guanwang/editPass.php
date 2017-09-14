<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 验证码修改密码 先支持手机  
* ==============================================
* @date: 2016-7-29
* @author: luoxue
* @version:
*/
include_once 'config.php';
global $mdString;
$post = serialize($_POST);
global $mdString;
write_log(ROOT_PATH."log","editpass_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$username = trim($_POST['username']);
$code = $_POST['code'];
$pass = trim($_POST['password']);
$gameId = intval($_POST['game_id']);
$sign = trim($_POST['sign']);
$appSecret = $key_arr['appSecret'];
$accountConn = $accountServer[$gameId];
if(!$appSecret)
	exit(json_encode(array('status'=>1, 'msg'=>'appSecret error.')));
if(!$accountConn)
	exit(json_encode(array('status'=>1, 'msg'=>'gameId error.')));

$params = array(
		'username',
		'password',
		'code',
		'game_id',
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
	//邮箱绑定
	$email = $username;
} else if(strlen($username) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $username)){
	//手机手机绑定
	$phone = $username;
} else
	exit(json_encode(array('status'=>1, 'msg'=>'phone or email format error.')));

if(!preg_match("/^[A-Za-z0-9]{6,13}$/", $pass))
	exit(json_encode(array('status'=>1, 'msg'=>'password wrong format or length greater than 6 less than 13')));

$array['game_id'] = $gameId;
$array['username'] = $username;
$array['password'] = $pass;
$array['code'] = $code;

ksort($array);
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));

$conn = SetConn('88');
$sql = "select addtime from web_message where username='$username' and code='$code'  order by id desc limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
$rs = @mysqli_fetch_assoc($query);
if(empty($rs) || (time()-$rs['addtime'] > 900))
	exit(json_encode(array('status'=>1, 'msg'=>'code not exist or code invalid.')));

$conn = SetConn($accountConn);
$sql = "select id from account where NAME = '$username' limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'check account is exists  sql error.')));

$rs = mysqli_fetch_assoc($query);
if(!isset($rs['id']))
	exit(json_encode(array('status'=>1, 'msg'=>'account not  exists.')));

$accountId = $rs['id'];
$password = md5($password.$mdString);
$update_sql = "update account set password='$password' where id ='$accountId'";
if(false == mysqli_query($conn,$update_sql))
	exit(json_encode(array('status'=>1, 'msg'=>'fail')));
exit(json_encode(array('status'=>0, 'msg'=>'success')));
?>
