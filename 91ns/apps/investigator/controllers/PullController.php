<?php
namespace Micro\Controllers;
class PullController  extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction(){
        //获取比例
        $ratioNum = $this->invMgr->getRatioNum($this->config->websiteinfo->ratioconfig['key']);
        $limitNum = $this->invMgr->getDayMaxLimitNum();
        // $ratioData = \Micro\Models\BaseConfigs::findfirst("key='".$this->config->dbBaseConfigName->ratioKey."'");

        $this->view->ratioNum = ($ratioNum === false) ? $this->config->websiteinfo->ratioconfig['default'] : $ratioNum;
        $this->view->limitNum = $limitNum;
    }

    public function anchorinfoAction(){
    	$uid = $this->request->get('uid');

        //账号详情 根据UID获取信息
        $result= $this->invMgr->getUidUserInfo($uid)[0];

        $this->view->userInfo = $result;

        $this->view->ns_type = 'anchorinfo';
    }

    public function anchorinfoNewAction(){
        $uid = $this->request->get('uid');

        //账号详情 根据UID获取信息
        $result= $this->invMgr->getUidUserInfo($uid)[0];

        $this->view->userInfo = $result;

        $this->view->ns_type = 'anchorinfoNew';
    }
    public function anchorchatAction(){
        $uid = $this->request->get('uid');

        //账号详情 根据UID获取信息
        $result= $this->invMgr->getUidUserInfo($uid)[0];

        $this->view->userInfo = $result;
    }
     public function navyAction(){
        
    }
    /**
     * 推广用户
     */
    public function recDetailListAction(){
    
    }
    public function accountinfoAction(){
		
		$uid = $this->request->get('uid');
        $result = $this->recordMgr->getChatObject()->getChatUserInfo($uid)[0];       
		$this->view->userInfo = $result;
    }

    public function accountStatisticsAction(){
        
        $uid = $this->request->get('uid');
        $result = $this->recordMgr->getChatObject()->getChatUserInfo($uid)[0];       
        $this->view->userInfo = $result;
    }

    public function reclistAction(){
        
        // $uid = $this->request->get('uid');
        // $result = $this->recordMgr->getChatObject()->getChatUserInfo($uid)[0];       
        // $this->view->userInfo = $result;
        // $this->view->recInfo = $this->invMgr->getRecDetailByUid($uid);
    }

    public function detailsAction(){
        
        $uid = $this->request->get('uid');
        $id = $this->request->get('id');

        $result = $this->recordMgr->getChatObject()->getChatUserInfo($uid)[0];
        $result['id'] = $id;
        $this->view->userInfo = $result;

        $this->view->recInfo = $this->invMgr->getRecDetailByUid($id);
    }
    public function payoffAction(){

        $uid = $this->request->get('uid');
        $id = $this->request->get('id');

        $result = $this->recordMgr->getChatObject()->getChatUserInfo($uid)[0];
        $result['id'] = $id;
        $this->view->userInfo = $result;

        $this->view->recInfo = $this->invMgr->getRecDetailByUid($id);
    }
    public function analysisAction(){

        $uid = $this->request->get('uid');
        $id = $this->request->get('id');

        $result = $this->recordMgr->getChatObject()->getChatUserInfo($uid)[0];
        $result['id'] = $id;
        $this->view->userInfo = $result;

        $this->view->recInfo = $this->invMgr->getRecDetailByUid($id);
    }

    public function tuoAction(){
        $limitNum = $this->invMgr->getDayMaxLimitNum(1);
        $this->view->limitNum = $limitNum;
    }

    public function supportinfoAction(){
        $uid = $this->request->get('uid');

        //账号详情 根据UID获取信息
        $result= $this->invMgr->getUidUserInfo($uid)[0];

        $this->view->userInfo = $result;

        $this->view->ns_type = 'supportinfo';
    }

    public function supportinfoNewAction(){
        $uid = $this->request->get('uid');

        //账号详情 根据UID获取信息
        $result= $this->invMgr->getUidUserInfo($uid)[0];

        $this->view->userInfo = $result;

        $this->view->ns_type = 'supportinfoNew';
    }



    
}