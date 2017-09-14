<?php

namespace Micro\Frameworks\Logic\Rank;

use Phalcon\DI\FactoryDefault;
use Micro\Models\Family;
use Micro\Models\UserProfiles;
use Micro\Models\RankLog;
use Micro\Models\GiftLog;
use Micro\Models\GiftConfigs;
use Micro\Models\ConsumeLog;
use Micro\Models\SignAnchor;
use Micro\Models\Rooms;
use Micro\Frameworks\Logic\User\UserData\UserInfo;
use Micro\Frameworks\Logic\User\UserAuth;
use Micro\Frameworks\Logic\User\UserFactory;

class Rank {

    protected $di;
    protected $status;
    protected $session;
    protected $config;
    protected $validator;
    protected $logger;
    protected $userAuth;
    protected $pathGenerator;
    protected $storage;
    protected $modelsManager;
    protected $comm;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->session = $this->di->get('session');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->logger = $this->di->get('logger');
        $this->userAuth = $this->di->get('userAuth');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->storage = $this->di->get('storage');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->comm = $this->di->get('comm');
    }

    /*
     * 获得明星排行(收益排行)
     */

    public function getStarRank($type) {
        try {
            // 读取缓存
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['star_' . $type]);
            if ($arrData) {
                $arrData = array_slice($arrData->toArray(), 0, 10);
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                //return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获得富豪排行(消费聊币)
     */

    public function getRichRank($type) {
        try {
            // 读取缓存
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['rich_' . $type]);
            if ($arrData) {
                $arrData = array_slice($arrData->toArray(), 0, 10);
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                //return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获得人气排行（关注人数）
     */

    public function getFansRank($type) {
        try {
            // 读取缓存
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['fans_' . $type]);
            if ($arrData) {
                $arrData = array_slice($arrData->toArray(), 0, 10);
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                //return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    
    /*
     * 获得家族排行（家族收益）
     */

    public function getFamilysRank($type) {
        try {
            // 读取缓存
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['family_' . $type]);
            if ($arrData) {
                $arrData = array_slice($arrData->toArray(), 0, 10);
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                //return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    /*
     * 获得礼物排行
     */

    public function getFirstGiftRank($type) {
        try {
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['gift_star_' . $type]);
            if ($arrData) {
                $arrData = $arrData->toArray();
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                // return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获得魅力星排行
     */

    public function getCharmRank($type) {
        try {
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['charm_' . $type]);
            if ($arrData) {
                $arrData = $arrData->toArray();
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                // return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 上周观众最多主播
     * */

    public function getLastWeekVisitorRankAnchor() {
        try {
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['visitor_anchor']);
            if ($arrData) {
                $arrData = array_slice($arrData->toArray(), 0, 3);
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                //return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 上周最强家族
     * */

    public function getLastWeekCashRankFamily() {
        try {
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['consume_family']);
            if ($arrData) {
                $arrData = array_slice($arrData->toArray(), 0, 3);
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                //return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 上周魅力最强主播
     * */

    public function getLastWeekFollowRankAnchor() {
        try {
            $arrData = RankLog::findFirst("index=" . $this->config->rankLogType['fans_anchor']);
            if ($arrData) {
                $arrData = array_slice($arrData->toArray(), 0, 3);
            } else {
                return $this->status->retFromFramework($this->status->getCode('OK'), array());
                //return $this->status->retFromFramework($this->status->getCode('NO_ANY_DATA_OF_RANK_LOG'));
            }
            $result = json_decode($arrData['content'], TRUE);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 检测时间
     *
     * @param $dataType
     * @param $rankType
     */
    public function checkTime($dataType, $rankType) {
        $data = RankLog::findFirst("index=" . $this->config->rankLogType[$rankType . '_' . $dataType]);
        $now = time();
        if ($data) {
            $data = $data->toArray();
            switch ($dataType) {
                case 'day':
                    if ($now - intval($data['lastTime']) < 60 * 60) {
                        return FALSE;
                    }

                    break;
                case 'week':
                    if (date('w') == 1 && intval($data['lastTime']) - strtotime(date('Y-m-d')) < 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }

                    break;
                case 'month':
                    if (date('d') == 1 && intval($data['lastTime']) - strtotime(date('Y-m-d')) < 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }

                    break;
                case 'total':

                    break;
                default:
                    return FALSE;
                    break;
            }
        }

        return TRUE;
    }

    /**
     * 获得房间的消费排行头几名(聊豆消费去除)
     * @param $rankType : 3-总榜 2-本月 1-本场 4-本日 5-本周
     * @param $roomId : 房间Id
     * @param $topNum : 头几名
     * @return 调用retFromFramework返回的json数据
     */
    public function getRoomConsumeRank($rankType, $roomId, $topNum) {
        // 参数判断
        $postData['roomid'] = $roomId;
        $postData['type'] = $rankType;
        $postData['number'] = $topNum;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        
        //读取缓存
        $normalLib = $this->di->get('normalLib');
        $cacheKey = 'room_consume_rank_' . $roomId . "_" . $rankType;
        $cacheResult = $normalLib->getCache($cacheKey);
        if (isset($cacheResult)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $cacheResult);
        }

        // 获取房间信息
        $roomData = Rooms::findFirst("roomId = " . $roomId);
        if (empty($roomData)) {
            return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }

        $anchorId = $roomData->uid;

        try {
            $resultArray = array();

            $types = $this->config->consumeTypeAnchor;
            // $types = $this->config->consumeType->grabSeat . "," . $this->config->consumeType->sendGift . "," . $this->config->consumeType->buyGuard; //抢座、送礼、购买守护
            $phql = "select cl.uid,sum(cl.amount) as total,ui.nickName,up.level3,ui.avatar"
                    . " from \Micro\Models\ConsumeDetailLog cl "
                    . " inner join  \Micro\Models\UserInfo ui on cl.uid=ui.uid "
                    . " inner join \Micro\Models\UserProfiles up on cl.uid=up.uid "
                    . " where cl.receiveUid=" . $anchorId . " and cl.type in ({$types})";

            switch ($rankType) {
                case 3:// 总榜
                    break;
                case 2://本月榜单
                    /* $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
                      $endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y')); */
                    $day = intval(date('d'));
                    if ($day >= 11) {
                        $beginThismonth = strtotime(date('Y-m-11', strtotime('this month')));
                        $endThismonth = time();
                    } else {
                        $beginThismonth = strtotime(date('Y-m-11', strtotime('-1 month', strtotime(date('Y-m-01')))));
                        $endThismonth = time();
                    }
                    $phql.=" and  cl.createTime >= " . $beginThismonth . " AND cl.createTime <= " . $endThismonth;
                    break;

                case 1:     // 本场消费排行
                    $liveStatus = $roomData->liveStatus;
                    $publicTime = $roomData->publicTime;
                    if ($liveStatus == 0) { //未开播，消费排行为空
                        break;
                    }
                    $phql.= "cl.createTime > " . $publicTime;
                    break;

                case 4:     // 本日榜单
                    $beginTime = strtotime(date('Ymd'));
                    $phql .= " and  cl.createTime >= " . $beginTime;

                    break;
                case 5:     // 本周榜单
                    $week = date('w');
                    if ($week == 1) {
                        $beginTime = strtotime(date('Y-m-d', strtotime('this Monday')));
                    } else {
                        $beginTime = strtotime(date('Y-m-d', strtotime('-1 Monday')));
                    }
                    $phql .= " and  cl.createTime >= " . $beginTime
                    ;
                    break;

                default:
                    return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
            }

            $phql.= " group by cl.uid having total > 0 order by total desc limit " . $topNum;
            $query = $this->modelsManager->createQuery($phql);
            $rankDatas = $query->execute();
            if ($rankDatas->valid() && $rankDatas->toArray()) {
                foreach ($rankDatas as $val) {
                    $userdata['uid'] = $val->uid;
                    $userdata['nickName'] = $val->nickName;
                    $userdata['richerLevel'] = $val->level3;
                    $userdata['avatar'] = $val->avatar;

                    $data['userdata'] = $userdata;
                    $data['total'] = $val->total;
                    array_push($resultArray, $data);
                }
            }

            //设置缓存
            $liftTime = 5; //有效期5秒
            $normalLib->setCache($cacheKey, $resultArray, $liftTime);
           
            return $this->status->retFromFramework($this->status->getCode('OK'), $resultArray);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获得房间的消费排行每种类型的第一名
     * @param $roomId : 房间Id
     * @return 返回uid数组
     */
    public function getTopRoomConsumeUsers($roomId) {
        $result = array();

        $roomConsumeRankResult = $this->getRoomConsumeRank(3, $roomId, 1);
        $allTopUid = 0;
        if ($roomConsumeRankResult['code'] == $this->status->getCode('OK')) {
            $rankArray = $roomConsumeRankResult['data'];
            foreach ($rankArray as $rankData) {
                $allTopUid = $rankData['uid'];
                break;
            }
        }
        // 获取更新消费记录之前的本月场的消费排行
        $roomConsumeRankResult = $this->getRoomConsumeRank(2, $roomId, 1);
        $monthTopUid = 0;
        if ($roomConsumeRankResult['code'] == $this->status->getCode('OK')) {
            $rankArray = $roomConsumeRankResult['data'];
            foreach ($rankArray as $rankData) {
                $monthTopUid = $rankData['uid'];
                break;
            }
        }
        // 获取更新消费记录之前的当前场的消费排行
        $roomConsumeRankResult = $this->getRoomConsumeRank(1, $roomId, 1);
        $currentTopUid = 0;
        if ($roomConsumeRankResult['code'] == $this->status->getCode('OK')) {
            $rankArray = $roomConsumeRankResult['data'];
            foreach ($rankArray as $rankData) {
                $currentTopUid = $rankData['uid'];
                break;
            }
        }

        array_push($result, $allTopUid);
        array_push($result, $monthTopUid);
        array_push($result, $currentTopUid);

        return $result;
    }

    /**
     * 检验房间消费排行是否有变化，和上面的getTopRoomConsumeUsers函数返回值有对应的关系
     */
    public function checkRoomConsumeChange($roomId, $rankUids) {
        if (count($rankUids) < 3) //传入参数判断
            return;
        $allTopUid = $rankUids[0];
        $monthTopUid = $rankUids[1];
        $currentTopUid = $rankUids[2];

        $userDataArray = array();
        $roomConsumeRankResult = $this->getRoomConsumeRank(3, $roomId, 1);
        if ($roomConsumeRankResult['code'] == $this->status->getCode('OK')) {
            $rankArray = $roomConsumeRankResult['data'];
            foreach ($rankArray as $rankData) {
                if ($rankData['uid'] != $allTopUid) {
                    $rankData['type'] = 3;
                    array_push($userDataArray, $rankData);
                }
            }
        }
        // 获取更新消费记录之后的月场消费排行，判断是否有改变榜单
        $roomConsumeRankResult = $this->getRoomConsumeRank(2, $roomId, 1);
        if ($roomConsumeRankResult['code'] == $this->status->getCode('OK')) {
            $rankArray = $roomConsumeRankResult['data'];
            foreach ($rankArray as $rankData) {
                if ($rankData['uid'] != $monthTopUid) {
                    $rankData['type'] = 2;
                    array_push($userDataArray, $rankData);
                }
            }
        }
        // 获取更新消费记录之后的当前场消费排行，判断是否有改变榜单
        $roomConsumeRankResult = $this->getRoomConsumeRank(1, $roomId, 1);
        if ($roomConsumeRankResult['code'] == $this->status->getCode('OK')) {
            $rankArray = $roomConsumeRankResult['data'];
            foreach ($rankArray as $rankData) {
                if ($rankData['uid'] != $currentTopUid) {
                    $rankData['type'] = 1;
                    array_push($userDataArray, $rankData);
                }
            }
        }

        // 处理消费记录排行榜，进行广播
        if (count($userDataArray) > 0) {
            $rankbroadData['controltype'] = "rank";
            $rankbroadData['data'] = $userDataArray;
            $result = $this->comm->roomBroadcast($roomId, $rankbroadData);
        }
    }

}
