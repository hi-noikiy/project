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
        // $ratioData = \Micro\Models\BaseConfigs::findfirst("key='".$this->config->dbBaseConfigName->ratioKey."'");

        $this->view->ratioNum = ($ratioNum === false) ? $this->config->websiteinfo->ratioconfig['default'] : $ratioNum;
    }

    public function anchorinfoAction(){
    	$uid = $this->request->get('uid');

        //账号详情 根据UID获取信息
        $result= $this->invMgr->getUidUserInfo($uid)[0];

        $this->view->userInfo = $result;
    }
    public function anchorchatAction(){
        $uid = $this->request->get('uid');

        //账号详情 根据UID获取信息
        $result= $this->invMgr->getUidUserInfo($uid)[0];

        $this->view->userInfo = $result;
    }
     public function navyAction(){
        
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


    
}