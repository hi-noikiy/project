<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 阿斯卡德配置文件
* ==============================================
* @date: 2015-10-23
* @author: Administrator
* @return:
*/
define('ROOT_PATH', str_replace('interface/asdk/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";


$arr_key = array(
		8=>array('appId'=>'100079', 'appKey'=>'51c5212a59135401', 'gameId'=>'100524')
);
function httpBuidQuery($array, $appKey){
	if(!is_array($array))
		return false;
	if(!$appKey) return false;
	ksort($array);
	$md5Str = http_build_query($array);
	$mySign = md5($md5Str.$appKey);
	return $mySign;
}
?>