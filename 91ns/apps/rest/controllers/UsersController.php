<?php

namespace Micro\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Micro\Models\Users;

class UsersController extends ControllerBase
{
    public function reg() {
        if ($this->request->isPost()) {
            $userName = $this->request->getPost('userName'); //用户名
            $password = $this->request->getPost('password'); //用户密码
            $nickName = $this->request->getPost('nickName'); //用户昵称
            $loginType = $this->request->getPost('loginType');
            $result = $this->userAuth->userRegByMobile($userName, $password, $nickName, $loginType);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * 类别：接口
     * 手机注册
     * */
    public function phoneRegister() {
        if ($this->request->isPost()) {
            //用户名
            $userName = $this->request->getPost('userName');
            //用户密码
            $password = $this->request->getPost('password');
            //用户昵称
            $nickName = $this->request->getPost('nickName');
            //验证码
            $smsCode = $this->request->getPost('smsCode');
//            $result = $this->userAuth->checkNickName($nickName);
//            if ($result) {
//                return $this->status->mobileReturn($this->status->getCode('USER_NICK_NAME_EXIST'));
//            }

            $result = $this->userAuth->newPhoneReg($userName, $password, $nickName,$smsCode);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->userAuth->userAutoLogin($result['data']['accountId']);
                //自动登录
                $this->session->set($this->config->websiteinfo->user_auto_login_password, $password);
                $this->session->set($this->config->websiteinfo->user_auto_login_username, $userName);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     *
     * 手机注册发送验证码
     * */
    public function regPhoneSendCode() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $result = $this->userAuth->regPhoneSendCode($telephone);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }
    
    /*
     *
     * 手机注册发送验证码 add by 2016/01/21 添加图形验证码验证
     * */
    public function newRegPhoneSendCode() {
        if ($this->request->isPost()) {
            $telephone = $this->request->getPost('telephone');
            $result = $this->userAuth->regPhoneSendCode($telephone,1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getList(){
        if($this->request->isGet()){
            $type = $this->request->get('type');
            $limit = intval($this->request->get('limit'));
            $result = $this->roomModule->getRoomMgrObject()->getRoomListByType($type, $limit ? $limit : 300);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getNewList() {
        if ($this->request->isPost()) {
            $uid = intval($this->request->getPost('uid'));
            $skip = intval($this->request->getPost('skip'));
            $limit = intval($this->request->getPost('limit'));
            $roomType = $this->request->getPost('roomType') ? intval($this->request->getPost('roomType')) : 0;
            $result = $this->roomModule->getRoomMgrObject()->getNewRoomList($uid, $skip, $limit, $roomType, 1);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getInfo($uid){
        if($this->request->isGet()){
            $result = $this->userMgr->getMobileUserInfo($uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getUserLevelInfo($uid){
        if($this->request->isGet()){
            $result = $this->userMgr->getUserLevelInfo($uid);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function uploadAvatar(){
        if($this->request->isPost()){
            $result = $this->userAuth->getUser()->getUserInfoObject()->uploadAvatar();
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function mofpwd(){
        if ($this->request->isPost()) {
            $oldPwd = $this->request->getPost('oldpassword');
            $newPwd = $this->request->getPost('newpassword');
            if(!(empty($oldPwd)||empty($newPwd))){
                $result = $this->userAuth->changePassword($oldPwd,$newPwd);
                if ($result['code'] == $this->status->getCode('OK')) {
                    return $this->status->mobileReturn($this->status->getCode('OK'));
                }

                return $this->status->mobileReturn($result['code'], $result['data']);
            }
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 绑定手机(发送验证码)
     * */
    public function bindPhoneSendCode(){
        if($this->request->isGet()){
            $telephone = $this->request->get('phone');
            $result = $this->userAuth->bindPhoneSendCode($telephone);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
     * TYPE:AJAX
     * 绑定手机(绑定)
     * */
    public function bindPhone(){
        if($this->request->isPost()){
            $smsCode = $this->request->getPost('smsCode');
            $telephone = $this->request->getPost('phone');
            $result = $this->userAuth->bindPhone($smsCode, $telephone);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
 * TYPE:AJAX
 * 解绑手机(发送验证码)
 * */
    public function unbindPhoneSendCode(){
        if($this->request->isGet()){
            $result = $this->userAuth->unbindPhoneSendCode();
            if($result['code'] == $this->status->getCode('OK')){
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /*
 * TYPE:AJAX
 * 解绑手机（解绑）
 * */
    public function unbindPhone(){
        if($this->request->isPost()){
            $smsCode = $this->request->getPost('smsCode');
            $result = $this->userAuth->unbindPhone($smsCode);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'));
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getUserVip()
    {
        if($this->request->isGet()) {
            $vipConfig = $this->config->buyVipConfig;
            $user = $this->userAuth->getUser();
            if ($user) {
                $result = $user->getUserInfoObject()->getUserVip();
                if ($result['code'] == $this->status->getCode('OK')) {
                    $vipInfo = $result['data'];
                }
            }else{
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $data = array(
//                'vipConfig' => $vipConfig,
                'vipInfo' => $vipInfo
            );

            return $this->status->mobileReturn($result['code'], $data);
        }

        return $this->proxyError();
    }

    public function getUserVipNew()
    {
        if($this->request->isGet()) {
            $user = $this->userAuth->getUser();
            if ($user != NULL) {
                $result = $user->getUserInfoObject()->getUserVipNew();
                return $this->status->mobileReturn($result['code'], $result['data']);
            }else{
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        return $this->proxyError();
    }

    /**
     * 我的道具（其它）
     *
     * @return mixed
     */
    public function getUserNormal()
    {
        if($this->request->isGet()) {
            $user = $this->userAuth->getUser();
            if ($user) {
                $item = $user->getUserItemsObject();
                $result = $item->getItemList(5);
                return $this->status->mobileReturn($result['code'], $result['data']);
            }else{
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
        }

        return $this->proxyError();
    }

    public function buyVip(){
        if($this->request->isPost()){
            $post = $this->request->getPost();
            $data = intval($post['buyVip']);                //获取聊币
            $buyType = intval($post['buyType']);            //1：普通vip 2：至尊vip
            $receiveUid = $post['receiveUid'];        //接收者的uid
            $smsCode = $post['smsCode'];              //手机验证码
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $result = $user->getUserItemsObject()->buyVip($data, $buyType, $receiveUid, $smsCode);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function getUserCars()
    {
        if($this->request->isGet()) {
            $userData = array();
            $result = $this->configMgr->getAllCarConfigList();
            if ($result['code'] == $this->status->getCode('OK')) {
                //登录过用户
                $user = $this->userAuth->getUser();
                if ($user) {
                    $userData = $user->getUserItemsObject()->getCarItemList();
                }else{
                    return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
                }

                return $this->status->mobileReturn($result['code'], $userData);
            }
        }

        return $this->proxyError();
    }

    //购买座驾
    public function buyCar(){

        if($this->request->isPost()){
            $post = $this->request->getPost();
            $carID = $post['carId'];                //获取座驾ID
            $receiveUid = $post['receiveUid'];        //接收者的uid
            $smsCode = $post['smsCode'];              //手机验证码
            $month = $post['buyTime'];                //购买月数
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $result = $user->getUserItemsObject()->buyCar($carID, $month, $receiveUid, $smsCode);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //座驾开关
    public function updateCarStatus(){
        if ($this->request->isPost()) {
            $status = $this->request->getPost('status');
            $itemId = $this->request->getPost('carId');
            $result = $this->userMgr->updateCarStatus($itemId, $status);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    //守护
    public function getUserGuard()
    {
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        if($this->request->isGet()) {
            $result = $user->getUserItemsObject()->getItemList(3);
            return $this->status->mobileReturn($this->status->getCode('OK'), $result['data']);
        }

        return $this->proxyError();
    }

    public function getUserBeGuard(){
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        if($this->request->isGet()) {
            $result = $user->getUserItemsObject()->getBeGuardedList();
            return $this->status->mobileReturn($this->status->getCode('OK'), $result);
        }

        return $this->proxyError();
    }

    //守护 === 购买
    public function buyGuard(){
        if($this->request->isPost()){
            $post = $this->request->getPost();
            $guardId = $post['guardId'];           //购买信息
            $type = $post['type'];          		 //类型
            $GuardedUid = $post['GuardedUid'];      //要守护的ID

            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->status->mobileReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $result = $user->getUserItemsObject()->buyGuard($guardId, $GuardedUid, $type);
            return $this->status->mobileReturn($result['code'], $result['data']);
        }

        return $this->proxyError();

    }
}