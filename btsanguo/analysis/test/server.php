<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-11
 * Time: ÉÏÎç10:28
 */
define('ROOT_PATH', str_replace('analysis/test/server.php', '', str_replace('\\', '/', __FILE__)));
define('CLS_PATH', ROOT_PATH.'analysis/class/');
include ROOT_PATH ."inc/config.php";
set_time_limit(0);
$db_sum    = db('analysis');
include CLS_PATH.'fun.inc.php';
include CLS_PATH.'Server.class.php';

$server = new Server($db_sum);
$server->GenerateServerCache(5);
$server->FenbaoList();
