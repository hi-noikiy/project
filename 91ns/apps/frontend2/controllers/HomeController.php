<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;

class HomeController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = 'Home';
            $this->view->ns_name = '空间';

            $uid = $this->request->get('uid');

            $user = UserFactory::getInstance(intval($uid));
            if(!$user->getUid()){
                return $this->pageError();
            }

            // 基础信息
            $result = $this->userMgr->getAnchorInfo($uid);
            if ($result['code'] == $this->status->getCode('OK')) {
                $showData = $result['data'];
            }else{
                $showData = array();
            }

            // 徽章
            $item = $user->getUserItemsObject();
            $result = $item->getUserBadge();
            if ($result['code'] == $this->status->getCode('OK')) {
                $showData['badge'] = $result['data'];
            }else{
                $showData['badge'] = array();
            }

            // 等级信息
            $result = $this->userMgr->getUserLevelInfo($uid);
            if($result['code'] == $this->status->getCode('OK')){
                $showData['levelInfo'] = $result['data'];
            }

            $this->view->anchorInfo = $showData;
        }

        parent::initialize();
    }

    /*首页*/
    public function indexAction(){
        $uid = $this->request->get('uid');
        $this->redirect('home/detail?uid='.$uid);
    }

    /*个人档案*/
    public function detailAction(){
        if (!$this->request->isGet()) {
            return $this->pageError();
        }
        $this->view->ns_type = 'detail';
    }

    /*相册*/
    public function galleryAction(){
        if (!$this->request->isGet()) {
            return $this->pageError();
        }
        $this->view->ns_type = 'gallery';

        $uid =  intval($this->request->get('uid'));

        $result = $this->userMgr->getMyGalleryNum($uid);
        $this->view->galleryNums = array('myGalleryNum'=>0,'myDynamicNum'=>0);
        if($result['code'] == $this->status->getCode('OK')){
            $this->view->galleryNums = $result['data'];
        }

        //swf版本
        $content = $this->config->url->swfUrl;
        $this->view->roomFileName = $content ? $content : 'room';
    }

    /*动态*/
    public function dynamicAction(){
        if (!$this->request->isGet()) {
            return $this->pageError();
        }
        $this->view->ns_type = 'dynamic';
    }

    /*留言板*/
    public function messageAction(){
        if (!$this->request->isGet()) {
            return $this->pageError();
        }
        $this->view->ns_type = 'message';
    }

    /*获取个人相册*/
    public function getMyGalleryAction(){
        if ($this->request->isPost()) {

            $uid = $this->request->getPost('uid');
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');

            $result = $this->userMgr->getMyGallery($uid, $type, $page, $pageSize);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //删除相册照片
    public function delGalleryImgAction() {
        if ($this->request->isPost()) {
            $ids = $this->request->getPost('ids');
            $result = $this->userMgr->delMyGalleryImages($ids);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //排序相册照片
    public function sortGalleryImgAction() {
        if ($this->request->isPost()) {
            $ids = $this->request->getPost('ids');
            $result = $this->userMgr->sortMyGalleryImages($ids);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

}