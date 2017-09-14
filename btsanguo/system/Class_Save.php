<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$Action=$_REQUEST["Action"];
$ClassName=CheckStr($_REQUEST["ClassName"]);
$ClassID=$_REQUEST["ClassID"];
$IsHide=$_REQUEST["IsHide"];
$game_id=$_REQUEST["game_id"];
if ($Action=="Add"){
	$sql="insert into news_class(ClassName,ParentID,IsHide,game_id)
	values('$ClassName',$ClassID,$IsHide,$game_id)";
	mysql_query($sql);
}
if ($Action=="Edit"){
	$sql="Update news_class Set ClassName='$ClassName' Where ClassID=$ClassID";
	mysql_query($sql);
}
if ($Action=="Move"){
	$MoveID=$_REQUEST["MoveID"];
	$sql="Update news_class Set ParentID=$MoveID Where ClassID=$ClassID";
	mysql_query($sql);
}
mysql_close();
header("Location: Class_List.php");
?>