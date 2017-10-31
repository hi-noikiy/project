<?php
error_reporting(0);
include_once 'config.php';
$post = serialize($_POST);
write_log(ROOT_PATH."log","tuisong_info_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$reg_id = $_POST['regid'];
$message = $_POST['message'];
$type = $_POST['type'];
$apikey = $key_arr[8][$type]['apiKey'];
if(send_notify($type,$reg_id,$message,$apikey)){
	write_log(ROOT_PATH."log","tuisong_success_","post=$post ".date("Y-m-d H:i:s")."\r\n");
}else{
	write_log(ROOT_PATH."log","tuisong_error_","post=$post ".date("Y-m-d H:i:s")."\r\n");
}

function send_notify($type,$reg_id,$message,$apikey){
	if(substr($type,0,7) == 'android'){
		return send_gcm_notify($reg_id,$message,$apikey);
	}else{
		return send_apn_notify($reg_id,$message,$apikey);
	}
}


