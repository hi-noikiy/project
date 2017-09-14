<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class AgreementController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '协议';
            $this->view->ns_name = 'agreement';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction()
    {
        
    }
     public function createGroupAgreementAction()
    {
        
    }
    public function reggreementAction()
    {
        
    }
     public function ns_greementAction()
    {
        
    }

}