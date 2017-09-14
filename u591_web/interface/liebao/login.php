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

$username = $_REQUEST['username'];
$logintime = $_REQUEST['logintime'];
$gameId = $_REQUEST['game_id'];

write_log(ROOT_PATH."log","liebao_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$username || !$logintime || !$gameId){
    write_log(ROOT_PATH."log","liebao_login_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$appGameId = $key_arr[$gameId]['gameId'];
$appKey = $key_arr[$gameId]['appKey'];
$signStr = "gameid=$appGameId&username=$username&logintime=$logintime&appkey=$appKey";
$data = array();
$data['username'] = $username;
$data['logintime'] = $logintime;
$data['gameid'] = $appGameId;
$data['sign'] = md5($signStr);

$url = 'http://sdk.51508.com/sdk/login/information.action';
$result = https_post($url, $data);
$resultArr = json_decode($result, true);

write_log(ROOT_PATH."log","liebao_login_result_","result=$result, md5Str=$signStr, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(isset($resultArr['code']) && $resultArr['code'] == 200){
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $channel_account = mysqli_real_escape_string($conn,$username.'@liebao');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","liebao_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_liebao_log_","liebao new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
} else {
    write_log(ROOT_PATH."log","liebao_login_error_","sign error!, result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
exit('999 0');