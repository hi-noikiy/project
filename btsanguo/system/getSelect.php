<?php
include("inc/CheckUser.php");
include("inc/config.php");
header("Content-type: text/html;charset=GB2312");
$id=mysql_real_escape_string($_POST['id']);
if ($id>0){
	$SqlMain="select ClassID,ClassName from news_class where ParentID='$id' order by OrderID";
	$conn=mysql_query($SqlMain);
	echo "<select name=NewsType>";
	echo "<option value=''>——请选择信息分类——</option>";
	while($RsID=mysql_fetch_array($conn))
	{
	echo "<option value=".$RsID['ClassID'].">".$RsID['ClassName']."</option>\n";
	}
	echo "</select>";
}
else{
	echo "<select name=NewsType>";
	echo "<option value=''>无</option>";
	echo "</select>";
}

?>