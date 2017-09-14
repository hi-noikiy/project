<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/8
 * Time: 下午1:36
 */
define('ROOT_PATH', str_replace('interface/haima/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH.'inc/function.php';
$key_arr = array(
    8=>array(
    	'android'=>array(
    				'appId'     =>'d7f0bca27ed2e33c38c308c0b650e330',
    				'appKey'    =>'62504da92a7ffc594e2ccc6fae3b6f10',
    	),
    	'ios'=>array(
    			'appId'     =>'1c35af7d138cf9a2175cb659b4bca6a9',
    			'appKey'    =>'22506f0bb7f073d7bda7167842fac3b9',
    	),	
    ),
);