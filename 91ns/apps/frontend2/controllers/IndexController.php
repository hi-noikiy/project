<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class IndexController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '首页';
            $this->view->ns_active = 'index';
        }
        parent::initialize();
    }

    public function indexAction()
    {
        header("Content-type: text/html; charset=utf-8"); 
        $result = $this->configMgr->getBannerList(0, 0, 100);
        if($result['code'] == $this->status->getCode('OK')){
            $this->view->bannerList = $result['data']['list'];
        }else{
            $this->view->bannerList = array();
        }

        $this->view->swfUrl = $this->config->url->swfUrl;

	//渠道来源
        $log = new \Micro\Frameworks\Logic\Base\BaseStatistics();
        $source = $log->getSource();
        if ($source != NULL) {
            $this->view->ns_source = $source;
        }
    }
}