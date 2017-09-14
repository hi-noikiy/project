<?php

namespace Micro\Frameworks\Logic\Sign;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\Sign\SignData;

//签到接口
class SignMgr {

    protected $di;
    protected $userAuth;
    protected $taskData;
    protected $status;
    protected $config;
    protected $validator;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->userAuth = $this->di->get('userAuth');
        $this->status = $this->di->get('status');
        $this->signData = new SignData();
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
    }

    //查询用户签到完成情况
    public function getUserSign() {
        $user = $this->userAuth->getUser();
        $uid = 0;
        $user && $uid = $user->getUid();
        return $this->signData->getUserSignList($uid);
    }

    //用户签到
    public function setSign() {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        return $this->signData->setUserSign($user->getUid());
    }

    //用户领取签到奖励
    public function getSignReward($type,$roomId=0) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $uid = $user->getUid();
        //判断手机是否验证
        $userinfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid);
        /*if (!$userinfo->telephone) {
            return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
        }*/
        return $this->signData->getUserSignReward($uid, $type,$roomId);
    }

    //判断今日是否已签到
    public function getSignStatus() {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $uid = $user->getUid();
        $result = $this->signData->getOneSignStatus($uid);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //礼包配置
    public function editGiftPackage($id, $param) {
        return $this->signData->editGiftPackageConfig($id, $param);
    }

    //签到奖励配置
    public function editSignGift($id, $param) {
        return $this->signData->editSignGiftPackageConfig($id, $param);
    }

    public function setUserSignStatus($status){
        $postData['type'] = $status;
        $isValid = $this->validator->validate($postData);
        if (!$isValid || !in_array($status, array(0, 1))) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }

        $uid = $user->getUid();
        $userprofiles = \Micro\Models\UserProfiles::findfirst("uid=" . $uid);
        $userprofiles->isOpenSign = $status;
        $ret = $userprofiles->save();
        if($ret){
            return $this->status->retFromFramework($this->status->getCode('OK'), $ret);
        }

        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

}
