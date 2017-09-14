<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/4/11 下午1:52
 * @desc: 黑桃支付回调
 * @param:
 * @return:
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","heitao_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$orderid = $_REQUEST['orderid'];
$appOrderid = $_REQUEST['app_orderid'];
$uid = $_REQUEST['uid'];
$amount = $_REQUEST['amount'];
$appExt = $_REQUEST['app_ext'];
$type = $_REQUEST['type'];
$sid = $_REQUEST['sid'];
$msg = $_REQUEST['msg'];
$sign = $_REQUEST['sign'];

$appExtArr = explode('_', $appExt);
$gameId = isset($appExtArr[0]) ? $appExtArr[0] : '';
$serverId = isset($appExtArr[1]) ? $appExtArr[1] : '';
$accountId = isset($appExtArr[2]) ? $appExtArr[2] : '';
if(!$gameId || !$serverId || !$accountId)
    exit('1003'); //其他错误
//$type = $extendArr[3];
global $key_arr;
$secret = $key_arr[$gameId]['secretkey'];

$md5Str = 'orderid='.$orderid.'&app_orderid='.$appOrderid.'&uid='.$uid.'&amount='.$amount.'&app_ext='.$appExt.'&secret='.$secret;

$mySign = md5($md5Str);
if($mySign != $sign){
    write_log(ROOT_PATH."log","heitao_callback_error_","sign=$sign, mySign=$mySign, signStr=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit('1004'); //sign error
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","heitao_callback_error_","account mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit('1000'); //系统错误
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
$query_account= @mysqli_query($conn, $sql_account);
$result_account= @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","heitao_callback_error_", "account error! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit('1001');  //用户存在
}
$PayName = $result_account['NAME'];
$dwFenBaoID = $result_account['dwFenBaoID'];
$clienttype = $result_account['clienttype'];

$conn = SetConn(88);
if($conn == false){
    write_log(ROOT_PATH."log","heitao_callback_error_","web mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit('1000');
}
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$orderid' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","heitao_callback_error_", "order exist!  post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('1002'); //订单重复
}
if($msg == 0){
    $Add_Time= date('Y-m-d H:i:s', time());
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
    $sql=$sql." VALUES (154,$accountId,'$PayName','$serverId','$amount','$orderid','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',1)";
    if (mysqli_query($conn,$sql) == false){
        write_log(ROOT_PATH."log","heitao_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit('1000');
    }
    WriteCard_money(1,$serverId,$amount,$accountId,$orderid);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$amount,$orderid,1,$tjAppId);
}
exit('0');