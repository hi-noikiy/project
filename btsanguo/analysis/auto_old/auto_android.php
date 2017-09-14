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
$runtime= new runtime;
//TODO::选择数据库服务器--正式上线时需配置数据库连接文件
//foreach (array('2014-07-11','2014-07-12','2014-07-13') as $date) {
//    $ana = new Analysis($db_source, $db_sum, $date);
//    $ana->SumRegTrans($runtime);
//}


$ana = new Analysis($db_source, $db_sum);
//TODO:元宝消耗统计
echo "========SumRmbUsed+++++元宝统计========". PHP_EOL;
$ana->SumRmbUsed();
//exit;
//TODO:统计商城消费
echo "========SumMarketConsumption+++++统计商城消费========". PHP_EOL;
$ana->SumConsumption(Analysis::ConsumptionMarket, $runtime);

//TODO:统计消费行为
echo "========ConsumptionBehavior========". PHP_EOL;
$ana->SumConsumption(Analysis::ConsumptionBehavior, $runtime);
//TODO：系统赠送的元宝

echo '==========End===============' . PHP_EOL;

//关闭数据库连接
$db_sum     = null;
$db_source  = null;
