<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
header("Content-Type: text/html; charset=UTF-8");
$TabName="admin_user";
$Action=$_REQUEST["Action"];
$ID=$_REQUEST["ID"];

if ($Action=="Del"){
	$sql="Delete from ".$TabName." Where ID In($ID)";
	mysql_query($sql);
	header("Location:".getPath());
}
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
<script language="javascript" src="JS/ActionFrom.js"></script>
</head>
<body class="main">
<?
$UserName=CheckStr($_REQUEST["UserName"]);
$UserOper=CheckStr($_REQUEST["UserOper"]);
$StarTime=CheckStr($_REQUEST["StarTime"]);
$EndsTime=CheckStr($_REQUEST["EndsTime"]);

if ($UserName != ""){
	$sql=$sql." And UserName Like '%$UserName%'";
	$PURL=$PURL."UserName=$UserName&";
}
if ($UserOper != ""){
	$sql=$sql." And UserOper Like '%$UserOper%'";
	$PURL=$PURL."UserOper=$UserOper&";
}

$SqlStr="select * from ".$TabName." Where 1=1".$sql." Order By Add_Time Desc";
//echo $SqlStr;
//分页参数
$pagesize=15;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->PageSql();//格式化sql语句
mysql_query("set names 'utf8'");
$result=mysql_query($SqlStr);
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr> 
      <th height="22" colspan="2" align="center">帐号管理</th>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">帐号：</td>
      <td width="85%" class="forumRow"><input name="UserName" type="text" value="<?=$UserName?>"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">真实姓名：</td>
      <td width="85%" class="forumRow"><input name="UserOper" type="text" value="<?=$UserOper?>"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 "></td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
<form name="form1" action="" method="post">
  <tr height="22"> 
    <th width="29" height="22" align="center"><input type="checkbox" name="check_all" value="1" onClick="select_ok();"></th>
    <th width="149"  height="22" align="center">帐号</th>
    <th width="164" height="22">真实姓名</th>
    <th width="236" height="22">上次登录</th>
    <th width="267">登录次数</th>
    <th width="136">操作</th>
  </tr>
<?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr bgcolor="#ECECED" id="t<?=$rs[0]?>">
    <td height="22" align="center" nowrap><input name="AutoID" type="checkbox" value="<?=$rs[0]?>" onClick="if(this.checked){t<?=$rs[0]?>.style.backgroundColor='#DBEAF5';}else{t<?=$rs[0]?>.style.backgroundColor='#ECECED';}"></td>
    <td nowrap><a href="User_Edit.php?ID=<?=$rs[0]?>"><?=$rs["UserName"]?></a></td>
    <td nowrap><?=$rs["UserOper"]?></td>
    <td nowrap><?=$rs["LoginTime"]?></td>
	 <td nowrap><?=$rs["LoginNum"]?></td>
	 <td align="center"><a href="User_Edit.php?ID=<?=$rs[0]?>">修改</a> <a href="<?=getPath()?>?Action=Del&ID=<?=$rs[0]?>" onClick="return confirm('确实要删除吗？');">删除</a></td>
  </tr>
<?
$I++;
}
?>
    <tr> 
    <td height="25" colspan="6" class="forumRow"><input class="bott01" name="button_select" type="button" onClick="javascript:select_change();" value=" 全 选 " accesskey="a">
      <input class="bott01" type="button" name="add_prod" value=" 新 增 " onClick="window.location='User_Add.php'"> </td>
  </tr>
  </form>
  <tr> 
    <td height="25" colspan="6" align="center" class="forumRow"><?=$page->show();?></td>
  </tr>
</table>
</body>
</html>