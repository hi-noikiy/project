<?php

namespace Micro\Router;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;

class NotFoundPlugin extends Plugin
{
    public function beforeNotFound(Event $event, Micro $app)
    {
        $di = FactoryDefault::getDefault();
        $status = $di->get("status");

        echo json_encode($status->mobileReturn($status->getCode('URI_ERROR')), JSON_UNESCAPED_UNICODE);
        die;
    }
}
