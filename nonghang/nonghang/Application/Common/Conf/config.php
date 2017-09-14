<?php
return array(
	'DEFAULT_MODULE'     => 'Home',
	'TMPL_ENGINE_TYPE'   =>  'Think',
	'USER_ADMINISTRATOR' => 15,
	'MODULE_ALLOW_LIST' =>  array('Interfaces', 'Vote', 'Admin', 'Api', 'Check', 'Home', 'Refresh', 'Web'),
	'MODULE_DENY_LIST'   => array('Common'),
	'SHOW_PAGE_TRACE'    => true,
	'DEFAULT_CHARSET'    =>  'utf-8',
	'URL_MODEL'          => 0,
	// 'APP_DEBUG'=>true,
	'DB_FIELD_CACHE'=>false, //字段缓存
	'HTML_CACHE_ON'=>false, 
	'SHOW_PAGE_TRACE' =>false,
	//'URL_CASE_INSENSITIVE' => true,

	'alipay_config' => array(
			'input_charset'=> 'utf-8',//字符编码格式 目前支持 gbk 或 utf-8
			'transport'    => 'http',//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
	),
	'SMS' => 'Ihyi',
	'LOG_RECORD' => false, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
    'LOG_TYPE'   =>  'File', // 日志记录类型 默认为文件方式
    'LOG_FILE_SIZE' => 2097152, // 日志文件大小限制

 //    'SHOW_PAGE_TRACE' =>false,
	'DATA_CACHE_TIME'       => 0,      // 数据缓存有效期 0表示永久缓存
	'DATA_CACHE_COMPRESS'   => false,   // 数据缓存是否压缩缓存
	'DATA_CACHE_CHECK'      => false,   // 数据缓存是否校验缓存
	'DATA_CACHE_PREFIX'     => 'zmaxfilmCacheName123_',     // 缓存前缀
	'DATA_CACHE_TYPE'       => 'Redis',
	'REDIS_HOST'            => '192.168.10.239',
	'REDIS_PORT'            => 6379,

    'CACHE_NAME_LIST' => 'zmaxfilmCacheName',
     //'IMG_URL' => 'http://localhost/',
 	//'PAY_URL' =>'http://wappay.zmaxfilm.net:8181/',
	'IMG_URL' => 'http://wap.zmaxfilm.net:8181/',
	'PAY_URL' =>'http://wappay.zmaxfilm.net:8181/',
	

	'FILM_IMG_URL' => 'http://wap.zmaxfilm.net:90/Public/Home/abchina/images/movie/default.jpg',
	'HEAD_IMG_URL' => 'http://wap.zmaxfilm.net:90/Public/Home/abchina/images/user/defaulthead.png',
	'GOODS_IMG_URL' => '/Public/Web/default/images/pic/goods.png',
	'singKey' => 'singKey123',
		
    /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '192.168.10.239', // 服务器地址
	'DB_NAME'   => 'nonghan', // 数据库名
	'DB_USER'   => 'wangtao', // 用户名
	'DB_PWD'    => 'wangtaotest',
    // 'DB_HOST'   => '127.0.0.1', // 服务器地址
    // 'DB_NAME'   => 'zmax', // 数据库名
    // 'DB_USER'   => 'root', // 用户名
    // 'DB_PWD'    => '',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'zx_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'TMPL_DENY_PHP' =>  false, // 默认模板引擎是否禁用PHP原生代码
	'LOG_PATH' =>  ROOR_PATH . '/Runtime/Other/',
	'__UPLOAD__' => 'Uploads/',
	'WHOLE_UPLOAD' => '/Uploads', 
);