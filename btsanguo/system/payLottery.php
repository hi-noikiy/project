<?php
include("inc/CheckUser.php");
include("../inc/config.php");
include("inc/function.php");
include("../inc/function.php");
include("inc/page.php");

    $StarTime=CheckStr($_REQUEST["StarTime"]);
    //默认时间
    if ($StarTime==""){
       $StarTime=date("Y-m-d");
    }
    #在数据库时间条件加1天
    $sql=" And Add_Time >= '$StarTime' And Add_Time < DATE_ADD('$StarTime',INTERVAL 1 DAY)";
    $PURL=$PURL."StarTime=$StarTime&";

	SetConn(88);
	$SqlStr2="SELECT PayName,PayMoney,OrderID FROM pay_log where 1".$sql." And rpCode in ('1','10') and PayMoney>='50' and  mod(cast(OrderID AS UNSIGNED),60)=0 order by id desc";
	//echo $SqlStr2;
        $pagesize=15;
	$page=new page($SqlStr2,$pagesize,getPath()."?".$PURL);//分页类
	$SqlStr2=$page->PageSql();//格式化sql语句
	$result=mysql_query($SqlStr2);
        $ary = array();
?>
<html>
<head>
<title>幸运充值查询</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
</head>
<body class="main">

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">幸运充值查询</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">日期：</td>
      <td class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)" readonly></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 提交 ">  <a href="mexcelLottery.php?StarTime=<?=$StarTime?>">导出excel</a></td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
<form name="form1" action="" method="post">
  <tr>
    <th width="60"  height="22" align="center">编号</th>
    <th width="100" height="22" align="center">玩家账号</th>
    <th width="93" height="22" align="center">金额</th>
    <th width="93" height="22" align="center">订单号</th>
  </tr>
<?php
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;

while($rs=mysql_fetch_array($result))
{
?>
  <tr bgcolor="#ECECED">
	<td height="22" align="center" width="100"><?=$I+($Ipage-1)*$pagesize?></td>
	<td width="100" height="22" align="center">
	<?=$rs['PayName']?>
	</td>
        <td width="100" height="22" align="center">
	<?=$rs['PayMoney']?>
	</td>
	<td width="93" height="22" align="center">
	<?=$rs['OrderID']?>
	</td>
  </tr>
<?
$I++;
}
?>
  </form>
  <tr>
    <td height="25" colspan="7" align="center" class="forumRow"><?=$page->show();?></td>
  </tr>
</table>
<?php mysql_close();?>
</body>
</html>