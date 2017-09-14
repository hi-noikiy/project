<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class LoginController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '用户';
            $this->view->ns_name = 'login';
        }
        parent::initialize();
    }

    public function indexAction()
    {

    }
    public function logintelAction()
    {

    }
}