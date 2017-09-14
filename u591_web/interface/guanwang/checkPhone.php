<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 验证电话是否注册
* ==============================================
* @date: 2016-7-15
* @author: luoxue
* @version:
*/
include_once 'config.php';
$post = serialize($_POST);
write_log(ROOT_PATH."log","checkphone_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$game_id = intval($_POST['game_id']);
$sign = trim($_POST['sign']);
$phone = $_POST['phone'];
$appKey = $key_arr['appKey'];
$accountConn = $accountServer[$game_id];

if(strlen($phone) != 11 || !preg_match('/^1[34578]{1}\d{9}$/', $phone))
	exit(json_encode(array('status'=>1, 'msg'=>'phone format error.')));

$params = array(
		'phone',
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
if(!$appKey)
	exit(json_encode(array('status'=>1, 'msg'=>'appKey error.')));
if(!$accountConn)
	exit(json_encode(array('status'=>1, 'msg'=>'gameId error.')));
$array['phone'] = $phone;
$array['game_id'] = $game_id;
ksort($array);
$md5Str = http_build_query($array);
$my_sign = md5($md5Str.$appKey);

if($sign != $my_sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));
$conn = SetConn($accountConn);
$sql = "select id from account where NAME = '$phone' or phone='$phone' limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));

$result = @mysqli_fetch_assoc($query);
$msg = isset($result['id']) ? 'registered' : 'unregistered';

exit(json_encode(array('status'=>0, 'msg'=>$msg)));