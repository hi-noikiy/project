<?php
error_reporting(0);
include_once 'config.php';
$post = serialize($_POST);
write_log(ROOT_PATH."log","tuisong_info_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$reg_id = $_POST['regid'];
$message = $_POST['message'];
$type = $_POST['type'];
$apikey = $key_arr[$type]['apiKey'];
$secret = isset($key_arr[$type]['secret'])?$key_arr[$type]['secret']:0;
if(send_notify($type,$reg_id,$message,$apikey,$secret)){
	write_log(ROOT_PATH."log","tuisong_success_","post=$post ".date("Y-m-d H:i:s")."\r\n");
	echo 'success';
	die();
}else{
	write_log(ROOT_PATH."log","tuisong_error_","post=$post ".date("Y-m-d H:i:s")."\r\n");
	echo 'fail';
	die();
}


