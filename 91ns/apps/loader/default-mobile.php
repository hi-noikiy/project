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

use Micro\Router\WebSecurityPlugin as SecurityAuth;
use Micro\Router\WebNotFoundPlugin as NotFound;


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
	//$eventsManager->attach('dispatch:beforeDispatch', new SecurityAuth());

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

    //NS自定义过滤器
    $compiler->addFilter('ns_date', function($resolvedArgs, $exprArgs) {
        return 'date("Y年m月d日",' . $resolvedArgs . ')';
    });

    $compiler->addFilter('ns_date2', function($resolvedArgs, $exprArgs) {
        return 'date("Y-m-d",' . $resolvedArgs . ')';
    });

    $compiler->addFilter('ns_dateOfDay', function($resolvedArgs, $exprArgs) {
        return 'floor(' . $resolvedArgs . '/(3600*24))';
    });

    $compiler->addFilter('ns_dateOfHours_', function($resolvedArgs, $exprArgs) {
        return $resolvedArgs . '>=60?(floor(' . $resolvedArgs . '/3600)."小时".floor((' . $resolvedArgs . '%3600)/60)."分钟"):"少于一分钟"';
    });

	$compiler->addFilter('ns_css', function($resolvedArgs, $exprArgs) {
		$css = 'css';
		$resolvedArgs = substr($resolvedArgs, 1, -1);
        return '"web/'.$css.'/'.$resolvedArgs.'.css?v=1.2"';
    });

	$compiler->addFilter('ns_css_version', function($resolvedArgs, $exprArgs) {
		$css = $this->config->url->cssURL;
		$resolvedArgs = substr($resolvedArgs, 1, -1);
        return '"'.$css.$resolvedArgs.'.css?v=1.6"';
    });

	$compiler->addFilter('ns_js', function($resolvedArgs, $exprArgs) {
		$js = 'js';
		$resolvedArgs = substr($resolvedArgs, 1, -1);
        return '"web/'.$js.'/'.$resolvedArgs.'.js?v=2.9"';
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

$di->set('router', function(){
    $router = new \Phalcon\Mvc\Router();

    $router->add(
    	"/:int",
    	array(
    		"controller" => "rooms",
    		"action" => "index",
    		"params" => 0
    	));

    return $router;
});

