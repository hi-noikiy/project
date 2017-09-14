<?php

namespace Micro\Frameworks\Logic\Investigator;

//客服后台--首页
class InvIndex extends InvBase {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }

    //首页--统计信息
    public function getIndexStatistics() {
        //房间总数
        $roomNum = $this->getRoomNum();
        //正在直播房间数
        $liveRoomNum = $this->getLiveRoomNum();
        //今日新签约数
        $newInchorNum = $this->getTodaySignAnchorNum();
        //今日聊币消费总额
        $newCashNum = $this->getTodayConsumeCashNum();
        //今日聊币实际消费总额
        $newRealCashNum = $this->getTodayConsumeRealCashNum();
        //今日托账号消费聊币
        $newTuoCashNum = $this->getTodayConsumeTuoCashNum();
        //今日新创建家族数
        $newFamilyNum = $this->getTodayFamilyNum();
        return array('roomNum' => $roomNum, 'liveRoomNum' => $liveRoomNum, 'newInchorNum' => $newInchorNum, 'newCashNum' => $newCashNum, 'newRealCashNum' => $newRealCashNum, 'newTuoCashNum' => $newTuoCashNum, 'newFamilyNum' => $newFamilyNum);
    }

    //首页--待处理申请
    public function getPendingApplyList($currentPage = 1, $pageSize = 5) {

        $return = array();
        try {
            $list = array();
            $sql = "SELECT a.id,a.uid,ui.nickName,a.type,a.createTime "
                    . " FROM \Micro\Models\ApplyLog a"
                    . " INNER JOIN \Micro\Models\UserInfo ui on a.uid=ui.uid"
                    . " WHERE a.status=" . $this->config->applyStatus->ing . " AND (a.type=" . $this->config->applyType->sign . " OR a.type=" . $this->config->applyType->createFamily . ")";
            $sql.=" LIMIT " . ($currentPage - 1) * $pageSize . "," . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                foreach ($result as $val) {
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['type'] = $val->type;
                    $data['time'] = date('Y-m-d H:i:s', $val->createTime);
                    if ($val->type == $this->config->applyType->sign) {//主播签约申请
                        $url = "/anchor/applyinfo?id=" . $val->id;
                    } elseif ($val->type == $this->config->applyType->createFamily) {//创建家族签约申请
                        $url = "/agent/applyinfo?id=" . $val->id;
                    }
                    $data['url'] = $url;
                    array_push($list, $data);
                }
            }
            $return['list'] = $list;
            $return['count'] = \Micro\Models\ApplyLog::count("status=" . $this->config->applyStatus->ing . " and ( type=" . $this->config->applyType->sign . " or type=" . $this->config->applyType->createFamily . ")");
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getPendingApplyList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //房间总数
    private function getRoomNum() {
        try {
            return \Micro\Models\Rooms::count();
        } catch (\Exception $e) {
            $this->errLog('getRoomNum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //今日聊币消耗数
    private function getTodayConsumeCashNum() {
        try {
            $today = strtotime(date('Y-m-d'));
            $sum = \Micro\Models\ConsumeDetailLog::sum(array(
                        "column" => "amount",
                        "conditions" => "createTime>=" . $today . " and type < " . $this->config->consumeType->coinType,
                            )
            );
            return $sum ? $sum : 0;
        } catch (\Exception $e) {
            $this->errLog('getTodayConsumeCashNum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //今日托账号消费聊币
    private function getTodayConsumeTuoCashNum() {
        try {
            $today = strtotime(date('Y-m-d'));
            $sum = \Micro\Models\ConsumeDetailLog::sum(array(
                    "column" => "amount",
                    "conditions" => "createTime>=" . $today . " and isTuo = 1 and type < " . $this->config->consumeType->coinType,
                )
            );
            return $sum ? $sum : 0;
        } catch (\Exception $e) {
            $this->errLog('getTodayConsumeCashNum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //今日聊币实际消耗数(非推广员)
    private function getTodayConsumeRealCashNum() {
        try {
            $today = strtotime(date('Y-m-d'));
            $sql = 'select sum(cl.amount) as sum from \Micro\Models\ConsumeDetailLog as cl left join \Micro\Models\Users as u on u.uid=cl.uid where cl.createTime >= '.$today . ' and cl.type < ' . $this->config->consumeType->coinType . ' and u.internalType = 0';
            $query = $this->modelsManager->createQuery($sql);           
            $tempData = $query->execute();
            if(!empty($tempData)){
                foreach($tempData as $val){
                    return is_null($val->sum) ? 0 : $val->sum;
                }
            }
                
            /*$sum = \Micro\Models\ConsumeLog::sum(array(
                        "column" => "income",
                        "conditions" => "createTime>=" . $today . " and type < " . $this->config->consumeType->coinType,
                            )
            );*/
            return 0;
        } catch (\Exception $e) {
            $this->errLog('getTodayConsumeRealCashNum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //今日新签约数
    private function getTodaySignAnchorNum() {
        try {
            $today = strtotime(date('Y-m-d'));
            return \Micro\Models\ApplyLog::count("status=" . $this->config->applyStatus->pass . "AND type = " . $this->config->applyType->sign . " AND auditTime>=" . $today);
        } catch (\Exception $e) {
            $this->errLog('getTodaySignAnchorNum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //今日新创建家族数
    public function getTodayFamilyNum() {
        try {
            $today = strtotime(date('Y-m-d'));
            return \Micro\Models\ApplyLog::count("status = " . $this->config->applyStatus->pass . " AND type = " . $this->config->applyType->createFamily . " AND auditTime >= " . $today);
        } catch (\Exception $e) {
            $this->errLog('getTodayFamilyNum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //正在直播的房间数
    private function getLiveRoomNum() {
        try {
            return \Micro\Models\Rooms::count("liveStatus=1");
        } catch (\Exception $e) {
            $this->errLog('getLiveRoomNum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }
	
	//调试状态数
	private function _getDebugCount(){
		try {
            return \Micro\Models\Rooms::count("showStatus=0");
        } catch (\Exception $e) {
            $this->errLog('_getDebugCount error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
	}
	
	//全部房间
	public function getAllRooms($type,$userName,$page,$pageSize,$order=0){
		$list = array();
		try {

            //监控日志调用接口
            // $monitorDB = new \Micro\Frameworks\Logic\Base\BaseStatistics();
            $monitorDatas = $type == 1 ? $this->addMonitorLog() : array();

			$table='\Micro\Models\Rooms r left join \Micro\Models\UserInfo u on u.uid = r.uid ';
			$field = 'r.liveStatus,r.showStatus,r.uid,u.nickName,r.poster,r.onlineNum,r.robotNum,r.totalNum';
			$where = '1=1';
			switch($type){
				case 1:		//直播状态
					$where .= ' AND r.liveStatus = 1';
					break;
				case 2:		//调试状态
					$where .= ' AND r.showStatus = 0';
					break;	
			}
			//搜索
			if(!empty($userName)){
				$where .= " AND r.uid LIKE '%".$userName."%' or u.nickName LIKE '%".$userName."%'";
			}
			//分页
			$limit = ($page-1)*$pageSize;
			$sql = " SELECT ".$field." FROM ".$table." where ".$where." order by r.showStatus desc,r.liveStatus desc, r.onlineNum desc limit ".$limit." ,".$pageSize;
			$query = $this->modelsManager->createQuery($sql);			
            $tempData = $query->execute();
			foreach($tempData as $val){
				$data['liveStatus'] = $val->liveStatus;
				$data['showStatus'] = is_null($val->showStatus) ? 0 : $val->showStatus;
				$data['uid'] = $val->uid;
//                $data['onlineNum'] = $val->onlineNum+$val->robotNum;
                $data['onlineNum'] = $val->totalNum;
                $data['realNum'] = $val->onlineNum;
				$data['robotNum'] = $val->robotNum;
				$data['nickName'] = $val->nickName;
				$data['poster'] = $val->poster;
                if($type == 1 && in_array($val->uid, $monitorDatas['uids'])){
                    $tmp = $monitorDatas['datas'][$val->uid];
                    $data['monitorFlag'] = 1;
                    $data['inbandwidth'] = $tmp['inbandwidth'];
                    $data['lfr'] = $tmp['lfr'];
                    $data['deployaddress'] = $tmp['deployaddress'];
                    $data['inaddress'] = $tmp['inaddress'];
                    $data['hists'] = $tmp['hists'];
                    unset($tmp);
                }else{
                    $data['monitorFlag'] = 0;
                    $data['inbandwidth'] = 0;
                    $data['lfr'] = 0;
                    $data['deployaddress'] = '';
                    $data['inaddress'] = '';
                    $data['hists'] = 0;
                }
				array_push($list,$data);
			}

            //正在直播的根据条件进行降序排序
            if($type == 1){
                $newArr = array();
                switch ($order) {
                    case '1':
                        $orderKey = 'inbandwidth';
                        break;
                    
                    case '2':
                        $orderKey = 'lfr';
                        break;
                    
                    case '3':
                        $orderKey = 'hists';
                        break;
                    
                    default:
                        $orderKey = 'onlineNum';
                        break;
                }
                foreach ($list as $k => $v) {
                    $newArr[$k] = $v[$orderKey];
                }
                array_multisort($newArr,SORT_DESC,$list);
            }
			//统计总数
			$count = 0;
            if ($list) {
                $countSql = "SELECT count(1) as count FROM " . $table . " WHERE " . $where . " limit 1";
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                if ($countresult->valid()) {
                    $count = $countresult[0]['count'];
                }
            }
			$result['count'] = $count;
			$result['liveRoomNum'] = $this->getLiveRoomNum();   //直播数
			$result['debugCount'] = $this->_getDebugCount();	//调试数
			$result['list'] = $list;
			return $this->status->retFromFramework($this->status->getCode('OK'), $result);
		} catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
	}

    //监控日志
    public function addMonitorLog(){
        $data = file_get_contents("http://streammonitor.fastcdn.com:5922/rtmp?app=B21F9AFC3DF4DEA00FA975063F8712E0");
        
        if(!empty($data)){
            $data_decode = json_decode($data, true);
            $data_result = isset($data_decode['result']) ? $data_decode['result'] : array();

            $datas = array();
            $uids = array();
            foreach ($data_result['datavalue'] as $k => $val) {
                if(empty($val['deployaddress'])) continue;//发布点为空跳出

                $uid = substr($val['streamname'], 10);
                /*if($uid == 10314){
                    $uid = 10078;
                }elseif($uid == 10489){
                    $uid = 1;
                }*/
                 // == 11232 ? 10078 : substr($val['streamname'], 10);
                // $uid = substr($val['streamname'], 10) == 10391 ? 1 : substr($val['streamname'], 10);
                // echo $uid;
                $uids[] = $uid;
                $tmp = array(
                    // 'streamname' => 
                    'inbandwidth' => $val['inbandwidth'],
                    'lfr' => $val['lfr'],
                    'deployaddress' => $val['deployaddress'],
                    'inaddress' => $val['inaddress'],
                    'hists' =>$val['hists']
                );
                $datas[$uid] = $tmp;
                unset($uid);
                unset($tmp);
            }
            return array('uids'=>$uids,'datas'=>$datas);
        }
    }
    

}
