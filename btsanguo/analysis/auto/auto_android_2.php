<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-28
 * Time: 上午10:05
 * 为防止发生“General error: 2006 MySQL server has gone away”错误
 */
include 'path.php';
$db_sum    = db('analysis');
$db_source = db('gamedata');
echo '==========Begin===============' . PHP_EOL;
$runtime= new runtime;

$ana = new Analysis($db_source, $db_sum);
echo "==============SumGiveEmoney=============". PHP_EOL;
$ana->SumGiveEmoney();
//exit;
echo "==============SumOnline=============". PHP_EOL;
$ana->SumOnline($runtime);
//$ana->SumPlayOnline();
//exit;
//注册转化
echo "==============SumRegTrans=============". PHP_EOL;
$ana->SumRegTrans($runtime);
unset($ana);
echo "==============SumPlayOnline=============". PHP_EOL;
//TODO:在线时长统计
$ol = new PlayerOnlineLvl($db_source, $db_sum);
$ol->run();
//exit;
echo '==========End===============' . PHP_EOL;

//关闭数据库连接
$db_sum     = null;
$db_source  = null;
