<?
include_once 'init.php';




SetConn(81);//连接账号库

$account_id=$_REQUEST["account_id"];
echo searchPoints($account_id);
?>