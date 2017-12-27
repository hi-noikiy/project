<?php
error_reporting(0);
include_once 'config.php';
/*$_POST['regid'] = 'a7ce3f0b616997c96228db8e75e878600e40de02ebfd283b715e836e94719a63';
$_POST['message'] = 'test again';
$_POST['type'] = 'iosnm';*/
$post = serialize($_POST);
write_log(ROOT_PATH."log","tuisong_info_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$reg_id = $_POST['regid'];
$message = $_POST['message'];
$type = $_POST['type'];
$apikey = $key_arr[8][$type]['apiKey'];
if(send_notify($type,$reg_id,$message,$apikey)){
	write_log(ROOT_PATH."log","tuisong_success_","post=$post ".date("Y-m-d H:i:s")."\r\n");
	echo 'success';
	die();
}else{
	write_log(ROOT_PATH."log","tuisong_error_","post=$post ".date("Y-m-d H:i:s")."\r\n");
	echo 'fail';
	die();
}

function send_notify($type,$reg_id,$message,$apikey){
	if(substr($type,0,7) == 'android'){
		$title = array(
				'els'=>'Мир монстров',
				'nm'=>'Monster World',
				'yn'=>'',
				'zg'=>'口袋妖怪'
		);
		return send_gcm_notify($reg_id,$message,$apikey,$title['nm']);
	}else{
		return send_apn_notify($reg_id,$message,$apikey);
	}
}
