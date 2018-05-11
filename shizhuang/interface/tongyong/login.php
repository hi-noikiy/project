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
	case 7 : // 当乐
		$url = $webHost . '/interface/dangle/login.php';
		$data ['token'] = $p;
		$data ['mid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 32 : // 联想
		$url = $webHost . '/interface/lenovo/login.php';
		$data ['token'] = $p;
		$data ['realm'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 99 : // oppo
		$url = $webHost . '/interface/oppo/login.php';
		$data ['fileid'] = $token;
		$data ['token'] = $p;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 123 : // 果盘
		$url = $webHost . '/interface/guopan/login.php';
		$data ['game_uin'] = $token;
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
	case 226 : // 游戏fan
		$url = $webHost . "/interface/gamefan/login.php";
		$data ['jsondata'] = $p;
		// $data['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 236 : // 锤子
		$url = $webHost . "/interface/smartisan/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
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
	case 238 : // 百度
		$url = $webHost . "/interface/baidu/login.php";
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
	case 243 : // 小米
		$url = $webHost . "/interface/xiaomi/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 244 : // 360
		$url = $webHost . "/interface/360/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 245 : // 魅族
		$url = $webHost . "/interface/meizu/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 250 : // 金立
		$url = $webHost . "/interface/jinli/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 251 : // 酷派
		$url = $webHost . "/interface/kupai/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 252 : // 美图
		$url = $webHost . "/interface/meitu/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 253 : // 咪咕
		$url = $webHost . "/interface/migu/login.php";
		$data ['user_token'] = $p;
		$data ['uid'] = $token;
		$data ['game_id'] = $gameId;
		$rs = https_post ( $url, $data );
		echo $rs;
		break;
	case 254 : // 喜马拉雅
		$url = $webHost . "/interface/ximalaya/login.php";
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