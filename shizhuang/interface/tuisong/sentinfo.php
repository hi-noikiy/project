<?php
error_reporting(0);
include_once 'config.php';
$post = serialize($_POST);
write_log(ROOT_PATH."log","system_tuisong_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$accountid = $_POST['accountid'];
$num = $_POST['num'];
$sign = $_POST['sign'];
$mdString = 'fu,djf,jk7g.fk*o3l';
$mysign = md5($accountid.$num.$mdString);
if($sign != $mysign){
	write_log(ROOT_PATH."log","system_tuisong_error_","sign error,post=$post ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
if(!$accountid || !$num){
	write_log(ROOT_PATH."log","system_tuisong_error_","param is null,post=$post ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
$conn = ConnServer2('122.112.211.43', 'payor', 'u591*hainiu', 'sdk');
if($conn == false){
	write_log(ROOT_PATH."log","system_tuisong_error_","sdk connect error,post=$post ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
$sql2 = "select regid,devicetype from push_regid where accountid='$accountid' limit 1";
$query2 = @mysqli_query($conn,$sql2);
$result = @mysqli_fetch_assoc($query2);
if(!isset($result['regid'])){
	write_log(ROOT_PATH."log","system_tuisong_error_","$sql2,post=$post ,".mysqli_error($conn).','.date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
$reg_id = $result['regid'];
$message = '您有新邮件，快去看看有什么惊喜！';
$type = $result['devicetype'];

$apikey = $key_arr[$type]['apiKey'];
$secret = isset($key_arr[$type]['secret'])?$key_arr[$type]['secret']:0;
if(send_notify($type,$reg_id,$message,$apikey,$secret,$num)){
	write_log(ROOT_PATH."log","system_tuisong_success_","post=$post ".date("Y-m-d H:i:s")."\r\n");
	exit( 'success');
}else{
	write_log(ROOT_PATH."log","system_tuisong_error_","post=$post ".date("Y-m-d H:i:s")."\r\n");
	exit( 'fail');
}

function ConnServer2($db_host, $db_user, $db_pass, $db_database){
	$db = @mysqli_connect($db_host,$db_user,$db_pass, $db_database);
	if(!$db){
		$db = @mysqli_connect($db_host,$db_user,$db_pass, $db_database);
	}
	if(!$db){
		return false;
	}
	return $db;
}