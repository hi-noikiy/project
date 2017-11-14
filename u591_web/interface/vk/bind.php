<?php
/**
 * 游客绑定vk帐号
 */
include_once 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH.'log','vk_bind_info_'," post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");

$gameId = isset($_REQUEST['game_id']) ? $_REQUEST['game_id'] : 8;
$userToken = $_REQUEST['token'];
$ext = $_REQUEST['ext']; //accountId_type

if(!$userToken || !$ext || !$gameId){
    write_log(ROOT_PATH.'log','vk_bind_error_',"parameters error.post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");
    exit("2 0");
}
$extArr = explode('_', $ext);
$accountId = isset($extArr[0]) ? $extArr[0] : 0;
$currenty = isset($extArr[1]) ? $extArr[1] : 0;
$type = isset($extArr[2]) ? $extArr[2] : 'ios';
if(!in_array($currenty, array('vk'))){
	write_log(ROOT_PATH.'log','vk_bind_error_',"bind type not in vk,$currenty ".date('Y-m-d H:i:s')."\r\n");
	exit("4 0");
}
if($currenty == 'vk'){
    global $key_arr;
    $appid= $key_arr[$gameId][$type]['appid'];
    $appkey= $key_arr[$gameId][$type]['appkey'];
    $url = "https://oauth.vk.com/access_token?client_id=$appid&client_secret=$appkey&v=5.1&grant_type=client_credentials";
    $rdata = https_post($url, $data);
    write_log(ROOT_PATH."log","vk_bind_result_log_",$url.",result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    $rdata= json_decode($rdata,true);
    $token = $rdata['access_token'];
    $url = "https://api.vk.com/method/secure.checkToken?token=$userToken&client_secret=$appkey&access_token=$token";
    $rdata = https_post($url, $data);
    write_log(ROOT_PATH."log","vk_bind_result_log_",$url.",result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    $channel_account = '';
    if($rdata){
    	$rdata = json_decode($rdata,true);
    	if('1' == $rdata['response']['success']){
    		$channel_account = $rdata['response']['user_id'].'@vk';
    	}
    }
}
if(!$channel_account){
	write_log(ROOT_PATH.'log','vk_bind_error_'," channel_account is not exist. post=$post,get=$get,".date('Y-m-d H:i:s')."\r\n");
	exit("3 0");
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH.'log','vk_bind_error_',"account mysql connect error.".date('Y-m-d H:i:s')."\r\n");
    exit("3 0");
}
//查询这个帐号是否绑定
$channel_account = mysqli_real_escape_string($conn, $channel_account);
$sql = "select account_id from account_ggp where channel_account='$channel_account' limit 1;";
$query = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_assoc($query);

if(isset($result['account_id'])){
    $accountId = $result['account_id'];
    write_log(ROOT_PATH.'log','vk_bind_return_',"return:1 $accountId. post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");
    exit("1 $accountId");
} else {
    //判断是否是可绑定状态
    $accountId = mysqli_real_escape_string($conn, $accountId);
    $sql = "select id,channel_account from account where id='$accountId' limit 1";
    $query = @mysqli_query($conn, $sql);
    $result = @mysqli_fetch_assoc($query);
    if(!$result['id']){
        write_log(ROOT_PATH.'log','vk_bind_error_'," account id is not exist. post=$post,get=$get,".date('Y-m-d H:i:s')."\r\n");
        exit("2 0");
    }
    $channelAccountArr = explode("@", $result['channel_account']);
    if(isset($channelAccountArr[1]) && $channelAccountArr[1] != 'u591'){
        write_log(ROOT_PATH.'log','vk_bind_error_',"account id is not u591. post=$post,get=$get,".date('Y-m-d H:i:s')."\r\n");
        exit("1000 $accountId");
    }
    //判断vk是否已经注册帐号
    $sql = "select id,channel_account from account where channel_account='$channel_account' limit 1";
    $query = @mysqli_query($conn, $sql);
    $result = @mysqli_fetch_assoc($query);
    if(isset($result['id'])){
        write_log(ROOT_PATH.'log','vk_bind_error_',"vk alread registerd. post=$post,get=$get,".date('Y-m-d H:i:s')."\r\n");
        exit("1000 $accountId");
    }
    $bindall_time = time();
    $insert_sql = "insert into account_ggp (ggp_account,account_id,bind_time) VALUES ('$channel_account','$accountId','$bindall_time')";
    if(!mysqli_query($conn, $insert_sql)){
        write_log(ROOT_PATH.'log','vk_bind_error_',"sql error.sql=$insert_sql, ".mysqli_error($conn)." , ".date('Y-m-d H:i:s')."\r\n");
        exit("3 0");
    }
    write_log(ROOT_PATH.'log','vk_bind_return_',"return=0 $accountId. post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");
    exit("0 $accountId");
}