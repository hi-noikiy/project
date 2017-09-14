<?php

$defaultConfig = require_once APP_PATH.'/apps/config/default.php';

$defaultBaseConfig = require_once APP_PATH.'/apps/config/default-base.php';
$defaultConfig = array_merge($defaultConfig, $defaultBaseConfig);

$urlConfig = require_once APP_PATH.'/apps/config/default-urlconfig.php';
$defaultConfig = array_merge($defaultConfig, $urlConfig);

$activityConfig = require_once APP_PATH.'/apps/config/default-activity.php';
$defaultConfig = array_merge($defaultConfig, $activityConfig);

$downloadConfig = require_once APP_PATH.'/apps/config/default-download.php';
$defaultConfig = array_merge($defaultConfig, $downloadConfig);

$frontendConfig = array(
    'directory' => array(
        'controllersDir'    => APP_PATH . '/apps/frontend/controllers',
        'modelsDir'         => APP_PATH . '/apps/models',
        'viewsDir'          => APP_PATH . '/apps/frontend/views',
        'cacheDir'          => APP_PATH . '/apps/frontend/cache',
        'logsDir'           => APP_PATH . '/apps/frontend/logs',
    ),

    /*'acl' => array( //首字母大写，写controller的全称
        'guest'     => array('PostsController'    => array('index','show','edit','test'),
                             'PushsController'    => array('index','show'),
                             'SessionController'  => array('auth'),
                             'ActionsController'  => array('login','forgetPwd','checkUserName'),
                             'UsersController'    => array('reg')),

        'user'      => array('PostsController'    => array('index','show','edit','test'),
                             'PushsController'    => array('index','show'),
                             'SessionController'  => array('auth'),
                             'ActionsController'  => array('logout','checkUserName'),
                             'UsersController'    => array('getList','getInfo','updateInfo','changePwd','uploadAvatar'),
                             'AlbumsController'   => array('getList','add','del'),
                             'ConfigsController'  => array('getGift','getVip','getRecharge'))
    ),*/
);

return array_merge($defaultConfig, $frontendConfig);