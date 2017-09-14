<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/3/31 下午7:18
 * @desc:逗游支付回调
 * @param:
 * @return:
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","douyou_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$orderid = $_REQUEST['orderid'];
$username = $_REQUEST['username'];
$gameid = $_REQUEST['gameid'];
$roleid = $_REQUEST['roleid'];
$serverid = $_REQUEST['serverid'];
$paytype = $_REQUEST['paytype'];
$amount = $_REQUEST['amount'];
$paytime = $_REQUEST['paytime'];
$attach = $_REQUEST['attach'];
//$appkey = $_REQUEST['appkey'];
$sign = $_REQUEST['sign'];


$attachArr = explode('_', $attach);
$gameId = isset($attachArr[0]) ? $attachArr[0] : '';
$serverId = isset($attachArr[1]) ? $attachArr[1] : '';
$accountId = isset($attachArr[2]) ? $attachArr[2] : '';
if(!$gameid || !$serverId || !$accountId)
    exit('error');
//$type = $extendArr[3];
global $key_arr;
$appKey = $key_arr[$gameId]['appkey'];

$md5Str = "orderid=$orderid&username=$username&gameid=$gameid&roleid=".urlencode($roleid)."&serverid=$serverid";
$md5Str .="&paytype=$paytype&amount=$amount&paytime=$paytime&attach=".urlencode($attach)."&appkey=$appKey";
$mySign = md5($md5Str);
if($mySign != $sign){
    write_log(ROOT_PATH."log","douyou_callback_error_","sign=$sign, mySign=$mySign, signStr=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit('errorSign');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","douyou_callback_error_","account mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
$query_account= @mysqli_query($conn, $sql_account);
$result_account= @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","douyou_callback_error_", "account error! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
$PayName = $result_account['NAME'];
$dwFenBaoID = $result_account['dwFenBaoID'];
$clienttype = $result_account['clienttype'];

$conn = SetConn(88);
if($conn == false){
    write_log(ROOT_PATH."log","douyou_callback_error_","web mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$orderid' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","douyou_callback_error_", "order exist!  post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('success');
}
$Add_Time= date('Y-m-d H:i:s', $paytime);
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (152,$accountId,'$PayName','$serverId','$amount','$orderid','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',1)";
if (mysqli_query($conn,$sql) == false){
    write_log(ROOT_PATH."log","douyou_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('error');
}
WriteCard_money(1,$serverId,$amount,$accountId,$orderid);
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$amount,$orderid,1,$tjAppId);
exit('success');