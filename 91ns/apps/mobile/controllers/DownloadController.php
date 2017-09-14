<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class DownloadController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
        }
        parent::initialize();
    }

    public function indexAction()
    {
//        $downloads = $this->config->downloadConfig->downloadUrl;
//        $downloads = $downloads->toArray();
        $downloads = $this->configMgr->getAppdownloadConfig(0);
        $type = $this->request->get('mobileType');
        $appCount = new \Micro\Models\AppCount();
        $appCount->save(array(
        		"time" => time(),
        		"version" => $downloads["version_$type"],
        		'device'=>$type,
        		'ip'=>getenv('REMOTE_ADDR'),
        ));
        return $this->redirect($downloads[$type]);
//        return $this->redirect('http://192.168.1.19:5003/web/NSLive.apk');
    }

    public function appAction()
    {
        $this->view->appVersion = $this->configMgr->getAppdownloadConfig(3);
    }
    public function ios9tipAction()
    {

    }

    public function getDownloadConfigAction(){
        $downloads = $this->configMgr->getAppdownloadConfig(3);
        return $this->status->ajaxReturn($this->status->getCode('OK'), $downloads);
    }

}