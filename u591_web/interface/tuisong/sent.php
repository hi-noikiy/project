<?php
error_reporting(0);
include_once 'config.php';
/*$_POST['regid'] = '529be970176849367eb2d7a3e40d8354bf1b576eed7ea1da2f528c1ad02a832c';
$_POST['message'] = '今天登录可以免费领取大礼包一份！';
$_POST['type'] = 'iosels';*/
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


