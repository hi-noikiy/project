<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class AboutController extends ControllerBase {
    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '关于我们';
            $this->view->ns_name = 'about';
        }
        parent::initialize();
    }

    public function indexAction() {
    }
}
