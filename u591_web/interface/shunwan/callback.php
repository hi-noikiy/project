<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/18
 * Time: 下午5:25
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","shunwan_callback_log_", "post=$post, get=$get,"." ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:10:{s:2:"id";s:5:"11298";s:8:"order_no";s:32:"ba79cee27b071298098e615b0d1afaad";s:8:"trade_no";s:38:"8_8052_2245425_android_0_1484733225469";s:3:"uid";s:4:"9925";s:12:"order_status";s:1:"1";s:9:"order_fee";s:3:"100";s:5:"goods";s:8:"60钻石";s:8:"ext_info";s:29:"获得60钻石,另送60钻石";s:9:"send_time";s:10:"1484733593";s:4:"sign";s:32:"2926981232b8e245196843224b5b6e68";}';
//$_REQUEST = unserialize($str);
$cpOrderId = $_REQUEST['trade_no'];
$cpOrderIdArr = explode('_', $cpOrderId);
if(!isset($cpOrderIdArr[0]) || !isset($cpOrderIdArr[1]) || !isset($cpOrderIdArr[2]) || !isset($cpOrderIdArr[3]))
    exit('fail');
$gameId = $cpOrderIdArr[0];
$serverId = $cpOrderIdArr[1];
$accountId = $cpOrderIdArr[2];
$type = $cpOrderIdArr[3];
$isgoods = intval($cpOrderIdArr[4]);
global $key_arr;
$secKey = $key_arr[$gameId][$type]['secKey'];
$array = array();
foreach ($_REQUEST as $k=>$v){
    if($k != 'sign' )
        $array[$k] = urldecode($v);
}
$sign = $_REQUEST['sign'];
ksort($array);
$md5Str = '';
foreach ($array as $k => $v){
    $md5Str .= $k.'='.$v.'&';
}
$md5Str .= 'signKey='.$secKey;
if(md5($md5Str) !=$sign){
    write_log(ROOT_PATH."log","shunwan_callback_error_","sign error,sign=$sign,md5Str=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","shunwan_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}
$order_id = $array['order_no'];
$PayMoney = intval($array['order_fee']/100);

$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1";
$query=@mysqli_query($conn,$sql);
$result_count=@mysqli_fetch_assoc($query);

if($result_count['id']){
    write_log(ROOT_PATH."log","shunwan_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('SUCCESS');
}
$Add_Time=date('Y-m-d H:i:s');
$rpCode = ($array['order_status'] == 1) ? '1' : '2';
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (140,$accountId,'$PayName','$serverId','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype','$rpCode')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","shunwan_callback_error_", "sql=$sql, post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
} else {
    $isPay = 1;
    if($array['order_status'] == 1){
        $isPay = 0;
        WriteCard_money(1,$serverId, $PayMoney,$accountId, $order_id,8,0,0,$isgoods);
    }
    //统计
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId,$isPay);
    exit('SUCCESS');
}