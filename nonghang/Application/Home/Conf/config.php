<?php
$config_array = array(
	//'配置项'=>'配置值'
    'URL_MODEL'      => 2,
    'DEFAULT_THEME'    =>    'template',
    'SERVER_URL'=>'wap.zmaxfilm.com',
    'VOUCHER_URL'=>'http://v.zrfilm.com/Api/Server',
);

$config_array['TMPL_PARSE_STRING'] = array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/' . $config_array['DEFAULT_THEME'] . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME  . '/' . $config_array['DEFAULT_THEME']. '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME  . '/' . $config_array['DEFAULT_THEME']. '/js',
		'__UPLOAD__'     => __ROOT__ . '/Uploads',
);
$config_array['bookwhole']['call_back_url'] = 'http://wap.zmaxfilm.net:8181/home/bookwhole/paymentSuccess';
$config_array['bookwhole']['notify_url'] = 'http://wap.zmaxfilm.net:8181/home/bookwhole/notify';
$config_array['bookwhole']['merchant_url'] = 'http://wap.zmaxfilm.net:8181/home/bookwhole/paymentstatus';
return $config_array;
