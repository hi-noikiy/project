<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 20170606
 * Time: 上午10:02
 */
include 'base.php';
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","aibei_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$string = $_POST;
global $queryResultUrl, $platpkey;
$transdata=$string['transdata'];
if(stripos("%22",$transdata)){ //判断接收到的数据是否做过 Urldecode处理，如果没有处理则对数据进行Urldecode处理
	$string= array_map ('urldecode',$string);
}
$transdata=$string['transdata'];
$data=json_decode($transdata,true);
if($data['result'] != 0){
	write_log(ROOT_PATH."log","aibei_callback_error_","交易失败".$transdata.date("Y-m-d H:i:s")."\r\n");
	exit('SUCCESS');
}
$extendsInfo = $data['cpprivate']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
$isgoods = $extendsInfoArr[4];
$platpkey = $karr[$type]['platpkey'];
$respData = 'transdata='.$string['transdata'].'&sign='.$string['sign'].'&signtype='.$string['signtype'];//把数据组装成验签函数要求的参数格式
if(!parseResp($respData, $platpkey, $respJson)) {
	//验签失败
	write_log(ROOT_PATH."log","aibei_callback_error_","sign error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}

$conn = SetConn(88);
$orderId = $data['cporderid'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit("SUCCESS");
}
$payMoney = intval($data['money']);
if(!$result){
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","aibei_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("FAILURE");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (158, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","aibei_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('FAILURE');
    }
    write_log(ROOT_PATH."log","aibei_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("SUCCESS");
}
exit("SUCCESS");