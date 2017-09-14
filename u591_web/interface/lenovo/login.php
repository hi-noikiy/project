<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$file_in = file_get_contents("php://input");
write_log(ROOT_PATH."log","lenovo_login_all_log_","post=$post,get=$get,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");

$token = $_REQUEST['token'];
$realm = $_REQUEST['realm'];
$game_id = $_REQUEST['game_id'];
$url = "http://passport.lenovo.com/interserver/authen/1.2/getaccountid?lpsust=$token&realm=$realm";
$data = array();
$url_result = https_post($url,$data);
$xml_arr = simplexml_load_string($url_result);

write_log(ROOT_PATH."log","lenovo_check_log_"," url=$url,url_result=$url_result, ".date("Y-m-d H:i:s")."\r\n");
if( isset($xml_arr->AccountID) && !empty($xml_arr->AccountID)){
	$id = $xml_arr->AccountID;
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$channel_account=mysqli_real_escape_string($conn,$id.'@lenovo');
	$username = rand(10000,99999).time().'@lenovo';
	$sql = " select id from account where channel_account = '$channel_account'";
	$query=mysqli_query($conn,$sql);
	$result=array();
	if($query){
		$result=mysqli_fetch_assoc($query);
	}else{
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
	mysqli_query($conn,$sql_game);
	$insert_id = mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_lenovo_log_","lenovo new account login ,post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
}else{
	write_log(ROOT_PATH."log","lenovo_error_log_"," url=$url,url_result=$url_result, post=$post,get=$get,file_in=$file_in,  ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}