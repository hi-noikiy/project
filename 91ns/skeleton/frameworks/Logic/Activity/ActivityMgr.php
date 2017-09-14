<?php

namespace Micro\Frameworks\Logic\Activity;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Models\MoonEnergy;
use Micro\Models\UserItem;
use Micro\Frameworks\Logic\User\UserData\UserCash;

class ActivityMgr {

    protected $di;
    protected $status;
    protected $config;
    protected $validator;
    protected $comm;
    protected $userAuth;
    protected $modelsManager;
    protected $db;
    protected $userCash;
    protected $roomModule;
    protected $normalLib;
    protected $logger;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->comm = $this->di->get('comm');
        $this->userAuth = $this->di->get('userAuth');
        $this->db = $this->di->get('db');
        $this->userCash = new UserCash();
        $this->modelsManager = $this->di->get('modelsManager');
        $this->roomModule = $this->di->get('roomModule');
        $this->normalLib = $this->di->get('normalLib');
        $this->logger = $this->di->get('logger');
    }

    //查询博饼情况
    public function getRoomBobingInfo($anchorUid) {
        if (time() > $this->config->midAutumn->endTime) {//活动已结束
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $postData['uid'] = $anchorUid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            //查询博饼活动状态
            $bobingData = \Micro\Models\MoonEnergy::findfirst("uid=" . $anchorUid . " and type=2");
            if ($bobingData != false) {
                $result['energy'] = $bobingData->totalNum;
            } else {
                $result['energy'] = 0;
            }
            $result['energyLimit'] = $this->config->midAutumn->energyLimit;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取用户中秋博饼活动信息
    public function getUserBobingInfo() {
        if (time() > $this->config->midAutumn->endTime) {//活动已结束
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $user = $this->userAuth->getUser();
        $uid = 0;
        if ($user) {
            $uid = $user->getUid();
        }
        try {
            //查询用户自己的月光值
            $uid && $energyData = \Micro\Models\MoonEnergy::findfirst("uid=" . $uid . " and type=1");
            if (!$uid || $energyData == false) {
                $return['moon'] = 0;
            } else {
                $return['moon'] = $energyData->leftNum;
            }
            //查询是否有每日首次免费的
            $today = strtotime(date("Ymd"));
            $uid && $bobingLog = \Micro\Models\MoonBobingLog::findfirst("uid=" . $uid . " and createTime>=" . $today . " and moonValue=0");
            if (!$uid || $bobingLog == false) {
                $return['isFree'] = 1;
            } else {
                $return['isFree'] = 0;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //用户博饼摇骰子
    public function boboingShakeDice($anchorUid, $times) {
        if (time() > $this->config->midAutumn->endTime) {//活动已结束
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $times = intval($times);
        if ($times <= 0 || $times > $this->config->midAutumn->bobingTimesLimit) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        try {
            //查询博饼活动状态
            $bobingData = \Micro\Models\MoonEnergy::findfirst("uid=" . $anchorUid . " and type=2");
            if ($bobingData == false || $bobingData->totalNum < $this->config->midAutumn->energyLimit) {
                return $this->status->retFromFramework($this->status->getCode('GAME_CAN_NOT_START'));
            }

            $isNeedMoonValue = 1; //是否需要消耗月光值
            $consumeMoonValue = $this->config->midAutumn->consumeMoonValue;

            //查询今天是否有免费的机会
            $today = strtotime(date("Ymd"));
            $bobingLog = \Micro\Models\MoonBobingLog::findfirst("uid=" . $uid . " and createTime>=" . $today . " and moonValue=0");
            if ($bobingLog == false) {
                $isFree = 1;
            } else {
                $isFree = 0;
            }

            //每日首次博饼免费，其他博饼每次消耗10点月光值
            if ($times == 1 && $isFree) {//可免费
                $isNeedMoonValue = 0;
                $consumeMoonValue = 0;
                $isFree = 0;
            }

            //查询月光值
            $moonData = \Micro\Models\MoonEnergy::findfirst("uid=" . $uid . " and type=1");
            $moon = 0;
            if ($moonData != false) {
                $moon = $moonData->leftNum;
            }

            if ($isNeedMoonValue) {
                $consumeMoonValue = $this->config->midAutumn->consumeMoonValue * $times; //需消耗的月光值
                if ($moonData == false || $moonData->leftNum < $consumeMoonValue) {
                    return $this->status->retFromFramework($this->status->getCode('MOON_VALUE_NOT_ENOUGH'));
                }
                //扣除月光值
                $moonData->leftNum-=$consumeMoonValue;
                $moonData->save();
                $moon = $moonData->leftNum;
            }

            $return = array();
            for ($i = 0; $i < $times; $i++) {
                //摇骰子
                $diceResult = $this->newShakeDiceResult();
                //记录到骰子记录表
                $log = new \Micro\Models\MoonBobingLog();
                $log->uid = $uid;
                $log->moonValue = $consumeMoonValue / $times;
                $log->resultCode = $diceResult['resultCode'];
                $log->points = $diceResult['points'];
                $log->isChampion = $diceResult['isChampion'];
                $log->createTime = time();
                $log->save();

                $resultCodeShow = $diceResult['resultCode'];

                //中奖送奖励
                $rewardResult = array();
                if ($resultCodeShow) {
                    if ($diceResult['isChampion']) {//状元
                        $resultCodeShow = $this->changeCode($diceResult['resultCode']);
                        //世界喇叭
                        $userInfo = $user->getUserInfoObject()->getUserInfo();
                        $roomData = \Micro\Models\Rooms::findfirst("uid=" . $anchorUid);
                        $hostUser = UserFactory::getInstance($anchorUid);
                        $hoserUserInfo = $hostUser->getUserInfoObject()->getUserInfo();
                        $broadData['hostUid'] = $anchorUid;
                        $broadData['roomId'] = $roomData->roomId;
                        $broadData['hostName'] = $hoserUserInfo['nickName']; //主播昵称
                        $broadData['type'] = 2;
                        $broadData['userdata'] = array('nickName' => $userInfo['nickName']);
                        $ArraySubData['controltype'] = "worldbroadcast";
                        $ArraySubData['data'] = $broadData;
                        $roomModule = $this->di->get('roomModule');
                        $roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData);
                    }
                    //送奖品
                    $rewardResult = $this->sendBobingGift($user, $resultCodeShow);
                }
                $data['points'] = $diceResult['points']; //骰子点数
                $data['resultCode'] = $resultCodeShow; //博饼结果
                $data['gift'] = isset($rewardResult['giftIds']) ? $rewardResult['giftIds'] : array(); //礼物id
                $list[] = $data;
            }
            $return['list'] = $list;
            $return['isFree'] = $isFree;
            $return['moon'] = $moon;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询状元排行榜
    public function checkZhuangyuanRank($num) {
        try {
            $return = array();
//            $sql = "select nickName,points from \Micro\Models\MoonBobingLog l inner join \Micro\Models\UserInfo ui on l.uid=ui.uid where l.isChampion=1 group by l.uid order by l.resultCode desc, l.createTime desc limit " . $num;
//            $query = $this->modelsManager->createQuery($sql);
//            $result = $query->execute();
            $sql = " select nickName,points, resultCode from (select * from pre_moon_bobing_log order by resultCode desc,createTime desc) l INNER JOIN  pre_user_info ui on l.uid=ui.uid where l.isChampion=1 group by l.uid order by resultCode desc,createTime desc limit " . $num;
            $result = $this->db->fetchAll($sql);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $data['rank'] = $key + 1;
                    $data['nickName'] = $val['nickName'];
                    $data['points'] = $val['points'];
                    $return[] = $data;
                    unset($data);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //摇骰子结果
    private function shakeDiceResult() {
        $rands = ''; //骰子数
        $isChampion = 0; //是否状元
        for ($i = 0; $i < 6; $i++) {
            $rand = mt_rand(1, 6);
            $rands .= $rand;
        }

        //状元插金花：摇出的结果为4个四点和2个一点。
        if (substr_count($rands, '4') == 4 && substr_count($rands, '1') == 2) {
            $resultCode = $this->config->midAutumn->reward->chajinhua->resultCode . "00";
            $isChampion = 1;
        }
        //六杯红：摇出的结果为6个四点。
        elseif (substr_count($rands, '4') == 6) {
            $resultCode = $this->config->midAutumn->reward->liubeihong->resultCode . "00";
            $isChampion = 1;
        }
        //遍地锦：摇出的结果为6个一样的数字（除四点）
        elseif (substr_count($rands, '1') == 6 || substr_count($rands, '2') == 6 || substr_count($rands, '3') == 6 || substr_count($rands, '5') == 6 || substr_count($rands, '6') == 6) {
            if (substr_count($rands, '1') == 6) {
                $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '01';
            } elseif (substr_count($rands, '2') == 6) {
                $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '02';
            } elseif (substr_count($rands, '3') == 6) {
                $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '03';
            } elseif (substr_count($rands, '5') == 6) {
                $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '05';
            } elseif (substr_count($rands, '6') == 6) {
                $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '06';
            }
            $isChampion = 1;
        }
        //五红：摇出的结果里有五个4点。
        elseif (substr_count($rands, '4') == 5) {
            if (substr_count($rands, '1') == 1) {
                $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "01";
            } elseif (substr_count($rands, '2') == 1) {
                $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "02";
            } elseif (substr_count($rands, '3') == 1) {
                $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "03";
            } elseif (substr_count($rands, '5') == 1) {
                $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "05";
            } elseif (substr_count($rands, '6') == 1) {
                $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "06";
            }
            $isChampion = 1;
        }

        //五子登科：摇出的结果有5个相同的数字（除四点外）
        elseif (substr_count($rands, '1') == 5 || substr_count($rands, '2') == 5 || substr_count($rands, '3') == 5 || substr_count($rands, '5') == 5 || substr_count($rands, '6') == 5) {
            for ($i = 1; $i <= 6; $i++) {
                if (substr_count($rands, $i) == 5) {
                    $resultCode = $this->config->midAutumn->reward->wuzidengke->resultCode . $i;
                    for ($j = 1; $i <= 6; $j++) {
                        if ($i != $j && substr_count($rands, $j) == 1) {
                            $resultCode.=$j;
                            break;
                        }
                    }
                }
            }

            $isChampion = 1;
        }
        //四点红：摇出的结果有4个四点。
        elseif (substr_count($rands, '4') == 4) {
            $arr = str_split($rands); //提取出字符，放入数组
            $plus = 0;
            foreach ($arr as $val) {
                if ($val != 4) {
                    $plus+=$val;
                }
            }
            $plus < 10 && $plus = '0' . $plus;
            $resultCode = $this->config->midAutumn->reward->sidianhong->resultCode . $plus;
            $isChampion = 1;
        }


        //对堂：摇出的结果为：1,2,3,4,5,6。可得对堂饼一个。
        elseif (substr_count($rands, '1') == 1 && substr_count($rands, '2') == 1 && substr_count($rands, '3') == 1 && substr_count($rands, '4') == 1 && substr_count($rands, '5') == 1 && substr_count($rands, '6') == 1) {
            $resultCode = $this->config->midAutumn->reward->duitang->resultCode;
        }
        //三红：摇出的结果为3个四点，可得探花饼一个。
        elseif (substr_count($rands, '4') == 3) {
            $resultCode = $this->config->midAutumn->reward->tanhua->resultCode;
        }
        //四进：摇出的结果为四个相同点数（除四点外，且可叠加二举或者一秀），可得进士饼一个，若有二举或者一秀，可同时拿一个举人饼或者秀才饼。
        elseif (substr_count($rands, '1') == 4 || substr_count($rands, '2') == 4 || substr_count($rands, '3') == 4 || substr_count($rands, '5') == 4 || substr_count($rands, '6') == 4) {
            $resultCode = $this->config->midAutumn->reward->jinshi->resultCode;
            if (substr_count($rands, '4') == 2) {//四进+二举
                $resultCode = $this->config->midAutumn->reward->jinshi_juren->resultCode;
            } elseif (substr_count($rands, '4') == 1) {//四进+一秀
                $resultCode = $this->config->midAutumn->reward->jinshi_xiucai->resultCode;
            }
        }

        //二举：摇出的结果里有2个四点，可得举人一个。
        elseif (substr_count($rands, '4') == 2) {
            $resultCode = $this->config->midAutumn->reward->juren->resultCode;
        }
        //一秀：摇出的结果里有1个四点，可得秀才饼一个
        elseif (substr_count($rands, '4') == 1) {
            $resultCode = $this->config->midAutumn->reward->xiucai->resultCode;
        }
        //罚黑：无任何奖励的即罚黑，不可得饼
        else {
            $resultCode = 0;
        }
        $result['points'] = $rands;
        $result['isChampion'] = $isChampion;
        $result['resultCode'] = $resultCode;
        return $result;
    }

    //送奖品
    private function sendBobingGift($user, $resultCodeShow) {
        $giftIds = array();
        $vip = 0; //vip
        $car = 0; //座驾
        $badge = 0; //徽章
        switch ($resultCodeShow) {
            case $this->config->midAutumn->reward->xiucai->showCode://一秀
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->xiucai->giftId,
                    'type' => $this->config->midAutumn->reward->xiucai->giftType,
                    'count' => 1,
                );

                break;
            case $this->config->midAutumn->reward->juren->showCode://二举
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->juren->giftId,
                    'type' => $this->config->midAutumn->reward->juren->giftType,
                    'count' => 1,
                );

                break;
            case $this->config->midAutumn->reward->jinshi->showCode://四进
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->jinshi->giftId,
                    'type' => $this->config->midAutumn->reward->jinshi->giftType,
                    'count' => 1,
                );

                break;
            case $this->config->midAutumn->reward->jinshi_juren->showCode://四进+二举
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->jinshi->giftId,
                    'type' => $this->config->midAutumn->reward->jinshi->giftType,
                    'count' => 1,
                );

                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->juren->giftId,
                    'type' => $this->config->midAutumn->reward->juren->giftType,
                    'count' => 1,
                );

                break;
            case $this->config->midAutumn->reward->jinshi_xiucai->showCode://四进+一秀
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->jinshi->giftId,
                    'type' => $this->config->midAutumn->reward->jinshi->giftType,
                    'count' => 1,
                );

                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->xiucai->giftId,
                    'type' => $this->config->midAutumn->reward->xiucai->giftType,
                    'count' => 1,
                );

                break;
            case $this->config->midAutumn->reward->tanhua->showCode://三红
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->tanhua->giftId,
                    'type' => $this->config->midAutumn->reward->tanhua->giftType,
                    'count' => 1,
                );

                $vip = 1; //普通VIP体验（1天）
                $vipTime = 86400;
                break;
            case $this->config->midAutumn->reward->duitang->showCode://对堂
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->duitang->giftId,
                    'type' => $this->config->midAutumn->reward->duitang->giftType,
                    'count' => 1,
                );

                $vip = 2; //至尊VIP体验（2天）
                $vipTime = 172800;
                break;

            case $this->config->midAutumn->reward->wuzidengkexiu->showCode://五子登科一秀
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->sidianhong->giftId,
                    'type' => $this->config->midAutumn->reward->sidianhong->giftType,
                    'count' => 1,
                );

                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->xiucai->giftId,
                    'type' => $this->config->midAutumn->reward->xiucai->giftType,
                    'count' => 1,
                );
                //状元徽章（3天）+中秋座驾（3天）
                $badge = $this->config->midAutumn->badge->zhuangyuan;
                $badgeTime = 259200;
                $car = $this->config->midAutumn->car->yueliangche;
                $carTime = 259200;

                break;
            case $this->config->midAutumn->reward->sidianhong->showCode://四点红
            case $this->config->midAutumn->reward->wuzidengke->showCode://五子登科
            case $this->config->midAutumn->reward->wuhong->showCode://五红
            case $this->config->midAutumn->reward->biandijin->showCode://遍地锦
            case $this->config->midAutumn->reward->liubeihong->showCode://六杯红
            case $this->config->midAutumn->reward->chajinhua->showCode://状元插金花
                $giftIds[] = array(
                    'id' => $this->config->midAutumn->reward->sidianhong->giftId,
                    'type' => $this->config->midAutumn->reward->sidianhong->giftType,
                    'count' => 1,
                );

                //状元徽章（3天）+中秋座驾（3天）
                $badge = $this->config->midAutumn->badge->zhuangyuan;
                $badgeTime = 259200;
                $car = $this->config->midAutumn->car->yueliangche;
                $carTime = 259200;
            default :
                break;
        }
        if ($vip) {//送vip
            $uid = $user->getUid();
            $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
            $userCash->addUserVipTime($vip, $vipTime, $uid);
        }
        if ($giftIds) {//送礼物
            foreach ($giftIds as $val) {
                $user->getUserItemsObject()->giveGift($val['id'], 1);
            }
        }
        if ($badge) {//送徽章
            $user->getUserItemsObject()->giveItem($badge, 1, $badgeTime);
        }
        if ($car) {//送座驾
            $user->getUserItemsObject()->giveCar($car, $carTime);
        }
        $return['giftIds'] = $giftIds;
        return $return;
    }

    private function changeCode($resultCode) {
        $str = substr($resultCode, 0, 3); //截取前三位
        if ($str == $this->config->midAutumn->reward->chajinhua->showCode) {//状元插金花
            return $this->config->midAutumn->reward->chajinhua->showCode;
        } else {//普通状元
            //判断是否五子登科带一秀
            $str2 = substr($resultCode, 0, 2); //截取前两位
            if ($str2 == $this->config->midAutumn->reward->wuzidengke->showCode) {
                if (substr_count($resultCode, '4') == 1) {//是五子登科带一秀
                    return $this->config->midAutumn->reward->wuzidengkexiu->showCode;
                }
            }
            return $str2;
        }
    }

    //生成概率表
    private function getOdds() {
        $array = $this->config->midAutumn->odds->toArray();
        $str = '';
        foreach ($array as $val) {
            for ($i = 0; $i < $val[1]; $i++) {
                $str.=$val[0];
            }
        }
        $str = str_shuffle($str); //随机打乱
        $oddsArray = str_split($str); //转成数组
        $valArray = array();
        foreach ($oddsArray as $val) {
            $valArray[] = "(" . $val . ")";
        }
        $values = implode(',', $valArray);

        //写入数据库
        $sql = "insert into pre_moon_odds(code)values" . $values;
        $connection = $this->di->get('db');
        $connection->execute($sql);
        return;
    }

    //摇骰子结果 改版
    private function newShakeDiceResult() {
        $count = \Micro\Models\MoonOdds::count("status=1");
        if ($count < 1000) {
            $this->getOdds();
        }
        $oddsResult = \Micro\Models\MoonOdds::findfirst("status=1 order by id asc");
        $code = $oddsResult->code;
        $oddsResult->status = 0;
        $oddsResult->save();
        $isChampion = 0;
        $resultCode = 0;
        if ($code == $this->config->midAutumn->odds->other[0]) {//没有中奖
            //从1,2,3,5,6中生成结果，生成第四位，第五位，第六位时，检测前面是否有三个相同数字，后面则不再生成此相同数字。
            $points = '';
            $rands1 = mt_rand(0, 4);
            $rands2 = mt_rand(0, 4);
            $rands3 = mt_rand(0, 4);
            $mt_str = array(1, 2, 3, 5, 6);
            $points1 = $mt_str[$rands1]; //第一位
            $points2 = $mt_str[$rands2]; //第二位
            $points3 = $mt_str[$rands3]; //第三位
            $points = $points1 . $points2 . $points3;
            if ($points1 == $points2 && $points2 == $points3) {//如果前三位相同
                $newMt_str = array();
                foreach ($mt_str as $key => $value) {
                    if ($value != $points1) {//则第四位不能和前三位相同
                        $newMt_str[] = $value;
                    }
                }
                $mt_str = $newMt_str;
            }
            //第四到第六位
            for ($j = 4; $j <= 6; $j++) {
                $repeat = 0;
                $rand4 = mt_rand(0, 4);
                for ($i = 1; $i <= 6; $i++) {
                    if (substr_count($points, $i) == 3) {//如果前面有三个相同数字
                        $repeat = $i; //重复的数字
                        break;
                    }
                }
                if ($repeat) {
                    $newMt_str = array();
                    foreach ($mt_str as $key => $value) {
                        if ($value != $repeat) {//则不再生成此相同数字
                            $newMt_str[] = $value;
                        }
                    }
                    $mt_str = $newMt_str;
                    $rand4 = mt_rand(0, 3);
                }
                $points4 = $mt_str[$rand4];
                $points.=$points4;
            }
        } elseif ($code == $this->config->midAutumn->odds->xiucai[0]) {//一秀
            //基础是1个四点，其他位数依从从1，2，3，5，6中生成， 但是生成倒数第二位的时候要注意。
            //如果检测前面3位生成的数字相同，第五位不再生成，前面的数字。
            //生成最后一位的时候要注意：
            //a.检测前面是否有三位相同数字，若有，则最后一位排除这个相同的数字。
            //b.检测前面5位是否均不相同，若是，则最后一位不生成剩余的一个数字。
            $points1 = 4;
            $rand2 = mt_rand(0, 4);
            $rand3 = mt_rand(0, 4);
            $rand4 = mt_rand(0, 4);
            $mt_str = array(1, 2, 3, 5, 6);
            $points2 = $mt_str[$rand2];
            $points3 = $mt_str[$rand3];
            $points4 = $mt_str[$rand4];
            $points = $points1 . $points2 . $points3 . $points4;
            $repeat = 0;
            for ($i = 1; $i <= 6; $i++) {
                if (substr_count($points, $i) == 3) {//如果前面有三个相同数字
                    $repeat = $i; //重复的数字
                    break;
                }
            }
            if ($repeat) {
                $newMt_str = array();
                foreach ($mt_str as $key => $value) {
                    if ($value != $repeat) {//则不再生成此相同数字
                        $newMt_str[] = $value;
                    }
                }
                $mt_str = $newMt_str;
            }
            $rand5 = mt_rand(0, 3);
            $points5 = $mt_str[$rand5];
            $points.=$points5;

            for ($i = 1; $i <= 6; $i++) {
                if (substr_count($points, $i) == 3) {//如果前面有三个相同数字
                    $repeat = $i; //重复的数字
                    break;
                }
            }
            if ($repeat) {
                $newMt_str = array();
                foreach ($mt_str as $key => $value) {
                    if ($value != $repeat) {//则不再生成此相同数字
                        $newMt_str[] = $value;
                    }
                }
                $mt_str = $newMt_str;
                $rand6 = mt_rand(0, 3);
                $points6 = $mt_str[$rand6];
                $points.=$points6;
            } else {//检测前面5位是否均不相同，若是，则最后一位不生成剩余的一个数字
                if (substr_count($points, 1) != 2 && substr_count($points, 2) != 2 && substr_count($points, 3) != 2 && substr_count($points, 5) != 2 && substr_count($points, 6) != 2) {
                    $newMt_str0 = str_split($points);
                    foreach ($newMt_str0 as $key => $value) {
                        if ($value != 4) {
                            $newMt_str[] = $value;
                        }
                    }
                    $mt_str = $newMt_str;
                    $rand6 = mt_rand(0, 3);
                    $points6 = $mt_str[$rand6];
                    $points.=$points6;
                } else {
                    $rand6 = mt_rand(0, 4);
                    $points6 = $mt_str[$rand6];
                    $points.=$points6;
                }
            }
            $points = str_shuffle($points); //随机打乱顺序
            $resultCode = $this->config->midAutumn->reward->xiucai->resultCode;
        } elseif ($code == $this->config->midAutumn->odds->juren[0]) {//二举
            //基础是两个4点，其他从1，2，3，5，6中随机就行。
            $point1 = 4;
            $point2 = 4;
            $points = $point1 . $point2;
            $mt_str = array(1, 2, 3, 5, 6);
            for ($i = 3; $i <= 6; $i++) {
                $rand3 = mt_rand(0, 4);
                $points3 = $mt_str[$rand3];
                $points.=$points3;
            }
            $points = str_shuffle($points); //随机打乱顺序
            $resultCode = $this->config->midAutumn->reward->juren->resultCode;
        } elseif ($code == $this->config->midAutumn->odds->jinshi[0]) {//进士
            //结果基础四个点数相同，随机相同数字（1，2，3，5，6中随机），最后两位从不与上述相同的数字随机出来，可出现四进带一秀或者四进带二举。
            //奖励为1个进士饼，奖励后面两个是否有四，若有2个四，则显示四进带二举，额外奖励一个举人饼，若有1个四，则显示四进带一秀，额外奖励一个秀才饼 
            $mt_str = array(1, 2, 3, 5, 6);
            $rand1 = mt_rand(0, 4);
            $point1 = $mt_str[$rand1];
            $points = $point1 . $point1 . $point1 . $point1;
            $newMt_str = array();
            for ($i = 5; $i <= 6; $i++) {
                foreach ($mt_str as $key => $value) {
                    if ($value != $point1) {//则不再生成此相同数字
                        $newMt_str[] = $value;
                    }
                }
                $newMt_str[] = 4;
                $mt_str = $newMt_str;
                $rand6 = mt_rand(0, 4);
                $points6 = $mt_str[$rand6];
                $points.=$points6;
            }
            $points = str_shuffle($points); //随机打乱顺序

            if (substr_count($points, 4) == 2) {//四进带二举
                $resultCode = $this->config->midAutumn->reward->jinshi_juren->resultCode;
            } elseif (substr_count($points, 4) == 1) {//四进带一秀
                $resultCode = $this->config->midAutumn->reward->jinshi_xiucai->resultCode;
            } else {
                $resultCode = $this->config->midAutumn->reward->jinshi->resultCode;
            }
        } elseif ($code == $this->config->midAutumn->odds->tanhua[0]) {//探花
            //结果基础三个4点，其他从1，2，3，5，6中随机。
            $points = '444';
            $mt_str = array(1, 2, 3, 5, 6);
            for ($i = 4; $i <= 6; $i++) {
                $rand6 = mt_rand(0, 4);
                $points6 = $mt_str[$rand6];
                $points.=$points6;
            }
            $points = str_shuffle($points); //随机打乱顺序
            $resultCode = $this->config->midAutumn->reward->tanhua->resultCode;
        } elseif ($code == $this->config->midAutumn->odds->duitang[0]) {//对堂
            //结果为1,2,3,4,5,6 
            $points = '123456';
            $points = str_shuffle($points); //随机打乱顺序
            $resultCode = $this->config->midAutumn->reward->duitang->resultCode;
        } elseif ($code == $this->config->midAutumn->odds->zhuangyuan[0]) {//状元
            $rand = mt_rand(1, 557);
            //四个4固定，随机后2位值，为基本状元，可出现普通状元，五红和六杯红，或者状元插金花
            if ($rand <= 402) {//概率为402/557
                $points = "4444";
                $mt_str = array(1, 2, 3, 4, 5, 6);
                $rand5 = mt_rand(0, 5);
                $point5 = $mt_str[$rand5]; //第五位数
                $points.=$point5;
                $rand6 = mt_rand(0, 5);
                $points6 = $mt_str[$rand6]; //第六位数
                $points.=$points6;
                //状元插金花：摇出的结果为4个四点和2个一点。
                if (substr_count($points, '1') == 2) {
                    $resultCode = $this->config->midAutumn->reward->chajinhua->resultCode . "00";
                }
                //六杯红：摇出的结果为6个四点。
                elseif (substr_count($points, '4') == 6) {
                    $resultCode = $this->config->midAutumn->reward->liubeihong->resultCode . "00";
                }
                //五红：摇出的结果里有五个4点。
                elseif (substr_count($points, '4') == 5) {
                    if (substr_count($points, '1') == 1) {
                        $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "01";
                    } elseif (substr_count($points, '2') == 1) {
                        $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "02";
                    } elseif (substr_count($points, '3') == 1) {
                        $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "03";
                    } elseif (substr_count($points, '5') == 1) {
                        $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "05";
                    } elseif (substr_count($points, '6') == 1) {
                        $resultCode = $this->config->midAutumn->reward->wuhong->resultCode . "06";
                    }
                } else {
                    //四点红：摇出的结果有4个四点。
                    $arr = str_split($points); //提取出字符，放入数组
                    $plus = 0;
                    foreach ($arr as $val) {
                        if ($val != 4) {
                            $plus+=$val;
                        }
                    }
                    $plus < 10 && $plus = '0' . $plus;
                    $resultCode = $this->config->midAutumn->reward->sidianhong->resultCode . $plus;
                }
            }
            //五个除4外点数相同的数字，先随机相同数字，从1，2，3，5，6中随机，然后再随机最后一位的值，可出现五子登科，五子登科带一秀，满地锦
            else {//b概率为155/557 
                $mt_str = array(1, 2, 3, 5, 6);
                $rand1 = mt_rand(0, 4);
                $point1 = $mt_str[$rand1];
                $points = $point1 . $point1 . $point1 . $point1 . $point1;
                $mt_str6 = array(1, 2, 3, 4, 5, 6);
                $rand6 = mt_rand(0, 5);
                $point6 = $mt_str6[$rand6]; //第六位数
                $points.=$point6;
                //遍地锦：摇出的结果为6个一样的数字（除四点）
                if ($point1 == $point6) {
                    if ($point1 == 1) {
                        $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '01';
                    } elseif ($point1 == 2) {
                        $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '02';
                    } elseif ($point1 == 3) {
                        $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '03';
                    } elseif ($point1 == 5) {
                        $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '05';
                    } elseif ($point1 == 6) {
                        $resultCode = $this->config->midAutumn->reward->biandijin->resultCode . '06';
                    }
                }
                //五子登科：摇出的结果有5个相同的数字（除四点外）
                else {
                    for ($i = 1; $i <= 6; $i++) {
                        if (substr_count($points, $i) == 5) {
                            $resultCode = $this->config->midAutumn->reward->wuzidengke->resultCode . $i;
                            for ($j = 1; $i <= 6; $j++) {
                                if ($i != $j && substr_count($points, $j) == 1) {
                                    $resultCode.=$j;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            $points = str_shuffle($points); //随机打乱顺序
            $isChampion = 1;
        }
        $result['points'] = $points;
        $result['isChampion'] = $isChampion;
        $result['resultCode'] = $resultCode;
        return $result;
    }

    /**
     * 中秋排行榜送奖励
     */
    public function midAutumnRank() {
        $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir . '/activity.log');
        $logger->error('开始');
        $midAutumnRankConfig = $this->config->midAutumnRankConfig;
        // 判断是否采集过，有则退出程序
        $logger->error('判断是否执行过');
        $resAnchor = MoonEnergy::findFirst('type = 2 and rank>0');
        $resUser = MoonEnergy::findFirst('type = 1 and rank>0');
        if (!empty($resUser) || !empty($resAnchor)) {
            $logger->error('停止:已执行过');
            return $this->status->retFromFramework($this->status->getCode('OK'), 'midAutumnRank reward has sent');
        }


        $message = '';
        // 徽章过期时间
        $itemExpireTime = 24 * 3600 * 7;
        try {
            $logger->error('获取排行榜-开始');
            // 查询主播排行榜前三名
            $anchorRank = MoonEnergy::find('type = 2 and uid != 10072 order by totalNum desc limit 3');
            // 查询用户排行前三名
            $userRank = MoonEnergy::find('type = 1 and uid != 26964 order by totalNum desc limit 3');
            // 查询状态排行前三名
            $sql = " select nickName,points, resultCode,l.uid from (select * from pre_moon_bobing_log order by resultCode desc,createTime desc) l INNER JOIN  pre_user_info ui on l.uid=ui.uid where l.isChampion=1 group by l.uid order by resultCode desc,createTime desc limit 3";
            $zhuangyuanRank = $this->db->fetchAll($sql);

            $logger->error('处理月光仙子-开始');

            if (!empty($anchorRank)) {
                $logger->error('处理月光仙子-有数据，开始循环');
                foreach ($anchorRank as $ak => $aRank) {
                    $user = UserFactory::getInstance($aRank->uid);
                    if (empty($user)) {
                        continue;
                    }

                    $rewardLog = array();
                    $anchorRankConfig = $midAutumnRankConfig->anchorConfig[$ak];
                    if ($anchorRankConfig['itemId'] > 0) {
                        // 赠送徽章
                        $res = $user->getUserItemsObject()->giveItem($anchorRankConfig['itemId'], 1, $itemExpireTime);
                        if ($res['code'] == $this->status->getCode('OK')) {
                            $rewardLog['item'] = $anchorRankConfig['itemId'];
                        }

                        $message = $midAutumnRankConfig->anchorMessageConfig[$ak]['second'];
                    }

                    if ($anchorRankConfig['moonLimit'] > 0) {
                        // 有配置月光限制
                        if ($anchorRankConfig['moonLimit'] <= $aRank->totalNum) {
                            // 送聊币
                            if ($anchorRankConfig['cash'] > 0) {
                                $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
                                //送聊币
                                if (isset($anchorRankConfig['cash'])) {
//                                    $res = $userCash->addUserCash($anchorRankConfig['cash'], $aRank->uid);
//                                    //添加聊币记录
//                                    $userCash->addCashLog($anchorRankConfig['cash'], $this->config->cashSource->activity, 0, $aRank->uid);
                                    $createTime = strtotime(date('Y-m-d'));
                                    $sql1 = 'delete from \Micro\Models\ActivityIncomeLog where createTime = ' . $createTime . " and type=4 and uid=" . $aRank->uid;
                                    $qry1 = $this->modelsManager->createQuery($sql1);
                                    $qry1->execute();
                                    $activityIncomeLog = new \Micro\Models\ActivityIncomeLog();
                                    $activityIncomeLog->uid = $aRank->uid;
                                    $activityIncomeLog->remark = $this->config->activityIncomeType[4];
                                    $activityIncomeLog->money = $anchorRankConfig['cash'];
                                    $activityIncomeLog->type = 4;
                                    $activityIncomeLog->createTime = $createTime;
                                    $res = $activityIncomeLog->save();
                                    if ($res) {
                                        $rewardLog['cash'] = $anchorRankConfig['cash'];
                                    }
                                }
                            }

                            $message = $midAutumnRankConfig->anchorMessageConfig[$ak]['first'];
                        }
                    }

                    // 记录奖励内容
                    $model = MoonEnergy::findFirst('type = 2 AND uid=' . $aRank->uid);
                    $model->rank = $ak + 1;
                    $model->reward = json_encode($rewardLog);
                    $model->save();
                    // 给用户发信息
                    if ($message) {
                        $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $message, 'link' => '', 'operType' => ''));
                    }
                }
                $logger->error('处理月光仙子-有数据，结束循环');
            }

            $logger->error('处理桂月护法-开始');

            if ($userRank) {
                $logger->error('处理桂月护法-有数据，开始循环');
                foreach ($userRank as $uk => $uRank) {
                    $userRankConfig = $midAutumnRankConfig->userConfig[$uk];
                    $user = UserFactory::getInstance($uRank->uid);
                    if (empty($user)) {
                        continue;
                    }

                    $rewardLog = array();
                    if ($userRankConfig['itemId'] > 0) {
                        // 赠送徽章
                        $user->getUserItemsObject()->giveItem($userRankConfig['itemId'], 1, $itemExpireTime);
                        if ($res['code'] == $this->status->getCode('OK')) {
                            $rewardLog['itemId'] = $userRankConfig['itemId'];
                        }
                    }

                    // 赠送座驾
                    if ($userRankConfig['carId'] > 0) {
                        $expireTime = $userRankConfig['expireDay'] * 3600 * 24;
                        $user->getUserItemsObject()->giveCar($userRankConfig['carId'], $expireTime);
                        $rewardLog['carId'] = $userRankConfig['carId'];
                    }

                    // 记录奖励内容
                    $model = MoonEnergy::findFirst('type = 1 AND uid=' . $uRank->uid);
                    $model->rank = $uk + 1;
                    $model->reward = json_encode($rewardLog);
                    $model->save();
                    // 给用户发信息
                    $message = $midAutumnRankConfig->userMessageConfig[$uk]['first'];
                    if ($message) {
                        $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $message, 'link' => '', 'operType' => ''));
                    }
                }
                $logger->error('处理桂月护法-有数据，结束循环');
            }

            $logger->error('处理状元王中王-开始');

            if ($zhuangyuanRank) {
                $logger->error('处理状元王中王-有数据，开始循环');
                foreach ($zhuangyuanRank as $zk => $zRank) {
                    $zhuangyuanRankConfig = $midAutumnRankConfig->zhuangyuanConfig[$zk];
                    $user = UserFactory::getInstance($zRank['uid']);
                    if (empty($user)) {
                        continue;
                    }

                    $rewardLog = array();
                    if ($zhuangyuanRankConfig['itemId'] > 0) {
                        // 更新为状元王中王徽章
                        $bagItem = UserItem::findFirst("uid={$zRank['uid']} and itemType=4 and itemId=" . $this->config->midAutumn->badge->zhuangyuan);
                        if ($bagItem) {
                            $bagItem->itemId = $zhuangyuanRankConfig['itemId'];
                            $bagItem->save();
                        }
                    }

                    // 赠送座驾
                    if ($zhuangyuanRankConfig['carId'] > 0) {
                        $expireTime = $zhuangyuanRankConfig['expireDay'] * 3600 * 24;
                        $user->getUserItemsObject()->giveCar($zhuangyuanRankConfig['carId'], $expireTime);
                        $rewardLog['carId'] = $zhuangyuanRankConfig['carId'];
                    }

                    // 给用户发信息
                    $message = $midAutumnRankConfig->zhuangyuanMessageConfig[$zk]['first'];
                    if ($message) {
                        $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $message, 'link' => '', 'operType' => ''));
                    }
                }
                $logger->error('处理状元王中王-有数据，结束循环');
            }

            $logger->error('结束');
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }


        return $this->status->retFromFramework($this->status->getCode('OK'), 'midAutumnRank---');
    }

    /**
     * 获得月光排行榜
     *
     * @param int $num
     * @return mixed
     */
    public function energyRank($num = 10, $anchorId = 0) {
        try {
            $anchorRankArr = $userRankArr = $userInfo = $anchorInfo = array();
            // 查询主播排行榜
            $anchorRank = MoonEnergy::find('type = 2 and uid != 10072 order by totalNum desc limit ' . $num);   //10072测试数据
            if(!empty($anchorRank)){
                foreach($anchorRank as $key => $val){
                    $uid = $val->uid;
                    $user = UserFactory::getInstance($uid);
                    $nickName = $user->getUserInfoObject()->getNickName();
                    $tmp['rank'] = $key + 1;
                    $tmp['nickName'] = $nickName;
                    $tmp ['totalNum'] = $val->totalNum;
                    $anchorRankArr[] = $tmp;
                }
            }

            // 查询用户排行
            $userRank = MoonEnergy::find('type = 1 and uid != 26964 order by totalNum desc limit ' . $num);     //26964测试数据
            if(!empty($userRank)){
                foreach($userRank as $key => $val){
                    $uid = $val->uid;
                    $user = UserFactory::getInstance($uid);
                    $nickName = $user->getUserInfoObject()->getNickName();
                    $tmp['rank'] = $key + 1;
                    $tmp['nickName'] = $nickName;
                    $tmp ['totalNum'] = $val->totalNum;
                    $userRankArr[] = $tmp;
                }
            }

            $user = $this->userAuth->getUser();
            if ($user) {
                $uid = $user->getUid();
                // 获得这个用户的月光值
                $userRank = MoonEnergy::findFirst('type = 1 and uid=' . $uid);
                if ($userRank) {
                    $userInfo['totalNum'] = $userRank->totalNum;
                } else {
                    $userInfo['totalNum'] = 0;
                }
            }

            if ($anchorId > 0) {
                $userRank = MoonEnergy::findFirst('type = 2 and uid=' . $anchorId);
                $anchorInfo['totalNum'] = intval($userRank->totalNum);
            }

            $result = array(
                'userRank' => $userRankArr,
                'anchorRank' => $anchorRankArr,
//                'userInfo' => $userInfo,
//                'anchorInfo' => $anchorInfo,
            );

            if ($userInfo) {
                $result['userInfo'] = $userInfo;
            }

            if ($anchorInfo) {
                $result['anchorInfo'] = $anchorInfo;
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //用户撒红包
    public function startRedPacket($roomId, $type, $num, $money, $limit, $redPacketType = 1) {
        if (!$this->config->redPacketConfigs->enable) {//活动未开启
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }

        if ($redPacketType != 2) {
            $redPacketType = 1;
        }

        //猴年春节红包
        if ($redPacketType == 2) {
            //活动未开始或已结束
            if (time() < $this->config->redPacketConfigs->monkeyRedPacket->startTime || time() > $this->config->redPacketConfigs->monkeyRedPacket->endTime) {
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
        }

        $postData['roomid'] = $roomId; //房间id
        $postData['giftcount'] = $num; //红包个数 正整数判断
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        //红包个数判断
        if ($num > $this->config->redPacketConfigs->numMax) {
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        if ($type != 1 && $type != 2) {//TYPE:1：手气红包 2：人气红包
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        if ($type == 1) {//如果是手气红包 
            if (!in_array($money, $this->config->redPacketConfigs->moneyList->toArray())) {//判断金额是否正确
                return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
            }
        } else {//人气红包
            //红包总金额判断
            if ($money * $num < $this->config->redPacketConfigs->sumMoneyMin) {
                return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
            }
        }

//        //判断限制条件是否正确
//        if (!isset($this->config->redPacketConfigs->limitList[$limit])) {
//            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
//        }
        ///
        // $logger = $this->di->get('logger');
        // $session = $this->di->get('session');
        // $logger->error('-----APP test startRedPacket sessionId = '.$session->getId());
        ///
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        //检查用户是否是推广员或者托
        $users = \Micro\Models\Users::findFirst('uid = ' . $uid);
        if ($users && $users->internalType != 0) {
            return $this->status->retFromFramework($this->status->getCode('NOT_ALLOWED_TO_SEND'));
        }
        try {
            //查询是否有可开启塞红包的资格
            $sql = "select id from pre_red_packet where roomId=" . $roomId . " and uid=" . $uid . " and redPacketType=" . $redPacketType . " and status=0 order by id desc ";
            $redPacketData = $this->db->fetchOne($sql);
            if (empty($redPacketData)) {
                return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
            }


            if ($type == 1) {//如果是手气红包 
                $sumMoney = $money; //money即为总金额
            } else {//如果是平均红包
                $sumMoney = $money * $num; //总金额=单个金额*红包个数
            }
            //判断聊币是否足够
            $cashsql = "select cash from pre_user_profiles where uid=" . $uid;
            $cashResult = $this->db->fetchOne($cashsql);
            if ($cashResult['cash'] < $sumMoney) {//聊币不足
                return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));
            }

            //扣除聊币
            $cashUpdatesql = "update pre_user_profiles set cash=cash-" . $sumMoney . " where uid=" . $uid;
            $this->db->execute($cashUpdatesql);
            $now = time();
            //更新状态、时间
            $updatesql = "update pre_red_packet set `status`=1,initTime=" . $now . ",type=" . $type . ",`num`=" . $num . ",`limit`=" . $limit . ",`sumMoney`=" . $sumMoney . " where id=" . $redPacketData['id'];
            $this->db->execute($updatesql);

            //生成每份红包的金额，写入数据库
            $insertsql = "insert into pre_red_packet_log(redPacketId,money) values";
            if ($type == 1) {//手气红包
                $min = $this->config->redPacketConfigs->randMin;
                $randArr = $this->redPacketRand($min, $num, $sumMoney);
                foreach ($randArr as $val) {
                    $arr[] = "(" . $redPacketData['id'] . "," . $val . ")";
                }
                $str = implode(',', $arr);
                $insertsql.=$str;
            } else {//平均红包
                for ($i = 0; $i < $num; $i++) {
                    $arr[] = "(" . $redPacketData['id'] . "," . $money . ")";
                }
                $str = implode(',', $arr);
                $insertsql.=$str;
            }
            $this->db->execute($insertsql);

            //世界喇叭
            $userInfo = $user->getUserInfoObject()->getUserInfo();
            $roomData = \Micro\Models\Rooms::findfirst($roomId);
            $anchorUid = $roomData->uid;
            $hostUser = UserFactory::getInstance($anchorUid);
            $hoserUserInfo = $hostUser->getUserInfoObject()->getUserInfo();
            $broadData['hostUid'] = $anchorUid;
            $broadData['roomId'] = $roomId;
            $broadData['hostName'] = $hoserUserInfo['nickName']; //主播昵称
            $broadData['type'] = 3; //用户撒红包
            $broadData['userdata'] = array('nickName' => $userInfo['nickName']);
            $broadData['id'] = $redPacketData['id'];
            $tomorry = strtotime(date("Ymd", time() + 86400)); //第二天凌点
            //每天午夜12点，清算所有红包，存在时间超过1小时的红包全部过期。存在时间小于1个消失的红包，不过期。
            if ($tomorry - $now < 3600) {
                $tomorry+=86400;
            }
            $remainTime = $tomorry - $now;
            $broadData['remainTime'] = $remainTime;
            $ArraySubData['controltype'] = "worldbroadcast";
            $ArraySubData['data'] = $broadData;
            $this->roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData);


            $return = array();
            //更新用户聊币聊豆数量
            //$userPro = $user->getUserInfoObject()->getUserProfiles();
            // $return['cash'] = $userPro['cash'];
            //$return['coin'] = $userPro['coin'];

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //用户领取红包
    public function getRoomRedPacket($roomId, $redPacketId) {
        if (!$this->config->redPacketConfigs->enable) {//活动未开启
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $postData['roomid'] = $roomId; //房间id
        $postData['id'] = $redPacketId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            //查询红包数据
            $sql = "select id,status,`limit`,redPacketType,uid from pre_red_packet where id=" . $redPacketId . " and roomId=" . $roomId;
            $redPacketData = $this->db->fetchOne($sql);

            $return['id'] = $redPacketData['id'];

            if (!$redPacketData) {
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }

            if ($redPacketData['status'] != 1) {//无可领取的红包
                return $this->status->retFromFramework($this->status->getCode('RED_PACKET_HAS_GRAB'), $return);
            }

            $redPacketType = $redPacketData['redPacketType'];
            if ($redPacketType == 2) {//猴年春节红包
                //查询用户富豪等级是否满足
                $userInfo = $user->getUserInfoObject()->getUserProfiles();
                if ($userInfo['richerLevel'] < $this->config->redPacketConfigs->monkeyRedPacket->richerLimit) {
                    return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_RICHERLEVEL'));
                }
            } else {//普通红包
                //查询是否有可抢红包的资格
                $limit = $redPacketData['limit']; //抢红包条件限制
                $isBreak = 0;
                if ($limit == 1) {//主播、守护、管理
                    //                //查询用户vip等级
//                $userInfo = $user->getUserInfoObject()->getUserProfiles();
//                if (!$userInfo['vipLevel']) {
//                    return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_VIPLEVEL'));
//                }
                    //查询是否房间管理员
                    $roomData = \Micro\Models\Rooms::findfirst($roomId);
                    $hoster = UserFactory::getInstance($roomData->uid);
                    $userLevel = $this->roomModule->getRoomOperObject()->getUserLevelInRoom($roomId, $hoster, $user);
                    if ($userLevel < 2) {//不是管理员、主播
                        $isBreak = 1;
                    }

                    if ($isBreak) {
                        //查询用户守护信息
                        $guardData = $user->getUserItemsObject()->getGuardData($roomData->uid);
                        if (!$guardData) {
                            return $this->status->retFromFramework($this->status->getCode('RED_PACKET_LIMIT'));
                        }
                    }
                }
            }


            //查询红包领取日志
            $getsql = "select id,money from pre_red_packet_log where redPacketId=" . $redPacketData['id'] . " and uid=" . $uid;
            $getres = $this->db->fetchOne($getsql);
            if ($getres) {//已领取过红包
                //更新用户聊币聊豆数量
                //$userInfo = $user->getUserInfoObject()->getUserProfiles();
                // $return['cash'] = $userInfo['cash'];
                // $return['coin'] = $userInfo['coin'];
                $return['money'] = $getres['money'];
                return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'), $return);
            }

            $ressql = "select id,money from pre_red_packet_log where redPacketId=" . $redPacketData['id'] . " and uid=0";
            $resres = $this->db->fetchOne($ressql);
            if (!$resres) {//红包已抢完
                return $this->status->retFromFramework($this->status->getCode('RED_PACKET_HAS_GRAB'), $return);
            }

            //更新状态、时间
            $updatesql = "update pre_red_packet_log set status=0,getTime=" . time() . ",uid=" . $uid . " where id=" . $resres['id'] . " and uid=0";
            $this->db->execute($updatesql);
            $updateres = $this->db->affectedRows(); //判断更新是否成功
            if (!$updateres) {//红包已抢完
                return $this->status->retFromFramework($this->status->getCode('RED_PACKET_HAS_GRAB'), $return);
            }

            $cashNum = $resres['money'];
            //增加聊币
            $this->userCash->addUserCash($cashNum, $uid, 0);
            //写入聊币记录表
            $this->userCash->addCashLog($cashNum, $this->config->cashSource->redPacket, $redPacketData['id'], $uid);



            //查询发送红包人的昵称
            $sendUid = $redPacketData['uid'];
            $sendUser = UserFactory::getInstance($sendUid);
            $sendUserInfo = $sendUser->getUserInfoObject()->getUserInfo();

            //查询红包是否都已领取完了
            $selectsql = "select count(1) as count from pre_red_packet_log where redPacketId=" . $redPacketData['id'] . " and status=1";
            $selectRes = $this->db->fetchOne($selectsql);
            if (!$selectRes || !$selectRes['count']) {
                //更新红包记录表
                $updatesql_ = "update pre_red_packet set status=2 where id=" . $redPacketData['id'];
                $this->db->execute($updatesql_);

                //发送红包领取完的广播
                $ArraySubData['controltype'] = "redPackageChange";
                $broadData['id'] = $redPacketData['id'];
                $ArraySubData['data'] = $broadData;
                $this->comm->roomBroadcast($roomId, $ArraySubData);
            }

            //更新用户聊币聊豆数量
//            $userInfo = $user->getUserInfoObject()->getUserProfiles();
//            $return['cash'] = $userInfo['cash'];
//            $return['coin'] = $userInfo['coin'];
            $return['sendNickName'] = $sendUserInfo['nickName'];
            $return['money'] = $cashNum;
            $return['id'] = $redPacketData['id'];

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //随机红包生成算法
    private function redPacketRand($min, $num, $total) {
        $return = array();
        for ($i = 1; $i < $num; $i++) {
            $safe_total = ($total - ($num - $i) * $min) / ($num - $i); //随机安全上限 
            $money = mt_rand($min, $safe_total);
            $total = $total - $money;
            $return[] = $money;
        }
        $return[] = $total;
        return $return;
    }

    //查询红包基本配置
    public function getRedPacketConfigs($redPacketType = 1) {
        if (!$this->config->redPacketConfigs->enable) {//活动未开启
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $return = array();
        if ($redPacketType == 2) {//猴年春节红包
            $limitList = $this->config->redPacketConfigs->monkeyRedPacket->limitList;
            $return['richerLimit'] = $this->config->redPacketConfigs->richerLimit;
        } else {//普通红包
            $limitList = $this->config->redPacketConfigs->limitList;
        }
        $return['moneyList'] = $this->config->redPacketConfigs->moneyList;
        $return['limitList'] = $limitList;
        $return['numMax'] = $this->config->redPacketConfigs->numMax;
        $return['moneyMin'] = $this->config->redPacketConfigs->moneyMin;
        $return['moneyMax'] = $this->config->redPacketConfigs->moneyMax;
        $return['redPacketType'] = $redPacketType == 2 ? $redPacketType : 1;

        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }

    //查询某个红包领取情况
    public function getRoomRedPacketInfo($roomId, $redPacketId) {
        if (!$this->config->redPacketConfigs->enable) {//活动未开启
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $postData['roomid'] = $roomId; //房间id
        $postData['id'] = $redPacketId; //红包id
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $datasql = "select id,status,num,sumMoney,returnTime from pre_red_packet where id=" . $redPacketId . " and roomId=" . $roomId;
            $datares = $this->db->fetchOne($datasql);
            if (!$datares) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $num = $datares['num']; //红包个数
            $totalMoney = $datares['sumMoney']; //红包总额
            $isEnd = 0;
            if ($datares['returnTime'] > 0) {//已过期
                $isEnd = 1;
            }
            //已领取个数
            $countsql = "select count(1) as count from pre_red_packet_log where redPacketId=" . $redPacketId . " and uid<>0";
            $countres = $this->db->fetchOne($countsql);
            if ($countres) {
                $count = $countres['count'];
            }

            //领奖记录
            $sql = "select r.id,ui.nickName,r.money from pre_red_packet_log r inner join pre_user_info ui on r.uid=ui.uid where r.redPacketId=" . $redPacketId . " and r.uid<>0 order by r.money desc,r.getTime asc";
            $res = $this->db->fetchAll($sql);
            $list = array();
            $sumMoney = 0; //已领总额
            foreach ($res as $val) {
                $data['id'] = $val['id'];
                $data['nickName'] = $val['nickName'];
                $data['money'] = $val['money'];
                $list[] = $data;
                $sumMoney+= $val['money'];
                unset($data);
            }
            $return['num'] = $num;
            $return['count'] = $count ? $count : 0;
            $return['sumMoney'] = $sumMoney;
            $return['totalMoney'] = $totalMoney;
            $return['list'] = $list;
            $return['isEnd'] = $isEnd;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询房间是否有红包、红包数量
    public function isHasRedPacket($roomId) {
        $postData['roomid'] = $roomId; //房间id
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $count = 0;
        if ($this->config->redPacketConfigs->enable) {//活动需开启
            try {
                $sql = "select count(1) as count from pre_red_packet where roomId=" . $roomId . " and status=1";
                $res = $this->db->fetchOne($sql);
                if ($res) {
                    $count = $res['count'];
                }
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }
        }
        $return['count'] = $count ? $count : 0;
        return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    }

    //送礼红包触发
    public function setRedPacket($roomId, $giftId) {
        if (!$this->config->redPacketConfigs->enable) {//活动未开启
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $redPacketType = 1; //普通红包

        if ($giftId == $this->config->redPacketConfigs->monkeyRedPacket->giftId) {//猴年春节红包
            //活动未开始或已结束
            if (time() < $this->config->redPacketConfigs->monkeyRedPacket->startTime || time() > $this->config->redPacketConfigs->monkeyRedPacket->endTime) {
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
            $redPacketType = 2; //猴年春节红包
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        try {
            //检查用户是否是推广员或者托
            $users = \Micro\Models\Users::findFirst('uid = ' . $uid);
            if ($users && $users->internalType != 0) {
                return $this->status->retFromFramework($this->status->getCode('NOT_ALLOWED_TO_SEND'));
            }

            //普通红包需要守护或者管理员
            if ($redPacketType == 1) {
                //查询是否房间管理员
                $roomData = \Micro\Models\Rooms::findfirst($roomId);
                $hoster = UserFactory::getInstance($roomData->uid);
                $userLevel = $this->roomModule->getRoomOperObject()->getUserLevelInRoom($roomId, $hoster, $user);
                if ($userLevel < 2) {//不是管理员、主播
                    //查询用户守护信息
                    $guardData = $user->getUserItemsObject()->getGuardData($roomData->uid);
                    if (!$guardData) {
                        return $this->status->retFromFramework($this->status->getCode('RED_PACKET_LIMIT'));
                    }
                }
            }


            //写入红包表
            $now = time();
            $insertsql = "insert into pre_red_packet(redPacketType,roomId,uid,createTime,status)values({$redPacketType},{$roomId},{$uid},{$now},0)";
            $this->db->execute($insertsql);
            $return['redPacketType'] = $redPacketType;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询红包列表
    public function getRoomRedPacketList($roomId) {
        if (!$this->config->redPacketConfigs->enable) {//活动未开启
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $postData['roomid'] = $roomId; //房间id
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            //查询该房间正在进行中的红包列表
            $sql = "select r.id,ui.nickName,r.initTime,r.redPacketType from pre_red_packet r inner join pre_user_info ui on r.uid=ui.uid where r.roomId=" . $roomId . " and r.status=1";
            $res = $this->db->fetchAll($sql);
            $return = array();
            $tomorry = strtotime(date("Ymd", time() + 86400)); //第二天凌点
            $now = time();
            foreach ($res as $val) {
                $data['id'] = $val['id'];
                $data['sendNickName'] = $val['nickName'];
                //每天午夜12点，清算所有红包，存在时间超过1小时的红包全部过期。存在时间小于1个消失的红包，不过期。
                if ($tomorry - $val['initTime'] < 3600) {
                    $tomorry+=86400;
                }
                $data['remainTime'] = $tomorry - $now;
                //判断是否已抢过
                $getMoney = 0;
                if ($uid) {
                    //查询红包领取日志
                    $getsql = "select id,money from pre_red_packet_log where redPacketId=" . $val['id'] . " and uid=" . $uid;
                    $getres = $this->db->fetchOne($getsql);
                    if ($getres) {//已领取过红包
                        $getMoney = $getres['money'];
                    }
                }
                $data['getMoney'] = $getMoney; //抢到的金额
                $data['redPacketType'] = $val['redPacketType'];
                $data['richerLimit'] = $this->config->redPacketConfigs->richerLimit;
                $return[] = $data;
                unset($data);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //送幸运礼物判断是否中奖 add by 2015/11/02
    public function sendLuckyGift($giftInfo, $giftNum, $nickName, $hostUid, $roomId) {
        try {
            $giftId = $giftInfo->id;

            if ($giftId == $this->config->newyear->giftId) {//元旦礼物  
                if (time() < $this->config->newyear->startTime || time() > $this->config->newyear->endTime) {//活动未开始或已结束
                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }
            }

            $isFlower = 0;
            if ($giftId == $this->config->luckyGiftConfigs->flowerConfigs->giftId || $giftId == $this->config->newyear->giftId) {//幸运桃花 /元旦礼物
                $isFlower = 1; //是否幸运桃花 /幸运菊花
                $exceptOdds = $this->config->luckyGiftConfigs->flowerConfigs->exceptOdds;
            } elseif ($giftId == $this->config->luckyGiftConfigs->jhConfigs->giftId) {//幸运菊花
                $isFlower = 1; //是否幸运桃花 /幸运菊花
                $exceptOdds = $this->config->luckyGiftConfigs->jhConfigs->exceptOdds;
            } elseif($giftId == $this->config->luckyGiftConfigs->qgConfigs->giftId){
                $isFlower = 1; //是否幸运青瓜
                $exceptOdds = array();
            }

            if ($isFlower && $giftNum < 50 && $giftId != $this->config->luckyGiftConfigs->qgConfigs->giftId) {//桃花少于50朵不中奖
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }



            //幸运桃花 /幸运菊花,改版，改成循环使用固定的10万概率池
            if ($isFlower) {
                //查询幸运礼物概率表个数
                $selectsql = "select count from pre_lucky_gift_configs where giftId=" . $giftId;
                $countres = $this->db->fetchOne($selectsql);
                $oldCount = $countres['count'];
                $newCount = $oldCount + $giftNum;
                $maxnum = 300000; //10万概率池
                // $maxnum = 300461;
                if ($newCount > $maxnum) {//10万概率池用完
                    $secCount = $newCount - $maxnum; //从头查询
                    //更新送出幸运礼物个数
                    $updatesql = "update pre_lucky_gift_configs set count=" . $secCount . " where giftId=" . $giftId;
                    $this->db->execute($updatesql);

                    //查询概率表，查询该区间所有的中奖倍数
                    $selectsql = "select multiple,id from pre_lucky_gift_odds "
                            . "where giftId=" . $giftId . " and ((sequence>" . $oldCount . " and sequence<=" . $maxnum . ") or  (sequence>0 and sequence<=" . $secCount . "))";
                    $oddsres = $this->db->fetchAll($selectsql);
                } else {
                    //更新送出幸运礼物个数
                    $updatesql = "update pre_lucky_gift_configs set count=count+" . $giftNum . " where giftId=" . $giftId;
                    $this->db->execute($updatesql);
                    //查询概率表，查询该区间所有的中奖倍数
                    $selectsql = "select multiple,id from pre_lucky_gift_odds where giftId=" . $giftId . " and sequence>" . $oldCount . " and sequence<=" . $newCount;
                    $oddsres = $this->db->fetchAll($selectsql);
                }
                $isCycled = 1;

                //其他幸运礼物
            } else {
                //更新概率表
                $countres = $this->updateLuckyGiftOdds($giftId, $giftNum);

                $oldCount = $countres['count'];
                $newCount = $oldCount + $giftNum;
                $isCycled = 0; //是否循环

                if ($this->config->luckyGiftConfigs->accrue) {//累加中奖倍数
                    if ($this->config->luckyGiftConfigs->cycled || $isFlower) {//循环广播每个中奖倍数
                        //查询概率表，查询该区间所有的中奖倍数
                        $selectsql = "select multiple,id from pre_lucky_gift_odds where giftId=" . $giftId . " and sequence>" . $oldCount . " and sequence<=" . $newCount;
                        $oddsres = $this->db->fetchAll($selectsql);
                        $isCycled = 1;
                    } else {//只广播一次
                        $selectsql = "select sum(multiple) as multiple  from pre_lucky_gift_odds where giftId=" . $giftId . " and sequence>" . $oldCount . " and sequence<=" . $newCount;
                        $oddsres = $this->db->fetchOne($selectsql);
                    }
                } else {//不累加中奖倍数，只中奖一次
                    //查询概率表,查询该区间最大的中奖倍数
                    $selectsql = "select multiple,id from pre_lucky_gift_odds where giftId=" . $giftId . " and sequence>" . $oldCount . " and sequence<=" . $newCount . " order by multiple desc";
                    $oddsres = $this->db->fetchOne($selectsql);
                }
            }

            if (!$oddsres) {//没有中奖
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }

            $giftPrice = $giftInfo->cash; //礼物价值
            $broadArr = array();


            if ($isCycled) {//循环广播
                $multiple = 0;
                if ($isFlower) {//如果是幸运桃花
                    foreach ($oddsres as $val) {
                        $IsEffective = 1; //本次中奖是否有效
                        //赠送少量礼物则不允许中大倍率奖励
                        if($exceptOdds){
                           foreach ($exceptOdds as $k => $v) {
                                if ($giftNum <= $k) {
                                    $allowMultiple = $v; //允许中奖的倍数
                                    if ($val['multiple'] > $allowMultiple) {//如果实际中奖的倍数 大于 允许中奖的倍数  则按不中奖处理
                                        $this->dealLuckyGiftOdds($giftId, $val['id']); //调整幸运桃花概率表
                                        $IsEffective = 0; //本次中奖无效
                                        break;
                                    }
                                }
                            } 
                        }
                            
                        if ($IsEffective) {//本次中奖是否有效
                            $multiple+=$val['multiple']; //累加中奖倍数
                            $data['price'] = $giftPrice * $val['multiple']; //中奖金额
                            $data['multiple'] = $val['multiple'];
                            $broadArr[] = $data; //广播用
                            unset($data);
                        }
                    }
                } else {//其他幸运礼物
                    foreach ($oddsres as $val) {
                        $multiple+=$val['multiple']; //累加中奖倍数
                        $data['price'] = $giftPrice * $val['multiple']; //中奖金额
                        $data['multiple'] = $val['multiple'];
                        $broadArr[] = $data; //广播用
                        unset($data);
                    }
                }
            } else {//单次广播
                $multiple = $oddsres['multiple']; //中奖倍数

                if ($isFlower) {//如果是幸运桃花
                    //赠送少量礼物则不允许中大倍率奖励
                    foreach ($exceptOdds as $k => $v) {
                        if ($giftNum <= $k) {
                            $allowMultiple = $v; //允许中奖的倍数
                            if ($multiple > $allowMultiple) {//如果实际中奖的倍数 大于 允许中奖的倍数  则按不中奖处理
                                $this->dealLuckyGiftOdds($giftId, $oddsres['id']); //调整幸运桃花概率表
                                return $this->status->retFromFramework($this->status->getCode('OK'));
                            }
                        }
                    }
                }

                $broadArr[0]['price'] = $giftPrice * $multiple; //中奖金额
                $broadArr[0]['multiple'] = $multiple;
            }

            if (!$multiple) {//没有中奖
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }


            //中奖
            $user = $this->userAuth->getUser();
            $uid = $user->getUid();
            //增加聊币
            $addCashNum = $giftPrice * $multiple; //礼物价值 乘以 中奖倍数
            //增加聊币
            $this->userCash->addUserCash($addCashNum, $uid);
            //写入聊币记录表
            $this->userCash->addCashLog($addCashNum, $this->config->cashSource->lucky, $giftId, $uid);


            //构造广播数据
            foreach ($broadArr as $bro) {
                $broadData = array();
                $broadData['nickName'] = $nickName; //昵称
                $broadData['giftName'] = $giftInfo->name; //礼物名称
                $broadData['giftConfigName'] = $giftInfo->configName; //礼物图标索引名称
                $broadData['price'] = $bro['price']; //中奖聊币
                $broadData['multiple'] = $bro['multiple']; //中奖倍率
                //中奖特效显示时长
                $showTime = 0;
                $showTimeArr = $this->config->luckyGiftConfigs->showTime->toArray();
                foreach ($showTimeArr as $k => $t) {
                    if ($bro['multiple'] >= $k) {
                        $showTime = $t;
                        break;
                    }
                }
                $broadData['showTime'] = $showTime; //单位秒
                $broadData['isFlower'] = $isFlower; //是否幸运桃花
                $broadData['hostUid'] = $hostUid; //主播id
                $list[] = $broadData;
                unset($broadData);
            }


            //广播  以数组形式
            if ($this->config->luckyGiftConfigs->isBroadArr) {
                $ArraySubData['controltype'] = 'luckyGift'; //幸运礼物中奖广播
                $ArraySubData['list'] = $list;
                $list = array(); //中奖的信息数组
                $this->roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData, $roomId);

                //广播 
            } else {
                foreach ($list as $bro) {
                    $ArraySubData['controltype'] = "luckyGift";
                    $ArraySubData['data'] = $bro;
                    $this->comm->roomBroadcast($roomId, $ArraySubData);
                    $this->roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData, $roomId);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //更新幸运礼物概率表 add by 2015/11/03
    private function updateLuckyGiftOdds($giftId, $giftNum) {
        $logger = new \Phalcon\Logger\Adapter\File($this->config->directory->logsDir . '/luckyGift.log');
        try {
            //查询幸运礼物概率表个数
            $selectsql = "select pointer,count from pre_lucky_gift_configs where giftId=" . $giftId;
            $countres = $this->db->fetchOne($selectsql);

            $return['count'] = $countres['count'];

            $isFlower = 0;
            $isJh = 0;
            if ($giftId == $this->config->luckyGiftConfigs->flowerConfigs->giftId || $giftId == $this->config->newyear->giftId) {//幸运桃花
                $isFlower = 1;
                $oddsNum = $this->config->luckyGiftConfigs->flowerConfigs->oddsNum; //概率条数
                $oddsRound = $this->config->luckyGiftConfigs->flowerConfigs->oddsRound; //循环次数
                $array = $this->config->luckyGiftConfigs->flowerConfigs->odds->toArray(); //基本概率配置
                $oddsEx = $this->config->luckyGiftConfigs->flowerConfigs->oddsEx->toArray(); //特殊概率配置
            } elseif ($giftId == $this->config->luckyGiftConfigs->jhConfigs->giftId) {//幸运菊花
                /*                 * $isJh = 1;
                  $oddsNum = $this->config->luckyGiftConfigs->jhConfigs->oddsNum; //概率条数
                  $oddsRound = $this->config->luckyGiftConfigs->jhConfigs->oddsRound; //循环次数
                  $array = $this->config->luckyGiftConfigs->jhConfigs->odds->toArray(); //基本概率配置
                  $oddsEx = $this->config->luckyGiftConfigs->jhConfigs->oddsEx->toArray(); //特殊概率配置
                 * * */
                //改成用桃花的概率 edit by 2015/12/21
                $isFlower = 1;
                $oddsNum = $this->config->luckyGiftConfigs->flowerConfigs->oddsNum; //概率条数
                $oddsRound = $this->config->luckyGiftConfigs->flowerConfigs->oddsRound; //循环次数
                $array = $this->config->luckyGiftConfigs->flowerConfigs->odds->toArray(); //基本概率配置
                $oddsEx = $this->config->luckyGiftConfigs->flowerConfigs->oddsEx->toArray(); //特殊概率配置
            } else {//其他幸运礼物
                $oddsNum = $this->config->luckyGiftConfigs->oddsNum; //概率条数
                $oddsRound = $this->config->luckyGiftConfigs->oddsRound; //循环次数
                $array = $this->config->luckyGiftConfigs->odds->toArray(); //基本概率配置
                $oddsEx = $this->config->luckyGiftConfigs->oddsEx->toArray(); //特殊概率配置
            }


            $remainNum = $countres['pointer'] - $countres['count'];

            if ($remainNum - $giftNum > $oddsNum) {//剩余个数大于概率表需要的个数，不生成新的概率数据
                //更新数据送出幸运礼物个数
                $updatesql = "update pre_lucky_gift_configs set count=count+" . $giftNum . " where giftId=" . $giftId;
                $this->db->execute($updatesql);
                return $return;
            }

            /*
             * 改成每次生成两个概率池， 避免多个用户同时赠送礼物时，概率池还未生成，导致不中奖
             */
            if ($remainNum > 0) {//还有剩余数
                $cycle = floor(($countres['count'] + $giftNum + 2 * $oddsNum) / $oddsNum) - floor($countres['pointer'] / $oddsNum); //需生成多少次概率表
            } else {
                if ($giftNum > $oddsNum) {//如果礼物数量超过单个概率池数量
                    $cycle = floor(($giftNum + 2 * $oddsNum) / $oddsNum); //需生成多少次概率表
                } else {
                    $cycle = 2; //默认生成两个概率池
                }
            }


            //更新数据送出幸运礼物个数,及指针
            $pointerSum = $oddsNum * $cycle;
            $updatesql = "update pre_lucky_gift_configs set count=count+" . $giftNum . ",pointer=pointer+" . $pointerSum . " where giftId=" . $giftId . " and pointer=" . $countres['pointer'];
            $this->db->execute($updatesql);
            $updateres = $this->db->affectedRows(); //判断更新是否成功
            $logger->error('result:' . $updateres . '  updatesql:' . $updatesql); //写日志

            if (!$updateres) {//插入不成功，返回
                return $return;
            }


            $pointer = $countres['pointer'] + 1; //下一个指针

            for ($c = 0; $c < $cycle; $c++) {

                $newArray = array(); //结果数组
                $emArray = array(); //未中奖数组
                $e = 0;
                if ($isFlower) {//如果是幸运桃花 除不尽 临时处理下
                    foreach ($this->config->luckyGiftConfigs->flowerConfigs->oddsRe as $r) {
                        for ($n = 0; $n < $r[1]; $n++) {
                            $oddsRemain[] = $r[0]; //除不尽的剩余倍数
                        }
                    }
                    //打乱数组
                    shuffle($oddsRemain);
                }

                for ($r = 0; $r < $oddsRound; $r++) {//循环
                    //生成一维数组
                    $newArraytemp = array();
                    foreach ($array as $val) {
                        for ($i = 0; $i < $val[1]; $i++) {
                            $newArraytemp[] = $val[0]; //单个循环的结果
                        }
                    }

                    if ($isFlower) {//幸运桃花 除不尽 临时处理下
                        $max = count($oddsRemain) - 1;
                        $exrand = mt_rand(0, $max); //随机选出一个位置
                        $newArraytemp[] = $oddsRemain[$exrand];
                        array_splice($oddsRemain, $exrand, 1); //移除该条记录
                    }


                    //打乱单个循环结果数组
                    shuffle($newArraytemp);


                    //重新赋值键值
                    foreach ($newArraytemp as $vn) {
                        $newkey = $pointer++;
                        if ($vn > 0) {//有中奖的写入数据库
                            $newArray[$newkey] = $vn;
                        } else {//未中奖的结果存于数组中
                            $emArray[$e++] = $newkey;
                        }
                    }
                }


                //幸运桃花 临时处理下 保证每5000个概率里面有一个1000/2000倍数
                if ($isFlower) {
                    $otherCycle = count($this->config->luckyGiftConfigs->flowerConfigs->oddsEx->toArray());
                    $onum = floor((count($emArray) - $otherCycle) / $otherCycle);
                    $startnum = 0;
                    for ($o = 0; $o < $otherCycle; $o++) {
                        $max = $startnum + $onum - 1;
                        $exrand = mt_rand($startnum, $max); //从未中奖的位置随机选出一个位置
                        $startnum+=$onum;
                        $exrandValue = $emArray[$exrand];
                        $newArray[$exrandValue] = $this->config->luckyGiftConfigs->flowerConfigs->oddsEx->toArray()[$o + 1][0]; //中奖倍数
                        array_splice($emArray, $exrand, 1); //移除未中奖结果数组中的该条记录
                    }
                } else {
                    //添加特殊概率
                    foreach ($oddsEx as $vo) {
                        for ($n = 0; $n < $vo[1]; $n++) {
                            $max = count($emArray) - 1;
                            $exrand = mt_rand(0, $max); //从未中奖的位置随机选出一个位置
                            $exrandValue = $emArray[$exrand];
                            $newArray[$exrandValue] = $vo[0]; //增加到中奖记录中
                            array_splice($emArray, $exrand, 1); //移除未中奖结果数组中的该条记录
                        }
                    }
                }

                //幸运桃花 临时处理下  保证每1000个概率里面有一个100倍数
                if ($isFlower) {
                    $otherCycle = $this->config->luckyGiftConfigs->flowerConfigs->oddsOt->toArray()[1];
                    $onum = floor((count($emArray) - $otherCycle) / $otherCycle);
                    $startnum = 0;
                    for ($o = 0; $o < $otherCycle; $o++) {
                        $max = $startnum + $onum - 1;
                        $exrand = mt_rand($startnum, $max); //从未中奖的位置随机选出一个位置
                        $startnum+=$onum;
                        $exrandValue = $emArray[$exrand];
                        $newArray[$exrandValue] = $this->config->luckyGiftConfigs->flowerConfigs->oddsOt->toArray()[0]; //中奖倍数
                        array_splice($emArray, $exrand, 1); //移除未中奖结果数组中的该条记录
                    }
                }
                //幸运菊花 临时处理下  保证每5000个概率里面有一个500倍数
                elseif ($isJh) {
                    $otherCycle = $this->config->luckyGiftConfigs->jhConfigs->oddsOt->toArray()[1];
                    $onum = floor((count($emArray) - $otherCycle) / $otherCycle);
                    $startnum = 0;
                    for ($o = 0; $o < $otherCycle; $o++) {
                        $max = $startnum + $onum - 1;
                        $exrand = mt_rand($startnum, $max); //从未中奖的位置随机选出一个位置
                        $startnum+=$onum;
                        $exrandValue = $emArray[$exrand];
                        $newArray[$exrandValue] = $this->config->luckyGiftConfigs->jhConfigs->oddsOt->toArray()[0]; //中奖倍数
                        array_splice($emArray, $exrand, 1); //移除未中奖结果数组中的该条记录
                    }
                }


                //键值升序排序
                ksort($newArray);


                //构造sql
                $vArray = array();
                foreach ($newArray as $k => $v) {
                    $vArray[] = "(" . $v . "," . $k . "," . $giftId . ")";
                }
                $values = implode(',', $vArray);


                //写入数据库
                $insertsql = "insert into pre_lucky_gift_odds(multiple,sequence,giftId) values" . $values;
                $insertres = $this->db->execute($insertsql);
                //写日志
                $logger->error('result:' . $insertres . '  insertsql:' . $insertsql);
            }

            return $return;
        } catch (\Exception $e) {
            //写日志
            $logger->error('updateLuckyGiftOdds error:errorMessage = ' . $e->getMessage());
            return;
        }
    }

    //主播查询红包记录列表  add by 2015/11/06
    public function getUserRedPacketList($roomId) {
        if (!$this->config->redPacketConfigs->enable) {//活动未开启
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $postData['roomid'] = $roomId; //房间id
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        try {
            //查询房间信息
            $roomInfo = \Micro\Models\Rooms::findfirst($roomId);

            if ($this->config->redPacketConfigs->checkIsOnlyAnchor) {//只有该房间的主播本人可以查看
                if (!$roomInfo || $roomInfo->uid != $uid) {
                    return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'));
                }
            }

            //查询该房间红包列表
            $time = time() - 86400; //查询24小时内的红包记录
            $sql = "select r.id,ui.nickName,r.initTime,r.status,r.sumMoney as money from pre_red_packet r inner join pre_user_info ui on r.uid=ui.uid where r.roomId=" . $roomId . " and r.initTime>" . $time . " order by r.initTime desc";
            $res = $this->db->fetchAll($sql);
            $return = array();
            $sum = 0;
            $list = array();
            foreach ($res as $val) {
                $data['id'] = $val['id'];
                $data['sendNickName'] = $val['nickName'];
                $data['money'] = $val['money'];
                $sum+=$val['money'];

                $list[] = $data;
                unset($data);
            }
            $count = count($res);
            $return['totalMoney'] = $sum;
            $return['count'] = $count;
            $return['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //把该幸运礼物的中奖放到后面的概率池中
    private function dealLuckyGiftOdds($giftId, $oddId) {
        try {
            //查询幸运礼物概率表未使用剩余个数
            $selectsql = "select count,pointer from pre_lucky_gift_configs where giftId=" . $giftId;
            $countres = $this->db->fetchOne($selectsql);
            $oddsNum = $this->config->luckyGiftConfigs->flowerConfigs->oddsNum; //每个概率池的条数

            $round = floor(($countres['pointer'] - $countres['count']) / $oddsNum) + 1; //未使用的概率池的个数

            $indexStart = $countres['count'] + 1; //未使用的概率池开始位置
            $indexEnd = $countres['pointer'] - $oddsNum * ($round - 1); //未使用的第一个概率池结束位置
            //一个一个概率池循环 查询应放在哪个概率池中
            for ($c = 0; $c < $round; $c++) {
                //查询概率池中奖位置
                $indexsql = "select sequence from pre_lucky_gift_odds where giftId=" . $giftId . " and sequence>=" . $indexStart . " and sequence<=" . $indexEnd;
                $indexres = $this->db->fetchAll($indexsql);
                $hsasequence = array(); //中奖的位置数组
                $selist = array(); //可生成新的概率的位置
                foreach ($indexres as $val) {
                    $hsasequence[] = $val['sequence'];
                }
                for ($i = $indexStart; $i <= $indexEnd; $i++) {
                    if (!in_array($i, $hsasequence)) {//过滤掉已生成中奖数据的位置
                        $selist[] = $i;
                    }
                }

                if ($selist) {//如果找到概率池，则跳出循环
                    break;
                }

                $indexEnd+=$oddsNum; //下一轮的概率池结束位置
            }


            if ($selist) {
                $secount = count($selist) - 1;
                $newSequence = $selist[mt_rand(0, $secount)]; //生成新的位置
                //更新位置
                $updatesql = "update pre_lucky_gift_odds set sequence=" . $newSequence . " where id=" . $oddId;
                $this->db->execute($updatesql);
            }
            return;
        } catch (\Exception $e) {
            $this->logger->error('dealLuckyGiftOdds error:errorMessage = ' . $e->getMessage());
            return;
        }
        return;
    }

    //送圣诞礼物触发
    public function sendChristmasGift($roomId, $anchorUid, $giftNum) {
        if (time() < $this->config->christmas->startTime || time() > $this->config->christmas->endTime) {//活动未开始或已结束
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $return = array();
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {
            //更新主播圣诞礼物记录表
            $updatesql = "insert into pre_christmas(uid,count)values({$anchorUid},{$giftNum}) on duplicate key update count=count+{$giftNum}";
            $this->db->execute($updatesql);

            //查询主播圣诞礼物个数
            $selectsql = "select count from pre_christmas where uid=" . $anchorUid;
            $selectres = $this->db->fetchOne($selectsql);

            $count = $selectres['count'] ? $selectres['count'] : 0; //主播圣诞礼物总数
            //判断圣诞树等级
            $level = 0;
            foreach ($this->config->christmas->levelConfig as $l) {
                if ($count >= $l['min'] && $count < $l['max']) {
                    $level = $l['level'];
                    $limit = $l['max'];
                    break;
                }
            }

            //广播
            $broadData['count'] = (int) $count;
            $broadData['limit'] = $limit;
            $broadData['level'] = $level; //圣诞树等级
            $ArraySubData['controltype'] = "christmasGift"; //圣诞礼物
            $ArraySubData['data'] = $broadData;
            $this->comm->roomBroadcast($roomId, $ArraySubData);


            /*
             * *是否中奖处理
             */
            $giftId = $this->config->christmas->giftId;

            //查询幸运礼物已累计个数
            $configsql = "select count from pre_lucky_gift_configs where giftId=" . $giftId;
            $countres = $this->db->fetchOne($configsql);

            $oldCount = $countres['count'];
            $newCount = $oldCount + $giftNum;

            $this->christmasOdds($giftId, $giftNum); //生成礼物概率表
            //查询概率表，查询该区间所有的中奖倍数
            $selectsql = "select id from pre_christmas_log where sequence>" . $oldCount . " and sequence<=" . $newCount;
            $oddsres = $this->db->fetchAll($selectsql);

            if (!$oddsres) {//没有中奖
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }

            $userInfo = $user->getUserInfoObject()->getUserInfo();

            $hostUser = UserFactory::getInstance($anchorUid);
            $hoserUserInfo = $hostUser->getUserInfoObject()->getUserInfo();

            foreach ($oddsres as $val) {
                //给用户送座驾
                $user->getUserItemsObject()->giveCar($this->config->christmas->reward->carId, $this->config->christmas->reward->expireTime);

                //给用户发消息
                $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $this->config->christmas->reward->message, 'link' => '', 'operType' => ''));

                //写入圣诞日志
                $updatesql = "update pre_christmas_log set uid=" . $uid . ",editTime=" . time() . " where id=" . $val['id'];
                $this->db->execute($updatesql);


                //世界喇叭
                $broadData['hostUid'] = $anchorUid;
                $broadData['roomId'] = $roomId;
                $broadData['hostName'] = $hoserUserInfo['nickName']; //主播昵称
                $broadData['type'] = 4; //圣诞活动中奖
                $broadData['userdata'] = array('nickName' => $userInfo['nickName']);
                $ArraySubData['controltype'] = "worldbroadcast";
                $ArraySubData['data'] = $broadData;
                $roomModule = $this->di->get('roomModule');
                $roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //圣诞礼物活动概率
    private function christmasOdds($giftId, $giftNum) {
        //查询概率表未使用剩余个数
        $selectsql = "select pointer-count as remainNum,pointer,count from pre_lucky_gift_configs where giftId=" . $giftId;
        $countres = $this->db->fetchOne($selectsql);

        $oddsNum = $this->config->christmas->oddsNum; //概率条数
        $array = $this->config->christmas->odds->toArray(); //基本概率配置


        if ($countres['remainNum'] - $giftNum > $oddsNum) {//剩余个数大于概率表需要的个数，不生成新的概率数据
            //更新数据送出礼物个数
            $updatesql = "update pre_lucky_gift_configs set count=count+" . $giftNum . " where giftId=" . $giftId;
            $this->db->execute($updatesql);
            return;
        }

        if ($countres['remainNum'] > 0) {//还有剩余数
            $cycle = floor(($countres['count'] + $giftNum + 2 * $oddsNum) / $oddsNum) - floor($countres['pointer'] / $oddsNum); //需生成多少次概率表
        } else {
            if ($giftNum > $oddsNum) {//如果礼物数量超过单个概率池数量
                $cycle = floor(($giftNum + 2 * $oddsNum) / $oddsNum); //需生成多少次概率表
            } else {
                $cycle = 2; //默认生成两个概率池
            }
        }

        //更新数据送出礼物个数,及指针
        $pointerSum = $oddsNum * $cycle;
        $updatesql = "update pre_lucky_gift_configs set count=count+" . $giftNum . ",pointer=pointer+" . $pointerSum . " where giftId=" . $giftId;
        $this->db->execute($updatesql);


        $pointer = $countres['pointer'] + 1; //下一个指针

        for ($c = 0; $c < $cycle; $c++) {
            $newArray = array(); //结果数组
            $emArray = array(); //未中奖数组
            $e = 0;

            //生成一维数组
            $newArraytemp = array();
            foreach ($array as $val) {
                for ($i = 0; $i < $val[1]; $i++) {
                    $newArraytemp[] = $val[0]; //单个循环的结果
                }
            }

            //打乱单个循环结果数组
            shuffle($newArraytemp);

            //重新赋值键值
            foreach ($newArraytemp as $vn) {
                $newkey = $pointer++;
                if ($vn > 0) {//有中奖的写入数据库
                    $newArray[$newkey] = $vn;
                } else {//未中奖的结果存于数组中
                    $emArray[$e++] = $newkey;
                }
            }

            //键值升序排序
            ksort($newArray);


            //构造sql
            $vArray = array();
            foreach ($newArray as $k => $v) {
                $vArray[] = "(" . $k . ")";
            }
            $values = implode(',', $vArray);


            //写入数据库
            $insertsql = "insert into pre_christmas_log(sequence) values" . $values;
            $this->db->execute($insertsql);
        }
        return;
    }

    //查询圣诞树信息接口
    public function getRoomChristmasInfo($anchorUid) {
        if (time() < $this->config->christmas->startTime || time() > $this->config->christmas->endTime) {//活动未开始或已结束
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $return = array();
        try {
            //查询主播圣诞礼物个数
            $selectsql = "select count from pre_christmas where uid=" . $anchorUid;
            $selectres = $this->db->fetchOne($selectsql);

            $count = $selectres['count'] ? $selectres['count'] : 0; //主播圣诞礼物总数
            //判断圣诞树等级
            $level = 0;
            foreach ($this->config->christmas->levelConfig as $l) {
                if ($count >= $l['min'] && $count < $l['max']) {
                    $level = $l['level'];
                    $limit = $l['max'];
                    break;
                }
            }
            $return['count'] = $count;
            $return['limit'] = $limit;
            $return['level'] = $level;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //送元旦礼物触发
    public function sendnewYearGift($giftInfo, $giftNum, $nickName, $anchorUid, $roomId) {
        if (time() < $this->config->newyear->startTime || time() > $this->config->newyear->endTime) {//活动未开始或已结束
            return $this->status->retFromFramework($this->status->getCode('ACTIVITY_END'));
        }
        $return = array();
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {

            $giftId = $this->config->newyear->giftId;
            $limit = $this->config->newyear->limit;

            //查询主播之前礼物个数
            $selectsql = "select count from pre_gift_collect_log where uid=" . $anchorUid . " and giftId=" . $giftId;
            $selectres = $this->db->fetchOne($selectsql);

            $oldCount = $selectres['count'] ? $selectres['count'] : 0; //主播礼物数

            $nowCount = $oldCount + $giftNum; //主播当前礼物数
            //更新收到的礼物数量
            $this->updateGiftNum($anchorUid, $giftId, $giftNum);

            $count = $nowCount % $limit;
            //广播
            $broadData['count'] = $count;
            $broadData['limit'] = $limit;
            $ArraySubData['controltype'] = "newyearGift"; //元旦礼物
            $ArraySubData['data'] = $broadData;
            $this->comm->roomBroadcast($roomId, $ArraySubData);


            /*
             * 是否触发开门红动画
             */
            $oldmod = $oldCount % $limit;
            if (($oldmod + $giftNum) >= $limit) {//达到开门红数量要求
                $num = floor(($oldmod + $giftNum) / $limit);
                for ($i = 0; $i < $num; $i++) {
                    //广播
                    $broadData = array();
                    $broadData['configName'] = $this->config->newyear->configName;
                    $ArraySubData['controltype'] = "specialSwf"; //特殊大礼物特效
                    $ArraySubData['data'] = $broadData;
                    $this->comm->roomBroadcast($roomId, $ArraySubData);
                }
            }


            /*
             * *是否中奖处理
             */
            //查询是否是中奖时间段
            $now = time();
            $isRightTime = 0; //是否为中奖时间段
            $type = 0; //类型
            $isZhong = 0; //是否中奖
            foreach ($this->config->newyear->timeArr as $key => $val) {
                $time1 = strtotime(date("Ymd" . " " . $val[0]));
                $time2 = strtotime(date("Ymd" . " " . $val[1]));
                if ($now > $time1 && $now < $time2) {
                    $type = $key;
                    $isRightTime = 1;
                    break;
                }
            }

            $today = date("Ymd");
            if ($isRightTime) {
                //查询该时间段是否中奖过
                $selectsql = "select uid from pre_newyear_log where type=" . $type . " and date=" . $today . " order by id desc";
                $selectres = $this->db->fetchOne($selectsql);
                if ($selectres && $selectres['uid']) {//已经中奖过
                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }
                $oddsNum = $this->config->newyear->oddsNum; //概率数
                if ($giftNum >= $oddsNum) {//送的数量大于概率数
                    $isZhong = 1; //中奖
                } else {
                    $n0 = $oddsNum - $giftNum; //未中奖数
                    $arr = array('0' => $n0, '1' => $giftNum);
                    $zhongres = $this->normalLib->getProRand($arr);
                    if ($zhongres == '1') {//中奖
                        $isZhong = 1;
                    }
                }
            }

            if (!$isZhong) {//没有中奖
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }

            //写入元旦日志
            $insertsql = "insert into pre_newyear_log(date,type,uid,getTime)values({$today},{$type},{$uid},{$now})";
            $this->db->execute($insertsql);

            //增加聊币
            $cashNum = $this->config->newyear->cashNum;
            $this->userCash->addUserCash($cashNum, $uid, 0);
            //写入聊币记录表
            $this->userCash->addCashLog($cashNum, $this->config->cashSource->lucky, $giftId, $uid);


            $broadData = array();
            $broadData['nickName'] = $nickName; //昵称
            $broadData['giftName'] = $giftInfo->name; //礼物名称
            $broadData['giftConfigName'] = $giftInfo->configName; //礼物图标索引名称
            $broadData['price'] = $cashNum; //中奖聊币
            $broadData['multiple'] = floor($cashNum / $giftInfo->cash); //中奖倍率
            //中奖特效显示时长
            $broadData['showTime'] = 5; //单位秒
            $broadData['isFlower'] = 1; //是否幸运桃花
            $broadData['hostUid'] = $anchorUid; //主播id
            $list[] = $broadData;


            //广播  以数组形式
            $ArraySubData['controltype'] = 'luckyGift'; //幸运礼物中奖广播
            $ArraySubData['list'] = $list;
            $list = array(); //中奖的信息数组
            $this->roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData, $roomId);

            //世界喇叭
            $hostUser = UserFactory::getInstance($anchorUid);
            $hoserUserInfo = $hostUser->getUserInfoObject()->getUserInfo();
            $broadData['hostUid'] = $anchorUid;
            $broadData['roomId'] = $roomId;
            $broadData['hostName'] = $hoserUserInfo['nickName']; //主播昵称
            $broadData['type'] = 5; //元旦活动中奖
            $broadData['userdata'] = array('nickName' => $nickName);
            $ArraySubData['controltype'] = "worldbroadcast";
            $ArraySubData['data'] = $broadData;
            $roomModule = $this->di->get('roomModule');
            $roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData);

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //更新主播礼物数量
    private function updateGiftNum($anchorUid, $giftId, $giftNum) {
        try {
            //更新主播礼物记录表
            $updatesql = "insert into pre_gift_collect_log(uid,giftId,count)values({$anchorUid},{$giftId},{$giftNum}) on duplicate key update count=count+{$giftNum}";
            $this->db->execute($updatesql);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //年味礼物
    public function springFestival($roomId, $anchorData, $userData, $giftId, $giftNum) {
        $return = array();
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        try {

            $isSuccess = 0;
            while ($isSuccess < 1) {
                $giftkey = $this->config->springFestival->giftIds->toArray()[0];
                $selectsql = "select pointer,count from pre_lucky_gift_configs where giftId=" . $giftkey;
                $selectres = $this->db->fetchOne($selectsql);

                $count = $selectres['count']; //年味累计数量
                $round = $selectres['pointer']; //当前第几轮
                $flavors = $this->config->springFestival->flavors[$giftId] * $giftNum; //年味值

                $newCount = $count + $flavors;
                if ($newCount >= $this->config->springFestival->oddsNum) {//年味积满，触发中奖
                    $n = floor($newCount / $this->config->springFestival->oddsNum); //触发中奖次数
                    $newCount = $newCount % $this->config->springFestival->oddsNum; //求余
                    $percount = $count;
                    for ($i = 0; $i < $n; $i++) {
                        //插入用户送礼记录
                        $index = $round + $i;
                        $num = $this->config->springFestival->oddsNum - $percount;
                        $insertsql = "insert into pre_spring_festival_log(round,uid,count)values({$index},{$uid},{$num})";
                        $this->db->execute($insertsql);
                        $percount = 0;
                        //抽取中奖用户
                        $logsql = "select uid,count from pre_spring_festival_log where round=" . $index;
                        $logres = $this->db->fetchAll($logsql);
                        $broadres = array();
                        foreach ($this->config->springFestival->rewards as $r) {
                            $resarr = array();
                            foreach ($logres as $val) {
                                isset($resarr[$val['uid']]) ? $resarr[$val['uid']]+= $val['count'] : $resarr[$val['uid']] = $val['count'];
                            }
                            $zhonguid = $this->normalLib->getProRand($resarr);
                            //增加聊币
                            $this->userCash->addUserCash($r['cash'], $zhonguid, 0);
                            //写入聊币记录表
                            $this->userCash->addCashLog($r['cash'], $this->config->cashSource->lucky, $giftkey, $zhonguid);
                            // 给用户发信息
                            $zhonguser = UserFactory::getInstance($zhonguid);
                            $zhonguser->getUserInformationObject()->addUserInformation($this->config->informationType->system, array('content' => $r['message']));
                            $data['uid'] = $zhonguid;
                            $data['cash'] = $r['cash'];
                            $zhonguserinfo = $zhonguser->getUserInfoObject()->getUserInfo();
                            $data['nickName'] = $zhonguserinfo['nickName'];
                            $broadres[] = $data;
                            unset($data);
                        }
                        //广播中奖结果
                        $broadData = array();
                        $broadData['list'] = $broadres;
                        $ArraySubData['controltype'] = "springFestivalResult"; //春节活动中奖结果
                        $ArraySubData['data'] = $broadData;
                        $roomModule = $this->di->get('roomModule');
                        $roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData);
                    }

                    $round+=$n;
                    if ($newCount > 0) {
                        //插入用户送礼记录
                        $insertsql = "insert into pre_spring_festival_log(round,uid,count)values({$round},{$uid},{$newCount})";
                        $this->db->execute($insertsql);
                    }

                    //更新数据库
                    $updatesql = "update pre_lucky_gift_configs set count=" . $newCount . ",pointer=" . $round . " where giftId=" . $giftkey;
                    $this->db->execute($updatesql);
                    $isSuccess = 1;
                } else {//年味未积满，继续累计
                    //更新数据库
                    $updatesql = "update pre_lucky_gift_configs set count=" . $newCount . " where giftId=" . $giftkey . " and count=" . $count;
                    $this->db->execute($updatesql);
                    $updateres = $this->db->affectedRows(); //判断更新是否成功
                    if ($updateres) {
                        $isSuccess = 1;
                        //插入用户送礼记录
                        $insertsql = "insert into pre_spring_festival_log(round,uid,count)values({$round},{$uid},{$giftNum})";
                        $this->db->execute($insertsql);
                    }
                }
            }

            //座驾中奖判断
            $isZhong = 0;
            $arr0 = $this->config->springFestival->carProbability[$giftId]->toArray(); //1个礼物的中奖概率
            $arr = array(); //N个礼物的概率数组
            $arr['1'] = $arr0['1'] * $giftNum; //中奖的概率
            $arr['0'] = $arr0['0'] + $arr0['1'] - $arr['1']; //不中奖的概率
            $arr['0'] < 0 && $arr['0'] = 0;
            $zhongres = $this->normalLib->getProRand($arr); //中奖判断
            if ($zhongres == '1') {//中奖
                $isZhong = 1;
            }

            if ($isZhong) {//中奖
                //查询已送出多少座驾
                $carcountsql = "select count(*) count from pre_spring_festival_log where getCar>0";
                $carcountres = $this->db->fetchOne($carcountsql);
                if ($carcountres['count'] < $this->config->springFestival->carLimitNum) {
                    //查询该用户是否中奖过该座驾
                    $usercarsql = "select id from pre_spring_festival_log where uid=" . $uid . " and getCar>0";
                    $usercarres = $this->db->fetchOne($usercarsql);
                    if (!$usercarres) {
                        //给用户发放座驾
                        $user->getUserItemsObject()->giveCar($this->config->springFestival->carId, $this->config->springFestival->expireTime);
                        //写入记录
                        $carupdatesql = "update pre_spring_festival_log set getCar=" . $this->config->springFestival->carId . " where uid=" . $uid . " and round=" . $round . " limit 1";
                        $this->db->execute($carupdatesql);
                        //世界喇叭
                        $roomModule = $this->di->get('roomModule');
                        $roomModule->getRoomOperObject()->sendWorldBroadcast($this->config->worldBroadcastType->springFestival, $roomId, $anchorData, $userData);
                    }
                }
            }

            //广播年味进度
            $broadData = array();
            $broadData['limit'] = $this->config->springFestival->oddsNum; //年味下限
            $broadData['count'] = $newCount; //年味收集数
            $ArraySubData['controltype'] = "springFestival"; //春节活动
            $ArraySubData['data'] = $broadData;
            $roomModule = $this->di->get('roomModule');
            $roomModule->getRoomOperObject()->allRoomBroadcast($ArraySubData);


            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询年味值
    public function getSpringFestivalInfo() {
        try {
            $giftkey = $this->config->springFestival->giftIds->toArray()[0];
            $sql = "select count from pre_lucky_gift_configs where giftId=" . $giftkey;
            $res = $this->db->fetchOne($sql);
            $return['count'] = $res['count'];
            $return['limit'] = $this->config->springFestival->oddsNum; //年味下限
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询红包排行榜列表
    public function getMoneyRedPacketList() {
        $return = array();
        try {
            $sql = "select l.uid,ui.nickName,count(*)count from pre_red_packet l "
                    . "inner join pre_user_info ui on l.uid=ui.uid "
                    . "where l.redPacketType=2 and l.status>0 group by l.uid order by count desc limit 10";
            $list = $this->db->fetchAll($sql);
            $sendlist = array();
            foreach ($list as $val) {
                $data['uid'] = $val['uid'];
                $data['nickName'] = $val['nickName'];
                $data['count'] = $val['count'];
                $sendlist[] = $data;
                unset($data);
            }
            $sql = "select l.uid,ui.nickName,count(*)count from pre_red_packet_log l "
                    . "inner join pre_red_packet r on l.redPacketId=r.id "
                    . "inner join pre_user_info ui on l.uid=ui.uid "
                    . "where r.redPacketType=2 group by l.uid order by count desc limit 10";

            $list = $this->db->fetchAll($sql);
            $getlist = array();
            foreach ($list as $val) {
                $data['uid'] = $val['uid'];
                $data['nickName'] = $val['nickName'];
                $data['count'] = $val['count'];
                $getlist[] = $data;
                unset($data);
            }
            $return['sendlist'] = $sendlist;
            $return['getlist'] = $getlist;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获得自己的抢红包发红包的个数
    public function getMyRedPacketCount() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $return = array();
        try {
            $sql = "select count(*)count from pre_red_packet  "
                    . "where redPacketType=2 and uid=" . $uid . " and status>0";
            $res = $this->db->fetchOne($sql);
            $return['sendCount'] = $res['count'] ? $res['count'] : 0;

            $sql = "select count(*)count from pre_red_packet_log l "
                    . "inner join pre_red_packet r on l.redPacketId=r.id "
                    . "where r.redPacketType=2 and l.uid=" . $uid;
            $res = $this->db->fetchOne($sql);
            $return['getCount'] = $res['count'] ? $res['count'] : 0;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //
    /**
     * 获取活动结果统计
     * @param $times 期数
     * @param $type 类型【1-电影众筹】
     * @param $uid 主播uid 可选
     * @return array
     */
    public function activitySummary($times = 0, $type = 1, $uid = 0){
        //数据检查
        $checkData = array('type'=>$type);
        $uid && $checkData['uid'] = $uid;
        $times && $checkData['id'] = $times;
        $isValid = $this->validator->validate($checkData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            //检查或是否存在
            $activityRound = \Micro\Models\ActivityRound::findfirst('type = ' . $type);
            if(!$activityRound){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            //数据处理
            $data = array();
            !$times && $times = $activityRound->times;
            if($activityRound->times == $times){//进行中取实时数据并写缓存
                //读取缓存
                /*$normalLib = $this->di->get('normalLib');
                $cacheKey = 'movie_rank';
                $cacheResult = $normalLib->getCache($cacheKey);
                if (isset($cacheResult)) {
                    return $this->status->retFromFramework($this->status->getCode('OK'), $cacheResult);
                }*/
                //条件拼接
                $where = 'type = 1';
                $uid && $where .= ' and uid = ' . $uid;
                $res = \Micro\Models\ActivityAnchors::find($where);

                $startTime = $this->config->anchorMovie->beginTime + $this->config->anchorMovie->periodTime * ($times - 1);
                $endTime = $startTime + $this->config->anchorMovie->periodTime;

                $j = 0;
                if(count($res) > 0){
                    foreach ($res as $k => $v) {
                        $anchor = UserFactory::getInstance($v->uid);
                        $anchorInfo = $anchor->getUserInfoObject()->getUserInfo();
                        $data[$j]['uid'] = $v->uid;
                        $data[$j]['nickName'] = $anchorInfo['nickName'];
                        $data[$j]['avatar'] = $anchorInfo['avatar'];
                        //主播获取电影票收入总额
                        $sumsql = "select sum(count) sum "
                            . "from \Micro\Models\ConsumeDetailLog "
                            . "where receiveUid=" . $v->uid . " and type=" . $this->config->consumeType->sendGift
                            . " and itemId=" . $this->config->anchorMovie->giftId . " and createTime >= " . $startTime . " and createTime < " . $endTime;
                        $sumquery = $this->modelsManager->createQuery($sumsql);
                        $sumres = $sumquery->execute();
                        $sumres = $sumres->toArray();

                        $data[$j]['sum'] = $sumres[0]['sum'] ? $sumres[0]['sum'] : 0;
                        $sort[] = $data[$j]['sum'];

                        //主播下贡献前十的用户信息
                        $listsql = "select uid,sum(count) sum "
                            . "from \Micro\Models\ConsumeDetailLog "
                            . "where receiveUid=" . $v->uid . " and type=" . $this->config->consumeType->sendGift
                            . " and itemId=" . $this->config->anchorMovie->giftId . " and createTime >= " . $startTime . " and createTime < " . $endTime
                            . " group by uid order by sum desc limit 10";
                        $listquery = $this->modelsManager->createQuery($listsql);
                        $listres = $listquery->execute();
                        $listres = $listres->toArray();
                        $list = array();
                        if ($listres) {
                            foreach ($listres as $val) {
                                $user = UserFactory::getInstance($val['uid']);
                                $userInfo = $user->getUserInfoObject()->getUserInfo();
                                $tmp = array(
                                    'avatar' => $userInfo['avatar'],
                                    'nickName' => $userInfo['nickName'],
                                    'sum' => $val['sum']
                                );
                                array_push($list, $tmp);
                            }
                        }
                        $data[$j]['list'] = $list;
                        $j++;
                    }
                    //排序
                    array_multisort($sort, SORT_DESC, $data);
                }

                // //设置缓存
                // $liftTime = 300; //有效期5分钟
                // $normalLib->setCache($cacheKey, $data, $liftTime);

            }else{//不是进行中取日志表数据
                $res = \Micro\Models\ActivityResultLog::findFirst('times = ' . $times . ' and type = ' . $type);
                if($res){
                    $startTime = $res->startTime;
                    $endTime = $res->endTime;
                    $data = json_decode($res->rankInfo, true);
                    if($uid){
                        foreach ($data as $key => $value) {
                            if($value['uid'] == $uid){
                                $data = $value;break;
                            }
                        }
                    } 
                }else{
                    return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
                }
            }

            //返回数据
            return $this->status->retFromFramework(
                $this->status->getCode('OK'), 
                array(
                    'lists' => $data,
                    'totalTimes' => $activityRound->times,
                    'finishNum' => $this->config->anchorMovie->finishNum,
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'nowTimes' => $times
                )
            );
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //主播电影众筹活动送礼广播
    public function anchorMovieBroad($roomId =0, $anchorUid = 0, $giftId = 0){
        try {
            //判断电影众筹正在进行的期数
            $activityRound = \Micro\Models\ActivityRound::findfirst('type = 1');
            if(!$activityRound){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $startTime = $this->config->anchorMovie->beginTime + $this->config->anchorMovie->periodTime * ($activityRound->times - 1);
            $endTime = $startTime + $this->config->anchorMovie->periodTime;
            $sumsql = "select sum(count) sum "
                . "from \Micro\Models\ConsumeDetailLog "
                . "where receiveUid=" . $anchorUid . " and type=" . $this->config->consumeType->sendGift
                . " and itemId=" . $giftId . " and createTime >= " . $startTime . " and createTime < " . $endTime;
            $sumquery = $this->modelsManager->createQuery($sumsql);
            $sumres = $sumquery->execute();
            $sumres = $sumres->toArray();
            $nums = $sumres[0]['sum'] ? $sumres[0]['sum'] : 0;

            //广播电影众筹进度
            $broadData = array();
            $broadData['finishNum'] = $this->config->anchorMovie->finishNum; //任务完成最低数目
            $broadData['count'] = $nums; //电影众筹数
            $ArraySubData['controltype'] = "anchorMovie"; //电影众筹
            $ArraySubData['data'] = $broadData;
            $roomModule = $this->di->get('roomModule');
            $this->comm->roomBroadcast($roomId, $ArraySubData);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取直播间电影众筹信息
    public function getAnchorMovieInfo($uid = 0){
        $isValid = $this->validator->validate(array('uid'=>$uid));
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            //判断时间是否在活动内
            if(time() < $this->config->anchorMovie->beginTime || time() > $this->config->anchorMovie->endTime){
                return $this->status->retFromFramework($this->status->getCode('NOT_IN_ACTIVITY_PERIOD'));
            }
            //判断主播是否在活动中
            $res = \Micro\Models\ActivityAnchors::findFirst('type = 1 and uid = ' . $uid);
            if(!$res){
                return $this->status->retFromFramework($this->status->getCode('NOT_JOIN_ACTIVITY'));
            }
            //判断电影众筹正在进行的期数
            $activityRound = \Micro\Models\ActivityRound::findfirst('type = 1');
            if(!$activityRound){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $startTime = $this->config->anchorMovie->beginTime + $this->config->anchorMovie->periodTime * ($activityRound->times - 1);
            $endTime = $startTime + $this->config->anchorMovie->periodTime;
            $sumsql = "select sum(count) sum "
                . "from \Micro\Models\ConsumeDetailLog "
                . "where receiveUid=" . $uid . " and type=" . $this->config->consumeType->sendGift
                . " and itemId=" . $this->config->anchorMovie->giftId . " and createTime >= " . $startTime . " and createTime < " . $endTime;
            $sumquery = $this->modelsManager->createQuery($sumsql);
            $sumres = $sumquery->execute();
            $sumres = $sumres->toArray();
            $nums = $sumres[0]['sum'] ? $sumres[0]['sum'] : 0;

            return $this->status->retFromFramework($this->status->getCode('OK'), array('count'=>$nums,'finishNum'=>$this->config->anchorMovie->finishNum));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
