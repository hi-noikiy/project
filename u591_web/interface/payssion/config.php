<?php
define('ROOT_PATH', str_replace('interface/payssion/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		8=>array(
				'els'=>array(
						'app_name'=>'精灵世界',//标记应用
						'currency'=>'USD',// 货币
						'return_url'=>'http://pokeruweb.u776hainiu.com/interface/payssion/payment.php',// 支付成功后网页跳转地址
						'action'=>'https://www.payssion.com/payment/create.html', 
						'api_key'=>'f85b4a65e8960f05',
						'secret_key'=>'1d19017903045c94b99104488e1eaae6',
				),
				'elstest'=>array(
						'app_name'=>'测试',//标记应用
						'currency'=>'USD',// 货币
						'return_url'=>'http://rmiswt.in.3322.org:47186/interface/payssion/payment.php',// 支付成功后网页跳转地址
						'action'=>'http://sandbox.payssion.com/payment/create.html', 
						'api_key'=>'fd2a432c5bd0d36b',
						'secret_key'=>'8bc2b3a255a65b30d789a716790c8d95',
				),
		)
);
