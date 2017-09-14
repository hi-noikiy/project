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
		8=>array(
			//'qq' => array('appId'=>'1105668083', 'appKey'=>'PLUtnIO8Gj6rw3Et'),
            //'weixin'=> array('appId'=>'wxd9be18255b54d001', 'appKey'=>'6f7dacf690359a6b0c382a280ea8d555'),
            'pay'=>array('appId'=>'1105668083', 'appKey'=>'7I2JOhhYI9qJKocaw8cGKOipYmquojNP'),
            //沙箱appKey:7I2JOhhYI9qJKocaw8cGKOipYmquojNP
			//XjbAtJfAtCAhT7CY9ItBH4adVK9AriVd
            'qq' => array('appId'=>'1106023787', 'appKey'=>'BGdXwbnQvdsaDLzs'),
            'weixin'=> array('appId'=>'wxcec897d93abfa26c', 'appKey'=>'d278e59e2016ece002c65affaa6dbeab'),
        )
);
?>