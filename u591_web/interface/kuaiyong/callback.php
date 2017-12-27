<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 快用支付回调
* ==============================================
* @date: 2016-4-27
* @author: Administrator
* @return:
*/
include_once 'config.php';
include_once 'Rsa.php';
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log(ROOT_PATH."log","kuaiyong_callback_all_","post=$post, get=$get, ip=$ip ".date("Y-m-d H:i:s")."\r\n");

$notify_data = $_POST['notify_data'];
$orderid = $_POST['orderid'];
$dealseq = $_POST['dealseq'];
$uid = $_POST['uid'];
$subject = $_POST['subject'];
$v = $_POST['v'];
$sign = $_POST['sign'];

$dealseq_arr = explode("_", $dealseq);
$game_id = $dealseq_arr[0];
$server_id = $dealseq_arr[1];
$account_id = $dealseq_arr[2];
$isgoods = intval($dealseq_arr[4]);

$public_key = $arr_key[$game_id]['public_key'];

$parametersArray = array();
$parametersArray['notify_data'] = $notify_data;
$parametersArray['orderid'] = $orderid;
$parametersArray['dealseq'] = $dealseq;
$parametersArray['uid'] = $uid;
$parametersArray['subject'] = $subject;
$parametersArray['v'] = $v;

ksort($parametersArray);

$sourcestr="";
foreach ($parametersArray as $key => $val) {
    $sourcestr==""?$sourcestr=$key."=".$val:$sourcestr.="&".$key."=".$val;
}

$public_key_string = Rsa::instance()->convert_publicKey($public_key);

$sign = base64_decode($sign);
$notify_data = base64_decode($notify_data);

$re = Rsa::instance()->verify($sourcestr, $sign, $public_key_string);
$notify_data_str = Rsa::instance()->publickey_decodeing($notify_data,$public_key_string);

$notify_data_arr = explode("&", $notify_data_str);
$money_arr = explode("=", $notify_data_arr[1]);
$money = $money_arr[1];
$payresult_arr = explode("=", $notify_data_arr[2]);
$payresult = $payresult_arr[1];

if($re==1){
    $order_id = $orderid;
    $PayMoney = intval($money);
    //获取账号信息
    global $accountServer;
    $accountConn = $accountServer[$game_id];
    $conn = SetConn($accountConn);
    $sql_account = " select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
    $query_account=mysqli_query($conn,$sql_account);
    $result_account=mysqli_fetch_assoc($query_account);

    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","kuaiyong_callback_error_", "account is not exist!  post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("failed");//账号不存在
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }

    $conn = SetConn(88);
    //判断订单id情况
    $sql = " select id,rpCode from web_pay_log where OrderID = '$order_id'limit 1;";
    $query=mysqli_query($conn,$sql);
    $result_count=mysqli_fetch_assoc($query);
    if($result_count['id']){
        write_log(ROOT_PATH."log","kuaiyong_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("success");//订单已存在
    }

    if($payresult==0&&$PayMoney){//充值成功
    	$isPay = 0;
        $conn = SetConn(88);
        $Add_Time=date('Y-m-d H:i:s');
        $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
        $sql=$sql." VALUES (80,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
        if (mysqli_query($conn,$sql) == False){
            write_log(ROOT_PATH."log","kuaiyong_callback_error_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
            exit("failed");
        }
        WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
    }else{//充值失败
    	$isPay = 1;
        $conn = SetConn(88);
        $Add_Time=date('Y-m-d H:i:s');
        $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
        $sql=$sql." VALUES (80,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',2)";
        if (mysqli_query($conn,$sql) == False){
            $str="sql异常".$sql."  , get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n";
            write_log(ROOT_PATH."log","kuaiyong_callback_error_",$str);
            exit("failed");
        }
    }
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId,$isPay);
    exit("success");
}else{
    write_log(ROOT_PATH."log","kuaiyong_callback_error_", "sign error! post=$post, get=$get, ip=$ip ".date("Y-m-d H:i:s")."\r\n");
    exit("failed");
}
?>