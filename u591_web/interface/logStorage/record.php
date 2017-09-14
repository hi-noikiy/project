<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 日志存放
* ==============================================
* @date: 2016-8-24
* @author: luoxue
* @version:
*/
include_once 'config.php';
$post = serialize($_POST);

$params = array(
		'mac',
		'log',
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
$sign = strtolower($_POST['sign']);
$appKey = $key_arr['appKey'];
$array['mac'] = $_POST['mac'];
$array['log'] = $_POST['log'];
$array['game_id'] = $_POST['game_id'];
$mySign = httpBuidQuery($array, $appKey);
if($mySign != $sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));

write_log(ROOT_PATH."log","logRecord_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
exit(json_encode(array('status'=>0, 'msg'=>'success')));
?>
