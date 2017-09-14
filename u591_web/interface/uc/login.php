<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* uc登陆接口
* ==============================================
* @date: 2016-7-28
* @author: Administrator
* @return:
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","uc_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$sid = $_REQUEST['sid'];
$channel = trim($_REQUEST['channel']);
$game_id = intval($_REQUEST['game_id']);

if(!$sid || !$channel){
	exit('2 0');
}

$url = "http://sdk.g.uc.cn/ss/";

$cpId = $key_arr[$game_id][$channel]['cpId'];
$gameId = $key_arr[$game_id][$channel]['gameId'];
$serverId = $key_arr[$game_id][$channel]['serverId'];
$channelId = $key_arr[$game_id][$channel]['channelId'];
$apiKey = $key_arr[$game_id][$channel]['apiKey'];

$service = "account.verifySession";
$data_arr = array('sid'=>$sid);
$data = json_encode($data_arr);
$game_arr = array('cpId'=>$cpId,'gameId'=>$gameId,'channelId'=>$channelId,'serverId'=>$serverId);
$game = json_encode($game_arr);
$encrypt = "md5";
$sign = md5("sid=$sid".$apiKey);

$formvars['id'] = time();
$formvars['service'] = $service;
$formvars['data'] = $data_arr;
$formvars['game'] = $game_arr;
$formvars['sign'] = $sign;
$formvars['encrypt'] = $encrypt;

$formvars_str = json_encode($formvars);

$result = http_post($url,$formvars_str);
write_log(ROOT_PATH."log","uc_result_log_","result=$result ".date("Y-m-d H:i:s")."\r\n");
$result_arr = json_decode($result,true);
$result_arr['sid'] = $sid;
if(isset($result_arr['state']['code']) && $result_arr['state']['code']==1){
	$ucid = $result_arr['data']['accountId'];
	$accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
	$channel_account = mysqli_real_escape_string($conn,$ucid.'@uc');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
    	write_log(ROOT_PATH."log","uc_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_uc_log_"," uc new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}else{
	exit('4 0');
}
exit('999 0');
?>
