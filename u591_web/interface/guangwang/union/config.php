<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 爱普
* ==============================================
* @date: 2016-11-10
* @author: luoxue
* @version:
*/
define('ROOT_PATH', str_replace('interface/union/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$arr_key = array(
		8=>array(
				'appSecret'=>'366b5a1cef6e03b6cfb3c3babdbda5ee',
				'appKey'=>'b366966f1720694956c51e0c42058256', 
				'payPublicKey'=>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCCvAjlr0VpIfl2wODbO2Lvkg4pUWBeXDgHFuZymxaL0aPz2PayPrivateKey:MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIK8COWvRWkh+XbA4Ns7Yu+SDilRYF5cOAcW5nKbFovRo/Pb+sIFGkUNHuT5YF5t4kWeJfHJ4sz0sWhCceMOqdG727X/1WLPoKy3C5+PHLBDYq6udMttjgJ65fQCPx8YGyELJztyiBsuY6+Uhdt67v1Foht+gojBlA6ic0+pA1WjAgMBAAECgYAhkBaUqOef8vnxc9kGT3u513xNPTgJYZF8uHNXLtud76cwvuPklZzF53VsAV2tQHabutsw9MyGI7e79Xr5eNU9xXF3KOtwqfSqAAJAzoXiOsN3rgK8d5y0Q1s/e5ergiWd79OqF4VFHfK0xEwLqNyr987+8TR9cYIVSQn2yuZ0aQJBAM2w2DHUFCBt+pPgE5M59/U9RxblRRv5JW6OqyMoGTU4qHbx21zcj5LQNaZWJiD65HTMGJLX/adilbHcC6KjZk0CQQCitd2Z3ip++atD1XXo4uA4g6xAiYHe1tMJF6AqOLVebqZTNtJw8S1A3T7B6YkPg7P13vY+N/nyaXvQTMX/U4OvAkBWohx86DlN62R18hPsl6bTOOr/PrOb85ULerWkChiL7QAvkPB2rUMfb+iY1YIbs/CTLP9Qof5pCMHJ9sdDyhSlAkAEKn/oBqYz5hSaggao1dZD3Cs2485tdDanDvHM4vLR3idoDglJVwN/m6qVsHMP8KFU9EZ9xVUi/cxrwut25B6FAkEAx6ECdo55pjn2UrOaZU2IJsJMIC+iwvoHjtCbM+Quu9FeKbMOScjIv8ej4mrppsBSr0Ol3bNQrUqkk5JcqzQwmw==',
		),
);
?>