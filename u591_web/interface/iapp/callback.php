<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/9
 * Time: 上午9:52
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","iapp_callback_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$n_time = $_REQUEST['n_time'];
$appid = $_REQUEST['appid'];
$o_id = $_REQUEST['o_id'];
$t_fee = $_REQUEST['t_fee'];
$g_name = urldecode($_REQUEST['g_name']);
$g_body = urldecode($_REQUEST['g_body']);
$t_status = $_REQUEST['t_status'];
$o_sign = $_REQUEST['o_sign'];
$u_param = $_REQUEST['u_param'];

$uParamArr = explode('_', $u_param);
if(!isset($uParamArr[0]) || !isset($uParamArr[1]) || !isset($uParamArr[2]) || !isset($uParamArr[3])){
    write_log(ROOT_PATH."log","iapp_callback_error_","prams error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}

$gameId = $uParamArr[0];
$serverId = $uParamArr[1];
$accountId = $uParamArr[2];
$type = $uParamArr[3];
$isgoods = $uParamArr[4];
global $key_arr;
$appKey = $key_arr[$gameId][$type]['appKey'];

$signStr = "n_time=".urlencode($n_time)."&appid=$appid&o_id=$o_id&t_fee=$t_fee&g_name=".urlencode($g_name)."&g_body=".urlencode($g_body)."&t_status=$t_status".$appKey;
if($o_sign != md5($signStr)){
    write_log(ROOT_PATH."log","iapp_callback_error_","sign error,signStr=$signStr, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","iapp_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","iapp_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clientType = $result_account['clienttype'];
}
$conn = SetConn(88);
//判断订单id情况
$payMoney = $t_fee;
$orderId = $o_id;
$sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","iapp_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('success');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (133,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '2')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","iapp_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$isPay = 1;
if($t_status == '1'){
    $isPay = 0;
    ChangPayLog($orderId, 1, $payMoney);
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
}
//统计数据
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
        write_log(ROOT_PATH."log","iapp_callback_error_", "sql=$sql".date("Y-m-d H:i:s")."\r\n");
    }
}