<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/3/3
 * Time: 下午1:36
 * 一六游戏
 */
define('ROOT_PATH', str_replace('interface/16yo/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
    8=>array('appid' =>'60074', 'appkey'=>'e562776fb3ed458832a0840d50e0e88c'),
);