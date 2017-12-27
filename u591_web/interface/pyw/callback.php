<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 20170524
 * Time: 上午10:02
 */
include 'config.php';
$mydata = file_get_contents("php://input");
write_log(ROOT_PATH."log","pyw_callback_info_","data=$mydata, ".date("Y-m-d H:i:s")."\r\n");
$data_array = json_decode($mydata, true);
$sign = $data_array['sign'];
$data['cp_orderid'] = $data_array["cp_orderid"];
$data['ch_orderid'] = $data_array['ch_orderid'];
$data['amount'] = $data_array['amount'];  

$extendsInfo = $data_array['cp_orderid']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
$isgoods = intval($extendsInfoArr[4]);
global $key_arr;
$success = '{"ack":200,"msg":"Ok"}';
$data['apiSecret'] = $key_arr[$gameId][$type]['appSecret'];
$str = $data['apiSecret'].$data['cp_orderid'].$data['ch_orderid'].$data['amount'];
$data['sign'] = md5($str);
//write_log(ROOT_PATH."log","pyw_callback_error_","data: ".json_encode($data).date("Y-m-d H:i:s")."\r\n");
if($sign != $data['sign']) {
    write_log(ROOT_PATH."log","pyw_callback_error_","$str,sign error,$sign,data=".json_encode($data).",  ".date("Y-m-d H:i:s")."\r\n");
    exit('FAILURE');
}
$conn = SetConn(88);
$orderId = $data['cp_orderid'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
	write_log(ROOT_PATH."log","pyw_callback_error_",json_encode($result)."is pay success".json_encode($data).",  ".date("Y-m-d H:i:s")."\r\n");
    exit($success);
}
$payMoney = intval($data['amount']);
if(!$result){
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","pyw_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("FAILURE");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (166, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","pyw_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('FAILURE');
    }
    //write_log(ROOT_PATH."log","pyw_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit($success);
}
exit($success);