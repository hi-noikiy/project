<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/4
 * Time: 下午2:01
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","gangtai_callback_log_", "post=$post, get=$get,"." ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:10:{s:13:"transactionID";s:15:"PZ1483588102868";s:5:"appId";s:5:"10086";s:6:"userId";s:8:"25085559";s:6:"roleId";s:7:"2244379";s:11:"gameOrderId";s:38:"8_8048_1846943_android_0_1483588053996";s:10:"gameZoneId";s:4:"8048";s:4:"type";s:1:"0";s:11:"productName";s:16:"product_mycard50";s:4:"sign";s:32:"5490a530b95d85d62220d6f4cd33b9f2";s:6:"exInfo";s:78:"{"amount":50.00,"currency":"TWD","gameCoin":100,"gameCoinCurrency":"Diamonds"}";}';
//$_REQUEST = unserialize($str);
$transactionID = $_REQUEST['transactionID'];
$appId = $_REQUEST['appId'];
$userId = $_REQUEST['userId'];
$roleId = $_REQUEST['roleId'];
$gameOrderId = $_REQUEST['gameOrderId'];
$gameZoneId = $_REQUEST['gameZoneId'];
$type = $_REQUEST['type'];
$productName = $_REQUEST['productName'];
$exInfo = $_REQUEST['exInfo'];
$sign = $_REQUEST['sign'];

$gameOrderIdArr = explode('_', $gameOrderId);
if(!isset($gameOrderIdArr[0]) || !isset($gameOrderIdArr[1]) || !isset($gameOrderIdArr[2]) || !isset($gameOrderIdArr[3]))
    exit(json_encode(array('code'=>'0', 'error_msg'=>'gameorderid format error.')));
$gameId = $gameOrderIdArr[0];
$serverId = $gameOrderIdArr[1];
$accountId = $gameOrderIdArr[2];
$type = $gameOrderIdArr[3];
global $key_arr;
$appSecret = $key_arr[$gameId][$type]['appsecret'];

$mySign = md5(strtolower($transactionID.$appId.$userId.$gameZoneId.$productName.$appSecret));
if($mySign != $sign){
    write_log(ROOT_PATH."log","gangtai_callback_error_","sign error,sign=$sign, mySign=$mySign, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'0', 'error_msg'=>'sign error.')));
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","gangtai_callback_error_", "account is not exist! post=$post,get=$get, data=$data, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'0', 'error_msg'=>'account is not exist.')));
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}
$order_id = $transactionID;
$exInfoArr = json_decode($exInfo, true);
$PayMoney = $exInfoArr['amount'];
$currency = $exInfoArr['currency']; //TWD USD
$yuanbao = $exInfoArr['gameCoin'];

$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query=mysqli_query($conn,$sql);
$result_count=mysqli_fetch_assoc($query);

if($result_count['id']){
    write_log(ROOT_PATH."log","gangtai_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'200', 'error_msg'=>'orderid is exist.')));
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (137,'$currency',$accountId,'$PayName','$serverId','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype','1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","gangtai_callback_error_", "sql=$sql, post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'0', 'error_msg'=>'insert mysql error.')));
} else {
    WriteCard_money(1,$serverId, $yuanbao,$accountId, $order_id);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$yuanbao,$order_id,1,$tjAppId);
    exit(json_encode(array('code'=>'200', 'error_msg'=>'success.')));
}