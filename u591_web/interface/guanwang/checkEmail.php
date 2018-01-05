<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 验证邮箱是否注册
 * ==============================================
 * @date: 2016-7-15
 * @author: luoxue
 * @version:
 */
include_once 'config.php';
$post = serialize($_POST);
write_log(ROOT_PATH."log","checkemail_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$game_id = intval($_POST['game_id']);
$sign = trim($_POST['sign']);
$email = $_POST['email'];
$appKey = $key_arr['appKey'];
$accountConn = $accountServer[$game_id];

if (!eregi('^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$',$email))
	exit(json_encode(array('status'=>2, 'msg'=>'邮箱格式不正确.')));

$params = array(
		'email',
		'game_id',
		'sign'
);
for ($i = 0; $i< count($params); $i++){
	if (!isset($_POST[$params[$i]])) {
		exit(json_encode(array('status'=>2, 'msg'=>'缺失参数'.$params[$i])));
	} else {
		if(empty($_POST[$params[$i]]))
			exit(json_encode(array('status'=>2, 'msg'=>$params[$i].'值不能为空.')));
	}
}
if(!$appKey)
	exit(json_encode(array('status'=>2, 'msg'=>'appKey错误.')));
if(!$accountConn)
	exit(json_encode(array('status'=>2, 'msg'=>'gameId错误.')));
$array['email'] = $email;
$array['game_id'] = $game_id;
ksort($array);
$md5Str = http_build_query($array);
$my_sign = md5(urldecode($md5Str).$appKey);

if($sign != $my_sign)
	exit(json_encode(array('status'=>2, 'msg'=>'sign错误.')));
$conn = SetConn($accountConn);
$sql = "select id from account where NAME = '$email' or email='$email' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","checkemail_error_","sql=$sql, ".mysqli_error($conn).date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
}


$result = @mysqli_fetch_assoc($query);
$msg = isset($result['id']) ? 'registered' : 'unregistered';

exit(json_encode(array('status'=>0, 'msg'=>$msg)));