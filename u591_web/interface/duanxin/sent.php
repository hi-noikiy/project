<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 短信发送登陆接口
* ==============================================
* @date: 2016-5-5
* @author: Administrator
* @return:
* 	"2 0"      参数异常
*   '3 0'      sql异常
*   "4 0"      验证出错
*   "999 0"    未知错误
*   "0 0"      发送成功
*   "1 0"      不能重复发送
*/
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","duanxin_sent_all_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$game_id = intval($_REQUEST['game_id']);
$sign = trim($_REQUEST['sign']);
$phone = $_REQUEST['phone'];
$key = $key_arr[$game_id]['key'];

if(strlen($phone) != 11 || !preg_match('/^1[34578]{1}\d{9}$/', $phone)){
	write_log(ROOT_PATH."log","duanxin_sent_error_log_","params error! game_id=$game_id,sign=$sign,phone=$phone,key=$key, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}

if(!$game_id || !$sign || !$phone || !$key){
	write_log(ROOT_PATH."log","duanxin_sent_error_log_","params error! game_id=$game_id,sign=$sign,phone=$phone,key=$key, ".date("Y-m-d H:i:s")."\r\n");
	exit('2 0');
}

$my_sign = md5($game_id."&".$key);
if($sign != $my_sign){
	write_log(ROOT_PATH."log","duanxin_sent_error_log_","sign error! sign=$sign,my_sign=$my_sign, ".date("Y-m-d H:i:s")."\r\n");
    exit("4 0");
}
$OperID = "hainwl";
$OperPass = "hnsy47";
$conn = SetConn('88');
$code = rand(1000,9999);


$sql = "select * from web_message where game_id='$game_id' and username='$phone' limit 1";
if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","duanxin_sent_error_log_","mysql error! sql=$sql, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}
$rs = mysqli_fetch_assoc($query);
$nowTime = time();
if(!empty($rs['addtime'])){
	if($nowTime-$rs['addtime'] <60)
		exit('1 0');//不能重复发送
}
$iSql= "insert into web_message(game_id, username, code, addtime) values('$game_id', '$phone', '$code', '$nowTime')";
if(false == mysqli_query($conn,$iSql)){
	write_log(ROOT_PATH."log","duanxin_sent_error_log_","mysql error! sql=$sql, ".mysqli_error($conn).", ".date("Y-m-d H:i:s")."\r\n");
	exit('3 0');
}

$content = "【海牛网络】".$code.",退定回复0000";
$content =iconv("UTF-8","GBK",$content);
$content = urlencode($content);
$url = "http://221.179.172.68:8000/QxtSms/QxtFirewall?OperID=$OperID&OperPass=$OperPass&SendTime=&ValidTime=&AppendID=&DesMobile=$phone&Content=$content&ContentType=8";
$data = array();
$result =  https_post($url, $data);
write_log(ROOT_PATH."log","duanxin_sent_result_log_"," result=$result, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");


$xml_arr = simplexml_load_string($result);
$code = $xml_arr->code;
if($code=='06'){
    write_log(ROOT_PATH."log","duanxin_sent_error_log_","剩余短信不足 url=$url, result=$result, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("3 0");
}
if($code=="00" || $code=="01" || $code=="03"){
	
   	exit("0 0");
}
write_log(ROOT_PATH."log","duanxin_sent_error_log_","其他错误 url=$url, result=$result, post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit("999 0");
?>
