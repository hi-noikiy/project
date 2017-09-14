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

$game_id = intval($_POST['game_id']);
$sign = trim($_POST['sign']);
$phone = $_POST['phone'];
$appKey = $key_arr['appKey'];

if(strlen($phone) != 11 || !preg_match('/^1[34578]{1}\d{9}$/', $phone))
	exit(json_encode(array('status'=>2, 'msg'=>'手机格式错误.')));

$params = array(
		'phone',
		'game_id',
		'sign'		
);

for ($i = 0; $i< count($params); $i++){
	if (!isset($_POST[$params[$i]])) {
		exit(json_encode(array('status'=>1, 'msg'=>'缺失参数'.$params[$i])));
	} else {
		if(empty($_POST[$params[$i]])) 
			exit(json_encode(array('status'=>1, 'msg'=>$params[$i].'参数不能为空.')));
	}
}
if(!$appKey)
	exit(json_encode(array('status'=>1, 'msg'=>'appKey error.')));
$array['phone'] = $phone;
$array['game_id'] = $game_id;
ksort($array);
$md5Str = http_build_query($array);
$my_sign = md5($md5Str.$appKey);

if($sign != $my_sign)
	exit(json_encode(array('status'=>2, 'msg'=>'验证错误.')));

$OperID = "hainwl";
$OperPass = "hnsy47";
$conn = SetConn('88');
$code = rand(100000,999999);

$where = " and date_format(from_unixtime(addtime),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')";
$sql = "select count(id) as count from web_message where game_id='$game_id' and username='$phone'".$where;
if(false ==$query = mysqli_query($conn,$sql))
    exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
$row = @mysqli_fetch_assoc($query);
if($row['count'] >= 15)
	exit(json_encode(array('status'=>2, 'msg'=>'短信发送次数不能超过5次.')));

$sql = "select * from web_message where game_id='$game_id' and username='$phone' limit 1";
if(false == $query = mysqli_query($conn,$sql))
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));

$rs = @mysqli_fetch_assoc($query);
$nowTime = time();
if(!empty($rs['addtime'])){
	if($nowTime-$rs['addtime'] < 60)
		exit(json_encode(array('status'=>2, 'msg'=>'60秒内不能重复发送.')));
}
$iSql= "insert into web_message(game_id, username, code, addtime) values('$game_id', '$phone', '$code', '$nowTime')";
if(false == mysqli_query($conn,$iSql))
	exit(json_encode(array('status'=>1, 'msg'=>'web server sql error.')));
$content = "【海牛网络】衣范儿验证码：".$code."。有效期15分钟，请勿转发，以免造成账户或资金损失。";

$content = iconv("UTF-8","GBK",$content);
$content = urlencode($content);
$url = "http://221.179.180.158:9007/QxtSms/QxtFirewall?OperID=$OperID&OperPass=$OperPass&SendTime=&ValidTime=&AppendID=&DesMobile=$phone&Content=$content&ContentType=8";
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