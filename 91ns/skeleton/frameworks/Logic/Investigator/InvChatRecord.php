<?php

namespace Micro\Frameworks\Logic\Investigator;
use Micro\Frameworks\Logic\User\UserFactory;

//客服后台--水军
class InvChatRecord extends InvBase {

    public function __construct() {
        parent::__construct();
    }

    public function chatRecordList($name,$sort,$page,$pageSize){
   		$list = array();
   		try{
   			$table = "\Micro\Models\Users u left join \Micro\Models\UserInfo ui on u.uid = ui.uid ".
					 " left join \Micro\Models\UserProfiles up on up.uid = u.uid ".
					 " left join \Micro\Models\RicherConfigs rc on rc.level = up.level3";
   			$field = "ui.avatar,ui.nickName,rc.name,u.uid ";

   			$condition = "u.isChatRecord =1";
   			if($name != ''){
   				$condition .=  "AND ui.uid like '%".$name."%' OR ui.nickName like '%".$name."%'";
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
					$data['lastTime'] = date('Y-m-d H:i:s',$this->_getLastChat($val->uid));
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

    private function _getLastChat($uid){
    	try{
    		$sql = "select r.createTime  from \Micro\Models\RecordChat r where r.uid =  ".$uid." order by createTime desc limit 1";
			$query = $this->modelsManager->createQuery($sql);
		    $result = $query->execute();			
		    return $result[0]['createTime'];
    	} catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

     //被推荐人信息
     public function getBeRecUserData($uid) {
        try {
            $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid);
            if ($userInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('UID_ERROR'));
            }
            $nickName=$userInfo->nickName;
            $hasbind = 0;
            $orinfo = \Micro\Models\RecommendLog::findfirst("beRecUid=" . $uid);
            if ($orinfo != false) {//已绑定了推荐者
                $hasbind = 1;
            }
            $result['nickName'] = $nickName;
            $result['hasbind'] = $hasbind;
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //绑定推荐人
    public function bindRecUser($recUid, $beRecUid) {
        $postData['uid'] = $beRecUid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $beRecUid);
            if ($userInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('UID_ERROR'));
            }

            $rinfo = \Micro\Models\Recommend::findfirst("uid=" . $recUid);
            if ($rinfo == false) {
                return $this->status->retFromFramework($this->status->getCode('RECOMMOND_UID_ERROR'));
            }

            $orinfo = \Micro\Models\RecommendLog::findfirst("beRecUid=" . $beRecUid);
            if ($orinfo != false) {//已绑定了推荐者
                return $this->status->retFromFramework($this->status->getCode('HAS_BIND_RECOMMOND'));
            }

            //写入数据库
            $new = new \Micro\Models\RecommendLog();
            $new->beRecUid = $beRecUid;
            $new->telephone = 0;
            $new->recUid = $recUid;
            $new->createTime = time();
            $new->save();
            //给用户礼包
            $giftPackageId=  $this->config->recommendGiftId;
            $user = UserFactory::getInstance($beRecUid);
            $user->getUserItemsObject()->giveGiftPackage($giftPackageId);

            //把之前的充值返还给推荐者
            $userMgr=  $this->di->get('userMgr');
            $userMgr->getRecommendPay($recUid, $beRecUid);
            
            
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    //新用户推广抽成 导出excel add by 2015/11/06
    public function excelRecommendListIncome($startDate, $endDate, $nickName='') {
        try {
            $today = date("Y-m-d");
            !$startDate && $startDate = $today;
            !$endDate && $endDate = $today;
            $startTime = strtotime($startDate);
            $endTime = strtotime($endDate) + 86400;
            $where = '1 and (l.type = 2 or l.type = 3)  and l.createTime >= ' . $startTime . ' and l.createTime < ' . $endTime;
            if ($nickName) {
                $where.=" and (s.uid like '%{$nickName}%' or s.realName like '%{$nickName}%' or ui.nickName like '%{$nickName}%')";
            }
            $connection = $this->di->get('db');

            $sql = "select ifnull(sum(l.money / l.proportion), 0) as allCash, ifnull(sum(l.money * 0.01), 0) as allMoney,ui.nickName,ui.avatar,s.realName,l.uid,l.proportion,f.name as familyName "
                    . " from pre_activity_income_log l inner join pre_user_info ui on l.uid = ui.uid left join pre_sign_anchor s on ui.uid=s.uid left join pre_family f on s.familyId=f.id and s.familyId>0 "
                    . " where {$where} and l.createTime>={$startTime} and l.createTime<={$endTime} group by l.uid order by allCash desc";
            $incomeres = $connection->fetchAll($sql);
            $result['sheetName'] = '抽成数据';
                $result['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
                $result['list'][] = array('', '');
                $result['list'][] = array('UID', '昵称', '姓名', '家族','累计充值', '抽成比例', '抽成总额');
                $result['list'][] = array('', '');
                foreach ($incomeres as $val) {
                    $result['list'][] = array($val['uid'], $val['nickName'], $val['realName'], $val['familyName'], $val['allCash'], $val['proportion']."%", $val['allMoney']);
                }
                $excelResult[] = $result;
                //生成excel
                $fileName = '推广抽成表_' . $startDate . ' -- ' . $endDate; //excel文件名
                $normalLib = $this->di->get('normalLib');
                $normalLib->toExcel($fileName, $excelResult);
                                        
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*//摸个用户推荐的新用户推广抽成 导出excel add by 2015/11/10
    public function excelRecommendIncomeByUid($startDate, $endDate, $uid = 0) {
        try {
            $today = date("Y-m-d");
            !$startDate && $startDate = $today;
            !$endDate && $endDate = $today;
            $startTime = strtotime($startDate);
            $endTime = strtotime($endDate) + 86400;
            $sql = 'select rl.id,rl.beRecUid,rl.createTime,ui.nickName,ifnull(sum(o.totalFee), 0) as ttl from \Micro\Models\RecommendLog as rl '
                . ' left join \Micro\Models\UserInfo as ui on rl.beRecUid = ui.uid '
                . ' left join \Micro\Models\Order as o on o.uid = ui.uid and o.status = 1 and o.createTime >= ' . $startTime . ' and o.createTime < ' . $endTime
                . ' where rl.beRecUid > 0 and rl.recUid = ' . $uid . ' group by rl.beRecUid order by ttl desc';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $result['sheetName'] = '抽成数据';
            $result['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
            $result['list'][] = array('', '');
            $result['list'][] = array('UID', '昵称', '累计充值');
            // $result['list'][] = array('', '');
            $sum = 0;
            foreach ($res as $val) {
                $result['list'][] = array($val->beRecUid, $val->nickName, $val->ttl);
                $sum += $val->ttl;
            }
            $result['list'][] = array('', '', '');
            $result['list'][] = array('', '', '总和', $sum);
            $excelResult[] = $result;
            //生成excel
            $fileName = '推广抽成表_' . $startDate . ' -- ' . $endDate; //excel文件名
            $normalLib = $this->di->get('normalLib');
            $normalLib->toExcel($fileName, $excelResult);
                                        
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //新用户推广抽成 导出excel add by 2015/11/06
    public function excelRecommendPayoff($startDate, $endDate, $uid = 0) {
        try {
            $today = date("Y-m-d");
            !$startDate && $startDate = $today;
            !$endDate && $endDate = $today;
            $startTime = strtotime($startDate);
            $endTime = strtotime($endDate) + 86400;
            $sql = 'select ui.nickName,al.remark,ifnull(sum(al.money / al.proportion),0) as ttl '
                . ' from \Micro\Models\ActivityIncomeLog as al '
                . ' left join \Micro\Models\UserInfo as ui on al.remark = ui.uid '
                . ' where (al.type = 2 or al.type = 3) and al.uid = ' . $uid . ' and al.createTime >= ' . $startTime . ' and al.createTime < ' . $endTime
                . ' group by al.remark order by ttl desc ';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            var_dump($res->toArray());die;
            $result['sheetName'] = '抽成数据';
                $result['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
                $result['list'][] = array('', '');
                $result['list'][] = array('UID', '昵称', '姓名', '累计充值', '抽成比例', '抽成总额');
                $result['list'][] = array('', '');
                foreach ($incomeres as $val) {
                    $result['list'][] = array($val['uid'], $val['nickName'], $val['realName'], $val['allCash'], $val['proportion']."%", $val['allMoney']);
                }
                $excelResult[] = $result;
                //生成excel
                $fileName = '推广抽成表_' . $startDate . ' -- ' . $endDate; //excel文件名
                $normalLib = $this->di->get('normalLib');
                $normalLib->toExcel($fileName, $excelResult);
                                        
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }*/

}
