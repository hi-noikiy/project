<?php
error_reporting(0);
include_once 'config.php';
/*$_POST['regid'] = 'dQ9lZm0xQO4:APA91bGFm3tTDxTF-YuL8QA9vKkdeUzMquYdq4P5jFDHDBS_bB0vgu0_7EW7YlOaJJW8dNd33W07Mp3M-zObx5_0TiKMcmyxLOnlXYn4NfciQyP5RfspG69W9T0ilyKmQhqe54NWGPFZ';
$_POST['message'] = '今天登录可以免费领取大礼包一份！';
$_POST['type'] = 'androidyn6';*/
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
		return send_gcm_notify($reg_id,$message,$apikey,$title);
	}else{
		return send_apn_notify($reg_id,$message,$apikey);
	}
}
