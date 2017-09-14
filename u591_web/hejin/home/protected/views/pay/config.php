<?php
define('ROOT_PATH', str_replace('hejin/webpay/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
function bindParam(&$sql, $data) {
	foreach ($data as $var){
		$var = addslashes($var);  //转义
		$var = "'".$var."'";      //加上单引号.SQL语句中字符串插入必须加单引号
		$pos = strpos($sql, '?');
		//替换问号
		$sql = substr($sql, 0, $pos) . $var . substr($sql, $pos + 1);
	}
	
}
$key_arr = array(
		'appKey'		 =>'0dbddcc74ed6e1a3c3b9708ec32d0532',
		'appSecret' 	 =>'074092074142feb68cf2d0dd35d5997a',
);

$accountServer = array(
		5 =>81,
		8 =>85, //口袋账号库
);

function httpBuidQuery($array, $appKey){
	if(!is_array($array))
		return false;
	if(!$appKey) return false;
	ksort($array);
	$md5Str = http_build_query($array);
	$mySign = md5(urldecode($md5Str).$appKey);
	return $mySign;
}
?>