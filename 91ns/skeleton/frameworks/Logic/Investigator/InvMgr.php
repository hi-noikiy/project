<?php

namespace Micro\Frameworks\Logic\Investigator;

use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Frameworks\Logic\User\UserData\UserCash;
use Micro\Models\Rooms;

//客服后台 --类 有权限限制
class InvMgr extends InvBase {

    public function __construct() {
        parent::__construct();
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 主播模块start
    //
    ////////////////////////////////////////////////////////////////////////
    //修改已签约主播-- 冻结、解冻主播，主播底薪
    public function editSign($type, $uid, $status) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        if ($type == 1) {//冻结、解冻主播
            return $invAnchor->setAnchorStatus($uid, $status);
        }
    }

    //查看已签约主播
    public function checkSign($anchorUid = 0, $isFamily = 0, $namelike = '', $order = 0, $currentPage = 1, $pageSize = 5) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        if ($anchorUid) {//查看某个签约主播
            return $invAnchor->getAnchorDetail($anchorUid);
        }
        //返回主播列表
        return $invAnchor->getAnchorList(1, $isFamily, $namelike, $order, $currentPage, $pageSize);
    }

    //修改未签约主播-- 冻结、解冻主播，主播底薪
    public function editNoSign() {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
    }

    //查看未签约主播
    public function checkNoSign($anchorUid = 0, $namelike = '', $order = 0, $currentPage = 1, $pageSize = 5,$richerLow,$richerHigh,$loginLow,$loginHigh) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        if ($anchorUid) {//查看某个未签约主播
            return $invAnchor->getAnchorDetail($anchorUid);
        }
        //返回主播列表
        return $invAnchor->getAnchorList(0, 0, $namelike, $order, $currentPage, $pageSize,$richerLow,$richerHigh,$loginLow,$loginHigh);
    }

    //添加分成规则--添加规则、添加例外主播
    public function addBonus($type = 1, $isSign = 0, $nameLike = '', $currentPage = 1, $pageSize = 5, $str1 = '', $str2 = '', $uids = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        if ($type == 1) {//添加分成规则
            $invBase = new InvBase();
            return $invBase->addOneRule($this->config->ruleType->anchorBonus, $isSign, $nameLike, $str1, $str2);
        } elseif ($type == 2) {//添加例外主播时用户列表
            $invAnchor = new InvAnchor();
            return $invAnchor->getExcAnchorList($isSign, $nameLike, $currentPage, $pageSize, $this->config->exceptionType->bonus, $uids);
        } else {//添加分成规则例外主播
            $invAnchor = new InvAnchor();
            return $invAnchor->addException($uids, $isSign, $this->config->exceptionType->bonus);
        }
    }

    //修改分成规则--修改规则、修改例外主播
    public function editBonus($type = 1, $id = 0, $value = '', $conditions = 1, $str1 = '', $str2 = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        if ($type == 1) {//修改分成规则
            $invBase = new InvBase();
            return $invBase->editOneRule($id, $conditions, $value, $str1, $str2);
        } else {//修改例外主播
            $invAnchor = new InvAnchor();
            return $invAnchor->setExceptionInfo($id, $value);
        }
    }

    //删除分成规则--删除分成规则、删除例外主播
    public function delBonus($type = 1, $id = 0) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        if ($type == 1) {//删除分成规则
            $invBase = new InvBase();
            return $invBase->delRule($id);
        } else {//删除例外主播
            $invAnchor = new InvAnchor();
            return $invAnchor->delExceptionInfo($id);
        }
    }

    //查看分成规则--查看规则、查看例外主播
    public function checkBonus($type = 1, $namelike = '', $currentPage = 1, $pageSize = 5) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        if ($type == 1) {//查询分成规则
            $invBase = new InvBase();
            return $invBase->checkRule($this->config->ruleType->anchorBonus, $currentPage, $pageSize);
        } else {//查看分成规则例外主播
            $invAnchor = new InvAnchor();
            return $invAnchor->getExceptionList($this->config->exceptionType->bonus, $namelike, $currentPage, $pageSize);
        }
    }

    //添加兑换下限-- 添加兑换下限、例外主播
    public function addExchange($type = 1, $isSign = 0, $namelike = '', $currentPage = 1, $pageSize = 5, $uids = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        if ($type == 1) {//添加兑换下限
        } elseif ($type == 2) {//添加例外主播时用户列表
            return $invAnchor->getExcAnchorList($isSign, $namelike, $currentPage, $pageSize, $this->config->exceptionType->exchange, $uids);
        } else {//添加兑换下限例外主播
            return $invAnchor->addException($uids, $isSign, $this->config->exceptionType->exchange);
        }
    }

    //修改兑换下限--修改兑换下限、修改例外主播
    public function editExchange($type = 1, $id = 0, $value = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        if ($type == 1) {//修改兑换下限
            $result = $invAnchor->setExchangeLimit($id);
            if ($result) {
                return $this->status->retFromFramework($this->status->getCode('OK'), '');
            }
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
        } else {//修改例外主播
            return $invAnchor->setExceptionInfo($id, $value);
        }
    }

    //删除兑换下限--删除例外主播
    public function delExchange($type = 1, $id = 0) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        if ($type == 1) {
            
        } else {//删除例外主播
            return $invAnchor->delExceptionInfo($id);
        }
    }

    //查看兑换下限--查看兑换下限、 例外主播
    public function checkExchange($type = 1, $namelike = '', $currentPage = 1, $pageSize = 5) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        if ($type == 1) {//查询兑换下限
            return $invAnchor->setExchangeLimit(0);
        } else {//查看兑换下限例外主播
            return $invAnchor->getExceptionList($this->config->exceptionType->exchange, $namelike, $currentPage, $pageSize);
        }
    }

    //修改签约申请--审批申请
    public function editSignApply($applyId, $uid, $status,$reason) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        //返回审批结果
        return $invAnchor->editApplyStatus($applyId, $uid, $status,$reason);
    }

    //查看签约申请--详细信息
    public function getSignApplyInfo($applyId) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        return $invAnchor->getApplyInfo($applyId);
    }

    //签约申请列表
    public function checkSignApply($type = 0, $nickName = '', $page = 1, $pageSize = 20) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        return $invAnchor->anchorApplyList($type, $nickName, $page, $pageSize);
    }

    //修改收益结算--收益的结算，结算表导出
    public function editAccounts() {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
    }

    //查看结算
    public function checkAnchorAccout($id, $type = 1, $page = 1, $pageSize = 20) {
        $invAnchor = new InvAnchor();
        return $invAnchor->getAnchorAccountList($id, $type, $page, $pageSize);
    }
                
    //修改家族申请--审批申请
    public function editFamilyApply($applyId, $uid, $status, $reason) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        //返回审批结果
        return $invAnchor->editApplyStatus($applyId, $uid, $status, $reason);
    }

    //主播详情--收益--个人收益
    public function getAnchorIncomeInfo($uid = 0, $type = 1, $timeType = 'day', $startDate = '', $endDate = '', $isFamily = 0) {
        $invAnchor = new InvAnchor();
        if ($type == 1) {//主播总收益、主播状态
            return $invAnchor->getAnchorIncomeInfo($uid);
        } elseif ($type == 2) {//主播个人收益/家族收益
            return $invAnchor->getAnchorIncomeList($timeType, $uid, $startDate, $endDate, $isFamily);
        } elseif ($type == 3) {//主播家族收益排行
            return $invAnchor->getAnchorFamilyIncomeDetail($uid);
        }elseif($type==4){//判断主播是否有加入家族
             return $invAnchor->checkAnchorIsFamily($uid);
        }
    }

    //getAnchorSalary 主播 -- 底薪分成 --- 底薪
    public function AnchorSalaryInfo($uid) {

        $invAnchor = new InvAnchor();
        return $invAnchor->getAnchorSalary($uid);
    }

    //修改获取到要修改的信息
    public function getEditInfo($id) {
        $invAnchor = new InvAnchor();
        if ($result = $invAnchor->editInfo($id)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
    }

    // //editSalary 修改底薪
    public function getEditSalary($id, $data) {
        $invAnchor = new InvAnchor();
        //返回审批结果
        if ($invAnchor->editSalary($id, $data)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), '');
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    // 主播 == 播出时长
    public function familyBroadcastTime($uid,$type='day',$Begin, $End,$isExcel=0){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->getFamilyBroadcastTime($uid,$type,$Begin, $End,$isExcel);
        return $result;
    }

    //主播信息
    public function getAnchorInfo($uid){
          $InvAnchor = new InvAnchor();
          return $InvAnchor->getAnchorInfo($uid);
    }

    //主播 == 贡献
    public function contributionAnchor($uid,$type,$page=1,$pageSize=20){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->getContributionAnchor($uid,$type,$page,$pageSize);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    //主播 == 主播详情 ==直播  状态：正常 <===>  解禁
    public function liveStatus($uid,$status = 0){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->getLiveStatus($uid,$status);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //主播 == 主播详情 == 账号 状态：正常 <===>  冻结
    public function userStatus($uid,$status){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->getUserStatus($uid,$status);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //主播 == 主播详情 == 是否显示 状态、：1显示0不显示
    public function showStatus($uid,$status){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->getShowStatus($uid,$status);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }

    }

    //主播详情状态
    public function getUidStatus($uid){
        $InvAnchor = new InvAnchor();
        return $InvAnchor->anchorStatus($uid);
    }

    //工作情况表
    public function getAnchorWorkingData($uid,$startTime,$stopTime,$page,$pageSize){
         $invAnchor = new InvAnchor();
         return $invAnchor->getAnchorWorkingData($uid,$startTime,$stopTime,$page,$pageSize);
    }

    //主播工作表导出excel
    public function getProtoData($uid,$startTime,$stopTime){
        $invAnchor = new InvAnchor();
        return $invAnchor->getPeriodData($uid,$startTime,$stopTime);
    }

////////////////////////////////////////////////////////////////////////
//
// 代理模块start
//
////////////////////////////////////////////////////////////////////////
//
    //查看家族信息
    public function checkFamilyInfo($familyId) {
        $invAgent = new InvAgent();
        return $invAgent->getFamilyInfo($familyId);
    }

    //查看家族旗下主播
    public function checkFamilyAnchor($familyId, $page = 1, $pageSize = 20) {
        try {

            $invAgent = new InvAgent();
            $result = $invAgent->getFamilyAnchor($familyId, $page, $pageSize);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //家族旗下的主播导出excel
    public function getExcelData($familyId,$startTime,$stopTime){
        $invAgent = new InvAgent();
        return $invAgent->getExcelData($familyId,$startTime,$stopTime);
    }
    //查看家族申请信息
    public function checkFamilyApply($applyId) {
        $this->checkIsAllowed(__FUNCTION__);
        $invAgent = new InvAgent();
        //返回审批结果
        return $invAgent->getFamilyApplyInfo($applyId);
    }

    //查看家族申请列表
    public function checkFamilyApplyList($type = 0, $page = 1, $pageSize = 20) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAgent = new InvAgent();
        //返回审批结果
        return $invAgent->getFamilyApplyList($type, $page, $pageSize);
    }

    //家族
    public function AllFamilyList($familyName = '', $orderType = 0, $page = 1, $pageSize = 5) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAgent = new InvAgent();
        //返回审批结果
        return $invAgent->getFamilyList($familyName, $orderType, $page, $pageSize);
    }

    //查询家族分成规则
    public function getFamilyRule($currentPage = 1, $pageSize = 5) {
        $invBase = new InvBase();
        return $invBase->checkRule($this->config->ruleType->familyBonus, $currentPage, $pageSize);
    }

    //删除家族分成规则
    public function delFamilyRule($id) {
        $invBase = new InvBase();
        return $invBase->delRule($id);
    }

    //修改家族分成规则
    public function editFamilyRule($id = 0, $conditions = 1, $value = '', $str1 = '', $str2 = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invBase = new InvBase();
        return $invBase->editOneRule($id, $conditions, $value, $str1, $str2);
    }

    //新增家族分成规则
    public function addFamilyRule($conditions = 1, $value = '', $str1 = '', $str2 = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invBase = new InvBase();
        return $invBase->addOneRule($this->config->ruleType->familyBonus, $conditions, $value, $str1, $str2);
    }

    //家族在线人数
    public function onlineNum($familyId, $Begin, $endTime) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);

        try {
            $times = $Begin == '' ? time() : $Begin;
            $y = date("Y", $times);
            $m = date("m", $times);
            $d = date("d", $times);
            $start = mktime(0, 0, 0, $m, $d, $y);  //开始时间戳
            $end = mktime(23, 59, 59, $m, $d, $y);  //结束时间戳

            $weekDay = date('N'); // 获得当前是周几
            $timeDiff = $weekDay - 1;
            $weekStar = strtotime(date('Y-m-d', strtotime("- $timeDiff days"))); //周一的日期
            $monthStar = strtotime(date('Y-m') . "-01");

            $startHours = strtotime(date('Y-m-d', $times)) + (60 * 60 * date('H', $times)); //获取当时几点
            $endHours = $startHours + 3599;

            switch ($type) {
                //时
                case 'thisHours':
                    $timeBegin = $startHours;
                    $timeEnd = $endHours;
                    break;
                //日
                case 'thisDay':
                    $timeBegin = $start;
                    $timeEnd = $end;
                    break;
                //周	
                case 'thisWeek':
                    $timeBegin = $Begin == '' ? $weekStar : $Begin;
                    $timeEnd = $endTime == '' ? time() : $endTime;
                    break;
                //月
                case 'thisMonth':
                    $timeBegin = $Begin == '' ? $monthStar : $Begin;
                    $timeEnd = $endTime == '' ? time() : $endTime;
                    break;
                default:
                    $timeBegin = $start;
                    $timeEnd = $end;
                    break;
            }
            $invAgent = new InvAgent();
            $data = $invAgent->getOnlineNum($familyId, $timeBegin, $timeEnd);
            return $this->status->retFromFramework($this->status->getCode('OK'), $data);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //--家族收益
    public function getfamilyComeList($type = 'day', $familyId, $startDate, $endDate) {
        try {
            $InvAgent = new InvAgent();
            return $InvAgent->getFamilyIncomeList($type, $familyId, $startDate, $endDate);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //getBroadcastTime 播出时长
    public function broadcastTime($familyId, $type = 'day', $timeBegin, $timeEnd) {
        try {
            $invAgent = new InvAgent();
            $result = $invAgent->getBroadcastTime($familyId, $type, $timeBegin, $timeEnd);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //家族收益 == 详情
    public function getFamileyInfo($familyId){
         $invAgent = new InvAgent();
         return  $invAgent->getFamileyInfo($familyId);
    }

    //查看结算
    public function checkFamilyAccout($familyId, $page = 1, $pageSize = 20) {
        $invAgent = new InvAgent();
        return $invAgent->getFamilyAccountList($familyId, $page, $pageSize);
    }
	
	//家族主播贡献排行
	public function getFamilyContributionv($familyId,$startTime,$stopTime,$type,$page,$pageSize){
		$invAgent = new InvAgent();
        return $invAgent->getFamilyContributionv($familyId,$startTime,$stopTime,$type,$page,$pageSize);
	}

    //家族 == 收益 == 结算日
    public function settlementDate($id,$day){
        $invAgent = new InvAgent();
        return $invAgent->getSettlementDate($id,$day);
    }

    //显示结算日
    public function getFamliySettlement($id){
        $invAgent = new InvAgent();
        return $invAgent->getFamliySettlement($id);
    }



    //////////////////////////////////////////////////////////////////////////
    //
    // 直播间模块start
    //
    ////////////////////////////////////////////////////////////////////////
    //查询所有的机器人规则
    public function getRobotRule() {
        $commConfig = $this->di->get('commConfig');
        return $commConfig->getRobotRule();
    }

    //新增正在直播的房间加机器人的规则
    public function addLiveRoomRobotRule($minCount, $maxCount, $unitPerUser, $unitPerTimes) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);

        $commConfig = $this->di->get('commConfig');
        $jsonData = array();
        $jsonData['minCount'] = $minCount;
        $jsonData['maxCount'] = $maxCount;
        $jsonData['unitPerUser'] = $unitPerUser;
        $jsonData['unitPerTimes'] = $unitPerTimes;
        return $commConfig->addLiveRoomRobotRule($jsonData);
    }

    //查询正在直播的房间加机器人的规则
    public function getLiveRoomRobotRule($currentPage = 1, $pageSize = 5) {
        //old
        //$invBase = new InvBase();
        //return $invBase->checkRule($this->config->ruleType->liveRoomRobotIn, $currentPage, $pageSize);
        
        // test
        //$commConfig = $this->di->get('commConfig');
        //return $commConfig->getRobotRule();

        //return $this->addLiveRoomRobotRule(1001, 2000, 1, 2);
        //return $this->editLiveRoomRobotRule(0, 1001, 1, 11, 2);
        //return $this->delLiveRoomRobotRule(0);

        //return $this->editRobotOutRule(2, 10);
        //return $this->delRobotOutRule(2);
        //return $this->changeRobotCount(1, 1);

        //$commConfig = $this->di->get('commConfig');
        //return $commConfig->getCallbackConfig();
        //return $commConfig->setCallbackEnable(0);
        //return $commConfig->setCallbackUrl("http://127.0.0.1/ajax/chatServerCallback");
    }

    //修改正在直播的房间加机器人的规则
    public function editLiveRoomRobotRule($index, $minCount, $maxCount, $unitPerUser, $unitPerTimes) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);

        $commConfig = $this->di->get('commConfig');
        $jsonData = array();
        $jsonData['minCount'] = $minCount;
        $jsonData['maxCount'] = $maxCount;
        $jsonData['unitPerUser'] = $unitPerUser;
        $jsonData['unitPerTimes'] = $unitPerTimes;
        return $commConfig->editLiveRoomRobotRule($index, $jsonData);
    }

    //删除正在直播的房间加机器人的规则
    public function delLiveRoomRobotRule($index) {
        $commConfig = $this->di->get('commConfig');
        return $commConfig->delLiveRoomRobotRule($index);
    }

    //修改直播房间机器人退出的规则
    // type = 1 waitTime; type = 2; percent; type = 3 percentPerTimes
    public function editRobotOutRule($type, $data) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);

        $commConfig = $this->di->get('commConfig');
        return $commConfig->editRobotOutRule($type, $data);
    }

    //删除直播房间机器人退出的规则
    // type = 1 waitTime; type = 2; percent; type = 3 percentPerTimes
    public function delRobotOutRule($type) {
        $commConfig = $this->di->get('commConfig');
        return $commConfig->delRobotOutRule($type);
    }

    // 增减房间里面机器人的个数
    public function changeRobotCount($uid, $count, $time) {
        if (($count === null) || ($count === '')){
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

    	$roomData = Rooms::findFirst("uid = " . $uid);
    	if (empty($roomData)) { 
    		return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
    	}

    	$roomId = $roomData->roomId;
    	$currentRobotNum = $roomData->robotNum;
    	if (($roomData->liveStatus != 1) && ($count > 0)) {
    		return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_NOT_PUBLISHED'));
    	}

    	if (($count < 0) && ($currentRobotNum < abs($count))) {
    		return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
    	}

        $commConfig = $this->di->get('commConfig');
        if($this->config->robotVersion == '0.0.2'){
            return $commConfig->changeRobotCount($roomId, $count, $time);
        }else{
            return $commConfig->changeRobotCount($roomId, $count);
        }

    }

    public function getRoomRobotCount($uid) {
        $roomData = Rooms::findFirst("uid = " . $uid);
        if (empty($roomData)) { 
            return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }

        $result['robotCount'] = $roomData->robotNum;
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    //删除正在直播的房间加机器人的规则
    /*public function delLiveRoomRobotRule($id) {
        $invBase = new InvBase();
        return $invBase->delRule($id);
    }*/

    //修改正在直播的房间加机器人的规则
    /*public function editLiveRoomRobotRule($id = 0, $conditions = 1, $value = '', $str1 = '', $str2 = '', $conType = 1, $conValue = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invBase = new InvBase();
        return $invBase->editOneRule($id, $conditions, $value, $str1, $str2, $conType, $conValue);
    }*/

    //新增正在直播的房间加机器人的规则
    /*public function addLiveRoomRobotRule($conditions = 1, $value = '', $str1 = '', $str2 = '', $conType = 1, $conValue = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invBase = new InvBase();
        return $invBase->addOneRule($this->config->ruleType->liveRoomRobotIn, $conditions, $value, $str1, $str2, $conType, $conValue);
    }*/

    //查询直播房间机器人退出的规则
    public function getRobotOutRule($currentPage = 1, $pageSize = 5) {
        $invBase = new InvBase();
        return $invBase->checkRule($this->config->ruleType->liveRoomRobotOut, $currentPage, $pageSize);
    }

    //删除直播房间机器人退出的规则
    /*public function delRobotOutRule($id) {
        $invBase = new InvBase();
        return $invBase->delRule($id);
    }*/

    //修改直播房间机器人退出的规则
    /*public function editRobotOutRule($id = 0, $value = '', $conType = 1, $conValue = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invBase = new InvBase();
        return $invBase->editOneRule($id, 0, $value, '', '', $conType, $conValue);
    }*/

    //新增直播房间机器人退出的规则
    public function addRobotOutRule($value = '', $conType = 1, $conValue = '') {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invBase = new InvBase();
        return $invBase->addOneRule($this->config->ruleType->liveRoomRobotOut, 0, $value, '', '', $conType, $conValue);
    }

    //新增直播房间机器人退出的规则排序
    public function robotOutRuleSort($id, $moveType) {
        $invBase = new InvBase();
        return $invBase->setRuleSort($id, $moveType, $this->config->ruleType->liveRoomRobotOut);
    }

    //直播间自动跳转配置--添加时 主播列表
    public function robotAnchorList($isSign = 0, $nameLike = '', $currentPage = 1, $pageSize = 5, $uids = '') {
        $invAnchor = new InvAnchor();
        return $invAnchor->getJumpAnchors($isSign, $nameLike, $currentPage, $pageSize, $uids);
        // return $invAnchor->getExcAnchorList($isSign, $nameLike, $currentPage, $pageSize, $this->config->exceptionType->robotSkip, $uids);
    }

    //直播间自动跳转用户列表
    public function robotSkipList($currentPage = 1, $pageSize = 5) {
        //权限验证
        $this->checkIsAllowed(__FUNCTION__);
        $invAnchor = new InvAnchor();
        return $invAnchor->getExceptionList($this->config->exceptionType->robotSkip, '', $currentPage, $pageSize);
    }

    //删除直播间自动跳转用户
    public function delRobotSkip($id) {
        $invAnchor = new InvAnchor();
        return $invAnchor->delExceptionInfo($id);
    }

    //添加直播间自动跳转用户
    public function addRobotSkip($uids) {
        $invAnchor = new InvAnchor();
        return $invAnchor->addException($uids, 0, $this->config->exceptionType->robotSkip);
    }

    //添加跳转主播池
    public function addAnchorJump($uids, $type) {
        $invAnchor = new InvAnchor();
        return $invAnchor->addAnchorJump($uids, $type);
    }
    //添加跳转主播池
    public function delAnchorJump($id) {
        $invAnchor = new InvAnchor();
        return $invAnchor->delAnchorJump($id);
    }

    //获取跳转主播池
    public function getAnchorJump() {
        $invAnchor = new InvAnchor();
        return $invAnchor->getExceptionList($this->config->exceptionType->robotSkip, '', $currentPage, $pageSize);
    }

    //直播间 == 消息内容
    public function RobotMessageList($page = 1, $pageSize = 20) {
        try {
            $InvRoom = new InvRoom();
            $result = $InvRoom->getRuleMessageList($page, $pageSize);

            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //根据ID获取信息
    public function getIdInfo($id) {
        try {
            $InvRoom = new InvRoom;
            $result = $InvRoom->getIdMessage($id);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //操作数据
    public function SaveData($id = 0, $data = array()) {
        $InvRoom = new InvRoom();
        if ($InvRoom->getSaveData($id, $data)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), '');
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //直播间 == 消息内容删除
    public function delRoomMessage($id) {
        $InvRoom = new InvRoom;
        if ($InvRoom->getDelMessage($id)) {
            return $this->status->retFromFramework($this->status->getCode('OK'), '');
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //////////////////////////////////////////////////////////////////////////
//
// 数据统计模块start
//
    ////////////////////////////////////////////////////////////////////////
    //   
    //畅销礼物
    public function giftList($type, $starTime, $stopTime, $page, $pageSize,$sort) {
        try {
            $InvStatistics = new InvStatistics();
            $result = $InvStatistics->getGiftList($type, $starTime, $stopTime, $page, $pageSize,$sort);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

//消费趋势
    public function consumptionTrendInfo(){
        $InvStatistics = new InvStatistics();
        return $InvStatistics->consumptionTrend();
    }

    //消费趋势图
    public function consumptionData($type='day', $starTime='', $stopTime=''){
        try {
            $InvStatistics = new InvStatistics();
            $result = $InvStatistics->getConsumptionData($type, $starTime, $stopTime);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //////////////////////////////////////////////////////////////////////////
//
// "托"账号模块start
//
    ////////////////////////////////////////////////////////////////////////
    //托账号 == 列表  InvAccountNumber
    public function UserList($userName = '', $sort = 1, $page = 1, $pageSize = 20, $type = 1) {
        try {
            $InvAccountNumber = new InvAccountNumber();
            $result = $InvAccountNumber->getUserList($userName, $sort, $page, $pageSize, $type);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //账号详情
    public function uidConsumerInfo($uid, $start, $end, $page = 1, $pageSize = 20, $searchId = 0) {
        try {
            $InvAccountNumber = new InvAccountNumber();
            $result = $InvAccountNumber->getUidConsumer($uid, $start, $end, $page, $pageSize, $searchId);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //账号详情
    public function uidConsumerInfoNew($uid, $start, $end, $page = 1, $pageSize = 20, $searchId = 0) {
        try {
            $InvAccountNumber = new InvAccountNumber();
            $result = $InvAccountNumber->getUidConsumerNew($uid, $start, $end, $page, $pageSize, $searchId);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //根据UID获取用户信息
    public function getUidUserInfo($uid) {
        $InvAccountNumber = new InvAccountNumber();
        return $InvAccountNumber->getUserInfo($uid);
    }

    // 发放
    public function getGrant($num, $uid) {
        $userCash = new UserCash();
        $result = $userCash->addUserCash($num, $uid, 0);
        $userCash->addCashLog($num, $this->config->cashSource->invSend, 0, $uid);
        $this->addOperate($this->username,'发放',$uid,'发放聊币','',$num);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }



    //////////////////////////////////////////////////////////////////////////
//
// 结算模块start
//
    ////////////////////////////////////////////////////////////////////////
    //可结算申请列表
    public function settleApplyList($isFamily = 0, $namelike = '', $currentPage = 1, $pageSize = 10, $ids = '') {
        $InvSettle = new InvSettle();
        return $InvSettle->getAccountApplyList($isFamily, $namelike, $currentPage, $pageSize, $ids);
    }

    //提交结算申请
    public function setSettleApply($ids, $isFamilys,$settleTypes) {
        $InvSettle = new InvSettle();
        $result = $InvSettle->setAccountApply($ids, $isFamilys,$settleTypes);
        if ($result) {
            return $this->status->retFromFramework($this->status->getCode('OK'), '');
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), '');
    }

    //提交家族结算申请
    public function setFamilyAccountApply($family){
        $InvSettle = new InvSettle();
        return $InvSettle->setFamilyAccountApply($family);
    }

    //待结算列表
    public function getSettleList($currentPage = 1, $pageSize = 10) {
        $InvSettle = new InvSettle();
        return $InvSettle->getAccountWaitingList($currentPage, $pageSize);
    }

    //待结算导出excel
    public function getWaitSettleByExcel(){
        $InvSettle = new InvSettle();
        return $InvSettle->getWaitSettleByExcel();
    }

    //待结算导出excel
    public function getWaitSettleExcel(){
        $InvSettle = new InvSettle();
        return $InvSettle->getWaitSettleExcel();
    }

    //待结算详情信息
    public function getOneSettleApply($accountId) {
        $InvSettle = new InvSettle();
        return $InvSettle->getOneAccountApply($accountId);
    }

    //待结算详情信息--列表
    public function getSettleApplyLogList($accountId, $currentPage = 1, $pageSize = 50) {
        $InvSettle = new InvSettle();
        return $InvSettle->getOneAccountList($accountId, $currentPage, $pageSize);
    }

    //待结算 ## 主播工资表
    public function getAnchorSalaryList($familyId,$page,$pageSize){
         $InvSettle = new InvSettle();
         return $InvSettle->getAnchorSalaryList($familyId,$page,$pageSize);
    }

    //待结算 ## 工资表导出excel
    public function getSalaryByExcel($familyId){
        $InvSettle = new InvSettle();
        return $InvSettle->getSalaryByExcel($familyId);
    }

    //主播结算详情
    public function getAnchorSettleInfo($id){
        $InvSettle = new InvSettle();
        return $InvSettle->getAnchorSettleInfo($id);
    }

    //家族结算详情
    public function getFamilySettleInfo($id){
        $InvSettle = new InvSettle();
        return $InvSettle->getFamilySettleInfo($id);
    }

    //上传结算图片
    public function uploadSettlePic($file, $id,$remark) {
        $InvSettle = new InvSettle();
        return $InvSettle->uploadAccountPic($file, $id,$remark);
    }

    //已结算列表
    public function getSettleSuccessList($beginDate = 0, $endDate = 0, $currentPage = 1, $pageSize = 10, $isExcel = 0) {
        $InvSettle = new InvSettle();
        return $InvSettle->getAccoutList($beginDate, $endDate, $currentPage, $pageSize, $isExcel);
    }

    //结算通知列表
    public function getSettleRemindList() {
        $InvSettle = new InvSettle();
        return $InvSettle->getAccountNoticeList();
    }

    //结算通知新增/修改
    public function setSettleNotice($mobile, $id = 0) {
        $InvSettle = new InvSettle();
        return $InvSettle->setAccountNotice($mobile, $id);
    }

    //结算通知删除
    public function delSettleNotice($id) {
        $InvSettle = new InvSettle();
        return $InvSettle->delAccountNotice($id);
    }

     //主播  ## 选择结算方式
    public function getAnchorClearingForm($uid,$page,$pageSize){
        $InvSettle = new InvSettle();
        return $InvSettle->getAnchorClearingForm($uid,$page,$pageSize);
    }

    //家族 ## 获取家族
    public function getFamily($familyId){
         $InvSettle = new InvSettle();
         return $InvSettle->getFamily($familyId);
    }

    //家族 ## 选择结算方式
    public function getFamilyClearingForm($familyId,$page,$pageSize){
         $InvSettle = new InvSettle();
         return $InvSettle->getFamilyClearingForm($familyId,$page,$pageSize);
    }



    //////////////////////////////////////////////////////////////////////////
//
// 日志管理模块start
//
    ////////////////////////////////////////////////////////////////////////
    //操作日志
    public function journalAllInfo($start, $end, $page, $pageSize) {
        try {

            $invJournal = new InvJournal();
            $result = $invJournal->getJournalAllInfo($start, $end, $page, $pageSize);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //登录日志
    public function loginLogData($start, $end, $page, $pageSize) {
        try {
            $invJournal = new InvJournal();
            $result = $invJournal->getLoginLogInfo($start, $end, $page, $pageSize);
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //解约主播
    public function unbindStatus($uid, $status = 0){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->unbindStatus($uid, $status);
            if($result){
                return $this->status->retFromFramework(
                    $result['code']==0 ? $this->status->getCode('OK') : $this->status->getCode('DB_OPER_ERROR'), 
                    $result
                );
            }else{
                return false;
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //删除家族旗下主播
    public function delAnchor($uid, $status = 0){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->delAnchor($uid, $status);
            if($result){
                return $this->status->retFromFramework(
                    $result['code'] == 0 ? $this->status->getCode('OK') : $this->status->getCode('DB_OPER_ERROR'), 
                    $result
                );
            }else{
                return false;
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getRatioNum($ratioKey){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->getRatioNum($ratioKey);
        return $result;
    }

    public function setRatio($ratioNum){
        try {
            $InvAnchor = new InvAnchor();
            $result = $InvAnchor->setRatio($ratioNum);
            if($result){
                return $this->status->retFromFramework(
                    $result['code'] == 0 ? $this->status->getCode('OK') : $this->status->getCode('DB_OPER_ERROR'), 
                    $result
                );
            }else{
                return false;
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    public function getStatisticsExport($startTime,$endTime,$channel) {
        $InvStatistics = new InvStatistics();
        $result = $InvStatistics->getGuestStatisticsExcel($startTime,$endTime,$channel);
        return $result;
    }

    public function getRegStatisticsExport($startTime,$endTime) {
        $InvStatistics = new InvStatistics();
        $result = $InvStatistics->getRegStatisticsExcel($startTime,$endTime);
        return $result;
    }

    //礼物明细
    public function getDayRecvGifts($uid, $startDate, $endDate, $type, $page, $pageSize, $giftName, $sendUid){
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getRecvGifts($uid, $startDate, $endDate, $type, $page, $pageSize, $giftName, $sendUid);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
        
    }

    //获取礼物收入
    public function getDayGiftsLog($uid, $date, $type, $page, $pageSize){
        try {
            $invAnchor = new InvAnchor();
            $result = $invAnchor->getDayGiftsLog($uid, $date, $type, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }        
    }

    //获取流水
    public function getDayIncomeLog($uid, $date, $type, $page, $pageSize){
        try {
            $invAnchor = new InvAnchor();
            $result = $invAnchor->getDayIncomeLog($uid, $date, $type, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }        
    }

    //获取佣金
    public function getMonthIncomeLog($uid, $date, $type, $page, $pageSize){
        try {
            $invAnchor = new InvAnchor();
            $result = $invAnchor->getMonthIncomeLog($uid, $date, $type, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }        
    }

    //获取交易
    public function getChangeLog($uid, $startTime, $endTime, $page, $pageSize){
        try {
            $invAnchor = new InvAnchor();
            $result = $invAnchor->getChangeLog($uid, $startTime, $endTime, $page, $pageSize);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }        
    }

    //获取是否为家族长
    public function checkIsFamilyHeader($uid = 0){
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->checkIsFamilyHeader($uid);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取结算列表
    public function getSettleLog($status, $page, $pageSize, $startTime, $endTime){
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getSettleLog($status, $page, $pageSize, $startTime, $endTime);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取结算详情
    public function getSettleDetail($id){
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->getChangeDetail($id);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //进行结算
    public function updateSettleLog($id, $files, $remark, $type){
        try {
            $InvAnchor = new InvAnchor();
            $result =  $InvAnchor->updateSettleLog($id, $files, $remark, $type);
            return $result;
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //给用户发送通知
    public function sendUserNotice($uids, $content) {
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->sendUserNotice($uids, $content);
        return $result;
    }
    
    //查询发送通知记录
    public function getUserNoticeLog($page, $pagesize) {
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->getNoticeLogList($page, $pagesize);
        return $result;
    }

    // 获取推荐用户列表
    public function getUserRecList($search, $page, $pageSize){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->getUserRecList($search, $page, $pageSize);
        return $result;
    }

    // 新增推荐链接
    public function addRec($uid, $proportion, $validity, $remark){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->addRec($uid, $proportion, $validity, $remark);
        return $result;
    }

    // 新增推荐链接
    public function addRecUrl($utm_source, $utm_medium, $uid, $proportion, $validity, $remark){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->addRecUrl($utm_source, $utm_medium, $uid, $proportion, $validity, $remark);
        return $result;
    }

    // 获取用户昵称
    public function getNicknameByUid($uid){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->getNicknameByUid($uid);
        return $result;
    }

    // 删除推广用【逻辑删除】
    public function delRec($id){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->delRec($id);
        return $result;
    }
    
    // 新用户推广详情列表
    public function getRecDetailList($uid, $page, $pageSize){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->getRecDetailList($uid, $page, $pageSize);
        return $result;
    }
    // 新用户推广详情列表
    public function getRecDetailsList($search, $page, $pageSize){
    	$InvAnchor = new InvAnchor();
    	$result = $InvAnchor->getRecDetailsList($search, $page, $pageSize);
    	return $result;
    }

    // 获取新用户推广抽成记录列表
    public function getBonusList($uid, $startTime, $endTime, $search, $page, $pageSize){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->getBonusList($uid, $startTime, $endTime, $search, $page, $pageSize);
        return $result;
    }

    // 修改推荐信息
    public function editRecInfo($uid, $type, $editInfo){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->editRecInfo($uid, $type, $editInfo);
        return $result;
    }

    // 获取摸个用户的新用户推荐信息
    public function getRecDetailByUid($id = 0){
        $res = \Micro\Models\Recommend::findFirst('id = ' . $id);

        if(!empty($res)){
            $data = array(
                'remark' => $res->remark,
                'proportion' => $res->proportion,
                'validity' => $res->validity,
                'url' => $res->url,
                'longUrl' => $res->longUrl,
                'tinyUrl' => $res->tinyUrl,
                'dueTime' => date('Y-m-d H:i:s',$res->createTime + $res->validity * 86400),
                'img' => $res->imgPath ? $res->imgPath : $this->pathGenerator->getFullRecommendqrcodePath('qrcode_' . $res->uid . ".png")
            );
            return $data;
        }else{
            return array(
                'remark' => '',
                'proportion' => 0,
                'validity' => 0,
                'url' => '',
                'longUrl' => '',
                'tinyUrl' => '',
                'dueTime' => 0,
                'img' => ''
            );
        }
    }

    //获取推荐新用户注册趋势
    public function getRecUserData($uid, $type, $starTime, $endTime){
        $InvStatistics = new InvStatistics();
        $result = $InvStatistics->getRecUserData($uid, $type, $starTime, $endTime);
        return $result;
    }
    
    
    //被推荐人信息
    public function getBeRecUserData($uid) {
        $invChatRecord = new InvChatRecord();
        $result = $invChatRecord->getBeRecUserData($uid);
        return $result;
    }

    //绑定推荐人
    public function bindRecUser($recUid, $beRecUid) {
        $invChatRecord = new InvChatRecord();
        $result = $invChatRecord->bindRecUser($recUid, $beRecUid);
        return $result;
    }
    
    //直播间历史在线人数统计
    public function checkRoomUserCount($starTime,$endTime,$type) {
        $invCount = new InvCount();
        $result = $invCount->getRoomUserCountData($starTime,$endTime,$type);
        return $result;
    }
    //直播间在线人数统计
    public function checkRoomUserOnlineCount() {
        $invCount = new InvCount();
        $result = $invCount->getRoomUserOnlineCount();
        return $result;
    }
    //直播间历史活跃人数统计
    public function checkUserActiveCount($starTime,$endTime) {
        $invCount = new InvCount();
        $result = $invCount->getUserActiveCountData($starTime,$endTime);
        return $result;
    }
    
     //审核某个主播封面 add by 2015/10/21
    public function auditOneCover($id,$status) {
        $invAnchor = new InvAnchor();
        $result = $invAnchor->auditAnchorCover($id,$status);
        return $result;
    }
    
    //查询主播封面列表 add by 2015/10/21
    public function getAllCoverList($page,$pageSize,$nickName='') {
        $invAnchor = new InvAnchor();
        $result = $invAnchor->getAllAnchorCoverList($page,$pageSize,$nickName);
        return $result;
    }
 
   //导出考勤表/收益表 add by 2015/11/02
    public function excelAnchorWorkData($isFamily, $startDate, $endDate, $type, $nickName) {
        $invAnchor = new InvAnchor();
        $result = $invAnchor->anchorWorkDataExcel($isFamily, $startDate, $endDate, $type, $nickName);
        return $result;
    }
    
    //新用户推广抽成 导出excel add by 2015/11/06
    public function excelRecListIncome($startDate, $endDate, $nickName) {
        $object = new InvChatRecord();
        $result = $object->excelRecommendListIncome($startDate, $endDate, $nickName);
        return $result;
    }
                
    //获取录像列表 add by 2015/11/10
    public function getRECList($date, $search, $page, $pageSize) {
        $invAnchor = new InvAnchor();
        $result = $invAnchor->getRECList($date, $search, $page, $pageSize);
        return $result;
    }
    
    //用户新增数 add by 2015/11/13
    public function getNewRegCount() {
        $object = new InvCount();
        $result = $object->getNewRegUsersCount();
        return $result;
    }
    
    //注册终端统计 add by 2015/11/13
    public function getRegUserPlatCount($startDate, $endDate) {
        $object = new InvCount();
        $result = $object->getDiffPlatRegCount($startDate, $endDate);
        return $result;
    } 
    
    /**
     * 统计下载次数
     * @param unknown $startDate
     * @param unknown $endDate
     * @return unknown
     */
    public function getAppCount($startDate, $endDate) {
    	$object = new InvCount();
    	$result = $object->getAppCount($startDate, $endDate);
    	return $result;
    }
    
    //渠道用户注册统计 add by 2015/11/13
    public function getRecRegUserCount($startDate, $endDate) {
        $object = new InvCount();
        $result = $object->getDiffSourceRegCount($startDate, $endDate);
        return $result;
    }

    //富豪经验倍数修改 add by 2015/11/16
    public function setUserRichRatio($uid, $value) {
        $postData['uid'] = $uid;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        //判断是否正数
        $pattern = '/^[0-9][0-9.]*$/';
        if (!preg_match($pattern, $value)) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $userProfile = \Micro\Models\UserProfiles::findfirst($uid);
            $userProfile->richRatio = $value;
            $userProfile->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }
                
    
    //查看不同时间段用户留存率 add by 2015/11/20
    public function checkUserRetentionList($startDate, $endDate) {
        $object = new InvCount();
        $result = $object->checkUserRetentionList($startDate, $endDate);
        return $result;
    }
    
    //各渠道的用户留存率 add by 2015/11/20
    public function checkPlatRetention($startDate, $endDate,$order) {
        $object = new InvCount();
        $result = $object->checkPlatUserRetention($startDate, $endDate,$order);
        return $result;
    }

    //设置推广员每日最大送礼聊币
    public function setDayMaxLimit($limitNum = '', $type = 0){
        if($limitNum != ''){
            $pattern = '/^[0-9]+$/';
            if (!preg_match($pattern, $limitNum)) {
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }
        }
        
        try {
            $key = 'dayMaxLimit';
            if($type){
                $key = 'dayMaxLimitTuo';
            }
            $baseConfigs = \Micro\Models\BaseConfigs::findFirst('key = "'.$key.'"');
            if($baseConfigs){
                $baseConfigs->value = $limitNum;
                $baseConfigs->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }
    //获取推广员每日最大送礼聊币
    public function getDayMaxLimitNum($type = 0){
        try {
            $dayMaxLimit = '';
            $key = 'dayMaxLimit';
            if($type){
                $key = 'dayMaxLimitTuo';
            }
            $baseConfigs = \Micro\Models\BaseConfigs::findFirst('key = "'.$key.'"');
            if($baseConfigs){
                $dayMaxLimit = $baseConfigs->value;
            }
            return $dayMaxLimit;
        } catch (\Exception $e) {
            return '';
        }
    }

    //
    public function getBettingStat($type, $startDate, $endDate){
        $InvAnchor = new InvAnchor();
        $result = $InvAnchor->getBettingStat($type, $startDate, $endDate);
        return $result;
    }
    
    //用户签到统计
    public function getSignCount($month){
        $object = new InvCount();
        $result = $object->getUserSignCount($month);
        return $result;
    }
    
    //用户任务统计
    public function getTaskCount($startDate, $endDate) {
        $object = new InvCount();
        $result = $object->getUserTaskCount($startDate, $endDate);
        return $result;
    }

    //修改家族状态
    public function modifyFamily($id, $isHide){
        $invAgent = new InvAgent();
        return $invAgent->modifyFamily($id, $isHide);
    }
    
    //充值金额统计
    public function getRechargeCount(){
        $object = new InvCount();
        return $object->getUserRechargeCount();
    }
    
    //渠道充值统计 
    public function getRechargeList($startDate, $endDate){
        $object = new InvCount();
        return $object->getUserRechargeList($startDate, $endDate);
    }
    
    
    //充值排行
    public function getRechargeRank($type) {
        $object = new InvCount();
        return $object->getUserRechargeRank($type);
    }
    
    //充值平均值
    public function getRechargeAvg($type) {
        $object = new InvCount();
        return $object->getUserRechargeAvg($type);
    }
    
    //充值平均值列表
    public function getRechargeAvgList($key) {
        $object = new InvCount();
        return $object->getUserRechargeAvgList($key);
    }
    
    //充值查询
    public function getRechargeData($startDate, $endDate, $uid, $page, $pageSize) {
        $object = new InvCount();
        return $object->getUserRechargeData($startDate, $endDate, $uid, $page, $pageSize);
    }

    //获取开奖列表
    public function getRecentBetRes($page, $pageSize){
        $invAnchor = new InvAnchor();
        return $invAnchor->getRecentBetRes($page, $pageSize);
    }

    //获取酒水列表
    public function getWineList($price){
        $invAnchor = new InvAnchor();
        //价格临时调整上浮6%
        return $invAnchor->getWineList($price * 1.06);
    }

    //编辑所属主播
    public function editWineInfo($id, $uid, $description){
        $invAnchor = new InvAnchor();
        return $invAnchor->editWineInfo($id, $uid, $description);
    }

    //添加对应价格酒水
    public function addWine($price) {
        $invAnchor = new InvAnchor();
        return $invAnchor->addWine($price);
    }

    //给用户送座驾
    public function sendUserCar($uid, $carId, $day = 10) {
        $postData['uid'] = $uid;
        $postData['id'] = $carId;
        $postData['giftcount'] = $day;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $userinfo = \Micro\Models\UserInfo::findfirst($uid);
        if (!$userinfo) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }
        $carinfo = \Micro\Models\CarConfigs::findfirst($carId);
        if (!$carinfo) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }
        $user = UserFactory::getInstance($uid);
        $user->getUserItemsObject()->giveCar($carId, $day * 86400);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }
    
    //给用户送vip
    public function sendUserVip($uid, $carId, $day = 10) {
    	$postData['uid'] = $uid;
    	$postData['id'] = $carId;
    	$postData['giftcount'] = $day;
    	$isValid = $this->validator->validate($postData);
    	if (!$isValid) {
    		$errorMsg = $this->validator->getLastError();
    		return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
    	}
    	$userinfo = \Micro\Models\UserInfo::findfirst($uid);
    	if (!$userinfo) {
    		return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
    	}
    	$carinfo = \Micro\Models\VipConfigs::findfirst($carId);
    	if (!$carinfo) {
    		return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
    	}
    	$user = UserFactory::getInstance($uid);
        $userProfiles = \Micro\Models\UserProfiles::findfirst($uid);
        if($carId == 11){
            $newTime = $userProfiles->vipExpireTime > time() ? $userProfiles->vipExpireTime : time();
            $sql = 'update pre_user_profiles set level1 = 1, vipExpireTime = ' . ($newTime + $day * 86400) . ' where uid = ' . $uid;
        }else{
            $newTime = $userProfiles->vipExpireTime2 > time() ? $userProfiles->vipExpireTime2 : time();
            $sql = 'update pre_user_profiles set level6 = 1, vipExpireTime2 = ' . ($newTime + $day * 86400) . ' where uid = ' . $uid;
        }

        $this->db->execute($sql);
    	$user->getUserItemsObject()->giveCar($carinfo->carId, $day * 86400 , 0 , 'car');
    	return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //给用户送徽章
    public function sendUserBadge($uid, $itemId, $day=10) {
        $postData['uid'] = $uid;
        $postData['id'] = $itemId;
        $postData['giftcount'] = $day;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $userinfo = \Micro\Models\UserInfo::findfirst($uid);
        if (!$userinfo) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }
        $iteminfo = \Micro\Models\ItemConfigs::findfirst($itemId);
        if (!$iteminfo) {
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }
        $user = UserFactory::getInstance($uid);
        $user->getUserItemsObject()->giveItem($itemId, 1, $day * 86400);
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    public function getGroupList(){
        $groupMgr=$this->di->get('groupMgr');
        $result=$groupMgr->groupList();       
        return $result;
    }
    
    public function updateGroup($id = 0, $name = '', $shortName = '') {
        $groupMgr = $this->di->get('groupMgr');
        $result = $groupMgr->editGroup($id, $name, $shortName);
        return $result;
    }
    public function deleteGroup($ids='') {
        $groupMgr = $this->di->get('groupMgr');
        $result = $groupMgr->delGroup($ids);
        return $result;
    }
    
    public function addGroupMembers($id,$uid) {
        $groupMgr = $this->di->get('groupMgr');
        $result = $groupMgr->addGroupMember($id,$uid);
        return $result;
    }
    
    public function delGroupMembers($id,$uid) {
        $groupMgr = $this->di->get('groupMgr');
        $result = $groupMgr->delGroupMember($id,$uid);
        return $result;
    }
    
    public function setGroupLeaders($id,$uid,$type) {
        $groupMgr = $this->di->get('groupMgr');
        $result = $groupMgr->setGroupLeader($id,$uid,$type);
        return $result;
    }

    //徽章发放/撤销
    public function sendChargeBadge($uid, $itemId, $day=10, $type = 1) {
        $postData['uid'] = $uid;
        // $postData['id'] = $itemId;
        if($day != 0){
            $postData['giftcount'] = $day;
        }
        
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $userinfo = \Micro\Models\UserInfo::findfirst($uid);
        if (!$userinfo) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }

        $itemIdArr = explode(',', $itemId);
        foreach ($itemIdArr as $iId) {
            $isValid = $this->validator->validate(array('id'=>$iId));
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $iteminfo = \Micro\Models\ItemConfigs::findfirst($iId);
            if (!$iteminfo) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
        }

        $user = UserFactory::getInstance($uid);
        if($type == 1){
            foreach ($itemIdArr as $iId) {
                $user->getUserItemsObject()->giveItem($iId, 1, $day * 86400);
            }
        }else if($type == 2){
            foreach ($itemIdArr as $iId) {
                $user->getUserItemsObject()->cancelItem($iId);
            }
        }

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    //获取活动收入
    public function getActivityIncomeDayLog($uid ,$date, $page, $pageSize) {
        $invAnchor = new InvAnchor();
        return $invAnchor->getActivityIncomeDayLog($uid ,$date, $page, $pageSize);
    }

    //获取游戏提成明细
    public function getGameDeductDetail($uid, $date, $page, $pageSize){
        $invAnchor = new InvAnchor();
        return $invAnchor->getGameDeductDetail($uid ,$date, $page, $pageSize);
    }

    //获取游戏提成每日收入
    public function getGameDeductDay($uid, $date, $page, $pageSize){
        $invAnchor = new InvAnchor();
        return $invAnchor->getGameDeductDay($uid ,$date, $page, $pageSize);
    }

    //删除推广用户
    public function delRecLog($id = 0){
        $isValid = $this->validator->validate(array('id'=>$id));
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }

        $res = \Micro\Models\RecommendLog::findFirst('id = ' . $id);
        if(!$res){
            return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
        }

        $sql1 = 'delete from pre_recommend_log where id = ' . $id;
        $sql2 = 'insert ignore into pre_rec_refuse_log (`uid`) values (' . $res->beRecUid . ')';
        $this->db->execute($sql1);
        $this->db->execute($sql2);

        return $this->status->retFromFramework($this->status->getCode('OK'));
    }


    //发放主播电影众筹
    public function allocateMovie($uid = 0){
        $isValid = $this->validator->validate(array('uid' => $uid));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $signAnchor = \Micro\Models\SignAnchor::findFirst('uid = '. $uid . ' and (status = 1 or  status = 2)');
            if(!$signAnchor){
                return $this->status->retFromFramework($this->status->getCode('NOT_SIGN_USER'));
            }
            $now = time();
            $movieRound = \Micro\Models\ActivityRound::findFirst('type = 1');
            if(!$movieRound){
                $period = floor(($now - $this->config->anchorMovie->beginTime)/$this->config->anchorMovie->periodTime);
                $period < 1 && $period = 0;
                $times = $period + 1;
                $startTime = $this->config->anchorMovie->beginTime + $period * $this->config->anchorMovie->periodTime;
                $sql = 'insert into pre_activity_round (`type`, `times`, `createTime`, `startTime`) values (1, ' 
                    . $times . ', ' . $now . ', ' . $startTime . ') on duplicate key update times = ' . $times . ',startTime = ' . $startTime;
                $this->db->execute($sql);
            }
            $sql = 'insert ignore into pre_activity_anchors (`uid`, `type`, `createTime`) values (' . $uid . ', 1, ' . $now . ')';
            $this->db->execute($sql);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //删除主播电影众筹
    public function delMovie($id = 0){
        $isValid = $this->validator->validate(array('id' => $id));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $sql = 'delete from pre_activity_anchors where id = ' . $id;
            $this->db->execute($sql);

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //获取电影众筹列表
    public function getMovie($page = 1, $pageSize = 20){
        try {
            !$page && $page = 1;
            !$pageSize && $pageSize = 20;
            $limit = ($page - 1) * $pageSize;

            $sql = 'select aa.id,aa.uid,aa.createTime,ui.nickName from \Micro\Models\ActivityAnchors as aa '
                . ' left join \Micro\Models\UserInfo as ui on ui.uid = aa.uid '
                . ' where aa.type = 1 order by aa.uid limit ' . $limit . ',' . $pageSize;

            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $nums = \Micro\Models\ActivityAnchors::count('type = 1');

            $list = $res->valid() ? $res->toArray() : array();
            return $this->status->retFromFramework($this->status->getCode('OK'), array('list'=>$list,'count'=>$nums));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    
    

    //////////////////////////////////////////////////////////////////////////
//
// 水军模块start
//
    ////////////////////////////////////////////////////////////////////////
    //水军列表
    // public function chatRecord($name,$sort,$page,$pageSize){
    //     $InvChatRecord = new InvChatRecord();
    //     return $InvChatRecord->chatRecordList($name,$sort,$page,$pageSize);        
    // }

    // //水军 == 详情
    // public function chatInfo($uid,$startTime,$stopTime,$page,$pageSize,$isExcel=0){
    //     $InvChatRecord = new InvChatRecord();
    //     return $InvChatRecord->getChatInfo($uid,$startTime,$stopTime,$page,$pageSize,$isExcel);     

    // }

    // //水军用户等级信息
    // public function chatUserInfo($uid){
    //     $InvChatRecord = new InvChatRecord();
    //     return $InvChatRecord->getChatUserInfo($uid);        
    // }


//////////////////////////////////////////////////////////////////////////
//
// 系统设置模块start
//
    ////////////////////////////////////////////////////////////////////////
    //    //添加用户
//    public function addUser($username, $password) {
//        return $this->status->retFromFramework($this->status->getCode('OK'), $this->invUser->addUser($username, $password));
//    }
//
//    //编辑模块
//    public function setModule($info) {
//        return $this->status->retFromFramework($this->status->getCode('OK'), $this->invModule->setModule($info));
//    }
//
}
