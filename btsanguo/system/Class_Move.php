<?
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/config.php");

$ClassID=$_REQUEST["ClassID"];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title></title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?
include ("Class_Top.php");

$SqlMain="select ClassID,ClassName from news_class where ClassID=$ClassID";
$conn=mysql_query($SqlMain);
$rs=mysql_fetch_array($conn);
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form method="POST" name="myform" action="Class_Save.php">
    <tr> 
      <th height="22" colspan="2" align="center">分类移动</th>
    </tr>
    <tr> 
      <td width="30%" height="7" align="right" class="forumRow">栏目名称
      </td>
      <td width="70%" class="forumRow"><?=$rs[ClassName]?></td>
    </tr>
    <tr> 
      <td height="8" align="right" class="forumRow">移动到：</td>
      <td width="70%" class="forumRow">
<?
//object_selected
$SqlMain="select ClassID,ClassName from news_class where ParentID=0 order by OrderID";
$conn=mysql_query($SqlMain);
echo "<select name=MoveID size=2 style=height:300px;width:500px;>\n";
echo "<option value=0>无（作为一级栏目）</option>\n";
while($rsMain=mysql_fetch_array($conn))
{
	echo "<option value=".$rsMain[ClassID].">".$rsMain[ClassName]."</option>\n";
	/*$SqlPar="select ClassID,ClassName from news_class where ParentID=".$rsMain[ClassID]." order by OrderID";
	$conn=mysql_query($SqlPar);
	while($RsPar=mysql_fetch_array($conn))
	{
	echo "<option value=".$RsPar[ClassID].">┆┠".$RsPar[ClassName]."</option>\n";
	}*/
}
echo "</select>";
?>
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"> <input name="Action" type="hidden" value="Move"> <input name="ClassID" type="hidden" value="<?=$rs[ClassID]?>"> 
        <input name="Save" type="submit" class="bott" value=" 保 存 "> <input name="Cancel" type="button" class="bott" value=" 取 消 " onClick="javascript:history.back();"></td>
    </tr>
  </form>
</table>
</body>
</html>
<? mysql_close();?>