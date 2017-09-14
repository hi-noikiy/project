<?php

namespace Micro\Frameworks\Logic\Investigator;

class InvStatistics extends InvBase{
	
	//客服后台 === 数据统计模块 
	public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }
	public function getGiftList($type,$starTime,$stopTime,$page,$pageSize,$sort){
		$list = array();
		try{
			$starTime = $starTime != '' ? strtotime($starTime) : '';
			$stopTime = $stopTime != '' ? strtotime($stopTime)+86399 : '';
				
			// $start = $starTime != '' ? $starTime : strtotime(date('Y-m-d'));   //计算今天开始时间
			// $end = $start + 86400;  //60*60*24
			
			$yesterDayStart = strtotime(date('Y-m-d'))- 60*60*24;  			//昨天 00:00:00
			$yesterDayEnd = $yesterDayStart + 86399;			          	//昨天  23:59:59
			
			$sevenDayStart = strtotime(date('Y-m-d'))- 60*60*24*7;  		//最近七天 
			
			$thirtyDaysStart = strtotime(date('Y-m-d'))- 60*60*24*30; 		 //最近30天 
				$timeStart = '';
				$timeEnd = '';	
			switch($type){
				case 'toDay':  //今天
					if($starTime != '' && $stopTime != ''){
						$start = $starTime;
						$end = $stopTime+86399;
					}else if($starTime == '' && $stopTime == ''){
						$start = strtotime(date('Y-m-d'));
						$end = $start+86399;
					}else if($start != '' && $stopTime==''){
						$start = $strtotime(date('Y-m-d'));
						$end = $start+86399;
					}
					$timeStart = $start;
					$timeEnd = $end;
					break;//昨天
				case 'yesterDay':
					$timeStart = $starTime != '' ? $starTime : $yesterDayStart;
					$timeEnd = $stopTime != '' ? $stopTime : $yesterDayEnd;
				
					break;
				case 'sevenDay': //最近7天
					$timeStart = $starTime != '' ? $starTime : $sevenDayStart;
					$timeEnd = $stopTime != '' ? $stopTime : time();
					break;
				case 'thirtyDays': //最近三十天
					$timeStart = $starTime != '' ? $starTime : $thirtyDaysStart;
					$timeEnd = $stopTime != '' ? $stopTime : time();
					break;
				default:
					//
					break;  
			}
			$order = '';
			switch ($sort) {
				case 1:  //消费次数升序
					$order = 'consume ASC';
					break;
				case 2: //送出总数降序
					$order = 'counts DESC';
					break;
				case 3: //送出总数升序
					$order = 'counts ASC';
					break;
				case 4:  //送出总金额降序
					$order = 'cl.amount DESC';
					break;
				case 5:  //送出总金额升序
					$order = 'cl.amount ASC';
					break;
				
				default: //消费次数降序
					$order = ' consume desc';
					break;
			}

			$limit = ($page-1)*$pageSize;
            // $table ="\Micro\Models\GiftConfigs gf INNER JOIN \Micro\Models\GiftLog gl ON gf.id = gl.giftId LEFT JOIN \Micro\Models\ConsumeLog cl ON gl.consumeLogId = cl.id where cl.createTime BETWEEN '".$timeStart."' AND '".$timeEnd."' AND cl.type < ".
			$table ="\Micro\Models\GiftConfigs gf LEFT JOIN \Micro\Models\ConsumeDetailLog cl ON gf.id = cl.itemId where cl.createTime BETWEEN '".$timeStart."' AND '".$timeEnd."' AND cl.type < ".
			$this->config->consumeType->coinType." GROUP BY gf.id"; 
			$field = "gf.name,count(cl.itemId)  as consume,cl.id as consumeLogId,cl.createTime ,sum(cl.count)counts, sum(cl.amount) amount,gf.id";
			$sql = "SELECT " . $field . " FROM " . $table."  ORDER BY ".$order." limit ".$limit.",".$pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			if($tempData->valid()){
				$num = 1+$limit;
				foreach($tempData as $val){	
					$data['uidCount'] = $this->getUserCount($val->id); 		 //消费用户数				
					$data['num'] = $num++;
					$data['name'] = $val->name;
					$data['createTime'] = date('Y-m-d',$val->createTime);		
					$data['consume'] = $val->consume;  //消费次数			
					$data['counts'] = $val->counts;		//送出总数			
					$data['amount'] = $val->amount;		//送出总金额
					array_push($list,$data);
				}

				if($sort == 6){
					//消费用户数降序
					array_multisort($list, SORT_DESC);
				}else if($sort == 7){
					//消费用户数升序
					array_multisort($list, SORT_ASC);
				}
			} 

			//统计总条数
			$count = 0;
			if($list){
				$countSql = "SELECT COUNT(*) FROM ".$table;
				$countQuery = $this->modelsManager->createQuery($countSql);
            	$countData = $countQuery->execute();
            	$count = count($countData);
			}
			$result['count'] = $count;
			$result['list'] = $list;
			return $result;
			
		} catch (\Exception $e) {
            $this->errLog('getGiftList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
	}

	//统计消费用户人数
	private function getUserCount($giftId){
		try {		
       		$sql = " select count(cl.uid) count from \Micro\Models\ConsumeDetailLog cl where cl.itemId = ".$giftId." GROUP by cl.uid";
       		$query = $this->modelsManager->createQuery($sql);
            $count =   $query->execute();
            return count($count);

        } catch (\Exception $e) {
            $this->errLog('getUserCount error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}

	//消费趋势
	public function consumptionTrend(){
		$result = array();
		try {
			$result['yesterDay'] = $this->yesterDayConsumption();
			$result['week'] = $this->weekConsumption();
			$result['month'] = $this->monthConsumption();
			return $result;

		} catch (\Exception $e) {
            $this->errLog('consumptionTrend error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}

	//昨天趋势
	private function yesterDayConsumption(){
		try {
			$yesterDayStart = strtotime(date('Y-m-d'))- 60*60*24;  			//昨天 00:00:00
			$yesterDayEnd = $yesterDayStart + 86399;			          	//昨天  23:59:59
       		$sum = \Micro\Models\ConsumeDetailLog::sum(
                        array("column" => "amount", "conditions" => "createTime BETWEEN " . $yesterDayStart . " AND " . $yesterDayEnd . " and type < " . $this->config->consumeType->coinType));
        	return $sum ? $sum : 0;
        } catch (\Exception $e) {
            $this->errLog('yesterDayConsumption error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}

	//本周消费
	private function weekConsumption(){
		try {
			$weekDay = date('N'); // 获得当前是周几
            $timeDiff = $weekDay - 1;
            $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
            $sum = \Micro\Models\ConsumeDetailLog::sum(
                        array("column" => "amount", "conditions" => "createTime BETWEEN " . $weekStar . " AND " . time() . " and type < " . $this->config->consumeType->coinType));
        	return $sum ? $sum : 0;
        } catch (\Exception $e) {
            $this->errLog('weekConsumption error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}

	//本月消费
	private function monthConsumption(){
		try {
			 $monthStar = strtotime(date('Y-m') . "-01");
			  $sum = \Micro\Models\ConsumeDetailLog::sum(
                        array("column" => "amount", "conditions" => "createTime BETWEEN " . $monthStar . " AND " . time() . " and type < " . $this->config->consumeType->coinType));
        	return $sum ? $sum : 0;
		} catch (\Exception $e) {
            $this->errLog('monthConsumption error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}

	//消费趋势数据图
	public function getConsumptionData($type, $Begin, $End){
		$timeBegin = strtotime($Begin);
        $timeEnd = strtotime($End);
        $list = array();
        try {
            switch ($type) {            	
                case 'month':   //按月统计
                    $dataFormat = "%Y%m";
                    break;
                case 'week'://按周统计
                    $dataFormat = '%Y%u';
                    break;

                case 'day' ://按天统计
                    $dataFormat = "%Y%m%d";
                    break;
                case 'hour':   //按时统计
                    $dataFormat = "%H";
                    break;
                default :
                //
            }
            if($type == 'hour'){
        		if($Begin == '' ){
        			$timeBegin = strtotime(date('Y-m-d'));
        		}
        		$timeEnd = $timeBegin+86399;
    		}else if ($timeBegin == '' && $timeEnd == '') {
                $timeBegin = strtotime(" - 6 days", time());
                $timeEnd = time();
            }else  if(!empty($timeBegin) && empty($timeEnd)){

                $timeEnd = $timeBegin + 86399;

            }else if($timeEnd != ''){

                $timeEnd = $timeEnd+86399;
            }
            if($type == 'month'){
            	$end = date("Y-m-d 23:59:59"); //默认为今天
            	$begin = date("Y-m-d", strtotime($end) - 2592000); //默认为30天前
            	$timeEnd = strtotime($end);
            	$timeBegin = strtotime($begin);

            }

           $sql = "select DATE_FORMAT(from_unixtime(cl.createTime), '{$dataFormat}') as time,sum(amount) as sum from \Micro\Models\ConsumeDetailLog cl ".
           " where cl.createTime BETWEEN '" . $timeBegin . "' AND '" . $timeEnd . "'"." AND cl.type < " . $this->config->consumeType->coinType ." GROUP BY time ORDER BY cl.id DESC";
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $newResult = $this->getDataByDates($type, $result, date('Y-m-d',$timeBegin),date('Y-m-d',$timeEnd));
            return $newResult;
        } catch (\Exception $e) {
            $this->errLog('getFamilyBroadcastTime error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	}

    //新用户推广注册趋势数据图
    public function getRecUserData($uid, $type, $starTime, $endTime){
        $timeBegin = strtotime($starTime);
        $timeEnd = strtotime($endTime);
        $list = array();
        try {
            switch ($type) {                
                case 'month':   //按月统计
                    $dataFormat = "%Y%m";
                    break;
                case 'week'://按周统计
                    $dataFormat = '%Y%u';
                    break;

                case 'day' ://按天统计
                    $dataFormat = "%Y%m%d";
                    break;
                default :
                    $dataFormat = "%Y%m%d";
                    break;
            }
            if ($timeBegin == '' && $timeEnd == '') {
                $timeBegin = strtotime(" -6 days", strtotime(date('Y-m-d')));
                $timeEnd = time();
            }else  if(!empty($timeBegin) && empty($timeEnd)){
                $timeEnd = $timeBegin + 86399;
            }else if($timeEnd != ''){
                $timeEnd = $timeEnd + 86399;
            }
            if($type == 'month'){
                $end = date("Y-m-d 23:59:59"); //默认为今天
                $begin = date("Y-m-d", strtotime($end) - 2592000); //默认为30天前
                $timeEnd = strtotime($end);
                $timeBegin = strtotime($begin);
            }

            $sql = "select DATE_FORMAT(from_unixtime(rl.createTime), '{$dataFormat}') as time,count(1) as sum "
                . " from \Micro\Models\RecommendLog rl "
                . " where rl.beRecUid > 0 and rl.recUid = " . $uid ." and rl.createTime between " . $timeBegin . " and " . $timeEnd 
                . " group by time order by rl.id desc ";
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();

            $newResult = $this->getDataByDates($type, $result, date('Y-m-d',$timeBegin),date('Y-m-d',$timeEnd));

            return $this->status->retFromFramework($this->status->getCode('OK'), $newResult);
        } catch (\Exception $e) {
            $this->errLog('getFamilyBroadcastTime error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }
        
        
    //导出统计网站留存数据
    public function getGuestStatisticsExcel($startTime, $endTime,$channel) {
        $headarr = array("日期", "当日注册用户数", "次日留存数", "次日留存率", "七日留存数", "七日留存率");
        $excelData = array();

        $dt_start = strtotime($startTime);
        $dt_end = strtotime($endTime);
        do {
            $date = date('Ymd', $dt_start);

            $time1 = strtotime($date);
            $time2 = $time1 + 86400;
            $excelData[$date]['date'] = $date;
            //当日新增用户
            $newNum = \Micro\Models\RegisterLog::count("createTime between " . $time1 . " and " . $time2 . " and parentType='".$channel."'");
            $excelData[$date]['newNum'] = $newNum ? $newNum : 0;

            //次日留存
            $time3 = $time2 + 86400;
            $sql2 = "select count(*)count from \Micro\Models\LoginLog l"
                    . " inner join \Micro\Models\RegisterLog r on l.uid=r.uid"
                    . " where r.createTime between " . $time1 . " and " . $time2 . " and l.createTime between " . $time2 . " and " . $time3
                    . " and r.parentType='".$channel."'";
            $query2 = $this->modelsManager->createQuery($sql2);
            $result2 = $query2->execute();
            $excelData[$date]['count2'] = $result2 ? $result2[0]['count'] : "0";
            $excelData[$date]['scale2'] = $result2 && $newNum ? sprintf("%.2f", $result2[0]['count'] / $newNum) * 100 . "%" : "0%";


            //7日留存
            $time4 = $time1 + 518400;
            $time5 = $time4 + 86400;
            $sql7 = "select count(*)count from \Micro\Models\LoginLog l"
                    . " inner join \Micro\Models\RegisterLog r on l.uid=r.uid"
                    . " where r.createTime between " . $time1 . " and " . $time2 . " and l.createTime between " . $time4 . " and " . $time5
                    . " and r.parentType='".$channel."'";
            $query7 = $this->modelsManager->createQuery($sql7);
            $result7 = $query7->execute();
            $excelData[$date]['count7'] = $result7 ? $result7[0]['count'] : "0";
            $excelData[$date]['scale7'] = $result7 && $newNum ? sprintf("%.2f", $result7[0]['count'] / $newNum) * 100 . "%" : "0%";
            
            $excelData[$date]['remain1'] = '';
            $excelData[$date]['remain2'] = '';

          /**  //设备留存
            $guestNum = \Micro\Models\GuestLog::count("createTime between " . $time1 . " and " . $time2);
            $excelData[$date]['guest'] = $guestNum ? $guestNum : 0;
            //设备次日留存
            $gsql2 = "select count(*)count from \Micro\Models\GuestLog l"
                    . " inner join \Micro\Models\GuestLog r on l.uuid=r.uuid"
                    . " where r.createTime between " . $time1 . " and " . $time2 . " and l.createTime between " . $time2 . " and " . $time3;
            $gquery2 = $this->modelsManager->createQuery($gsql2);
            $gresult2 = $gquery2->execute();
            $excelData[$date]['gcount2'] = $gresult2 ? $gresult2[0]['count'] : "0";
            $excelData[$date]['gscale2'] = $gresult2 && $guestNum ? sprintf("%.2f", $gresult2[0]['count'] / $guestNum) * 100 . "%" : "0%";
            //设备7日留存
            $gsql7 = "select count(*)count from \Micro\Models\GuestLog l"
                    . " inner join \Micro\Models\GuestLog r on l.uuid=r.uuid"
                    . " where r.createTime between " . $time1 . " and " . $time2 . " and l.createTime between " . $time4 . " and " . $time5;
            $gquery7 = $this->modelsManager->createQuery($gsql7);
            $gresult7 = $gquery7->execute();
            $excelData[$date]['gcount7'] = $gresult7 ? $gresult7[0]['count'] : "0";
            $excelData[$date]['gscale7'] = $gresult7 && $guestNum ? sprintf("%.2f", $gresult7[0]['count'] / $guestNum) * 100 . "%" : "0%";
           * **/
                
        } while (($dt_start += 86400) <= $dt_end);

        // print_R($excelData);exit;
        $this->getExcel($startTime."_".$endTime."_[$channel]", $headarr, $excelData);
    }

    //导出统计每天用户的注册数
    public function getRegStatisticsExcel($startTime, $endTime) {
    	$headarr = array("日期", "当日注册用户数");
        $excelData = array();

        $dt_start = strtotime($startTime);
        $dt_end = strtotime($endTime);
        do {
        	$date = date('Ymd', $dt_start);

            $time1 = strtotime($date);
            $time2 = $time1 + 86400;
            $excelData[$date]['date'] = $date;
            //当日新增用户
            $newNum = \Micro\Models\Users::count("createTime between " . $time1 . " and " . $time2);
            $excelData[$date]['newNum'] = $newNum ? $newNum : 0;

        } while (($dt_start += 86400) <= $dt_end);

        $this->getExcel($startTime."_".$endTime, $headarr, $excelData);
    }
}
