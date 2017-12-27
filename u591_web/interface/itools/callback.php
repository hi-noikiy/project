<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
$ip = getIP_front();
write_log(ROOT_PATH."log","itools_callback_all_"," post=$post,get=$get,file_in=$file_in,ip=$ip ".date("Y-m-d H:i:s")."\r\n");

include_once ROOT_PATH.'interface/itools/lib/KLogger.php';
include_once ROOT_PATH.'interface/itools/lib/notify.class.php';
//通知数据
$notify_data = $_POST['notify_data'];
//签名
$sign = $_POST['sign'];
$notify = new notify;
//RSA解密
$notify_data = $notify->decrypt($notify_data);
//验证签名
if ($notify->verify($notify_data, $sign)) {
    //签名校验成功
    $json_arr = json_decode($notify_data, true);
    $order_id_com = $json_arr['order_id_com'];
    $user_id = $json_arr['user_id'];
    $amount = $json_arr['amount'];
    $account = $json_arr['account'];
    $order_id = $json_arr['order_id'];
    $result = $json_arr['result'];

    $order_id_com_arr = explode("_", $order_id_com);
    $game_id = $order_id_com_arr[0];
    $server_id = $order_id_com_arr[1];
    $account_id = $order_id_com_arr[2];
    $isgoods = intval($order_id_com_arr[4]);

    if($result!="success"){
        write_log(ROOT_PATH."log","itools_callback_error_", "result=$notify_data, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("fail");
    }
    //获取账号信息
    global $accountServer;
    $accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
    $sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
    $query_account = @mysqli_query($conn,$sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);

    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","itools_callback_error_", "account not exist. post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
        exit("fail");//账号不存在
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $PayMoney = intval($amount);
    $conn = SetConn(88);
    //判断订单id情况
    $sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
    $query = @mysqli_query($conn,$sql);
    $result_count = @mysqli_fetch_assoc($query);
    if($result_count['id']){
        write_log(ROOT_PATH."log","itools_callback_error_", "order exist.  post=$post, get=$get,".date("Y-m-d H:i:s")."\r\n");
        exit("Success");//订单已存在
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype)";
    $sql=$sql." VALUES (68,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype')";
    if (mysqli_query($conn,$sql) == False){
        $str=$sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","itools_callback_error_",$str);
        exit("fail");
    }
    $rpCode =1;
    PayLog_itools($order_id,$rpCode,$PayMoney);//更新充值记录
    WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
    exit("Success");
} else {
    write_log(ROOT_PATH."log","itools_callback_error_", "sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("fail");
}

function PayLog_itools($OrderID,$rpCode,$PayMoney){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime' ";
    $sql=$sql." where OrderID='$OrderID'";
    //echo $sql;
    if (mysqli_query($conn,$sql) == False){
        //写入失败日志
        write_log(ROOT_PATH."log","itools_callback_error_log_", $sql."  " .mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit;
    }
}
?>