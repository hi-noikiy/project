<?php
include_once 'config.php';
$data = file_get_contents("php://input");

write_log(ROOT_PATH."log","uc_callback_all_log_","data=$data, ".date("Y-m-d H:i:s")."\r\n");

$data_array = json_decode($data, true);

$sign = $data_array['sign'];
$orderId = $data_array['data']['orderId'];
$gameId = $data_array['data']['gameId'];
$ucid = $data_array['data']['accountId'];
$creator = $data_array['data']['creator'];
$payway = $data_array['data']['payWay'];
$amount = $data_array['data']['amount'];
$callbackInfo = $data_array['data']['callbackInfo'];
$orderStatus = $data_array['data']['orderStatus'];
$failedDesc = $data_array['data']['failedDesc'];
$cpOrderId = $data_array['data']['cpOrderId'];

$callbackInfo_arr = explode("#",$callbackInfo);
$game_id = intval($callbackInfo_arr[0])?intval($callbackInfo_arr[0]):2;
$server_id = intval($callbackInfo_arr[1]);
$pay_id = intval($callbackInfo_arr[2]);

$channel = $callbackInfo_arr[3];
$apiKey = $key_arr[$game_id][$channel]['apiKey'];

$cpOrderIdStr = '';
if(!empty($cpOrderId))
	$cpOrderIdStr = 'cpOrderId='.$cpOrderId;
	

$signStr = "accountId=".$ucid."amount=$amount"."callbackInfo=$callbackInfo".$cpOrderIdStr."creator=$creator"."failedDesc=$failedDesc"."gameId=$gameId"."orderId=$orderId"."orderStatus=$orderStatus"."payWay=$payway".$apiKey;
$sign_check = md5($signStr);
if($sign != $sign_check){
    write_log(ROOT_PATH."log","uc_callback_error_","sign=$sign, mySign=$sign_check, signStr=$signStr, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAILURE');
}

$conn = SetConn(88);
$sql = "select  rpCode  from web_pay_log where OrderID = '$orderId'";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);

if($result['rpCode']==1 || $result['rpCode']==10){
    exit("SUCCESS");
}

$PayMoney = intval($amount);
if(!$result){
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$pay_id'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","uc_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("4");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,PayCode,SubStat,game_id,clienttype)";
    $sql=$sql." VALUES (15,$pay_id,'$PayName','$server_id','$PayMoney','$orderId','$dwFenBaoID','$Add_Time','$payway','1','$game_id','$clienttype')";
    
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","uc_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('93');
    }
}
if($orderStatus=='S'){
	$isPay = 0;//成功
    $rpCode =1;
    ChangPayLog($orderId, $rpCode,$pay_id, $PayMoney, $payway);
    updatePoints($pay_id,$PayMoney,'f_dx',$orderId);
    updateRankUp($pay_id,'f_dx');
    WriteCard_money(1,$server_id, $PayMoney,$pay_id, $orderId);
    WritePayMsg(0,$server_id,$pay_id,$orderId,$PayMoney,$game_id);
}else if($orderStatus=='F'){
	$isPay = 1; 
    $rpCode =2;
    ChangPayLog($orderId,$rpCode,$pay_id,$PayMoney, $payway);
    WritePayMsg(1,$server_id,$pay_id,$orderId,$PayMoney, $game_id);
}
//统计数据
$tjAppId = $tongjiServer[$game_id];
$tongjiData = tongjiData($game_id, $pay_id, $server_id, $dwFenBaoID, 0 ,$PayMoney, $orderId, 1, $tjAppId, $isPay);
SAddData($tongjiData);
exit("SUCCESS");
function tongjiData($gameId, $accountId, $serverId, $channel, $lev=0, $payMoney, $orderId, $isNew =1, $appId, $isPay=0){
	$tongjiArr = array();
	$tongjiArr['accountid'] = $accountId;
	$tongjiArr['serverid'] = $serverId;
	$tongjiArr['channel'] = $channel;
	$tongjiArr['lev'] = $lev;
	$tongjiArr['money'] = $payMoney;
	$tongjiArr['orderid'] = $orderId;
	$tongjiArr['is_new'] = $isNew;
	$tongjiArr['is_pay'] = $isPay;
	$conn = SetConn(88);
	$sql = "select count(*) as count from web_pay_log where PayID=$accountId and game_id=$gameId limit 1;";
	$query = mysqli_query($conn, $sql);
	$rows = @mysqli_fetch_array($query);
	if($rows['count'] > 0)
		$tongjiArr['is_new'] = 0;
	$tongjiArr['created_at'] = time();
	$tongjiArr['appid'] = $appId;//$tongjiServer[$gameId];
	return $tongjiArr;
}
function ChangPayLog($OrderID,$rpCode,$PayID,$PayMoney,$PayCode){
    $conn = SetConn(88);
    $rpTime=date('Y-m-d H:i:s');
    $sql="update web_pay_log set PayMoney='$PayMoney',rpCode='$rpCode', rpTime='$rpTime', PayCode='$PayCode'";
    $sql=$sql." where PayID=$PayID and OrderID='$OrderID'";
    if (mysqli_query($conn,$sql) == False){
    	write_log(ROOT_PATH."log","uc_callback_error_", "sql=$sql".date("Y-m-d H:i:s")."\r\n");
    }
}
?>