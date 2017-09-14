<?php
// +----------------------------------------------------------------------
// | 应用入口文件
// +----------------------------------------------------------------------
// | 中瑞卷管理系统
// +----------------------------------------------------------------------
// | Author: 嗄沬 280708784@qq.com
// +----------------------------------------------------------------------

header("Content-type: text/html;charset=utf-8");
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);
error_reporting(1);
if(!APP_DEBUG){
	// delDirAndFile('Runtime');
}

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define ( 'APP_PATH', './Application/' );
/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ('RUNTIME_PATH', './Runtime/' );
define ('SOAP_CACHE_PATH', RUNTIME_PATH . 'Soap/' );
ini_set('soap.wsdl_cache_dir', SOAP_CACHE_PATH);

define('THINK_PATH', str_replace('index.php', 'ThinkPHP/', str_replace('\\', '/', __FILE__)));
define('ROOR_PATH', str_replace('index.php', '', str_replace('\\', '/', __FILE__)));

// 引入ThinkPHP入口文件
require THINK_PATH . '/ThinkPHP.php';

if(!is_dir(SOAP_CACHE_PATH)){
	aotumkdir(SOAP_CACHE_PATH);
}
//清空目录
function delDirAndFile($dirName) {
	if($handle = opendir($dirName)) {
		while(false !== ($item = readdir($handle))) {
			if($item != "." && $item != "..") {
				$filefullname = "$dirName/$item";
				if(is_dir($filefullname)) {
					delDirAndFile($filefullname);
				} else {
					@unlink($filefullname);
				}
			}
		}
		closedir($handle);
	}
}
// 亲^_^ 后面不需要任何代码了 就是如此简单
