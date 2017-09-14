<?php
header("Content-type:text/html;charset=utf-8");
define('ROOT_PATH', str_replace('kq/admin/ajax/getHughPass.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'kq/conf/db.conf.php';
$host=$GLOBALS['host'];
$db=mysql_connect($host['host'], $host['user'], $host['pass']);
mysql_select_db($host['database'],$db);
mysql_set_charset('utf8', $db);

/* -1、参数错误
 *  1、操作成功
 */
$uid=intval($_POST['uid']);
$hughdate=trim($_POST['hughdate']);

if(!$uid || !$hughdate)
	exit(json_encode(array('status'=>'-1','msg'=>'参数错误')));

$sql="select id from _web_hugh_pass where uid='$uid' and hughdate='$hughdate' limit 1";
$query = mysql_query($sql);
$row=mysql_fetch_array($query);

if($row['id']){
	$sql="delete from _web_hugh_pass where id='{$row['id']}'";
	mysql_query($sql);
} else {
	$adddate=date('Y-m-d H:i:s', time());
	$sql="insert into _web_hugh_pass(uid, hughdate, adddate) values('$uid', '$hughdate', '$adddate')";
	mysql_query($sql);
}
if(mysql_affected_rows())
	exit(json_encode(array('status'=>'1','msg'=>'操作成功！')));
else 
	exit(json_encode(array('status'=>'-1','msg'=>'数据异常！')));