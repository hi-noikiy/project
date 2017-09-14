<?php
include_once('common.inc.php');
if ($_GET ['do']) {
	if ($_GET ['type']) {
		if ($_GET ['cn'])
			$subPage = $_GET ['do'] . '.php';
		else
			$subPage = $_GET ['type'] . '/' . $_GET ['do'] . '.php';
	} else
		$subPage = $_GET ['do'] . '.php';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>海牛考勤管理系统</title>
<!-- JQuery文件 -->
<script src="../include/jscode/jquery.js" type="text/javascript"></script>
<!--
<script type="text/javascript" src="../include/jscode/jquery/jquery-calendar.js"></script>
<link rel="stylesheet" type="text/css" href="../include/jscode/jquery/jquery-calendar.css" />
-->
<!--<script src="../include/jscode/jquery/jquery.date.js" type="text/javascript"></script>-->
<!-- <script src="../include/jscode/jquery/jquery.select.js" type="text/javascript"></script>-->
<script src="../include/jscode/jquery/jquery.datepick.js" type="text/javascript"></script>
<script src="../include/jscode/jquery/jquery.datepick-zh-CN.js" type="text/javascript"></script>
<link href="../include/jscode/jquery/jquery.datepick.css" rel="stylesheet" type="text/css" />
<!-- Cookie文件 -->
<script src="../include/jscode/cookie.js" type="text/javascript"></script>
<!-- 公共JS文件 -->
<script type="text/javascript" src="../comm/comm.js"></script>
<script type="text/javascript" src="index.js"></script>
<link href="../include/jscode/messager.css" rel="stylesheet" type="text/css" />
<link href="style/css/admin2.css" rel="stylesheet" type="text/css" />
<link href="../images/tree/tree_menu.css" type="text/css" rel=stylesheet />
<script language="JavaScript" src="../images/tree/tree_menu.js"></script>
<script src="../include/jscode/jquery.messager.js"></script>
<script>
function newsCheck(){
	$.ajax({       
   		type: "POST",
   		url: "newsCheck.php",
   		data: "id=<?=$_SESSION['ADMIN_ID']?>",
	    success: function(msg){   
        	if(msg=='yes')
                $.messager.show('短消息提醒','你有新消息:<a href="unread.php">点击查看</a>',0);
	    }
	});
}
</script>
</head>
<body>
	<div style="width: 100%;">
		<div style="float: left; padding: 0 0 5px 0">
			<img src="admin_logo.jpg" border="0" width="202" height="45" />
		</div>
		<div style="float: right; padding: 5px">
			<a href="login.php?out=yes">
				<img src="style/images/main_r1_c35.gif" width="16" height="40" border="0" title="退出" />
			</a>
		</div>
		<div style="float: right; padding: 20px">欢迎使用：<? $admin = new admin();echo $admin->getInfo($_SESSION['ADMIN_ID'], 'real_name', 'pass')?></div>
	</div>
	<div style="width: 100%; height: 90%; float: left;">
		<div id="left">
			<div class="left_box">
				<?include('index.menu.php')?>
			</div>
		</div>
		<div id="right">
			<?if($subPage) include($subPage)?>
		</div>
	</div>
	<script type="text/javascript">
	    $(document).ready(function (){
	    	$('#date_s').datepick({dateFormat: 'yy-mm-dd'});
	    	$('#date_e').datepick({dateFormat: 'yy-mm-dd'});
	    	shows();
	    });
	    
	    function shows(){
	        var deptag = $("#depTag").val();
	        var mantag = $("#manTag").val();
	        var pertag = $("#perTag").val();
	        //alert(deptag);
	        if(deptag=='1'){            
	            if($.browser.mozilla){                       //判断浏览器 ff
	                $("#depshow").css('display', 'table-row');
	            } else {
	                $("#depshow").css('display', 'block');  //别的用浏览器
	            }
	        } else {
	            $("#depshow").css('display', 'none');
	        }
	        if(mantag=='1') {
	            if($.browser.mozilla){
	                $("#manshow").css('display', 'table-row');
	            } else {
	                $("#manshow").css('display', 'block');
	            }
	        } else {
	            $("#manshow").css('display', 'none');
	        }
	        if(pertag=='1') {
	            if($.browser.mozilla){
	                $("#pershow").css('display', 'table-row');
	            } else {
	                $("#pershow").css('display', 'block');
	            }
	        } else {
	            $("#pershow").css('display', 'none');
	        }
	    }
	
		function GetContents(name){
			// Get the editor instance that we want to interact with.
			var oEditor = FCKeditorAPI.GetInstance(name) ;
			// Get the editor contents in XHTML.
			return oEditor.GetXHTML( true );		
		}
	
		function totaltime(){
		    if( $('#date_s').val() !='' && $('#date_e').val() !=''){
		        var time_start = $('#date_s').val()+' '+$('#hour_s').val()+':'+$('#minute_s').val()+":00";
		        var time_end = $('#date_e').val()+' '+$('#hour_e').val()+':'+$('#minute_e').val()+":00";
		        var st1 = $('#date_s').val().split("-");
		        var et1 = $('#date_e').val().split("-");
		        var d1 = new Date(st1[0],st1[1]-1,st1[2],$('#hour_s').val(),$('#minute_s').val(),00);
		        var d2 = new Date(et1[0],et1[1]-1,et1[2],$('#hour_e').val(),$('#minute_e').val(),00);
		        var d2tod1 = (d2.getTime()-d1.getTime())/(1000*3600);//小时
		        var d2tod2 = (d2.getTime()-d1.getTime())/(1000*60);//分钟
		        $('#totalTime').attr('value',d2tod1.toFixed(1));
		        $('#totalM').attr('value',d2tod2);
		    }
		}
	</script>
	<?if($altmsg || $altmsg=$_GET['altmsg']){?>
	<script>alert('<?=$altmsg?>');</script>
	<?}?>
</body>
</html>