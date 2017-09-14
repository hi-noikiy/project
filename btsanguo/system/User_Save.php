<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

if (!getFlag('1',$_SESSION['u_flag'])){
	header("Location: Adm_Login.php");exit;
}
if (!getFlag('1',$uFlag)){
	header("Location: Adm_Login.php");exit;
}

$TabName="admin_user";
$Action=$_REQUEST["Action"];
$ID=$_REQUEST["ID"];

$UserName=CheckStr($_REQUEST["UserName"]);
$IsUser=$_REQUEST["IsUser"];
$PassWord=$_REQUEST["PassWord"];
$UserOper=CheckStr($_REQUEST["UserOper"]);

$num=count($_POST["UserFlag"]);
for ( $i=0; $i<$num; $i++ ){
	$ArrFlag[$i] = $_POST["UserFlag"][$i];
	$UserFlag = $UserFlag.$ArrFlag[$i].",";
}

$Add_Time=date("Y-m-d H:i:s");
 write_log_admin(ROOT_PATH."log","user_save","UserName=$UserName  UserFlag=$UserFlag "."  AdminName=$AdminName ".date("Y-m-d H:i:s")."\r\n");
if ($Action=="Add"){
	$sql="select count(0) from ".$TabName." where UserName='$UserName'";
	$query=mysql_query($sql);
	$RowCount=mysql_result($query,0);
	if ($RowCount == 0){
		$PassWord=md5($PassWord);
		$sql="insert into ".$TabName."(UserName,PassWord,UserOper,UserFlag,Add_Time)
		values('$UserName','$PassWord','$UserOper','$UserFlag','$Add_Time')";
		//echo $sql;
		//exit;
	}else{
		ErrMsg("您输入的帐号已存在！");
	}
	mysql_query($sql);
}
if ($Action=="Edit"){

	$sql="Update ".$TabName." Set UserOper='$UserOper'";
	$sql=$sql.", UserFlag='$UserFlag'";
	//如果修改密码
	if ($PassWord != ""){
		$PassWord=md5($PassWord);
		$sql=$sql.", PassWord='$PassWord'";
	}
	//如果修改帐号
	if ($IsUser != $UserName){
		$sqlStr="select count(0) from ".$TabName." where UserName='$UserName'";
		$query=mysql_query($sqlStr);
		$RowCount=mysql_result($query,0);
		if ($RowCount == 0){
			$sql=$sql.", UserName='$UserName'";
		}else{
			ErrMsg("您输入的帐号已存在！");
		}
	}
	$sql=$sql." Where ID=$ID";
	//echo $sql;
	mysql_query($sql);
}
header("Location: User_List.php");
?>