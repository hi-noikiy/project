<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/27
 * Time: 下午8:09
 */
define('ROOT_PATH', str_replace('interface/1pay/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array(
        'android'=>array(
            'access_key' =>'uzc5r6mjo1myb3skppam',
            'secret'=>'4axe31lz16g22u5fhpg3gi8eurcb4rie'
        ),
    		'ios'=>array(
    				'access_key' =>'uzc5r6mjo1myb3skppam',
    				'secret'=>'4axe31lz16g22u5fhpg3gi8eurcb4rie'
    		),
    ),
);

function execPostRequest($url, $data){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function webPay($gameId, $serverId, $accountId, $orderId, $money,$yuanbao,$CardNO,$CardPwd,$isgoods = 0){
    global $accountServer;
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    if($conn == false){
        write_log(ROOT_PATH."log","1pay_cardv5_error_","account mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
        return false;
    }
    $sql_account = "select NAME,dwFenBaoID,clienttype from account where id='$accountId' limit 1;";
    $query_account = @mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","1pay_cardv5_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        return false;
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }

    $conn = SetConn(88);
    if($conn == false){
        write_log(ROOT_PATH."log","1pay_cardv5_error_","web mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
        return false;
    }
    $sql = "select  rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
    $query = @mysqli_query($conn, $sql);
    $result = @mysqli_fetch_array($query);
    if($result['rpCode']==1 || $result['rpCode']==10){
        write_log(ROOT_PATH."log","1pay_cardv5_error_","order id exist. orderid=$orderId, ".date("Y-m-d H:i:s")."\r\n");
        return true;
    }
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayCode,CardNO,CardPwd,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (147,'VND','$CardNO','$CardPwd','$accountId','$PayName','$serverId','$money','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == false){
        write_log(ROOT_PATH."log","1pay_cardv5_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        return false;
    }
    WriteCard_money(1,$serverId, $yuanbao,$accountId, $orderId, 8, 0, $isgoods);
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    $PayMoney = $money/22730;
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$PayMoney,$orderId,1,$tjAppId);
    return true;
}

function webPay1($gameId, $serverId, $accountId, $orderId, $money,$yuanbao,$CardNO,$CardPwd,$isgoods = 0){
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	if($conn == false){
		write_log(ROOT_PATH."log","1pay_cardback_error_","account mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
		return false;
	}
	$sql_account = "select NAME,dwFenBaoID,clienttype from account where id='$accountId' limit 1;";
	$query_account = @mysqli_query($conn, $sql_account);
	$result_account = @mysqli_fetch_assoc($query_account);
	if(!$result_account['NAME']){
		write_log(ROOT_PATH."log","1pay_cardback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
		return false;
	}else{
		$PayName = $result_account['NAME'];
		$dwFenBaoID = $result_account['dwFenBaoID'];
		$clienttype = $result_account['clienttype'];
	}

	$conn = SetConn(88);
	if($conn == false){
		write_log(ROOT_PATH."log","1pay_cardback_error_","web mysql connect error, ".date("Y-m-d H:i:s")."\r\n");
		return false;
	}
	$sql = "select  rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
	$query = @mysqli_query($conn, $sql);
	$result = @mysqli_fetch_array($query);
	if($result['rpCode']==1 || $result['rpCode']==10){
		write_log(ROOT_PATH."log","1pay_cardback_error_","order id exist. orderid=$orderId, ".date("Y-m-d H:i:s")."\r\n");
		return true;
	}
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayCode,CardNO,CardPwd,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
	$sql=$sql." VALUES (148,'VND','$CardNO','$CardPwd','$accountId','$PayName','$serverId','$money','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
	if (mysqli_query($conn,$sql) == false){
		write_log(ROOT_PATH."log","1pay_cardback_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		return false;
	}
	WriteCard_money(1,$serverId, $yuanbao,$accountId, $orderId, 8, 0, $isgoods);
	global $tongjiServer;
	$tjAppId = $tongjiServer[$gameId];
	$PayMoney = $money/22730;
	sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$PayMoney,$orderId,1,$tjAppId);
	return true;
}
