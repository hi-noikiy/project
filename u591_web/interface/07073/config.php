<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 07073
* ==============================================
* @date: 2016-11-20
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/07073/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";
include_once ROOT_PATH.'inc/config_account.php';

$arr_key = array(
		8=>array('gameId'=>'1045', 'pid'=>'1075','secretKey'=>'639A992DC96D49D507A8B144DA017B'),
);
function gethttpcurl($url, $ntarr = null){
	$header = array('User-Agent: Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1)');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_TIMEOUT, (isset($ntarr['timeout']) && is_numeric($ntarr['timeout']))?$ntarr['timeout']:10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if(is_array($ntarr['postarr'])){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $ntarr['postarr']);
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$s = curl_exec($ch);
	curl_close($ch);
	return $s;
}
?>