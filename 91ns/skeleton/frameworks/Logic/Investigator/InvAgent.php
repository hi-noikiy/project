<?php

namespace Micro\Frameworks\Logic\Investigator;

//客服后台--代理
class InvAgent extends InvBase {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
    }

    //申请详情
    public function getFamilyApplyInfo($applyId) {
        $info = array();
        try {
            $sql = "select a.status as applyStatus,a.createTime as applyTime,a.auditUser,a.auditTime,a.id,a.uid,a.status,f.name,f.shortName,f.createTime,f.logo,f.companyName,f.address,ui.nickName,ui.avatar,s.realName,s.telephone,p.level2 "
                    . " from \Micro\Models\ApplyLog a inner join \Micro\Models\Family f on a.uid=f.creatorUid "
                    . " inner join \Micro\Models\UserInfo ui on ui.uid=a.uid "
                    . " inner join \Micro\Models\SignAnchor s on s.uid=a.uid "
                    . " inner join \Micro\Models\UserProfiles p on p.uid=ui.uid where a.id = " . $applyId . " and type=" . $this->config->applyType->createFamily . ' order by f.id desc';
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                $tempData = $result->toarray();
                $info['id'] = $tempData[0]['id'];
                $info['uid'] = $tempData[0]['uid'];
                $info['status'] = $tempData[0]['status'];
                $info['name'] = $tempData[0]['name'];
                $info['shortName'] = $tempData[0]['shortName'];
                $info['logo'] = $tempData[0]['logo'];
                $info['companyName'] = $tempData[0]['companyName'];
                $info['address'] = $tempData[0]['address'];
                $info['nickName'] = $tempData[0]['nickName'];
                $info['avatar'] = $tempData[0]['avatar'];
                if (empty($info['avatar'])) {
                    $info['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }
                $info['realName'] = $tempData[0]['realName'];
                $info['telephone'] = $tempData[0]['telephone'];
                $info['level2'] = $tempData[0]['level2'];
                $info['applyStatus'] = $tempData[0]['applyStatus'];
                $info['createTime'] = date('Y-m-d H:i:s', $tempData[0]['createTime']);
                $info['applyTime'] = date('Y-m-d H:i:s', $tempData[0]['applyTime']);
                $info['auditUser'] = $tempData[0]['auditUser'];
                $info['auditTime'] = date('Y-m-d H:i:s', $tempData[0]['auditTime']);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $info);
        } catch (\Exception $e) {
            $this->errLog('getFamilyApplyInfo error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //创建申请
    public function getFamilyApplyList($type, $page, $pageSize) {
        $list = array();
        try {
            $table = "\Micro\Models\ApplyLog a inner join \Micro\Models\Family f inner join \Micro\Models\UserInfo ui";
            $field = "a.id,a.uid,a.status,a.createTime,f.address,f.name, ui.nickName,ui.avatar";
            $condition = " a.targetId=f.id AND a.uid = ui.uid  AND a.type=" . $this->config->applyType->createFamily;
            switch ($type) {
                case 1:
                    $condition .= " AND a.status = " . $this->config->applyStatus->ing;
                    break;
                case 2:
                    $condition .= " AND a.status = " . $this->config->applyStatus->pass;
                    break;
                default:
                //
            }
            $limit = ($page - 1) * $pageSize;

            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $condition . " limit " . $limit . "," . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                foreach ($result as $val) {
                    $data['id'] = $val->id;
                    $data['uid'] = $val->uid;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    $data['nickName'] = $val->nickName;
                    $data['name'] = $val->name;
                    $data['createTime'] = date('Y-m-d H:i:s', $val->createTime);
                    $data['status'] = $val->status;
                    array_push($list, $data);
                }
            }
            //统计总数
            $count = 0;
            $ingcount = 0;
            if ($list) {
                // $count = \Micro\Models\ApplyLog::count($condition . 'type = ' . $this->config->applyType->createFamily);
                // $ingcount = \Micro\Models\ApplyLog::count($condition . 'type = ' . $this->config->applyType->createFamily . ' AND status = ' . $this->config->applyStatus->ing);
                $countSql = "SELECT COUNT(*) AS count FROM " . $table . " WHERE " . $condition . " limit 1";
                $countQuery = $this->modelsManager->createQuery($countSql);
                $countResult = $countQuery->execute();
                if ($countResult->valid()) {
                    $count = $countResult[0]['count'];
                }

                //获取待处理的总数
                $statusing = "SELECT COUNT(*) as ingcoung  FROM " . $table . " WHERE a.status = " . $this->config->applyStatus->ing . " AND " . $condition;
                $Ingcountquery = $this->modelsManager->createQuery($statusing);
                $ingcountresult = $Ingcountquery->execute();

                if ($ingcountresult) {
                    $ingcount = $ingcountresult[0]['ingcoung'];
                }
            }

            $familyData['list'] = $list;
            $familyData['count'] = $count;
            $familyData['ingcount'] = $ingcount;
            return $this->status->retFromFramework($this->status->getCode('OK'), $familyData);
        } catch (\Exception $e) {
            $this->errLog('getFamilyApplyList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //家族列表
    public function getFamilyList($familyName = '', $orderType, $page = 1, $pageSize = 20){
        try {
            !$page && $page = 1;
            !$pageSize && $pageSize = 20;
            $limit = ($page - 1) * $pageSize;
            $condition = ' where 1 ';
            if ($familyName !== '') {
                $condition .= ' and f.name like "%' . $familyName . '%"';
            }

            $sql = 'select ui.nickName,f.creatorUid,f.logo,f.name,f.shortName,f.id,f.isHide '
                . ' from \Micro\Models\Family as f left join \Micro\Models\UserInfo as ui on f.creatorUid = ui.uid '
                . $condition . ' order by f.isHide asc limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();

            $list = array();
            if($result->valid()){
                foreach ($result as $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['logo'] = $v->logo;
                    $tmp['name'] = $v->name;
                    $tmp['shortName'] = $v->shortName;
                    $tmp['uid'] = $v->creatorUid;
                    $tmp['isHide'] = $v->isHide;
                    array_push($list, $tmp);
                }
            }

            $countSql = 'select count(1) as count from \Micro\Models\Family as f ' . $condition . ' limit 1';
            $countQuery = $this->modelsManager->createQuery($countSql);
            $countResult = $countQuery->execute();
            $count = $countResult->valid() ? $countResult->toArray()[0]['count'] : 0;
                
            $familyData['count'] = $count;
            $familyData['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $familyData);

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //修改家族状态
    public function modifyFamily($id = 0, $isHide = 0){
        try {
            $familyData = \Micro\Models\Family::findFirst('id = ' . $id);
            if(empty($familyData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $familyData->isHide = $isHide;
            $familyData->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //家族
    public function getAllFamilyList($familyName, $orderType, $page, $pageSize) {
        $list = array();
        try {
            //SELECT f.id,f.logo,f.name,f.shortName,ui.nickName,ui.uid,sum(cl.income) income FROM pre_family f LEFT JOIN pre_user_info ui on f.creatorUid=ui.uid LEFT JOIN pre_consume_log cl on f.id=cl.familyId AND type < 1000;
            /*
            SELECT a.name,IFNULL(total_income,0) total_income from 
            pre_family a LEFT JOIN 
            (
            select familyid, type, sum(income) total_income from pre_consume_log where type < 1000 group by familyid
            ) b on a.id = b.familyid
            */

            // $table = "\Micro\Models\Family f left JOIN \Micro\Models\UserInfo ui on f.creatorUid = ui.uid"
            //         . " left join \Micro\Models\ConsumeLog cl on f.id = cl.familyId AND type < " . $this->config->consumeType->coinType;
            //$field = "f.id,f.logo,f.name,f.shortName,ui.nickName,ui.uid ,sum(cl.income) income";

            //嵌套子查询的做法（少用）
            /*$table = "pre_family f LEFT JOIN pre_user_info ui on f.creatorUid = ui.uid ".
                     " LEFT JOIN (SELECT familyId, type, sum(income) income FROM pre_consume_detail_log where type < ".$this->config->consumeType->coinType. " group by familyId) b on f.id = b.familyid";
            $field = "f.id, f.logo, f.name, f.shortName, ui.nickName, ui.uid ,IFNULL(income, 0) income";*/

            $limit = ($page - 1) * $pageSize;
            //$condition = "type < " . $this->config->consumeType->coinType;
            $condition = " 1 ";
            if ($familyName) {
                $condition .= " AND f.name like '%" . $familyName . "%'";
            }
            //$condition.=" GROUP BY f.id";

            $order = '';
            switch ($orderType) {
                case 1://收益升序
                    //$order = "cl.income asc";
                    $order = "income asc";
                    break;
                case 2: //收益降序
                    //$order = "cl.income desc";
                    $order = "income desc";
                    break;
                default :
                    //
                    $order = "f.id asc ";
            }
            //获取用户信息
            $sql = 'select nickName,uid from Micro\Models\UserInfo';
            $query = $this->modelsManager->createQuery($sql);
            $users = $query->execute();
            $userArr = array();
            if($users->valid()){
                foreach ($users as $k => $v) {
                    $tmp = array(
                        'nickName' => $v->nickName,
                        'uid' => $v->uid
                    );
                    $userArr[$v->uid] = $tmp;
                }
            }
            /*$sql = 'select f.id, f.logo, f.name, f.shortName, f.creatorUid ,IFNULL(sum(cd.income), 0) income from pre_family as f ' 
                   . ' left join pre_consume_detail_log as cd on f.id = cd.familyId '
                   . ' where ' . $condition . ' group by f.id ORDER BY ' . $order . ' limit ' . $limit . ',' . $pageSize;*/
            $sql = 'select f.id, f.logo, f.name, f.shortName, f.creatorUid, ui.nickName from pre_family as f left join pre_user_info as ui on f.creatorUid = ui.uid ' 
                . ' where ' . $condition . ' ORDER BY ' . $order . ' limit ' . $limit . ',' . $pageSize;
                // echo $sql ;die;
            /*echo $sql;die;
            $sql = "SELECT " . $field . " FROM " . $table . " where " . $condition . " ORDER BY " . $order . " limit " . $limit . "," . $pageSize;
            echo $sql;die;*/
            $connection = $this->di->get('db');
            $result = $connection->fetchAll($sql);

            //$query = $this->modelsManager->createQuery($sql);
            //$result = $query->execute();
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $data['id'] = $val['id'];
                    $data['logo'] = $val['logo'];
                    $data['name'] = $val['name'];
                    $data['shortName'] = $val['shortName'];
                    // $data['nickName'] = $val['nickName'];
                    $data['nickName'] = $userArr[$val['creatorUid']]['nickName'];
                    // $data['uid'] = $val['uid'];
                    $data['uid'] = $val['creatorUid'];
                    // $data['income'] = number_format($val['income'], 3);
                    array_push($list, $data);
                }
            }
            //统计总数 
            $count = 0;
            //rsort()	
            if ($list) {
                $count = \Micro\Models\Family::count();
                // $count = count($list);
                /* $countSql = "SELECT COUNT(".$field.") as count FROM ".$table." WHERE ".$condition." limit 1";
                  $countQuery = $this->modelsManager->createQuery($countSql);
                  $countResult = $countQuery->execute();
                  if($countResult->valid()){
                  $count = $countResult[0]['count'];
                  } */
            }
            $familyData['count'] = $count;
            $familyData['list'] = $list;
            return $this->status->retFromFramework($this->status->getCode('OK'), $familyData);
        } catch (\Exception $e) {
            $this->errLog('getAllFamilyList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //家族 == 详情,基本资料
    public function getFamilyInfo($id) {

        $info = array();
        try {
            $sql = "SELECT f.id,f.name,f.shortName,f.logo,f.address,f.companyName,ui.nickName,ui.uid,ui.avatar FROM \Micro\Models\Family f LEFT join \Micro\Models\FamilyLog fl on f.id = fl.familyId LEFT join  \Micro\Models\UserInfo ui  ON ui.uid = f.creatorUid WHERE f.id = " . $id . " limit 1";
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                $result = $result->toarray();
                $info['id'] = $result[0]['id'];
                $info['name'] = $result[0]['name'];
                $info['shortName'] = $result[0]['shortName'];
                $info['logo'] = $result[0]['logo'];
                $info['companyName'] = $result[0]['companyName'];
                $info['address'] = $result[0]['address'];
                $info['nickName'] = $result[0]['nickName'];
                $info['uid'] = $result[0]['uid'];
                $info['avatar'] = $result[0]['avatar'];
                if (empty($info['avatar'])) {
                    $info['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $info);
        } catch (\Exception $e) {
            $this->errLog('getFamilyInfo error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //家族 == 旗下的主播
    public function getFamilyAnchor($familyId, $page, $pageSize) {
        $list = array();
        try {
				$table = "\Micro\Models\SignAnchor s LEFT JOIN \Micro\Models\UserInfo ui on s.uid = ui.uid LEFT JOIN \Micro\Models\UserProfiles p on s.uid = p.uid";
                //  LEFT JOIN \Micro\Models\ConsumeDetailLog cl on s.uid = cl.receiveUid and  cl.type < ".$this->config->consumeType->coinType." and s.familyId = cl.familyId
				$field = "s.id,ui.uid,ui.avatar,ui.nickName,p.level2";//,sum(cl.income) as income
				$condition = " s.familyId = " . $familyId . " GROUP BY s.uid";
				
			/*   $table = "\Micro\Models\SignAnchor s inner join \Micro\Models\UserInfo ui inner join \Micro\Models\UserProfiles p inner join \Micro\Models\ConsumeLog cl";
            $field = "s.id,ui.uid,ui.avatar,ui.nickName,p.level2,sum(cl.income) as income";
            $condition = "s.uid = ui.uid AND s.uid = p.uid AND s.uid = cl.anchorId AND s.familyId = cl.familyId AND type < " . $this->config->consumeType->coinType . " AND s.familyId = " . $familyId . " GROUP BY cl.uid";  */
            $limit = ($page - 1) * $pageSize;
	
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $condition . " order by s.uid desc limit " . $limit . "," . $pageSize;
            $query = $this->modelsManager->createQuery($sql);     
		
            $tempData = $query->execute();
            if ($tempData->valid()) {
                foreach ($tempData as $val) {
					$data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    $data['id'] = $val->id;
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
					$data['level2'] = $val->level2;
					// $data['fans'] = $this->getAnchorFansNum($val['uid']);  //粉丝
     //                $data['income'] = $val->income ? $val->income : 0;                    
                    array_push($list, $data);
                }
            }
            //统计总数
            $count = 0;    
             if ($list) {
                $countSql = "SELECT count(*) counts FROM " . $table . " WHERE " . $condition;
                $countquery = $this->modelsManager->createQuery($countSql);				
                $countresult = $countquery->execute();
                $count =count($countresult);
            }
			
			$result = array();
            $result['count'] = $count;
            $result['list'] = $list;
			return $result;
        } catch (\Exception $e) {
            $this->errLog('getFamilyAnchor error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    public function getFamilyAnchorNew($familyId, $page, $pageSize = 10, $search = '') {
        $list = array();
        $count = 0;
        try {
            // 获取家族创建者id
            $res = \Micro\Models\Family::findFirst('id = ' . $familyId);

            $creatorUid = !empty($res) ? $res->creatorUid : 0;

            $limit = ($page - 1) * $pageSize;
            $sql = 'select sa.id,ui.uid,ui.avatar,ui.nickName,up.level2,up.level4,r.roomId from \Micro\Models\SignAnchor as sa '
                . ' left join \Micro\Models\UserInfo as ui on sa.uid = ui.uid '
                . ' left join \Micro\Models\UserProfiles as up on up.uid = ui.uid '
                . ' left join \Micro\Models\Rooms r on r.uid = sa.uid '
                // . ' left join \Micro\Models\RoomLog rl on rl.roomId = r.roomId '
                . ' where sa.familyId = ' . $familyId . ($search ? ' and ui.nickName like "%' . $search . '%" ' : '') . ' group by sa.uid '
                . ' order by sa.uid desc ';//,rl.publicTime desc
            $query = $this->modelsManager->createQuery($sql . ' limit ' . $limit . ',' . $pageSize);     
            $tempData = $query->execute();
            if ($tempData->valid()) {
                foreach ($tempData as $val) {
                    $data['canDelete'] = $creatorUid == $val->uid ? 0 : 1;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    $data['id'] = $val->id;
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['level2'] = $val->level2;
                    $data['publicTime'] = $val->roomId ? $this->getAnchorLastPub($val->roomId) : '';//$val->publicTime ? date('Y-m-d H:i:s',$val->publicTime) : '';
                    // $data['fans'] = $this->getAnchorFansNum($val['uid']);  //粉丝
                    $data['fansLevel'] = $val->level4;  //粉丝
                    array_push($list, $data);
                }
                $sqlCount = 'select sa.uid from \Micro\Models\SignAnchor as sa '
                    . ' left join \Micro\Models\UserInfo as ui on sa.uid = ui.uid '
                    . ' where sa.familyId = ' . $familyId . ($search ? ' and ui.nickName like "%' . $search . '%" ' : '') . ' group by sa.uid ';
                $queryCount = $this->modelsManager->createQuery($sqlCount);
                $countRes = $queryCount->execute();
                $count = $countRes->valid() ? count($countRes) : 0;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data' => $list, 'count' => $count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    private function getAnchorLastPub($roomId = 0){
        try {
            $res = \Micro\Models\RoomLog::findFirst('roomId = ' . $roomId . ' order by publicTime desc');
            return $res ? date('Y-m-d H:i:s',$res->publicTime) : '';
        } catch (\Exception $e) {
            $this->errLog('getFamilyAnchor error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return '';
        }
    }

    //家族旗下所有主播工作情况导出Excel
    public function getExcelData($familyId,$startTime,$stopTime){
        $info = array();
        try {
            $start = strtotime($startTime);
            $end = strtotime($stopTime);
            if(!empty($startTime) && !empty($stopTime)){
                $stop = $end + 86399;
            }else if($startTime != '' && $stopTime == ''){
                $stop = $start + 86399;
            }
            $table = "\Micro\Models\SignAnchor s LEFT JOIN \Micro\Models\UserInfo ui on s.uid = ui.uid ".
            " LEFT JOIN \Micro\Models\BasicSalary b on s.uid = b.uid ".
            " LEFT JOIN \Micro\Models\FamilyLog fl on fl.uid = s.uid ".
            " LEFT JOIN \Micro\Models\ConsumeLog cl on s.uid = cl.anchorId and  cl.type < ".$this->config->consumeType->coinType." and s.familyId = cl.familyId";
            $field = "s.realName,ui.uid,ui.nickName,s.telephone,b.type,b.money,fl.outOfTime";
            $condition = " s.familyId = " . $familyId . " AND fl.joinTime < '".$stop."' AND (fl.outOfTime > '".$start."' or fl.outOfTime is null) GROUP BY s.uid";
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $condition . " order by s.uid desc";
            $query = $this->modelsManager->createQuery($sql);     
			
            $tempData = $query->execute();
            if(!empty($tempData)){
                foreach($tempData as $val){
                    $data['realName'] = $val->realName;
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['telephone'] = $val->telephone;
                    $type = '无底薪';
                    if($val->type == $this->config->salaryType->keepLow){
                        $type = '保底';
                    }else if($val->type == $this->config->salaryType->fixation){
                        $type = '固定';
                    }else{
						$type = '时薪';
					}
                    $data['type'] = $type;
                    $data['money'] = $val->money;
                    array_push($info, $data);
                }
            }
            $this->getAnchorExcels('家族的旗下主播', $info);
        } catch (\Exception $e) {
            $this->errLog('getFamilyAnchor error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //家族 == 播出时长
    public function getBroadcastTime($familyId,$type='day',$Begin, $End) {

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
				default : 
					//
			}
			
			if($Begin == '' && $End == ''){
                
                $timeBegin = strtotime(" - 6 days", time());        
                $timeEnd = time();
				if($type == 'month'){
					$end = date("Y-m-d 23:59:59"); //默认为今天
					$begin = date("Y-m-d", strtotime($end) - 2592000); //默认为30天前
					$timeEnd = strtotime($end);
					$timeBegin = strtotime($begin);
				}

			}else   if(!empty($Begin) && empty($End)){

                $timeEnd = $timeBegin + 86399;

            }else if($timeEnd != ''){

                $timeEnd = $timeEnd+86399;
            }

          
            
			$familyUid = $this->getFamilyUid($familyId);
            $uid = array();

            if (is_array($familyUid) && !empty($familyUid)) {
				
                foreach ($familyUid as $key => $id) {
                    if ($id['uid'] != '')
                        $uid[] = $id['uid'];
					
                }
                $strUid = implode(',', $uid);
            }
            // $sql = "select DATE_FORMAT(from_unixtime(rl.publicTime), '{$dataFormat}') as time,sum(rl.endTime - rl.publicTime - IFNULL((".$timeBegin." - rlop.publicTime),0) - IFNULL((rlop.endTime -".$timeEnd." ),0)) AS sum from".
            // " \Micro\Models\Rooms r inner join \Micro\Models\RoomLog  rl on r.roomId = rl.roomId ".
            // " LEFT JOIN \Micro\Models\RoomLog rle ON rl.id=rle.id".
            // " LEFT JOIN \Micro\Models\RoomLog rloe ON rloe.id=rle.id AND !(rloe.endTime BETWEEN ".$timeBegin." AND ".$timeEnd.") ".
            // " LEFT JOIN \Micro\Models\RoomLog rlop ON rlop.id=rle.id AND !(rlop.publicTime BETWEEN ".$timeBegin." AND ".$timeEnd.")".
            // " WHERE r.uid in(" .$strUid . ") AND rle.endTime > rle.publicTime AND ((rle.publicTime BETWEEN ".$timeBegin." AND ".$timeEnd.") OR (rle.endTime BETWEEN ".$timeBegin." AND ".$timeEnd.")) GROUP BY time ORDER BY rl.id DESC";
            /*$sql = "select DATE_FORMAT(from_unixtime(rl.publicTime), '{$dataFormat}') as time,sum(rl.endTime - rl.publicTime) as sum from ".
                   " \Micro\Models\Rooms r inner join \Micro\Models\RoomLog  rl on r.roomId = rl.roomId".
                   " WHERE r.uid in(" .$strUid . ")  AND ((rl.publicTime BETWEEN ".$timeBegin." AND ".$timeEnd.") OR (rl.endTime BETWEEN ".$timeBegin." AND ".$timeEnd.")) AND r.showStatus <> 0 GROUP BY time ORDER BY rl.id DESC";*/
            $sql = "select DATE_FORMAT(from_unixtime(rl.publicTime), '{$dataFormat}') as time,sum(rl.endTime - rl.publicTime) as sum from ".
                   " \Micro\Models\Rooms r inner join \Micro\Models\RoomLog  rl on r.roomId = rl.roomId".
                   " WHERE r.uid in(" .$strUid . ")  AND ((rl.publicTime BETWEEN ".$timeBegin." AND ".$timeEnd.") OR (rl.endTime BETWEEN ".$timeBegin." AND ".$timeEnd.")) AND r.showStatus <> 0 GROUP BY time ORDER BY rl.id DESC";
            $query = $this->modelsManager->createQuery($sql);
            $rooms = $query->execute();	 
			$newResult = $this->getDataByDates($type, $rooms, date('Y-m-d',$timeBegin), date('Y-m-d',$timeEnd));	
            $max = 0;
            $min = $rooms->valid() ? $rooms->toArray()[0]['num'] : 0;
            $sum = 0;
            $mean = 0;
            //计算最多,最少,平均
            if (!empty($rooms)) {
                foreach ($rooms as $val) {
                    $data['time'] = $val->time;
                    $data['duration'] = $val->sum;
                    if ($max < $val->sum) {
                        $max = $val->sum;
                    }
                    if ($min > $val->sum) {
                        $min = $val->sum;
                    }

                    $sum += $val->sum;
                    array_push($list, $data);
                }
                if ($sum != 0) {
                    $mean = floor($sum / count($list));
                }
            }
            if(!empty($newResult)){
                foreach($newResult as $key => $val){
                    if($val['sum'] != 0)
                    $newResult[$key]['sum'] = floor($val['sum']/60);
                }
            }
            $result['max'] = $max;
            $result['min'] = $min;
            $result['mean'] = $mean;
            $result['list'] = $newResult;
            return $result;
        } catch (\Exception $e) {
            $this->errLog('getAnchorIncomeList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
       
    }

    //获取某个家族下的主播uid
    private function getFamilyUid($familyId) {
        try {
            $sql = "SELECT f.name,f.logo,fl.uid FROM \Micro\Models\Family f left join \Micro\Models\FamilyLog fl on f.id = fl.familyId AND f.id = " . $familyId;
            $query = $this->modelsManager->createQuery($sql);
            $familyLog = $query->execute();

            $familyUid = array();
            if ($familyLog->valid()) {
                foreach ($familyLog as $val) {
                    $data['uid'] = $val->uid;
                    $data['name'] = $val->name;
                    $data['logo'] = $val->logo;
                    array_push($familyUid, $data);
                }
            }
            return $familyUid;
        } catch (\Exception $e) {
            $this->errLog('getFamilyUid error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }

    }


      //某日期内家族收益
    public function getFamilyIncomeList($type, $familyId, $startDate, $endDate){
        $starttime = strtotime($startDate);
        $endtime = strtotime($endDate);
        switch ($type) {
            case 'week'://按周统计
                $dataFormat = '%Y%u';
                break;
            case 'month':   //按月统计
                $dataFormat = "%Y%m";
                break;
            default ://按天统计
                $dataFormat = "%Y%m%d";
        }

            if($startDate == '' && $endDate == ''){
                $starttime = strtotime(" - 6 days", time());
                if($type == 'month'){
                    $starttime = strtotime(" - 30 days", time());
                }               
                $endtime = time();
            }else if($startDate != '' && $endDate==''){
                $endtime = $starttime+86399;
            }else if($$endDate !=''){
                $endtime = $starttime+86399;
            }

        try {
            $sql = "SELECT sum(cl.income)as sum,  DATE_FORMAT(from_unixtime(cl.createTime), '{$dataFormat}')  as time".
                    " FROM \Micro\Models\ConsumeDetailLog cl ".
                    " WHERE cl.familyId = {$familyId}".
                    " AND cl.type < ".$this->config->consumeType->coinType.
                    " AND cl.createTime BETWEEN '".$starttime."' AND '".$endtime.
                    "' GROUP BY  time";
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $newResult = $this->getDataByDates($type, $result, date('Y-m-d',$starttime), date('Y-m-d',$endtime));
            return $this->status->retFromFramework($this->status->getCode('OK'), $newResult);
        } catch (\Exception $e) {
            $this->errLog('getFamilyIncomeList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //家族收益==详情
    public function getFamileyInfo($familyid){
        $info = array();
        try {
           $sql = "select f.id,f.logo,f.name from \Micro\Models\Family f  where  f.id = ".$familyid;
            $query = $this->modelsManager->createQuery($sql);

            $tempData = $query->execute();
            if($tempData->valid()){
                foreach($tempData as $val){
                    $info['id'] = $val->id;
                    $info['logo'] = $val->logo;
                    $info['name'] =$val->name;
                    $info['income'] = $this->checkFamilyIncomeSum($val->id);
                    $info['money'] = $this->checkFamilyMoney($val->id);
                    $info['notSettle'] = 0;  //不可结算
                }
            }
            return $info;
        } catch (\Exception $e) {
            $this->errLog('getFamileyInfo error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //家族总收益
     private function checkFamilyIncomeSum($familyid) {
        $sum = 0;
        try {
            $sum = \Micro\Models\ConsumeDetailLog::sum(
                    array("column" => "income", "conditions" => "familyId = " . $familyid . " and type < " . $this->config->consumeType->coinType));
            return $sum ? $sum : 0;
        } catch (Exception $e) {
            $this->errLog('checkFamilyIncomeSum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //查询家族可结算聊币
    private function checkFamilyMoney($familyid) {
         $sum = 0;
        try {
           $sum = \Micro\Models\SignAnchor::sum(array("column" => "money", "conditions" => "familyId = " . $familyid . " and money > 0"));
           return $sum ? $sum : 0;
        } catch (Exception $e) {
            $this->errLog('checkFamilyMoney error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }
	
	//代理 ## 家族详情 ## 收益 ## 贡献排行
	public function getFamilyContributionv($familyId,$startTime,$stopTime,$type,$page,$pageSize){
		$list = array();
		try {	
			
			$yesterDayStart = strtotime(date('Y-m-d'))- 60*60*24;  			//昨天 00:00:00
			
			$weekDay = date('N'); // 获得当前是周几
            $timeDiff = $weekDay - 1;
            $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
			$lastWeekStar = strtotime("-7 days", $weekStar); //上周一
			$monthStar = strtotime(date('Y-m') . "-01");
            $lastMonthStar = strtotime('-1 month', $monthStar);  //上个月
			
			switch($type){
				case 'thisDay':  //今天
					$start = strtotime(date('Y-m-d'));
					$stop = time();
					break;
				case 'yesterday':  //昨天
					$start = $yesterDayStart;
					$stop = strtotime(date('Y-m-d'));
					break;
				case 'thisWeek':  //本周
					$start = $weekStar;
					$stop = time();
					break;
				case 'lastWeek':  //上周
					$start = $lastWeekStar;
					$stop = $weekStar;
					break;
				case 'thisMonth':  //本月
					$start = $monthStar;
					$stop = time();
					break;
				case 'lastMonth':  //上月
					$start = $lastMonthStar;
					$stop = $monthStar;
					break;
				default :
					//
			}
	
			if(!empty($startTime) && !empty($stopTime)){
				$start = strtotime($startTime);
				$stop = strtotime($stopTime);	
				$stop = $stop + 86399;
			}else if(!empty($startTime) && empty($stopTime)){
				$start = strtotime($startTime);
				$stop = $start + 86399;
			}
			$familyUid = $this->getFamilyUid($familyId);
            $uid = array();

            if (is_array($familyUid) && !empty($familyUid)) {
                foreach ($familyUid as $key => $id) {
                    if ($id['uid'] != '')
                        $uid[] = $id['uid'];
                }
                $strUid = implode(',', $uid);
            }
			$table = '\Micro\Models\ConsumeDetailLog cl ';//'left join \Micro\Models\UserInfo ui on ui.uid = cl.receiveUid';
            $field = 'cl.receiveUid,cl.remark as nickName,sum(cl.income) as income';
			// $field = 'ui.avatar,ui.uid,ui.nickName,sum(cl.income) as income';
			$where = "cl.createTime BETWEEN '".$start."' AND '".$stop."' AND cl.type < " . $this->config->consumeType->coinType . " AND income > 0 AND cl.receiveUid in (" . $strUid . ")  group by cl.receiveUid ";
			
			$limit = ($page - 1) * $pageSize;
            $sql = "select " . $field . " from " . $table . " where " . $where . " order by income desc limit " . $limit . "," . $pageSize;
			$query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();
			$num =1+$limit;
			if(!empty($tempData)){
				foreach($tempData as $val){
					$data['number'] = $num++;
					// $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    // $data['uid'] = $val->uid;
					$data['uid'] = $val->receiveUid;
					$data['nickName'] = $val->nickName;
					// $data['income'] = $val->income;
					array_push($list,$data);
				}
			}
			
			//统计总数
            $count = 0;    
            if ($list) {
                $countSql = "SELECT count(*) counts FROM " . $table . " WHERE " . $where;
                $countquery = $this->modelsManager->createQuery($countSql);				
                $countresult = $countquery->execute();
                $count =count($countresult);
            }
			
			$result['count'] = $count;
			$result['list'] = $list;
			return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            $this->errLog('getAnchorInfo error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
	}
	
	//结算日
	public function getSettlementDate($id,$day){
		try{
			if(!intval($id)){
				return false;
			}
			$family = \Micro\Models\Family::findFirst('id = '.$id);
			if(empty($family)){
				return $this->status->retFromFramework($this->status->getCode('FAMILY_NOT_EXISTS'));
			}			
			$family->settlementDate = $day;
			$family->save();
			return $this->status->retFromFramework($this->status->getCode('OK'));
		} catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
	}

    //显示结算日F
    public function getFamliySettlement($id){
        try{
            if(!intval($id)) return false;
            $info = array();
            $family = \Micro\Models\Family::findFirst('id = '.$id);
            if(!empty($family)){
                $info['settlementDate'] = $family->settlementDate;
            }
            return $info;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }
}
