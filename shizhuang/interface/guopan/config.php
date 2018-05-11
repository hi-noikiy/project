<?php
define('ROOT_PATH', str_replace('interface/guopan/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		9=>array(
				'ios'=>array('appId'=>'116690','appSecret'=>'NK83R6OHTBOXOWRBK58AAR1HJX59LIX4QKQI2XY09JRHP9QTTX69SUSOA8IVDQ0J'),
				'android'=>array('appId'=>'116692','appSecret'=>'L7FZACH8E01VZ85748KVRWLMFV60ZU6KZILO9RXLP2IEOVLSR5FU6K71RZ41HEB5'),
		),
);
?>
