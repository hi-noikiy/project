<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/15
 * Time: 上午10:33
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xingqu_login_all_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");


$accessToken = $_REQUEST['access_token'];
$cchId = $_REQUEST['cch_id'];
$gameId = $_REQUEST['game_id'];


if(!$accessToken || !$cchId || !$gameId){
    write_log(ROOT_PATH."log","xingqu_login_error_log_"," parameter error! post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}

$appId = $key_arr[$gameId]['appid'];
$appKey = $key_arr[$gameId]['appkey'];

$data = array(
    'app_id'        =>$appId,
    'cch_id'        =>$cchId,
    'access_token'  =>$accessToken,
    'tm'            =>time(),
);
$sign = xingquSign($data, $appKey);
$data['sign'] = $sign;
$url = 'http://access_token.starjoys.com/Token/verify/';
$rs = https_post($url, $data);
$rsArr = json_decode($rs, true);
if(isset($rsArr['state']) && $rsArr['state'] == 1){
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    if($conn == false){
        write_log(ROOT_PATH."log","xingqu_login_error_","mysql connect error. conn=$accountConn, ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }

    $openid = $rsArr['data']['openid'];
    $channel_account = @mysqli_real_escape_string($conn,$openid.'@xingqu');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","xingqu_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_xingqu_log_","xingqu new account login. return= 1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
} else {
    write_log(ROOT_PATH."log","xingqu_login_error_log_","sign error, result=$rs, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
exit('999 0');