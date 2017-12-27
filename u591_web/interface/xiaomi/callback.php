<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xiaomi_callback_log_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$orderStatus = $_REQUEST['orderStatus'];
if($orderStatus !='TRADE_SUCCESS'){
	write_log(ROOT_PATH."log","xiaomi_callback_error_","status=$orderStatus_check, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('{"errcode":1506}');
}

$signature = $_REQUEST['signature'];
$data = $_REQUEST;
unset($data['signature']);
ksort($data);
$text = urldecode(http_build_query($data));

$cpOrderId = $data['cpOrderId'];
$cpOrderId_arr = explode('_', $cpOrderId);
$gameId = $cpOrderId_arr[0];
$serverId = $cpOrderId_arr[1];
$accountId = $cpOrderId_arr[2];
$type = $cpOrderId_arr[3];
$isgoods = intval($cpOrderId_arr[4]);
global $key_arr;
$appSecret = $key_arr[$gameId][$type]['appSecret'];
$signature_check = hash_hmac("sha1",$text,$appSecret);
write_log(ROOT_PATH."log","xiaomi_callback_result_log_",$appSecret.", $text ".$signature_check.", post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
if($signature_check == $signature){
    $orderId = $cpOrderId;
    $conn = SetConn(88);
    $sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
    $query = mysqli_query($conn, $sql);
    $result = @mysqli_fetch_array($query);
    if($result['rpCode']==1 || $result['rpCode']==10){
    	write_log(ROOT_PATH."log","xiaomi_callback_error_",$orderId."is pay success,  ".date("Y-m-d H:i:s")."\r\n");
    	exit('{"errcode":200}');
    }
    $payMoney = $data['payFee']/100;
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
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit('{"errcode":200}');
}else{
    write_log(ROOT_PATH."log","xiaomi_callback_result_log_"," cpOrderId=$cpOrderId, url=$url,result=$result,check error! ".date("Y-m-d H:i:s")."\r\n");
    exit('{"errcode":1525}');
}
?>