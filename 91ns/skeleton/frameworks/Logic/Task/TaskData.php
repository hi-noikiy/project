<?php

namespace Micro\Frameworks\Logic\Task;

use Phalcon\DI\FactoryDefault;
use Micro\Models\TaskLog;
use Micro\Frameworks\Logic\User\UserData\UserCash;
use Micro\Frameworks\Logic\User\UserFactory;

//任务操作类
class TaskData {

    protected $di;
    protected $config;
    protected $modelsManager;
    protected $logger;
    protected $userCash;
    protected $userAuth;
    protected $status;
    protected $db;
    protected $validator;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->logger = $this->di->get('logger');
        $this->userCash = new UserCash();
        $this->userAuth = $this->di->get('userAuth');
        $this->db = $this->di->get("db");
        $this->validator = $this->di->get('validator');
    }

    /**
     * 获得某个用户在线任务信息
     * @param $uid 用户id
     */
    public function getUserTaskList($uid = 0) {
        try {
            //获取在线任务配置
            $taskList = \Micro\Models\Task::find("status=1 and taskType=2 and showStatus=1 order by taskSort asc");
            $taskResult = $taskList->valid();

            $list = array();
            if ($taskResult) {
                $units = $this->config->taskConfig->taskUnits->toArray(); //单位
                foreach ($taskList as $val) {
                    $data['taskId'] = $val->taskId; //任务id
                    $data['taskName'] = $val->taskName; //任务名称
                    $data['taskDes'] = $val->taskDes; //任务描述
                    $data['taskReward'] = array(); //任务奖励

                    $data['status'] = 0;
                    $data['unit'] = isset($units[$val->taskId]) ? $units[$val->taskId] : '个';
                    $data['taskRate'] = ''; //任务的进度
                    $finishNum = 0; //任务需完成的次数
                    $finishRate = 0; //当前已完成次数

                    $info = false;
                    if ($uid) {//查询用户完成情况
                        $today = strtotime(date("Ymd") . " 00:00:00");
                        $where = "uid=" . $uid . " and taskId=" . $val->taskId . " and finishTime>=" . $today . " order by id desc";
                        $info = \Micro\Models\TaskLog::findfirst($where);
                    }
                    switch ($val->taskId) {
                        case $this->config->taskIds->share://日常任务--分享
                            $finishNum = $this->config->taskConfig->shareTimes;
                            $taskRewardArr = array(0 => array("times" => $this->config->taskConfig->shareTimes, 'reward' => array("coin" => $val->taskReward)));
                            $data['taskReward'] = $taskRewardArr;
                            $data['isShare'] = 1;
                            break;
                        case $this->config->taskIds->charm://日常任务--魅力
                            $finishNum = $this->config->taskConfig->starNum;
                            $taskRewardArr = array(0 => array("times" => $this->config->taskConfig->starNum, 'reward' => $this->config->taskConfig->starReward));
                            $data['taskReward'] = $taskRewardArr;
                            break;
                        case $this->config->taskIds->seat://日常任务--抢座
                            $finishNum = $this->config->taskConfig->seatNum;
                            $taskRewardArr = array(0 => array("times" => $this->config->taskConfig->seatNum, 'reward' => $this->config->taskConfig->seatReward));
                            $data['taskReward'] = $taskRewardArr;
                            break;
                        case $this->config->taskIds->totalWatch://日常任务--累计观看直播
                            $taskRewardArr = $this->config->taskConfig->watchs->toArray();
                            $data['taskReward'] = $taskRewardArr;
                            $data['isWatch'] = 1;
                            $data['watchsTimes'] = $this->config->taskConfig->watchsTimes;
                            if ($info != false) {
                                $data['taskRate'] = $info->finishRate;
                            }
                            $count = count($taskRewardArr);
                            $finishNum = $this->config->taskConfig->watchs->toArray()[$count - 1]['times'];
                            break;
                        case $this->config->taskIds->totalTalk://日常任务--累计发言
                            $taskRewardArr = $this->config->taskConfig->talks->toArray();
                            $data['taskReward'] = $taskRewardArr;
                            $data['isTalk'] = 1;
                            if ($info != false) {
                                $data['taskRate'] = $info->finishRate;
                            }
                            $count = count($taskRewardArr);
                            $finishNum = $this->config->taskConfig->talks->toArray()[$count - 1]['times'];
                            break;
                        case $this->config->taskIds->totalGift://日常任务--累计送聊币礼物
                            $taskRewardArr = $this->config->taskConfig->gifts->toArray();
                            $data['taskReward'] = $taskRewardArr;
                            $data['isGift'] = 1;
                            if ($info != false) {
                                $data['taskRate'] = $info->finishRate;
                            }
                            $count = count($taskRewardArr);
                            $finishNum = $this->config->taskConfig->gifts->toArray()[$count - 1]['times'];
                            break;
                    }

                    if ($info != false) {
                        $data['status'] = $info->status;
                        $finishRate = $info->finishRate;
                    }
                    $data['taskRate'] = $finishRate;
                    $data['finishNum'] = $finishNum;
                    array_push($list, $data);
                    unset($data);
                }
            }
            $return['taskTime'] = $this->config->taskConfig->taskTime; //截止时间
            $return['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->logger->error('getUserTaskList error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 用户领取任务的奖励
     *  @param uid 用户id
     * @param taskId 任务id
     */
    public function getUserTaskReward($uid, $taskId) {
        $return = array();
        try {
            $where = "uid=" . $uid . " and taskId=" . $taskId;
            switch ($taskId) {
                case $this->config->taskIds->online://日常任务--在线领奖
                case $this->config->taskIds->share://日常任务--分享
                case $this->config->taskIds->rose://日常任务--送玫瑰
                case $this->config->taskIds->charm://日常任务--魅力
                case $this->config->taskIds->seat://日常任务--抢座
                    $today = strtotime(date("Ymd") . " 00:00:00");
                    $where.=" and finishTime>=" . $today;
                    break;
                default:  //新手任务  只能完成一次
                    break;
            }
            $where.= " order by id desc";
            $info = \Micro\Models\TaskLog::findfirst($where);
            if ($info != false && $info->status == $this->config->taskStatus->done) {//可领取
                $this->editUserTask($uid, $taskId, $this->config->taskStatus->received);
                //给用户送任务奖励
                $return = $this->sendUserReward($uid, $taskId);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 编辑用户任务信息
     * @param uid 用户id
     * @param taskId 任务id
     * @param status 任务状态
     */
    public function editUserTask($uid, $taskId, $status, $finishNum = 1) {
        $postData['id'] = $taskId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            
            $taskInfo = \Micro\Models\Task::findfirst("taskId=" . $taskId);
            if (!$taskInfo || $taskInfo->status != 1) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $where = "uid=" . $uid . " and taskId=" . $taskId;
            $finishRate = 1;
            $isOk = false;
            $isEdit = false;
            $finishStatus = 0; //完成状态
            $today = strtotime(date("Ymd") . " 00:00:00");
            $now = time();
            $return = array(); //返回值
            $receiveTime = 0;
            switch ($status) {
                case $this->config->taskStatus->done://完成任务
                    $where.=" and finishTime>=" . $today;
                    $infosql = "select id,finishRate,status from pre_task_log where {$where}";
                    $info = $this->db->fetchOne($infosql);
                    switch ($taskId) {
                        case $this->config->taskIds->share://日常任务--分享
                            if (!$info) {//添加
                                $isOk = true;
                            } elseif ($info['status'] == $this->config->taskStatus->undone) {//修改
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                if ($oldFinishRate < $this->config->taskConfig->shareNum) {
                                    $finishRate = $oldFinishRate + $finishNum;
                                    if ($finishRate >= $this->config->taskConfig->shareNum) {//达到完成次数
                                        $finishStatus = $this->config->taskStatus->done;
                                        $finishRate = $this->config->taskConfig->shareNum;
                                        $return['hasreward'] = 1; //可领取奖励
                                    }
                                    $isEdit = true;
                                }
                            }
                            break;

                        case $this->config->taskIds->charm://日常任务--魅力
                            if (!$info) {//添加
                                $isOk = true;
                            } elseif ($info['status'] == $this->config->taskStatus->undone) {//修改
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                if ($oldFinishRate < $this->config->taskConfig->starNum) {
                                    $finishRate = $oldFinishRate + $finishNum;
                                    if ($finishRate >= $this->config->taskConfig->starNum) {//达到完成次数
                                        $finishStatus = $this->config->taskStatus->done;
                                        $finishRate = $this->config->taskConfig->starNum;
                                        $return['hasreward'] = 1; //可领取奖励
                                    }
                                    $isEdit = true;
                                }
                            }
                            break;
                        case $this->config->taskIds->seat://日常任务--抢座
                            if (!$info) {//添加
                                $isOk = true;
                                if ($finishNum >= $this->config->taskConfig->seatNum) {//达到完成次数
                                    $finishStatus = $this->config->taskStatus->done;
                                    $finishNum = $this->config->taskConfig->seatNum;
                                    $return['hasreward'] = 1; //可领取奖励
                                }
                            } elseif ($info['status'] == $this->config->taskStatus->undone) {//修改
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                if ($oldFinishRate < $this->config->taskConfig->seatNum) {
                                    $finishRate = $oldFinishRate + $finishNum;
                                    if ($finishRate >= $this->config->taskConfig->seatNum) {//达到完成次数
                                        $finishStatus = $this->config->taskStatus->done;
                                        $finishRate = $this->config->taskConfig->seatNum;
                                        $return['hasreward'] = 1; //可领取奖励
                                    }
                                    $isEdit = true;
                                }
                            }
                            break;

                        case $this->config->taskIds->vipReward1://日常任务--领取普通vip礼物
                        case $this->config->taskIds->vipReward2://日常任务--领取至尊vip礼物
                            if (!$info) {//添加
                                $finishStatus = $this->config->taskStatus->done;
                                $finishNum = 1;
                                $isOk = true;
                            }
                            break;
                        case $this->config->taskIds->totalGift://累计送礼
                            $reward = array(); //达到任务的奖励
                            if (!$info) {//添加
                                $oldFinishRate = 0;
                                $isOk = true;
                                $finishRate = $finishNum;
                                foreach ($this->config->taskConfig->gifts as $w) {
                                    if ($finishRate >= $w['times']) {
                                        $reward[] = $w['times']; //可领取的奖励
                                    }
                                }
                            } elseif ($info['status'] == $this->config->taskStatus->undone) {//修改
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                $finishRate = $oldFinishRate + $finishNum;
                                foreach ($this->config->taskConfig->gifts as $w) {
                                    if ($oldFinishRate < $w['times'] && $finishRate >= $w['times']) {
                                        $reward[] = $w['times']; //可领取的奖励
                                    }
                                }
                                $isEdit = true;
                            } else {
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                $finishRate = $oldFinishRate + $finishNum;
                            }
                            $return['finishRate'] = $finishRate;
                            $return['reward'] = $reward;
                            $count = count($this->config->taskConfig->gifts->toArray());
                            $lastTime = $this->config->taskConfig->gifts->toArray()[$count - 1]['times'];
                            if ($finishRate >= $lastTime) {//已全部完成
                                $finishStatus = $this->config->taskStatus->received; //领取
                                $receiveTime = $now;
                            }
                            break;
                        case $this->config->taskIds->totalTalk://累计发言
                            $reward = array(); //达到任务的奖励
                            if (!$info) {//添加
                                $oldFinishRate = 0;
                                $isOk = true;
                                $finishRate = $finishNum;
                                foreach ($this->config->taskConfig->talks as $w) {
                                    if ($finishRate >= $w['times']) {
                                        $reward[] = $w['times']; //可领取的奖励
                                    }
                                }
                            } elseif ($info['status'] == $this->config->taskStatus->undone) {//修改
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                $finishRate = $oldFinishRate + $finishNum;
                                foreach ($this->config->taskConfig->talks as $w) {
                                    if ($oldFinishRate < $w['times'] && $finishRate >= $w['times']) {
                                        $reward[] = $w['times']; //可领取的奖励
                                    }
                                }
                                $isEdit = true;
                            } else {
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                $finishRate = $oldFinishRate + $finishNum;
                            }
                            $return['finishRate'] = $finishRate;
                            $return['reward'] = $reward;
                            $count = count($this->config->taskConfig->talks->toArray());
                            $lastTime = $this->config->taskConfig->talks->toArray()[$count - 1]['times'];
                            if ($finishRate >= $lastTime) {//已全部完成
                                $finishStatus = $this->config->taskStatus->received; //领取
                                $return['status'] = $finishStatus; //完成状态
                                $receiveTime = $now;
                            }
                            break;
                        case $this->config->taskIds->totalWatch://累计观看
                            $reward = array(); //达到任务的奖励
                            if (!$info) {//添加
                                $oldFinishRate = 0;
                                $isOk = true;
                                foreach ($this->config->taskConfig->watchs as $w) {
                                    if ($finishNum >= $w['times']) {
                                        $reward[] = $w['times']; //可领取的奖励
                                    }
                                }
                            } elseif ($info['status'] == $this->config->taskStatus->undone) {//修改
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                $finishRate = $oldFinishRate + $finishNum;
                                foreach ($this->config->taskConfig->watchs as $w) {
                                    if ($oldFinishRate < $w['times'] && $finishRate >= $w['times']) {
                                        $reward[] = $w['times']; //可领取的奖励
                                    }
                                }
                                $isEdit = true;
                            } else {
                                $oldFinishRate = $info['finishRate']; //已完成的次数
                                $finishRate = $oldFinishRate + $finishNum;
                            }
                            $return['finishRate'] = $finishRate;
                            $return['reward'] = $reward;
                            $count = count($this->config->taskConfig->watchs->toArray());
                            $lastTime = $this->config->taskConfig->watchs->toArray()[$count - 1]['times'];
                            if ($finishRate >= $lastTime) {//已全部完成
                                $finishStatus = $this->config->taskStatus->received; //领取
                                $return['status'] = $finishStatus; //完成状态
                                $receiveTime = $now;
                            }
                            break;

                        default:  //新手任务  只能完成一次
                            $infosql = "select id,finishRate,status from pre_task_log where {$where}";
                            if (!$info) {//添加
                                $isOk = true;
                                $finishStatus = $this->config->taskStatus->done;
                            }
                            break;
                    }

                    if ($isOk) {//添加到任务记录表
                        $insertsql = "insert into pre_task_log(uid,taskId,status,finishRate,finishTime)values({$uid},{$taskId},{$finishStatus},{$finishNum},{$now})";
                        $this->db->execute($insertsql);
                    } elseif ($isEdit) {
                        $updatesql = "update pre_task_log set finishRate={$finishRate},status={$finishStatus},finishTime={$now},receiveTime={$receiveTime} where id={$info['id']}";
                        $this->db->execute($updatesql);
                    }
                    return $this->status->retFromFramework($this->status->getCode('OK'), $return);
                case $this->config->taskStatus->received://领取任务奖励
                    $where.=" order by id desc";
                    $selectsql = "select id,status from pre_task_log where " . $where;
                    $selectinfo = $this->db->fetchOne($selectsql);
                    if ($selectinfo) {
                        $updatesql = "update pre_task_log set status=" . $status . ",receiveTime=" . $now . " where id=" . $selectinfo['id'] . " and status=" . $selectinfo['status'];
                        $this->db->execute($updatesql);
                        $updateres = $this->db->affectedRows(); //判断更新是否成功
                        if (!$updateres) {//操作失败
                            return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'), $return);
                        }
                    }
                    return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 给用户送任务奖励
     * @param uid 用户id
     * @param taskId 任务id
     */
    private function sendUserReward($uid, $taskId) {
        try {
            $info = \Micro\Models\Task::findfirst("taskId=" . $taskId . " and status=1");
            if ($info == FALSE) {
                return FALSE;
            }
            if ($taskId == $this->config->taskIds->pay) {//新手引导的充值任务
                //需查询是否有符合条件的订单
                $order = new \Micro\Frameworks\Pay\OrderMgr();
                $firstPay = $order->checkFirstPayByType($uid, $this->config->orderType->guide);
                //首充任务中可领取的聊币数量
                $addCashNum = round(($info->taskReward) / 100 * $firstPay); //奖励10%聊币，上限为100000聊币
                //判断是否超过了赠送的上限
                $addCashNum > $this->config->taskConfig->cashMax && $addCashNum = $this->config->taskConfig->cashMax;
                //增加聊币
                $result = $this->userCash->addUserCash($addCashNum, $uid, 0);
                //写入聊币记录表
                $this->userCash->addCashLog($addCashNum, $this->config->cashSource->task, $taskId, $uid);
                return $addCashNum;
            } else {//普通任务
                $addNum = $info->taskReward; //赠送的数量
                if ($info->rewardType == $this->config->taskConfig->sendCash) {//送聊币
                    //增加聊币
                    $result = $this->userCash->addUserCash($addNum, $uid, 0);
                    //写入聊币记录表
                    $this->userCash->addCashLog($addNum, $this->config->cashSource->task, $taskId, $uid);
                    return $addNum;
                } elseif ($info->rewardType == $this->config->taskConfig->sendCoin) {//送聊豆
                    //增加聊豆
                    $coinResult = $this->userCash->sendUserCoin($addNum, $uid);
                    return $addNum;
                } elseif ($info->rewardType == $this->config->taskConfig->sendGift) {//送礼包
                    $user = UserFactory::getInstance($uid);
                    $giftPackageId = 0;
                    if ($info->sourceReward) {//判断是否有渠道礼包
                        $array = json_decode($info->sourceReward, true);
                        //查询用户渠道
                        $source = $user->getUserInfoObject()->getUserSource();
                        if ($source['ns_source']) {
                            $giftPackageId = $array[$source['ns_source']];
                        }
                    }
                    !$giftPackageId && $giftPackageId = $info->taskReward;
                    $user->getUserItemsObject()->giveGiftPackage($giftPackageId);
                    return $giftPackageId;
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('sendUserReward error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
        }
        return false;
    }

    //分享链接回访操作
    public function shareCallback($uid) {
        //判断cookie的值
        if (isset($_COOKIE['fromShare'])) {
            return false;
        }
        //设置cookie
        $time = strtotime(date("Y-m-d", strtotime("+1 day")) . " 00:00:00"); //第二天0点过期
        setcookie("fromShare", time(), $time);
        //判断是否本人
        $user = $this->userAuth->getUser();
        if ($user != NULL) {
            if ($user->getUid() == $uid) {
                return false;
            }
        }
        //日常任务--修改
        $this->editUserTask($uid, $this->config->taskIds->shareBack, $this->config->taskStatus->done);
    }

    //查询任务状态
    public function getOneTaskStatus($taskId, $uid) {
        try {
            if ($taskId == $this->config->taskIds->totalTalk) {//在线聊天任务，临时处理下，直接返回status=2,避免app有问题
                $result['status'] = 2;
                return $result;
            }

            $result['status'] = 0;
            if ($taskId) {
                if ($taskId == $this->config->taskIds->totalWatch) {//在线累计观看
                    $result['watchsTimes'] = $this->config->taskConfig->watchsTimes;
                }

                if ($taskId == $this->config->taskIds->userGuide) {//新手引导
                    //旧用户没有新手引导
                    $time = 1435921200; //2015/07/03 19:00
                    //查询用户注册时间
                    $user = UserFactory::getInstance($uid);
                    $userInfo = $user->getUserInfoObject()->getUserAccountInfo();
                    if ($userInfo['createTime'] < $time) {
                        $result['status'] = $this->config->taskStatus->received;
                        return $result;
                    }
                }
                $info = \Micro\Models\Task::findfirst("taskId=" . $taskId . " and status=1");
                if ($info != false) {
                    switch ($info->taskType) {
                        case $this->config->taskType->daily; //日常任务
                            $today = strtotime(date("Ymd") . " 00:00:00");
                            $taskLog = \Micro\Models\TaskLog::findfirst("uid=" . $uid . " and taskId=" . $taskId . " and finishTime>=" . $today . " order by finishTime desc");
                            if ($taskLog != false) {
                                $result['status'] = $taskLog->status;
                                return $result;
                            }
                            break;
                        default :
                            $taskLog = \Micro\Models\TaskLog::findfirst("uid=" . $uid . " and taskId=" . $taskId." order by id desc");
                            if ($taskLog != false) {
                                $result['status'] = $taskLog->status;
                                return $result;
                            }
                            break;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('getOneTaskStatus error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
        }
        return $result;
    }

    //查询用户新手引导完成情况
    public function getUserGuideTaskInfo($uid) {
        try {
            $status = 0; //完成状态
            $taskId = $this->config->taskIds->login; //初始任务

            $sql = "select l.status,t.taskId,t.taskSort from  \Micro\Models\TaskLog l "
                    . "inner join  \Micro\Models\Task t on l.taskId=t.taskId "
                    . "where l.uid=" . $uid . " and t.status=1 and t.taskType=" . $this->config->taskType->guide . " order by t.taskSort desc,l.id desc limit 1";
            $query = $this->modelsManager->createQuery($sql);
            $info = $query->execute();
            $info = $info->toArray();
            if ($info) {
                $taskId = $info[0]['taskId'];
                $status = $info[0]['status'];
                if ($status == $this->config->taskStatus->received) {//该任务已完成
                    //查询下一个新手引导任务
                    $nextTaskInfo = \Micro\Models\Task::findfirst("taskType=" . $this->config->taskType->guide . " and status=1 and taskSort>" . $info[0]['taskSort'] . " order by taskSort asc");
                    $nextTaskId = $this->config->taskIds->userGuide;
                    $nextStatus = $this->config->taskStatus->done;
                    if ($nextTaskInfo != false) {
                        $nextTaskId = $nextTaskInfo->taskId;
                        $nextStatus = $this->config->taskStatus->undone;
                    }
                    $taskId = $nextTaskId;
                    $status = $nextStatus;
                }
            }
            $result['status'] = $status;
            $result['taskId'] = $taskId;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //完成某个新手引导任务
    public function editNewUserGuideTask($taskId, $uid) {
        try {
            $taskInfo = \Micro\Models\Task::findfirst("taskId=" . $taskId . " and taskType=" . $this->config->taskType->guide . " and status=1");
            if ($taskInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            //查询上一个任务是否完成
            $lastTaskInfo = \Micro\Models\Task::findfirst("taskType=" . $this->config->taskType->guide . " and status=1 and taskSort<" . $taskInfo->taskSort . " order by taskSort desc");
            if ($lastTaskInfo != false) {
                $lastTaskLogInfo = \Micro\Models\TaskLog::findfirst("uid=" . $uid . " and taskId=" . $lastTaskInfo->taskId." order by id desc");
                if ($lastTaskLogInfo == false || $lastTaskLogInfo->status != $this->config->taskStatus->received) {//上一个任务未完成
                    return $this->status->retFromFramework($this->status->getCode('LAST_TASK_NOT_FINISH'));
                }
            }
            if ($taskId == $this->config->taskIds->pay) {//如果是充值任务
                //需查询是否有符合条件的订单
                $order = new \Micro\Frameworks\Pay\OrderMgr();
                $firstPay = $order->checkFirstPayByType($uid, $this->config->orderType->guide);
                if (!$firstPay) {
                    return $this->status->retFromFramework($this->status->getCode('THIS_TASK_NOT_FINISH'));
                }
            }
            $this->editUserTask($uid, $taskId, $this->config->taskStatus->done);

            $return['taskId'] = $taskId;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //领取某个新手引导任务的奖励
    public function getNewUserGuideTaskReward($taskId, $uid) {
        try {
            if ($taskId == $this->config->taskIds->userGuide) {//新手引导总奖励
                $nextTaskId = 0; //下一个任务id
            } else {
                $info = \Micro\Models\Task::findfirst("taskId=" . $taskId . " and status=1");
                if ($info == false) {
                    return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
                }
            }

            //给用户送任务奖励
            $rewardResult = $this->getUserTaskReward($uid, $taskId);
            if ($rewardResult) {
                if ($taskId != $this->config->taskIds->userGuide) {//新手引导总奖励
                    //查询下一个新手引导任务
                    $nextTaskInfo = \Micro\Models\Task::findfirst("taskType=" . $this->config->taskType->guide . " and status=1 and taskSort>" . $info->taskSort . " order by taskSort asc");
                    $nextTaskId = $this->config->taskIds->userGuide; //下一个任务id
                    if ($nextTaskInfo != false) {//不是最后一个新手引导任务
                        $nextTaskId = $nextTaskInfo->taskId;
                    } else {//如果是最后一个新手任务
                        $this->editUserTask($uid, $this->config->taskIds->userGuide, $this->config->taskStatus->done);
                    }
                }

                if ($taskId == $this->config->taskIds->pay) {//如果是新手引导里充值任务
                    $reward['cash'] = $rewardResult;
                } else {
                    //获得礼包详细信息
                    //$configMgr = $this->di->get('configMgr');
                    //$reward = $configMgr->getgiftPackageBaseConfig($rewardResult, true);
                    $reward['giftPackageId'] = $rewardResult;
                }

                $user = UserFactory::getInstance($uid);
                $userInfo = $user->getUserInfoObject()->getUserProfiles();
                $return['cash'] = $userInfo['cash'];
                $return['coin'] = $userInfo['coin'];
                $return['reward'] = $reward;
                $return['nextTaskId'] = $nextTaskId;
                return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            }
            return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
