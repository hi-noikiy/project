<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/19
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","yijie_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$requestSign = $_GET['sign'];//请求链接的sign
$customData_arr = explode('_', $_GET['cbi']);
$gameId = $customData_arr[0];
$serverId = $customData_arr[1];
$accountId = $customData_arr[2];
$type = $customData_arr[3];
$key = $key_arr[$gameId][$type]['app_key'];
$params = $_GET;
unset($params['sign']);
ksort($params);//对参数字母排序
reset($params);
$source = http_build_query($params).$key;//拼接出源串
$sign = md5($source);

if($requestSign != $sign){
	//流程处理
	write_log(ROOT_PATH."log","yijie_callback_error_","sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('sign fail');
}
if($_GET['st'] != 1){
	write_log(ROOT_PATH."log","yijie_callback_error_","交易失败，post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('success');
}
$conn = SetConn(88);
$orderId = $params['cbi'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
	exit("success");
}
$payMoney = $params['fee']/100;
if(!$result){
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	$sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
	$query_account = mysqli_query($conn, $sql_account);
	$result_account = @mysqli_fetch_assoc($query_account);
	if(!$result_account['NAME']){
		write_log(ROOT_PATH."log","yijie_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
		exit("fail");
	}else{
		$PayName = $result_account['NAME'];
		$dwFenBaoID = $result_account['dwFenBaoID'];
		$clienttype = $result_account['clienttype'];
	}
	$conn = SetConn(88);
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
	$sql=$sql." VALUES (185, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
	if (mysqli_query($conn,$sql) == False){
		write_log(ROOT_PATH."log","yijie_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
		exit('fail');
	}
	write_log(ROOT_PATH."log","yijie_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
	WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
	//统计数据
	global $tongjiServer;
	$tjAppId = $tongjiServer[$gameId];
	sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
	exit("success");
}
exit("success");

