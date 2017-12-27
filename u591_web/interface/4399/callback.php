<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","4399_callback_info_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$orderId = $_REQUEST['orderid'];
$pType = $_REQUEST['p_type'];
$openId = $_REQUEST['uid'];
$amount = $_REQUEST['money'];
$gamemoney = $_REQUEST['gamemoney'];
$appserverid = $_REQUEST['serverid'];
$mark = $_REQUEST['mark'];
$roleid = $_REQUEST['roleid'];
$time = $_REQUEST['time'];
$sign = $_REQUEST['sign'];

$markArr = explode('_', $mark);
$gameId = $markArr[0];
$serverId = $markArr[1];
$accountId = $markArr[2];
$isgoods= isset($markArr[4])?$markArr[4]:0;;
global $key_arr;
$appSecret = $key_arr[$gameId]['appsecret'];

$md5Str  = $orderId.$openId.$amount.$gamemoney.$appserverid.$appSecret.$mark.$roleid.$time;
$mySign = md5($orderId.$openId.$amount.$gamemoney.$appserverid.$appSecret.$mark.$roleid.$time);

if($sign != $mySign){
    write_log(ROOT_PATH."log","4399_callback_error_","sign=$sign, mySign=$mySign, md5Str=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array('status'=>1, 'code'=>'sign_error', 'money'=>$amount, 'gamemoney'=>$gamemoney, 'msg'=>'sign错误')));
}

$conn = SetConn(88);
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);

if($result['rpCode']==1 || $result['rpCode']==10){
	exit(json_encode(array('status'=>2, 'code'=>'', 'money'=>$amount, 'gamemoney'=>$gamemoney, 'msg'=>'充值成功')));
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
        write_log(ROOT_PATH."log","4399_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit(json_encode(array('status'=>1, 'code'=>'user_not_exist', 'money'=>$amount, 'gamemoney'=>$gamemoney, 'msg'=>'account error.')));
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (119, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","4399_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit(json_encode(array('status'=>1, 'code'=>'other_error', 'money'=>$amount, 'gamemoney'=>$gamemoney, 'msg'=>'msql error.')));
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit(json_encode(array('status'=>2, 'code'=>'', 'money'=>$amount, 'gamemoney'=>$gamemoney, 'msg'=>'充值成功')));
    
}
exit(json_encode(array('status'=>1, 'code'=>'sign_error', 'money'=>$amount, 'gamemoney'=>$gamemoney, 'msg'=>'其他错误')));
?>