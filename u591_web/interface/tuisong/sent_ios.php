<?php
error_reporting(0);
//php需要开启ssl(OpenSSL)支持
$apnsCert    = "webpush.pem";//连接到APNS时的证书许可文件，证书需格外按要求创建
$pass        = "1234";//证书口令
$serverUrl   = "ssl://gateway.sandbox.push.apple.com:2195";//push服务器，这里是开发测试服务器
$deviceToken = "4eba4e1dee8e5d91cd5e78a639f7dc86b2df8517b204239fc345eb7cbea8b228";//ios设备id，中间不能有空格，每个ios设备一个id
$message = $_GET ['message'] or $message = "hello!";
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
?>