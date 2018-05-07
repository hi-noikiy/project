<?php
include_once('common.inc.php');
$id = $_REQUEST['id'];
$tag = $_REQUEST['tag'];

$sql = "update _web_workday set tag='$tag' where id=$id";
$webdb->query($sql);
$count = mysql_affected_rows();   //sql执行条数;
if($count>0)
echo "yes";
else
echo "no";
?>