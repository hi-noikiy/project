<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/7
 * Time: 下午2:06
 */
include 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","longxia_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$outTradeNo = $_REQUEST['out_trade_no'];
$price = $_REQUEST['price'];
$payStatus = $_REQUEST['pay_status'];
$extend = $_REQUEST['extend'];
$signType = $_REQUEST['signType'];
$sign = $_REQUEST['sign'];

$extendArr = explode("_", $extend);
if(!isset($extendArr[0]) || !isset($extendArr[1]) || !isset($extendArr[2])){
    write_log(ROOT_PATH."log","longxia_callback_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$gameId = $extendArr[0];
$serverId = $extendArr[1];
$accountId = $extendArr[2];
global $key_arr;
$appKey = $key_arr[$gameId]['appKey'];

$mySign = md5($outTradeNo.$price.$payStatus.$extend.$appKey);
if($mySign !=$sign){
    write_log(ROOT_PATH."log","longxia_callback_error_","sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","longxia_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","longxia_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clientType = $result_account['clienttype'];
}
$conn = SetConn(88);
//判断订单id情况
$orderId = $outTradeNo;
$payMoney = $price;
$sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","longxia_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('success');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (149,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '2')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","longxia_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
if($payStatus == 1){
    ChangPayLog($orderId, 1, $payMoney);
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
}
$isPay = ($payStatus == 1) ? 0 : 1;
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId,$isPay);
exit('success');

function ChangPayLog($OrderID,$rpCode,$PayMoney){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime'";
    $sql=$sql." where OrderID='$OrderID'";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","longxia_callback_error_", "sql=$sql".date("Y-m-d H:i:s")."\r\n");
    }
}