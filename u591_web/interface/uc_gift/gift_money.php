<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 活动期间充值返利
 * 返回金额
 * @param gameId,accountId, startDate, endDate, sign, type(0获取金额,1检查是否已经领取乐礼包)
 * ==============================================
 * @date: 2016-9-23
 * @author: luoxue
 * @version:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","uc_gift_money_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$accountId = intval($_REQUEST['accountId']);
$gameId = intval($_REQUEST['gameId']);
$startDate = trim($_REQUEST['startDate']);
$endDate = trim($_REQUEST['endDate']);
$sign = strtolower(trim($_REQUEST['sign']));

$params = array(
		'gameId',
		'accountId',
		'startDate',
		'endDate',
		'sign'
);
for ($i = 0; $i< count($params); $i++){
	if (!isset($_REQUEST[$params[$i]])) {
		exit(json_encode(array('status'=>1, 'msg'=>'Missing '.$params[$i])));
	} else {
		if(empty($_REQUEST[$params[$i]]))
			exit(json_encode(array('status'=>1, 'msg'=>$params[$i].' should not be empty.')));
	}
}

$array = array();
$array['gameId'] = $gameId;
$array['accountId'] = $accountId;
$array['startDate'] = $startDate;
$array['endDate'] =$endDate;

ksort($array);
$appKey = $key_arr['appKey'];
$md5Str = http_build_query($array);
$mySign = md5(urldecode($md5Str).$appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));

$conn = SetConn(88);
$startDate = date('Y-m-d H:i:00', $startDate);
$endDate = date('Y-m-d H:i:59', $endDate);
$sql = "select sum(PayMoney) as money from web_pay_log where PayID = '$accountId' and game_id='$gameId' and Add_time >=$startDate and Add_Time<=$endDate";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'sql error.')));

$rs = @mysqli_fetch_assoc($query);
if(isset($rs['money']))
	exit("$accountId {$rs['money']}");
else
	exit(json_encode(array('status'=>2, 'msg'=>'没有充值纪录.')));
?>