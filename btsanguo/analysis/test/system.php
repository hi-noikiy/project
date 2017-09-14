<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-16
 * Time: 上午9:07
 */
include '../config/config.php';

$db_sum  = db('analysis');
$sys = new System($db_sum);
//$ret = $sys->UserAdd(1, 'cgp','陈光鹏','all','225800','225800');
$ret = $sys->GenerateFilesCache();
var_dump($ret);