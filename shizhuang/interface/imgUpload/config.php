<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
*
* ==============================================
* @date: 2016-7-23
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/imgUpload/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		'appKey'			=>'0dbddcc74ed6e1a3c3b9708ec32d0532',
		'appSecret' 		=>'074092074142feb68cf2d0dd35d5997a',
);

function httpBuidQuery($array, $appKey){
	if(!is_array($array))
		return false;
	if(!$appKey) return false;
	ksort($array);
	$md5Str = http_build_query($array);
	$mySign = md5(urldecode($md5Str).$appKey);
	return $mySign;
}