<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","tongyouyou_callback_info_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$orderid = $_REQUEST['orderid'];
$username = $_REQUEST['username'];
$appGameid = $_REQUEST['gameid'];
$roleid = $_REQUEST['roleid'];
$appServerid = $_REQUEST['serverid'];
$paytype = $_REQUEST['paytype'];
$amount = $_REQUEST['amount'];
$paytime = $_REQUEST['paytime'];
$attach = $_REQUEST['attach'];
$sign = $_REQUEST['sign'];
$externArr = explode('#', $attach);
$gameId = $externArr[0];
$serverId = $externArr[1];
$accountId = $externArr[2];
global $key_arr;
$appKey = $key_arr[$gameId]['appkey'];
$urlAttach = urlencode($attach);
$md5Str = "orderid=$orderid&username=$username&gameid=$appGameid&roleid=$roleid&serverid=$appServerid&paytype=$paytype&amount=$amount&paytime=$paytime&attach=$urlAttach&appkey=$appKey";

$mySign = md5($md5Str);
if($sign != $mySign){
    write_log(ROOT_PATH."log","tongyouyou_callback_error_","sign=$sign, mySign=$mySign, md5Str=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit('failure');
}
$conn = SetConn(88);
$orderId = $orderid;
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
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
        write_log(ROOT_PATH."log","tongyouyou_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("failure");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (125, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","tongyouyou_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('failure');
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("success");
}
exit("failure");
?>