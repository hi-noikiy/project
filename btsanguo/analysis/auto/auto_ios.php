<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-28
 * Time: 上午10:05
 */
include 'path.php';
$db_sum    = db('analysis_ios');
$db_source = db('gamedata_ios');
echo '==========Begin===============' . PHP_EOL;
$runtime= new runtime;
//TODO::选择数据库服务器--正式上线时需配置数据库连接文件
$ana = new Analysis($db_source, $db_sum);
//TODO:元宝消耗统计
echo "========SumRmbUsed+++++元宝统计========". PHP_EOL;
$ana->SumRmbUsed();
//TODO:统计商城消费
echo "========SumMarketConsumption+++++统计商城消费========". PHP_EOL;
$ana->SumConsumption(Analysis::ConsumptionMarket, $runtime);
//TODO:统计消费行为
echo "========SumMarketConsumption========". PHP_EOL;
$ana->SumConsumption(Analysis::ConsumptionBehavior, $runtime);
//TODO:统计注册转化
echo "==============SumRegTrans=============". PHP_EOL;
//$ana->SumRegTrans($runtime);
//TODO:在线统计
echo "==============SumOnline=============". PHP_EOL;
$ana->SumOnline($runtime);
echo "==============SumPlayOnline=============". PHP_EOL;
//TODO:在线时长统计
$ana->SumPlayOnline();
unset($ana);
echo '==========End===============' . PHP_EOL;
echo "========用户流失========". PHP_EOL;
$userObject = new UserLost($db_source, $db_sum);
foreach ($serverList as $serverid) {
    echo "date:[$date]+ServerID:[$serverid]+Count:[$cnt]++++++++++",PHP_EOL;
    $userObject->lost($serverid);
}
$userObject->sum();
unset($userObject);

echo "========用户留存========". PHP_EOL;
$userObject = new UserRemain($db_source, $db_sum);
try{
    $userObject->SumLoginOrNew(false);
} catch (Exception $e) {
    echo $e->getMessage();
}

try{
    $userObject->SumLoginOrNew(true);
} catch (Exception $e) {
    echo $e->getMessage();
}
//TODO:统计新增登录
echo PHP_EOL ."========archiveNewLogin========". PHP_EOL;
$userObject->archiveNewLogin();
//TODO:活跃度统计
echo PHP_EOL ."========archiveAU========". PHP_EOL;
$userObject->archiveAU();
//TODO:统计留存
echo PHP_EOL ."========remainDaily========". PHP_EOL;
$userObject->remainDaily();
unset($userObject);

//TODO:统计昨天的支付数据，新增支付人数、金额，总充值次数、总金额
$arcPay  = new Pay($db_source, $db_sum);
echo "========archivePay========". PHP_EOL;
$arcPay->archivePay();
unset($arcPay);
//TODO:总数据统计
echo PHP_EOL ."========archiveDaily========". PHP_EOL;
$arcObject = new Archive($db_source, $db_sum);
$arcObject->archiveDaily();
unset($arcObject);

//关闭数据库连接
$db_sum = null;
$db_source = null;
