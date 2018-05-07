<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/17
 * Time: 上午11:53
 */
include_once 'config.php';
$gameId = 9;
$sql = "SELECT PayID,PayName,sum(PayMoney) cmoney FROM `web_pay_log` where game_id=9 and PayName not like '%@%' and id>912 group by PayName;";
$conn = SetConn(88);
$query = mysqli_query($conn, $sql);
$datas = array();
while($result = @mysqli_fetch_array($query)){
	$accountId = $result['PayID'];
	$datas[$result['PayName']] = array('accountId'=>$accountId,'money'=>$result['cmoney']);
}
$sql = "SELECT PayID,sum(PayMoney) cmoney FROM `web_pay_log` where game_id=9 and PayName like '%@u591' or PayName like '%@weixin' or PayName like '%@qq' and id>912 group by PayID;";
$query = mysqli_query($conn, $sql);
while($result = @mysqli_fetch_array($query)){
	$accountId = $result['PayID'];
	$snum = giQSModHash($accountId);
	$conn = SetConn($gameId,$snum,1);//account分表
	$acctable = betaSubTableNew($accountId,'account',999);
	$sql_account = "select phone from $acctable where id=$accountId limit 1;";
	$query_account = mysqli_query($conn, $sql_account);
	$result_account = @mysqli_fetch_assoc($query_account);
	if($result_account['phone']){
		if(isset($datas[$result_account['phone']])){
			$datas[$result_account['phone']]['money'] += $result['cmoney'];
		}else{
			$datas[$result_account['phone']] = array('accountId'=>$accountId,'money'=>$result['cmoney']);
		}
	}
}
$conn = SetConn(88);
$sql = "SELECT PayName,sum(PayMoney) cmoney FROM `web_pay_log` where game_id=9 and PayName not like '%@%' and PayID>4000000 group by PayName;";
$query = mysqli_query($conn, $sql);
$odata = array();
while($result = @mysqli_fetch_array($query)){
	if(isset($datas[$result['PayName']])){
		$datas[$result['PayName']]['money'] += $result['cmoney'];
	}else{
		$odata[$result['PayName']] = $result['cmoney'];
	}
}
write_log(ROOT_PATH."log","test_", "data= ".json_encode($datas).',count='.count($datas).date("Y-m-d H:i:s")."\r\n");
write_log(ROOT_PATH."log","test_other_", "data= ".json_encode($odata).',count='.count($odata).date("Y-m-d H:i:s")."\r\n");