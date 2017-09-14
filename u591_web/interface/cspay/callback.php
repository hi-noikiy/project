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
write_log(ROOT_PATH."log","cspay_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$success = '{code:"0000"}';
if($_REQUEST["error"] != 1){
	write_log(ROOT_PATH."log","cspay_callback_error_","pay is not success,post=$post ".date("Y-m-d H:i:s")."\r\n");
	exit($success);
}
$data['error'] = $_REQUEST["error"];
$data['message'] = $_REQUEST["message"];
$data['parter'] = $_REQUEST['parter'];
$data['ptoid'] = $_REQUEST['ptoid'];  
$data['oid'] = $_REQUEST['oid'];
$data['attach'] = $_REQUEST['attach'];  
$data['money'] = $_REQUEST['money'];
$data['stime'] = $_REQUEST['stime'];
$extendsInfo = $data['attach']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
global $key_arr;
$data['key'] = $key_arr[$gameId][$type]['appkey'];
$str = implode('', $data);
$data['sign'] = md5($str);
$sign = $_REQUEST['sign'];
if($sign != $data['sign']) {
    write_log(ROOT_PATH."log","cspay_callback_error_","sign error, {$str},sign={$sign},{$data['sign']},post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('errorSign');
}
$conn = SetConn(88);
$orderId = $data['oid'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit($success);
}
$payMoney = $data['money'];
if(!$result){
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","cspay_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("error");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (169, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","cspay_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('error');
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit($success);
}
exit($success);