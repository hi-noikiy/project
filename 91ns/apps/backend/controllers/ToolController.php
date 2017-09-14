<?php
namespace Micro\Controllers;
class ToolController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        return $this->forward("tool/baseconfig");
    }

    public function baseconfigAction()
    {
    }
}