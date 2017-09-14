<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 未读列表
* ==============================================
* @date: 2015-5-29
* @author: Administrator
* @return:
*/
include_once('common.inc.php');
if($_SESSION['first'] == 'y'){
    //加班时间累计
    $overclass = new overtime();
    $overclass->wheres = "addtag='0' and manTag='2' and available='1' and uid='".$_SESSION['ADMIN_ID']."'";
    $overList = $overclass->getList();
    $total_overtime = 0;
    foreach($overList as $key => $val){
        $toTime = time()-strtotime($val['toTime']." ".$val['hour_e'].":".$val['minute_e'].":00");
        if($toTime > 0){
        	//计算加班时间
            $totaltime = strtotime($val['toTime']." ".$val['hour_e'].":".$val['minute_e'].":00")-strtotime($val['fromTime']." ".$val['hour_s'].":".$val['minute_s'].":00");
            $total_overtime += $totaltime;
            $overclass->editData(array('addtag'=>'1'), $val['id']);
        } else
            unset($overList[$key]);
    }
    $total_overtime = $total_overtime/60;//累加加班秒数转换为分钟
    $webdb->query("update _sys_admin set totalOverTime=totalOverTime+$total_overtime where id ='".$_SESSION['ADMIN_ID']."'");
    //上次已调休时间累计
    $hughclass = new hugh();
    $hughclass->wheres = "addtag='0' and manTag='2' and available='1' and uid='".$_SESSION['ADMIN_ID']."'";
    $hughList = $hughclass->getList();
    $total_hugh = 0;
    foreach($hughList as $key => $val){
        $toTime = time() - strtotime($val['toTime']." ".$val['hour_e'].":".$val['minute_e'].":00");
        if($toTime > 0){
        	//计算调休时间
            $totaltime = strtotime($val['toTime']." ".$val['hour_e'].":".$val['minute_e'].":00")-strtotime($val['fromTime']." ".$val['hour_s'].":".$val['minute_s'].":00");
            $total_hugh += $totaltime;
            $hughclass->editData(array('addtag'=>'1'), $val['id']);
        } else
            unset($hughList[$key]);
    }
    $total_hugh = $total_hugh/60;//累加调休秒数转换为分钟
    $webdb->query("update _sys_admin set totalOverTime=totalOverTime-$total_hugh where id ='".$_SESSION['ADMIN_ID']."'");

    //已申请调休时间
    $hughdureclass = new hugh();
    $hughdureclass->dure($_SESSION['ADMIN_ID']);

    $_SESSION['first'] = 'n';
    //echo $total;
    //print_r($overList);
}
$action = $_REQUEST['action'];
if($action=='sign'){
  $subPage = 'web/unread_sign.list.php';
}else{
  $subPage = 'web/unread.list.php';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>海牛考勤管理系统</title>
<!-- JQuery文件 -->
<script src="../include/jscode/jquery.js" type="text/javascript"></script>
<!--<script src="../include/jscode/jquery/jquery.date.js" type="text/javascript"></script>
<script src="../include/jscode/jquery/jquery.select.js" type="text/javascript"></script>
-->
<!-- Cookie文件 -->
<script src="../include/jscode/cookie.js" type="text/javascript"></script>
<!-- 公共JS文件 -->
<script type="text/javascript" src="../comm/comm.js"></script>
<script type="text/javascript" src="index.js"></script>
<script src="../include/jscode/popup_layer.js" type="text/javascript" language="javascript"></script>
<link href="../include/jscode/core.css" type="text/css" rel="stylesheet"/>
<link href="../include/jscode/messager.css" rel="stylesheet"  type="text/css" />
<link href="style/css/admin2.css" rel="stylesheet" type="text/css" />
<link rev=stylesheet media=all href="../images/tree/tree_menu.css" type=text/css rel=stylesheet />
<script language="JavaScript" src="../images/tree/tree_menu.js"></script>
<script src="../include/jscode/jquery.messager.js"></script>
<script>
//    $(document).ready(function() {
//         newsCheck();
//         setInterval("newsCheck()", 1800000);
//    });
//    function newsCheck()
//    {
//        $.ajax({
//	   type: "POST",
//	   url: "newsCheck.php",
//	   data: "id=<?=$_SESSION['ADMIN_ID']?>",
//	   success: function(msg){
//                if(msg=='yes')
//                $.messager.show('短消息提醒','你有新消息:<a href="unread.php">点击查看</a>');
//	   }
//	});
//    }
</script>
</head>
<body>
<div style="width:100%;">
	<div style="float:left; padding:0 0 5px 0"><img src="admin_logo.jpg" border="0"  width="202" height="45" /></div>
	<div style="float:right;padding:5px">
		<a href="login.php?out=yes"><img src="style/images/main_r1_c35.gif" width="16" height="40" border="0" title="登出" /></a>
	</div>
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
<script>
$(document).ready(function (){
	//时间控件
	//$("input[date]").jSelectDate({ yearEnd: 2010, yearBegin: 1995, disabled : false, css:"select", isShowLabel : true });
});
</script>
<?if($altmsg || $altmsg=$_GET['altmsg']){?>
<script>alert('<?=$altmsg?>');</script>
<?}?>
</body>
</html>
