<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 短信登陆   新的接口以后都采用json 返回
* ==============================================
* @date: 2016-7-14
* @author: luoxue
* @version:
*/
include_once 'config.php';
include_once 'myEncrypt.php';
global $mdString;
$post = serialize ( $_POST );
$get = serialize ( $_GET );
write_log ( ROOT_PATH . "log", "guanwang_login_log_", "post=$post, get=$get, " . date ( "Y-m-d H:i:s" ) . "\r\n" );

$token = trim ( $_REQUEST ['token'] );
$gameId = trim ( $_REQUEST ['game_id'] );
if (! $token || ! $gameId) {
	write_log ( ROOT_PATH . "log", "guanwang_login_error_log_", " parameter error!, token=$token ,game_id=$gameId, " . date ( "Y-m-d H:i:s" ) . "\r\n" );
	exit ( '2 0' );
}
$appSecret = $key_arr ['appSecret'];
$parseStr = myEncrypt::decrypt ( $token, $appSecret );
parse_str ( $parseStr, $parseArr );

write_log ( ROOT_PATH . "log", "guanwang_login_result_log_", "parseStr=$parseStr, " . date ( "Y-m-d H:i:s" ) . "\r\n" );
if (isset ( $parseArr ['username'] ) && isset ( $parseArr ['account_id'] ) && isset ( $parseArr ['game_id'] )) {
	$username = urldecode ( $parseArr ['username'] );
	$accountId = intval ( $parseArr ['account_id'] );
	$gameId = intval ( $gameId );
	$conn = SetConn ( '88' );
	$sql = "select id,token,addtime,isnew from web_token where account_id='$accountId' and game_id='$gameId'  order by id desc limit 1";
	if (false == $query = mysqli_query ( $conn, $sql )) {
		write_log ( ROOT_PATH . "log", "guanwang_login_error_log_", " sql error!, sql=$sql, " . date ( "Y-m-d H:i:s" ) . "\r\n" );
		exit ( '3 0' );
	}
	$rs = @mysqli_fetch_assoc ( $query );
	if ($rs) {
		if ($rs ['token'] == $token) {
			toNewCharge($username,$accountId);
			if ($rs ['isnew'] == 1) {
				write_log ( ROOT_PATH . "log", "guanwang_new_account_", "sql=$sql, " . date ( "Y-m-d H:i:s" ) . "\r\n" );
				$upsql = "update web_token set isnew=0 where id='{$rs['id']}'";
				mysqli_query($conn,$upsql);
			}
			exit ( "{$rs['isnew']} $accountId" );
		}
	}
} else {
	write_log ( ROOT_PATH . "log", "guanwang_login_error_log_", " sign error, post=$post, get=$get, " . date ( "Y-m-d H:i:s" ) . "\r\n" );
	exit ( '4 0' );
}
exit ( '0 999' );

function toNewCharge($phone,$accountid){
	write_log ( ROOT_PATH . "log", "guanwang_tocharge_log_", "$accountid , $phone". date ( "Y-m-d H:i:s" ) . "\r\n" );
	if(!(strlen($phone) == 11 && preg_match('/^1\d{10}$/', $phone))){
		return false;
	}
	$conn = SetConn('88');
	$sql = "select money from old_phone where phone='$phone' and status=0 limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log ( ROOT_PATH . "log", "guanwang_tocharge_error_", "sql=$sql, ".mysqli_error($conn) . date ( "Y-m-d H:i:s" ) . "\r\n" );
		return false;
	}
		
	$rs = @mysqli_fetch_assoc($query);
	if($rs && $rs['money']>0){
		$usql = "update old_phone set status=1 where phone='$phone'";
		if(false == $query = mysqli_query($conn,$usql)){
			write_log ( ROOT_PATH . "log", "guanwang_tocharge_error_", "sql=$usql, ".mysqli_error($conn) . date ( "Y-m-d H:i:s" ) . "\r\n" );
			return false;
		}
			
		$mconn = SetConn('0');
		$msql = "insert into u_alpha_recharge003(account_id,rmb) values('$accountid','{$rs['money']}')";
		if(false == $query = mysqli_query($mconn,$msql)){
			write_log ( ROOT_PATH . "log", "guanwang_tocharge_error_", "sql=$msql, ".mysqli_error($mconn) . date ( "Y-m-d H:i:s" ) . "\r\n" );
			$usql = "update old_phone set status=0 where phone='$phone'";
			$conn = SetConn('88');
			if(false == $query = mysqli_query($conn,$usql)){
				write_log ( ROOT_PATH . "log", "guanwang_tocharge_error_", "sql=$usql, ".mysqli_error($conn) . date ( "Y-m-d H:i:s" ) . "\r\n" );
				return false;
			}
		}
		write_log ( ROOT_PATH . "log", "guanwang_tocharge_success_", "sql=$msql ". date ( "Y-m-d H:i:s" ) . "\r\n" );
		return true;
	}
	return false;
}
?>