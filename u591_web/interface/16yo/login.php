<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/3
 * Time: 下午1:36
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","16yo_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$userToken = $_REQUEST['user_token'];
$memId = $_REQUEST['mem_id'];
$gameId = $_REQUEST['game_id'];

if(!$userToken || !$memId || !$gameId){
    write_log(ROOT_PATH."log","16yo_info_log_","param error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}

$appid = isset($key_arr[$gameId]['appid']) ? $key_arr[$gameId]['appid'] : 0;
$appkey = isset($key_arr[$gameId]['appkey']) ? $key_arr[$gameId]['appkey'] : 0;

$data['user_token'] = $userToken;  //获取的user_token
$data['mem_id'] = $memId;	    //获取的用户ID
$data['app_id'] = $appid;       //获取的游戏APPID

$signstr = "app_id=".$data['app_id']."&mem_id=".$data['mem_id']."&user_token=".$data['user_token']."&app_key=".$appkey;

$data['sign'] = md5($signstr);
$params = json_encode($data);
$url = "http://www.16yo.cn/sdk/checkUsertoken.php";
$rdata = common_json_post($url, $params);

write_log(ROOT_PATH."log","16yo_result_log_","result=$rdata, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    $rdata = (array)json_decode($rdata);
    if('1' == $rdata['status']){
        //CP操作,请求成功,用户有效
        global $accountServer;
        $accountConn = $accountServer[$gameId];
        $conn = SetConn($accountConn);
        if($conn == false){
            write_log(ROOT_PATH."log","16yo_login_error_","account connect error. conn=$accountConn, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
            exit('3 0');
        }
        $channel_account = mysqli_real_escape_string($conn,$memId.'@16yo');
        $sql = "select id from account where channel_account='$channel_account' limit 1";
        if(false == $query = mysqli_query($conn, $sql)){
            write_log(ROOT_PATH."log","16yo_login_error_","mysql error. sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
            write_log(ROOT_PATH."log","new_account_16yo_log_","return=1 $insert_id, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
            exit("1 $insert_id");
        }
    }
}
write_log(ROOT_PATH."log","16yo_login_error_","result=$rdata, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('4 0');