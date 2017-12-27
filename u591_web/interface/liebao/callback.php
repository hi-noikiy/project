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
write_log(ROOT_PATH."log","liebao_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$orderId = $_REQUEST['orderid'];
$username = $_REQUEST['username'];
$appGameId = $_REQUEST['gameid'];
$roleId = $_REQUEST['roleid'];
$appServerId = $_REQUEST['serverid'];
$paytype = $_REQUEST['paytype'];
$amount = $_REQUEST['amount'];
$paytime = $_REQUEST['paytime'];
$attach = $_REQUEST['attach'];
$sign = $_REQUEST['sign'];

$attachArr = explode("_", $attach);
if(!isset($attachArr[0]) || !isset($attachArr[1]) || !isset($attachArr[2])){
    write_log(ROOT_PATH."log","liebao_callback_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
$gameId = $attachArr[0];
$serverId = $attachArr[1];
$accountId = $attachArr[2];
$isgoods = intval($attachArr[4]);

global $key_arr;
$appGameId = $key_arr[$gameId]['gameId'];
$appKey = $key_arr[$gameId]['appKey'];

$md5Str = "orderid=$orderId&username=$username&gameid=$appGameId&roleid=$roleId&serverid=$serverId";
$md5Str .="&paytype=$paytype&amount=$amount&paytime=$paytime&attach=$attach&appkey=$appKey";
$mySign = md5($md5Str);
if($mySign !=$sign){
    write_log(ROOT_PATH."log","liebao_callback_error_","sign error,signStr=$md5Str, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('errorSign');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","liebao_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","liebao_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clientType = $result_account['clienttype'];
}
$conn = SetConn(88);
//判断订单id情况
$payMoney = $amount;
$sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","liebao_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('success');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (132,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","liebao_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
} else {
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
}
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
exit('success');