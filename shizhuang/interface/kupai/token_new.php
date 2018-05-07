<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$request = serialize($_REQUEST);

$str = "post=$post,get=$get,request=$request,";
write_log(ROOT_PATH."log","kupai_token_new_log_",$str." ".date("Y-m-d H:i:s")."\r\n");

$code = $_REQUEST['code'];
$game_id = $_REQUEST['game_id'];
$type = $_REQUEST['type'];

if(!$code||!$game_id||!$type){
    write_log(ROOT_PATH."log","kupai_login_error_log_"," 参数异常, str=$str ".date("Y-m-d H:i:s")."\r\n");
    exit(0);//参数异常
}
$config = $key_arr[$game_id][$type];
$url_user = "https://openapi.coolyun.com/oauth2/token?grant_type=authorization_code&client_id={$config['appId']}&redirect_uri={$config['appKey']}&client_secret={$config['appKey']}&code=$code";
$data = array();
$result_user = https_post($url_user,$data);
write_log(ROOT_PATH."log","kupai_token_new_result_log_"," result_user=$result_user, url=$url_user,".date("Y-m-d H:i:s")."\r\n");
exit($result_user);





?>
