<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 同步推登陆接口
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","tongbu_login_info_all_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$session = trim($_REQUEST['session']);
$game_id = trim($_REQUEST['game_id']);

if(!$session||!$game_id){
    write_log(ROOT_PATH."log","tongbu_login_error_","params error! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}

$data = array();
$url = "http://tgi.tongbu.com/checkv2.aspx?k=$session";
$url_result =  https_post($url, $data);
$Uid = intval($url_result);
write_log(ROOT_PATH."log","tongbu_login_url_","url_result=$url_result,url=$url, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!($Uid>1)){
    write_log(ROOT_PATH."log","tongbu_login_error_","sign error! url_result=$url_result, url=$url, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$channel_account=mysqli_real_escape_string($conn,$Uid.'@tongbu');
$username = rand(10000,99999).time().'@tongbu';
$sql = " select id from account where channel_account = '$channel_account'";
$query=mysqli_query($conn,$sql);
$result=array();
if($query){
    $result=mysqli_fetch_assoc($query);
}else{
    write_log(ROOT_PATH."log","tongbu_login_error_"," get=$get, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
    exit('3 0');
}
if($result){
    $insert_id = $result['id'];
    write_log(ROOT_PATH."log","old_account_tongbu_log_","new account login, get=$get, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
    exit("0 $insert_id");
}
$insert_id='';
$password=random_common();
$reg_time=date("ymdHi");
$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
mysqli_query($conn,$sql_game);
$insert_id = mysqli_insert_id($conn);
if($insert_id){
    write_log(ROOT_PATH."log","new_account_tongbu_log_","old account login, get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
    exit("1 $insert_id");
}
exit("999 0");
?>