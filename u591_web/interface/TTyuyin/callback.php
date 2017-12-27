<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * TT语音支付回调
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
header("Content-type: text/html; charset=utf-8");
include_once 'config.php';
require_once 'service/SDKServices.php';

$post = serialize($_POST);
$get = serialize($_GET);
$data = $GLOBALS["HTTP_RAW_POST_DATA"];
$urldecodeData = urldecode($data);
write_log(ROOT_PATH."log","TTyuyin_callback_all_"," post=$post,get=$get, data=$urldecodeData, ".date("Y-m-d H:i:s")."\r\n");
//获取SDK服务器回调
if (empty($GLOBALS["HTTP_RAW_POST_DATA"])) {
	echo '非法请求!';
} else {
	//从headers中的SIGN获取到SDK服务器的签名
	$ttsign = $_SERVER['HTTP_SIGN'];
	//SDK服务器报文
	$urldata = urldecode($GLOBALS["HTTP_RAW_POST_DATA"]);
	//签名验证
	$dataArr = json_decode($urldecodeData, true);
	$exInfo = $dataArr['exInfo'];
	$exInfoArr = explode("_", $exInfo);
	$game_id = $exInfoArr[0];
	$server_id = $exInfoArr[1];
	$account_id = $exInfoArr[2];
	$type = $exInfoArr[3];
	$isgoods = intval($exInfoArr[4]);
	global $key_arr;
	$msg = SDKServices::verifyNotify($ttsign, $urldata,$key_arr[$type]['chargekey']);
	write_log(ROOT_PATH."log","TTyuyin_callback_all_"," post=$post,get=$get, result=$msg, ".date("Y-m-d H:i:s")."\r\n");
	$msgArr = json_decode($msg, true);
	if(isset($msgArr['head']['result']) && $msgArr['head']['result'] == 0){
		$cpOrderId = $dataArr['cpOrderId'];	
		$gameId = $dataArr['gameId'];
		$payDate = $dataArr['payDate'];
		$payFee = $dataArr['payFee'];
		$payResult = $dataArr['payResult'];
		$sdkOrderId = $dataArr['sdkOrderId'];
		$appUid = $dataArr['uid'];
		global $accountServer;
		$accountConn = $accountServer[$game_id];
		$conn = SetConn($accountConn);
		$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$account_id' limit 1;";
		$query_account = @mysqli_query($conn, $sql_account);
		$result_account = @mysqli_fetch_assoc($query_account);
		if(!$result_account['NAME']){
			write_log(ROOT_PATH."log","TTyuyin_callback_error_", "account is not exist! sql=$sql_account, data=$urldecodeData, ".date("Y-m-d H:i:s")."\r\n");
			exit('FAIL');
		}else{
			$PayName = $result_account['NAME'];
			$dwFenBaoID = $result_account['dwFenBaoID'];
			$clienttype = $result_account['clienttype'];
		}
		$order_id = $cpOrderId;
		$PayMoney = intval($payFee);
		$conn = SetConn(88);
		//判断订单id情况
		$sql = "select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
		$query=mysqli_query($conn,$sql);
		$result_count=mysqli_fetch_assoc($query);
		if($result_count['id']){
			write_log(ROOT_PATH."log","TTyuyin_callback_error_", "order is exist! post=$post, get=$get, data=$urldecodeData, ".date("Y-m-d H:i:s")."\r\n");
			exit($msg);
		}
		$Add_Time=date('Y-m-d H:i:s');
		$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
		$sql=$sql." VALUES (121,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype','1')";
		if (mysqli_query($conn,$sql) == False){
			write_log(ROOT_PATH."log","TTyuyin_callback_error_", $sql.", post=$post, get=$get, data=$urldecodeData, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
			exit('FAIL');
		} else {
			WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
			//统计数据
			global $tongjiServer;
            $tjAppId = $tongjiServer[$game_id];
            sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
			exit($msg);
		}
	}
}
?>