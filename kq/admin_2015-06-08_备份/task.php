<?php
include_once('common.inc.php');

$from = $_REQUEST['fromTime'];
$to = $_REQUEST['toTime'];
$dz_uid = $_REQUEST['dz_uid'];
$p=$_REQUEST['p'];
if(!$p)$p='1';
if(!$from){
    $from = date('Y-m')."-01";
}
if(!$to){
    $to = date('Y-m-d');
}
$from=strtotime($from.' 00:00:00');
$to=strtotime($to.' 23:59:59');
$query=$webdb->query("select task_id,schedule from task 
where schedule>='60' ");
while ($rs=mysql_fetch_assoc($query)){
	$list[]=$rs;
}
$ary=array();
if($list){
	foreach ($list as $val){
		$sql="select run_id,sum(mark) mark from task_info 
		where task_id='".$val['task_id']."' ".($dz_uid?" and run_id='$dz_uid' ":'')." 
		and (add_time between '$from' and '$to') group by run_id";
		$query=$webdb->query($sql);
		$tmp=array();
		while ($rs=mysql_fetch_assoc($query)){
			$tmp[]=$rs;
		}
		if($tmp){//6,13程序部
			foreach ($tmp as $val2){
				$isCoder=false;//是否为程序员
				$sql="select depId from _sys_admin where id=(
				select kq_id from task_permissions where dz_uid='".$val2['run_id']."') ";
				$depId=mysql_fetch_assoc(mysql_query($sql,$kq));
				if($depId['depId']=='6'||$depId['depId']='13'){
					$isCoder=true;
				}
				if(($isCoder&&$val['schedule']>='60')||(!$isCoder&&$val['schedule']>='100')){
					$ary[$val2['run_id']]['run_id']=$val2['run_id'];
					$ary[$val2['run_id']]['mark']+=$val2['mark'];
				}
			}
		}
		unset($tmp);
	}
}
if($ary){
	sort($ary);
}

$pn = '15';
$pageCtrl=getPageInfoHTMLForRecord($ary,$p,'',$pn);
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
	<div id="right">
	<div class="search">
任务绩效查询
<form action="task.php?fromTime=<?=date('Y-m-d',$from) ?>&toTime=<?=date('Y-m-d',$to)?>&dz_uid=<?=$dz_uid?>" method="post">
	  	  时间:<input name="fromTime" type="text" size="10" id="date_s" value="<?=date('Y-m-d',$from)?>" readonly/>
		  到:<input name="toTime" id="date_e" type="text" size="10" value="<?=date('Y-m-d',$to)?>" readonly/>
                  姓名:<select name="dz_uid">
                    <option value="">全部</option>
                <?php
                    $sql = "select dz_uid,name from task_permissions";
                    $res = $webdb->getList($sql);
                    foreach($res as $val){
                ?>
                    <option value="<?=$val['dz_uid']?>" <?php echo $val['dz_uid']==$dz_uid?'selected':'' ?>><?=$val['name']?></option>
                    <?}?>
                      </select>
		  <input type="submit" value="搜索" class="sub2"/>
</form>
</div>
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
        <th scope="col" class="T_title">编号</th>
        <th scope="col" class="T_title">姓名</th>
      <th scope="col" class="T_title">分数</th>

    </tr>
    <?
    //计算第一条
    $first = $pn*($p-1);
    $f =0;
      for($i=0;$i<$pn;$i++){
      $f=$first+$i;
          if($f<=count($ary)-1)
          {
            $vs = $ary[$f];
    ?>

    <tr class="Ls2">
        <td class="N_title"><?=($f+1)?></td>
        <td class="N_title">
        <?php
        	$query=$webdb->query("select name from task_permissions where dz_uid='$vs[run_id]'");
            $name=mysql_fetch_assoc($query);
            echo $name['name'];
        ?>
        </td>
        <td class="N_title"><?=$vs['mark']?></td>

    </tr>
    <?
          }
    }
    ?>
  </table>
<div class="news-viewpage"><?=$pageCtrl?></div>
	</div>
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