<?php

namespace Micro\Frameworks\Logic\Dynamics;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\Dynamics\UserDynamics;
// 动态
class DynamicsMgr {

    protected $di;
    protected $userAuth;
    protected $status;
    protected $config;
    protected $validator;
    protected $session;
    protected $dynamics;
    protected $userMgr;
    protected $request;
    protected $storage;
    protected $pathGenerator;
    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->userAuth = $this->di->get('userAuth');
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->session = $this->di->get('session');
        $this->userMgr = $this->di->get('userMgr');
        $this->request = $this->di->get('request');
        $this->storage = $this->di->get('storage');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->dynamics = new UserDynamics();
    }

    /**
     * 添加动态
     *
     * @param $uid
     * @param $pid
     * @param $content
     * @param $forward
     * @param $reply
     * @param $praise
     * @param $praiseList
     * @param $forwardList
     * @param $picList
     * @param $pos
     * @param $addtime
     * @return mixed
     */
    public function addDynamics($pid, $content, $forward, $reply, $praise, $praiseList, $forwardList , $picList, $pos, $addtime){
//        $postData['content'] = $content;
//        $isValid = $this->validator->validate($postData);
//        if (!$isValid) {
//            $errorMsg = $this->validator->getLastError();
//            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
//        }

        //登录验证
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        $timePre = date('YmdHis',time());
        $picRes = $this->uploadDynamicsPic($timePre);
        if($picRes['code'] == $this->status->getCode('OK')){
            $picList = $picRes['data'];
        }else{
            $picList = array();
        }

        try {
            $result = $this->dynamics->addDynamics($uid, $pid, $content, $forward, $reply, $praise, $praiseList, $forwardList , $picList, $pos, $addtime);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->execDynamicsHotPoint($result['data']); // 计算热值
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 上传图片
     *
     * @param string $timePre
     * @return mixed
     */
    public function uploadDynamicsPic($timePre = ''){
        if ($this->request->hasFiles()) {
            // 自身业务的验证
            $userdata = $this->session->get($this->config->websiteinfo->authkey);
            $uid = $userdata['uid'];
            try {
                foreach ($this->request->getUploadedFiles() as $key => $file) {
                    $fileNameArray = explode('.', strtolower($file->getName()));
                    $fileExt = $fileNameArray[count($fileNameArray) - 1];
                    $dirName = $timePre . "_".$uid;

                    $filePath = $this->pathGenerator->getDynamicsPath($dirName);
                    $fileName = $timePre .  "_pic{$key}." . $fileExt;
                    $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                    try {
                        $picList[] = $this->pathGenerator->getFullDynamicsPath($dirName, $fileName);
                    } catch (\Exception $e) {
                        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
                    }
                }

                return $this->status->retFromFramework($this->status->getCode('OK'), $picList);
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), $e->getMessage());
            }
        } else {
            return $this->status->retFromFramework($this->status->getCode('UPLOADFILE_ERROR'));
        }
    }

    /**
     * 获得动态列表
     *
     * @param $uid 动态信息
     * @param $type 类型，1为我关注的人，2为热门
     * @return mixed
     */
    public function getDynamicsList($uid, $type){
        $uidList = array();
        $original = 0;
        $timeStart = 0;
        $timeEnd = 0;
        switch($type){
            case 1:
                if($uid > 0){
                    $user=UserFactory::getInstance($uid);
                    if ($user == NULL) {
                        return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
                    }
                }else{
                    //登录验证
                    $user = $this->userAuth->getUser();
                    if ($user == NULL) {
                        return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
                    }
                }

                // 1 我关注的人，获得关注uid列表
                $result = $user->getUserFoucusObject()->getOwnFollowList(0, 0, '', '');
                $uidList = $result['data'];
                if(empty($uidList)){
                    return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
                }

                break;
            case 2:
                // 2 热门
                $original = 1;
                $timeStart = strtotime(date('Y-m-d'));
                $timeEnd = strtotime('+1 day', $timeStart);
                break;
            default:
                // 全部的人

                break;
        }

        try {
            $result = $this->dynamics->getDynamicsList($uidList, $original, $timeStart, $timeEnd);
            if ($result['code'] == $this->status->getCode('OK')) {
                if($result['data']){
                    foreach($result['data'] as &$val){
                        $uid = $val['uid'];
                        $val['_id'] = $val['_id']->{'$id'};
                        $val['userInfo'] = $this->userMgr->getMobileUserInfo($uid)['data'];
                    }
                }

                return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 回复动态
     *
     * @param $did
     * @param $toUid
     * @param $content
     * @param $pos
     * @param $addtime
     * @return mixed
     */
    public function replyDynamics($did, $toUid, $content, $pos, $addtime){
        //登录验证
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        // 获得当前动态数据
        $dynamicsInfo = $this->dynamics->getDynamicsInfo($did);
        if(!$dynamicsInfo['data']){
            return $this->status->retFromFramework($this->status->getCode('NOT_EXIST_DYNAMICS'));
        }

        try {
            $result = $this->dynamics->replyDynamics($uid, $did, $toUid, $content, $pos, $addtime);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->execDynamicsHotPoint($did); // 计算热值
                return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得动态回复列表
     *
     * @param $did
     * @return mixed
     */
    public function getDynamicsReply($did){
        //登录验证
        try {
            $result = $this->dynamics->getDynamicsReply($did);
            if ($result['code'] == $this->status->getCode('OK')) {
                return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得动态赞列表
     *
     * @param $did
     * @return mixed
     */
    public function getDynamicsPraise($did){
        //登录验证
        try {
            $result = $this->dynamics->getDynamicsPraise($did);
            if($result['code'] == $this->status->getCode('OK') && !empty($result['data'])){
                foreach($result['data'] as &$val){
                    // 获得评论的人用户信息
                    $val['userInfo'] = $this->userMgr->getMobileUserInfo($val['uid'])['data'];
                }
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得动态转发列表
     *
     * @param $did
     * @return mixed
     */
    public function getDynamicsForwards($did){
        //登录验证
        try {
            $result = $this->dynamics->getDynamicsForwards($did);
            if($result['code'] == $this->status->getCode('OK') && !empty($result['data'])){
                foreach($result['data'] as &$val){
                    // 获得评论的人用户信息
                    $val['userInfo'] = $this->userMgr->getMobileUserInfo($val['uid'])['data'];
                }
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 赞动态
     *
     * @param $did
     * @return mixed
     */
    public function praiseDynamics($did){
        //登录验证
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        $uid = $user->getUid();
        // 获得当前动态数据
        $dynamicsInfo = $this->dynamics->getDynamicsInfo($did);
        if(!$dynamicsInfo['data']){
            return $this->status->retFromFramework($this->status->getCode('NOT_EXIST_DYNAMICS'));
        }

        try {
            $result = $this->dynamics->praiseDynamics($uid, $did);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->execDynamicsHotPoint($did); // 计算热值
                return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    /**
     * 转发动态
     *
     * @param $did
     * @param $pos
     * @param $content
     * @return mixed
     */
    public function forwardDynamics($did, $pos, $content){
        //登录验证
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        // 获得当前动态数据
        $dynamicsInfo = $this->dynamics->getDynamicsInfo($did);
        if(!$dynamicsInfo['data']){
            return $this->status->retFromFramework($this->status->getCode('NOT_EXIST_DYNAMICS'));
        }

        $uid = $user->getUid();
        try {
            $result = $this->dynamics->forwardDynamics($uid, $did, $content, $pos);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->execDynamicsHotPoint($did); // 计算热值
                return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
            }

            return $this->status->retFromFramework($result['code'], $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 计算动态热值
     *
     * @param $did
     */
    public function execDynamicsHotPoint($did){
        // 获得最高统计
        try {
            $maxReply = $this->dynamics->getDynamicsCount('forward')['data'];
            $maxPraise = $this->dynamics->getDynamicsCount('praise')['data'];
            $maxForward = $this->dynamics->getDynamicsCount('forward')['data'];
            // 获得当前动态数据
            $dynamicsInfo = $this->dynamics->getDynamicsInfo($did);
            if($dynamicsInfo['data']){
                $addtime = isset($dynamicsInfo['data']['addtime']) ? $dynamicsInfo['data']['addtime'] : time();
            }else{
                return $this->status->retFromFramework($this->status->getCode('NOT_EXIST_DYNAMICS'));
            }

            $addtime = $addtime ? $addtime : time();
            // 获得发布时间距离当前的小时数（向上取整）
            $defHours = ceil((time() - $addtime) / 3600);
            // 计算获得热值
            $baseReplyHot = $maxReply ? (100 / $maxReply * $dynamicsInfo['data']['reply']) : 0;
            $baseForwardHot = $maxForward ? (100 / $maxForward * $dynamicsInfo['data']['forward']) : 0;
            $basePraiseHot = $maxPraise ? (100 / $maxPraise * $dynamicsInfo['data']['praise']) : 0;
            $baseTimeHot = 100 - ($defHours > 25 ? 25 : $defHours) * 4;
            $baseHot = $baseReplyHot + $baseForwardHot + $basePraiseHot + $baseTimeHot;
            $picCount = count($dynamicsInfo['data']['picList']);
            // 获得等级信息
            $vLevelRes = $this->userMgr->getUserLevelInfo($dynamicsInfo['data']['uid']);
            if($vLevelRes['code'] == $this->status->getCode('OK')){
                $uLevelInfo = $vLevelRes['data'];
                $levelHot = ($uLevelInfo['anchorLevel'] > 1 ? ($uLevelInfo['anchorLevel'] - 1) * 0.5 : 0) + ($uLevelInfo['fansLevel'] > 1 ? ($uLevelInfo['fansLevel'] - 1) * 1 : 0) + $uLevelInfo['vipLevel'];
            }else{
                $levelHot = 0;
            }

            $addHot = ($picCount > 1 ? ($picCount - 1) * 0.5 : 0) + $levelHot;
            $pattern = '/<a .*?href="(.*?)".*?>*<\/a>/is';
            if(preg_match($pattern, $dynamicsInfo['data']['content'])){
                $delHot = -5;
            }else{
                $delHot = 0;
            }

            $hot = $baseHot + $addHot + $delHot;
            $result = $this->dynamics->updateDynamicsHot($did, $hot);
            if($result['data']){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }else{
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 删除动态
     *
     * @param $did
     * @return mixed
     */
    public function delDynamics($did){
        try{
            //登录验证
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $uid = $user->getUid();
            // 获得当前动态数据
            $dynamicsInfo = $this->dynamics->getDynamicsInfo($did);
            if(!$dynamicsInfo['data']){
                return $this->status->retFromFramework($this->status->getCode('NOT_EXIST_DYNAMICS'));
            }

            if($dynamicsInfo['data']['uid'] != $uid){
                return $this->status->retFromFramework($this->status->getCode('NOT_OWNER_DYNAMICS'));
            }

            $result = $this->dynamics->delDynamics($did);
            if($result['data']){
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }else{
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得赞的用户列表
     *
     * @return mixed
     */
    public function getPraiseList(){
        try{
            //登录验证
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $uid = $user->getUid();
            // 获得当前动态数据
            $result = $this->dynamics->getUserPraiseList($uid);
            if($result['code'] == $this->status->getCode('OK') && !empty($result['data'])){
                foreach($result['data'] as &$val){
                    // 获得评论的人用户信息
                    $val['userInfo'] = $this->userMgr->getMobileUserInfo($val['uid'])['data'];
                    $val['dynamicsInfo'] = $this->dynamics->getDynamicsInfo($val['did'])['data'];
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得评论的用户列表
     *
     * @return mixed
     */
    public function getReplyList(){
        try{
            //登录验证
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $uid = $user->getUid();
            // 获得当前动态数据
            $result = $this->dynamics->getUserReplyList($uid);
            if($result['code'] == $this->status->getCode('OK') && !empty($result['data'])){
                foreach($result['data'] as &$val){
                    // 获得评论的人用户信息
                    $val['userInfo'] = $this->userMgr->getMobileUserInfo($val['uid'])['data'];
                    $val['dynamicsInfo'] = $this->dynamics->getDynamicsInfo($val['did'])['data'];
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得转发的评论列表
     *
     * @return mixed
     */
    public function getForwardList(){
        try{
            //登录验证
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $uid = $user->getUid();
            // 获得当前动态数据
            $result = $this->dynamics->getUserForwardList($uid);
            if($result['code'] == $this->status->getCode('OK') && !empty($result['data'])){
                foreach($result['data'] as &$val){
                    // 获得评论的人用户信息
                    $val['userInfo'] = $this->userMgr->getMobileUserInfo($val['uid'])['data'];
                    $val['dynamicsInfo'] = $this->dynamics->getDynamicsInfo($val['did'])['data'];
                }
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $result['data']);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
}
