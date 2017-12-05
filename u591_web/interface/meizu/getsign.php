<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 * Time: 下午1:36
 */
include_once 'config.php';
$post = file_get_contents('php://input');
write_log(ROOT_PATH."log","meizu_sign_","post=$post ".date("Y-m-d H:i:s")."\r\n");
parse_str($post,$data);
$extendsInfo = $data['user_info']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$gameId = $extendsInfoArr[0];
$type = $extendsInfoArr[3];
global $key_arr;
$key = $key_arr[$gameId][$type]['appSecret'];
ksort($data);
$signstr = urldecode(http_build_query($data).':'.$key);
$sign = md5($signstr);
write_log(ROOT_PATH."log","meizu_sign_","signstr=$signstr,sign=$sign ".date("Y-m-d H:i:s")."\r\n");
exit($sign);