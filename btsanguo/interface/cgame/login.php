<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","cgame_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userid = $_REQUEST['uid'];
$token = $_REQUEST['token'];
$gameId = $_REQUEST['gameid'];

if(!$userid || !$token || !$gameId){
    write_log(ROOT_PATH."log","cgame_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}

$appid = $key_arr[$gameId]['appid'];
$appkey = $key_arr[$gameId]['appkey'];

$sign = strtolower(md5($appid.$userid.$token.$appkey)); 

$signstr = "userid=$userid&appid=$appid&token=$token&sign=$sign";

$url = "http://i.union.sifuba.net/api/check_token.php?".$signstr;
$rdata = https_post($url, $data);

write_log(ROOT_PATH."log","cgame_result_log_","url=$url;result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata == 'success'){
	SetConn(81);
	$channel_account=mysql_escape_string($userid.'@cgame');
	$username = rand(10000,99999).time().'@cgame';
	$sql = " select id from account where channel_account = '$channel_account'";
	$query=mysql_query($sql);
	$result=array();
	if($query){
		$result=mysql_fetch_assoc($query);
	}else{
		write_log(ROOT_PATH."log","cgame_login_error_log_"," 数据库异常,get=$get, ".date("Y-m-d H:i:s")."\r\n");
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
		write_log(ROOT_PATH."log","new_account_cgame_log_","cgame new account ,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
}

write_log(ROOT_PATH."log","cgame_login_error_","result=$rdata, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');