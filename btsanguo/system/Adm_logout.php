<?
setcookie('AdminName',0,time()-1);
setcookie('AdminPwd',0,time()-1);
setcookie('LoginTime',0,time()-1);
session_start();
$_SESSION['admin_name']= null;
$_SESSION['u_flag']= null;
$_SESSION['login_time']= null;
header("Location:Adm_Login.php");
?>