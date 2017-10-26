<?php
define('ROOT_PATH', str_replace('interface/tuisong/config.php', '', str_replace('\\', '/', __FILE__)));
define("GOOGLE_GCM_URL", "https://fcm.googleapis.com/fcm/send");
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
    	8=>array(
    			'androidnm'=>array('apiKey'=>'AAAAqUl2rcU:APA91bHSqLSSPAeqXOVer1v3-HEAz9EDioUmj-rm-yvgu2Z9bXz9lTkQLUh0rDupSFRgx3QxIgyl5aD68EYQxaEm6Jdp6xr6SDXMlnv5ATX4_9RxLmEGzPS0CWUkVmurXUaZePHvg4X-'),
    			'iosnm'=>array('apiKey'=>'pem/nm.pem'),
    			'iosyn'=>array('apiKey'=>'pem/yn.pem'),
    	),
);

function send_gcm_notify($reg_id,$message,$apikey) {
	$fields = array(
			'to'=>		$reg_id,
			'notification'=>array(
					"body" =>"$message",
					"title" => "口袋妖怪",
					"icon" =>"myicon"
			)
	);
	$headers = array(
			'Authorization: key=' . $apikey,
			'Content-Type: application/json'
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, GOOGLE_GCM_URL);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	if ($result === FALSE) {
		die('Problem occurred: ' . curl_error($ch));
	}
	curl_close($ch);
	echo $result;
}
function send_apn_notify($deviceToken,$message,$apnsCert) {

	//php需要开启ssl(OpenSSL)支持
	$pass        = "1234";//证书口令
	$serverUrl   = "ssl://gateway.sandbox.push.apple.com:2195";//"ssl://gateway.sandbox.push.apple.com:2195";push服务器，这里是开发测试服务器
	$badge   = ( int ) $_GET ['badge'] or $badge = 2;
	$sound   = $_GET ['sound'] or $sound = "default";
	$body    = array('aps' => array('alert' => $message , 'badge' => $badge , 'sound' => $sound));
	$streamContext = stream_context_create();
	stream_context_set_option ( $streamContext, 'ssl', 'local_cert', $apnsCert );
	stream_context_set_option ( $streamContext, 'ssl', 'passphrase', $pass );
	$apns = stream_socket_client ( $serverUrl, $error, $errorString, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $streamContext);//连接服务器
	if ($apns) {
		echo "Connection OK <br/>";
	} else {
		echo "Failed to connect $errorString";
		return;
	}
	$payload = json_encode ( $body );
	$msg     = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack('n', strlen($payload)) . $payload;
	$result  = fwrite ( $apns, $msg);//发送消息
	fclose ( $apns );
	if ($result)
		echo "Sending message successfully: " . $payload;
		else
			echo 'Message not delivered';
 }
?>