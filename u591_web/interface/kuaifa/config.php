<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/1/5
 * Time: 下午3:00
 */
define('ROOT_PATH', str_replace('interface/kuaifa/config.php', '', str_replace('\\', '/', __FILE__)));
include_once ROOT_PATH.'inc/config.php';
include_once ROOT_PATH.'inc/config_account.php';
include_once ROOT_PATH."inc/function.php";

$key_arr = array(
    8=>array(
        'android'=>array('gamekey'=>'376d5cd3dd298a5af4537e663018a10f','securutykey'=>'YGR0PmsKy88wORlHQF7jZnrHVOxeBzsj'),

    ),
);