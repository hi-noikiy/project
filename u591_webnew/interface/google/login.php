<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/17
 * Time: 下午1:41
 */
include_once 'config.php';
include_once 'src/Google/autoload.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH.'log','google_login_info_',"post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");

//$str = 'a:3:{s:12:"access_token";s:45:"4/fU6TpufDnEYKJ5dgD6yYir3TlXjMSF4BfmJnhRkLcL4";s:4:"type";s:6:"yuenan";s:7:"game_id";s:1:"8";}';
//$_REQUEST = unserialize($str);

$access_token = $_REQUEST['access_token'];
$gameId = $_REQUEST['game_id'] ? $_REQUEST['game_id'] : 8;
$type = $_REQUEST['type'];

if(!$access_token || !$gameId){
    write_log(ROOT_PATH."log","google_login_error_","param error! post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}
$data = array();
global $key_arr;

$appId = isset($key_arr[$gameId][$type]['appId']) ? $key_arr[$gameId][$type]['appId'] : $key_arr[$gameId]['appId'];
$appSecret = isset($key_arr[$gameId][$type]['appSecret']) ? $key_arr[$gameId][$type]['appSecret'] : $key_arr[$gameId]['appSecret'];
$client = new Google_Client();
$client->setClientId($appId);
$client->setClientSecret($appSecret);
$token = $client->fetchAccessTokenWithAuthCode($_REQUEST['access_token']);

if(isset($token['access_token'])) {
    $client->setAccessToken($token);
    if ($client->getAccessToken()) {
        $token_data = $client->verifyIdToken();
    }
}

$jsonRs = isset($token_data) ? json_encode($token_data) : json_encode($token);
write_log(ROOT_PATH.'log','google_login_check_',"$jsonRs, ".date('Y-m-d H:i:s')."\r\n");
if(isset($token_data['sub'])){
    $google_id = $token_data['sub'];
    $conn = SetConn($gameId);
    if($conn == false){
        write_log(ROOT_PATH.'log','google_login_error_',"account mysql connect error. ".date('Y-m-d H:i:s')."\r\n");
        exit('3 0');
    }
    $channel_account = mysqli_escape_string($conn, $google_id.'@google');
    $username = rand(10000,99999).time().'@google';
//     $username = $channel_account;
    $sql = " select id from account where channel_account = '$channel_account' limit 1";
    $query = @mysqli_query($conn, $sql);
    $result=array();
    if($query){
        $result = @mysqli_fetch_assoc($query);
    }else{
        write_log(ROOT_PATH."log","google_login_error_","sql error,sql=$sql,". mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    if($result){
        $insert_id = $result['id'];
        exit("0 $insert_id");
    }
    //判断是否越南或者新马帐号登录
    //if(substr($type,0,6) == 'yuenan' || $type == 'xinma' || substr($type,0,6) == 'nanmei'){
        $sql = "select account_id from account_ggp where ggp_account='$channel_account' limit 1;";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","google_login_error_","sql error,sql=$sql,". mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $result = @mysqli_fetch_assoc($query);
        if(isset($result['account_id'])){
            $accountId = $result['account_id'];
            write_log(ROOT_PATH."log","google_login_ggp_log_","return=0 $accountId, ".date("Y-m-d H:i:s")."\r\n");
            exit("0 $accountId");
        }
    //}
    $insert_id='';
    $password=random_common();
    $reg_time=date("ymdHi");
    $sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
    if(false == mysqli_query($conn, $sql_game)){
        write_log(ROOT_PATH."log","google_login_error_","sql error,sql=$sql,". mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    $insert_id = @mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","google_new_login_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }else{
        $str=$sql_game."  ".mysqli_error($conn)." get=$get, ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","google_login_error_",$str);
        exit("3 0");
    }
}else{
    write_log(ROOT_PATH.'log','google_login_error_'," check error,result=$jsonRs,post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");
    exit("4 0");
}