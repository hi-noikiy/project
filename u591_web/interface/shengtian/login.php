<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/20
 * Time: 上午11:09
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","shengtian_login_info_", "post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$sign = $_REQUEST['sign'];
$uid = $_REQUEST['uid'];
$gameId = intval($_REQUEST['game_id']);

if(!$sign || !$gameId || !$uid) {
    write_log(ROOT_PATH."log","shengtian_login_error_", "params error. post=$post, get=$get,  ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
global $key_arr;
$hainiuKey = $key_arr['hainiuKey'];
$md5Str = 'uid='.$uid.'&game_id='.$gameId.$hainiuKey;
$mySign = md5($md5Str);
if($sign != $mySign){
    write_log(ROOT_PATH."log","shengtian_login_error_","sign error. md5Str=$md5Str,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}
global $accountServer;
$accountConn = $accountServer[$gameId];
$conn = SetConn($accountConn);
if($conn == false){
    write_log(ROOT_PATH."log","shengtian_login_error_","account connect error. conn=$accountConn, ".date("Y-m-d H:i:s")."\r\n");
    exit('3 0');
}
$channel_account = mysqli_real_escape_string($conn,$uid.'@shengtian');
$sql = "select id from account where channel_account='$channel_account' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
    write_log(ROOT_PATH."log","shengtian_login_error_","mysql error.sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
    write_log(ROOT_PATH."log","new_account_shengtian_log_","return=1 $insert_id, ".date("Y-m-d H:i:s")."\r\n");
    exit("1 $insert_id");
}
exit('999 0');