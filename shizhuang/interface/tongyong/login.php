<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 通用登陆接口
 * ==============================================
 * @date: 2016-7-28
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize ( $_POST );
$get = serialize ( $_GET );
write_log ( ROOT_PATH . "log", "tongyong_info_log_", "post=$post,get=$get, " . date ( "Y-m-d H:i:s" ) . "\r\n" );

$sdkType = $_REQUEST ['sdktype'];
$token = $_REQUEST ['token'];
$p = $_REQUEST ['p'];
$gameId = intval ( $_REQUEST ['game_id'] );
$webHost = 'http://fhweb.u776.com:86';
switch ($sdkType) {
	case 99 : // oppo
		$url = $webHost . '/interface/oppo/login.php';
		$data ['fileid'] = $token;
		$data ['token'] = $p;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 125 : // qq
		$url = $webHost . '/interface/guangwang/qqlogin.php';
		$data ['openid'] = $token;
		$data ['sign'] = $p;
		$data ['gameid'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 126 : // 微信
		$url = $webHost . '/interface/guangwang/weixinlogin.php';
		$data ['openid'] = $token;
		$data ['sign'] = $p;
		$data ['gameid'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 129 : // 应用宝
		$url = $webHost . '/interface/ysdk/login.php';
		$data ['access_token'] = $token;
		$data ['type'] = $p;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 213 : // 阿里uc
		$url = $webHost . '/interface/uc_new/login.php';
		$data ['sid'] = $p;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 237 : // 华为
		$url = $webHost . "/interface/huawei/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 239 : // vivo
		$url = $webHost . "/interface/vivo/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 700 :
		$url = $webHost . '/interface/guanwang/login.php';
		$data ['token'] = $p;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 701 :
		$url = $webHost . '/interface/autologin/login.php';
		$data ['token'] = $token;
		$data ['channel'] = $p;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
}