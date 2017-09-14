<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class DownloadController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '下载';
            $this->view->ns_active = 'download';
        }
        parent::initialize();
    }

    public function indexAction()
    {
//        $downloads = $this->config->downloadConfig->downloadUrl;
//        $downloads = $downloads->toArray();
        $downloads = $this->configMgr->getAppdownloadConfig(0);
        $this->view->downloads = $downloads;
    }
    public function appAction()
    {
        $type = $this->request->get('mobileType');
        if ($type == 'android') {
            $url = $this->config->downloadConfig->downloadUrl[$type];
            return $this->redirect($url);
        }
    }

}