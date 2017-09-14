<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Micro\Frameworks\Logic\User\UserFactory;
use Phalcon\DI\FactoryDefault;

class UserInfo extends UserDataBase {

    protected $request;
    protected $storage;
    protected $taskMgr;

    public function __construct($uid) {
        parent::__construct($uid);

        // 该两个请求看下是否是放在这里合适？
        $this->request = $this->di->get('request');
        $this->storage = $this->di->get('storage');

        $this->taskMgr = $this->di->get('taskMgr');
    }

    // 从数据库中获得用户所有信息
    public function getData() {
        $accountInfo = $this->getUserAccountInfo();
        $userInfo = $this->getUserInfo();
        $userInfo = array_merge($accountInfo, $userInfo);
        $userProfiles = $this->getUserProfiles();
        $newUserInfo = array_merge($userInfo, $userProfiles);
        return $newUserInfo;
    }

    public function getUserAccountInfo() {
        $userData = \Micro\Models\Users::findFirst($this->uid);
        if (empty($userData)) {
            $this->errLog('getUserAccountInfo error : uid = ' . $this->uid);
            return array();
        }
        //edit by 2015/07/24 将uid赋值给accountId
        $result=$userData->toArray();
        $result['accountId']=(string)$result['uid'];
        return $result;
    }

    public function getUserInfo() {
        $userData = \Micro\Models\UserInfo::findFirst($this->uid);
        if (empty($userData)) {
            $this->errLog('getUserInfo error : uid = ' . $this->uid);
            return array();
        }

        $userData = $userData->toArray();

        if (empty($userData['avatar'])/* || ($userData['avatar'] == null) */) {
            $userData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
        }

        if (empty($userData['nickName'])) {
            $userData['nickName'] = $this->uid;
        }

        return $userData;
    }

    public function getUserVipExpireTime(){
        $userData = \Micro\Models\UserProfiles::findFirst($this->uid);
        if (empty($userData)) {
            return 0;
        }

        $expireTime = 0;
        if ($userData->level6 > 0 && $userData->vipExpireTime2 > time()) {//至尊vip
            $expireTime = $userData->vipExpireTime2;
        }

        if ($userData->level1 > 0 && $userData->vipExpireTime > time()) {//普通vip
            if($userData->vipExpireTime > $expireTime){
                $expireTime = $userData->vipExpireTime;
            }
        }

        return $expireTime;
    }

    public function getUserProfiles() {
        $userData = \Micro\Models\UserProfiles::findFirst($this->uid);
        if (empty($userData)) {
            $this->errLog('getUserProfile error : uid = ' . $this->uid);
            return array();
        }

        $resultArray = array();
        $resultArray['coin'] = $userData->coin;
        $resultArray['cash'] = $userData->cash;
        $resultArray['money'] = $userData->money;
        $resultArray['vipExp'] = $userData->exp1;
        $resultArray['anchorExp'] = $userData->exp2;
        $resultArray['richerExp'] = $userData->exp3;
        $resultArray['fansExp'] = $userData->exp4;
        $resultArray['charmExp'] = $userData->exp5;
        // $resultArray['vipLevel'] = $userData->level1 ;
        $resultArray['anchorLevel'] = $userData->level2;
        $resultArray['richerLevel'] = $userData->level3;
        $resultArray['fansLevel'] = $userData->level4;
        $resultArray['charmLevel'] = $userData->level5;
        $resultArray['isOpenSign'] = $userData->isOpenSign;
        $resultArray['questionId'] = $userData->questionId;
        $resultArray['points'] = $userData->points;
//        $resultArray['answer'] = $userData->answer;

        if ($userData->level6 > 0 && $userData->vipExpireTime2 > time()) {//至尊vip
            $resultArray['vipLevel'] = 2;
            $resultArray['vipExpireTime'] = $userData->vipExpireTime2;
        } elseif ($userData->level1 > 0 && $userData->vipExpireTime > time()) {//普通vip
            $resultArray['vipLevel'] = 1;
            $resultArray['vipExpireTime'] = $userData->vipExpireTime;
        } else {
            $resultArray['vipLevel'] = 0;
            $resultArray['vipExpireTime'] = 0;
        }

        return $resultArray;
    }

