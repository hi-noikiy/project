<?php

namespace Micro\Controllers;

class AjaxController extends ControllerBase {

    public function initialize() {

        // parent::initialize();
    }

    //登录
    public function loginAction() {
        if ($this->request->isPost()) {
            $postData['username'] = $this->request->get('username');
            $postData['password'] = $this->request->get('password');
            $result = $this->invMgrBase->login($postData['username'], $postData['password']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //首页--待处理申请列表
    public function applyListAction() {
        if ($this->request->isPost()) {
            $postData['number'] = $this->request->getPost('page');
            $postData['type'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgrBase->getPendingApply($postData['number'], $postData['type']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //首页 ## 全部房间
    public function allRoomsAction() {
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');  //默认0,1直播状态，2测试状态
            $name = $this->request->getPost('name');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $order = $this->request->getPost('order');
            $result = $this->invMgrBase->getAllRooms($type,$name,$page,$pageSize,$order);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //增减房间里面机器人的个数
    public function changeRobotCountAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $count = $this->request->getPost('count');
            $result = $this->invMgr->changeRobotCount($uid, $count);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取房间机器人的个数
    public function getRoomRobotCountAction() {
    	if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $result = $this->invMgr->getRoomRobotCount($uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //修改密码
    public function passwordAction() {
        if ($this->request->isPost()) {
            $password = $this->request->getPost('password');
            $postData['type'] = $this->request->getPost('type');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgrBase->editPassword($password, $postData['type']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //退出登录
    public function loginoutAction() {
        if ($this->request->isPost()) {
            $result = $this->invMgrBase->loginout();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //主播列表
    public function anchorListAction() {
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('isSign');
            $isFamily = $this->request->getPost('isFamily');
            $namelike = $this->request->getPost('name');
            $order = $this->request->getPost('order');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');

            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//已签约主播
                $result = $this->invMgr->checkSign(0, $isFamily, $namelike, $order, $postData['number'], $postData['sorttype']);
            } else {//未签约主播
                $result = $this->invMgr->checkNoSign(0, $namelike, $order, $postData['number'], $postData['sorttype']);
            }
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //主播 == 主播详情 ==直播  状态：0,1正常 <===>  2禁播
    public function liveStatusAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $status = $this->request->getPost('status');
            $result = $this->invMgr->liveStatus($uid,$status);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

     //主播 == 主播详情 == 账号 状态：1正常 <===>  2冻结
    public function userStatusAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $status = $this->request->getPost('status');
            $result = $this->invMgr->userStatus($uid,$status);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //主播 == 主播详情 == 是否显示 状态、：1显示0不显示
    public function showStatusAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $status = $this->request->getPost('status');
            $result = $this->invMgr->showStatus($uid,$status);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //

    //审核申请
    public function auditApplyAction() {
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('type');
            $postData['id'] = $this->request->getPost('applyId');
            $postData['uid'] = $this->request->getPost('uid');
            $postData['status'] = $this->request->getPost('status');
            $postData['reason'] = $this->request->getPost('reason');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//签约主播申请
                $result = $this->invMgr->editSignApply($postData['id'], $postData['uid'], $postData['status'],$postData['reason']);
            } else {//家族申请
                $result = $this->invMgr->editFamilyApply($postData['id'], $postData['uid'], $postData['status'],$postData['reason']);
            }
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //编辑兑换限制的值
    public function editExchangeAction() {
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('value');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->editExchange(1, $postData['id']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获得例外用户列表
    public function getExceptionAction() {
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('type');
            $name = $this->request->getPost('name');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//查询分成例外的用户
                $result = $this->invMgr->checkBonus(2, $name, $postData['number'], $postData['sorttype']);
            } elseif ($postData['type'] == 2) {//查询兑换限制例外的用户
                $result = $this->invMgr->checkExchange(2, $name, $postData['number'], $postData['sorttype']);
            } elseif ($postData['type'] == 3) {//查询直播间自动跳转用户
                $result = $this->invMgr->robotSkipList($postData['number'], $postData['sorttype']);
            }
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //修改例外
    public function setExceptionAction() {
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('type');
            $postData['id'] = $this->request->getPost('id');
            $postData['number'] = $this->request->getPost('value');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//修改分成规则例外
                $result = $this->invMgr->editBonus(2, $postData['id'], $postData['number']);
            } elseif ($postData['type'] == 2) {//修改兑换限制例外
                $result = $this->invMgr->editExchange(2, $postData['id'], $postData['number']);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //删除例外
    public function delExceptionAction() {
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('type');
            $postData['id'] = $this->request->getPost('id');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//删除分成规则例外
                $result = $this->invMgr->delBonus(2, $postData['id']);
            } elseif ($postData['type'] == 2) {//删除兑换限制例外
                $result = $this->invMgr->delExchange(2, $postData['id']);
            } elseif ($postData['type'] == 3) {//删除直播间自动跳转
                $result = $this->invMgr->delRobotSkip($postData['id']);
            }
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //删除例外
    public function delAnchorJumpAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $result = $this->invMgr->delAnchorJump($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //添加例外时 人员列表
    public function getExcAnchorAction() {
        if ($this->request->isPost()) {
            $uids = $this->request->getPost('uids'); //已选择的排除
            $postData['type'] = $this->request->getPost('type');
            $isSign = $this->request->getPost('isSign');
            $name = $this->request->getPost('name');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 2) {//添加分成规则时 人员列表
                $result = $this->invMgr->addBonus(2, $isSign, $name, $postData['number'], $postData['sorttype'], $uids);
            } elseif ($postData['type'] == 1) {//添加兑换下限时 人员列表
                $result = $this->invMgr->addExchange(2, $isSign, $name, $postData['number'], $postData['sorttype'], $uids);
            } elseif ($postData['type'] == 3) {//直播间自动跳转 人员列表
                $result = $this->invMgr->robotAnchorList($isSign, $name, $postData['number'], $postData['sorttype'], $uids);
            }
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }



    //添加例外
    public function addExceptionAction() {
        if ($this->request->isPost()) {
            $uids = $this->request->getPost('uids');
            $value = $this->request->getPost('value');
            $postData['type'] = $this->request->getPost('type');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//添加分成规则例外
                $result = $this->invMgr->addBonus(3, $value, '', 0, 0, '', '', $uids);
            } elseif ($postData['type'] == 2) {//添加兑换限制例外
                $result = $this->invMgr->addExchange(3, $value, '', 0, 0, $uids);
            } elseif ($postData['type'] == 3) {//直播间自动跳转
                $result = $this->invMgr->addRobotSkip($uids);
            }
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //添加跳转主播池
    public function addAnchorJumpAction(){
        if ($this->request->isPost()) {
            $uids = $this->request->getPost('uids');
            $type = $this->request->getPost('type');
            $result = $this->invMgr->addAnchorJump($uids, $type);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取跳转主播池
    public function getAnchorJumpAction(){
        if ($this->request->isPost()) {
            $result = $this->invMgr->getAnchorJump();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //添加分成规则时 查询主播等级列表
    public function getAnchorLevelAction() {
        if ($this->request->isPost()) {
            $result = $this->invMgrBase->getAnchorLevel();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //添加分成规则时 查询大于等于号列表
    public function getSymbolAction() {
        if ($this->request->isPost()) {
            $result = $this->invMgrBase->getSymbol();
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //查询规则列表
    public function getRuleListAction() {
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('type');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//查询主播分成规则
                $result = $this->invMgr->checkBonus(1, '', $postData['number'], $postData['sorttype']);
            } else if ($postData['type'] == 2) {//查询家族分成规则
                $result = $this->invMgr->getFamilyRule($postData['number'], $postData['sorttype']);
            } else if ($postData['type'] == 3) {//查询直播间加机器人规则
               // $result = $this->invMgr->getLiveRoomRobotRule($postData['number'], $postData['sorttype']);
                $result = $this->invMgr->getRobotRule();
            } else if ($postData['type'] == 4) {//查询直播间机器人退出规则
                //$result = $this->invMgr->getRobotOutRule($postData['number'], $postData['sorttype']);
                $result = $this->invMgr->getRobotRule();
            }
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //签约主播申请列表
    public function anchorApplyListAction() {
        if ($this->request->isPost()) {
            $status = $this->request->getPost('status');
            $name = $this->request->getPost('name');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');

            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->checkSignApply($status, $name, $postData['number'], $postData['sorttype']);

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //主播例外修改时 查询
    public function getOneExceptinAction() {
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('id');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgrBase->getOneExceptin($postData['id']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //修改规则时 查询规则
    public function getOneRuleAction() {
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('id');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgrBase->getOneRule($postData['id']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //修改/添加规则
    public function editRuleAction() {
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('type');
            $id = $this->request->getPost('id');
            $conditions = $this->request->getPost('conditions');
            $postData['number'] = $this->request->getPost('value');
            $str1 = $this->request->getPost('str1');
            $str2 = $this->request->getPost('str2');
            $conType = $this->request->getPost('conType');
            //$conValue = $this->request->getPost('conValue');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//修改/添加主播分成规则
                if ($id) {//修改
                    $result = $this->invMgr->editBonus(1, $id, $postData['number'], $conditions, $str1, $str2);
                } else {//添加
                    $result = $this->invMgr->addBonus(1, $conditions, $postData['number'], 0, 0, $str1, $str2);
                }
            } elseif ($postData['type'] == 2) {//修改/添加家族分成规则
                if ($id) {//修改
                    $result = $this->invMgr->editFamilyRule($id, $conditions, $postData['number'], $str1, $str2);
                } else {//添加
                    $result = $this->invMgr->addFamilyRule($conditions, $postData['number'], $str1, $str2);
                }
            } elseif ($postData['type'] == 3) {//修改/添加直播间加机器人规则
                if ($id) {//修改
                    //$result = $this->invMgr->editLiveRoomRobotRule($id, $conditions, $postData['number'], $str1, $str2, $conType, $conValue);
                } else {//添加
                    //先屏蔽
                    //$result = $this->invMgr->addLiveRoomRobotRule($conditions, $postData['number'], $str1, $str2, $conType, $conValue);
                }
            } elseif ($postData['type'] == 4) {//修改/添加直播间机器人退出规则
                if ($id) {//修改
                    // $result = $this->invMgr->editRobotOutRule($id, $postData['number'], $conType, $conValue);
                    $result = $this->invMgr->editRobotOutRule($conType,$postData['number']);
                } else {//添加
                    //$result = $this->invMgr->addRobotOutRule($postData['number'], $conType, $conValue);
                    $result = $this->invMgr->editRobotOutRule($conType,$postData['number']);
                }
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //修改/添加直播间加机器人规则
    public function liveRoomRobotRuleAction(){
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');
            $id = $this->request->getPost('id');
            $minCount = $this->request->getPost('minCount');  
            $maxCount = $this->request->getPost('maxCount');
            $unitPerUser = $this->request->getPost('unitPerUser');
            $unitPerTimes = $this->request->getPost('unitPerTimes');
            /*$isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }*/
            switch ($type) {
                case 1: //新增
                    $result = $this->invMgr->addLiveRoomRobotRule($minCount, $maxCount, $unitPerUser, $unitPerTimes);
                    break;
                case 2:
                    if ($id>=0) {//修改
                        $result = $this->invMgr->editLiveRoomRobotRule($id, $minCount, $maxCount, $unitPerUser, $unitPerTimes);
                    }
                    else {
                        return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'));
                    }
                    break;
                
                default:
                    return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'));
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //删除规则
    public function delRuleAction() {
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('type');
            $postData['id'] = $this->request->getPost('id');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//删除分成规则
                $result = $this->invMgr->delBonus(1, $postData['id']);
            } elseif ($postData['type'] == 2) {//删除家族分成规则 
                $result = $this->invMgr->delFamilyRule($postData['id']);
            }/* elseif ($postData['type'] == 3) {//删除直播间加机器人规则
                $result = $this->invMgr->delLiveRoomRobotRule($postData['id']);
            } elseif ($postData['type'] == 4) {//删除直播间机器人退出规则
                //$result = $this->invMgr->delRobotOutRule($postData['id']);
                $result = $this->invMgr->delRobotOutRule($conType);
            }*/

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //删除机器人规则
    public function delRobotRuleAction(){
        if ($this->request->isPost()) {
            $postData['type'] = $this->request->getPost('type');
            $index = $this->request->getPost('index');
            $conType = $this->request->getPost('conType');

            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            if ($postData['type'] == 1) {//删除直播间机器人退出规则
                $result = $this->invMgr->delRobotOutRule($conType);
            }
            else if ($postData['type'] == 2) {//删除直播间加机器人规则
                $result = $this->invMgr->delLiveRoomRobotRule($index);
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //冻结/解冻主播
    public function setAnchorStatusAction() {
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('uid');
            $postData['status'] = $this->request->getPost('status');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->editSign(1, $postData['id'], $postData['status']);

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //查询主播、家族结算记录
    public function getAnchorSettleAction() {
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('uid');
            $postData['type'] = $this->request->getPost('type');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->checkAnchorAccout($postData['id'], $postData['type'], $postData['number'], $postData['sorttype']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //按日期查询数据
    public function checkDataByDateAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $timeType = $this->request->getPost('timeType');
            $type = $this->request->getPost('type');
            $startDate = $this->request->getPost('startDate');
            $endDate = $this->request->getPost('endDate');

            switch ($type) {
                case 'anchorIncome'://主播个人收益
                    $result = $this->invMgr->getAnchorIncomeInfo($id, 2, $timeType, $startDate, $endDate);
                    break;
                case 'anchorFamilyIncome'://主播家族收益
                    $result = $this->invMgr->getAnchorIncomeInfo($id, 2, $timeType, $startDate, $endDate,1);
                    break;
            }

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //直播间机器人退出规则排序--上移、下移
    public function setRuleSortAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $type = $this->request->getPost('type'); //‘up’、'down'
            $result = $this->invMgr->robotOutRuleSort($id, $type);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //可申请结算列表
    public function getSettleApplyAction() {
        if ($this->request->isPost()) {
            $ids = $this->request->getPost('ids'); //已选择的排除
            $isFamily = $this->request->getPost('isFamily');
            $name = $this->request->getPost('name');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }

            $result = $this->invMgr->settleApplyList($isFamily, $name, $postData['number'], $postData['sorttype'], $ids);

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //提交结算
    public function setSettleAction() {
        if ($this->request->isPost()) {
            $ids = $this->request->getPost('ids');
            $isFamilys = $this->request->getPost('isFamilys');
            $settleTypes = $this->request->getPost('settleTypes');
            $result = $this->invMgr->setSettleApply($ids, $isFamilys,$settleTypes);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //家族提交
    public function setFamilyAccountAction(){
        if ($this->request->isPost()) {
            $familyInfo = $this->request->getPost('familyInfo');
            $result = $this->invMgr->setFamilyAccountApply($familyInfo);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //待结算列表
    public function getSettleListAction() {
        if ($this->request->isPost()) {
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->getSettleList($postData['number'], $postData['sorttype']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //待结算 ## 导出Excel
    public function waitSettleByExcelAction(){
        $this->invMgr->getWaitSettleExcel();
    }

    //主播待结算详情
    public function anchorSettleInfoAction(){
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('id');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->getAnchorSettleInfo($postData['id']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
       
    }

    //待结算 ## 家族详情
    public function familySettleInfoAction(){
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('id');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->getFamilySettleInfo($postData['id']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //待结算 ## 工资列表
    public function anchorSalaryListAction(){
        if ($this->request->isPost()) {
            $postData['familyId'] = $this->request->getPost('familyId');
            $postData['page'] = $this->request->getPost('page');
            $postData['pageSize'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr-> getAnchorSalaryList($postData['familyId'],$postData['page'],$postData['pageSize']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
       
    }

    //待结算 ## 工资带出excel
    public function salaryByExcelAction(){
        if ($this->request->isPost()) {
            $familyId = $this->request->getPost('familyId');
            $result = $this->invMgr->getSalaryByExcel($familyId);
            return $this->status->ajaxReturn($result['code'], $result['data']);
         }
        $this->proxyError();
    }


    //主播确定结算列表
    public function anchorClearingFormAction(){
        if ($this->request->isPost()) {
            $uids = $this->request->getPost('uids');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getAnchorClearingForm($uids,$page,$pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //家族确定结算列表
    public function familyClearingFormAction(){
        if ($this->request->isPost()) {
            $familyId = $this->request->getPost('familyId');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');

            $result = $this->invMgr->getFamilyClearingForm($familyId,$page,$pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function familyInfoAction(){
        if ($this->request->isPost()) {
            $familyIds = $this->request->getPost('familyIds');
            $result = $this->invMgr->getFamily($familyIds);
            return $this->status->ajaxReturn($result['code'], $result['data']);
         }
        $this->proxyError();
    }


    //已结算列表
    public function getSettleSuccessAction() {
        if ($this->request->isPost()) {
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $startDate = $this->request->getPost('startDate');
            $endDate = $this->request->getPost('endDate');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->getSettleSuccessList($startDate, $endDate, $postData['number'], $postData['sorttype']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //已结算详情
    public function getSettleInfoAction() {
        if ($this->request->isPost()) {
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $postData['id'] = $this->request->getPost('id');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->getSettleApplyLogList($postData['id'], $postData['number'], $postData['sorttype']);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //结算提醒添加/修改
    public function setSettleRemindAction() {
        if ($this->request->isPost()) {
            $mobile = $this->request->getPost('mobile');
            $id = $this->request->getPost('id');
            $result = $this->invMgr->setSettleNotice($mobile, $id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //结算提醒删除
    public function delSettleRemindAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $result = $this->invMgr->delSettleNotice($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //代理 ## 主播收益 ## 贡献排行
    public function familyContributionvAction(){
        if ($this->request->isPost()) {
            $familyId = $this->request->getPost('familyId');
            $startTime = $this->request->getPost('startTime');
            $stopTime = $this->request->getPost('stopTime');
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getFamilyContributionv($familyId,$startTime,$stopTime,$type,$page,$pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //代理--家族--旗下主播
    public function getFamilyAnchor() {
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('familyId');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->editSign(1, $postData['id'], $postData['status']);

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取代理 -- 家族 -- 旗下所有主播
    public function familyAllAnchorAction() {
        if ($this->request->isPost()) {
            $familyId = $this->request->getPost('familyId');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');

            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->checkFamilyAnchor($familyId, $postData['number'], $postData['sorttype']);


            return $this->status->ajaxReturn($result['code'], $result);
        }
        $this->proxyError();
    }

    //家族旗下主播导出excel
    public function excelDataAction(){
        if ($this->request->isPost()) {
            $familyId = $this->request->getPost('familyId');
            $startTime = $this->request->getPost('startTime');
            $endTime = $this->request->getPost('endTime');
            $result = $this->invMgr->getExcelData($familyId,$startTime, $endTime);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //主播工作情况表
    public function anchorWorkingDataAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $startTime = $this->request->getPost('startTime');
            $endTime = $this->request->getPost('endTime');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getAnchorWorkingData($uid,$startTime,$endTime,$page,$pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //主播工作情况表导出excel
    public function protoDataExcelAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $startTime = $this->request->getPost('startTime');
            $endTime = $this->request->getPost('endTime');
            $result = $this->invMgr->getProtoData($uid,$startTime,$endTime);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    //主播 播出时长
    public function anchorBroadcastTimeAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $type = $this->request->getPost('type');        //month月 week周  day天
            $timeBegin = $this->request->getPost('timeBegin');
            $timeEnd = $this->request->getPost('timeEnd');
            $result = $this->invMgr->FamilyBroadcastTime($uid, $type, $timeBegin, $timeEnd);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();

    }

    //贡献
    public function contributionAnchorAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $type = $this->request->getPost('type');        //thisMonth 本月 lastMonth  上月
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->contributionAnchor($uid,$type, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    } 
    
    //获取修改底薪信息
    public function getIdInfoAction() {

        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');

            $result = $this->invMgr->getEditInfo($id);
            // print_r($result);
            // die();
            return $this->status->ajaxReturn($result['code'], $result);
        }
        $this->proxyError();
    }

    //底薪分成     修改
    public function editSalaryAction() {

        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $data['type'] = $this->request->getPost('type');    
            $data['expirationTime'] = $this->request->getPost('expirationTime');    
            $data['money'] = $this->request->getPost('money');
            $data['uid'] = $this->request->getPost('uid');
            $data['status'] = $this->request->getPost('status');
            $data['affectStatus'] = $this->request->getPost('affectStatus');
            $result = $this->invMgr->getEditSalary($id, $data);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //畅销礼物
    public function giftListAction() {
        if ($this->request->isPost()) {
            $typeDay = $this->request->getPost('typeDay');          //toDay 今天 yesterDay昨天  sevenDay7天  thirtyDays30天
            $starTime = $this->request->getPost('starTime');
            $stopTime = $this->request->getPost('stopTime');
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $postData['sort'] = $this->request->getPost('sort');  //排序 默认0消费次数降序 2://送出总数降序 3://送出总数升序 4://送出总金额降序 5://送出总金额升序 1://消费次数升序 6//消费用户数升序 7//消费用户数升序
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->giftList($typeDay, $starTime, $stopTime, $postData['number'], $postData['sorttype'],$postData['sort']);

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //消费趋势图
    public function consumptionTrendMapAction(){
        if ($this->request->isPost()) {
            $type = $this->request->getPost('type');          //hour时 day天  week周  month月
            $starTime = $this->request->getPost('startTime');
            $stopTime = $this->request->getPost('stopTime');
            $result = $this->invMgr->consumptionData($type,$starTime,$stopTime);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //消息内容列表
    public function contentAction() {

        if ($this->request->isPost()) {
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->RobotMessageList($postData['number'], $postData['sorttype']);

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //根据ID获取消息内容数据
    public function getIdMessageAction() {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $id = $post['id'];                //  
            $result = $this->invMgr->getIdInfo($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //修改消息内容
    public function editAction() {

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $id = $post['id'];                //
            $data['content'] = $post['content'];
            $result = $this->invMgr->SaveData($id, $data);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //删除消息 
    public function delAction() {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $result = $this->invMgr->delRoomMessage($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //添加消息内容

    public function addAction() {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $id = $post['id'];                //
            $data['content'] = $post['content'];
            $result = $this->invMgr->SaveData($id, $data);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //托账号
    public function userListAction() {
        if ($this->request->isPost()) {
            $userName = $this->request->getPost('userName');    //用户，昵称 搜索
            $sort = $this->request->getPost('sort');            //1://富豪等级升序2: //富豪等级降序 3://余额升序 4: //余额降序 
            $postData['number'] = $this->request->getPost('page');
            $postData['sorttype'] = $this->request->getPost('pageSize');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result = $this->invMgr->UserList($userName, $sort, $postData['number'], $postData['sorttype']);

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //账号详情
    public function consumerInfoAction() {
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $start = $this->request->getPost('startTime');
            $end = $this->request->getPost('endTime');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->uidConsumerInfo($uid, $start, $end, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //托账号 发放
    public function grantAction() {
       /* if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $num = $post['num'];                //获取聊币
            $uid = $post['uid'];
            $result = $this->invMgr->getGrant($num, $uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();*/
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $RMB = $this->request->getPost('num');

            if(empty($RMB) || !is_numeric($RMB)){
                self::codeReturn('', '参数错误', 1);
            }

            if(empty($uid) || !is_numeric($uid)){
                self::codeReturn('', '参数错误', 1);
            }

            $innerPayDB = new \Micro\Frameworks\Pay\InnerPay\InnerPay();

            $result = $innerPayDB->pay($RMB,$uid);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //操作日志
    public function journalAction() {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $start = $this->request->getPost('startTime');
            $end = $this->request->getPost('endTime');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->journalAllInfo($start, $end, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 登录日志
    public function loginJournalAction() {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $start = $this->request->getPost('startTime');
            $end = $this->request->getPost('endTime');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->loginLogData($start, $end, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 家族 == 播出时长
    public function broadcastTimeAction() {
        // $result = $this->invMgr->broadcastTime(1, $type='week','','');
        // print_r($result);exit;
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $familyId = $this->request->getPost('familyId');
            $type = $this->request->getPost('type');        //month月 week周  day天
            $timeBegin = $this->request->getPost('timeBegin');
            $timeEnd = $this->request->getPost('timeEnd');
            $result = $this->invMgr->broadcastTime($familyId, $type, $timeBegin, $timeEnd);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //家族收益
    public function familyComeListAction() {
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $familyId = $post['familyId'];
            $type = $post['type'];        //month月 week周  day天
            $timeBegin = $post['timeBegin'];
            $timeEnd = $post['timeEnd'];
            $result = $this->invMgr->getfamilyComeList($type, $familyId, $timeBegin, $timeEnd);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //家族结算日
    public function sellteddayAction(){
        if ($this->request->isPost()) {
            $postData['id'] = $this->request->getPost('id');
            $postData['day'] = $this->request->getPost('day');
            $isValid = $this->validator->validate($postData);
            if (!$isValid) {
                $errorMsg = $this->validator->getLastError();
                return $this->status->ajaxReturn($this->status->getCode('VALID_ERROR'), $errorMsg);
            }
            $result =  $this->invMgr->settlementDate($postData['id'], $postData['day']);

            return $this->status->ajaxReturn($result['code'], $result);
        }
        $this->proxyError();
    }

    //水军
    public function chatRecordListAction(){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $name = $post['name'];
            $sort = $post['sort'];        //1:富豪等级升序  2:富豪等级降序 3:时间升序 4:时间降序
            $page = $post['page'];
            $pageSize = $post['pageSize'];
            $result = $result = $this->recordMgr->getChatObject()->getChatList($name,$sort,$page,$pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //水军详情
    public function chatInfoAction(){
        // $result = $result = $this->recordMgr->getChatObject()->getChatInfo(3,'','',1,10,1);
        // exit;
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $uid = $post['uid'];
            $startTime = $post['startTime'];        
            $stopTime = $post['stopTime'];
            $page = $post['page'];
            $pageSize = $post['pageSize'];
            $isExcel = $post['isExcel'];  //默认0 导出表格为1

            $result = $result = $this->recordMgr->getChatObject()->getChatInfo($uid,$startTime,$stopTime,$page,$pageSize,$isExcel);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }


    //水军统计
    public function statisticsAction(){
         // $result = $this->recordMgr->getChatObject()->getStatistics(3,'2014-11-18','2014-11-20',1,10);
         // print_r($result);exit;
         // exit;
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $uid = $post['uid'];
            $startTime = $post['startTime'];        
            $stopTime = $post['stopTime'];
            $page = $post['page'];
            $pageSize = $post['pageSize'];
            $type = $post['type'];
            $isExcel = $post['isExcel'];
            $result = $this->recordMgr->getChatObject()->getStatistics($uid,$startTime,$stopTime,$page,$pageSize,$type,$isExcel);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //聊天服务器回调接口(web网站要有，GM后台也有(测试方便))
    public function chatServerCallbackAction() {
        $rawBody = $this->request->getRawBody();
        $jsonData = json_decode($rawBody, true);
        $dataArray = $jsonData['data'];
        try{
            foreach ($jsonData['data']as $dataInfo) {
                //$roomId = $dataInfo['roomId'];
                $roomData = $dataInfo['data'];
                $controlType = $roomData['controltype'];

                // 回调机器人自动增减
                if ($controlType == 'autoRobot') {
                    $roomId = $roomData['data']['roomId'];
                    $robotCount = intval($roomData['data']['robotCount']);
                    $changeRobotCount = intval($roomData['data']['changeRobotCount']);
                    // 写数据库。。。
                    $result = $this->roomModule->getRoomMgrObject()->updateRobotCount($roomId, $robotCount);
                    //echo "roomId = ".$roomId.", robotCount = ".$robotCount.", changeRobotCount = ".$changeRobotCount;die;
                    // test
                    // return $this->status->ajaxReturn($result['code'], $result['data']);
                }
            }
            return $this->status->ajaxReturn($this->status->getCode('OK'));
        }
        catch(\Exception $e){
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //解约主播
    public function unbindStatusAction(){
        if ($this->request->isPost()) {

            $uid = $this->request->getPost('uid');

            if(empty($uid) || !is_numeric($uid)){
                self::codeReturn('', '参数错误', 1);
            }

            $status = $this->config->signAnchorStatus->unbind;

            $result = $this->invMgr->unbindStatus($uid, $status);

            if($result){
                return $this->status->ajaxReturn($result['code'], $result['data']);
            }else{
                self::codeReturn('', '操作失败', 1);
            }
            
        }
    }

    //删除家族旗下主播
    public function delAnchorAction(){
        if($this->request->isPost()){

            $uid = $this->request->getPost('uid');

            if(empty($uid) || !is_numeric($uid)){
                self::codeReturn('', '参数错误', 1);
            }

            $status = 0;

            $result = $this->invMgr->delAnchor($uid, $status);

            if($result){
                return $this->status->ajaxReturn($result['code'], $result['data']);
            }else{
                self::codeReturn('', '操作失败', 1);
            }
        }
    }

    //设置主播每个月最多收到推广员的收益占保底底薪的比例
    public function setRatioAction(){
        if($this->request->isPost()){

            $ratioNum = $this->request->getPost('ratio');

            if(!isset($ratioNum) || !is_numeric($ratioNum)){
                self::codeReturn('', '参数错误', 1);
            }

            $result = $this->invMgr->setRatio($ratioNum);

            if($result){
                return $this->status->ajaxReturn($result['code'], $result['data']);
            }else{
                self::codeReturn('', '操作失败', 1);
            }
        }
    }

    /**
     * 获取礼物明细
     * @param $uid 主播ID
     * @param $date
     */
    public function getDayRecvGiftsAction(){
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $date = $this->request->getPost('date');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getDayRecvGifts($uid, $date, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取礼物收入
     * @param $uid 主播ID
     * @param $date 日期为空或者如2015-07
     * @param $type 1-主播，2-家族长
     */
    public function getDayGiftsAction(){
        if ($this->request->isPost()) {
            $uid = $this->request->getPost('uid');
            $date = $this->request->getPost('date');
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getDayGiftsLog($uid, $date, $type, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取收益流水
     * @param $uid 主播ID
     * @param $date 日期为空或者如2015-07
     * @param $type 1-主播，2-家族长
     */
    public function getDayIncomeAction(){
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $date = $this->request->getPost('date');
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getDayIncomeLog($uid, $date, $type, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取佣金
     * @param $uid 主播ID
     * @param $date 日期为空或者如2015-07
     * @param $type 1-主播，2-家族长
     */
    public function getMonthIncomeAction(){
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $date = $this->request->getPost('date');
            $type = $this->request->getPost('type');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getMonthIncomeLog($uid, $date, $type, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取交易明细
     * @param $uid 主播ID
     * @param $date 日期为空或者如2015-07
     * @param $type 1-主播，2-家族长
     */
    public function getChangeLogAction(){
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $startTime = $this->request->getPost('startTime');
            $endTime = $this->request->getPost('endTime');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getChangeLog($uid, $startTime, $endTime, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取结算列表
     * @param $type 0-待结算1-已结算
     */
    public function getSettleLogAction(){
        if($this->request->isPost()){
            $status = intval($this->request->getPost('status'));
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');
            $result = $this->invMgr->getSettleLog($status, $page, $pageSize);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 获取结算详情
     * @param $id
     */
    public function getSettleDetailAction(){
        if($this->request->isPost()){
            $id = intval($this->request->getPost('id'));
            $result = $this->invMgr->getSettleDetail($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /**
     * 进行结算
     * @param $id 结算ID
     * @param $files 图片信息
     * @param $remark 备注信息
     */
    public function updateSettleAction(){
        if($this->request->isPost() && $this->request->getPost('upload')){
            $id = intval($this->request->getPost('id'));
            $files = $this->request->getUploadedFiles();
            $remark = $this->request->getPost('remark');
            $result = $this->invMgr->updateSettleLog($id, $files, $remark);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
}
