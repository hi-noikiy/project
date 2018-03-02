<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 微信登陆接口
* ==============================================
* @date: 2016-7-12
* @author: Administrator
* @return:
*/
include_once 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","weixin_login_all_log_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$sign = $_REQUEST['sign'];
$openid = $_REQUEST['openid'];
$gameId = $_REQUEST['gameid'];
$appKey = $key_arr['weixin'];
if(!$openid || !$appKey){
	write_log(ROOT_PATH."log","weixin_login_error_log_","params error! openid=$openid, appKey=$appKey, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}
$mysign = md5('openid='.$openid.$appKey);

if($sign != $mysign){
	write_log(ROOT_PATH."log","weixin_login_error_log_","sign error! mysign=$mysign, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");
}
$username=$openid.'@weixin';
$bindtable = getAccountTable($username,'token_bind');
$bindwhere = 'token';
$insertinfo = insertaccount($username,$bindtable,$bindwhere,$gameId);
if($insertinfo['status'] == '1'){
	write_log(ROOT_PATH."log","weixin_login_error_log_",json_encode($insertinfo).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}else{
	$insert_id = $insertinfo['data'];
	if($insertinfo['isNew'] == '1'){
		exit("1 $insert_id");
	}else{
		exit("0 $insert_id");
	}
}