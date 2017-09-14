<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/3/23 下午2:14
 * @desc:星宇登录
 * @param:
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xingyu_login_info_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$userId = $_REQUEST['user_id'];
$token = $_REQUEST['token'];
$gameId = $_REQUEST['game_id'];

if(!$userId || !$token || !$gameId){
    write_log(ROOT_PATH."log","xingyu_login_error_","parameter error! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$url = "http://www.xingyuyou.com/sdk.php/LoginNotify/login_verify";
$data = array();
$data['user_id'] = $userId;
$data['token'] = $token;
$data = json_encode($data);
$result = common_json_post($url, $data);
write_log(ROOT_PATH."log","xingyu_login_result_","result=$result, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$resultArr = json_decode($result, true);
if(isset($resultArr['status']) && $resultArr['status'] == 1){
    global $accountServer;
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    if($conn == false){
        write_log(ROOT_PATH."log","xingyu_login_error_","mysql connect error. conn=$accountConn, ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    $channel_account = @mysqli_real_escape_string($conn,$userId.'@xingyu');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","xingyu_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_xingyu_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
    exit('999 0');
} else {
    write_log(ROOT_PATH."log","xingyu_login_error_","result=$result, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}

