<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$TabName="link_data";
$ID=CheckStr($_REQUEST["ID"]);

$sql="select * from ".$TabName." where ID=$ID";
$result=mysql_query($sql);
$rs=mysql_fetch_array($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>edit</title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language = "JavaScript">
function CheckForm(){
	if (document.form1.linkName.value=="")
	{
		alert("网站名称不能为空！");
		document.form1.linkName.focus();
		return false;
	}
	if (document.form1.linkUrl.value=="")
	{
		alert("链接地址不能为空！");
		document.form1.linkUrl.focus();
		return false;
	}	
}
</script>
</head>
<body class="main">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="form1" method="POST" onSubmit="return CheckForm();" action="link_Save.php?Action=Edit&ID=<?=$ID?>">
    <tr> 
      <th colspan="2" align="center">编辑</th>
    </tr>
    <tr> 
      <td width="15%" align="right" class="forumRow">网站名称： </td>
      <td width="85%" class="forumRow"><input name="linkName" type="text" size="50" value="<?=$rs[linkName]?>">
        * </td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">链接地址：</td>
      <td class="forumRow"><input name="linkUrl" type="text" size="50" value="<?=$rs[linkUrl]?>">
        *</td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">所属：</td>
      <td class="forumRow"><select name="linkType">
          <option value="1" <?=SeleObject($rs[linkType],1)?>>WAP</option>
          <option value="2" <?=SeleObject($rs[linkType],2)?>>WEB</option>
        </select></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">链接类型：</td>
      <td class="forumRow"><input type="radio" name="isPic" value="0" <?=ChecObject($rs[isPic],0)?>>
文字链接
<input name="isPic" type="radio" value="1" <?=ChecObject($rs[isPic],1)?>>
图片链接</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">LOGO地址：</td>
      <td class="forumRow"><input name="linkLogo" type="text" size="50" value="<?=$rs[linkLogo]?>"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">网站说明：</td>
      <td class="forumRow"><textarea name="linkInfo" cols="50" rows="3"><?=$rs[linkInfo]?></textarea></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">标记：</td>
      <td class="forumRow"><input type="checkbox" name="isHide" value="1" <?=ChecObject($rs[isHide],1)?>>
        待审核</td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input name="Add" class="bott01" type="submit" value=" 修 改 "  style="cursor:hand;" accesskey="s"> 
        <input name="Cancel" class="bott01" type="button" value=" 取 消 " onClick="javascript:history.back()" style="cursor:hand;"></td>
    </tr>
  </form>
</table>
</body>
</html>