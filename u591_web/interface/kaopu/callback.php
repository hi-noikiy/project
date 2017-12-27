<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 靠谱回调地址
* ==============================================
* @date: 2015-10-23
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);

$get = serialize($_GET);
$ip = getIP_front();

write_log(ROOT_PATH."log","kaopu_callback_log_","post=$post,get=$get,ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
$username = $_REQUEST['username'];
$orderid = $_REQUEST['kpordernum'];
$ywordernum = $_REQUEST['ywordernum']; //gameid_serverid_accountid_kpordernum
$status = $_REQUEST['status'];
$paytype = $_REQUEST['paytype'];
$amount = $_REQUEST['amount'];
$gameserver = $_REQUEST['gameserver'];
$errdesc = $_REQUEST['errdesc'];
$paytime = $_REQUEST['paytime'];
$gamename = $_REQUEST['gamename'];
$sign = $_REQUEST['sign'];

//根据客户端获取
$ywordernumArr=explode('_', $ywordernum);
$gameId = $ywordernumArr[0];
$serverId= $ywordernumArr[1];
$accountId= $ywordernumArr[2];
$isgoods= $ywordernumArr[4];

if(!$gameId || !$serverId || !$accountId){
	$str= "parameters error: post=$post, get=$get,".date("Y-m-d H:i:s")."\r\n";
	write_log(ROOT_PATH."log","kaopu_callback_error_log_",$str);
	exit("-1");
}

$sinkey = $arr_key[$gameId]['SECRETKEY'];

$mysign = $username.'|'.$orderid.'|'.$ywordernum.'|'.$status.'|'.$paytype.'|'.$amount.'|'.$gameserver.'|'.$errdesc.'|'.$paytime.'|'.$gamename.'|'.$sinkey;
$mysign = md5($mysign);

if($mysign != $sign){
	$str= " sign error: post=$post, get=$get, mysign=$mysign,".date("Y-m-d H:i:s")."\r\n";
	write_log(ROOT_PATH."log","kaopu_callback_error_log_",$str);
	exit("-2");
}

//获取账号信息
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = $conn = SetConn($accountConn);
$sql_account = " select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
	$str= " account not exist: post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n";
	write_log(ROOT_PATH."log","kaopu_callback_error_log_",$str);
	exit("-1");//账号不存在
}else{
	$PayName = $result_account['NAME'];
	$dwFenBaoID = $result_account['dwFenBaoID'];
	$clienttype = $result_account['clienttype'];
}
$PayMoney = intval($amount/100);

$conn = $conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$orderid' limit 1;";
$query = mysqli_query($conn, $sql);
$result_count = mysqli_fetch_assoc($query);

$returnSign = md5('1000|'.$arr_key[$gameId]['SECRETKEY']);
if($result_count['rpCode']==1){
	$str= "orderid exist: post=$post, get=$get,".date("Y-m-d H:i:s")."\r\n";
	write_log(ROOT_PATH."log","kaopu_callback_error_log_",$str);
	
	$msg = urlencode('成功');
	exit(urldecode(json_encode(array('code'=>1000, 'msg'=>$msg, 'sign'=>$returnSign))));
	//订单已存在
}
if($status == '1'){
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
	$sql=$sql." VALUES ('112',$accountId,'$PayName','$serverId','$PayMoney','$orderid','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype','1')";
	if (mysqli_query($conn, $sql) == False){
		$str=$sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n";
		write_log(ROOT_PATH."log","kaopu_callback_error_log_",$str);
		exit("-3");
	}
	WriteCard_money(1,$serverId, $PayMoney,$accountId, $orderid,8,0,0,$isgoods);
	$msg = urlencode('成功');
	//统计数据
	global $tongjiServer;
	$tjAppId = $tongjiServer[$gameId];
	sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$PayMoney,$orderid,1,$tjAppId);
	exit(urldecode(json_encode(array('code'=>1000, 'msg'=>$msg, 'sign'=>$returnSign))));
}else{
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
	$sql=$sql." VALUES (112,$accountId,'$PayName','$serverId','$PayMoney','$orderid','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',2)";
	if (mysqli_query($conn, $sql) == False){
		write_log(ROOT_PATH."log","aboluo_callback_error_", "sql error!".$sql."  , ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
		exit("-3");
	}
	$msg = urlencode('成功');
	exit(urldecode(json_encode(array('code'=>1000, 'msg'=>$msg, 'sign'=>$returnSign))));
}
?>