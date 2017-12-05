<?php
define('ROOT_PATH', str_replace('interface/sanxing/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$karr = array(
	'android'=> "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKjScHuWqaEApYhdJ7
B3zmzHNxMf4286olqspqkg+aicVGiZAmd2L5XMQT/
m6LSYr132eLqA4Y768whu9YC8RnGxbwtQA7/
Y4LCMfgGIP74FEqpBMIccsyj7P8bobKqpD+krF5KZSm/
2tGIy2kJNGbduGcJoaVzmJw2/S608AK9QIDAQAB
-----END PUBLIC KEY-----",	
		'android1'=> "-----BEGIN PUBLIC KEY-----\n".
		wordwrap('MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCCf+eMJvvCqAMpEw4os2hP5bvtrFEELPpsh1dbTvvRxFiQUisnyvHjUzfOK0vtndZ/5OOyURQbJKwmChY51EhRdtU29mlCxzCC5UMN5EmxCkMz7umtJrvIb0KnGUovv+01hUMPJmXjtFxyIjMN7QWoZ2KVJAGKKEAcWUp6LefdHQIDAQAB', 64, "\n", true) .
		"\n-----END PUBLIC KEY-----",
);
?>
