<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/27
 * Time: 下午8:08
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","1pay_cardv5_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

//$str = 'a:4:{s:8:"lstTelco";s:8:"mobifone";s:7:"txtSeri";s:15:"052571002999816";s:7:"txtCode";s:12:"918544495005";s:6:"extern";s:38:"8_6001_56104_android_0_1_1488443987155";}';
//$_POST = unserialize($str);
$extern = $_REQUEST['extern'];
$externArr = explode('_', $extern);
$gameId = isset($externArr[0]) ? $externArr[0] : 0;
$serverId = isset($externArr[1]) ? $externArr[1] : 0;
$accountId = isset($externArr[2]) ? $externArr[2] : 0;
$type = isset($externArr[3]) ? $externArr[3] : 0;
$isgoods = isset($externArr[4]) ? $externArr[4] : 0;

if(empty($gameId) || empty($serverId) || empty($accountId) || empty($type)){
    write_log(ROOT_PATH."log","1pay_cardv5_error_","format error.post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('code'=>"102")));
}
$transRef = date("ymdHis").floor(microtime()*1000).rand(10000,99999); //merchant's transaction reference
global $key_arr;
$access_key = $key_arr[$gameId][$type]['access_key']; //require your access key from 1pay
$secret = $key_arr[$gameId][$type]['secret']; //require your secret key from 1pay
$type = $_REQUEST["lstTelco"];
$pin = $_REQUEST["pin"];
$serial = $_REQUEST["txtSeri"];

if(!$pin || !$serial)
    exit(json_encode(array('code'=>'231')));

$data = "access_key=" . $access_key . "&pin=" . $pin . "&serial=" . $serial . "&transRef=" . $transRef . "&type=" . $type;
$signature = hash_hmac("sha256", $data, $secret);
$data.= "&signature=" . $signature;

//do some thing
$url = 'https://api.1pay.vn/card-charging/v5/topup';
$json_cardCharging = execPostRequest($url, $data);
$decode_cardCharging = json_decode($json_cardCharging,true);  // decode json

write_log(ROOT_PATH."log","1pay_cardv5_result_","topup=$json_cardCharging,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if (isset($decode_cardCharging)) {
    $description = $decode_cardCharging["description"];   // transaction description
    $status = $decode_cardCharging["status"];
    $amount = $decode_cardCharging["amount"];       // card's amount
    //$transId = $decode_cardCharging["transId"];
    $transRef = $decode_cardCharging["transRef"];
    $description = $decode_cardCharging['description'];
    if($status == '00' && $amount > 0){
        //success
        $yuanbao = intval($amount/250); //钻石
           
        webPay($gameId, $serverId, $accountId, $transRef, $amount,$yuanbao,$serial,$pin,$isgoods);
        exit(json_encode(array('code'=>"200",'description'=>$description)));
    }
} else {
	sleep(30);
    $data_ep = "access_key=" . $access_key . "&pin=" . $pin . "&serial=" . $serial . "&transId=&transRef=" . $transRef . "&type=" . $type;
    $signature_ep = hash_hmac("sha256", $data_ep, $secret);
    $data_ep.= "&signature=" . $signature_ep;
    $url = 'https://api.1pay.vn/card-charging/v5/query';
    $query_api_ep = execPostRequest($url, $data_ep);

    write_log(ROOT_PATH."log","1pay_cardv5_result_","query=$query_api_ep,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

    $decode_cardCharging=json_decode($json_cardCharging,true);  // decode json
    $description_ep = $decode_cardCharging["description"];   // transaction description
    $status_ep = $decode_cardCharging["status"];
    $amount_ep = $decode_cardCharging["amount"];       // card's amount
    //$transId_ep = $decode_cardCharging["transId"];
    $transRef_ep = $decode_cardCharging["transRef"];
    $description_ep = $decode_cardCharging['description'];

    if($status_ep == '00' && $amount_ep > 0){
        //success
        $yuanbao = intval($amount_ep/250); //钻石
            
        webPay($gameId, $serverId, $accountId, $transRef_ep,$amount_ep,$yuanbao,$serial,$pin,$isgoods);
        exit(json_encode(array('code'=>"200",'description'=>$description_ep)));
    }
}
exit(json_encode(array('code'=>"207",'description'=>$description_ep)));
