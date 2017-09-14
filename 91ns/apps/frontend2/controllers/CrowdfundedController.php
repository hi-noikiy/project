<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class CrowdfundedController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '一元夺宝';
            $this->view->ns_active = 'crowdfunded';
        }
        parent::initialize();
    }

    public function indexAction() {
        $result = $this->configMgr->getBannerList(4, 0, 5);//4代表pc的一元夺宝banner
        if($result['code'] == $this->status->getCode('OK')){
            $this->view->banners = $result['data'];
        }else{
            $this->view->banners = array();
        }
    }
    public function goodsAction() {
        
    }
    public function detailsAction() {
        if (!$this->request->isAjax()) {
            $id = $this->request->get('id');
            $times = $this->request->get('ts');
            $isValid = $this->validator->validate(array('id'=>$id));
            if (!$isValid) {
                return $this->redirect("errors/show404");
            }

            $goodsData = \Micro\Models\GoodsConfigs::findFirst('isShow = 0 and id = ' . $id);
            if (empty($goodsData)) {
                return $this->redirect("errors/show404");
            }

            $result = $this->TreasureMgr->getGoodsInfo($id, $times);
            if($result['code'] == $this->status->getCode('OK')){
                $this->view->details = $result['data'];
            }else{
                $this->redirect("errors/show404");
            }
        }else{
            $this->redirect("errors/show404");
        }
    }
   
}
