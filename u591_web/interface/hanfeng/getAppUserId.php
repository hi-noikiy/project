<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 汉风获取userId接口
* ==============================================
* @date: 2016-7-28
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","hanfeng_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$p = $_REQUEST['p'];
$sid = $_REQUEST['sid'];
$game_id = intval($_REQUEST['game_id']);
if(!$p || !$sid || !$game_id){
	write_log(ROOT_PATH."log","hanfeng_login_error_"," parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('0');
}
$pArr = explode('_', $p);
if(!isset($pArr[0]) || !isset($pArr[1]) || !isset($pArr[2]))
	exit('0');
$channel = $pArr[0];
$userId = $pArr[1];
$version = $pArr[2];
$url = 'http://119.29.50.47:4005/verifyToken';
//$url = 'http://123.207.92.126:5003/verifyToken'; test url
$appId = $key_arr[$game_id]['appid'];
$appkey = $key_arr[$game_id]['appkey'];
$mySignStr = "$appId|$channel|$userId|$sid|$version|$appkey";
$mySign = md5($mySignStr);

$data = array();
$data['gameId'] = $appId;
$data['channel'] = $channel;
$data['userId'] = $userId;
$data['sid'] = $sid;
$data['version'] = $version;
$data['sign'] = $mySign;
$result = common_json_post($url, json_encode($data));
$resultArr = json_decode($result, true);
if(isset($resultArr['status']) && $resultArr['status'] == 'YHYZ_000'){
	$userId = trim($resultArr['userId']);
	exit($userId);
} else {
	write_log(ROOT_PATH."log","hanfeng_login_error_","result=$result, mySignStr=$mySignStr, post=$post, get=$get,, ".date("Y-m-d H:i:s")."\r\n");
	exit('0');
}
exit('0');
?>