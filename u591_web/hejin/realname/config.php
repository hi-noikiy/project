<?php
define('ROOT_PATH', str_replace('hejin/realname/config.php', '', str_replace('\\', '/', __FILE__)));
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
?>