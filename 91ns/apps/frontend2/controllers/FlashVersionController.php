<?php
namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class FlashVersionController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function addVersionAction(){
        if($this->request->isGet()){
            $filename = $this->request->get('filename');
            $res = $this->versionMgr->addFlashVersion($filename);
            return $this->status->ajaxReturn($res['code'], $res['data']);
        }

        return $this->proxyError();
    }

    public function updateVersionAction(){
        if($this->request->isGet()){
            $filename = $this->request->get('filename');
            $res = $this->versionMgr->updateFlashVersion($filename);
            return $this->status->ajaxReturn($res['code'], $res['data']);
        }

        return $this->proxyError();
    }

    public function getVersionAction(){
        if($this->request->isGet()){
//            $res = $this->versionMgr->getFlashVersion();
//            return $this->status->ajaxReturn($res['code'], $res['data']);
            $url = $this->config->urlConfig->flashFileName;
            $content = file_get_contents($url);
            return $this->status->ajaxReturn($this->status->getCode('OK'), $content ? $content : 'room');
        }

        return $this->proxyError();
    }

}