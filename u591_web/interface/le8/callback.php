<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 乐8支付回调
* ==============================================
* @date: 2016-4-27
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log(ROOT_PATH."log","le8_callback_all_"," post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");

$n_time = $_REQUEST['n_time'];
$appid = $_REQUEST['appid'];
$o_id = $_REQUEST['o_id'];
$money = $_REQUEST['t_fee'];
$g_name = $_REQUEST['g_name'];
$g_body = $_REQUEST['g_body'];
$t_status = $_REQUEST['t_status'];
$o_sign = $_REQUEST['o_sign'];

$u_param = $_REQUEST['u_param'];
$o_orderid = $_REQUEST['o_orderid'];

$o_idArr = explode("_", $o_id);
$game_id = $o_idArr[0];
$server_id = $o_idArr[1];
$account_id = $o_idArr[2];
$isgoods = $o_idArr[4];


$appkey = $arr_key[$game_id]['appkey'];
// post=a:10:{s:6:"n_time";s:19:"2016-05-24 17:08:25";s:5:"appid";s:32:"7e1f5aa92a5b2ddc4c4ae17626b68657";s:4:"o_id";s:31:"6_1201_6205530_485773677.884424";s:5:"t_fee";s:4:"0.01";s:6:"g_name";s:8:"60元宝";s:6:"g_body";s:8:"60元宝";s:8:"t_status";s:1:"1";s:9:"o_orderid";s:20:"CZ201605241708057259";s:6:"o_sign";s:32:"ca41cf1d83740eb124f6a353cb8eff15";s:7:"u_param";s:31:"6_1201_6205530_485773677.884424";}, get=a:0:{}, ip=121.40.146.217, 2016-05-24 17:08:12
$mySignStr = "n_time=".urlencode($n_time)."&appid=$appid&o_id=$o_id&t_fee=$money&g_name=".urlencode($g_name)."&g_body=".urlencode($g_body)."&t_status=$t_status".$appkey;
$sign = md5($mySignStr);

if($o_sign != $sign){
	write_log(ROOT_PATH."log","le8_callback_check_","sign error! sign=$o_sign, mySignStr=$mySignStr, ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}

if($t_status == 1){
	//获取账号信息
    global $accountServer;
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1";
	$query_account = mysqli_query($conn,$sql_account);
	$result_account = mysqli_fetch_assoc($query_account);
	if(!$result_account['NAME']){
		write_log(ROOT_PATH."log","le8_callback_error_", "account is not exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit("fail");//账号不存在
	}else{
		$PayName = $result_account['NAME'];
		$dwFenBaoID = $result_account['dwFenBaoID'];
		$clienttype = $result_account['clienttype'];
	}
	$conn = SetConn(88);
	//判断订单id情况
	$sql = "select id,rpCode from web_pay_log where OrderID ='$o_orderid' limit 1";
	$query = mysqli_query($conn,$sql);
	$result_count = mysqli_fetch_assoc($query);
	if($result_count['id']){
		write_log(ROOT_PATH."log","le8_callback_error_", "order is exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit("success");//订单已存在
	}
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
	$sql=$sql." VALUES (127,$account_id,'$PayName','$server_id','$money','$o_orderid','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
	if (mysqli_query($conn,$sql) == False){
		write_log(ROOT_PATH."log","le8_callback_error_", $sql." ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
		exit("fail");
	}
	WriteCard_money(1,$server_id, $money,$account_id, $o_orderid,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$money,$o_orderid,1,$tjAppId);
    exit("success");
}else{
	write_log(ROOT_PATH."log","le8_callback_error_", "Order Processing! post=$post,get=$get ".date("Y-m-d H:i:s")."\r\n");
	exit("fail");
}
?>