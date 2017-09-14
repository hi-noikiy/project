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
write_log(ROOT_PATH."log","quickgame_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($_REQUEST["payStatus"] != '0'){
	write_log(ROOT_PATH."log","quickgame_callback_error_","paystatus error, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('FAILURE');
}
$actRate = $_REQUEST["actRate"];
$sign = $_REQUEST["sign"];
$extendsInfo = $_REQUEST['extrasParams']; //提取拓展信息
$extendsInfoArr = explode(',', $extendsInfo);
if(count($extendsInfoArr)==3){ //web支付
	$gameId = 8;
	$serverId = explode('=', $extendsInfoArr[1])[1];
	$roleid = explode('=', $extendsInfoArr[0])[1];
	$sid = $serverId;
	$table = betaSubTable($sid, 'u_player', '1000');
	$conn = SetConn($sid);
	$sql = "select account_id from $table where serverid='$sid' and id='".$roleid."' limit 1;";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","quickgame_callback_error_","账号查询失败$extendsInfo，sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('FAILURE');
	}
	$rs = @mysqli_fetch_assoc($query);
	if(!$rs){
		write_log(ROOT_PATH."log","quickgame_callback_error_","账号不存在$extendsInfo，sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('SUCCESS');
	}
	$accountId = $rs['account_id'];
	$gifttype = explode('=', $extendsInfoArr[2])[1];
	$type = 'gp';
	$isgoods = 0;
	if(in_array($gifttype, $moncards)){ //月卡
		$isgoods = $mVal[$gifttype][2];
	}
	$cpid=2;
}else{
	$cpid=1;
	$extendsInfoArr = explode('_', $extendsInfo);
	$gameId = $extendsInfoArr[0];
	$serverId = $extendsInfoArr[1];
	$accountId = $extendsInfoArr[2];
	$type = $extendsInfoArr[3];
	if($type == 'ios'){
		$cpid = 3;
	}
	$isgoods = $extendsInfoArr[4];
	$gifttype = $extendsInfoArr[6].'_'.$extendsInfoArr[7];
}
global $key_arr;
$key = $key_arr[$gameId][$type]['appSecret'];
$data = $_REQUEST;
unset($data['sign']);
ksort($data);
$str = urldecode(http_build_query($data)).'&'.$key;
$data['sign'] = md5($str);
if($sign != $data['sign']) {
    write_log(ROOT_PATH."log","quickgame_callback_error_",$str.",sign error,{$data['sign']}, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('FAILURE');
}
$conn = SetConn(88);
$orderId = $data['cpOrderNo'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
    exit("SUCCESS");
}
$currency = 'USD';//$data['payCurrency']; //TWD USD
$payMoney = $data['usdAmount'];
foreach ($xmVal as $k=>$v){
	if($payMoney>=$k){
		$yuanbao = $v+ceil(($payMoney-$k)*60);
		//write_log(ROOT_PATH."log","quickgame_callback_cal_",'ceil(('.$payMoney.'-'.$k.')*60)='.ceil(($payMoney-$k)*60).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		break;
	}
}

if(in_array($gifttype, $moncards)){ //月卡
	if($payMoney>=$mVal[$gifttype][0]){ //满足条件
		$yuanbao = $mVal[$gifttype][1]+ceil(($payMoney-$mVal[$gifttype][0])*60);
	}
}

$odds = $actRate == '1'?0:$actRate*100;
if(!$result){
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","quickgame_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit("FAILURE");
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayCode,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES ('$cpid','$currency', $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","quickgame_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit('FAILURE');
    }
    //write_log(ROOT_PATH."log","quickgame_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
    $gameMoney = $yuanbao;//转化为RMB
    WriteCard_money(1,$serverId, $gameMoney,$accountId, $orderId ,8 ,0 , $odds ,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit("SUCCESS");
}
exit("SUCCESS");