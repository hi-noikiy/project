<?php

/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/5/12 上午9:26
 * @desc:
 * @param:
 * @return:
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

//$_REQUEST = 'a:11:{s:6:"amount";s:4:"1.00";s:5:"appid";s:2:"26";s:6:"charid";s:7:"3480045";s:9:"cporderid";s:40:"8_8093_4750617_android_2_1_1494563156000";s:7:"extinfo";s:26:"8_8093_4750617_android_2_1";s:4:"gold";s:1:"0";s:7:"orderid";s:18:"201705121226081390";s:8:"serverid";s:4:"8093";s:4:"time";s:10:"1494563210";s:3:"uid";s:2:"62";s:4:"sign";s:32:"41206f5ede9df080c3b373240563b2d5";}';
//
//$_REQUEST =  unserialize($_REQUEST);

write_log(ROOT_PATH."log","kaijia_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$orderid = $_REQUEST['orderid'];
$sign = $_REQUEST['sign'];
$extinfo = $_REQUEST['extinfo'];
$amount = $_REQUEST['amount'];

$arr['amount'] = $amount;
$arr['appid'] = $_REQUEST['appid'];
$arr['charid'] = urlencode($_REQUEST['charid']);
$arr['cporderid'] = $_REQUEST['cporderid'];
$arr['extinfo'] = urlencode($_REQUEST['extinfo']);
$arr['gold'] = $_REQUEST['gold'];
$arr['orderid'] = $orderid;
$arr['serverid'] = urlencode($_REQUEST['serverid']);
$arr['time'] = $_REQUEST['time'];
$arr['uid'] = $_REQUEST['uid'];

$extinfoArr = explode('_', $extinfo);
$gameId = isset($extinfoArr[0]) ? $extinfoArr[0] : '';
$serverId = isset($extinfoArr[1]) ? $extinfoArr[1] : '';
$accountId = isset($extinfoArr[2]) ? $extinfoArr[2] : '';
$type = isset($extinfoArr[3]) ? $extinfoArr[3] : '';
$isgoods = intval($extinfoArr[4]);
if(!$gameId || !$serverId || !$accountId)
    exit('ERROR');
global $key_arr;
//$appid = isset($key_arr[$gameId][$type]['appid']) ? $key_arr[$gameId][$type]['appid'] : '';
$payKey = isset($key_arr[$gameId][$type]['paykey']) ? $key_arr[$gameId][$type]['paykey'] : '';

ksort($arr);
$signstr = http_build_query($arr);
$mySign = md5($signstr.$payKey);

if($mySign != $sign){
    write_log(ROOT_PATH."log","kaijia_callback_error_","sign=$sign, mySign=$mySign, signStr=$signstr, ".date("Y-m-d H:i:s")."\r\n");
    exit('ERROR'); //sign error
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","kaijia_callback_error_","account mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit('ERROR'); //系统错误
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
$query_account= @mysqli_query($conn, $sql_account);
$result_account= @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","kaijia_callback_error_", "account error! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit('ERROR');  //用户存在
}
$PayName = $result_account['NAME'];
$dwFenBaoID = $result_account['dwFenBaoID'];
$clienttype = $result_account['clienttype'];

$conn = SetConn(88);
if($conn == false){
    write_log(ROOT_PATH."log","kaijia_callback_error_","web mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit('ERROR');
}
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$orderid' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","kaijia_callback_error_", "order exist!  post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('SUCCESS'); //订单重复
}
$Add_Time= date('Y-m-d H:i:s', time());
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (155,$accountId,'$PayName','$serverId','$amount','$orderid','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',1)";
if (mysqli_query($conn,$sql) == false){
    write_log(ROOT_PATH."log","kaijia_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('ERROR');
}
WriteCard_money(1,$serverId,$amount,$accountId,$orderid,8,0,0,$isgoods);
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$amount,$orderid,1,$tjAppId);
exit('SUCCESS');