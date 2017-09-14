<?php
include_once('common.inc.php');
$id = $_POST['id'];
//$role = "";
//$admin = new admin();
//$userinfo = $admin->getInfo($id);
//$sqlstr = '';
//if($userinfo['depId']=='2'&&$userinfo['depMax']=='1')
//{
//    $role = '1';//老大
//    $sqlstr = " and depTag='2' and maxTag='0'";
//}
//elseif($userinfo['depId']<>'2'&&$userinfo['depMax']=='1')
//{
//    $role = '2';//主管
//    $sqlstr = " and depTag='0' and maxTag='2'";
//}
//else
//{
//    $role = '3';//普通员工
//}
$unreadstr = '';
$sql = "select unread from _sys_admin where id=$id";
$ret = $webdb->query($sql);
$unreadstr = @mysql_result($ret,0);
if(strlen($unreadstr)>0)
echo "yes";
else
echo "no";
?>