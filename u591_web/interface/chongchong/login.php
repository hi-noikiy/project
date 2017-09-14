<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","chongchong_login_all_",  "post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$game_id = $_REQUEST['game_id'];
$token = $_REQUEST['token'];
$appUid = $_REQUEST['uid'];

$url ='http://android-api.ccplay.com.cn/api/v2/payment/checkUser';

if(!$game_id || !$token || !$appUid){
    write_log(ROOT_PATH."log","chongchong_login_error_log_","params error. get=$get, post=$post, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}
$data = array();
$data['token'] = $token;
$result = https_post($url, $data);
if($result == 'success'){
	$userId = $appUid;
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$channel_account=mysqli_real_escape_string($conn,$userId.'@chongchong');
	$username = rand(10000,99999).time().'@chongchong';
	$sql = "select id from account where channel_account = '$channel_account'";
	$query = mysqli_query($conn, $sql);
	$result=array();
	if($query){
		$result = @mysqli_fetch_assoc($query);
	}else{
		write_log(ROOT_PATH."log","chongchong_login_error_log_","sql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
		write_log(ROOT_PATH."log","new_account_chongchong_log_","chongchong new login. get=$get, "."return=1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}else{
		write_log(ROOT_PATH."log","chongchong_login_error_log_", "sql error. sql=$sql_game,  ".mysqli_error($conn)." get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
} else {
	write_log(ROOT_PATH."log","chongchong_login_error_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
?>