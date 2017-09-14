<?php
	//客服后台 == 日志管理
namespace Micro\Frameworks\Logic\Investigator;
use Micro\Models\InvOperationLog;

class InvJournal extends InvBase{
	
	//
	public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }
	
	//获取日志所有信息
	public function getJournalAllInfo($start,$end,$page,$pageSize){
		$list = array();
		try{
			$start = strtotime($start);
			$end = strtotime($end);
			if(!empty($end)){
					$end = $end + 86399;
			}
			if(!empty($start) && empty($end)){
				$end = $start + 86399;
			}

			$table = "\Micro\Models\InvOperationLog l ";
			$field = "l.id,l.uid as userName,l.createTime,l.log1,l.log2,l.operaDesc,l.operaObject,l.operaType";
			$condition = "1=1";
			if($start!= '' || $end != ''){
				$condition .= " AND l.createTime BETWEEN ".$start." AND ".$end;
			}			
			$limit = ($page-1)*$pageSize;
			$sql = "select ".$field." from ".$table." where ".$condition." order by l.id desc limit ".$limit.",".$pageSize;
			$query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			if($tempData->valid()){
				foreach($tempData as $val){
					$data['id'] = $val->id;
					$data['userName'] = $val->userName;				//操作者
					$data['operaObject'] = $val->operaObject;		//操作对象
					$data['operaType'] = $val->operaType;			//操作类型
					$data['operaDesc'] = $val->operaDesc;			//操作描述
					$data['createTime'] = date('Y-m-d H:i:s',$val->createTime);
					$data['log1'] = $val->log1;				//操作前日志
					$data['log2'] = $val->log2;				//操作后日志	
					array_push($list,$data);
				}
			}
			//统计总条数
			$count = 0;
			if ($list) {
                $countSql = "SELECT COUNT(*) AS count FROM " . $table . " WHERE " . $condition . " limit 1";
                $countQuery = $this->modelsManager->createQuery($countSql);
                $countResult = $countQuery->execute();
                if ($countResult->valid()) {
                    $count = $countResult[0]['count'];
                }
            }
			$result['count'] = $count;
			$result['list'] = $list;
			return $result;
			
		} catch (\Exception $e) {
            $this->errLog('getJournalAllInfo error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }  
	}


	//登录日志
	public function getLoginLogInfo($start,$end,$page,$pageSize){
		$list = array();
		try{
			$start = strtotime($start);
			$end = strtotime($end);
			if(!empty($end)){
					$end = $end + 86399;
			}
			
			if(!empty($start) && empty($end)){
				$end = $start + 86399;
			}
			$table = "\Micro\Models\InvLoginLog l";
			$field = "l.id,l.createTime,l.ip,l.loginType,l.uid as userName";

			$condition = "1=1";

			if($start!= '' || $end != ''){
				$condition .= " AND l.createTime BETWEEN ".$start." AND ".$end;
			}			
			$limit = ($page-1)*$pageSize;

			$sql = "select ".$field." from ".$table." where ".$condition." order by l.id desc limit ".$limit.",".$pageSize;
			
			$query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			if($tempData->valid()){
				foreach($tempData as $val){
					$data['id'] = $val->id;
					$data['userName'] = $val->userName;				//操作者					
					$data['createTime'] = date('Y-m-d H:i:s',$val->createTime);
					$data['loginType'] = $val->loginType;				//登录类型
					$data['ip'] = $val->ip;				//操作后日志	
					array_push($list,$data);
				}
			}
			//统计总条数
			$count = 0;
			if ($list) {
                $countSql = "SELECT COUNT(*) AS count FROM " . $table . " WHERE " . $condition . " limit 1";
                $countQuery = $this->modelsManager->createQuery($countSql);
                $countResult = $countQuery->execute();
                if ($countResult->valid()) {
                    $count = $countResult[0]['count'];
                }
            }
			$result['count'] = $count;
			$result['list'] = $list;
			return $result;
			
		} catch (\Exception $e) {
            $this->errLog('getJournalAllInfo error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }  
	}
	
}
