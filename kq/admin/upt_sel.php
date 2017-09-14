<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 调休时间查询优化
 * ==============================================
 * @date: 2015-6-23
 * @author: Administrator
 * @return:
 */
include_once('common.inc.php');
$admin=new admin();
$uid =$_REQUEST['uid'];
$fromTime =$_REQUEST['fromTime'];
$toTime =$_REQUEST['toTime'];
$sql="select * from hugh_time_log where 1=1";
if($uid)
	$sql.=" and hughID='$uid'";

if($fromTime&&$toTime)
	$sql.=" and (addtime between '$fromTime' and '$toTime')";

$sql.=" order by addtime desc";
$query=$webdb->query($sql);
$list=array();
while (@$rs=mysql_fetch_assoc($query)){
	$list[]=$rs;
}

$dep=new department();
$depList=$dep->getArray('pass');

$admin = new admin();
$depId=$_REQUEST['depId'];
if($depId){
	$admin->wheres="depId='$depId'";
	$userList=$admin->getList();
}

$p=$_REQUEST['p'];
if(!$p)
	$p='1';
$pn = '15';
$pageCtrl=getPageInfoHTMLForRecord($list,$p,'',$pn);
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
<link rev=stylesheet media=all href="../images/tree/tree_menu.css" type="text/css" rel=stylesheet />
<script language="JavaScript" src="../images/tree/tree_menu.js"></script>
<script src="../include/jscode/jquery.messager.js"></script>
</head>
<body>
<form action="upt_sel.php" method="post">
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
			 <?php include('index.menu.php')?>
		</div>
	</div>
	<div id="right">
		<div class="search">
			<span>调休时间查询</span><font color="red">(2012-01-10之前的记录为补BUG调休时间)</font><br/>
		 	<span>部门:</span>
			<select name="depId" id="departmentSelect">
				<option value=''>请选择...</option>
		        <? foreach ($depList as $v){?>
					<option value='<?=$v['id'] ?>' <? if($depId==$v['id']){ echo 'selected';} ?>><?=$v['name']?></option>
				<? }?>
			</select>
			<span>姓名:</span> 
		    <select name="uid" id="uidSelect">
		        <option value=''>请选择...</option>
		       	<? foreach ($userList as $v){ ?>
		        	<option value='<?=$v['id']?>' <? if($uid==$v['id']){ echo 'selected';} ?>><?=$v['real_name'] ?></option>
		        <? } ?>
			</select>
			<span>时间：</span>
			<input type="text" name="fromTime" size="10" id="date_s" value="<?=$fromTime?>" readonly="readonly" /> 
			<span>到：</span>
		    <input type="text" name="toTime" size="10" id="date_e" value="<?=$toTime?>" readonly="readonly" />     
			<input type="submit" value="查询" class="sub2" name="sel"/>&nbsp;&nbsp;&nbsp;
		</div>
		<table cellspacing="0" cellpadding="0" class="Admin_L">
		    <tr>
		        <th scope="col" class="T_title">姓名</th>
		        <th scope="col" class="T_title">更改调休人</th>
				<th scope="col" class="T_title">扣调休时间(小时)</th>
				<th scope="col" class="T_title">更改时间</th>
		    </tr>
		    <?
		    	//计算第一条
		    	$first = $pn*($p-1);
		    	$f =0;
		      	for($i=0;$i<$pn;$i++){
		      		$f=$first+$i;
		          	if($f<=count($list)-1) {
		            	$vs = $list[$f];
		    ?>
		    <tr class="Ls2">
		        <td class="N_title"><?php echo $admin->getInfo($vs['hughID'],'real_name','pass');?></td>
		        <td class="N_title"><?php echo $admin->getInfo($vs['operaterID'],'real_name','pass');?></td>
		        <td class="N_title"><?php echo number_format($vs['hughTime']/60,1);?></td>
		        <td class="N_title"><?php echo $vs['addTime'];?></td>
		    </tr>
		    <? }} ?>
		</table>
		<div class="news-viewpage"><?=$pageCtrl?></div>
	</div>
</div>
</form>
<script type="text/javascript">
$(function(){
	var $department=$("#departmentSelect");
	var $uidSelect=$("#uidSelect");
	$department.change(function(){
		var depId=$(this).val();
		$.ajax( { 
		    url:'ajax/getUser.php',  
		    data:{depId:depId},    
		    type:'post',    
		    cache:false,    
		    dataType:'json',    
		    success:function(data) {
			    var str="<option value=''>请选择...</option>";
			    for(var i=0; i<data.length; i++){
					str+="<option value='"+data[i].id+"'>"+data[i].real_name+"</option>";
				}
			    $uidSelect.html(str);
		     },    
		     error : function() {
		          alert("异常！");    
		     }    
		});  
	});
});
</script>
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
</body>
</html>
