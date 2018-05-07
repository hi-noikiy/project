<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 20170524
 * Time: 上午10:02
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","meizu_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$success = json_encode(array('code'=>200));
$fali = json_encode(array('code'=>120014));
if($_REQUEST['trade_status'] != 3){
	write_log(ROOT_PATH."log","meizu_callback_error_","交易失败，post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit($success);
}
$sign = $_REQUEST["sign"];

$extendsInfo = $_REQUEST['user_info']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$playerId = $extendsInfoArr[2];
$accountId = $extendsInfoArr[3];
$type = $extendsInfoArr[4];
$isgoods = 0;
global $key_arr;
$key = $key_arr[$gameId][$type]['appSecret'];
$data = $_REQUEST;
unset($data['sign'],$data['sign_type']);
ksort($data);
$signstr = urldecode(http_build_query($data)).':'.$key;
$data['sign'] = strtolower(md5($signstr));
if($sign != $data['sign']) {
    write_log(ROOT_PATH."log","meizu_callback_error_",$signstr.",sign error,{$data['sign']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit($fali);
}
$conn = SetConn(88);
$orderId = $data['cp_order_id'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit($success);
}
$payMoney = intval($data['total_price']);
if(!$result){
	$snum = giQSModHash($accountId);
	$conn = SetConn($gameId,$snum,1);//account分表
	$acctable = betaSubTableNew($accountId,'account',999);
	$sql_account = "select NAME,dwFenBaoID,clienttype from $acctable where id=$accountId limit 1;";
	$query_account = mysqli_query($conn, $sql_account);
	$result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","meizu_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit($fali);
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $loginname = 'meizu';
    if(isOwnWay($PayName,$loginname)){
    	write_log(ROOT_PATH."log","name_{$loginname}_", "account is $PayName ! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    	exit($success);
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PlayerID,data,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode,packageName)";
    $sql=$sql." VALUES (178, $accountId,'$playerId','$payMoney','$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1','$isgoods')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","meizu_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit($fali);
    }
    WriteCard_money(1,$serverId, $payMoney,$playerId, $orderId);
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId);
    exit($success);
}
exit($success);