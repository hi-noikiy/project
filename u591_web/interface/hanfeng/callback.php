<?php
include_once 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
$data = file_get_contents("php://input");
$dataArr = json_decode($data, true);
write_log(ROOT_PATH."log","hanfeng_callback_log_","post=$post, get=$get, data=$data, ".date("Y-m-d H:i:s")."\r\n");
$cpTradeNo = $dataArr['cpTradeNo'];// 通行证及支付服务生成的订单号
$gameId = $dataArr['gameId'];//游戏编号
$userId = $dataArr['userId'];//用户编号
$roleId = $dataArr['roleId'];//角色编号
$serverId = $dataArr['serverId'];//游戏区号(客户端传过来)
$channelId = $dataArr['channelId'];//渠道编号
$itemId = $dataArr['itemId'];//购买的物品编号
$itemAmount = $dataArr['itemAmount'];//发货数量，优先使用money字段发货
$privateField = $dataArr['privateField'];//应用自定义字段varchar(128)保留字段，除非有特别说明，否则一律为空字符串。
$money = $dataArr['money'];//发货金额（分，值为-1时使用itemAmount字段发货
$status = $dataArr['status'];//交易状态，0表示成功
$sign = $dataArr['sign'];//验证签名

$privateFieldArr = explode('#', $privateField);
$game_id = $privateFieldArr[0];
$server_id = $privateFieldArr[1];
$account_id = $privateFieldArr[2];

$goodsarr = explode('_', $privateFieldArr[4]);
$isgoods = intval($goodsarr[0]);

global $key_arr;
$privateKey = $key_arr[$game_id]['appkey'];//开发者自定义参数
//sign=md5(cpTradeNo|gameId|userId|roleId|serverId|channelId|itemId|itemAmount|privateField|money|status|privateKey)
$md5Str = $cpTradeNo.'|'.$gameId.'|'.$userId.'|'.$roleId.'|'.$serverId.'|'.$channelId.'|'.$itemId.'|'.$itemAmount.'|'.$privateField.'|'.$money.'|'.$status.'|'.$privateKey;
$sign = md5($md5Str);
//验证签名 判断订单状态是否成功
if($sign == $dataArr['sign'] && $dataArr['status'] == 0){
	global $accountServer;
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$sql_account = "select NAME,dwFenBaoID,clienttype from account where id ='$account_id' limit 1;";
	$query_account = mysqli_query($conn, $sql_account);
	$result_account = @mysqli_fetch_assoc($query_account);
	if(!$result_account['NAME']){
		write_log(ROOT_PATH."log","hanfeng_callback_error_", "account is not exist! sql=$sql_account, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('-1');
	}else{
		$PayName = $result_account['NAME'];
		$dwFenBaoID = $result_account['dwFenBaoID'];
		$clienttype = $result_account['clienttype'];
	}
	$order_id = $cpTradeNo;
	$PayMoney = intval($money/100);
	$conn = SetConn(88);
	//判断订单id情况
	$sql = "select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
	$query=mysqli_query($conn,$sql);
	$result_count=mysqli_fetch_assoc($query);
	if($result_count['id']){
		write_log(ROOT_PATH."log","hanfeng_callback_error_", "order is exist! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit($cpTradeNo);
	}
	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
	$sql=$sql." VALUES (126,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype','1')";
	if (mysqli_query($conn,$sql) == False){
		write_log(ROOT_PATH."log","hanfeng_callback_error_", $sql.", post=$post, get=$get, ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
		exit('-1');
	} else {
		WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
		//统计数据
        global $tongjiServer;
		$tjAppId = $tongjiServer[$game_id];
		sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
        exit($cpTradeNo);
	}
} else {
	write_log(ROOT_PATH."log","hanfeng_callback_error_","sign error! post=$post, get=$get, md5Str=$md5Str, ".date("Y-m-d H:i:s")."\r\n");
	exit('-1');
}
?>