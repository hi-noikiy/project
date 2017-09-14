<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/5
 * Time: 下午3:00
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","kuaifa_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

//$str = 'a:3:{s:5:"token";s:32:"b9777984394b970e6ebafc8a44243386";s:6:"openid";s:40:"3a5955cb5cd98df78a368029b914762b_android";s:7:"game_id";s:1:"8";}';
//$_REQUEST = unserialize($str);
$token = $_REQUEST['token'];
$game_id = intval($_REQUEST['game_id']);
$openid = strtolower($_REQUEST['openid']);
if(!$token || !$game_id || !$openid){
    write_log(ROOT_PATH."log","kuaifa_login_error_","parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$openidArr = explode('_', $openid);
$type = (isset($openidArr[1]) && $openidArr[1] == 'android') ? 'android' : 'ios';
$gameKey = $key_arr[$game_id][$type]['gamekey'];
$securutyKey = $key_arr[$game_id][$type]['securutykey'];
$openid = $openidArr[0];
$timestamp = time();

$array = array();
$array['token'] = urlencode($token);
$array['openid'] = urlencode($openid);
$array['timestamp'] = urlencode($timestamp);
$array['gamekey'] = urlencode($gameKey);
ksort($array);
$md5Str = http_build_query($array);
$sign = md5(md5($md5Str).$securutyKey);
$array['_sign'] = $sign;

$url = "http://z.kuaifazs.com/foreign/oauth/verification2.php";

$result = https_post($url, $array);
$resultArr = json_decode($result, true);
write_log(ROOT_PATH."log","kuaifa_result_log_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
if(isset($resultArr['result']) && $resultArr['result'] == 0){
    $accountConn = $accountServer[$game_id];
    $conn = SetConn($accountConn);
    $channel_account = mysqli_real_escape_string($conn,$openid.'@kuaifa');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","kuaifa_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_kuaifa_log_"," kuaifa new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}else{
    write_log(ROOT_PATH."log","kuaifa_login_error_","url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}
exit('999 0');