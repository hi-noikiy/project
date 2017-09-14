<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/3/31 上午11:47
 * @desc: 统计、web订单同步.
 * @param:
 * @return:
 */
include_once 'config.php';
set_time_limit(1000);
$conn = SetConn(88);
$sql = "select OrderID,PayMoney from web_pay_log where CPID='139'";
$query = @mysqli_query($conn,$sql);
while (@$rows = mysqli_fetch_assoc($query)){
    $orderId = $rows['OrderID'];
    $payMoney = $rows['PayMoney'];
    updateOrder($orderId, $payMoney);
    break;
}
exit(json_encode(array('status'=>0,'msg'=>'success')));

function updateOrder($orderId, $money){
    $conn = ConnServer2('10.0.133.255', 'payor', 'u591*hainiu', 'sdk');

    var_dump($conn);
    exit();
    if($conn == false)
        return false;
    $sql2 = "select id,money from u_paylog where orderid='$orderId' limit 1";
    $query2 = @mysqli_query($conn,$sql2);
    $result = @mysqli_fetch_assoc($query2);

    var_dump($result);
    echo '<br>';
    var_dump($money);
    if($money != $result['money'])
        return false;
    $_money = $money*10;
    $sql = "update u_paylog set money=$_money where orderid='$orderId'";
    mysqli_query($conn, $sql);
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
