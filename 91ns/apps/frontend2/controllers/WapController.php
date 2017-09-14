<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;


class WapController extends ControllerBase{
    public function initialize(){
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '房间';
            $this->view->ns_name = 'room';
            $this->view->wap = true;
        }
        parent::initialize();
    }
    //首页
    public function indexAction(){
        $sortType = 2;//观众人数
        $roomList = $this->roomModule->getRoomMgrObject()->getRoomList($sortType,NULL,0,20);
        $this->pageCheckSuccess($roomList);
        $this->view->roomList = $roomList['data']['data'];
    }

    //房间
    public function roomAction($uid){
        $sortType = 2;//观众人数
        $roomList = $this->roomModule->getRoomMgrObject()->getRoomList($sortType,NULL,0,20);
        $this->pageCheckSuccess($roomList);
        $this->view->roomList = $roomList['data']['data'];

        //主播信息
        $user = UserFactory::getInstance($uid);
        $userInfo = $user->getUserInfoObject()->getData();
        $this->view->userInfo = $userInfo;
        $this->view->publicUrl = 'http://hls.putianmm.com/xhblive/livestream'.$uid.'_300/index.m3u8';
        $result = $this->roomModule->getRoomOperObject()->enterRoom($uid);

        if($this->status->getCode('OK') == $result['code']){
            if($result['data']['roomInfo']['publishRoute'] == 1){
                $this->view->publicUrl = 'http://mobile.91ns.com/xhblive/livestream'.$uid.'/index.m3u8';
            }
        }

        //获取人数
        $number = $this->roomModule->getRoomMgrObject()->getCountInRoom($uid);
        $this->pageCheckSuccess($number);
        $this->view->number = $number['data']['totalCount'];

        //分享回访操作
        $fromuid = $this->request->get('fromuid');
        $fromuid&&$this->taskMgr->shareBack($fromuid);
    }
    public function staticAction(){
    }
}
