<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 兑换码
* ==============================================
* @date: 20170727
* @author: wangtao
* @version:
*/
$table = 'web_code_exchange';
$tableLog = 'web_code_exchange_log';
include 'config.php';
set_time_limit(10);
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log(ROOT_PATH."log","exchange_code_ioslog_"," post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");

$sign = $_REQUEST["sign"];
global $key_arr;
$key = $key_arr[8]['ios']['appSecret'];
$data = $_REQUEST;
unset($data['sign']);
ksort($data);
$str = urldecode(http_build_query($data)).'&'.$key;
$data['sign'] = md5($str);
if($sign != $data['sign']) {
	write_log(ROOT_PATH."log","exchange_code_ioslog_error_",$str.",sign error,{$data['sign']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('FAILURE');
}
$codeid = trim($_REQUEST['giftNo']);
$serverid = $_REQUEST['serverInfo'];
$playerid = $_REQUEST['roleInfo'];
$message = $_REQUEST['message'];
$message = 'FB活动奖励';
$account = checkPlayer($serverid,$playerid);
if($account == ''){
	write_log(ROOT_PATH."log","exchange_code_ioslog_error_","accountid is null, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('FAILURE');
}
$myconn = SetConn(88);
$tableLog = 'web_user_code';
$sql = "select * from $tableLog where player_id ='$playerid' and server_id='$serverid' and code_id='$codeid' limit 1";
if(false == $result_type = mysqli_query($myconn,$sql)){
	write_log(ROOT_PATH."log","exchange_code_ioslog_error_","$sql, ".date("Y-m-d H:i:s")."\r\n");
	exit('FAILURE');
}
$num_rows  = mysqli_num_rows($result_type);
if($num_rows > 0){
	write_log(ROOT_PATH."log","exchange_code_ioslog_error_","already used by user, $sql, ".date("Y-m-d H:i:s")."\r\n");
	exit('FAILURE');
}
$sid = togetherServer($serverid);
$table = subTable($sid, 'u_gmtool', 1000);
$conn = SetConn($sid);
$sql = "insert into $table(type, serverid, param, message, award_type1)";
$sql .= " values(9, '$sid', '$playerid' ,'$message', '$codeid') ";
if(false == mysqli_query($conn, $sql)){
	write_log(ROOT_PATH."log","exchange_code_ioslog_error_","sql=$sql, ".mysqli_error($conn).date("Y-m-d H:i:s")."\r\n");
    exit('FAILURE');
}
$sql = "insert into $tableLog(player_id, server_id, code_id, ctime)";
$sql .= " values('$playerid', '$serverid', '$codeid' ,".time().") ";
if(false == mysqli_query($myconn, $sql)){
	write_log(ROOT_PATH."log","exchange_code_insert_error_","sql=$sql, ".mysqli_error($myconn).date("Y-m-d H:i:s")."\r\n");
	exit('FAILURE');
}
write_log(ROOT_PATH."log","exchange_code_ioslog_","发送成功，sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
exit('SUCCESS');

function subTable($accountId, $table, $sum){
	$suffix = $accountId%$sum;
	$s = sprintf('%03d', $suffix);
	return $table.$s;
}
?>