<?php
include_once 'config.php';
include_once 'appPayRequest.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","alipay_callback_all_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
//$str ='a:25:{s:12:"total_amount";s:4:"0.01";s:8:"buyer_id";s:16:"2088802080560517";s:8:"trade_no";s:28:"2016112721001004510294660841";s:11:"notify_time";s:19:"2016-11-27 22:23:20";s:7:"subject";s:8:"60钻石";s:9:"sign_type";s:3:"RSA";s:14:"buyer_logon_id";s:11:"134****5424";s:11:"auth_app_id";s:16:"2016110802629965";s:7:"charset";s:5:"utf-8";s:11:"notify_type";s:17:"trade_status_sync";s:14:"invoice_amount";s:4:"0.01";s:12:"out_trade_no";s:29:"8_8001_56104_2016112710110251";s:12:"trade_status";s:13:"TRADE_SUCCESS";s:11:"gmt_payment";s:19:"2016-11-27 22:23:20";s:7:"version";s:3:"1.0";s:12:"point_amount";s:4:"0.00";s:4:"sign";s:172:"KEslGiLqspiO07gwJMSxufq7v79pFKa0hmGLJImbFtkHOJaQAiRCsWjgNwdlRf2KyQqQSfBsixIJStBe1l0RpGt1Lf/c/wvgLQaOtEJV3R+ayoGMPwoEShuzBBrvKxs5vDoTAypzONHWNIPZY5TXny/utxOWeSnIbKbc4a6Pmnw=";s:10:"gmt_create";s:19:"2016-11-27 22:23:19";s:16:"buyer_pay_amount";s:4:"0.01";s:14:"receipt_amount";s:4:"0.01";s:14:"fund_bill_list";s:49:"[{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]";s:6:"app_id";s:16:"2016110802629965";s:9:"seller_id";s:16:"2088801186307426";s:9:"notify_id";s:34:"b99fb2c891f6588ca4418584e562c97jxq";s:12:"seller_email";s:14:"linhq@u591.com";}';
//$_POST = unserialize($str);
$out_trade_no = $_POST['out_trade_no'];
$outTradeNoArr = explode('_', $out_trade_no);
$game_id = $outTradeNoArr[0];
$server_id = $outTradeNoArr[1];
$account_id = $outTradeNoArr[2];
$isgoods = isset($outTradeNoArr[4])?$outTradeNoArr[4]:0;
global $key_arr;
$rsaPublicKey = $key_arr[$game_id]['publicKey'];

$appPaRequest = new appPayRequest();
$appPaRequest->setRsaPublicKey($rsaPublicKey);
$result = $appPaRequest->rsaCheckV1($_POST, $rsaPublicKeyFilePath);
if($result && $_POST['trade_status'] == 'TRADE_SUCCESS'){
	//获取账号信息
	$money = intval($_POST['total_amount']);
    global $accountServer;
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1";
	$query_account = mysqli_query($conn,$sql_account);
	$result_account = mysqli_fetch_assoc($query_account);
	if(!$result_account['NAME']){
		write_log(ROOT_PATH."log","alipay_callback_error_", "account is not exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit("fail");//账号不存在
	}else{
		$PayName = $result_account['NAME'];
		$dwFenBaoID = $result_account['dwFenBaoID'];
		$clienttype = $result_account['clienttype'];
	}
	$conn = SetConn(88);
	//判断订单id情况
	$sql = "select id,rpCode from web_pay_log where OrderID ='$out_trade_no' limit 1";
	$query = @mysqli_query($conn,$sql);
	$result_count = @mysqli_fetch_assoc($query);
	if($result_count['id']){
		write_log(ROOT_PATH."log","alipay_callback_error_", "order is exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit("success");//订单已存在
	}
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
	$sql=$sql." VALUES (128,$account_id,'$PayName','$server_id','$money','$out_trade_no','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
	if (mysqli_query($conn,$sql) == False){
		write_log(ROOT_PATH."log","alipay_callback_error_", $sql." ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
		exit("failure");
	}
	WriteCard_money(1,$server_id, $money,$account_id, $out_trade_no,8,0,0,$isgoods);
	//统计数据
    global $tongjiServer;
	$tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$money,$out_trade_no,1,$tjAppId);
    exit("success");
}else{
	write_log(ROOT_PATH."log","alipay_callback_error_", "sign error! result=$result, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("success"); 
}