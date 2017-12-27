<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","07073_callback_info_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$data = stripslashes($_REQUEST['data']);
if(!$data)
	exit('fail');
//将json数据解析为数组
$data	= json_decode($data,TRUE);
//提取签名数据
$sign	= $data['sign'];
//提取扩展信息
$extendsInfo = $data['extendsInfo'];
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$isgoods = $extendsInfoArr[4];
//去掉不参与签名的元素
unset($data['sign']);
unset($data['extendsInfo']);
//数组排序
ksort($data);
$appKey = $arr_key[$gameId]['secretKey'];
//验证签名
if($sign != md5(http_build_query($data).$appKey)){
	write_log(ROOT_PATH."log","07073_callback_error_","sign error, data=$data, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	//签名失败
	exit('fail');
}

$conn = SetConn(88);
$orderId = $data['orderid'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit("succ");
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
        write_log(ROOT_PATH."log","07073_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("failure");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (122, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","07073_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('failure');
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("success");
}
exit("success");
?>