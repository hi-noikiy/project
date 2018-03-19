<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 微鲜、手Q
* ==============================================
* @date: 2016-10-25
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/ysdk/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";
include_once ROOT_PATH.'inc/config_account.php';

$key_arr = array(
		9=>array(
				'android'=>array(
					'pay'=>array('appId'=>'1106702979', 'appKey'=>'uJp2Bu6i9Iq2MZpNdWQWNwQ0oYzCKCMY'),
					'qq' => array('appId'=>'1106702979', 'appKey'=>'JMxUkSqEYPjeAGfe'),
					'weixin'=> array('appId'=>'wxbbf85c37732e87eb', 'appKey'=>'d1694e228294752c8327e68f87f8fa5a'),
						
				),
				'test'=>array(
					'pay'=>array('appId'=>'1106702979', 'appKey'=>'JMxUkSqEYPjeAGfe'),
					'qq' => array('appId'=>'1106702979', 'appKey'=>'JMxUkSqEYPjeAGfe'),
					'weixin'=> array('appId'=>'wxbbf85c37732e87eb', 'appKey'=>'d1694e228294752c8327e68f87f8fa5a'),
				),
        )
);
?>