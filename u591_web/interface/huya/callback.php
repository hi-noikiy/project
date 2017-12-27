<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 20170615
 * Time: 上午10:02
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","huya_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$sign = $_REQUEST["signature"];

$extendsInfo = $_REQUEST['userDefine']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
$isgoods = $extendsInfoArr[4];
global $key_arr;
$key = $key_arr[$gameId][$type]['appSecret'];
$data = $_REQUEST;
unset($data['signature']);
ksort($data);
$signstr = '';
foreach ($data as $k=>$v){
	$signstr .= "$k=$v";
}
$success = json_encode(array('code'=>200,'data'=>'true'));
$signstr .= $key;
$data['sign'] = strtolower(md5($signstr));
if($sign != $data['sign']) {
    write_log(ROOT_PATH."log","huya_callback_error_",$signstr.",sign error,{$data['sign']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAILURE');
}
$conn = SetConn(88);
$orderId = $data['gameOrderId'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
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
        write_log(ROOT_PATH."log","huya_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("FAILURE");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (164, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","huya_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('FAILURE');
    }
    //write_log(ROOT_PATH."log","huya_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit($success);
}
exit($success);