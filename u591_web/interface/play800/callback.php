<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/9
 * Time: 上午11:24
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","play800_callback_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

//$str = 'a:13:{s:4:"site";s:8:"kdyg_ios";s:8:"order_id";s:31:"kdy1481286006_582_30309458_2553";s:3:"uid";s:8:"30309458";s:3:"sid";s:4:"8030";s:11:"cp_order_id";s:34:"8_8030_907529_ios_502978806.627500";s:6:"roleid";s:7:"1597717";s:8:"rolename";s:15:"谦逊の姬子";s:11:"order_money";s:4:"6.00";s:9:"productid";s:6:"kdyg_1";s:8:"pay_type";s:1:"1";s:3:"ext";s:0:"";s:4:"time";s:10:"1481287681";s:4:"sign";s:32:"0a98dcd1d862d90cd7eca79b1565d251";}';
//$_REQUEST = unserialize($str);
$site = $_REQUEST['site'];
$sid = $_REQUEST['sid'];
$appUid = $_REQUEST['uid'];
$orderId = $_REQUEST['order_id'];
$cpOrderId = $_REQUEST['cp_order_id'];
$roleId = $_REQUEST['roleid'];
$roleName = $_REQUEST['rolename'];
$orderMoney = $_REQUEST['order_money'];
$productId = $_REQUEST['productid'];
$payType = $_REQUEST['pay_type'];
$ext = $_REQUEST['ext'];
$extArr = explode('_', $cpOrderId);
$sign = $_REQUEST['sign'];
$time = $_REQUEST['time'];

if(!isset($extArr[0]) || !isset($extArr[1]) || !isset($extArr[2]) || !isset($extArr[3])){
    write_log(ROOT_PATH."log","play800_callback_error_","prams error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$gameId = $extArr[0];
$serverId = $extArr[1];
$accountId = $extArr[2];
$type = $extArr[3];
$isgoods = $extArr[4];
global $key_arr;
$key = $key_arr[$gameId][$type]['key'];

$signStr = $site.$time.$key.$appUid.$orderMoney.$cpOrderId;
if($sign != md5($signStr)){
    write_log(ROOT_PATH."log","play800_callback_error_","$type sign error,signStr=$signStr, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","play800_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","play800_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clientType = $result_account['clienttype'];
}
$conn = SetConn(88);
//判断订单id情况
$payMoney = $orderMoney;
$sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","play800_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('success');
}
$Add_Time=date('Y-m-d H:i:s');
//payType为1的时候苹果支付 其他线下支付
$CPID = $payType == 1 ? '134' : '153';
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES ('$CPID',$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","play800_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
} else {
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit('success');
}