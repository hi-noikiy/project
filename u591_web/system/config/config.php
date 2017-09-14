<?php
define('DIR_DIAGONAL', '/');
if(strripos(dirname($_SERVER['SCRIPT_NAME']),'/'))
	$_dir= substr($_SERVER['SCRIPT_NAME'],0,strripos(dirname($_SERVER['SCRIPT_NAME']),'/'));
else{
	$_dir= dirname($_SERVER['SCRIPT_NAME']);
	if($_dir=='/' || $_dir=='\\')
		$_dir="";
}

define('ROOT', $_dir.DIR_DIAGONAL);

//域名指定去除APPNAME
if(strstr($_SERVER['SERVER_NAME'], '.com') && !strstr($_SERVER['REQUEST_URI'], 'admin.php'))
	define('ASSETS_URL',$_dir.'/assets/');
else 
	define('ASSETS_URL',$_dir.DIR_DIAGONAL.APPNAME.'/assets/');

define('IMAGESURL', 'http://images.u591.com/');
$_uri=substr($_SERVER['REQUEST_URI'],0,strripos($_SERVER['REQUEST_URI'],'/'));

define('__URL__', $_uri);

/*
 * 引入时尚配置路径
 */
include_once 'config_vogue.php';

?>