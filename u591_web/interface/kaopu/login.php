<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 靠谱登陆接口
* ==============================================
* @date: 2015-10-23
* @author: Administrator
* @return:
*   "2 0"      参数异常
*   '3 0'      sql异常
*   "4 0"      验证出错
*   "999 0"    未知错误
*   "0 $insert_id"       二次登陆返回
*   "1 $insert_id"       首次登陆返回
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
//$str ='a:3:{s:3:"sid";s:100:"android_99000807964894_1_kaopu_0E0757FF95EFC58D5598190A78E0A920_2f15ed0f332e7ef040057feb1d827b04_1.0";s:5:"token";s:32:"27CF0744806EAC6CFD6D78550DD7C44A";s:7:"game_id";s:1:"8";}';
//$str = unserialize($str);
//$_REQUEST = $str;
write_log(ROOT_PATH."log","kaopu_login_all_","post=$post, get=$get, host={$host}".date("Y-m-d H:i:s")."\r\n");
$sid = trim($_REQUEST['sid']);
$game_id = trim($_REQUEST['game_id']);
$token = $_REQUEST['token']; //用户通信token
if(!$sid || !$game_id || !$token){
    write_log(ROOT_PATH."log","kaopu_login_error_"," parameters error , post=$post, get=$get,  ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");
}

$exSid = explode('_', $sid);
$devicetype = $exSid[0];
$imei = $exSid[1];
$r = intval($exSid[2]);
$channelkey = $exSid[3];
$openid = $exSid[4];  //用户id
$sign = $exSid[5];
$version = $exSid[6];
if(preg_match("/[^\.]+\.kpzs\.com$/", $token)){//传的是url
	$url = $token;
}else{
	$tag = $arr_key[$game_id]['APPKEY'];
	$tagid = $arr_key[$game_id]['SECRETKEY'];
	$appid = $arr_key[$game_id]['APPID'];
	
	$sign = md5($appid.$channelkey.$imei.$r.$tag.$tagid.$version.$four_r[$r]);
	
	$url = "http://sdk.geturl.kpzs.com/api/UserAuthUrl?tag=$tag&tagid=$tagid&appid=$appid&version=$version&imei=$imei&channelkey=$channelkey&r=$r&sign=$sign";
}


$data = array();
$url_result = https_post($url, $data);
write_log(ROOT_PATH."log","kaopu_login_check_log_"," url=$url,url_result=$url_result, ".date("Y-m-d H:i:s")."\r\n");

$url_result_arr = json_decode($url_result, true);
if($url_result_arr['code'] != 1){
	write_log(ROOT_PATH."log","kaopu_login_error_log_"," sign error,url=$url,url_result=$url_result, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");//验证异常
}

$sign2 = md5($appid.$channelkey.$devicetype.$imei.$openid.$r.$tag.$tagid.$token.$four_r[$r]);
$url = $url_result_arr['data']['url'];
$url = $url."?devicetype=$devicetype&imei=$imei&r=$r&tag=$tag&tagid=$tagid&appid=$appid&channelkey=$channelkey&openid=$openid&token=$token&sign=$sign2";

$url_result2 = https_post($url, $data);
write_log(ROOT_PATH."log","kaopu_login_check_log_"," url=$url,url_result=$url_result2, ".date("Y-m-d H:i:s")."\r\n");

$result = json_decode($url_result2, true);

if($result['code'] != 1){
	write_log(ROOT_PATH."log","kaopu_login_error_log_"," sign error, url=$url, url_result=$url_result2, ".date("Y-m-d H:i:s")."\r\n");
	exit("4 0");//验证异常
}
 $accountConn = $accountServer[$game_id];
$conn = $conn = SetConn($accountConn);
$channel_account=mysqli_real_escape_string($conn, $openid.'@kaopu');
$username = rand(10000,99999).time().'@kaopu';
$sql = "select id from account where channel_account = '$channel_account'";
$query = @mysqli_query($conn,$sql);
$result=array();

if($query){
	$result = @mysqli_fetch_assoc($query);
}else{
	write_log(ROOT_PATH."log","kaopu_login_error_log_"," mysql error: ".mysqli_error()."  ,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
if($result){
	$insert_id = $result['id'];
	write_log(ROOT_PATH."log","old_account_kaopu_log_","old account login , get=$get, "."return= 0 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("0 $insert_id");
}
$insert_id='';
$password=random_common();
$reg_time=date("ymdHi");
$sql_game = "insert into account (NAME,password,reg_date,channel_account) VALUES ('$username','$password','$reg_time','$channel_account')";
@mysqli_query($conn, $sql_game);
$insert_id = mysqli_insert_id($conn);
if($insert_id){
	write_log(ROOT_PATH."log","new_account_kaopu_log_","new account login ,get=$get, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("1 $insert_id");
}else{
	write_log(ROOT_PATH."log","kaopu_login_error_log_", "$sql_game ".mysqli_error($conn)." ".date('Y-m-d H:i:s')."\r\n");
	exit("0");
}
?>