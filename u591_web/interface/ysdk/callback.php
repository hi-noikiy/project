<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 支付回调
* ==============================================
* @date: 2016-10-26
* @author: luoxue
* @version:
*/
require_once 'config.php';
require_once 'ysdks/Api.php';
require_once 'ysdks/Payments.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","ysdk_callback_log_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$channel = trim($_REQUEST['channel']);
$gameId = intval($_REQUEST['game_id']);
$orderId = $_POST['order_id'];
$serverId = $_POST['server_id'];
$chn = explode('_', $channel);
$channel = $chn[0];
$type = isset($chn[1])?$chn[1]:'android';
$isgoods = isset($chn[2])?$chn[2]:'0';
global $key_arr;
/*if($channel == 'qq'){
	$appid = $key_arr[$gameId][$channel]['appId'];
	$appkey = $key_arr[$gameId][$channel]['appKey'];
} elseif ($channel == 'weixin') {
	$appid = $key_arr[$gameId][$channel]['appId'];
}*/
$appid = $key_arr[$gameId][$type][$channel]['appId'];
$appkey = $key_arr[$gameId][$type][$channel]['appKey'];
// 应用支付基本信息,需要替换为应用自己的信息，必须和客户端保持一致
// 需要登录腾讯开放平台管理中心 http://op.open.qq.com/，选择已创建的应用进入，然后进入支付结算，完成支付的接入配置
$pay_appid = $key_arr[$gameId]['pay']['appId'];
$pay_appkey = $key_arr[$gameId]['pay']['appKey'];
// YSDK后台API的服务器域名
// 调试环境: ysdktest.qq.com
// 正式环境: ysdk.qq.com
// 调试环境仅供调试时调用，调试完成发布至现网环境时请务必修改为正式环境域名
$server_name = 'ysdk.qq.com';

// 用户的OpenID，从客户端YSDK登录返回的LoginRet获取
$openid = $_POST['openid'];
// 用户的openkey，从客户端YSDK登录返回的LoginRet获取
$openkey = $_POST['openkey'];
// 支付接口票据, 从客户端YSDK登录返回的LoginRet获取
$pay_token = $_POST['payToken'];
// 支付接口票据, 从客户端YSDK登录返回的LoginRet获取
$pf = $_POST['pf'];
// 支付接口票据, 从客户端YSDK登录返回的LoginRet获取
$pfkey = $_POST['pfkey'];
/// 当前UNIX时间戳
$ts=time();
// 用户的IP，可选，默认为空
$userip = '';
//支付相关参数
$zoneid=1;
// 创建YSDK实例
$sdk = new Api($appid, $appkey);
// 设置支付信息
$sdk->setPay($pay_appid, $pay_appkey);
// 设置YSDK调用环境
$sdk->setServerName($server_name);
$params = array(
	'openid'    => $openid,
	'openkey'   => $openkey,
	'pay_token' => $pay_token,
	'ts'        => $ts,
	'pf'        => $pf,
	'pfkey'     => $pfkey,
	'zoneid'    => $zoneid,
);
//检查订单
$conn = SetConn(88);
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
	exit('success');
}
$accout_type=$channel;
$ret = get_balance_m($sdk, $params,$accout_type);
write_log(ROOT_PATH."log","ysdk_callback_result_log_",json_encode($ret).",  post=$post, ".date("Y-m-d H:i:s")."\r\n");
if($ret['ret'] == 0){
	$amt = intval($_POST['amt']);
	if($amt == 0)
		exit('success');
	$params['billno']=$orderId;
	$params['amt'] = $amt;
	$ret2 = pay_m($sdk, $params, $accout_type);
	$sRet1 = serialize($ret);
	$sRet2 = serialize($ret2);
	write_log(ROOT_PATH."log","ysdk_callback_result_log_"," ret1=$sRet1,ret2=$sRet2, ".date("Y-m-d H:i:s")."\r\n");
	if($ret2['ret'] == 0){
        global $accountServer;
		$accountConn = $accountServer[$gameId];
		$conn = SetConn($accountConn);
		$channel_account=mysqli_real_escape_string($conn,$openid.'@ysdk');
		$sql = " select id,NAME,dwFenBaoID,clienttype from account where channel_account = '$channel_account' limit 1";
		$query = mysqli_query($conn, $sql);
		$result = @mysqli_fetch_assoc($query);
		if(!$result['NAME']){
			write_log(ROOT_PATH."log","ysdk_callback_error_log_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
			exit("fail");
		}else{
			$accountId = $result['id'];
			$PayName = $result['NAME'];
			$dwFenBaoID = $result['dwFenBaoID'];
			$clienttype = $result['clienttype'];
		}
 		$conn = SetConn(88);
 		$Add_Time=date('Y-m-d H:i:s');
 		$payMoney = $amt;
 		$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
 		$sql=$sql." VALUES (114,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',1)";
		if(mysqli_query($conn,$sql)){
			WriteCard_money(1, $serverId, $payMoney, $accountId, $orderId,8,0,0,$isgoods);
			//统计数据
            global $tongjiServer;
			$tjAppId = $tongjiServer[$gameId];
            sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
			exit('success');
		} else
			exit('fail');
	}
} else {
	$return = serialize($ret);
	$params = serialize($params);
	write_log(ROOT_PATH."log","ysdk_callback_error_log_"," ret=$return,params=$params, ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
?>