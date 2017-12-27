<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 同步推支付回调
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log(ROOT_PATH."log","tongbu_callback_all_"," post=$post,get=$get,ip=$ip ".date("Y-m-d H:i:s")."\r\n");

$source = $_GET['source'];
$trade_no = $_GET['trade_no'];
$amount = $_GET['amount'];
$partner = $_GET['partner'];
$paydes = $_GET['paydes'];
$debug = $_GET['debug'];
$tborder = $_GET['tborder'];
$sign = $_GET['sign'];

$trade_no_arr = explode("_", $trade_no);

$game_id = $trade_no_arr[0];
$server_id = $trade_no_arr[1];
$account_id = $trade_no_arr[2];
$isgoods = intval($trade_no_arr[4]);

$app_id = $arr_key[$game_id]['app_id'];
$app_key = $arr_key[$game_id]['app_key'];

$my_sign_base = "source=tongbu&trade_no=$trade_no&amount=$amount&partner=$partner&paydes=$paydes&debug=$debug&tborder=$tborder&key=$app_key";
$my_sign = md5($my_sign_base);

if($my_sign != $sign){
    write_log(ROOT_PATH."log","tongbu_callback_error_","sign error, my_sign_base=$my_sign_base, my_sign=$my_sign, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('{"status":"fail"}');
}
global $accountServer;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql_account = " select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
$query_account = @mysqli_query($conn,$sql_account);
$result_account = @mysqli_fetch_assoc($query_account);
if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","tongbu_callback_error_", "account is not exist! post=$post,get=$get,ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
    exit('{"status":"fail"}');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}
$order_id = $trade_no;
$PayMoney = intval($amount/100);
$conn = SetConn(88);
//判断订单id情况
$sql = "select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","tongbu_callback_error_", "order is exist! post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
    exit('{"status":"success"}');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (108,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype','1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","tongbu_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('{"status":"fail"}');
} else {
	WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
	//统计数据
    global $tongjiServer;
	$tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
	exit('{"status":"success"}');
}
?>