<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/3/31 下午7:18
 * @desc:逗游登录验证
 * @param:
 * @return:
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

$gameId = $_REQUEST['game_id'];
$username = $_REQUEST['username'];
$memKey = $_REQUEST['memkey'];
write_log(ROOT_PATH."log","douyou_login_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$gameId  || !$username || !$memKey){
    write_log(ROOT_PATH."log","douyou_login_error_","params error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$url = "http://sdkapi.douyou8.com/islogin.php";
$data = array();
$data['username'] = $username;
$data['memkey'] = $memKey;
$result = https_post($url, $data);
if($result == 'success'){
    global $accountServer;
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    if($conn == false){
        write_log(ROOT_PATH."log","douyou_login_error_","account mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    $channel_account = @mysqli_real_escape_string($conn,$username.'@douyou');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","douyou_login_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
    if(false == mysqli_query($conn, $sql_game)){
        write_log(ROOT_PATH."log","douyou_login_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    $insert_id = @mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_douyou_log_","new account login. return= 1 $insert_id, ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
    exit('999 0');
} else {
    write_log(ROOT_PATH."log","douyou_login_error_","sign error!, result=$result,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}