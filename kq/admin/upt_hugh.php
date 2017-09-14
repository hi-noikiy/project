<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 调休时间更改优化
* ==============================================
* @date: 2015-6-23
* @author: Administrator
* @return:
*/
include_once('common.inc.php');
$uid =intval($_REQUEST['uid']);
$sql="select id,real_name,totalOverTime from _sys_admin where id!='99'";
if($uid)
	$sql.=" and id='$uid'";
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

if($_POST['upt']){
	foreach ($_POST as $key=>$val){
		if(strstr($key,'hugh')){
			$tmp_id=str_replace('hugh','',$key);
			if($val && ($_POST['old'.$tmp_id]-$val)>0){
					
				$hughTime=$val*60;
				$totalOverTime=($_POST['old'.$tmp_id]-$val)*60;
				$sql="update _sys_admin set totalOverTime='$totalOverTime' where id='$tmp_id'";
				$query=$webdb->query($sql);
				//$hughTime=$val-$_POST['old'.$tmp_id]*60;//修改的总调休时间减去原来的,得到调休多少时间
				$sql="insert into hugh_time_log(operaterID,hughID,hughTime,addTime) 
				values('$_SESSION[ADMIN_ID]','$tmp_id','$hughTime','".date("Y-m-d H:i:s")."')";
				$query=$webdb->query($sql);
			}
		}
	}
	echo '<script>alert(\'修改完成\');</script>';
	echo '<script>location.replace(document.referrer);</script>';exit;
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
<form action="upt_hugh.php?p=<?php echo $p?>" method="post">
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
			<span>调休时间更改</span><font color="red">(扣除的时间超过总调休时间，将不做更改)</font><br/>	
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
			<input type="submit" value="查询" class="sub2" name="sel"/>&nbsp;&nbsp;&nbsp;
			<input type="submit" value="修改" class="sub2" name="upt" onclick="return confirm('确认提交?')"/>
	
		</div>
	  	<table cellspacing="0" cellpadding="0" class="Admin_L">
	    	<tr>
	        	<th scope="col" class="T_title">ID</th>
	        	<th scope="col" class="T_title">姓名</th>
	        	<th scope="col" class="T_title">现有总调休时间(小时)</th>
				<th scope="col" class="T_title">扣调休时间(小时)</th>
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
	        	<td class="N_title"><?=$vs['id'];?></td>
	        	<td class="N_title"><?=$vs['real_name'];?></td>
	        	<td class="N_title">
	        		<input type="text" readonly="readonly" size="10" name="old<?=$vs['id'];?>" value="<?=$vs['totalOverTime']/60;?>" />
	        	</td>
	        	<td class="N_title"><input type="text" size="10" maxlength="6" name="hugh<?=$vs['id'];?>" value="" /></td>
	    	</tr>
	    	<? }}?>
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