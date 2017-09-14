<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class AboutController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '91ns';
            $this->view->ns_name = '91ns';
        }
        $this->view->ns_static = true;
        parent::initialize();
    }

    /*首页*/
    public function indexAction(){
        $this->view->ns_active = 'index';
    }
    /*关于我们*/
    public function aboutAction(){
        $this->view->ns_active = 'about';
    }
    /*高薪诚聘*/
    public function jobAction(){
        $this->view->ns_active = 'job';
    }
    /*联系我们*/
    public function contactAction(){
        $this->view->ns_active = 'contact';
    }
    /*商务合作*/
    public function partnerAction(){
        $this->view->ns_active = 'partner';
    }
    /*使用协议*/
    public function protocolAction(){
        $this->view->ns_active = 'protocol';
    }
    /*隐私原则*/
    public function principleAction(){
        $this->view->ns_active = 'principle';
    }
}