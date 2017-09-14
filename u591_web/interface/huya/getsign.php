<?php
/**
 * Created by PhpStorm.
 * User: wangtao
 * Date: 2017/5/24
 * Time: 下午1:36
 */
include_once 'config.php';
$post = file_get_contents('php://input');
write_log(ROOT_PATH."log","huya_sign_","post=$post ".date("Y-m-d H:i:s")."\r\n");
$gameId = 8;
$data = json_decode($post,true);
$extendsInfo = $data['userDefine']; //提取拓展信息
$extendsInfoArr = explode('_', $extendsInfo);
$type = $extendsInfoArr[3];
global $key_arr;
$key = $key_arr[$gameId][$type]['appSecret'];
ksort($data);
$signstr = '';
foreach ($data as $k=>$v){
	$signstr .= "$k=$v";
}
$signstr .= $key;
$sign = md5($signstr);
write_log(ROOT_PATH."log","huya_sign_","signstr=$signstr,sign=$sign ".date("Y-m-d H:i:s")."\r\n");
exit($sign);