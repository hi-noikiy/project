<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","tutu_callback_info_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$openId = $_REQUEST['open_id'];
$cpOrderNo = $_REQUEST['cp_order_no'];
$serialNumber = $_REQUEST['serial_number'];
$amount = $_REQUEST['amount'];
$verfy = $_REQUEST['verfy'];

$cpOrderNoArr = explode('_', $cpOrderNo);
$gameId = $cpOrderNoArr[0];
$serverId = $cpOrderNoArr[1];
$accountId = $cpOrderNoArr[2];
$isgoods = intval($cpOrderNoArr[4]);
global $key_arr;
$appKey = $key_arr[$gameId]['appkey'];
$skey = $key_arr[$gameId]['skey'];
$sign = md5($appKey.$skey.$openId.$cpOrderNo.$serialNumber);
if($sign != $verfy){
    write_log(ROOT_PATH."log","tutu_callback_error_","sign=$verfy, mySign=$sign, ".date("Y-m-d H:i:s")."\r\n");
    exit('failure');
}
$conn = SetConn(88);
$orderId = $serialNumber;
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit("success");
}
$payMoney = intval($amount);
if(!$result){
    global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$accountId' limit 1;";
    $query_account = @mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","tutu_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("failure");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (117, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","tutu_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('failure');
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("success");
}
exit("success");
?>