<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class RegisterController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '用户';
            $this->view->ns_active = 'register';
        }
        parent::initialize();
    }

    public function indexAction()
    {

    }
}