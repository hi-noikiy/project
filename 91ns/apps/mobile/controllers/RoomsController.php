<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class RoomsController extends ControllerBase {

    public function initialize() {
        if (!$this->request->isAjax()) {
            $this->view->ns_title = '房间';
            $this->view->ns_active = 'rooms';
            //$this->view->setTemplateAfter('main');
        }
        //$this->view->setTemplateAfter('main');  //use views/layouts/main.volt
        parent::initialize();
    }

    public function indexAction($uid) {
        $this->view->ROOMJSV = 1;

        //$this->view->publicUrl = '';

        $this->view->ns_room = TRUE;
        $user = $this->userAuth->getUser();
        if ($user != null) {
            $userInfo['uid'] = $user->getUid();
            $userInfo['isAnchor'] = $userInfo['uid'] == $uid;
            $this->view->userInfo = $userInfo;
        }
        //分享回访操作
        $fromuid = $this->request->get('fromuid');
        $fromuid && $this->taskMgr->shareBack($fromuid);

        $this->view->loggerLevel = $this->config->loggerLevel;
        $anchorInfo['uid'] = $uid;
        $this->view->anchorInfo = $anchorInfo;
        //$this->flash->notice('It is a test flash notice');
    }

    // 只有分类，一次性获取
    public function getRoomListAction() {
        if ($this->request->isPost()) {
            $sortType = $this->request->getPost('order');
            $uid = $this->request->getPost('uid');
            $skip = $this->request->getPost('skip');
            $limit = $this->request->getPost('limit');
            $result = $this->roomModule->getRoomMgrObject()->getRoomList($sortType, $uid, $skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function enterAction($uid) {
        $result = $this->roomModule->getRoomOperObject()->enterRoom($uid);
        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    /* public function enterAction($roomId)
      {
      $resultData = $this->roommgr->getRoomData($roomId);
      if ($resultData['code'] == $this->status->getCode('OK')) {
      $roomdata = $resultData['data'];

      $this->view->roominfo = $roomdata;
      //$this->view->creatorinfo = $userinfo;
      if ($this->session->get($this->config->websiteinfo->authkey) != NULL)
      {
      $UserData = $this->session->get($this->config->websiteinfo->authkey);
      $this->view->userid = $UserData['uid'];
      }
      else {
      $this->view->userid = time();   //游客
      }

      //下发下载资源的路径
      $host = "http://".$_SERVER['HTTP_HOST'];
      $this->view->pubfile = $this->url->getStatic('web/down/liveCaptain.exe');   //需要修改
      $this->view->videopublish = $this->config->websiteinfo->pushmediastream_url;
      $this->view->videoplay = $this->config->websiteinfo->pullmediastream_url;
      $controllerName = "/Rooms";  //控制器名称，先写死，不知道怎么在当前类获取控制器的名称字符串
      $this->view->phpStartURL = $host.$controllerName.'/startPublishFromC';
      $this->view->phpStopURL = $host.$controllerName.'/stopPublishFromC';
      $this->view->phpUpdateURL = $host.$controllerName.'/updatePublishInfoFromC';
      $this->view->serverTime = time();
      $this->view->validateURL = $host.$controllerName.'/validatePublish';
      $this->view->validateTag = "ooxx";
      }
      else {
      //..错误如何处理..可以考虑跳到404错误页面，或者说跳到房间不存在的页面之类的
      return $this->forward("errors/404");
      }
      } */

    // 登录NodeJS
    public function loginToNodeJSAction() {
        if ($this->request->isPost()) {
            $roomid = $this->request->getPost('roomId');
            $result = $this->userAuth->loginToNodeJS($roomid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 进入NodeJS房间
    public function enterNodeJSRoomAction() {
        if ($this->request->isPost()) {
            $roomid = $this->request->getPost('roomId');
            $token = $this->request->getPost('token');

            $result = $this->roomModule->getRoomOperObject()->enterNodeJSRoom($roomid, $token);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 开始直播之前，修改房间标题
    public function setRoomTitleAction() {
        if ($this->request->isPost()) {
            $title = $this->request->getPost('title');
            $announcement = $this->request->getPost('announcement');
            $result = $this->roomModule->getRoomMgrObject()->setRoomTitle($title, $announcement);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 上传海报
    public function uploadPosterAction() {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    $result = $this->roomModule->getRoomMgrObject()->uploadPoster($file);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                }
            }
            return $this->status->ajaxReturn($this->status->getCode('UPLOADFILE_ERROR'));
        }
        $this->proxyError();
    }

    // 获取房间座驾列表
    public function getCarsInRoomAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');

            $result = $this->roomModule->getRoomMgrObject()->getCarsInRoom($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 获取房间守护列表
    public function getGuardDataListAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');

            $result = $this->roomModule->getRoomMgrObject()->getGuardDataList($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 获取抢座列表
    public function getGrabSeatListAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');

            $result = $this->roomModule->getRoomMgrObject()->getGrabSeatList($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 获取房间总人数接口
    public function getTotalCountAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');

            $result = $this->roomModule->getRoomMgrObject()->getCountInRoom($roomId);
            //session充值
            $this->baseCode->updateSession();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 修改用户权限
    public function levelUpPermissionAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $uid = $this->request->getPost('uid');
            $level = $this->request->getPost('level');
            $token = $this->request->getPost('token');

            $result = $this->roomModule->getRoomOperObject()->levelUpPermission($roomId, $uid, $level, $token);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //  禁言
    public function forbidTalkAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $uid = $this->request->getPost('uid');
            $isForbid = $this->request->getPost('isForbid');
            $token = $this->request->getPost('token');

            $result = $this->roomModule->getRoomOperObject()->forbidTalk($roomId, $uid, $isForbid, $token);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //  踢人
    public function kickUserAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $uid = $this->request->getPost('uid');
            $token = $this->request->getPost('token');

            $result = $this->roomModule->getRoomOperObject()->kickUser($roomId, $uid, $token);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 获取房间消费排行榜
    public function getRoomConsumeRankAction() {
        if ($this->request->isPost()) {
            $rankType = $this->request->getPost('rankType');
            $roomId = $this->request->getPost('roomId');
            $topNum = $this->request->getPost('topNum');
            $result = $this->rankMgr->getRoomConsumeRank($rankType, $roomId, $topNum);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 主播客户端向服务器发起请求说明，当前房间开始直播
    public function startPublishAction() {
        if ($this->request->isPost()) {
            $roomid = $this->request->getPost('roomId');
            //$validateTag = $this->request->getPost('tag');

            $result = $this->roomModule->getRoomOperObject()->startPublish($roomid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 主播客户端向服务器发起请求说明，当前房间停止直播
    public function stopPublishAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            //$validateTag = $this->request->getPost('tag');

            $result = $this->roomModule->getRoomOperObject()->stopPublish($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updatePublishAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            //$validateTag = $this->request->getPost('tag');

            $result = $this->roomModule->getRoomOperObject()->updatePublish($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function validatePublishAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            //查询是否禁播
            $result = $this->roomModule->getRoomMgrObject()->checkLiveStatus($roomId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取猜猜看
    public function getGuessRoomAction() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $limit = $this->request->getPost('limit');
            $result = $this->roomModule->getRoomMgrObject()->getGuessHoster($uid, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //抢座
    public function grabSeatAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $seatPos = $this->request->getPost('seatPos');
            $seatCount = $this->request->getPost('seatCount');
            $result = $this->roomModule->getRoomOperObject()->grabSeat($roomId, $seatPos, $seatCount);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //发送房间广播(银喇叭)
    public function sendRoomBroadcastAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $content = $this->request->getPost('content');
            $isUseItem = $this->request->getPost('isUseItem'); //是否使用道具
            $result = $this->roomModule->getRoomOperObject()->sendRoomBroadcast($roomId, $content, $isUseItem);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //发送全服广播（金喇叭）
    public function sendAllRoomBroadcastAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $content = $this->request->getPost('content');
            $isUseItem = $this->request->getPost('isUseItem');//是否使用道具
            $result = $this->roomModule->getRoomOperObject()->sendAllRoomBroadcast($roomId, $content,$isUseItem);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //发送礼物
    public function sendGiftAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $uid = $this->request->getPost('uid');
            $giftId = $this->request->getPost('giftId');
            $giftCount = $this->request->getPost('giftCount');
            $anonymous = $this->request->getPost('anonymous');
            $result = $this->roomModule->getRoomOperObject()->sendGift($roomId, $uid, $giftId, $giftCount, $anonymous);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取在线的免费聊豆
    public function getOnlineCoinAction() {
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomMgrObject()->onlineActivities();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //直播间包裹
    public function getRoomBagAction() {
         if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomOperObject()->getRoomBag();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    
    //使用包裹里礼物
    public function sendBagGiftAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $uid = $this->request->getPost('uid');
            $id = $this->request->getPost('id');
            $giftCount = $this->request->getPost('giftCount');
            $anonymous = $this->request->getPost('anonymous');
            $type = $this->request->getPost('type');
            $result = $this->roomModule->getRoomOperObject()->sendBagGift($roomId, $uid, $id, $giftCount, $anonymous,$type);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    
    //直播间领取vip奖品
    public function getVipRewardAction() {
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomOperObject()->getVipReward();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    //获取用户签到内容
    public function getUserSignAction(){
          if ($this->request->isPost()) {
            $result = $this->signMgr->getUserSign();;
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();  
    }
    //用户签到
    public function setUserSignAction(){
        if ($this->request->isPost()) {
            $result = $this->signMgr->setSign();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    //获取用户签到奖励
    public function getUserSignRewardAction(){
        if ($this->request->isPost()) {
            $type=$this->request->getPost("type");
            $result = $this->signMgr->getSignReward($type);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    //获取用户签到状态
    public function getUserSignStatusAction(){
        if ($this->request->isPost()) {
            $result = $this->signMgr->getSignStatus();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    
    // 开始直播之后，修改房间标题/公告
    public function setRoomContentAction() {
        if ($this->request->isPost()) {
            $title = $this->request->getPost('title');
            $announcement = $this->request->getPost('announcement');
            $result = $this->roomModule->getRoomOperObject()->setRoomContent($title, $announcement);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    //直播间送礼物列表
    public function getSendGiftListAction() {
        if ($this->request->isPost()) {
            $roomId = $this->request->getPost('roomId');
            $num = $this->request->getPost('num');
            $result = $this->roomModule->getRoomOperObject()->getLastSendGiftList($roomId, $num);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //直播间送礼物列表
    /*public function hotRoomAction() {
        $result = $this->roomModule->getRoomMgrObject()->getHotRoom();
        if($result['code'] != $this->status->getCode('OK')){
            return $this->redirect('');
        }
        return $this->redirect(''.$result['data']['uid']);
    }*/
    public function test() {
        try {
            $sql = "SELECT uid FROM \Micro\Models\Rooms WHERE liveStatus=1 AND showStatus=1 ORDER BY onlineNum DESC LIMIT 0,1";
            $query = $this->modelsManager->createQuery($sql);
            $data = $query->execute();
            if (!$data->valid()) {
                return $this->status->retFromFramework($this->status->getCode("NO_PUBLISHED_ROOM"));
            }
            $result['uid'] = $data->toArray()[0]['uid'];
            return $this->status->retFromFramework($this->status->getCode("OK"), $result);
        } catch (Exception $e) {
            $this->errLog('getHotRoom errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $result);
        }
    }
    public function hotRoomAction() {
        /*$result = $this->test();
        if($result['code'] != $this->status->getCode('OK')){
            return $this->forward('');
        }
        return $this->forward('rooms/index/'.$result['data']['uid']);*/
         //$this->forward('shop');
        //$this->view->uid = $result['data']['uid'];
        //return $this->redirect(''.$result['data']['uid']);
        //return $this->forward('/'.$result['data']['uid']);
        $result = $this->normalLib->getHotRoom();

        $param = $this->request->get();
        $val = '?';

        foreach ($param as $key => $value) {
            if($key == '_url'){
                continue;
            }else{
                $val .= $key.'='.$value.'&';
            }
        }
        
        $val = substr($val, 0, -1);
        if($result){
            $this->redirect($result.$val);
        }else{
            $this->redirect(''.$val);
        }
    }
    
    //获取vip礼物领取状态
    public function getVipGiftStatusAction(){  
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomOperObject()->getVipGiftStatus();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
	
    //获取物品基本配置
    public function getItemConfigListAction() {
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomOperObject()->getItemsBaseConfiglist();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取用户喇叭拥有数量
    public function getUserHornAction() {
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $result = $this->roomModule->getRoomOperObject()->checkUserHorn($type);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

}
