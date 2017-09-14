<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/4/11 下午1:52
 * @desc:黑桃配置文件
 * @param:
 * @return:
 */
define('ROOT_PATH', str_replace('interface/heitao/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";
$key_arr = array(
    8=>array('secretkey'=>'3cded1ce3b60722ab5a86eff449def1c','appkey'=>'fa2e999161036985a64dd35204de2edf'),
);