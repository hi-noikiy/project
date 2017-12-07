<?php
require_once('config.php');
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","paypal_payment_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$orderinfo = $_REQUEST['item_name'];
$infos = explode('_', $orderinfo);
$gameId = $infos[0];
$type = $infos[1];
global $key_arr;
$data['cmd'] = '_notify-synch';
$data['tx'] = $_REQUEST['tx'];
$data['at'] = $key_arr[$gameId][$type]['app_key'];
$url= $key_arr[$gameId][$type]['action'];
$response = https_post($url, $data);
$response = urldecode($response);
if(strpos($response, 'SUCCESS') === 0) {
	$response = substr($response, 7);
	preg_match_all('/^([^=\r\n]++)=(.*+)/m', $response, $m, PREG_PATTERN_ORDER);
	$response = array_combine($m[1], $m[2]);
	if($response['payment_status'] == 'Completed' && $response['receiver_email'] == $key_arr[$gameId][$type]['business'] &&
			$response['mc_currency'] == $key_arr[$gameId][$type]['currency_code'] && $response['mc_gross']>0){ // 支付成功返回成功信息给玩家交由IPN执行回调
		echo 'pay success';
	}
	
}else{
	echo 'pay fail';
}
$result = json_encode($response);
write_log(ROOT_PATH."log","paypal_payment_log_","result=$result,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

