<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);

write_log(ROOT_PATH."log","xy_login_all_",  "post=$post, get=$get,"." ".date("Y-m-d H:i:s")."\r\n");

$game_id = $_GET['game_id'];
$uid =  $_GET['uid'];
$token =  $_GET['token'];

if(!$game_id||!$token||!$uid){
    write_log(ROOT_PATH."log","xy_login_error_log_"," 参数异常,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}
$appid = $arr_key[$game_id]['appid'];

$url = "http://passport.xyzs.com/checkLogin.php";
$data['uid'] = $uid;
$data['appid'] = $appid;
$data['token'] = $token;
$data_str = serialize($data);
$url_result = https_post($url, $data);

write_log(ROOT_PATH."log","xy_login_check_log_","  url=$url,url_result=$url_result,data_str=$data_str, get=$get, ".date("Y-m-d H:i:s")."\r\n");

$url_result_arr = json_decode($url_result, true);
if($url_result_arr['ret']==0){
   $accountConn = $accountServer[$game_id];
	$conn = SetConn($accountConn);
    $channel_account=mysqli_real_escape_string($conn,$uid.'@xy');
    $username = rand(10000,99999).time().'@xy';
    $sql = "select id from account where channel_account = '$channel_account'";
    $query= mysqli_query($conn, $sql);
    $result=array();
    if($query){
        $result= @mysqli_fetch_assoc($query);
    }else{
        write_log(ROOT_PATH."log","xy_login_error_log_"," 数据库异常,get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit('3 0');
    }
    if($result){
        $insert_id = $result['id'];
        write_log(ROOT_PATH."log","old_account_xy_log_","xy老登陆 ,get=$get, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("0 $insert_id");
    }
    $insert_id='';
    $password=random_common();
    $reg_time=date("ymdHi");
    $sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
    mysqli_query($conn,$sql_game);
    $insert_id = mysqli_insert_id($conn);
    if($insert_id){
        write_log(ROOT_PATH."log","new_account_xy_log_","xy新登陆 ,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
        exit("1 $insert_id");
    }else{
        $str=$sql_game."  ".mysqli_error($conn)." get=$get, ".date("Y-m-d H:i:s")."\r\n";
        write_log(ROOT_PATH."log","xy_login_error_log_",$str);
        exit("999");
    }
}else{
    write_log(ROOT_PATH."log","xy_login_error_log_"," 验证异常,url=$url,url_result=$url_result,data_str=$data_str, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
?>