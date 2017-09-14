<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/7
 * Time: 下午2:06
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

$userId = $_REQUEST['user_id'];
$token = $_REQUEST['token'];
$gameId = $_REQUEST['game_id'];
write_log(ROOT_PATH."log","longxia_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$userId || !$token || !$gameId){
    write_log(ROOT_PATH."log","longxia_login_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$url = 'http://longxiasy.com/sdk.php/LoginNotify/login_verify';
$data = array();
$data['user_id'] = $userId;
$data['token'] = $token;
$result = https_post($url, $data);
$resultArr = json_decode($result, true);

if(isset($resultArr['status']) && $resultArr['status'] == 1){
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $userId = $resultArr['user_id'];
    $channel_account = mysqli_real_escape_string($conn,$userId.'@longxia');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","longxia_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_longxia_log_","longxia new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
} else {
    write_log(ROOT_PATH."log","longxia_login_error_","sign error!, result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
exit('999 0');