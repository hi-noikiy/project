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

$appkey = $arr_key[$game_id]['appkey'];

$my_sign =  _gen_safe_sign($_POST, $appkey);
if($my_sign!=$sign){
    write_log(ROOT_PATH."log","xy_callback_error_log_","sign error! mySign=$my_sign, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit(json_encode(array("ret"=>6,"msg"=>"")));
}
//获取账号信息
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$account_id'";
$query_account= mysqli_query($conn, $sql_account);
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
$sql = " select id,rpCode from web_pay_log where OrderID = '$order_id' ";
$query=mysqli_query($conn,$sql);
$result_count=mysqli_fetch_assoc($query);
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

updatePoints($account_id,$PayMoney,'f_dx',$order_id);//修改积分
updateRankUp($account_id,'f_dx');//修改等级
WriteCard_money(1,$server_id, $PayMoney,$account_id, $order_id);
WritePayMsg(0,$server_id,$account_id,$order_id,$PayMoney,$game_id);

//统计数据
$tjAppId = $tongjiServer[$game_id];
$tongjiData = tongjiData($game_id, $account_id, $server_id, $dwFenBaoID, 0 ,$PayMoney, $order_id, 1, $tjAppId);
SAddData($tongjiData);
exit(json_encode(array("ret"=>0,"msg"=>"")));

function tongjiData($gameId, $accountId, $serverId, $channel, $lev=0, $payMoney, $orderId, $isNew =1, $appId ){
	$tongjiArr = array();
	$tongjiArr['accountid'] = $accountId;
	$tongjiArr['serverid'] = $serverId;
	$tongjiArr['channel'] = $channel;
	$tongjiArr['lev'] = $lev;
	$tongjiArr['money'] = $payMoney;
	$tongjiArr['orderid'] = $orderId;
	$tongjiArr['is_new'] = $isNew;
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
function _gen_safe_sign($_POST, $appkey, &$query_string2=null) {
    ksort($_POST);
    $query_string = array();
    $query_string2 = array();
    foreach ($_POST as $key => $val)
    {
        if($key == "sig" || $key == "sign") {
            continue;
        }
        array_push($query_string, $key . '=' . $val);
        array_push($query_string2, $key . '=' . rawurlencode($val));
    }
    $query_string = join('&', $query_string);
    $query_string2 = join('&', $query_string2);
    $sign = md5($appkey. $query_string);
    $query_string2 .= "&sign=".$sign;
    return $sign;
}
?>