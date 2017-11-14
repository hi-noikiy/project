<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/13
 * Time: 下午2:13
 * Desc: mac绑定渠道帐号(google,facebook)
 *       由于google比较特殊，所以放在这目录下面
 * Retu: 2 0 参数错误
 *       3 0 数据库连接错误
 *       4 0 验证错误
 *       1 accountId 该帐号已经绑定
 *       0 accountId 绑定成功
 *       1000 accountId 此帐号不能绑定
 */
include_once 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH.'log','bindall_login_info_'," post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");

$gameId = isset($_REQUEST['game_id']) ? $_REQUEST['game_id'] : 8;
$token = $_REQUEST['token'];
$ext = $_REQUEST['ext']; //accountId_type

if(!$token || !$ext || !$gameId){
    write_log(ROOT_PATH.'log','bindall_login_error_',"parameters error.post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");
    exit("2 0");
}
$extArr = explode('_', $ext);
$accountId = isset($extArr[0]) ? $extArr[0] : 0;
$type = isset($extArr[1]) ? $extArr[1] : 0;
$currenty = isset($extArr[2]) ? $extArr[2] : '';
if(!$currenty){
	$currenty = 'yuenan';
}
if(!in_array($type, array('google'))){
	write_log(ROOT_PATH.'log','bindall_login_error_',"bind type not in google and fb,$type ".date('Y-m-d H:i:s')."\r\n");
	exit("4 0");
}
if($type == 'google'){
    global $key_arr;
    include_once 'src/Google/autoload.php';
    $data = array();

    $appId = isset($key_arr[$gameId][$currenty]['appId']) ? $key_arr[$gameId][$currenty]['appId'] : $key_arr[$gameId]['appId'];
    $appSecret = isset($key_arr[$gameId][$currenty]['appSecret']) ? $key_arr[$gameId][$currenty]['appSecret'] : $key_arr[$gameId]['appSecret'];

    $client = new Google_Client();
    $client->setClientId($appId);
    $client->setClientSecret($appSecret);
    $token = $client->fetchAccessTokenWithAuthCode($token);
    if(isset($token['access_token'])) {
        $client->setAccessToken($token);
        if ($client->getAccessToken()) {
            $token_data = $client->verifyIdToken();
        }
    }
    $jsonRs = isset($token_data) ? json_encode($token_data) : json_encode($token);
    write_log(ROOT_PATH.'log','bindall_login_check_',"$jsonRs, ".date('Y-m-d H:i:s')."\r\n");
    if(!isset($token_data['sub'])){
        write_log(ROOT_PATH.'log','bindall_login_error_',"result=$jsonRs, ".date('Y-m-d H:i:s')."\r\n");
        exit("4 0");
    }
    $channel_account = $token_data['sub'].'@google';
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH.'log','bindall_login_error_',"account mysql connect error.".date('Y-m-d H:i:s')."\r\n");
    exit("3 0");
}
//查询这个帐号是否绑定
$channel_account = mysqli_real_escape_string($conn, $channel_account);
$sql = "select account_id from account_ggp where channel_account='$channel_account' limit 1;";
$query = @mysqli_query($conn, $sql);
$result = @mysqli_fetch_assoc($query);

if(isset($result['account_id'])){
    $accountId = $result['account_id'];
    write_log(ROOT_PATH.'log','bindall_login_return_',"return:1 $accountId. post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");
    exit("1 $accountId");
} else {
    //判断是否是可绑定状态
    $accountId = mysqli_real_escape_string($conn, $accountId);
    $sql = "select id,channel_account from account where id='$accountId' limit 1";
    $query = @mysqli_query($conn, $sql);
    $result = @mysqli_fetch_assoc($query);
    if(!$result['id']){
        write_log(ROOT_PATH.'log','bindall_login_error_'," account id is not exist. post=$post,get=$get,".date('Y-m-d H:i:s')."\r\n");
        exit("2 0");
    }
    $channelAccountArr = explode("@", $result['channel_account']);
    if(isset($channelAccountArr[1]) &&  !in_array($channelAccountArr[1], array('fb','u591'))){
        write_log(ROOT_PATH.'log','bindall_login_error_',"account id is not fb. post=$post,get=$get,".date('Y-m-d H:i:s')."\r\n");
        exit("1000 $accountId");
    }
    //判断google是否已经注册帐号
    $sql = "select id,channel_account from account where channel_account='$channel_account' limit 1";
    $query = @mysqli_query($conn, $sql);
    $result = @mysqli_fetch_assoc($query);
    if(isset($result['id'])){
        write_log(ROOT_PATH.'log','bindall_login_error_',"google alread registerd. post=$post,get=$get,".date('Y-m-d H:i:s')."\r\n");
        exit("1000 {$result['id']}");
    }
    $bindall_time = time();
    $insert_sql = "insert into account_ggp (ggp_account,account_id,bind_time) VALUES ('$channel_account','$accountId','$bindall_time')";
    if(!mysqli_query($conn, $insert_sql)){
        write_log(ROOT_PATH.'log','bindall_login_error_',"sql error.sql=$insert_sql, ".mysqli_error($conn)." , ".date('Y-m-d H:i:s')."\r\n");
        exit("3 0");
    }
    write_log(ROOT_PATH.'log','bindall_login_return_',"return=0 $accountId. post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");
    exit("0 $accountId");
}