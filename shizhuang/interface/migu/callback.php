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
write_log(ROOT_PATH."log","migu_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!in_array($_POST['order_status'], ['TRADE_FINISHED','TRADE_SUCCESS'])){ //支付失败
	write_log(ROOT_PATH."log","migu_callback_error_","pay error，{$_POST['tradeStatus']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
$sign = $_REQUEST["sign"];

$extendsInfo = $_REQUEST['cp_order_id ']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$playerId = $extendsInfoArr[2];
$accountId = $extendsInfoArr[3];
$type = $extendsInfoArr[4];
global $key_arr;
$signKey = $key_arr[$gameId][$type]['appKey'];
$data = $_REQUEST;
unset($data['sign']);
ksort($data);
$data['sign'] = hmacSha1Sign($data,$signKey);
if($sign != $data['sign']) {
    write_log(ROOT_PATH."log","migu_callback_error_","sign error,{$data['sign']},{$str},{$key}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAILURE');
}
$conn = SetConn(88);
$orderId = $extendsInfo;
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' and game_id='$gameId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit("success");
}
$payMoney = intval($data['pay_fee']/100);
if(!$result){
	$snum = giQSModHash($accountId);
	$conn = SetConn($gameId,$snum,1);//account分表
	$acctable = betaSubTableNew($accountId,'account',999);
	$sql_account = "select NAME,dwFenBaoID,clienttype from $acctable where id=$accountId limit 1;";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","migu_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("FAILURE");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $loginname = 'migu';
    if(isOwnWay($PayName,$loginname)){
    	write_log(ROOT_PATH."log","name_{$loginname}_", "account is $PayName ! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    	exit("success");
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PlayerID,data,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (190, $accountId,'$playerId','$payMoney','$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","migu_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('FAILURE');
    }
    WriteCard_money(1,$serverId, $payMoney,$playerId, $orderId);
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId);
    exit("success");
}
exit("success");