<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");

$TabName="link_data";
$Action=$_REQUEST["Action"];
$ID=$_REQUEST["ID"];

$ID=CheckStr($_REQUEST["ID"]);
if ($Action=="Del"){
	$num=count($_POST['AutoID']);
	for ( $i=0; $i<$num; $i++ ){
		$ID[$i] = $_POST['AutoID'][$i];
		//删除记录
		$sql="Delete from ".$TabName." Where ID=$ID[$i]";
		mysql_query($sql);
		//echo $sql."<br>";
		header("Location:".getPath());
	}
}
?>
<html>
<head>
<title>list</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/DateTime/DateDialog.js"></script>
<script language="javascript" src="JS/ActionFrom.js"></script>
<SCRIPT LANGUAGE="JavaScript">
function del_to() {
	if(confirm("您真的要删除这些记录吗？")){
	document.form1.action="<?=getPath()?>?Action=Del";
	document.form1.submit();
	}
}
</SCRIPT>
</head>
<body class="main">
<?
$linkName=CheckStr($_REQUEST["linkName"]);
$linkUrl=CheckStr($_REQUEST["linkUrl"]);
$StarTime=CheckStr($_REQUEST["StarTime"]);
$EndsTime=CheckStr($_REQUEST["EndsTime"]);

if ($linkName != ""){
	$sql=$sql." And linkName Like '$linkName%'";
	$PURL=$PURL."linkName=$linkName&";
}
if ($linkUrl != ""){
	$sql=$sql." And linkUrl Like '%$linkUrl%'";
	$PURL=$PURL."linkUrl=$linkUrl&";
}

$SqlStr="select * from ".$TabName." Where 1=1".$sql." Order By add_Time Desc";
//echo $SqlStr;
//分页参数
$pagesize=15;
$page=new page($SqlStr,$pagesize,getPath()."?".$PURL);//分页类
$SqlStr=$page->PageSql();//格式化sql语句
$result=mysql_query($SqlStr);
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="SearchForm" method="POST" action="<?=getPath()?>">
    <tr> 
      <th height="22" colspan="2" align="center">管理</th>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">网站名称：</td>
      <td width="85%" class="forumRow"><input name="linkName" type="text" value="<?=$linkName?>"> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">网站链接：</td>
      <td width="85%" class="forumRow"><input name="linkUrl" type="text" value="<?=$linkUrl?>"> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">时间：</td>
      <td class="forumRow"><input name="StarTime" type="text" size="12" value="<?=$StarTime?>" onClick="javascript:toggleDatePicker(this)">
        ～ 
        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>" onClick="javascript:toggleDatePicker(this)"> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">
	  <a href="link_Order.php?linkType=1">WAP排序</a> <a href="link_Order.php?linkType=2">WEB排序</a></td>
    </tr>
  </form>
</table>
<DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
<form name="form1" action="" method="post">
  <tr height="22"> 
    <th width="43" height="22" align="center"><input type="checkbox" name="check_all" value="1" onClick="select_ok();"></th>
    <th width="148"  height="22" align="center">网站名称</th>
    <th width="120" height="22">类型</th>
    <th width="107" height="22">链接方式</th>
    <th width="598" height="22">网站链接</th>
    <th width="92" height="22">增加时间</th>
    <th width="107">操作</th>
  </tr>
<?
$Ipage = $_REQUEST["page"];
if (empty($Ipage) || $Ipage==0) $Ipage=1;
$I=1;
while($rs=mysql_fetch_array($result)){
?>
  <tr bgcolor="#ECECED" id="t<?=$rs[0]?>">
    <td height="22" align="center" nowrap><input name="AutoID[]" type="checkbox" value="<?=$rs[0]?>" onClick="if(this.checked){t<?=$rs[0]?>.style.backgroundColor='#DBEAF5';}else{t<?=$rs[0]?>.style.backgroundColor='#ECECED';}"></td>
    <td><a href="link_Edit.php?ID=<?=$rs[0]?>"><?=$rs[linkName]?></a>
	<?
	if ($rs[isHide]=="1" ) echo "<font color=green>[待审核]</font>";
	?>
	</td>
    <td><?=$rs["linkType"]==1?"WAP":"WEB"?></a></td>
    <td nowrap><?=$rs["isPic"]==1?"图片":"文字"?></td>
    <td nowrap><?=$rs["linkUrl"]?></td>
    <td nowrap><?=$rs["add_Time"]?></td>
	<td align="center"><a href="link_Edit.php?ID=<?=$rs[0]?>">修改</a></td>
  </tr>
<?
$I++;
}
?>
    <tr> 
    <td height="25" colspan="7" class="forumRow"><input class="bott01" name="button_select" type="button" onClick="javascript:select_change();" value=" 全 选 " accesskey="a"> 
        <input class="bott01" name="del" type="button" onClick="javascript:del_to();" value=" 删 除 "></td>
  </tr>
  </form>
  <tr> 
    <td height="25" colspan="7" align="center" class="forumRow"><?=$page->show();?></td>
  </tr>
</table>
</body>
</html>