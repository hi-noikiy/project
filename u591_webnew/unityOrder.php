<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/3/31 上午11:47
 * @desc: 统计、web订单同步.
 * @param:
 * @return:
 */
include("inc/config.php");
include("inc/function.php");
/*$clientIp = getIP_front();
write_log("log","unityOrder_info_","ip=$clientIp, ".date("Y-m-d H:i:s")."\r\n");
if($clientIp != '121.43.39.108'){
    exit(json_encode(array('status'=>1,'msg'=>'fail')));
}*/
set_time_limit(1000);
$conn = SetConn(88);
$nowDate = date('Y-m-d', time());
$nowDate = '2017-12-01';
$startDate = $_REQUEST['startDate'] ? $_REQUEST['startDate'] : $nowDate;
$endDate = $_REQUEST['endDate'] ? $_REQUEST['endDate'] : $nowDate;

$startTime = $startDate.' 00:00:00';
$endTime = $endDate.' 23:59:59';
if(strtotime($startTime) >= strtotime($endTime))
    exit(json_encode(array('status'=>1,'msg'=>'date error.')));
$sql = "select * from web_pay_log where Add_Time BETWEEN '$startTime' and '$endTime'";
$query = @mysqli_query($conn,$sql);
while (@$rows = mysqli_fetch_assoc($query)){
    $orderId = $rows['OrderID'];
    $gameId = $rows['game_id'];
    $accountId = $rows['PayID'];
    $serverId = $rows['ServerID'];
    $dwFenBaoID = $rows['dwFenBaoID'];
    $payMoney = $rows['PayMoney'];
    $isPay = ($rows['rpCode'] ==1) ? 0 : 1;
    $addTime = strtotime($rows['Add_Time']);
    $isNew = countOrderId($conn, $accountId, $gameId);
    //发送数据
    setMs($gameId,$accountId,$serverId,$dwFenBaoID, 0,$payMoney,$orderId,$isNew,$isPay,$addTime);
    //更新丢单时间、暂时这样
    //$addTime = $rows['Add_Time'];
    //updateDate($orderId, $addTime);
}
exit(json_encode(array('status'=>0,'msg'=>'success')));

function countOrderId($conn, $accountId, $gameId){
    $sql = "select count(id) as count from web_pay_log where PayID=$accountId and game_id=$gameId limit 1;";
    $query = @mysqli_query($conn, $sql);
    $rows = @mysqli_fetch_array($query);
    $isNew = 1;
    if($rows['count'] > 1)
        $isNew = 0;
    return $isNew;
}

function setMs ($gameId,$accountId,$serverId,$fenbaoId,$lev=0,$money, $orderId,$isNew,$isPay,$addTime){
    $conn = ConnServer2('10.26.94.88', 'payor', 'u591*hainiu', 'sdk');
    if($conn == false)
        return false;
    $sql2 = "select id from u_paylog where orderid='$orderId' limit 1";
    $query2 = @mysqli_query($conn,$sql2);
    $result = @mysqli_fetch_assoc($query2);
    if(!isset($result['id'])) {
        //统计数据
        global $tongjiServer;
        $tjAppId = $tongjiServer[$gameId];
        $sql = "insert into u_paylog (accountid,serverid,channel,lev,money,orderid,is_new,is_pay,created_at,appid)";
        $sql .= " values('$accountId','$serverId','$fenbaoId','$lev','$money','$orderId','$isNew','$isPay','$addTime','$tjAppId')";
        if(@mysqli_query($conn, $sql))
            return true;
        return true;
    }
    return true;
}

function updateDate($orderId, $addTime){
    $conn = ConnServer2('10.26.94.88', 'payor', 'u591*hainiu', 'sdk');
    if($conn == false)
        return false;
    $time = strtotime($addTime);
    $sql = "update u_paylog set created_at='$time' where orderid='$orderId'";
    @mysqli_query($conn, $sql);
    return true;
}

function ConnServer2($db_host, $db_user, $db_pass, $db_database){
	$db = @mysqli_connect($db_host,$db_user,$db_pass, $db_database);
	if(!$db){
		$db = @mysqli_connect($db_host,$db_user,$db_pass, $db_database);
	}
	if(!$db){
		return false;
	}
	return $db;
}
