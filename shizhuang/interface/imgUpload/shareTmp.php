<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 分享临时存放图片接口
* ==============================================
* @date: 2016-7-23
* @author: Administrator
* @return:
*/
include_once 'config.php';
include_once 'class/upload.php';

define('SHARETMP_IMG__PATH', ROOT_PATH.'upload/shareTmp/');
$post = serialize($_POST);
$file = serialize($_FILES);
write_log(ROOT_PATH."log","shareTmp_info_"," post=$post, file=$file,".date("Y-m-d H:i:s")."\r\n");

$gameId = intval($_POST['game_id']);
$name = trim($_POST['imageName']);
$sign = trim($_POST['sign']);
$appKey = $key_arr['appKey'];

$params = array(
		'game_id',
		'imageName',
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

if(!$appKey)
	exit(json_encode(array('status'=>1, 'msg'=>'appKey error.')));
$array['imageName'] = $name;
$array['game_id'] = $gameId;
$md5Str = httpBuidQuery($array, $appKey);
ksort($array);
$md5Str = http_build_query($array);
$my_sign = md5($md5Str.$appKey);

if($sign != $my_sign)
	exit(json_encode(array('status'=>1, 'msg'=>'sign error.')));

//上传小图标
$upload = new upload('filepath', SHARETMP_IMG__PATH);
$load = $upload->UploadFile($name);
$rs = json_encode($load);
write_log(ROOT_PATH."log","shareTmp_result_","result=$rs,".date("Y-m-d H:i:s")."\r\n");
exit($rs);
?>