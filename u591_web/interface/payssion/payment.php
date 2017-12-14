<?php
require_once('config.php');
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","payssion_payment_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$state = $_GET['state'];
if($state == 'completed') {
	echo 'pay success!!transaction_id is HC13550478090294';
}else{
	echo 'pay fail';
}

