<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserPropController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'userprop';
        }
    }

    public function indexAction()
    {
        
        //跳转到新版我的道具页面
        return $this->redirect('personal/props');
        
        
//        //获取类型
//        $pageType = $this->request->get('action');
//
//        if(!$pageType){
//            $pageType = 'all';//设置默认类型
//        }
//
//        $user = $this->userAuth->getUser();
//        if(!$user){
//            return $this->pageError();
//        }
//        $item = $user->getUserItemsObject();
//
//        $typeArry = array(
//            'all' => 0,
//            'normal' => 1,
//            'car' => 2,
//            'guard' => 3,
//            'badge' => 4,
//        );
//        $result = $item->getItemList(0);//$typeArry[$pageType]
//
//        if ($result['code'] == $this->status->getCode('OK')) {
//            $this->view->allData = $result['data'];
//        }else{
//            $this->view->allData = array();
//        }
//
//        $this->view->severTime = time();
//        $this->view->ns_sonType = $pageType;

    }

    //座驾开关
    public function updateCarStatusAction(){
        if ($this->request->isPost()) {
            $status = $this->request->getPost('status');
            $itemId = $this->request->getPost('carId');
            $result = $this->userMgr->updateCarStatus($itemId, $status);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

     
}