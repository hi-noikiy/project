<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Micro\Frameworks\Logic\User\UserData\UserCash as UserCash;
use Micro\Models\ActivitiesShare;
use Phalcon\DI\FactoryDefault;
use Micro\Models\ActivityLog;
use Micro\Models\Rooms;
use Micro\Frameworks\Logic\User\UserFactory;

//用户活动类
class UserActivity extends UserDataBase {

    protected $userCash;
    protected $di;
    protected $logger;
    protected $modelsManager;
    protected $status;

    public function __construct($uid) {
        parent::__construct($uid);
        $this->userCash = new UserCash();
        $this->di = FactoryDefault::getDefault();
        $this->logger = $this->di->get('logger');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->status = $this->di->get('status');
    }

    //查询用户累计活动完成情况
    public function getPayActivityInfo() {
        $return = array();
//        //首充1
//        $activity1 = $this->getUserActivityInfo($this->config->activities->firstPay3->activityId);
//        $return[0]['status'] = $activity1['status'];
//        $return[0]['key'] = 'firstPay3';
//
//        //首充2
//        $activity2 = $this->getUserActivityInfo($this->config->activities->firstPay4->activityId);
//        $return[1]['status'] = $activity2['status'];
//        $return[1]['key'] = 'firstPay4';
        foreach ($this->config->activities as $key => $val) {
            $activity = $this->getUserActivityInfo($val->activityId);
            if ($activity['status']) {
                $activity = $this->getUserActivityInfo($val->activityId);
                $data['status'] = $activity['status'];
            } else {
                $data['status'] = $this->config->activityStatus->undone;
            }
            $data['key'] = $key;
            array_push($return, $data);
        }
        return $return;
    }

