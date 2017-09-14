<?php
header("Content-type:text/html;charset=utf-8");
define('ROOT_PATH', str_replace('kq/admin/ajax/getUser.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'kq/conf/db.conf.php';
$host=$GLOBALS['host'];
$db=mysql_connect($host['host'], $host['user'], $host['pass']);
mysql_select_db($host['database'],$db);
mysql_set_charset('utf8', $db);
$depId=intval($_POST['depId']);

$sql="select id,real_name from _sys_admin where depId='$depId' order by id asc";
$query = mysql_query($sql);
$userArr=array();
$i=0;
while (@$row=mysql_fetch_array($query)){
	$userArr[$i]['id']=$row['id'];
	$userArr[$i]['real_name']=$row['real_name'];
	$i++;
}
exit(json_encode($userArr));