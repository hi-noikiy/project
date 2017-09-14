<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
?>
<html>
<head>
<title>充值查询</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
</head>
<body class="main">
<?
$PayName=CheckStr($_REQUEST["PayName"]);
if($_REQUEST['addSubmit'])
{
    $points=CheckStr($_REQUEST["points"]);
    if(!is_numeric($points)||strpos($points,".")!==false)
    {
        echo"<script>alert('积分格式错误,请用整数');history.go(-1);</script>";
        exit;
    }
    $reason=CheckStr($_REQUEST["reason"]);
    addPointsByAdmin($PayName,$points,$AdminName,$reason);
}
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="addForm" method="POST" action="<?=getPath()?>">
      <tr>
      <th height="22" colspan="3" align="center">管理员加积分</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">帐号：</td>
      <td class="forumRow"><input name="PayName" type="text" value="<?=$PayName?>" size="30"></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">加积分：</td>
      <td class="forumRow"><input name="points" type="text" value="" size="30"></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      <td width="15%" align="right" class="forumRow">理由：</td>
      <td class="forumRow"><textarea cols="30" rows="2" name="reason"></textarea></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="forumRow"></td>
      <td class="forumRow"><input type="submit" name="addSubmit" class="bott01" value=" 确 定 "></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    </form>
</table>
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
      <tr>
      <td align="right" class="forumRow" width="15%">帐号：</td>
      <td class="forumRow"><input name="PayName" type="text" value="<?=$PayName?>" size="30"></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    <tr> 
      <td align="right" class="forumRow"></td>
      <td class="forumRow"><input type="submit" name="serSubmit" class="bott01" value=" 搜 索 "></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
  </form>
</table>
<?
//链接账号库
    SetConn(81);
if ($PayName != ""){
	$sql=" And NAME Like '$PayName%'";
	$PURL=$PURL."PayName=$PayName&";
}
$SqlStr="select * from add_points_log Where 1=1".$sql." Order By id Desc";
//分页参数
$pagesize=15;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->pageSql();//格式化sql语句
//echo $SqlStr;
$result=mysql_query($SqlStr);
?>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr height="22"> 
    <th width="30" height="22" align="center">编号 </th>
    <th width="40"  height="22" align="center">账号</th>
    <th width="30" align="center">积分</th>
    <th width="60" height="22">管理员</th>
    <th width="94">时间</th>
    <th width="250" height="22">理由</th>
  </tr>
  <?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr> 
    <td height="22" align="center" nowrap class="forumRow"><?=$I+($Ipage-1)*$pagesize?></td>
    <td nowrap align="center" class="forumRow"><?=$rs["NAME"]?></td>
    <td nowrap align="center" class="forumRow"><?=$rs["points"]?></td>
    <td nowrap align="center" class="forumRow"><?=$rs["byWho"]?></td>
    <td class="forumRow" align="center"><?=$rs["addTime"]?></td>
    <td class="forumRow" ><?=$rs["reason"]?></td>
  </tr>
  <?
$I++;}
?>
  <tr> 
    <td height="25" colspan="13" align="center" class="forumRow"> 
      <?
	  echo $page->show();
      ?>
    </td>
  </tr>
</table>

</body>
</html>
<?
mysql_close();
?>