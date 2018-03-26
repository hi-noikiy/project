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
    $playerId = intval($attachArr[2]);
    $account_id = intval($attachArr[3]);
    $isgoods = intval($attachArr[5]);
    $accountId= $account_id;
	$gameId = $game_id;
	$snum = giQSModHash($accountId);
	$conn = SetConn($gameId,$snum,1);//account分表
	$acctable = betaSubTableNew($accountId,'account',999);
	$sql_account = "select NAME,dwFenBaoID,clienttype from $acctable where id=$accountId limit 1;";
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
    $loginname = 'oppo';
    if(isOwnWay($PayName,$loginname)){
    	write_log(ROOT_PATH."log","name_{$loginname}_", "account is $PayName ! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    	exit("OK");
    }
    $loginname = 'oppo';
    if(isOwnWay($PayName,$loginname)){
    	write_log(ROOT_PATH."log","name_{$loginname}_", "account is $PayName ! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    	exit("OK");
    }
    $conn = SetConn(88);
    //判断订单id情况
    $sql = "select id,rpCode from web_pay_log where OrderID = '$order_id' and game_id='$game_id' limit 1;";
    $query = @mysqli_query($conn, $sql);
    $result_count = @mysqli_fetch_assoc($query);
    if($result_count['id']){
        write_log(ROOT_PATH."log","oppo_callback_error_", "orderid exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("OK");//订单已存在
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PlayerID,data,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode,packageName)";
    $sql=$sql." VALUES (35,$account_id,'$playerId','$PayMoney','$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype', '1','$isgoods')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","oppo_callback_error_", "sql error! $sql,".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
        exit("f");
    }
    WriteCard_money(1,$server_id, $PayMoney,$playerId, $order_id,8,0,0,$isgoods);
    sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id);
    exit("OK");
}else{
    write_log(ROOT_PATH."log","oppo_callback_error_","$re , sign error! post=$post, get=$get, $sign_basestring".date("Y-m-d H:i:s")."\r\n");
    exit("f");
}
?>