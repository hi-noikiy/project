<?php
error_reporting(0);
include_once 'config.php';
/*$_POST['regid'] = 'dbImLCdO6No:APA91bE8NXGWuVHNHX5PvBb42OVPo96t7NIG1Fwklc_sSGeoOdsZWs-mFZQNrwPQP7bxmODzVL8SVXo7-TNjMv93ZN11mQ4df6RFY1pwjIjiGc1nIK0zDbMHwAmVE4i6M7KS7hVAylsZ';
$_POST['message'] = '今天登录可以免费领取大礼包一份！';
$_POST['type'] = 'androidsels';*/
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
