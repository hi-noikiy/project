<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/19
 * Time: 上午10:02
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","dianyou_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:12:{s:4:"sign";s:64:"3838326262646534623663313633336236633534356238363639663237613337";s:3:"uid";s:5:"18451";s:3:"fee";s:3:"100";s:3:"app";s:4:"A001";s:2:"pt";s:13:"1482130546000";s:4:"ssid";s:28:"8_8037_1341326_1482130534765";s:3:"ver";s:1:"1";s:2:"st";s:1:"1";s:3:"sdk";s:4:"0001";s:3:"tcd";s:27:"IWD184510000000390690668599";s:3:"cbi";s:14:"8_8037_1341326";s:2:"ct";s:13:"1482130572591";}';
//$_REQUEST = unserialize($str);
$arr['app'] = $_REQUEST["app"];
$arr['cbi'] = $_REQUEST["cbi"];
$arr['ct'] = $_REQUEST['ct'];
$arr['fee'] = $_REQUEST['fee'];  //金额分
$arr['pt'] = $_REQUEST['pt'];
$arr['sdk'] = $_REQUEST['sdk'];
$arr['ssid'] = $_REQUEST["ssid"];  //cpOrderId
$arr['st'] = $_REQUEST['st'];
$arr['tcd'] = $_REQUEST['tcd'];  //orderId
$arr['uid'] = $_REQUEST['uid'];
$arr['ver'] = $_REQUEST['ver'];
$sign = $_REQUEST['sign'];

$cpOrderIdArr = explode("_", $arr['ssid']);
if(!isset($cpOrderIdArr[0]) || !isset($cpOrderIdArr[1]) || !isset($cpOrderIdArr[2])){
    write_log(ROOT_PATH."log","dianyou_callback_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
$gameId = $cpOrderIdArr[0];
$serverId = $cpOrderIdArr[1];
$accountId = $cpOrderIdArr[2];
global $key_arr;
$appSecret = $key_arr[$gameId]['publicKey'];
ksort($arr);
$urlstr = http_build_query($arr);
$mySign = bin2hex(md5($urlstr.$appSecret));
if($sign != $mySign) {
    write_log(ROOT_PATH."log","dianyou_callback_error_","sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","dianyou_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","dianyou_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clientType = $result_account['clienttype'];
}
$conn = SetConn(88);
//判断订单id情况
$orderId = $arr['tcd'];
$payMoney = intval($arr['fee']/100);
$sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","dianyou_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('SUCCESS');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (136,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","dianyou_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
exit('SUCCESS');