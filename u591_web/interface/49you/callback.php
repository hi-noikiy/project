<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/19
 * Time: 上午10:02
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
/*$post = 'a:6:{s:7:"orderId";s:22:"9201712240942209828810";s:3:"uid";s:9:"736616643";s:6:"amount";s:2:"30";s:8:"serverId";s:4:"8168";s:4:"sign";s:32:"2bd8e108bcb49bb0eccd90d55144ff01";s:9:"extraInfo";s:29:"8_8168_725972953_android_3_19";}';
$_REQUEST = http_build_query(unserialize($post));
echo $_REQUEST;die;*/
write_log(ROOT_PATH."log","49you_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$orderId = $_REQUEST["orderId"];
$uid = $_REQUEST["uid"];
$myserverId = $_REQUEST['serverId'];
$amount = $_REQUEST['amount'];  
$extraInfo = $_REQUEST['extraInfo'];
$sign = $_REQUEST['sign'];
$extendsInfoArr = explode('_', $extraInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
$isgoods = $extendsInfoArr[4];
global $key_arr;
$secret = $key_arr[$gameId][$type]['appSecret'];
$signstr = "{$orderId}{$uid}{$myserverId}{$amount}{$extraInfo}";
$signstr .= $secret;
$mysign = strtolower(md5($signstr));
if($sign != $mysign) {
    write_log(ROOT_PATH."log","49you_callback_error_",$signstr.",sign error,$secret, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}
$conn = SetConn(88);
$orderId = $_REQUEST['orderId'];
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
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","49you_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("fail");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (176, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","49you_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('fail');
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("success");
}
exit("success");