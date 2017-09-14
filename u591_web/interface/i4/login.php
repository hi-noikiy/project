<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 爱思助手登陆接口
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","i4_login_info_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$token = $_REQUEST['token'];
$game_id = $_REQUEST['game_id'];

if(!$token||!$game_id){
    write_log(ROOT_PATH."log","i4_login_error_log_","param error! token=$token, game_id=$game_id, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}

$url = "https://pay.i4.cn/member_third.action?token=$token";
$data = array();
$result = https_post($url,$data);
write_log(ROOT_PATH."log","i4_login_result_log_"," url=$url, result=$result, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$result_arr = json_decode($result, true);
$userid = $result_arr['userid'];
if($result_arr['status']==0&&$userid){
    
    $accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
    $channel_account=mysqli_real_escape_string($conn,$userid.'@i4');
    $username = rand(10000,99999).time().'@i4';
    $sql = " select id from account where channel_account = '$channel_account'";
    $query=mysqli_query($conn,$sql);
    $result=array();
    if($query){
        $result=mysqli_fetch_assoc($query);
    }else{
        exit('3 0');
    }
    if($result){
        $insert_id = $result['id'];
         write_log(ROOT_PATH."log","old_account_i4_log_","i4老登陆 ,get=$get, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("0 $insert_id");
    }
    $insert_id='';
    $password=random_common();
    $reg_time=date("ymdHi");
    $sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
    mysqli_query($conn,$sql_game);
    $insert_id = mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_i4_log_"," i4 新登陆 ,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
    write_log(ROOT_PATH."log","i4_login_result_log_","result=$result,url=".$url.",sql_game=$sql_game, error=".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");

}else{
    write_log(ROOT_PATH."log","i4_login_error_log_"," url=$url,result=$result,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
?>