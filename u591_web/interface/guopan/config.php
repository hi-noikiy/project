<?php
define('ROOT_PATH', str_replace('interface/guopan/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		8=>array(
				'ios'=>array('appId'=>'106520','appSecret'=>'6H7M98TJWJXBHSWOFVDF4UNSLM12UJFI74WZ40D0XF0K8LJO1ST8UUW2QBVH258E'),
				'android'=>array('appId'=>'106268','appSecret'=>'BHNU7XIT4IOSC5UHOKY1136QC5Z85E638TJN7KJHFWE7YZ5DY1BTGAABATRUEJRP'),
		),
);
?>
