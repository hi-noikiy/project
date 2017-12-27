<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

$str = "post=$post,get=$get";
write_log(ROOT_PATH."log","360_callback_log_",$str." ".date("Y-m-d H:i:s")."\r\n");
$success = json_encode(array(
	'status'=>'ok',	
	'delivery'=>'success',
	'msg'=>''		
));
$fail = json_encode(array(
		'status'=>'error',
		'delivery'=>'other',
		'msg'=>''
));
$gateway_flag = $_REQUEST['gateway_flag'];
if($gateway_flag != 'success'){
	write_log(ROOT_PATH."log","360_callback_error_","status is not success,".$str." ".date("Y-m-d H:i:s")."\r\n");
	exit($fail);
}

$sign = $_REQUEST['sign'];
$app_order_id = $_REQUEST['app_order_id'];
$app_order_id_arr = explode("_", $app_order_id);
$gameId = $app_order_id_arr[0];
$serverId = $app_order_id_arr[1];
$accountId = $app_order_id_arr[2];
$type = $app_order_id_arr[3];
$type = $app_order_id_arr[3];
$isgoods = $app_order_id_arr[4];
$data = $_REQUEST;

unset($data['sign'],$data['sign_return']);
foreach($data as $k=>$v)
{
	if(empty($v)){
		unset($data[$k]);
	}
}
$app_secret = $key_arr[$gameId][$type]['app_secret'];
ksort($data);//对参数按照 key 进行排序
$sign_str = implode('#',$data).'#'.$app_secret;//第四步
$sign_my = md5($sign_str);
write_log(ROOT_PATH."log","360_callback_result_",$sign_str.",$sign_my,".$str." ".date("Y-m-d H:i:s")."\r\n");
if($sign_my==$sign){//验证成功
	$orderId = $app_order_id;
    $conn = SetConn(88);
    $sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
    $query = mysqli_query($conn, $sql);
    $result = @mysqli_fetch_array($query);
    if($result['rpCode']==1 || $result['rpCode']==10){
    	write_log(ROOT_PATH."log","xiaomi_callback_error_",$orderId."is pay success,  ".date("Y-m-d H:i:s")."\r\n");
    	exit($fail);
    }
    $payMoney = $data['amount']/100;
    //获取账号信息
    global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","xiaomi_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        exit($fail);
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (43, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","xiaomi_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        exit($fail);
    }
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    exit($success);

}else{
    $str=" 验证失败，get=$get,  ".date("Y-m-d H:i:s")."\r\n";
    write_log(ROOT_PATH."log","360_callback_error_log_",$str);
}



?>
