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
write_log(ROOT_PATH."log","smartisan_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userToken = $_REQUEST['user_token'];
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$gameId){
    write_log(ROOT_PATH."log","smartisan_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$url = "https://api.smartisan.com/oauth/users/info?access_token={$userToken}";
$rdata = https_post($url, $data);

write_log(ROOT_PATH."log","smartisan_result_log_","result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $rdata = json_decode($rdata,true);
    $memId = $rdata['openid'];
    $username = $memId.'@smartisan';
    $bindtable = getAccountTable($username,'token_bind');
    $bindwhere = 'token';
    $insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
    if($insertinfo['status'] == '1'){
    	write_log(ROOT_PATH."log","smartisan_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    	exit('3 0');
    }else{
    	$insert_id = $insertinfo['data'];
    	if($insertinfo['isNew'] == '1'){
    		write_log(ROOT_PATH."log","new_account_smartisan_log_","smartisan new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
    		exit("1 $insert_id");
    	}else{
    		exit("0 $insert_id");
    	}
    }
}else{
	write_log(ROOT_PATH."log","smartisan_login_error_","result=$rdata, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
