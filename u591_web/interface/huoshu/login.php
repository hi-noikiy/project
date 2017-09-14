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
write_log(ROOT_PATH."log","huoshu_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userToken = $_REQUEST['user_token'];
$appUid = $_REQUEST['uid'];
$gameId = $_REQUEST['game_id'];
$appUidArr = explode('_', $appUid);
$memId = $appUidArr[0];
$type = $appUidArr[1];

if(!$userToken || !$memId || !$gameId || !$type){
    write_log(ROOT_PATH."log","huoshu_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}

$appid = $key_arr[$gameId][$type]['appId'];
$appkey = $key_arr[$gameId][$type]['appSecret'];

$data['app_id'] = $appid;       //获取的游戏APPID
$data['mem_id'] = $memId;	    //获取的用户ID
$data['user_token'] = $userToken;  //获取的user_token

$signstr = "app_id=".$data['app_id']."&mem_id=".$data['mem_id']."&user_token=".$data['user_token']."&app_key=".$appkey;

$data['sign'] = md5($signstr);
$url = "http://qhios.1tsdk.com/api/v7/cp/user/check";
if(substr($type,0,7) == 'android'){
	$url = "http://sdk.huoyx.cn/api/cp/user/check";
}
$rdata = https_post($url, $data);

write_log(ROOT_PATH."log","huoshu_result_log_","result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $rdata = (array)json_decode($rdata);
    if('1' == $rdata['status']){
        //CP操作,请求成功,用户有效
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","huoshu_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$memId.'@huoshu');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","huoshu_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
            write_log(ROOT_PATH."log","new_account_huoshu_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }
    }
}
write_log(ROOT_PATH."log","huoshu_login_error_","result=".json_encode($rdata).", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');