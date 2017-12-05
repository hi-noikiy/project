<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/9
 * Time: 上午9:51
 * 爱应用
 */
define('ROOT_PATH', str_replace('interface/iapp/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH.'inc/function.php';
$key_arr = array(
    8=>array(
        'ios'=>array(
            'appId'     =>'2446ec3ead7ded8c4e6b802cada9ae4b',
            'appKey'    =>'ee7bdb5332e157b3873aae18275bb105',
        ),
    	'ios1'=>array(
    				'appId'     =>'b0ef54107d5c87116eccf4484f644616',
    				'appKey'    =>'b69550f8312286b37a1eb7182c2ddc08',
    		),
    	'android'=>array(
    				'appId'     =>'199850feaa50ed6c66c88aadc2eb9e05',
    				'appKey'    =>'31f619b17e992282f13150a0b6c66029',
    		),
    ),
);
