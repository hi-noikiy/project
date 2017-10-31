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
include 'config.php';
set_time_limit(10);
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","exchange_code_gangtai_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$returnarr =array();
$sign = $_REQUEST["sign"];
global $key_arr;
$key = $key_arr[8]['android']['appkey'];
$data = $_REQUEST;
unset($data['sign'],$data['message']);
ksort($data);
$str = urldecode(http_build_query($data)).'&'.$key;
$data['sign'] = md5($str);
if($sign != $data['sign']) {
	write_log(ROOT_PATH."log","exchange_code_gangtai_error_",$str.",sign error,{$data['sign']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['status'] = '1';
	$returnarr['message'] = 'sign error';
	exit(json_encode($returnarr));
}
$codeid = trim($_REQUEST['giftNo']);
$serverid = $_REQUEST['serverInfo'];
$accountId = $_REQUEST['roleInfo'];
$message = $_REQUEST['message'];
$useTime = $_REQUEST['useTime'];
if(!$message){
	$message = '活动奖励';
}
global $accountServer;
$accountConn = $accountServer[8];
$conn = SetConn($accountConn);
$sql_account = "select id from account where channel_account ='$accountId' limit 1;";
$query_account = mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!$result_account['id']){ 
	write_log(ROOT_PATH."log","exchange_code_gangtai_error_","accountid is null, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['status'] = '1';
	$returnarr['message'] = 'accountid is not exist';
	exit(json_encode($returnarr));
}

$playerid = checkPlayer($serverid,$result_account['id']);
if($playerid == ''){
	write_log(ROOT_PATH."log","exchange_code_gangtai_error_","player is null, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['status'] = '1';
	$returnarr['message'] = 'player is not exist';
	exit(json_encode($returnarr));
}
$myconn = SetConn(88);
$tableLog = 'web_user_code';
$sql = "select * from $tableLog where player_id ='$playerid' and server_id='$serverid' and code_id='$codeid' and useTime='$useTime' limit 1";
if(false == $result_type = mysqli_query($myconn,$sql)){
	write_log(ROOT_PATH."log","exchange_code_gangtai_error_",mysqli_error($myconn).",$sql, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['status'] = '1';
	$returnarr['message'] = 'database selectsql  by web error';
	exit(json_encode($returnarr));
}
$num_rows  = mysqli_num_rows($result_type);
if($num_rows > 0){
	write_log(ROOT_PATH."log","exchange_code_gangtai_error_","already used by user, $sql, ".date("Y-m-d H:i:s")."\r\n");
	$returnarr['status'] = '1';
	$returnarr['message'] = 'already send by user';
	exit(json_encode($returnarr));
}
$sid = togetherServer($serverid);
$table = subTable($sid, 'u_gmtool', 1000);
$conn = SetConn($sid);
$sql = "insert into $table(type, serverid, param, message, award_type1)";
$sql .= " values(9, '$sid', '$playerid' ,'$message', '$codeid') ";
if(false == mysqli_query($conn, $sql)){
	write_log(ROOT_PATH."log","exchange_code_gangtai_error_","sql=$sql, ".mysqli_error($conn).date("Y-m-d H:i:s")."\r\n");
    $returnarr['status'] = '1';
	$returnarr['message'] = 'database insertsql by game error';
	exit(json_encode($returnarr));
}
$sql = "insert into $tableLog(player_id, server_id, code_id, useTime,ctime)";
$sql .= " values('$playerid', '$serverid', '$codeid', '$useTime' ,".time().") ";
if(false == mysqli_query($myconn, $sql)){
	write_log(ROOT_PATH."log","exchange_code_insert_error_","sql=$sql, ".mysqli_error($myconn).date("Y-m-d H:i:s")."\r\n");
	$returnarr['status'] = '1';
	$returnarr['message'] = 'database insertsql by web error';
	exit(json_encode($returnarr));
}
write_log(ROOT_PATH."log","exchange_code_gangtai_","发送成功，sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
$returnarr['status'] = '0';
$returnarr['message'] = 'ok';
exit(json_encode($returnarr));

function checkPlayer($serverId, $accountId){
	$conn = SetConn($serverId);
	if($conn == false) return false;
	$table = betaSubTable($serverId, 'u_player', '1000');
	$sql = "select id from $table where account_id='$accountId' limit 1";
	$query = @mysqli_query($conn,$sql);
	$rows = @mysqli_fetch_assoc($query);
	if($rows)
		$rs = $rows;
	@mysqli_close($conn);
	return $rs['id']?$rs['id']:'';
}
?>