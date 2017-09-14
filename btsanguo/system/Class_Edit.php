<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$ClassID=$_REQUEST["ClassID"];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>edit</title>
<script language = "JavaScript">
function CheckForm(){
  if (document.myform.ClassName.value==""){
    alert("类别名称不能为空！");
	document.myform.ClassName.focus();
	return false;
  }
}
</script>
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
  <form method="POST" name="myform" onSubmit="return CheckForm();" action="Class_Save.php">
    <tr> 
      <th height="22" colspan="2" align="center">分类编辑</th>
    </tr>
    <tr> 
      <td width="30%" height="16" align="right" class="forumRow">分类名称：
      </td>
      <td width="70%" class="forumRow"><input name="ClassName" type="text" value="<?=$rs[ClassName]?>" size="30" maxlength="255"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp; </td>
      <td class="forumRow">
		<input name="Action" type="hidden" value="Edit">
		<input name="ClassID" type="hidden" value="<?=$rs[ClassID]?>">
		<input name="Save" type="submit" class="bott" value=" 保 存 ">
		<input name="Cancel" type="button" class="bott" value=" 取 消 " onClick="javascript:history.back();"></td>
    </tr>
  </form>
</table>
</body>
</html>
<? mysql_close();?>