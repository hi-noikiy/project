<?php

namespace Micro\Controllers;
use Phalcon\DI\FactoryDefault;
use Micro\Models\Users;
class YipTestController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');  // use views/layouts/main.volt
        parent::initialize();
    }

    /**
     * 用户登录
     */
    public function loginAction(){
        if($this->request->isPost()){
            $username = $this->request->getPost('name');
            $password = $this->request->getPost('pwd');
            $result = $this->userAuth->userLogin($username, $password);
            if ($result['code'] == $this->status->getCode('OK')) {
                //$this->status->ajaxReturn($this->status->getCode('OK'), session_id());
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

    public function logoutAction(){
        $result = $this->userAuth->userLogout();
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }

        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    /**
     * 上传头像
     */

    public function uploadAvatarAction() {
        $result = $this->userInfo->uploadAvatar();
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'));
        }

        $this->status->ajaxReturn($result['code'], $result['data']);
    }


    /**
     * 修改昵称
     */

    public function changeNicknameAction(){
        if($this->request->isPost()){
            $nickName = $this->request->getPost('nickName');
            $result = $this->userInfo->updateNickName($nickName);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 修改密码
     */

    public function changePasswordAction(){
        if($this->request->isPost()){
            $oldPwd = $this->request->getPost('oldPwd');
            $newPwd = $this->request->getPost('newPwd');
            $result = $this->userAuth->changePassword($oldPwd, $newPwd);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 重置密码
     */

    public function resetPasswordAction(){
        if($this->request->isPost()){
            $newPwd = $this->request->getPost('newPwd');
            $result = $this->userAuth->userResetPwd($newPwd);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function checkUserNameAction(){
        if($this->request->isPost()){
            $name = $this->request->getPost('name');
            $result = $this->userMgr->checkUserName($name);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 发验证码接口
     *
     * @param $telephone
     */
    public function sendSmsVerifyAction(){
        if($this->request->isPost()){
            $telephone = $this->request->getPost('telephone');
            $result = $this->userAuth->sendSmsVerify($telephone);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 判断是否签约主播
     */

    public function isSignAnchorAction(){
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $result = $this->userInfo->isSignAnchor($uid);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     *判断是否签约家族主播
     */
    public function isSignFamilyAnchorAction(){
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $result = $this->userInfo->isSignFamilyAnchor($uid);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }

            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    /**
     * 获得家族消费
     */
    public function getFamilyConsumeAction(){

        $result = $this->familyMgr->getFamilyConsume(1);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }

        $this->status->ajaxReturn($result['code']);
    }


    /**
     * 获得主播消费
     */
    public function getAnchorConsumeAction(){

        $result = $this->familyMgr->getAnchorConsume(1);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }

        $this->status->ajaxReturn($result['code']);
    }

    /**
     * 获得消费排行
     */

    public function getConsumeRankAction(){
        $this->RankMgr->getFirstGiftRank();
    }
}