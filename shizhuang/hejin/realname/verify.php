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
$post = json_encode($_POST);
//write_log(ROOT_PATH."log","verify_info_log_","post=$post, ".date("Y-m-d H:i:s")."\r\n");
$name = $_POST['name'];
$phone = $_POST['phone'];
$qq = $_POST['qq'];
!$qq && $qq=0;
!$phone && $phone=0;
$idCard = $_POST['cardnum'];
$accountId = $_POST['accountId'];
$game_id = $_POST['game_id']?$_POST['game_id']:8;
$accountConn = $accountServer[$game_id];
$conn = SetConn($accountConn);
$sql = "SELECT id FROM real_name WHERE accountId = ?";
bindParam($sql, [$accountId]);

if(false == $query = mysqli_query($conn,$sql)){
	//write_log(ROOT_PATH."log","verify_error_log_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit('3');
}
$result = @mysqli_fetch_assoc($query);
if($result){ //该账户已认证
	$insert_id = $result['id'];
	exit("1");
}

$sql = "SELECT GROUP_CONCAT(accountId) accounts FROM real_name WHERE name = ? AND  idCard = ?";
bindParam($sql, [$name,$idCard]);
if(false == $query = mysqli_query($conn,$sql)){
	//write_log(ROOT_PATH."log","verify_error_log_","$accountConn, sql=$sql, mysql error, ".mysqli_error($conn)." ".date("Y-m-d H:i:s")."\r\n");
	exit('3');
}
$result = @mysqli_fetch_assoc($query);
if(!($result && $result['accounts'])){ //本地库认证不成功调用第三方库操作
	//待执行
}


/****插入数据****/
$sql_game = "insert into real_name (qq,phone,idCard,name, createTime,accountId) VALUES ($qq,$phone,'$idCard','$name',".time().", ?)";
bindParam($sql_game, [$accountId]);
mysqli_query($conn, $sql_game);
$insert_id = mysqli_insert_id($conn);
if($insert_id){
	//write_log(ROOT_PATH."log","new_realname_log_"," realname new, post=$post, "."return= 1 $insert_id  ".date("Y-m-d H:i:s")."\r\n");
	exit("1");
}
/**** end****/

exit('999');
?>
