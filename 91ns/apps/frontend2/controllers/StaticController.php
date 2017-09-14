<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class StaticController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '91ns';
            $this->view->ns_name = '91ns';
        }
        parent::initialize();
    }

    public function indexAction()
    {
    }

    public function douziloginAction()
    {
    }
}