<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

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
            $avatarFlash = $this->url->getStatic('web/swf/room/uploadhead.swf').'?type=0&avatarUrl=' . '/user/updateavatar?action=uploadAvatar&uid=' . $uid . '&hash=' . md5($_SERVER['HTTP_USER_AGENT']);

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
        try{
            foreach ($jsonData['data']as $dataInfo) {
                //$roomId = $dataInfo['roomId'];
                $roomData = $dataInfo['data'];
                $controlType = $roomData['controltype'];

                // 回调机器人自动增减
                if ($controlType == 'autoRobot') {
                    $roomId = $roomData['data']['roomId'];
                    $robotCount = intval($roomData['data']['robotCount']);
                    $changeRobotCount = intval($roomData['data']['changeRobotCount']);
                    // 写数据库。。。
                    $result = $this->roomModule->getRoomMgrObject()->updateRobotCount($roomId, $robotCount);
                    //echo "roomId = ".$roomId.", robotCount = ".$robotCount.", changeRobotCount = ".$changeRobotCount;die;
                    // test
                    // return $this->status->ajaxReturn($result['code'], $result['data']);
                }
                else if ($controlType == 'userLeave') {
                    // 回调用户和NodeJS已经断开连接
                    $roomId = $roomData['data']['roomId'];
                    $uid = $roomData['data']['uid'];
                    
                    try {
                        // 用户人数减一
                        //echo "roomId = ".$roomId.", uid = ".$uid;die;
                        $phql = "UPDATE \Micro\Models\Rooms SET onlineNum = onlineNum-1 WHERE roomId = ?0 AND onlineNum>0";
                        $valueArray = array(
                            0 => $roomId
                        );
                        $this->modelsManager->executeQuery($phql, $valueArray);

                        $phql = "SELECT count(1)count FROM \Micro\Models\Users u, \Micro\Models\Rooms r WHERE u.uid=r.uid AND u.accountId='".$uid."'";
                        $query = $this->modelsManager->createQuery($phql);
                        $countResult = $query->execute();

                        if ($countResult->valid()) {
                            $count = $countResult[0]['count'];
                            if ($count > 0)  {
                                $result = $this->roomModule->getRoomOperObject()->stopPublishFromNodeJs($roomId);
                                return $this->status->ajaxReturn($result['code'], $result['data']);
                            }
                        }
                    }
                    catch(\Exception $e) {
                        echo $e->getMessage();die;
                    }
                }
            }
            return $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        catch(\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //获取敏感字txt文件
    public function forbiddenwordtxtAction() {
        if ($this->request->isPost()) {
            $result['url'] = $this->config->url->forbiddenwordtxt;
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
//            $user = $this->userAuth->getUser();
//            if (!$user) {//未登录
//                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
//            }
//            $di = FactoryDefault::getDefault();
//            $cookies = $di->get('cookies');
//            $config = $di->get('config');
// 
//            if ($cookies->has($config->websitecookies->utm_source)) {//cookies存在
//                $ns_source = trim($cookies->get($config->websitecookies->utm_source)->getValue());
//                $utm_medium = trim($cookies->get($config->websitecookies->utm_medium)->getValue());
//                $result = $this->taskMgr->getSourceGiftPackageTask($ns_source, $utm_medium);
//                return $this->status->ajaxReturn($result['code'], $result['data']);
//            }
            return $this->status->ajaxReturn($this->status->getCode('REWARD_IS_NOT_EXISTED'));
        }
        $this->proxyError();
    }

}
