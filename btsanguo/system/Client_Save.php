<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$TabName="client_data";
$Action=$_REQUEST["Action"];
$ID=$_REQUEST["ID"];

$cName=CheckStr($_REQUEST["cName"]);
$hotName=CheckStr($_REQUEST["hotName"]);
$cType=$_REQUEST["cType"];
$cPath=CheckStr($_REQUEST["cPath"]);
$content=CheckStr($_REQUEST["content"]);
$IsJad=$_REQUEST["IsJad"];
$IsHide=$_REQUEST["IsHide"];
$IsHot=$_REQUEST["IsHot"];
$HitCount=$_REQUEST["HitCount"];
$Add_Time=$_REQUEST["Add_Time"];

$game_id=intval($_REQUEST["game_id"]);

if($IsHide=="") $IsHide=0;
if($IsHot=="") $IsHot=0;

if ($Action=="Add"){
	
	$upClass=new upload("NewsFile","jpg|gif|bmp","500",$NewsPath);
	$ImgPath=$upClass->UploadFile();//开始上传

	$sql="insert into ".$TabName."(cName,hotName,cType,cPath,content,IsJad,IsHide,IsHot,HitCount,ImgPath,Add_Time,game_id)
	values('$cName','$hotName',$cType,'$cPath','$content','$IsJad','$IsHide','$IsHot',$HitCount,'$ImgPath','$Add_Time','$game_id')";
	//echo $sql;
	//exit;
	mysql_query($sql);
}
if ($Action=="Edit"){

	$upClass=new upload("NewsFile","jpg|gif|bmp","500",$NewsPath);
	$ImgPath=$upClass->UploadFile();//开始上传
	
	$result=mysql_query("select ImgPath from ".$TabName." Where ID=$ID");
	$Fstr=mysql_result($result,0);
	//检测是否存在图片
	if ($ImgPath!=""){
		$sqlImg=", ImgPath='$ImgPath'";
		if ($Fstr!=""){
		ErrMsg("请先删除图片，再上传");
		exit;
		}
	}
	$sql="Update ".$TabName." Set cName='$cName',hotName='$hotName', cType=$cType, cPath='$cPath'";
	$sql=$sql.", IsJad='$IsJad', IsHide='$IsHide',IsHot='$IsHot', HitCount=$HitCount,game_id=$game_id";
	$sql=$sql.$sqlImg.", ImgPath='$ImgPath', content='$content', Add_Time='$Add_Time' Where ID=$ID";
	//echo $sql;
	mysql_query($sql);
}

header("Location: Client_List.php");
?>