<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
$mdString = 'fu;djf,jk7g.fk*o3l';

function SetConn($ServerInfo){
	switch ($ServerInfo){	
		case 88:
		case 999:	
	        //return ConnServer('127.0.0.1', 'root', 'u591,hainiu*', 'shizhuang');
	        return ConnServer("127.0.0.1","root","root","u591_hj");
			break;
	  	case 81:
       		return ConnServer("106.75.27.74","accountmyuser","rf,d5,8.fgig,fj.80rtyuyjj464j","account", '3356');
			break;
	  	case 90:
	  		return ConnServer('123.59.144.183', 'gameuser', 'rio8t89o,690.60fk', 'fhgame1', '3316');
	  		break;
		default:
			return ConnServer('123.59.144.183', 'gameuser', 'rio8t89o,690.60fk', 'fhgame2', '3356');
	  		break;
	}
}
/**
 * 合服函数
 * @param $server
 * @return int
 */
function togetherServer($server){
	switch ($server){
		default :
			return $server;
			break;
	}
}
function ConnServer($db_host,$db_user,$db_pass,$db_database, $port = 3306){
    $db = @mysqli_connect($db_host,$db_user,$db_pass,$db_database, $port);
    if(!$db){
        $db = @mysqli_connect($db_host,$db_user,$db_pass,$db_database, $port);
    }
    if(!$db){
        write_log(ROOT_PATH."log","mysql_connect_log_","mysql connect error,".mysqli_connect_error().", $db_host,$db_user,$db_pass,$db_database, ".date("Y-m-d H:i:s")."\r\n");
        return false;
    }
    return $db;
}
?>