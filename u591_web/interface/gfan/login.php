<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","gfan_login_all_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
//$_REQUEST_str = 'a:3:{s:11:"encrypt_str";s:32:"S153phixxLd8wQq3W5vCJLgYlik/GM1V";s:1:"p";s:32:"S153phixxLd8wQq3W5vCJLgYlik/GM1V";s:7:"game_id";s:1:"5";}';
//$_REQUEST = unserialize($_REQUEST_str);
$game_id = $_REQUEST['game_id'];
$encrypt_str = $_REQUEST['encrypt_str'];

if(!$game_id || !$encrypt_str){ 
	write_log(ROOT_PATH."log","gfan_login_error_log_","param error! token=$encrypt_str, game_id=$game_id, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}

$url = "http://api.gfan.com/uc1/common/verify_token";
$formvars['token'] = $encrypt_str;

$data['token'] = $encrypt_str;
$result = https_post($url,$data);

write_log(ROOT_PATH."log","gfan_login_result_log_"," url=$url, encrypt_str=$encrypt_str, result=$result, ".date("Y-m-d H:i:s")."\r\n");
$resultArr = json_decode($result, true);

if(isset($resultArr['uid']) && isset($resultArr['resultCode']) && $resultArr['resultCode']==1){
	$uid = $resultArr['uid'];
	$resultCode = $resultArr['resultCode'];

	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$channel_account=mysqli_real_escape_string($conn,$uid.'@gfan');
	$username = rand(10000,99999).time().'@gfan';
	$sql = "select id from account where channel_account='$channel_account'";
	$query= mysqli_query($conn, $sql);
	$result=array();
	if($query){
		$result = @mysqli_fetch_assoc($query);
	}else{
		write_log(ROOT_PATH.'log', 'gfan_login_error_log_',"sql=$sql, mysql error! ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	if($result){
		$insert_id = $result['id'];
		exit("0 $insert_id");
	}
	$insert_id = '';
	$password = random_common();
	$reg_time = date("ymdHi");
	$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
	mysqli_query($conn, $sql_game);
	$insert_id = @mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_gfan_log_","new acccount login! post=$post, get=$get, "."return=1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
}else{
	write_log(ROOT_PATH."log","gfan_login_error_log_","url=$url, result=$result, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}
exit("999 0");
?>