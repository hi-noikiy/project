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

function mydb(){
	return ConnServer("203.66.13.158:3356","gameusertj","df,yyo67.yyo,ddjh","pokegametw");
}
function subTable($accountId, $table, $sum){
	$suffix = $accountId%$sum;
	$s = sprintf('%03d', $suffix);
	return $table.$s;
}


