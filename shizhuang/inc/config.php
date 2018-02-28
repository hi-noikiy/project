<?php
header ( "Content-type:text/html;charset=utf-8" );
date_default_timezone_set ( 'Asia/Shanghai' );
$mdString = 'fu;djf,jk7g.fk*o3l';
function SetConn($ServerInfo,$accnum=0,$type=0) {
	switch ($ServerInfo) {
		case 88 :
			return ConnServer ( '127.0.0.1', 'root', 'root', 'shizhuang' );
			break;
		case 9 : //账服
			switch ($type) {
				case 0 : // bind分表
				case 1 : // account分表
					switch ($accnum) { // 表的后缀数字
						default :
							return ConnServer ( "122.112.226.86", "gameuser", "rio8t89o,690.60fk", "account", '3356' );
							break;
					}
					break;
			}
			break;
		
		case 6998 :
			return ConnServer ( '122.112.226.41', 'gameuser', 'rio8t89o,690.60fk', 'Travelgamesh', '3356' );
			break;
		case 8998 :
			return ConnServer ( '122.112.226.41', 'gameuser', 'rio8t89o,690.60fk', 'fhgamesh', '3356' );
			break;
		default :
			
			// return ConnServer('123.59.144.183', 'gameuser', 'rio8t89o,690.60fk', 'fhshenhe', '3356');
			return ConnServer ( '122.112.226.41', 'gameuser', 'rio8t89o,690.60fk', 'fhgame', '3356' );
			break;
	}
}
/**
 * 合服函数
 * 
 * @param
 *        	$server
 * @return int
 */
function togetherServer($server) {
	switch ($server) {
		default :
			return $server;
			break;
	}
}
function ConnServer($db_host, $db_user, $db_pass, $db_database, $port = 3306) {
	$db = @mysqli_connect ( $db_host, $db_user, $db_pass, $db_database, $port );
	if (! $db) {
		$db = @mysqli_connect ( $db_host, $db_user, $db_pass, $db_database, $port );
	}
	if (! $db) {
		write_log ( ROOT_PATH . "log", "mysql_connect_log_", "mysql connect error," . mysqli_connect_error () . ", $db_host,$db_user,$db_pass,$db_database, " . date ( "Y-m-d H:i:s" ) . "\r\n" );
		return false;
	}
	return $db;
}
?>
