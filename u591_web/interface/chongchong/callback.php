<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","chongchong_callback_log_", "post=$post,get=$get, "." ".date("Y-m-d H:i:s")."\r\n");
$sign = $_REQUEST['sign'];
$array = array();
$array['transactionNo'] = $_REQUEST['transactionNo'];
$array['partnerTransactionNo'] = $_REQUEST['partnerTransactionNo'];
$array['statusCode'] = $_REQUEST['statusCode'];
if($_REQUEST['productId'])
    $array['productId'] = $_REQUEST['productId'];
$array['orderPrice'] = $_REQUEST['orderPrice'];
$array['packageId'] = $_REQUEST['packageId'];

//sdk 1.6新增字段
if($_REQUEST['productName'])
    $array['productName'] = urldecode($_REQUEST['productName']);
if($_REQUEST['extParam'])
    $array['extParam'] = $_REQUEST['extParam'];
if($_REQUEST['userId'])
    $array['userId'] = $_REQUEST['userId'];

$partnerTransactionNoArr = explode('_', $array['partnerTransactionNo']);
$gameId = $partnerTransactionNoArr[0];
$serverId = $partnerTransactionNoArr[1];
$accountId = $partnerTransactionNoArr[2];
$isgoods = $partnerTransactionNoArr[4];

global $key_arr;
$appSecret = $key_arr[$gameId]['appSecret'];
$mySignStr = httpBuidQuery($array, $appSecret);
$mySign = md5($mySignStr);

if($mySign != $sign){
	write_log(ROOT_PATH."log","chongchong_callback_error_","sign error, mySignStr=$mySignStr, mySign=$mySign, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
//获取账号信息
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
$query_account = mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","chongchong_callback_error_log_", "account not exist. post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit("-2");//账号不存在
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}

$order_id = $array['partnerTransactionNo'];
$PayMoney = intval($array['orderPrice']);

$conn = SetConn(88);
//判断订单id情况
$sql = "select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query = mysqli_query($conn, $sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","chongchong_callback_error_log_", "order is exist. post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit($order_id);//订单已存在
}
$conn = SetConn(88);
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype)";
$sql=$sql." VALUES (75,$accountId,'$PayName','$serverId','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","chongchong_callback_error_log_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit("-3");
}
if($array['statusCode'] == '0000'){
    $rpCode =1;
    $isPay = 0;
    PayLog_chongchong($order_id,$rpCode,$PayMoney);//更新充值记录
    WriteCard_money(1,$serverId, $PayMoney,$accountId, $order_id,8,0,0,$isgoods);
}else{
	$isPay = 1;
    $rpCode =2;
    PayLog_chongchong($order_id,$rpCode,$PayMoney);//更新充值记录
    WritePayMsg(1,$serverId,$accountId,$order_id,$PayMoney,$gameId);
}
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId,$isPay);
exit('success');
function PayLog_chongchong($OrderID,$rpCode,$PayMoney){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime' ";
    $sql=$sql." where OrderID='$OrderID'";
    if (mysqli_query($conn,$sql) == False){
        //写入失败日志
        write_log(ROOT_PATH."log","chongchong_callback_error_log_", $sql."  " .mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit;
    }
}
?>