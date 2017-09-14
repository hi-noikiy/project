<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-24
 * Time: 上午10:36
 * 元宝消耗统计
 */
include 'path.php';
echo '==========Begin===============' . PHP_EOL;
$db_sum    = db('analysis');
$db_source = db('gamedata');

//$arcPay  = new Pay($db_source, $db_sum);
//$arcPay->run();
//sleep(5);
//exit;
$object  = new Archive($db_source, $db_sum);
$object->run();
exit;
$tm = strtotime('-8 days');
for($i=0 ;$i<8; $i++) {
    $date = date('Y-m-d', strtotime("+$i days", $tm));
    echo $date . PHP_EOL;
    $arcPay  = new Pay($db_source, $db_sum, $date);
    $arcPay->run();
}

sleep(5);

for($i=0 ;$i<8; $i++) {
    $date = date('Y-m-d', strtotime("+$i days", $tm));
    echo $date . PHP_EOL;
    $object  = new Archive($db_source, $db_sum, $date);
    $object->run();
}

//$object  = new Archive($db_source, $db_sum);
//echo "========archivePay========". PHP_EOL;
//$arcPay->run();
//unset($arcPay);
echo '==========End===============' . PHP_EOL;
exit;
//$db_sum    = db('analysis');
//$db_source = db('gamedata');

//$runtime= new runtime;
//TODO:统计注册转化,循环统计IOS和Android的数据
//$date = date('Y-m-d');
//1303250939
//foreach ($dbList as $db) {
//    $db_sum    = db($db[0]);
//    $db_source = db($db[1]);
/*$tm = strtotime('-7 days');
for($i=0 ;$i<7; $i++) {
    $date = date('Y-m-d', strtotime("+$i days", $tm));
    $db_sum    = db('analysis');
    $db_source = db('gamedata');
    $ana = new Analysis($db_source, $db_sum, $date);
    echo "==============SumRegTrans=============". PHP_EOL;
    $ana->SumRmbUsed();
}*/
echo '==========End===============' . PHP_EOL;