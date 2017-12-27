<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/8
 * Time: 下午1:36
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","haima_callback_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$notify_time = urldecode($_POST['notify_time']);//通知时间
$appid = urldecode($_POST['appid']);//应用 id
$out_trade_no = urldecode($_POST['out_trade_no']);//开发者的订单号
$total_fee = urldecode($_POST['total_fee']);//该笔订单的总金额
$subject = urldecode($_POST['subject']);//商品名称
$body = urldecode($_POST['body']);//游戏名或商品详情
$trade_status = urldecode($_POST['trade_status']);//交易状态
$sign = $_POST['sign'];//验证签名
$user_param = $_POST['user_param'];//开发者自定义参数

$userParamArr = explode("_", $user_param);
if(!isset($userParamArr[0]) || !isset($userParamArr[1]) || !isset($userParamArr[2])){
    write_log(ROOT_PATH."log","haima_callback_error_","prams error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$gameId = $userParamArr[0];
$serverId = $userParamArr[1];
$accountId = $userParamArr[2];
$type = $userParamArr[3];
$isgoods = $userParamArr[4];
global $key_arr;
$appKey = $key_arr[$gameId][$type]['appKey'];

$signStr = 'notify_time='.urlencode($notify_time).'&appid='.urlencode($appid).'&out_trade_no='.urlencode($out_trade_no).'&total_fee='.urlencode($total_fee).'&subject='.urlencode($subject).'&body='.urlencode($body).'&trade_status='.urlencode($trade_status).$appKey;
$signVerify = md5($signStr);

if($sign != $signVerify){
    write_log(ROOT_PATH."log","haima_callback_error_","sign error,signStr=$signStr, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","haima_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","haima_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clientType = $result_account['clienttype'];
}
$conn = SetConn(88);
//判断订单id情况
$payMoney = $total_fee;
$orderId = $out_trade_no;
$sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","haima_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('success');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (98,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '2')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","haima_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$isPay = 1;
if($trade_status == '1'){
    $isPay = 0;
    ChangPayLog($orderId, 1, $payMoney);
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
}
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
        write_log(ROOT_PATH."log","haima_callback_error_", "sql=$sql".date("Y-m-d H:i:s")."\r\n");
    }
}