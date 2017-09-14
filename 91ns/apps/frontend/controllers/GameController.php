<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class GameController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '游戏';
            $this->view->ns_name = 'game';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $this->view->ns_type = 'game';
    }
}