<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/5/12 上午9:26
 * @desc:铠甲sdk
 * @param:
 * @return:
 */
define('ROOT_PATH', str_replace('interface/kaijia/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH.'inc/function.php';
$key_arr = array(
    8=>array(
        'android'=>array(
            'appid'     =>'26',
            'appkey'    =>'d0f88c83753a9990da2039331b541ba6',
            'paykey'    =>'ae37e9500f392e7f3a49a8535d24e5a0',
        ),
    	'android1'=>array(
    				'appid'     =>'40',
    				'appkey'    =>'4bbf2798046b19a26115e751e8352b26',
    				'paykey'    =>'d2de0a499baa4ef2d297d24c0322f16d',
    	),
    	'ios'=>array(
    		'appid'     =>'40',
    		'appkey'    =>'4bbf2798046b19a26115e751e8352b26',
    		'paykey'    =>'d2de0a499baa4ef2d297d24c0322f16d',
    	),
    	'ios1'=>array(
    				'appid'     =>'41',
    				'appkey'    =>'e53ed3a0359fc871e1c07ab2f1d7130b',
    				'paykey'    =>'3fa6ce547b9e4ab2fb1fc75928be438f',
    	),
    	'ios2'=>array(
    				'appid'     =>'42',
    				'appkey'    =>'c6e5401f91487839f4565972c633fb7b',
    				'paykey'    =>'796d10a99a0fd8ab50256822b39a3dd2',
    	),
    ),
);