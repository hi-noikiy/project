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
write_log(ROOT_PATH."log","guanwang_bindphone_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$accountId = trim($_POST['accountid']);
$username = trim($_POST['username']);
$code = trim($_POST['code']);
// $sign = trim($_POST['sign']);


$params = array(
		'username',
		'accountid',
		'code',
// 		'sign'
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
$array['code'] = $code;
if (eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$', $username)){
	//邮箱登陆
	$email = $username;
} else if(strlen($username) == 11 && preg_match('/^1[34578]{1}\d{9}$/', $username)){
	//手机登陆
	$phone = $username;
} else
	exit(json_encode(array('status'=>1, 'msg'=>'phone or email format error.')));

/*$appKey = $key_arr['appKey'];
$array['username'] = $username;
$array['accountid'] = $accountId;
$array['code'] = $code;
$mySign = httpBuidQuery($array, $appKey);
if($mySign != $sign){
	write_log(ROOT_PATH."log","guanwang_bindphone_error_",json_encode($array).",sign error,post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));
}*/
	


if($code){
	$conn = SetConn('88');
	$sql = "select * from web_message where username='$username' order by id desc limit 1";
	if(false == $query = mysqli_query($conn,$sql))
		exit(json_encode(array('status'=>1, 'msg'=>'web sql error.')));
	$rs = @mysqli_fetch_assoc($query);
	if(empty($rs))
		exit(json_encode(array('status'=>1, 'msg'=>'code does not exist.')));
	
	$nowTime = time();
	if($nowTime-$rs['addtime'] > 900)
		exit(json_encode(array('status'=>1, 'msg'=>'code is invalid.')));
}
$gameId = 8;
$bind_time=date('Y-m-d H:i:s');
$accountConn = $accountServer[$gameId];

$conn = SetConn($accountConn);

$sql_game = "insert into mobile_bind (mobile,accountid,bindtime) VALUES ('$username','$accountId', '$bind_time')";
if(false == mysqli_query($conn,$sql_game)){
	write_log(ROOT_PATH."log","guanwang_bindphone_error_",$sql_game.','.mysqli_error($conn).",post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'insert account sql error.')));
}

$insert_id = mysqli_insert_id($conn);
if($insert_id){
	write_log(ROOT_PATH."log","guanwang_bindphone_successs_",$insert_id.",post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>0, 'msg'=>'success')));
}
else 
	exit(json_encode(array('status'=>1, 'msg'=>'fail')));
?>