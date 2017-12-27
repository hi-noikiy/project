<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 阿斯卡德支付回调
* ==============================================
* @date: 2016-11-3
* @author: luoxue
* @version:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log(ROOT_PATH."log","asdk_callback_all_"," post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
$sdkAccountId = $_REQUEST['account'];
$money = $_REQUEST['money']; //单位分
$addtime = $_REQUEST['addtime']; //时间戳
$orderId = $_REQUEST['orderid'];

$paytype = $_REQUEST['paytype'];
$senddate = $_REQUEST['senddate']; //时间戳

$customorderid = $_REQUEST['customorderid'];
$custominfo = $_REQUEST['custominfo'];
$success = $_REQUEST['success'];
$sign = $_REQUEST['sign'];

$array = array();
$array['account'] = $sdkAccountId;
$array['money'] = $money;
$array['addtime'] = $addtime;
$array['orderid'] = $orderId;
$array['customorderid'] = $customorderid;
$array['paytype'] = $paytype;
$array['senddate'] = $senddate;

$array['custominfo'] = $custominfo;
$array['success'] = $success;

//根据客户端获取
$custominfoArr = explode('_', $custominfo);
$gameId = $custominfoArr[0];
$serverId= $custominfoArr[1];
$accountId= $custominfoArr[2];
$isgoods = $custominfoArr[4];
$appKey = $arr_key[$gameId]['appKey'];

$mySign = httpBuidQuery($array, $appKey);

if(!$gameId ||  !$serverId ||  !$accountId)
	exit('failure');


if($mySign != $sign){
	write_log(ROOT_PATH."log","asdk_callback_check_","sign error! post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
	exit('failure');
}
if($success == 1){
	//获取账号信息
    global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1";
	$query_account = mysqli_query($conn,$sql_account);
	$result_account = mysqli_fetch_assoc($query_account);
	if(!$result_account['NAME']){
		write_log(ROOT_PATH."log","asdk_callback_error_", "account is not exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit("failure");//账号不存在
	}else{
		$PayName = $result_account['NAME'];
		$dwFenBaoID = $result_account['dwFenBaoID'];
		$clienttype = $result_account['clienttype'];
	}
	$conn = SetConn(88);
	//判断订单id情况
	$sql = "select id,rpCode from web_pay_log where OrderID ='$orderId' limit 1";
	$query = mysqli_query($conn, $sql);
	$result_count = @mysqli_fetch_assoc($query);
	if($result_count['id']){
		write_log(ROOT_PATH."log","asdk_callback_error_", "order is exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit("success");//订单已存在
	}
	$Add_Time=date('Y-m-d H:i:s');
	$payMoney = intval($money/100);
	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
	$sql=$sql." VALUES (116,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',1)";
	if (mysqli_query($conn,$sql) == False){
		write_log(ROOT_PATH."log","asdk_callback_error_", $sql." ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
		exit("fail");
	}

	WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
	//统计数据
    global $tongjiServer;
	$tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("success");
}else{
	write_log(ROOT_PATH."log","asdk_callback_error_", "Order Processing! post=$post,get=$get ".date("Y-m-d H:i:s")."\r\n");
	exit("fail");
}
?>