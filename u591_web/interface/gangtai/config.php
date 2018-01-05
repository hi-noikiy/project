<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/4
 * Time: 下午2:01
 * 港台
 */
define('ROOT_PATH', str_replace('interface/gangtai/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array(
        'ios' => array(
            'appid'=>'10086',
            'appkey' =>'31864c672441491990170507af676d70',
            'appsecret'=>'b9eb9e62742740e1811b59ffd913b371',
        ),
        'android' => array(
            'appid'=>'10086',
            'appkey' =>'31864c672441491990170507af676d70',
            'appsecret'=>'b9eb9e62742740e1811b59ffd913b371',
        ),
    )
);
function mydb($serverid){
	$conn1 = ConnServer("203.66.13.158:3356","gameusertj","df,yyo67.yyo,ddjh","pokegametw");
	$sql = 'select DBName,idserver1 from g_dbconfig';
	$query_account = mysqli_query($conn1, $sql);
	while($v = @mysqli_fetch_assoc($query_account)){
		if(empty($v['DBName'])){ //第一个库略过
			continue;
		}
		$myservers = explode(',', $v['idserver1']);
		foreach ($myservers as $value){
			if(substr($value, 1,1) == 9){ //pk服
				continue;
			}
			$myserver = explode('-', $value);
			if(!isset($myserver[1])){
				$myserver[1] = $myserver[0];
			}
			if($serverid>=$myserver[0] && $serverid<=$myserver[1]){
				write_log(ROOT_PATH."log","gangtai_getserver_error_","$serverid, 2库".date("Y-m-d H:i:s")."\r\n");
				return ConnServer("203.66.13.158:3357","gameusertj","df,yyo67.yyo,ddjh","pokegametw2");
				break;
			}
		}
	}
	write_log(ROOT_PATH."log","gangtai_getserver_error_","$serverid, 1库".date("Y-m-d H:i:s")."\r\n");
	return $conn1;
}
/*function mydb($serverid){
	if($serverid>=8100 && $serverid<=8170){
		return ConnServer("203.66.13.158:3357","gameusertj","df,yyo67.yyo,ddjh","pokegametw2");
	}
	return ConnServer("203.66.13.158:3356","gameusertj","df,yyo67.yyo,ddjh","pokegametw");
}*/
function subTable($accountId, $table, $sum){
	$suffix = $accountId%$sum;
	$s = sprintf('%03d', $suffix);
	return $table.$s;
}


