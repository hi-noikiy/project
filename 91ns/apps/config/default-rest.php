<?php

$defaultConfig = require_once APP_PATH.'/apps/config/default.php';
$urlConfig = require_once APP_PATH.'/apps/config/default-urlconfig.php';
$defaultBaseConfig = require_once APP_PATH.'/apps/config/default-base.php';
$defaultActivityConfig = require_once APP_PATH.'/apps/config/default-activity.php';
$defaultConfig = array_merge($defaultConfig, $defaultBaseConfig);
$defaultConfig = array_merge($defaultConfig, $urlConfig);
$defaultConfig = array_merge($defaultConfig, $defaultActivityConfig);
$downloadConfig = require_once APP_PATH.'/apps/config/default-download.php';
$defaultConfig = array_merge($defaultConfig, $downloadConfig);

$restConfig = array(
    'directory' => array(
        'controllersDir'    => APP_PATH . '/apps/rest/controllers',
        'modelsDir'         => APP_PATH . '/apps/models',
        'viewsDir'          => APP_PATH . '/apps/rest/views',
        'cacheDir'          => APP_PATH . '/apps/rest/cache',
        'logsDir'           => APP_PATH . '/apps/rest/logs',
    ),

    'acl' => array( //首字母大写，写controller的全称
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
    ),

);

return array_merge($defaultConfig, $restConfig);