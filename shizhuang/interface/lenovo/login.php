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
	$username = $id.'@lenovo';
	$bindtable = getAccountTable($username,'token_bind');
	$bindwhere = 'token';
	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$game_id);
	if($insertinfo['status'] == '1'){
		write_log(ROOT_PATH."log","lenovo_login_error_",json_encode($insertinfo).",post=$post,get=$get,$HTTP_RAW_POST_DATA,file_in=$file_in, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}else{
		$insert_id = $insertinfo['data'];
		if($insertinfo['isNew'] == '1'){
			write_log(ROOT_PATH."log","new_account_lenovo_log_","oppo new account login , post=$post,get=$get,$HTTP_RAW_POST_DATA,file_in=$file_in, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
			exit("1 $insert_id");
		}else{
			exit("0 $insert_id");
		}
	}
}else{
	write_log(ROOT_PATH."log","lenovo_error_log_"," url=$url,url_result=$url_result, post=$post,get=$get,file_in=$file_in,  ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}