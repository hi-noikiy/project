<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class ConfigController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
    }

    //获取URL
    public function urlAction()
    {
        if ($this->request->isPost()) {
            $result['faceConfig'] = '/web/swf/room/res/face.json';
            $result['faceURL'] = '/web/swf/room/face.swf';
            $result['forbiddenword'] = '/web/swf/room/res/forbiddenword.txt';
            $this->status->ajaxReturn($this->status->getCode('OK'), $result);
        }
        $this->proxyError();
    }

    public function getGiftListAction() {
        $result = $this->configMgr->getAllGiftConfigList();
        $this->status->ajaxReturn($result['code'], $result['data']);
    }
}