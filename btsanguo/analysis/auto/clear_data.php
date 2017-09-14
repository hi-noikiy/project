<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 8/14/14
 * Time: 2:29 PM
 * 清理三个月之前的数据
 * 自动执行程序，每三个月执行一次。
 */
include 'path.php';

$db_source = db('gamedata');

$before_3_month = strtotime('-3 months');
$date_before_3_month_1 = date('1ymd', $before_3_month);
$date_before_3_month_2 = date('ymd', $before_3_month);
$date_before_3_month_3 = date('Ymd', $before_3_month);
$date_before_3_month_begin = date('ymd0000', $before_3_month);
$date_before_3_month_end = date('ymd2359', $before_3_month);

//loginmac
$del = "DELETE FROM loginmac WHERE logintime>=? AND logintime<=?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_begin, $date_before_3_month_end));
$db_source->exec("OPTIMIZE TABLE loginmac");

//newmac
$del = "DELETE FROM newmac WHERE createtime>=? AND createtime<=?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_begin, $date_before_3_month_end));
$db_source->exec("OPTIMIZE TABLE newmac");

//pay
$del = "DELETE FROM pay WHERE daytime>=? AND daytime<=?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_begin, $date_before_3_month_end));
$db_source->exec("OPTIMIZE TABLE pay");

//online
$del = "DELETE FROM online WHERE daytime>=? AND daytime<=?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_begin, $date_before_3_month_end));
$db_source->exec("OPTIMIZE TABLE online");

//palyerday
$del = "DELETE FROM palyerday WHERE day<?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_3));
$db_source->exec("OPTIMIZE TABLE palyerday");

//dayonline
$del = "DELETE FROM dayonline WHERE day<?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_3));
$db_source->exec("OPTIMIZE TABLE dayonline");

//rmb
$del = "DELETE FROM rmb WHERE daytime>=> AND daytime<=?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_begin, $date_before_3_month_end));
$db_source->exec("OPTIMIZE TABLE rmb");

//total_emoney
$del = "DELETE FROM total_emoney WHERE daytime<?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_1));
$db_source->exec("OPTIMIZE TABLE total_emoney");

//rmb_emoney
$del = "DELETE FROM rmb_emoney WHERE daytime<?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_1));
$db_source->exec("OPTIMIZE TABLE rmb_emoney");

//give_emoney
$del = "DELETE FROM give_emoney WHERE daytime<?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_1));
$db_source->exec("OPTIMIZE TABLE give_emoney");

//first_rmb
$del = "DELETE FROM first_rmb WHERE daytime>=> AND daytime<=?";
$stmt = $db_source->prepare($del);
$stmt->execute(array($date_before_3_month_begin, $date_before_3_month_end));
$db_source->exec("OPTIMIZE TABLE first_rmb");