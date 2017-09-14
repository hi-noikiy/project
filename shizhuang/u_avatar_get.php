<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 获取头像
* ==============================================
* @date: 2015-11-24
* @author: Administrator
* @return:
*   "2"      参数异常
*   '3'      sql异常
*   "4"      验证出错
*   "0"  	  找不到图片    
*   "图片名称"  成功
*/
include("./inc/config.php");
include("./inc/function.php");
$accountid = intval($_POST['accountid']);
$sign = $_POST['sign'];
$get = serialize($_GET);
$post = serialize($_POST);

write_log("log","avatar_get_info_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
if(!$accountid)
	exit('2');
$conn = SetConn('81');
$sql = "select id from account where id = '$accountid' limit 1";
$query= @mysqli_query($conn, $sql);
if($query == false){
	write_log("log","avatar_get_error_"," sql=$sql, post=$post, get=$get,". mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit('3');
}
$result=mysqli_fetch_assoc($query);
if(!$result)
	exit('4');
$rId = intval($result['id']);
$conn = SetConn('999');
$webSql = "select avatar from sz_user_avatar where account_id = $rId order by id desc limit 1";
$webQuery = mysqli_query($conn, $webSql);
if($webQuery == false){
	write_log("log","avatar_get_error_"," sql=$webSql, ".date("Y-m-d H:i:s")."\r\n");
	exit('3');
}
$re = mysqli_fetch_assoc($webQuery);
if($re['avatar'])
	exit($re['avatar']);
exit('0');