<?php
define('ROOT_PATH', str_replace('interface/guangwang/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		'qq' 	=>'fcb2d5437b050de1a022964cb926f534',
		'weixin'=>'1efbdee92855e1ac82fbe0675b66f7ae',
		'key'	=>'0dbddcc74ed6e1a3c3b9708ec32d0532',
);

function toNewCharge($channel_account,$accountid){
	write_log ( ROOT_PATH . "log", "guanwang_tocharge_log_", "$accountid , $channel_account". date ( "Y-m-d H:i:s" ) . "\r\n" );
	$conn = SetConn('88');
	$sql = "select money from old_phone where channel_account='$channel_account' and status=0 limit 1";
	if(false == $query = mysqli_query($conn,$sql)){
		write_log ( ROOT_PATH . "log", "guanwang_tocharge_error_", "sql=$sql, ".mysqli_error($conn) . date ( "Y-m-d H:i:s" ) . "\r\n" );
		return false;
	}

	$rs = @mysqli_fetch_assoc($query);
	if($rs && $rs['money']>0){
		$usql = "update old_phone set status=1 where channel_account='$channel_account'";
		if(false == $query = mysqli_query($conn,$usql)){
			write_log ( ROOT_PATH . "log", "guanwang_tocharge_error_", "sql=$usql, ".mysqli_error($conn) . date ( "Y-m-d H:i:s" ) . "\r\n" );
			return false;
		}
			
		$mconn = SetConn('0');
		$msql = "insert into u_alpha_recharge003(account_id,rmb) values('$accountid','{$rs['money']}')";
		if(false == $query = mysqli_query($mconn,$msql)){
			write_log ( ROOT_PATH . "log", "guanwang_tocharge_error_", "sql=$msql, ".mysqli_error($mconn) . date ( "Y-m-d H:i:s" ) . "\r\n" );
			$usql = "update old_phone set status=0 where channel_account='$channel_account'";
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
