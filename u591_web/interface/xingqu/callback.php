<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/15
 * Time: 上午11:14
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xingqu_callback_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$appId = $_REQUEST['app_id'];
$cchId = $_REQUEST['cch_id'];
$rOrderNo = $_REQUEST['r_order_no']; //星趣订单号
$appOrderNo = $_REQUEST['app_order_no'];
$openid = $_REQUEST['openid'];
$money = $_REQUEST['money'];
$tm = $_REQUEST['tm'];
$signType = $_REQUEST['sign_type'];
$sign = $_REQUEST['sign'];
$paidState = $_REQUEST['paid_state'];
$appExt = $_REQUEST['app_ext'];
$sid = $_REQUEST['sid'];
$sname = $_REQUEST['sname'];
$roleId = $_REQUEST['role_id'];
$roleName = $_REQUEST['role_name'];

$appExtArr = explode('_', $appExt);
$gameId = $appExtArr[0];
$serverId = $appExtArr[1];
$accountId = $appExtArr[2];
$type = $appExtArr[3];
global $key_arr;
$payKey = $key_arr[$gameId]['paykey'];
$mySign = xingquSign($_REQUEST, $payKey);
if($sign != $mySign){
    write_log(ROOT_PATH."log","xingqu_callback_error_","sign=$sign, mySign=$sign_check, signStr=$signStr, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
$query_account= @mysqli_query($conn, $sql_account);
$result_account= @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","xingqu_callback_error_", "account error! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}

$orderId = $appOrderNo;
$PayMoney = intval($money);
$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","xingqu_callback_error_", "order exist!  post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('success');
}
if($paidState == 1){
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
    $sql=$sql." VALUES (143,$accountId,'$PayName','$serverId','$PayMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',1)";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","xingqu_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit('fail');
    }
    WriteCard_money(1,$serverId, $PayMoney,$accountId,$orderId);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$PayMoney,$orderId,1,$tjAppId);
}
exit('success');