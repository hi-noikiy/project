<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 20170524
 * Time: 上午10:02
 */
include 'config.php';
$post = file_get_contents('php://input');
write_log(ROOT_PATH."log","huawei_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$success = "{\"result\":0}";
$fail = "{\"result\":1}";
if (null === $post || "" === $post)
{
	write_log(ROOT_PATH."log","huawei_callback_error_","param is null, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit($fail);
}

$elements = split('&', $post);
$valueMap = array();
foreach ($elements as $element)
{
	$single = split('=', $element);
	$valueMap[$single[0]] = $single[1];
}
if($valueMap['result'] !='0'){
	write_log(ROOT_PATH."log","huawei_callback_error_","this order is not success, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit($fail);
}

if(null !== $valueMap["sign"])
{
	$valueMap["sign"] = urldecode($valueMap["sign"]);
}
if(null !== $valueMap["extReserved"])
{
	$valueMap["extReserved"]= urldecode($valueMap["extReserved"]);
}
if(null !== $valueMap["sysReserved"])
{
	$valueMap["sysReserved"] = urldecode($valueMap["sysReserved"]);
}

ksort($valueMap);
$sign = $valueMap["sign"];

if(empty($sign))
{
	write_log(ROOT_PATH."log","huawei_callback_error_","sign is null, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit($fail);
}
$sign = str_replace(' ','+',$sign);
$content = "";
$i = 0;
foreach($valueMap as $key=>$value)
{
	if($key != "sign" && $key != "signType")
	{
		$content .= ($i == 0 ? '' : '&').$key.'='.$value;
	}
	$i++;
}
/*$filename = dirname(__FILE__)."/payPublicKey1.pem";

if(!file_exists($filename))
{
	write_log(ROOT_PATH."log","huawei_callback_error_","file is not exit, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit($fail);
}
$pubKey = @file_get_contents($filename);*/
$extendsInfo = $valueMap["requestId"];
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
$isgoods = $extendsInfoArr[4];
$pubKey = $key_arr[$gameId][$type]['appKey'];
$openssl_public_key = @openssl_get_publickey($pubKey);
write_log(ROOT_PATH."log","huawei_callback_result_","content={$content},sign={$sign},openssl_public_key={$pubKey},post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$ok = @openssl_verify($content,base64_decode($sign), $openssl_public_key,OPENSSL_ALGO_SHA256);
@openssl_free_key($openssl_public_key);

$result = "";

if($ok)
{
	write_log(ROOT_PATH."log","huawei_callback_result_","isok,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	$conn = SetConn(88);
	$orderId = $extendsInfo;
	$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
	$query = mysqli_query($conn, $sql);
	$result = @mysqli_fetch_array($query);
	if($result['rpCode']==1 || $result['rpCode']==10){
		exit($success);
	}
	$payMoney = intval($valueMap['amount']);
	if(!$result){
		global $accountServer;
		$accountConn = $accountServer[$gameId];
		$conn = SetConn($accountConn);
		$sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
		$query_account = mysqli_query($conn, $sql_account);
		$result_account = @mysqli_fetch_assoc($query_account);
		if(!$result_account['NAME']){
			write_log(ROOT_PATH."log","huawei_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
			exit($fail);
		}else{
			$PayName = $result_account['NAME'];
			$dwFenBaoID = $result_account['dwFenBaoID'];
			$clienttype = $result_account['clienttype'];
		}
		$conn = SetConn(88);
		$Add_Time=date('Y-m-d H:i:s');
		$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode,packageName)";
		$sql=$sql." VALUES (172, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1','$isgoods')";
		if (mysqli_query($conn,$sql) == False){
			write_log(ROOT_PATH."log","huawei_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
			exit($fail);
		}
		//write_log(ROOT_PATH."log","huawei_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
		WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
		//统计数据
		global $tongjiServer;
		$tjAppId = $tongjiServer[$gameId];
		sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
		exit($success);
	}
	exit($success);
}
else
{
	write_log(ROOT_PATH."log","huawei_callback_error_","sign error ,post=$post".date("Y-m-d H:i:s")."\r\n");
	exit($fail);
}


