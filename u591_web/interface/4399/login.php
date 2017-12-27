<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 4399登陆接口
* ==============================================
* @date: 2016-7-28
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","4399_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$token = $_REQUEST['state'];
$openId = $_REQUEST['uid'];

$game_id = intval($_REQUEST['game_id']);

if(!$openId || !$token || !$game_id){
	write_log(ROOT_PATH."log","4399_login_error_"," parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$data = array();
$url = "http://m.4399api.com/openapi/oauth-check.html?state=$token&uid=$openId";
$result =https_post($url, $data);
$resultArr = json_decode($result, true);
write_log(ROOT_PATH."log","4399_result_log_","url=$url, result=$result,".date("Y-m-d H:i:s")."\r\n");
if(isset($resultArr['code']) && $resultArr['code']==100){
	$accountConn = $accountServer[$game_id];
	$returnOpenId = $resultArr['result']['uid'];
	if($returnOpenId !=$openId){
		write_log(ROOT_PATH."log","4399_login_error_","url=$url, result=$result, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit('4 0');
	}
	$conn = SetConn($accountConn);
	$channel_account = mysqli_real_escape_string($conn,$returnOpenId.'@4399');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn, $sql)){
    	write_log(ROOT_PATH."log","4399_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
    	exit('3 0');
    }
    $result = @mysqli_fetch_assoc($query);
    if($result){
        $insert_id = $result['id'];
        exit("0 $insert_id");
    }
    $insert_id = '';
    $password = random_common();
    $reg_time = date("ymdHi");
    $sql_game = "insert into account (NAME,password,reg_date, channel_account) VALUES ('$channel_account','$password','$reg_time', '$channel_account')";
    mysqli_query($conn, $sql_game);
    $insert_id = mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_4399_log_"," 4399 new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}else{
	write_log(ROOT_PATH."log","4399_login_error_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
exit('999 0');
?>
