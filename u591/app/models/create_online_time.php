<?php
date_default_timezone_set('Asia/Shanghai');
$sql = "REPLACE into sum_online_time(serverid, channel,appid,sday,vip_online,vip_cnt,active_cnt,new_cnt,active_online,new_online) values ";
$date = date('Ymd');
for ($i=1; $i<20; $i++) {
    $date = date('Ymd', strtotime("-$i days"));
    // $date = date('Ymd', strtotime("+$i days"));
    if ($i>10) {
        $ii = $i-10;
        $tm = strtotime("+$ii days");
    }
    else {
        $tm = strtotime("-$i days");
    }
    $date = date('Ymd', $tm);
    for ($j=1; $j<50; $j++) {
        $serverid = mt_rand(100,103);
        $channel  = mt_rand(1000, 1004);
        $appid    = '0001';
        $active_online= mt_rand(100, 999);
        $vip_online= mt_rand(100, 999);
        $new_online= mt_rand(100, 999);
        $vip_cnt = mt_rand(90,100);
        $active_cnt = mt_rand(500, 600);
        $new_cnt = mt_rand(80,100);
        $values  .= "($serverid, $channel, '$appid', $date, $vip_online,$vip_cnt, $active_cnt, $new_cnt,$active_online,$new_online),";
    }
}
echo $sql . rtrim($values, ",").";\n";


function get_mac()
{
    return implode(':',str_split(str_pad(
            base_convert(mt_rand(0,0xffffff),10,16).
            base_convert(mt_rand(0,0xffffff),10,16),12),2)
    );
}
