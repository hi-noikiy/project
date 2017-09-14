<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xiaomi_login_all_log_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$session_id = trim($_REQUEST['session_id']);
$uid = trim($_REQUEST['uid']);
$game_id = intval($_REQUEST['game_id']);
$game_id = $game_id ? $game_id : 3;

$appId = $key_arr[$game_id]['appId'];
$appSecret = $key_arr[$game_id]['appSecret'];

if(!$session_id||!$uid){
	write_log(ROOT_PATH."log","xiaomi_login_error_log_"," parameter error!, session_id=$session_id, uid=$uid, game_id=$game_id, ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}
$text = "appId=$appId&session=$session_id&uid=$uid";
$signature = get_signature($text, $appSecret);
$url = "http://mis.migc.xiaomi.com/api/biz/service/verifySession.do?appId=$appId&session=$session_id&uid=$uid&signature=$signature";
$result = file_get_contents($url);
write_log(ROOT_PATH."log","xiaomi_login_result_log_"," url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");

$result_arr = json_decode($result,true);

if($result_arr['errcode']=='200'){
    $conn = SetConn(81);
    $channel_account=mysqli_real_escape_string($conn,$uid.'@xiaomi');
    $username = time().'@xiaomi';
    $sql = " select id from account where channel_account = '$channel_account'";
    if(false == $query=mysqli_query($conn,$sql)){
    	write_log(ROOT_PATH."log","xiaomi_login_error_log_"," sql error!, sql=$sql, ".date("Y-m-d H:i:s")."\r\n");
    	exit('3 0');
    }
    $rs=mysqli_fetch_assoc($query);
    if($rs){
        $insert_id = $rs['id'];
        exit("0 $insert_id");
    }
    $insert_id = '';
    $password = random_common();
    $reg_time = date("ymdHi");
    $sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
    if(mysqli_query($conn,$sql_game) == false){
    	write_log(ROOT_PATH."log","xiaomi_login_error_log_"," sql error!, sql=$sql_game, ".date("Y-m-d H:i:s")."\r\n");
    	exit('3 0');
    }
    $insert_id = mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_xiaomi_log_"," xiaomi new account login!, get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
    write_log(ROOT_PATH."log","xiaomi_login_result_log_","result=$result,url=".$url.",sql_game=$sql_game, ".date("Y-m-d H:i:s")."\r\n");

}else{
	write_log(ROOT_PATH."log","xiaomi_login_error_log_"," sign error, result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
?>
