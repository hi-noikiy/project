<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");

$OrderID=CheckStr($_REQUEST["OrderID"]);
$rpTime=date('Y-m-d H:i:s');

$sql="update pay_log set rpCode='1', rpTime='$rpTime'";
$sql=$sql." where OrderID='$OrderID'";
//echo $sql;
mysql_query($sql);
echo '0';
?>