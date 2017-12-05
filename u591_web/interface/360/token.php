<?php
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
$request = serialize($_REQUEST);

$str = "post=$post,get=$get,request=$request,";
write_log(ROOT_PATH."log","360_token_log_",$str." ".date("Y-m-d H:i:s")."\r\n");


$game_id = $_REQUEST['game_id'];
$action = $_REQUEST['action'];
$info = $_REQUEST['info'];

if(!$info||!$game_id||!$action){
    write_log(ROOT_PATH."log","360_token_error_log_"," 参数异常, str=$str ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}

if($action=='authorization'){

    $app_key = $key_arr[$game_id]['app_key'];
    $app_secret = $key_arr[$game_id]['app_secret'];
    $url = "https://openapi.360.cn/oauth2/access_token?grant_type=authorization_code&code=$info&client_id=$app_key&client_secret=$app_secret&redirect_uri=oob";

    $result = https_post($url,$data);
    write_log(ROOT_PATH."log","360_token_result_log_"," result=$result, url=$url,".date("Y-m-d H:i:s")."\r\n");

    $result_arr =json_decode($result,true);

    if(!$result_arr['access_token']){
        write_log(ROOT_PATH."log","360_token_error_log_"," token验证失败  , str=$str ".date("Y-m-d H:i:s")."\r\n");
        exit("4 0");
    }

    $access_token = $result_arr['access_token'];
    $expires_in = $result_arr['expires_in'];
    $refresh_token = $result_arr['refresh_token'];
    $scope = $result_arr['scope'];
    


    $url_user = "https://openapi.360.cn/user/me.json?access_token=".$access_token."&fields=id,name,avatar,sex,area";
    $result_user = https_post($url_user,$data);
    $result_user_arr = json_decode($result_user,true);
    write_log(ROOT_PATH."log","360_user_result_log_"," result_user=$result_user, url=$url_user,".date("Y-m-d H:i:s")."\r\n");

    $id_360 = $result_user_arr['id'];
    $name_360 = $result_user_arr['name'];
    $avatar_360 = $result_user_arr['avatar'];
    $sex_360 = $result_user_arr['sex'];
    $area_360 = $result_user_arr['area'];
    
    exit("0 ".$access_token."_".$expires_in."_".$refresh_token."_".$scope."_".$id_360."_".$name_360);
   // exit("0 ".$access_token."|".$expires_in."|".$refresh_token."|".$scope."|".$id_360."|".$name_360."|".$avatar_360);

}elseif($action=='refresh'){

    $info_arr = explode("_", $info);
    $access_token = $info_arr[0];
    $expires_in = $info_arr[1];
    $refresh_token = $info_arr[2];
    // $id_360 = $info_arr[3];

    $app_key = $key_arr[$game_id]['app_key'];
    $app_secret = $key_arr[$game_id]['app_secret'];


    $url = "https://openapi.360.cn/oauth2/access_token?grant_type=refresh_token&refresh_token=$refresh_token&client_id=$app_key&client_secret=$app_secret&scope=basic";
    $result = https_post($url,$data);
    write_log(ROOT_PATH."log","360_refresh_token_result_log_"," result=$result, url=$url,".date("Y-m-d H:i:s")."\r\n");

    $result_arr =json_decode($result,true);
    $access_token_new = $result_arr['access_token'];
    $expires_in_new = $result_arr['expires_in'];
    $refresh_token_new = $result_arr['refresh_token'];
    $scope_new = $result_arr['scope'];
    if($access_token_new){
        exit("0 ".$access_token_new."_".$expires_in_new."_".$refresh_token_new."_".$scope_new);
    }else{
        write_log(ROOT_PATH."log","360_token_error_log_"," token刷新失败 , str=$str  ".date("Y-m-d H:i:s")."\r\n");
        exit("4 0");
    }


}else{
    write_log(ROOT_PATH."log","360_token_error_log_"," 参数异常, str=$str ".date("Y-m-d H:i:s")."\r\n");
    exit("2 0");//参数异常
}



?>
