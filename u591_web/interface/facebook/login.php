<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/21
 * Time: 下午7:28
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

$gameId = $_REQUEST['game_id'] ? $_REQUEST['game_id'] : 8;
$token =  $_REQUEST['token'];
$type = $_REQUEST['type'];

write_log(ROOT_PATH.'log','fb_login_info_',"post=$post,get=$get, ".date('Y-m-d H:i:s')."\r\n");
if(!$token || !$gameId){
    write_log(ROOT_PATH.'log','fb_login_error_',"params error. post=$post,get=$get,".date('Y-m-d H:i:s')."\r\n");
    exit("2 0");
}

$url = "https://graph.facebook.com/me/?access_token=$token";
$data = array();
$result = https_post($url,$data);
$result_arr = json_decode($result,true);
write_log(ROOT_PATH.'log','fb_check_info_',"result=$result,url=$url,".date('Y-m-d H:i:s')."\r\n");

if(!isset($result_arr['id'])){
    write_log(ROOT_PATH.'log','fb_login_error_',"result=$result,url=$url,".date('Y-m-d H:i:s')."\r\n");
    exit("4 0");
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH.'log','fb_login_error_',"account mysql connect error. ".date('Y-m-d H:i:s')."\r\n");
    exit('3 0');
}
$fbId = $result_arr['id'];
$channel_account = mysqli_escape_string($conn, $fbId.'@fb');

$sql = "select id from account where channel_account='$channel_account' limit 1";
$query = @mysqli_query($conn, $sql);
if($query == false){
    write_log(ROOT_PATH."log","fb_login_error_","sql error. sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
    exit('3 0');
}
$result = @mysqli_fetch_assoc($query);
if(isset($result['id'])){
    $insert_id = $result['id'];
    exit("0 $insert_id");
}
//判断是否ggp帐号登录
//if(substr($type,0,6) == 'yuenan' || substr($type,0,6) == 'nanmei'){
    $sql = "select account_id from account_ggp where ggp_account='$channel_account' limit 1;";
    if(false == $query = mysqli_query($conn, $sql)){
        write_log(ROOT_PATH."log","fb_login_error_","sql error,sql=$sql,". mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    $result = @mysqli_fetch_assoc($query);
    if(isset($result['account_id'])){
        $accountId = $result['account_id'];
        write_log(ROOT_PATH."log","fb_login_ggp_log_","return=0 $accountId, ".date("Y-m-d H:i:s")."\r\n");
        exit("0 $accountId");
    }
//}
$insert_id = '';
$password = random_common();
$reg_time = date("ymdHi");
$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$channel_account','$password','$reg_time','$channel_account')";
@mysqli_query($conn, $sql_game);
$insert_id = @mysqli_insert_id($conn);
if($insert_id){
    write_log(ROOT_PATH."log","fb_new_login_log_","return=1 $insert_id, ".date("Y-m-d H:i:s")."\r\n");
    exit("1 $insert_id");
}else{
    write_log(ROOT_PATH."log","fb_login_error_", "sql=$sql_game,".mysqli_error($conn)." get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("3 0");
}