    //用户领取活动礼包
    public function getPayActivityGift($key) {
        //查询该活动完成情况
        $info = $this->getUserActivityInfo($this->config->activities->$key->activityId);
        if ($info['status'] != $this->config->activityStatus->done) {//不可领取
            return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'));
        }
        $result = $this->sendUserGift($key, $this->uid);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    /**
     * 根据用户某个活动的完成情况
     * @param  $activityId 活动id
     * @return 结果
     */
    private function getUserActivityInfo($activityId) {
        $return = array();
        try {
            $info = \Micro\Models\ActivityLog::findfirst('uid =' . $this->uid . ' AND activityId = ' . $activityId);
            if ($info == FALSE) {//任务未完成
                $return['status'] = $this->config->activityStatus->undone;
            } else {
                $return['status'] = $info->status;
                //判断是否过期
                $info->status == 1 && $info->expireTime < time() && $return['status'] = $this->config->activityStatus->expired;
            }

            return $return;
        } catch (\Exception $e) {
            $this->logger->error('getUserActivityInfo error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return $return;
        }
    }

    /**
     * 给用户发送礼包
     * @param $key default配置中数组activities的键值
     * @param $uid 用户id
     */
    private function sendUserGift($key, $uid) {
        //礼包id
        $giftPackageId = $this->config->activities->$key->giftPackageId;
        $user = UserFactory::getInstance($uid);
        $user->getUserItemsObject()->giveGiftPackage($giftPackageId);
//        $giftInfo = $this->config->activities->$key->activityGift;
//        //送聊币
//        $giftInfo->cash && $this->userCash->addUserCash($giftInfo->cash, $uid, 0);
//        //添加聊币log
//        $giftInfo->cash && $this->userCash->addCashLog($giftInfo->cash, $this->config->cashSource->activity, $this->config->activities->$key->activityId, $uid);
//        //送vip
//        $giftInfo->vipTime && $this->userCash->addUserVipTime($giftInfo->vipTime, $uid);
//        //送坐骑
//        $user = $this->userAuth->getUser();
//        $giftInfo->carId && $user->getUserItemsObject()->giveCar($giftInfo->carId, $giftInfo->carTime);
        //修改用户活动表记录
        return $this->editUserActivityStatus($this->config->activities->$key->activityId);
    }

    /**
     * 修改用户对应活动的状态
     * @param $activityId 活动id
     */
    private function editUserActivityStatus($activityId) {
        try {
            $info = \Micro\Models\ActivityLog::findfirst('uid =' . $this->uid . ' AND activityId = ' . $activityId);
            if ($info->status == $this->config->activityStatus->done) {
                $info->status = $this->config->activityStatus->received;
                return $info->save();
            }
            return FALSE;
        } catch (\Exception $e) {
            $this->logger->error('editUserActivityStatus error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * 添加用户活动领取
     */
    public function setUserActivity($uid, $activityId, $time) {
        try {
            $result = ActivityLog::findfirst("uid=" . $uid . "and activityId=" . $activityId);
            if ($result != false) {
                return;
            }
            $info = new ActivityLog();
            $info->uid = $uid;
            $info->status = $this->config->activityStatus->done;
            $info->activityId = $activityId;
            $info->expireTime = time() + $time;
            return $info->save();
        } catch (\Exception $e) {
            $this->logger->error('setUserActivity error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    /*
     * 	分享活动
     */

    public function shareActivity($type, $anchorId) {
        $return['status'] = 0;
        try {
            //本活动必须先关注主播
            $user = $this->userAuth->getUser();
            $flag = $user->getUserFoucusObject()->isFans($anchorId);
            if (!$flag) {
                return $return;
            }
            //分享的主播必须是正在直播的主播
            $count = Rooms::count('uid = ' . $anchorId . ' AND liveStatus = 1');
            if (!$count) {
                return $return;
            }

            $typeId = $this->config->shareType->$type;
            //每次分享必须为同个主播的不同SNS，或者不同主播的同个SNS
            $today = strtotime(date("Y-m-d", time()) . " 00:00:00");
            $counts = ActivitiesShare::count('uid = ' . $this->uid . ' AND anchorId = ' . $anchorId . " and type=" . $typeId . " and createTime>=" . $today);
            if ($counts) {
                return $return;
            }

            //分享
            $ActivitiesShare = new ActivitiesShare();
            $ActivitiesShare->uid = $this->uid;
            $ActivitiesShare->anchorId = $anchorId;  //主播ID
            $ActivitiesShare->type = $typeId;
            $ActivitiesShare->createTime = time();
            $result = $ActivitiesShare->save();

            //添加到日常任务--分享
            if ($result) {
                $taskRes = $this->taskMgr->setUserTask($this->uid, $this->config->taskIds->share);
                if ($taskRes['code'] == $this->status->getCode("OK") && isset($taskRes['data']['hasreward'])) {//完成任务
                    //领取奖励
                    $taskRewardRes = $this->taskMgr->getNewTaskReward($this->config->taskIds->share);
                    if ($taskRewardRes['code'] == $this->status->getCode("OK")) {
                        $return['status'] = 1;
                        $return['taskReward'] = $taskRewardRes['data'];
                    }
                }
                return $return;
            }
        } catch (\Exception $e) {
            $this->logger->error('shareActivity error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
        }
        return $return;
    }

    //
    public function getDayTime($times) {

        $y = date("Y", $times);
        $m = date("m", $times);
        $d = date("d", $times);
        $day_start = mktime(0, 0, 0, $m, $d, $y);  //开始时间戳

        $day_end = mktime(23, 59, 59, $m, $d, $y);  //结束时间戳

        $nowtime = time();  //当前时间

        if ($nowtime >= $day_start && $nowtime <= $day_end) {
            return $day_end;
        } else {
            return false;
        }
    }

    /**
     * 		领取
     * */
    public function getShareActivity() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        //验证是否有领取
        $activityLog = activityLog::findfirst('uid = ' . $this->uid);
        if (!$activityLog) {
            return $this->status->retFromFramework($this->status->getCode('....'));
        }
        //验证是否已经过期
        $oldtime = $this->getDayTime($activityLog->expireTime);

        if ($oldtime == false) {
            return $this->status->retFromFramework($this->status->getCode('....'));   //不是当天时间已经过期
        }
        //验证是否有领取过 2表示已经领取过
        if ($activityLog->status == 2) {
            return $this->status->retFromFramework($this->status->getCode('....'));
        }
        $activityLog->status = 2;
        $activityLog->save();
    }

}
