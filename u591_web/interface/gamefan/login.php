<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* gamefan登陆接口
* ==============================================
* @date: 20170519
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","gamefan_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$game_id = intval($_REQUEST['game_id']);
$jsondata = json_decode($_REQUEST['jsondata'],true);
$username = $jsondata['username'];
$logintime = $jsondata['logintime'];
$openId =  $jsondata['userId'];
$sign =  $jsondata['sign'];
if(!$username || !$logintime || !$sign || !$openId || !$game_id){
	write_log(ROOT_PATH."log","gamefan_login_error_"," parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$data = array();
$data['username'] = $username;
$data['appkey'] = $key_arr[$game_id]['appkey'];
$data['logintime'] = $logintime;
$data['sign'] = md5(http_build_query($data));
if($sign == $data['sign']){
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$channel_account = mysqli_real_escape_string($conn,$openId.'@gamefan');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
    	write_log(ROOT_PATH."log","gamefan_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_gamefan_log_"," gamefan new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}else{
	write_log(ROOT_PATH."log","gamefan_login_error_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
exit('999 0');
?>
