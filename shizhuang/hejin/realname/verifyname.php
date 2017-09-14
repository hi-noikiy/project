<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 实名认证
* ==============================================
* @date: 20170523
* @author: 王涛
* @return:
*/
error_reporting(0);
include_once 'config.php';
$get = json_encode($_GET);
$accountId = $_GET['accountId'];
$game_id = $_GET['game_id']?$_GET['game_id']:8;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql = "SELECT id FROM real_name WHERE accountId = ?";
bindParam($sql, [$accountId]);

if(false == $query = mysqli_query($conn,$sql)){
	write_log(ROOT_PATH."log","verify_error_log_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit('3');
}
$result = @mysqli_fetch_assoc($query);
if($result){ //该账户已认证
	$insert_id = $result['id'];
	exit("1");
}


exit('0');
?>
