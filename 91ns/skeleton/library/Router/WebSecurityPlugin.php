<?php

namespace Micro\Router;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

use Phalcon\DI\FactoryDefault;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class WebSecurityPlugin extends Plugin
{

	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{

		//throw new \Exception("something");

		if (!isset($this->persistent->acl)) {

			$acl = new AclList();

			$acl->setDefaultAction(Acl::DENY);

			//Register roles
			$roles = array(
				'users'  => new Role('Users'),
				'guests' => new Role('Guests')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			//Private area resources
			$privateResources = array(
				'companies'    => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'products'     => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'producttypes' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'invoices'     => array('index', 'profile')
			);
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Public area resources
			$publicResources = array(
				'index'      => array('index'),
				'about'      => array('index'),
				'register'   => array('index'),
				'errors'     => array('show404', 'show500'),
				'session'    => array('index', 'register', 'start', 'end'),
				'contact'    => array('index', 'send')
			);
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Grant access to public areas to both users and guests
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					foreach ($actions as $action){
						$acl->allow($role->getName(), $resource, $action);
					}
				}
			}

			//Grant acess to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Users', $resource, $action);
				}
			}

			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{
		/*$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		if ($controller != 'wap') {
			$di = FactoryDefault::getDefault();
			$baseCode = $di->get('baseCode');
			$isMobile = $baseCode->isMobile();
			if ($isMobile) {
				$dispatcher->forward(array(
					'controller' => 'wap',
					'action'     => 'index'
				));
				return false;
			}
		}*/
		$serverName = $_SERVER['SERVER_NAME'];
		if(stripos($serverName, '91ns.cn') !== false ){//|| stripos($serverName, 'putianmm.com') !== false
            return $this->response->redirect('http://www.91ns.com'.$_SERVER['REQUEST_URI']);
			return false;
        }

        $di = FactoryDefault::getDefault();
		$baseCode = $di->get('baseCode');
		$isMobile = $baseCode->isMobile();

		if ($isMobile) {
			//$serverName = $_SERVER['SERVER_NAME'];
			
			//if(stripos($serverName, '127.0.0.1') !== false ){//|| stripos($serverName, 'putianmm.com') !== false
			if(stripos($serverName, 'www.91ns') !== false || stripos($serverName, 'goto.91ns') !== false){//|| stripos($serverName, 'putianmm.com') !== false
	            $this->response->redirect('http://m.91ns.com'.$_SERVER['REQUEST_URI']);
				return false;
	        }
		}else{
			if(stripos($serverName, 'goto.91ns') !== false){
				$this->response->redirect('http://www.91ns.com'.$_SERVER['REQUEST_URI']);
				return false;
			}
		}

		/*$auth = $this->session->get('auth');
		if (!$auth){
			$role = 'Guests';
		} else {
			$role = 'Users';
		}

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $controller, $action);
		if ($allowed != Acl::ALLOW) {
			$dispatcher->forward(array(
				'controller' => 'errors',
				'action'     => 'show401'
			));
			return false;
		}*/
	}
}
