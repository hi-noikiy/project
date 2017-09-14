<?php
$config_array = array(
	//'配置项'=>'配置值'
	'ADMIN_ALLOW_IP' =>'',   //管理员允许登录的ip,空表示不限制IP,多个IP用','号隔开
	'ALLOW_VISIT'    =>array('index/index', 'index/goUrl', 'film/index', 'plan/index', 'price/index', 'member/index', 'order/index', 'user/index', 'user/index', 'user/index', 'Service/index', 'channel/index'),   //不需功能权限即可访问的节点
	'DENY_VISIT'     =>array(),   //非超级管理员不允许访问的节点
    'URL_MODEL'      => 2,
    'READ_DATA_MAP'=>true,
    'DEFAULT_THEME'    =>    'default',
    'DB_FIELDS_CACHE'=>false,
);

$config_array['TMPL_PARSE_STRING'] = array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/' . $config_array['DEFAULT_THEME'] . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME  . '/' . $config_array['DEFAULT_THEME']. '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME  . '/' . $config_array['DEFAULT_THEME']. '/js',
		'__UPLOAD__'     => __ROOT__ . '/Uploads',
    );


return $config_array;
