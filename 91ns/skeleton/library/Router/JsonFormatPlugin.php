<?php

namespace Micro\Router;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Micro;

class JsonFormatPlugin extends Plugin
{
	public function afterExecuteRoute(Event $event, Micro $app)
	{
             if(isset($_GET['callback'])){
                 echo $_GET['callback']."(".json_encode($app->getReturnedValue(), JSON_UNESCAPED_UNICODE).")";
             }else{
		echo json_encode($app->getReturnedValue(), JSON_UNESCAPED_UNICODE);
             }
	}
}
