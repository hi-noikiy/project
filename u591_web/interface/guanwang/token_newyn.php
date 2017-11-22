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
if(!empty($code)){
	$array['code'] = $code;
	if (eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$', $username)){
		//邮箱登陆
	} else if(strlen($username) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $username)){
		//手机登陆
	} else 
		exit(json_encode(array('status'=>2, 'msg'=>'Lỗi thư hoặc số điện thoại.')));
}
$sign = trim($_POST['sign']);

$gameId = intval($_POST['game_id']);
$array['game_id'] = $gameId;
$accountConn = $accountServer[$gameId];
if(empty($accountConn) || empty($gameId))
	exit(json_encode(array('status'=>2, 'msg'=>'Lỗi ID game.')));
if(empty($array))
	exit(json_encode(array('status'=>2, 'msg'=>'Lỗi tham số.')));
$appKey = $key_arr['appKey'];
$appSecret = $key_arr['appSecret'];
if(!$appKey)
	exit(json_encode(array('status'=>2, 'msg'=>'Lỗi appKey.')));
if(!$appSecret)
	exit(json_encode(array('status'=>2, 'msg'=>'Lỗi appSecret.')));
ksort($array);
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>2, 'msg'=>'Lỗi sign.')));

$isNew = 0;
if(isset($code) && !empty($code)){
	//手机验证码登陆
	$conn = SetConn('88');
	$sql = "select addtime from web_message where username='$username' and code='$code'  order by id desc limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		
		exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
	}
	$rs = @mysqli_fetch_assoc($query);
	if(empty($rs)){
		exit(json_encode(array('status'=>2, 'msg'=>'Mã kiểm chứng không tồn tại.')));
	}
	if(time()-$rs['addtime'] > 900){
		exit(json_encode(array('status'=>2, 'msg'=>'Mã kiểm chứng vô hiệu.')));
	}
	$conn = SetConn($accountConn);
	$sql = "select id from account where NAME = '$username' limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		
		exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
	}
	$result = @mysqli_fetch_assoc($query);
	if(isset($result['id'])){
		$insert_id = intval($result['id']);
	} else {
		$password = random_common();
		$reg_time=date("ymdHi");
		$sql_game = "insert into account (NAME, phone, password, reg_date) VALUES ('$username', '$username', '$password', '$reg_time')";
		if(false == mysqli_query($conn,$sql_game)){
			exit(json_encode(array('status'=>1, 'msg'=>'insert account error.')));
		}
		$insert_id = mysqli_insert_id($conn);
        $isNew = 1;
	}
}  else if(isset($pass) && !empty($pass)){
	//账号密码错误
	$conn = SetConn($accountConn);
	$password = md5($pass.$mdString);
	$sql = "select id, password from account where NAME = '$username' limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		
		exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
	}
	$result = @mysqli_fetch_assoc($query);
	if(!$result){
		exit(json_encode(array('status'=>2, 'msg'=>'Sai tài khoản, hãy nhập lại!')));
	}
	if($result['password'] != $password){
		exit(json_encode(array('status'=>2, 'msg'=>'Sai mật khẩu, hãy nhập lại!')));
	}
	
	$insert_id = intval($result['id']);
} else {
	//快速登陆
	$conn = SetConn($accountConn);
	$channel_account = $username.'@u591';
	$sql = "select id from account where channel_account = '$channel_account' limit 1;";
	if(false == $query = mysqli_query($conn,$sql))
		exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
	$result = @mysqli_fetch_assoc($query);
	if($result){
		$insert_id = intval($result['id']);
	} else {
		$password = random_common();
		$reg_time = date("ymdHi");
		$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$channel_account','$password','$reg_time','$channel_account')";
		if(false == mysqli_query($conn,$sql_game))
			exit(json_encode(array('status'=>1, 'msg'=>'insert account error.')));
		$insert_id = mysqli_insert_id($conn);
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
exit(json_encode(array('status'=>2, 'msg'=>'Lỗi token.')));
