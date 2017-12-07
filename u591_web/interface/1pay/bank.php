<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/27
 * Time: 下午8:12
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","1pay_bank_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

//$str = 'a:4:{s:8:"lstTelco";s:8:"mobifone";s:7:"txtSeri";s:15:"052571002999816";s:7:"txtCode";s:12:"918544495005";s:6:"extern";s:38:"8_6001_56104_android_0_1_1488443987155";}';
//$_POST = unserialize($str);
$order_id = $_POST['order_info'];
$orderIdArr = explode('_', $order_id);
$gameId = isset($orderIdArr[0]) ? $orderIdArr[0] : 0;
$serverId = isset($orderIdArr[1]) ? $orderIdArr[1] : 0;
$accountId = isset($orderIdArr[2]) ? $orderIdArr[2] : 0;
$type = isset($orderIdArr[3]) ? $orderIdArr[3] : 0;

if(empty($gameId) || empty($serverId) || empty($accountId) || empty($type)){
    write_log(ROOT_PATH."log","1pay_bank_error_","format error.post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>"102")));
}
global $key_arr;
$access_key = $key_arr[$gameId][$type]['access_key']; //require your access key from 1pay
$secret = $key_arr[$gameId][$type]['secret']; //require your secret key from 1pay
$return_url = "http://pokeynweb.u591776.com/interface/1pay/callback.php";
$command = 'request_transaction';
$amount = $_POST['amount'];   // >10000

$outTradeNo = $gameId.'_'.$serverId.'_'.$accountId.'_'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
$order_id = $outTradeNo;
$order_info = $_POST['order_info'];

$data = "access_key=".$access_key."&amount=".$amount."&command=".$command."&order_id=".$order_id."&order_info=".$order_info."&return_url=".$return_url;
$signature = hash_hmac("sha256", $data, $secret);
$data.= "&signature=".$signature;
$url = 'http://api.1pay.vn/bank-charging/service/v2';
$json_bankCharging = execPostRequest($url, $data);
//Ex: {"pay_url":"http://api.1pay.vn/bank-charging/sml/nd/order?token=LuNIFOeClp9d8SI7XWNG7O%2BvM8GsLAO%2BAHWJVsaF0%3D", "status":"init", "trans_ref":"16aa72d82f1940144b533e788a6bcb6"}
write_log(ROOT_PATH."log","1pay_bank_result_","result=$json_bankCharging. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$decode_bankCharging=json_decode($json_bankCharging,true);  // decode json
if($decode_bankCharging['trans_ref']){
	$conn = SetConn(88);
	if($conn == false){
		write_log(ROOT_PATH."log","1pay_bank_error_","web mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
		return false;
	}
	$Add_Time=date('Y-m-d H:i:s');
	$sql = "insert into web_bank_order(trans_ref,account_id,Add_Time,order_id,type,amount) values('{$decode_bankCharging['trans_ref']}',$accountId,'$Add_Time','$outTradeNo','$type','$amount');";
	@mysqli_query($conn, $sql);
	write_log(ROOT_PATH."log","1pay_bank_order_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
}
$pay_url = $decode_bankCharging["pay_url"];
exit(json_encode(array('code'=>'200','data'=>array('paymentUrl'=>$pay_url))));