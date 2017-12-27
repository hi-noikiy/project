<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","xy_callback_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$orderid = $_POST['orderid'];
$uid = $_POST['uid'];
$serverid = $_POST['serverid'];
$amount = $_POST['amount'];
$extra = $_POST['extra'];
$ts = $_POST['ts'];
$sign = $_POST['sign'];

$extra_arr = explode("_", $extra);
$game_id = $extra_arr[0];
$server_id = $extra_arr[1];
$account_id = $extra_arr[2];
$isgoods = intval($extra_arr[4]);

$appkey = $arr_key[$game_id]['appkey'];
$paykey = $arr_key[$game_id]['paykey'];

$my_sign =  _gen_safe_sign($_POST, $appkey);
if($my_sign!=$sign){
    write_log(ROOT_PATH."log","xy_callback_error_log_","sign error! mySign=$my_sign, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array("ret"=>6,"msg"=>"App签名错误")));
}

/*
$sig = $_POST['sig'];
$mysig = _gen_safe_sign($_POST, $paykey);
if($sig != $mysig) {
	write_log(ROOT_PATH."log","xy_callback_error_log_","sign error! mySgn=$mysig, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('ret'=>6, 'msg'=>'支付签名错误')));
}
*/

//获取账号信息
global $accountServer;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql_account = "select NAME,dwFenBaoID,clienttype from account where id = '$account_id' limit 1;";
$query_account= @mysqli_query($conn, $sql_account);
$result_account= @mysqli_fetch_assoc($query_account);

if(!$result_account['NAME']){
    write_log(ROOT_PATH."log","xy_callback_error_log_", "account error! post=$post,get=$get,".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array("ret"=>2,"msg"=>"")));//账号不存在
}else{
    $PayName = $result_account['NAME'];
    $dwFenBaoID = $result_account['dwFenBaoID'];
    $clienttype = $result_account['clienttype'];
}

$order_id = $orderid;
$PayMoney = intval($amount);
$conn = SetConn(88);
//判断订单id情况
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' limit 1;";
$query = @mysqli_query($conn,$sql);
$result_count = @mysqli_fetch_assoc($query);
if($result_count['id']){
    write_log(ROOT_PATH."log","xy_callback_error_log_", "order exist!  post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array("ret"=>4,"msg"=>"")));//订单已存在
}
$conn = SetConn(88);
$Add_Time=date('Y-m-d H:i:s');
$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype,rpCode)";
$sql=$sql." VALUES (97,$account_id,'$PayName','$server_id','$PayMoney','$order_id','$dwFenBaoID','$Add_Time','1','$game_id','$clienttype',1)";
if (mysqli_query($conn,$sql) == False){
    write_log(ROOT_PATH."log","xy_callback_error_log_", $sql."  ".mysqli_error($conn)."  ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array("ret"=>8,"msg"=>"")));
}

WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id,8,0,0,$isgoods);
//统计数据
global $tongjiServer;
$tjAppId = $tongjiServer[$game_id];
sendTongjiData($game_id,$account_id,$server_id,$dwFenBaoID,0,$PayMoney,$order_id,1,$tjAppId);
exit(json_encode(array("ret"=>0,"msg"=>"")));
/**
* 签名和 POST 的参数生成方法
* @param
_POST
去除 sign 和 sig 外的所有 POST 参数数组
* @param
appkey
每个 app 对应的 appkey
* @return
sign
生成的签名字符串
* @return
query_string2 发送请求的 post 参数，已包含 sign
*/
function _gen_safe_sign($params, $prikey)
{
	ksort($params);
	$query_string = array();
	foreach ($params as $key => $val)
	{
		if($key == "sig" || $key == "sign") {
			continue;
		}
		array_push($query_string, $key . '=' . $val);
	}
	$query_string = join('&', $query_string);
	$sign = md5($prikey . $query_string);
	return $sign;
}
?>