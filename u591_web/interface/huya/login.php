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
write_log(ROOT_PATH."log","huya_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$packageName = $_REQUEST['user_token'];
$gameId = $_REQUEST['game_id'];
$access_token = $_REQUEST['uid'];
$access_tokens = explode('_', $access_token);
$access_token = $access_tokens[0];
$logintype = isset($access_tokens[1])?$access_tokens[1]:'0';
if(!$access_token || !$gameId || !$packageName){
    write_log(ROOT_PATH."log","huya_login_error_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
if($logintype == 5){
	global $key_arr;
	$type = $access_tokens[2];
	$key = $key_arr[$gameId][$type]['appId'];
	$signstr = "visitorUserId=$access_token&gameId=$key";
	$url = "http://api3rd-gameunion.tuboshu.com/api/union/v1/user/checkVisitor4Game?$signstr";
}else{
	$signstr = "userKey=$access_token&packageName=$packageName";
	$url = "http://api3rd-gameunion.tuboshu.com/api/union/v1/user/checkUser4Game?$signstr";
}


$rdata = https_post($url, $data);

write_log(ROOT_PATH."log","huya_result_log_","$url,result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $rdata = json_decode($rdata,true);
    if('200' == $rdata['status']){
    	$memId = $rdata['data']['user']['visitorUserId']?$rdata['data']['user']['visitorUserId']:$rdata['data']['user']['userId'];
        //CP操作,请求成功,用户有效
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","huya_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$memId.'@huya');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","huya_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
            write_log(ROOT_PATH."log","new_account_huya_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }
    }
}
write_log(ROOT_PATH."log","huya_login_error_","result=". json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');