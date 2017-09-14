<?php

namespace Micro\Frameworks\Pay;

use Phalcon\DI\FactoryDefault;
use Micro\Models\Order;
use Micro\Frameworks\Logic\User\UserFactory;

class OrderMgr {

    protected $di;
    protected $config;
    protected $userAuth;
    protected $uid;
    protected $user;
    protected $status;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->userAuth = $this->di->get('userAuth');
        $this->user = $this->userAuth->getUser();
        $this->status = $this->di->get('status');
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【order】 error : ' . $errInfo);
    }

    /*
     * 插入订单表
     * @param totalFee 金额
     * @param orderId 订单号
     * @param  payType 支付类型
     * @return 返回操作结果
     */

    public function addOrder($totalFee, $payType, $orderType = 0, $receiveUid = 0) {
        if (!$this->user) {
            return 0;
        }
        $this->uid = $this->user->getUid();
        try {
            $orderId = date('YmdHis') . mt_rand(100000, 999999);
            $order = new Order();
            $order->uid = $this->uid;
            $order->orderId = $orderId;
            $order->createTime = time();
            $order->cashNum = $totalFee * $this->config->cashScale;
            $order->totalFee = $totalFee;
            $order->payType = $payType;
            $order->orderType = $orderType;
            $order->receiveUid = $receiveUid;
            $order->status = $this->config->payStatus->ing;
            $order->isDelete = 0;
            $result = $order->save();
            if ($result) {
                return $orderId;
            }
            return 0;
        } catch (\Exception $e) {
            $this->errLog('addOrder error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * 支付成功
     * @param orderId 订单号
     * @return 返回操作结果
     */
    public function editOrder($orderId, $tradeNo, $rmb = 0) {
        try {
            //查询订单
            $orderInfo = \Micro\Models\Order::findFirst("orderId='{$orderId}' and status<>" . $this->config->payStatus->success);
            if ($orderInfo == FALSE) {
                return 0;
            }

            if($rmb > 0){
                // 判断支付金额是否正确
                if($orderInfo->totalFee != $rmb){
                    return FALSE;
                }
            }

            $orderInfo->payTime = time();
            $orderInfo->status = $this->config->payStatus->success;
            $orderInfo->tradeNo = $tradeNo;
            $result = $orderInfo->save();
            if ($result) {
                $receiveUid = $orderInfo->receiveUid;
                if ($receiveUid) {//给别人充值
                    $uid = $receiveUid;
                } else {//给自己充值
                    $uid = $orderInfo->uid;
                }
                //充值成功，单点广播
                $user = UserFactory::getInstance($uid);
                $accountId = $user->getUserInfoObject()->getAccountId();
                $roomBase = new \Micro\Frameworks\Logic\Room\RoomBase();
                $roomList = $roomBase->getUsersWhereIn($uid);
                $ArraySubData['controltype'] = "charge";
                $broadData = array();
                /** if ($orderInfo->orderType == $this->config->orderType->guide) {//新手引导
                  $broadData['taskId'] = $this->config->taskIds->pay;
                  //完成新手引导的充值任务
                  $taskMgr = $this->di->get('taskMgr');
                  $taskMgr->setUserGuideTask($this->config->taskIds->pay);
                  }* */
                $ArraySubData['data'] = $broadData;
                if ($roomList) {
                    foreach ($roomList as $roomVal) {
                        $comm = $this->di->get('comm');
                        $comm->roomNotify($roomVal['roomid'], $accountId, $ArraySubData);
                    }
                }
                return true;
            }
            return;
        } catch (\Exception $e) {
            $this->errLog('editOrder error orderId=' . $orderId . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

    /**
     * 添加累充活动
     */
    public function addPayActivity($uid) {
        $payMoney = $this->checkPaySum($uid); //查询用户累计充值金额
        if ($payMoney) {
            $sendUser = UserFactory::getInstance($uid);
            $result = FALSE;
            foreach ($this->config->activities as $key => $val) {
                if ($payMoney >= $val->max || $payMoney >= $val->min && $payMoney < $val->max) {
                    $result = $sendUser->getUserActivityObject()->setUserActivity($uid, $val->activityId, $val->giftTime);
                    //改为自动领取 edit by 2016/01/20
                    if ($result) {
                        $res = $sendUser->getUserActivityObject()->getPayActivityGift($key);
                        if ($res['code'] == $this->status->getCode("OK")) {
                            //给用户发送通知
                            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $val->message, 'link' => '', 'operType' => ''));
                            $result = true;
                        }
                    }
                }
            }
            return $result;

            /* if ($result) {
              //给用户发送通知
              $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->totalPay);
              $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
              return $result;
              } */
        }
        return;
    }

    //查询支付是否成功了
    public function getOrderStatus($orderId) {
        try {
            if ($this->user == NULL) {//未登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $orderInfo = \Micro\Models\Order::findfirst("orderId=" . $orderId . " and uid=" . $this->user->getUid());
            if ($orderInfo == false) {//订单不存在
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($orderInfo->status != $this->config->payStatus->success) {//未充值成功
                return $this->status->retFromFramework($this->status->getCode('PAY_NOT_FINISH'));
            }
            //充值成功，查询是否有累计充值活动
            foreach($this->config->activities as $val){
                $activitiesId[] = $val->activityId;
            }
            $activitiesIds=  implode(',', $activitiesId);
            $str="(".$activitiesIds.")";
            $where = 'uid = ' . $orderInfo->uid . ' and activityId in '.$str.'  and status = 1 and expireTime>' . time();
            $info = \Micro\Models\ActivityLog::findfirst($where);
            if ($info) {
                $data['isFirstCharge'] = 1; //累计充值活动可领奖
            } else {
                $data['isFirstCharge'] = 0;
            }
            $data['totalFee'] = $orderInfo->totalFee;
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), 'errorMessage =

                ' . $e->getMessage());
        }
    }

    /**
     * 查询是否首充
     */
    public function checkIsFirstPay($uid) {
        try {
            //查询订单
            $list = \Micro\Models\Order::find(array(
                        "conditions" => "uid = " . $uid . " AND  status=" . $this->config->payStatus->success . " and payType<" . $this->config->payType->innerpay->id . " limit 2"
            ));
            $list = $list->toArray();
            if (count($list) == 1) {//是首充
                return $list[0]['totalFee']; //首充金额
            }
            return 0;
        } catch (\Exception $e) {
            $this->errLog('checkIsFirstPay error uid = ' . $uid . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //查询第一笔订单
    public function checkFirstPayByType($uid, $orderType = 0) {
        try {

            $cash = 0;
            //查询订单
            $where = "uid = " . $uid . " AND  status=" . $this->config->payStatus->success . " and payType<" . $this->config->payType->innerpay->id;
            $orderType && $where.=" and orderType=" . $orderType;
            $where.=" order by createTime asc";
            $info = \Micro\Models\Order::findfirst($where);
            if ($info != false) {
                $cash = $info->cashNum;
            }
            return $cash;
        } catch (\Exception $e) {
            $this->errLog('checkIsFirstPay error uid = ' . $uid . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //查询累计充值金额
    public function checkPaySum($uid) {
        $sum = 0;
        try {
            //累计充值活动开始时间
            $time = 1435921200; //2015/07/03 19:00
            //查询订单
            $result = \Micro\Models\Order::sum(array(
                        'column' => 'totalFee',
                        "conditions" => "uid = " . $uid . " AND  status=" . $this->config->payStatus->success . " and payType<" . $this->config->payType->innerpay->id . " and payTime>" . $time
            ));

            if ($result != false) {
                $sum = $result;
            }
            return $sum;
        } catch (\Exception $e) {
            $this->errLog('checkPaySum error uid = ' . $uid . ' errorMessage = ' . $e->getMessage());
            return $sum;
        }
    }

    //充值成功后的操作
    public function paySuccessOperation($orderId) {
        try {
            $data = array();
            //查询订单
            $orderInfo = \Micro\Models\Order::findFirst("orderId = '{$orderId}' and status = " . $this->config->payStatus->success);
            if ($orderInfo == false) {
                return;
            }
            $data['payType'] = $orderInfo->payType; //支付方式
            $data['orderId'] = $orderId; //订单号
            $cashNum = $orderInfo->cashNum; //聊币数量
            //写入聊币记录表
            if ($orderInfo->receiveUid) {//替别人代充
                $cashSource = $this->config->cashSource->givePay;
                $uid = $orderInfo->receiveUid;
            } else {//给自己充值
                $cashSource = $this->config->cashSource->pay;
                $uid = $orderInfo->uid;
            }
            //给用户添加聊币
            $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
            $userCash->addUserCash($cashNum, $uid);
            //写入聊币日志
            $userCash->addCashLog($cashNum, $cashSource, $orderId, $uid);
            //添加累充活动
            $this->addPayActivity($orderInfo->uid);
            //添加首充任务
            //$this->taskMgr->setUserTask($uid, $this->config->taskIds->firstPay);
            if ($orderInfo->receiveUid) {//替别人代充
                //给接收者发送通知
                $user = UserFactory::getInstance($orderInfo->uid);
                $userDetail = $user->getUserInfoObject()->getUserInfo();
                $receiveUser = UserFactory::getInstance($orderInfo->receiveUid);
                $paramArray = array(0 => $userDetail['nickName'], 1 => $orderInfo->uid, 2 => $orderInfo->cashNum);
                $content = $receiveUser->getUserInformationObject()->getInfoContent($this->config->informationCode->givePay, $paramArray);
                $receiveUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
            }
            return $data;
        } catch (\Exception $e) {
            $this->errLog('paySuccessOperation error orderId = ' . $orderId . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

}