    // 限于用单数据获取的接口
    public function getRicherLevel() {
        //try {
        $parameters = array(
            "uid" => $this->uid,
        );
        $userProfiles = \Micro\Models\UserProfiles::findFirst(array(
                    "uid = :uid:",
                    "bind" => $parameters,
                    "columns" => "level3"
        ));

        if (empty($userProfiles)) {
            $this->errLog('getRicherLevel error uid=' . $this->uid . ' data not exist ');
            return 0;
        }

        return $userProfiles->level3;
        /* }
          catch (\Exception $e) {
          $this->errLog('getRicherLevel error uid='.$uid.' errorMessage = '.$e->getMessage());
          return 0;
          } */
    }

    /**
     * 获得昵称，如果不存在则显示用户名称
     *
     * @param $uid 用户id
     */
    public function getNickName() {
        $parameters = array(
            "uid" => $this->uid,
        );
        $userInfo = \Micro\Models\UserInfo::findFirst(array(
                    "uid = :uid:",
                    "bind" => $parameters,
                    "columns" => "nickName"
        ));

        if ($userInfo) {
            if (!empty($userInfo->nickName)) {
                return $userInfo->nickName;
            }
        }

        return $this->uid;
    }

    /**
     * 获取用户的vip等级,如果过期，则返回0
     */
    public function getVipLevel() {
        $parameters = array(
            "uid" => $this->uid,
        );
        $userProfiles = \Micro\Models\UserProfiles::findFirst(array(
                    "uid = :uid:",
                    "bind" => $parameters
        ));

        if (empty($userProfiles)) {
            $this->errLog('getVipLevel error : uid = ' . $this->uid);
            return 0;
        }

        $resultArray = $userProfiles->toArray();
//        $vipLevel = $resultArray['level1'];
//        //注意这里如果有屏蔽，那么在房间操作对vip等级的判断，调用的是这个接口，可能会出现问题
//        if ($resultArray['vipExpireTime'] < time()) { //vip过期
//            $vipLevel = 0;
//        }
        if ($resultArray['level6'] > 0 && $resultArray['vipExpireTime2'] > time()) {//至尊vip
            $vipLevel = 2;
        } elseif ($resultArray['level1'] > 0 && $resultArray['vipExpireTime'] > time()) {//普通vip
            $vipLevel = 1;
        } else {
            $vipLevel = 0;
        }

        return $vipLevel;
    }

    /**
     * 获取用户的vip等级,如果过期，则返回0
     */
    public function getVipInfo() {
        $parameters = array(
            "uid" => $this->uid,
        );
        $userProfiles = \Micro\Models\UserProfiles::findFirst(array(
                    "uid = :uid:",
                    "bind" => $parameters
        ));

        $vipArr = array(
            'vip2' => 0,
            'vip1' => 0
        );

        if (empty($userProfiles)) {
            $this->errLog('getVipLevel error : uid = ' . $this->uid);
            return 0;
        }

        $resultArray = $userProfiles->toArray();
        if ($resultArray['level6'] > 0 && $resultArray['vipExpireTime2'] > time()) {//至尊vip
            $vipArr['vip2'] = 1;
        }
        if ($resultArray['level1'] > 0 && $resultArray['vipExpireTime'] > time()) {//普通vip
            $vipArr['vip1'] = 1;
        }

        return $vipArr;
    }

    /**
     * 获取用户的AccountId
     */
    public function getAccountId() {
        
        return (string)$this->uid;
        
       /** $parameters = array(
            "uid" => $this->uid,
        );
        $user = \Micro\Models\Users::findFirst(array(
                    "uid = :uid:",
                    "bind" => $parameters,
                    "columns" => "accountId"
        ));
        if (empty($user)) {
            $this->errLog('getAccountId error : uid = ' . $this->uid);
            return 0;
        }

        return $user['accountId'];
        * *
        */
    }

