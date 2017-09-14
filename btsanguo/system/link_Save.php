<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");

$TabName="link_data";
$Action=$_REQUEST["Action"];
$ID=$_REQUEST["ID"];

$linkName=trim($_REQUEST["linkName"]);
$linkUrl=trim($_REQUEST["linkUrl"]);
$linkType=$_REQUEST["linkType"];
$isPic=$_REQUEST["isPic"];
$linkLogo=trim($_REQUEST["linkLogo"]);
$linkInfo=CheckStr($_REQUEST["linkInfo"]);
$isHide=$_REQUEST["isHide"];

if($isHide=="") $isHide=0;

if ($Action=="Add"){

	$Add_Time=date('Y-m-d H:i:s');
	$sql="insert into ".$TabName."(linkName,linkUrl,linkType,linkLogo,linkInfo,isPic,isHide,addOper,add_Time)
	values('$linkName','$linkUrl',$linkType,'$linkLogo','$linkInfo',$isPic,$isHide,'$AdminName','$Add_Time')";
	//echo $sql;
	//exit;
	mysql_query($sql);
}
if ($Action=="Edit"){
	
	$sql="Update ".$TabName." Set linkName='$linkName', linkUrl='$linkUrl', linkType=$linkType";
	$sql=$sql.", linkLogo='$linkLogo', linkInfo='$linkInfo', isPic=$isPic, isHide=$isHide Where ID=$ID";
	//echo $sql;
	//exit;
	mysql_query($sql);
}

header("Location: link_List.php");
?>