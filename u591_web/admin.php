<?php
error_reporting(0);
define('APPNAME', 'system/admin');
define('ROOT_PATH', str_replace('admin.php', '', str_replace('\\', '/', __FILE__)));

date_default_timezone_set('PRC');
// change the following paths if necessary
$yii=dirname(__FILE__).'/system/framework/yii.php';
$config=dirname(__FILE__).'/system/admin/protected/config/main.php';
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once(dirname(__FILE__).'/system/func/func.php');
require_once(dirname(__FILE__).'/system/config/config.php');


require_once($yii);
Yii::createWebApplication($config)->run();
