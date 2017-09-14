<?php

namespace Micro\Frameworks\Logic\User;

use Micro\Frameworks\Logic\User\UserData\UserInfo;
use Micro\Frameworks\Logic\User\UserData\UserItems;
use Micro\Frameworks\Logic\User\UserData\UserSecurity;
use Micro\Frameworks\Logic\User\UserData\UserConsume;
use Micro\Frameworks\Logic\User\UserData\UserFocus;
use Micro\Frameworks\Logic\User\UserData\UserApply;
use Micro\Frameworks\Logic\User\UserData\UserActivity;
use Micro\Frameworks\Logic\User\UserData\UserInformation;
use Micro\Frameworks\Logic\User\UserData\Management;

class User extends UserAbstract {

    protected $uid;
    protected $userInfo = null;
    protected $userItems = null;
    protected $userFocus = null;
    protected $userFans = null;
    protected $userApply = null;
    protected $userSecurity = null;
    protected $userConsume = null;  // 用户收入和支出对象
    protected $userActivity = null;  // 用户活动对象
    protected $userInformation = null;  // 用户通知对象
    protected $management = null;  // 用户通知对象

    public function __construct($uid) {
        $this->uid = $uid;
    }

    public function getUid() {
        return $this->uid;
    }

    public function getType() {
        try{
            $userData = \Micro\Models\Users::findFirst("uid='" . $this->uid . "'");
            return $userData->userType;
        }catch(\Exception $e){
            return 0;
        }
    }

    public function getUserInfoObject() {
        if ($this->userInfo == null) {
            $this->userInfo = new UserInfo($this->uid);
        }

        return $this->userInfo;
    }

    public function getUserItemsObject() {
        if ($this->userItems == null) {
            $this->userItems = new UserItems($this->uid);
        }

        return $this->userItems;
    }

    public function getUserFoucusObject() {
        if ($this->userFocus == null) {
            $this->userFocus = new UserFocus($this->uid, $this);
        }

        return $this->userFocus;
    }

    public function getUserFansObject() {
        
    }

    public function getUserApplyObject() {
        if ($this->userApply == null) {
            $this->userApply = new UserApply($this->uid);
        }

        return $this->userApply;
    }

    public function getUserSecurityObject() {

        if ($this->userSecurity == null) {
            $this->userSecurity = new UserSecurity($this->uid);
        }

        return $this->userSecurity;
    }

    public function getUserConsumeObject() {
        if ($this->userConsume == null) {
            $this->userConsume = new UserConsume($this->uid);
        }
        return $this->userConsume;
    }

    public function getUserActivityObject() {
        if ($this->userActivity == null) {
            $this->userActivity = new UserActivity($this->uid);
        }
        return $this->userActivity;
    }

    public function getUserInformationObject() {
        if ($this->userInformation == null) {
            $this->userInformation = new UserInformation($this->uid);
        }
        return $this->userInformation;
    }

    public function getManagementObject() {
        if ($this->management == null) {
            $this->management = new Management($this->uid);
        }
        return $this->management;
    }

    public static function getRobotData($uid) {
        try{
            $data = \Micro\Models\UserRobot::findFirst("uid='" . $uid . "'");
            if (empty($data)) {
                return array();
            }
            $result = $data->toArray();
        }catch (\Exception $e) {
            return false;
        }
        return $result;
    }
}
