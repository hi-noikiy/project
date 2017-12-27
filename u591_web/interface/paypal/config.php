<?php
define('ROOT_PATH', str_replace('interface/paypal/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
		8=>array(
				'nm'=>array(
						'business'=>'1069956@qq.com',//'821586178@qq.com',//卖家帐号 也就是收钱的帐号 
						'currency_code'=>'USD',// 货币 
						'return'=>'http://pokenmweb.u776hainiu.com/interface/paypal/payment.php',// 支付成功后网页跳转地址 
						'notify_url'=>'http://pokenmweb.u776hainiu.com/interface/paypal/callback.php',//'http://wanda.imwork.net:31783/interface/paypal/callback.php',//支付成功后paypal后台发送订单通知地址 
						'cancel_return'=>'http://pokenmweb.u776hainiu.com/interface/paypal/cancel.php',//用户取消交易返回地址 
						'action'=>'https://www.paypal.com/cgi-bin/websc', //通知地址//测试 https://www.sandbox.paypal.com/cgi-bin/webscr
						'charset'=>'utf-8',// 字符集 
						'no_shipping'=>'1',// 不要求客户提供收货地址 
						'app_key'=>'YcemulRSAPv7zrdH0i7I99Ejp0fw2UtIZ1gXfL4SB196Aocp0sKDiebrSsa',//'cwxYc6p5RuN7X6_J1Cx1Pc8PWS1NfPBHkodsNFg1xPjHuBuKdacWS9IHfpS',
				),
				'els'=>array(
						'business'=>'wenschan@hotmail.com',//'821586178@qq.com',//卖家帐号 也就是收钱的帐号
						'currency_code'=>'RUB',// 货币
						'return'=>'http://pokeruweb.u776hainiu.com/interface/paypal/payment.php',// 支付成功后网页跳转地址
						'notify_url'=>'http://pokeruweb.u776hainiu.com/interface/paypal/callback.php',//'http://wanda.imwork.net:31783/interface/paypal/callback.php',//支付成功后paypal后台发送订单通知地址
						'cancel_return'=>'http://pokeruweb.u776hainiu.com/interface/paypal/cancel.php',//用户取消交易返回地址
						'action'=>'https://www.paypal.com/cgi-bin/websc', //通知地址//测试 https://www.sandbox.paypal.com/cgi-bin/webscr
						'charset'=>'utf-8',// 字符集
						'no_shipping'=>'1',// 不要求客户提供收货地址
						'app_key'=>'-vHwEf_fe7FmNrOaA0TBUHbX0lzqggg2OVGbQo9fHErRDPYI5KOaMFa2d74',//'cwxYc6p5RuN7X6_J1Cx1Pc8PWS1NfPBHkodsNFg1xPjHuBuKdacWS9IHfpS',
				),
				'nmtest'=>array(
						'business'=>'821586178@qq.com',//卖家帐号 也就是收钱的帐号
						'currency_code'=>'USD',// 货币
						'return'=>'http://wanda.imwork.net:31783/interface/paypal/payment.php',// 支付成功后网页跳转地址
						'notify_url'=>'http://wanda.imwork.net:31783/interface/paypal/callback.php',//'http://wanda.imwork.net:31783/interface/paypal/callback.php',//支付成功后paypal后台发送订单通知地址
						'cancel_return'=>'http://wanda.imwork.net:31783/interface/paypal/cancel.php',//用户取消交易返回地址
						'action'=>'https://www.sandbox.paypal.com/cgi-bin/websc', //通知地址//测试 https://www.sandbox.paypal.com/cgi-bin/webscr
						'charset'=>'gb2312',// 字符集
						'no_shipping'=>'1',// 不要求客户提供收货地址
						'app_key'=>'cwxYc6p5RuN7X6_J1Cx1Pc8PWS1NfPBHkodsNFg1xPjHuBuKdacWS9IHfpS',//'cwxYc6p5RuN7X6_J1Cx1Pc8PWS1NfPBHkodsNFg1xPjHuBuKdacWS9IHfpS',
				),
		)
);
