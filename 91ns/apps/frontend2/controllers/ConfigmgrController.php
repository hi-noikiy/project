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
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            !$uid && $uid = 0;
            $result = $this->configMgr->getAllGiftConfigList($uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
}