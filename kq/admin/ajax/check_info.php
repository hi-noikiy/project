<?php
if(!isset($_SESSION)) @session_start();
define('ROOT_PATH', str_replace('kq/admin/ajax/check_info.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'kq/conf/db.conf.php';

$action = $_REQUEST['action'];
$class_name = $_REQUEST['class_name'];
$time = $_REQUEST['time'];
$time = date('Y-m-d',strtotime($time));
$db=mysql_connect($host['host'],$host['user'],$host['pass']);
mysql_select_db($host['database'],$db);
$ADMIN_ID = $_SESSION['ADMIN_ID'];
//echo "select * from _web_$class_name where uid ='$ADMIN_ID' and fromTime='$time' and (depTag=1 or perTag=1 or manTag=1) ";
$query = mysql_query("select * from _web_$class_name where uid ='$ADMIN_ID' and fromTime='$time' and (depTag=1 or perTag=1 or manTag=1) ");
$result = mysql_fetch_array($query);


var_dump($result);
if($result['id']){
   echo "ok";exit;
}else{
    echo "ddddd";
}

