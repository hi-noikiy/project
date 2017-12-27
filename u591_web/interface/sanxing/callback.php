<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 20170615
 * Time: 上午10:02
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","sanxing_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$sign = $_REQUEST["sign"];
$sign = base64_decode(urldecode($sign));
$extendsInfo = $_REQUEST['out_trade_no']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
$isgoods = intval($extendsInfoArr[4]);
$pubkey = $karr[$type];
/*$pubkey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKjScHuWqaEApYhdJ7
B3zmzHNxMf4286olqspqkg+aicVGiZAmd2L5XMQT/
m6LSYr132eLqA4Y768whu9YC8RnGxbwtQA7/
Y4LCMfgGIP74FEqpBMIccsyj7P8bobKqpD+krF5KZSm/
2tGIy2kJNGbduGcJoaVzmJw2/S608AK9QIDAQAB
-----END PUBLIC KEY-----";*/
$info = "";
$pub = openssl_pkey_get_public($pubkey);
$ret = openssl_public_decrypt($sign, $info, $pub);
if(!$ret) {
	write_log(ROOT_PATH."log","sanxing_callback_error_","sign验证失败,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	die("fail");
}
$data = $_REQUEST;
unset($data['sign']);
ksort($data);
$info1 = http_build_query($data);
$isok = verify_sign(urldecode($info1),$sign,$pubkey) ;
if($isok == 1){//验证成功
	$conn = SetConn(88);
	$orderId = $data['out_trade_no'];
	$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
	$query = mysqli_query($conn, $sql);
	$result = @mysqli_fetch_array($query);
	if($result['rpCode']==1 || $result['rpCode']==10){
		exit("success");
	}
	$payMoney = intval($data['total_fee']/100);
	if(!$result){
		global $accountServer;
		$accountConn = $accountServer[$gameId];
		$conn = SetConn($accountConn);
		$sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
		$query_account = mysqli_query($conn, $sql_account);
		$result_account = @mysqli_fetch_assoc($query_account);
		if(!$result_account['NAME']){
			write_log(ROOT_PATH."log","sanxing_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
			exit("fail");
		}else{
			$PayName = $result_account['NAME'];
			$dwFenBaoID = $result_account['dwFenBaoID'];
			$clienttype = $result_account['clienttype'];
		}
		$conn = SetConn(88);
		$Add_Time=date('Y-m-d H:i:s');
		$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
		$sql=$sql." VALUES (165, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
		if (mysqli_query($conn,$sql) == False){
			write_log(ROOT_PATH."log","sanxing_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
			exit('fail');
		}
		//write_log(ROOT_PATH."log","sanxing_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
		WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
		//统计数据
		global $tongjiServer;
		$tjAppId = $tongjiServer[$gameId];
		sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
		exit("success");
	}
}
write_log(ROOT_PATH."log","sanxing_callback_error_", "isok=$isok.  ".date("Y-m-d H:i:s")."\r\n");
exit("fail");


function verify_sign($data,$signature,$pubkeyid){
	$signature = $signature;
	$ok = openssl_verify($data, $signature, $pubkeyid);
	if($ok==1){
		return $ok;
	}else{
		$sha1 = sha1($data, true);
		$sign = $signature;
		$deco = '';
		openssl_public_decrypt($sign, $deco, $pubkeyid);
		write_log(ROOT_PATH."log","sanxing_callback_sign_","data=$data,pubkeyid=$pubkeyid,deco=$deco,sha1=$sha1 ".date("Y-m-d H:i:s")."\r\n");
		if($sha1==$deco){
			$ok=1;
		}else{
			$ok=2;
		}
		return $ok;
	}
}