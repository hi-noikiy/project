<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 支付宝支付web 即使支付
* ==============================================
* @date: 2016-11-24
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/alipay_web/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";