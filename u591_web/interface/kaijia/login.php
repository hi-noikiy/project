<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/5/12 上午9:26
 * @desc:
 * @param:
 * @return:
 */
include 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

$gameId = $_REQUEST['game_id'];
$sessionid = base64_decode($_REQUEST['sessionid']);
$type = $_REQUEST['type'];
$time = time();
write_log(ROOT_PATH."log","kaijia_login_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$gameId  || !$sessionid || !$type){
    write_log(ROOT_PATH."log","kaijia_login_error_","params error. post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$appid = isset($key_arr[$gameId][$type]['appid']) ? $key_arr[$gameId][$type]['appid'] : '';
$appkey = isset($key_arr[$gameId][$type]['appkey']) ? $key_arr[$gameId][$type]['appkey'] : '';
$url = "http://api.kaijia.com/sdkapi.php";
$data = array();
$data['ac'] = 'check';
$data['appid'] = $appid;
$data['sdkversion'] = '4.1';
$data['sessionid'] = urldecode($sessionid);
if(substr($type, 0 , 3) == 'ios'){
	$data['sdkversion'] = '2.1.4';
	$data['sessionid'] = str_replace(' ','+', $data['sessionid']);
}

$data['time'] = $time;

$str = "ac={$data['ac']}&appid={$data['appid']}&sdkversion={$data['sdkversion']}&sessionid=".urlencode($data['sessionid'])."&time={$data['time']}".$appkey;
$sign = md5($str);
$data['sign'] = $sign;

$result = https_post($url, $data);
$resultArr = json_decode($result, true);
if(isset($resultArr['code']) && $resultArr['code'] == '1'){
    global $accountServer;
    $accountConn = $accountServer[$gameId];
    $conn = SetConn($accountConn);
    if($conn == false){
        write_log(ROOT_PATH."log","kaijia_login_error_","account mysql connect error. ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    $uid = $resultArr['userInfo']['uid'];
    $channel_account = @mysqli_real_escape_string($conn,$uid.'@kaijia');
    $sql = "select id from account where channel_account='$channel_account' limit 1";
    if(false == $query = mysqli_query($conn,$sql)){
        write_log(ROOT_PATH."log","kaijia_login_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
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
    if(false == mysqli_query($conn, $sql_game)){
        write_log(ROOT_PATH."log","kaijia_login_error_","sql=$sql, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    $insert_id = @mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_kaijia_log_","new account login. return= 1 $insert_id, ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }
    exit('999 0');
} else {
    write_log(ROOT_PATH."log","kaijia_login_error_","sign error!, $str,".json_encode($data)."result=$result,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}