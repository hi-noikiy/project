<?php
include_once('common.inc.php');
//print_r($_SESSION);exit;
$subPage = 'web/annual_leave.list.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>海牛考勤管理系统</title>
<!-- JQuery文件 -->
<script src="../include/jscode/jquery.js" type="text/javascript"></script>

<script src="../include/jscode/jquery/jquery.datepick.js" type="text/javascript"></script>
  <script src="../include/jscode/jquery/jquery.datepick-zh-CN.js" type="text/javascript"></script>

 <link href="../include/jscode/jquery/jquery.datepick.css" rel="stylesheet" type="text/css" />
<!-- Cookie文件 -->
<script src="../include/jscode/cookie.js" type="text/javascript"></script>
<!-- 公共JS文件 -->
<script type="text/javascript" src="../comm/comm.js"></script>
<script type="text/javascript" src="index.js"></script>
<link href="../include/jscode/messager.css" rel="stylesheet"  type="text/css" />
<link href="style/css/admin2.css" rel="stylesheet" type="text/css" />

<LINK rev=stylesheet media=all href="../images/tree/tree_menu.css" type="text/css" rel=stylesheet />
<script language="JavaScript" src="../images/tree/tree_menu.js"></script>

<script src="../include/jscode/jquery.messager.js"></script>

<script>
    $(document).ready(function() {
         //newsCheck();
         //setInterval("newsCheck()", 1800000);
    });
    function newsCheck()
    {
        $.ajax({
	   type: "POST",
	   url: "newsCheck.php",
	   data: "id=<?=$_SESSION['ADMIN_ID']?>",
	   success: function(msg){
                if(msg=='yes')
                $.messager.show('短消息提醒','你有新消息:<a href="unread.php">点击查看</a>');
	   }
	});
    }
</script>
</head>

<body>
<div style="width:100%;">
	<div style="float:left; padding:0 0 5px 0"><img src="admin_logo.jpg" border="0"  width="202" height="45"></div>
 <div style="float:right;padding:5px"><A HREF="login.php?out=yes"><img src="style/images/main_r1_c35.gif" width="16" height="40" border="0" title="登出"></A></div>
 <div style="float:right;padding:20px">欢迎使用：<? $admin = new admin();echo $admin->getInfo($_SESSION['ADMIN_ID'], 'real_name', 'pass')?></div>
</div>
<div style="width:100%; height: 90%; float: left;">
	<div id="left">
		<div class="left_box">
			 <?include('index.menu.php')?>
		</div>
	</div>
	<div id="right"><?if($subPage) include($subPage)?></div>
</div>
</body>
</html>
<script>
$(document).ready(function (){
        $('#date_s').datepick({dateFormat: 'yy-mm-dd'});
        $('#date_e').datepick({dateFormat: 'yy-mm-dd'});
	//时间控件
	//$("input[date]").jSelectDate({ yearEnd: 2010, yearBegin: 1995, disabled : false, css:"select", isShowLabel : true });
});
</script>
<?if($altmsg || $altmsg=$_GET['altmsg']){?>
<script>alert('<?=$altmsg?>');</script>
<?}?>