<?php

include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xiaomi_login_all_log_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$session_id = $_REQUEST['user_token'];
$game_id = $_REQUEST['game_id'];
$uid = $_REQUEST['uid'];

if(!$session_id||!$uid){
	write_log(ROOT_PATH."log","xiaomi_login_error_"," parameter error!, session_id=$session_id, uid=$uid, game_id=$game_id, ".date("Y-m-d H:i:s")."\r\n");
	exit("2 0");
}
$uids = explode('_', $uid);
$uid = $uids[0];
$type = isset($uids[1])?$uids[1]:'android';

$appId = $key_arr[$game_id][$type]['appId'];
$appSecret = $key_arr[$game_id][$type]['appSecret'];
$text = "appId=$appId&session=$session_id&uid=$uid";
$signature = get_signature($text, $appSecret);
$url = "http://mis.migc.xiaomi.com/api/biz/service/verifySession.do?appId=$appId&session=$session_id&uid=$uid&signature=$signature";
$result = https_post($url,array());
write_log(ROOT_PATH."log","xiaomi_login_result_log_"," url=$url, result=$result, ".date("Y-m-d H:i:s")."\r\n");

$result_arr = json_decode($result,true);

if($result_arr['errcode']=='200'){
		$memId = $uid;
        //CP操作,请求成功,用户有效
       global $accountServer;
        $accountConn = $accountServer[$game_id];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","xiaomi_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$memId.'@xiaomi');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","xiaomi_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
            write_log(ROOT_PATH."log","new_account_xiaomi_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }

}else{
	write_log(ROOT_PATH."log","xiaomi_login_error_"," sign error, result=$result, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
?>
