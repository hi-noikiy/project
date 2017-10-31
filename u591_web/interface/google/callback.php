<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/17
 * Time: 下午1:41
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","google_callback_info_all_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

//$str = 'a:2:{s:8:"sinature";s:460:"a1F0c20yOFRVZ1d2bCt5RGV5VWhMZTlHTWZUYWJsMTZOVUl0MkZ3eFdQMC9GdThqaGliaE5xSzZQNExIT0ozRy9hM0pzeDFGdDZLYXhHL1pXcW44YUp3dVF2ZnpWYmVpaGwxcVJ6bmlLMkdYSmlWTnlmRTREUWxYb0hYY0YzWVlDWWpROXc1NDN5TjlkWW1mc294b3BUM0s3eE9YNEIyNmdoN1ZtN29oTTFMSnFDVUlVMmdHSEJsOGE0UE5ucmJzTFJ5RnVUaVByeHBJVW01emJPSXpPaWRCUTNMSzFTNlNFdGJlRGRZamN6VTN5L2oxZzNkK3FrbzA4MjNtSnNOa2lUWFRpRmhadnNnNVR5Sm55UXJpY2Q5aFE0R2M0TTFteXZOUk92YWozQXBRMm5OME5RQXVNM1BtN3R2Y0dINEhUSTB4NTRjcUJKTCs5Nkw1SGcvZ3hBPT0=";s:9:"sinedData";s:460:"UHVyY2hhc2VJbmZvKHR5cGU6aW5hcHApOnsicGFja2FnZU5hbWUiOiJjb20udTc3Ni5wb2tlLmFuZHJvaWQueG0iLCJwcm9kdWN0SWQiOiJrZGxtNiIsInB1cmNoYXNlVGltZSI6MTQ5MzE5NDU2MzQzNiwicHVyY2hhc2VTdGF0ZSI6MCwiZGV2ZWxvcGVyUGF5bG9hZCI6IjhfODAwMV8xNzEwMTI5X3hpbm1hXzJfMSIsInB1cmNoYXNlVG9rZW4iOiJhZWlnaW1qb2xibGVpbmtkYXBmaW1kbWcuQU8tSjFPeHNwaDJQam0xQ3RKei1BT2hIX0RKMDhKSGI4eWhJMzFfUk55STBybHBFM1NfTmdYbzdvZFJTT2JqVFdLcS1GcWhqTzFkNm9EcHpzU3lVVU5uMmtQY0xPbGI0SVh2ak1kRC1zQnBVWFNNUXlXMHRIbm8ifQ==";}';
//$_REQUEST = unserialize($str);

$google_wallet_data = base64_decode(str_replace(' ', '+', $_REQUEST['sinedData']));
$sinature = base64_decode($_REQUEST['sinature']);
$time = date('Y-m-d H:i:s');

if (empty($google_wallet_data) || empty($sinature)) {
    write_log(ROOT_PATH."log","google_callback_error_","106验证信息为空,post=$post,get=$get, ". date('Y-m-d H:i:s')."\r\n");
    exit('106');//验证信息为空
}
$google_wallet_data = str_replace('PurchaseInfo(type:inapp):', '', $google_wallet_data);
$walletData = json_decode($google_wallet_data, true);

$developerPayloadArr = explode('_', $walletData['developerPayload']);
$gameId = $developerPayloadArr[0];
$serverId  = $developerPayloadArr[1];
$accountId = $developerPayloadArr[2];
$type = $developerPayloadArr[3];
$isgoods = $developerPayloadArr[4]?$developerPayloadArr[4]:0;
global $key_arr;
$publicKey = isset($key_arr[$gameId][$type]['public_key']) ? $key_arr[$gameId][$type]['public_key'] : $key_arr[$gameId]['public_key'];
if (!$publicKey) {
    write_log(ROOT_PATH."log","google_callback_error_","107密钥不存在,post=$post,get=$get".date('Y-m-d H:i:s')."\r\n");
    exit('107');//密钥不存在
}
//验证
//write_log(ROOT_PATH."log","google_callback_error_",$publicKey.''.date('Y-m-d H:i:s')."\r\n");
$verifyOK = verify_market_in_app($google_wallet_data, $sinature, $publicKey);
if ($verifyOK) {
    $productId = $walletData['productId'];
    //$orderId   = $walletData['developerPayload'];
    $orderId   = $walletData['orderId'];
    if (!array_key_exists($productId, $google_id_value)) {
        write_log(ROOT_PATH."log","google_callback_error_","101 产品ID不存在,productId=$productId, ".date('Y-m-d H:i:s')."\r\n");
        exit('101');
    }
    //获取账号信息
    global $accountServer;
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $sql_account = " select NAME,dwFenBaoID,clienttype from account where id='$accountId' limit 1;";
    $query_account = @mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);

    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","google_callback_error_", "account not exist. get=$get,".date("Y-m-d H:i:s")."\r\n");
        exit();//账号不存在
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $EMoney = $google_id_value[$productId][1];//emoney
    $PayMoney= $google_id_value[$productId][0];
    $currency= isset($google_id_value[$productId][2]) ? $google_id_value[$productId][2] : 'USD';
    $conn = SetConn(88);
    //判断订单id情况
    $sql = "SELECT COUNT(id) count FROM web_pay_log WHERE OrderID ='$orderId' limit 1; ";
    $query = @mysqli_query($conn, $sql);
    $result_count = @mysqli_fetch_assoc($query);
    if($result_count['count']){
        write_log(ROOT_PATH."log","google_callback_error_","订单已存在,orderId=$orderId, ".date('Y-m-d H:i:s')."\r\n");
        exit('200');//订单已存在
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql = "insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
    $sql.= " VALUES (144,'$currency',$accountId,'$PayName','$serverId','$PayMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype',1)";
    if (mysqli_query($conn, $sql) == false) {
       write_log(ROOT_PATH."log","google_callback_error_","105sql error,sql=$sql,".mysqli_error($conn)." ".date('Y-m-d H:i:s')."\r\n");
        exit('105');//sql异常
    }
    @mysqli_close($conn);
    WriteCard_money(1,$serverId, $EMoney, $accountId, $orderId, 8, 0, $isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$PayMoney,$orderId,1,$tjAppId);
    exit('200');
} else {
   write_log(ROOT_PATH."log","google_callback_error_", "sign error. verifyOK=$verifyOK, post=$post, get=$get," .date('Y-m-d H:i:s')."\r\n");
    exit('100');
}
function verify_market_in_app($signed_data, $signature, $public_key_base64) {
    $key = "-----BEGIN PUBLIC KEY-----\n".
        chunk_split($public_key_base64, 64,"\n").
        '-----END PUBLIC KEY-----';
    //using PHP to create an RSA key
    $key = openssl_get_publickey($key);
    //$signature should be in binary format, but it comes as BASE64.
    //So, I'll convert it.
    $signature = base64_decode($signature);
    //using PHP's native support to verify the signature
    $result = openssl_verify($signed_data, $signature, $key, OPENSSL_ALGO_SHA1);
    if (0 === $result) {
        return false;
    } else if (1 !== $result) {
        return false;
    } else {
        return true;
    }
}