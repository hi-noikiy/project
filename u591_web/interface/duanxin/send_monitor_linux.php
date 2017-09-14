<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 短信发送登陆接口
 * ==============================================
 * @date: 2016-5-5
 * @author: Administrator
 */
include_once 'config.php';
header("Content-type:text/html;charset=utf-8");
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","duanxin_linux_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$game_id = $_REQUEST['game_id'];
$sign = $_REQUEST['sign'];
$content = $_REQUEST['content'];

global $key_arr;
$key = $key_arr[$game_id]['key'];
$my_sign = md5($game_id."&".$key);
if($sign!=$my_sign){
    exit("4 0");
}

$OperID = "hainwl";
$OperPass = "hnsy47";
//$telArr = $key_arr[$game_id]['tel'];
$telArr = array('15059449082','17071930091');
$i=0;
$tel = '';
foreach($telArr as $key=>$value){
    if($i==0)
        $tel = $value;
    else
        $tel .= ",".$value;
    $i++;
}
$content = $content ? $content:"短信测试ABC";
$content = "【海牛网络】".$content.",退定回复0000";
$content=iconv("UTF-8", "GBK", $content);
$content = urlencode($content);
$url = "http://221.179.172.68:8000/QxtSms/QxtFirewall?OperID=$OperID&OperPass=$OperPass&SendTime=&ValidTime=&AppendID=&DesMobile=$tel&Content=$content&ContentType=8";
$data = array();
$result =  https_post($url, $data);
write_log(ROOT_PATH."log","duanxin_linux_result_log_","result=$result,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$xml_arr = simplexml_load_string($result);

$code = $xml_arr->code."";
if($code=='06'){
    write_log(ROOT_PATH."log","duanxin_linux_error_log_","url=$url,result=$result,post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("3 0");
}
if($code=="00"||$code=="01"||$code=="03"){
    exit("0 0");
}
write_log(ROOT_PATH."log","duanxin_linux_error_log_","other error. url=$url,result=$result, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit("5 0");