<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$Action=$_REQUEST["Action"];
$PassOld=$_POST["PassOld"];
$PassOld=md5($_POST["PassOld"]);
$pass1=$_POST["pass1"];
$pass1=md5($_POST["pass1"]);

if ($Action=="Edit"){
	$sql="select PassWord from admin_user where UserName='$AdminName'";
	$conn=mysql_query($sql);
	$rs=mysql_fetch_array($conn);
	if($rs) {
		if($rs[PassWord] != $PassOld){
			echo"<script>alert('您输入的旧密码有误！');history.go(-1);</script>";
		}else{
			$sql="Update admin_user set PassWord='$pass1' where UserName='$AdminName'";
			mysql_query($sql);
			echo"<script>alert('恭喜你你的密码已修改成功！');history.go(-1);</script>";
		}
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>pwd</title>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
function checkform(){ 
	if (document.form1.PassOld.value==""){
		alert("请您输入旧密码！");
		document.form1.PassOld.focus();		
		return (false);
	}
    if (document.form1.pass1.value==""){
		alert("请您输入新密码！");
		document.form1.pass1.focus();		
		return (false);
  }
    if (document.form1.pass2.value==""){
		alert("请您输入确认密码！");
		document.form1.pass2.focus();		
		return (false);
  }
   if (document.form1.pass1.value!=document.form1.pass2.value){
		alert("请您输入的两次密码不一致！");
		document.form1.pass1.focus();		
		return (false);
  }
}
</SCRIPT>
</head>
<body class="main">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
<form name="form1" method="post" action="<?=getPath()?>?Action=Edit" onsubmit="return checkform();" >
    <tr align="center"> 
      <th height="26" colspan="2">&nbsp;修改系统密码</th>
                    </tr>
                    <tr> 
      <td align="right" class="forumRow">登陆用户：</td>
                      
      <td class="forumRow"> 
        <input name="BlUser" type="text" disabled class="input" value="<?=$AdminName?>" size="30">
      </td>
                    </tr>
                    <tr> 
      <td align="right" class="forumRow">旧密码：</td>
      <td class="forumRow"> 
        <input name="PassOld" type="password" class="input" size="30"></td>
                    </tr>
                    <tr> 
      <td align="right" class="forumRow">新密码：</td>
      <td class="forumRow"> 
        <input name="pass1" type="password" class="input"  size="30"></td>
                    </tr>
                    <tr> 
      <td align="right" class="forumRow">确认密码：</td>
      <td class="forumRow"> 
        <input name="pass2" type="password" class="input" size="30"></td>
                    </tr>
                    <tr> 
      <td class="forumRow">&nbsp;</td>
      <td class="forumRow"> 
        <input class="bott" name="Submit" type="submit" value=" 修 改 "> 
                        <input class="bott" name="Submit2" type="reset" value=" 重 写 "></td>
                    </tr>
		</form>
		  </table>
</body>
</html>