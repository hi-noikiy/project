<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 口袋uc礼包直通车
* ==============================================
* @date: 2016-8-19
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/uc_gift/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		'apiKey' =>'0dbddcc74ed6e1a3c3b9708ec32d0532',
		'caller'=>'hainiu',
		'aesKey'=>'30W4bm4de86yn23F',
);
$giftList = array(
		8901=>'封测新手礼包', 
		8903=>'封测精英礼包', 
		8904=>'封测公会入驻礼包',
		8934=>'宠爱10级礼包',
		8935=>'宠爱30级礼包',
		8936=>'宠爱40级礼包',
		8937=>'宠爱50级礼包',
);

function decryptText($aesText, $key, $iv){
	$decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,base64_decode($aesText),MCRYPT_MODE_CBC,$iv);
	return trim($decryptText);
}

function encyptText($text, $key, $iv){
	$aesText = mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$key,$text,MCRYPT_MODE_CBC,$iv);
	$encryptText = base64_encode($aesText);
	return $encryptText;
}


function subTable($accountId, $table, $sum){
	$suffix = $accountId%$sum;
	$s = sprintf('%03d', $suffix);
	return $table.$s;
}

?>