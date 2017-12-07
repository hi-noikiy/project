<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

$str = "post=$post,get=$get";
write_log(ROOT_PATH."log","360_login_log_",$str." ".date("Y-m-d H:i:s")."\r\n");

$access_token = $_REQUEST['user_token'];
$gameId = $_REQUEST['game_id'];

if(!$access_token||!$gameId){
    write_log(ROOT_PATH."log","360_login_error_"," 参数异常, str=$str ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}


$url_user = "https://openapi.360.cn/user/me.json?access_token=".$access_token."&fields=id,name,avatar,sex,area";
$result_user = https_post($url_user,$data);
$result_user_arr = json_decode($result_user,true);
write_log(ROOT_PATH."log","360_user_result_log_"," result_user=$result_user, url=$url_user,".date("Y-m-d H:i:s")."\r\n");

$id_360 = $result_user_arr['id'];

if($id_360&&is_array($result_user_arr)){
	$memId = $id_360;
	//CP操作,请求成功,用户有效
	global $accountServer;
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	if($conn == false){
		write_log(ROOT_PATH."log","360_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	$channel_account = mysqli_real_escape_string($conn,$memId.'@360');
	$sql = "select id from account where channel_account='$channel_account' limit 1";
	if(false == $query = mysqli_query($conn, $sql)){
		write_log(ROOT_PATH."log","360_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}
	$result = @mysqli_fetch_assoc($query);
	if($result){
		$insert_id = $result['id'];
		exit("0 $insert_id");
	}
	$insert_id = '';
	$password = random_common();
	$reg_time = date("ymdHi");
	$sql_game = "insert into account (NAME,password,reg_date, channel_account) VALUES ('$channel_account','$password','$reg_time', '$channel_account')";
	@mysqli_query($conn, $sql_game);
	$insert_id = @mysqli_insert_id($conn);
	if($insert_id){
		write_log(ROOT_PATH."log","new_account_360_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit("1 $insert_id");
	}
}else{
    write_log(ROOT_PATH."log","360_login_error_"," user验证异常 ,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}

exit("999 0");





?>
