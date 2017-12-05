<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/8
 * Time: 下午1:36
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","baidu_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
//客户端SDK返回的登陆令牌
$accessToken = $_REQUEST['user_token'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];
if(!$accessToken || !$appUid || !$gameId){
    write_log(ROOT_PATH."log","baidu_login_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
require_once 'Sdk.php';
global $key_arr;
$auids = explode('_', $appUid);
$type = isset($auids[1])?$auids[1]:'android';
$appid = $key_arr[$gameId][$type]['appid'];
$appkey = $key_arr[$gameId][$type]['appsecret'];
$sdk = new Sdk($appid,$appkey);
$Res = $sdk->login_state_result($accessToken);
write_log(ROOT_PATH."log","baidu_login_result_",json_encode($Res).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($Res['ResultCode']=="1"&&$Res['Sign']==$sdk->SignMd5($Res['ResultCode'],urldecode($Res['Content']))){
	//Content参数需要urldecode后再进行base64解码
	$result=base64_decode(urldecode($Res['Content']));
	write_log(ROOT_PATH."log","baidu_login_result_",'content:'.json_encode($result).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	//json解析
	$Item=extract(json_decode($result,true));
	$accountConn = $accountServer[$gameId];
	$conn = SetConn($accountConn);
	$channel_account = mysqli_real_escape_string($conn,$UID.'@baidu');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
    	write_log(ROOT_PATH."log","baidu_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
    mysqli_query($conn, $sql_game);
    $insert_id = mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_baidu_log_"," baidu new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}
write_log(ROOT_PATH."log","baidu_login_error_",json_encode($Res).",post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

exit("999 0");