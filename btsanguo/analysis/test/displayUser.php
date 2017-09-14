<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-16
 * Time: 上午9:07
 */
include '../config/config.php';

$db_sum  = db('gamedata_online');
$sys = new DisplayUser($db_sum, 5);
$ret = $sys->ShowUserLevel('2014-06-10','2014-06-11');
var_dump($ret);