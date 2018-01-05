<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 参数组装
* ==============================================
* @date: 2016-11-24
* @author: luoxue
* @version:
*/
header("Content-type: text/html; charset=utf-8");
include_once 'config.php';
include_once 'appPayRequest.php';

$post = serialize($_POST);
$get = serialize($_GET);

$subject = $_POST['subject'];
$bizContent = $_POST['biz_content']; //json{"game_id":"8","server_id":"xx", "account_id":"xxx"}
$amount = $_POST['total_amount'];


write_log(ROOT_PATH."log","alipay_params_all_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$subject || !$bizContent || !$amount){
	write_log(ROOT_PATH."log","alipay_params_error_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit();
}
/*
 * 由于不需要biz_content这个参数
 * 客户端修改又麻烦，自己转了。
 */
$bizContentArr = json_decode($bizContent, true);
$gameId = $bizContentArr['game_id'];
$serverId = $bizContentArr['server_id'];
$accountId = $bizContentArr['account_id'];
$giftId = $bizContentArr['gift_id'];

$bizContent = $gameId.'_'.$serverId.'_'.$accountId.'_android_'.$giftId;
$appId = $key_arr[$gameId]['appId'];
$rsaPrivateKey = $key_arr[$gameId]['privateKey'];

$appPayRequest = new appPayRequest();
$appPayRequest->setBizContent($bizContent);
$appPayRequest->setSubject($subject);
$appPayRequest->setAmount($amount);
$appPayRequest->setAppId($appId);
$appPayRequest->setRsaPrivateKey($rsaPrivateKey);

$params = $appPayRequest->getParams();

$sign = $appPayRequest->rsaSign($params);

//待签名字符串
$preStr=$appPayRequest->getSignContent($params);
$preSignStr = $appPayRequest->getSignContentUrlencode($params);


$str = base64_encode($preSignStr."&sign=".urlencode($sign));
write_log(ROOT_PATH."log","alipay_params_sign_","post=$post, get=$get, str=$preStr ".date("Y-m-d H:i:s")."\r\n");
exit($str);
?>