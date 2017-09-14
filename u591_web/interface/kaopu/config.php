<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 靠谱接口配置文件
* ==============================================
* @date: 2015-10-23
* @author: Administrator
* @return:
*/
define('ROOT_PATH', str_replace('interface/kaopu/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";


$arr_key = array(
		8=>array('APPID'=>'10414001', 'APPKEY'=>'10414', 'SECRETKEY'=>'28808CD9-2E45-4862-94FF-72A7F0FFD842')
);

$four_r = array(
		'18257284-7F5D-348D-AB09-299E5B7DD997',
		'655A957D-157D-7C21-E3A7-9CAAFA835318',
		'F467CA93-D550-346D-6BCB-173995F7C83A',
		'BD32817A-99F9-2E26-5B33-15208F7B360A'
);
?>