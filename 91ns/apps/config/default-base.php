<?php

return array(
    'application' => array(
        'debug' => true,
        'assetsDebug' => false,
        'dataCache' => true, //数据缓存是否开启
    ),
    'mysql' => array(
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => '91ns',
        'charset' => 'utf8'
    ),
    'mongo' => array(
        'host' => 'mongodb://localhost',
        'port' => '27017',
        'dbname' => 'lobbyplatform',
        'charset' => 'utf8'
    ),
    'redis' => array(
//        'host' => '192.168.235.60',
        'host' => '192.168.1.104',  // 内网测试
        'port' => '6379',
    ),
    'charserver' => array(
        'nodejsserver' => 'localhost',
        'nodejsport' => '22056',
    ),
    //当前网站所在的渠道版本
    // owner = 1; douzi=2
    'channelType' => 1,
    
    'memcache' => array(
        'host' => 'localhost',
        'port' => '11211',
        'prefix' => '',
        'lifetime' => 3600
    ),
);