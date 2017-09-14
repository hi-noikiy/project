<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
  <tr> 
    <th height="22" colspan="2"  align="center" class="forumRow">信息分类管理</th>
  </tr>
  <tr> 
    <td height="30" class="forumRow" >&nbsp;<a href="Class_List.php">栏目管理</a> 
      | <a href="Class_Add.php">新增栏目</a> | <a href="Class_Order.php">一级栏目排序</a></td>
  </tr>
  <tr>
    <td height="30" class="forumRow" ><a href="Class_List.php?ParentID=0">显示全部</a>
	<?
$SqlMain="select ClassID,ClassName from news_class where ParentID=0 order by OrderID";
$conn=mysql_query($SqlMain);
while($Rs_Main=mysql_fetch_array($conn))
{	
	$Str=" | <a href=Class_List.php?ClassID=".$Rs_Main[0].">";
	if ($ClassID==$Rs_Main[ClassID]){
	$Str=$Str."<font color=red>".$Rs_Main[1]."</font>";
	}else{
	$Str=$Str.$Rs_Main[1];
	}
	echo $Str."</a>";
}
echo " | <a href='Client_Hot.php'>热机排序 </a>";
	?></td>
  </tr>
</table>
<DIV style="FONT-SIZE: 3px">&nbsp;</DIV>