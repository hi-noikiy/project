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

$params = array(
		'username',
		'password',
		'code',
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

if(strlen($username) == 11 && preg_match('/^1\d{10}$/', $username)){
	$bindtable = getAccountTable($username,'mobile_bind');
	$bindwhere = 'mobile';
} else{
	exit(json_encode(array('status'=>1, 'msg'=>'手机格式不正确')));
}

if(!preg_match("/^[A-Za-z0-9]{6,13}$/", $pass)){
	exit(json_encode(array('status'=>2, 'msg'=>'密码格式为字母数字且6-13位之间.')));
}


$array['game_id'] = $gameId;
$array['username'] = $username;
$array['password'] = $pass;
$array['code'] = $code;

ksort($array);
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign){
	write_log(ROOT_PATH."log","editpass_error_","签名错误，post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>2, 'msg'=>'验证错误.')));
}


$conn = SetConn('88');
$sql = "select addtime from web_message where username='$username' and code='$code'  order by id desc limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
$rs = @mysqli_fetch_assoc($query);
if(empty($rs) || (time()-$rs['addtime'] > 900)){
	write_log(ROOT_PATH."log","editpass_error_","验证码不存在或已失效，post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>2, 'msg'=>'验证码不存在或已失效.')));
}


$conn = SetConn($gameId);
$selectsql = "select accountid from $bindtable where $bindwhere = '$username' and gameid='$gameId' limit 1";
if(false == $query = mysqli_query($conn,$selectsql))
	exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
$result = @mysqli_fetch_assoc($query);
if(!$result){
	write_log(ROOT_PATH."log","editpass_error_","帐号不存在，post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>2, 'msg'=>'Account error, please enter again!')));
}
$accountid = $result['accountid'];
$acctable = betaSubTableNew($accountid,'account',999);
$sql = "select id, password from $acctable where id = '$accountid' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","editpass_error_","帐号查询失败，post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'check account is exists  sql error.')));
}


$rs = mysqli_fetch_assoc($query);
if(!isset($rs['id'])){
	write_log(ROOT_PATH."log","editpass_error_","帐号id不存在，post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>2, 'msg'=>'账号不存在.')));
}

$password = md5($pass.$mdString);
$accountUpdate = "update $acctable set password='$password' where id='$accountid';";
if(false ==mysqli_query($conn,$accountUpdate)){
	write_log(ROOT_PATH."log","eidtpass_error_","$accountUpdate, ".mysqli_error($conn).date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>2, 'msg'=>'修改失败!')));
}
exit(json_encode(array('status'=>0, 'msg'=>'success')));
?>
