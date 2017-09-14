<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 阿斯卡德登陆接口
* ==============================================
* @date: 2015-10-23
* @author: Administrator
* @return:
*   "2 0"      参数异常
*   '3 0'      sql异常
*   "4 0"      验证出错
*   "999 0"    未知错误
*   "0 $insert_id"       二次登陆返回
*   "1 $insert_id"       首次登陆返回
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","ask_login_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$sdkAccountId = trim($_REQUEST['accountid']);
$gameId = trim($_REQUEST['game_id']);
$sessionId = $_REQUEST['sessionid'];

if(!$sdkAccountId || !$gameId || !$sessionId){
    write_log(ROOT_PATH."log","ask_login_error_"," parameters error , post=$post, get=$get,  ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}

$appKey = $arr_key[$gameId]['appKey'];
$sdkGameId = $arr_key[$gameId]['gameId'];
$appId = $arr_key[$gameId]['appId'];

$array = array();
$array['accountid'] = $sdkAccountId;
$array['gameid'] = $sdkGameId;
$array['sessionid'] = $sessionId;
$mySign = httpBuidQuery($array, $appKey);

$url = 'http://asdk.ay99.net:8081/loginvalid.php';

$array['sign'] = $mySign;
$rs = https_post($url, $array);
write_log(ROOT_PATH."log","ask_login_check_log_","url=$url, result=$rs, ".date("Y-m-d H:i:s")."\r\n");

$rsArr = json_decode($rs, true);
if($rsArr['code'] != 0){
	write_log(ROOT_PATH."log","ask_login_error_log_"," sign error,rs=$rs, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");//验证异常
}

$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$channel_account = mysqli_real_escape_string($conn,$sdkAccountId.'@asdk');
$sql = "select id from account where channel_account='$channel_account'";
$query = mysqli_query($conn, $sql);
$result=array();
if($query){
	$result = @mysqli_fetch_assoc($query);
}else{
	write_log(ROOT_PATH."log","ask_login_error_log_"," mysql error: ".mysqli_error($conn)." post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
if($result){
	$insert_id = $result['id'];
	write_log(ROOT_PATH."log","old_account_asdk_log_","old account login , get=$get, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("0 $insert_id");
}
$insert_id='';
$password=random_common();
$reg_time=date("ymdHi");
$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$channel_account','$password','$reg_time','$channel_account')";
mysqli_query($conn, $sql_game);
$insert_id = mysqli_insert_id($conn);
if($insert_id){
	write_log(ROOT_PATH."log","new_account_asdk_log_","new account login ,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("1 $insert_id");
}else{
	write_log(ROOT_PATH."log","ask_login_error_log_", "$sql_game ".mysqli_error($conn)." ".date('Y-m-d H:i:s')."\r\n");
	exit("0");
}
?>