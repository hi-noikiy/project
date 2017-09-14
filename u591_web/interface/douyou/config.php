<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/3/31 下午7:18
 * @desc:逗游配置信息
 * @param:
 * @return:
 */
define('ROOT_PATH', str_replace('interface/douyou/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array('appid'=>'717','appkey'=>'a33346aa8b9296af965a550032d2dd20'),
);