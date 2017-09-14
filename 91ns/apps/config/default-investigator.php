<?php

$defaultConfig = require_once APP_PATH . '/apps/config/default.php';

$defaultBaseConfig = require_once APP_PATH.'/apps/config/default-base.php';
$defaultConfig = array_merge($defaultConfig, $defaultBaseConfig);

$activityConfig = require_once APP_PATH.'/apps/config/default-activity.php';
$defaultConfig = array_merge($defaultConfig, $activityConfig);

$backendConfig = array(
    'directory' => array(
        'controllersDir' => APP_PATH . '/apps/investigator/controllers',
        'modelsDir' => APP_PATH . '/apps/models',
        'viewsDir' => APP_PATH . '/apps/investigator/views',
        'cacheDir' => APP_PATH . '/apps/investigator/cache',
        'logsDir' => APP_PATH . '/apps/investigator/logs',
    ),
    'investigator' => array(
    	'authkey' => 'gmAuth',
        'cashkey' => 'cashKey'
    ),

);

return array_merge($defaultConfig, $backendConfig);
