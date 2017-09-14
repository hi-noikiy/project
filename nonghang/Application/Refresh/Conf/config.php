<?php
$config_array = array(
	//'配置项'=>'配置值'
    'URL_MODEL'      => 2,
    'DEFAULT_THEME'    =>    'default',
    'SERVER_URL'=>'http://v.com/Api/Server',

    'DB_SQL_LOG' => false, // SQL执行日志记录

    /* 日志设置 */
    'LOG_RECORD'            =>  false,   // 默认不记录日志
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT',// 允许记录的日志级别
    'LOG_EXCEPTION_RECORD'  =>  false,    // 是否记录异常信息日志
    'SHOW_ERROR_MSG'        =>  false,    // 显示错误信息
    'LOG_FILE_SIZE' => 2097152, // 日志文件大小限制
    'LOG_EXCEPTION_RECORD' => false, // 是否记录异常信息日志
    'APP_STATUS' => 'debug'
);


return $config_array;
