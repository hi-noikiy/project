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
write_log(ROOT_PATH."log","aochuang_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:11:{s:6:"amount";s:4:"1.00";s:5:"appid";s:3:"297";s:6:"charid";s:7:"1871652";s:9:"cporderid";s:28:"8_8041_1343675_1482137440268";s:7:"extinfo";s:14:"8_8041_1343675";s:4:"gold";s:2:"10";s:7:"orderid";s:18:"201612191650503590";s:8:"serverid";s:4:"8041";s:4:"time";s:10:"1482137522";s:3:"uid";s:4:"4328";s:4:"sign";s:32:"0154a5351bbc885ef059c6a16baaa344";}';
//$_GET = unserialize($str);

$arr['cporderid'] = $_GET["cporderid"];
$arr['orderid'] = $_GET["orderid"];
$arr['uid'] = $_GET['uid'];
$arr['charid'] = $_GET['charid'];
$arr['time']=$_GET['time'];
$arr['extinfo']= urlencode($_GET['extinfo']);
$arr['amount'] = $_GET["amount"];
$arr['serverid']= urlencode($_GET['serverid']);
$arr['charid']= urlencode($_GET['charid']);
$arr['gold']=$_GET['gold'];

$extinfoArr = explode("_", $arr['extinfo']);
if(!isset($extinfoArr[0]) || !isset($extinfoArr[1]) || !isset($extinfoArr[2])){
    write_log(ROOT_PATH."log","aochuang_callback_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
$gameId = $extinfoArr[0];
$serverId = $extinfoArr[1];
$accountId = $extinfoArr[2];
global $key_arr;
$appId = $key_arr[$gameId]['appId'];
$payKey = $key_arr[$gameId]['payKey'];

$arr['appid']=$appId;
ksort($arr);
$urlstr = http_build_query($arr);
$mySign = md5($urlstr.$payKey);
if($_GET["sign"] != $mySign) {
    write_log(ROOT_PATH."log","aochuang_callback_error_","sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","aochuang_callback_error_","post=$post,get=$get,msg=account mysql error, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","aochuang_callback_error_", "account is not exist! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clientType = $result_account['clienttype'];
}
$conn = SetConn(88);
//判断订单id情况
$orderId = $arr['orderid'];
$payMoney = $arr['amount'];
$sql = "select id,rpCode from web_pay_log where OrderID='$orderId' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","aochuang_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('SUCCESS');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (135,$accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clientType', '1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","aochuang_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$gameId];
sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
exit('SUCCESS');