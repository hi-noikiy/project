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

$array['game_id'] = $gameId;
$array['username'] = $username;
ksort($array);
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>2, 'msg'=>'验证错误.')));


$accountid = $username;
$snum = giQSModHash($accountid);
$conn = SetConn($gameId,$snum,1);//account分表
$acctable = betaSubTable($accountid,'account',999);
$sql = "select id,phone from $acctable where id = '$accountid' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	exit(json_encode(array('status'=>1, 'msg'=>'account server sql error.')));
}
$result = @mysqli_fetch_assoc($query);
if(!$result){
	exit(json_encode(array('status'=>2, 'msg'=>'Account error, please enter again!')));
}
if($result['phone']){
	$data = array('username'=>$result['phone']);
	exit(json_encode(array('status'=>0, 'msg'=>'registered', 'data'=>$data)));
}else{
	exit(json_encode(array('status'=>0, 'msg'=>'unregistered.')));
}
?>