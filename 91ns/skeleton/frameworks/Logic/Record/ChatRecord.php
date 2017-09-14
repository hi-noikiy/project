<?php

namespace Micro\Frameworks\Logic\Record;

use Phalcon\DI\FactoryDefault;

use Micro\Models\RecordChat;
use Micro\Models\UserInfo;
use Micro\Models\UserProfiles;
use Micro\Models\RicherConfigs;
use Micro\Frameworks\Logic\Investigator\InvSettle;


class ChatRecord{
    protected $di;
    protected $session;
    protected $config;
    protected $status;
    protected $userAuth;
    protected $validator;

    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->session = $this->di->get('session');
        $this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->userAuth = $this->di->get('userAuth');
        $this->validator = $this->di->get('validator');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->pathGenerator = $this->di->get('pathGenerator');
    }

    public function errLog($errInfo) {
        $logger = $this->di->get('logger');
        $logger->error('【ChatRecord】 error : '.$errInfo);
    }

    /*
     * 前台添加
     */
    public function addChat($roomId, $chatData) {
        $postData['roomid'] = $roomId;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if ($user == NULL) {
            $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }

        try {
            $chatLog = new RecordChat();
            $chatLog->uid = $user->getUid();
            $chatLog->roomId = $roomId;
            $chatLog->chatData = $chatData;
            $chatLog->createTime = time();
            $chatLog->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 后台查询
     */
    public function getChatList($name,$sort,$page,$pageSize) {
        $list = array();
        try{
            $table = "\Micro\Models\Users u left join \Micro\Models\UserInfo ui on u.uid = ui.uid ".            
                     " left join \Micro\Models\UserProfiles up on up.uid = u.uid ".
                     " left join \Micro\Models\RicherConfigs rc on rc.level = up.level3";
            $field = "ui.avatar,ui.nickName,rc.name,u.uid ";
            $condition = "u.isChatRecord =1";

            if($name != ''){
                $condition .=  "AND (u.uid like '%".$name."%' OR ui.nickName like '%".$name."%') ";
            }
            switch($sort){
                case 1://富豪等级升序
                    $order = "up.level3 asc";
                    break;
                case 2: //富豪等级降序
                    $order = "up.level3 desc";
                    break; 
                default : $order = " u.uid desc";
            }

            $limit = ($page-1)*$pageSize;

            $sql = "select ".$field." from ".$table." where ".$condition." order by ".$order." limit ".$limit." , ".$pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
            if(!empty($tempData)){
                foreach ($tempData as  $K => $val) {
                    $lastTime = $this->_getLastChat($val->uid);
                    if ($lastTime != 0) {
                        $data['lastTime'] = date('Y-m-d H:i:s', $lastTime);
                    }
                    else {
                        $data['lastTime'] = "无";
                    }
                    //$data['lastTime'] = date('Y-m-d H:i:s',$this->_getLastChat($val->uid));
                    $data['uid'] = $val->uid;
                    $data['avatar'] = $val->avatar ?  $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();              
                    $data['name'] = $val->name;
                    $data['nickName'] = $val->nickName;                 
                    array_push($list, $data);
                }
                switch($sort){
                    case 3://时间升序
                        array_multisort($list, SORT_ASC);
                        break;
                    case 4: //时间降序
                        array_multisort($list, SORT_DESC);
                        break; 
                }
            }
            
            //统计总条数
            $count = 0;
            if($list){
                $countSql = " SELECT COUNT(*) as counts FROM ".$table." WHERE ".$condition;
                $queryCount = $this->modelsManager->createQuery($countSql);
                $countResult = $queryCount->execute();
                if($countResult->valid()){
                      $count = $countResult[0]['counts'];
                }
            }
            $result['count'] = $count;
            $result['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

     //获取最后时间
    private function _getLastChat($uid){
        try{
            $sql = "select r.createTime from \Micro\Models\RecordChat r where r.uid =  ".$uid." order by createTime desc limit 1";
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();            
            if ($result->valid()) {
                return $result[0]['createTime'];
            }
            return 0;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

     //详情
    public function getChatInfo($uid,$startTime,$stopTime,$page,$pageSize,$isExcel=0){
        $list = array();
        try{
			
			//默认最后一天数据
			$timeSql = 'select rc.createTime FROM \Micro\Models\RecordChat rc ORDER BY createTime DESC limit 1 ';
            $timeQuery = $this->modelsManager->createQuery($timeSql);
            $timeData = $timeQuery->execute();
            $start = strtotime(date('Y-m-d',$timeData[0]['createTime']));
            $stop = $start + 86399;
			
            $startTime = $startTime != '' ? strtotime($startTime) : $start;
            $stopTime = $stopTime != '' ? strtotime($stopTime)+86399 : $stop;
			
            $table = "\Micro\Models\RecordChat rc left join \Micro\Models\Rooms r on r.roomId = rc.roomId left join  \Micro\Models\UserInfo ui on ui.uid = r.uid";
            $field = "rc.createTime,ui.uid,rc.chatData,ui.nickName";

            //条件
            $condition = " rc.uid = ".$uid;
            if($startTime != '' && $stopTime != ''){
                $condition .= " and rc.createTime BETWEEN '".$startTime."' AND '".$stopTime."'";
            }else if($startTime != '' && $stopTime == ''){
                $stopTime = time();
            }
            
            $limit = ($page-1)*$pageSize;
            $sql = " select ".$field." from ".$table." where ".$condition." limit ".$limit.",".$pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
            if(!empty($tempData)){
                foreach ($tempData as $val) {
                    $data['createTime'] = date('Y-m-d H:i:s',$val->createTime);
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['chatData'] = $val->chatData;
                    array_push($list,$data);
                }
            }   

            //获取总条数
            $count = 0;
            if($list){
                $countSql = " SELECT COUNT(*) as counts FROM ".$table." WHERE ".$condition;
                $queryCount = $this->modelsManager->createQuery($countSql);
                $countResult = $queryCount->execute();
                if($countResult->valid()){
                      $count = $countResult[0]['counts'];
                }

                //导出表格
                if($isExcel == 1){
                    $InvSettle = new InvSettle();
                    $headarr = array("发言时间", "所在直播间",'昵称', "发言内容");
                    $InvSettle->getExcel('水军详情列表', $headarr, $list);
                    exit;
                }
            }
            $result['count'] = $count;
            $result['list'] = $list;
           return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
          return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //数据详情
    public function getChatUserInfo($uid){
        $info = array();
        try{
            $sql = "select ui.uid,ui.avatar,ui.nickName,r.level,r.name from \Micro\Models\UserInfo ui ".
                    "left join \Micro\Models\UserProfiles up on up.uid = ui.uid ".
                    "left join \Micro\Models\RicherConfigs r on r.level = up.level3 ".
                    " where ui.uid = ".$uid.
                    " order by ui.uid desc limit 1";
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
            if($tempData->valid()){
                    $data['uid'] = $tempData[0]['uid'];
                    $data['avatar'] = $tempData[0]['avatar'] ? $tempData[0]['avatar'] : $this->pathGenerator->getFullDefaultAvatarPath();
                    $data['nickName'] = $tempData[0]['nickName'];
                    $data['level'] = $tempData[0]['level'];
                    $data['name'] = $tempData[0]['name'];
                    array_push($info,$data);
            }
            return $info;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    
    //时间
    private function _getTimes($start, $end) {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);       
		     //验证时间 某人昨天
        if($start == '' && $end == ''){
            $yesterDayStart = strtotime(date('Y-m-d'))- 60*60*24;           //昨天 00:00:00
            $yesterDayEnd = $yesterDayStart + 86399;                        //昨天  23:59:59
            $dt_start = $yesterDayStart;
            $dt_end = $yesterDayEnd;
        }else  if(!empty($start) && empty($end)){
                $dt_end = $dt_start + 86399;
        } 
        do {
            $result[] = date('Y-m-d', $dt_start);
        } while (($dt_start += 86400) <= $dt_end);
        return $result;
    }

    //获取数据库数据
    private function _getChatData($uid){
        $sql = " select * from \Micro\Models\RecordChat r where uid = ".$uid." order by createTime asc";
        $query = $this->modelsManager->createQuery($sql);
        $tempData = $query->execute();      
        $list = array();
       if(!empty($tempData)){
            foreach($tempData as $val){
                $data['id'] = $val->id;
                $data['uid'] =$val->uid;
                $data['roomId'] = $val->roomId;
                $data['chatData'] = $val->chatData;
                $data['createTime'] = $val->createTime;
                array_push($list,$data);
            }
            return $list;
        }
    }

 
    //返回水军相应的统计数据
    public function getStatistics($uid,$start,$end,$page=0,$pageSize=10,$type=1,$isExcel=0){
        try{
            $date_array = $this->_getTimes($start,$end);
            $chat_array = $this->_getChatData($uid);
            // print_r($chat_array);exit;       

            //导出excel
            if($isExcel == 1){
                $this->_getExcelData($date_array,$chat_array,$uid,$start,$end,$type);   
            }    
            // ## 按日期归类一下所有数据 ##
            $reData = array();
            foreach($date_array as $day){
                    $reData[$day] = array();
                    foreach($chat_array as $k=>$chat){
                        $day_start = strtotime($day);               //当天开始时间
                        $day_stop = $day_start + (60*60*24);        //当天结束时间
                        if($chat['createTime'] >= $day_start && $chat['createTime'] < $day_stop){       //如果是当天的活动记录
                            $reData[$day][] = $chat;
                        }
                    }
            } 
            // print_r($reData);exit;
			$pid = 1;
			$_count = 0;			//当前总数(临时)
            $demoData = array();			
			$dataCounts =0;   //总条数 
            foreach($reData as $day=>$val){   
				isset($demoData[$pid]) or $demoData[$pid] = array();
                isset($demoData[$pid][$day]) or $demoData[$pid][$day] = array();			
                foreach($val as $chat){
                    // 获取上一个时段数据
                    $tmp = array(
                        'start' => $chat['createTime'],             //开始时间
                        'start_txt' => date('H:i:s', $chat['createTime']),
                        'stop' => $chat['createTime'],              //结束时间
                        'stop_txt' => date('H:i:s', $chat['createTime']),
                        'length' => 0,                              //共计时长
                        'count' => 1,                               //发言条数
                        'length_txt' => date('H:i:s',0),
                    );
					
                    if($demoData[$pid][$day] == array()){
                        $demoData[$pid][$day]['data'] = array();
						if($_count < $pageSize){
							$demoData[$pid][$day]['data'][] = $tmp;
							$_count++;
						}else{
							$pid++;
							
							isset($demoData[$pid]) or $demoData[$pid] = array();
							isset($demoData[$pid][$day]) or $demoData[$pid][$day] = array();
							isset($demoData[$pid][$day]['data']) or $demoData[$pid][$day]['data'] = array();
							
							$demoData[$pid][$day]['data'][] = $tmp;
							$_count = 1;
						}
						$dataCounts++;
                    }else{
                        //获取这个数组最后一个元素
                        $len = count($demoData[$pid][$day]['data'])-1;
                        $_tmp = $demoData[$pid][$day]['data'][$len];
                        
                        //  误差时间5分钟 ## 以结束时间判断
                        $end_time = $_tmp['stop'] + (60*5);     //结束时间五分钟后
                        if($tmp['start'] <= $end_time){         //五分钟内 
                            $demoData[$pid][$day]['data'][$len]['stop'] = $tmp['stop'];
                            $demoData[$pid][$day]['data'][$len]['length'] = number_format(($demoData[$pid][$day]['data'][$len]['stop'] - $demoData[$pid][$day]['data'][$len]['start'])/60, 2);
                            $demoData[$pid][$day]['data'][$len]['count'] = $demoData[$pid][$day]['data'][$len]['count'] + 1;
                            $demoData[$pid][$day]['data'][$len]['stop_txt'] = date('H:i:s', $tmp['stop']);
                            $demoData[$pid][$day]['data'][$len]['length_txt'] = date('H:i:s', $demoData[$pid][$day]['data'][$len]['length']);
							
                        }else{
							if($_count < $pageSize){
								$demoData[$pid][$day]['data'][] = $tmp;
								$_count++;
							}else{
								$pid++;
								
								isset($demoData[$pid]) or $demoData[$pid] = array();
								isset($demoData[$pid][$day]) or $demoData[$pid][$day] = array();
								isset($demoData[$pid][$day]['data']) or $demoData[$pid][$day]['data'] = array();
								
								$demoData[$pid][$day]['data'][] = $tmp;
								$_count = 1;
							}
							$dataCounts++;
                        }
                    }
					//$dataCount++;
					// ## 为防止单天有分页,在循环里计算统计 ##
					if(!empty($demoData[$pid][$day]['data'])){		
							$timeCount = 0;  //总时长
							$count = 0;  //总发言数
							//$number = 0;  //
							//$minute = 0;
						foreach($demoData[$pid][$day]['data'] as $k =>  $v){
							if($type == 1){
								$demoData[$pid][$day]['data'][$k]['minute'] = ($v['length'] != 0 ? number_format($v['count']/$v['length'],2) : 0);  //   条/分钟
								//$minute += $demoData[$pid][$day]['data'][$k]['minute']; 
							}
							if($type == 2){
								$demoData[$pid][$day]['data'][$k]['number'] = ($v['count'] != 0 ? number_format ($v['length']/$v['count'],2) : 0);  //  分钟/条
								//$number += $demoData[$pid][$day]['data'][$k]['number'];
							}
							$timeCount += $v['length'];  
							$count += $v['count'];  			  
						}
						$demoData[$pid][$day]['timeCount'] = $timeCount;
						$demoData[$pid][$day]['count'] = $count;
						$demoData[$pid][$day]['number'] = ($count != 0 ? number_format ($timeCount/$count, 2) : 0);
						$demoData[$pid][$day]['minute'] = ($timeCount != 0 ? number_format($count/$timeCount, 2) : 0);
					}
                }              
            }
			
			// ## 统计当天数据 ##
			for($i = $pid; $i > 0; $i--){
				
				foreach($demoData[$i] as $day => $val){
					$j = $i;
					$j--;
					while(isset($demoData[$j][$day]) && isset($val['timeCount'])){
						
						$demoData[$i][$day]['timeCount'] += $demoData[$j][$day]['timeCount'];
						unset($demoData[$j][$day]['timeCount']);
						
						$demoData[$i][$day]['count'] += $demoData[$j][$day]['count'];
						unset($demoData[$j][$day]['count']);
						
						$demoData[$i][$day]['number'] += $demoData[$j][$day]['number'];
						unset($demoData[$j][$day]['number']);
						
						$demoData[$i][$day]['minute'] += $demoData[$j][$day]['minute'];
						unset($demoData[$j][$day]['minute']);
						
						$j--;
						
					}
					
				}
				
			}
			
			if(isset($demoData[$page])){	//如果存在
				$pageDemoData = $demoData[$page];
				//print_r($demoData[$page]);
			}else{
				//当前页数据不存在
				$pageDemoData = array();
			}
			
            $result['dataCounts'] = $dataCounts;  //总条数
            $result['list'] = $pageDemoData;			
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
          return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
	
	//导出EXCEL
    private function _getExcelData($date_array,$chat_array,$uid,$start,$end,$type){
        $reData = array();
        foreach($date_array as $day){
            $reData[$day] = array();
            foreach($chat_array as $k=>$chat){
                $day_start = strtotime($day);               //当天开始时间
                $day_stop = $day_start + (60*60*24);        //当天结束时间
                if($chat['createTime'] >= $day_start && $chat['createTime'] < $day_stop){       //如果是当天的活动记录
                    $reData[$day][] = $chat;
                }
            }
        }
        $demoData = array();
        foreach($reData as $day=>$val){        
            $demoData[$day] = array();    
            foreach($val as $chat){
                // 获取上一个时段数据
                $tmp = array(
                    'start' => $chat['createTime'],             //开始时间
                    'start_txt' => date('H:i:s', $chat['createTime']),
                    'stop' => $chat['createTime'],              //结束时间
                    'stop_txt' => date('H:i:s', $chat['createTime']),
                    'length' => 0,                              //共计时长
                    'count' => 1,                               //发言条数
                    'length_txt' => date('H:i:s',0),

                );
                if($demoData[$day] == array()){
                    $demoData[$day]['data'][] = $tmp;
                }else{
                    //获取这个数组最后一个元素
                    $len = count($demoData[$day]['data'])-1;
                    $_tmp = $demoData[$day]['data'][$len];
                    
                    //  误差时间5分钟 ## 以结束时间判断
                    $end_time = $_tmp['stop'] + (60*5);     //结束时间五分钟后
                    if($tmp['start'] <= $end_time){         //五分钟内
                        $demoData[$day]['data'][$len]['stop'] = $tmp['stop'];
                        $demoData[$day]['data'][$len]['length'] = number_format(($demoData[$day]['data'][$len]['stop'] - $demoData[$day]['data'][$len]['start'])/60, 2);
                        $demoData[$day]['data'][$len]['count'] = $demoData[$day]['data'][$len]['count'] + 1;
                        $demoData[$day]['data'][$len]['stop_txt'] = date('H:i:s', $tmp['stop']);
                        $demoData[$day]['data'][$len]['length_txt'] = date('H:i:s', $demoData[$day]['data'][$len]['length']);
                    }else{                   
                        $demoData[$day]['data'][] = $tmp;
                    }
                }

                if(!empty($demoData[$day]['data'])){   
                        foreach($demoData[$day]['data'] as $k =>  $v){   
                            if($type == 1){
                                $demoData[$day]['data'][$k]['minute'] = ($v['length'] != 0 ? number_format($v['count']/$v['length'],2) : 0);//   条/分钟
                            }
                            if($type == 2){
                                $demoData[$day]['data'][$k]['number'] = ($v['count'] != 0 ? number_format ($v['length']/$v['count'],2) : 0);  //  分钟/条
                            }              
                        }
                    }

            }    
            
        }

        $headarr = array("日期", "时段", "时长","发言数","发言频率");
        $excelData = array();
        foreach($demoData as $day=>$val){
            //$tmp = array($day); 
            if(isset($val['data']) && is_array($val['data'])){
                foreach($val['data'] as $_data){
                    $minute = '';
                    if($type == 1){
                        $minute = $_data['minute'].'条/分钟';
                    }
                    if($type == 2){
                        $minute = $_data['number'].'分钟/条';
                    }
                    $tmp = array($day, $_data['start_txt'] . ' - ' . $_data['stop_txt'], $_data['length'],$_data['count'], $minute);
                    
                    $excelData[] = $tmp;
                }
            }
            //$excelData[] = $tmp;
        }   
        $InvSettle = new InvSettle();
        $InvSettle->getExcel('统计列表', $headarr, $excelData);
        exit;    
    }

}
