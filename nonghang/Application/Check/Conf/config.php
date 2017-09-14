<?php
$config_array = array(
	//'配置项'=>'配置值'
    'URL_MODEL'      => 2,
    'DEFAULT_THEME'    =>    'default',
    'SERVER_URL'=>'http://v.zrfilm.com/Api/Server',
 

);

$config_array['TMPL_PARSE_STRING'] = array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/' . $config_array['DEFAULT_THEME'] . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME  . '/' . $config_array['DEFAULT_THEME']. '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME  . '/' . $config_array['DEFAULT_THEME']. '/js',
    );
/* 后台错误页面模板 */
// $config_array['TMPL_ACTION_ERROR'] = MODULE_PATH.'View/' . $config_array['DEFAULT_THEME'] . '/Notice/error.html'; // 默认错误跳转对应的模板文件
// $config_array['TMPL_ACTION_SUCCESS'] = MODULE_PATH.'View/' . $config_array['DEFAULT_THEME'] . '/Notice/success.html'; // 默认成功跳转对应的模板文件
// $config_array['TMPL_EXCEPTION_FILE'] = MODULE_PATH.'View/' . $config_array['DEFAULT_THEME'] . '/Notice/exception.html';// 异常页面的模板文件


return $config_array;
