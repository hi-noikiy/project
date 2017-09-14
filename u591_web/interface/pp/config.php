<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* pp助手
* ==============================================
* @date: 2016-11-25
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/pp/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		8=>array('app_id'=>'7649','app_key'=>'2e3c7038ea5921203a156933250c71d7')
);

$pp_ip = array("58.218.248.205","122.192.164.118","58.218.248.205","58.218.147.104","120.31.134.125","122.13.176.125","183.238.95.70","222.187.82.46","122.192.164.118");
?>