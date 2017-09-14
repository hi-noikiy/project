<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-28
 * Time: 上午10:05
 */
include 'path.php';
$db_sum    = db('analysis');
$db_source = db('gamedata');
echo '==========Begin===============' . PHP_EOL;
$pm = new PlayerMoney( $db_source, $db_sum );//每日活跃玩家元宝统计
$pl = new PlayerLevel( $db_source, $db_sum );//等级分布
$ul = new OurUserLost( $db_source, $db_sum );//流失
$pm->run();
unset($pm);
usleep(5000);

$pl->run();
unset($pl);
usleep(5000);

$ul->run();
usleep(5000);
$ul->sum();
unset($ul);
//关闭数据库连接
$db_sum     = null;
$db_source  = null;
