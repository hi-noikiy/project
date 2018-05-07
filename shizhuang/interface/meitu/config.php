<?php
define('ROOT_PATH', str_replace('interface/meitu/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		9=>array(
				'android'=>array('appId'=>'2016867654','appKey'=>'wq3cPCBlBQvo13UvD7ng','appSecret'=>'uOMhAYLmfU5LIDkp92w9RXopMGv0Psta'),
		)
);
function hmacSha1Sign($params,$signKey){
	ksort($params);
	$paramString = '';
	foreach ($params as $key => $value) {
		if (is_null($value) || $value=='' || $key == 'sign') {
			continue;
		}
		$paramString .= $key.'='.$value.'&';
	}
	$paramString = substr($paramString,0,-1);
	write_log(ROOT_PATH."log","meitu_orderQuery_result_","key=$signKey ,".$paramString.date("Y-m-d H:i:s")."\r\n");
	$sign = base64_encode(hash_hmac("sha1", $paramString, $signKey, $raw_output=TRUE));
	return $sign;
}
?>
