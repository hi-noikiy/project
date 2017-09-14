<?php
define('ROOT_PATH', str_replace('interface/huya/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
		8=>array(
				'android'=>array('appId'=>'46','appKey'=>'218dfa7c603d7c6b83a2d9fbb8149a37','appSecret'=>'9960a68db5be46f5c7f8665908b407bc'),
				'android1'=>array('appId'=>'118','appKey'=>'c42607294608aa2594a12f85d62779b4','appSecret'=>'6c54293f03213dee463c5723e1fea046'),
		),
);
?>
