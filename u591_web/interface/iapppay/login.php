<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午8:16
 * 登录走官网。保留
 */
include_once 'config.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","iapppay_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$logintoken = $_REQUEST['logintoken'];
$sign = $_REQUEST['Sign'];
$gameId = $_REQUEST['game_id'];

if(!$logintoken || !$sign || !$gameId){
    write_log(ROOT_PATH."log","iapppay_login_error_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit('2 0');
}
$logintokenArr = explode('_', $logintoken);
$type = isset($appUidArr[1]) ? strtolower($appUidArr[1]) : 'ios';

$appId = $key_arr[$gameId][$type]['appId'];
$appKey = $key_arr[$gameId][$type]['appKey'];
$logintoken = $logintokenArr[0];

$contentdata = array();
$contentdata["appid"] = $appId;
$contentdata["logintoken"] = $logintoken;//这个需要调登录接口时时获取。有效期10min
$reqData = composeReq($contentdata, $appKey);

$url = 'http://ipay.iapppay.com:9999/openid/openidcheck';
$result = common_json_post($url, $reqData);

write_log(ROOT_PATH."log","iapppay_login_result_","result=$result, ".date("Y-m-d H:i:s")."\r\n");
