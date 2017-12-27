<?php
include_once 'config.php';
include_once 'Rsa.php';
$post = serialize($_POST);
$get = serialize($_GET);
$ip = getIP_front();
write_log(ROOT_PATH."log","oppo_callback_info_all_","post=$post, get=$get, ip=$ip ".date("Y-m-d H:i:s")."\r\n");

$notifyId = $_REQUEST['notifyId'];
$partnerOrder = $_REQUEST['partnerOrder'];
$count = $_REQUEST['count'];
$attach = $_REQUEST['attach'];
$price = $_REQUEST['price'];
$sign = $_REQUEST['sign'];
$productName = $_REQUEST['productName'];
$productDesc = $_REQUEST['productDesc'];

$sign_basestring = "notifyId=$notifyId&partnerOrder=$partnerOrder&productName=$productName&productDesc=$productDesc&price=$price&count=$count&attach=$attach";
$rsa = new Rsa();
$re = $rsa->verify($sign_basestring, $sign);
if($re==1){
    $order_id = $partnerOrder;
    $PayMoney = intval($price/100);
    $attachArr = explode("_", $attach);
    $game_id = intval($attachArr[0]);
    $server_id = intval($attachArr[1]);
    $account_id = intval($attachArr[2]);
    $isgoods = intval($attachArr[4]);
    global $accountServer;
    $accountConn = $accountServer[$game_id];
    $conn = SetConn($accountConn);
    $sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
    $query_account= @mysqli_query($conn, $sql_account);
    $result_account= @mysqli_fetch_assoc($query_account);

    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","oppo_callback_error_", "account not exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("4");//账号不存在
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    //判断订单id情况
    $sql = "select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
    $query = @mysqli_query($conn, $sql);
    $result_count = @mysqli_fetch_assoc($query);
    if($result_count['id']){
        write_log(ROOT_PATH."log","oppo_callback_error_", "orderid exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("OK");//订单已存在
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (35,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","oppo_callback_error_", "sql error! $sql,".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit("f");
    }
    WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$game_id];
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
    exit("OK");
}else{
    write_log(ROOT_PATH."log","oppo_callback_error_","sign error! post=$post, get=$get, $sign_basestring".date("Y-m-d H:i:s")."\r\n");
    exit("f");
}
?>