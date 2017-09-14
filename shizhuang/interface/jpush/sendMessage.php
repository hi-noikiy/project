<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 极光推送
* ==============================================
* @date: 2016-7-12
* @author: Administrator
* @return:
*/
include_once 'config.php';
include_once 'JPush.php';

$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","jpush_message_log_"," post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
//accountId_serverId_
$sign = trim($_REQUEST['sign']);
$openid = base64_decode($_REQUEST['message']);

$appKey = $key_arr['appKey'];
$appSecret = $key_arr['appSecret'];
// 初始化
$client = new JPush($appKey, $appSecret);

$payload = $client->push()->setPlatform("all")->addAllAudience()->setNotificationAlert("Hi, 这是一条定时发送的消息")->build();