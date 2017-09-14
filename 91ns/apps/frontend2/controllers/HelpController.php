<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class HelpController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '帮助';
            $this->view->ns_name = 'help';
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $this->view->ns_static =true;
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;
         $this->view->ns_type = 'help';
    }
    public function helpanchorAction()
    {
         $this->view->ns_type = 'helpanchor';
        
    }
    public function helpricherAction()
    {
         $this->view->ns_type = 'helpricher';
        
    }
    public function helpsendgiftAction()
    {
         $this->view->ns_type = 'helpsendgift';
        
    }
    public function helpchargeAction()
    {
         $this->view->ns_type = 'helpcharge';
        
    }
    public function helpbindAction()
    {
         $this->view->ns_type = 'helpbind';
        
    }
    public function helpricherprivilegeAction()
    {
         $this->view->ns_type = 'helpricherprivilege';
        
    }
    public function helpdemandAction()
    {
         $this->view->ns_type = 'helpdemand';
        
    }
    
}