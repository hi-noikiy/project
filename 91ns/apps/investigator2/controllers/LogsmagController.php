<?php
namespace Micro\Controllers;
class LogsmagController  extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
    *
    **/
    public function indexAction(){        
         $this->redirect('logsmag/operlogs');
    }

    public function operlogsAction(){

    }

    public function loginlogsAction(){

    }

    
}