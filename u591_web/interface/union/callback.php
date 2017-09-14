<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 爱普支付回调
 * ==============================================
 * @date: 2016-4-27
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$data = file_get_contents("php://input");
$ip = getIP_front();
write_log(ROOT_PATH."log","union_callback_all_"," post=$post,get=$get, data=$data, ip=$ip ".date("Y-m-d H:i:s")."\r\n");

$dataArr = json_decode($data, true);
if($dataArr['state'] != 1)
	exit('success');
$sign = $dataArr['data']['sign'];
$array = array();
$array['productID'] = $dataArr['data']['productID'];
$array['orderID'] = $dataArr['data']['orderID'];
$array['channelID'] = $dataArr['data']['channelID'];
$array['userID'] = $dataArr['data']['userID'];
$array['gameID'] = $dataArr['data']['gameID'];
$array['serverID'] = $dataArr['data']['serverID'];
$array['money'] = $dataArr['data']['money'];
$array['currency'] = $dataArr['data']['currency'];
$array['extension'] = $dataArr['data']['extension'];
//$array['signType'] = $dataArr['data']['signType'];

$extensionArr = explode("_", $array['extension']);
$game_id = $extensionArr[0];
$server_id = $extensionArr[1];
$account_id = $extensionArr[2];

$appSecret = $arr_key[$game_id]['appSecret'];


$mySignStr = "channelID={$array['channelID']}&currency={$array['currency']}&extension={$array['extension']}&gameID={$array['gameID']}";
$mySignStr .="&money={$array['money']}&orderID={$array['orderID']}&productID={$array['productID']}&serverID={$array['serverID']}&userID={$array['userID']}&".$appSecret;
$mySign = md5($mySignStr);

if($mySign != $sign){
    write_log(ROOT_PATH."log","union_callback_error_","sign error, mySignStr=$mySignStr, mySign=$mySign, data=$data, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}
global $accountServer;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$account_id' limit 1;";
$query_account = @mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","union_callback_error_", "account is not exist! post=$post,get=$get, data=$data, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}
$order_id = $array['orderID'];
$PayMoney = intval($array['money']/100);
$conn = SetConn(88);
//判断订单id情况
$sql = "select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","union_callback_error_", "order is exist! post=$post, get=$get, data=$data, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
    exit('SUCCESS');
}
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (120,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype','1')";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","union_callback_error_", $sql.", post=$post, get=$get, data=$data, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit('FAIL');
} else {
	WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id);
	//统计数据
    global $tongjiServer;
	$tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
	exit('SUCCESS');
}
?>