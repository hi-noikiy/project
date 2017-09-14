<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","yijie_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$customData_arr = explode('_', $_GET['uid']);
$sdk = $customData_arr[1];
$app = $customData_arr[0];
$uin = urlencode($customData_arr[2]);
$sess = urlencode($customData_arr[3]);
$gameId = $_REQUEST['gameid'];

if(!$sdk || !$app || !$uin || !$sess || !$gameId){
    write_log(ROOT_PATH."log","yijie_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}


$signstr = "sdk=$sdk&app=$app&uin=$uin&sess=$sess";

$url = "http://sync.1sdk.cn/login/check.html?".$signstr;
$rdata = https_post($url, $data);

write_log(ROOT_PATH."log","yijie_result_log_","url=$url;result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata == 0){
	SetConn(81);
	$userid = $uin;
	$channel_account=mysql_escape_string($userid.'@yijie');
	$username = rand(10000,99999).time().'@yijie';
	$sql = " select id from account where channel_account = '$channel_account'";
	$query=mysql_query($sql);
	$result=array();
	if($query){
		$result=mysql_fetch_assoc($query);
	}else{
		write_log(ROOT_PATH."log","yijie_login_error_log_"," 数据库异常,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	if($result){
		$insert_id = $result['id'];
		exit("0 $insert_id");
	}
	$insert_id='';
	$password=random_common();
	$reg_time=date("ymdHi");
	$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
	mysql_query($sql_game);
	$insert_id = mysql_insert_id();
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_yijie_log_","yijie new account ,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
}

write_log(ROOT_PATH."log","yijie_login_error_","result=$rdata, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');