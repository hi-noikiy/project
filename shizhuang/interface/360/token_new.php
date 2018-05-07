<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$request = serialize($_REQUEST);

$str = "post=$post,get=$get,request=$request,";
write_log(ROOT_PATH."log","360_token_new_log_",$str." ".date("Y-m-d H:i:s")."\r\n");

$access_token = $_REQUEST['access_token'];
$game_id = $_REQUEST['game_id'];

if(!$access_token||!$game_id){
    write_log(ROOT_PATH."log","360_login_error_log_"," 参数异常, str=$str ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}

$url_user = "https://openapi.360.cn/user/me.json?access_token=".$access_token."&fields=id,name,avatar,sex,area";
$data = array();
$result_user = https_post($url_user,$data);
$result_user_arr = json_decode($result_user,true);
write_log(ROOT_PATH."log","360_token_new_result_log_"," result_user=$result_user, url=$url_user,".date("Y-m-d H:i:s")."\r\n");

$id_360 = $result_user_arr['id'];
$avatar = $result_user_arr['avatar'];
$name = $result_user_arr['name'];

if($id_360){
   exit("0 $id_360 $avatar $name");
}else{
    write_log(ROOT_PATH."log","360_login_error_log_"," user验证异常 ,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}

exit("999 0");





?>
