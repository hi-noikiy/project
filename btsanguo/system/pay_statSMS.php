<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");

$OrderID=CheckStr($_REQUEST["OrderID"]);
$rpTime=date('Y-m-d H:i:s');

$sql="update pay_sms set rpCode='DELIVRD', rpTime='$rpTime'";
$sql=$sql." where LinkID='$OrderID'";
echo $sql;
mysql_query($sql);
echo '0';
?>