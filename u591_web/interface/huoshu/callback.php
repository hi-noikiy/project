<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 20170524
 * Time: 上午10:02
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","huoshu_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($_REQUEST['order_status'] != 2){
	write_log(ROOT_PATH."log","huoshu_callback_error_","交易失败，post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("SUCCESS");
}
$data['app_id'] = $_REQUEST["app_id"];
$data['cp_order_id'] = $_REQUEST["cp_order_id"];
$data['mem_id'] = $_REQUEST['mem_id'];
$data['order_id'] = $_REQUEST['order_id'];  
$data['order_status'] = $_REQUEST['order_status'];
$data['pay_time'] = $_REQUEST['pay_time'];
$data['product_id'] = $_REQUEST["product_id"];  
$data['product_name'] = $_REQUEST['product_name'];
$data['product_price'] = $_REQUEST['product_price'];

$extendsInfo = $_REQUEST['ext']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
global $key_arr;
$cpid = 157;
if(substr($type,0,7) == 'android'){
	$cpid = 168;
}
$data['app_key'] = $key_arr[$gameId][$type]['appSecret'];
$data['sign'] = md5(http_build_query($data));
$sign = $_REQUEST['sign'];
//write_log(ROOT_PATH."log","huoshu_callback_error_","data: ".json_encode($data).date("Y-m-d H:i:s")."\r\n");
if($sign != $data['sign']) {
    write_log(ROOT_PATH."log","huoshu_callback_error_","sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAILURE');
}
$conn = SetConn(88);
$orderId = $data['order_id'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit("SUCCESS");
}
$payMoney = intval($data['product_price']);
if(!$result){
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","huoshu_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("FAILURE");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES ($cpid, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","huoshu_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('FAILURE');
    }
    //write_log(ROOT_PATH."log","huoshu_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("SUCCESS");
}
exit("SUCCESS");