<?php
define('ROOT_PATH', str_replace('interface/vk/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
		8=>array(
				'ios'=>array(
						'appid'       =>'6224623',
						'appkey'       =>'m2Zld8cQ2lDzYwE95BMP',
						'appsecret'       =>'5ed92d815ed92d815ed92d81505e87d76e55ed95ed92d81073e0c0457c4500c26c137c9',
				),
				'android'=>array(
						'appid'       =>'6224623',
						'appkey'       =>'m2Zld8cQ2lDzYwE95BMP',
						'appsecret'       =>'5ed92d815ed92d815ed92d81505e87d76e55ed95ed92d81073e0c0457c4500c26c137c9',
				),
		),
);
?>
