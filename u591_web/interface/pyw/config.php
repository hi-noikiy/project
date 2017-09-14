<?php
define('ROOT_PATH', str_replace('interface/pyw/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		8=>array(
				'ios'=>array('appSecret'=>'8d1539e6f15aef8d'),
				'ios1'=>array('appSecret'=>'281031629f50d13b'),
		)
);
?>
