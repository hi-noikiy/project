<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/22
 * Time: 下午2:39
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","ailuoke_callback_log_", "post=$post, get=$get,"." ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:14:{s:8:"trade_no";s:23:"G201702101453H1VXMwETmf";s:4:"uuid";s:32:"71c8771fd9324a80b6e7473b7fd7455e";s:5:"extra";s:24:"8_5998_1652141_android_0";s:3:"url";s:62:"http://pokexmalkweb.u591776.com/interface/ailuoke/callback.php";s:12:"product_name";s:12:"0.99_Package";s:6:"app_id";s:6:"scmnzv";s:4:"sign";s:32:"f8bae89329867660627fcf96a15a8c9a";s:10:"product_id";s:20:"yoyo.easy2play.item1";s:9:"total_fee";s:4:"0.99";s:11:"notify_time";s:10:"1486709723";s:12:"trade_status";s:11:"call_server";s:9:"notify_id";s:32:"14f5fb2dd5ec431db58c150ed9fbc539";s:11:"notify_type";s:18:"trade_status_async";s:13:"request_count";s:1:"4";}';
//$_REQUEST = unserialize($str);
$tradeNo = $_REQUEST['trade_no'];
$sign = $_REQUEST['sign'];
$extra = $_REQUEST['extra'];
$extraArr = explode('_', $extra);
if(!isset($extraArr[0]) || !isset($extraArr[1]) || !isset($extraArr[2]) || !isset($extraArr[3]))
    exit('fail');

$gameId = $extraArr[0];
$serverId = $extraArr[1];
$accountId = $extraArr[2];
$type = $extraArr[3];
$lev = isset($extraArr[5]) ? $extraArr[5] : 0;
global $key_arr;
$appkey = $key_arr[$gameId][$type]['appKey'];

if(verify_signature($_REQUEST, $appkey) !== true){
    write_log(ROOT_PATH."log","ailuoke_callback_error_","sign error, post=$post, get=$get ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","ailuoke_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}
$order_id = $tradeNo;
$PayMoney = $_REQUEST['total_fee'];

$productId = $_REQUEST['product_id'];
global $yuanbaoArr;
$currency = $yuanbaoArr[$productId][0];
$yuanbao = $yuanbaoArr[$productId][1];
$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1";
$query=@mysqli_query($conn,$sql);
$result_count=@mysqli_fetch_assoc($query);

if($result_count['id']){
    write_log(ROOT_PATH."log","ailuoke_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('ok');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (141,'$currency',$accountId,'$PayName','$serverId','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype','1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","ailuoke_callback_error_", "sql=$sql, post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
} else {
    WriteCard_money(1,$serverId, $yuanbao,$accountId, $order_id);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,$lev,$PayMoney,$order_id,1,$tjAppId);
    exit('ok');
}