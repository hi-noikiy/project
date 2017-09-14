<?php

$defaultConfig = require_once APP_PATH.'/apps/config/default.php';

$defaultBaseConfig = require_once APP_PATH.'/apps/config/default-base.php';
$defaultConfig = array_merge($defaultConfig, $defaultBaseConfig);

$backendConfig = array(
    'directory' => array(
        'controllersDir'    => APP_PATH . '/apps/backend/controllers',
        'modelsDir'         => APP_PATH . '/apps/models',
        'viewsDir'          => APP_PATH . '/apps/backend/views',
        'cacheDir'          => APP_PATH . '/apps/backend/cache',
        'logsDir'           => APP_PATH . '/apps/backend/logs',
    ),

    'backend' => array(
    	'authkey' => 'backAuth',
    ),
);

return array_merge($defaultConfig, $backendConfig);