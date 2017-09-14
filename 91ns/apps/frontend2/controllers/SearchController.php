<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class SearchController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '搜索';
            $this->view->ns_active = 'search';
        }
        parent::initialize();
    }

    public function indexAction() {
    }
}
