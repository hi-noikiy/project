<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Micro\Models\ApplyLog;
use Micro\Models\SignAnchor;
use Micro\Models\Family;
use Micro\Models\FamilyLog;
use Micro\Models\UserPhoto;
use Micro\Frameworks\Logic\User\UserFactory;

class UserApply extends UserDataBase {

    public function __construct($uid) {
        parent::__construct($uid);
    }

    /*
     * 加入家族申请
     * @param targetId 家族familyId
     * @return 返回操作结果
     */

    public function joinFamilyApply($targetId, $description) {
        $result = $this->addApply($this->config->applyType->family, $targetId, $description);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } else {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    /*
     * 签约申请
     * @param targetId 当前uid申请的用户uid
     * @return 返回操作结果
     */

    public function signApply($description) {
        $result = $this->addApply($this->config->applyType->sign, 0, $description);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } else {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    /*
     * 创建家族申请
     * @param targetId 家族familyId
     * @return 返回操作结果
     */

    public function createFamilyApply($targetId, $description) {
        $result = $this->addApply($this->config->applyType->createFamily, $targetId, $description);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } else {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    /*
     * 撤销申请
     * @param $applyId
     * @return 返回操作结果
     */

    public function cancelApply($applyId) {
        $result = $this->editApply($applyId, $this->config->applyStatus->cancel, '', 1);
        if ($result) {
            $apply = ApplyLog::findFirst("id={$applyId}");
            switch ($apply->type) {
                case $this->config->applyType->family://加入家族申请
                    //删除申请的记录
                    //$familyLog = FamilyLog::findfirst("uid={$this->uid} AND familyId={$apply->targetId}");
                    //$familyLog->status = $this->config->familyLogStatus->refuse;
                    //$familyLog->save();
                    break;
                case $this->config->applyType->sign://签约主播申请
                    $signAnchor = SignAnchor::findfirst("uid={$this->uid} AND status={$this->config->signAnchorStatus->apply}");
                    $signAnchor->status = $this->config->signAnchorStatus->refuse;
                    $signAnchor->save();
                    break;
                case $this->config->applyType->createFamily://创建家族申请
                    //删除申请记录
                    $family = Family::findfirst("creatorUid={$this->uid} AND status = 0");
                    $family->status = $this->config->familyStatus->refuse;
                    $family->save();
                    break;
                default:
                    break;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    /*
     * 判断申请类别并修改状态
     * @param $applyId
     * @return 返回操作结果
     */

    public function updateApplyById($applyId, $status) {
        try {
            $log = ApplyLog::findfirst($applyId);
            if (empty($log)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            switch ($log->type) {
                case $this->config->applyType->family://加入家族/
                    if ($status == 1) {
                        $result = $this->familyMgr->agreeJoinFamily($applyId);
                        return $this->status->retFromFramework($result['code'], $result['data']);
                    } else {
                        $result = false; //
                    }
                    break;
                default:
                    $result = false;
                    break;
            }

            if (!$result) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            $this->errLog('updateApplyById errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 通过申请
     * @param $applyId 
     * @return 返回操作结果
     */

    public function passApply($applyId) {
        $result = $this->editApply($applyId, $this->config->applyStatus->pass);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } else {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    /*
     * 拒绝申请
     * @param $applyId
     * @return 返回操作结果
     */

    public function refuseApply($applyId) {
        $result = $this->editApply($applyId, $this->config->applyStatus->fail);
        if ($result) {
            //给用户发送通知
            $applyInfo = \Micro\Models\ApplyLog::findfirst($applyId);
            $familyInfo = \Micro\Models\Family::findfirst($applyInfo->targetId);
            $sendUser = UserFactory::getInstance($applyInfo->uid);
            $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->failJoinFamily, array(0 => $familyInfo->name));
            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } else {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    /*
     * 获得我的申请列表
     * @return 返回操作结果
     */

    public function getApplyList($currentPage, $pageSize, $status = -1) {
        try {
            $limit = $pageSize*($currentPage-1);
            if($status >= 0){
                $where = " AND a.status=" . intval($status);
                $where1 = " AND status=" . intval($status);
            }else{
                $where = '';
                $where1 = '';
            }

            $phql = "SELECT a.id as applyId,a.status,a.type, b.id as familyId,b.name,b.logo,a.isRead,a.createTime FROM \Micro\Models\ApplyLog a".
                " LEFT JOIN  \Micro\Models\Family b ON a.targetId=b.id".
                " WHERE a.uid = " . $this->uid . " AND a.status<>" . $this->config->applyStatus->cancel . " {$where} order by a.isRead asc ".
                " LIMIT {$limit},{$pageSize}";
            $query = $this->modelsManager->createQuery($phql);
            $applys = $query->execute();

            $applyList = array();
            if ($applys->valid()) {
                foreach ($applys as $val) {
                    $applyData['applyId'] = $val->applyId;
                    $applyData['familyId'] = $val->familyId;
                    $applyData['status'] = $val->status;
                    $applyData['name'] = $val->name;
                    $applyData['logo'] = $val->logo;
                    $applyData['isRead'] = $val->isRead;
                    $applyData['createTime'] = $val->createTime;

                    $typeArray = array_flip($this->config->applyType->toArray());
                    $applyData['type'] = $typeArray[$val->type];

                    array_push($applyList, $applyData);
                }
            }
            $applyResult['list'] = $applyList;
            $count = ApplyLog::count("uid={$this->uid}" . " AND status<>" . $this->config->applyStatus->cancel . $where1);
            $applyResult['count'] = 0;
            if($count>0){
                $applyResult['count'] = $count;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $applyResult);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获得我的申请数量(未读)
     */

    public function getApplyCount() {
        try {
            $count = \Micro\Models\ApplyLog::count("uid = {$this->uid} AND isRead=0");
            return $count;
        } catch (\Exception $e) {
            $this->errLog('getApplyCount error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    /*
     * 获得我的申请数量(未处理)
     */

    public function getApplyUndoCount() {
        try {
            $count = \Micro\Models\ApplyLog::count("uid = {$this->uid} AND status=0");
            return $count;
        } catch (\Exception $e) {
            $this->errLog('getApplyCount error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    /*
     * 获得我的审计列表
     * @return 返回操作结果
     */

    public function getAuditingList($currentPage, $pageSize, $status = -1) {
        try {
            if($status >= 0){
                $where = $status == 0 ? " AND a.status = 0" : " AND ( a.status = 1 or a.status = 3 )";
                $order = $status == 0 ? " order by a.createTime asc " : " order by a.auditTime desc ";
            }else{
                $where = '';
            }

            $limit = $pageSize*($currentPage-1);
            $phql = "SELECT a.id,a.createTime,a.auditTime,a.uid,b.logo,b.id as familyId,c.photo,d.nickName,d.avatar,e.level3,e.level2,a.status FROM \Micro\Models\ApplyLog a"
                    . " INNER JOIN  \Micro\Models\Family b ON a.targetId=b.id"
                    . " LEFT JOIN \Micro\Models\SignAnchor c ON c.familyId=b.id"
                    . " LEFT JOIN \Micro\Models\UserInfo d ON d.uid=a.uid"
                    . " LEFT JOIN \Micro\Models\UserProfiles e ON e.uid=a.uid"
                    . " WHERE b.creatorUid = " . $this->uid . " AND a.type=".$this->config->applyType->family." AND b.status=1 {$where} group by a.id "
                    . $order
                    . " LIMIT {$limit},{$pageSize}";
            $query = $this->modelsManager->createQuery($phql);
            $applys = $query->execute();

            $applyList = array();
            if ($applys->valid()) {
                foreach ($applys as $val) {
                    $applyData['id'] = $val->id;
                    $applyData['uid'] = $val->uid;
                    $applyData['nickName'] = $val->nickName;
                    $applyData['logo'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $applyData['familyId'] = $val->familyId;
                    $applyData['photo'] = $val->photo;
                    $applyData['anchorLevel'] = $val->level2;
                    $applyData['richerLevel'] = $val->level3;
                    $applyData['createTime'] = $val->createTime ? date('Y-m-d H:i:s',$val->createTime) : '';
                    $applyData['auditTime'] = $val->auditTime ? date('Y-m-d H:i:s',$val->auditTime) : '';
                    $applyData['status'] = $val->status;

                    array_push($applyList, $applyData);
                }
            }
            $applyResult['list'] = $applyList;
            $sql =  "SELECT count(*)count FROM \Micro\Models\ApplyLog a"
                    . " INNER JOIN  \Micro\Models\Family f ON a.targetId=f.id"
                    . " WHERE f.creatorUid = " . $this->uid . " AND a.type=".$this->config->applyType->family." AND f.status=1 {$where} ";// AND al.status=" . $this->config->applyStatus->ing;
            $query = $this->modelsManager->createQuery($sql);
            $count = $query->execute();
            $applyResult['count'] = 0;
            if($count->valid()){
                $applyResult['count'] = $count->toArray()[0]['count'];
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $applyResult);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getAuditingNum(){
        try {
            $count = 0;
            $sql =  "SELECT count(*) as count FROM \Micro\Models\ApplyLog a"
                    . " INNER JOIN  \Micro\Models\Family f ON a.targetId = f.id"
                    . " WHERE f.creatorUid = " . $this->uid . " AND a.type = ".$this->config->applyType->family." AND f.status = 1 AND a.status = 0 ";
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            if($res->valid()){
                $count = $res->toArray()[0]['count'];
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('count'=>$count));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /*
     * 获得我的审核数量
     */

    public function getAuditingCount() {
        try {
            /* $phql = "SELECT count(1) as count FROM \Micro\Models\ApplyLog a"
              . " INNER JOIN  \Micro\Models\Family b ON a.targetId=b.id"
              . " WHERE b.creatorUid = " . $this->uid . " AND a.status=" . $this->config->applyStatus->ing . " LIMIT 1"; */
            $phql = "SELECT count(al.id)count FROM \Micro\Models\ApplyLog al"
                    . " INNER JOIN \Micro\Models\Family f ON f.creatorUid={$this->uid} AND f.status=1 AND al.targetId=f.id "
                    . " WHERE al.status=0";
            $query = $this->modelsManager->createQuery($phql);
            $applys = $query->execute();
            $count = 0;
            if ($applys->valid()) {
                $count = $applys[0]['count'];
            }
            return $count;
        } catch (\Exception $e) {
            $this->errLog('getAuditingCount error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    /*
     * 获得我的审核数量(未处理)
     */

    public function getAuditingUndoCount() {
        try {
            /* $phql = "SELECT count(1) as count FROM \Micro\Models\ApplyLog a"
              . " INNER JOIN  \Micro\Models\Family b ON a.targetId=b.id"
              . " WHERE b.creatorUid = " . $this->uid . " AND a.status=" . $this->config->applyStatus->ing . " LIMIT 1"; */
            $phql = "SELECT count(al.id)count FROM \Micro\Models\ApplyLog al"
                . " INNER JOIN \Micro\Models\Family f ON f.creatorUid={$this->uid} AND f.status=1 AND al.status=0 AND al.targetId=f.id "
                . " WHERE al.status=0";
            $query = $this->modelsManager->createQuery($phql);
            $applys = $query->execute();
            $count = 0;
            if ($applys->valid()) {
                $count = $applys[0]['count'];
            }
            return $count;
        } catch (\Exception $e) {
            $this->errLog('getAuditingCount error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    /*
     * 查询某个申请的信息
     */

    public function getApplyInfo($applyId) {
        try {
            $phql = "SELECT sa.realName,ui.gender,sa.birthday,sa.birth,sa.idCard,sa.telephone,sa.address,sa.accountName,sa.cardNumber,sa.qq,al.id,ui.avatar,ui.nickName,up.level2,al.uid " .
                    " FROM \Micro\Models\ApplyLog al" .
                    " LEFT JOIN \Micro\Models\Family f ON al.targetId=f.id " .
                    " LEFT JOIN \Micro\Models\UserInfo ui ON ui.uid=al.uid " .
                    " LEFT JOIN \Micro\Models\UserProfiles up ON up.uid=al.uid " .
                    " LEFT JOIN \Micro\Models\SignAnchor sa ON sa.uid=al.uid " .
                    " WHERE al.id={$applyId} AND f.creatorUid = {$this->uid}  LIMIT 1";
            $query = $this->modelsManager->createQuery($phql);
            $applys = $query->execute();

            if ($applys->valid()) {
                $photo = UserPhoto::find("uid={$applys[0]['uid']}");
                if ($photo->valid()) {
                    $applyData['avatar'] = $applys[0]['avatar'];
                    if (empty($applyData['avatar'])) {
                        $applyData['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    $applyData['nickName'] = $applys[0]['nickName'];
                    $applyData['anchorLevel'] = $applys[0]['level2'];
                    $applyData['realName'] = $applys[0]['realName'];
                    $applyData['gender'] = $applys[0]['gender'];
                    $applyData['birthday'] = $applys[0]['birthday'];
                    $applyData['birth'] = $applys[0]['birth'];
                    $applyData['idCard'] = $applys[0]['idCard'];
                    $applyData['telephone'] = $applys[0]['telephone'];
                    $applyData['address'] = $applys[0]['address'];
                    $applyData['accountName'] = $applys[0]['accountName'];
                    $applyData['cardNumber'] = $applys[0]['cardNumber'];
                    $applyData['qq'] = $applys[0]['qq'];
                    foreach ($photo as $val) {
                        if ($val->type == $this->config->photoType->lifePhoto) {
                            $applyData['photo']['lifePhoto'][] = $val->photoUrl;
                        } elseif ($val->type == $this->config->photoType->idPhoto) {
                            $applyData['photo']['idPhoto'][] = $val->photoUrl;
                        }
                    }
                }
                return $this->status->retFromFramework($this->status->getCode('OK'), $applyData);
            }
            return $this->status->retFromFramework($this->status->getCode('APPLY_NO_COMPLETE'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 后台审核用户申请
     */
    public function auditApply($applyId, $status, $auditName) {
        return $this->editApply($applyId, $status, $auditName);
    }

    /*
     * 插入申请表
     * @param targetId 当前uid申请的用户uid
     * @param  type 申请类型
     * @return 返回操作结果
     */

    private function addApply($type, $targetId, $description) {
        try {
            $log = new ApplyLog();
            $log->uid = $this->uid;
            $log->targetId = $targetId;
            $log->description = $description;
            $log->type = $type;
            $log->createTime = time();
            $log->status = $this->config->applyStatus->ing;
            $log->isRead = 1; //已读
            $log->save();
            return TRUE;
        } catch (\Exception $e) {
            $this->errLog('addApply error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    /*
     * 审批申请表
     * @param id  applyId
     * @param $status  0:取消，1:通过
     * @param $operator  操作者名称（必须有）
     * @return 返回操作结果
     */

    public function approvalApply($id, $status, $operator, $reason) {
        try {

            $log = ApplyLog::findfirst($id);
            if (empty($log)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            switch ($log->type) {
                case $this->config->applyType->family://加入家族
                    $result = $this->approvalJoinFamilyApply($log, $status, $operator, $reason);
                    break;
                case $this->config->applyType->sign://签约
                    $result = $this->approvalSignApply($log, $status, $operator, $reason);
                    break;
                case $this->config->applyType->createFamily://创建家族
                    $result = $this->approvalCreateFamilyApply($log, $status, $operator, $reason);
                    break;
                default:
                    $result = false;
                    break;
            }

            if (!$result) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            $this->errLog('approvalApply errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //加入家族

    private function approvalJoinFamilyApply($log, $status, $operator, $reason) {
        return false;
    }

    //签约
    private function approvalSignApply($log, $status, $operator, $reason) {
        if (empty($operator)) {
            return false;
        }
        $sign = SignAnchor::findfirst("uid={$log->uid} ORDER BY id DESC");
         // AND status={$this->config->signAnchorStatus->apply}

        if (empty($sign)) {
            return false;
        }

        if ($status == 1) {//通过
            //log 表操作
            $log->status = $this->config->applyStatus->pass;
            $log->auditUser = $operator;
            $log->auditTime = time();
            $log->save();
            //其他操作
            $sign->status = $this->config->signAnchorStatus->normal;
            $sign->createTime = time();
            $sign->save();

            //给用户发送通知
            $sendUser = UserFactory::getInstance($log->uid);
            $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->passAnchorSign, array());
            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
            return true;
        }elseif($status == 3){//拒绝
            $sign->status = $this->config->signAnchorStatus->refuse;
            $sign->save();

            $log->status = $this->config->applyStatus->fail;
            $log->auditUser = $operator;
            $log->reason = $reason;
            $log->auditTime = time();
            $log->save();
            //其他操作
            //给用户发送通知
            $sendUser = UserFactory::getInstance($log->uid);
            $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->failAnchorSign, array());
            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
        }elseif($status == 4){//解约
            /*$sign->status = $this->config->signAnchorStatus->unbind;
            $sign->save();*/

            $log->status = $this->config->applyStatus->unbind;
            $log->auditUser = $operator;
            $log->reason = $reason;
            $log->auditTime = time();
            $log->save();
            //其他操作
            //给用户发送通知
            $sendUser = UserFactory::getInstance($log->uid);
            $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->unbindAnchorSign, array());
            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
        }
            

        return true;
    }

    //创建家族

    private function approvalCreateFamilyApply($log, $status, $operator, $reason) {
        if (empty($operator)) {
            return false;
        }
        $family = Family::findfirst("creatorUid={$log->uid} AND status=0 order by id desc");

        if (empty($family)) {
            return false;
        }
        $sign = SignAnchor::findfirst("uid={$log->uid} AND familyId=0 order by id desc");

        if (empty($sign)) {
            return false;
        }

        if ($status == 1) {//通过
            //log 表操作
            $log->status = $this->config->applyStatus->pass;
            $log->auditUser = $operator;
            $log->auditTime = time();
            $log->save();
            //其他操作
            $family->status = 1;
            $family->createTime = time();
            $family->save();

            $sign->familyId = $family->id;
            $sign->save();
            //log加入家族（家族长）
            $this->familyMgr->familyLogJoin($log->uid, $family->id);

            //给用户发送通知
            $sendUser = UserFactory::getInstance($log->uid);
            $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->passCreateFamily, array(0 => $family->name));
            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);

            return true;
        }
        //拒绝
        //log 表操作
        $log->status = $this->config->applyStatus->fail;
        $log->auditUser = $operator;
        $log->reason = $reason;
        $log->auditTime = time();
        $log->save();
        //其他操作
        //$family->delete();
        //给用户发送通知
        $sendUser = UserFactory::getInstance($log->uid);
        $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->failCreateFamily, array(0 => $family->name));
        $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);

        return true;
    }

    /*
     * 修改申请表
     * @param  applyId 
     * @param  status 申请状态
     * @param  $auditUser 审核者
     * @param  $isRead 是否已读
     * @return 返回操作结果
     */

    private function editApply($applyId, $status, $auditUser = '', $isRead = 0) {
        try {

            $uid = $this->uid;
            $applyInfo = '';
            // 判断该条申请记录是否存在
            if ($status == $this->config->applyStatus->cancel) {//取消申请
                $applyInfo = \Micro\Models\ApplyLog::findFirst("id={$applyId} and uid={$uid} and status=" . $this->config->applyStatus->ing);
            } else if ($status == $this->config->applyStatus->pass) {//申请通过
                $applyInfo = \Micro\Models\ApplyLog::findFirst("id={$applyId} and status=" . $this->config->applyStatus->ing);
            } else if ($status == $this->config->applyStatus->fail) {//拒绝申请
                $applyInfo = \Micro\Models\ApplyLog::findFirst("id={$applyId} and status=" . $this->config->applyStatus->ing);
            }
            if ($applyInfo == FALSE) {
                return FALSE;
            }
            //修改状态
            $applyInfo->status = $status;
            $applyInfo->auditUser = $auditUser;
            $applyInfo->auditTime = time();
            $applyInfo->isRead = $isRead;
            $applyInfo->save();
            return TRUE;
        } catch (\Exception $e) {
            $this->errLog('getApply error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
            return FALSE;
        }
    }

    //阅读通知
    public function readUserApply($ids) {
        try {
            $result = false;
            if ($ids) {
                $idArray = explode(',', $ids);
                foreach ($idArray as $key => $val) {
                    $info = \Micro\Models\ApplyLog::findfirst("id=" . $val . " and uid=" . $this->uid);
                    if ($info != false) {
                        $info->isRead = 1;
                        $result = $info->save();
                    }
                }
                return $result;
            }
        } catch (\Exception $e) {
            $this->errLog('readUserApply error uid=' . $this->uid . ' errorMessage = ' . $e->getMessage());
        }
        return false;
    }

}
