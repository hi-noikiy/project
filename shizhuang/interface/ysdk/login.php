<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* ysdk登录接口
* ==============================================
* @date: 2016-10-25
* @author: luoxue
* @version:
* @param channel=weixin&token=openid,token&game_id=xxx;

*/
require_once 'config.php';
require_once 'ysdks/Api.php';
require_once 'ysdks/Ysdk.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","ysdk_login_all_log_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$channel = trim($_REQUEST['access_token']);
$token = trim($_REQUEST['type']);
$gameId = intval($_REQUEST['game_id']);

$chn = explode('_', $channel);
$channel = $chn[0];
$type = isset($chn[1])?$chn[1]:'android';
//$channel = 'qq';
//$token = 'D2676F95BB1EE4310E4E26C1FA5EF602,E2405A0464018C2BB1065F3DA29693F5';
//$gameId = 8;

if(!in_array($channel, array('weixin', 'qq')) || !$token || !$gameId){
	write_log(ROOT_PATH."log","ysdk_login_error_","params error ! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}
$tokenArr = explode(',', $token);
if(empty($tokenArr[0]) || empty($tokenArr[1])){
	exit("2 0");
}
$openid = $tokenArr[0];
$accesstoken = $tokenArr[1];
$appid = $key_arr[$gameId][$type][$channel]['appId'];
$appkey = $key_arr[$gameId][$type][$channel]['appKey'];

// 调试环境: ysdktest.qq.com
// 正式环境: ysdk.qq.com
// 调试环境仅供调试时调用，调试完成发布至现网环境时请务必修改为正式环境域名
$server_name = 'ysdk.qq.com';
$ts=time();
// 用户的IP，可选，默认为空
$userip = '';
// 创建YSDK实例
$sdk = new Api($appid, $appkey);
// 设置支付信息
// 设置YSDK调用环境
$sdk->setServerName($server_name);
if($channel == 'qq'){
	$params = array(
			'appid' => $appid,
			'openid' => $openid,
			'openkey' => $accesstoken,
			'userip' => $userip,
			'sig' =>   md5($appkey.$ts),
			'timestamp' => $ts,
	);
	$ret = qq_check_token($sdk, $params);
} else if ($channel == 'weixin'){
	$params = array(
			'appid' => $appid,
			'openid' => $openid,
			'userip' => $userip,
			'sig' => md5($appkey.$ts),
			'access_token' => $accesstoken,
			'timestamp' => $ts,
	);
	$ret = wx_check_token($sdk, $params);
}
if($ret['ret'] == 0){
	$username = $openid.'@ysdk';
	$bindtable = getAccountTable($username,'token_bind');
	$bindwhere = 'token';
	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
	if($insertinfo['status'] == '1'){
		write_log(ROOT_PATH."log","ysdk_login_error_log_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}else{
		$insert_id = $insertinfo['data'];
		if($insertinfo['isNew'] == '1'){
			write_log(ROOT_PATH."log","new_account_ysdk_log_","oppo new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
			exit("1 $insert_id");
		}else{
			exit("0 $insert_id");
		}
	}
} else {
	$return = serialize($ret);
	write_log(ROOT_PATH."log","ysdk_login_error_log_"," curl error ! ret=$return, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}
?>