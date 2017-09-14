<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$Action=$_REQUEST["Action"];
$ClassID=$_REQUEST["ClassID"];
if ($Action=="Del"){
	$sql="select count(0) from news_class where ParentID=$ClassID";
	$query=mysql_query($sql);
	$RowCount=mysql_result($query,0);
	if ($RowCount != 0){
		ErrMsg("无法删除，此类别含有子类别，请先清空！");
	}else{
		$sql="Delete from news_class Where ClassID In($ClassID)";
		mysql_query($sql);
	}
	header("Location: Class_List.php");
}
if ($Action=="Hidd"){
	$IsHidd=$_REQUEST["IsHidd"];
	if ($IsHidd == 1){
		$sql="Update news_class Set IsHide='0' Where ClassID In($ClassID)";
		mysql_query($sql);
	}else{
		$sql="Update news_class Set IsHide='1' Where ClassID In($ClassID)";
		mysql_query($sql);
	}
	header("Location: Class_List.php");
}
?>
<html>
<head>
<title>class</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<script language="JavaScript">
function CreateClass(ClassID)
{
	var filename="Create_Class.asp?ClassID="+ClassID;
	window.open(filename,"显示窗口","scrollbars=yes,width=300,height=100,status=yes,resizable=no");
}
</script>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?
include ("Class_Top.php");
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <tr height="22"> 
    <th height="22" align="center">分类列表</th>
  </tr>
<?
if ($ClassID!=""){
$SqlStr=" And ParentID=$ClassID";
}else{
$SqlStr=" And ParentID=0";
}
$sql="select * from news_class where 1=1".$SqlStr." order by OrderID";
$conn=mysql_query($sql);
while($rs=mysql_fetch_array($conn)){

	$ClassID=$rs[ClassID];
	//$sqlNum="select count(0) from news_class where 1=1".$SqlStr;
	//$result=mysql_query($sqlNum);
	//$num=mysql_num_rows($result);
?>
  <tr> 
    <td height="23" class="forumRow"><img src="Images/tree11.gif" width="17" height="16"><img src="Images/tree61.gif" width="15" height="15"><?
if ($rs[ParentID] == 0){
echo "<a href=Class_List.php?ClassID=".$ClassID.">".$rs[ClassName]."</a>";
}else{
echo $rs[ClassName];
}
echo "　<a href=".getPath()."?Action=Hidd&ClassID=".$ClassID."&IsHidd=".$rs[IsHide].">";
if ($rs[IsHide] == 1){
echo "<font color=red>隐藏</font>";
}else{
echo "显示";
}
echo "</a>";
$Link=" <a href=Class_Edit.php?ClassID=".$ClassID.">修改</a>\n";
$Link=$Link." <a href=Class_Move.php?ClassID=".$ClassID.">移动</a>\n";
$Link=$Link." <a href=".getPath()."?Action=Del&ClassID=".$ClassID." onClick=\"return confirm('确实要删除吗？');\">删除</a>\n";

if ($rs[ParentID] == 0){
$Link=$Link." <a href=Class_Add.php?ClassID=".$ClassID.">添加子栏目</a>\n";
$Link=$Link." <a href=Class_Order.php?ClassID=".$ClassID.">排序</a>\n";
$Link=$Link." <a href=".getPath()."?Action=ClearNull&?ClassID=".$ClassID." onClick=\"return confirm('确实要清空子类别吗？清空后将无法还原！');\">清空子类别</a>\n";
}
echo $Link;
?></td>
  </tr>
<?}?>
</table>
</body>
</html>