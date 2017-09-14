<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","muzhiwan_callback_info_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$appKey = $_REQUEST['appkey'];
$orderID = $_REQUEST['orderID'];
$productName = $_REQUEST['productName'];
$productDesc = $_REQUEST['productDesc'];
$productID = $_REQUEST['productID'];
$money = $_REQUEST['money'];
$appUid = $_REQUEST['uid'];
$extern = $_REQUEST['extern'];
$sign = $_REQUEST['sign'];
$externArr = explode('#', $extern);
$gameId = $externArr[0];
$serverId = $externArr[1];
$accountId = $externArr[2];

global $key_arr;
$verify = $key_arr[$gameId]['verify'];
$mySign = md5($appKey.$orderID.$productName.$productDesc.$productID.$money.$appUid.$extern.$verify);

if($sign != $mySign){
    write_log(ROOT_PATH."log","muzhiwan_callback_error_","sign=$sign, mySign=$mySign, ".date("Y-m-d H:i:s")."\r\n");
    exit('failure');
}
$conn = SetConn(88);
$orderId = $orderID;
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit("SUCCESS");
}
$payMoney = intval($money);
if(!$result){
    global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
    $query_account = @mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","muzhiwan_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("failure");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (123, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","muzhiwan_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('failure');
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("SUCCESS");
}
exit("failure");
?>