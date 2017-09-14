<?php
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/jurisdiction.php");
if (!getFlag('1',$uFlag)){
	header("Location: Adm_Login.php");
}
$TabName="admin_user";
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
	if (document.form1.UserName.value=="")
	{
		alert("帐号不能为空！");
		document.form1.UserName.focus();
		return false;
	}
}
</script>
</head>
<body class="main">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="form1" method="POST" onSubmit="return CheckForm();" action="User_Save.php?Action=Edit&ID=<?=$ID?>">
    <tr>
      <th colspan="2" align="center">编辑</th>
    </tr>
    <tr>
      <td width="15%" align="right" class="forumRow">帐号：</td>
      <td width="85%" class="forumRow"><input name="UserName" type="text" value="<?=$rs['UserName']?>">
        * <input name="IsUser" type="hidden" value="<?=$rs['UserName']?>"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">密码：</td>
      <td class="forumRow"><input name="PassWord" type="password">
（如果不更改密码此处请留空）</td>
    </tr>
    <tr>
      <td align="right" class="forumRow">真实姓名：</td>
      <td class="forumRow"><input name="UserOper" type="text" value="<?=$rs['UserOper']?>"></td>
    </tr>
    <tr>
      <td align="right" class="forumRow">权限：</td>
      <td class="forumRow">

       <?php

       foreach($jurisdiction_arr as $key=>$value){
           echo '<input name="UserFlag[]" type="checkbox" value="'.$key.'"  '.MuchObject($key,$rs['UserFlag']).'    > '.$value['name'];
       }

       ?>


      </td>
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