<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * pp助手登陆接口
 * ==============================================
 * @date: 2015-7-1
 * @author: Administrator
 * @return:
 * 	"2 0"      参数异常
 *   '3 0'      sql异常
 *   "4 0"      验证出错
 *   "999 0"    未知错误
 *   "0 $insert_id"       二次登陆返回
 *   "1 $insert_id"       首次登陆返回
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$request = serialize($_REQUEST);

$sid=$_REQUEST['sid'];
$gameId=$_REQUEST['game_id'];

$app_id = $key_arr[$gameId]['app_id'];
$app_key = $key_arr[$gameId]['app_key'];

if(!$sid || !$gameId || !$app_id || !$app_key) {
	write_log(ROOT_PATH."log","pp_new_login_err_log_", "Parameters error: sid=$sid, gameid=$gameId, app_id=$app_id, app_key=$app_key ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}

write_log(ROOT_PATH."log","pp_new_login_log_", "post=$post, get=$get, request=$request ".date("Y-m-d H:i:s")."\r\n");
	
$data = array(
		'id' => time(),
		'service' => 'account.verifySession',
		'game' => array(
				'gameId' => $app_id
		),
		'data' => array(
				'sid' => $sid,
		),
		'encrypt' => 'MD5',
		'sign' => md5("sid=".$sid.$app_key)
);

$url='http://passport_i.25pp.com:8080/account?tunnel-command=2852126760';
$data= json_encode($data);
$result = http_post($url, $data);
//从返回的JSON数据中获取accountId作为唯一标识
write_log(ROOT_PATH."log","pp_new_login_result_log_"," retult = $result, ".date("Y-m-d H:i:s")."\r\n");

$resultArr = json_decode($result, true);

$code= $resultArr['state']['code'];
$nickName = $resultArr['data']['nickName'];
$accountId = $resultArr['data']['accountId'];
if($code==1 && !empty($nickName) && !empty($accountId)) {
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	$_name = $accountId.'@25pp';
	$sql = "select id,NAME,channel_account from account where NAME = '$_name'";
	$query = mysqli_query($conn, $sql);
	if(!$query) {
		write_log(ROOT_PATH."log","pp_new_login_error_log_"," sql = $sql,".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	$row= @mysqli_fetch_assoc($query);
	if($row['id']){
		$insert_id = $row['id'];
		write_log(ROOT_PATH."log","old_account_pp_new_log_","pp助手老登陆 , accountId=$insert_id, return= 0 $insert_id ".date("Y-m-d H:i:s")."\r\n");
		exit("0 $insert_id");
	} else {
		$insert_id='';
		$username = $accountId.'@25pp';
		$password = random_common();
		$reg_time = date("ymdHi");
		$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$username')";
		$query = mysqli_query($conn, $sql_game);
		if(!$query) {
			write_log(ROOT_PATH."log","pp_new_login_error_log_"," sql = $sql,".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
			exit('3 0');
		}
		$insert_id = mysqli_insert_id($conn);
		write_log(ROOT_PATH."log","new_account_pp_new_log_","pp助手新登陆 , accountId=$insert_id, return= 1 $insert_id ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
		}
} else {
	write_log(ROOT_PATH."log","pp_new_login_error_log_"," url=$url, data=$data, result=$result ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}
function http_post($url, $data) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json;charset=utf-8'));
	$rtn = curl_exec($ch);
	if(@$errno = curl_errno($ch)) {
		throw new Exception(curl_error($ch), $errno);
	}
	curl_close($ch);
	return $rtn;
}
?>