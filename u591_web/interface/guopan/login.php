<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 果盘登陆接口
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","guopan_login_info_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$game_uin = $_REQUEST['game_uin'];
$token = $_REQUEST['token'];
$game_id = $_REQUEST['game_id'];

if(!$token||!$game_id || !$game_uin){
    write_log(ROOT_PATH."log","guopan_login_error_log_","param error! token=$token, game_id=$game_id, game_uin=$game_uin, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}
$gameUinArr = explode('_', $game_uin);
if(!isset($gameUinArr[0]) || !isset($gameUinArr[1]))
	exit('2 0');
$channel = strtolower($gameUinArr[1]);
$game_uin = $gameUinArr[0];

$appid = $key_arr[$game_id][$channel]['appId'];
$appsecret = $key_arr[$game_id][$channel]['appSecret'];
$t = time();
$sign = md5($game_uin.$appid.$t.$appsecret) ;
$url = "http://guopan.cn/gamesdk/verify/?game_uin=$game_uin&appid=$appid&token=$token&t=$t&sign=$sign";

$result = https_post($url,array());
write_log(ROOT_PATH."log","guopan_login_result_log_"," url=$url, result=$result, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($result != 'true'){
	write_log(ROOT_PATH."log","guopan_login_error_log_","url=$url, result=$result, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$channel_account = mysqli_real_escape_string($conn,$game_uin.'@guopan');
$username = $channel_account;
$sql = " select id from account where channel_account = '$channel_account'";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH.'log', 'guopan_login_error_log_',"mysql error! ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$result = mysqli_fetch_assoc($query);
if(!empty($result)){
	$insert_id = $result['id'];
	write_log(ROOT_PATH."log","old_account_guopan_log_","old account login, get=$get, post=$post, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("0 $insert_id");
}

$insert_id='';
$password=random_common();
$reg_time=date("ymdHi");
$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
if(mysqli_query($conn,$sql_game) == false){
	write_log(ROOT_PATH.'log', 'guopan_login_error_log_',"mysql error! ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$insert_id = mysqli_insert_id($conn);
if($insert_id){
	write_log(ROOT_PATH."log","new_account_guopan_log_"," new account login! get=$get, post=$post, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("1 $insert_id");
}
exit('999 0');
?>