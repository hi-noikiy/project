<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class ActionsController extends ControllerBase
{
    public function login(){
        if($this->request->isPost()){
            $userName = $this->request->getPost('userName');
            $password = $this->request->getPost('password');
            //自动登录
            $autoLogin = $this->request->getPost('autoLogin');
            $loginType = $this->request->getPost('loginType');

            //判断用户是否频繁操作
            if(!$this->userAuth->questCheck() && !$this->baseCode->checkCaptcha()){
                return $this->status->mobileReturn($this->status->getCode('SECURITY_CODE_ERROR'));
            }

            if($loginType == '2'){
                $result = $this->userAuth->userLoginOfDouzi($userName, $password);
            }else{
                $result = $this->userAuth->userLoginByMobile($userName, $password);
            }

            if ($result['code'] == $this->status->getCode('OK')) {
                if($autoLogin == '1'){
                    $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
                    $this->session->set($this->config->websiteinfo->user_auto_login_username, $userName);
                }

                return $this->status->mobileReturn($this->status->getCode('OK'), $result);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * 第三方登录（回调）
    */
    public function loginOfThirdCallback(){
        $type = $this->request->get('type');
        $openId = $this->request->get('openId');
        $nickName = $this->request->get('nickname');
        $avatar = $this->request->get('avatar');

        // 临时写死，回头要修改
        if ($type == "QQ") {
            $type = 'qqdenglu';
        }
        else if ($type == "Sina") {
            $type = 'sinaweibo';
        }
        else if ($type == "Weixin") {
            $type = 'weixin';
        }

        if($openId){
            $result = $this->userAuth->userLoginCallbackFromMobile($type, $openId , $nickName, $avatar);
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function logout(){
        if($this->request->isGet()){
            // 清空用户设备信息
            $this->roomModule->getRoomMgrObject()->clearUsserDevice();
            $result = $this->userAuth->userLogout();
            return $this->status->mobileReturn($this->status->getCode('OK'), $result);
        }

        return $this->proxyError();
    }

    public function checkUserExists(){
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username'); //用户名
            $result = $this->userMgr->checkUserExist($username); //查询用户名是否存在
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    public function checkNicknameExists(){
        if ($this->request->isPost()) {
            $nickName = trim($this->request->getPost('nickname'));
            $result = $this->userAuth->checkNickName($nickName);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    public function getSecure(){
        if ($this->request->isGet()) {
            $username = $this->request->get('username');
            $type = $this->request->get('type');
            if(!empty($username)){
                $result = $this->userMgr->getSecure($username, $type);
                if ($result['code'] == $this->status->getCode('OK')) {
                    $this->session->set($this->config->websiteinfo->securityuser, $username);
                    if(!empty($result['data']['telephone'])){
                        $result['data']['telephone'] = substr_replace($result['data']['telephone'], '****', 3, 4);
                    }

                    if(!empty($result['data']['email'])){
                        $result['data']['email'] = substr_replace($result['data']['email'], '****', 4, strrpos($result['data']['email'], '@') - 4);
                    }

                    if($type == 1){
                        // 获得密保列表
                        unset($result['data']['telephone']);
                        $secureList = $this->configMgr->getQuestionsConfigs();
                        $result['data']['secureList'] = $secureList['data'];
                    }elseif($type == 2){
                        // 判断是否有设置过密码，否则报错
                        $uid = $result['data']['uid'];
                        $res = $this->userMgr->ifSetPwd($uid);
                        if($res['code'] == $this->status->getCode('OK')){
                            if($res['data'] == 1){
                                // 未重置过密码，不可通过手机找回
                                return $this->status->mobileReturn($this->status->getCode('NOT_SET_LOGIN_PWD'));
                            }
                        }else{
                            return $this->status->mobileReturn($res['code'], $res['data']);
                        }

                        unset($result['data']['issues']);
                    }

                    return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
                }

                return $this->status->mobileReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    public function checkPwdBySecure(){
        if($this->request->isPost()){
            $questions = $this->request->getPost('questionid');
            $answer = $this->request->getPost('answer');
            $result = $this->userMgr->checkQusetionAnswer($questions, $answer);
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function checkPwdByPhone(){
        if($this->request->isPost()){
            $code = $this->request->getPost('seccode');
            $result = $this->userMgr->checkSecSMSCode($code);
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //修改用户名
    public function setUsername(){
        if ($this->request->isPost()) {
            $username = $this->request->getPost('userName');
            $result = $this->userMgr->setUsername($username);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //设置初始密码
    public function setInitPassword(){
        if ($this->request->isPost()) {
            $password = $this->request->getPost('password');
            $result = $this->userMgr->setInitPassword($password);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function resetPwd(){
        if($this->request->isPost()){
            $password = $this->request->getPost('newPwd');
            if($this->userMgr->checkUseResetPwd()){
                $result = $this->userMgr->userResetPwd($password);
                if($result['code'] == $this->status->getCode('OK')){
                    //删除各个session、
                    $session = $this->session;
                    $info = $this->config->websiteinfo;
                    $session->remove($info->securityuser);
                    $session->remove($info->user_get_password_key);
                    $session->remove($info->user_get_password_reset);
                    return $this->status->mobileReturn($this->status->getCode('OK'));
                }

                return $this->status->mobileReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    public function sendCodeToPhone(){
        if($this->request->isPost()){
            $userName = $this->session->get($this->config->websiteinfo->securityuser);
            $result = $this->userMgr->getPasswordSendCode($userName);

            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function  updateNickName(){
        if($this->request->isPost()){
            $nickName = $this->request->getPost('nickname');

            $result = $this->userMgr->updateNickName($nickName);
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function  updateSignature(){
        if($this->request->isPost()){
            $signature = $this->request->getPost('signature');

            $result = $this->userMgr->updateSignature($signature);
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function setUserCoordinate(){
        if($this->request->isPost()){
            $result = $this->userMgr->setUserPositions();
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getRecommFocusList(){
        if($this->request->isGet()){
            $result =  $this->roomModule->getRoomMgrObject()->getRecommFocusList();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * @param type 类型  赠送vip : giveVip,赠送座驾 ：giveCar
     *
     * @return mixed
     */
    public function giveItemSendSmsCode() {
        if ($this->request->isPost()) {
            $type = $this->request->getPost("type");
            $result = $this->userAuth->userGiveItemSendCode($type);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }


    //手机验证码登录时 发送验证码
    public function sendPhoneLoginSms() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $result = $this->userAuth->phoneLoginSendCode($telephone, 1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //手机验证码登录时 发送验证码 add by 2016/01/21
    public function newSendPhoneLoginSms() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $result = $this->userAuth->phoneLoginSendCode($telephone);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //手机验证码登录 add by 2015/07/20
    public function phoneSmsLogin() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $smsCode = $this->request->getPost('smsCode');
            $result = $this->userAuth->phoneSmsCodeLogin($telephone,$smsCode);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    
    //手机验证码登录 多账号选择  add by 2015/09/25
    public function phoneSmsUsers() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $smsCode = $this->request->getPost('smsCode');
            $result = $this->userAuth->phoneSmsUsers($telephone, $smsCode, 1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //手机验证码登录 add by 2015/09/25
    public function phoneUserLogin() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $result = $this->userAuth->newPhoneSmsCodeLogin($uid,1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    public function getEventList(){
        if($this->request->isGet()){
            $p = intval($this->request->get('p')) ? intval($this->request->get('p')) : 1;
            $percount = intval($this->request->get("perCount")) ? intval($this->request->get("perCount")) : 10;
            if($p >= 1){
                $offset = ($p - 1) * $percount;
            }else{
                $offset = 0;
            }

            $result = $this->configMgr->getEventList(1, $offset, $percount);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getBannersList(){
        if($this->request->isGet()){
            $result = $this->configMgr->getBannerList(1, 0, 100);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    
    //手机验证码注册  add by 2015/09/28
    public function phoneSmsReg() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $smsCode = $this->request->getPost('smsCode');
            $uid = $this->request->getPost('uid');
            !isset($uid) && $uid = '';
            $result = $this->userAuth->phoneSmsCodeReg($telephone, $smsCode, $uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    //appapp第三方登录未绑定手机 发送手机验证码 add by 2015/09/29
    public function thirdLoignBindPhoneSms() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $result = $this->userAuth->thirdLoginBindPhoneCode($telephone);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //app第三方登录未绑定手机 需绑定手机后登录 add by 2015/09/28
    public function thirdLoignBindPhone() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $smsCode = $this->request->getPost('smsCode');
            $result = $this->userAuth->thirdLoignBindPhone($telephone, $smsCode);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    
    //app通过token登录
    public function loginByToken() {
        if ($this->request->isPost()) {
            $token = $this->request->getPost('token');
            $deviceid = $this->request->getPost('deviceid');
            $result = $this->userAuth->loginByToken($token, $deviceid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    // app多账号切换
    public function changeAccount() {
        if ($this->request->isGet()) {
            $result = $this->userAuth->changeAccount();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    // app多账号切换登录
    public function changeAccountLogin() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $result = $this->userAuth->changeAccountLogin($uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    
    //获取用户的任务列表
    public function getUserTask() {
        $result = $this->taskMgr->getUserTask(2);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    //获取某个任务状态
    public function getTaskStatus() {
        $taskId = $this->request->get("taskId");
        $result = $this->taskMgr->getTaskStatus($taskId);
        return $this->status->mobileReturn($result['code'], $result['data']);
    }

    //累计观看直播任务
    public function setTotalWatch() {
        if ($this->request->isPost()) {
            $roomId=$this->request->getPost("roomId");
            $result = $this->taskMgr->totalWatchTask($roomId);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    //累计发言任务
    public function setTotalTalk() {
        if ($this->request->isPost()) {
            $roomId=$this->request->getPost("roomId");
            $result = $this->taskMgr->getTotalTalkTask($roomId);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    //分享
    public function share() {
        if ($this->request->isPost()) {
            $type = $this->request->getPost("type");
            $anchorId = $this->request->getPost("anchorId");
            $result = $this->userMgr->shareActivity($type, $anchorId);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    
    //图形验证码
    public function getSecurityScript() {
        //验证码类型使用geeTest
        if ($this->config->captchaType == 'geeTest') {
            $revert['type'] = 'geeTest';
            $revert['url'] = $this->geetest->getGeetest();
        } else {
            $revert['type'] = 'securimage';
            $revert['url'] = \Securimage::getCaptchaId();
        }
        return $this->status->ajaxReturn($this->status->getCode('OK'), $revert);
    }

    /*
     * 验证码显示（图片）
     * */
    public function getSecurityImage() {
        ob_clean();
        $captchaId = $this->request->get('id');
        $options = array('captchaId' => $captchaId,
            'no_session' => true,
            'use_database' => true);
        $captcha = new \Securimage($options);

        $captcha->show();
    }

    /**
     * 拒绝填写推荐码
     */
    public function refuseRec(){
        if($this->request->isGet()){
            $result =  $this->userAuth->refuseRec();
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 填写推荐码
     */
    public function fillRec(){
        if($this->request->isPost()){
            $recUid = $this->request->getPost('recUid');
            $result =  $this->userAuth->fillRec($recUid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

}