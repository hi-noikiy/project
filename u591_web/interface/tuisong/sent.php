<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 推送接口
 * ==============================================
 * @date: 20170525
 * @author: Administrator
 * @return:
 */
require_once 'autoload.php';
// $app_key = 'f1edb72fb696a1ad5c106524';
// $master_secret = '0818e703b009bcd70cf3f633';
$app_key = 'f32740e3da44fe95a7241df8';
$master_secret = 'b8731fdf5f76991da5b5aa10';
$client = new \JPush\Client($app_key, $master_secret);
$pusher = $client->push();
$pusher->setPlatform('all');
$pusher->addAllAudience();
#$pusher->addRegistrationId($registers);
$pusher->setNotificationAlert('一条程序员的测试数据');
try {
	$pusher->send();
} catch (\JPush\Exceptions\JPushException $e) {
	// try something else here
	print_r($e) ;
}
?>