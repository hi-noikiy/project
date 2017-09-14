<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;

class AjaxController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
        }
        parent::initialize();
    }

    public function indexAction()
    {
        if ($this->request->isPost()) {

        }
        $this->proxyError();
    }

    /*
     * 验证码生成（图片）
     * */
    public function getSecurityCodeAction()
    {
        if ($this->request->isPost()) {
            $captchaId = \Securimage::getCaptchaId();
            $result['captchaId'] = $captchaId;
            $this->status->ajaxReturn($this->status->getCode('OK'), $result);
        }
        $this->proxyError();
    }

    /*
     * 验证码显示（图片）
     * */
    public function getSecurityImageAction()
    {
        ob_clean();
        $captchaId = $this->request->get('id');
        $options = array(   'captchaId'  => $captchaId,
                            'no_session' => true,
                            'use_database' => true);
        $captcha = new \Securimage($options);

        $captcha->show();
    }

    /*
     * 验证码验证（图片）
     * */
    public function checkSecurityAction()
    {
        if ($this->request->isPost()) {
            $securityCode = $this->request->getPost('securityCode');
            $captchaId = $this->request->getPost('captchaId');
            if(!empty($securityCode)){
                if($this->baseCode->checkSecurityCode($captchaId, $securityCode)){
                    $this->status->ajaxReturn($this->status->getCode('OK'));
                }
                $this->status->ajaxReturn($this->status->getCode('SECURITY_CODE_ERROR'));
            }
        }
        $this->proxyError();
    }

    /*
     * 验证码第三方
     * */
    public function getSecurityScriptAction(){
        if ($this->request->isPost()) {
            //验证码类型使用geeTest
            if($this->config->captchaType == 'geeTest'){
                $revert['type'] = 'geeTest';
                $revert['url'] = $this->geetest->getGeetest();
            }else{
                $revert['type'] = 'securimage';
                $revert['url'] = \Securimage::getCaptchaId();
            }
            $this->status->ajaxReturn($this->status->getCode('OK'), $revert);
        }
        $this->proxyError();
    }

    /*
     * 密码找回发送验证码（邮件）
     * */
    public function sendEmailVerifyCodeAction(){
        if($this->request->isPost()){
            $di = FactoryDefault::getDefault();
            $session = $di->get('session');
            $config = $di->get('config');

            $userName = $session->get($config->websiteinfo->securityuser);
            $email = $this->userMgr->getEmailByUserName($userName);

            if($email){
                $result = $this->userMgr->sendEmailVerifyCode($email);
                if ($result['code'] == $this->status->getCode('OK')) {
                    $this->status->ajaxReturn($this->status->getCode('OK'));
                }

                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    /*
     * 验证验证码：（邮件）
     * */
    public function checkEmailVerifyCodeAction(){
        if($this->request->isPost()){
            $emailCode = $this->request->getPost('emailCode');

            if($this->userMgr->checkEmailVerifyCode($emailCode)){
                $di = FactoryDefault::getDefault();
                $session = $di->get('session');
                $config = $di->get('config');
                $session->set($config->websiteinfo->securityuserverified, '1');
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($this->status->getCode('SECURITY_CODE_ERROR'));
        }

        return $this->proxyError();
    }
    /*
     * 上传头像FLASH
     * */
    public function  getFlashHtmlAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if (!$user) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid = $user->getUid();

            //$avatarFlash = '/web/swf/room/uploadhead.swf?type=0&avatarUrl=' . '/user/updateavatar?action=uploadAvatar&uid=' . $uid . '&hash=' . md5($_SERVER['HTTP_USER_AGENT']);
            $avatarFlash = $this->url->getStatic('web/swf/'.$this->config->urlConfig->flashFileName.'/uploadhead.swf').'?type=0&avatarUrl=' . '/user/updateavatar?action=uploadAvatar&uid=' . $uid . '&hash=' . md5($_SERVER['HTTP_USER_AGENT']);

            $html =  '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="100%" height="100%" style="position:relative;" id="mycamera" align="middle" wmode="opaque">';
            $html .= '<param name="allowScriptAccess" value="always" />';
            $html .= '<param name="scale" value="exactfit" />';
            $html .= '<param name="wmode" value="opaque" />';
            $html .= '<param name="quality" value="high" />';
            $html .= '<param name="bgcolor" value="#ffffff" />';
            $html .= '<param name="movie" value="'.$avatarFlash.'" />';
            $html .= '<param name="menu" value="false" />';
            $html .= '<embed src="'.$avatarFlash.'" quality="high" bgcolor="#ffffff" width="100%" height="100%" name="mycamera" align="middle" allowScriptAccess="always" allowFullScreen="false" scale="exactfit"  wmode="opaque" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" style="position:relative;"/>';
            $html .= '</object>';
            $this->status->ajaxReturn($this->status->getCode('OK'), $html);
        }
        $this->proxyError();
    }

        /*
     * 上传家族头像FLASH
     * */
    public function  getFlashHtmlFamilyAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if (!$user) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid = $user->getUid();

            $familyId = $this->request->getPost('familyId');

            $swfUrl = $this->config->url->swfUrl;

            //$avatarFlash = '/web/swf/room/uploadhead.swf?type=0&avatarUrl=' . '/user/updateavatar?action=uploadAvatar&uid=' . $uid . '&hash=' . md5($_SERVER['HTTP_USER_AGENT']);
            $avatarFlash = $this->url->getStatic($swfUrl.'/modifyFamilyIcon.swf').'?fid=' . $familyId . '&hash=' . md5($_SERVER['HTTP_USER_AGENT']);

            $html =  '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="100%" height="100%" style="position:relative;" id="mycamera" align="middle" wmode="opaque">';
            $html .= '<param name="allowScriptAccess" value="always" />';
            $html .= '<param name="scale" value="exactfit" />';
            $html .= '<param name="wmode" value="opaque" />';
            $html .= '<param name="quality" value="high" />';
            $html .= '<param name="bgcolor" value="#ffffff" />';
            $html .= '<param name="movie" value="'.$avatarFlash.'" />';
            $html .= '<param name="menu" value="false" />';
            $html .= '<embed src="'.$avatarFlash.'" quality="high" bgcolor="#ffffff" width="100%" height="98%" name="mycamera" align="middle" allowScriptAccess="always" allowFullScreen="false" scale="exactfit"  wmode="opaque" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" style="position:relative;"/>';
            $html .= '</object>';
            $this->status->ajaxReturn($this->status->getCode('OK'), $html);
        }
        $this->proxyError();
    }

    /**
     * 获取系统时间
     */
    public function getServerTimeAction() {
        $result['time'] = $this->baseCode->getServerTime();
        $this->status->ajaxReturn($this->status->getCode('OK'), $result);
    }

    /*
     * 分享
     * */
     public function shareAction($type, $anchorId) {
         if ($this->request->isPost()) {
            $result = $this->userMgr->shareActivity($type, $anchorId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取默认头像
	public function getCustomDefaultAvatarAction(){
        //if ($this->request->isPost()) {
            $result = $this->userMgr->getCustomDefaultAvatar();
			if($result['code'] == $this->status->getCode('OK')){
                $this->status->ajaxReturn($result['code'], $result);
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        //}
        //$this->proxyError();
	}

	//设置默认图片
	public function setCustomDefaultAvatarAction(){
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $result = $this->userMgr->setCustomDefaultAvatar($id);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
		
	}

    public function uploadLogAction() {
        if ($this->request->isPost()) {
            $data = $this->request->getPost('data');

            $directory = $this->config->directory->logsDir;
            $logger = new \Phalcon\Logger\Adapter\File($directory.'/client.log');

            $logger->error($data);
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        else {
            $this->proxyError();
        }
    }

    public function uploadPublishLogAction() {
        if ($this->request->isPost()) {
            $data = $this->request->getPost('data');

            $directory = $this->config->directory->logsDir;
            $logger = new \Phalcon\Logger\Adapter\File($directory.'/publish.log');

            $logger->error($data);
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        else {
            $this->proxyError();
        }
    }

    // 提交登录用户的聊天信息
    public function uploadChatDataAction() {
        if ($this->request->isPost()) {
            $chatData = $this->request->getPost("chatData");
            $roomId = $this->request->getPost("roomId");
            $result = $this->recordMgr->getChatObject()->addChat($roomId, $chatData);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        else {
            $this->proxyError();
        }
    }

    //聊天服务器回调接口(web网站要有，GM后台也有(测试方便))
    public function chatServerCallbackAction() {
        $rawBody = $this->request->getRawBody();
        $jsonData = json_decode($rawBody, true);
        $dataArray = $jsonData['data'];

        //设定返回值,返回格式为json格式,返回数据会在nodejs中写入日志
        $result['code'] = 0;
        $result['data'] = array();

        if (!$dataArray) {
            $result['code'] = -1;
            $result['info'] = "no post rawbody : ".$rawBody;
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        try{
            foreach ($jsonData['data']as $dataInfo) {
                //$roomId = $dataInfo['roomId'];
                $roomData = $dataInfo['data'];
                $controlType = $roomData['controltype'];

                // 回调机器人自动增减
                if ($controlType == 'autoRobot') {
                    $roomId = $roomData['data']['roomId'];
                    if($this->config->robotVersion == '0.0.2'){
                        $totalCount = intval($roomData['data']['totalCount']);
                        $robotCount = intval($roomData['data']['robotCount']);
                        // 写数据库。。。
                        $resultData = $this->roomModule->getRoomMgrObject()->updateRobotCount($roomId, $totalCount, $robotCount);
                    }else{
                        $robotCount = intval($roomData['data']['robotCount']);
                        $changeRobotCount = intval($roomData['data']['changeRobotCount']);
                        // 写数据库。。。
                        $resultData = $this->roomModule->getRoomMgrObject()->updateRobotCount($roomId, $robotCount);
                    }

                    //保存数据准备返回
                    $callbackData['controltype'] = $controlType;
                    /*$callbackData['roomId'] = $roomId;
                    $callbackData['robotCount'] = $robotCount;
                    //$callbackData['result'] = $resultData;*/
                    array_push($result['data'], $callbackData);
                }
                else if ($controlType == 'userLeave') {     //用户退出nodejs房间
                    // 回调用户和NodeJS已经断开连接
                    $roomId = $roomData['data']['roomId'];
                    $uid = $roomData['data']['uid'];
                    $userType = $roomData['data']['userType'];
                    $deviceType = $roomData['data']['deviceType'];

                    //保存数据准备返回
                    $callbackData['controltype'] = $controlType;
                    $callbackData['roomId'] = $roomId;
                    $callbackData['uid'] = $uid;

                    try {
//                        // 用户人数减一
//                        $phql = "UPDATE \Micro\Models\Rooms SET onlineNum = onlineNum-1,totalNum = onlineNum + robotNum WHERE roomId = ?0 AND onlineNum>0";
//                        $valueArray = array(
//                            0 => $roomId
//                        );
//                        $this->modelsManager->executeQuery($phql, $valueArray);

                        $connection = $this->di->get('db');
                         // 用户人数减一
                        $sql0 = "update pre_rooms set onlineNum = onlineNum-1,totalNum = onlineNum + robotNum WHERE roomId =" . $roomId . " and onlineNum>0";
                        $connection->execute($sql0);

                        if ($userType == $this->config->roomUserType->hoster) {
                            $resultData = $this->roomModule->getRoomOperObject()->stopPublishFromNodeJs($roomId);
                            //$callbackData['hosterresult'] = $resultData;
                        }

                        //写入房间用户统计表，用于后台用户分析，add by 2015/10/12
                        if ($deviceType == 'ios') {
                            $platform = 2;
                        } elseif ($deviceType == 'android') {
                            $platform = 3;
                        } else {//pc端
                            $platform = 1;
                        }
                        //用户数减一
                        $sql = "update pre_room_user_count set count=count-1 where platform=" . $platform;
                        $connection->execute($sql);

                        //游客跳过以下处理
                        if($userType != 0){
                            //写入用户活跃统计表，用于后台用户分析，add by 2015/10/12
                            //更新用户离开时间，并统计在线时间
                            $now = time();
                            $sql1 = "update pre_user_active_count set endTime={$now},timeCount={$now}-createTime+timeCount where platform=" . $platform." and roomId=".$roomId." and uid=".$uid." order by id desc";
                            $connection->execute($sql1);
                        }

                    }
                    catch(\Exception $e) {
                        $errorData = $e->getMessage();
                        $directory = $this->config->directory->logsDir;
                        $logger = new \Phalcon\Logger\Adapter\File($directory.'/callback.log');
                        $logger->error($errorData);

                        $callbackData['errorinfo'] = "userLeave error : ".$errorData;
                    }
                    array_push($result['data'], $callbackData);
                }
                else if ($controlType == 'serverReady') {
                    //保存数据准备返回
                    $callbackData['controltype'] = $controlType;

                    try {
                        //设置所有房间人数为0
                        $phql = "UPDATE \Micro\Models\Rooms SET onlineNum=0, robotNum=0,totalNum=0";
                        $query = $this->modelsManager->createQuery($phql);
                        $query->execute();

                        //查询出所有的开播的记录，设置结束时间为当前时间
                        $phql = "SELECT roomId, publicTime FROM \Micro\Models\Rooms"
                                ." WHERE (liveStatus=".$this->config->roomLiveStatus->start." OR liveStatus=".$this->config->roomLiveStatus->pause.")"
                                ." AND publicTime>0";
                        $query = $this->modelsManager->createQuery($phql);
                        $rooms = $query->execute();
                        $currentTime = time();
                        if ($rooms->valid()) {
                            foreach ($rooms as $room) {
                                $phql = "UPDATE \Micro\Models\RoomLog SET endTime = ".$currentTime
                                ." WHERE roomId=".$room->roomId." AND publicTime=".$room->publicTime;
                                $query = $this->modelsManager->createQuery($phql);
                                $query->execute();
                            }
                        }

                        //查询出所有的liveStatus为开播或者是暂停状态的，设置为0
                        $phql = "UPDATE \Micro\Models\Rooms SET liveStatus = ".$this->config->roomLiveStatus->stop
                                ." WHERE liveStatus=".$this->config->roomLiveStatus->start." OR liveStatus=".$this->config->roomLiveStatus->pause;
                        $query = $this->modelsManager->createQuery($phql);
                        $query->execute();
                        
                        //写入房间用户统计表，用于后台用户分析，add by 2015/10/14
                        $connection = $this->di->get('db');
                        //用户数清空
                        $sql = "update pre_room_user_count set count=0";
                        $connection->execute($sql);

                        //写入用户活跃统计表，用于后台用户分析，add by 2015/10/14
                        //更新用户离开时间，并统计在线时间
                        $now = time();
                        $sql1 = "update pre_user_active_count set endTime={$now},timeCount={$now}-createTime+timeCount where endTime=0";
                        $connection->execute($sql1);
                        
                    }
                    catch(\Exception $e) {
                        $errorData = $e->getMessage();
                        $directory = $this->config->directory->logsDir;
                        $logger = new \Phalcon\Logger\Adapter\File($directory.'/callback.log');
                        $logger->error($errorData);

                        $callbackData['errorinfo'] = "serverReady error : ".$errorData;
                    }

                    array_push($result['data'], $callbackData);
                }
                else if ($controlType == 'connect') {           //与nodejs建立连接
                    $uid = $roomData['data']['uid'];

                    $callbackData['controltype'] = $controlType;
                    $callbackData['uid'] = $uid;
                    array_push($result['data'], $callbackData);
                    // {"data":{"controltype":"connect","data":{"uid":"94066c40-d836-11e4-bf82-b1b7d49c930a"}},"route":"connect"}
                }
                else if ($controlType == 'disconnect') {        //与nodejs断开连接
                    $uid = $roomData['data']['uid'];

                    $callbackData['controltype'] = $controlType;
                    $callbackData['uid'] = $uid;
                    array_push($result['data'], $callbackData);
                    // {"data":{"controltype":"disconnect","data":{"uid":1}},"route":"disconnect"}
                }
                else if ($controlType == 'enterRoom') {         //用户进入nodejs房间
                    $uid = $roomData['data']['uid'];
                    $roomId = $roomData['data']['roomId'];
                    $userType = $roomData['data']['userType'];
                    $deviceType = $roomData['data']['deviceType'];

                    $callbackData['controltype'] = $controlType;
                    $callbackData['uid'] = $uid;
                    $callbackData['roomId'] = $roomId;
                    $callbackData['userType'] = $userType;
                    $callbackData['deviceType'] = $deviceType;
                    array_push($result['data'], $callbackData);
                    // {"data":{"controltype":"enterRoom","data":{"uid":"94066c40-d836-11e4-bf82-b1b7d49c930a","level":"1","userdata":"In WebSite : It is a UserData To NodeJS"}},"route":"enterRoom"}
                    
                    try {
                        //写入房间用户统计表，用于后台用户分析，add by 2015/10/12
                        if ($deviceType == 'ios') {
                            $platform = 2;
                        } elseif ($deviceType == 'android') {
                            $platform = 3;
                        } else {//pc端
                            $platform = 1;
                        }
                        //存在则更新，不存在则添加
                        $sql = "insert into pre_room_user_count(platform,count) VALUES ({$platform},1) ON DUPLICATE KEY UPDATE count=count+1";
                        $connection = $this->di->get('db');
                        $connection->execute($sql);

                        //游客跳过以下处理
                        if($userType != 0){
                            //写入用户活跃统计表，用于后台用户分析，add by 2015/10/12
                            //存在则更新，不存在则添加
                            $today = date("Ymd");
                            $now = time();
                            $sql1 = "insert into pre_user_active_count(platform,roomId,uid,date,createTime) VALUES ({$platform},{$roomId},{$uid},{$today},{$now}) ON DUPLICATE KEY UPDATE createTime=" . $now.",endTime=0";
                            $connection->execute($sql1);
                        }
                    } catch (\Exception $e) {
                        $errorData = $e->getMessage();
                        $directory = $this->config->directory->logsDir;
                        $logger = new \Phalcon\Logger\Adapter\File($directory . '/callback.log');
                        $logger->error($errorData);
                        $callbackData['errorinfo'] = "enterRoom error : " . $errorData;
                    }
                }
                else if ($controlType == 'leaveRoom') { //和userleave流程相同，因此屏蔽,不做处理
                    /*$uid = $roomData['data']['uid'];
                    $roomId = $roomData['data']['roomId'];
                    $userType = $roomData['data']['userType'];
                    $callbackData['controltype'] = $controlType;
                    $callbackData['uid'] = $uid;
                    $callbackData['roomId'] = $roomId;
                    $callbackData['userType'] = $userType;
                    array_push($result['data'], $callbackData);*/
                    // {"data":{"controltype":"leaveRoom","data":{"uid":"94066c40-d836-11e4-bf82-b1b7d49c930a","level":"1","userdata":"In WebSite : It is a UserData To NodeJS"}},"route":"leaveRoom"}
                }
            }

            $result['info'] = "ok";
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
        catch(\Exception $e){
            $result['code'] = 1;
            $result['info'] = "process error : ".$e->getMessage();
            exit(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
    }
    
    //获取敏感字txt文件
    public function forbiddenwordtxtAction() {
        if ($this->request->isPost()) {
            $result['url'] = '/'.$this->config->url->forbiddenwordtxt;
            return $this->status->ajaxReturn($this->status->getCode('OK'), $result);
        }
        $this->proxyError();
    }

    //举报系统接口【新增】
    public function addInformAction(){
        // try{
        if ($this->request->isPost()) {
            $result = $this->roomModule->getRoomMgrObject()->addInform();
            /*$user = $this->userAuth->getUser();
            $dataArray['uid'] = $user->getUid();
            $dataArray['targetId'] = $this->request->getPost("targetId");
            $dataArray['type'] = $this->request->getPost("type");
            $dataArray['content'] = $this->request->getPost("content");
            $InvBaseClass = new \Micro\Frameworks\Logic\Investigator\InvBase();
            $result = $InvBaseClass->addInform($dataArray);*/
            return $this->status->ajaxReturn($this->status->getCode('OK'),$result['data']);
        }
        $this->proxyError();
        // }
        /*catch(\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }*/
        /*if($this->request->isPost()){

            $addData['uid'] = $this->request->getPost('uid');
            $addData['targetId'] = $this->request->getPost('targetId');
            $addData['type'] = $this->request->getPost('type');
            $addData['content'] = $this->request->getPost('content');

            $innerPayDB = new \Micro\Frameworks\Logic\Investigator\InvBase();
            $result = $innerPayDB->addInform($addData);
            
        }else{
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }*/
    }

    //举报系统接口【查询】
    public function getInformAction(){
        
    }

    //储存聊天记录
    public function addChatLogAction(){
        if ($this->request->isPost()) {
            // $uid = $this->request->getPost("uid");
            $streamName = $this->request->getPost("streamName") ? $this->request->getPost("streamName") : '_';
            $chatData = $this->request->getPost("chatData");
            $result = $this->roomModule->getRoomMgrObject()->addChatLog($streamName, $chatData);
            return $result;
        }

        $this->proxyError();
    }

    //意见反馈接口【新增】
    public function addSuggestionsAction(){

        if ($this->request->isPost()) { 
            $result = $this->roomModule->getRoomMgrObject()->saveSuggestion();
//            $user = $this->userAuth->getUser();
//            $dataArray['uid'] = $user->getUid();
//            /*$dataArray['pic1'] = $this->request->getPost("pic1");
//            $dataArray['pic2'] = $this->request->getPost("pic2");
//            $dataArray['pic3'] = $this->request->getPost("pic3");*/
//            $pic1 = $this->request->getPost("pic1");
//            $pic2 = $this->request->getPost("pic2");
//            $pic3 = $this->request->getPost("pic3");
//
//            $dirName = date('YmdHis',time())."_".$dataArray['uid'];
//            $filePath = $this->pathGenerator->getSuggestionsPath($dirName);
//            if(!empty($pic1)){
//                $pic1Data = hex2bin($pic1);
//                $file1Name = date('YmdHis',time()).'_pic1.jpg';
//                // $this->storage->write($filePath . $file1Name, $pic1Data, TRUE);
//                $dataArray['pic1'] = $this->saveLogAction($dirName, $pic1Data, 'pic1.jpg');
//                /*$handle = fopen($filePath, 'w');
//                fwrite($handle, $pic1Data);
//                fclose($handle);*/
//            }else{
//                $dataArray['pic1'] = '';
//            }
//            if(!empty($pic2)){
//                $pic2Data = hex2bin($pic2);
//                $file2Name = date('YmdHis',time()).'_pic2.jpg';
//                $dataArray['pic2'] = $this->saveLogAction($dirName, $pic2Data, 'pic2.jpg');
//                // $this->storage->write($filePath . $file2Name, $pic2Data, TRUE);
//            }else{
//                $dataArray['pic2'] = '';
//            }
//            if(!empty($pic3)){
//                $pic3Data = hex2bin($pic3);
//                $file3Name = date('YmdHis',time()).'_pic3.jpg';
//                $dataArray['pic3'] = $this->saveLogAction($dirName, $pic3Data, 'pic3.jpg');
//                // $this->storage->write($filePath . $file3Name, $pic3Data, TRUE);
//                /*$fileName = date('YmdHis',time()).'_pic1.' . $fileExt;
//                $handle = fopen($filePath, 'w');
//                fwrite($handle, $pic1Data);
//                fclose($handle);*/
//            }else{
//                $dataArray['pic3'] = '';
//            }
//
//            $dataArray['dirName'] = $this->request->getPost("dirName");
//            $logdata =  urldecode($this->request->getPost("log"));
//            $dataArray['log'] = $this->saveLogAction($dirName, $logdata, 'log.txt');//$this->request->getPost("log");
//            $dataArray['type'] = $this->request->getPost("type");
//            $dataArray['content'] = $this->request->getPost("content");
//            $this->saveLogAction($dirName, '举报内容：'.$dataArray['content'], 'content.txt');
//            $dataArray['mobile'] = $this->request->getPost("mobile");
//            $dataArray['email'] = $this->request->getPost("email");
//            $dataArray['qq'] = $this->request->getPost("qq");
//            $InvBaseClass = new \Micro\Frameworks\Logic\Investigator\InvBase();
//            $result = $InvBaseClass->addSuggestion($dataArray);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

//    public function savePicAction(){
//        if ($this->request->hasFiles()) {
//            // 自身业务的验证
//            $userdata = $this->session->get($this->config->websiteinfo->authkey);
//            $uid = $userdata['uid'];
//            $dirName = $this->request->getPost("dirName");
//            try {
//                foreach ($this->request->getUploadedFiles() as $file) {
//                    $fileNameArray = explode('.', strtolower($file->getName()));
//                    $fileExt = $fileNameArray[count($fileNameArray) - 1];
//                    //$fileExt = substr($file->getName(), -4);
//                    //创建目录
//                    // if(!$dirName){
//                        $dirName = date('YmdHis',time())."_".$uid;
//                    // }
//
//                    $filePath = $this->pathGenerator->getSuggestionsPath($dirName);
//                    $fileName = date('YmdHis',time()).'_pic.' . $fileExt;
//                    $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
//                    try {
//                        $avatar = $this->pathGenerator->getFullSuggestionsPath($dirName, $fileName);
//                        return $this->status->ajaxReturn($this->status->getCode('OK'),$avatar);
//                    } catch (\Exception $e) {
//                        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
//                    }
//                }
//            } catch (\Exception $e) {
//                return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
//            }
//        } else {
//            return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
//        }
//    }
//
//    public function saveLogAction($dirName, $logData, $type = 'log.txt'){
//        if(!$dirName){
//            $dirName = date('YmdHis',time()) . '_' . $uid;
//        }
//        $filePath = $this->pathGenerator->getSuggestionsPath($dirName);
//        // $fileExt = '.txt';
//        $fileName = date('YmdHis',time()) . '_' . $type;// . $fileExt;
//        // $avatar = $this->pathGenerator->getFullSuggestionsLogPath($fileName);
//        $this->storage->write($filePath . $fileName, $logData, TRUE);
//        try {
//            $avatar = $this->pathGenerator->getFullSuggestionsPath($dirName, $fileName);
//            return $avatar;
//        } catch (\Exception $e) {
//            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
//        }
//    }
  
    
    //领取渠道礼包
    public function getSourceGiftAction() {
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if (!$user) {//未登录
                return $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $result = $this->taskMgr->getSourceGiftPackageTask();
            return $this->status->ajaxReturn($result['code'], $result['data']);
         }
        $this->proxyError();
    }
    
    //发送赠送道具的验证码
    public function giveItemSendSmsCodeAction() {
        if ($this->request->isPost()) {
            $type = $this->request->getPost("type");
            $result = $this->userAuth->userGiveItemSendCode($type);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    //判断账号是否正确
    public function checkUidIsRightAction() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost("uid");
            $postData['uid'] = $uid;
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $user = UserFactory::getInstance($uid);
            $userInfo = $user->getUserInfoObject()->getUserInfo();
            if ($userInfo) {
                $result['nickName'] = $userInfo['nickName'];
                return $this->status->ajaxReturn($this->status->getCode('OK'), $result);
            } else {
                return $this->status->ajaxReturn($this->status->getCode('UID_ERROR'));
            }
        }
        $this->proxyError();
    }

    
    //判断用户手机是否绑定
    public function checkUserTelephoneAction() {
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if (!$user) {//未登录
                return $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $userInfo = $user->getUserInfoObject()->getUserInfo();
            if ($userInfo['telephone']) {
                $result['telephone'] = substr_replace($userInfo['telephone'], '****', 3, 4);
                return $this->status->ajaxReturn($this->status->getCode('OK'), $result);
            } else {
                return $this->status->ajaxReturn($this->status->getCode('HAS_NOT_BIND_TELEPHONE'));
            }
        }
        $this->proxyError();
    }

    public function getBannersListAction(){
        if($this->request->isPost()){
            $result = $this->configMgr->getBannerList(0, 0, 100);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getEventListAction(){
        if($this->request->isPost()){
            $p = intval($this->request->getPost('p')) ? intval($this->request->getPost('p')) : 1;
            $percount = intval($this->request->getPost("perCount")) ? intval($this->request->getPost("perCount")) : 10;
            if($p >= 1){
                $offset = ($p - 1) * $percount;
            }else{
                $offset = 0;
            }

            $result = $this->configMgr->getEventList(0, $offset, $percount);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getEventListForHeaderAction(){
        if($this->request->isPost()){
            $p = intval($this->request->getPost('p')) ? intval($this->request->getPost('p')) : 1;
            $percount = intval($this->request->getPost("perCount")) ? intval($this->request->getPost("perCount")) : 10;
            if($p >= 1){
                $offset = ($p - 1) * $percount;
            }else{
                $offset = 0;
            }

            $result = $this->configMgr->getEventListForHeader(0, $offset, $percount);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getHomeFlashConfigAction(){
        if($this->request->isPost()){
            $data = array();
            $uid = $this->request->getPost('uid');
            $result = $this->roomModule->getRoomMgrObject()->getRoomInfo(NULL, $uid);
            if($result){
                $result = $result->toArray();
                $data['useAccelarate'] = $this->config->radioType;	//写死返回
                $data['publishRoute'] = $result['publishRoute'];
            }

            return $this->status->ajaxReturn($this->status->getCode('OK'), $data);
        }

        return $this->proxyError();
    }

    public function getFlashPicsAction(){
        if($this->request->isPost()){
            $picArr = $this->config->flashPicUrl;

            return $this->status->ajaxReturn($this->status->getCode('OK'), $picArr);
        }

        return $this->proxyError();
    }
    //获取flash版本
    public function getFlashVersionAction(){
        if($this->request->isPost()){
            return $this->status->ajaxReturn($this->status->getCode('OK'), $this->config->urlConfig->flashFileName);
        }
        return $this->proxyError();
    }
    
    //主播房间封面上传 add by 2015/10/20   
    public function uploadAnchorPosterAction() {
        if ($this->request->isPost()) {
            $result = $this->userMgr->uploadAnchorRoomPoster();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    //主播房间封面列表 add by 2015/10/20   
    public function getAnchorPosterAction() {
        if ($this->request->isPost()) {
            $page = $this->request->getPost("page");
            $pageSize = $this->request->getPost("pageSize");
            !isset($page) && $page = 1;
            !isset($pageSize) && $pageSize = 20;
            $result = $this->userMgr->getAnchorRoomPosterList($page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    //删除主播海报 add by 2015/10/20   
    public function delAnchorPosterAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost("id");
            $result = $this->userMgr->delAnchorRoomPoster($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    //使用主播海报 add by 2015/10/20   
    public function useAnchorPosterAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost("id");
            $result = $this->userMgr->useAnchorRoomPoster($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    //查询主播海报是否上传 add by 2015/10/20 
    public function checkAnchorPosterAction() {
        if ($this->request->isPost()) {
            $result = $this->userMgr->checkAnchorRoomPoster();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    //验证图形验证码是否正确 add by 2015/10/22
    public function checkSmsCodeAction() {
        if ($this->request->isPost()) {
            if (!$this->baseCode->checkCaptcha()) {//验证验证码是否正确
                //验证码错误
                return $this->status->ajaxReturn($this->status->getCode('SECURITY_CODE_ERROR'));
            }
            return $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        return $this->proxyError();
    }

    //相册上传
    public function uploadPhotoAlbumAction() {
        if ($this->request->isPost()) {
            $result = $this->userMgr->uploadPhotoAlbum();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //检查录像次数
    public function checkVideoNumAction() {
        if ($this->request->isPost()) {
            $result = $this->userMgr->checkVideoNum();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //获取录像列表
    public function getRECListAction() {
        if ($this->request->isPost()) {
            $result = $this->userMgr->getRECList();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //删除录像
    public function delRECAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $result = $this->userMgr->delREC($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //修改录像开启状态
    public function setRECStatusAction(){
        if ($this->request->isPost()) {
            $status = $this->request->getPost('status');
            if (is_null($status) || !in_array($status, array(0,1))) {
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'));
            }
            $result = $this->userMgr->setRECStatus($status);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //修改播放的录像
    public function setPlayRECAction(){
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $status = $this->request->getPost('status');
            if (is_null($status) || !in_array($status, array(0,1))) {
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'));
            }
            $result = $this->userMgr->setPlayREC($id, $status);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //获取日志存储位置
    public function getChatLogUrlAction(){
        if ($this->request->isPost()) {
            $url = $this->url->getStatic($this->config->websiteinfo->chatdatapath);
            return $this->status->ajaxReturn($this->status->getCode('OK'), $url);
        }
        return $this->proxyError();
    }
    

}
