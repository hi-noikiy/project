<?php
define('ROOT_PATH', str_replace('interface/tuisong/config.php', '', str_replace('\\', '/', __FILE__)));
define("GOOGLE_GCM_URL", "https://fcm.googleapis.com/fcm/send");
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
    	'android'=>array('apiKey'=>'a7f532eb89e0569adc6bddb2','secret'=>'4a500fb687db6727315250eb'),
		'android1'=>array('apiKey'=>'693f84f4edc3b6f8303d1581','secret'=>'f7a7afac7343d187cf7a2de6'),
    	'ios'=>array('apiKey'=>'pem/ios.pem'),
);

function send_notify($type,$reg_id,$message,$apikey,$secret,$num=1){
	if(substr($type,0,7) == 'android'){
		return send_gcm_notify($reg_id,$message,$apikey,$secret,$num);
	}else{
		return send_apn_notify($reg_id,$message,$apikey,$num);
	}
}

function send_gcm_notify($registers,$message,$app_key,$master_secret,$num) {
	require_once 'autoload.php';
	$client = new \JPush\Client($app_key, $master_secret);
	$pusher = $client->push();
	$pusher->setPlatform('android');
	$pusher->addRegistrationId($registers);
	$pusher->setNotificationAlert($message);
	try {
		$pusher->send();
		return 1;
	} catch (\JPush\Exceptions\JPushException $e) {
		// try something else here
		return 0;
	}
}
function send_apn_notify($deviceToken,$message,$apnsCert,$num) {

	//php需要开启ssl(OpenSSL)支持
	$pass        = "1234";//证书口令
	$serverUrl   = "ssl://gateway.sandbox.push.apple.com:2195";//"ssl://gateway.sandbox.push.apple.com:2195";push服务器，这里是开发测试服务器
	$badge   = $num;
	$sound   = $_GET ['sound'] or $sound = "default";
	$body    = array('aps' => array('alert' => $message, 'badge' => $badge));
	$streamContext = stream_context_create();
	stream_context_set_option ( $streamContext, 'ssl', 'local_cert', $apnsCert );
	stream_context_set_option ( $streamContext, 'ssl', 'passphrase', $pass );
	$apns = stream_socket_client ( $serverUrl, $error, $errorString, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $streamContext);//连接服务器
	if (!$apns) {
		return 0;
	}
	$payload = json_encode ( $body );
	$msg     = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack('n', strlen($payload)) . $payload;
	$result  = fwrite ( $apns, $msg);//发送消息
	fclose ( $apns );
	if (!$result)
		return 0;
	return 1;
 }
?>