<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 检查绑定是否mac是否
* ==============================================
* @date: 2016-8-8
* @author: Administrator
* @return:
*/
include_once 'config.php';
global $mdString;
$post = serialize($_POST);
write_log(ROOT_PATH."log","guanwang_checkBind_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$username = trim($_POST['username']);
$gameId = intval($_POST['game_id']);
$sign = $_POST['sign'];
$params = array(
		'username',
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

$accountConn = $accountServer[$gameId];
if(empty($accountConn))
	exit(json_encode(array('status'=>1, 'msg'=>'gameId or conn config should not empty.')));

$array['game_id'] = $gameId;
$array['username'] = $username;
ksort($array);
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>2, 'msg'=>'验证错误.')));

$conn = SetConn($accountConn);
$sql = "select id,NAME from account where channel_account='$username' limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'check account is exists  sql error.')));

$rs = @mysqli_fetch_assoc($query);

if(!isset($rs['id']))
	exit(json_encode(array('status'=>2, 'msg'=>'账号不存在.')));
$nameArr = explode('@', $rs['NAME']);
if(isset($nameArr[1]) && ($nameArr[1] != 'u591' || $nameArr[1] !='weixin' || $nameArr[1] != 'qq'))
	exit(json_encode(array('status'=>0, 'msg'=>'unregistered.')));

$data = array('username'=>$rs['NAME']);
exit(json_encode(array('status'=>0, 'msg'=>'registered', 'data'=>$data)));
?>