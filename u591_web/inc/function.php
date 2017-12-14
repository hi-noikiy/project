<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 登陆接口、支付回调函数
 * ==============================================
 * @date: 2016-5-6
 * @author: Administrator
 * @return:
 */
// 获取ip
function getIP_front() {
	$ip = getenv('REMOTE_ADDR');
	$ip_ = getenv('HTTP_X_FORWARDED_FOR');
	if (($ip_ != "") && ($ip_ != "unknown"))
		$ip = $ip_;
	return $ip;
}
// 写日志:路径,文件名,内容
function write_log($dirName, $logName, $str) {
	$path_name = $dirName . "/" . date("ym");
	if (!is_dir($path_name))
		@mkdir ( $path_name, 0777);
	$fs = fopen ( $path_name . "/" . $logName . date ("ymd") . ".txt", "a" );
	fwrite ( $fs, $str );
	fclose ( $fs );
}
// 充值成功写入游戏库
// 参数说明：充值方式,服务区ID,充值类型,帐号ID,定单号
function WriteCard_money($tabType, $ServerID, $money, $PayID, $OrderID, $type=8, $i=0,$wap=0,$id_buygoods=0) {
	$i++;
    $sid = togetherServer($ServerID);
    $table = betaSubTable($sid, 'u_card', 1000);
    $conn = SetConn($sid);

	if($conn == false){
		gameOrder($OrderID); //更新订单状态
        write_log (ROOT_PATH."log", "card_err_", "serverId=$ServerID, game mysql connect error. ".date ("Y-m-d H:i:s")."\r\n");
        return ;
	}

	$time_stamp = date ('ymdHi');
	// 判断定单号是否重复
	$sql = "select count(*) as count from $table where ref_id='$OrderID' limit 1;";
	$query = @mysqli_query ($conn, $sql);
	if($query == false){
		write_log (ROOT_PATH."log", "card_err_", "sql=$sql, ".mysqli_error($conn)." ".date ("Y-m-d H:i:s")."\r\n");
		gameOrder($OrderID); //更新订单状态
		return ;
	}
	$rows = @mysqli_fetch_assoc($query);
	if ($rows['count'] == 0) {
		$sql = "insert into $table(data,account_id,ref_id,time_stamp,used,type,server_id";
		$mysql = " values('$money',$PayID,'$OrderID',$time_stamp,0,'$type','$ServerID'";
		if($wap == 1){
			$sql .= ',wap_flag';
			$mysql .= ",'$wap'";
		}
		if($id_buygoods){
			$sql .= ',id_buygoods';
			$mysql .= ",'$id_buygoods'";
		}
		$sql .= ')';
		$mysql .= ')';
		$sql = $sql . $mysql;
		
		if (mysqli_query ($conn, $sql ) == false) {
			write_log (ROOT_PATH."log", "card_err_", "sql=$sql, ".mysqli_error($conn)." ".date ("Y-m-d H:i:s")."\r\n");
			gameOrder($OrderID); //更新订单状态
			//执行失败再次请求
			if($i == 1){
				WriteCard_money($tabType, $ServerID, $money, $PayID, $OrderID, $type = 8, $i,$wap,$id_buygoods);
			}
		} else {
			write_log (ROOT_PATH."log", "card_true_", "ServerID=$ServerID,sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
		}
	}
}

function gameOrder($orderId, $isUc = 1){
	$conn = SetConn (88); // 连接u591数据库
	$sql = "update web_pay_log set IsUC=$isUc where OrderID='$orderId'";
	@mysqli_query ($conn, $sql);
	@mysqli_close($conn);
}

// 通知客户端消息(充值卡)
// PayStat：0=成功消息,1=失败消息
function WritePayMsg($PayStat, $ServerID, $PayID, $OrderID, $PayMoney, $game_id = 1) {

}
// 函数updatePoints加积分,每次充值的时候调用,前后台共用
// $PayID玩家账号ID
// $PayMoney充值金额 1元=1分制
// $way支付方式短信(dx)和非短信(f_dx)
// $oid订单号
// $front判断前后台
function updatePoints($PayID, $PayMoney, $way, $oid, $front = '') {

}
// 上升等级更新 updateRankUp,前后台共用
// $PayID玩家账号ID
function updateRankUp($PayID, $way, $front = '') {

}
function data_check($val) {
	if (is_array ( $val )) {
		foreach ( $val as $k => $v )
			$val [$k] = data_check ( $v );
	} else {
		if (! get_magic_quotes_gpc ()) {
			$val = addslashes ( $val );
		}
		$dstr = 'select|insert|update|delete|union|into|load_file|outfile';
		$val = eregi_replace ( $dstr, '', $val );
	}
	return $val;
}

function random_common() {
	$hash = '';
	$array = array ('~','!','@','#','$','%','^','&','*','(');
	shuffle ( $array );
	for($i = 0; $i < 3; $i ++) {
		$hash .= uniqid ( rand () ) . $array [$i];
	}
	return md5 ( md5 ( $hash ) );
}
function common_json_post($url,$data ){
	$curl = curl_init($url); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_HTTPHEADER,
			array(
					'Content-Type: application/json' ,
					'Content-Length: ' . strlen($data)
			));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
	$tmpInfo = curl_exec($curl);
	if (curl_errno($curl)) {
		return 'Errno' .curl_error($curl);
	}
	curl_close($curl);
	return $tmpInfo;
}
function https_post($url, $data, $i = 0) {
	$i++;
	$str = '';
	if ($data) {
		foreach ( $data as $key => $value ) {
			$str .= $key . "=" . $value . "&";
		}
	}
	$curl = curl_init ( $url ); // 启动一个CURL会话
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
	// curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
	curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
	if ($str) {
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $str ); // Post提交的数据包
	}
	curl_setopt ( $curl, CURLOPT_TIMEOUT, 5 ); // 设置超时限制防止死循环
	// curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
	// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec ($curl);
	$Err = curl_error($curl);
	if (false === $tmpInfo || !empty($Err)) {
		if($i == 1)
			return https_post ($url, $data, $i);
		curl_close ($curl);
		return $Err;
	}
	curl_close ($curl);
	return $tmpInfo;
}
function Sign($data){
	ksort($data);
	$md5Str = http_build_query($data);
	$mySign = md5($md5Str.'0dbddcc74ed6e1a3c3b9708ec32d0532');
	return $mySign;
}
//分表
function betaSubTable($serverId, $table, $sum){
    $suffix = $serverId%$sum;
    $s = sprintf('%03d', $suffix);
    return $table.$s;
}

