<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","miyu_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userid = $params['userid'] = $_REQUEST['uid'];
$token = $params['sessionid'] = $_REQUEST['token'];
$gameId = $_REQUEST['gameid'];
$params['gameid'] = $key_arr[$gameId]['app_id'];
if(!$userid || !$token || !$gameId){
    write_log(ROOT_PATH."log","miyu_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$key = $key_arr[$gameId]['app_key'];
ksort($params);//对参数字母排序
$source = http_build_query($params).':'.$key;//拼接出源串
$params['sign'] = strtolower(md5($source));

$url = "http://sdkdrive.miyugame.com/CheckLogin";
$rdata = https_post($url, $params);
$rdata = json_decode($rdata,true);
write_log(ROOT_PATH."log","miyu_result_log_","params=".json_encode($params).";result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata['result'] == '1'){
	//write_log(ROOT_PATH."log","miyu_result_result_log_","result={$rdata['result']}, ".date("Y-m-d H:i:s")."\r\n");
	SetConn(81);
	$channel_account=mysql_escape_string($userid.'@miyu');
	$username = rand(10000,99999).time().'@miyu';
	$sql = " select id from account where channel_account = '$channel_account'";
	$query=mysql_query($sql);
	$result=array();
	if($query){
		$result=mysql_fetch_assoc($query);
	}else{
		write_log(ROOT_PATH."log","miyu_login_error_log_"," 数据库异常,get=$get, ".date("Y-m-d H:i:s")."\r\n");
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
		write_log(ROOT_PATH."log","new_account_miyu_log_","miyu new account ,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
}

write_log(ROOT_PATH."log","miyu_login_error_","result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');