<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-28
 * Time: 上午10:05
 */
include 'path.php';
//$db_sum    = db('u591_new');
$db_sum    = db('analysis');
$db_source = db('gamedata');
echo '==========Begin===============' . PHP_EOL;
$ana = new Analysis($db_source, $db_sum);
$ana->SumGiveEmoney();
//$bt = strtotime('2014-07-22');
//$et = strtotime(date('Y-m-d'));
//$df = ($et-$bt) / 86400;
//for($i=0; $i<$df; $i++) {
//    $date = date('Y-m-d', strtotime("+{$i} days", $bt));
//    echo $date . '----' . PHP_EOL;
//    $olt = new OnlineTest($db_source, $db_sum, $date);
//    $olt->SumPlayOnline();
//}
//$olt = new OnlineTest($db_source, $db_sum);
//$olt->SumPlayOnline();
//$runtime = new runtime();
//$ana = new Analysis($db_source, $db_sum);
//$ana->SumRegTrans($runtime);
//sleep(4);
//$ol = new PlayerOnlineLvl($db_source, $db_sum);
//$ol->run();

//sleep(4);
//exit;
echo PHP_EOL ."========archiveDaily========". PHP_EOL;
//$arcObject = new Archive($db_source, $db_sum);
//$arcObject->run();
//foreach (array('2014-07-10','2014-07-11','2014-07-12','2014-07-13','2014-07-14','2014-07-15','2014-07-16') as $date) {
//    echo '--' . $date . '----' . PHP_EOL;
//    $ana = new PlayerOnlineLvl($db_source, $db_sum, $date);
//    $ana->run();
//}

//

//$ul = new OurUserLost( $db_source, $db_sum,'2014-07-23' );//流失
//$ul->run();
//usleep(5000);
//$ul->sum();
//unset($ul);
//关闭数据库连接
$db_sum     = null;
$db_source  = null;
//$ana = new PlayerOnlineLvl($db_source, $db_sum);
//$ana->run();
exit;