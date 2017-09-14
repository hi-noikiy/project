<?php

$defaultConfig = require_once APP_PATH . '/apps/config/default.php';

$defaultBaseConfig = require_once APP_PATH.'/apps/config/default-base.php';
$defaultConfig = array_merge($defaultConfig, $defaultBaseConfig);

$backendConfig = array(
    'directory' => array(
        'controllersDir' => APP_PATH . '/apps/investigator2/controllers',
        'modelsDir' => APP_PATH . '/apps/models',
        'viewsDir' => APP_PATH . '/apps/investigator2/views',
        'cacheDir' => APP_PATH . '/apps/investigator2/cache',
        'logsDir' => APP_PATH . '/apps/investigator2/logs',
    ),

);

return array_merge($defaultConfig, $backendConfig);
