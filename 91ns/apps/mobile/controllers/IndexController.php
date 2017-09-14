<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class IndexController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '首页';
            $this->view->ns_active = 'index';
        }
        parent::initialize();
    }

    public function indexAction()
    {
        /*$value = $this->session->get('isShowDownload');
        if ($value!=1) {
            $this->session->set('isShowDownload', 1);
            $this->forward('index/initial');
        }*/
    }
    public function initialAction()
    {
    }
}