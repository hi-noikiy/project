<?php

namespace Micro\Frameworks\Logic\Task;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\Task\TaskData;
use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Frameworks\Logic\User\UserData\UserCash;

//任务接口
class TaskMgr {

    protected $di;
    protected $userAuth;
    protected $taskData;
    protected $status;
    protected $config;
    protected $validator;
    protected $session;
    protected $db;
    protected $userCash;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->userAuth = $this->di->get('userAuth');
        $this->status = $this->di->get('status');
        $this->taskData = new TaskData();
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->session = $this->di->get('session');
        $this->db = $this->di->get("db");
        $this->userCash = new UserCash();
    }

    //查询用户任务完成情况
    public function getUserTask($type = 1) {
        $user = $this->userAuth->getUser();
        $uid = 0;
        if ($user) {
            $uid = $user->getUid();
        }
        $result = $this->taskData->getUserTaskList($uid, $type);
        return $result;
    }

    //用户领取某个任务奖励
    public function getTaskReward($taskId) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $result = $this->taskData->getUserTaskReward($uid, $taskId);
        return $result;
    }

    //跟主播聊天任务
    public function setTalkTask() {
        //此新手任务已取消
        return;
//        $user = $this->userAuth->getUser();
//        if (!$user) {
//            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
//        }
//        $uid = $user->getUid();
//        return $this->setUserTask($uid, $this->config->taskIds->sayHello);
    }

    //连续观看任务
    public function setwatchTask($type = 1) {
        //此任务已取消
        return;
        /*    $user = $this->userAuth->getUser();
          //        if (!$user) {
          //            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
          //        }
          $result = '';
          $taskId = $this->config->taskIds->online;
          $currentTime = time();
          if (!$this->session->get("getCashTime")) {//session不存在
          $this->session->set("getCashTime", $currentTime);
          $result['leftTime'] = $this->config->getCoinTime; //剩余时间
          return $this->status->retFromFramework($this->status->getCode('FREE_COIN_NOT_AVAILABLE'), $result);
          }

          $time = $this->session->get("getCashTime") + $this->config->getCoinTime;
          if ($currentTime >= $time) {//可领取,重置session时间
          if ($user) {//如果已登录，写入数据库
          $uid = $user->getUid();
          $result = $this->setUserTask($uid, $taskId);
          }else{
          $result['leftTime'] = 0; //剩余时间
          return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), $result);
          }
          } else {//时间未到
          $result['leftTime'] = $time - $currentTime; //剩余时间
          return $this->status->retFromFramework($this->status->getCode('FREE_COIN_NOT_AVAILABLE'), $result);
          }

          return $this->status->retFromFramework($this->status->getCode('OK'), $result); */
    }

    //添加用户任务记录
    public function setUserTask($uid, $taskId, $finishNum = 1) {
        return $this->taskData->editUserTask($uid, $taskId, $this->config->taskStatus->done, $finishNum);
    }

    //分享链接回访任务
    public function shareBack($fromuid) {
        $postData['uid'] = $fromuid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $result = $this->taskData->shareCallback($fromuid);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'), '');
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //查询某个任务是否完成
    public function getTaskStatus($taskId) {
        $postData['id'] = $taskId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $result = $this->taskData->getOneTaskStatus($taskId, $uid);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    //渠道礼包领取
    public function getSourceGiftPackageTask() {
        /* $user = $this->userAuth->getUser();
          if (!$user) {
          return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
          }
          $return['giftId'] = '';
          $uid = $user->getUid();
          $taskInfo = $this->taskData->getOneTaskStatus($this->config->taskIds->sourceLoginReward, $uid);
          if ($taskInfo['status'] == $this->config->taskStatus->received) {//已领取
          return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'));
          }

          //查询用户渠道
          $ns_sources = $user->getUserInfoObject()->getUserSource();
          $utm_source = $ns_sources['ns_source'];
          $utm_medium = $ns_sources['utm_medium'];
          $configMgr = $this->di->get('configMgr');
          $giftPackageId = $configMgr->getSourceGiftPackage($this->config->giftPackageAction->login, $utm_source, $utm_medium); //查询礼包
          if ($giftPackageId) {
          $this->taskData->editUserTask($uid, $this->config->taskIds->sourceLoginReward, $this->config->taskStatus->done); //修改任务状态为完成状态
          $this->taskData->editUserTask($uid, $this->config->taskIds->sourceLoginReward, $this->config->taskStatus->received); //修改任务状态为领取状态
          //给用户送礼包
          $user->getUserItemsObject()->giveGiftPackage($giftPackageId);
          $return['giftId'] = $giftPackageId;

          $session = $this->di->get('session');
          $session->set($this->config->websitecookies->source_gift, 1);
          return $this->status->retFromFramework($this->status->getCode('OK'), $return);
          } */
        return $this->status->retFromFramework($this->status->getCode('REWARD_IS_NOT_EXISTED'));
    }

    //提交新手引导的任务
    public function setUserGuideTask($taskId) {
        $postData['id'] = $taskId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $uid = $user->getUid();
        $result = $this->taskData->editNewUserGuideTask($taskId, $uid);
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    //领取新手引导任务的奖励
    public function getUserGuideTaskReward($taskId) {
        $postData['id'] = $taskId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
        }
        $uid = $user->getUid();
        $result = $this->taskData->getNewUserGuideTaskReward($taskId, $uid);
        return $this->status->retFromFramework($result['code'], $result['data']);
    }

    //绑定手机任务
    public function bindTelephoneTask() {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $taskId = $this->config->taskIds->bindPhone;
        $taskInfo = $this->taskData->getOneTaskStatus($taskId, $uid);
        if ($taskInfo['status'] == $this->config->taskStatus->received) {//已领取过
            return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'));
        }
        $this->setUserTask($uid, $taskId);
        $this->taskData->getUserTaskReward($uid, $taskId);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //累计看直播
    public function totalWatchTask($roomId = 0) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $now = time();
        if (!$this->session->get("watchTime")) {//session不存在
            return $this->status->retFromFramework($this->status->getCode('EMPTY_DATA'));
        }
        //判断间隔时间
        if ($now - $this->session->get("watchTime") < $this->config->taskConfig->watchsTimes) {
            return $this->status->retFromFramework($this->status->getCode('OPER_NOT_AFFACT'));
        }
        //记录次数
        $taskId = $this->config->taskIds->totalWatch;
        $res = $this->setUserTask($uid, $taskId);

        $return = array();
        if ($res['code'] == $this->status->getCode("OK")) {
            $this->session->set("watchTime",$now);//重置session
            
            //判断是否有奖励领取
            $reward = $res['data']['reward'];
            if ($reward) {
                $getres = $this->getStageReward($this->config->taskConfig->watchs->toArray(), $reward, $uid, $roomId);
                $return = $getres;
            }
            $return['status'] = isset($res['data']['status']) ? $res['data']['status'] : 0;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        }
        return $res;
    }

    //累计发言任务
    public function getTotalTalkTask($roomId = 0) {
        //临时处理下，直接返回status=0,避免app有问题，
        $return['status'] =0;
        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        
        
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        if (!$this->session->get("enterRoom")) {//session不存在
            return $this->status->retFromFramework($this->status->getCode('EMPTY_DATA'));
        }
        //记录次数
        $taskId = $this->config->taskIds->totalTalk;
        $res = $this->setUserTask($uid, $taskId);

        $return = array();
        if ($res['code'] == $this->status->getCode("OK")) {
            //判断是否有奖励领取
            $reward = $res['data']['reward'];
            if ($reward) {
                $getres = $this->getStageReward($this->config->taskConfig->talks->toArray(), $reward, $uid, $roomId);
                $return = $getres;
            }
            $return['status'] = isset($res['data']['status']) ? $res['data']['status'] : 0;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        }
        return $res;
    }

    //累计送礼任务
    public function getTotalGiftTask($giftNum, $roomId = 0) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        //记录次数
        $taskId = $this->config->taskIds->totalGift;
        $res = $this->setUserTask($uid, $taskId, $giftNum);

        $return = array();
        if ($res['code'] == $this->status->getCode("OK")) {
            //判断是否有奖励领取
            $reward = $res['data']['reward'];
            if ($reward) {
                $getres = $this->getStageReward($this->config->taskConfig->gifts->toArray(), $reward, $uid, $roomId);
                $return = $getres;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        }
        return $res;
    }

    //分阶段的奖励
    private function getStageReward($configs = array(), $rewardArr = array(), $uid = 0, $roomId = 0) {
        $count = count($rewardArr);
        $i = 1;
        $return = array();
        $finalExp = 0;
        $finalPoints = 0;
        $finalGiftNum = 0;
        foreach ($rewardArr as $finishRate) {
            foreach ($configs as $w) {
                if ($w['times'] == $finishRate) {//完成某个阶段的任务
                    $reward = $w['reward'];
                    $user = UserFactory::getInstance($uid);
                    //送经验值
                    if (isset($reward['exp'])) {
                        $user->getUserItemsObject()->giveItem(15, $reward['exp'], 0, $roomId); //送经验值
                        $finalExp+=$reward['exp']; //任务获得的经验值
                        if ($roomId && $count == $i) {
                            $userProfiles = \Micro\Models\UserProfiles::findFirst($uid);
                            $return['richerExp'] = $userProfiles->exp3;
                            $richerConfigs = \Micro\Models\RicherConfigs::findfirst('higher >= ' . $userProfiles->exp3 . ' and lower <= ' . $userProfiles->exp3);
                            $return['richerHigher'] = $richerConfigs ? ($richerConfigs->higher + 1) : 0;
                            $return['richerLower'] = $richerConfigs ? ($richerConfigs->lower + 1) : 0;
                            $return['taskExp'] = $finalExp;
                        }
                    }
                    //送积分
                    if (isset($reward['points'])) {
                        $finalPoints+=$reward['points']; //任务获得的积分
                        $user->getUserItemsObject()->addPoints($reward['points'], $this->config->pointsType->task); //送积分
                        if ($roomId && $count == $i) {
                            $userProfiles = \Micro\Models\UserProfiles::findFirst($uid);
                            $return['points'] = $userProfiles->points;
                            $return['taskPoints'] = $finalPoints;
                        }
                    }
                    //送礼物
                    if (isset($reward['giftId'])) {
                        $finalGiftNum+=$reward['giftNum']; //任务获得的礼物数量
                        $user->getUserItemsObject()->giveGift($reward['giftId'], $reward['giftNum']);
                        if ($roomId && $count == $i) {
                            $return['type'] = $this->config->itemType->gift;
                            $return['num'] = $finalGiftNum;
                            $return['itemId'] = $reward['giftId'];
                            $return['configName'] = $reward['configName'];
                            $return['name'] = $reward['giftName'];
                        }
                    }
                    break;
                }
            }
            $i++;
        }
        return $return;
    }

    //领取奖励 并 修改状态  
    public function getNewTaskReward($taskId) {
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $today = strtotime(date("Ymd"));
        $return = array();
        try {
            $selectsql = "select id from pre_task_log where uid=" . $uid . " and taskId=" . $taskId . " and status=1 and finishTime>=" . $today;
            $selectres = $this->db->fetchOne($selectsql);
            if (!$selectres) {
                return $this->status->retFromFramework($this->status->getCode('REWARD_IS_NOT_EXISTED'));
            }
            $reward = array();
            if ($taskId == $this->config->taskIds->seat) {//抢座
                $reward = $this->config->taskConfig->seatReward;
            } elseif ($taskId == $this->config->taskIds->charm) {//送魅力星
                $reward = $this->config->taskConfig->starReward;
            } elseif ($taskId == $this->config->taskIds->share) {//分享
                $reward = $this->config->taskConfig->shareReward;
            }
            if ($reward) {
                //送经验值
                if (isset($reward['exp'])) {
                    $user->getUserItemsObject()->giveItem(15, $reward['exp']); //送经验值
                    $return['taskExp'] = $reward['exp'];
                }
                //送积分
                if (isset($reward['points'])) {
                    $user->getUserItemsObject()->addPoints($reward['points'], $this->config->pointsType->task); //送积分
                    $return['taskPoints'] = $reward['points'];
                }
                //送聊豆
                if (isset($reward['coin'])) {
                    $this->userCash->sendUserCoin($reward['coin'], $uid);
                    $return['taskCoin'] = $reward['coin'];
                }
            }
            //修改任务状态
            $now = time();
            $updatesql = "update pre_task_log set status=2,receiveTime=" . $now . " where id=" . $selectres['id'];
            $this->db->execute($updatesql);
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
