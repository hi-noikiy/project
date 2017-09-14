<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ForgetpwdController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '忘记密码';
            $this->view->ns_active = 'forgetpwd';
        }
        parent::initialize();
    }

    public function indexAction()
    {        
    }
   
}