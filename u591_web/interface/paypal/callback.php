<?php
require_once('config.php');
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","paypal_callback_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$response = $_REQUEST;
$orderinfo = $response['invoice'];
$extendsInfoArr = explode('_', $orderinfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
global $key_arr;
$response['cmd'] = '_notify-validate';
$url= $key_arr[$gameId][$type]['action'];
$resultstr = https_post($url, $response);
write_log(ROOT_PATH."log","paypal_callback_log_","result=$resultstr,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($resultstr == 'VERIFIED'){
	if($response['payment_status'] == 'Completed' && $response['receiver_email'] == $key_arr[$gameId][$type]['business'] &&
			$response['mc_currency'] == $key_arr[$gameId][$type]['currency_code'] && $response['mc_gross']>0){ // 支付成功
		$currency = $response['mc_currency'];
		$orderId = $response['txn_id'];
		$conn = SetConn(88);
		$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
		$query = mysqli_query($conn, $sql);
		$result = @mysqli_fetch_array($query);
		if($result['rpCode']==1 || $result['rpCode']==10){
			exit("success");
		}
		$payMoney = $response['mc_gross'];
		global $accountServer;
		$accountConn = $accountServer[$gameId];
		$conn = SetConn($accountConn);
		$sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
		$query_account = mysqli_query($conn, $sql_account);
		$result_account = @mysqli_fetch_assoc($query_account);
		if(!$result_account['NAME']){
			write_log(ROOT_PATH."log","paypal_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
			exit("FAILURE");
		}else{
			$PayName = $result_account['NAME'];
			$dwFenBaoID = $result_account['dwFenBaoID'];
			$clienttype = $result_account['clienttype'];
		}
		$conn = SetConn(88);
		$Add_Time=date('Y-m-d H:i:s');
		$sql="insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
		$sql=$sql." VALUES (179,'$currency', $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
		if (mysqli_query($conn,$sql) == False){
			write_log(ROOT_PATH."log","paypal_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
			exit('FAILURE');
		}
		$EMoney = ceil($payMoney*60);//emoney
		//write_log(ROOT_PATH."log","paypal_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
		WriteCard_money(1,$serverId, $EMoney,$accountId, $orderId);
		//统计数据
		global $tongjiServer;
		$tjAppId = $tongjiServer[$gameId];
		sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
		exit("success");
	}else{
		exit("fail");
	}
}
$conn = SetConn(88);
$Add_Time=date('Y-m-d H:i:s');
$sql = "insert into web_paypal_order (orderid,Add_time,status,orderinfo,amount,email)";
$sql=$sql." VALUES ('{$response['txn_id']}','{$Add_Time}', '{$resultstr}','{$response['invoice']}','{$response['mc_gross']}','{$response['payer_email']}')";
@mysqli_query($conn,$sql);
write_log(ROOT_PATH."log","paypal_callback_into_", "$sql.  ".date("Y-m-d H:i:s")."\r\n");
