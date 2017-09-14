<?php
$config_array = array(
	//'配置项'=>'配置值'
    'URL_MODEL'      => 2,
    'DEFAULT_THEME'    =>    'default',
    'SERVER_URL'=>'wap.zmaxfilm.com',
);
$config_array['TMPL_PARSE_STRING'] = array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/' . $config_array['DEFAULT_THEME'] . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME  . '/' . $config_array['DEFAULT_THEME']. '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME  . '/' . $config_array['DEFAULT_THEME']. '/js',
		'__UPLOAD__'     => __ROOT__ . '/Uploads',
    );


return $config_array;