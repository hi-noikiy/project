<?php
header("Content-type:text/html;charset=utf-8");
define('ROOT_PATH', str_replace('kq/admin/ajax/goth.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'kq/conf/db.conf.php';
$host=$GLOBALS['host'];
$db=mysql_connect($host['host'], $host['user'], $host['pass']);
mysql_select_db($host['database'],$db);
mysql_set_charset('utf8', $db);
$id=intval($_GET['id']);
$perTag=intval($_GET['perTag']);
$sql="update _web_overtime set perTag='$perTag' where id=$id";
mysql_query($sql);