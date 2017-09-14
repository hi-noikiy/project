<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/20
 * Time: 上午11:09
 * 盛天SKD
 */

define('ROOT_PATH', str_replace('interface/shengtian/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH.'inc/function.php';

$key_arr = array(
    'hainiuKey'		    =>'0dbddcc74ed6e1a3c3b9708ec32d0532',
    8 => array(
        'android'=>array(
            'appKey' 	    =>'448e8bbcee812835a6bff9d8434029b8',
            'appSecret'     =>'32133e3626f802bb301d9890aba07383',
        ),
    ),
);