<?php

namespace Micro\Router;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

use Micro\Controllers;
//use Phalcon\Mvc\Controller;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class AdminWebSecurityPlugin extends Plugin
{
    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     */
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        if (($controller == 'login') && ($action == 'login'))   // 登录接口
            return;
        $auth = $this->session->get($this->config->backend->authkey);
        if (!$auth){
            //var_dump($dispatcher);die;
            // $dispatcher->forward(array(
            //     'controller' => 'index',
            //     'action'     => 'index'
            // ));
            //return false;

            $dispatcher->setControllerName('login');
            $dispatcher->setActionName('index');


            
        }
    }
}
