<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/14
 * Time: 下午2:40
 * 牛牛支付回调
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","niuniu_callback_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:12:{s:6:"app_id";s:4:"1038";s:7:"serv_id";s:4:"8001";s:6:"usr_id";s:2:"57";s:9:"player_id";s:7:"1026505";s:12:"app_order_id";s:30:"8_8001_153475_2017021454999952";s:4:"coin";s:2:"60";s:5:"money";s:1:"6";s:8:"add_time";s:10:"1487061670";s:9:"good_code";s:1:"2";s:13:"payExpandData";s:0:"";s:4:"sign";s:32:"7f6a480b553aa3bfacedeab927b1c905";s:8:"order_id";s:26:"VS103820170214164110974662";}';
//$_REQUEST = unserialize($str);
$appId = $_REQUEST['app_id'];
$servId = $_REQUEST['serv_id'];
$usrId = $_REQUEST['usr_id'];
$playerId = $_REQUEST['player_id'];
$appOrderId = $_REQUEST['app_order_id'];
$coin = $_REQUEST['coin'];
$money = $_REQUEST['money'];
$addTime = $_REQUEST['add_time'];
$goodCode = $_REQUEST['good_code'];
$payExpandDate = $_REQUEST['payExpandDate'];
$orderId = $_REQUEST['order_id'];
$sign = $_REQUEST['sign'];
$gameId = 8;

$appKey = $arr_key[$gameId]['app_key'];
//md5(app_id+serv_id+usr_id+player_id+app_order_id+coin+money+add_time+app_key)
$md5Str = $appId.$servId.$usrId.$playerId.$appOrderId.$coin.$money.$addTime.$appKey;
$mySign = md5($md5Str);

if($sign != $mySign){
    write_log(ROOT_PATH."log","niuniu_callback_error_","sign=$sign, str=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('success'=>0,'desc'=>'sign error.')));
}

$conn = SetConn(88);
$sql = "select id,PayID,dwFenBaoID,OrderID,PayMoney,rpCode from web_pay_log where OrderID='$appOrderId' limit 1";
$query = @mysqli_query($conn, $sql);
$orderInfo = @mysqli_fetch_assoc($query);

if(!isset($orderInfo['id'])){
    write_log(ROOT_PATH."log","niuniu_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('success'=>0, 'desc'=>'订单号不存在')));
}
$payMoney = $orderInfo['PayMoney'];
$accountId = $orderInfo['PayID'];
$dwFenBaoID = $orderInfo['dwFenBaoID'];
ChangPayLog($appOrderId, 1);
WriteCard_money(1,$servId, $payMoney,$accountId, $appOrderId);
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$servId,$dwFenBaoID,0,$payMoney,$appOrderId,1,$tjAppId);
exit(json_encode(array('success'=>1, 'desc'=>'充值成功')));

function ChangPayLog($OrderID,$rpCode){
    $conn = SetConn(88);
    $rpTime = date('Y-m-d H:i:s');
    $sql="update web_pay_log set rpCode='$rpCode', rpTime='$rpTime',IsUC='0' where OrderID='$OrderID'";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","niuniu_callback_error_", "sql=$sql".date("Y-m-d H:i:s")."\r\n");
    }
}