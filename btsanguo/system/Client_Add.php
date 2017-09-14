<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/game_config.php");
$game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:1;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>新增</title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
<script language = "JavaScript">
function CheckForm(){
	if (document.form1.cName.value=="")
	{
		alert("系列名称不能为空！");
		document.form1.cName.focus();
		return false;
	}
	if (document.form1.cType.value=="")
	{
		alert("请选择手机品牌！");
		document.form1.cType.focus();
		return false;
	}	
}
</script>
</head>
<body class="main">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="form1" method="POST" onSubmit="return CheckForm();" enctype="multipart/form-data" action="Client_Save.php?Action=Add">
    <tr> 
      <th colspan="2" align="center">新增游戏</th>
    </tr>
       <tr>
      <td align="right" class="forumRow">游戏分类：</td>
      <td class="forumRow">
      <select name='game_id' onchange="change_game(this.value)">
      <?php
      if($game_arr){
	      foreach ($game_arr as $key=>$val){
	      	echo "<option value='$key' ".(($key==$game_id)?"selected":"")." >".$val['name']."</option>";
	      }
      }
      ?>
      </select>
      </td>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">系列：</td>
      <td width="85%" class="forumRow"><input name="cName" type="text" size="50">
        * </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">品牌：</td>
      <td class="forumRow"> 
        <?
$SqlMain="select ClassID,ClassName from down_class where game_id=$game_id and IsHide =0 order by OrderID";
$conn=mysql_query($SqlMain);
echo "<select name=cType id=cType>\n";
echo "<option value=''>——请选择分类——</option>\n";
while($rs=mysql_fetch_array($conn))
{
echo "<option value=".$rs[ClassID].">".$rs[ClassName]."</option>\n";
}
echo "</select>";
?> *     </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">文件名：</td>
      <td class="forumRow"><input name="cPath" type="text" size="50"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">支持型号：</td>
      <td class="forumRow"><textarea name="content" cols="60" rows="6"></textarea></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">提供JAD下载：</td>
      <td class="forumRow"><input type="radio" name="IsJad" value="1">
是
		<input name="IsJad" type="radio" value="0" checked>
否</td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">标记：</td>
      <td class="forumRow"><input type="checkbox" name="IsHide" value="1">
          待审核 &nbsp;&nbsp;<input type="checkbox" name="IsHot" value="1">热机
      &nbsp;&nbsp;名称<input type="text" name="hotName" value="<?=$rs[hotName]?>">
      </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">下载次数：</td>
      <td class="forumRow"><input name="HitCount" type="text" value="0" size="15"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">上传图片：</td>
      <td class="forumRow"><input name="NewsFile" type="file" size="45"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">时间：</td>
      <td class="forumRow"> <input name="Add_Time" type="text" value="<?=date("Y-m-d H:i:s")?>" size="40"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input name="Add" class="bott01" type="submit" value=" 增 加 "  style="cursor:hand;" accesskey="s"> 
        <input name="Cancel" class="bott01" type="button" value=" 取 消 " onClick="javascript:history.back()" style="cursor:hand;">      </td>
    </tr>
  </form>
</table>
</body>
    <script type="text/javascript">
        function change_game(game_id){
            $.post("ajax/ctype.php", { action: "change_ctype", game_id: game_id },
            function(data){
                $("#cType").html(data);
            });
        }
    </script>

</html>