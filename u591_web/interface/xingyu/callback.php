<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/3/23 下午2:25
 * @desc:支付回调
 * @param:
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xingyu_callback_info_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$outTradeNo = $_REQUEST['out_trade_no'];
$price = $_REQUEST['price'];
$payStatus = $_REQUEST['pay_status'];
$extend = $_REQUEST['extend'];
$signType = $_REQUEST['signType'];
$sign = $_REQUEST['sign'];

$extendArr = explode('_', $extend);
$gameId = $extendArr[0];
$serverId = $extendArr[1];
$accountId = $extendArr[2];
//$type = $extendArr[3];
//MD5(订单号+价格+支付状态+扩展参数+KEY)
global $key_arr;
$appKey = $key_arr[$gameId]['appkey'];
$signStr = $outTradeNo.$price.$payStatus.$extend.$appKey;

$mySign = md5($signStr);
if($sign != $mySign){
    write_log(ROOT_PATH."log","xingyu_callback_error_","sign=$sign, mySign=$sign_check, signStr=$signStr, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}

global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","xingyu_callback_error_","account mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
$query_account= @mysqli_query($conn, $sql_account);
$result_account= @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","xingyu_callback_error_", "account error! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$PayName = $result_account['NAME'];
$dwFenBaoID = $result_account['dwFenBaoID'];
$clienttype = $result_account['clienttype'];

$conn = SetConn(88);
if($conn == false){
    write_log(ROOT_PATH."log","xingyu_callback_error_","web mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$outTradeNo' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","xingyu_callback_error_", "order exist!  post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('success');
}
if($payStatus == 1){
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
    $sql=$sql." VALUES (151,$accountId,'$PayName','$serverId','$price','$outTradeNo','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',1)";
    if (mysqli_query($conn,$sql) == false){
        write_log(ROOT_PATH."log","xingyu_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit('fail');
    }
    WriteCard_money(1,$serverId, $price,$accountId,$outTradeNo);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$price,$outTradeNo,1,$tjAppId);
}
exit('success');