    /**
     * 上传头像
     */
    public function uploadAvatar() {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles()) {
                // 自身业务的验证
                $userdata = $this->session->get($this->config->websiteinfo->authkey);
                $uid = $userdata['uid'];
                if (empty($uid)) {
                    return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
                }

                try {
                    foreach ($this->request->getUploadedFiles() as $file) {
                        $fileNameArray = explode('.', strtolower($file->getName()));
                        $fileExt = $fileNameArray[count($fileNameArray) - 1];
                        //$fileExt = substr($file->getName(), -4);
                        $filePath = $this->pathGenerator->getAvatarPath($uid);
                        $fileName = time() . '.' . $fileExt;
                        // $fileName = '0.' . $fileExt;
                        $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                        try {
                            $avatar = $this->pathGenerator->getFullAvatarPath($uid, $fileName);
                            $this->setAvatarPath($avatar);// . '?v=' . time()
                            return $this->status->retFromFramework($this->status->getCode('OK'));
                        } catch (\Exception $e) {
                            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
            }
        }
        return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
    }

    /**
     * 修改签名
     *
     * @param $nickName
     * @return mixed
     *
     */
    public function updateSignature($signature) {
        $postData['signature'] = $signature;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $userdata = $this->session->get($this->config->websiteinfo->authkey);
        $uid = $userdata['uid'];
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $user = \Micro\Models\UserInfo::findFirst($uid);
        $user->signature = $signature;
        $result = $user->save();
        if ($result) {//修改成功
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }

        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    /*
     * 修改昵称
     */

    public function updateNickName($nickName) {
        $postData['nickname'] = $nickName;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        if (!empty($nickName)) {
            // 检测昵称是否合法
            if (mb_strlen($nickName) > 10 || mb_strlen($nickName) < 2) {
                return $this->status->retFromFramework($this->status->getCode('USERNAME_LENGTH_ERROR'));
            }

            if (is_numeric($nickName)) {
                return $this->status->retFromFramework($this->status->getCode('NICKNAME_ALL_NUMBER'));
            }

            if (empty(trim($nickName))) {
                return $this->status->retFromFramework($this->status->getCode('NICKNAME_ALL_SPACE'));
            }
        }

        try{
        // 判断昵称是否存在
        $sql = "select * from pre_user_info where binary nickName  = '{$nickName}'";
        $connection = $this->di->get('db');
        $nicknameResult = $connection->fetchOne($sql);

        if ($nicknameResult) {
            return $this->status->retFromFramework($this->status->getCode('NICKNAME_HAS_EXISTS'));
        }

        $uid = $this->uid;
            $user = \Micro\Models\UserInfo::findFirst($uid);
            $user->nickName = $nickName;
            $user->save();

            //判断是否符合新手任务
            // $this->taskMgr->setUserTask($uid, $this->config->taskIds->editNickName);
            //重新下发nodejsdata
            // 查询用户在哪些房间
            $roomBase = new \Micro\Frameworks\Logic\Room\RoomBase();
            $userRoom = $roomBase->getUsersWhereIn($uid);
            if ($userRoom) {
                $newuser = UserFactory::getInstance($uid);
                $nodejsUserData = array();
                $userInfo = $newuser->getUserInfoObject()->getData();
                // 昵称、头像
                $nodejsUserData['userId'] = $uid;
                $nodejsUserData['name'] = $userInfo['nickName'];
                $nodejsUserData['avatar'] = $userInfo['avatar'];
                $accountId = $userInfo['accountId'];

                //是否超级管理员
                $nodejsUserData['manageType'] = $userInfo['manageType'];

                // 进入房间的用户的主播富豪等级、VIP等级
                $nodejsUserData['vipLevel'] = $userInfo['vipLevel'];
                if ($userInfo['vipExpireTime'] < time()) { //vip过期
                    $nodejsUserData['vipLevel'] = 0;
                }
                $nodejsUserData['anchorLevel'] = $userInfo['anchorLevel'];
                $nodejsUserData['richerLevel'] = $userInfo['richerLevel'];
                //平台信息
                $nodejsUserData['platform'] = $this->di->get('roomModule')->getRoomOperObject()->getPlatform();

                // 座驾信息
                $carInfo = $newuser->getUserItemsObject()->getActiveCarData();
                if ($carInfo) {
                    $nodejsUserData['carInfo'] = $carInfo;
                }
                foreach ($userRoom as $val) {
                    // 获取是否禁言状态
                    $nodejsUserData['isForbid'] = $roomBase->checkUserIsForbidden($val['roomid'], $uid);
                    // 获取守护信息
                    $roomData = \Micro\Models\Rooms::findfirst($val['roomid']);
                    /*$guardData = $newuser->getUserItemsObject()->getGuardData($roomData->uid);
                    if ($guardData != NULL) {
                        $nodejsUserData['guardLevel'] = $guardData['level'];
                    } else {
                        $nodejsUserData['guardLevel'] = '';
                    }*/
                    $nodejsUserData['guardLevel'] = $newuser->getUserItemsObject()->getGuardLevel($uid, $roomData->uid);
                    //用户家族信息
                    $nodejsUserData['isFamilyLeader'] = $this->di->get('userMgr')->checkUserIsHeader($roomData->uid, $uid);
                    //查询用户属于哪个军团
                    $groupres = $this->di->get('groupMgr')->checkUserGroup($uid);
                    if ($groupres['code'] == $this->status->getCode('OK') && $groupres['data']) {
                        $nodejsUserData['group'] = $groupres['data'];
                    }

                    $this->comm->updateUserData($val['roomid'], $accountId, json_encode($nodejsUserData));
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 绑定手机
     * */

    public function bindPhone($smsCode) {
        $telephone = $this->session->get($this->config->websiteinfo->userbindphonekey);
        $smsCode_right = $this->session->get($this->config->websiteinfo->smscodekey);
        if (empty($telephone)) {
            return $this->status->retFromFramework($this->status->getCode('PROXY_ERROR'));
        }
        if ($smsCode_right != $smsCode) {
            return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
        }

        if (!$this->userAuth->getSessionData()) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $postData['telephone'] = $telephone;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 判断手机是否绑定过
        $parameters = array(
            "telephone" => $this->validator->qs($telephone),
        );
        $count = \Micro\Models\UserInfo::count(array(
                    "conditions" => "telephone = :telephone:",
                    "bind" => $parameters,
        ));
        if (0 < $count) {
            return $this->status->retFromFramework($this->status->getCode('TELEPHONE_HAS_EXISTS'));
        }

        $userdata = $this->session->get($this->config->websiteinfo->authkey);
        $uid = $userdata['uid'];

        $user = \Micro\Models\UserInfo::findFirst($uid);
        $user->telephone = $telephone;
        $user->save();

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /**
     * 判断是否签约主播
     */
    public function isSignAnchorNew($uid) {
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'), '');
        }

        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $parameters = array(
            "uid" => $uid,
            "status1" => $this->config->signAnchorStatus->apply,
            "status2" => $this->config->signAnchorStatus->refuse,
            "status3" => $this->config->signAnchorStatus->unbind,
        );
        $anchor = \Micro\Models\SignAnchor::findFirst(array(
                    "conditions" => "uid = :uid: AND status != :status1: AND status != :status2: AND status != :status3:",
                    "bind" => $parameters,
        ));
        return $anchor ? 1 : 0;
    }

    /**
     * 判断是否签约主播
     */
    public function isSignAnchor($uid) {
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'), '');
        }

        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $parameters = array(
            "uid" => $uid,
            "status1" => $this->config->signAnchorStatus->apply,
            "status2" => $this->config->signAnchorStatus->refuse,
            "status3" => $this->config->signAnchorStatus->unbind,
        );
        $anchor = \Micro\Models\SignAnchor::findFirst(array(
                    "conditions" => "uid = :uid: AND familyId = 0 AND status != :status1: AND status != :status2: AND status != :status3:",
                    "bind" => $parameters,
        ));
        return $anchor ? TRUE : FALSE;
    }

    /**
     * 判断是否签约主播审核中
     */
    public function isSignAnchoringNew($uid) {
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'), '');
        }

        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $parameters = array(
            "uid" => $uid,
            "status" => $this->config->signAnchorStatus->apply,
        );
        $anchor = \Micro\Models\SignAnchor::findFirst(array(
                    "conditions" => "uid = :uid: AND status = :status:",
                    "bind" => $parameters,
        ));
        return $anchor ? 1 : 0;
    }

    /**
     * 判断是否签约主播审核中
     */
    public function isSignAnchoring($uid) {
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'), '');
        }

        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $parameters = array(
            "uid" => $uid,
            "status" => $this->config->signAnchorStatus->apply,
        );
        $anchor = \Micro\Models\SignAnchor::findFirst(array(
                    "conditions" => "uid = :uid: AND familyId = 0 AND status = :status:",
                    "bind" => $parameters,
        ));
        return $anchor ? TRUE : FALSE;
    }

    /**
     * 判断是否签约家族主播
     */
    public function isSignFamilyAnchor($uid) {
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $parameters = array(
            "uid" => $uid,
        );
        $anchor = \Micro\Models\SignAnchor::findFirst(array(
                    "conditions" => "uid = :uid: AND familyId > 0",
                    "bind" => $parameters,
        ));
        return $anchor ? TRUE : FALSE;
    }

    /**
     * 判断是否签约家族主播审核中
     */
    public function isSignFamilyAnchoring($uid) {
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $parameters = array(
            "uid" => $uid,
            "status" => $this->config->applyStatus->ing,
            "type" => $this->config->applyStatus->family,
        );
        $anchor = \Micro\Models\ApplyLog::findFirst(array(
                    "conditions" => "uid = :uid: AND status = :status: AND type = :type:",
                    "bind" => $parameters,
        ));
        return $anchor ? TRUE : FALSE;
    }

    /**
     * 账户是否在冻结状态
     */
    public function isFrozen($uid) {
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $parameters = array(
            "uid" => $uid,
            "status" => $this->config->signAnchorStatus->forzen,
        );
        $anchor = \Micro\Models\SignAnchor::findFirst(array(
                    "conditions" => "uid = :uid: AND status = :status:",
                    "bind" => $parameters,
        ));
        return $anchor ? TRUE : FALSE;
    }

    /**
     * 冻结账户
     */
    public function accountFrozen($uid) {
        if (empty($uid)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $parameters = array(
            "uid" => $uid,
            "status1" => $this->config->signAnchorStatus->apply,
            "status2" => $this->config->signAnchorStatus->refuse,
        );
        $anchor = \Micro\Models\SignAnchor::findFirst(array(
                    "conditions" => "uid = :uid: AND status != :status1: AND status != :status2:",
                    "bind" => $parameters,
        ));
        if (empty($anchor)) {
            return $this->status->retFromFramework($this->status->getCode('HAS_FROZEN_OR_NOT_SIGN'));
        }

        $anchor->status = $this->config->signAnchorStatus->forzen;
        $anchor->save();
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * 获取用户照片
     */

    public function getUserPhoto($type) {
        try {
            $parameters = array(
                "uid" => $this->uid,
                "type" => $type,
            );
            $list = \Micro\Models\UserPhoto::find(array(
                        "conditions" => "uid = :uid: AND type = :type:",
                        "bind" => $parameters,
            ));
            $return = array();
            if ($list->valid()) {
                foreach ($list as $val) {
                    $data['photoUrl'] = $val->photoUrl;
                    $data['id'] = $val->id;
                    array_push($return, $data);
                }
            }
            return $return;
        } catch (\Exception $e) {
            $this->errLog('getUserPhoto error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

    //获取默认头像
    function getAllFiles($dir = './', $fileExp = array('jpg', 'png', 'gif')) {
        if (!is_dir($dir))
            return false;
        $allFiles = array();
        foreach ($fileExp as $k => $exp) {
            $files = glob($dir . '*.' . $exp);
            $allFiles = array_merge($allFiles, $files);
        }
        $data = array();
        foreach ($allFiles as $k => $v) {
            $data[$k]['img'] = $v;
            $data[$k]['id'] = $k;
        }
        return $data;
    }

    //设头像为默认值
    public function setAvatarPath($facePath) {
        if (empty($facePath)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $parameters = array(
            "uid" => $this->uid,
        );
        $userInfo = \Micro\Models\UserInfo::findFirst(array(
                    "conditions" => "uid = :uid:",
                    "bind" => $parameters,
        ));
        if (!$userInfo) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

//        $userInfo->avatar = $facePath;
//        $result = $userInfo->save();
        // 暂时用原生的写
        $sql = "update pre_user_info set avatar='".$facePath."' where uid=".$this->uid;
        $connection = $this->di->get('db');
        $timeResult = $connection->execute($sql);
//        if ($result) {//修改成功，判断是否符合新手任务
//            $taskMgr = $this->di->get('taskMgr');
//            $taskMgr->setUserTask($this->uid, $this->config->taskIds->editAvtar);
//        }
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /**
     * 修改个性签名
     *
     * @param $signature
     * @return mixed
     */
    public function updateUserSignature($signature) {
        if (empty($signature)) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        $postData['signature'] = $signature;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $parameters = array(
            "uid" => $this->uid,
        );

        $userInfo = \Micro\Models\UserInfo::findFirst(array(
                    "conditions" => "uid = :uid:",
                    "bind" => $parameters,
        ));

        if (!$userInfo) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        $userInfo->signature = $signature;
        $result = $userInfo->save();
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //获取用户vip
    public function getUserVip() {
        try {
            $return['vipLevel1'] = 0;
            $return['vipLevel2'] = 0;
            if (!$this->uid) {
                return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            }
            $parameters = array(
                "uid" => $this->uid,
            );
            $info = \Micro\Models\UserProfiles::findfirst(array(
                        "conditions" => "uid = :uid:",
                        "bind" => $parameters,
            ));

            if ($info->level1 && $info->vipExpireTime > time()) {//普通vip
                $return['vipLevel1'] = 1;
                $return['vipExpireTime1'] = $info->vipExpireTime;
            }
            if ($info->level6 && $info->vipExpireTime2 > time()) {//至尊vip
                $return['vipLevel2'] = 1;
                $return['vipExpireTime2'] = $info->vipExpireTime2;
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取用户vip NEW【20151106】
    public function getUserVipNew() {
        try {
            $return = array();
            if (!$this->uid) {
                return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            }

            $parameters = array(
                "uid" => $this->uid,
            );
            $info = \Micro\Models\UserProfiles::findfirst(array(
                "conditions" => "uid = :uid:",
                "bind" => $parameters,
            ));

            $res = $this->di->get('configMgr')->getVipByLevel();
            if($res['code'] == $this->status->getCode('OK')){
                $rights = $res['data'];
            }

            $buyVipConfig = $this->config->buyVipConfig->toArray();
            $vipRenewConfig = $this->config->wordAndColor->vip->renew->toArray();

            if ($info->level6 && $info->vipExpireTime2 > time()) {//至尊vip
                $tmp = array();
                $tmp['level'] = 2;
                $tmp['vipName'] = '至尊VIP';
                $tmp['expireTime'] = $info->vipExpireTime2 ? ('有效期至' . date('Y年m月d日',$info->vipExpireTime2)) : '无';
                $tmp['buyConfig'] = $buyVipConfig[2];
                $tmp['rightConfig'] = $rights[2]['rights'];
                $tmp['bigImg'] = $rights[2]['bigImg'];
                $tmp['wordColor'] = $vipRenewConfig[2];
                // $return[] = $tmp;
                array_push($return, $tmp);
                unset($tmp);

                // $return['vipLevel2'] = 1;
                // $return['vipExpireTime2'] = $info->vipExpireTime2;
            }
            if ($info->level1 && $info->vipExpireTime > time()) {//普通vip
                $tmp = array();
                $tmp['level'] = 1;
                $tmp['vipName'] = '普通VIP';
                $tmp['expireTime'] = $info->vipExpireTime ? ('有效期至' . date('Y年m月d日',$info->vipExpireTime)) : '无';
                $tmp['buyConfig'] = $buyVipConfig[1];
                $tmp['rightConfig'] = $rights[1]['rights'];
                $tmp['bigImg'] = $rights[1]['bigImg'];
                $tmp['wordColor'] = $vipRenewConfig[1];
                // $return[] = $tmp;
                array_push($return, $tmp);
                unset($tmp);
            }
            

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取用户渠道来源
    public function getUserSource() {
        $return['ns_source'] = '';
        $return['utm_medium'] = '';
        $parameters = array(
            "uid" => $this->uid,
        );
        $info = \Micro\Models\RegisterLog::findfirst(array(
                    "conditions" => "uid = :uid:",
                    "bind" => $parameters,
        ));
        if ($info != false) {
            $mt_source = $info->parentType;
            if ($mt_source) {
                if (!in_array($mt_source, $this->config->mt_source->toArray())) {//查询渠道值是否正确
                    $mt_source = '';
                }
            }
            $return['ns_source'] = $mt_source;
            $return['utm_medium'] = $info->subType;
        }
        return $return;
    }
    
    //获取用户今天手机验证码已发送次数 add by 2015/08/24
    public function getUserSmsSendCount($types = '') {
        try {
            $exp = '';
            $today = strtotime(date("Y-m-d"));
            $uid = $this->uid;
            if ($types) {
                if (strpos($types, ',')) {
                    $exp = " and type in (" . $types . ")";
                } else {
                    $exp = " and type=" . $types;
                }
            }
            $count = \Micro\Models\SmsLog::count("uid=" . $uid . " and createTime>=" . $today . $exp);
            return $count;
        } catch (\Exception $e) {
            $this->errLog('getUserSmsSendCount error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

    //获取用户基本信息
    public function getUserBasicInfo(){
        try {
            $sql = 'select ui.avatar,up.uid,up.level6,up.vipExpireTime2,up.level1,up.vipExpireTime,up.cash,up.coin,up.points,up.level2 as anchorLevel,up.exp2 as anchorExp,up.level3 as richerLevel,up.exp3 as richerExp,ui.nickName'
                . ' from \Micro\Models\UserProfiles as up left join \Micro\Models\UserInfo as ui on ui.uid = up.uid '
                . ' where up.uid = ' . $this->uid;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $userBasicInfo = array();
            if($res->valid()){
                $userBasicInfo = $res->toArray()[0];
                $userBasicInfo['vipLevel'] = 0;
                if($userBasicInfo['level6'] > 0 && $userBasicInfo['vipExpireTime2'] > time()){
                    $userBasicInfo['vipLevel'] = 2;
                }else if($userBasicInfo['level1'] > 0 && $userBasicInfo['vipExpireTime'] > time()){
                    $userBasicInfo['vipLevel'] = 1;
                }
                unset($userBasicInfo['level6']);
                unset($userBasicInfo['level1']);
                unset($userBasicInfo['vipExpireTime2']);
                unset($userBasicInfo['vipExpireTime']);

                !$userBasicInfo['avatar'] && $userBasicInfo['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();

                //获取富豪等级信息
                $richerRes = \Micro\Models\RicherConfigs::findFirst('level = ' . $userBasicInfo['richerLevel']);
                $userBasicInfo['richerLevelHigher'] = $richerRes->higher + 1;
                $userBasicInfo['richerLevelLower'] = $richerRes->lower;

                $richerNextRes = \Micro\Models\RicherConfigs::findFirst('level = ' . ($userBasicInfo['richerLevel'] + 1));
                if($richerNextRes){
                    $userBasicInfo['nextRicherLevel'] = $richerNextRes->level;
                }else{
                    $userBasicInfo['nextRicherLevel'] = $userBasicInfo['richerLevel'];
                }

                //获取富豪等级信息
                $anchorRes = \Micro\Models\AnchorConfigs::findFirst('level = ' . $userBasicInfo['anchorLevel']);
                $userBasicInfo['anchorLevelHigher'] = $anchorRes->higher + 1;
                $userBasicInfo['anchorLevelLower'] = $anchorRes->lower;

                $anchorNextRes = \Micro\Models\AnchorConfigs::findFirst('level = ' . ($userBasicInfo['anchorLevel'] + 1));
                if($anchorNextRes){
                    $userBasicInfo['nextAnchorLevel'] = $anchorNextRes->level;
                }else{
                    $userBasicInfo['nextAnchorLevel'] = $userBasicInfo['anchorLevel'];
                }

                $result = $this->di->get('userMgr')->isHasUnRead();
                $userBasicInfo['news'] = 0;
                if($result['code'] == $this->status->getCode('OK')){
                    $userBasicInfo['news'] = $result['data'];
                }
                
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $userBasicInfo);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
