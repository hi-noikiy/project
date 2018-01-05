<?php
require_once('config.php');
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","payssion_callback_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$state = $_POST['state'];
if($state != 'completed'){
	write_log(ROOT_PATH."log","payssion_callback_error_",$state.",state error,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("success");
}
$cpids = array(
		'webmoney'=>180,
		'qiwi'=>181,
		'yamoney'=>182,
		'yamoneyac'=>183,
		'yamoneygp'=>184,
);
$cpid = isset($cpids[$_POST['pm_id']])?$cpids[$_POST['pm_id']]:0;
if(!$cpid){
	write_log(ROOT_PATH."log","payssion_callback_error_","payway error,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	header('HTTP/1.1 405 payway error');
	exit("fail");
}
$orderId = $_POST['order_id'];
$extendsInfoArr = explode('_', $orderId);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
$isgoods = $extendsInfoArr[4];
global $key_arr;
$api_key  = $key_arr[$gameId][$type]['api_key'];
$secret_key = $key_arr[$gameId][$type]['secret_key'];

// Assign payment notification values to local variables
$pm_id = $_POST['pm_id'];
$amount = $_POST['amount'];
$currency = $_POST['currency'];
$check_array = array(
		$api_key,
		$pm_id,
		$amount,
		$currency,
		$orderId,
		$state,
		$secret_key
);

$check_msg = implode('|', $check_array);
$check_sig = md5($check_msg);
$notify_sig = $_POST['notify_sig'];
if ($notify_sig == $check_sig) {
	$orderId = $_POST['transaction_id'];
	$conn = SetConn(88);
	$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
	$query = mysqli_query($conn, $sql);
	$result = @mysqli_fetch_array($query);
	if($result['rpCode']==1 || $result['rpCode']==10){
		exit("success");
	}
	$payMoney = $_POST['amount'];
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	$sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
	$query_account = mysqli_query($conn, $sql_account);
	$result_account = @mysqli_fetch_assoc($query_account);
	if(!$result_account['NAME']){
		write_log(ROOT_PATH."log","payssion_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
		header('HTTP/1.1 501 other error');
		exit("FAILURE");
	}else{
		$PayName = $result_account['NAME'];
		$dwFenBaoID = $result_account['dwFenBaoID'];
		$clienttype = $result_account['clienttype'];
	}
	$conn = SetConn(88);
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
	$sql=$sql." VALUES ('$cpid','$currency', $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
	if (mysqli_query($conn,$sql) == False){
		write_log(ROOT_PATH."log","payssion_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
		header('HTTP/1.1 501 other error');
		exit('FAILURE');
	}
	$EMoney = ceil($payMoney*60);//emoney
	//write_log(ROOT_PATH."log","payssion_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
	WriteCard_money(1,$serverId, $EMoney,$accountId, $orderId,8,0,0,$isgoods);
	//统计数据
	global $tongjiServer;
	$tjAppId = $tongjiServer[$gameId];
	sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);

}else{
	write_log(ROOT_PATH."log","payssion_callback_error_",$check_msg.',sign error,'.$notify_sig.",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	header('HTTP/1.1 402 sign error');
	die;
}
