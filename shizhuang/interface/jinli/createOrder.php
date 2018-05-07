<?php
/*
 * 创建订单的demo，请运行。
 */ 
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","jinli_order_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$type = $_REQUEST['type'];
$gameId = $_REQUEST['game_id'];
$data = $key_arr[$gameId][$type];
$data['user_id'] = $_REQUEST['user_id'];
$data['player_id'] = $_REQUEST['player_id'];
$data['order_id'] = $_REQUEST['order_id'];
$data['money'] = $_REQUEST['money'];
//生成内部订单，创建金立订单
$dst_url = "https://pay.gionee.com/amigo/create/order";

$post_arr['user_id'] = $data['user_id']; // 用户id（参与签名）客户端登录返回的信息中 包含user_id,
$post_arr['out_order_no'] = $data['order_id']; // 商户订单号，商户需要确保订单号的唯一性，不能重复（参与签名）
$post_arr['subject'] = 'testPaymobile'; // 【NOTE】请填写你的支付标题
$post_arr['submit_time'] = date('YmdHis');
$post_arr['total_fee'] = $data['money']; // 【NOTE】需要支付的金额
$post_arr['sign'] = rsa_sign($post_arr,$data['privateKey']);
$post_arr['api_key'] = $data['appKey']; // 【NOTE】跑通demo后替换成商户自己的api_key
$post_arr['deal_price'] = $data['money']; // 【NOTE】 需要支付的金额
$post_arr['deliver_type'] = '1'; // 网游类型接入时固定值
$post_arr['notify_url'] = $data['notifyUrl']; // 【NOTE】请填写充值后的回调URL

$post_arr['player_id'] = $data['player_id']; // 【NOTE】请填写amigo玩家id

$json = json_encode($post_arr);

$return_json = https_curl($dst_url, $json);
write_log(ROOT_PATH."log","jinli_order_result_","post=$post,get=$get, ".$return_json.date("Y-m-d H:i:s")."\r\n");
$return_arr = json_decode($return_json, 1);

//订单创建成功的状态码判断
if ($return_arr['status'] !== '200010000') {
	write_log(ROOT_PATH."log","jinli_order_error_","post=$post,get=$get, ".$return_json.date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1,'msg'=>'error')));
}

die(json_encode($return_arr));
// {"status":"200010000","api_key":"FF20A4B3BCD44F1380DEECADFC6657D3","description":"\u6210\u529f\u521b\u5efa\u8ba2\u5355","out_order_no":"test2016081700000003","submit_time":"20170720142901","order_no":"59704dac0cf2ff80e74cc67b"}

function rsa_sign($post_arr,$private_key){
	ksort($post_arr);
	foreach($post_arr as $key => $value){
		$signature_str .= $value;
	}
	// 【NOTE】跑通demo后替换成商户自己的private_key
// 	$private_key= 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAN33eE2nD7fcBF/WNxTukvffy9NTXeWduFjfsyzVXPLXbxysQDsOJpoXIYhwU0Dif1bpT9BHY74Jnymw3+/D2bTM1mc+r0G84hSQ67wjL4fr3gY9UP5GgUCEX2t2lp9CLv0RU68elISSCE7Or+jN0kXLxhC1ZlxEmskNc8y7o87jAgMBAAECgYEAoWRi0PN79k+/zn9PpaSisCDFb27agy5e8CAXg63P27LRU6PbQBVV9AyFkVM69Z66wFL8eZCu8WrFk+bLrOZW0Ei2v8MHru1aYkX1Oa0hprob8O0hlr8Wxri1VHxSXOHq3MTD/NM9bAB2Kb6coqpR4T2Poajtk5zXyNZMiDeiPYECQQDzRm6RlXaKorHRbAhYXfktQ/0o+hZSidzYaDDKlUijZFF2CmczK93/na0HRwoIEUTyucLdL2BVipyu5cu7rb6xAkEA6ZO1O9WkJRxWxtnO5h0HNsEsH1mSRa5sjK1i2QJ4h1OxLJz4+P/UrXAvj1/sgnfxUG/eDh0WOTmz6V370fCT0wJAGzwyWrgZ6lFmiOSIVqRGpiurZvAAmcL3Z37an4Nw+2HawNVPUmpB00EqwtrQI7ETP/1N9Ic+SLVY7zeoxF0iMQJBAKSQ+xmjFjlHVCRaBRm/zftX8pxL4XDSyYv8BS7cPMsrviKungPhS5i+9+NONDZgB1ci2hKbj7LV4tpC608o7x0CQDY8BmLv63BNxS9/1s7X6thmVzP6co2fKdpWr5gw3E5bBXr2VJAInc5CfuDjaX2iRiYjvYLpC8QOWfxqTBLokY0=';
	$pem = chunk_split($private_key,64,"\n");
	$pem = "-----BEGIN PRIVATE KEY-----\n".$pem."-----END PRIVATE KEY-----\n";
	$private_key_id = openssl_pkey_get_private($pem);
	$signature = false;
	openssl_sign($signature_str, $signature, $private_key_id);
	$sign =  base64_encode($signature);
	return $sign;
}

function https_curl($url, $post_arr = array(), $timeout = 10)
{
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_arr);
	curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	$content = curl_exec($curl);
	curl_close($curl);

	return $content;
}



