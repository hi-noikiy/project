<?php
$timestamp = strtotime('2014-06-01');
for ($i=1; $i<=30; $i++){
    if($i>8 && $i<15) continue;
    if($i>15 && $i<30) continue;
    //bt = 20140520
    $tm   = strtotime("- $i days", $timestamp);
    $day  = date('Ymd', $tm);//20140519,18
    $nlbt = date('ymd0000', $tm);//19
    $nlet = date('ymd2359', $tm);//19

    $col = "day{$i}";//day8
    $day_cnt = date('Ymd', strtotime("+$i days", $tm));//20,20

    $sql_cnt_newlogin = <<<SQL
       SELECT l.fenbaoid, l.serverid, count(*) AS cnt
       FROM `loginmac` l LEFT JOIN newmac n
       ON l.accountid = n.accountid WHERE `n`.gameid =5
       AND n.createtime >= $nlbt AND n.createtime <= $nlet
       AND l.logintime=$day_cnt
       GROUP BY l.fenbaoid,l.serverid
SQL;
    echo str_repeat('+', 50) . PHP_EOL;
    echo $sql_cnt_newlogin . PHP_EOL;
}
