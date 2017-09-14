<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-28
 * Time: 上午10:05
 * 凌晨2:40
 */
include 'path.php';
$db_sum    = db('analysis');
$db_source = db('gamedata');
echo '==========Begin===============' . PHP_EOL;
//TODO:充值统计

$arcPay  = new Pay($db_source, $db_sum);
$arcPay->run();

sleep(5);
//TODO:总数据统计
echo PHP_EOL ."========archiveDaily========". PHP_EOL;
$arcObject = new Archive($db_source, $db_sum);
$arcObject->run();
unset($arcObject);

//关闭数据库连接
$db_sum     = null;
$db_source  = null;
