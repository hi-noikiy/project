<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class FamilyController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '家族';
            $this->view->ns_name = 'family';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $this->view->ns_type = 'family';
    }

    public function incomeFamilyAction()
    {
        $this->view->ns_type = 'family';
    }

    public function myincomeAction()
    {
        $this->view->ns_type = 'family';
    }

}