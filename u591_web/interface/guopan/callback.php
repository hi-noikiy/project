<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 果盘支付回调
* ==============================================
* @date: 2016-4-27
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log(ROOT_PATH."log","guopan_callback_all_"," post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");

//sign error! post=a:13:{s:8:"trade_no";s:32:"1464075382_104299_KMW7WJ4ROG9SG3";s:12:"serialNumber";s:31:"6_1201_6205523_485768157.426119";s:5:"money";s:4:"0.01";s:6:"status";s:1:"1";s:1:"t";s:10:"1464075646";s:4:"sign";s:32:"960136ccd3b7f4d840bc1a771b6f2c50";s:5:"appid";s:6:"104299";s:6:"app_id";s:6:"104299";s:7:"item_id";s:1:"1";s:10:"item_price";s:4:"0.01";s:10:"item_count";s:1:"1";s:8:"reserved";s:31:"6_1201_6205523_485768157.426119";s:8:"game_uin";s:16:"3J7B6AE9F34BTRRT";}, get=a:0:{}, ip=125.88.168.100, 2016-05-24 15:40:33
$trade_no = $_REQUEST['trade_no'];
$serialNumber = $_REQUEST['serialNumber'];
$money = $_REQUEST['money'];
$status = $_REQUEST['status'];
$t = $_REQUEST['t'];
$sign = $_REQUEST['sign'];

$serialNumberArr = explode("_", $serialNumber);
$game_id = $serialNumberArr[0];
$server_id = $serialNumberArr[1];
$account_id = $serialNumberArr[2];
$channel = $serialNumberArr[3];
$isgoods = $serialNumberArr[4];
global $key_arr;
$appsecret = $key_arr[$game_id][$channel]['appSecret'];
$md5Str = $serialNumber.$money.$status.$t.$appsecret;
$mysign=md5($md5Str);

if($mysign != $sign){
	write_log(ROOT_PATH."log","guopan_callback_check_","sign error! post=$post, get=$get, md5Str=$md5Str, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
if($status == 1){
	//获取账号信息
	global $accountServer;
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1";
	$query_account = @mysqli_query($conn,$sql_account);
	$result_account = @mysqli_fetch_assoc($query_account);
	if(!$result_account['NAME']){
		write_log(ROOT_PATH."log","guopan_callback_error_", "account is not exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit("fail");//账号不存在
	}else{
		$PayName = $result_account['NAME'];
		$dwFenBaoID = $result_account['dwFenBaoID'];
		$clienttype = $result_account['clienttype'];
	}
	$conn = SetConn(88);
	//判断订单id情况
	$sql = "select id,rpCode from web_pay_log where OrderID ='$trade_no' limit 1";
	$query = mysqli_query($conn, $sql);
	$result_count = @mysqli_fetch_assoc($query);
	if($result_count['id']){
		write_log(ROOT_PATH."log","guopan_callback_error_", "order is exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit("success");//订单已存在
	}
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
	$sql=$sql." VALUES (115,$account_id,'$PayName','$server_id','$money','$trade_no','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
	if (mysqli_query($conn,$sql) == False){
		write_log(ROOT_PATH."log","guopan_callback_error_", $sql." ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
		exit("fail");
	}
	WriteCard_money(1,$server_id, $money,$account_id, $trade_no,8,0,0,$isgoods);
	//统计数据
	global $tongjiServer;
	$tjAppId = $tongjiServer[$game_id];
	sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$money,$trade_no,1,$tjAppId);
	exit("success");

}else{
	write_log(ROOT_PATH."log","guopan_callback_error_", "Order Processing! post=$post,get=$get ".date("Y-m-d H:i:s")."\r\n");
	exit("fail"); 
}
?>