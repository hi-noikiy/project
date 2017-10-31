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
write_log(ROOT_PATH."log","vk_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$uid = $_REQUEST['uid'];
$userToken = $_REQUEST['user_token'];
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$gameId){
    write_log(ROOT_PATH."log","vk_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
global $key_arr;
$uids = explode('_', $uid);
$uid = $uids[0];
$type = $uids[1];
$appid= $key_arr[$gameId][$type]['appid'];
$appkey= $key_arr[$gameId][$type]['appkey'];
$url = "https://oauth.vk.com/access_token?client_id=$appid&client_secret=$appkey&v=5.1&grant_type=client_credentials";
$rdata = https_post($url, $data);
$rdata= json_decode($rdata,true);
$token = $rdata['access_token'];
$url = "https://api.vk.com/method/secure.checkToken?token=$userToken&client_secret=$appkey&access_token=$token";
$rdata = https_post($url, $data);

write_log(ROOT_PATH."log","vk_result_log_",$url.",result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $rdata = json_decode($rdata,true);
    if('1' == $rdata['response']['success']){
    	$memId = $rdata['response']['user_id'];
        //CP操作,请求成功,用户有效
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","vk_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$memId.'@vk');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","vk_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $result = @mysqli_fetch_assoc($query);
        if($result){
            $insert_id = $result['id'];
            exit("0 $insert_id");
        }
        //是否绑定的帐号
        $sql = "select account_id from account_ggp where ggp_account='$channel_account' limit 1;";
        if(false == $query = mysqli_query($conn, $sql)){
        	write_log(ROOT_PATH."log","vklogin_error_","sql error,sql=$sql,". mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        	exit('3 0');
        }
        $result = @mysqli_fetch_assoc($query);
        if(isset($result['account_id'])){
        	$accountId = $result['account_id'];
        	write_log(ROOT_PATH."log","vklogin_ggp_log_","return=0 $accountId, ".date("Y-m-d H:i:s")."\r\n");
        	exit("0 $accountId");
        }
        
        $insert_id = '';
        $password = random_common();
        $reg_time = date("ymdHi");
        $sql_game = "insert into account (NAME,password,reg_date, channel_account) VALUES ('$channel_account','$password','$reg_time', '$channel_account')";
        @mysqli_query($conn, $sql_game);
        $insert_id = @mysqli_insert_id($conn);
        if($insert_id){
            write_log(ROOT_PATH."log","new_account_vk_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }
    }
}
write_log(ROOT_PATH."log","vk_login_error_",$token.",result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');