<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ConfigmgrController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
        }
        parent::initialize();
    }

    public function indexAction()
    {
        if ($this->request->isPost()) {

        }
        $this->proxyError();
    }

    public function getGiftListAction() {
        $result = $this->configMgr->getAllGiftConfigList();
        $this->status->ajaxReturn($result['code'], $result['data']);
    }
}