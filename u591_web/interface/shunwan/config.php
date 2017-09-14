<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/18
 * Time: 上午11:55
 */
define('ROOT_PATH', str_replace('interface/shunwan/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array(
        'android' => array(
            'appId'     =>'196',
            'appKey'    =>'6193d27ca3f3125b',
            'secKey'    =>'42a585576193d27ca3f3125b4426aa1f',
        ),
    )
);