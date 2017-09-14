<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\Configs;

class NoticeController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '公告';
            $this->view->ns_name = 'notice';
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $id =  $this->request->get('id');
        if(empty($id)){
            $this->view->listType = TRUE;
            $pageIndex = $this->request->get('p');
            if(empty($pageIndex)){
                $pageIndex = 0;
            }else{
                --$pageIndex;
            }
            $numPerPage = 15;

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $this->view->noticeList = $this->configMgr->getNoticeConfigList($skip, $limit);
            $this->view->thisPage = $pageIndex + 1;
            $this->view->pageTotal = ceil($this->view->noticeList['data']['count']/$numPerPage);
        }else{
            $this->view->listType = FALSE;
            $this->view->noticeContent = $this->configMgr->getNoticeConfigInfo($id);
        }

    }
    public function getNoticeAction() {
        if ($this->request->isPost()) {
            $pageIndex =  $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getNoticeConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function getNoticeByIdAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');

            $result = $this->configMgr->getNoticeConfigInfo($id);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }


}