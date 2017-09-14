<?php

require APP_PATH.'/apps/loader/default.php';

$loader->registerNamespaces(
    array(
        'Micro\Controllers'         => $config->directory->controllersDir,
        'Micro\Models'              => $config->directory->modelsDir,
    ),true
)->register();

// $loader->registerDirs(
//     array(
//         $config->directory->controllersDir,
//         $config->directory->modelsDir,
//     )
// )->register();

use Phalcon\Mvc\View as ViewResolver;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Flash\Session as FlashSession;
use Micro\Router\AdminWebSecurityPlugin as SecurityAuth;
use Micro\Router\WebNotFoundPlugin as NotFound;
use Micro\Frameworks\Logic\Investigator\InvMgrBase as InvMgrBase;

$di->set('view', function() use ($config) {
    $view = new ViewResolver();
    $view->setViewsDir($config->directory->viewsDir);
    $view->registerEngines(array(
        ".html" => 'volt'
    ));
    return $view;
});

/**
 * We register the events manager
 */
$di->set('dispatcher', function() use ($di) {

    $eventsManager = new EventsManager;

    /**
     * Check if the user is allowed to access certain action using the SecurityPlugin
     */
    $eventsManager->attach('dispatch:beforeDispatchLoop', new SecurityAuth());

    /**
     * Handle exceptions and not-found exceptions using NotFoundPlugin
     */
    $eventsManager->attach('dispatch:beforeException', new NotFound());

    $dispatcher = new Dispatcher;
    $dispatcher->setEventsManager($eventsManager);
    $dispatcher->setDefaultNamespace('Micro\Controllers\\');

    return $dispatcher;
});

/**
 * Setting up volt
 */
$di->set('volt', function($view, $di) use ($config) {

    $volt = new VoltEngine($view, $di);

    $volt->setOptions(array(
        "compiledPath" => $config->directory->cacheDir."/html/",
        "compileAlways" => true 						//debug
    ));

    $compiler = $volt->getCompiler();
    $compiler->addFunction('is_a', 'is_a');

    $compiler->addFilter('ns_dateOfHours_', function($resolvedArgs, $exprArgs) {
        return $resolvedArgs . '>=60?(floor(' . $resolvedArgs . '/3600)."小时".floor((' . $resolvedArgs . '%3600)/60)."分钟"):"少于一分钟"';
    });

    return $volt;
}, true);

/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function(){
    return new FlashSession(array(
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
    ));
});

/**
 * 客服后台--公用对象  无权限限制
 */
$di->setShared('invMgrBase', function() use ($config) {
    return new InvMgrBase();
});

