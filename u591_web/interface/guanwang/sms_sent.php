<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 短信发送接口
* ==============================================
* @date: 2016-7-14
* @author: luoxue
* @version:
*/
include_once 'config.php';
$post = serialize($_POST);
write_log(ROOT_PATH."log","duanxin_sent_all_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");

$game_id = intval($_REQUEST['game_id']);
$sign = trim($_REQUEST['sign']);
$phone = $_REQUEST['phone'];
$appKey = $key_arr['appKey'];

if(strlen($phone) != 11 || !preg_match('/^1[34578]{1}\d{9}$/', $phone))
	exit(json_encode(array('status'=>1, 'msg'=>'phone format error.')));

$params = array(
		'phone',
		'game_id',
		'sign'		
);

for ($i = 0; $i< count($params); $i++){
	if (!isset($_REQUEST[$params[$i]])) {
		exit(json_encode(array('status'=>1, 'msg'=>'Missing '.$params[$i])));
	} else {
		if(empty($_REQUEST[$params[$i]])) 
			exit(json_encode(array('status'=>1, 'msg'=>$params[$i].' should not be empty.')));
	}
}
if(!$appKey)
	exit(json_encode(array('status'=>1, 'msg'=>'appKey error.')));
$array['phone'] = $phone;
$array['game_id'] = $game_id;
ksort($array);
$md5Str = http_build_query($array);
$my_sign = md5($md5Str.$appKey);

if($sign != $my_sign){
	write_log(ROOT_PATH."log","duanxin_sent_error_log_",$md5Str.$appKey.",sign=$sign, mySign=$my_sign, ".date("Y-m-d H:i:s")."\r\n");
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));
}
$OperID = "hainwl";
$OperPass = "hnsy47";
$conn = SetConn('88');
$code = rand(1000,9999);

$sql = "select * from web_message where game_id='$game_id' and username='$phone' limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));

$rs = @mysqli_fetch_assoc($query);
$nowTime = time();
if(!empty($rs['addtime'])){
	if($nowTime-$rs['addtime'] < 60)
		exit(json_encode(array('status'=>1, 'msg'=>'can not repeatedly transmitted within 60 seconds.')));
}
$iSql= "insert into web_message(game_id, username, code, addtime) values('$game_id', '$phone', '$code', '$nowTime')";
if(false == mysqli_query($conn,$iSql))
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));

$content = "【海牛网络】".$code.",退定回复TD";
$content = iconv("UTF-8","GBK",$content);
$content = urlencode($content);
$url = "http://221.179.172.68:8000/QxtSms/QxtFirewall?OperID=$OperID&OperPass=$OperPass&SendTime=&ValidTime=&AppendID=&DesMobile=$phone&Content=$content&ContentType=8";
$data = array();
$result =  https_post($url, $data);
write_log(ROOT_PATH."log","duanxin_sent_result_log_"," result=$result, post=$post, ".date("Y-m-d H:i:s")."\r\n");

$xml_arr = simplexml_load_string($result);
$code = $xml_arr->code;
if($code == '06')
	exit(json_encode(array('status'=>1, 'msg'=>'remaining SMS inadequate.')));

if($code=="00" || $code=="01" || $code=="03")
	exit(json_encode(array('status'=>0, 'msg'=>'success')));

exit(json_encode(array('status'=>1, 'msg'=>'other error.')));