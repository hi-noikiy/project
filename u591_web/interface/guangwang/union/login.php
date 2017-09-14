<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 爱普登陆接口
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","union_login_info_all_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$token = trim($_REQUEST['token']);
$userID = $_REQUEST['userID'];
$game_id = trim($_REQUEST['game_id']);

if(!$token || !$game_id || !$userID){
    write_log(ROOT_PATH."log","union_login_error_","params error! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}
$appKey = $arr_key[$game_id]['appKey'];
$sign = md5("userID=".$userID."token=".$token.$appKey);
$data = array();
$url = "http://123.207.248.208:8080/u8server/user/verifyAccount";
$data['token'] = $token;
$data['userID'] = $userID;
$data['sign'] = $sign;
$url_result =  https_post($url, $data);
write_log(ROOT_PATH."log","union_login_url_","url_result=$url_result,url=$url, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$urlResultArr = json_decode($url_result, true);
if(isset($urlResultArr['state']) && $urlResultArr['state'] == 1){
	$userID = $urlResultArr['data']['userID'];
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$channel_account=mysqli_real_escape_string($conn,$userID.'@union');
	$username = rand(10000,99999).time().'@union';
	$sql = " select id from account where channel_account = '$channel_account'";
	$query=mysqli_query($conn,$sql);
	$result=array();
	if($query){
		$result=mysqli_fetch_assoc($query);
	}else{
		write_log(ROOT_PATH."log","union_login_error_"," get=$get, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	if($result){
		$insert_id = $result['id'];
		write_log(ROOT_PATH."log","old_account_union_log_","new account login, get=$get, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("0 $insert_id");
	}
	$insert_id='';
	$password = random_common();
	$reg_time=date("ymdHi");
	$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
	mysqli_query($conn, $sql_game);
	$insert_id = @mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_union_log_","old account login, get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
} else {
	write_log(ROOT_PATH."log","union_login_error_","url=$url, result=$url_result, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
exit("999 0");
?>