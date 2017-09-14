<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-28
 * Time: 上午10:05
 */
include 'path.php';
$db_sum    = db('u591_new');
$db_source = db('test_old_db');
echo '==========Begin===============' . PHP_EOL;


echo "========用户留存========". PHP_EOL;
$dateBegin = strtotime('2013-10-19');
$dateBegin = strtotime('2013-10-27');
$dateEnd   = '2013-10-27';
$dateDiff  = (strtotime($dateEnd) - $dateBegin) / 86400;

//for($i=0;$i<$dateDiff;$i++) {

    $date = date('Y-m-d', strtotime("+ $i days", $dateBegin));
    echo  'running on ' .$date . PHP_EOL;
    $date = '2013-10-27';
   $userObject = new UserRemain($db_source, $db_sum, $date);
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
//}




//关闭数据库连接
$db_sum     = null;
$db_source  = null;
