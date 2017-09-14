<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class DownloadController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '下载';
            $this->view->ns_name = 'download';
            $this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $downloads = $this->config->downloadUrl;
        $downloads = $downloads->toArray();
        $this->view->downloads = $downloads;
    }
    public function appAction()
    {
        $type = $this->request->get('mobileType');
        if ($type == 'android') {
            $url = $this->config->downloadUrl[$type];
            return $this->redirect($url);
        }
    }

}