<?php
date_default_timezone_set('Asia/Shanghai');
$tables = array(
    'u_paylog'
);
// $table_name = $argv[1] ? $argv[1] : 'sum_register_day';
// var_dump($table_name);exit;
foreach ($tables as $table_name) {
    $sql = "insert into {$table_name}(serverid, channel,appid,accountid,lev,money,orderid,is_new,created_at) values ";
    $date = date('Ymd');
    for ($i=1; $i<20; $i++) {
        $date = date('Ymd', strtotime("+$i days"));
        // $date = date('Ymd', strtotime("+$i days"));
        $tm = strtotime("-$i days");
        $date = date('Ymd', $tm);
        for ($j=1; $j<109; $j++) {
            $serverid = mt_rand(100,103);
            $channel  = mt_rand(1000, 1004);
            $appid    = '0001';
            $accountid= mt_rand(100, 999);
            $money = mt_rand(10, 100);
            $lev = mt_rand(1, 15);
            $orderid = 'order' . $tm;
            $is_new  = 1;
            $created_at = $tm;
            $values  .= "($serverid, $channel, '$appid', $accountid, $lev, $money, '$orderid', $is_new, $created_at),";
        }
    }
    echo $sql . rtrim($values, ",").";\n";
}

function get_mac()
{
    return implode(':',str_split(str_pad(
            base_convert(mt_rand(0,0xffffff),10,16).
            base_convert(mt_rand(0,0xffffff),10,16),12),2)
    );
}
