<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/22
 * Time: 下午2:39
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","ailuoke_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$token = $_REQUEST['token'];
$game_id = intval($_REQUEST['game_id']);
$p = strtolower($_REQUEST['p']);
if(!$token || !$game_id || !$p){
    write_log(ROOT_PATH."log","ailuoke_login_error_","parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$pArr = explode('_', $p);
//$type = (isset($pArr[2]) && $pArr[2] == 'android') ? 'android' : 'ios';
$uuid = $pArr[0];

$url = 'http://sdk.gamemorefun.com/rest/v1/verify?token='.$token.'&uuid='.$uuid;

$result = file_get_contents($url);
$resultArr = json_decode($result, true);
if(isset($resultArr['messageCode']) && $resultArr['messageCode'] == '10000'){
    $accountConn = $accountServer[$game_id];
    $conn = SetConn($accountConn);
    $channel_account = mysqli_real_escape_string($conn,$uuid.'@ailuoke');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","ailuoke_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_ailuoke_log_"," ailuoke new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
}else{
    write_log(ROOT_PATH."log","ailuoke_login_error_","result=$result,url=$url, ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}
exit('999 0');