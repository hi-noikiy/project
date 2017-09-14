<?php

namespace Micro\Frameworks\Logic\Investigator;

use Micro\Models\SignAnchor;
use Micro\Models\BasicSalary;
use Micro\Models\Rooms;
use Micro\Frameworks\Logic\User\UserFactory;

//客服后台--主播模块
class InvAnchor extends InvBase {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
        $this->comm = $this->di->get('comm');
    }

    /*
     * 获得主播信息列表
     * @param $isSign 是否签约
     * @param $isFamily是否有家族
     * @param $namelike 昵称查找
     * @param $order 排序
     * @param $currentPage 要查询的页码
     * @param $pageSize  每页显示条数
     */

    public function getAnchorList($isSign = 1, $isFamily = 0, $namelike = '', $order = 0, $currentPage = 1, $pageSize = 10,$richerLow=null,$richerHigh=null,$loginLow=null,$loginHigh=null) {
        try {
            $list = array();
            $count = 0;
            if (!$isSign) {//未签约
                $table = "\Micro\Models\Users u left join \Micro\Models\SignAnchor s on u.uid=s.uid";
                $table.= " inner join \Micro\Models\UserInfo ui on u.uid=ui.uid ";
            } else {//已签约
                $table = "\Micro\Models\SignAnchor s";
                $table.= " inner join \Micro\Models\Users u on s.uid=u.uid "
                        . " inner join \Micro\Models\UserInfo ui on s.uid=ui.uid ";
            }

            $table.=" inner join \Micro\Models\UserProfiles up on ui.uid=up.uid"
                    . " left join \Micro\Models\Rooms r on r.uid=up.uid ";
            
            
            $field = " ui.uid,s.id as signId,ui.nickName,ui.avatar,up.level2,up.exp4,up.richRatio,r.showStatus,r.liveStatus,u.status as userStatus,u.updateTime,s.realName";
            
            if (!$isSign) {//未签约
                $table.= " left join \Micro\Models\RicherConfigs rc on rc.level=up.level3 ";
                $field.=" ,rc.name as richerName ";
            } else {//已签约
                $field.=" ,a.name ";
                $table.= " left join \Micro\Models\AnchorConfigs a on up.level2=a.level ";
            }


            $exp = "1";
            if ($isSign) {//已签约
                $exp.=" AND s.status <> {$this->config->signAnchorStatus->apply} AND s.status <> {$this->config->signAnchorStatus->refuse} AND s.status <> {$this->config->signAnchorStatus->unbind}";
                if ($isFamily) {//有家族
                    $exp.=" AND s.familyId <> 0 ";
                    // $field .= ",s.money as money ";
                } else {//无家族
                    $exp.=" AND s.familyId = 0 ";
                    // $field .= ",up.money as money ";
                }
            } else {//未签约
               /* $uinfo = \Micro\Models\SignAnchor::find("status <> {$this->config->signAnchorStatus->apply} AND status <> {$this->config->signAnchorStatus->refuse} AND status <> {$this->config->signAnchorStatus->unbind}");
                if ($uinfo->valid()) {
                    foreach ($uinfo as $vu) {
                        $uarr[] = $vu->uid;
                    }
                    $ustr = implode(',', $uarr);
                    $exp = "u.uid not in (" . $ustr . ")";
                }*/
                // $field .= ",up.money as money ";
            }
            if ($namelike) {//按uid/昵称查找 增加真实姓名查询 
                $exp.=" AND ((ui.uid like '%" . $namelike . "%') OR (ui.nickName like '%" . $namelike . "%') OR (s.realName like '%" . $namelike . "%')) ";
            }
            if (isset($richerLow)) {//按富豪等级查询
                $exp.=" AND up.level3 >=" . $richerLow;
            }
            if (isset($richerHigh)) {//按富豪等级查询
                $exp.=" AND up.level3 <=" . $richerHigh;
            }
            if (isset($loginLow)) {//按未登录时间查询
                $loginTime = time() - $loginLow * 86400;
                //echo date("Ymd H:i:s",$loginTime);
                //  echo "--";
                $exp.=" AND u.updateTime <" . $loginTime;
            }
            if (isset($loginHigh)) {//按未登录时间查询
                $loginTime = time() - $loginHigh * 86400 - 86400;
                // echo date("Ymd H:i:s",$loginTime);
                // exit;
                $exp.=" AND u.updateTime >" . $loginTime;
            }

            $limit = $pageSize * ( $currentPage - 1);
            //排序
            switch ($order) {
                case 1://主播等级升序
                    $orderby = "up.level2 asc";
                    break;
                case 2://主播等级降序
                    $orderby = "up.level2 desc";
                    break;
                case 3://粉丝升序
                    $orderby = "up.exp4 asc";
                    break;
                case 4://粉丝降序
                    $orderby = "up.exp4 desc";
                    break;
                case 5://收益升序
                    $orderby = " money asc";
                    break;
                case 6://收益降序
                    $orderby = " money desc";
                    break;
                case 7://富豪等级升序
                    $orderby = "up.level3 asc";
                    break;
                case 8://富豪等级降序
                    $orderby = "up.level3 desc";
                    break;
                case 9://登录时间降序
                    $orderby = "u.updateTime desc";
                    break;
                case 10://登录时间升序
                    $orderby = "u.updateTime asc";
                    break;
                case 11://富豪经验倍数升序
                    $orderby = "up.richRatio asc";
                    break;
                case 12://富豪经验倍数降序
                    $orderby = "up.richRatio desc";
                    break;

                default :$orderby = "ui.uid asc";
            }
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $exp . " ORDER BY " . $orderby . " limit " . $limit . ", " . $pageSize;
            //echo $sql;exit;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                $now=time();
                foreach ($result as $val) {
                    $data['id'] = $val->signId;
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['updateTime'] = $val->updateTime;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    if ($isSign) {//主播
                        $data['levelName'] = $val->name;
                    }
                    // $data['cash'] = $val->money;
                    // $data['fans'] = $val->exp4;
                    $data['liveStatus'] = $val->liveStatus == 2 ? "禁播" : "正常";
                    $data['userStatus'] = $val->userStatus == 1 ? "正常" : "禁用";
                    $data['accountStatus'] = "正常";
                    if ($val->showStatus == 1) {
                        $showStatus = "显示";
                    } elseif ($val->showStatus == '') {
                        $showStatus = '无房间';
                    } else {
                        $showStatus = "不显示";
                    }
                    $data['showStatus'] = $showStatus;
                    $data['realName'] = $val->realName;
                    if (!$isSign) {//用户
                        $data['levelName'] = $val->richerName; //富豪等级
                        //未登录天数
                        $data['noLoginDay'] = floor(($now - $val->updateTime) / 86400);
                        //查询最近一次登录的房间
                        $userlogsql = "select ui.nickName,ui.uid from pre_user_log ul inner join pre_user_info ui on ul.uid=ui.uid where ul.uid=" . $val->uid . " order by ul.updateTime desc";
                        $userlogres = $this->db->fetchOne($userlogsql);
                        if ($userlogres) {
                            $data['loginRoom'] = $userlogres['nickName'];
                        } else {
                            $data['loginRoom'] = '';
                        }
                    }
                    $data['richRatio'] = $val->richRatio;//富豪经验倍数
                    array_push($list, $data);
                }
                //统计总数
                $countSql = "SELECT count(1) as count FROM " . $table . " WHERE " . $exp . " limit 1";
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                $count = $countresult[0]['count'];
            }
            $newResult['list'] = $list;
            $newResult['count'] = $count;
            $newResult['order'] = $order;
            return $this->status->retFromFramework($this->status->getCode('OK'), $newResult);
        } catch (\Exception $e) {
            $this->errLog('getAnchorList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //主播详细信息
    public function getAnchorDetail($uid) {
        $info = array();
        try {
            $sql = "select ui.uid, s.status,s.bank, ui.nickName, ui.avatar, up.level2, a.name, s.familyId, s.realName, s.gender, s.cardNumber, s.birth, s.birthday, s.address, s.idCard, s.telephone, s.qq, s.accountName "
                    . " from \Micro\Models\UserInfo ui "
                    . " left join \Micro\Models\SignAnchor s on s.uid = ui.uid "
                    . " inner join \Micro\Models\UserProfiles up on ui.uid = up.uid "
                    . " left join \Micro\Models\AnchorConfigs a on up.level2 = a.level "
                    . " where ui.uid = " . $uid;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                $info['uid'] = $result[0]['uid'];
                $info['levelName'] = $result[0]['name'];
                $info['nickName'] = $result[0]['nickName'];
                $info['level2'] = $result[0]['level2'];
                $info['avatar'] = $result[0]['avatar'];
                if (empty($info['avatar'])) {
                    $info['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }
                $info['familyId'] = $result[0]['familyId'];
                $info['realName'] = $result[0]['realName'];
                $info['gender'] = $result[0]['gender'];
                $info['cardNumber'] = $result[0]['cardNumber'];
                $info['birth'] = $result[0]['birth'];
                $info['birthday'] = date("Y-m-d", $result[0]['birthday']);
                $info['address'] = $result[0]['address'];
                $info['idCard'] = $result[0]['idCard'];
                $info['telephone'] = $result[0]['telephone'];
                $info['qq'] = $result[0]['qq'];
                $info['accountName'] = $result[0]['accountName'];
                $info['status'] = $result[0]['status'];
                $info['signStatus'] = ($result[0]['status'] != $this->config->signAnchorStatus->apply) && ($result[0]['status'] != $this->config->signAnchorStatus->refuse) && ($result[0]['status'] != $this->config->signAnchorStatus->unbind) ? '已签约' : '未签约';
                $info['bank'] = $result[0]['bank'];
                $info['lifePhotoList'] = $this->getAnchorPhoto($uid, $this->config->photoType->lifePhoto);
                $info['idCardPhotoList'] = $this->getAnchorPhoto($uid, $this->config->photoType->idPhoto);
                if ($result[0]['familyId']) {
                    $finfo = \Micro\Models\Family::findfirst($result[0]['familyId']);
                    $info['familyName'] = $finfo->name;
                    $info['familyLogo'] = $finfo->logo;
                }
                $info['familyList'] = array('list' => '', 'count' => 0);
                if (($result[0]['status'] != $this->config->signAnchorStatus->apply) && ($result[0]['status'] != $this->config->signAnchorStatus->refuse)) {//已签约 查询家族履历
                    $info['familyList'] = $this->getAnchorFamilyList($uid);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $info);
        } catch (\Exception $e) {
            $this->errLog('getAnchorDetail error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //主播家族履历
    public function getAnchorFamilyList($uid, $currentPage = 1, $pageSize = 20) {
        try {
            $list = array();
            $count = 0;
            $table = "Micro\Models\FamilyLog fl inner join Micro\Models\Family f on fl.familyId=f.id";
            $exp = "fl.uid=" . $uid;
            $orderby = "fl.joinTime desc";
            $limit = $pageSize * ( $currentPage - 1);
            $sql = "select fl.joinTime,fl.outOfTime,fl.familyId,fl.status,f.logo,f.name from " . $table . " where " . $exp . " order by " . $orderby . " limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                foreach ($result as $val) {
                    $data['joinTime'] = date('Y.m.d', $val->joinTime);
                    $data['outOfTime'] = $val->outOfTime ? date('Y.m.d', $val->outOfTime) : '';
                    $data['familyId'] = $val->familyId;
                    $data['name'] = $val->name;
                    $data['logo'] = $val->logo;
                    $data['cash'] = $this->getAnchorFamilyIncome($uid, $val->familyId);
                    array_push($list, $data);
                }
                //统计总数
                $countSql = "SELECT count(1) as count FROM " . $table . " WHERE " . $exp . " limit 1";
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                $count = $countresult[0]['count'];
            }
            $newResult['list'] = $list;
            $newResult['count'] = $count;
            return $newResult;
        } catch (\Exception $e) {
            $this->errLog('getAnchorFamilyList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

    //主播为家族创造的收益
    private function getAnchorFamilyIncome($uid, $familyId) {
        try {
            $sum = \Micro\Models\ConsumeDetailLog::sum(
                            array("column" => "income", "conditions" => "receiveUid = " . $uid . " and familyId=" . $familyId . " and type < " . $this->config->consumeType->coinType));
            return $sum ? $sum : 0;
        } catch (\Exception $e) {
            $this->errLog('getAnchorFamilyIncome error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //编辑兑换限制的值
    public function setExchangeLimit($limit = 0) {
        try {
            $info = \Micro\Models\BaseConfigs::findfirst("key = 'exchangeLimit'");
            if (!$limit) {//查询兑换限制的值
                if ($info != FALSE) {
                    $limit = $info->value;
                }
                return $limit;
            } else {//修改兑换限制的值
                if ($info) {
                    $log1 = $info->value;
                    $info->value = $limit;
                    $this->addOperate($this->username, '修改', '主播默认兑换下限', '修改主播默认兑换下限', $log1, $limit);
                    return $info->save();
                } else {
                    $info = new \Micro\Models\BaseConfigs();
                    $info->key = 'exchangeLimit';
                    $info->value = $limit;
                    $info->value = $limit;
                    $this->addOperate($this->username, '修改', '主播默认兑换下限', '修改主播默认兑换下限', 0, $limit);
                    return $info->save();
                }
            }
        } catch (\Exception $e) {
            $this->errLog('setExchangeLimit error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return 0;
    }

    //例外用户列表
    public function getExceptionList($type, $namelike = '', $currentPage = 1, $pageSize = 10) {
        $return = array();
        try {
            if($type == $this->config->exceptionType->robotSkip){
                $sqlStart = "SELECT ue.id, ue.uid, ui.nickName, ui.avatar, ue.type FROM \Micro\Models\AnchorJump ue ";
                $where = ' WHERE 1 = 1 ';
            }else{
                $sqlStart = "SELECT ue.id, ue.uid, ue.value, ui.nickName, ui.avatar FROM \Micro\Models\InvUserException ue ";
                $where = " WHERE ue.type = " . $type;
            }
                    //"SELECT ue.id, ue.uid, ue.value, ui.nickName, ui.avatar "
                    //" FROM \Micro\Models\InvUserException ue "
            $sql =  $sqlStart . " INNER JOIN \Micro\Models\UserInfo ui on ue.uid = ui.uid " . $where;
                    // . " WHERE ue.type = " . $type;
            $namelike && $sql.=" AND ((ui.uid like '%" . $namelike . "%' OR ui.nickName like'%" . $namelike . "%' ))";
            $sql.=" ORDER BY ue.uid ASC ";
            $sql.=" LIMIT " . ($currentPage - 1) * $pageSize . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            // var_dump($result);
            // exit;
            if ($result->valid()) {
                $list = array();
                foreach ($result as $val) {
                    $data['id'] = $val->id;
                    $data['uid'] = $val->uid;
                    $data['value'] = isset($val->value) ? $val->value : 0;
                    if($type == $this->config->exceptionType->robotSkip){
                        $typeArr = $this->config->jumpRatio;
                        $data['type'] = $typeArr[$val->type][0];
                    }
                    $data['nickName'] = $val->nickName;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    array_push($list, $data);
                }
                $return['list'] = $list;

                if($type == $this->config->exceptionType->robotSkip){
                    $count = \Micro\Models\AnchorJump::count();
                    $return['count'] = $count;
                    return $this->status->retFromFramework($this->status->getCode('OK'), $return);
                }

                if (!$namelike) {
                    $count = \Micro\Models\InvUserException::count("type = " . $type);
                } else {

                    $countSql = "SELECT COUNT(1)"
                            . " FROM \Micro\Models\InvUserException ue "
                            . " INNER JOIN \Micro\Models\UserInfo ui on ue.uid = ui.uid "
                            . " WHERE type = " . $type . " AND ((ui.uid like '%" . $namelike . "%' OR ui.nickName like'%" . $namelike . "%' )) LIMIT 1";
                    $countQuery = $this->modelsManager->createQuery($countSql);
                    $countResult = $countQuery->execute();
                    $count = $countResult[0]['count'];
                }
                $return['count'] = $count;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            $this->errLog('getexceptionList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //修改例外  
    public function setExceptionInfo($id, $value) {
        if ($this->setException($id, $value)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), '');
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //删除例外
    public function delExceptionInfo($id) {
        if ($this->delException($id)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), '');
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //申请详情
    public function getApplyInfo($applyId) {
        $info = array();
        try {
            $sql = "select al.id, al.uid,al.status as applyStatus,al.createTime,al.auditUser,al.auditTime, ui.nickName, ui.avatar, up.level2,up.exp4, a.name,s.realName, s.gender, s.cardNumber, s.bank, s.birth, s.birthday, s.address, s.idCard, s.telephone, s.qq, s.accountName,up.cash,up.money "
                    . " from \Micro\Models\ApplyLog al "
                    . " left join \Micro\Models\SignAnchor s on al.uid = s.uid "
                    . " inner join \Micro\Models\UserInfo ui on al.uid=ui.uid "
                    . " inner join \Micro\Models\UserProfiles up on al.uid = up.uid "
                    . " left join \Micro\Models\AnchorConfigs a on up.level2 = a.level "
                    . " where al.id= " . $applyId . " and type=" . $this->config->applyType->sign;

            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                $info['id'] = $result[0]['id'];
                $info['uid'] = $result[0]['uid'];
                $info['levelName'] = $result[0]['name'];
                $info['nickName'] = $result[0]['nickName'];
                $info['avatar'] = $result[0]['avatar'];
                if (empty($info['avatar'])) {
                    $info['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }
                $info['realName'] = $result[0]['realName'];
                $info['gender'] = $result[0]['gender'];
                $info['cardNumber'] = $result[0]['cardNumber'];
                $info['bank'] = $result[0]['bank'];
                $info['birth'] = $result[0]['birth'];
                $info['birthday'] = date("Y-m-d", $result[0]['birthday']);
                $info['address'] = $result[0]['address'];
                $info['idCard'] = $result[0]['idCard'];
                $info['telephone'] = $result[0]['telephone'];
                $info['qq'] = $result[0]['qq'];
                $info['accountName'] = $result[0]['accountName'];
                $info['applyStatus'] = $result[0]['applyStatus'];
                $info['auditUser'] = $result[0]['auditUser'];
                $info['createTime'] = date('Y-m-d H:i:s', $result[0]['createTime']);
                $info['auditTime'] = date('Y-m-d H:i:s', $result[0]['auditTime']);
                $info['lifePhotoList'] = $this->getAnchorPhoto($result[0]['uid'], $this->config->photoType->lifePhoto);
                $info['idCardPhotoList'] = $this->getAnchorPhoto($result[0]['uid'], $this->config->photoType->idPhoto);
                // $info['income'] = $result[0]['money'];
                // $info['fans'] = $result[0]['exp4'];
                $playTime = $this->getAnchorPlayTime($result[0]['uid']);
                $info['playTime'] = $this->secToTime($playTime);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $info);
        } catch (\Exception $e) {
            $this->errLog('getApplyInfo error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //获取自动跳转主播
    public function getAnchorJumpList($isSign = 1, $namelike = '', $page = 1, $pageSize = 10, $uids = ''){
        try {
            $isSign = 0;
            $uidstring = '';
            $anchorJumps = \Micro\Models\AnchorJump::find();
            if ($anchorJumps->valid()) {
                foreach ($anchorJumps as $v) {
                    $ajUids[] = $v->uid;
                }
                $uidstring = implode(',', $ajUids);
                $uids && $uidstring = $uidstring . ',' . $uids;
            } else {
                $uidstring = $uids;
            }

            $list = array();
            $count = 0;
            if($isSign){
                $sql = 'select sa.uid,ui.nickName,ui.avatar,r.liveStatus,r.roomId from \Micro\Models\SignAnchor as sa, \Micro\Models\UserInfo as ui, \Micro\Models\Rooms as r ';
                $sql .= ' where sa.uid = ui.uid and sa.uid = r.uid and sa.status <> '.$this->config->signAnchorStatus->apply.' AND sa.status <> '.$this->config->signAnchorStatus->refuse.' AND sa.status <> '.$this->config->signAnchorStatus->unbind;
                if($uidstring) {
                    $sql .= ' AND sa.uid not in (' . $uidstring . ') ';
                }
                $query = $this->modelsManager->createQuery($sql);
                $result = $query->execute();
                $count = count($result);
                foreach ($result as $key => $val) {
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['liveStatus'] = $val->liveStatus;
                    $data['roomId'] = $val->roomId;
                    $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    array_push($list, $data);
                }
            }else{
                $sql = 'select ui.uid,ui.nickName,ui.avatar,r.liveStatus from pre_user_info as ui, pre_rooms as r ';
                $sql .= 'where not exists (select sa.uid from pre_sign_anchor as sa where sa.status <> '.$this->config->signAnchorStatus->apply.' AND sa.status <> '.$this->config->signAnchorStatus->refuse.' AND sa.status <> '.$this->config->signAnchorStatus->unbind .')';
                echo $sql;
                die;
            }
            var_dump($list);
            die;

            $newresult['list'] = $list;
            $newresult['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $newresult);

        } catch (\Exception $e) {
            $this->errLog('getAnchorJumpList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    public function getJumpAnchors($isSign = 1, $namelike = '', $currentPage = 1, $pageSize = 10, $uids = ''){
        try {
            $anchorJumps = \Micro\Models\AnchorJump::find();
            $uidstring = '';
            if ($anchorJumps->valid()) {
                foreach ($anchorJumps as $v) {
                    $ajUids[] = $v->uid;
                }
                $uidstring = implode(',', $ajUids);
            }
            if($uids){
                $uidstring .= ',' . $uids;
            }
            $limit = $pageSize * ( $currentPage - 1);
            $list = array();

            $exp = ' 1 ';

            if ($isSign) {//已签约
                if ($namelike) {//按昵称查找
                    $exp .= " and ((s.uid like '%" . $namelike . "%') OR (ui.nickName like '%" . $namelike . "%')) ";
                }
                $sql = 'select s.uid, ui.nickName, ui.avatar from \Micro\Models\SignAnchor as s left join \Micro\Models\UserInfo ui on s.uid = ui.uid '
                       . ' where ' . $exp . ' and s.status <> ' . $this->config->signAnchorStatus->apply . ' and s.status <> ' . $this->config->signAnchorStatus->refuse . ' and s.status <> ' . $this->config->signAnchorStatus->unbind
                       . ' and s.uid not in (' . $uidstring . ')' . ' order by s.uid asc limit ' . $limit . ',' . $pageSize;
                $sqlCount = 'select count(1) as count from \Micro\Models\SignAnchor as s left join \Micro\Models\UserInfo ui on s.uid = ui.uid '
                       . ' where ' . $exp . ' and s.status <> ' . $this->config->signAnchorStatus->apply . ' and s.status <> ' . $this->config->signAnchorStatus->refuse . ' and s.status <> ' . $this->config->signAnchorStatus->unbind
                       . ' and s.uid not in (' . $uidstring . ')';
            } else {//未签约
                $uinfo = \Micro\Models\SignAnchor::find("status <> {$this->config->signAnchorStatus->apply} AND status <> {$this->config->signAnchorStatus->refuse} AND status <> {$this->config->signAnchorStatus->unbind}");
                $ustr = '';
                if ($uinfo->valid()) {
                    foreach ($uinfo as $vu) {
                        $uarr[] = $vu->uid;
                    }
                    $ustr = implode(',', $uarr);
                }
                if($ustr){
                    $uidstring .= ',' . $ustr;
                }
                if ($namelike) {//按昵称查找
                    $exp .= " and ((ui.uid like '%" . $namelike . "%') OR (ui.nickName like '%" . $namelike . "%')) ";
                }
                $sql = 'select ui.uid, ui.nickName, ui.avatar from \Micro\Models\UserInfo ui '
                       . ' where ' . $exp . ' and ui.uid not in (' . $uidstring . ')' . ' order by ui.uid asc limit ' . $limit . ',' . $pageSize;
                $sqlCount = 'select count(1) as count from \Micro\Models\UserInfo ui '
                       . ' where ' . $exp . ' and ui.uid not in (' . $uidstring . ')';
            }
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $count = 0;
            if (!$this->is_empty($result)) {
                foreach ($result as $val) {
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    array_push($list, $data);
                }
                //统计总数
                $queryCount = $this->modelsManager->createQuery($sqlCount);
                $resultCount = $queryCount->execute();
                // var_dump($resultCount->toArray());die;
                $count = $resultCount[0]['count'];
            }
            

            $newresult['list'] = $list;
            $newresult['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $newresult);
        } catch (\Exception $e) {
            $this->errLog('getExcAnchorList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    // 主播列表 添加例外时选择用的
    public function getExcAnchorList($isSign = 1, $namelike = '', $currentPage = 1, $pageSize = 10, $type = 1, $uids = '') {
        $newresult = array();
        try {
            $uidstring = '';
            //排除已选择的uid
            if($type == $this->config->exceptionType->robotSkip){
                $anchorJumps = \Micro\Models\AnchorJump::find();
                if ($anchorJumps->valid()) {
                    foreach ($anchorJumps as $v) {
                        $ajUids[] = $v->uid;
                    }
                    $uidstring = implode(',', $ajUids);
                    $uids && $uidstring = $uidstring . ',' . $uids;
                } else {
                    $uidstring = $uids;
                }
            }else{
                $exceptionResult = \Micro\Models\InvUserException::find('type=' . $type);
                if ($exceptionResult->valid()) {
                    foreach ($exceptionResult as $v) {
                        $exUids[] = $v->uid;
                    }
                    $uidstring = implode(',', $exUids);
                    $uids && $uidstring = $uidstring . ',' . $uids;
                } else {
                    $uidstring = $uids;
                }
            }
                
            $list = array();
            $table = "\Micro\Models\SignAnchor s inner join \Micro\Models\UserInfo ui on s.uid = ui.uid ";
            $field = "s.uid, ui.nickName, ui.avatar ";
            $exp = "1";
            if ($isSign) {//已签约
                $exp.=" AND s.status <> {$this->config->signAnchorStatus->apply} AND s.status <> {$this->config->signAnchorStatus->refuse} AND s.status <> {$this->config->signAnchorStatus->unbind}";
            } else {//未签约
                $exp.=" AND (s.status = {$this->config->signAnchorStatus->apply} OR s.status = {$this->config->signAnchorStatus->refuse} OR s.status <> {$this->config->signAnchorStatus->unbind})";
            }
            $uidstring && $exp.=" AND s.uid not in (" . $uidstring . ")";

            if ($namelike) {//按昵称查找
                $exp.=" AND ((s.uid like '%" . $namelike . "%') OR (ui.nickName like '%" . $namelike . "%')) ";
            }
            $limit = $pageSize * ( $currentPage - 1);
            $order = "s.uid ASC";
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $exp . " ORDER BY " . $order . " limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                foreach ($result as $val) {
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    array_push($list, $data);
                }
                //统计总数
                $count = 0;
                $countSql = "SELECT count(1) as count FROM " . $table . " WHERE " . $exp . " limit 1";
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                $count = $countresult[0]['count'];
            }
            $newresult['list'] = $list;
            $newresult['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $newresult);
        } catch (\Exception $e) {
            $this->errLog('getExcAnchorList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //修改主播申请状态
    public function editApplyStatus($applyId, $uid, $status, $reason) {
        //添加日志记录
        $applyInfo = \Micro\Models\ApplyLog::findfirst($applyId);
        if ($status == 1) {
            $log2 = '同意';
        } elseif($status == 3) {
            $log2 = '拒绝';
        }else{
            $log2 = '解约';
        }
        if ($applyInfo->type == 2) {//审核签约主播
            if($status == 4){
                $this->addOperate($this->username, '修改', "主播,uid" . $uid, "签约", '解约', $log2);
            }else{
                $this->addOperate($this->username, '修改', "主播,uid" . $uid, "审核签约申请", '申请中', $log2);
            }
            
        } elseif ($applyInfo->type == 3) {//审核创建家族
            $familyInfo = \Micro\Models\Family::findfirst($applyInfo->targetId);
            $this->addOperate($this->username, '修改', "家族," . $familyInfo->name, "审核创建申请", '申请中', $log2);
        }
        $anchorUser = UserFactory::getInstance($uid);
        return $anchorUser->getUserApplyObject()->approvalApply($applyId, $status, $this->username, $reason);
    }

    //主播例外用户新增
    public function addException($uids = '', $value = 0, $type = 1) {
        try {
            if (!empty($uids)) {
                $uidArray = explode(',', $uids);
                foreach ($uidArray as $val) {
                    $model = new \Micro\Models\InvUserException();
                    $model->uid = $val;
                    $model->value = $value;
                    $model->type = $type;
                    $result = $model->save();
                }
                if ($result) {
                    //添加日志记录
                    if ($type == $this->config->exceptionType->bonus) {
                        $value = $value . "%";
                        foreach ($uidArray as $val) {
                            $this->addOperate($this->username, '新增', "主播,uid" . $val, "分成比例设为例外", '', $value);
                        }
                    } elseif ($type == $this->config->exceptionType->exchange) {
                        foreach ($uidArray as $val) {
                            $this->addOperate($this->username, '新增', "主播,uid" . $val, "兑换下限设为例外", '', $value);
                        }
                    } elseif ($type == $this->config->exceptionType->robotSkip) {
                        $roomIds = '';
                        foreach ($uidArray as $val) {
                            $roominfo = \Micro\Models\Rooms::findfirst("uid=" . $val);
                            $roomIdArr[] = $roominfo->roomId;
                        }
                        $roomIds = implode(',', $roomIdArr);
                        $this->addOperate($this->username, '新增', "直播间自动跳转", "添加房间{$roomIds}", '', '');
                    }

                    return $this->status->retFromFramework($this->status->getCode('OK'), '');
                }
            }
        } catch (\Exception $e) {
            $this->errLog('addException error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //添加跳转主播池
    public function addAnchorJump($uids = '', $type = 0) {
        try {
            if (!empty($uids)) {
                $uidArray = explode(',', $uids);
                foreach ($uidArray as $val) {
                    $model = new \Micro\Models\AnchorJump();
                    $model->uid = $val;
                    $model->type = $type;
                    $result = $model->save();
                }
                if ($result) {
                    //添加日志记录
                    $roomIds = '';
                    foreach ($uidArray as $val) {
                        $roominfo = \Micro\Models\Rooms::findfirst("uid=" . $val);
                        $roomIdArr[] = isset($roominfo->roomId) ? $roominfo->roomId : '';
                    }
                    $roomIds = implode(',', $roomIdArr);
                    $this->addOperate($this->username, '新增', "跳转主播池", "添加房间{$roomIds}", '', '');
                    return $this->status->retFromFramework($this->status->getCode('OK'), '');
                }
            }
        } catch (\Exception $e) {
            $this->errLog('addAnchorJump error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //主播例外修改时 查询
    public function getOneExceptin($id) {
        try {
            $info = \Micro\Models\InvUserException::findfirst($id);
            if ($info == FALSE) {
                return;
            }
            $uinfo = \Micro\Models\UserInfo::findfirst($info->uid);
            $result = array();
            $result['id'] = $id;
            $result['avatar'] = $uinfo->avatar;
            if (empty($result['avatar'])) {
                $result['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
            }
            $result['uid'] = $uinfo->uid;
            $result['value'] = $info->value;
            $result['nickName'] = $uinfo->nickName;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            $this->errLog('getOneExceptin error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //冻结/解冻主播状态
    public function setAnchorStatus($uid, $status) {
        try {
            $info = \Micro\Models\SignAnchor::findfirst($uid);
            if ($info == FALSE) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'), '');
            }
            if ($status == $this->config->signAnchorStatus->forzen) {//冻结
                if ($info->familyId) {//判断是否是家族主播
                    $finfo = \Micro\Models\Family::findfirst($info->familyId);
                    if ($uid == $finfo->creatorUid) {
                        return $this->status->retFromFramework($this->status->getCode('USER_CAN_NOT_OPER'), '');
                    }
                }
                $info->status = $status;
                $result = $info->save();
            } elseif ($status == $this->config->signAnchorStatus->normal) {//解冻
                $info->status = $status;
                $result = $info->save();
            }
            if ($result) {
                return $this->status->retFromFramework($this->status->getCode('OK'), '');
            }
        } catch (\Exception $e) {
            $this->errLog('setAnchorStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //主播详情--收益--个人收益
    public function getAnchorIncomeInfo($uid) {
        $result = array();
        //主播状态
        $result['statusResult'] = $this->checkAnchorStatus($uid);
        //总收益
        $result['income'] = $this->checkAnchorIncomeSum($uid);
        //可结算收益
        $result['moneyStatus'] = $this->checkAnchorMoneyStatus($uid);
        //查询分成比例
        $result['isBounsException'] = 0;
        $exInfo = \Micro\Models\InvUserException::findfirst("type=" . $this->config->exceptionType->bonus . " and uid=" . $uid); //查询是否为例外用户
        if ($exInfo != false) {
            $result['bouns'] = $exInfo->value;
            $result['bounsId'] = $exInfo->id;
            $result['isBounsException'] = 1;
        } else {
            $result['bouns'] = $this->checkRuleByOne($this->config->ruleType->anchorBonus, $uid);
        }
        // var_dump($result);
        // exit;

        //主播兑换下限
        $result['isLimitException'] = 0;
        $exInfo = \Micro\Models\InvUserException::findfirst("type=" . $this->config->exceptionType->exchange . " and uid=" . $uid); //查询是否为例外用户
        if ($exInfo != false) {
            $result['limit'] = $exInfo->value;
            $result['limitId'] = $exInfo->id;
            $result['isLimitException'] = 1;
        } else {
            $result['limit'] = $this->setExchangeLimit(0);
        }

        return $result;
    }

    //某段日期内某主播收入
    public function getAnchorIncomeList($type = 'day', $uid, $startDate, $endDate, $isFamily = 0) {
        switch ($type) {
            case 'week'://按周统计
                $dataFormat = '%Y%u';
                !$endDate && $endDate = date("Y-m-d 23:59:59"); //默认为今天
                !$startDate && $startDate = date("Y-m-d", strtotime($endDate) - 604800); //默认为一周前
                break;
            case 'month':   //按月统计
                $dataFormat = "%Y%m";
                !$endDate && $endDate = date("Y-m-d 23:59:59"); //默认为今天
                !$startDate && $startDate = date("Y-m-d", strtotime($endDate) - 2592000); //默认为30天前
                break;
            default ://按天统计
                $dataFormat = "%Y%m%d";
                !$endDate && $endDate = date("Y-m-d 23:59:59"); //默认为今天
                !$startDate && $startDate = date("Y-m-d", strtotime($endDate) - 604800); //默认为7天前
        }
        $starttime = strtotime($startDate);
        $endtime = strtotime($endDate);
        try {
            $familyId = 0;
            if ($isFamily) {//主播家族收益
                $familyInfo = \Micro\Models\SignAnchor::findfirst("uid=" . $uid);
                if ($familyInfo && $familyInfo->familyId) {//如果有加入家族
                    $familyId = $familyInfo->familyId;
                }
            }
            $sql = "select DATE_FORMAT(from_unixtime(createTime), '{$dataFormat}') as time, sum(income) as sum "
                    . " from \Micro\Models\ConsumeLog "
                    . " where anchorId = " . $uid . " and familyId= " . $familyId
                    . " and type < " . $this->config->consumeType->coinType . " "
                    . " and createTime > '" . $starttime . "' and createTime < '" . $endtime . "' "
                    . " group by time";
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $newResult = $this->getDataByDates($type, $result, $startDate, $endDate);
            return $this->status->retFromFramework($this->status->getCode('OK'), $newResult);
        } catch (\Exception $e) {
            $this->errLog('getAnchorIncomeList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //主播的家族收益信息、排行
    public function getAnchorFamilyIncomeDetail($uid) {
        $result = array();
        $familyInfo = \Micro\Models\SignAnchor::findfirst("uid=" . $uid);
        if ($familyInfo && $familyInfo->familyId) {//如果有加入家族
            $result['familyIncome'] = $this->getAnchorFamilyIncome($uid, $familyInfo->familyId); //主播为家族创造的收益
            $result['rankInfo'] = $this->getAnchorFamilyRank($uid, $familyInfo->familyId); //主播在家族中的排行
        }
        return $result;
    }

    //主播收益在家族中的排行
    public function getAnchorFamilyRank($uid, $familyId) {
        $return = array();
        try {
            $connection = $this->di->get('db');
            //总榜：主播在当前家族创造的总收益在家族中的排名
            $sql_all = "select rank   
                from (select t.*,@rownum:=@rownum+1 as rank 
                      from (SELECT uid,sum(money) as total FROM pre_sign_anchor WHERE familyId=" . $familyId . " GROUP BY uid ORDER BY total desc) t, (SELECT @rownum:=0) r ) k
                where uid=" . $uid;
            $result_all = $connection->fetchOne($sql_all);
            //本月：主播本月在当前家族创造的收益在家族中的排名
            $sql_month = "select rank   
                from (select t.*,@rownum:=@rownum+1 as rank 
                      from (SELECT uid,sum(money) as total FROM pre_sign_anchor WHERE familyId=" . $familyId . " AND  FROM_UNIXTIME(createTime, '%Y%m')=DATE_FORMAT(CURDATE() ,'%Y%m')  GROUP BY uid ORDER BY total desc) t, (SELECT @rownum:=0) r ) k
                where uid=" . $uid;
            $result_month = $connection->fetchOne($sql_month);
            //本周：主播本周在当前家族创造的收益在家族中的排名
            $sql_week = "select rank   
                from (select t.*,@rownum:=@rownum+1 as rank 
                      from (SELECT uid,sum(money) as total FROM pre_sign_anchor WHERE familyId=" . $familyId . " AND YEARWEEK(FROM_UNIXTIME(createTime,'%Y-%m-%d')) = YEARWEEK(now()) GROUP BY uid ORDER BY total desc) t, (SELECT @rownum:=0) r ) k
                where uid=" . $uid;
            $result_week = $connection->fetchOne($sql_week);
            $return['rank_all'] = $result_all['rank'] ? $result_all['rank'] : 0;
            $return['rank_week'] = $result_week['rank'] ? $result_week['rank'] : 0;
            $return['rank_month'] = $result_month['rank'] ? $result_month['rank'] : 0;
        } catch (\Exception $e) {
            $this->errLog('getAnchorFamilyRank error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $return;
    }

    // 主播列表 结算收益时选择用的
    public function getAccountAnchorList($namelike = '', $currentPage = 1, $pageSize = 10) {
        $exchangeLimit = $this->setExceptionLimit(0); //兑换下限
        try {
            $list = array();
            $count = 0;
            $table = "\Micro\Models\SignAnchor s inner join \Micro\Models\UserInfo ui on s.uid = ui.uid ";
            $field = "s.uid, ui.nickName, ui.avatar ";
            $exp = "1";
            $exp.=" AND s.status <> " . $this->config->signAnchorStatus->apply;

            if ($namelike) {//按昵称查找
                $exp.=" AND ((s.uid like '%" . $namelike . "%') OR (ui.nickName like '%" . $namelike . "%')) ";
            }
            $limit = $pageSize * ( $currentPage - 1);
            $order = "s.uid ASC";
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $exp . " ORDER BY " . $order . " limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                foreach ($result as $val) {
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    array_push($list, $data);
                }
                //统计总数
                $countSql = "SELECT count(1) as count FROM " . $table . " WHERE " . $exp . " limit 1";
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                $count = $countresult[0]['count'];
            }

            $newResult['list'] = $list;
            $newResult['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $newResult);
        } catch (\Exception $e) {
            $this->errLog('getAccountAnchorList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //主播、家族结算记录列表 type=1查看主播的 =2查看家族的
    public function getAnchorAccountList($id, $type = 1, $currentPage = 1, $pageSize = 10) {
        try {
            $list = array();
            $newResult = array();
            $count = 0;
            $exp = "l.uid=" . $id . " and l.type=" . $type;
            $limit = $pageSize * ( $currentPage - 1);
            $field = "l.cash,l.auditUser,l.status,a.createTime,a.applyUser";
            $table = "\Micro\Models\InvAccountsLog l inner join \Micro\Models\InvAccounts a on l.accountId=a.id ";
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $exp . " ORDER BY l.id desc limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                foreach ($result as $val) {
                    $data['createTime'] = date("Y-m-d H:i:s", $val->createTime);
                    $data['cash'] = $val->cash;
                    $data['applyUser'] = $val->applyUser;
                    //$data['createTime'] = $val->createTime;
                    $data['auditUser'] = $val->auditUser;
                    $data['status'] = $val->status;
                    array_push($list, $data);
                }
                $count = \Micro\Models\InvAccountsLog::count("uid=" . $id . " and type=" . $type);
            }
            $newResult['list'] = $list;
            $newResult['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $newResult);
        } catch (\Exception $e) {
            $this->errLog('getAnchorList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //主播例外用户修改
    private function setException($id, $value = 0) {
        try {
            $info = \Micro\Models\InvUserException::findfirst($id);
            if ($info == FALSE) {
                return FALSE;
            } else {
                $log1 = $info->value;
                $uid = $info->uid;
                $info->value = $value;
                $result = $info->save();
                if ($result) {
                    //添加日志记录
                    if ($info->type == $this->config->exceptionType->bonus) {
                        $value = $value . "%";
                        $log1 = $log1 . "%";
                        $this->addOperate($this->username, '修改', '主播,uid' . $uid, "修改分成比例", $log1, $value);
                    } elseif ($info->type == $this->config->exceptionType->exchange) {
                        $this->addOperate($this->username, '修改', '主播,uid' . $uid, "修改兑换下限", $log1, $value);
                    }

                    return true;
                }
            }
        } catch (\Exception $e) {
            $this->errLog('setException error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return false;
    }

    //主播例外删除
    private function delException($id) {
        try {
            $info = \Micro\Models\InvUserException::findfirst($id);
            if ($info != false) {
                $log1 = $info->value;
                $type = $info->type;
                $uid = $info->uid;
                if ($info->delete() != false) {
                    //添加日志记录
                    if ($type == $this->config->exceptionType->bonus) {
                        $operaDesc = "删除分成比例的例外";
                        $value = $log1 . "%";
                        $log1 = $log1 . "%";
                        $this->addOperate($this->username, '删除', '主播,uid' . $uid, $operaDesc, $log1, '');
                    } elseif ($type == $this->config->exceptionType->exchange) {
                        $this->addOperate($this->username, '删除', '主播,uid' . $uid, '删除兑换下限的例外', $log1, '');
                    } elseif ($type == $this->config->exceptionType->robotSkip) {
                        $roominfo = \Micro\Models\Rooms::findfirst("uid=" . $uid);
                        $this->addOperate($this->username, '删除', '直播间自动跳转', "删除房间{$roominfo->roomId}", '', '');
                    }

                    return true;
                }
            }
        } catch (\Exception $e) {
            $this->errLog('delException error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return false;
    }

    public function delAnchorJump($id){
        try {
            $info = \Micro\Models\AnchorJump::findfirst($id);
            if(empty($info)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'), '');
            }
            $anchorId = $info->uid;
            $type = $info->type;
            if($info->delete() != false){
                $roominfo = \Micro\Models\Rooms::findfirst("uid=" . $anchorId);
                $roomId = isset($roominfo->roomId) ? $roominfo->roomId : '';

                $this->addOperate($this->username, '删除', '直播间自动跳转', "删除房间{$roomId}", '', '');
                return $this->status->retFromFramework($this->status->getCode('OK'), '');
            }
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        } catch (\Exception $e) {
            $this->errLog('delAnchorJump error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //查询主播状态 冻结\解冻
    private function checkAnchorStatus($uid) {
        $result['status'] = 2; //冻结
        try {
            $info = \Micro\Models\SignAnchor::findfirst($uid);
            if ($info != false) {
                if ($info->status == $this->config->signAnchorStatus->normal) {//正常
                    $result['status'] = 1;
                    return $result;
                }
                //判断是否可解冻
                $finfo = \Micro\Models\Family::findfirst($info->familyId);
                if ($finfo != FALSE && $info->uid != $finfo->creatorUid) {//不是家族长
                    $result['canForzen'] = 1; //可解冻
                }
            }

            return $result;
        } catch (\Exception $e) {
            $this->errLog('checkAnchorStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return false;
        }
    }

    //查询主播可结算聊币、结算状态
    private function checkAnchorMoneyStatus($uid) {
        $result = array('status' => 0, 'money' => 0);
        try {
            $find = \Micro\Models\UserProfiles::findfirst($uid);
            $money = $find->money; //可结算的聊币
            $result['money'] = $money;
            if ($money > 0) {
                $info = \Micro\Models\SignAnchor::findfirst("uid=" . $uid);
                if ($info != false && !$info->familyId) {//已签约 并且 没有加入家族
                    $limit = $this->getAnchorExchangeLimit($uid); //查询兑换下限
                    if ($money >= $limit) {
                        $result['status'] = 1; //可结算
                        return $result;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->errLog('checkAnchorMoneyStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $result;
    }

    //查询用户的兑换下限
    public function getAnchorExchangeLimit($uid) {
        try {
            //查询是否是例外用户
            $info = \Micro\Models\InvUserException::findfirst("uid=" . $uid . " and type=" . $this->config->exceptionType->exchange);
            if ($info != false) {
                return $info->value;
            }
            //系统默认下限
            return $this->setExchangeLimit(0);
        } catch (\Exception $e) {
            $this->errLog('getAnchorExchangeLimit error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return 0;
    }

    //查询某主播总收益
    public function checkAnchorIncomeSum($uid) {
        $sum = 0;
        try {
            $sum = \Micro\Models\ConsumeLog::sum(
                            array("column" => "income", "conditions" => "anchorId = " . $uid . " and familyId=0 and type < " . $this->config->consumeType->coinType));
            return $sum ? $sum : 0;
        } catch (\Exception $e) {
            $this->errLog('checkAnchorIncomeSum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //获得某个主播的总播出时长
    public function getAnchorPlayTime($uid) {
        try {
            $info = \Micro\Models\Rooms::findfirst("uid=" . $uid);
            if ($info == false) {
                return 0;
            }
            $roomId = $info->roomId;
            $sum = \Micro\Models\RoomLog::sum(
                            array("column" => "endTime - publicTime", "conditions" => "roomId = " . $roomId . " and status = 1"));
            return $sum ? $sum : 0;
        } catch (\Exception $e) {
            $this->errLog('getAnchorPlayTime error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //签约申请
    public function anchorApplyList($type = 1, $namelike = '', $page = 5, $pageSize = 20) {
        try {
            $list = array();
            $count = 0;
            $table = "\Micro\Models\ApplyLog a "
                    . " inner join \Micro\Models\UserInfo ui on a.uid = ui.uid"
                    . " inner join \Micro\Models\UserProfiles p on a.uid = p.uid"
                    . " left join \Micro\Models\AnchorConfigs ac on p.level2=ac.level";
            $field = " a.uid, a.id, a.createTime, a.status, ui.nickName, ui.avatar, p.level2, p.money,ac.name,p.exp4";
            $condition = "1 = 1 AND a.type = " . $this->config->applyType->sign;
            switch ($type) {
                case 1:
                    $condition .= " AND a.status = " . $this->config->applyStatus->ing;
                    break;
                case 2:
                    $condition .= "AND a.status <> " . $this->config->applyStatus->ing;
                    break;
                default:
            }

            //搜索名称
            if ($namelike) {
                $condition .= "AND (ui.nickName '%" . $namelike . "%' OR a.uid '%" . $nickName . "%')";
            }

            //分页
            $limit = ($page - 1) * $pageSize;

            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $condition . " ORDER BY a.createTime DESC limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $tempDate = $query->execute();
            if (!$this->is_empty($tempDate)) {
                foreach ($tempDate as $val) {
                    $data['id'] = $val->id;
                    $data['uid'] = $val->uid;
                    $data['avatar'] = $val->avatar;
                    if (empty($data['avatar'])) {
                        $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    $data['nickName'] = $val->nickName;
                    $data['level2'] = $val->level2;
                    $data['levelName'] = $val->name;
                    $data['status'] = $val->status;
                    $data['fans'] = $val->exp4;
                    $data['createTime'] = date('Y-m-d H:i:s', $val->createTime);
                    array_push($list, $data);
                }
                //统计总数
                $countSql = "SELECT count(*) as count FROM " . $table . " WHERE " . $condition;
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                $count = $countresult[0]['count'];
            }

            $result['list'] = $list;
            $result['count'] = $count;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            $this->errLog('anchorApplyList error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //主播 -- 底薪分成 --- 底薪
    public function getAnchorSalary($uid) {
        try {
            $info = array();
            $basicSalary = BasicSalary::findfirst('uid = ' . $uid . ' ORDER BY id DESC');
            if ($basicSalary) {
                $signAnchor = SignAnchor::findfirst('uid = ' . $uid . ' AND status = ' . $this->config->signAnchorStatus->normal);
                $normal = 1;
                if (empty($signAnchor)) {
                    $normal = 0;
                }
                $data['normal'] = $normal;

                //调用自动变更接口
                $invMgrBase = new \Micro\Frameworks\Logic\Investigator\InvBase();
                $result = $invMgrBase->checkAnchorChange($uid); 
                if($result){
                    $data['id'] = $result->id;
                    $data['money'] = $result->money;    //保底薪资
                    $data['type'] = $result->type;
                    // $data['condition'] = $basicSalary->condition;   //发放条件
                    $data['expirationTime'] = $result->expirationTime ? date('Y-m-d', $result->expirationTime) : 0;    //过期时间                
                    $data['changeInfo'] = is_null($basicSalary->changeInfo) ? '' : unserialize($basicSalary->changeInfo);    //过期时间                
                    $data['status'] = $result->status;
                }else{
                    $data['id'] = $basicSalary->id;
                    $data['money'] = $basicSalary->money;    //保底薪资
                    $data['type'] = $basicSalary->type;
                    // $data['condition'] = $basicSalary->condition;   //发放条件
                    $data['expirationTime'] = $basicSalary->expirationTime ? date('Y-m-d', $basicSalary->expirationTime) : 0;    //过期时间                
                    $data['changeInfo'] = is_null($basicSalary->changeInfo) ? '' : unserialize($basicSalary->changeInfo);    //过期时间                
                    $data['status'] = $basicSalary->status;
                }
                    
                array_push($info, $data);
            }
            return $info;
        } catch (\Exception $e) {
            $this->errLog('getAnchorSalary error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //修改信息显示
    public function editInfo($id) {
        try {
            $basicSalary = BasicSalary::findfirst('id = ' . $id . ' ORDER BY id DESC');
            $data = array();
            if ($basicSalary) {
                $data['id'] = $basicSalary->id;
                $data['type'] = $basicSalary->type;       //薪资类型
                $data['expirationTime'] = $basicSalary->expirationTime ? date('Y-m-d', $basicSalary->expirationTime) : 0;       //过期时间
                $data['affectTime'] = $basicSalary->affectTime;       //生效时间
                $data['money'] = $basicSalary->money;
                $data['uid'] = $basicSalary->uid;
                $data['status'] = $basicSalary->status;       //过期类型
            }
            return $data;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //修改底薪
    public function editSalary($id = 0, $data) {

        try {
            if ($id != 0) {//修改时生效时间只能次月生效
                $salaryInfo = BasicSalary::findfirst('id = ' . $id . ' ORDER BY id DESC');
                if ($salaryInfo == false) {
                    return false;
                }
                /*//生效时间
                $nowdate = date('Y-m',time());
                $affectTime = strtotime(date("Y-m-d",strtotime("$nowdate +1 month")));
                $salaryInfo->affectTime = $affectTime;

                $changeInfo = array();
                $changeInfo['affectTime'] = $affectTime;
                $changeInfo['money'] = $data['money'];
                $changeInfo['status'] = $data['status'];
                $changeInfo['expirationTime'] = $data['expirationTime'] ? $data['expirationTime'] : 0;
                $changeInfo['type'] = $data['type'];
                $salaryInfo->changeInfo = serialize($changeInfo);*/
            } else {//新增时判断
                $salaryInfo = new BasicSalary();
                $salaryInfo->uid = $data['uid'];
                
            }
            if($data['affectStatus'] == 1){//次月生效生效
                $nowdate = date('Y-m',time());
                $affectTime = strtotime(date("Y-m-d",strtotime("$nowdate +1 month")));
                $salaryInfo->affectTime = $affectTime;
                $changeInfo = array();
                $changeInfo['affectTime'] = $affectTime;
                $changeInfo['money'] = $data['money'];
                $changeInfo['status'] = $data['status'];
                $changeInfo['expirationTime'] = $data['status'] == 1 ? '' : $data['expirationTime'];
                $changeInfo['type'] = $data['type'];
                $salaryInfo->changeInfo = serialize($changeInfo);
            }else{//立即生效
                $salaryInfo->money = $data['money'];
                $salaryInfo->affectTime = 0;
                $salaryInfo->type = $data['type'];
                $salaryInfo->status = $data['status'];
                $salaryInfo->expirationTime = $data['status'] == 1 ? '' : $data['expirationTime'];
                $salaryInfo->changeInfo = '';
            }

            $result = $salaryInfo->save();
            if ($result) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->errLog('editSalary error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //主播 == 播出时长
    public function getFamilyBroadcastTime($uid, $type, $begin, $end, $isExcel = 0) {
        $list = array();
        try {
            $daynum = array();
            $dayList = array();
            switch ($type) {
                case 'week'://按周统计
                    !$end && $end = date("Y-m-d 23:59:59"); //默认为今天
                    !$begin && $begin = date("Y-m-d", strtotime($end) - 604800); //默认为一周前
                    break;
                case 'month':   //按月统计
                    !$end && $end = date("Y-m-d 23:59:59"); //默认为今天
                    !$begin && $begin = date("Y-m-d", strtotime($end) - 2592000); //默认为30天前
                    break;
                default ://按天统计
                    !$end && $end = date("Y-m-d 23:59:59"); //默认为今天
                    !$begin && $begin = date("Y-m-d", strtotime($end) - 604800); //默认为7天前
                    break;
            }

            $startTime = strtotime($begin);
            $endTime = strtotime($end) + 86400;
            $roomModule = $this->di->get('roomModule');
            $roomInfo = \Micro\Models\Rooms::findfirst("uid=" . $uid);
            $timeresult = $roomModule->getRoomOperObject()->getAnchorBroadcastTime($roomInfo->roomId, $startTime, $endTime);
            $newTimeResult = array();
            if ($timeresult['code'] == $this->status->getCode('OK')) {
                foreach ($timeresult['data'] as $key => $val) {
                    $newTimeResult[$key] = $val['sum'];
                }
            }

            $newTimeResults = array();
            switch ($type) {
                case 'week'://按周统计
                    foreach ($newTimeResult as $key => $val) {
                        //转换成周
                        $strtime = strtotime($key);
                        $strweek = date('YW', $strtime);
                        !isset($newTimeResults[$strweek]) && $newTimeResults[$strweek] = 0;
                        $newTimeResults[$strweek]+=$val;
                    }
                    break;
                case 'month':   //按月统计
                    foreach ($newTimeResult as $key => $val) {
                        //转换成周
                        $strtime = strtotime($key);
                        $strmonth = date('Ym', $strtime);
                        !isset($newTimeResults[$strmonth]) && $newTimeResults[$strmonth] = 0;
                        $newTimeResults[$strmonth]+=$val;
                    }
                    break;
                default ://按天统计
                    $newTimeResults = $newTimeResult;
                    break;
            }

            $newResult = $this->getDataByDates($type, $newTimeResults, $begin, $end);
            if ($isExcel) {
                $excelData = array();
                if ($newResult) {
                    foreach ($newResult as $val) {
                        unset($data);
                        $data[] = $val['time'];
                        $data[] = $this->secToTime($val['sum']);
                        array_push($excelData, $data);
                    }
                }
                $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid);
                $headarr = array("日期", "播出时长)");
                $this->getExcel('播出时长_' . $userInfo->nickName . '(' . $uid . ')', $headarr, $excelData);
                exit;
            }

            //print_r($newResult);die;

            $max = 0;
            $min = 0;
            $sum = 0;
            $mean = 0;
            if (count($newResult) > 0) {
                $min = $newResult[0]['sum'];
                $max = $newResult[0]['sum'];
                foreach ($newResult as $val) {
                    if ($max < $val['sum']) {
                        $max = $val['sum'];
                    }
                    if ($min > $val['sum']) {
                        $min = $val['sum'];
                    }

                    $sum += $val['sum'];
                }
                if ($sum != 0) {
                    $mean = floor($sum / count($newResult));
                }
            }

            if (!empty($newResult)) {
                foreach ($newResult as $key => $val) {
                    if ($val['sum'] != 0)
                        $newResult[$key]['sum'] = floor($val['sum'] / 60);
                }
            }

            $result['max'] = $max;
            $result['min'] = $min;
            $result['mean'] = $mean;
            $result['list'] = $newResult;

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (Exception $e) {
            $this->errLog('getFamilyBroadcastTime error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //已签约直播详情
    public function getAnchorInfo($uid) {
        try {
            $sql = "select ui.uid, s.status, ui.nickName, ui.avatar, up.level2, a.name "
                    . " from \Micro\Models\UserInfo ui "
                    . " left join \Micro\Models\SignAnchor s on s.uid = ui.uid "
                    . " inner join \Micro\Models\UserProfiles up on ui.uid = up.uid "
                    . " left join \Micro\Models\AnchorConfigs a on up.level2 = a.level "
                    . " where ui.uid = " . $uid;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if (!$this->is_empty($result)) {
                $data['avatar'] = $result[0]['avatar'] ? $result[0]['avatar'] : $this->pathGenerator->getFullDefaultAvatarPath();
                $data['nickName'] = $result[0]['nickName'];
                $data['uid'] = $result[0]['uid'];
                $data['name'] = $result[0]['name'];
                $data['level2'] = $result[0]['level2'];
                $data['status'] = ($result[0]['status'] != $this->config->signAnchorStatus->apply) && ($result[0]['status'] != $this->config->signAnchorStatus->refuse) && ($result[0]['status'] != $this->config->signAnchorStatus->unbind) ? '已签约' : '未签约';
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            $this->errLog('getAnchorInfo error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //主播 == 贡献
    public function getContributionAnchor($uid, $type, $page, $pageSize) {
        $list = array();
        try {
            $monthStar = strtotime(date('Y-m') . "-01");
            $lastMonthStar = strtotime('-1 month', $monthStar);
            switch ($type) {
                case 'thisMonth':
                    // $timeBegin = $monthStar;
                    // $timeEnd = time();
                    $day = intval(date('d'));
                    if($day >= 11){
                        $timeBegin = strtotime(date('Y-m-11',time()));
                    }else{
                        $timeBegin = strtotime(date('Y-m-11',strtotime('-1 month', strtotime(date('Y-m-01')))));
                        // $timeEnd = strtotime(date('Y-m-10',strtotime('-1 month', strtotime(date('Y-m-01')))));
                    }
                    $timeEnd = time();
                    break;
                case 'lastMonth':
                    // $timeBegin = $lastMonthStar;
                    // $timeEnd = $monthStar;
                    $day = intval(date('d'));
                    if($day >= 11){
                        $timeBegin = strtotime(date('Y-m-11',strtotime('-1 month', strtotime(date('Y-m-01')))));
                        $timeEnd = strtotime(date('Y-m-10'));
                    }else{
                        $timeBegin = strtotime(date('Y-m-11',strtotime('-2 month', strtotime(date('Y-m-01')))));
                        $timeEnd = strtotime(date('Y-m-10',strtotime('-1 month', strtotime(date('Y-m-01')))));
                        // $timeEnd = strtotime(date('Y-m-10',strtotime('-1 month', strtotime(date('Y-m-01')))));
                    }
                    break;
                default:
                //
            }
            $table = "\Micro\Models\ConsumeDetailLog cl left join \Micro\Models\UserInfo ui on ui.uid = cl.uid";
            $field = 'ui.avatar,ui.uid,ui.nickName,sum(cl.income) as income';
            $condition = " cl.createTime BETWEEN " . $timeBegin . " AND " . $timeEnd . " AND cl.type < " . $this->config->consumeType->coinType . " AND income > 0 AND cl.receiveUid = " . $uid . "  group by cl.uid";
            $limit = ($page - 1) * $pageSize;
            $sql = "select " . $field . " from " . $table . " where " . $condition . " order by income desc limit " . $limit . "," . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $tempDate = $query->execute();
            if ($tempDate->valid()) {
                $num = 1 + $limit;
                foreach ($tempDate as $val) {
                    $data['num'] = $num ++;
                    $data['avatar'] = $val->avatar ? $val->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $data['uid'] = $val->uid;
                    $data['nickName'] = $val->nickName;
                    $data['income'] = $val->income;
                    array_push($list, $data);
                }
            }
            //统计总条数
            $count = 0;
            if ($list) {
                $countSql = "SELECT count(1) FROM " . $table . " WHERE " . $condition;
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                $count = count($countresult);
            }
            $result['count'] = $count;
            $result['list'] = $list;
            return $result;
        } catch (\Exception $e) {
            $this->errLog('getContributionAnchor error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //判断是否加入家族
    public function checkAnchorIsFamily($uid) {
        try {
            $info = \Micro\Models\SignAnchor::findfirst("uid=" . $uid);
            if ($info != false) {
                return $info->familyId;
            }
        } catch (\Exception $e) {
            $this->errLog('checkAnchorIsFamily error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return 0;
    }

    //
    public function checkIsFamilyHeader($uid) {
        try {
            $info = \Micro\Models\Family::findfirst("creatorUid = " . $uid . " and status = 1");
            if ($info != false) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->errLog('checkIsFamilyHeader error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //主播 == 主播详情 ==直播  状态：正常 <===>  解禁
    public function getLiveStatus($uid, $status) {
        try {
            $findParam = array('uid' => $uid);
            $roomUid = Rooms::findFirst(array(
                        'conditions' => " uid = :uid:",
                        'bind' => $findParam
            ));
            if (empty($roomUid)) {
                return false;
            }
            /*if ($status == 2) {
                $roomUid->showStatus = 0;
            }*/
            $roomUid->liveStatus = $status;
            $roomUid->save();
            //广播
            $ArraySubData['controltype'] = "showStatus";
            $broadData['status'] = "play";
            $ArraySubData['data'] = $broadData;
            $this->comm->roomBroadcast($roomUid->roomId, $ArraySubData);
        } catch (Exception $e) {
            $this->errLog('getLiveStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //主播 == 主播详情 == 账号 状态：正常 <===>  冻结
    public function getUserStatus($uid, $status) {
        try {
            $param = array('uid' => $uid);
            $user = \Micro\Models\Users::findFirst(array(
                        'conditions' => " uid = :uid:",
                        'bind' => $param
            ));
            if (empty($user)) {
                return false;
            }

            $user->status = $status;
            $user->save();
        } catch (Exception $e) {
            $this->errLog('getUserStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //主播 == 主播详情 == 直播间是否显示
    public function getShowStatus($uid, $status) {
        try {
            $findParam = array('uid' => $uid);
            $roomUid = Rooms::findFirst(array(
                        'conditions' => " uid = :uid:",
                        'bind' => $findParam
            ));
            if (empty($roomUid)) {
                return false;
            }
            $roomUid->showStatus = $status;
            $roomUid->save();

            //添加显示日志
            $roomParam = array('roomId'=>$roomUid->roomId);
            $showRoomLog = \Micro\Models\ShowRoomLog::findFirst(array(
                'conditions' => " roomId=:roomId: AND startTime is not NULL AND endTime is NULL ",
                'bind' => $roomParam,
                'order' => " id DESC"
            ));
            if(!empty($showRoomLog)){
                if($status == 1){
                    $showRoomLog->endTime = time();
                    $showRoomLog->save();
                }
            }else{
                $showRoom = new \Micro\Models\ShowRoomLog();
                $showRoom->startTime = time();
                $showRoom->roomId = $roomUid->roomId;
                $showRoom->save();
            }
            
            $ArraySubData['controltype'] = "showStatus";
            $broadData['status'] = "play";
            $ArraySubData['data'] = $broadData;
            $this->comm->roomBroadcast($roomUid->roomId, $ArraySubData);
        } catch (Exception $e) {
            $this->errLog('getShowStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //解约主播
    public function unbindStatus($uid, $status) {
        try {
            $parameters = array(
                "uid" => $uid,
                "status1" => $this->config->signAnchorStatus->apply,
                "status2" => $this->config->signAnchorStatus->refuse,
            );
            $signAnchor = SignAnchor::findfirst(array(
                "conditions" => "uid=:uid: AND (status!=:status1: OR status!=:status2:)",
                "bind" => $parameters,
            ));

            if (empty($signAnchor)) {
                return false;
            }
            $signAnchor->status = $status;
            if($signAnchor->save()){
                //解约后将直播间设置成调试状态
                $roomData = \Micro\Models\Rooms::findFirst('uid = ' . $uid);
                if(!empty($roomData)){
                    $roomData->showStatus = 0;
                    $roomData->save();
                }
                $result = array('code'=>'0','desc'=>'操作成功');
                $applyInfo = \Micro\Models\ApplyLog::findfirst('uid='.$uid.' AND type='.$this->config->applyType->sign.' ORDER BY id DESC');
                /*var_dump($applyInfo->id);
                exit;*/
                if(!empty($applyInfo)){
                    $this->editApplyStatus($applyInfo->id, $uid, $this->config->applyStatus->unbind, '解约');
                }
            }else{
                $result = array('code'=>'1','desc'=>'操作失败');
            }

            // editApplyStatus
            return $result;
        } catch (Exception $e) {
            $this->errLog('getShowStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //主播状态信息
    public function anchorStatus($uid) {
        try {
            $findParam = array('uid' => $uid);
            $roomUid = Rooms::findFirst(array(
                        'conditions' => " uid = :uid:",
                        'bind' => $findParam
            ));
            if (!empty($roomUid)) {
                $data['liveStatus'] = $roomUid->liveStatus;
                $data['showStatus'] = $roomUid->showStatus;
            } else {
                $data['liveStatus'] = -1;
                $data['showStatus'] = -1;
            }
            $data['userStatus'] = $this->getUidStatus($uid);
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (Exception $e) {
            $this->errLog('anchorStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //获取主播账号状态
    private function getUidStatus($uid) {
        try {
            $param = array('uid' => $uid);
            $user = \Micro\Models\Users::findFirst(array(
                        'conditions' => " uid = :uid:",
                        'bind' => $param
            ));
            if (empty($user)) {
                return false;
            }
            return $user->status;
        } catch (\Exception $e) {
            $this->errLog('getUidStatus error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
    }

    //主播工作情况
    public function getAnchorWorkingData($uid, $startTime, $stopTime, $page, $pageSize, $isBack = true) {
        $list = array();
        try {
            /*$start = strtotime($startTime);
            $end = strtotime($stopTime);
            if (!empty($startTime) && !empty($stopTime)) {
                $stop = $end + 86399;
            } else if ($startTime != '' && $stopTime == '') {
                $stop = $start + 86399;
            }
            $table = '\Micro\Models\Rooms r LEFT join \Micro\Models\RoomLog rl on rl.roomId = r.roomId';
            $field = 'rl.publicTime,rl.endTime';
            $where = 'r.uid =' . $uid;
            if (!empty($start) or ! empty($stop)) {
                $where .= " AND rl.publicTime between '" . $start . "' AND '" . $stop . "'";
            }
            $limit = ($page - 1) * $pageSize;
            $sql = " SELECT " . $field . " FROM " . $table . " WHERE " . $where . "  order by rl.publicTime DESC limit " . $limit . "," . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();*/

            //New SQL
            $start = $startTime ? strtotime($startTime) : strtotime(date('Y-m-d',strtotime('-7 days')));
            $end = $stopTime ? (strtotime($stopTime) + 86399) : time();
            $limit = ($page - 1) * $pageSize;
            $limit = $limit < 0 ? 0 : $limit;

            //获取roomId
            $roomData = \Micro\Models\Rooms::findFirst('uid = ' . $uid);
            if(!empty($roomData)){
                $roomId = $roomData->roomId;
            }else{
                $roomId = 0;
            }
            /*$sql = "select (rl.endTime - rl.publicTime) as t1,FROM_UNIXTIME(rl.publicTime, '%Y%m%d') as time, rl.publicTime, rl.endTime from pre_room_log as rl "
                    . " where rl.roomId = {$roomId} and publicTime>=" . $start . " and endTime<=" . $end . " and not exists "
                    ." (select sl.roomId from pre_show_room_log as sl where rl.publicTime > sl.startTime and (rl.publicTime < sl.endTime or isnull(sl.endTime)) and sl.roomId = rl.roomId) order by rl.publicTime DESC ";
            */
            $sql = "select (rl.endTime - rl.publicTime) as t1,FROM_UNIXTIME(rl.publicTime, '%Y%m%d') as time, rl.publicTime, rl.endTime,rl.status from pre_room_log as rl "
                    . " where rl.roomId = {$roomId} and publicTime >= " . $start . " and publicTime <= " . $end . " and rl.status = 1 order by rl.publicTime DESC ";

            if($isBack){//判断是否是前台统计查询
                $sql .=  " limit " . $limit . "," . $pageSize;
            }

            $connection = $this->di->get('db');
            $timeResult = $connection->fetchAll($sql);

            $sumLength = 0;
            foreach ($timeResult as $k => $val) {
                $data['dayTime'] = date('Y-m-d', $val['publicTime']);
                $data['publicTime'] = date('Y-m-d H:i:s', $val['publicTime']);
                $data['endTime'] = date('Y-m-d H:i:s', $val['endTime']);
                $data['length'] = $val['endTime'] - $val['publicTime'];  //时长
                // $data['income'] = $this->getIncome($uid, $val['publicTime'], $val['endTime']);
                $data['statusDesc'] = $val['status'] ? '正常' : '调试';
                $sumLength += $data['length'];
                array_push($list, $data);
            }
            // var_dump($timeResult);
            // exit;
            /*if (!empty($tempData)) {
                foreach ($tempData as $val) {  //
                    $data['dayTime'] = date('Y-m-d', $val->publicTime);
                    $data['publicTime'] = date('H:i:s', $val->publicTime);
                    $data['endTime'] = date('H:i:s', $val->endTime);
                    $data['length'] = $val->endTime - $val->publicTime;   //时长
                    $data['income'] = $this->getIncome($uid, $val->publicTime, $val->endTime);
                    array_push($list, $data);
                }
            }*/

            //统计总数
            $count = 0;
            /*$sqlCount = "select (rl.endTime - rl.publicTime) as t1,FROM_UNIXTIME(rl.publicTime, '%Y%m%d') as time, rl.publicTime, rl.endTime,rl.status from pre_room_log as rl "
                    . " where rl.roomId = {$roomId} and publicTime>=" . $start . " and endTime<=" . $end . " and rl.status = 1 order by rl.publicTime DESC ";*/
            $sqlCount = "select count(1) as count from pre_room_log as rl "
                    . " where rl.roomId = {$roomId} and publicTime >= " . $start . " and publicTime <= " . $end . " and rl.status = 1";
                    //" and not exists "
                    // ." (select sl.roomId from pre_show_room_log as sl where rl.publicTime > sl.startTime and (rl.publicTime < sl.endTime or isnull(sl.endTime)) and sl.roomId = rl.roomId) limit 1";
            $connection = $this->di->get('db');
            $countResult = $connection->fetchAll($sqlCount);
            /*if ($list) {
                $countSql = "SELECT count(1) as count FROM " . $table . " WHERE " . $where . " limit 1";
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                $count = $countresult[0]['count'];
            }*/
            $result['count'] = $countResult[0]['count'];//$count;
            $result['list'] = $list;
            $result['sum'] = $sumLength;
            $sevenRes = $this->getAnchor7Times($roomId);
            $result['ttlSeven'] = $sevenRes['ttl'];
            $result['rankInfo'] = $sevenRes['rankInfo'];
            /* print_r($list);exit; */
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }
    // 获取主播近七天的直播数据
    public function getAnchor7Times($roomId = 0){
        try {
            // 获取七天时长总和
            $startTime = strtotime(date('Y-m-d',strtotime('-7 days')));
            $endTime = strtotime(date('Y-m-d',time())) + 86399;
            $sql7Sum = "select ifnull(sum(rl.endTime - rl.publicTime), 0) as ttl from pre_room_log as rl "
                . " where rl.roomId = {$roomId} and publicTime >= " . $startTime . " and publicTime <= " . $endTime . " and rl.status = 1 ";
            $connection = $this->di->get('db');
            $ttlRes = $connection->fetchAll($sql7Sum);
            $ttl = !empty($ttlRes) ? $ttlRes[0]['ttl'] : 0;
            // 排名
            $sql7SumAll = "select ifnull(sum(rl.endTime - rl.publicTime), 0) as ttl, rl.roomId from pre_room_log as rl "
                . " where publicTime >= " . $startTime . " and publicTime <= " . $endTime . " and rl.status = 1 group by rl.roomId order by ttl desc";
            $connection = $this->di->get('db');
            $ttlResAll = $connection->fetchAll($sql7SumAll);
            if(!empty($ttlResAll)){
                $ttlCount = count($ttlResAll);
                $midllNum = floor($ttlCount / 2);
                foreach ($ttlResAll as $k => $v) {
                    if($v['roomId'] == $roomId){
                        if($k >= $midllNum){
                            $say = '您的前面还有<span class="ps">' . $k . '</span>人，请多多努力哦';
                        }else{
                            $say = '您排在<span class="ps">' . ($ttlCount - 1 - $k) . '</span>人前面，请继续保持哦';
                        }
                    }else{
                        $say = '您的前面还有<span class="ps">' . $ttlCount . '</span>人，请多多努力哦';
                    }
                }
            }else{
                $say = '';
            }
            return array('ttl'=>$ttl,'rankInfo'=>$say);
        } catch (\Exception $e) {
            $this->errLog('getAnchor7Times  ERROR:' . $e->getMessage());
            return array('ttl'=>0,'rankInfo'=>'');//$this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取某主播某个时段的收益
    private function getIncome($uid, $start, $end) {
        $sql = 'SELECT sum(income) as income FROM \Micro\Models\ConsumeDetailLog cl WHERE cl.receiveUid = ' . $uid . ' AND cl.income > 0 AND cl.createTime >=' . $start . ' AND cl.createTime <= ' . $end . ' and type < ' . $this->config->consumeType->coinType;
        $query = $this->modelsManager->createQuery($sql);
        $data = $query->execute();
        return $data[0]->income ? $data[0]->income : 0;
    }

    //查询主播在一段时间内的工作情况
    public function getPeriodData($uid, $startTime, $stopTime) {
        $list = array();
        try {
            /*$start = strtotime($startTime);
            $end = strtotime($stopTime);
            if (!empty($startTime) && !empty($stopTime)) {
                $stop = $end + 86399;
            } else if ($startTime != '' && $stopTime == '') {
                $stop = $start + 86399;
            }
            $table = '\Micro\Models\Rooms r LEFT join \Micro\Models\RoomLog rl on rl.roomId = r.roomId';
            $field = 'rl.publicTime,rl.endTime';
            $where = 'r.uid =' . $uid;
            if (!empty($start) or ! empty($stop)) {
                $where .= " AND rl.publicTime between '" . $start . "' AND '" . $stop . "'";
            }
            $sql = " SELECT " . $field . " FROM " . $table . " WHERE " . $where . " order by rl.publicTime ASC";
            $query = $this->modelsManager->createQuery($sql);
            $tempData = $query->execute();*/
            $start = $startTime ? strtotime($startTime) : 0;
            $end = $stopTime ? (strtotime($stopTime) + 86400) : time();

            //获取roomId
            $roomData = \Micro\Models\Rooms::findFirst('uid = ' . $uid);
            if(!empty($roomData)){
                $roomId = $roomData->roomId;
            }else{
                $roomId = 0;
            }
           /* $sql = "select (rl.endTime - rl.publicTime) as t1,FROM_UNIXTIME(rl.publicTime, '%Y%m%d') as time, rl.publicTime, rl.endTime from pre_room_log as rl "
                    . " where rl.roomId = {$roomId} and publicTime>=" . $start . " and endTime<=" . $end . " and not exists "
                    ." (select sl.roomId from pre_show_room_log as sl where rl.publicTime > sl.startTime and (rl.publicTime < sl.endTime or isnull(sl.endTime)) and sl.roomId = rl.roomId) order by rl.publicTime";
            */
            $sql = "select (rl.endTime - rl.publicTime) as t1,FROM_UNIXTIME(rl.publicTime, '%Y%m%d') as time, rl.publicTime, rl.endTime from pre_room_log as rl "
                    . " where rl.roomId = {$roomId} and publicTime>=" . $start . " and endTime<=" . $end . " and rl.status = 1 order by rl.publicTime DESC ";
            $connection = $this->di->get('db');
            $tempData = $connection->fetchAll($sql);
            $sumIncome = 0;
            $sumLength = 0;
            if (!empty($tempData)) {
				$newList = array();
                foreach ($tempData as $val) {  //
					
					$day = date('Y-m-d', $val['publicTime']);//$val->publicTime
					if(isset($newList[$day])){
						$tmp = $newList[$day];
					}else{
						$tmp = array(
							'income' => 0,
							'sumLength' => 0,
							'list' => array(),
						);
					}
					
                    $data['dayTime'] = date('Y-m-d', $val['publicTime']);//$val->publicTime
                    $data['publicTime'] = date('H:i:s', $val['publicTime']);//$val->publicTime
                    $data['endTime'] = date('H:i:s', $val['endTime']);//$val->endTime
                    $data['length'] = $val['endTime'] - $val['publicTime'];//$val->endTime - $val->publicTime;   //时长
                    // $data['income'] = $this->getIncome($uid, $val['publicTime'], $val['endTime']);//$val->publicTime, $val->endTime
                    // $sumIncome += $data['income'] ? $data['income'] : 0;
                    $sumLength += $data['length'];
					
					// $tmp['income'] += $data['income'] ? $data['income'] : 0;
                    $tmp['sumLength'] += $data['length'];
					$tmp['list'][] = $data;
					
					$newList[$day] = $tmp;
					
                    array_push($list, $data);
                }
                // $result['income'] = $sumIncome;
                $result['sumLength'] = $sumLength;
                $result['list'] = $list;
				
				$result['data'] = $newList;
				
                // print_r($result);exit; 
                //导出excel
                $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid);
                $this->getPeriodExcel($userInfo->nickName . '(' . $uid . ')' . $startTime . ' 至 ' . $stopTime . '主播工作情况', $result);
                return $this->status->retFromFramework($this->status->getCode('OK'), $result);
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    /*public function translateTime($time){
        if($time >= 86400){
            $days = floor($time / 86400);
            $leftTmp = $time % 86400;
            $hours = floor($leftTmp / 3600);
            $leftTmp = $leftTmp % 3600;
            $minutes = floor($leftTmp / 60);
        }elseif($time >= 3600){
            $days = 0;
            $hours = floor($time / 3600);
            $leftTmp = $time % 3600;
            $minutes = floor($leftTmp / 60);
        }elseif($time >=0){
            $days = 0;
            $hours = 0;
            $minutes = floor($time / 60);
        }else{
            $days = 0;
            $hours = 0;
            $minutes = 0;
        }
        return $days . '天' . $hours . '小时' . $minutes . '分钟';
    }*/

    public function delAnchorNew($id = 0){
        try {
            $signAnchor = SignAnchor::findfirst('id = ' . $id);
            if (empty($signAnchor)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $uid = $signAnchor->uid;
            $familyId = $signAnchor->familyId;

            // 更新签约表
            $signAnchor->familyId = 0;
            $signAnchor->save();

            $familyLog = \Micro\Models\FamilyLog::findFirst('uid = '. $uid . ' and familyId = ' . $familyId . ' and status = 1');
            if (empty($familyLog)) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            // 更新退出家族时间
            $familyLog->outOfTime = time();
            $familyLog->status = 0;
            $familyLog->save();

            //给主播发送通知
            $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid);
            $familyInfo = \Micro\Models\Family::findfirst($familyId);
            $sendUser = UserFactory::getInstance($uid);
            $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->familyHeaderDelAnchor, array(0 => $familyInfo->name));
            $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);

            return $this->status->retFromFramework($this->status->getCode('OK'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //删除家族旗下主播
    public function delAnchor($uid, $status = 0){
        try {
            //获取数据
            $signAnchor = SignAnchor::findfirst('uid = '.$uid.' AND familyId != 0');
            if (empty($signAnchor)) {
                return false;
            }

            //记录原来所属的家族
            $oldFamilyId = $signAnchor->familyId;
            $signAnchor->familyId = 0;

            if($signAnchor->save()){

                //更新家族日志--退出时间和状态
                $familyLogs = \Micro\Models\FamilyLog::find('uid = '.$uid.' AND status = 1');
                foreach ($familyLogs as $key => $familyLog) {
                    $familyLog->outOfTime = time();
                    $familyLog->status = 0;
                    if(!$familyLog->save()){//由于表为MyISAM，手动回滚数据
                        $signAnchor->familyId = $oldFamilyId;
                        $signAnchor->save();
                    }
                }

                //给家族长发送通知
                $userInfo = \Micro\Models\UserInfo::findfirst("uid=" . $uid);
                $familyInfo = \Micro\Models\Family::findfirst($oldFamilyId);
                $sendUser = UserFactory::getInstance($familyInfo->creatorUid);
                $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->outFamily, array(0 => $userInfo->nickName));
                $informationResult = $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);

                if(!$informationResult){//回滚
                    $signAnchor->familyId = $oldFamilyId;
                    $signAnchor->save();
                    $familyLog->outOfTime = '';
                    $familyLog->status = 1;
                    $familyLog->save();
                    $result = array('code'=>'1','desc'=>'操作失败');
                }else{
                    $result = array('code'=>'0','desc'=>'操作成功');
                }
                return $result;
            }else{
                return false;
            }

                

            $result['code'] = '0';
            $result['info'] = '操作成功';
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //获取比例
    public function getRatioNum($ratioKey){
        try {
            $parameters = array(
                "key" => $ratioKey
            );
            $ratioRes = \Micro\Models\BaseConfigs::findfirst(array(
                "conditions" => "key=:key:",
                "bind" => $parameters,
            ));

            return !empty($ratioRes) ? $ratioRes->value : false;

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //设置比例
    public function setRatio($ratioNum){
        try {
            $parameters = array(
                "key" => $this->config->websiteinfo->ratioconfig['key']
            );
            $ratioRes = \Micro\Models\BaseConfigs::findfirst(array(
                "conditions" => "key=:key:",
                "bind" => $parameters,
            ));

            if (empty($ratioRes)) {
                return false;
            }
            $ratioRes->value = $ratioNum;
            if($ratioRes->save()){
                $result = array('code'=>'0','desc'=>'操作成功');
            }else{
                $result = array('code'=>'1','desc'=>'操作失败');
            }
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        }
    }

    //礼物明细【时间段】
    public function getRecvGifts($uid = 0, $startDate = '', $endDate = '', $type = 0, $page = 1, $pageSize = 20, $giftName = '', $sendUid = 0){
        try {
            !$startDate && $startDate = date('Y-m-d',strtotime('-1 day'));
            !$endDate && $endDate = $startDate;

            $startTime = strtotime($startDate);
            $endTime = strtotime($endDate) + 86399;

            $normalLib = $this->di->get('normalLib');
            $configs = $normalLib->getConfigs();

            $conditions = ' where cd.receiveUid = ' . $uid;
            $typeCon = ' and ( cd.type = ' . $this->config->consumeType->grabSeat . ' or cd.type = ' . $this->config->consumeType->sendGift . ' or cd.type = ' . $this->config->consumeType->buyGuard . ') ';
            if($giftName){
                $res = \Micro\Models\GiftConfigs::findFirst('name = "' . $giftName . '"');
                if($res){
                    $typeCon = ' and cd.type = ' . $this->config->consumeType->sendGift . ' and cd.itemId = ' . $res->id;
                }
            }
            $conditions .= $typeCon;

            if($sendUid){
                $conditions .= ' and cd.uid = "' . $sendUid . '"';
            }

            if($type == 1 || $type == 2 || $type == 3){
                $conditions .= ' and u.internalType = ' . ($type - 1);
            }

            $conditions .= ' and cd.createTime >= ' . $startTime . ' and cd.createTime < ' . $endTime;

            $offset = ($page - 1) * $pageSize;
            $sql = 'select cd.type,cd.count,cd.createTime,cd.nickName,cd.itemId,cd.uid,cd.receiveUid,cd.amount,cd.income from \Micro\Models\ConsumeDetailLog as cd '
                . ' left join \Micro\Models\Users as u on u.uid = cd.uid ' . $conditions 
                . ' ORDER BY cd.createTime DESC limit ' . $offset . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $consumeData = $query->execute();

            $sqlCount = 'select count(1) as count from \Micro\Models\ConsumeDetailLog as cd '
                . ' left join \Micro\Models\Users as u on u.uid = cd.uid ' . $conditions;
            $queryCount = $this->modelsManager->createQuery($sqlCount);
            $countData = $queryCount->execute();
            $count = $countData->valid() ? $countData->toArray()[0]['count'] : 0;

            $sqlSum = 'select sum(cd.amount) as allRecv,sum(cd.income) as allIncome from \Micro\Models\ConsumeDetailLog as cd '
                . ' left join \Micro\Models\Users as u on u.uid = cd.uid ' . $conditions;
            $querySum = $this->modelsManager->createQuery($sqlSum);
            $sumData = $querySum->execute();
            $sumArr = $sumData->valid() ? array('allRecv'=>$sumData->toArray()[0]['allRecv'],'allIncome'=>$sumData->toArray()[0]['allIncome']) : array('allRecv'=>0,'allIncome'=>0);

            $typeArr = array(
                $this->config->consumeType->sendGift => '1',
                $this->config->consumeType->grabSeat => '2',
                $this->config->consumeType->buyGuard => '3'
            );
            $data = array();
            $totalRecv = 0;
            $totalIncome = 0;
            if($consumeData->valid()){
                foreach ($consumeData as $v) {
                    $tmp['type'] = $typeArr[$v->type];
                    $tmp['count'] = ($v->type == $this->config->consumeType->buyGuard) ? 1 : $v->count;
                    $tmp['createTime'] = date('Y-m-d H:i:s', $v->createTime);
                    $tmp['nickName'] = $v->nickName ? $v->nickName : '';
                    $tmp['guardType'] = $v->itemId;
                    $tmp['uid'] = $v->uid;
                    $tmp['anchorId'] = $v->receiveUid;
                    $tmp['name'] = $configs[$v->type][$v->itemId]['name'];
                    // var_dump($configs[$v->type][$v->itemId]);
                    $tmp['configName'] = $configs[$v->type][$v->itemId]['configName'];
                    $totalRecv += $v->amount;
                    $totalIncome += $v->income;
                    $data[] = $tmp;
                    unset($tmp);
                }
            }

            /*$settleCash = 0;
            $settleRes = \Micro\Models\ChangeLog::sum(
                array(
                    'column' => 'money',
                    'conditions' => 'uid = ' . $uid . ' and status = 1 and type = 3'
                        . ' and createTime >= ' . $startTime . ' and createTime < ' . $endTime
                )
            );
            $settleCash = abs($settleRes) * $this->config->cashScale;*/

            return $this->status->retFromFramework($this->status->getCode('OK'), 
                array(
                    'data' => $data,'count'=>$count,'totalRecv'=>$totalRecv,'totalIncome'=>$totalIncome,
                    'allRecv' => round($sumArr['allRecv']),'allIncome'=>round($sumArr['allIncome'])
                )
            );
	    //,'settleCash' => $settleCash

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //礼物明细【按日】
    public function getDayRecvGifts($uid, $date = 0, $page = 1, $pageSize = 20){
        try {
            if($date){
                $begin = strtotime($date);
            }else{
                $begin = strtotime(date('Y-m-d',strtotime('-1 day')));
            }
            $end = $begin + 86399;

            $normalLib = $this->di->get('normalLib');
            $configs = $normalLib->getConfigs();
            $offset = ($page - 1) * $pageSize;

            $types = $this->config->consumeTypeAnchor;
            $consumeData = \Micro\Models\ConsumeDetailLog::find(
                'receiveUid = ' . $uid . ' and type in (' . $types . ')' . 
                ' and createTime >= ' . $begin . ' and createTime <= ' . $end . ' ORDER BY createTime DESC limit ' . $offset . ',' . $pageSize
            );
            $count = \Micro\Models\ConsumeDetailLog::count(
                array('column' => 'id', 
                'conditions' => 'receiveUid = ' . $uid . 
                ' and type in ( ' . $types . ')' . 
                ' and createTime >= ' . $begin . ' and createTime <= ' . $end
            ));
            $count = $count ? $count : 0;
            $typeArr = array(
                $this->config->consumeType->sendGift => '1',
                $this->config->consumeType->grabSeat => '2',
                $this->config->consumeType->buyGuard => '3',
                $this->config->consumeType->buyShow => '11'
            );
            $data = array();
            if(!empty($consumeData)){
                foreach ($consumeData as $v) {
                    $tmp['type'] = $typeArr[$v->type];
                    $tmp['count'] = ($v->type == $this->config->consumeType->buyGuard) ? 1 : $v->count;
                    $tmp['createTime'] = date('H:i:s', $v->createTime);
                    $tmp['nickName'] = $v->nickName ? $v->nickName : '';
                    $tmp['guardType'] = $v->itemId;
                    $tmp['uid'] = $v->uid;
                    $tmp['anchorId'] = $v->receiveUid;
                    $tmp['name'] = $configs[$v->type][$v->itemId]['name'];
                    // var_dump($configs[$v->type][$v->itemId]);
                    $tmp['configName'] = $configs[$v->type][$v->itemId]['configName'];
                    $data[] = $tmp;
                    unset($tmp);
                }
            }

            /*$sqlGift = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gl.count,gc.name,gc.configName from \Micro\Models\ConsumeLog as cl '.
                ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.uid '.
                ' left join \Micro\Models\GiftLog as gl on gl.consumeLogId = cl.id left join \Micro\Models\GiftConfigs as gc on gl.giftId = gc.id ' .
                ' where cl.type = ' . $this->config->consumeType->sendGift;
            $sqlSeat = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gl.count from \Micro\Models\ConsumeLog as cl '.
                ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.uid '.
                ' left join \Micro\Models\GrabLog as gl on gl.consumeLogId = cl.id ' .
                ' where cl.type = ' . $this->config->consumeType->grabSeat;
            $sqlGuard = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gc.name,gl.guardType from \Micro\Models\ConsumeLog as cl '.
                ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.uid '.
                ' left join \Micro\Models\GuardLog as gl on gl.consumeLogId = cl.id left join \Micro\Models\GuardConfigs as gc on gl.guardType = gc.level ' .
                ' where cl.type = ' . $this->config->consumeType->buyGuard;
            $where = ' and cl.createTime >= ' . $begin . ' and cl.createTime <= ' . $end . ' and cl.anchorId = ' . $uid . ' ORDER BY cl.createTime DESC';
            $data = array();
            $sort = array();

            $queryGift = $this->modelsManager->createQuery($sqlGift . $where);
            $tempData = $queryGift->execute();
            $giftData = $tempData->toArray();
            foreach ($giftData as $k => $v1) {
                $tmp = array();
                $tmp = $v1;
                $tmp['createTime'] = date('H:i:s', $tmp['createTime']);
                $tmp['type'] = 1;
                $data[] = $tmp;
                $sort[] = $tmp['createTime'];
                unset($tmp);
            }
            $querySeat = $this->modelsManager->createQuery($sqlSeat . $where);
            $tempData = $querySeat->execute();
            $seatData = $tempData->toArray();
            foreach ($seatData as $k => $v2) {
                $tmp = array();
                $tmp = $v2;
                $tmp['createTime'] = date('H:i:s', $tmp['createTime']);
                $tmp['name'] = '沙发';
                $tmp['configName'] = 'qz';
                $tmp['type'] = 2;
                $data[] = $tmp;
                $sort[] = $tmp['createTime'];
                unset($tmp);
            }
            $queryGuard = $this->modelsManager->createQuery($sqlGuard . $where);
            $tempData = $queryGuard->execute();
            $guardData = $tempData->toArray();
            foreach ($guardData as $k => $v3) {
                $tmp = array();
                $tmp = $v3;
                $tmp['count'] = 1;
                $tmp['createTime'] = date('H:i:s', $tmp['createTime']);
                $tmp['type'] = 3;
                $data[] = $tmp;
                $sort[] = $tmp['createTime'];
                unset($tmp);
            }

            array_multisort($sort, SORT_DESC, $data);
            
            $offset = ($page - 1) * $pageSize;
            $returnData = array_slice($data, $offset, $pageSize);*/

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //礼物收入
    public function getDayGiftsLog($uid, $date = 0, $type = 1, $page = 1, $pageSize = 20){
        try {
            if(!$date){//默认当月
                $day = date('d');
                if($day >= 12){
                    $startTime = strtotime(date('Y-m-12',time()));
                }else{
                    $startTime = strtotime(date('Y-m-12',strtotime('-1 month', strtotime(date('Y-m-01')))));
                }
                $endTime = time();
                $inTime = date('Y-m-12',strtotime('+1 month', strtotime(date('Y-m-01',$startTime))));
            }else{
                $startTime = strtotime(date('Y-m-12',strtotime('-1 month', strtotime(date('Y-m-01',strtotime($date))))));
                $endTime = strtotime(date('Y-m-11',strtotime($date)));
                $inTime = date('Y-m-12',$endTime);
            }

            $platRatio = $this->config->incomeRatios->platRatio;
            $divideRatio = $this->config->incomeRatios->divideRatio;
            //,dg.settleTime
            if($type == 2){
                $select_page = 'select dg.uid,dg.createTime,dg.divideRatio,dg.divideIncome as finalIncome,dg.allIncome,ui.nickName ';
                $select_sum = 'select ifnull(sum(dg.divideIncome), "0") as ttl ';
                $where = ' where dg.creatorUid = ' . $uid . ' and dg.createTime >= ' . $startTime . ' and dg.createTime <= ' . $endTime;
                $ratio = $platRatio * $divideRatio / 10000;
            }else{
                $select_page = 'select dg.uid,dg.createTime,dg.divideRatio,dg.myIncome as finalIncome,dg.allIncome,ui.nickName ';
                $select_sum = 'select ifnull(sum(dg.myIncome), "0") as ttl ';
                $where = ' where dg.uid = ' . $uid . ' and dg.createTime >= ' . $startTime . ' and dg.createTime <= ' . $endTime;
                $ratio = $platRatio * (100 - $divideRatio) / 10000;
            }

            $select_count = 'select count(1) as count ';

            $table = ' from \Micro\Models\DayGiftsLog as dg left join \Micro\Models\UserInfo as ui on dg.uid = ui.uid ';

            $limit = ($page - 1) * $pageSize;
            $page_condition = ' order by dg.createTime desc limit ' . $limit . ',' . $pageSize;

            $page_sql = $select_page . $table . $where . $page_condition;

            $query_page = $this->modelsManager->createQuery($page_sql);
            $result = $query_page->execute();

            $data = array();
            $count = 0;
            $ttl = 0;
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['divideRatio'] = $v->divideRatio;
                    $tmp['finalIncome'] = $v->finalIncome;
                    $tmp['allIncome'] = $v->allIncome;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['createTime'] = date('Y-m-d',$v->createTime);
                    $tmp['inTime'] = $inTime;
                    // $ttl += $v->finalIncome;
                    $data[] = $tmp;
                    unset($tmp);
                }
                //获取count值
                $count_sql = $select_count . $table . $where;
                $query_count = $this->modelsManager->createQuery($count_sql);
                $count_res = $query_count->execute();
                $count = $count_res[0]['count'];
            }
            
            //获取ttl值
            $sum_sql = $select_sum . $table . $where;
            $sum_count = $this->modelsManager->createQuery($sum_sql);
            $sum_res = $sum_count->execute();
            $ttl = $sum_res[0]['ttl'];

            //获取
            $types = $this->config->consumeTypeAnchor;
            $tuoSum = \Micro\Models\ConsumeDetailLog::sum(
                array(
                    'column' => 'income',
                    'conditions' => 'receiveUid = ' . $uid . ' and type in (' . $types . ')' . ' and isTuo = 1'
                        . ' and createTime >= ' . ($startTime - 86400) . ' and createTime < ' . $endTime
                )
            );
            $ttlTuo = $tuoSum ? ($tuoSum * $ratio) : 0;

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count,'ttl'=>$ttl,'ttlTuo'=>$ttlTuo));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //收益流水
    public function getDayIncomeLog($uid, $date = 0, $type = 1, $page = 1, $pageSize = 20){
        try {
            if ($type == 1) {
                $type = $this->config->moneyType[$type]['type'];
                $type_ = $this->config->moneyType[3]['type'];
            } else if ($type == 2) {
                $type = $this->config->moneyType[$type]['type'];
                $type_ = $this->config->moneyType[4]['type'];
            } else if ($type == 5) {
                $type = $this->config->moneyType[5]['type'];
                $type_ = $this->config->moneyType[6]['type'];
            } else if ($type == 7) {
                $type = $this->config->moneyType[7]['type'];
                $type_ = $this->config->moneyType[8]['type'];
            }
            if(!$date){//默认当月
                $createTime = strtotime(date('Y-m-11'));
            }else{
                $createTime = strtotime(date('Y-m-11',strtotime($date)));
            }
            $endTime = $createTime + 86400;

            !$uid && $uid = 0;
            !$page && $page = 1;
            !$pageSize && $pageSize = 20;
            $limit = ($page - 1) * $pageSize;

            $result = \Micro\Models\DayIncomeLog::find('uid = ' . $uid 
                . ' and ((type = ' . $type . ' and createTime = ' . $createTime . ') or (type = ' . $type_ 
                . ' and createTime = ' . $endTime . ')) order by id desc limit ' . $limit . ',' . $pageSize
            );

            $data = array();
            $count = 0;
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['uid'] = $v->uid;
                    $tmp['money'] = $v->money;
                    $tmp['type'] = $v->type;
                    $tmp['description'] = $v->description;
                    $tmp['createTime'] = date('Y-m-d',$v->createTime);
                    $data[] = $tmp;
                    unset($tmp);
                }
                // unset($v);
            }

            $count = \Micro\Models\DayIncomeLog::count('uid = ' . $uid . ' and ((type = ' . $type 
                . ' and createTime = ' . $createTime . ') or (type = ' . $type_ . ' and createTime = ' . $endTime . '))'
            );

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count)); 
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //佣金流水表
    public function getMonthIncomeLog($uid, $date = 0,$type = 1, $page = 1, $pageSize = 20){
        try {
            if($date){
                $startTime = strtotime(date('Y', strtotime($date.'-1-1')) . '-2-12');
                $endTime = strtotime(($date + 1) . '-1-12');
            }else{
                $startTime = strtotime(date('Y-2-12'));
                $endTime = time();
            }

            !$uid && $uid = 0;
            !$page && $page = 1;
            !$pageSize && $pageSize = 20;
            $limit = ($page - 1) * $pageSize;

            $result = \Micro\Models\MonthIncomeLog::find('uid = ' . $uid . ' and type = ' . $type 
                . ' and createTime >= ' . $startTime . ' and createTime <= ' . $endTime 
                . ' order by createTime desc limit ' . $limit . ',' . $pageSize
            );

            $data = array();
            $count = 0;
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['uid'] = $v->uid;
                    $tmp['money'] = $v->money;
                    $tmp['type'] = $v->type;
                    $tmp['createTime'] = date('Y-m-d',$v->createTime);
                    $tmp['desc'] = intval(date('m',$v->createTime)) . '月月结';
                    $data[] = $tmp;
                }
            }

            $count = \Micro\Models\MonthIncomeLog::count('uid = ' . $uid . ' and type = ' . $type 
                . ' and createTime >= ' . $startTime . ' and createTime <= ' . $endTime
            );

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //交易记录
    public function getChangeLog($uid, $startTime = 0, $endTime = 0, $page = 1, $pageSize = 20){
        try {
            if($startTime && $endTime){
                $begin = strtotime($startTime);
                $end = strtotime($endTime) + 86399;
            }else{
                $begin = 0;
                $end = time();
            }

            $limit = ($page - 1) * $pageSize;

            $result = \Micro\Models\ChangeLog::find('uid = ' . $uid . ' and createTime>= ' . $begin . ' and createTime<= ' . $end . ' order by createTime desc limit ' . $limit . ',' . $pageSize);

            $data = array();
            $count = 0;
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['uid'] = $v->uid;
                    $tmp['type'] = $v->type;
                    $tmp['status'] = $v->status;
                    $tmp['typeDesc'] = $this->config->changeType[$v->type]['desc'];
                    $tmp['statusDesc'] = $this->config->changeType[$v->type]['status'][$v->status];
                    $tmp['createTime'] = date('Y-m-d H:i:s',$v->createTime);
                    $tmp['orderNum'] = $v->orderNum;
                    $tmp['money'] = $v->money;
                    $data[] = $tmp;
                    unset($tmp);
                }

                $count = \Micro\Models\ChangeLog::count('uid = ' . $uid . ' and createTime>= ' . $begin . ' and createTime<= ' . $end . ' order by createTime');
            }

            //获取佣金余额
            $res = \Micro\Models\UserProfiles::findfirst('uid = ' . $uid);
            $usefulMoney = $res->usefulMoney;

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count,'usefulMoney'=>$usefulMoney));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    //申请提现接口
    public function addSettleLog($uid = 0, $money = 0, $type = 2, $accountArr = array()){
        try {
            //获取账户信息
            // $userAccount = $this->getUserAccount($uid, true);
            $createTime = time();

            // 更新账户信息
            if($accountArr){
                $userInfo  = \Micro\Models\UserInfo::findFirst('uid = ' . $uid);
                if(!empty($userInfo)){
                    $userInfo->bank = $accountArr['bank'];
                    $userInfo->cardNumber = $accountArr['cardNumber'];
                    $userInfo->realName = $accountArr['realName'];
                    $userInfo->ID = $accountArr['ID'];
                    if(!$userInfo->save()){
                        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
                    }
                }
            }

            //扣除金额
            $connection = $this->di->get('db');
            $data = $connection->fetchOne('select usefulMoney from pre_user_profiles where uid = ' . $uid . ' and usefulMoney >= ' . $money);
            if(!$data){
                return $this->status->retFromFramework($this->status->getCode('MONEY_TOO_LARGE_ENOUGH'), '');
            }
            $sql = 'update pre_user_profiles set usefulMoney = usefulMoney - ' . $money . ' where uid = ' . $uid . ' and usefulMoney >= ' . $money;
            $result = $connection->execute($sql);
            if(!$result){
                return $this->status->retFromFramework($this->status->getCode('MONEY_TOO_LARGE_ENOUGH'), '');
            }

            //添加提现日志
            $ChangeLogDb = new \Micro\Models\ChangeLog();
            $ChangeLogDb->uid = $uid;
            $ChangeLogDb->money = 0 - $money;
            $ChangeLogDb->createTime = $createTime;
            $ChangeLogDb->type = $type;
            $ChangeLogDb->status = 0;
            $ChangeLogDb->orderNum = date('YmdHis') . rand(100000,999999);

            if($ChangeLogDb->save()){
                //添加结算记录
                $changeId = $ChangeLogDb->id;
                $SettleLogDb = new \Micro\Models\InvSettleLog();
                $SettleLogDb->changeId = $changeId;
                $SettleLogDb->uid = $uid;
                $SettleLogDb->rmb = 0 - $money;
                // $SettleLogDb->auditTime = $createTime;
                $SettleLogDb->createTime = $createTime;
                $SettleLogDb->status = 0;
                $SettleLogDb->type = $type;
                if(!$SettleLogDb->save()){
                    $sql = 'update pre_user_profiles set usefulMoney = usefulMoney + ' . $money . ' where uid = ' . $uid;
                    $connection = $this->di->get('db');
                    $data = $connection->execute($sql);
                    $sql = 'delete from \Micro\Models\ChangeLog where id = ' . $changeId;
                    $query = $this->modelsManager->createQuery($sql);
                    $result = $query->execute();
                }
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }else{
                $sql = 'update pre_user_profiles set usefulMoney = usefulMoney + ' . $money . ' where uid = ' . $uid;
                $connection = $this->di->get('db');
                $data = $connection->execute($sql);
            }
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取账户信息
    public function getUserAccount($uid = 0, $returnType = false){
        try {
            $sql = 'select ui.telephone,ui.realName,ui.cardNumber,ui.bank,ui.ID,up.usefulMoney from \Micro\Models\UserInfo as ui ' 
                   // . ' left join \Micro\Models\SignAnchor as sa on sa.uid = ui.uid ' 
                   . ' left join \Micro\Models\UserProfiles as up on ui.uid = up.uid '
                   . ' where ui.uid = ' . $uid . ' limit 1';
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();

            $data = array();
            if($result->valid()){
                $data = $result->toArray();
                $data[0]['cardNumber'] = substr_replace($data[0]['cardNumber'], '****', 4, -1);
                $data[0]['ID'] = substr_replace($data[0]['ID'], '****', 4, -3);
                if($data[0]['telephone']){
                    $data[0]['telephone'] = substr_replace($data[0]['telephone'], '****', 3, 4);
                }

            }

            //判断是否在每月14-20
            $day = date('d');
            if($day >= 14 && $day <= 20){
                $data[0]['canSubmit'] = true;
            }else{
                $data[0]['canSubmit'] = false;
            }

            if($returnType){
                return $data;
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取交易提现详情
    public function getChangeDetail($id = 0){
        try {
            $sql = 'select ui.nickName,ui.telephone,sl.auditUser,sl.remark,sl.rmb,sl.status,sl.type,sl.createTime,sl.auditTime,ui.realName,ui.cardNumber,ui.bank,ui.ID,cl.orderNum,sl.auditImg from \Micro\Models\InvSettleLog as sl ' 
                   // . ' left join \Micro\Models\SignAnchor as sa on sa.uid = sl.uid '
                   . ' left join \Micro\Models\ChangeLog as cl on cl.id = sl.changeId '
                   . ' left join \Micro\Models\UserInfo as ui on ui.uid = sl.uid '
                   . ' where sl.changeId = ' . $id;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $data = array();
            if($result->valid()){
                $tmp = $result->toArray()[0];
                $tmp['createTime'] = date('Y-m-d',$tmp['createTime']);
                $tmp['auditTime'] = $tmp['auditTime'] ? date('Y-m-d',$tmp['auditTime']) : '';
                $tmp['cardNumber'] = substr_replace($tmp['cardNumber'], '****', 6, -1);
                $tmp['telephone'] = substr_replace($tmp['telephone'], '****', 4, 4);                
                $tmp['ID'] = substr_replace($tmp['ID'], '****', 5, -3);                
                $tmp['status'] = intval($tmp['status']);
                $tmp['nickName'] = $tmp['nickName'];
                $tmp['auditUser'] = $tmp['auditUser'];
                $tmp['remark'] = $tmp['remark'];
                $tmp['auditImg'] = $tmp['auditImg'] ? $tmp['auditImg'] : '';
                $tmp['orderNum'] = $tmp['orderNum'];
                $tmp['statusDesc'] = $this->config->changeType[$tmp['type']]['status'][$tmp['status']];
                $data[] = $tmp;
                unset($tmp);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取结算
    public function getSettleLog($status = 0, $page = 1, $pageSize = 20, $startTime = 0, $endTime = 0){
        try {
            $sql_count = 'select count(1) as count ';
            $sql_select = 'select cl.id,cl.uid,cl.orderNum,cl.createTime,cl.money,cl.type,cl.status,ui.nickName,sl.auditUser,sl.auditTime,sl.remark ';

            $where = ' from \Micro\Models\InvSettleLog as sl left join \Micro\Models\UserInfo as ui on sl.uid = ui.uid left join \Micro\Models\ChangeLog as cl on sl.changeId = cl.id'
                . ' where cl.status = ' . $status . ' and (cl.type = ' . $this->config->changeType[2]['type'] . ' or cl.type = ' . $this->config->changeType[3]['type'] . ') ';

            //时间范围
            if($status == 1){
                $where .= ' and sl.auditTime >= ' . $startTime . ' and sl.auditTime <= ' . $endTime;
            }else{
                $where .= ' and sl.createTime >= ' . $startTime . ' and sl.createTime <= ' . $endTime;
            }
            $where .= ' order by cl.createTime desc';

            $limit = ($page - 1) * $pageSize;

            $sql = $sql_select . $where . ' limit ' . $limit . ',' . $pageSize;

            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();

            $data = array();
            $count = 0;
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['uid'] = $v->uid;
                    $tmp['orderNum'] = $v->orderNum;
                    $tmp['createTime'] = date('Y-m-d H:i:s',$v->createTime);
                    $tmp['auditTime'] = date('Y-m-d H:i:s',$v->auditTime);
                    $tmp['money'] = $v->money;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['auditUser'] = $v->auditUser;
                    $tmp['remark'] = $v->remark;
                    $tmp['type'] = $v->type;
                    $tmp['typeDesc'] = $this->config->changeType[$v->type]['desc'];
                    $tmp['status'] = $v->status;
                    $tmp['statusDesc'] = $this->config->changeType[$v->type]['status'][$v->status];
                    $data[] = $tmp;
                    unset($tmp);
                }

                $query_count = $this->modelsManager->createQuery($sql_count . $where);
                $count_res = $query_count->execute();
                $count = $count_res[0]['count'];
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //结算审核
    public function updateSettleLog($id = 0, $files, $remark, $type = 2) {
        try {
            $settleInfo = \Micro\Models\InvSettleLog::findfirst('changeId = ' . $id);
            $changeInfo = \Micro\Models\ChangeLog::findfirst('id = ' . $id);
            if ($settleInfo != false && $settleInfo->status == 0 && $changeInfo != false && $changeInfo->status == 0) {//未结算
                $nowTime = time();
                $file = $files[0];
                $fileNameArray = explode('.', strtolower($file->getName()));
                $fileExt = $fileNameArray[count($fileNameArray) - 1];
                $filePath = $this->pathGenerator->getInvAccountPath(date("Ymd"));
                $fileName = time() . '.' . $fileExt;
                $result = $this->storage->upload($filePath . $fileName, $file->getTempName(), TRUE);
                if ($result != false) {

                    if($type == $this->config->changeType[3]['type']){
                        $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash;
                        $addCashNum = (0 - $settleInfo->rmb) * $this->config->cashScale;
                        $userCash->addUserCash($addCashNum, $settleInfo->uid);
                        //写入聊币记录表
                        $userCash->addCashLog($addCashNum, $this->config->cashSource->changeCash, $changeInfo->orderNum, $settleInfo->uid);
                    }
                    /*//扣除金额
                    $sql = 'update pre_user_profiles set usefulMoney = usefulMoney - ' . $settleInfo->rmb . ' where uid = ' . $settleInfo->uid . ' and usefulMoney >= ' . $settleInfo->rmb;
                    $connection = $this->di->get('db');
                    $data = $connection->fetchOne('select usefulMoney from pre_user_profiles where uid = ' . $settleInfo->uid . ' and usefulMoney >= ' . $settleInfo->rmb);
                    if(!$data){
                        return $this->status->retFromFramework($this->status->getCode('MONEY_TOO_LARGE_ENOUGH'), '');
                    }
                    $result = $connection->execute($sql);
                    if(!$result){
                        return $this->status->retFromFramework($this->status->getCode('MONEY_TOO_LARGE_ENOUGH'), '');
                    }*/
                    //更新日志

                    $fileUrl = $this->pathGenerator->getFullInvAccountPath(date("Ymd"), $fileName);
                    $settleInfo->status = 1;
                    $settleInfo->auditUser = $this->username;
                    $settleInfo->auditTime = $nowTime;
                    $settleInfo->auditImg = $fileUrl;
                    $settleInfo->remark = $remark;
                    $res1 = $settleInfo->save();

                    $changeInfo->status = 1;
                    $res2 = $changeInfo->save();

                    //添加日志记录
                    $this->addOperate($this->username, '修改', "主播,uid" . $settleInfo->uid, "完成".$this->config->changeType[$settleInfo->type]['desc']."结算，结算金额{$settleInfo->rmb}人民币", '', '');

                    if ($res1 && $res2) {//操作成功
                        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
                    }/*else{
                        //扣除金额
                        $sql = 'update pre_user_profiles set usefulMoney = usefulMoney + ' . $settleInfo->rmb . ' where uid = ' . $settleInfo->uid . ' and usefulMoney >= ' . $settleInfo->rmb;
                        $connection = $this->di->get('db');
                        $connection->execute($sql);
                    }*/
                }
                return $this->status->retFromFramework($this->status->getCode('FILESYS_OPER_ERROR'), '');
            }
            return $this->status->retFromFramework($this->status->getCode('SETTLE_LOG_HAS_SETTLED'), '');
        } catch (\Exception $e) {
            $this->errLog('updateSettleLog error username= errorMessage = ' . $e->getMessage());
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取概况
    public function getBasicInfo($uid){
        try {
            $day = date('d');
            if($day >= 12){
                $createTime = strtotime(date('Y-m-12',strtotime('this month')));
            }else{
                $createTime = strtotime(date('Y-m-12',strtotime('-1 month', strtotime(date('Y-m-01')))));
            }
            $endTime = strtotime(date('Y-m-d',strtotime('-1 day')));

            //获取佣金余额
            $res = \Micro\Models\UserProfiles::findfirst('uid = ' . $uid);
            $usefulMoney = $res->usefulMoney;

            //获取上月收入
            $lastMonthMoney = \Micro\Models\MonthIncomeLog::sum(array('column'=>'money','conditions'=>'uid = ' . $uid . ' and createTime = ' . $createTime));

            //获取累计收入
            $allMoney = \Micro\Models\MonthIncomeLog::sum(array('column'=>'money','conditions'=>'uid = ' . $uid));

            //这个周期的聊币收入
            $periodAnchor = \Micro\Models\DayGiftsLog::sum(array('column'=>'myIncome','conditions'=>'uid = ' . $uid . ' and createTime >= ' . $createTime . ' and createTime <= ' . $endTime));
            $periodFamily = \Micro\Models\DayGiftsLog::sum(array('column'=>'divideIncome','conditions'=>'creatorUid = ' . $uid . ' and createTime >= ' . $createTime . ' and createTime <= ' . $endTime));
            $periodAll = $periodAnchor + $periodFamily;

            //这个周期的兑换金额
            $changeMoney = \Micro\Models\InvSettleLog::sum(array('column'=>'rmb','conditions'=>'type = ' . $this->config->changeType[3]['type'] . ' and uid = ' . $uid . ' and createTime >= ' . $createTime));
            //这个周期的提现金额
            $settleMoney = \Micro\Models\InvSettleLog::sum(array('column'=>'rmb','conditions'=>'type = ' . $this->config->changeType[2]['type'] . ' and uid = ' . $uid . ' and createTime >= ' . $createTime));

            $data = array(
                'usefulMoney' => $usefulMoney ? $usefulMoney : 0,
                'lastMonthMoney' => $lastMonthMoney ? $lastMonthMoney : 0,
                'allMoney' => $allMoney ? $allMoney : 0,
                'periodAll' => $periodAll ? $periodAll : 0,
                'changeMoney' => $changeMoney ? 0 - $changeMoney : 0,
                'settleMoney' => $settleMoney ? 0 - $settleMoney : 0,
                'periodTime' => date('Y.m.d',$createTime) . '-' . date('Y.m.d',$endTime)
            );

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data));
            
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取我的周星数据
    public function getMyWeekStar($uid = 0, $giftId = 0){
        try {
            $week = date('w');
            if($week == 1){
                $thisMondayTime = strtotime(date('Y-m-d',strtotime('this Monday')));
            }else{
                $thisMondayTime = strtotime(date('Y-m-d',strtotime('-1 Monday')));
            }

            $endTime = strtotime(date('Y-m-d')) - 1;

            $getNum = 0;
            $sendNum = 0;
            // $configName = '';

            if(!$giftId){
                $giftData = $this->getWeekStarGift();
                $giftId = $giftData['giftId'];
                // $configName = $giftData['configName'];
            }

            $sqlGet = 'select sum(cl.count) as ttl from \Micro\Models\ConsumeDetailLog as cl '
                . ' where cl.createTime >= ' . $thisMondayTime . ' and cl.createTime <= ' . $endTime . ' and cl.receiveUid = ' . $uid . ' and cl.itemId = ' . $giftId;
            $queryGet = $this->modelsManager->createQuery($sqlGet);
            $getResult = $queryGet->execute();
            if($getResult->valid()){
                $getNum = intval($getResult->toArray()[0]['ttl']);
            }

            $sqlSend = 'select sum(cl.count) as ttl from \Micro\Models\ConsumeDetailLog as cl '
                . ' where cl.createTime >= ' . $thisMondayTime . ' and cl.createTime <= ' . $endTime . ' and cl.uid = ' . $uid . ' and cl.itemId = ' . $giftId;
            $querySend = $this->modelsManager->createQuery($sqlSend);
            $sendResult = $querySend->execute();
            if($sendResult->valid()){
                $sendNum = intval($sendResult->toArray()[0]['ttl']);
            }

            //,'configName'=>$configName

            return $this->status->retFromFramework($this->status->getCode('OK'), array('getNum'=>$getNum,'sendNum'=>$sendNum));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取周星排行榜数据
    public function getWeekStar($giftId = 0){
        try {

            $configName = '';
            if(!$giftId){
                $giftData= $this->getWeekStarGift();
                $giftId = $giftData['giftId'];
                $configName = $giftData['configName'];
            }

            $result = \Micro\Models\WeekStarLog::findFirst('giftId = ' . $giftId);

            $lastweekInfo = array();
            $thisweekInfo = array();
            if(!empty($result)){
                $lastweekInfo = json_decode($result->lastweekInfo, true);
                $thisweekInfo = json_decode($result->thisweekInfo, true);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('lastweekInfo'=>$lastweekInfo,'thisweekInfo'=>$thisweekInfo, 'configName'=>$configName));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    // 获取礼物信息
    private function getWeekStarGift(){
        try {
            $starGift = \Micro\Models\GiftConfigs::findfirst('typeId = ' . $this->config->weekStarType);
            if(!empty($starGift)){
                $giftId = $starGift->id;
                $configName = $starGift->configName;
            }else{
                $giftId = 0;
                $configName = '';
            }
            return array('giftId'=>$giftId,'configName'=>$configName);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //给用户发送通知
    public function sendUserNotice($uids, $content) {
        try {
            if ($uids) {
                $uidsArr = explode(',', $uids);
                $time = time();
                foreach ($uidsArr as $val) {
                    //给用户发送通知
                    $sendUser = UserFactory::getInstance($val);
                    $params['content'] = $content;
                    $params['link'] = '';
                    $params['operType'] = 0;
                    $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->official, $params);
                    //写入日志表
                    $log = new \Micro\Models\InvNoticeLog();
                    $log->uid = $val;
                    $log->content = $content;
                    $log->createTime = $time;
                    $log->operator = $this->username;
                    $log->save();
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //查询发送通知列表
    public function getNoticeLogList($page = 1, $pageSize = 20) {
        try {
            $sql = 'select l.*,ui.nickName from \Micro\Models\InvNoticeLog l inner join \Micro\Models\UserInfo ui on l.uid=ui.uid';
            $sql .= ' order by l.id desc';
            $limit = ($page - 1) * $pageSize;
            $sql.=' limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $data = $result->toArray();
            $count = \Micro\Models\InvNoticeLog::count();
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data' => $data, 'count' => $count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取家族旗下主播直播数据
    public function getLiveData($familyId = 0, $startTime = 0, $endTime = 0, $page = 1, $pageSize = 10){
        try {
            // 获取家族旗下主播
            $limit = ($page - 1) * $pageSize;
            $sql = 'select rl.publicTime,rl.endTime,(rl.endTime - rl.publicTime) as ttl,rl.status,ui.uid,ui.nickName,ui.avatar '
                . ' from \Micro\Models\RoomLog as rl '
                . ' left join \Micro\Models\Rooms as r on rl.roomId = r.roomId '
                . ' left join \Micro\Models\SignAnchor sa on r.uid = sa.uid '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = r.uid '
                . ' left join \Micro\Models\UserProfiles as up on ui.uid = up.uid '
                . ' where sa.familyId = ' . $familyId . ' and rl.status = 1 and rl.publicTime >= ' . $startTime . ' and rl.publicTime < ' . $endTime
                . ' order by ttl desc,up.level2 desc ';
            $query = $this->modelsManager->createQuery($sql . ' limit ' . $limit . ',' . $pageSize);
            $result = $query->execute();

            $list = array();
            $count = 0;
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['publicTime'] = $v->publicTime ? date('Y-m-d H:i:s',$v->publicTime) : '';
                    $tmp['endTime'] = $v->endTime ? date('Y-m-d H:i:s',$v->endTime) : '';
                    $tmp['ttl'] = $v->ttl;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $tmp['status'] = $v->status;
                    $list[] = $tmp;
                    unset($tmp);
                }
                $sqlCount = 'select count(1) as count from \Micro\Models\RoomLog as rl left join \Micro\Models\Rooms as r on rl.roomId = r.roomId '
                    . ' left join \Micro\Models\SignAnchor sa on r.uid = sa.uid '
                    . ' where sa.familyId = ' . $familyId . ' and rl.status = 1 and rl.publicTime >= ' . $startTime . ' and rl.publicTime < ' . $endTime;
                $queryCount = $this->modelsManager->createQuery($sqlCount);
                $countRes = $queryCount->execute();
                // var_dump($countRes->toArray()[0]['count']);die;
                $count = $countRes->valid() ? $countRes->toArray()[0]['count'] : 0;
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data' => $list, 'count' => $count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getFamilyAnchorInfo($uid){
        try {
            $sql = 'select sa.uid,sa.realName,sa.gender,sa.location,sa.birthday,sa.telephone,sa.qq,sa.email,sa.address from \Micro\Models\SignAnchor as sa '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = sa.uid '
                . ' where sa.uid = ' . $uid;
                // echo $sql;die;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            $info = array();
            if ($result->valid()) {
                $info['uid'] = $result[0]['uid'];
                $info['realName'] = $result[0]['realName'];
                $info['gender'] = $result[0]['gender'];
                $info['location'] = $this->config->location[$result[0]['location']]['name'] ? $this->config->location[$result[0]['location']]['name'] : $this->config->location[$this->config->signAnchorCityDefault]['name'];
                $info['birthday'] = date("Y-m-d", $result[0]['birthday']);
                $info['telephone'] = $result[0]['telephone'];
                $info['qq'] = $result[0]['qq'];
                $info['email'] = $result[0]['email'];
                $info['address'] = $result[0]['address'];
                $info['lifePhotoList'] = $this->getAnchorPhoto($uid, $this->config->photoType->lifePhoto);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), $info);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取推荐用户列表
     */
    public function getUserRecList($search = '', $page = 1, $pageSize = 10){
        try {
            $limit = ($page - 1) * $pageSize;
//            $sql = 'select ur.id,ur.uid,ur.url,ur.proportion,ur.validity,ur.createTime,ur.longUrl,ur.tinyUrl,ur.type,ur.utmSource,ur.utmMedium,ui.nickName,ui.avatar ' 
//                . ' from \Micro\Models\Recommend as ur '
//                . ' left join \Micro\Models\UserInfo as ui on ui.uid = ur.uid ';
            $sql = 'select ur.id,ur.uid,ur.url,ur.proportion,ur.validity,ur.createTime,ur.longUrl,ur.tinyUrl,ur.type,ur.utmSource,ur.utmMedium,ui.nickName,ui.avatar,ifnull(sum(al.money / al.proportion), 0) as allCash '
                    . ' from \Micro\Models\Recommend as ur '
                    . ' left join \Micro\Models\UserInfo as ui on ui.uid = ur.uid '
                    . ' left join \Micro\Models\ActivityIncomeLog al on ur.uid=al.uid and (al.type=2 or al.type=3) ';
            $where = ' where ur.status = 0 ';
            if ($search !== '') {
                $where .= ' and (ui.uid like "%' . $search . '%" or ui.nickName like "%' . $search . '%") ';
            }

            $condition= " group by ur.id order by allCash desc,ur.uid desc";
            
                
            $query = $this->modelsManager->createQuery($sql . $where . $condition . ' limit ' . $limit . ',' . $pageSize);
            $result = $query->execute();
            $list = array();
            $count = 0;
            if($result->valid()){
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['uid'] = $v->uid;
                    $tmp['longUrl'] = $v->longUrl ? $v->longUrl : $v->url;
                    $tmp['tinyUrl'] = $v->tinyUrl ? $v->tinyUrl : '';
                    $tmp['proportion'] = $v->proportion;
                    $tmp['validity'] = $v->validity;
                    $tmp['utmSource'] = $v->utmSource;
                    $tmp['utmMedium'] = $v->utmMedium;
                    $tmp['createTime'] = $v->createTime;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $tmp['type'] = $v->type;
                    $tmp['allCash'] = number_format($v->allCash,3);//累计充值
                    $list[] = $tmp;
                    unset($tmp);
                }

                $countSql = 'select count(1) as count from \Micro\Models\Recommend as ur '
                    . ' left join \Micro\Models\UserInfo as ui on ui.uid = ur.uid ' . $where;
                $queryCount = $this->modelsManager->createQuery($countSql);
                $countRes = $queryCount->execute();

                $count = $countRes->valid() ? $countRes->toArray()[0]['count'] : 0;
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data' => $list, 'count' => $count)); 
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 生成推广链接【新】
    public function addRecUrl($utm_source = '91ns', $utm_medium = '91ns', $uid = 0, $proportion = 0, $validity = 0, $remark = ''){
        try {

            $urlDi = $this->di->get('url');
            $normalLib = $this->di->get('normalLib');

            if($uid){// 新用户推广

                $userInfo = \Micro\Models\UserInfo::findfirst('uid = ' . $uid);

                if ($userInfo == false) {
                    return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
                }

                $info = \Micro\Models\Recommend::findfirst('uid = ' . $uid);

                if(empty($info)) {
                    $info = new \Micro\Models\Recommend();
                    $info->uid = $uid;
                    $info->createTime = time();
                    $info->url = '';
                }

                $type = 1;
                
            }else{// 广告推广

                $uid = 10089;//默认uid暂定

                $userInfo = \Micro\Models\UserInfo::findfirst('uid = ' . $uid);

                if ($userInfo == false) {
                    return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
                }

                $info = \Micro\Models\Recommend::findfirst(
                    'utmSource = "' . $utm_source . '" and utmMedium = "' . $utm_medium . '" and uid = ' . $uid
                );

                if(empty($info)){
                    $info = new \Micro\Models\Recommend();
                    $info->uid = $uid;
                    $info->createTime = time();
                }

                $info->url = '';
                $type = 2;

            }

            $urlStart = 'http://goto.91ns.com/tg';
            $longUrl = "$urlStart?uid={$uid}&utm_source={$utm_source}&utm_medium={$utm_medium}";

            // 生成短地址
            $result = $normalLib->getBaiduShortUrl($longUrl);
            if(!$result || $result['status'] != 0){
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
            }

            $tinyUrl = $result['tinyurl'];

            //生成二维码
            $imgName = $utm_source . '_' . $utm_medium . '_' . $uid . '.png';
            $filename = $this->pathGenerator->getRecommendqrcodePath($imgName);
            $logo = $this->pathGenerator->getRecommendqrcodePath('logo.png');
            $newImage = $normalLib->getQrcode($longUrl, $filename, $logo);
            $imagePath = $urlDi->getStatic($newImage);
            
            $info->status = 0;
            $info->utmSource = $utm_source;
            $info->utmMedium = $utm_medium;
            $info->longUrl = $longUrl;
            $info->tinyUrl = $tinyUrl;
            $info->imgPath = $imagePath;
            $info->proportion = $proportion ? $proportion : 5;
            $info->validity = $validity ? $validity : 360;
            $info->remark = $remark;
            $info->type = $type;
            $info->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function addRec($uid = 0, $proportion = 0, $validity = 0, $remark = ''){
        try {
            $urlDi = $this->di->get('url');
            $normalLib = $this->di->get('normalLib');
            $info = \Micro\Models\Recommend::findfirst("uid=" . $uid);
            $userInfo = \Micro\Models\UserInfo::findfirst($uid);
            if ($info) {
                if($info->status == 0){
                    return $this->status->retFromFramework($this->status->getCode('RECOMMENT_IS_ADDED'));
                }
                $url = $info->url;
                $newImage = $this->pathGenerator->getRecommendqrcodePath('qrcode_' . $uid . ".png");
                if (!file_exists($newImage)) {//二维码不存在
                    //生成二维码
                    $filename = $this->pathGenerator->getRecommendqrcodePath('qrcode_' . $uid . ".png");
                    $logo = $this->pathGenerator->getRecommendqrcodePath("logo.png");
                    $newImage = $normalLib->getQrcode($url, $filename, $logo);
                    $imagePath = $urlDi->getStatic($newImage);
                } else {//二维码已存在
                    $imagePath = $urlDi->getStatic($newImage);
                }
                $info->status = 0;
                $info->proportion = $proportion;
                $info->validity = $validity;
                $info->remark = $remark;
                $info->save();
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
            if ($userInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
            }

            $domin = 'http://m.91ns.com';
            $key = "91ns.com_";
            $str = urlencode(base64_encode($key . $uid));
            $url = $domin . '/activities/recommendReceive?str=' . $str;
            $new = new \Micro\Models\Recommend();
            $new->uid = $uid;
            $new->url = $url;
            $new->proportion = $proportion;
            $new->validity = $validity;
            $new->remark = $remark;
            $new->createTime = time();
            $new->status = 0;
            $new->save();

            //生成二维码
            $filename = $this->pathGenerator->getRecommendqrcodePath('qrcode_' . $uid . ".png");
            $logo = $this->pathGenerator->getRecommendqrcodePath("logo.png");
            $newImage = $normalLib->getQrcode($url, $filename, $logo);
            $imagePath = $urlDi->getStatic($newImage);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 根据uid获取昵称
    public function getNicknameByUid($uid = 0){
        try {
            $res = \Micro\Models\UserInfo::findFirst('uid = ' . $uid);
            if(!empty($res)){
                $nickName = $res->nickName;
                return $this->status->retFromFramework($this->status->getCode('OK'),array('nickName'=>$nickName));
            }
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 删除推广用【逻辑删除】
    public function delRec($id = 0){
        try {
            $res = \Micro\Models\Recommend::findFirst('id = ' . $id);
            if(empty($res)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            $res->status = 1;
            $res->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 新用户推广详情列表
    public function getRecDetailList($uid = 0, $page = 1, $pageSize = 10){
        try {
            $limit = ($page - 1) * $pageSize;
            $sql = 'select rl.id,rl.beRecUid,rl.createTime,ui.nickName,ui.avatar from \Micro\Models\RecommendLog as rl '
                . ' left join \Micro\Models\UserInfo as ui on rl.beRecUid = ui.uid '
                . ' where rl.beRecUid > 0 and rl.recUid = ' . $uid . ' group by rl.beRecUid order by rl.createTime desc limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $list = array();
            $count = 0;
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $totalFee = \Micro\Models\Order::sum(array('column'=>'totalFee','conditions'=>'status = 1 and uid = ' . $v->beRecUid));
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['beRecUid'] = $v->beRecUid;
                    $tmp['createTime'] = $v->createTime;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['totalFee'] = $totalFee ? $totalFee : 0;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $list[] = $tmp;
                    //unset($tmp);
                }
                // $count = \Micro\Models\RecommendLog::count('beRecUid > 0 and recUid = ' . $uid . ' group by beRecUid');
            }
            $countSql = 'select count(distinct beRecUid) as count from \Micro\Models\RecommendLog where beRecUid > 0 and recUid = ' . $uid;
            $countQuery = $this->modelsManager->createQuery($countSql);
            $countRes = $countQuery->execute();
            $count = $countRes->valid() ? $countRes->toArray()[0]['count'] : 0;

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$list,'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 新用户推广详情列表
    public function getRecDetailsList($search = '', $page = 1, $pageSize = 10){
    	try {
    		$limit = ($page - 1) * $pageSize;
    	    if ($search !== '') {
    			$sqlstr = ' and (ui.uid like "%' . $search . '%" or ui.nickName like "%' . $search . '%") ';
    		}
    		$sql = 'select rl.id,rl.beRecUid,rl.createTime,ui.nickName,ui.avatar,rl.recUid,ui1.nickName as recName from \Micro\Models\RecommendLog as rl '
    				. ' left join \Micro\Models\UserInfo as ui on rl.beRecUid = ui.uid '
    						. ' left join \Micro\Models\UserInfo as ui1 on rl.recUid = ui1.uid '
    								. ' where rl.beRecUid > 0 ' . $sqlstr . ' group by rl.beRecUid order by rl.createTime desc limit ' . $limit . ',' . $pageSize;
    		$query = $this->modelsManager->createQuery($sql);
    		$res = $query->execute();
    
    		$list = array();
    		$count = 0;
    		if($res->valid()){
    			foreach ($res as $k => $v) {
    				$totalFee = \Micro\Models\Order::sum(array('column'=>'totalFee','conditions'=>'status = 1 and uid = ' . $v->beRecUid));
    				$tmp = array();
    				$tmp['id'] = $v->id;
    				$tmp['beRecUid'] = $v->beRecUid;
    				$tmp['createTime'] = $v->createTime;
    				$tmp['nickName'] = $v->nickName;
    				$tmp['totalFee'] = $totalFee ? $totalFee : 0;
    				$tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
    				$tmp['recUid'] = $v->recUid;
    				$tmp['recName'] = $v->recName;
    				$list[] = $tmp;
    				//unset($tmp);
    			}
    			// $count = \Micro\Models\RecommendLog::count('beRecUid > 0 and recUid = ' . $uid . ' group by beRecUid');
    		}
    		$countSql = 'select count(distinct beRecUid) as count from \Micro\Models\RecommendLog as rl '
    				. ' left join \Micro\Models\UserInfo as ui on rl.beRecUid = ui.uid '.
    				'where beRecUid > 0 '.$sqlstr;
    		$countQuery = $this->modelsManager->createQuery($countSql);
    		$countRes = $countQuery->execute();
    		$count = $countRes->valid() ? $countRes->toArray()[0]['count'] : 0;
    
    		return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$list,'count'=>$count));
    	} catch (\Exception $e) {
    		return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
    	}
    }
    // 获取新用户推广抽成记录列表
    public function getBonusList($uid = 0,$startTime, $endTime, $search = '', $page = 1, $pageSize = 10){
        try {
            $limit = ($page - 1) * $pageSize;
            $sql = 'select al.id,al.money,al.createTime,al.proportion,ui.nickName,ui.avatar,al.remark '
                . ' from \Micro\Models\ActivityIncomeLog as al left join \Micro\Models\UserInfo as ui on al.remark = ui.uid ';
            $condition = ' where (al.type = 2 or al.type = 3) and al.uid = ' . $uid . ' and al.createTime >= ' . $startTime . ' and al.createTime < ' . $endTime;
            if($search !== ''){
                $condition .= ' and (al.remark like "%' . $search . '%" or ui.nickName like "%' . $search . '%") ';
            }

            $query = $this->modelsManager->createQuery($sql . $condition . ' order by al.createTime desc limit ' . $limit . ',' . $pageSize);
            $res = $query->execute();

            $list = array();
            $count = 0;
            $allCash = 0;
            $allMoney = 0;
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['money'] = $v->money / $this->config->cashScale;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['uid'] = $v->remark;
                    $tmp['proportion'] = $v->proportion;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    $tmp['createTime'] = $v->createTime;
                    $tmp['total'] = $v->money / $v->proportion;
                    $list[] = $tmp;
                    unset($tmp);
                }
                $sqlCount = 'select count(1) as count from \Micro\Models\ActivityIncomeLog as al left join \Micro\Models\UserInfo as ui on al.remark = ui.uid ' . $condition;
                $queryCount = $this->modelsManager->createQuery($sqlCount);
                $resCount = $queryCount->execute();
                $count = $resCount->valid() ? $resCount->toArray()[0]['count'] : 0;

                $sqlTotal = 'select ifnull(sum(al.money / al.proportion), 0) as allCash, ifnull(sum(al.money * 0.01), 0) as allMoney '
                    . ' from \Micro\Models\ActivityIncomeLog as al left join \Micro\Models\UserInfo as ui on al.remark = ui.uid '
                    . $condition;
                $queryTotal = $this->modelsManager->createQuery($sqlTotal);
                $resTotal = $queryTotal->execute();
                $resTotalArr = $resTotal->valid() ? $resTotal->toArray()[0] : array('allCash'=>0,'allMoney'=>0); 
                $allCash = number_format($resTotalArr['allCash'], 3);
                $allMoney = number_format($resTotalArr['allMoney'], 3);
            }

            return $this->status->retFromFramework(
                $this->status->getCode('OK'), 
                array('data'=>$list,'count'=>$count,'allCash'=>$allCash,'allMoney'=>$allMoney)
            );

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 修改新用户推荐信息
    public function editRecInfo($uid = 0, $type = 1, $editInfo = ''){
        try {
            $res = \Micro\Models\Recommend::findFirst('uid = ' . $uid);
            if(empty($res)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            switch ($type) {
                case 1:
                    $res->proportion = $editInfo;
                    break;

                case 2:
                    $res->validity = $editInfo;
                    break;

                case 3:
                    $res->remark = $editInfo;
                    break;

                default:
                    $res->proportion = $editInfo;
                    break;
            }

            if($res->save()){
                return $this->status->retFromFramework($this->status->getCode('OK')); 
            }

            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //主播用海报列表 add by 2015/10/20
    public function getAllAnchorCoverList($page=1, $pageSize = 20,$nickName='') {
        $postData['id'] = $pageSize;
        $postData['uid'] = $page;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $exp = '';
            if ($nickName) {
                $exp.=" and ui.nickName='%{$nickName}%' ";
            }
            $limit = ($page - 1) * $pageSize;
            $sql = "select ui.nickName,p.id,p.createTime,p.status,p.auditor,p.auditTime,p.imageUrl"
                    . " from \Micro\Models\AnchorPoster p inner join \Micro\Models\UserInfo ui on ui.uid=p.uid "
                    . " where p.isShow=1 " . $exp
                    . " order by p.status asc,p.createTime desc"
                    . " limit " . $limit . "," . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $list = $query->execute();
            $return = array();
            $result = array();
            foreach ($list as $val) {
                $data['id'] = $val->id;
                $data['nickName'] = $val->nickName;
                $data['imageUrl'] = $val->imageUrl;
                $data['createTime'] = $val->createTime;
                $data['status'] = $val->status;
                $data['auditor'] = $val->auditor;
                $data['auditTime'] = $val->auditTime;
                $result[] = $data;
                unset($data);
            }
            $count = \Micro\Models\AnchorPoster::count("isShow=1".$exp);
            $return['list'] = $result;
            $return['count'] = $count;
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
  
    //审核某个主播封面 add by 2015/10/21
    public function auditAnchorCover($id, $status) {
        $postData['id'] = $id;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        try {
            $info = \Micro\Models\AnchorPoster::findfirst("id=" . $id . " and isShow=1");
            if ($info == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if ($info->status != 0) {
                return $this->status->retFromFramework($this->status->getCode('ACTION_NO_ALLOW'));
            }
            $info->status = $status;
            $info->auditor = 1;
            $info->auditTime = time();
            $info->save();
            
            if ($status == 1) {//审核通过
                //给主播发送通知
                $sendUser = UserFactory::getInstance($info->uid);
                $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->anchorPosterSuccess, array(0 => $info->imageUrl));
                $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
            } elseif ($status == 2) {//审核不通过
                //给主播发送通知
                $sendUser = UserFactory::getInstance($info->uid);
                $content = $sendUser->getUserInformationObject()->getInfoContent($this->config->informationCode->anchorPosterFail, array(0 => $info->imageUrl));
                $sendUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    //导出考勤表/收益表 导出excel add by 2015/11/06
    public function anchorWorkDataExcel($isFamily = 1, $startDate = 0, $endDate = 0, $type = 1, $nickName = '') {
        try {
            $today = date("Y-m-d");
            !$startDate && $startDate = $today;
            !$endDate && $endDate = $today;
            /*if($type == 1){//考勤
                $startTime = strtotime($startDate);
            }else{
                $startTime = strtotime($startDate) + 86400;
            }*/
            $startTimeJob = strtotime($startDate);
            $startTime = $startTimeJob + 86400;
            $endTime = strtotime($endDate) + 86400;
            $where = '1 ';
            if ($nickName) {
                $where.=" and (s.uid like '%{$nickName}%' or s.realName like '%{$nickName}%' or ui.nickName like '%{$nickName}%')";
            }
            if ($isFamily) {//是否有家族
                $where.=" and s.familyId>0 ";
            } else {
                $where.=" and s.familyId=0 ";
            }
            $connection = $this->di->get('db');

            $result['sheetName'] = '导出主播收益考勤数据';
            $result['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
            $result['list'][] = array('', '');
            $result['list'][] = array('UID', '昵称', '家族', '姓名', '工作天数', '工作总时长',
                '礼物总收入(聊币)', '游戏提成(聊币)', '已兑换聊币', '剩余总收入(聊币)', 
                '开户姓名', '开户银行', '银行卡号', '电话');
            $result['list'][] = array('', '');
            $data = array();
            $sql = "select s.uid,s.realName,a.roomId,count(1) as count,sum(time) as sum,ui.nickName,f.name as familyName,s.bank,s.cardNumber,s.accountName,s.idCard,s.telephone "
                . "from(select l.roomId,sum(endTime-publicTime) as time,FROM_UNIXTIME(publicTime,'%Y%m%d')days from pre_room_log l where l.status=1 and l.endTime>=l.publicTime and l.publicTime>{$startTimeJob} and l.endTime<{$endTime} group by days,roomId order by null) a "
                . "inner join pre_rooms r on a.roomId=r.roomId inner join pre_sign_anchor s on s.uid=r.uid inner join pre_user_info ui on s.uid=ui.uid "
                . " left join pre_family f on f.id=s.familyId and s.familyId>0 "
                . "where {$where} group by a.roomId order by null";
            $res = $connection->fetchAll($sql);
            foreach ($res as $val) {
                $sumTime = $this->secToTime($val['sum']);
                if(array_key_exists($val['uid'], $data)){
                    $data[$val['uid']][4] = $val['count'];
                    $data[$val['uid']][5] = $sumTime;
                }else{
                    $data[$val['uid']] = array(
                        $val['uid'], $val['nickName'], $val['familyName'], $val['realName'],
                        $val['count'], $sumTime, 0, 0, 0, 0, $val['accountName'], $val['bank'], " ".$val['cardNumber'], $val['telephone']
                    ); 
                }
            }
            $incomesql = "select s.uid,s.realName,sum(l.myIncome) sum,ui.nickName,f.name as familyName,s.bank,s.cardNumber,s.accountName,s.idCard,s.telephone "
                    . " from pre_day_gifts_log l inner join pre_sign_anchor s on l.uid=s.uid "
                    . "left join pre_family f on f.id=s.familyId and s.familyId>0 "
                    . "inner join pre_user_info ui on s.uid=ui.uid "
                    . "where {$where} and l.createTime>={$startTime} and l.createTime<={$endTime} group by l.uid order by null";
            $incomeres = $connection->fetchAll($incomesql);
            foreach ($incomeres as $val) {
                //兑换
                $settleCash = 0;
                $settleRes = \Micro\Models\ChangeLog::sum(
                    array(
                        'column' => 'money',
                        'conditions' => 'uid = ' . $val['uid'] . ' and status = 1 and type = 3'
                            . ' and createTime >= ' . $startTimeJob . ' and createTime < ' . $endTime
                    )
                );
                $settleCash = abs($settleRes) * $this->config->cashScale;
                //游戏提成
                $gameCash = 0;
                $gameRes = \Micro\Models\GameDeductDayLog::sum(
                    array(
                        'column' => 'cash',
                        'conditions' => 'uid = ' . $val['uid'] . ' and createTime >= ' . $startTime . ' and createTime <= ' . $endTime
                    )
                );
                $gameCash = $gameRes ? $gameRes : 0;
                if(array_key_exists($val['uid'], $data)){
                    $data[$val['uid']][6] = $val['sum'];
                    $data[$val['uid']][7] = $gameCash;
                    $data[$val['uid']][8] = $settleCash;
                    $data[$val['uid']][9] = $val['sum'] + $gameCash - $settleCash;
                }else{
                    $data[$val['uid']] = array(
                        $val['uid'], $val['nickName'], $val['familyName'], $val['realName'], 0, 0, 
                        $val['sum'], $gameCash, $settleCash, ($val['sum'] + $gameCash - $settleCash), 
                        $val['accountName'], $val['bank'], " ".$val['cardNumber'], $val['telephone']
                    ); 
                }
            }
            if($data){
                foreach ($data as $value) {
                    $result['list'][] = $value;
                }
            }
            $excelResult[] = $result;
            //生成excel
            $fileName = '收益表_' . $startDate . ' -- ' . $endDate; //excel文件名
            $normalLib = $this->di->get('normalLib');
            $normalLib->toExcel($fileName, $excelResult);
            /*if ($type == 1) {//考勤
                $sql = "select s.uid,s.realName,a.roomId,count(1) as count,sum(time) as sum,ui.nickName,f.name as familyName "
                        . "from(select l.roomId,sum(endTime-publicTime) as time,FROM_UNIXTIME(publicTime,'%Y%m%d')days from pre_room_log l where l.status=1 and l.endTime>=l.publicTime and l.publicTime>{$startTime} and l.endTime<{$endTime} group by days,roomId order by null) a "
                        . "inner join pre_rooms r on a.roomId=r.roomId inner join pre_sign_anchor s on s.uid=r.uid inner join pre_user_info ui on s.uid=ui.uid "
                        . " left join pre_family f on f.id=s.familyId and s.familyId>0 "
                        . "where {$where} group by a.roomId order by null";
                $res = $connection->fetchAll($sql);
                $result['sheetName'] = '考勤数据';
                $result['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
                $result['list'][] = array('', '');
                $result['list'][] = array('UID', '昵称', '家族', '姓名', '工作天数', '工作总时长');
                $result['list'][] = array('', '');
                foreach ($res as $val) {
                    $sumTime = $this->secToTime($val['sum']);
                    $result['list'][] = array($val['uid'], $val['nickName'], $val['familyName'], $val['realName'], $val['count'], $sumTime);
                }
                $excelResult[] = $result;
                //生成excel
                $fileName = '考勤表_' . $startDate . ' -- ' . $endDate; //excel文件名
                $normalLib = $this->di->get('normalLib');
                $normalLib->toExcel($fileName, $excelResult);
            } elseif ($type == 2) {//收益
                $incomesql = "select s.uid,s.realName,sum(l.myIncome) sum,ui.nickName,f.name as familyName  "
                        . " from pre_day_gifts_log l inner join pre_sign_anchor s on l.uid=s.uid "
                        . "left join pre_family f on f.id=s.familyId and s.familyId>0 "
                        . "inner join pre_user_info ui on s.uid=ui.uid "
                        . "where {$where} and l.createTime>={$startTime} and l.createTime<={$endTime} group by l.uid order by null";
                $incomeres = $connection->fetchAll($incomesql);
                $result['sheetName'] = '收益数据';
                $result['list'][] = array('查询时间区间：', $startDate . '至' . $endDate);
                $result['list'][] = array('', '');
                $result['list'][] = array('UID', '昵称', '家族', '姓名', '礼物总收入（聊币）');
                $result['list'][] = array('', '');
                foreach ($incomeres as $val) {
                    $result['list'][] = array($val['uid'], $val['nickName'], $val['familyName'], $val['realName'], $val['sum']);
                }
                $excelResult[] = $result;
                //生成excel
                $fileName = '收益表_' . $startDate . ' -- ' . $endDate; //excel文件名
                $normalLib = $this->di->get('normalLib');
                $normalLib->toExcel($fileName, $excelResult);
            }*/
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取录像列表
    public function getRECList($date = '', $search = '', $page = 1, $pageSize = 20){
        try {
            /*$lastDate = date('Y-m-d',strtotime('-1 days'));
            !$date && $date = $lastDate;
            $startTime = strtotime($date);
            $endTime = $startTime + 86399;*/
            $lastDate = date('Y-m-d',strtotime('-1 days'));
            !$date && $date = $lastDate;
            $createTime = strtotime($date) + 86400;
            if(!$page){
                $page = 1;
            }
            if(!$pageSize){
                $pageSize = 20;
            }
            $limit = ($page - 1) * $pageSize;

            // $condition = ' where v.createTime >= ' . $startTime . ' and v.createTime <= ' . $endTime;
            $condition = ' where v.createTime = ' . $createTime;
            if($search != ''){
                $condition .= ' and (ui.uid like "%' . $search . '%" or ui.nickName like "%' . $search . '%") ';
            }
            $sqlAnchors = 'select v.uid,ui.nickName,ui.avatar from \Micro\Models\VideoReview as v ' 
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = v.uid '
                . $condition . ' group by v.uid order by v.uid asc' . ' limit ' . $limit . ',' . $pageSize;
            $queryAnchors = $this->modelsManager->createQuery($sqlAnchors);
            $resAnchors = $queryAnchors->execute();

            $data = array();
            if($resAnchors->valid()){
                foreach ($resAnchors as $k => $v) {
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['avatar'] = $v->avatar ? $v->avatar : $this->pathGenerator->getFullDefaultAvatarPath();
                    /*$tmpRes = \Micro\Models\Videos::find(
                        'status = 0 and uid = ' . $v->uid . 
                        ' and createTime >= ' . $startTime . ' and createTime < ' . $endTime . 
                        ' order by createTime asc'
                    );*/
                    $tmpRes = \Micro\Models\VideoReview::find(
                        ' uid = ' . $v->uid . 
                        ' and createTime = ' . $createTime .
                        ' order by publicTime asc'
                    );
                    $tmp['RECList'] = $tmpRes->toArray();
                    array_push($data, $tmp);
                }
            }

            $countSql = 'select v.uid as count from \Micro\Models\VideoReview as v left join \Micro\Models\UserInfo as ui on ui.uid = v.uid '
                . $condition . ' group by v.uid';
            $queryCount = $this->modelsManager->createQuery($countSql);
            $resCount = $queryCount->execute();
            $count = count($resCount);

            return $this->status->retFromFramework(
                $this->status->getCode('OK'), 
                array('data'=>$data,
                    'count'=>$count,
                    'RECConfig'=>$this->config->RECInfo->toArray(),
                    'swfUrl'=>$this->config->url->swfUrl,
                )
            );
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //投注分布统计
    public function getBettingStat($type = 1, $startDate = '', $endDate = ''){
        try {
            !$startDate && $startDate = date('Y-m-d',strtotime('-1 days'));
            !$endDate && $endDate = $startDate;
            $startTime = strtotime($startDate);
            $endTime = strtotime($endDate) + 86399;

            if ($endTime - $startTime < 86400) {//如果是同一天的时间，则按小时统计
                $dataFormat = "%H";
                $timeType = "hour";
            } else {
                $dataFormat = "%Y%m%d"; //按天统计
                $timeType = "day";
            }

            $condition = " createTime >= " . $startTime . " and createTime < " . $endTime;
            switch ($type) {
                case 1:
                    //注数
                    $calcField = "ifnull(sum(nums), 0) as ttl";
                    break;

                case 2:
                    //积分
                    $calcField = "ifnull(sum(nums), 0) as ttl";
                    break;

                case 3:
                    //用户
                    $calcField = "count(DISTINCT uid) as ttl";
                    break;
                
                default:
                    //注数
                    $calcField = "ifnull(sum(nums), 0) as ttl";
                    break;
            }

            $return = array();

            //获取折线图数据
            $lineArr = array('all'=>array(),'points'=>array(),'cash'=>array());##0：总，1：积分，2：聊币
            $groupAll = " group by time ";
            $groupOne = " group by time,kind ";
            $sqlLine = "select DATE_FORMAT(from_unixtime(createTime), '{$dataFormat}') as time,if(kind = 1, 'points', 'cash') as kind, " . $calcField
                . " from pre_bet_points_log "
                . " where platform != 0 and createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "'";
            $sqlLineOne = $sqlLine . $groupOne;
            $sqlLineAll = $sqlLine . $groupAll;
            ##按投注类型
            $listOne = $this->db->fetchAll($sqlLineOne);
            if ($listOne) {
                foreach ($listOne as $v) {
                    $lineArr[$v['kind']][$v['time']] = $v['ttl'];
                }
            }
            ##总的不区分投注类型
            $listAll = $this->db->fetchAll($sqlLineAll);
            if ($listAll) {
                foreach ($listAll as $k => $v) {
                    $lineArr['all'][$v['time']] = $v['ttl'];
                }
            }
            foreach ($lineArr as $k => $v) {
                $result = $this->getDataByDates($timeType, $v, date('Y-m-d', $startTime), date('Y-m-d', $endTime));
                $return['lineData'][$k] = $result;
            }

            ##饼状图
            $cakeData = array('ttl'=>0,'points'=>0,'cash'=>0);
            //
            $sqlCakeOne = "select if(bp.kind = 1, 'points', 'cash') as kind, " . $calcField
                . " from pre_bet_points_log as bp "
                . " where bp.platform != 0 and bp.createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "' group by bp.kind";
            $listCakeOne = $this->db->fetchAll($sqlCakeOne);
            if ($listCakeOne) {
                foreach ($listCakeOne as $v) {
                    $cakeData[$v['kind']] = $v['ttl'];
                }
            }
            $sqlCakeAll = "select " . $calcField
                . " from pre_bet_points_log as bp "
                . " where bp.platform != 0 and bp.createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "'";
            $listCakeAll = $this->db->fetchAll($sqlCakeAll);
            if ($listCakeAll) {
                foreach ($listCakeAll as $v) {
                    $cakeData['ttl'] = $v['ttl'];
                }
            }

            ##表格
            $goodsConfigs = \Micro\Models\GoodsConfigs::find();
            $fillArr = array('ttl'=>0,'points'=>0,'cash'=>0);
            $tableData = array(0=>array('id'=>0,'name'=>'所有','all'=>$cakeData,'pc'=>$fillArr,'ios'=>$fillArr,'android'=>$fillArr));
            foreach ($goodsConfigs as $value) {
                $tableData[$value->id] = array(
                    'id' => $value->id,
                    'name' => $value->name,
                    'all' => $fillArr,
                    'pc' => $fillArr,
                    'ios' => $fillArr,
                    'android' => $fillArr
                );
            }
            //按商品---平台
            $platArray = array("pc" => 1, "ios" => 2, "android" => 3); //平台
            foreach ($platArray as $k => $v) {
                //平台总计
                $sqlCakeOne = "select if(bp.kind = 1, 'points', 'cash') as kind, " . $calcField
                    . " from pre_bet_points_log as bp "
                    . " where bp.platform = " . $v . " and bp.createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "' group by bp.kind";
                $listCakeOne = $this->db->fetchAll($sqlCakeOne);
                if ($listCakeOne) {
                    foreach ($listCakeOne as $vc1) {
                        $tableData[0][$k][$vc1['kind']] = $vc1['ttl'];
                    }
                }
                $sqlCakeAll = "select " . $calcField
                    . " from pre_bet_points_log as bp "
                    . " where bp.platform = " . $v . " and bp.createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "'";
                $listCakeAll = $this->db->fetchAll($sqlCakeAll);
                if ($listCakeAll) {
                    foreach ($listCakeAll as $vc2) {
                        $tableData[0][$k]['ttl'] = $vc2['ttl'];
                    }
                }

                //平台商品
                $sqlTmpAll = "select bp.type, " . $calcField//,gc.name
                    . " from pre_bet_points_log as bp "//left join pre_goods_configs as gc on gc.id = bp.type 
                    . " where bp.platform = " . $v . " and bp.createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "' group by bp.type";
                $listTmpAll = $this->db->fetchAll($sqlTmpAll);
                if ($listTmpAll) {
                    foreach ($listTmpAll as $v1) {
                        $tableData[$v1['type']][$k]['ttl'] = $v1['ttl'];
                        $sqlTmpOne = "select if(kind = 1, 'points', 'cash') as kind," . $calcField . " from pre_bet_points_log "
                            . " where type = " . $v1['type'] . " and platform = " . $v . " and createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "' group by kind";
                        $listTmpOne = $this->db->fetchAll($sqlTmpOne);

                        if ($listTmpOne) {
                            foreach ($listTmpOne as $v2) {
                                $tableData[$v1['type']][$k][$v2['kind']] = $v2['ttl'];
                            }
                        }
                    }
                }
            }
            //根据商品---总计
            $sqlAll = "select bp.type, " . $calcField
                . " from pre_bet_points_log as bp"
                . " where bp.platform != 0 and bp.createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "' group by bp.type";
            $listAll = $this->db->fetchAll($sqlAll);
            if ($listAll) {
                foreach ($listAll as $val) {
                    $tableData[$val['type']]['all']['ttl'] = $val['ttl'];

                    $sqlTmpOne = "select if(kind = 1, 'points', 'cash') as kind," . $calcField . " from pre_bet_points_log "
                        . " where type = " . $val['type'] . " and platform != 0 and createTime BETWEEN '" . $startTime . "' AND '" . $endTime . "' group by kind";
                    $listTmpOne = $this->db->fetchAll($sqlTmpOne);

                    if ($listTmpOne) {
                        foreach ($listTmpOne as $val1) {
                            $tableData[$val['type']]['all'][$val1['kind']] = $val1['ttl'];
                        }
                    }
                }
            }
            $return['tableData'] = $tableData;
            $return['cakeData'] = $cakeData;

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }


    //获取开奖列表
    public function getRecentBetRes($page = 1, $pageSize = 20){
        try {
            !$page && $page = 1;
            !$pageSize && $pageSize = 20;
            $limit = ($page - 1) * $pageSize;
            $sql = 'select gc.totalNums,gc.id,bpr.times,bpr.openTime,gc.name,gc.price,gc.img,gc.type,ui.nickName,ui.uid,ui.telephone '
                . ' from \Micro\Models\BetPointsResultLog as bpr '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = bpr.uid '
                . ' left join \Micro\Models\GoodsConfigs as gc on bpr.type = gc.id '
                . ' where bpr.status = 1 order by bpr.openTime desc limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();
            $data = array();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['totalNums'] = $v->totalNums;
                    $tmp['type'] = $v->type;
                    $tmp['times'] = $v->times;
                    $tmp['id'] = $v->id;
                    $tmp['name'] = $v->name;
                    $tmp['price'] = $v->price;
                    $tmp['img'] = $v->img;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['uid'] = $v->uid;
                    $tmp['mobile'] = $v->telephone;
                    $tmp['openTime'] = $v->openTime;
                    $tmp['winnerBetNums'] = $this->getWinnerBetNums($v->id, $v->times, $v->uid);
                    array_push($data, $tmp);
                }
            }

            $count = \Micro\Models\BetPointsResultLog::count('status = 1');

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$data,'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取该用户投的注数
    private function getWinnerBetNums($type = 0, $times = 0, $uid = 0){
        try {
            $betNumRes = \Micro\Models\BetPointsLog::sum(
                array('column'=>'nums','conditions'=>'times = ' . $times . ' and type = ' . $type . ' and uid = ' . $uid)
            );

            $betNums = $betNumRes ? $betNumRes : 0;
            return $betNums;
        } catch (\Exception $e) {
            $this->errLog('getWinnerBetNums errorMessage = ' . $e->getMessage());
            return 0;
        }
    }

    //获取酒水列表
    public function getWineList($price = 100){
        try {
            $sql = 'select gc.name,gc.description,gc.id,gc.totalNums,ifnull(ui.nickName, "") as nickName,gc.type '
                . ' from \Micro\Models\GoodsConfigs as gc left join \Micro\Models\UserInfo as ui on ui.uid = gc.type '
                . ' where gc.isShow = 0 and (gc.type = 0 or gc.type > 10) and price = ' . $price;

            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $data = array();
            if($res->valid()){
                $data = $res->toArray();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //编辑酒水
    public function editWineInfo($id = 0, $uid = 0, $description = ''){
        try {
            !$id && $id = 0;
            !$uid && $uid = 0;
            $isValid = $this->validator->validate(array('id'=>$id));
            if (!$isValid) {
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }
            $wineData = \Micro\Models\GoodsConfigs::findFirst('isShow = 0 and id = ' . $id);
            if(empty($wineData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            //判断用户是否分配过
            if($uid){
                $isValid = $this->validator->validate(array('uid'=>$uid));
                if (!$isValid) {
                    return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
                }
                $winePrice = $wineData->price;
                $userWine = \Micro\Models\GoodsConfigs::findFirst('id != ' . $id . ' and isShow = 0 and type = ' . $uid . ' and price = ' . $winePrice);
                if(!empty($userWine)){
                    return $this->status->retFromFramework($this->status->getCode('ONE_WINE_NOT_ALLOWED_REPEAT'));
                }
            }

            //检查是否有投注记录了且所属uid是否相同
            $betRes = \Micro\Models\BetPointsLog::findFirst('type = ' . $id);
            if(!empty($betRes) && $wineData->type != $uid){
                return $this->status->retFromFramework($this->status->getCode('THIS_WINE_HAS_ALLOCATED'));
            }

            if(!$wineData->type && $uid){
                $connection = $this->di->get('db');
                $newSql = "insert into pre_bet_points_result_log (uid,times,type,createTime,remark,status,openTime) values (0,1,$id,".time().",'',0,0)";
                $connection->execute($newSql);
            }

            $wineData->description = $description;
            $wineData->type = $uid;
            $wineData->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //添加对应酒水价格的商品
    public function addWine($price = 100){
        try {
            !$price && $price = 100;
            for($i = 0; $i < 100; $i++){
                $new = new \Micro\Models\GoodsConfigs();
                $new->name = $price . '元酒水券';
                $new->price = $price;
                $new->description = $price . '元酒水券';
                $new->totalNums = $price;
                $new->totalNums = $price;
                $new->perPoint = 500;
                $new->perCash = 100;
                $new->type = 0;
                $new->isShow = 0;
                $new->createTime = time();
                $new->img = 'jsq'.$price;
                $new->orderType = 0;
                $new->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取活动收益
    public function getActivityIncomeDayLog($uid = 0, $date = '', $page = 1, $pageSize = 20){
        try {
            if (!$date) {//默认当月
                $day = date('d');
                if ($day >= 12) {
                    $startTime = strtotime(date('Y-m-12'));
                } else {
                    $startTime = strtotime(date('Y-m-12', strtotime('-1 month', strtotime(date('Y-m-01')))));
                }
                $endTime = time();
                $inTime = date('Y-m-12', strtotime('+1 month', strtotime(date('Y-m-01', $startTime))));
            } else {
                $startTime = strtotime(date('Y-m-12', strtotime('-1 month', strtotime(date('Y-m-01', strtotime($date))))));
                $endTime = strtotime(date('Y-m-11', strtotime($date)));
                $inTime = date('Y-m-12', $endTime);
            }

            $select_page = 'select dg.uid,dg.createTime,dg.money,dg.type,dg.remark,ui.nickName';
            $where = ' where dg.uid = ' . $uid . ' and dg.createTime >= ' . $startTime . ' and dg.createTime <= ' . $endTime;

            $select_count = 'select count(1) as count ';

            $table = ' from \Micro\Models\ActivityIncomeLog as dg left join \Micro\Models\UserInfo as ui on dg.remark = ui.uid and dg.remark>0 ';

            $limit = ($page - 1) * $pageSize;
            $page_condition = ' order by dg.createTime desc limit ' . $limit . ',' . $pageSize;

            $page_sql = $select_page . $table . $where . $page_condition;
            
                
            $query_page = $this->modelsManager->createQuery($page_sql);
            $result = $query_page->execute();

            $data = array();
            $count = 0;
            if ($result->valid()) {
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['type'] = $v->type;
                    $tmp['income'] = $v->money;
                    $tmp['desc'] = $this->config->activityIncomeType[$v->type];
                    $tmp['source'] = $v->type == 1 ? '91NS' : $v->nickName . "(" . $v->remark . ")";
                    $tmp['createTime'] = date('Y-m-d', $v->createTime);
                    $data[] = $tmp;
                    unset($tmp);
                }
                //获取count值
                $count_sql = $select_count . $table . $where;
                $query_count = $this->modelsManager->createQuery($count_sql);
                $count_res = $query_count->execute();
                $count = $count_res[0]['count'];
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data' => $data, 'count' => $count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取游戏提成明细
    public function getGameDeductDetail($uid = 0, $date = '', $page = 1, $pageSize = 20){
        try {
            if($date){
                $createTime = strtotime($date);
            }else{
                $createTime = strtotime(date('Y-m-d'));
            }

            !$uid && $uid = 0;
            !$page && $page = 1;
            !$pageSize && $pageSize = 20;
            $limit = ($page - 1) * $pageSize;

            $sql = 'select dd.percentage,dd.deductTime,dd.gameType,dd.dealerUid,dd.remark,ui.nickName from \Micro\Models\GameDeductDetailLog as dd '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = dd.dealerUid '
                . ' where dd.anchorUid = ' . $uid . ' and dd.createTime = ' . $createTime
                . ' order by dd.deductTime limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();

            $data = array();
            if($result->valid()){
                foreach ($result as $v) {
                    $tmp = array();
                    $tmp['percentage'] = $v->percentage;
                    $tmp['deductTime'] = $v->deductTime ? date('H:i:s',$v->deductTime) : '';
                    $tmp['dealerUid'] = $v->dealerUid;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['gameType'] = $v->gameType;
                    $tmp['remark'] = $v->remark;
                    $tmp['desc'] = $this->config->gameIncomeType[$v->gameType];
                    array_push($data, $tmp);
                }
            }

            $count = \Micro\Models\GameDeductDetailLog::count('anchorUid = ' . $uid . ' and createTime = ' . $createTime);

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data' => $data, 'count' => $count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取游戏提成每日收入
    public function getGameDeductDay($uid = 0, $date = '', $page = 1, $pageSize = 20){
        try {
            if(!$date){//默认当月
                $day = date('d');
                if($day >= 12){
                    $startTime = strtotime(date('Y-m-12'));
                }else{
                    $startTime = strtotime(date('Y-m-12',strtotime('-1 month', strtotime(date('Y-m-01')))));
                }
                $endTime = time();
                $inTime = date('Y-m-12',strtotime('+1 month', strtotime(date('Y-m-01',$startTime))));
            }else{
                $startTime = strtotime(date('Y-m-12',strtotime('-1 month', strtotime(date('Y-m-01',strtotime($date))))));
                $endTime = strtotime(date('Y-m-11',strtotime($date)));
                $inTime = date('Y-m-12',$endTime);
            }

            !$uid && $uid = 0;
            !$page && $page = 1;
            !$pageSize && $pageSize = 20;
            $limit = ($page - 1) * $pageSize;

            $res = \Micro\Models\GameDeductDayLog::find('uid = ' . $uid . ' and createTime between '. $startTime . ' and ' . $endTime
                . ' order by createTime limit ' . $limit . ',' . $pageSize);
            $data = array();
            if($res){
                foreach ($res as $v) {
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['createTime'] = $v->createTime ? date('Y-m-d',$v->createTime) : '';
                    $tmp['cash'] = $v->cash;
                    $tmp['type'] = $v->type;
                    $tmp['remark'] = $v->remark;
                    array_push($data, $tmp);
                }
            }

            $count = \Micro\Models\GameDeductDayLog::count('uid = ' . $uid . ' and createTime between '. $startTime . ' and ' . $endTime);

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data' => $data, 'count' => $count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
