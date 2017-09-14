<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/23
 * Time: 下午7:03
 */
define('ROOT_PATH', str_replace('interface/aile/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array(
        'appId'     =>'362603',
        'appKey'    =>'347d4c9483021ea533cb3620cc290acd',
    )
);