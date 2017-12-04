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
write_log(ROOT_PATH."log","1pay_callback_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

//$str = 'a:14:{s:10:"access_key";s:20:"uzc5r6mjo1myb3skppam";s:6:"amount";s:5:"10000";s:9:"card_name";s:41:"Ngân hàng TMCP Kỹ thương Việt Nam";s:9:"card_type";s:3:"TCB";s:8:"order_id";s:38:"8_6001_56104_android_0_1_1488523300231";s:10:"order_info";s:28:"获得6钻石,另送60钻石";s:10:"order_type";s:2:"ND";s:12:"request_time";s:20:"2017-03-03T13:41:41Z";s:13:"response_code";s:2:"00";s:16:"response_message";s:20:"Giao dich thanh cong";s:13:"response_time";s:20:"2017-03-03T13:45:24Z";s:9:"signature";s:64:"7eb151f09647f666089a855796e9f54f609bb512606805e317f1542aac85215c";s:9:"trans_ref";s:32:"8639d559851f485ca0afa9d8d8158559";s:12:"trans_status";s:6:"finish";}';
//$_GET = unserialize($str);
$trans_ref = isset($_GET["trans_ref"]) ? $_GET["trans_ref"] : NULL;
$response_code = isset($_GET["response_code"]) ? $_GET["response_code"] : NULL;

$order_id = $_GET['order_id'];
$orderinfo =  $_GET['order_info'];
$orderIdArr = explode('_', $orderinfo);
$gameId = isset($orderIdArr[0]) ? $orderIdArr[0] : 0;
$serverId = isset($orderIdArr[1]) ? $orderIdArr[1] : 0;
$accountId = isset($orderIdArr[2]) ? $orderIdArr[2] : 0;
$type = isset($orderIdArr[3]) ? $orderIdArr[3] : 0;
$isgoods = isset($orderIdArr[4]) ? $orderIdArr[4] : 0;

$access_key = $key_arr[$gameId][$type]['access_key']; //require your access key from 1pay
$secret = $key_arr[$gameId][$type]['secret']; //require your secret key from 1pay

if($response_code == "00") {
    $command = "close_transaction";
    $data = "access_key=".$access_key."&command=".$command."&trans_ref=".$trans_ref;
    $signature = hash_hmac("sha256", $data, $secret);
    $data.= "&signature=" . $signature;
    $url = 'http://api.1pay.vn/bank-charging/service/v2';
    $json_bankCharging = execPostRequest($url, $data);

    $decode_bankCharging=json_decode($json_bankCharging,true);  // decode json
    // Ex: {"amount":10000,"trans_status":"close","response_time": "2014-12-31T00:52:12Z","response_message":"Giao dịch thành công","response_code":"00","order_info":"test dich vu","order_id":"001","trans_ref":"44df289349c74a7d9690ad27ed217094", "request_time":"2014-12-31T00:50:11Z","order_type":"ND"}
    $response_message = $decode_bankCharging["response_message"];
    $response_code = $decode_bankCharging["response_code"];
    $amount = $decode_bankCharging["amount"];
    if($response_code == "00") {
        $money = intval($amount/250);
        webPay1($gameId, $serverId, $accountId, $order_id,$amount, $money,  '', '',$isgoods);
        echo $response_message."-".$amount;
    } else
        echo $response_message;
} else
    echo $_GET["response_message"];

