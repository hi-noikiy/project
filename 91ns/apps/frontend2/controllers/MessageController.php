<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class MessageController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '消息';
            $this->view->ns_active = 'message';
        }
        parent::initialize();
    }

    public function indexAction() {
    }
}
