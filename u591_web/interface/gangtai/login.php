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
write_log(ROOT_PATH."log","gangtai_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$token = $_REQUEST['token'];
$game_id = intval($_REQUEST['game_id']);
$userId = strtolower($_REQUEST['userId']);
if(!$token || !$game_id || !$userId){
    write_log(ROOT_PATH."log","gangtai_login_error_","parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$userIdArr = explode('_', $userId);
$type = (isset($userIdArr[1]) && $userIdArr[1] == 'android') ? 'android' : 'ios';
$appId = $key_arr[$game_id][$type]['appid'];
$appSecret = $key_arr[$game_id][$type]['appsecret'];
$appUserId = $userIdArr[0];
$sign = md5($appId.$appUserId.$token.$appSecret);
//$url = "http://sdk-test.changic.net.cn:8191/pocketgames/"; //test url
if($type == 'android')
    $url = "http://kdygvs-android.88box.com:8091/pocketgames/";
else
    $url = "http://kdygvs-ios.88box.com:8091/pocketgames/"; //ios url
$url .= "client/user/verifyToken/$appId/$appUserId/$token/$sign";
$result = https_post($url, array());
$resultArr = json_decode($result, true);
write_log(ROOT_PATH."log","gangtai_result_log_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
if(isset($resultArr['code']) && $resultArr['code'] == 200){
    $accountConn = $accountServer[$game_id];
    $conn = SetConn($accountConn);
    $channel_account = mysqli_real_escape_string($conn,$appUserId.'@gangtai');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","gangtai_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_gangtai_log_"," gangtai new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}else{
    write_log(ROOT_PATH."log","gangtai_login_error_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}
exit('999 0');