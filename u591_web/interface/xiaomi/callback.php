<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","xiaomi_callback_log_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$appId = $_REQUEST['appId'];
$cpOrderId = $_REQUEST['cpOrderId'];
$uid = $_REQUEST['uid'];
$orderId = $_REQUEST['orderId'];
$orderStatus = $_REQUEST['orderStatus'];
$payFee = $_REQUEST['payFee'];
$productName = $_REQUEST['productName'];
$productCount = $_REQUEST['productCount'];
$payTime = $_REQUEST['payTime'];
$signature = $_REQUEST['signature'];
$errcode = $_REQUEST['errcode'];
$errMsg = $_REQUEST['errMsg'];

$cpOrderId_arr = explode('_', $cpOrderId);
$gameId = $cpOrderId_arr[0];
$serverId = $cpOrderId_arr[1];
$accountId = intval($cpOrderId_arr[2]);
$type = intval($cpOrderId_arr[3]);
global $key_arr;
$appSecret = $key_arr[$gameId][$type]['appSecret'];
$text = $appId.$cpOrderId.$uid;
$text = "appId=$appId&cpOrderId=$cpOrderId&uid=$uid";
$signature_check = get_signature($text, $appSecret);
$url = "http://mis.migc.xiaomi.com/api/biz/service/queryOrder.do?appId=$appId&cpOrderId=$cpOrderId&uid=$uid&signature=$signature_check";
$result = file_get_contents($url);
write_log(ROOT_PATH."log","xiaomi_check_result_log_"," url=$url,result=$result, ".date("Y-m-d H:i:s")."\r\n");

$result_arr = json_decode($result,true);
$appId_check = $result_arr['appId'];
$cpOrderId_check = $result_arr['cpOrderId'];
$cpUserInfo_check = $result_arr['cpUserInfo'];
$uid_check = $result_arr['uid'];
$orderId_check = $result_arr['orderId'];
$orderStatus_check = $result_arr['orderStatus'];
$payFee_check = $result_arr['payFee'];
$productCode_check = $result_arr['productCode'];
$productName_check = $result_arr['productName'];
$productCount_check = $result_arr['productCount'];
$payTime_check = $result_arr['payTime'];
$signature_check = $result_arr['signature'];
if($orderStatus_check !='TRADE_SUCCESS'){
	write_log(ROOT_PATH."log","xiaomi_callback_error_","status=$orderStatus_check, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('{"errcode":1506}');
}
if($appId_check&&$cpOrderId_check&&$cpUserInfo_check){

    $orderId = $cpOrderId;
    $conn = SetConn(88);
    $sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
    $query = mysqli_query($conn, $sql);
    $result = @mysqli_fetch_array($query);
    if($result['rpCode']==1 || $result['rpCode']==10){
    	write_log(ROOT_PATH."log","xiaomi_callback_error_",$orderId."is pay success,  ".date("Y-m-d H:i:s")."\r\n");
    	exit('{"errcode":200}');
    }
    $PayMoney = $payFee_check/100;
    //获取账号信息
    global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","xiaomi_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit('{"errcode":1506}');
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (25, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","xiaomi_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('{"errcode":1525}');
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit('{"errcode":200}');
}else{
    write_log(ROOT_PATH."log","xiaomi_check_result_log_"," cpOrderId=$cpOrderId, url=$url,result=$result,check error! ".date("Y-m-d H:i:s")."\r\n");
    exit('{"errcode":1525}');
}
?>