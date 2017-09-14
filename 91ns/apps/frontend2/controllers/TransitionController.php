<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;

class TransitionController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '直播';
            $this->view->ns_name = 'transition';
        }
        parent::initialize();
    }

    public function indexAction()
    {
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;
        //是否已经有房间
        $uid = $this->userAuth->getSessionData()['uid'];
        if($this->roomModule->getRoomMgrObject()->checkRoomExist($uid)){
            $this->view->first = TRUE;
        }else{
            $this->view->first = FALSE;
        }
        //判断是否签约中
        $user = UserFactory::getInstance($uid);
        $this->view->isSignIng = $user->getUserInfoObject()->isSignAnchoringNew($uid);
    }
    public function applyGroupAction()
    {
        $result = $this->familyMgr->getFamilyInfoList();
        if($result['code'] == $this->status->getCode('OK')){
            $this->view->familyInfoList = $result['data'];
        }else{
            $this->view->familyInfoList = array();
        }
        $bankListTemp = $this->config->banckcode;
        $bankList =array();
        foreach($bankListTemp as $key => &$val){
            $bankList[] =array(
                'name' => $key,
                'val' => $val,
            );
        }
        $this->view->bankList = $bankList;
    }
    public function applysignAction()
    {
       //所在地
        $this->view->location = $this->config->location;
        //城市
        $this->view->provinceDefault = $this->config->provinceDefault;
        //默认省份
        $this->view->cityDefault = $this->config->cityDefault;
        //默认城市
        $this->view->city = $this->config->city;
        //星座
        $this->view->constellation = $this->config->constellation;
        // 昵称uid
        $uid = $this->userAuth->getSessionData()['uid'];
        $user = UserFactory::getInstance($uid);
        $nickName = $user->getUserInfoObject()->getUserInfo()['nickName'];
        $this->view->uid = $uid;
        $this->view->nickName = $nickName;

         //判断是否签约中
        $user = UserFactory::getInstance($uid);
        $this->view->isSignIng = $user->getUserInfoObject()->isSignAnchoringNew($uid);
    }
    public function createGroupAction()
    {
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;
    }
    public function PerfectInfoAction()
    {
        
    }
    public function SigningActivitiesAction()
    {
        
    }

}