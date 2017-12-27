<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","lenovo_callback_all_log_","post=$post,get=$get,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");

/*$str = 'a:2:{s:4:"sign";s:172:"ETgmFTQjAFpVZraQkXQDrO3qG+zo6dM/qaLGxMZNF5u9Pmu0pug9DWGYYKG3cnAAHGwNXFPoeADEvn+HbHlREFMyld9ZvbuTTPm3LTdQFzkxuVpozz+rDH9/KZjHua8hV6G9AWhD/V+Dth1w0EkCM4aQTDLVVVPbiUKhqRSw+RQ=";s:9:"transdata";s:287:"{\"transtype\":0,\"result\":0,\"transtime\":\"2016-11-22 13:54:45\",\"count\":1,\"paytype\":5,\"money\":600,\"waresid\":105920,\"appid\":\"1611160041865.app.ln\",\"exorderno\":\"8_8001_137368_8\",\"feetype\":0,\"transid\":\"2161122135445832218598758\",\"cpprivate\":\"8_8001_137368_41\"}";}';
$str = unserialize($str);
$aa = stripcslashes($str['transdata']);
$_POST['transdata'] = $str['transdata'];
$_POST['sign'] = $str['sign'];*/

$transdata = stripslashes($_POST['transdata']);
$sign = $_POST['sign'];
$transdata_arr = json_decode($transdata,true);
$exorderno = $transdata_arr['exorderno'];
$transid = $transdata_arr['transid'];
$waresid = $transdata_arr['waresid'];
$appid = $transdata_arr['appid'];
$feetype = $transdata_arr['feetype'];
$money = $transdata_arr['money'];
$count = $transdata_arr['count'];
$result = $transdata_arr['result'];
$transtype = $transdata_arr['transtype'];
$transtime = $transdata_arr['transtime'];
$cpprivate = $transdata_arr['cpprivate'];

$exorderno_arr = explode("_", $exorderno);
$game_id = intval($exorderno_arr[0]);
$server_id = intval($exorderno_arr[1]);
$account_id = intval($exorderno_arr[2]);
$isgoods = intval($exorderno_arr[4]);

if(!$game_id || !$server_id || !$account_id){
	write_log(ROOT_PATH."log","lenovo_callback_error_", "param error, get=$get, post=$post, ".date("Y-m-d H:i:s")."\r\n");
	exit('FAILED') ;
}
global $key_arr;
$configAppId = $key_arr[$game_id]['appid'];
if($appid == $configAppId) {
	require 'new-valid.php';
	$key = $key_arr[$game_id]['paykey'];
	$result_check = verify($transdata, $key, $sign);
	if(!$result_check){
		write_log(ROOT_PATH."log","lenovo_callback_error_", "sign error, post=$post, get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit('FAILED') ;
	}
} else {
	require 'IappDecrypt.php';
	$key = $key_arr[$game_id]['paykey'];
	$tools = new IappDecrypt();
	$result_check = $tools->validsign($transdata,$sign,$key);
	if($result_check != 0){
		write_log(ROOT_PATH."log","lenovo_callback_error_", "sign error, post=$post, get=$get,".date("Y-m-d H:i:s")."\r\n");
		exit('FAILED') ;
	}
}


$PayMoney = intval($money/100);
$order_id = $transid;
//获取账号信息
global $accountServer;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
$query_account = mysqli_query($conn, $sql_account);
$result_account = @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
	write_log(ROOT_PATH."log","lenovo_callback_error_", "account not exist.  post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("FAILED");//账号不存在
}else{
	$PayName = $result_account['NAME'];
	$dwFenBaoID = $result_account['dwFenBaoID'];
	$clienttype = $result_account['clienttype'];
}

$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query=mysqli_query($conn,$sql);
$result_count=mysqli_fetch_assoc($query);
if($result_count['id']){
	write_log(ROOT_PATH."log","lenovo_callback_error_", "order exist. sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
	exit("SUCCESS");//订单已存在
}

$conn = SetConn(88);
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype)";
$sql=$sql." VALUES (48,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype')";
if (mysqli_query($conn,$sql) == False){
	write_log(ROOT_PATH."log","lenovo_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
	exit("FAILED");
}
$isPay = 0;
if($result==0){
	lenovoPayLog($order_id,1,$PayMoney);//更新充值记录
	WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
}else{
	$isPay =1;
	lenovoPayLog($order_id,2,$PayMoney);//更新充值记录
}
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$game_id];
sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId,$isPay);
exit('SUCCESS');
function lenovoPayLog($OrderID,$rpCode,$PayMoney){
	$conn = SetConn(88);
	$rpTime=date('Y-m-d H:i:s');
	$sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime' ";
	$sql=$sql." where OrderID='$OrderID'";
	//echo $sql;
	if (mysqli_query($conn,$sql) == False){
		//写入失败日志
		write_log(ROOT_PATH."log","lenovo_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
		exit("FAILED");
	}
}
?>