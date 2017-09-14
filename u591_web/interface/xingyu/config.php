<?php
/**
 * @created by PhpStorm.
 * @user: luoxue
 * @date: 2017/3/23 下午2:11
 * @desc: 星宇sdk配置文件
 * @param:
 * @return:
 */
define('ROOT_PATH', str_replace('interface/xingyu/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH."inc/function.php";
include_once ROOT_PATH.'inc/config_account.php';

$key_arr = array(
    8=>array('appid'=>'F2B11ADD1D1258DE7','appkey'=>'liu19900327','appsecret'=>'V1xQQF1W'),
);