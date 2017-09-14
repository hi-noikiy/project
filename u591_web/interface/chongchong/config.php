<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 虫虫
* ==============================================
* @date: 2016-11-20
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/chongchong/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";
include_once ROOT_PATH.'inc/config_account.php';

$key_arr = array(
    8=>array('appId'=>'108646','appkey'=>'1035cf23b5d54d0a90e1079e6a059f0d', 'appSecret'=>'8349691748054da79bdbec57f4198914')
);
function httpBuidQuery($array, $appKey){
	if(!is_array($array))
		return false;
	if(!$appKey) return false;
	ksort($array);
	$md5Str = http_build_query($array).'&'.$appKey;
	//$mySign = md5($md5Str.$appKey);
    return urldecode($md5Str);
}
?>