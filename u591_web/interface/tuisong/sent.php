<?php
error_reporting(0);
$reg_id = "466f5ad24d3959fe69c0313ea0b84ebd280b5513c1f62e597927f274122bd1cd";
$message = '推送测试';
$type = 'iosnm';
$reg_id = $_GET['reg_id'];
$message = $_GET['message'];
$type = $_GET['type'];
send_notify($type,$reg_id,$message);

function send_notify($type,$reg_id,$message){
	include_once 'config.php';
	$apikey = $key_arr[8][$type]['apiKey'];
	if(substr($type,0,6) == 'android'){
		send_gcm_notify($reg_id,$message,$apikey);
	}else{
		send_apn_notify($reg_id,$message,$apikey);
	}
}


