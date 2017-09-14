<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-28
 * Time: 上午10:05
 */
include '../config/config.php';
$db_sum    = db('analysis');
$db_source = db('gamedata_online');
echo '==========Begin===============' . PHP_EOL;
$runtime= new runtime;
//TODO::选择数据库服务器--正式上线时需配置数据库连接文件
$ana = new Analysis($db_source, $db_sum, '2014-06-24');
//TODO:元宝消耗统计
$ana->SumPlayOnline();

//关闭数据库连接
$db_sum     = null;
$db_source  = null;