//统计数据处理
function SAddData($data){
	$url = 'http://poketj.u591776.com:8080/index.php/ApiPay/PaylogProcess';
	$jsonData = base64_encode(json_encode($data));
	$postData = array();
	$postData['data'] = $jsonData;
	$postData['sign'] = Sign($data);

	$rs = https_post($url, $postData);
	$rows = json_decode($rs, true);
	if(is_array($rows) && $rows['errcode'] != 0){
        write_log(ROOT_PATH."log","SAddData_error_","result=$rs, ".date("Y-m-d H:i:s")."\r\n");
	}
}

function sendTongjiData($gameId, $accountId,$serverId,$channel,$lev=0,$money,$orderId,$isNew=1,$appId,$isPay=0, $isbt = '2' ,$created = 0){
    $tongjiArr = array();
    $tongjiArr['accountid'] = $accountId;
    $tongjiArr['serverid'] = $serverId;
    $tongjiArr['channel'] = $channel;
    $tongjiArr['lev'] = $lev;
    $tongjiArr['money'] = $money;
    $tongjiArr['orderid'] = $orderId;
    $tongjiArr['is_new'] = $isNew;
    $tongjiArr['is_pay'] = $isPay;
    if(in_array($isbt, array(0,1))){
    	$tongjiArr['isbt'] = $isbt;
    }
    $conn = SetConn(88);
    if($conn == false){
        write_log(ROOT_PATH."log","SAddData_error_","web mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
        return false;
    }
    $sql = "select count(id) as count from web_pay_log where PayID=$accountId and game_id=$gameId limit 1;";
    $query = @mysqli_query($conn, $sql);
    $rows = @mysqli_fetch_array($query);
    if($rows['count'] > 1)
        $tongjiArr['is_new'] = 0;
    $tongjiArr['created_at'] = time();
    if($created){
    	$tongjiArr['created_at'] = $created;
    }
    $tongjiArr['appid'] = $appId; //$tongjiServer[$gameId];
    //发送数据
    SAddData($tongjiArr);
    return true;
}
?>