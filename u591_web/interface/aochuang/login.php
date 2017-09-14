<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/19
 * Time: 上午10:02
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

$sessionId = base64_decode($_REQUEST['sessionId']);
$gameId = $_REQUEST['game_id'];

write_log(ROOT_PATH."log","aochuang_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$sessionId || !$gameId){
    write_log(ROOT_PATH."log","aochuang_login_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$appKey = $key_arr[$gameId]['appKey'];
$appId = $key_arr[$gameId]['appId'];
$url="http://api.xd178.cn/sdkapi.php";

$arr['ac'] = "check";
$arr['appid'] = $appId;
$arr['sessionid'] = urldecode($sessionId);
$arr["sdkversion"] = "3.2";
$arr['time'] = time();
ksort($arr);

$urlstr = http_build_query($arr);
$arr['sign'] = md5($urlstr.$appKey);
$rs = https_post($url,$arr);//验证登陆返回的数据
write_log(ROOT_PATH."log","aochuang_login_result_","result=$rs, md5Str=$urlstr, ".date("Y-m-d H:i:s")."\r\n");
$rsArr = json_decode($rs,true);
if(isset($rsArr['code']) && $rsArr['code'] == 1 && isset($rsArr['userInfo']['uid'])){

    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    $userId = $rsArr['userInfo']['uid'];
    $channel_account = mysqli_real_escape_string($conn,$userId.'@aochuang');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","aochuang_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
        write_log(ROOT_PATH."log","new_account_aochuang_log_","aochuang new account login, post=$post,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
} else {
    write_log(ROOT_PATH."log","aochuang_login_error_","sign error!, result=$rs, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
exit('999 0');