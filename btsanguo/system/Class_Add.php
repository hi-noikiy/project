<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/game_config.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>修改分类</title>
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
<?php
include ("Class_Top.php");
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form method="POST" name="myform" onSubmit="return CheckForm();" action="Class_Save.php">
    <tr> 
      <th height="22" colspan="2" align="center">编辑编辑</th>
    </tr>
    <tr> 
      <td width="27%" align="right" class="forumRow">所属栏目：</td>
      <td width="73%" class="forumRow"> 
<?php
$SqlMain="select ClassID,ClassName from news_class where ParentID=0 order by OrderID";
$conn=mysql_query($SqlMain);
echo "<select name=ClassID>\n";
echo "<option value=0>无（作为一级栏目）</option>\n";
while($rs=mysql_fetch_array($conn)){
	echo "<option value=".$rs[ClassID].">".$rs[ClassName]."</option>\n";
}
echo "</select>";
mysql_close();
?>
      </td>
    </tr>
        <tr> 
      <td align="right" class="forumRow">游戏分类：</td>
      <td class="forumRow"> 
      <select name='game_id'>
      <option value='0'>请选择游戏分类</option>
      <?php 
      if($game_arr){
      	foreach ($game_arr as $key=>$val){
      		echo "<option value='$key'>$val[name]</option>";
      	}
      }
      ?>
      </select>
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">栏目名称：</td>
      <td width="73%" class="forumRow"><input name="ClassName" type="text" size="30" maxlength="255" ></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">是否隐藏栏目：</td>
      <td class="forumRow"><input name="IsHide" type="radio" value="1">
        是<input name="IsHide" type="radio" value="0" checked>
        否</iframe></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp; </td>
      <td class="forumRow"> <input name="Action" type="hidden" value="Add"> <input name="Save" type="submit" class="bott" value=" 保 存 " accesskey="s"> 
        <input name="Cancel" type="button" class="bott" value=" 取 消 " onClick="javascript:history.back();"></td>
    </tr>
  </form>
</table>
</body>
</html>