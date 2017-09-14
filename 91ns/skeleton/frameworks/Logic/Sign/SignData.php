<?php

namespace Micro\Frameworks\Logic\Sign;

use Micro\Models\SignLog;
use Phalcon\DI\FactoryDefault;
use Micro\Models\SignConfigs;
use Micro\Models\Sign;
use Micro\Frameworks\Logic\User\UserData\UserCash;
use Micro\Frameworks\Logic\User\UserFactory;

//签到操作类
class SignData {

    protected $di;
    protected $config;
    protected $modelsManager;
    protected $status;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->modelsManager = $this->di->get('modelsManager');
    }

    //查询用户签到情况
    public function getUserSignList($uid) {
        $return = array();
        //$return['coin'] = $this->config->signConfig->dayCoin; //每日签到可领取的聊豆
        $giftRes = \Micro\Models\GiftConfigs::findfirst($this->config->signConfig->giftId);
        $return['remark'] = $this->config->signConfig->giftNum . "朵" . $giftRes->name;
        $return['configName'] = $this->config->signConfig->configName;
        $return['giftNum'] = $this->config->signConfig->giftNum;
        try {
            //查询用户本月签到领取情况
            $thisMonth = date("Ym");
            $newRewardLog = array();
            $userType = 0;
            if ($uid) {
                $rewardLog = Sign::find("uid=" . $uid . " and month=" . $thisMonth);
                if ($rewardLog->valid()) {
                    foreach ($rewardLog as $k => $v) {
                        $newRewardLog[$v->type] = $v->status;
                    }
                }
                //查询用户类型
                $userinfo = \Micro\Models\Users::findfirst($uid);
                $userType = $userinfo->userType;
            }
            $list = array();

            //查询签到配置
            $rewardLogList = SignConfigs::find();
            if ($rewardLogList->valid()) {
                $itemIdArr = array();
                $giftIdArr = array();
                $carIdArr = array();
                foreach ($rewardLogList as $kr => $vr) {
                    $arr = json_decode($vr->package, true);
                    foreach ($arr as $va) {
                        if (!$userType) {
                            if ($va['type'] == $userType) {
                                $giftPackageIdArr[] = $va['ids']; //礼包id
                                continue;
                            }
                        }
                        if (!$va['type']) {
                            $giftPackageIdArr[] = $va['ids']; //礼包id
                        }
                    }
                }
                //礼包配置
                $giftPackageIds = implode(',', $giftPackageIdArr);
                $giftPackConfigs = \Micro\Models\GiftPackageConfigs::find("id in (" . $giftPackageIds . ")");

                if ($giftPackConfigs->valid()) {
                    foreach ($giftPackConfigs as $vk) {
                        $types = json_decode($vk->items, true);
                        foreach ($types as $vt) {
                            if ($vt['type'] == $this->config->itemType->item) {
                                $itemIdArr[] = $vt['id']; //道具id
                            } else if ($vt['type'] == $this->config->itemType->car) {
                                $carIdArr[] = $vt['id']; //座驾id
                            } else if ($vt['type'] == $this->config->itemType->gift) {
                                $giftIdArr[] = $vt['id']; //礼物id
                            }
                        }
                    }
                }

                $itemIds = implode(',', $itemIdArr);
                $carIds = implode(',', $carIdArr);
                $giftIds = implode(',', $giftIdArr);
                $itemList = \Micro\Models\ItemConfigs::find("id in (" . $itemIds . ")"); //查询道具配置
                $newItemList = array();
                if ($itemList->valid()) {
                    foreach ($itemList as $kn => $vn) {
                        $newItemList[$vn->id]['itemId'] = $vn->id; //道具ID
                        $newItemList[$vn->id]['itemName'] = $vn->name; //道具名称
                        $newItemList[$vn->id]['itemName'] = $vn->name; //道具名称
                        $newItemList[$vn->id]['itemPrice'] = $vn->cash; //道具价钱
                        $newItemList[$vn->id]['priceType'] = 1; //聊币
                        $newItemList[$vn->id]['configName'] = $vn->configName; //配置名称，索引图片别名用
                        $newItemList[$vn->id]['type'] = $vn->type; //物品类型
                    }
                }

                $carList = \Micro\Models\CarConfigs::find("id in (" . $carIds . ")"); //查询座驾配置
                $newCarList = array();

                if ($carList->valid()) {
                    foreach ($carList as $kn => $vn) {
                        $newCarList[$vn->id]['itemId'] = $vn->id; //道具ID
                        $newCarList[$vn->id]['itemName'] = $vn->name; //座驾名称
                        $newCarList[$vn->id]['itemPrice'] = $vn->price; //座驾价钱
                        $newCarList[$vn->id]['priceType'] = 1; //聊币
                        $newCarList[$vn->id]['configName'] = $vn->configName; //配置名称，索引图片别名用
                    }
                }

                $giftList = \Micro\Models\GiftConfigs::find("id in (" . $giftIds . ")"); //查询礼物配置
                $newGiftList = array();
                if ($giftList->valid()) {
                    foreach ($giftList as $kn => $vn) {
                        $newGiftList[$vn->id]['itemId'] = $vn->id; //道具ID
                        $newGiftList[$vn->id]['itemName'] = $vn->name; //礼物名称
                        $newGiftList[$vn->id]['itemPrice'] = $vn->cash > 0 ? $vn->cash : $vn->coin; //礼物价钱
                        $newGiftList[$vn->id]['priceType'] = $vn->cash > 0 ? 1 : 2; //价钱类型 1：聊币2：聊豆
                        $newGiftList[$vn->id]['configName'] = $vn->configName; //配置名称，索引图片别名用
                    }
                }

                foreach ($rewardLogList as $key => $val) {
                    if (!empty($newRewardLog[$val->id])) {
                        $status = $newRewardLog[$val->id];
                    } else {
                        $status = 0;
                    }
                    $data['status'] = $status;  //签到状态
                    $data['desc'] = $val->desc; //签到描述
                    $data['daysNum'] = $val->daysNum; //签到天数


                    $arr = json_decode($val->package, true);
                    $idsarr = explode(',', $arr[0]['ids']);
                    $giftPackConfigsInfo = \Micro\Models\GiftPackageConfigs::findfirst("id=" . $idsarr[0]);
                    $arr = json_decode($giftPackConfigsInfo->items, true);

                    if ($arr[0]['type'] == $this->config->itemType->item) {//道具
                        $data['itemId'] = $newItemList[$arr[0]['id']]['itemId']; //道具ID
                        $data['itemName'] = $newItemList[$arr[0]['id']]['itemName']; //道具名称
                        $data['itemPrice'] = $newItemList[$arr[0]['id']]['itemPrice']; //道具价值
                        $data['priceType'] = $newItemList[$arr[0]['id']]['priceType']; //价钱类型
                        $data['configName'] = $newItemList[$arr[0]['id']]['configName']; //配置名称，索引图片别名用
                        $itemConfigType = $newItemList[$arr[0]['id']]['type'];
//                        if ($itemConfigType == $this->config->itemConfigType->badge) {//徽章
//                            $arr[0]['type'] = 5;
//                        }
                        if ($newItemList[$arr[0]['id']]['itemId'] == 15) {//经验值
                            $arr[0]['type'] = 6;
                        }
                    } elseif ($arr[0]['type'] == $this->config->itemType->car) {//座驾
                        $data['itemId'] = $newCarList[$arr[0]['id']]['itemId']; //座驾ID
                        $data['itemName'] = $newCarList[$arr[0]['id']]['itemName']; //座驾名称
                        $data['itemPrice'] = $newCarList[$arr[0]['id']]['itemPrice']; //座驾价值
                        $data['priceType'] = $newCarList[$arr[0]['id']]['priceType']; //价钱类型
                        $data['configName'] = $newCarList[$arr[0]['id']]['configName']; //配置名称，索引图片别名用
                    } elseif ($arr[0]['type'] == $this->config->itemType->gift) {//礼物
                        $data['itemId'] = $newGiftList[$arr[0]['id']]['itemId']; //礼物ID
                        $data['itemName'] = $newGiftList[$arr[0]['id']]['itemName']; //道具名称
                        $data['itemPrice'] = $newGiftList[$arr[0]['id']]['itemPrice']; //道具价值
                        $data['priceType'] = $newGiftList[$arr[0]['id']]['priceType']; //价钱类型
                        $data['configName'] = $newGiftList[$arr[0]['id']]['configName']; //配置名称，索引图片别名用
                    } else {
                        $data['itemName'] = '';
                        $data['itemPrice'] = 0;
                        $data['configName'] = '';
                        $data['itemPrice'] = 1;
                    }
                    $data['itemNum'] = $arr[0]['type'] == 6 ? 1 : $arr[0]['num']; //奖品数量
                    $data['id'] = $val->id; //签到类型id
                    $data['itemType'] = $arr[0]['type']; //奖品类型：道具、座驾、礼物

                    $data['signType'] = $val->type; //签到类型：连续签到、累计签到
                    array_push($list, $data);
                    unset($data);
                }
            }
            $return['rewardList'] = $list;

            $time = strtotime($thisMonth . "01");
            //查询本月已签到的日期
            $signLog = SignLog::find("uid=" . $uid . " and createTime>=" . $time);
            $signdata = array();
            if ($signLog->valid()) {
                foreach ($signLog as $ks => $vs) {
                    $signdata[] = date("j", $vs->createTime);
                }
            }
            $return['signDay'] = $signdata;


            //查询本月累计签到天数
            $total = count($signLog);
            $return['accumulateDay'] = $total ? $total : 0;
            $return['year'] = date('Y'); //当前年
            $return['month'] = date('n'); //当前月
            $return['day'] = date('j'); //当前日
            //查询本月连续签到天数
            $continueDay = SignLog::findfirst("uid=" . $uid . " and createTime>=" . $time . " order by createTime desc");
            $return['continueDay'] = $continueDay != false ? $continueDay->conTimes : 0;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //用户签到
    public function setUserSign($uid) {
        try {
            $today = strtotime(date("Y-m-d"));
            $info = SignLog::findfirst("uid=" . $uid . " and createTime>=" . $today); //查询今天是否签到过
            if ($info != false) {//今天已签到
                return $this->status->retFromFramework($this->status->getCode('HAS_SIGN'));
            }

            $return['type'] = $this->config->itemType->gift;
            $return['num'] = $this->config->signConfig->giftNum;
            $return['itemId'] = $this->config->signConfig->giftId;

            $conTimes = 1; //连续签到天数
            //判断是否是本月第一天
            $firstday = date('Y-m-01', time());
            if ($firstday == date("Y-m-d")) {//是
                $new = new SignLog();
                $new->uid = $uid;
                $new->createTime = time();
                $new->conTimes = $conTimes;
                $new->save();

                //每日签到送奖励
                $user = UserFactory::getInstance($uid);
                $user->getUserItemsObject()->giveGift($this->config->signConfig->giftId, $this->config->signConfig->giftNum);

                return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            }

            $yestoday = strtotime(date("Y-m-d", strtotime("-1 day")));
            //查询昨天是否有签到
            $yInfo = SignLog::findfirst("uid=" . $uid . " and createTime>" . $yestoday . " and createTime<" . $today);
            if ($yInfo != false) {
                $conTimes = $yInfo->conTimes + 1; //连续签到次数加1
            }
            $new = new SignLog();
            $new->uid = $uid;
            $new->createTime = time();
            $new->conTimes = $conTimes;
            $new->save();

            //每日签到送奖励
            $user = UserFactory::getInstance($uid);
            $user->getUserItemsObject()->giveGift($this->config->signConfig->giftId, $this->config->signConfig->giftNum);


            //查询本月累计签到天数
            $totalSign = SignLog::count("uid=" . $uid . " and createTime>=" . strtotime($firstday));
            if (!$totalSign || $totalSign == 1) {//未累计签到天数 或 才累计签到一天
                return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            }

            //判断是否有达到签到要求
            $signConfig = SignConfigs::find();
            $thisMonth = date("Ym");
            foreach ($signConfig as $key => $val) {
                $isOk = 0;
                if ($val->type == 1 && $totalSign == $val->daysNum) {//累计签到,已达到累计签到天数要求
                    $isOk = 1;
                } elseif ($val->type == 2 && $conTimes == $val->daysNum) {//连续签到,已达到连续签到天数要求
                    $isOk = 1;
                }
                if ($isOk) {
                    $sign = Sign::findfirst("uid=" . $uid . " and type=" . $val->id . " and month=" . $thisMonth);
                    if ($sign == false) {
                        $newSign = new Sign();
                        $newSign->uid = $uid;
                        $newSign->month = $thisMonth; //月份
                        $newSign->type = $val->id; //领取类型
                        $newSign->status = 1; //可领取
                        $newSign->save();
                    }
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //用户领取签到奖励
    public function getUserSignReward($uid, $type, $roomId = 0) {
        try {
            $thisMonth = date("Ym");
            $sign = Sign::findfirst("uid=" . $uid . " and type=" . $type . " and month=" . $thisMonth);
            if ($sign == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($sign->status == 2) {//已领取过
                return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'));
            }
            $configInfo = SignConfigs::findfirst($type); //查询签到奖励
            if ($configInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            //修改领取状态
            $sign->status = 2;
            $sign->save();
            //查询用户类型
            $userinfo = \Micro\Models\Users::findfirst($uid);
            $userType = $userinfo->userType;

            $arr = json_decode($configInfo->package, true); //礼包

            $giftPackageIds = '';
            foreach ($arr as $val) {
                if ($val['type'] == $userType) {
                    $giftPackageIds = $val['ids']; //礼包id
                    break;
                } else {
                    if (!$val['type']) {
                        $giftPackageIds = $val['ids']; //礼包id
                    }
                }
            }
            $giftPackageArr = explode(',', $giftPackageIds);

            $user = UserFactory::getInstance($uid);
            foreach ($giftPackageArr as $vg) {
                $user->getUserItemsObject()->giveGiftPackage($vg, $roomId);
            }

            $return = array();
            if ($type == 4 && $roomId) {//如果是送经验值
                $userProfiles = \Micro\Models\UserProfiles::findFirst($uid);
                $return['richerExp'] = $userProfiles->exp3;
                $richerConfigs = \Micro\Models\RicherConfigs::findfirst('higher >= ' . $userProfiles->exp3 . ' and lower <= ' . $userProfiles->exp3);
                $return['richerHigher'] = $richerConfigs ? ($richerConfigs->higher + 1) : 0;
                $return['richerLower'] = $richerConfigs ? $richerConfigs->lower : 0;
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //判断今日是否可签到
    public function getOneSignStatus($uid) {
        try {
            $today = strtotime(date("Ymd"));
            $info = \Micro\Models\SignLog::findfirst("uid=" . $uid . " and createTime>=" . $today);
            if ($info == false) {//未签到
                $result['status'] = 0;
                return $this->status->retFromFramework($this->status->getCode('OK'), $result);
            }
            //已签到
            $result['status'] = 1;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //编辑礼包
    public function editGiftPackageConfig($id = 0, $param = array()) {
        try {
            if (!$id) {//新增
                $info = new \Micro\Models\GiftPackageConfigs();
            } else {//编辑
                $info = \Micro\Models\GiftPackageConfigs::findfirst($id);
            }
            $info->name = $param['name'];
            $info->desc = $param['desc'];
            $info->items = json_encode($param['items']);
            $info->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //编辑签到配置
    public function editSignGiftPackageConfig($id = 0, $param = array()) {
        try {
            if (!$id) {//新增
                $info = new \Micro\Models\SignConfigs();
            } else {//编辑
                $info = \Micro\Models\SignConfigs::findfirst($id);
            }
            $info->package = json_encode($param['package']);
            $info->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
