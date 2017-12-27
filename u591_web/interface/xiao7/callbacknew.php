<?php
include_once 'config.php';
include_once 'xiao7_function.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xiao7_callbacknew_info_all_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$gameOrderid = $_REQUEST['game_orderid'];

$post_data = $_REQUEST;

$gameOrderIdArr = explode('_', $gameOrderid);
$gameId = $gameOrderIdArr[0];
$serverId = $gameOrderIdArr[1];
$accountId = $gameOrderIdArr[2];
$type = $gameOrderIdArr[3];
$isgoods = intval($gameOrderIdArr[4]);
global $key_arr;
$publickey = ($type == 'android') ? $key_arr[$gameId]['android']['publickey'] : $key_arr[$gameId]['ios']['publickey'];

$post_sign_data = base64_decode($post_data["sign_data"]);
//$post_sign_data = $post_data["sign_data"];
//因为sign_data是不加入签名里面的
unset($post_data["sign_data"]);
//按照参数名称的正序排序
ksort($post_data);
//对输入参数根据参数名排序，并拼接为key=value&key=value格式；对于这里是使用php中的函数http_build_query来实现转换成querystring字符串
//对于http_build_query会对键值对进行urlencode转码，所以得到的$sourcestr的值是经过urlencode转码的
$sourcestr=urldecode(http_build_query($post_data));
//对数据进行验签，注意对公钥做格式转换
$publicKey = ConvertPublicKey($publickey);
$verify = Verify($sourcestr, $post_sign_data,$publicKey);
//判断签名是否是正确
if($verify!=1){
	write_log(ROOT_PATH."log","xiao7_callbacknew_error_","验证失败，{$sourcestr},{$post_sign_data}，{$verify}，post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	ReturnResult("sign_data_verify_failed");
}
//对加密的encryp_data进行解密
$post_encryp_data_decode = base64_decode($post_data["encryp_data"]);
$decode_encryp_data = PublickeyDecodeing($post_encryp_data_decode,$publicKey);
//这里是对加密数据解密，成功解密后将会得到包含游戏订单ID(game_orderid)、支付金额(pay)、支付状态(payflag)的数组
$arr=decode_http_build_query($decode_encryp_data);
//下面这里是比较解密后的订单号是否与通过POST传递过来的订单号一致
$jsonArr = json_encode($arr);
write_log(ROOT_PATH."log","xiao7_callbacknew_result_","result=$jsonArr, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(isset($arr['game_orderid']) && $arr['game_orderid']==$_POST['game_orderid']){
	$conn = SetConn(88);
	$orderId = $gameOrderid;
	$sql = "select  rpCode  from web_pay_log where OrderID = '$orderId'";
	$query = mysqli_query($conn, $sql);
	$result = @mysqli_fetch_array($query);
	if($result['rpCode']==1 || $result['rpCode']==10){
		ReturnResult("success");
	}
	if(!$result){
		$payMoney = $arr['pay_price'];
        global $accountServer;
		$accountConn = $accountServer[$gameId];
		$conn = SetConn($accountConn);
		$sql_account = "select NAME,dwFenBaoID,clienttype,channel_account from account where id = '$accountId' limit 1;";
		$query_account = @mysqli_query($conn, $sql_account);
		$result_account = @mysqli_fetch_assoc($query_account);
		if(!$result_account['NAME']){
			write_log(ROOT_PATH."log","xiao7_callbacknew_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
			ReturnResult("failed:order error");
		}
		if($result_account['channel_account'] != $arr['guid'].'@xiao7'){
			write_log(ROOT_PATH."log","xiao7_callbacknew_error_", "account is not correct. {$result_account['channel_account']} != {$arr['guid']}@xiao7  ".date("Y-m-d H:i:s")."\r\n");
			ReturnResult("failed:guid error");
		}
        $PayName = $result_account['NAME'];
        $dwFenBaoID = $result_account['dwFenBaoID'];
        $clienttype = $result_account['clienttype'];

		$conn = SetConn(88);
		$Add_Time=date('Y-m-d H:i:s');
		$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
		$sql=$sql." VALUES (118, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
	
		if (mysqli_query($conn,$sql) == False){
			write_log(ROOT_PATH."log","xiao7_callbacknew_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
			ReturnResult("failed:order error");
		}
        WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
		//统计数据
        global $tongjiServer;
		$tjAppId = $tongjiServer[$gameId];
        sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
		ReturnResult("success");
	}
}
ReturnResult("encryp_data_decrypt_failed");
?>