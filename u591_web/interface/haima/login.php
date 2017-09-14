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
write_log(ROOT_PATH."log","haima_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$appT = $_REQUEST['t'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];
if(!$appT || !$appUid || !$gameId){
    write_log(ROOT_PATH."log","haima_login_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$types = explode('_', $appUid);
$appUid = $types[0];
$type = isset($types[1])?$types[1]:'android';
$appId = $key_arr[$gameId][$type]['appId'];
//登录验证POST的参数
$post_data = array(
    "appid" => $appId,
    "t" => $appT,
    "uid" => $appUid,
);
$url = "http://api.haimawan.com/index.php?m=api&a=validate_token";

$rs = https_post($url, $post_data);
if($rs === FALSE){
    write_log(ROOT_PATH."log","haima_login_error_","sign error!, result=$rs, ".date("Y-m-d H:i:s")."\r\n");
    exit("999 0");
}else{
    $data = explode('&', $rs);
    if(!isset($data[0]) || $data[0] != 'success'){
        write_log(ROOT_PATH."log","haima_login_error_","sign error!, result=$rs, ".date("Y-m-d H:i:s")."\r\n");
        exit("4 0");
    }
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $channel_account = @mysqli_real_escape_string($conn,$appUid.'@haima');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","haima_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
    @mysqli_query($conn, $sql_game);
    $insert_id = @mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_haima_log_","haima new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}
exit("999 0");