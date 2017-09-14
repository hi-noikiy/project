<?php
include("inc/CheckUser.php");
include("../inc/config.php");
include("inc/function.php");
include("../inc/function.php");
include("inc/page.php");

    $acc = $_REQUEST['account'];
    $sql = '';
    if($acc)
    {
        $sql = " and NAME ='".$acc."'";
        $PURL="account=$acc&";
    }
	SetConn(81);
	$SqlStr2="SELECT account_id,NAME,points,pointstotal,vip FROM vipPoints where 1".$sql." order by points desc";
	$pagesize=15;
	$page=new page($SqlStr2,$pagesize,getPath()."?".$PURL);//分页类
	$SqlStr2=$page->PageSql();//格式化sql语句
	$result=mysql_query($SqlStr2);
        
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr>
      <th height="22" colspan="2" align="center">积分和等级查询</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">账号：</td>
      <td width="85%" class="forumRow"><input name="account" type="text" size="30" value="<?=$acc?>" ></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 提交 "></td>
    </tr>
  </form>
</table>

<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>

<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
<form name="form1" action="" method="post">
  <tr>
    <th width="60"  height="22" align="center">玩家编号</th>
    <th width="100" height="22" align="center">玩家名称</th>
    <th width="93" height="22" align="center">玩家积分</th>
    <th width="48" height="22" align="center">玩家等级</th>
  </tr>
<?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;

while($rs=mysql_fetch_array($result)){


?>
  <tr bgcolor="#ECECED">

	<td width="60"  height="22" align="center"><?=$rs['account_id']?></td>
	<td width="100" height="22" align="center">
	<?=$rs['NAME']?>
	</td>
	<td width="93" height="22" align="center">
	<?=$rs['pointstotal']?>
	</td>

	<td width="48" height="22" align="center">
	<?=getRank($rs['vip'])?>
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