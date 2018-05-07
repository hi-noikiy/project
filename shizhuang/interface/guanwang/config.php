<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 官网账号系统以后全部采用json返回
* ==============================================
* @date: 2016-7-14
* @author: luoxue
* @version:
*/
define ( 'ROOT_PATH', str_replace ( 'interface/guanwang/config.php', '', str_replace ( '\\', '/', __FILE__ ) ) );
include_once ROOT_PATH . 'inc/config.php';
include_once ROOT_PATH . 'inc/config_account.php';
include_once ROOT_PATH . "inc/function.php";

$key_arr = array (
		'appKey' => '0dbddcc74ed6e1a3c3b9708ec32d0532',
		'appSecret' => '074092074142feb68cf2d0dd35d5997a' 
);
$share_url = array (
		'1' => 'http://fh.u776.com:88/public/index.php/index/yfe/index',
		'SDK_HN' => 'https://www.taptap.com/',
		'SDK_UC' => 'http://www.9game.cn/',
		'SDK_OPPO' => 'https://www.oppomobile.com/',
		'SDK_VIVO' => 'http://zs.vivo.com.cn/',
		'SDK_TENCENT' => 'http://android.myapp.com/',
		'SDK_HUAWEI' => 'http://appstore.huawei.com/' ,
		'SDK_XMLYFM' => 'http://game.ximalaya.com/games-operation/v1/games/list' ,
		'SDK_DANGLE' => 'http://www.d.cn/' ,
		'SDK_BAIDU' => 'http://rj.baidu.com/soft/detail/30712.html' ,
		'SDK_360' => 'http://www.360.cn/download/' ,
		'SDK_XIAOMI' => 'http://app.mi.com/' ,
		'SDK_JINLI' => 'http://game.gionee.com/Front/Index/index/?intersrc=mobilewe' ,
		'SDK_LIANXIANG' => 'http://apps.lenovo.com.cn/' ,
		'SDK_KUPAI' => 'http://app.coolpad.com/' ,
		'SDK_MEIZU' => 'http://app.meizu.com/' ,
		'SDK_MEITU' => 'https://www.meitu.com/' ,
);
function httpBuidQuery($array, $appKey) {
	if (! is_array ( $array ))
		return false;
	if (! $appKey)
		return false;
	ksort ( $array );
	$md5Str = http_build_query ( $array );
	$mySign = md5 ( urldecode ( $md5Str ) . $appKey );
	return $mySign;
}