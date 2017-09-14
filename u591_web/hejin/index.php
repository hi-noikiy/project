<?php
define('APPNAME', 'hejin');
define('ROOT_PATH', str_replace('hejin/index.php', '', str_replace('\\', '/', __FILE__)));

date_default_timezone_set('PRC');
// change the following paths if necessary
require_once(dirname(__FILE__).'/func/func.php');
require_once (ROOT_PATH.'system/config/config.php');


$yii =ROOT_PATH.'system/framework/yii.php';
$config = dirname(__FILE__).'/home/protected/config/main.php';

// remove the following lines when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);
define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

Yii::createWebApplication($config)->run();