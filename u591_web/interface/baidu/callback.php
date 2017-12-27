<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/19
 * Time: 上午10:02
 */

if (!function_exists('json_decode')){
	exit('您的PHP不支持JSON，请升级您的PHP版本。');
}

/**
 * 应用服务器接收服务器端发过来发货通知的接口DEMO
 * 当然这个DEMO只是个参考，具体的操作和业务逻辑处理开发者可以自由操作
 */
/*
 * 这里的AppId和Secretkey是我们自己做测试的
 * 开发者可以自己根据自己在平台上创建的具体应用信息进行修改
 */
include 'config.php';
global $key_arr;
$AppId = $key_arr[8]['android']['appid'];
$Secretkey = $key_arr[8]['android']['appsecret'];

$Res = notify_process($key_arr[8]);

print_r($Res);
write_log(ROOT_PATH."log","baidu_callback_info_","{$Res}".date("Y-m-d H:i:s")."\r\n");
exit();
/**
 * 此函数就是接收服务器那边传过来传后进行各种验证操作处理代码
 * @param int $AppId 应用Id
 * @param string $Secretkey 应用Secretkey
 * @return json 结果信息
*/
function notify_process($config = array()){
	header("Content-type: text/html; charset=utf-8");
	$Result = array();//存放结果数组
	$OrderSerial='';
	$CooperatorOrderSerial='';
	$Sign='';
	$Content='';

	//2.读取POST流方式获取请求参数
	$inputParams = file_get_contents('php://input');
	write_log(ROOT_PATH."log","baidu_callback_info_","post=$inputParams, ".date("Y-m-d H:i:s")."\r\n");
	$connectorParam = "&";
	$spiltParam="=";
	if(!empty($inputParams)){
		if(strpos($inputParams,$connectorParam) && strpos($inputParams,$spiltParam)){
			$list=explode($connectorParam,$inputParams);
			//print(count($list));
			for($i=0;$i<count($list);$i++){
				$kv=explode($spiltParam,$list[$i]);
				if(count($kv)>1){
					if($kv[0]=="OrderSerial"){
						$OrderSerial=$kv[1];
					}else if($kv[0]=="CooperatorOrderSerial"){
						$CooperatorOrderSerial=$kv[1];
					}else if($kv[0]=="Sign"){
						$Sign=$kv[1];
					}else if($kv[0]=="Content"){
						$Content=urldecode($kv[1]);	//读取POST流的方式需要进行UrlDecode解码操作
						//print($Content);
					}
				}
			}
		}
	}
	$extendsInfo = $CooperatorOrderSerial; //提取拓展信息
	$extendsInfoArr = explode('_', $extendsInfo);
	$gameId = $extendsInfoArr[0];
	$serverId = $extendsInfoArr[1];
	$accountId = $extendsInfoArr[2];
	$type = $extendsInfoArr[3];
	$isgoods = $extendsInfoArr[4];
	$AppId = $config[$type]['appid'];
	$Secretkey = $config[$type]['appsecret'];
	$Result["AppID"] =  $AppId;
	$Result["Content"] =  "";
	//参数检测
	if(empty($OrderSerial)||empty($CooperatorOrderSerial)||empty($Sign)
			||empty($Content)){
		$Result["ResultCode"] =  91;
		$Result["ResultMsg"] =  urlencode("接收参数失败");
		$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
		$Res = json_encode($Result);
		write_log(ROOT_PATH."log","baidu_callback_error_","接收参数失败, $Res, ".date("Y-m-d H:i:s")."\r\n");
		return urldecode($Res);
	}

	$Result["AppID"] =  $AppId;
	//检测请求数据签名是否合法
	if($Sign != md5($AppId.$OrderSerial.$CooperatorOrderSerial.$Content.$Secretkey)){
		$Result["ResultCode"] =  91;
		$Result["ResultMsg"] =  urlencode("签名错误");
		$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
		$Res = json_encode($Result);
		write_log(ROOT_PATH."log","baidu_callback_error_","签名错误, $Res, ".date("Y-m-d H:i:s")."\r\n");
		return urldecode($Res);
	}

	//base64解码
	$Content=base64_decode($Content);
	//json解析
	$Item=extract(json_decode($Content,true));
	//$UID $MerchandiseName $OrderMoney $StartDateTime $BankDateTime $OrderStatus $StatusMsg $ExtInfo $VoucherMoney
	//print($UID);
	//根据获取到的数据，执行业务处理
	if($OrderStatus != '1'){
		$Result["ResultCode"] =  91;
		$Result["ResultMsg"] =  urlencode("订单状态失败");
		$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
		$Res = json_encode($Result);
		write_log(ROOT_PATH."log","baidu_callback_error_","订单状态失败, $Content, ".date("Y-m-d H:i:s")."\r\n");
		return urldecode($Res);
	}
	$conn = SetConn(88);
	$orderId = $CooperatorOrderSerial;
	$sql = "select rpCode from web_pay_log where OrderID = '$orderId' limit 1;";
	$query = mysqli_query($conn, $sql);
	$result = @mysqli_fetch_array($query);
	if($result['rpCode']==1 || $result['rpCode']==10){
		$Result["ResultCode"] =  1;
		$Result["ResultMsg"] =  urlencode("成功");
		$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	$payMoney = intval($OrderMoney);
	if(!$result){
		global $accountServer;
		$accountConn = $accountServer[$gameId];
		$conn = SetConn($accountConn);
		$sql_account = "select  channel_account,NAME,dwFenBaoID,clienttype  from account where id = '$accountId'";
		$query_account = mysqli_query($conn, $sql_account);
		$result_account = @mysqli_fetch_assoc($query_account);
		if(!$result_account['NAME']){
			$Result["ResultCode"] =  91;
			$Result["ResultMsg"] =  urlencode("游戏账号不存在");
			$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
			$Res = json_encode($Result);
			write_log(ROOT_PATH."log","baidu_callback_error_", "account is not exist.  ".date("Y-m-d H:i:s")."\r\n");
			return urldecode($Res);
		}else{
			$PayName = $result_account['NAME'];
			$dwFenBaoID = $result_account['dwFenBaoID'];
			$clienttype = $result_account['clienttype'];
		}
		if($result_account['channel_account'] != $UID.'@baidu'){
			$Result["ResultCode"] =  91;
			$Result["ResultMsg"] =  urlencode("游戏账号不一致");
			$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
			$Res = json_encode($Result);
			write_log(ROOT_PATH."log","baidu_callback_error_", "uid is error.  ".date("Y-m-d H:i:s")."\r\n");
			return urldecode($Res);
		}
		$conn = SetConn(88);
		$Add_Time=date('Y-m-d H:i:s');
		$sql="insert into web_pay_log (CPID,PayID,PayName,ServerID,PayMoney,OrderID,dwFenBaoID,Add_Time,SubStat,game_id,clienttype, rpCode)";
		$sql=$sql." VALUES (174, $accountId,'$PayName','$serverId','$payMoney','$orderId','$dwFenBaoID','$Add_Time','1','$gameId','$clienttype', '1')";
		if (mysqli_query($conn,$sql) == False){
			$Result["ResultCode"] =  91;
			$Result["ResultMsg"] =  urlencode("数据库连接失败");
			$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
			$Res = json_encode($Result);
			write_log(ROOT_PATH."log","baidu_callback_error_","sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
			return urldecode($Res);
		}
		WriteCard_money(1,$serverId, $payMoney,$accountId, $orderId,8,0,0,$isgoods);
		//统计数据
		global $tongjiServer;
		$tjAppId = $tongjiServer[$gameId];
		sendTongjiData($gameId,$accountId,$serverId,$dwFenBaoID,0,$payMoney,$orderId,1,$tjAppId);
			//返回成功结果
		$Result["ResultCode"] =  1;
		$Result["ResultMsg"] =  urlencode("成功");
		$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	$Result["ResultCode"] =  1;
	$Result["ResultMsg"] =  urlencode("成功");
	$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
	$Res = json_encode($Result);
	return urldecode($Res);

}
