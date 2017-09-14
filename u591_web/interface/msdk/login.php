<?php
include_once 'config.php';

$ip = getIP_front();

$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","msdk_login_info_",date('Y-m-d H:i:s') . " post=$post,get=$get,$ip, ".date("Y-m-d H:i:s")."\r\n");

$appKey = trim($_REQUEST['appkey']);
$openid = trim($_REQUEST['openid']);
$gameid = intval($_REQUEST['game_id']);

if(!$appKey || !$openid || !$gameid){
    write_log(ROOT_PATH."log","msdk_login_error_"," parameter error ! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}

$ts = time();
$sig = md5($appKey.$ts);
$appid = $key_arr[$gameid]['appid'];

$url= "http://msdktest.qq.com/relation/qqfriends_detail/?timestamp=$ts&appid=$appid&sig=$sig&openid=$openid&encode=1";
$param = $key_arr[$gameid];
$result = SnsNetwork:: makeRequest($url,json_encode($param));
$rsStr = json_encode($result);
write_log(ROOT_PATH."log","msdk_login_result_", date('Y-m-d H:i:s') . " result=$rsStr, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($result['result'] == true){
	$msg = json_decode($result['msg'], true);
	if($msg['ret'] != 0){
		write_log(ROOT_PATH."log","msdk_login_error_"," sign error ! rs=$rsStr, url=$url, ".date("Y-m-d H:i:s")."\r\n");
		exit("4 0");
	}
	
	$uid = $msg['lists']['openid'];
	$conn = SetConn(81);
	$channel_account=mysqli_real_escape_string($conn,$uid.'@msdk');
	$username = time().'@msdk';
	$sql = " select id from account where channel_account = '$channel_account'";
	if(false == $query=mysqli_query($conn,$sql)){
		write_log(ROOT_PATH."log","msdk_login_error_log_"," sql error!, sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	$rs = mysqli_fetch_assoc($query);
	if($rs){
		$insert_id = $rs['id'];
		exit("0 $insert_id");
	}
	$insert_id = '';
	$password = random_common();
	$reg_time = date("ymdHi");
	$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
	if(mysqli_query($conn,$sql_game) == false){
		write_log(ROOT_PATH."log","msdk_login_error_log_"," sql error!, sql=$sql_game, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	$insert_id = mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_msdk_log_","msdk new account login! return= 1 $insert_id ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
} else {
	write_log(ROOT_PATH."log","msdk_login_error_"," curl error ! rs=$rsStr, url=$url, ".date("Y-m-d H:i:s")."\r\n");
	exit("999 0");
}
?>
