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
	$username = $memId.'@360';
	$bindtable = getAccountTable($username,'token_bind');
	$bindwhere = 'token';
	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
	if($insertinfo['status'] == '1'){
		write_log(ROOT_PATH."log","360_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}else{
		$insert_id = $insertinfo['data'];
		if($insertinfo['isNew'] == '1'){
			write_log(ROOT_PATH."log","new_account_360_log_","360 new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
			exit("1 $insert_id");
		}else{
			exit("0 $insert_id");
		}
	}
}else{
    write_log(ROOT_PATH."log","360_login_error_"," user验证异常 ,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}

exit("999 0");





?>
