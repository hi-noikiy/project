<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 熊猫玩
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
require_once 'common.php';
require_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xmw_login_all_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$gameId = intval($_REQUEST['game_id']);
$appId = $key_arr[$gameId]['appkey'];
$appSecret = $key_arr[$gameId]['appsecret'];
$accessToken = $_REQUEST['access_token'];

if(!$gameId || !$appId || !$appSecret || !$accessToken){
	write_log(ROOT_PATH."log","xmw_login_error_log_"," parameter error!, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}
$url = 'http://open.xmwan.com/v2/users/me?access_token='.$accessToken;
$result = https_post($url,array());
write_log(ROOT_PATH."log","xmw_login_result_log_","result=$result, ".date("Y-m-d H:i:s")."\r\n");
$resultArr = json_decode($result, true);
if(isset($resultArr['xmw_open_id'])){
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $xmwOpenId = $resultArr['xmw_open_id'];
    $channel_account = mysqli_real_escape_string($conn,$xmwOpenId.'@xmw');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","xmw_login_error_log_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_xmw_log_"," xmw new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
} else {
    write_log(ROOT_PATH."log","xmw_login_error_log_","sign error!, result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
exit('999');