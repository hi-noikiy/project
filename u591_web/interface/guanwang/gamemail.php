<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 活动信息
* ==============================================
* @date: 2016-7-15
* @author: luoxue
* @version:
*/
include_once 'config.php';
$post = serialize($_POST);
write_log(ROOT_PATH."log","message_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$data = $_POST;
unset($data['sign']);
$sign = trim($_POST['sign']);
$appKey = $key_arr['appKey'];
ksort($data);
$md5str = http_build_query($data).$appKey;
$my_sign = md5($md5str);

if($sign != $my_sign){
	write_log(ROOT_PATH."log","message_error_",$md5str.','.$my_sign.",signerror,post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));
}

$serverid = intval($_POST['serverid']);
$type = 8;
$playerId = trim($_POST['playerid']);
$message= trim($_POST['message']);
if(!$serverid){
	exit(json_encode(array('status'=>1, 'msg'=>'serverid is null.')));
}
if(!$message){
	exit(json_encode(array('status'=>1, 'msg'=>'message is null.')));
}
//获取合服[区服]
$sid = togetherServer($serverid);
$table = betaSubTable($sid, 'u_gmtool', 1000);
$conn = SetConn($sid);
$mess = <<<EOF
$message
如对此回复还有其他异议，请联系我们客服QQ：2637357440；3094399563；3327487243
EOF;
$sql = "insert into $table(type, serverid, param, message) values('$type', '$serverid', '$playerId', '$mess')";

$queryR = @mysqli_query($conn,$sql);
if($queryR){
	write_log(ROOT_PATH."log","message_log_",$sql.",success,post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>0, 'msg'=>'send success.')));
}
write_log(ROOT_PATH."log","message_error_",$sql.mysqli_error($conn).",error,post=$post, ".date("Y-m-d H:i:s")."\r\n");
exit(json_encode(array('status'=>1, 'msg'=>'send error.')));
