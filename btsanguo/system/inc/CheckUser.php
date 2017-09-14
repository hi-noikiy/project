<?
$AdminName=$_COOKIE["AdminName"];
$uFlag=$_COOKIE["uFlag"];
$LoginTime=$_COOKIE["LoginTime"];

if ($AdminName=="" || $uFlag==""){
	header("Location:Adm_Login.php");
}
?>