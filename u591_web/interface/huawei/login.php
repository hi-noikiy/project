<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 * Time: 下午1:36
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","huawei_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$userToken = $_REQUEST['user_token'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];
$appUidArr = explode('_', $userToken);
$appId = $appUidArr[0];
$ts = $appUidArr[1];
$playerId = $appUidArr[2];
$sign = urldecode($appUidArr[3]);
$sign = str_replace(' ','+',$sign);

if(!$appId || !$ts || !$playerId || !$sign){
	write_log(ROOT_PATH."log","huawei_login_error_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
// 使用从客户端上传过来的参数
$content = $appId.$ts.$playerId;

// .pem文件是一致的，请使用同目录下带的payPu
$filename = dirname(__FILE__)."/payPublicKey.pem";

if(!file_exists($filename))
{
	write_log(ROOT_PATH."log","huawei_login_error_log_","file is not exit. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}
$pubKey = @file_get_contents($filename);
$openssl_public_key = @openssl_get_publickey($pubKey);
write_log(ROOT_PATH."log","huawei_login_log_","content={$content},sign={$sign},openssl_public_key={$openssl_public_key},post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
$ok = @openssl_verify($content, base64_decode($sign), $openssl_public_key, OPENSSL_ALGO_SHA256);
@openssl_free_key($openssl_public_key);
if($ok)
{
		//CP操作,请求成功,用户有效
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","huawei_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$playerId.'@huawei');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","huawei_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
            write_log(ROOT_PATH."log","new_account_huawei_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }
}
else
{
	write_log(ROOT_PATH."log","huawei_login_error_","sign error, ".$sign."post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('4 0');
}
