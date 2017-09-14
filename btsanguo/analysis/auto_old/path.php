<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-12
 */
define('ROOT_PATH', str_replace('analysis/auto_old/path.php', '', str_replace('\\', '/', __FILE__)));
define('A_ROOT', ROOT_PATH.'analysis/');
if (!defined('LOG_PATH')) {
    define('LOG_PATH', A_ROOT . 'logs');
}
//echo LOG_PATH;
set_time_limit(0);
ini_set ('memory_limit', '2048M');

include A_ROOT.'config/config.php';
//include A_ROOT.'class/fun.inc.php';
//include A_ROOT.'class/autoload.php';

include A_ROOT.'inc/servers.php';

$dbList = array(
    'android' => array('analysis','gamedata'),
    'ios'     => array('analysis_ios','gamedata_ios'),
);
