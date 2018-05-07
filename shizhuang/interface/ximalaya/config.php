<?php
define('ROOT_PATH', str_replace('interface/ximalaya/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		9=>array(
				'android'=>array('appKey'=>'b6814f95ad0fb1676d820388330e506c','appSecret'=>'66e1b1d90bf310892557eb66c5d91f83'),
		)
);
?>
