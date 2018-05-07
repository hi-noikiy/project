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
write_log(ROOT_PATH."log","meizu_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$data['session_id'] = $_REQUEST['user_token'];
$uid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];
$appUidArr = explode('_', $uid);
$data['uid'] = $appUidArr[0];
$type = $appUidArr[1];

if(!$data['session_id'] || !$data['uid'] || !$gameId || !$type){
    write_log(ROOT_PATH."log","meizu_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}

$data['app_id'] = $key_arr[$gameId][$type]['appId'];
$appSecret = $key_arr[$gameId][$type]['appSecret'];
$data['ts'] = time();
ksort($data);
$signstr = http_build_query($data).':'.$appSecret;
$data['sign'] = md5($signstr);
$url = "https://api.game.meizu.com/game/security/checksession";
$data['sign_type'] = 'md5';
$rdata = https_post($url, $data);

write_log(ROOT_PATH."log","meizu_result_log_",$signstr.",result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $rdata = json_decode($rdata,true);
    if('200' == $rdata['code']){
    	$username = $data['uid'].'@meizu';
    	$bindtable = getAccountTable($username,'token_bind');
    	$bindwhere = 'token';
    	$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
    	if($insertinfo['status'] == '1'){
    		write_log(ROOT_PATH."log","meizu_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    		exit('3 0');
    	}else{
    		$insert_id = $insertinfo['data'];
    		if($insertinfo['isNew'] == '1'){
    			write_log(ROOT_PATH."log","new_account_meizu_log_","meizu new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
    			exit("1 $insert_id");
    		}else{
    			exit("0 $insert_id");
    		}
    	}
    }
}
write_log(ROOT_PATH."log","meizu_login_error_","result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');