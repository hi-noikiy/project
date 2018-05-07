<?php

include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xiaomi_login_all_log_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$session_id = $_REQUEST['user_token'];
$game_id = $_REQUEST['game_id'];
$uid = $_REQUEST['uid'];

if(!$session_id||!$uid){
	write_log(ROOT_PATH."log","xiaomi_login_error_"," parameter error!, session_id=$session_id, uid=$uid, game_id=$game_id, ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}
$uids = explode('_', $uid);
$uid = $uids[0];
$type = isset($uids[1])?$uids[1]:'android';

$appId = $key_arr[$game_id][$type]['appId'];
$appSecret = $key_arr[$game_id][$type]['appSecret'];
$text = "appId=$appId&session=$session_id&uid=$uid";
$signature = hash_hmac("sha1",$text,$appSecret);
$url = "http://mis.migc.xiaomi.com/api/biz/service/verifySession.do?appId=$appId&session=$session_id&uid=$uid&signature=$signature";
$result = https_post($url,array());
write_log(ROOT_PATH."log","xiaomi_login_result_log_"," url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");

$result_arr = json_decode($result,true);

if($result_arr['errcode']=='200'){
		$memId = $uid;
		$gameId = $game_id;
        //CP操作,请求成功,用户有效
		$username = $memId.'@xiaomi';
		$bindtable = getAccountTable($username,'token_bind');
		$bindwhere = 'token';
		$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
		if($insertinfo['status'] == '1'){
			write_log(ROOT_PATH."log","xiaomi_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
			exit('3 0');
		}else{
			$insert_id = $insertinfo['data'];
			if($insertinfo['isNew'] == '1'){
				write_log(ROOT_PATH."log","new_account_xiaomi_log_","xiaomi new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
				exit("1 $insert_id");
			}else{
				exit("0 $insert_id");
			}
		}

}else{
	write_log(ROOT_PATH."log","xiaomi_login_error_"," sign error, result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
?>
