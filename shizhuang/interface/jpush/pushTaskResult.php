<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 推送任务投票结果
* ==============================================
* @date: 2016-7-22
* @author: Administrator
* @return:
*/
include_once 'config.php';
include_once 'JPush.php';
global $hainiuAppKey;
$post = serialize($_POST);
write_log(ROOT_PATH."log","jpush_task_log_"," post=$post, ".date("Y-m-d H:i:s")."\r\n");

$gameId = intval($_POST['game_id']);
$message = base64_decode($_REQUEST['message']);

$sign = trim($_REQUEST['sign']);
$params = array(
		'message',
		'game_id',
		'sign'
);
for ($i = 0; $i< count($params); $i++){
	if (!isset($_POST[$params[$i]])) {
		exit(json_encode(array('status'=>1, 'msg'=>'Missing '.$params[$i])));
	} else {
		if(empty($_POST[$params[$i]]))
			exit(json_encode(array('status'=>1, 'msg'=>$params[$i].' should not be empty.')));
	}
}
$array['game_id'] = $gameId;
$array['message'] = $message;
$mySign = httpBuidQuery($array, $hainiuAppKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));


$appKey = $key_arr['appKey'];
$appSecret = $key_arr['appSecret'];
// 初始化
$client = new JPush($appKey, $appSecret);

$result = $client->push()
			->setPlatform("all")
			->addAllAudience()
			->setNotificationAlert("Hi, 这是一条定时发送的消息")->build();

$rs = json_encode($result);
write_log(ROOT_PATH."log","jpush_task_result_log_","result=$rs, post=$post, ".date("Y-m-d H:i:s")."\r\n");
exit(json_encode(array('status'=>0, 'msg'=>'success')));
?>