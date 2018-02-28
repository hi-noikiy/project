<?php
include_once 'config.php';
global $mdString;
$get = serialize($_GET);
global $mdString;
write_log(ROOT_PATH."log","flushnews_log_","get=$get, ".date("Y-m-d H:i:s")."\r\n");
$gameId = 5;
$time = trim($_GET['time']);
$sign = trim($_GET['sign']);
$mySign = md5($time.$mdString);
if($sign != $mySign){
	write_log(ROOT_PATH."log","flushnews_error_","sign error,get=$get, ".date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
$serverid = '';
$conn = SetConn($serverid);
$sql = "update u_news_flush set time='$time' where id=1";
if(false == mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","flushnews_error_",$sql.",sql error,get=$get, ".mysqli_error($conn).date("Y-m-d H:i:s")."\r\n");
	exit('fail');
}
write_log(ROOT_PATH."log","flushnews_error_",$sql.",get=$get, ".date("Y-m-d H:i:s")."\r\n");
exit('success');
?>
