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
write_log(ROOT_PATH."log","mofang_callback_log_", "post=$post, get=$get,"." ".date("Y-m-d H:i:s")."\r\n");
//$str = 'a:10:{s:3:"gid";s:6:"100059";s:7:"ordersn";s:24:"GM20170113175236186378V2";s:3:"sid";s:4:"3001";s:3:"uid";s:8:"43003893";s:8:"itemcode";s:21:"pokemon_gold_17funs02";s:7:"gmmoney";s:2:"30";s:4:"gold";s:2:"60";s:4:"time";s:10:"1484301160";s:6:"exinfo";s:24:"8_3001_1652167_android_0";s:4:"sign";s:32:"AF1BC590EBA224801AC44A7E8E450D8D";}';
//$_REQUEST = unserialize($str);

$gid = $_REQUEST['gid'];
$ordersn = $_REQUEST['ordersn'];
$sid = $_REQUEST['sid'];
$uid = $_REQUEST['uid'];
$itemcode = $_REQUEST['itemcode'];
$gmmoney = $_REQUEST['gmmoney']; //TWD
$gold = $_REQUEST['gold'];
$time = $_REQUEST['time'];
$exInfo = $_REQUEST['exinfo'];
$sign = $_REQUEST['sign'];

$exInfoArr = explode('_', $exInfo);
if(!isset($exInfoArr[0]) || !isset($exInfoArr[1]) || !isset($exInfoArr[2]) || !isset($exInfoArr[3]))
    exit(json_encode(array('code'=>'99', 'msg'=>'exinfo format error.', 'data'=>$ordersn)));
$gameId = $exInfoArr[0];
$serverId = $exInfoArr[1];
$accountId = $exInfoArr[2];
$type = $exInfoArr[3];
global $key_arr;
$payKey = $key_arr[$gameId][$type]['paykey'];

$md5Str = "gid=$gid&ordersn=$ordersn&sid=$sid&uid=$uid&itemcode=$itemcode&gmmoney=$gmmoney&gold=$gold&time=$time&exinfo=$exInfo&key=$payKey";
$mySign = strtoupper(md5($md5Str));
if($mySign != $sign){
    write_log(ROOT_PATH."log","mofang_callback_error_","sign error,sign=$sign, mySign=$mySign,md5Str=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'99', 'msg'=>'sign error.','data'=>$ordersn)));
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$accountId' limit 1;";
$query_account = mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);


if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","mofang_callback_error_", "account is not exist! post=$post,get=$get, data=$data, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'1', 'msg'=>'account is not exist.','data'=>$ordersn)));
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}
$order_id = $ordersn;
$PayMoney = $gmmoney;
$currency = 'TWD'; //TWD USD
$yuanbao = $gold;

$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);

if($result_count['id']){
    write_log(ROOT_PATH."log","mofang_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'0', 'msg'=>'Success','data'=>$ordersn)));
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (139,'$currency',$accountId,'$PayName','$serverId','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype','1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","mofang_callback_error_", "sql=$sql, post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>'99', 'msg'=>'insert mysql error.', 'data'=>$ordersn)));
} else {
    WriteCard_money(1,$serverId, $yuanbao,$accountId, $order_id);
    //统计数据
    if($yuanbao == 180)
        $yuanbao = 300;
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$yuanbao,$order_id,1,$tjAppId);
    exit(json_encode(array('code'=>'0', 'msg'=>'Success','data'=>$ordersn)));
}