<?php
include_once('common.inc.php');

$dz_uid = $_REQUEST['dz_uid'];
$score = $_REQUEST['score'];
if($dz_uid){
	$sql=" where dz_uid='$dz_uid'";
}
if($score){
	if($score=='2'){
		$sql=" where scoring='0'";
	}
	else{
		$sql=" where scoring='$score'";
	}
}
if($dz_uid&&$score){
	$sql=" where dz_uid='$dz_uid' and scoring='".($score!='2'?$score:'0')."'";
}
$query=$webdb->query("select tp_id,name,scoring from task_permissions$sql");
$list=array();
while ($rs=mysql_fetch_assoc($query)){
	$list[]=$rs;
}

if($_POST['upt']){
	foreach ($_POST as $key=>$val){
		if(strstr($key,'score')){
			$sql="update task_permissions set scoring='$val' where tp_id='".str_replace('score','',$key)."'";
			$query=$webdb->query($sql);
		}
	}
	echo '<script>alert(\'修改成功\');</script>';
	echo '<script>location.replace(document.referrer);</script>';exit;
}
$p=$_REQUEST['p'];
if(!$p)$p='1';
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
<LINK rev=stylesheet media=all href="../images/tree/tree_menu.css" type="text/css" rel=stylesheet />
<script language="JavaScript" src="../images/tree/tree_menu.js"></script>
<script src="../include/jscode/jquery.messager.js"></script>
</head>
<body>
<form action="task_per.php?p=<?php echo $p?>&score=<?php echo $score?>" method="post">
<div style="width:100%;">
	<div style="float:left; padding:0 0 5px 0"><img src="admin_logo.jpg" border="0"  width="202" height="45"></div>
 <div style="float:right;padding:5px"><A HREF="login.php?out=yes"><img src="style/images/main_r1_c35.gif" width="16" height="40" border="0" title="登出"></A></div>
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
任务绩效打分权限<br/>
 姓名:<select name="dz_uid">
		<option value="">全部</option>
                <?php
                    $sql = "select dz_uid,name from task_permissions";
                    $res = $webdb->getList($sql);
                    foreach($res as $val){
                ?>
		<option value="<?=$val['dz_uid']?>" <?php echo $val['dz_uid']==$_POST['dz_uid']?'selected':'' ?>><?=$val['name']?></option>
				<?}?>
	</select>
打分权限<select name="score">
<option value="" <?php echo $score==''?'selected':'' ?>>全部</option>
<option value="2" <?php echo $score=='2'?'selected':'' ?>>无</option>
<option value="1" <?php echo $score=='1'?'selected':'' ?>>有</option>
	</select>
	<input type="submit" value="查询" class="sub2" name="sel"/>&nbsp;&nbsp;&nbsp;
	<input type="submit" value="修改" class="sub2" name="upt"/>

</div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
        <th scope="col" class="T_title">编号</th>
        <th scope="col" class="T_title">姓名</th>
      <th scope="col" class="T_title">打分权限</th>

    </tr>
    <?
    //计算第一条
    $first = $pn*($p-1);
    $f =0;
      for($i=0;$i<$pn;$i++){
      $f=$first+$i;
          if($f<=count($list)-1)
          {
            $vs = $list[$f];
    ?>

    <tr class="Ls2">
        <td class="N_title"><?php echo $vs['tp_id'];?></td>
        <td class="N_title"><?php echo $vs['name'];?></td>
        <td class="N_title">
        <select name="score<?php echo $vs['tp_id'];?>">
        <option value="0" <?php echo $vs['scoring']=='0'?'selected':'' ?>>无</option>
        <option value="1" <?php echo $vs['scoring']=='1'?'selected':'' ?>>有</option>
        </select>
        </td>
    </tr>
    <?
          }
    }
    ?>
  </table>
<div class="news-viewpage"><?=$pageCtrl?></div>
	</div>
</div>
</form>
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