<?php

namespace Micro\Router;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Http\Response as Response;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Adapter\Memory as AclList;

class SecurityAuthPlugin extends Plugin
{
    public function getAcl()
    {
        $cacheKey = 'acl_cache_data_key';
        $acl = $this->aclsCache->get($cacheKey);
        if($acl == null){
            $acl = new AclList();
            $acl->setDefaultAction(Acl::DENY);

            foreach ($this->config->acl as $rolename => $resouces) {
                $role = new Role($rolename);
                $acl->addRole($role);

                foreach ($resouces as $controller => $actions){
                    $resource = 'Micro\\Controllers\\'.$controller;
                    $methods = array();

                    foreach ($actions as $action){
                        array_push($methods, $action);
                    }

                    $acl->addResource(new Resource($resource), $methods);       
                    $acl->allow($rolename, $resource, $methods);                
                }                               
            }
            $this->aclsCache->save($cacheKey, $acl);
        }
        return $acl;
    }

    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        return true;
        $di = FactoryDefault::getDefault();
        $status = $di->get("status");
        $config = $di->get('config');

        $auth = $this->session->get($config->websiteinfo->authkey);
        if (!$auth || !isset($auth['uid'])){
            $role = 'guest';
        } else {
            $role = 'user';
        }

        $activeHandler = $app->getActiveHandler();
        $controller = get_class($activeHandler[0]);
        $action = $activeHandler[1];

//        echo $role.':'.$controller.':'.$action;die;

        $acl = $this->getAcl();
        $allowed = $acl->isAllowed($role, $controller, $action);
        if ($allowed != Acl::ALLOW) {
            $response = new Response();
            $response->setStatusCode(401, 'Unauthorized');
            
            $ret = json_encode($status->generate($status->getStatus('UNAUTHORIZED'), $status->getCode('AUTH_ERROR')));
            $response->setContent($ret);

            $response->send();
            return false;
        }
            
        return true;
    }
}

