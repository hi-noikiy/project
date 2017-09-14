<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserSecurityController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_type = 'usersecurity';
        }
    }

    public function indexAction()
    {
        $sonType = $this->request->get('sonType');
        if(empty($sonType)){
            $sonType = 'center';
        }
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->pageError();
        }
      /*  $userName = $user->getUserInfoObject()->getData()['userName'];
        $result = $this->userMgr->getSecure($userName);
        if('sj' == $sonType){
            if ($result['code'] == $this->status->getCode('OK') && !empty($result['data']['telephone'])) {
                $this->view->ns_telephone = preg_replace('/(1{1}[0-9][0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$result['data']['telephone']);
            }
        }elseif('center' == $sonType){
            if ($result['code'] == $this->status->getCode('OK') && !empty($result['data']['issues'])) {
                $this->view->ns_issues = $result['data']['issues'];
            }
            //获取安全问题集合
            $result = $this->configMgr->getQuestionsConfigs();
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->view->issues = $result['data'];
            }
         }*/
        
        //安全中心
        if($sonType=='center'){
            $sinfo=array();
            $result = $this->userMgr->getUserSecurityInfo();
            if ($result['code'] == $this->status->getCode('OK')) {
                $sinfo = $result['data'];
            }
            $this->view->sinfo = $sinfo;
            //获取安全问题集合
            $questionResult = $this->configMgr->getQuestionsConfigs();
            if ($questionResult['code'] == $this->status->getCode('OK')) {
                $this->view->issues = $questionResult['data'];
            }
        }
        //个人资料
        elseif($sonType=='info'){
            $uinfo = array();
            $result = $user->getUserInfoObject()->getUserAccountInfo();
            $uinfo['userName'] = $result['userName'];
            $uinfo['canSetUserName'] = $result['canSetUserName'];
            $this->view->uinfo = $uinfo;
        }

        $this->view->ns_sonType = $sonType;
    }

    /*
     * 更新安全问题
     * */
    public function updateIssuesAction(){
        $question['id'] = $this->request->get('newQuestionid');
        $question['answer'] = $this->request->get('newAnswer');

        $oldQuestion['id'] = $this->request->get('questionid');
        $oldQuestion['answer'] = $this->request->get('answer');

        $result = $this->userMgr->updateIssues($oldQuestion, $question);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    /*
     * 更新安全问题(回答问题)
     * */
    public function answerIssuesAction(){
        $question['id'] = $this->request->get('newQuestionid');
        $question['answer'] = $this->request->get('newAnswer');

        $result = $this->userMgr->answerIssues($question);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        $this->status->ajaxReturn($result['code'], $result['data']);
    }


    /*
     * 设置安全问题
     * */
    public function setIssuesAction(){
        $question['id'] = $this->request->get('newQuestionid');
        $question['answer'] = $this->request->get('newAnswer');

        $result = $this->userMgr->setIssues($question);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    /*
     * TYPE:AJAX
     * 找回密码(发送验证码)
     * */
    public function getPasswordSendCodeAction(){
        if($this->request->isPost()){
            $userName = $this->session->get($this->config->websiteinfo->securityuser);
            $result = $this->userMgr->getPasswordSendCode($userName);

            if($result['code'] == $this->status->getCode('OK')){
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 找回密码(回答安全问题)
     * */
    public function answerQusetionAction(){
        if($this->request->isPost()){
            $questions = $this->request->getPost('questionid');
            $answer = $this->request->getPost('answer');

            $result = $this->userMgr->checkQusetionAnswer($questions, $answer);
            if($result['code'] == $this->status->getCode('OK')){
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 找回密码(验证验证码)
     * */
    public function checkSecSMSCodeAction(){
        if($this->request->isPost()){
            $code = $this->request->getPost('seccode');

            $result = $this->userMgr->checkSecSMSCode($code);
            if($result['code'] == $this->status->getCode('OK')){
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 找回密码(重置密码)
     * */
    public function resetPasswordAction(){
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

                    $this->status->ajaxReturn($this->status->getCode('OK'));
                }
                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 解绑手机(发送验证码)
     * */
    public function unbindPhoneSendCodeAction(){
        if($this->request->isPost()){
            $result = $this->userAuth->unbindPhoneSendCode();

            if($result['code'] == $this->status->getCode('OK')){
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 解绑手机（解绑）
     * */
    public function unbindPhoneAction(){
        if($this->request->isPost()){
            $smsCode = $this->request->getPost('emailCode');

            $result = $this->userAuth->unbindPhone($smsCode);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 绑定手机(发送验证码)
     * */
    public function bindPhoneSendCodeAction(){
        if($this->request->isPost()){
            $telephone = $this->request->getPost('phone');
            $result = $this->userAuth->bindPhoneSendCode($telephone);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 绑定手机(绑定)
     * */
    public function bindPhoneAction(){
        if($this->request->isPost()){
            $smsCode = $this->request->getPost('smsCode');

            $result = $this->userAuth->bindPhone($smsCode);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 提现：发送验证码
     * */
    public function sendTelSmsCodeAction(){
        if($this->request->isPost()){
            $result = $this->userAuth->sendTelSmsCode();
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 提现：验证验证码
     * */
    public function checkTelSmsCodeAction(){
        if($this->request->isPost()){
            $smsCode = $this->request->getPost('smsCode');
            $money = $this->request->getPost('money');

            if(empty($smsCode)){
                return $this->status->ajaxReturn($this->status->getCode('SECURITY_CODE_ERROR'));
            }

            if(empty($money) || $money < 100){
                return $this->status->ajaxReturn($this->status->getCode('MONEY_NOT_LARGE_ENOUGH'));
            }

            $type = $this->request->getPost('type') ? $this->request->getPost('type') : 2;

            $result = $this->userAuth->checkTelSmsCode($smsCode, $money, $type);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    
    
      //修改用户名
      public function setUsernameAction(){
        if ($this->request->isPost()) {
            $username = $this->request->getPost('userName');
            $result = $this->userMgr->setUsername($username);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
      //设置初始密码
      public function setInitPasswordAction(){
        if ($this->request->isPost()) {
            $password = $this->request->getPost('password');
            $result = $this->userMgr->setInitPassword($password);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }
    
    /*
     * TYPE:AJAX
     * 重置安全问题(发送验证码)
     * */

    public function unsetQuestionSendCodeAction() {
        if ($this->request->isPost()) {
            $result = $this->userAuth->unsetQuestionSendCode();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 通过手机验证码重置安全问题
     * */

    public function unsetQuestionByPhoneSmsAction() {
        if ($this->request->isPost()) {
            $smsCode = $this->request->getPost('smsCode');
            $result = $this->userAuth->unsetQuestionByPhoneSms($smsCode);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

}