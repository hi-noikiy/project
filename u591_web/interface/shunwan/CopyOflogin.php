<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/4
 * Time: 下午2:01
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","shunwan_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$token = $_REQUEST['token'];
$game_id = intval($_REQUEST['game_id']);
$p = strtolower($_REQUEST['p']);
if(!$token || !$game_id || !$p){
    write_log(ROOT_PATH."log","shunwan_login_error_","parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$pArr = explode('_', $p);
$type = (isset($pArr[2]) && $pArr[2] == 'android') ? 'android' : 'ios';
$appId = $key_arr[$game_id][$type]['appId'];
$secKey = $key_arr[$game_id][$type]['secKey'];
$appUid = $pArr[0];
$appUserName = $pArr[1];

$array = array(
    'game_id'   => $appId,
    'username'  => $appUserName,
    'token'     => $token,
    'uid'       => $appUid,
);
ksort($array);
$md5Str = http_build_query($array).'&signKey='.$secKey;
$array['sign'] = md5($md5Str);
$url = 'http://api.shunwan.cn/callback/user/info';

$result = https_post($url, $array);
if($result == 'SUCCESS'){
    $accountConn = $accountServer[$game_id];
    $conn = SetConn($accountConn);
    $channel_account = mysqli_real_escape_string($conn,$appUid.'@shunwan');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","shunwan_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_shunwan_log_"," shunwan new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}else{
    write_log(ROOT_PATH."log","shunwan_login_error_","result=$result, md5Str=$md5Str,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}
exit('999 0');