<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 * Time: 下午1:36
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","kupai_login_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userToken = $_REQUEST['user_token'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$appUid || !$gameId){
    write_log(ROOT_PATH."log","kupai_login_error_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}


$uids = explode('_', $appUid);
$uid = $uids[0];
$type = $uids[1];
$config = $key_arr[$gameId][$type];
$url_user = "https://openapi.coolyun.com/oauth2/api/get_user_info?access_token=$userToken&oauth_consumer_key={$config['appId']}&openid=$uid";
$data = array();
$result_json = https_post($url_user,$data);

write_log(ROOT_PATH."log","kupai_result_log_","result=".$result_json.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$result_arr = json_decode($result_json,1);
if($result_arr['rtn_code']=='0'){
	$username = $uid.'@kupai';
	$bindtable = getAccountTable($username,'token_bind');
	$bindwhere = 'token';
	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
	if($insertinfo['status'] == '1'){
		write_log(ROOT_PATH."log","kupai_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
		exit('3 0');
	}else{
		$insert_id = $insertinfo['data'];
		if($insertinfo['isNew'] == '1'){
			write_log(ROOT_PATH."log","new_account_kupai_log_","kupai new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
			exit("1 $insert_id");
		}else{
			exit("0 $insert_id");
		}
	}
}

write_log(ROOT_PATH."log","kupai_login_error_","result=".$rdata.date("Y-m-d H:i:s")."\r\n");
exit('4 0');
