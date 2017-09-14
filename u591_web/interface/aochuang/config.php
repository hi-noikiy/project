<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/19
 * Time: 上午10:01
 */

define('ROOT_PATH', str_replace('interface/aochuang/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
    8=>array('appId'=>'297','appKey'=>'9a7d6d161806fef2e5a30b9760e10da9','payKey'=>'f22e49657d4bd5c622dcb83036dc3e38'),
);