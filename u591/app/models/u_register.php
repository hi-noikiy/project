<?php
date_default_timezone_set('Asia/Shanghai');
// $table_name = $argv[1] ? $argv[1] : 'sum_register_day';
// var_dump($table_name);exit;
$sql = "insert into u_register(serverid, channel,appid,accountid,client_type,created_at) values ";
$date = date('Ymd');
for ($i=1; $i<40; $i++) {
    $date = date('Ymd', strtotime("-$i days"));
    // $date = date('Ymd', strtotime("+$i days"));
    $tm = strtotime("-$i days");
    $date = date('Ymd', $tm);
    for ($j=1; $j<109; $j++) {
        $serverid = mt_rand(100,103);
        $channel  = mt_rand(1000, 1004);
        $appid    = '0001';
        $accountid= mt_rand(100, 999);
        $created_at = $tm;
        $values  .= "($serverid, $channel, '$appid', $accountid,'andorid', $created_at),";
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
