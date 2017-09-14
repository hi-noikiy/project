<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* oppo新的登陆接口
* ==============================================
* @date: 2016-1-7
* @author: Administrator
* @return:
*/
include_once 'config.php';
include_once 'oppo.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","oppo_login_info_all_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$filedId = $_REQUEST['fileid'];
$token = urlencode(base64_decode($_REQUEST['token']));
$gameId = $_REQUEST['game_id'];
//$token = urlencode('TOKEN_1LzXQqVpADdCvcDy+SwoAIg1Bpo7+UJtZjiHgSjtK1sM2f4qTb3uUw==');
//$filedId = '150206758';
//$gameId = 8;


if(!$filedId || !$token){
	write_log(ROOT_PATH."log","oppo_login_error_"," parameter is error , filedId=$filedId, token=$token , ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}

$filedIds = explode('|', $filedId);
$filedId = $filedIds[0];
$type = isset($filedIds[1])?$filedIds[1]:'android';
$appKey = $arr_key[$gameId][$type]['app_key'];
$appSecret = $arr_key[$gameId][$type]['app_secret'];
if(!$appKey || !$appSecret)
	exit('2 0');
$oppo = new oppo($filedId, $token, $appKey, $appSecret);
$result = $oppo->result();
$resultstr =json_encode($result);

write_log(ROOT_PATH."log","oppo_login_check_result_"," get=$get, result=$resultstr, ".date("Y-m-d H:i:s")."\r\n");

if(is_array($result) && $result['ssoid'] == $filedId){
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	$channel_account=mysqli_real_escape_string($conn,$filedId.'@oppo');
	$username = $channel_account;
	$sql = " select id from account where channel_account = '$channel_account'";
	$query = mysqli_query($conn, $sql);
	$result = array();
	if($query){
		$result = @mysqli_fetch_assoc($query);
	}else{
		write_log(ROOT_PATH."log","oppo_login_error_"," get=$get,mysql is error, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	if($result){
		$insert_id = $result['id'];
		exit("0 $insert_id");
	}
	$insert_id='';
	$password=random_common();
	$reg_time=date("ymdHi");
	$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
	mysqli_query($conn, $sql_game);
	$insert_id = mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_oppo_log_","oppo new account login , get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
} else {
	write_log(ROOT_PATH."log","oppo_login_error_"," get=$get, sign check error, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");//验证失败
}
write_log(ROOT_PATH."log","oppo_login_error_"," get=$get, unknown exception, ".date("Y-m-d H:i:s")."\r\n");
exit("999 0");
?>