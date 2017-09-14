<?php
define('ROOT_PATH', str_replace('interface/init.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";
$ip = getIP_front();
//if(!in_array($ip,$ipList))
//{
//    echo "16 0";    //ip限制 $ipList为合法IP
//    $ipstr = "ip=$ip ";
//    write_log(ROOT_PATH."log","check_ip_log",$ipstr.date("Y-m-d H:i:s")."\r\n");
//    exit;
//}
?>
