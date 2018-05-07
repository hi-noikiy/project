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
write_log(ROOT_PATH."log","jinli_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

/*
 充值回调信息
 */
$contents = $_POST;
$extendsInfo = $contents['out_order_no'];
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$playerId = $extendsInfoArr[2];
$accountId = $extendsInfoArr[3];
$type = $extendsInfoArr[4];
$isgoods = 0;
$publickey = $key_arr[$gameId][$type]['publicKey'];
if(!rsa_verify($contents,$publickey)){
	write_log(ROOT_PATH."log","jinli_callback_error_","sign error,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	die('error sign');
}
$conn = SetConn(88);
$orderId = $extendsInfo;
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' and game_id='$gameId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit("success");
}
$payMoney = $contents['deal_price'];
if(!$result){
	$snum = giQSModHash($accountId);
	$conn = SetConn($gameId,$snum,1);//account分表
	$acctable = betaSubTableNew($accountId,'account',999);
	$sql_account = "select NAME,dwFenBaoID,clienttype from $acctable where id=$accountId limit 1;";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","jinli_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("FAILURE");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $loginname = 'jinli';
    if(isOwnWay($PayName,$loginname)){
    	write_log(ROOT_PATH."log","name_{$loginname}_", "account is $PayName ! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    	exit("success");
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PlayerID,data,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (188, $accountId,'$playerId','$payMoney','$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","jinli_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('FAILURE');
    }
    WriteCard_money(1,$serverId, $payMoney,$playerId, $orderId);
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId);
    exit("success");
}
exit("success");

function rsa_verify($post_arr,$publickey){
	ksort($post_arr);
	foreach($post_arr as $key => $value){
		if($key == 'sign') continue;
		$signature_str .= $key.'='.$value.'&';
	}
	$signature_str = substr($signature_str,0,-1);
	// 【NOTE】跑通demo后替换成商户自己的publickey
// 	$publickey= 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCYqyyVTZiOgM6fK8f4FoEN8IK8lWYDK0iTAkamGlXe00h1jsrSb23pVlBUr6y0WHoq2J2xC6Fh4ama8P22INyNXC0dvokcmBK9rWD6kmJMTZxWC9rMa1wFUGQDbQHVVUDM+zGXw4rMntcLVdu/fzCf6xL5HjyjQ1qTR1xuWePkzQIDAQAB';
	$pem = chunk_split($publickey,64,"\n");
	$pem = "-----BEGIN PUBLIC KEY-----\n".$pem."-----END PUBLIC KEY-----\n";
	$public_key_id = openssl_pkey_get_public($pem);
	$signature =base64_decode($post_arr['sign']);
	return openssl_verify($signature_str, $signature, $public_key_id);
}