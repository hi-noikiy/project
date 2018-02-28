<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 新增返回is_new字段
* ==============================================
* @date: 2016-7-14
* @author: luoxue
* @version:
*/
include_once 'config.php';
include_once 'myEncrypt.php';
global $mdString;
$post = serialize($_POST);
write_log(ROOT_PATH."log","guanwang_token_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$array = array();
$username = trim($_POST['username']);
if(!empty($username))
	$array['username'] = $username;
$pass = trim($_POST['password']);
if(!empty($pass))
	$array['password'] = $pass;
$code = trim($_POST['code']);
$bindtable = $bindwhere = '';
if(!empty($code)){
	$array['code'] = $code;
}
if(!empty($code) || !empty($pass)){
	if (eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$', $username)){
		//邮箱登陆
		$bindtable = getAccountTable($username,'mail_bind');
		$bindwhere = 'mail';
	} else if(strlen($username) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $username)){
		//手机登陆
		$bindtable = getAccountTable($username,'mobile_bind');
		$bindwhere = 'mobile';
	} else
		exit(json_encode(array('status'=>2, 'msg'=>'phone or email error.')));
}else{
	$username = $username.'@u591';
	$bindtable = getAccountTable($username,'token_bind');
	$bindwhere = 'token';
}
$sign = trim($_POST['sign']);
$gameId = intval($_POST['game_id']);
$array['game_id'] = $gameId;
if(empty($gameId) || empty($gameId))
	exit(json_encode(array('status'=>2, 'msg'=>'game id error.')));
if(empty($array))
	exit(json_encode(array('status'=>2, 'msg'=>'param error.')));
$appKey = $key_arr['appKey'];
$appSecret = $key_arr['appSecret'];
if(!$appKey)
	exit(json_encode(array('status'=>2, 'msg'=>'appKey error.')));
if(!$appSecret)
	exit(json_encode(array('status'=>2, 'msg'=>'appSecret error.')));
ksort($array);
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>2, 'msg'=>'sign error.')));

$isNew = 0;
if(isset($code) && !empty($code)){
	$myconn = SetConn('88');
	//手机验证码登陆
	$sql = "select addtime from web_message where username='$username' and code='$code' and game_id='$gameId'  order by id desc limit 1";
	if(false == $query = mysqli_query($myconn,$sql)){
		exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
	}
	$rs = @mysqli_fetch_assoc($query);
	if(empty($rs)){
		exit(json_encode(array('status'=>2, 'msg'=>'verification code does not exist.')));
	}
	if(time()-$rs['addtime'] > 900){
		exit(json_encode(array('status'=>2, 'msg'=>'verification failed.')));
	}
	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
	if($insertinfo['status'] == '1'){
		write_log(ROOT_PATH."log","guanwang_token_error_",json_encode($insertinfo).", ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode($insertinfo));
	}else{
		$insert_id = $insertinfo['data'];
		$isNew = 1;
	}

}  else if(isset($pass) && !empty($pass)){
	//账号密码错误
	$snum = giQSAccountHash($username);
	$conn = SetConn($gameId,$snum);
	$password = md5($pass.$mdString);
	$selectsql = "select accountid from $bindtable where $bindwhere = '$username' and gameid='$gameId' limit 1";
	if(false == $query = mysqli_query($conn,$selectsql))
		exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
	$result = @mysqli_fetch_assoc($query);
	if(!$result){
		exit(json_encode(array('status'=>2, 'msg'=>'Account error, please enter again!')));
	}
	$accountid = $result['accountid'];
	$snum = giQSModHash($accountid);
	$conn = SetConn($gameId,$snum,1);//account分表
	$acctable = betaSubTable($accountid,'account',999);
	$sql = "select id, password from $acctable where id = '$accountid' limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
	}
	$result = @mysqli_fetch_assoc($query);
	if(!$result){
		exit(json_encode(array('status'=>2, 'msg'=>'Account error, please enter again!')));
	}
	if($result['password'] != $password){
		exit(json_encode(array('status'=>2, 'msg'=>'Wrong password, please enter again!')));
	}
	$insert_id = intval($result['id']);
} else {
	//快速登陆
	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
	if($insertinfo['status'] == '1'){
		write_log(ROOT_PATH."log","guanwang_token_error_",json_encode($insertinfo).", ".date("Y-m-d H:i:s")."\r\n");
		exit(json_encode($insertinfo));
	}else{
		$insert_id = $insertinfo['data'];
		$isNew = 1;
	}
}
if($pass)
	unset($array['password']);
if($code)
	unset($array['code']);
if(!$insert_id)
	exit(json_encode(array('status'=>1, 'msg'=>'account id error.')));
$array['account_id'] = $insert_id;
$addtime = time();
$array['expired'] = $addtime;

$newMd5Str = http_build_query($array);
$token = myEncrypt::encrypt($newMd5Str, $appSecret);
$conn = SetConn(88);
$sql = "insert into web_token (account_id, game_id, token, addtime) values ('$insert_id', '$gameId', '$token', '$addtime')";
write_log(ROOT_PATH."log","guanwang_token_log_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");

if(mysqli_query($conn,$sql)){
	$data['token'] = $token;
    $data['is_new'] = $isNew;
    $data['account_id'] = $insert_id;
	exit(json_encode(array('status'=>0, 'msg'=>'success', 'data'=>$data)));
}
exit(json_encode(array('status'=>2, 'msg'=>'token error.')));
