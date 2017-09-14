<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 极光推送
* ==============================================
* @date: 2016-7-12
* @author: Administrator
* @return:
*/
define('ROOT_PATH', str_replace('interface/jpush/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		//jpush key secret
		9=>array('appKey'=>'693f84f4edc3b6f8303d1581','appSecret'=>'f7a7afac7343d187cf7a2de6')
);
$hainiuAppKey = '0dbddcc74ed6e1a3c3b9708ec32d0532';

function httpBuidQuery($array, $appKey){
	if(!is_array($array))
		return false;
	if(!$appKey) return false;
	ksort($array);
	$md5Str = http_build_query($array);
	$mySign = md5(urldecode($md5Str).$appKey);
	return $mySign;
}
