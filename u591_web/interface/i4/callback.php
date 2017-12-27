<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 爱思助手支付回调
* ==============================================
* @date: 2016-4-27
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log(ROOT_PATH."log","i4_callback_all_"," post=$post, get=$get, ip=$ip, ".date("Y-m-d H:i:s")."\r\n");

$order_id = $_POST['order_id'];
$billno = $_POST['billno'];
$account = $_POST['account'];
$amount = $_POST['amount'];
$status = $_POST['status'];
$app_id = $_POST['app_id'];
$role = $_POST['role'];
$zone = $_POST['zone'];
$sign = $_POST['sign'];

$check_result = chk($_POST);

if($check_result){
    $billno_arr = explode("_", $billno);
    $game_id = $billno_arr[0];
    $server_id = $billno_arr[1];
    $account_id = $billno_arr[2];
    $isgoods = $billno_arr[4];
    $PayMoney = intval($amount);
    //获取账号信息
    global $accountServer;
    $accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
    $sql_account = " select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
    $query_account=mysqli_query($conn,$sql_account);
    $result_account=mysqli_fetch_assoc($query_account);

    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","i4_callback_error_", "account is not exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
        exit("fail");//账号不存在
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    //判断订单id情况
    $sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
    $query=mysqli_query($conn,$sql);
    $result_count=mysqli_fetch_assoc($query);
    if($result_count['id']){
        write_log(ROOT_PATH."log","i4_callback_error_", "order is exist! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
        exit("success");//订单已存在
    }
    if($status!=0){
        write_log(ROOT_PATH."log","i4_callback_error_", "Order Processing! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("fail");
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
    $sql=$sql." VALUES (91,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","i4_callback_error_", $sql." ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit("fail");
    }
    WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
    exit("success");
}else{
    write_log(ROOT_PATH."log","i4_callback_error_", "sign error, post=$post,get=$get ".date("Y-m-d H:i:s")."\r\n");
    exit("fail");
}

function chk($notify_data){
    include('Rsa.php');
    include('MyRsa.php');

    $privatedata = $notify_data['sign'];
    $privatebackdata = base64_decode($privatedata);
    
    $MyRsa = new MyRsa;
    $data = $MyRsa->rsa_decrypt($privatebackdata, MyRsa::public_key);
 
    foreach(explode('&', $data) as $val) {
        $arr = explode('=', $val);
        $dataArr[$arr[0]] = $arr[1];
    }
    if($dataArr["billno"]==$notify_data['billno'] && $dataArr["amount"]==$notify_data['amount'] && $dataArr["status"]==$notify_data['status']) {
        return true;
    }else{
        return false;
    }
}
?>