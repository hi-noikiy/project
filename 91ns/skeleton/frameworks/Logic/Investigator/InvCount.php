<?php

namespace Micro\Frameworks\Logic\Investigator;

class InvCount extends InvBase {

    public function __construct() {
        parent::__construct();
    }

    //查询用户历史在线数数据
    public function getRoomUserCountData($timeBegin, $timeEnd, $type = 1) {
        $today = date('Y-m-d');
        !$timeBegin && $timeBegin = $today;
        !$timeEnd && $timeEnd = $today;
        $timeBegin = strtotime($timeBegin);
        $timeEnd = strtotime($timeEnd) + 86399;
        $return = array();

        if ($timeEnd - $timeBegin < 86400) {//如果是同一天的时间，则按小时统计
            $dataFormat = "%H";
            $timeType = 'hour';
        } else {
            $dataFormat = "%Y%m%d"; //按天统计
            $timeType = "day";
        }
        $platArray = array("pc" => 1, "ios" => 2, "android" => 3); //平台
        try {
            $conn = $this->di->get('db');
            foreach ($platArray as $val) {
                //查询pre_room_user_count_hour表取平均值
                $sql = "select DATE_FORMAT(from_unixtime(createTime), '{$dataFormat}') as time,sum(count) DIV count(1) as sum "
                        . " from pre_room_user_count_hour "
                        . " where type=" . $type . " and platform=" . $val . " and createTime BETWEEN '" . $timeBegin . "' AND '" . $timeEnd . "' group by time ";
                $list = $conn->fetchAll($sql);
                $resArray = array();
                if ($list) {
                    foreach ($list as $v) {
                        $resArray[$v['time']] = $v['sum'];
                    }
                }
                $result = $this->getDataByDates($timeType, $resArray, date('Y-m-d', $timeBegin), date('Y-m-d', $timeEnd));
                $return['list' . $val] = $result;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getRoomUserCountData error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询用户当前在线数
    public function getRoomUserOnlineCount() {
        $return = array();
        try {
            //在线人数--不去重
            $result1 = \Micro\Models\RoomUserCount::findfirst("platform=1"); //web
            $result2 = \Micro\Models\RoomUserCount::findfirst("platform=2"); //ios
            $result3 = \Micro\Models\RoomUserCount::findfirst("platform=3"); //android
            $sum1 = $result1 ? $result1->count : 0;
            $sum2 = $result2 ? $result2->count : 0;
            $sum3 = $result3 ? $result3->count : 0;
            $sum = $sum1 + $sum2 + $sum3;

            //在线人数--去重
            $today = date("Ymd");
            $sql = "select platform,count(DISTINCT uid) as count from pre_user_active_count where date={$today} and endTime=0 group by platform";
            $connection = $this->di->get("db");
            $res = $connection->fetchAll($sql);
            $sum_ = $sum_1 = $sum_2 = $sum_3 = 0;
            if ($res) {
                foreach ($res as $val) {
                    if ($val['platform'] == 1) {//pc
                        $sum_1 = $val['count'];
                        $sum_+=$val['count'];
                    } elseif ($val['platform'] == 2) {//ios
                        $sum_2 = $val['count'];
                        $sum_+=$val['count'];
                    } elseif ($val['platform'] == 3) {//android
                        $sum_3 = $val['count'];
                        $sum_+=$val['count'];
                    }
                }
            }

            $return['sum'] = $sum_ . "/" . $sum;
            $return['sum1'] = $sum_1 . "/" . $sum1;
            $return['sum2'] = $sum_2 . "/" . $sum2;
            $return['sum3'] = $sum_3 . "/" . $sum3;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getRoomUserCountData error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询用户历史活跃数数据
    public function getUserActiveCountData($timeBegin, $timeEnd) {
        $today = date('Ymd');
        !$timeBegin && $timeBegin = $today;
        !$timeEnd && $timeEnd = $today;
        $timeBegin = date('Ymd', strtotime($timeBegin));
        $timeEnd = date('Ymd', strtotime($timeEnd));
        $return = array();
        //上期时间
        $lastTimeEnd = date("Ymd", strtotime($timeBegin) - 86400);
        $interval = floor(strtotime($timeEnd) - strtotime($timeBegin)) / 86400 + 1; //两个日期之间间隔的天数
        $lastTimeBegin = date("Ymd", strtotime($timeBegin) - 86400 * $interval);
        //echo $lastTimeBegin."-".$lastTimeEnd;

        $platArray = array("pc" => 1, "ios" => 2, "android" => 3); //平台
        try {
            $total = 0;
            $total1 = 0;
            $conn = $this->di->get('db');
            foreach ($platArray as $val) {
                $sql = "select sum(count) sum "
                        . " from pre_user_active_count_day "
                        . " where platform=" . $val . " and date BETWEEN  " . $timeBegin . "  AND  " . $timeEnd;

                $sumres = $conn->fetchOne($sql);
                $sum = $sumres['sum'] ? $sumres['sum'] : 0;
                $return['sum' . $val] = $sum;
                $total+=$sum;
                //查询上期数据
                $sql1 = "select sum(count) sum "
                        . " from pre_user_active_count_day "
                        . " where platform=" . $val . " and date BETWEEN  " . $lastTimeBegin . "  AND  " . $lastTimeEnd;

                $sumres1 = $conn->fetchOne($sql1);
                $sum1 = $sumres1['sum'] ? $sumres1['sum'] : 0;
                $return['lastSum' . $val] = $sum1;
                $total1+=$sum1;
            }
            $return['sum'] = $total;
            $return['lastSum'] = $total1;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getRoomUserCountData error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //统计用户新增数
    public function getNewRegUsersCount() {
        try {
            //今日新增用户数
            $today = strtotime(date("Ymd"));
            $sql1 = "select count(*) as count from pre_register_log where createTime>=" . $today;
            $res1 = $this->db->fetchOne($sql1);
            $return['count1'] = $res1['count'] ? $res1['count'] : 0;
            //昨日新增用户数
            $yestoday = strtotime(date("Ymd", strtotime("-1 day")));
            $sql2 = "select count(*) as count from pre_register_log where createTime>=" . $yestoday . " and createTime<" . $today;
            $res2 = $this->db->fetchOne($sql2);
            $return['count2'] = $res2['count'] ? $res2['count'] : 0;
            //最近7天新增用户数
            $sevenday = strtotime(date("Ymd", strtotime("-7 day")));
            $sql3 = "select count(*) as count from pre_register_log where createTime>=" . $sevenday;
            $res3 = $this->db->fetchOne($sql3);
            $return['count3'] = $res3['count'] ? $res3['count'] : 0;
            //最近30天新增用户数
            $thirtyday = strtotime(date("Ymd", strtotime("-30 day")));
            $sql4 = "select count(*) as count from pre_register_log where createTime>=" . $thirtyday;
            $res4 = $this->db->fetchOne($sql4);
            $return['count4'] = $res4['count'] ? $res4['count'] : 0;

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //注册使用终端设备
    public function getDiffPlatRegCount($timeBegin, $timeEnd) {
        try {
            $today = date('Y-m-d');
            !$timeBegin && $timeBegin = $today;
            !$timeEnd && $timeEnd = $today;
            $timeBegin = strtotime($timeBegin);
            $timeEnd = strtotime($timeEnd) + 86399;
            $return = array();


            //统计各个平台的注册数
            $sql = "select platform,count(*) as count from pre_register_log where createTime>=" . $timeBegin . " and createTime<" . $timeEnd . " group by platform";
            $res = $this->db->fetchAll($sql);
            $platList['sum1'] = 0; //pc端
            $platList['sum2'] = 0; //ios
            $platList['sum3'] = 0; //android
            foreach ($res as $val) {
                if ($val['count'] > 0) {
                    $platList["sum{$val['platform']}"] = $val['count'];
                }
            }
            $return['platList'] = $platList;

            //注册趋势
            if ($timeEnd - $timeBegin < 86400) {//如果是同一天的时间，则按小时统计
                $dataFormat = "%H";
                $timeType = 'hour';
            } else {
                $dataFormat = "%Y%m%d"; //按天统计
                $timeType = "day";
            }

            $platArray = array("pc" => 1, "ios" => 2, "android" => 3); //平台
            foreach ($platArray as $val) {
                $sql_ = "select DATE_FORMAT(from_unixtime(createTime), '{$dataFormat}') as time,count(*) as sum"
                        . " from  pre_register_log"
                        . " where platform=" . $val . " and createTime BETWEEN '" . $timeBegin . "' AND '" . $timeEnd . "' group by time ";
                $list = $this->db->fetchAll($sql_);
                $resArray = array();
                if ($list) {
                    foreach ($list as $v) {
                        $resArray[$v['time']] = $v['sum'];
                    }
                }
                $result = $this->getDataByDates($timeType, $resArray, date('Y-m-d', $timeBegin), date('Y-m-d', $timeEnd));
                $return['regList']['list' . $val] = $result;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    /**
     * 统计下载次数
     * @param unknown $timeBegin
     * @param unknown $timeEnd
     */
    public function getAppCount($timeBegin, $timeEnd) {
    	try {
    		$today = date('Y-m-d');
    		!$timeBegin && $timeBegin = $today;
    		!$timeEnd && $timeEnd = $today;
    		$timeBegin = strtotime($timeBegin);
    		$timeEnd = strtotime($timeEnd) + 86399;
    		$return = array();
    
    
    		//统计各个平台的注册数
    		$sql = "select device,count(*) as count from pre_app_count where time BETWEEN '" . $timeBegin . "' AND '" . $timeEnd."' group by device";
    		$res = $this->db->fetchAll($sql);
    		$platList['sumandroid'] = 0; //android
    		$platList['sumios'] = 0; //ios
    		$platList['sumpcios'] = 0; //pcios
    		foreach ($res as $val) {
    			if ($val['count'] > 0) {
    				$platList["sum{$val['device']}"] = $val['count'];
    			}
    		}
    		$return['platList'] = $platList;
    
    		//注册趋势
    		if ($timeEnd - $timeBegin < 86400) {//如果是同一天的时间，则按小时统计
    			$dataFormat = "%H";
    			$timeType = 'hour';
    		} else {
    			$dataFormat = "%Y%m%d"; //按天统计
    			$timeType = "day";
    		}
    
    		$platArray = array("1" => 'ios', "2" =>  "android" ); //设备
    		foreach ($platArray as $val) {
    			$sql_ = "select DATE_FORMAT(from_unixtime(time), '{$dataFormat}') as htime,count(*) as sum"
    			. " from  pre_app_count"
    					. " where device = '".$val."'  and time BETWEEN '" . $timeBegin . "' AND '" . $timeEnd . "' group by htime ";
    			$list = $this->db->fetchAll($sql_);
    			$resArray = array();
    			if ($list) {
    				foreach ($list as $v) {
    					$resArray[$v['htime']] = $v['sum'];
    				}
    			}
    			$result = $this->getDataByDates($timeType, $resArray, date('Y-m-d', $timeBegin), date('Y-m-d', $timeEnd));
    			$return['regList']['list' . $val] = $result;
    		}
    		return $this->status->retFromFramework($this->status->getCode('OK'), $return);
    	} catch (\Exception $e) {
    		return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
    	}
    }

    //各渠道新增用户数量统计
    public function getDiffSourceRegCount($timeBegin, $timeEnd) {
        $today = date('Y-m-d');
        !$timeBegin && $timeBegin = $today;
        !$timeEnd && $timeEnd = $today;
        $timeBegin = strtotime($timeBegin);
        $timeEnd = strtotime($timeEnd) + 86399;
        $return = array();
        try {
            $total = 0; //总注册人数
            //各渠道注册统计
            $sql = "select parentType,count(*) as count from pre_register_log where createTime>=" . $timeBegin . " and createTime<=" . $timeEnd . " group by parentType";
            $res = $this->db->fetchAll($sql);
            $list = array();
            $i = 0;
            $count_source = 0; //渠道注册数
            foreach ($res as $val) {
                if ($val['parentType']) {//渠道
                    $data['name'] = $val['parentType'];
                    $data['count'] = $val['count'];
                    $data['url'] = '';
                    $list[$i++] = $data;
                    unset($data);
                    $count_source+=$val['count']; //渠道注册数
                }
                $total+=$val['count'];
            }

            //用户推荐注册统计
            $recsql = "select r.url ,count(*) as count,ui.nickName"
                    . " from pre_recommend_log rl "
                    . "left join pre_register_log l on rl.beRecUid=l.uid "
                    . "left join pre_recommend r on rl.recUid=r.uid "
                    . "left join pre_user_info ui on r.uid=ui.uid "
                    . "where rl.beRecUid>0 and l.createTime>=" . $timeBegin . " and l.createTime<=" . $timeEnd . " group by rl.recUid order by null";
            $recres = $this->db->fetchAll($recsql);
            $reclist = array();
            $count_rec = 0;
            foreach ($recres as $val) {
                $data['url'] = $val['url'];
                $data['name'] = $val['nickName'];
                $data['count'] = $val['count'];
                $reclist[$i++] = $data;
                unset($data);
                $count_rec+=$val['count']; //推荐注册数
            }

            $newlist = array();
            //91ns注册
            $count_91 = $total - $count_rec - $count_source;
            if ($count_91 > 0) {
                $newlist[0]['name'] = "91NS";
                $newlist[0]['url'] = "";
                $newlist[0]['count'] = $count_91;
            }


            if (!empty($list)) {
                $newlist = array_merge($newlist, $list);
            }
            if (!empty($reclist)) {
                $newlist = array_merge($newlist, $reclist);
            }



            $return = array();
            //按注册数降序排序
            if ($newlist) {
                foreach ($newlist as $key => $val) {
                    //计算百分比
                    $percent = $val['count'] > 0 ? number_format($val['count'] / $total * 100, 2) : 0;
                    $data = $val;
                    $data['percent'] = $percent . "%";
                    $return[] = $data;
                    $sort[] = $val['count'];
                    unset($data);
                }
                array_multisort($sort, SORT_DESC, $return);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询不同时间段的用户留存率
    public function checkUserRetentionList($timeBegin, $timeEnd) {
        $today = date('Ymd');
        !$timeBegin && $timeBegin = $today;
        !$timeEnd && $timeEnd = $today;
        $return = array();
        $startTime = strtotime($timeBegin);
        $endTme = strtotime($timeEnd) + 86399;
        try {
            $regcountSum = 0; //该时间段的总注册数
            $list2 = array(); //该时间段的次日留存率列表
            $list3 = array(); //该时间段的3日留存率列表
            $list7 = array(); //该时间段的7日留存率列表
            $list30 = array(); //该时间段的30日留存率列表
            $countSum2 = 0; //该时间段总的次日留存
            $countSum3 = 0; //该时间段总的3日留存
            $countSum7 = 0; //该时间段总的7日留存
            $countSum30 = 0; //该时间段总的30日留存
            //该时间段 每日 新增注册数
            $regsql = "select count(*) as sum,FROM_UNIXTIME(createTime,'%Y%m%d') time from pre_register_log where createTime>=" . $startTime . " and createTime<=" . $endTme . " group by time";
            $regres = $this->db->fetchAll($regsql);
            $regDayCount = array(); //该时间段每日新增注册数数组
            foreach ($regres as $rg) {
                $regDayCount[$rg['time']] = $rg['sum'];
                $regcountSum+=$rg['sum'];
            }



            //该时间段 每日 次日留存  次日留存率：（当天新增的用户中，在第2天还登录的用户数）/第一天新增总用户数；
            $time2 = 86400;
            $time22 = 172800;
            $startTime_ = $startTime + $time2;
            $endTme_ = $endTme + $time2;

            $retsql2 = "select count(*) as sum,FROM_UNIXTIME(createTime,'%Y%m%d') time "
                    . "from(select rl.uid,rl.createTime from pre_register_log rl "
                    . "INNER JOIN (select uid,createTime from pre_login_log "
                    . "where createTime>={$startTime_} and createTime<={$endTme_})ll "
                    . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time2} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time22} "
                    . "where rl.createTime>={$startTime} and rl.createTime<={$endTme}"
                    . " group by ll.uid order by null)a "
                    . "group by time order by null";

            $retres2 = $this->db->fetchAll($retsql2);
            foreach ($retres2 as $v) {
                $regcount = isset($regDayCount[$v['time']]) ? $regDayCount[$v['time']] : 0; //当天的注册数
                $retpercent = $regcount > 0 ? number_format($v['sum'] * 100 / $regcount, 2) : 0; //次日留存率
                $list2[$v['time']] = $retpercent;

                $countSum2+=$v['sum'];
            }


            //该时间段 每日 3日留存  3日留存率：（当天新增的用户中，在往后第3天还登录的用户数）/第一天新增总用户数；
            $time3 = 259200;
            $time33 = 345600;
            $startTime_ = $startTime + $time3;
            $endTme_ = $endTme + $time3;
            $retsql3 = "select count(*) as sum,FROM_UNIXTIME(createTime,'%Y%m%d') time "
                    . "from(select rl.uid,rl.createTime from pre_register_log rl "
                    . "INNER JOIN (select uid,createTime from pre_login_log "
                    . "where createTime>={$startTime_} and createTime<={$endTme_})ll "
                    . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time3} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time33} "
                    . "where rl.createTime>={$startTime} and rl.createTime<={$endTme}"
                    . " group by ll.uid order by null)a "
                    . "group by time order by null";
            $retres3 = $this->db->fetchAll($retsql3);
            foreach ($retres3 as $v) {
                $regcount = isset($regDayCount[$v['time']]) ? $regDayCount[$v['time']] : 0; //当天的注册数
                $retpercent = $regcount > 0 ? number_format($v['sum'] * 100 / $regcount, 2) : 0; //3日留存率
                $list3[$v['time']] = $retpercent;

                $countSum3+=$v['sum'];
            }


            //该时间段 每日 7日留存  7日留存率：（当天新增的用户中，在往后的第7天还登录的用户数）/第一天新增总用户数；
            $time7 = 604800;
            $time77 = 691200;
            $startTime_ = $startTime + $time7;
            $endTme_ = $endTme + $time7;
            $retsql7 = "select count(*) as sum,FROM_UNIXTIME(createTime,'%Y%m%d') time "
                    . "from(select rl.uid,rl.createTime from pre_register_log rl "
                    . "INNER JOIN (select uid,createTime from pre_login_log "
                    . "where createTime>={$startTime_} and createTime<={$endTme_})ll "
                    . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time7} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time77} "
                    . "where rl.createTime>={$startTime} and rl.createTime<={$endTme}"
                    . " group by ll.uid order by null)a "
                    . "group by time order by null";
            $retres7 = $this->db->fetchAll($retsql7);
            foreach ($retres7 as $v) {
                $regcount = isset($regDayCount[$v['time']]) ? $regDayCount[$v['time']] : 0; //当天的注册数
                $retpercent = $regcount > 0 ? number_format($v['sum'] * 100 / $regcount, 2) : 0; //7日留存率
                $list7[$v['time']] = $retpercent;

                $countSum7+=$v['sum'];
            }

            //该时间段 每日 30日留存  30日留存率：（当天新增的用户中，在往后的第30天还登录的用户数）/第一天新增总用户数；
            $time30 = 2592000;
            $time3030 = 26784000;
            $startTime_ = $startTime + $time30;
            $endTme_ = $endTme + $time30;
            $retsql30 = "select count(*) as sum,FROM_UNIXTIME(createTime,'%Y%m%d') time "
                    . "from(select rl.uid,rl.createTime from pre_register_log rl "
                    . "INNER JOIN (select uid,createTime from pre_login_log "
                    . "where createTime>={$startTime_} and createTime<={$endTme_})ll "
                    . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time30} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time3030} "
                    . "where rl.createTime>={$startTime} and rl.createTime<={$endTme}"
                    . " group by ll.uid order by null)a "
                    . "group by time order by null";
            $retres30 = $this->db->fetchAll($retsql30);
            foreach ($retres30 as $v) {
                $regcount = isset($regDayCount[$v['time']]) ? $regDayCount[$v['time']] : 0; //当天的注册数
                $retpercent = $regcount > 0 ? number_format($v['sum'] * 100 / $regcount, 2) : 0; //30日留存率
                $list30[$v['time']] = $retpercent;

                $countSum30+=$v['sum'];
            }


            $list2 = $this->getDataByDates('day', $list2, date('Y-m-d', $startTime), date('Y-m-d', $endTme)); //转成按日期排列
            $list3 = $this->getDataByDates('day', $list3, date('Y-m-d', $startTime), date('Y-m-d', $endTme)); //转成按日期排列
            $list7 = $this->getDataByDates('day', $list7, date('Y-m-d', $startTime), date('Y-m-d', $endTme)); //转成按日期排列
            $list30 = $this->getDataByDates('day', $list30, date('Y-m-d', $startTime), date('Y-m-d', $endTme)); //转成按日期排列

            $return['list2'] = $list2;
            $return['list3'] = $list3;
            $return['list7'] = $list7;
            $return['list30'] = $list30;
            $return['count2'] = $regcountSum > 0 ? number_format($countSum2 * 100 / $regcountSum, 2) . "%" : "0%";
            $return['count3'] = $regcountSum > 0 ? number_format($countSum3 * 100 / $regcountSum, 2) . "%" : "0%";
            $return['count7'] = $regcountSum > 0 ? number_format($countSum7 * 100 / $regcountSum, 2) . "%" : "0%";
            $return['count30'] = $regcountSum > 0 ? number_format($countSum30 * 100 / $regcountSum, 2) . "%" : "0%";

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //各渠道新增用户留存统计
    public function checkPlatUserRetention($timeBegin, $timeEnd, $order = 1) {
        $today = date('Ymd');
        !$timeBegin && $timeBegin = $today;
        !$timeEnd && $timeEnd = $today;
        $return = array();
        $startTime = strtotime($timeBegin);
        $endTme = strtotime($timeEnd) + 86399;
        try {
            //该时间段 有注册的渠道 
            $sql = "select parentType from pre_register_log where createTime>=" . $startTime . " and createTime<=" . $endTme . " group by parentType";
            $res = $this->db->fetchAll($sql);
            $list = array();
            $i = 0;
            foreach ($res as $val) {
                if (!$val['parentType']) {
                    $data['name'] = '91NS';
                } else {
                    $data['name'] = $val['parentType'];
                }
                $data['url'] = '';
                $data['isRec'] = 0; //平台渠道注册
                $data['channel'] = $val['parentType'];
                $list[$i++] = $data;
                unset($data);
            }

            //该时间段  推荐用户
            $recsql = "select r.url,ui.nickName,r.uid"
                    . " from pre_recommend_log rl "
                    . "left join pre_register_log l on rl.beRecUid=l.uid "
                    . "left join pre_recommend r on rl.recUid=r.uid "
                    . "left join pre_user_info ui on r.uid=ui.uid "
                    . "where rl.beRecUid>0 and l.createTime>=" . $startTime . " and l.createTime<=" . $endTme . " group by rl.recUid order by null";
            $recres = $this->db->fetchAll($recsql);
            $reclist = array();
            foreach ($recres as $val) {
                $data['url'] = $val['url'];
                $data['name'] = $val['nickName'];
                $data['isRec'] = 1; //用户推荐注册
                $data['channel'] = $val['uid'];
                $reclist[$i++] = $data;
                unset($data);
            }

            //渠道注册+推荐注册
            $newlist = array();
            if (!empty($list)) {
                $newlist = array_merge($newlist, $list);
            }
            if (!empty($reclist)) {
                $newlist = array_merge($newlist, $reclist);
            }


            if ($newlist) {
                //渠道数组循环 
                foreach ($newlist as $val) {
                    //该时间段 某渠道 新增注册数
                    if ($val['isRec']) {//用户推荐注册
                        $regsql = "select count(*) as count"
                                . " from pre_recommend_log rl "
                                . "left join pre_register_log l on rl.beRecUid=l.uid "
                                . "left join pre_recommend r on rl.recUid=r.uid "
                                . "where rl.recUid=" . $val['channel'] . " and rl.beRecUid>0 and l.createTime>=" . $startTime . " and l.createTime<=" . $endTme;
                    } else {//平台渠道注册
                        $regsql = "select count(*) as count from pre_register_log where parentType='{$val['channel']}' and createTime>=" . $startTime . " and createTime<=" . $endTme;
                    }
                    $regCountRes = $this->db->fetchOne($regsql);

                    $regcount = $regCountRes['count'] ? $regCountRes['count'] : 0; //新增注册数
                    //该时间段 某渠道 次日留存  次日留存率：（当天新增的用户中，在第2天还登录的用户数）/第一天新增总用户数；
                    $time2 = 86400;
                    $time22 = 172800;
                    $startTime_ = $startTime + $time2;
                    $endTme_ = $endTme + $time2;
                    if ($val['isRec']) {//推荐用户注册
                        $retsql2 = "select count(*) as count "
                                . "from(select rl.uid,rl.createTime "
                                . "from pre_register_log rl "
                                . "inner join pre_recommend_log rr on rl.uid=rr.beRecUid "
                                . "INNER JOIN (select uid,createTime "
                                . "from pre_login_log where createTime>={$startTime_} and createTime<={$endTme_})ll "
                                . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time2} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time22} "
                                . " where rr.recUid={$val['channel']} and rl.createTime>={$startTime} and rl.createTime<={$endTme} group by rl.uid order by null)a ";
                    } else {//渠道注册
                        $retsql2 = "select count(*) as count "
                                . " from(select rl.uid,rl.createTime from pre_register_log rl "
                                . "INNER JOIN (select uid,createTime from pre_login_log "
                                . "where createTime>={$startTime_} and createTime<={$endTme_})ll "
                                . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time2} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time22} "
                                . " where rl.parentType='{$val['channel']}' and rl.createTime>={$startTime} and rl.createTime<={$endTme}"
                                . " group by ll.uid order by null)a ";
                    }

                    $retres2 = $this->db->fetchOne($retsql2);
                    $retcount2 = $retres2['count'] ? $retres2['count'] : 0; //次日留存
                    $retpercent2 = $regcount > 0 ? number_format($retcount2 * 100 / $regcount, 2) : 0; //次日留存率
                    $data['retcount2'] = $retpercent2;
                    $data['count2'] = $retpercent2 . "%";


                    //该时间段 每日 3日留存  3日留存率：（当天新增的用户中，在往后的第3天还登录的用户数）/第一天新增总用户数；
                    $time3 = 259200;
                    $time33 = 345600;
                    $startTime_ = $startTime + $time3;
                    $endTme_ = $endTme + $time3;
                    if ($val['isRec']) {//推荐用户注册
                        $retsql3 = "select count(*) as count "
                                . "from(select rl.uid,rl.createTime "
                                . "from pre_register_log rl "
                                . "inner join pre_recommend_log rr on rl.uid=rr.beRecUid "
                                . "INNER JOIN (select uid,createTime "
                                . "from pre_login_log where createTime>={$startTime_} and createTime<={$endTme_})ll "
                                . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time3} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time33} "
                                . " where rr.recUid={$val['channel']} and rl.createTime>={$startTime} and rl.createTime<={$endTme} group by rl.uid order by null)a ";
                    } else {//渠道注册
                        $retsql3 = "select count(*) as count "
                                . " from(select rl.uid,rl.createTime from pre_register_log rl "
                                . "INNER JOIN (select uid,createTime from pre_login_log "
                                . "where createTime>={$startTime_} and createTime<={$endTme_})ll "
                                . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time3} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time33} "
                                . " where rl.parentType='{$val['channel']}' and rl.createTime>={$startTime} and rl.createTime<={$endTme}"
                                . " group by ll.uid order by null)a ";
                    }

                    $retres3 = $this->db->fetchOne($retsql3);
                    $retcount3 = $retres3['count'] ? $retres3['count'] : 0; //3日留存
                    $retpercent3 = $regcount > 0 ? number_format($retcount3 * 100 / $regcount, 2) : 0; //3日留存率
                    $data['retcount3'] = $retpercent3;
                    $data['count3'] = $retpercent3 . "%";


                    //该时间段 7日留存 7日留存率：（当天新增的用户中，在往后的第7天还登录的用户数）/第一天新增总用户数；
                    $time7 = 604800;
                    $time77 = 691200;
                    $startTime_ = $startTime + $time7;
                    $endTme_ = $endTme + $time7;
                    if ($val['isRec']) {//推荐用户注册
                        $retsql7 = "select count(*) as count "
                                . "from(select rl.uid,rl.createTime "
                                . "from pre_register_log rl "
                                . "inner join pre_recommend_log rr on rl.uid=rr.beRecUid "
                                . "INNER JOIN (select uid,createTime "
                                . "from pre_login_log where createTime>={$startTime_} and createTime<={$endTme_})ll "
                                . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time7} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time77} "
                                . " where rr.recUid={$val['channel']} and rl.createTime>={$startTime} and rl.createTime<={$endTme} group by rl.uid order by null)a ";
                    } else {//渠道注册
                        $retsql7 = "select count(*) as count "
                                . " from(select rl.uid,rl.createTime from pre_register_log rl "
                                . "INNER JOIN (select uid,createTime from pre_login_log "
                                . "where createTime>={$startTime_} and createTime<={$endTme_})ll "
                                . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time7} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time77} "
                                . " where rl.parentType='{$val['channel']}' and rl.createTime>={$startTime} and rl.createTime<={$endTme}"
                                . " group by ll.uid order by null)a ";
                    }

                    $retres7 = $this->db->fetchOne($retsql7);
                    $retcount7 = $retres7['count'] ? $retres7['count'] : 0; //7日留存
                    $retpercent7 = $regcount > 0 ? number_format($retcount7 * 100 / $regcount, 2) : 0; //7日留存率
                    $data['retcount7'] = $retpercent7;
                    $data['count7'] = $retpercent7 . "%";

                    //该时间段 30日留存  30日留存率：（当天新增的用户中，在往后的第30天还登录的用户数）/第一天新增总用户数；
                    $time30 = 2592000;
                    $time3030 = 26784000;
                    $startTime_ = $startTime + $time30;
                    $endTme_ = $endTme + $time30;
                    if ($val['isRec']) {//推荐用户注册
                        $retsql30 = "select count(*) as count "
                                . "from(select rl.uid,rl.createTime "
                                . "from pre_register_log rl "
                                . "inner join pre_recommend_log rr on rl.uid=rr.beRecUid "
                                . "INNER JOIN (select uid,createTime "
                                . "from pre_login_log where createTime>={$startTime_} and createTime<={$endTme_})ll "
                                . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time30} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time3030} "
                                . " where rr.recUid={$val['channel']} and rl.createTime>={$startTime} and rl.createTime<={$endTme} group by rl.uid order by null)a ";
                    } else {//渠道注册
                        $retsql30 = "select count(*) as count "
                                . " from(select rl.uid,rl.createTime from pre_register_log rl "
                                . "INNER JOIN (select uid,createTime from pre_login_log "
                                . "where createTime>={$startTime_} and createTime<={$endTme_})ll "
                                . "on ll.uid=rl.uid and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))>={$time30} and ll.createTime-UNIX_TIMESTAMP(FROM_UNIXTIME(rl.createTime, '%Y%m%d'))<{$time3030} "
                                . " where rl.parentType='{$val['channel']}' and rl.createTime>={$startTime} and rl.createTime<={$endTme}"
                                . " group by ll.uid order by null)a ";
                    }

                    $retres30 = $this->db->fetchOne($retsql30);
                    $retcount30 = $retres30['count'] ? $retres30['count'] : 0; //30日留存
                    $retpercent30 = $regcount > 0 ? number_format($retcount30 * 100 / $regcount, 2) : 0; //30日留存率
                    $data['retcount30'] = $retpercent30;
                    $data['count30'] = $retpercent30 . "%";



                    $data['name'] = $val['name'];
                    $data['url'] = $val['url'];
                    $return[] = $data;
                    unset($data);
                }
            }


            if ($return) {
                //排序
                $sort = array();
                foreach ($return as $key => $val) {
                    if ($order == 3) {//按3日留存率降序
                        $sort[] = $val['retcount3'];
                    } elseif ($order == 7) {//按7日留存率降序
                        $sort[] = $val['retcount7'];
                    } elseif ($order == 30) {//按30日留存率降序
                        $sort[] = $val['retcount30'];
                    } else {
                        $sort[] = $val['retcount2']; //按次日留存降序
                    }
                }
                array_multisort($sort, SORT_DESC, $return);
            }


            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //用户签到统计
    public function getUserSignCount($month) {
        !$month && $month = date("Ym");
        $timeBegin = date('Y-m-01', strtotime($month . "01"));
        $timeEnd = date('Y-m-d', strtotime($timeBegin . ' +1 month -1 day'));
        $startTime = strtotime($timeBegin);
        $endTime = strtotime($timeEnd) + 86399;


        $dataFormat = "%Y%m%d"; //按天统计
        $timeType = "day";
        try {
            $sql = "select DATE_FORMAT(from_unixtime(createTime), '{$dataFormat}') as time, count(*) as sum "
                    . " from pre_sign_log "
                    . " where  createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "' group by time ";
            $list = $this->db->fetchAll($sql);
            $resArray = array();
            $totalSum = 0;
            if ($list) {
                foreach ($list as $v) {
                    $resArray[$v['time']] = $v['sum'];
                    $totalSum+=$v['sum'];
                }
            }
            $result = $this->getDataByDates($timeType, $resArray, date('Y-m-d', $startTime), date('Y-m-d', $endTime));
            $return['list'] = $result;
            $return['sum'] = $totalSum; //总数
            //连续签到统计
//            $consql = "select c.daysNum ,count "
//                    . "from pre_sign_configs c "
//                    . "LEFT JOIN ( select count(DISTINCT uid) as count,conTimes "
//                    . "from(SELECT uid,conTimes from pre_sign_log where createTime>={$startTime} and createTime<={$endTime} group by uid,conTimes order by null)a "
//                    . "group by a.conTimes order by a.conTimes desc) a on c.daysNum=a.conTimes "
//                    . "where c.type=2";
            $consql = "select c.daysNum,count(s.uid)count from pre_sign_configs c left join pre_sign s on c.id=s.type and s.month={$month} where c.type=2 group by c.id order by daysNum asc";
            $conres = $this->db->fetchAll($consql);
            $conlist = array();
            foreach ($conres as $con) {
                $data['day'] = $con['daysNum'];
                $data['count'] = $con['count'] ? $con['count'] : 0;
                $conlist[] = $data;
                unset($data);
            }

            //累计签到统计
//            $totsql = "select c.daysNum ,count "
//                    . "from pre_sign_configs c "
//                    . "LEFT JOIN (select day,count(*) as count "
//                    . "from (select count(*) as day,uid from pre_sign_log where createTime>=" . $startTime . " and createTime<=" . $endTime . " group by uid order by null)a "
//                    . "group by day order by day desc)a on c.daysNum=a.day "
//                    . "where c.type=1";
            $totsql = "select c.daysNum,count(s.uid)count from pre_sign_configs c left join pre_sign s on c.id=s.type and s.month={$month} where c.type=1 group by c.id order by daysNum asc";
            $totres = $this->db->fetchAll($totsql);
            $totlist = array();
            foreach ($totres as $tot) {
                $data['day'] = $tot['daysNum'];
                $data['count'] = $tot['count'] ? $tot['count'] : 0;
                $totlist[] = $data;
                unset($data);
            }

            $return['totdata'] = $totlist;
            $return['condata'] = $conlist;


            //连续签到统计
            $consql2 = "select count(DISTINCT uid) as count,conTimes as day "
                    . "from(SELECT uid,conTimes from pre_sign_log "
                    . " where createTime>={$startTime} and createTime<={$endTime}"
                    . " group by uid,conTimes order by null)a "
                    . "group by a.conTimes order by a.conTimes desc";
            $conres2 = $this->db->fetchAll($consql2);
            $conlist2 = array();
            foreach ($conres2 as $con) {
                $data['day'] = $con['day'];
                $data['count'] = $con['count'] ? $con['count'] : 0;
                $conlist2[] = $data;
                unset($data);
            }
            $return['conlist'] = $conlist2;

            //累计签到统计
            $totsql2 = "select day,count(*) as count "
                    . "from (select count(*) as day,uid from pre_sign_log where createTime>=" . $startTime . " and createTime<=" . $endTime . " group by uid order by null)a "
                    . "group by day order by day desc";
            $totres2 = $this->db->fetchAll($totsql2);
            $totlist2 = array();
            foreach ($totres2 as $tot) {
                $data['day'] = $tot['day'];
                $data['count'] = $tot['count'] ? $tot['count'] : 0;
                $totlist2[] = $data;
                unset($data);
            }

            $return['totlist'] = $totlist2;

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getRoomUserCountData error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //用户任务统计
    public function getUserTaskCount($timeBegin, $timeEnd) {
        $today = date('Ymd');
        !$timeBegin && $timeBegin = $today;
        !$timeEnd && $timeEnd = $today;
        $return = array();
        $startTime = strtotime($timeBegin);
        $endTme = strtotime($timeEnd) + 86399;
        $dataFormat = "%Y%m%d"; //按天统计
        $timeType = "day";
        $exp = " tl.taskId in (2002,2004,2005,2007,2008,2009) and tl.status=2 and finishTime>={$startTime}  and finishTime<{$endTme} ";

        try {
            $userCount = 0; //完成任务的用户数
            $taskCount = 0; //任务完成次数
            //每日完成任务的用户数
            $sql = "select DATE_FORMAT(from_unixtime(finishTime), '{$dataFormat}') as time,count(DISTINCT uid) as sum"
                    . " from  pre_task_log tl"
                    . " where  " . $exp
                    . " group by time order by null";
            $list = $this->db->fetchAll($sql);
            $resArray = array();
            if ($list) {
                foreach ($list as $v) {
                    $resArray[$v['time']] = $v['sum'];
                }
            }
            $result = $this->getDataByDates($timeType, $resArray, date('Y-m-d', $startTime), date('Y-m-d', $endTme));
            $return['userList'] = $result;

            //完成任务的用户数
            $usersql = "select count(DISTINCT uid)count from pre_task_log tl where " . $exp;
            $userres = $this->db->fetchOne($usersql);

            $return['userCount'] = $userres['count'] ? $userres['count'] : 0;



            //查询每日任务完成数
            $sql = "select DATE_FORMAT(from_unixtime(finishTime), '{$dataFormat}') as time,count(*) as sum"
                    . " from  pre_task_log tl "
                    . "where {$exp} group by time order by null";
            $list = $this->db->fetchAll($sql);
            $resArray = array();
            if ($list) {
                foreach ($list as $v) {
                    $resArray[$v['time']] = $v['sum'];
                }
            }
            $result = $this->getDataByDates($timeType, $resArray, date('Y-m-d', $startTime), date('Y-m-d', $endTme));
            $return['taskList'] = $result;


            //完成任务数
            $tasksql = "select count(*)count from pre_task_log tl "
                    . "where {$exp}";
            $taskres = $this->db->fetchOne($tasksql);

            $return['taskCount'] = $taskres['count'] ? $taskres['count'] : 0;




            //查询各个任务完成情况
            $detailTaskList = array();
            $total = 0; //任务完成总用户数
            //累计观看任务
            foreach ($this->config->taskConfig->watchs as $t) {
                $sql = "select count(DISTINCT uid)count "
                        . "from pre_task_log "
                        . "where taskId=2007 and finishTime>=" . $startTime . " and finishTime<=" . $endTme . " and finishRate>=" . $t['times'];
                $res = $this->db->fetchOne($sql);
                $chlist = array();
                $chlist['count'] = $res['count'] ? $res['count'] : 0;
                $chlist['name'] = "完成累计看直播" . $t['times'] . "分钟";
                $detailTaskList[] = $chlist;

                $total+=$chlist['count'];
            }

            //累计发言任务
            foreach ($this->config->taskConfig->talks as $t) {
                $sql = "select count(DISTINCT uid)count "
                        . "from pre_task_log "
                        . "where taskId=2008 and finishTime>=" . $startTime . " and finishTime<=" . $endTme . " and finishRate>=" . $t['times'];
                $res = $this->db->fetchOne($sql);
                $chlist = array();
                $chlist['count'] = $res['count'] ? $res['count'] : 0;
                $chlist['name'] = "完成累计发言" . $t['times'] . "次";
                $detailTaskList[] = $chlist;

                $total+=$chlist['count'];
            }


            //累计送聊币礼物任务
            foreach ($this->config->taskConfig->gifts as $t) {
                $sql = "select count(DISTINCT uid)count "
                        . "from pre_task_log "
                        . "where taskId=2009 and finishTime>=" . $startTime . " and finishTime<=" . $endTme . " and finishRate>=" . $t['times'];
                $res = $this->db->fetchOne($sql);
                $chlist = array();
                $chlist['count'] = $res['count'] ? $res['count'] : 0;
                $chlist['name'] = "完成累计送聊币礼物" . $t['times'] . "个";
                $detailTaskList[] = $chlist;

                $total+=$chlist['count'];
            }





            //抢座、送魅力星、分享 任务完成的人数
            $sql = "select count(DISTINCT tl.uid) as count,t.taskName,t.taskId from pre_task t "
                    . "left join pre_task_log tl on t.taskId=tl.taskId and tl.status=2 and tl.finishTime>=" . $startTime . " and tl.finishTime<=" . $endTme
                    . " where tl.status=2 and (t.taskId=2002 or t.taskId=2004 or t.taskId=2005) group by t.taskId order by taskSort asc";
            $res = $this->db->fetchAll($sql);



            foreach ($res as $v) {
                $total+=$v['count'];
                $data['count'] = $v['count'];
                $data['name'] = "完成" . $v['taskName'];
                $detailTaskList[] = $data;
            }

            //计算百分比
            foreach ($detailTaskList as $key => $val) {
                $precent = $total > 0 ? number_format($val['count'] / $total * 100, 2) : 0;
                $detailTaskList[$key]['percent'] = $precent . "%";
            }


            $return['lists'] = $detailTaskList;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //充值金额统计
    public function getUserRechargeCount() {
        try {
            $return = array();
            //今日充值
            $today = strtotime(date("Ymd"));
            $todaySum = \Micro\Models\Order::sum(array("column" => "totalFee", "conditions" => "status=1 and payTime>=" . $today));
            //昨天充值
            $yestoday = $today - 86400;
            $yestodaySum = \Micro\Models\Order::sum(array("column" => "totalFee", "conditions" => "status=1 and payTime>=" . $yestoday . " and payTime<" . $today));
            //最近7天
            $day7 = $today - 518400;
            $day7Sum = \Micro\Models\Order::sum(array("column" => "totalFee", "conditions" => "status=1 and payTime>=" . $day7));
            //最近30天
            $day30 = $today - 2505600;
            $day30Sum = \Micro\Models\Order::sum(array("column" => "totalFee", "conditions" => "status=1 and payTime>=" . $day30));

            $return['todaySum'] = $todaySum ? $todaySum : 0;
            $return['yestodaySum'] = $yestodaySum ? $yestodaySum : 0;
            $return['day7Sum'] = $day7Sum ? $day7Sum : 0;
            $return['day30Sum'] = $day30Sum ? $day30Sum : 0;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //渠道充值统计 
    public function getUserRechargeList($timeBegin, $timeEnd) {
        $today = date('Y-m-d');
        !$timeBegin && $timeBegin = $today;
        !$timeEnd && $timeEnd = $today;
        $timeBegin = strtotime($timeBegin);
        $timeEnd = strtotime($timeEnd) + 86399;
        $return = array();
        try {
            $total = 0; //总充值金额
            //各渠道充值统计
            $sql = "select l.parentType,sum(o.totalFee) count"
                    . " from pre_order o left join pre_register_log l on o.uid=l.uid "
                    . " where o.status=1 and o.payTime>=" . $timeBegin . " and o.payTime<=" . $timeEnd . " group by l.parentType";

            $res = $this->db->fetchAll($sql);
            $list = array();
            $i = 0;
            $count_source = 0; //渠道充值数
            foreach ($res as $val) {
                if ($val['parentType']) {//渠道
                    $data['name'] = $val['parentType'];
                    $data['count'] = $val['count'];
                    $data['url'] = '';
                    $list[$i++] = $data;
                    unset($data);
                    $count_source+=$val['count']; //渠道充值数
                }
                $total+=$val['count'];
            }


            //用户推荐充值统计
            $recsql = "select r.url ,sum(o.totalFee) as count,ui.nickName "
                    . " from  pre_order o "
                    . " left join pre_recommend_log rl on rl.beRecUid=o.uid "
                    . " left join pre_recommend r on rl.recUid=r.uid "
                    . " left join pre_user_info ui on r.uid=ui.uid "
                    . " where rl.beRecUid>0 and o.status=1 and o.payTime>" . $timeBegin . " and o.payTime<" . $timeEnd . " group by rl.recUid order by null";
            $recres = $this->db->fetchAll($recsql);
            $reclist = array();
            $count_rec = 0;
            foreach ($recres as $val) {
                $data['url'] = $val['url'];
                $data['name'] = $val['nickName'];
                $data['count'] = $val['count'];
                $reclist[$i++] = $data;
                unset($data);
                $count_rec+=$val['count']; //推荐充值数
            }

            $newlist = array();
            //91ns充值
            $count_91 = $total - $count_rec - $count_source;
            if ($count_91 > 0) {
                $newlist[0]['name'] = "91NS";
                $newlist[0]['url'] = "";
                $newlist[0]['count'] = $count_91;
            }


            if (!empty($list)) {
                $newlist = array_merge($newlist, $list);
            }
            if (!empty($reclist)) {
                $newlist = array_merge($newlist, $reclist);
            }


            //按充值数降序排序
            if ($newlist) {
                foreach ($newlist as $key => $val) {
                    //计算百分比
                    $percent = $val['count'] > 0 ? number_format($val['count'] / $total * 100, 2) : 0;
                    $data = $val;
                    $data['percent'] = $percent . "%";
                    $return[] = $data;
                    $sort[] = $val['count'];
                    unset($data);
                }
                array_multisort($sort, SORT_DESC, $return);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //充值排行榜
    public function getUserRechargeRank($type = 'day') {
        $return = array();
        try {
            if ($type == 'week') {//本周
                $date = date('Y-m-d');  //当前日期
                $first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
                $w = date('w', strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6 
                $startTime = strtotime(date('Y-m-d', strtotime("$date -" . ($w ? $w - $first : 6) . ' days'))); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
            } elseif ($type == 'month') {//本月
                $startTime = strtotime(date("Y-m") . "-1");
            } else {//今日
                $startTime = strtotime(date("Ymd"));
            }
            $sql = "select sum(o.totalFee)sum,ui.nickName,ui.avatar,rc.name as levelName"
                    . " from pre_order o inner join pre_user_info ui on o.uid=ui.uid "
                    . " inner join pre_user_profiles up on o.uid=up.uid "
                    . " left join pre_richer_configs rc on up.level3=rc.level "
                    . " where o.status=1 and payTime>=" . $startTime . " group by o.uid order by sum desc limit 10";
            $res = $this->db->fetchAll($sql);
            $list = array();
            foreach ($res as $val) {
                $data = array();
                $data['avatar'] = $val['avatar'];
                $data['levelName'] = $val['levelName'];
                $data['sum'] = $val['sum'];
                $data['nickName'] = $val['nickName'];
                $list[] = $data;
            }
            $return['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //充值平均值
    public function getUserRechargeAvg($type = 'today') {
        $return = array();
        try {
            //今日
            $today = strtotime(date("Ymd"));
            //昨天
            $yestoday = $today - 86400;
            if ($type == 'yestoday') {//昨天
                $payexp = " and payTime>=" . $yestoday . " and payTime<" . $today;
                $loginexp = " createTime>=" . $yestoday . " and createTime<" . $today;
            } else {//今天
                $payexp = " and payTime>=" . $today;
                $loginexp = " createTime>=" . $today;
            }
            //当日总充值
            $paysql = "select sum(totalFee)sum from pre_order where status=1 " . $payexp;
            $paySum = $this->db->fetchOne($paysql);
            $return['sum'] = $paySum['sum'] ? $paySum['sum'] : 0;

            //当日登录用户数
            $loginsql = "select count(*)count from pre_login_log where" . $loginexp;
            $loginCount = $this->db->fetchOne($loginsql);
            $return['count'] = $loginCount['count'] ? $loginCount['count'] : 0;

            //当日人均充值
            $return['avg'] = $return['count'] ? number_format($return['sum'] / $return['count'], 2) : 0;


            //当日充值排行
            $sql = "select sum(o.totalFee)sum,o.uid,ui.nickName"
                    . " from pre_order o inner join pre_user_info ui on o.uid=ui.uid "
                    . " where o.status=1 " . $payexp . " group by o.uid order by sum desc";
            $res = $this->db->fetchAll($sql);
            $list = array();
            foreach ($res as $val) {
                $data = array();
                $data['nickName'] = $val['nickName'];
                $data['sum'] = $val['sum'];
                $data['uid'] = $val['uid'];
                $list[] = $data;
            }
            $return['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //充值平均值列表
    public function getUserRechargeAvgList($key = '') {
        $return = array();
        $len = strlen($key);
        $timeArr = array();
        $checkUser = 0; //是否查询某一天的用户充值
        $unit = '';
        $format = 'Ymd';

        if ($len == 4) {//年
            //获得该年下的所有月份
            $date1 = $key . "-01-01";
            if ($key == date("Y")) {
                //如果是当前年
                // $date2 = date("Y-m-d", strtotime("-1 day", strtotime(date("Ym")))); //当前月的最后一天
                $date2 = date("Y-m-d"); //今天
            } else {
                $date2 = $key . "-12-31";
            }

            $timeArr = $this->getMonths($date1, $date2);
            $unit = '月';
            $format = 'm';
        } elseif ($len == 6) {//年月
            //获得该月份下的所有天
            $date1 = $key . "01";
            if ($key == date("Ym")) {
                //如果是当前月
                $date2 = date("Ymd"); //今天
            } else {
                $date2 = date("Ymd", strtotime("+1 month", strtotime($date1)) - 86400);
            }
            $dateArr = $this->getDates($date1, $date2);
 
            foreach ($dateArr as $key => $d) {
                $timeArr[$d][] = $d;
                $timeArr[$d][] = $d;
            }
            $unit = '日';
            $format = 'd';
        } elseif ($len == 8) {//年月日
            $checkUser = 1;
        } else {
            //年数组
            $timeArr = array('2015' => array(0 => '20150101', 1 => '20151231'), '2016' => array(0 => '20160101', 1 => '20161231'));
            $unit = '年';
            $format = 'Y';
        }


        try {
            $list = array();
            if ($checkUser) {//查询某一天的用户充值
                $time1 = strtotime($key);
                $time2 = $time1 + 86400;
                $usql = "select sum(totalFee)sum,ui.nickName,o.uid"
                        . " from pre_order o inner join pre_user_info ui on o.uid=ui.uid "
                        . " where o.status=1 and o.payTime>=" . $time1 . " and o.payTime<" . $time2 . " group by o.uid order by sum desc";
                $ures = $this->db->fetchAll($usql);
                foreach ($ures as $val) {
                    $data['nickName'] = $val['nickName']; //用户昵称
                    $data['uid'] = $val['uid']; //用户id
                    $data['sum'] = $val['sum']; //用户充值数
                    $list[] = $data;
                    unset($data);
                }
            } else {//按查询充值
                foreach ($timeArr as $key => $val) {
                    $time1 = strtotime($val[0]);
                    $time2 = strtotime("+1 day", strtotime($val[1]));
                    //充值数
                    $sumsql = "select sum(o.totalFee)sum from pre_order o where o.status=1 and o.payTime>=" . $time1 . " and o.payTime<" . $time2;
                    $sumres = $this->db->fetchOne($sumsql);
                    $sum = $sumres['sum'] ? $sumres['sum'] : 0;
                    //登录数
                    $loginsql = "select count(*)count from pre_login_log l where l.createTime>=" . $time1 . " and l.createTime<" . $time2;
                    $loginres = $this->db->fetchOne($loginsql);
                    $count = $loginres['count'] ? $loginres['count'] : 0;
                    //人均充值
                    $avg = $count ? number_format($sum / $count, 2) : 0;

                    $data['sum'] = $sum;
                    $data['key'] = $key;
                    $data['time'] = date($format, strtotime($val[0])) . $unit;
                    $data['count'] = $count;
                    $data['avg'] = $avg;
                    $list[] = $data;
                    unset($data);
                }
            }

            $return['isEnd'] = $checkUser ? $checkUser : 0; //是否最后一级菜单
            $return['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //充值查询
    public function getUserRechargeData($startDate = '', $endDate = '', $namelike = '', $currentPage = 1, $pageSize = 10) {
        $return = array();
        try {
            $exp = ' o.status=1 ';
            if ($startDate) {
                $exp.=" and o.payTime>=" . strtotime($startDate);
            }
            if ($endDate) {
                $endtime = strtotime($endDate) + 86400;
                $exp.=" and o.payTime<" . $endtime;
            }

            if ($namelike) {
                $exp.=" and ((o.uid like '%" . $namelike . "%' OR ui.nickName like'%" . $namelike . "%' ))";
            }


            $limit = $pageSize * ( $currentPage - 1);
            $sql = "select o.uid,ui.nickName,o.payTime,o.orderId,o.cashNum,o.totalFee,o.payType"
                    . " from pre_order o inner join pre_user_info ui on o.uid=ui.uid "
                    . " where " . $exp;
            $sql.=" order by o.payTime desc limit " . $limit . ", " . $pageSize;

            $res = $this->db->fetchAll($sql);
            $payTypes=$this->config->payType->toArray();
            foreach ($payTypes as $key => $payType){
            	$payTypess[$payType['id']] = $payType['name'];
            }
            $list = array();
            foreach ($res as $val) {
                $data['orderId'] = $val['orderId'];
                $data['payTime'] = $val['payTime'];
                $data['uid'] = $val['uid'];
                $data['nickName'] = $val['nickName'];
                $data['cash'] = $val['cashNum'];
                $data['money'] = $val['totalFee'];
                $data['payType'] = $payTypess[$val['payType']];
                $list[] = $data;
                unset($data);
            }

            //总充值
            $sumsql = "select sum(totalFee)sum  from pre_order o inner join pre_user_info ui on o.uid=ui.uid "
                    . " where " . $exp;
            $sumres = $this->db->fetchOne($sumsql);

            //总条数
            $countsql = "select count(*)count from pre_order o inner join pre_user_info ui on o.uid=ui.uid "
                    . " where " . $exp;
            $countres = $this->db->fetchOne($countsql);

            $return['sum'] = $sumres['sum'] ? $sumres['sum'] : 0;
            $return['count'] = $countres['count'] ? $countres['count'] : 0;
            $return['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
