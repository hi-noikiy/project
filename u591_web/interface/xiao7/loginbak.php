<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 小七app登陆接口
* ==============================================
* @date: 2016-7-28
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xiao7_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$tokenkey = $_REQUEST['tokenkey'];
$game_id = intval($_REQUEST['game_id']);
$type = strtolower($_REQUEST['type']);
if(!$tokenkey || !$game_id){
	write_log(ROOT_PATH."log","xiao7_login_error_"," parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}

$appkey = ($type == 'android') ? $key_arr[$game_id]['android']['appkey'] : $key_arr[$game_id]['ios']['appkey'];
$sign = md5($appkey.$tokenkey);
$url = "http://developer.x7sy.com/member/check_login";
$data = array();
$data['tokenkey'] = $tokenkey;
$data['sign'] = $sign;

$result = https_post($url, $data);
$resultArr = json_decode($result, true);
write_log(ROOT_PATH."log","xiao7_result_log_","url=$url, result=$result,appKey=$appkey, ".date("Y-m-d H:i:s")."\r\n");
if(isset($resultArr['errorno']) && $resultArr['errorno'] == 0){
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$guid = $resultArr['data']['guid'];
	$channel_account = mysqli_real_escape_string($conn,$guid.'@xiao7');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
    	write_log(ROOT_PATH."log","xiao7_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_xiao7_log_"," xiao7 new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}else{
	write_log(ROOT_PATH."log","xiao7_login_error_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
exit('999 0');
?>