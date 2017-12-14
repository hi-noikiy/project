<?php
define('ROOT_PATH', str_replace('interface/pyw/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		8=>array(
				'ios'=>array('appSecret'=>'8d1539e6f15aef8d'),
				'ios1'=>array('appSecret'=>'281031629f50d13b'),
				'ios3'=>array('appSecret'=>'b49e3a7b7d2b0160'),
				'ios4'=>array('appSecret'=>'1e07837a44efa19b'),
				'ios5'=>array('appSecret'=>'281031629f50d13b'),
				'ios6'=>array('appSecret'=>'3254b521fd897359'),
				'ios7'=>array('appSecret'=>'119e0309a2496916'),
				'ios8'=>array('appSecret'=>'669fcfb81fe7f8e2'),
		)
);
?>
