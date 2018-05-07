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
write_log(ROOT_PATH."log","meitu_login_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userToken = $_REQUEST['user_token'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$appUid || !$gameId){
    write_log(ROOT_PATH."log","meitu_login_error_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$appid = $key_arr[$gameId][$appUid]['appId'];
$url = "https://openapi.account.meitu.com/open/user/info.json?access_token=$userToken&client_id=$appid";
$rdata = https_post($url, $data);//return_code是http状态码
write_log(ROOT_PATH."log","meitu_result_log_","result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $tdata = json_decode($rdata,true);
    if('0' == $tdata['meta']['code']){
        $username = $tdata['response']['user']['id'].'@meitu';
        $bindtable = getAccountTable($username,'token_bind');
        $bindwhere = 'token';
        $insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
        if($insertinfo['status'] == '1'){
        	write_log(ROOT_PATH."log","meitu_login_error_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        	exit('3 0');
        }else{
        	$insert_id = $insertinfo['data'];
        	if($insertinfo['isNew'] == '1'){
        		write_log(ROOT_PATH."log","new_account_meitu_log_","meitu new account login , post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        		exit("1 $insert_id");
        	}else{
        		exit("0 $insert_id");
        	}
        }
    }
}
write_log(ROOT_PATH."log","meitu_login_error_","result=".$rdata.date("Y-m-d H:i:s")."\r\n");
exit('4 0');
