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
write_log(ROOT_PATH."log","pc_callback_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$sign = $_REQUEST["sign"];

$extendsInfo = $_REQUEST['gameOrderId']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$serverId = $extendsInfoArr[1];
$accountId = $extendsInfoArr[2];
$type = $extendsInfoArr[3];
global $key_arr;
$key = $key_arr[$gameId]['key'];
$data = $_REQUEST;
unset($data['sign']);
ksort($data);
$mdstr = implode('', $data).$key;
$data['sign'] = strtolower(md5($mdstr));
$errarr = array(
		'0'=>'充值成功',
		'1'=>'订单重复',
		'-1'=>'参数不全',
		'-2'=>'签名错误',
		'-3'=>'用户不存在',
		'-4'=>'请求超时',
);
$err['data'] = array();
if($sign != $data['sign']) {
    write_log(ROOT_PATH."log","pc_callback_error_",$mdstr.",sign error, data=".json_encode($data).','.date("Y-m-d H:i:s")."\r\n");
    $err['errno'] = -2;
	$err['errmsg'] = $errarr[$err['errno']];
    exit(json_encode($err));
}

$conn = SetConn(88);
$orderId = $data['gameOrderId'];
$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
$query = mysqli_query($conn, $sql);
$result = @mysqli_fetch_array($query);
if($result['rpCode']==1 || $result['rpCode']==10){
	$err['errno'] = 1;
	$err['errmsg'] = $errarr[$err['errno']];
    exit(json_encode($err));
}
$payMoney = intval($data['money']);
if(!$result){
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
    $sql_account = "select  NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
    $query_account = mysqli_query($conn, $sql_account);
    $result_account = @mysqli_fetch_assoc($query_account);
    if(!$result_account['NAME']){
        write_log(ROOT_PATH."log","pc_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
        $err['errno'] = -3;
		$err['errmsg'] = $errarr[$err['errno']];
    	exit(json_encode($err));
    }else{
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];
    }
    $conn = SetConn(88);
    $Add_Time=date('Y-m-d H:i:s');
    $sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
    $sql=$sql." VALUES (177, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
    if (mysqli_query($conn,$sql) == False){
        write_log(ROOT_PATH."log","pc_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
        $err['errno'] = -4;
		$err['errmsg'] = $errarr[$err['errno']];
    	exit(json_encode($err));
    }
    //write_log(ROOT_PATH."log","pc_callback_info_","OK".date("Y-m-d H:i:s")."\r\n");
    WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId);
    //统计数据
    global $tongjiServer;
    $tjAppId = $tongjiServer[$gameId];
    sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
    $err['errno'] = 0;
	$err['errmsg'] = $errarr[$err['errno']];
    exit(json_encode($err));
}
$err['errno'] = 0;
$err['errmsg'] = $errarr[$err['errno']];
exit(json_encode($err));