<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/jurisdiction.php");
if (!getFlag('1',$uFlag)){
	header("Location: Adm_Login.php");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>新增</title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language = "JavaScript">
function CheckForm(){
	if (document.form1.UserName.value=="")
	{
		alert("帐号不能为空！");
		document.form1.UserName.focus();
		return false;
	}
	if (document.form1.PassWord.value=="")
	{
		alert("密码不能为空！");
		document.form1.PassWord.focus();
		return false;
	}
}
</script>
</head>
<body class="main">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="form1" method="POST" onSubmit="return CheckForm();" action="User_Save.php?Action=Add">
    <tr>
      <th colspan="2" align="center">新增用户</th>
    </tr>
    <tr>
      <td width="15%" align="right" class="forumRow">帐号：</td>
      <td width="85%" class="forumRow"><input name="UserName" type="text">
        * </td>
    </tr>
    <tr>
      <td align="right" class="forumRow">密码：</td>
      <td class="forumRow"><input name="PassWord" type="password">
        *</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">真实姓名：</td>
      <td class="forumRow"><input name="UserOper" type="text"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">权限：</td>
      <td class="forumRow">
       <?php
       foreach($jurisdiction_arr as $key=>$value){
           echo '<input name="UserFlag[]" type="checkbox" value="'.$key.'"> '.$value['name'];
       }
       ?>


	</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">&nbsp;</td>
      <td class="forumRow"><input name="Add" class="bott01" type="submit" value=" 增 加 "  style="cursor:hand;" accesskey="s">
        <input name="Cancel" class="bott01" type="button" value=" 取 消 " onClick="javascript:history.back()" style="cursor:hand;"></td>
    </tr>
  </form>
</table>
</body>
</html>