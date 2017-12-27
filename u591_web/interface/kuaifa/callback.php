<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/5
 * Time: 下午3:00
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","kuaifa_callback_log_", "post=$post, get=$get,"." ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:11:{s:13:"serial_number";s:16:"1701050400054566";s:6:"amount";s:4:"6.00";s:9:"timestamp";s:10:"1483604034";s:6:"extend";s:24:"8_8050_1850747_android_0";s:6:"server";s:0:"";s:2:"cp";s:4:"test";s:10:"product_id";s:1:"2";s:11:"product_num";s:1:"1";s:12:"game_orderno";s:37:"8_8050_1850747_android_01483604037591";s:6:"result";s:1:"0";s:4:"sign";s:32:"bfdc8edcd1b449274f72785673b93720";}';
//$_POST = unserialize($str);
$params = $_POST;
$sign  = $_POST['sign'];
unset($params['sign']);
ksort($params);//对key进行排序后再获取

$data = array();
foreach($params as $key => $val) {
    $data[] = $key.'='.urlencode($val);
}
$extend = $_POST['extend'];
$extendArr = explode('_', $extend);
if(!isset($extendArr[0]) || !isset($extendArr[1]) || !isset($extendArr[2]) || !isset($extendArr[3]))
    exit(json_encode(array('result'=>'1', 'result_desc'=>'param error.')));
$gameId = $extendArr[0];
$serverId = $extendArr[1];
$accountId = $extendArr[2];
$type = $extendArr[3];
$isgoods = intval($extendArr[4]);
global $key_arr;
$secretKey = $key_arr[$gameId][$type]['securutykey'];
$signStr = implode('&',$data);
$mySign = md5(md5($signStr).$secretKey);//这里就是签名
if($mySign != $sign){
    write_log(ROOT_PATH."log","kuaifa_callback_error_","sign error,sign=$sign, mySign=$mySign,signStr=$signStr, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('result'=>'1', 'result_desc'=>'sign error.')));
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","kuaifa_callback_error_", "account is not exist! post=$post,get=$get, data=$data, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('result'=>'1', 'result_desc'=>'account is not exist.')));
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}
$order_id = $_POST['serial_number'];
$paymoney = intval($_POST['amount']);
$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","kuaifa_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('result'=>'0', 'result_desc'=>'ok')));
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (138,$accountId,'$PayName','$serverId','$paymoney','$order_id','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype','2')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","kuaifa_callback_error_", "sql=$sql, post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('result'=>'1', 'result_desc'=>'insert mysql error')));
} else {
    if($_POST['result'] == 0){
        ChangPayLog($order_id, 1, $paymoney);
        WriteCard_money(1,$serverId, $paymoney,$accountId, $order_id,8,0,0,$isgoods);
        //统计数据
        global $tongjiServer;
        $tjAppId = $tongjiServer[$gameId];
        sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$paymoney,$order_id,1,$tjAppId);
    }
    exit(json_encode(array('result'=>'0', 'result_desc'=>'ok')));
}
function ChangPayLog($OrderID,$rpCode,$PayMoney){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime'";
    $sql=$sql." where OrderID='$OrderID'";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","kuaifa_callback_error_", "sql=$sql".date("Y-m-d H:i:s")."\r\n");
    }
}