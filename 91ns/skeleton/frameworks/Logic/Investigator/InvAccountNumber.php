<?php

namespace Micro\Frameworks\Logic\Investigator;

class InvAccountNumber extends InvBase{
	
	//
	public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }

    //账号
	public function getUserList($userName,$sort,$page,$pageSize,$type = 1){
		$list = array();
		try{
			$table = "\Micro\Models\Users u LEFT JOIN \Micro\Models\UserInfo ui on u.uid = ui.uid
					  left join \Micro\Models\UserProfiles up on up.uid = u.uid ".
					 " left join \Micro\Models\RicherConfigs r on r.level = up.level3"	;
			$field = "ui.uid,ui.avatar,ui.nickName,r.name,up.cash,up.money,up.level3";
			//$condition = "1=1";
			//$this->config->userInternalType->tuo
			$condition = "u.internalType = ".$type." AND u.status=1";
			if($userName != ''){
				$condition .= " AND (ui.uid like '%".$userName."%' OR ui.nickName like '%".$userName."%')";
			}

			$order = '';

			switch ($sort) {
                case 1://富豪等级升序
                    $order = "up.level3 asc";
                    break;
                case 2: //富豪等级降序
                    $order = "up.level3 desc";
                    break;
                 case 3://余额升序
                    $order = "(up.cash) asc";
                    // $order = "(up.cash + up.money) asc";
                    break;
                case 4: //余额降序
                    $order = "(up.cash) desc";
                    // $order = "(up.cash + up.money) desc";
                    break;
                default : $order = " ui.uid asc ";
                    //               
            }
            $limit = ($page-1)*$pageSize;

            $sql = "SELECT ".$field." FROM ".$table." where ".$condition." ORDER BY ".$order." limit ".$limit.",".$pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
            if(!empty($tempData)){
            	foreach ($tempData as  $val) {
            		$data['uid'] = $val->uid;
            		$data['avatar'] = $val->avatar;
            		if (empty($data['avatar'])) {
	                    $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
	                }
            		$data['name'] = $val->name;
            		$data['nickName'] = $val->nickName;
            		$data['cash'] = $val->cash;// + $val->money;
            		array_push($list, $data);
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
           return $result; 

		} catch (\Exception $e) {
            $this->errLog('getUserList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return array('count'=>0,'list'=>array());
        }
	}

	//账号详情
	public function getUidConsumerNew($uid, $start, $end, $page, $pageSize, $searchId = 0){		
		try{
			//消费记录
			$limit = ($page - 1) * $pageSize;
            $condition = ' where uid = ' . $uid . ' and type in (3,4,5) ';
            if($start != '' || $end != ''){
				$condition .= ' and createTime >= ' . strtotime($start) . ' and createTime <= ' . (strtotime($end) + 86399);
			}

			if($searchId){
				$condition .= ' and receiveUid = ' . $searchId;
			}
			$condition .= ' group by receiveUid ';
			$sql = 'select IFNULL(sum(amount), 0) as ttl,receiveUid,remark from \Micro\Models\ConsumeDetailLog ' . $condition;
			$query = $this->modelsManager->createQuery($sql . ' limit ' . $limit . ',' . $pageSize);
            $tempData = $query->execute();
			
			$sqlCount = 'select receiveUid from \Micro\Models\ConsumeDetailLog ' . $condition;
			$queryCount = $this->modelsManager->createQuery($sqlCount);
            $countData = $queryCount->execute();

            $count = $countData->valid() ? count($countData) : 0;

            $data = array();
            if($tempData->valid()){
                foreach ($tempData as $v) {
                    $tmp['nickName'] = $v->remark;
                    $tmp['anchorId'] = $v->receiveUid;
                    $tmp['ttl'] = $v->ttl;
                    $data[] = $tmp;
                    unset($tmp);
                }
            }
			$result['count'] = $count;
			$result['list'] = $data;
			return $result;
		} catch (\Exception $e) {
            $this->errLog('getUidConsumer error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}

	//账号详情
	public function getUidConsumer($uid, $start, $end, $page, $pageSize, $searchId = 0){		
		try{
			//消费记录 
            $normalLib = $this->di->get('normalLib');
            $configs = $normalLib->getConfigs();
            $limit = ($page-1)*$pageSize;
            $condition = 'uid = ' . $uid . ' and type < ' . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar;
            if($start != '' || $end != ''){
				$condition .= ' and createTime >= ' . strtotime($start) . ' and createTime <= ' . (strtotime($end) + 86399);
			}

			if($searchId){
				$condition .= ' and receiveUid = ' . $searchId;
			}
            $consumeData = \Micro\Models\ConsumeDetailLog::find(
                $condition . ' limit ' . $limit . ',' . $pageSize
            );
            $count = \Micro\Models\ConsumeDetailLog::count(
                $condition
            );

            $data = array();
            // $amountSum = 0;
            if(!empty($consumeData)){
                foreach ($consumeData as $v) {
                    $tmp['type'] = $v->type;
                    $tmp['count'] = ($v->type == $this->config->consumeType->buyGuard) ? 1 : $v->count;
                    $tmp['createTime'] = date('Y-m-d H:i:s', $v->createTime);
                    $tmp['nickName'] = $v->remark;
                    $tmp['anchorId'] = $v->receiveUid;
                    $tmp['uid'] = $v->uid;
                    $tmp['amount'] = $v->amount;
                    $tmp['guardType'] = $v->itemId;
                    // $amountSum += $v->amount;
                    $tmp['name'] = $configs[$v->type][$v->itemId]['name'];
                    $tmp['configName'] = $configs[$v->type][$v->itemId]['configName'];
                    $data[] = $tmp;
                    unset($tmp);
                }
            }
			/*$table ="\Micro\Models\ConsumeLog cl left join \Micro\Models\UserInfo ui on cl.anchorId = ui.uid";
			$condition = "cl.uid =".$uid." AND type < ".$this->config->consumeType->coinType;
			if($start !=''|| $end != ''){
				$condition .= ' AND cl.createTime BETWEEN {$start} AND {$end}';
			}
			$field = "cl.id,cl.amount,cl.type,cl.createTime,cl.anchorId,ui.nickName";

			$limit = ($page-1)*$pageSize;
			$sql = " select ".$field." from ".$table." where ".$condition." limit ".$limit.",".$pageSize;
			$query = $this->modelsManager->createQuery($sql);
       		$tempData = $query->execute();
			$ConsumeLog = $tempData->toArray();
			 $finalresult=array();
				if(!empty($ConsumeLog)){					
    				$data = array();							 
    				foreach($ConsumeLog as $k => $v){
    					$arr['id']=$v['id'];
    					$arr['nickName'] = $v['nickName'];
    					$arr['amount']=$v['amount'];
    					$arr['type']=$v['type'];
    					$arr['createTime']=date('Y-m-d H:i:s',$v['createTime']); 
                        $arr['anchorId']=$v['anchorId'];
    					$data[$v['type']][$v['id']]=$arr;
    					array_push($finalresult,$arr);
    				}
					//print_R($data);exit;
					foreach($data as $k=>$v){
						$id = array();
						foreach($v as $key => $val){
							$id[] = $key;
						}
						$array=implode(',',$id);

                        //VIP/金喇叭/银喇叭
                        if( ($k==$this->config->consumeType->buyVip) || 
                            ($k==$this->config->consumeType->sendRoomBroadcast) || 
                            ($k==$this->config->consumeType->sendAllRoomBroadcast) 
                            ){
                            foreach($finalresult as $k=>$v){
                                if( ($v['type'] == $this->config->consumeType->buyVip) ||
                                    ($v['type'] == $this->config->consumeType->sendRoomBroadcast) ||
                                    ($v['type'] == $this->config->consumeType->sendAllRoomBroadcast)
                                    ){ 
                                    // 这里的数量只会有一种，所以前端自己做
                                    $finalresult[$k]['count']=1;
                                }
                            }
                        }

						//buyCar
						if($k==$this->config->consumeType->buyCar){
							$sql = "select a.consumeLogId,b.name,b.configName from \Micro\Models\CarLog a,\Micro\Models\CarConfigs b where  a.carId = b.id  AND a.consumeLogId in(".$array.")";
							$query = $this->modelsManager->createQuery($sql);
							$tempData = $query->execute();
							$resultTemp = $tempData->toArray();
							foreach($resultTemp as $kr=>$vr){			
								$newresult[$vr['consumeLogId']]['name']=$vr['name'];
								$newresult[$vr['consumeLogId']]['configName']=$vr['configName'];
							}	
							foreach($finalresult as $k=>$v){
								if($v['type'] == $this->config->consumeType->buyCar){ 
									$finalresult[$k]['name']=$newresult[$v['id']]['name'];
									$finalresult[$k]['configName']=$newresult[$v['id']]['configName'];
                                    $finalresult[$k]['count']=1;
								}
                            }
						}
						
						//守护
						if($k == $this->config->consumeType->buyGuard){
							$sql = "SELECT consumeLogId, guardType FROM \Micro\Models\GuardLog WHERE consumeLogId IN (".$array.")";
                            $query = $this->modelsManager->createQuery($sql);
                            $tempData = $query->execute();
                            $resultTemp = $tempData->toArray();

                            foreach($resultTemp as $kr=>$vr){           
                                $newresult[$vr['consumeLogId']]['guardType']=$vr['guardType'];
                            }
                            foreach($finalresult as $k=>$v){
                                if($v['type'] == $this->config->consumeType->buyGuard){ 
                                    $finalresult[$k]['guardType']=$newresult[$v['id']]['guardType'];
                                    $finalresult[$k]['count']=1;
                                }
                            }
						}
						
						//抢坐grabSeat
						if($k == $this->config->consumeType->grabSeat){
							$sql = "select a.count,a.seatPos,a.consumeLogId from \Micro\Models\GrabLog a where a.consumeLogId in(".$array.")";
							$query = $this->modelsManager->createQuery($sql);
							$tempData = $query->execute();
							$resultTemp = $tempData->toArray();
							foreach($resultTemp as $kr=>$vr){			
								$newresult[$vr['consumeLogId']]['seatPos']=$vr['seatPos'];
								$newresult[$vr['consumeLogId']]['count']=$vr['count'];
							}	
							foreach($finalresult as $k=>$v){
								if($v['type'] == $this->config->consumeType->grabSeat){ 				
									$finalresult[$k]['seatPos']=$newresult[$v['id']]['seatPos'];
									$finalresult[$k]['count']=$newresult[$v['id']]['count'];
								}
                            }
							
						}
						
						//
						//礼物
						if($k==$this->config->consumeType->sendGift){//
							$sql = "select a.consumeLogId,b.configName,b.name,a.count from \Micro\Models\GiftLog a,\Micro\Models\GiftConfigs b where  a.giftId = b.id  AND a.consumeLogId in(".$array.")";
							$query = $this->modelsManager->createQuery($sql);
							$tempData = $query->execute();
							$resultTemp = $tempData->toArray();
							foreach($resultTemp as $kr=>$vr){
								$newresult[$vr['consumeLogId']]['name']=$vr['name'];
								$newresult[$vr['consumeLogId']]['configName']=$vr['configName'];
								$newresult[$vr['consumeLogId']]['count']=$vr['count'];
							}							
							
							foreach($finalresult as $k=>$v){
                                if($v['type'] == $this->config->consumeType->sendGift){ 
                                    $finalresult[$k]['name']=$newresult[$v['id']]['name'];
                                    $finalresult[$k]['configName']=$newresult[$v['id']]['configName'];
                                    $finalresult[$k]['count']=$newresult[$v['id']]['count'];
                                }
                            }
						}
					}
				}*/

				//统计总条数
				// $count = 0;
				$sum = \Micro\Models\ConsumeDetailLog::sum(
                                array("column" => "amount", "conditions" => $condition));
                $sum = $sum ? $sum : 0;
				/*if($finalresult){
					$countSql = "select count(*) as count from ".$table." where ".$condition;
					$countQuery = $this->modelsManager->createQuery($countSql);
       				$countData = $countQuery->execute();
       				if ($countData->valid()) {
                    	$count = $countData[0]['count'];
                	}
				}*/
				$result['count'] = $count;
				// $result['sum'] = $amountSum;
				$result['total'] = $sum;
				$result['list'] = $data;
				return $result;
		} catch (\Exception $e) {
            $this->errLog('getUidConsumer error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}

	//获取账号等级信息
	public function getUserInfo($uid){
		$info = array();
		try{
			// $this->getAmount($uid);
			$sql = "select ui.uid,ui.avatar,ui.nickName,r.level,r.name,(up.cash) AS cash from \Micro\Models\UserInfo ui ".
					"left join \Micro\Models\UserProfiles up on up.uid = ui.uid ".
					"left join \Micro\Models\RicherConfigs r on r.level = up.level3 ".
					"left join \Micro\Models\ConsumeDetailLog cl on ui.uid = cl.uid where ui.uid = ".$uid.
					" GROUP BY cl.uid order by ui.uid desc limit 1";
			$sql = 'select IFNULL(sum(cl.amount),0) as amount,cl.uid,cl.nickName from \Micro\Models\ConsumeDetailLog cl where cl.uid = ' . $uid . ' limit 1';
			// echo $sql;die;
			$query = $this->modelsManager->createQuery($sql);
	        $tempData = $query->execute();
	        $sql1 = "select ui.uid,ui.avatar,ui.nickName,r.level,r.name,(up.cash) AS cash from \Micro\Models\UserInfo ui " . 
					"left join \Micro\Models\UserProfiles up on up.uid = ui.uid " . 
					"left join \Micro\Models\RicherConfigs r on r.level = up.level3 " . 
					" where ui.uid = " . $uid . 
					" limit 1";
			$query1 = $this->modelsManager->createQuery($sql1);
	        $tempData1 = $query1->execute();
	        if($tempData->valid()){
	        	//foreach ($tempData as $val) {
	        		$data['uid'] = $tempData1[0]['uid'];
	        		$data['avatar'] = $tempData1[0]['avatar'] ? $tempData1[0]['avatar'] : $this->pathGenerator->getFullDefaultAvatarPath();
	        		$data['nickName'] = $tempData1[0]['nickName'];
	        		$data['amount'] = $tempData[0]['amount'];
	        		$data['level'] = $tempData1[0]['level'];
	        		$data['name'] = $tempData1[0]['name'];
	        		$data['cash'] = $tempData1[0]['cash'];
	        		// $data['amount'] = $this->getAmount($uid);
	        		array_push($info,$data);
	        	//}
	        }
	        return $info;
    	} catch (\Exception $e) {
            $this->errLog('getUidConsumer error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}

	//总消费数
	private function getAmount($uid){
		try{
			$sql = "select sum(cl.amount) as amount  from \Micro\Models\ConsumeDetailLog cl where cl.uid = ".$uid." AND type < ".$this->config->consumeType->coinType."  GROUP BY cl.uid ";
			$query = $this->modelsManager->createQuery($sql);
		    $result = $query->execute();
		    $amount = 0;
		    if($result->valid()){
		    $amount = $result[0]['amount'];	
		    }
		    return $amount;
	    } catch (\Exception $e) {
            $this->errLog('getUidConsumer error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}
}
