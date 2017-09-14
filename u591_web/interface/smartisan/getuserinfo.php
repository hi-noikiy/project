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
write_log(ROOT_PATH."log","smartisan_userinfo_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
global $key_arr;
$gameId = 8;
$data['client_id'] = $key_arr[$gameId]['android']['client_id'];
$data['client_secret'] = $key_arr[$gameId]['android']['client_secret'];
$data['grant_type'] = 'authorization_code';
$data['code'] = $_REQUEST['code'];
$url = "https://api.smartisan.com/oauth/token";
$rdata = https_post($url, $data);

write_log(ROOT_PATH."log","smartisan_userinfo_result_log_","result=".$rdata.", post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
if($rdata){
    echo  $rdata;
}
echo json_encode(array());