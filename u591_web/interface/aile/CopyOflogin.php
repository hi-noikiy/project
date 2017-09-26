<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午7:15
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","aile_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$userToken = $_REQUEST['user_token'];//获取的user_token
$memId = $_REQUEST['mem_id'];//获取的用户ID
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$memId || !$gameId){
    write_log(ROOT_PATH."log","aile_login_error_"," parameter is error ,post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}

$appid = $key_arr[$gameId]['appId'];
$appkey = $key_arr[$gameId]['appKey'];

$data = array();
$data['app_id'] = $appid;
$data['mem_id'] = $memId;
$data['user_token'] = $userToken;

$signstr = "app_id=".$data['app_id']."&mem_id=".$data['mem_id']."&user_token=".$data['user_token']."&app_key=".$appkey;
$data['sign'] = md5($signstr);
$params = json_encode($data);
$url = "http://api.2lyx.com/api/cp/user/check";
$rdata = https_post($url, $data);
//$rdata = common_json_post($url, $params);
write_log(ROOT_PATH."log","aile_result_log_","result=$rdata,param=$params".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $rdata = (array)json_decode($rdata);
    if('1' == $rdata['status']){
        //CP操作,请求成功,用户有效
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","aile_login_error_","$accountConn,,account connect error, ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$memId.'@aile');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","aile_login_error_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
            write_log(ROOT_PATH."log","new_account_aile_log_","return=1 $insert_id, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }
    } else {
        write_log(ROOT_PATH."log","aile_login_error_","result=$rdata, ".date("Y-m-d H:i:s")."\r\n");
        exit('4 0');
    }
} else {
    write_log(ROOT_PATH."log","aile_login_error_","result=$rdata, ".date("Y-m-d H:i:s")."\r\n");
    exit('4 0');
}
exit('999 0');