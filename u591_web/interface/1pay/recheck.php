<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/27
 * Time: 下午8:12
 */
include_once 'config.php';
$conn = SetConn(88);
if($conn == false){
	write_log(ROOT_PATH."log","1pay_bank_recheck_error_","web mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
	return false;
}
$Add_Time=date('Y-m-d H:i:s',time()-30*60);
$sql = "select * from  web_bank_order where status=0 and Add_Time<'$Add_Time'";
$query = mysqli_query($conn, $sql);
$url = 'http://api.1pay.vn/bank-charging/service/v2';
while ($row = mysqli_fetch_array($query,MYSQL_ASSOC)){
	$command = 'get_transaction_detail';
	$id = $row['id'];
	$type = $row['type'];
	$gameId = 8;
	$access_key = $key_arr[$gameId][$type]['access_key'];
	$secret = $key_arr[$gameId][$type]['secret'];
	$trans_ref = $row['trans_ref'];
	$data = "access_key=".$access_key."&command=".$command."&trans_ref=".$trans_ref;
	$signature = hash_hmac("sha256", $data, $secret);
	$data.= "&signature=" . $signature;
	$json_bankCharging = execPostRequest($url, $data);
	write_log(ROOT_PATH."log","1pay_bank_recheck_info_","result=$json_bankCharging ,".json_encode($row).date("Y-m-d H:i:s")."\r\n");
	$decode_bankCharging=json_decode($json_bankCharging,true);  // decode json
	$response_code = $decode_bankCharging['trans_status'];
	$order_id = $decode_bankCharging['order_id'];
	if($order_id != $row['order_id']){
		write_log(ROOT_PATH."log","1pay_bank_recheck_error_","order error, ".json_encode($row).json_encode($decode_bankCharging).date("Y-m-d H:i:s")."\r\n");
		set_order($id,2);
		continue;
	}
	$orderinfo =  $decode_bankCharging['order_info'];
	$orderIdArr = explode('_', $orderinfo);
	$gameId = isset($orderIdArr[0]) ? $orderIdArr[0] : 0;
	$serverId = isset($orderIdArr[1]) ? $orderIdArr[1] : 0;
	$accountId = isset($orderIdArr[2]) ? $orderIdArr[2] : 0;
	$type = isset($orderIdArr[3]) ? $orderIdArr[3] : 0;
	$isgoods = isset($orderIdArr[4]) ? $orderIdArr[4] : 0;
	if(in_array($response_code, array("close",'finish')) ) {
		$command = "close_transaction";
		$data = "access_key=".$access_key."&command=".$command."&trans_ref=".$trans_ref;
		$signature = hash_hmac("sha256", $data, $secret);
		$data.= "&signature=" . $signature;
		$json_bankCharging = execPostRequest($url, $data);
	
		$decode_bankCharging=json_decode($json_bankCharging,true);  // decode json
		// Ex: {"amount":10000,"trans_status":"close","response_time": "2014-12-31T00:52:12Z","response_message":"Giao dịch thành công","response_code":"00","order_info":"test dich vu","order_id":"001","trans_ref":"44df289349c74a7d9690ad27ed217094", "request_time":"2014-12-31T00:50:11Z","order_type":"ND"}
		$response_message = $decode_bankCharging["response_message"];
		$response_code = $decode_bankCharging["response_code"];
		$amount = $decode_bankCharging["amount"];
		if($amount != $row['amount']){
			write_log(ROOT_PATH."log","1pay_bank_recheck_error_","amount error, ".json_encode($row).json_encode($decode_bankCharging).date("Y-m-d H:i:s")."\r\n");
			set_order($id,2);
			continue;
		}
		if($response_code == "00") {
			write_log(ROOT_PATH."log","1pay_bank_recheck_true_",json_encode($row).json_encode($decode_bankCharging).date("Y-m-d H:i:s")."\r\n");
			$money = intval($amount/250);
			webPay1($gameId, $serverId, $accountId, $order_id,$amount, $money,  '', '',$isgoods);
			set_order($id,1);
		} else{
			set_order($id,2);
		}
	}else {
		set_order($id,2);
	}
	usleep(10);
}
function set_order($id,$status){
	$conn = SetConn(88);
	if($conn == false){
		write_log(ROOT_PATH."log","1pay_bank_recheck_error_","web mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
		return false;
	}
	$sql = "update web_bank_order set status=$status where id=$id";
	@mysqli_query($conn, $sql);
}