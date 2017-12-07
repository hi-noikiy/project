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
$conn = SetConn(88);
$nowDate = date('Y-m-d', time());
$startDate = $_REQUEST['startDate'] ? $_REQUEST['startDate'] : $nowDate;
$endDate = $_REQUEST['endDate'] ? $_REQUEST['endDate'] : $nowDate;
$startDate = '2017-11-11';
$endDate = '2017-11-12';
$startTime = $startDate.' 00:00:00';
$endTime = $endDate.' 23:59:59';
if(strtotime($startTime) >= strtotime($endTime))
    exit(json_encode(array('status'=>1,'msg'=>'date error.')));
$sql = "select * from web_pay_log where Add_Time BETWEEN '$startTime' and '$endTime'";
$query = @mysqli_query($conn,$sql);
$i = 0;
while (@$rows = mysqli_fetch_assoc($query)){
	echo ++$i;
    $orderId = $rows['OrderID'];
    $gameId = $rows['game_id'];
    $accountId = $rows['PayID'];
    $serverId = $rows['ServerID'];
    $dwFenBaoID = $rows['dwFenBaoID'];
    $payMoney = $rows['PayMoney'];
    $curr = $rows['PayCode'];
    if($curr == 'VND'){
    	$payMoney = $payMoney/22730;
    }
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
    $conn = ConnServer2('47.88.188.12', 'payor', 'u591*hainiu', 'sdk');
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
    $conn = ConnServer2('47.88.188.12', 'payor', 'u591*hainiu', 'sdk');
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
