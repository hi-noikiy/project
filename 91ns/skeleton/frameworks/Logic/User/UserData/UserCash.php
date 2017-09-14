<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Micro\Frameworks\Logic\User\UserFactory;
use Phalcon\DI\FactoryDefault;
use Micro\Models\CashLog;

class UserCash extends UserDataBase {

    protected $di;
    protected $config;
    protected $modelsManager;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->modelsManager = $this->di->get('modelsManager');
    }

    /**
     * 插入聊币记录表
     * @param  num 聊币数量
     * @param  source 来源
     * @param  orderId 订单id  当来源是任务时，orderId用来存任务id
     * @return 返回操作结果
     */
    public function addCashLog($num, $source, $orderId, $uid) {
        try {
            $log = new CashLog();
            $log->uid = $uid;
            $log->num = $num;
            $log->source = $source;
            $log->orderId = $orderId;
            $log->createTime = time();
            $log->save();
            return true;
        } catch (\Exception $e) {
            $this->errLog('addCashLog error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 给用户添加聊币、vip经验值
     * @param  $cashNum 聊币数量
     * @param  $vipNum，vip经验值
     * @return 返回操作结果
     */
    public function addUserCash($cashNum, $uid, $vipNum = 0) {
        try {
            $userInfo = \Micro\Models\UserProfiles::findFirst("uid=" . $uid);
            if ($userInfo == false) {
                //写入日志
                $this->errLog('addUserCash error: uid=' . $uid . ' find this uid fail');
                return false;
            }
            //$vipLevel = 0;
            //$level1 = 0;
            //修改聊币总数
            //$userInfo->cash += $cashNum;
            // BY 2015/05/28
            //vip系统已改版为普通vip跟至尊vip，所以取消增加vip经验值、vip升级
            //....
//            if ($vipNum && $userInfo->level1) {
//                //修改vip经验值
//                $userInfo->exp1 += $vipNum;
//                //判断是否升等级
//                $vipExp = $userInfo->exp1; //当前经验值
//                $vipConfigInfo = \Micro\Models\VipConfigs::findFirst("lower<=" . $vipExp . " and higher>=" . $vipExp); //查询vip等级配置表
//                if ($vipConfigInfo != false) {
//                    $vipLevel = $vipConfigInfo->level; //查出属于哪个vip等级
//                    $level1 = $userInfo->level1; //充值前的vip等级
//                    $vipLevel > $level1 && $userInfo->level1 = $vipLevel; //如果升级了，则修改等级
//                    $carId = $vipConfigInfo->carId;
//                }
//                //送座驾
//                if ($vipLevel > $level1 && $carId) {//vip升级,并且有座驾
//                    $user = UserFactory::getInstance($uid);
//                    if ($vipLevel - $level1 == 1) {//vip等级只升了1级
//                        $user->getUserItemsObject()->giveCar($carId);
//                    } else {//vip等级跨级
//                        $vipConList = \Micro\Models\VipConfigs::find("level>" . $level1 . " and level<=" . $vipLevel);
//                        foreach ($vipConList as $k => $v) {
//                            if ($v->carId) {
//                                $user->getUserItemsObject()->giveCar($v->carId);
//                            }
//                        }
//                    }
//                }
//            }
            // $result = $userInfo->save();
            $sql = "update \Micro\Models\UserProfiles set cash=cash+" . $cashNum . " where uid=" . $uid;
            $query = $this->modelsManager->createQuery($sql);
            $query->execute();
            return true;
            // if ($result) {
//                if ($vipNum && $vipLevel > $level1) {
//                    return 'levelUp'; //升级
//                }
            //   return true;
            // }
            // return false;
        } catch (\Exception $e) {
            $this->errLog('addUserCash error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 给用户发送vip
     * @param time 增加vip的有效期，单位秒
     */
    public function addUserVipTime($vipType, $time, $uid) {
        try {
            $userInfo = \Micro\Models\UserProfiles::findFirst("uid=" . $uid);
            if ($userInfo == false) {
                return false;
            }
            if ($vipType == 2) {//至尊vip
                if ($userInfo->vipExpireTime2 < time()) {//vip到期时间
                    $userInfo->vipExpireTime2 = time() + $time;
                } else {
                    $userInfo->vipExpireTime2 += $time;
                }
                !$userInfo->level6 && $userInfo->level6 = 1; //如果是vip0，则改为vip1
            } elseif ($vipType == 1) {//普通vip
                if ($userInfo->vipExpireTime < time()) {//vip到期时间
                    $userInfo->vipExpireTime = time() + $time;
                } else {
                    $userInfo->vipExpireTime += $time;
                }
                !$userInfo->level1 && $userInfo->level1 = 1; //如果是vip0，则改为vip1
            }
            return $userInfo->save();
        } catch (\Exception $e) {
            $this->errLog('addUserVipTime error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 查询用户首充获得的聊币
     */
    public function getUserPayCash($uid) {
        try {
            $info = \Micro\Models\CashLog::findfirst(array(
                        "conditions" => "uid=" . $uid . " AND source=" . $this->config->cashSource->pay,
                        "order" => "id ASC"
            ));
            return $info->num;
        } catch (\Exception $e) {
            $this->errLog('getUserPayCash error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //给用户送聊豆
    public function sendUserCoin($num, $uid) {
        try {
            $userInfo = \Micro\Models\UserProfiles::findFirst("uid=" . $uid);
            if ($userInfo == false) {
                return false;
            }
            //修改聊豆总数
            // $userInfo->coin += $num;
            // return $userInfo->save();
            $sql = "update \Micro\Models\UserProfiles set coin=coin+" . $num . " where uid=" . $uid;
            $query = $this->modelsManager->createQuery($sql);
            $query->execute();
            return true;
        } catch (\Exception $e) {
            $this->errLog('sendUserCoin error uid=' . $uid . ' errorMessage = ' . $e->getMessage());
            return false;
        }
    }

}
