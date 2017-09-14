<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;


class UserController extends ControllerBase
{
    public function initialize()
    {
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '用户';
            $this->view->ns_name = 'user';
            //$this->view->setTemplateAfter('main');
        }
        parent::initialize();
    }

    public function indexAction()
    {
        return $this->forward("/userprop");
    }

    /*
     * 类别：页面
     * 修改密码
     * */
    public function modifyPasswordAction()
    {
        $this->view->ns_type = 'modifyPassword';
    }
    
    //修改密码
    public function mofpwdAction() {
        if ($this->request->isPost()) {
            $oldPwd = $this->request->getPost('oldpassword');
            $newPwd = $this->request->getPost('newpassword');
            $result = $this->userAuth->changePassword($oldPwd, $newPwd);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 类别：页面
     * 忘记密码
     * */
    public function getPasswordAction()
    {
        $this->view->GMQQ = $this->config->GMConfig->QQNumber;
        $this->view->ns_type = 'getPassword';
        //获取安全问题集合
        $result = $this->configMgr->getQuestionsConfigs();
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->view->issues = $result['data'];
        }
    }

    /*
     * 类别：page
     * 用户注册成功
     * */
    public function regSuccessAction(){

        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->pageError();
        }
        $this->view->userName = $user->getUserInfoObject()->getUserInfo()['nickName'];
        $status = $this->session->get($this->config->websiteinfo->s_user_be_active);
        if(!$status){
            return $this->pageError();
        }
        $this->session->remove($this->config->websiteinfo->s_user_be_active);
    }

    /*
     * 第三方登录第一步（入口）
    */
    public function loginOfThirdAction(){
        $type = $this->request->get('media_type');
        $state = $this->request->get('state');
        $typeTransform = array(
            'qqdenglu' => 'QQ',
            'sinaweibo' => 'Sina',
            'douzi' => 'douzi',
        	'weixin' => 'weixin',
        );
        if(empty($typeTransform[$type])){
            $type = 'qqdenglu';
        }
        $url = $this->oauth->createOAuth($typeTransform[$type])->getAuthorizeURL($state);
        return $this->redirect($url);
    }

    /*
     * 第三方登录第二步（回调）
    */
    public function loginOfThirdCallbackAction(){
        $code = $this->request->get('code');
        $state = $this->request->get('state');
        if($code != NULL){
            $result = $this->userAuth->userLoginCallback($state, $code);
            if($result['code'] == $this->status->getCode('OK')){
                $result = $result['data'];
                return $this->redirect("thirdloginsuccess.html?state=/user/autoLogin&state2={$state}&status=0&first={$result['first']}&ns_source={$result['ns_source']}&type={$result['type']}&isRecommend={$result['isRecommend']}");
            }

            $msg = urlencode($this->status->getCodeInfo($result['code']));
            return $this->redirect("thirdloginsuccess.html?state=/user/autoLogin&state2={$state}&status={$result['code']}&msg={$msg}");
        }
        return $this->redirect("thirdloginsuccess.html?state=/user/autoLogin&state2={$state}&status=1");
    }
    /*
     * 豆子第三方登录第二步（回调）
    */
    public function loginOfThirdCallbackDouziAction(){
        $code['userId'] = $this->request->get('userID');
        $code['timestamp'] = $this->request->get('timestamp');
        $code['sign'] = $this->request->get('sign');
        $state = $this->request->get('state');
        
        $result = $this->userAuth->userLoginCallback($state, $code);
        if($result['code'] == $this->status->getCode('OK')){
            return $this->redirect("thirdloginsuccess.html?state={$state}&status=0");
        }
        return $this->redirect("thirdloginsuccess.html?state={$state}&status={$result['code']}&msg={$result['data']['info']}");
    }

    /*
     * 豆子登录
     */
    public function dzloginbackAction(){
        $code['userID'] = $this->request->get('userID');
        $code['timestamp'] = $this->request->get('timestamp');
        $code['sign'] = $this->request->get('sign');
        $state = $this->request->get('state');

        $result = $this->userAuth->userLoginByDZ($state, $code);
        if($result['code'] == $this->status->getCode('OK')){
            return $this->redirect('');
        }
        return $this->pageError();
    }

    /*
     * 类别：page
     * 用户完善资料
     * */
    public function updateProfileAction(){
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->pageError();
        }
        $this->view->avatar = $user->getUserInfoObject()->getUserInfo()['avatar'];
    }

    /*
     * 类别：接口
     * 用户是否存在
     * */
    public function checkUserExistAction() {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username'); //用户名
            $result = $this->userMgr->checkUserExist($username); //查询用户名是否存在
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 类别：ajax
     * 安全类别
     * */
    public function getSecureAction(){
        if ($this->request->isPost()) {
            $securityCode = $this->request->getPost('securityCode');
            $captchaId = $this->request->getPost('captchaId');

            if($this->baseCode->checkSecurityCode($captchaId, $securityCode)){
                $username = $this->request->getPost('username');
                if(!empty($username)){
                    $result = $this->userMgr->getSecure($username);
                    if ($result['code'] == $this->status->getCode('OK')) {
                        $this->session->set($this->config->websiteinfo->securityuser, $username);
                        if(!empty($result['data']['telephone'])){
                            $result['data']['telephone'] = substr_replace($result['data']['telephone'], '****', 3, 4);
                        }
                        if(!empty($result['data']['email'])){
                            $result['data']['email'] = substr_replace($result['data']['email'], '****', 4, strrpos($result['data']['email'], '@') - 4);
                        }
                        $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
                    }
                    $this->status->ajaxReturn($result['code'], $result['data']);
                }
            }
            $this->status->ajaxReturn($this->status->getCode('SECURITY_CODE_ERROR'));
        }
        $this->proxyError();
    }

    /*
     * TYPE: AJAX
     * 重置密码（安全模块）
     * */
    public function resetPasswordAction(){
        if ($this->request->isPost()) {
            $di = FactoryDefault::getDefault();
            $session = $di->get('session');
            $config = $di->get('config');
            if($session->get($config->websiteinfo->securityuserverified) == '1'){
                $newPwd = $this->request->getPost('newPwd');
                $result = $this->userMgr->userResetPwd($newPwd);
                if ($result['code'] == $this->status->getCode('OK')) {
                    $session->remove($config->websiteinfo->securityuserverified);
                    $session->remove($config->websiteinfo->securityuser);
                    $this->status->ajaxReturn($this->status->getCode('OK'));
                }
                $this->status->ajaxReturn($result['code'], $result['data']);
            }
            $this->status->ajaxReturn($this->status->getCode('SECURITY_SESSION_FALSE'));
        }
        $this->proxyError();
    }

    //检查用户昵称
    public function checkNickNameExistAction(){
        if ($this->request->isPost()) {
            $nickName = $this->request->getPost('nickName');
            $result = $this->userAuth->checkNickName($nickName);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 类别：接口
     * 用户注册
     * */
    public function userRegisterAction()
    { 
        if ($this->request->isPost()) {
            $userName = $this->request->getPost('userName'); //用户名
            $password = $this->request->getPost('password'); //用户密码
            $nickName = $this->request->getPost('nickName'); //用户昵称
            $loginType = $this->request->getPost('loginType');
            $result = $this->userAuth->newUserReg($userName, $password, $nickName, $loginType);
            $this->status->ajaxReturn($result['code'], $result['data']);

            //注册流程
            /*   if($this->baseCode->checkCaptcha()){
              $result = $this->userAuth->checkNickName($nickName);
              if($result){
              $this->status->ajaxReturn($this->status->getCode('USER_NICK_NAME_EXIST'));
              }
              if($loginType == '2'){
              $result = $this->userAuth->userRegOfDouzi($userName, $password, $nickName);
              }else{
              $result = $this->userAuth->userReg($userName, $password, $nickName);
              if ($result['code'] == $this->status->getCode('OK')) {
              $this->userAuth->userAutoLogin($result['data']['accountId']);
              //自动登录
              $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
              $this->session->set($this->config->websiteinfo->user_auto_login_username, $userName);
              }
              }
              $this->status->ajaxReturn($result['code'], $result['data']);
              }
              $this->status->ajaxReturn($this->status->getCode('SECURITY_CODE_ERROR')); */
        }
        $this->proxyError();
    }

    /*
     * 类别：接口
     * 手机注册
     * */
    public function phoneRegisterAction() {
        if ($this->request->isPost()) {
            $userName = $this->request->getPost('userName'); //用户名
            $password = $this->request->getPost('password'); //用户密码
            !isset($password) && $password = '';
            $nickName = $this->request->getPost('nickName'); //用户昵称
            $smsCode = $this->request->getPost('smsCode'); //验证码
            $result = $this->userAuth->newPhoneReg($userName, $password, $nickName, $smsCode);
            $this->status->ajaxReturn($result['code'], $result['data']);

            /*  $result = $this->userAuth->checkNickName($nickName);
              if ($result) {
              $this->status->ajaxReturn($this->status->getCode('USER_NICK_NAME_EXIST'));
              }

              $result = $this->userAuth->phoneReg($userName, $password, $nickName,$smsCode);
              if ($result['code'] == $this->status->getCode('OK')) {
              $this->userAuth->userAutoLogin($result['data']['accountId']);
              //自动登录
              $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
              $this->session->set($this->config->websiteinfo->user_auto_login_username, $userName);
              }
              $this->status->ajaxReturn($result['code'], $result['data']); */
        }
        $this->proxyError();
    }
    
    
    /*
     *
     * 手机注册发送验证码
     * */
     public function regPhoneSendCodeAction() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $result = $this->userAuth->regPhoneSendCode($telephone,1);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

    /*
     * 类别：接口
     * 用户登录
     */
    public function loginAction(){
        if($this->request->isPost()){
            $userName = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            //自动登录
            $autoLogin = $this->request->getPost('autoLogin');
            $loginType = $this->request->getPost('loginType');

            //判断用户是否频繁操作
            if(!$this->userAuth->questCheck() && !$this->baseCode->checkCaptcha()){
                return $this->status->ajaxReturn($this->status->getCode('SECURITY_CODE_ERROR'));
            }
            if($loginType == '2'){
                $result = $this->userAuth->userLoginOfDouzi($userName, $password);
                //$result = $this->userAuth->userLogin($userName, $password);
            }else{
                $result = $this->userAuth->userLogin($userName, $password);
            }
            if ($result['code'] == $this->status->getCode('OK')) {
                if($autoLogin == '1'){
                    $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
                    $this->session->set($this->config->websiteinfo->user_auto_login_username, $userName);
                }
                $this->status->ajaxReturn($result['code'], $result['data']);
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function aAction(){
        var_dump($this->request->get());die;
    }

    /*
     * 用户自动登录
    */
    public function autologinAction(){
        $state = $this->request->get('state');

        $result = $this->userAuth->setAutoLogin();
        if ($result['code'] == $this->status->getCode('OK')) {
            $time = 2592000;
            $this->cookies->set($this->config->websitecookies->userName, $result['data']['userName'], time() + $time);
            $this->cookies->set($this->config->websitecookies->userPassword, $result['data']['password'], time() + $time);
            $this->redirect($state);
        }else{
            $this->redirect('');
        }
    }

    /*
     * 类别：ajax
     * 用户登出
     */
    public function loginOutAction(){
        $state = $this->request->get('state');
        $this->cookies->set($this->config->websitecookies->userName, 0, time());
        $this->cookies->set($this->config->websitecookies->userPassword, 0, time());
        $this->userAuth->userLogout();
        if(empty($state)){
            $state = '';
        }
        $this->redirect($state);
    }

    /*
     * 类别：接口
     * 获取用户信息
     * */
    public function  getUserInfoAction(){
        if($this->request->isPost()){
            $user = $this->userAuth->getUser();
            if($user != NULL){
                $userInfo = $user->getUserInfoObject()->getData();
                $result = $this->userMgr->getUserLevelInfo($userInfo['uid']);
                if($result['code'] == $this->status->getCode('OK')){
                    $userInfo['levelInfo'] = $result['data'];
                }
                $userInfo['cash'] = $userInfo['cash'];
                //消息
                $result = $this->userMgr->isHasUnRead();
                $userInfo['news'] = 0;
                if($result['code'] == $this->status->getCode('OK')){
                    $userInfo['news'] = $result['data'];
                }
                $this->status->ajaxReturn($this->status->getCode('OK'), $userInfo);
            }else{
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }
    }

    /**
     * 获取用户基本信息【add by 2016/01/15】
     */
    public function getUserBasicInfoAction(){
      if($this->request->isPost()){
            $user = $this->userAuth->getUser();
            if($user != NULL){
                $userRes = $user->getUserInfoObject()->getUserBasicInfo();
                //消息
                $result = $this->userMgr->isHasUnRead();
                $userBasicInfo['news'] = 0;
                if($result['code'] == $this->status->getCode('OK')){
                    $userBasicInfo['news'] = $result['data'];
                }
                $this->status->ajaxReturn($userRes['code'], $userRes['data']);
            }else{
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }
    }
    
    //获得用户基本信息接口  公用接口 add by 2015/08/24
    public function getUserBaseInfoAction() {
        if ($this->request->isPost()) {
            $result = $this->userMgr->getUserBaseInfo();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 类别：接口
     * 修改昵称
     * */
    public function  updateNickNameAction(){
        if($this->request->isPost()){
            $nickName = $this->request->getPost('nickname');

            $result = $this->userMgr->updateNickName($nickName);
            if($result['code'] == $this->status->getCode('OK')){
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * TYPE: AJAX
     * 修改头像
     * */
    public function  updateAvatarAction(){
        if($this->request->isPost()){
            $result = $this->userAuth->getUser()->getUserInfoObject()->uploadAvatar();
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 类别：接口
     * 发送验证邮件
     * */
    public function sendRegMailAction(){
        if($this->request->isPost()){
            $mail = $this->request->getPost('username');
            $result = $this->userAuth->resendEmail($mail);

            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 类别：页面（跳转）
     * 注册邮箱验证
     * */
    public  function  regMailRetrieveAction(){
        $tokenSec = $this->request->get('tokenSec');
        $token = $this->request->get('token');
        $result = $this->userMgr->activeUser($tokenSec, $token);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->userAuth->userAutoLogin($result['data']['accountId']);
            return $this->redirect("user/regsuccess");
        }else{
            return $this->redirect("user/regsuccess");
        }
    }

    /*
     * 类别：page（跳转）
     * 第三方登录跳转
     * */
    public function thirdLoginAction(){
        if($this->request->isGet()){
            $code = $this->request->get('code');
            $state = $this->request->get('state');

            if($code != NULL){
                $ret = $this->userAuth->userLoginCallback($code);

                if ($ret['code'] == $this->status->getCode('OK')){
                    $defaultUrl = $ret['data']['header'];

                    if(empty($state)){
                        $this->redirect($defaultUrl);
                    }else{
                        $this->redirect($state);
                    }
                } else{
                    $this->redirect($state.'?tl=0');
                }
            }
        }
    }
    //获取用户等级信息
    public function getUserLevelInfoAction(){
        if($this->request->isPost()) {
            $uid = $this->request->get('uid');
            $result = $this->userMgr->getUserLevelInfo($uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }    

    //手机验证码登录时 发送验证码
    public function sendPhoneLoginSmsAction(){
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $result = $this->userAuth->phoneLoginSendCode($telephone);
            $this->status->ajaxReturn($result['code'], $result['data']);
         }
        $this->proxyError();
    }

    //手机验证码登录 add by 2015/07/20
    public function phoneSmsLoginAction() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $smsCode = $this->request->getPost('smsCode');
            $result = $this->userAuth->phoneSmsCodeLogin($telephone,$smsCode);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    //手机验证码登录 多账号选择  add by 2015/09/25
    public function phoneSmsUsersAction() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $smsCode = $this->request->getPost('smsCode');
            $result = $this->userAuth->phoneSmsUsers($telephone, $smsCode);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //手机验证码登录 add by 2015/09/25
    public function phoneUserLoginAction() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $result = $this->userAuth->newPhoneSmsCodeLogin($uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //修改个性签名
    public function updateSignatureAction() {
        if ($this->request->isPost()) {
            $signature = $this->request->getPost('signature');
            $result = $this->userMgr->updateSignature($signature);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        return $this->proxyError();
    }

}