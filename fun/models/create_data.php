<?php
date_default_timezone_set('Asia/Shanghai');
$tables = array(
    'sum_register_day','sum_login_day','sum_online_day','sum_device_active_day'
);
// $table_name = $argv[1] ? $argv[1] : 'sum_register_day';
// var_dump($table_name);exit;
foreach ($tables as $table_name) {
    $sql = "insert into {$table_name}(serverid, channel,appid,cnt,date) values ";
    $date = date('Ymd');
    for ($i=0; $i<10; $i++) {
        $date = date('Ymd', strtotime("-$i days"));
        for ($j=1; $j<59; $j++) {
            $serverid = mt_rand(100,103);
            $channel  = mt_rand(1000, 1004);
            $appid    = '0001';
            $cnt      = mt_rand(90, 500);
            $values  .= "($serverid, $channel, '$appid', $cnt, $date),";
        }
    }
    echo $sql . rtrim($values, ",").";\n";
}
