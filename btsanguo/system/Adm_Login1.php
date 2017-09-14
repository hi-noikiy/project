<?
include("inc/config.php");
include("inc/function.php");

if (ipLimit("loginConfig.txt")==False){
	echo "对不起，您无权访问，请与管理员联系！";
	exit;
}

$Action=$_REQUEST["Action"];
$UserName=$_POST["UserName"];
$PassWord=md5($_POST["PassWord"]);

if ($Action=="login"){
    $UserName = mysql_real_escape_string($UserName);
	$sql="select UserName,PassWord,LoginTime,UserFlag from admin_user where UserName='$UserName'";
	$conn=mysql_query($sql);
	$rs=mysql_fetch_array($conn);
	if($rs) {
		if(trim($rs[PassWord]) != $PassWord){
			echo"<script>alert('您的密码有误！');history.go(-1);</script>";
		}else{
			$IP=getenv('REMOTE_ADDR');
			$sql="Update admin_user set LoginIP='$IP',LoginNum=LoginNum+1,LoginTime=now() where UserName='$UserName'";
			mysql_query($sql);
			setcookie('AdminName' , $rs[UserName]);
			setcookie('uFlag' , $rs[UserFlag]);
			setcookie('LoginTime' , $rs[LoginTime]);
			header("Location:index.php");
		}
	}else{
		echo"<script>alert('您的用户名有误！');history.go(-1);</script>";
	}
}
?>
<html>
<head>
<title>后台管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function SetFocus(){
if (document.form1.UserName.value=="")
	document.form1.UserName.focus();
else	
	document.form1.UserName.select();
}
function CheckForm(){
	if(document.form1.UserName.value==""){
		alert("请输入用户名！");
		document.form1.UserName.focus();
		return false;
	}
	if(document.form1.PassWord.value == "")	{
		alert("请输入密码！");
		document.form1.PassWord.focus();
		return false;
	}
}
</script>
</head>
<body onLoad="SetFocus();">
<form name="form1" method="post" action="<?=getPath()?>?Action=login" onSubmit="return CheckForm();">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="333" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td><img src="Images/login_top.jpg" width="369" height="90"></td>
    </tr>
    <tr> 
      <td height="120" background="Images/login_cen.jpg"> 
        <table width="65%" border="0" align="center" cellpadding="3" cellspacing="1">
          <tr> 
            <td width="69" align="right">帐号：</td>
            <td width="150"><input name="UserName" type="text" size="15"></td>
          </tr>
          <tr> 
            <td align="right">密码：</td>
            <td> <input name="PassWord" type="password" class="input" size="15"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="ImageUser" type="image" src="Images/login.jpg" width="76" height="26" border="0"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td><img src="Images/login_bot.jpg" width="369" height="12"></td>
    </tr>
  </table>
</form>
</body>
</html>