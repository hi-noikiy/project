<?php
namespace Micro\Controllers;
class IndexController extends ControllerBase
{
    public function initialize()
    {
        //$this->tag->setTitle('Admin');
        parent::initialize();
    }

    public function indexAction()
    {
        //$this->flash->notice('It is a test flash notice');

        /*$this->dispatcher->forward(array(
            "controller" => "post",
            "action" => "index"
        ));*/

        return $this->forward("configmgr/index");
    }
}
