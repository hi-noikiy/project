<?php
define('ROOT_PATH', str_replace('interface/360/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
    9=>array(
    		'android'=>array('app_key'=>'b2c0e9cd9fcd64e7e978dbf2a4b18012','app_secret'=>'d15f417fc84df718fc261c133b31ca5c')
    		
    ),
);

?>
