<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","xiaomi_callback_log_"," post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$appId = $_REQUEST['appId'];
$cpOrderId = $_REQUEST['cpOrderId'];
$uid = $_REQUEST['uid'];
$orderId = $_REQUEST['orderId'];
$orderStatus = $_REQUEST['orderStatus'];
$payFee = $_REQUEST['payFee'];
$productName = $_REQUEST['productName'];
$productCount = $_REQUEST['productCount'];
$payTime = $_REQUEST['payTime'];
$signature = $_REQUEST['signature'];
$errcode = $_REQUEST['errcode'];
$errMsg = $_REQUEST['errMsg'];

$cpOrderId_arr = explode('_', $cpOrderId);
$game_id = $cpOrderId_arr[0];
$server_id = $cpOrderId_arr[1];
$account_id = intval($cpOrderId_arr[2]);
global $key_arr;
$appSecret = $key_arr[$game_id]['appSecret'];
$text = $appId.$cpOrderId.$uid;
$text = "appId=$appId&cpOrderId=$cpOrderId&uid=$uid";
$signature_check = get_signature($text, $appSecret);
$url = "http://mis.migc.xiaomi.com/api/biz/service/queryOrder.do?appId=$appId&cpOrderId=$cpOrderId&uid=$uid&signature=$signature_check";
$result = file_get_contents($url);
write_log(ROOT_PATH."log","xiaomi_check_result_log_"," url=$url,result=$result, ".date("Y-m-d H:i:s")."\r\n");

$result_arr = json_decode($result,true);
$appId_check = $result_arr['appId'];
$cpOrderId_check = $result_arr['cpOrderId'];
$cpUserInfo_check = $result_arr['cpUserInfo'];
$uid_check = $result_arr['uid'];
$orderId_check = $result_arr['orderId'];
$orderStatus_check = $result_arr['orderStatus'];
$payFee_check = $result_arr['payFee'];
$productCode_check = $result_arr['productCode'];
$productName_check = $result_arr['productName'];
$productCount_check = $result_arr['productCount'];
$payTime_check = $result_arr['payTime'];
$signature_check = $result_arr['signature'];

if($appId_check&&$cpOrderId_check&&$cpOrderId_check&&$cpUserInfo_check){
    $order_id = $cpOrderId_check;
    $PayMoney = $payFee_check/100;
    //获取账号信息
    global $accountServer;
    $accountConn = $accountServer[$game_id];
    $conn = SetConn($accountConn);
    $sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
    $query_account = @mysqli_query($conn,$sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);

    if(!$result_account['NAME']){
        $str= "4 账号不存在 ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","xiaomi_callback_error_",$str);
        exit('{"errcode":1506}');//账号不存在
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    //判断订单id情况
    $sql = " select count(id) count from web_pay_log where OrderID = '$order_id' limit 1;";
    $query = @mysqli_query($conn,$sql);
    $result_count = @mysqli_fetch_assoc($query);
    if($result_count['count']){
        $str= "5 订单已存在 ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","xiaomi_callback_error_",$str);
        exit('{"errcode":1506}');//订单已存在
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype)";
    $sql=$sql." VALUES (25,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype')";
    if (mysqli_query($conn,$sql) == False){
        $str="6 ".$sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","xiaomi_callback_error_",$str);
        exit;
    }
    $isPay = 0;
    if($orderStatus_check=='TRADE_SUCCESS'){
        xiaomiPayLog($order_id,1,$PayMoney);//更新充值记录
        WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id);
    }else{
        $isPay = 1;
        xiaomiPayLog($order_id,2,$PayMoney);//更新充值记录
    }
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId,$isPay);
    exit('{"errcode":200}');
}else{
    write_log(ROOT_PATH."log","xiaomi_check_result_log_"," cpOrderId=$cpOrderId, url=$url,result=$result,check error! ".date("Y-m-d H:i:s")."\r\n");
    exit('{"errcode":1525}');
}

function xiaomiPayLog($OrderID,$rpCode,$PayMoney){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime' ";
    $sql=$sql." where OrderID='$OrderID'";
    //echo $sql;
    if (mysqli_query($conn,$sql) == False){
        //写入失败日志
        $str="6 ".$sql."  ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","xiaomi_callback_error_",$str);
        exit('{"errcode":1525}');
    }
}
?>