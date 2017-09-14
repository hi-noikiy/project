<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 快用登陆接口
* ==============================================
* @date: 2016-4-27
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","kuaiyong_login_info_all_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$tokenKey = base64_decode($_REQUEST['p']);
$game_id = trim($_REQUEST['game_id']);

if(!$tokenKey||!$game_id){
    write_log(ROOT_PATH."log","kuaiyong_login_error_"," param error! tokenKey=$tokenKey, game_id=$game_id, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}

$APPKEY = $arr_key[$game_id]['APPKEY'];
$sign = md5($APPKEY.$tokenKey);

$url = "http://f_signin.bppstore.com/loginCheck.php";
$data['sign'] = $sign;
$data['tokenKey'] = urlencode($tokenKey);

$url_result = https_post($url, $data);
write_log(ROOT_PATH."log","kuaiyong_login_result_log_","url=$url, url_result=$url_result, sign=$sign ,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$url_result_arr = json_decode($url_result, true);

if(!isset($url_result_arr['code'])){
	write_log(ROOT_PATH."log","kuaiyong_login_error_"," sign error, url=$url, url_result=$url_result, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");//验证失败
}
$code = $url_result_arr['code'];
$guid =  $url_result_arr['data']['guid'];
if($code == 0 && $guid){
    $accountConn = $accountServer[$game_id];
    $conn = SetConn($accountConn);
    
    $channel_account=mysqli_real_escape_string($conn,$guid.'@kaiyong');
    $username = rand(10000,99999).time().'@kaiyong';
    $sql = " select id from account where channel_account = '$channel_account'";
    $query=mysqli_query($conn,$sql);
    $result=array();
    if($query){
        $result=mysqli_fetch_assoc($query);
    }else{
        write_log(ROOT_PATH."log","kuaiyong_login_error_","get=$get, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
    mysqli_query($conn,$sql_game);
    $insert_id = mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_kaiyong_log_","new account login! get=$get, "."return=1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }

}else{
    write_log(ROOT_PATH."log","kuaiyong_login_error_"," sign error, url=$url, url_result=$url_result, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");//验证失败
}
exit("999 0");
?>
