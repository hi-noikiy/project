<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-24
 * Time: 上午10:36
 * 定时执行任务-每30分钟执行一次
 */
include 'path.php';
//$db_sum    = db('analysis');
//$db_source = db('gamedata');
echo '==========Begin===============' . PHP_EOL;
$runtime= new runtime;
exit;
//TODO:统计注册转化,循环统计IOS和Android的数据
//$date = date('Y-m-d');
//1303250939
//foreach ($dbList as $db) {
//    $db_sum    = db($db[0]);
//    $db_source = db($db[1]);
$tm = strtotime('-7 days');
for($i=0 ;$i<7; $i++) {
    $date = date('Y-m-d', strtotime("+$i days", $tm));
    $db_sum    = db('analysis');
    $db_source = db('gamedata');
    $ana = new Analysis($db_source, $db_sum, $date);
    echo "==============SumRegTrans=============". PHP_EOL;
    $ana->SumRegTrans($runtime);
}
