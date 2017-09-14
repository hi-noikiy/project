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
$tokenKey = $_REQUEST['p'] ? base64_decode($_REQUEST['p']) : base64_decode($_REQUEST['token']);
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
    exit($guid);
}else{
    write_log(ROOT_PATH."log","kuaiyong_login_error_"," sign error, url=$url, url_result=$url_result, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");//验证失败
}
exit("999 0");
?>
