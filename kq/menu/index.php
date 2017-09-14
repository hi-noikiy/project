<?php 
include "../common.inc.php";
include_once 'section.class.php'; 
if($_GET["action"]){
	$subPage=$_GET["action"].".php";
}else $subPage="menu.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>菜单管理</title>
<script language="javascript" src="../include/jscode/jquery.js"></script>
<link rev=stylesheet media=all href="../images/tree/tree_menu.css" type=text/css rel=stylesheet>
<script language="JavaScript" src="../images/tree/tree_menu.js"></script>
<script src="../include/jscode/cookie.js" type="text/javascript"></script>
</head>

<body>
<table border="1" width="100%" height=600>
	<tr>
		<td width="160" valign="top">
			<table border="0" width="100%">
				<tr><td><a href="?action=menu">菜单管理</a></td></tr>
				<tr><td><a href="?action=bad_menu">失效菜单</a></td></tr>
				<tr><td><a href="?action=view">效果预览</a></td></tr>
				<tr><td><a href="?action=power">权限管理效果预览</a></td></tr>
			</table>
		</td>
		<td valign="top" align="left"><?php include $subPage;?></td>
	</tr>
</table>
</body>
</html>