<?php

namespace Micro\Frameworks\Logic\Investigator;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;

//客服后台--基础类
class InvBase {

    protected $di;
    protected $status;
    protected $config;
    protected $session;
    protected $modelsManager;
    protected $username;
    protected $storage;
    protected $pathGenerator;
    protected $validator;
    protected $db;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->session = $this->di->get('session');
        $this->config = $this->di->get('config');
        $this->status = $this->di->get('status');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->storage = $this->di->get('storage');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->uid = $this->session->get($this->config->userSession->invUid);
        $this->username = $this->session->get($this->config->userSession->invUsername);
        $this->validator = $this->di->get('validator');
        $this->db = $this->di->get('db');
    }

    //日志
    public function errLog($errInfo) {
        $logger = $this->di->get('invLogs');
        $logger->error('【Data】 error : ' . $errInfo);
    }

    //登录判断
    public function checkLogin($return = 1) {
        if (!$this->uid) {
            if ($return == 1) {
                return false;
            }
            header('Location:/login');
            exit;
        }
        return true;
    }

    //权限判断
    public function checkIsAllowed($methodName) {
        return; //test///
        $methodSession = $this->session->get($this->config->userSession->invRoleModule);
        if ($methodSession == 'all') {
            return true;
        } else {
            if ($methodSession && $methodName && in_array($methodName, $methodSession)) {
                return true;
            }
            //没有权限
            die('no access!');
        }
    }

    //获得某个主播的粉丝数
    public function getAnchorFansNum($uid) {
        $anchorUser = UserFactory::getInstance($uid);
        return $anchorUser->getUserFoucusObject()->getFansCount();
    }

    //获得用户照片
    public function getAnchorPhoto($uid, $type) {
        $anchorUser = UserFactory::getInstance($uid);
        return $anchorUser->getUserInfoObject()->getUserPhoto($type);
    }

    //获取主播等级列表
    public function getAnchorLevel() {
        $list = array();
        try {
            $result = \Micro\Models\AnchorConfigs::find();
            if ($result->valid()) {
                $list = array();
                foreach ($result as $val) {
                    $data['id'] = $val->level;
                    $data['name'] = $val->name;
                    array_push($list, $data);
                }
            }
            return $list;
        } catch (\Exception $e) {
            $this->errLog('getAnchorLevel error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

    //查看规则
    public function checkRule($type = 1, $currentPage = 1, $pageSize = 5) {
        try {
            $list = array();
            $table = "\Micro\Models\InvRule";
            $field = "id,rids,conditions,value,conType,conValue";
            $exp = "type=" . $type;

            $limit = $pageSize * ( $currentPage - 1);
            $order = "sort ASC,id ASC";
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $exp . " ORDER BY " . $order . " limit " . $limit . "," . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                $isLog = 1;
                $dataend = '';
                switch ($type) {
                    case $this->config->ruleType->anchorBonus://主播分成规则
                        $anchorLevel = $this->getAnchorLevel();
                        $newLevelArray = array();
                        foreach ($anchorLevel as $an) {
                            @$newLevelArray[$an['id']] = $an['name'];
                        }
                        $datafront = "主播等级";
                        break;
                    case $this->config->ruleType->familyBonus://家族分成规则
                        $datafront = "用户消费";
                        $dataend = "人民币";
                        break;
                    case $this->config->ruleType->liveRoomRobotIn://直播间加机器人规则
                        $datafront = "用户数";
                        $dataend = "人";
                        break;
                    case $this->config->ruleType->liveRoomRobotOut://直播间机器人退出规则
                        $isLog = 0; //不查询log表
                        break;
                    default:
                        break;
                }

                foreach ($result as $val) {
                    if ($isLog && $val->rids) {
                        $ruleLog = \Micro\Models\InvRuleLog::find("id in ({$val->rids})");
                        if ($ruleLog->valid()) {
                            $logdata = array();
                            if ($type == $this->config->ruleType->anchorBonus) {//主播分成规则
                                foreach ($ruleLog as $v) {
                                    @$logdata[] = $datafront . "<span class='font font18 cr-pe'>" . $this->config->ruleSymbol[$v->symbol] . "</span>" . $newLevelArray[$v->value];
                                }
                            } else {
                                foreach ($ruleLog as $v) {
                                    @$logdata[] = $datafront . "<span class='font font18 cr-pe'>" . $this->config->ruleSymbol[$v->symbol] . "</span>" . $v->value . $dataend;
                                }
                            }

                            $str = '';
                            if ($val->conditions == 1) {
                                $str = "<span class='font font18 cr-pe'>或</span>";
                            } else if ($val->conditions == 2) {
                                $str = "<span class='font font18 cr-pe'>且</span>";
                            }
                            $string = implode($str, $logdata);
                            $data['conditions'] = $string;
                        }
                    }
                    $data['id'] = $val->id;
                    if ($type == $this->config->ruleType->liveRoomRobotIn) {//直播间加机器人规则
                        if ($val->conType == 1) {//1代N
                            $value = "1代N," . $val->conValue . "个用户增加" . $val->value . "个机器人";
                        } else {//直接增加
                            $value = "直接增加,每" . $val->conValue . "分钟加" . $val->value . "个机器人";
                        }
                        $data['value'] = $value;
                    } else if ($type == $this->config->ruleType->liveRoomRobotOut) {//直播间机器人退出规则
                        if ($val->conType == 2) {//直接减少
                            $value = "直接减少,减少" . $val->value . "%的机器人";
                        } elseif ($val->conType == 3) {//按比例减少
                            $value = "按比例减少," . $val->conValue . "分钟减少" . $val->value . "%";
                        } else {//等待
                            $value = "等待" . $val->value . "秒";
                        }
                        $data['value'] = $value;
                    } else {
                        $data['value'] = $val->value . "%";
                    }

                    array_push($list, $data);
                }
            }
            //统计总数
            $count = 0;
            if ($list) {
                $countSql = "SELECT count(1) as count FROM " . $table . " WHERE " . $exp . " limit 1";
                $countquery = $this->modelsManager->createQuery($countSql);
                $countresult = $countquery->execute();
                if ($countresult->valid()) {
                    $count = $countresult[0]['count'];
                }
            }

            $newResult['list'] = $list;
            $newResult['count'] = $count;
            return array("code" => "OK", "data" => $newResult);
        } catch (\Exception $e) {
            $this->errLog('checkRule error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return array("code" => "DB_OPER_ERROR", "data" => "");
        }
    }

    //根据值查询属于哪个对应的分成规则
    public function checkRuleByOne($type, $uid) {
        $result = $this->getRuleResult($type, $uid);
        return $result;
    }

    //查询对应的规则
    private function getRuleResult($type, $id) {
        try {
            switch ($type) {
                //主播分成规则-根据主播等级判断
                case $this->config->ruleType->anchorBonus:

                    //查询是否为例外用户
                    $exInfo = \Micro\Models\InvUserException::findfirst("type=" . $this->config->exceptionType->bonus . " and uid=" . $id);
                    if ($exInfo != false) {
                        return $exInfo->value;
                    }
                    //查询主播等级
                    $leInfo = \Micro\Models\UserProfiles::findfirst($id);
                    $contidionValue = $leInfo->level2;
                    break;
                //家族分成规则-根据用户消费人民币判断
                case $this->config->ruleType->familyBonus:
                    //查询家族总收益聊币
                    $moneySum = \Micro\Models\SignAnchor::sum(
                                    array("column" => "money", "conditions" => "familyId = " . $id . " and money>0"));
                    //换算成人民币
                    $contidionValue = sprintf("%.3f", $moneySum / $this->config->cashScale); //兑换成人民币
                default:
                    break;
            }

            //查询规则表
            $ruleList = \Micro\Models\InvRule::find("type=" . $type);
            if ($ruleList->valid()) {
                //规则符号
                $syList = $this->config->ruleSymbol;
                foreach ($ruleList as $key => $val) {
                    if ($val->rids) {
                        //规则条件详细表
                        $ruleLog = \Micro\Models\InvRuleLog::find("id in ({$val->rids})");
                        if (count($ruleLog) == 1) {//只有一个条件
                            //判断是否符合条件
                            $ruleLog = $ruleLog->toArray();
                            if ($this->compareData($contidionValue, $ruleLog[0]['value'], $syList[$ruleLog[0]['symbol']])) {//符合
                                return $val->value; //结束
                            }
                        } else {//多个条件
                            if ($val->conditions == 2) { //且
                                $i = 0;
                                foreach ($ruleLog as $rk => $rv) {//判断是否每个条件都符合
                                    if ($this->compareData($contidionValue, $rv->value, $syList[$rv->symbol])) {//符合
                                        $i++;
                                    } else {//不符合
                                        break;
                                    }
                                }
                                if ($i == count($ruleLog)) {//每个条件都符合
                                    return $val->value; //结束
                                }
                            } elseif ($val->conditions == 1) { //或
                                foreach ($ruleLog as $rk => $rv) {//判断是否符合其中一个条件
                                    if ($this->compareData($contidionValue, $rv->value, $syList[$rv->symbol])) {//符合
                                        return $val->value; //结束                
                                    } else {//不符合
                                        continue;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->errLog('getRuleResult error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return 0;
    }

    //比较两个数
    private function compareData($value1, $value2, $conditons) {
        switch ($conditons) {
            case "=";
                if ($value1 == $value2) {
                    return true;
                }
                break;
            case ">=";
                if ($value1 >= $value2) {
                    return true;
                }
                break;
            case ">";
                if ($value1 > $value2) {
                    return true;
                }
                break;
            case "<";
                if ($value1 < $value2) {
                    return true;
                }
                break;
            case "<=";
                if ($value1 <= $value2) {
                    return true;
                }
                break;
        }
        return false;
    }

    //删除规则
    public function delRule($id) {
        try {
            $info = \Micro\Models\InvRule::findfirst($id);
            if ($info != false) {
                if ($info->type != $this->config->ruleType->liveRoomRobotOut) {
                    $rinfo = \Micro\Models\InvRuleLog::find("id in ({$info->rids})");
                    foreach ($rinfo as $r) {
                        $oldsymbol[] = $r->symbol;
                        $oldvalue[] = $r->value;
                        $r->delete();
                    }
                    $str1 = implode(',', $oldsymbol);
                    $str2 = implode(',', $oldvalue);
                }
                $type = $info->type;
                $value = $info->value;
                $conditions = $info->conditions;
                $conType = $info->conType;
                $conValue = $info->conValue;
                if ($info->delete() != false) {
                    //添加操作日志
                    switch ($type) {
                        case $this->config->ruleType->anchorBonus://主播分成比例
                            $log1 = $this->getRuleString($type, $value, $conditions, $str1, $str2);
                            $this->addOperate($this->username, '删除', "主播默认分成比例", "删除一条规则", $log1, '');
                            break;
                        case $this->config->ruleType->familyBonus://家族分成比例
                            $log1 = $this->getRuleString($type, $value, $conditions, $str1, $str2);
                            $this->addOperate($this->username, '删除', "默认家族分成比例", "删除一条规则", $log1, '');
                            break;
                        case $this->config->ruleType->liveRoomRobotIn://加机器人
                            $log1 = $this->getRuleString($type, $value, $conditions, $str1, $str2, $conType, $conValue);
                            $this->addOperate($this->username, '删除', "机器人添加", "删除一条规则", $log1, '');
                            break;
                        case $this->config->ruleType->liveRoomRobotOut://机器人退出
                            if ($conType == 2) {//直接减少
                                $log1 = "直接减少,减少" . $value . "%的机器人";
                            } elseif ($conType == 3) {//按比例减少
                                $log1 = "按比例减少," . $conValue . "分钟减少" . $value . "%";
                            } else {//等待
                                $log1 = "等待" . $value . "秒";
                            }
                            $this->addOperate($this->username, '删除', "机器人退出", "删除一条规则", $log1, '');
                            break;
                    }
                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }
            } else {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
        } catch (\Exception $e) {
            $this->errLog('delRule error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    //修改某个规则时 查询
    public function getOneRule($id) {
        $list = array();
        try {
            $logList = array();
            $info = \Micro\Models\InvRule::findfirst($id);
            if ($info != false) {
                if ($info->type != $this->config->ruleType->liveRoomRobotOut) {
                    $ruleLog = \Micro\Models\InvRuleLog::find("id in ({$info->rids})");
                    if ($ruleLog->valid()) {
                        $logList = array();
                        foreach ($ruleLog as $v) {
                            $logdata['lid'] = $v->id;
                            $logdata['symbol'] = $v->symbol;
                            $logdata['value'] = $v->value;
                            array_push($logList, $logdata);
                        }
                    }
                }
                $list['id'] = $info->id;
                $list['value'] = $info->value;
                $list['data'] = $logList;
                $list['conType'] = $info->conType;
                $list['conValue'] = $info->conValue;
            }
            return array("code" => "OK", "data" => $list);
        } catch (\Exception $e) {
            $this->errLog('getOneRule error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return array("code" => "DB_OPER_ERROR", "data" => "");
        }
    }

    //符号列表
    public function getSymbol() {
        $result = $this->config->ruleSymbol;
        $list = array();
        foreach ($result as $key => $val) {
            $data['id'] = $key;
            $data['name'] = $val;
            array_push($list, $data);
        }
        return array("code" => "OK", "data" => $list);
    }

    //添加规则
    public function addOneRule($type = 1, $conditions = 1, $value = '', $str1 = '', $str2 = '', $conType = 1, $conValue = '') {
        try {
            //冲突判断 here by/////
            //.....//
            $rids = '';
            $info = new \Micro\Models\InvRule();
            if ($str1 && $type != $this->config->ruleType->liveRoomRobotOut) {
                $rids = $this->addRuleLog($str1, $str2); //添加到规则记录表中
            }
            $info->conditions = $conditions;
            $info->value = $value;
            $info->type = $type;
            $info->rids = $rids;
            $info->conType = $conType;
            $info->conValue = $conValue;
            $result = $info->save();
            if ($result) {
                if ($type == $this->config->ruleType->liveRoomRobotOut) {//直播间机器人退出规则
                    $insertId = $info->id;
                    $newInfo = \Micro\Models\InvRule::findfirst($insertId);
                    $newInfo->sort = $insertId; //排序
                    $newInfo->save();
                }
                //添加操作日志
                switch ($type) {
                    case $this->config->ruleType->anchorBonus://主播分成比例
                        $log2 = $this->getRuleString($type, $value, $conditions, $str1, $str2);
                        $this->addOperate($this->username, '新增', "主播默认分成比例", "新增一条规则", '', $log2);
                        break;
                    case $this->config->ruleType->familyBonus://家族分成比例
                        $log2 = $this->getRuleString($type, $value, $conditions, $str1, $str2);
                        $this->addOperate($this->username, '新增', "默认家族分成比例", "新增一条规则", '', $log2);
                        break;
                    case $this->config->ruleType->liveRoomRobotIn://加机器人
                        $log2 = $this->getRuleString($type, $value, $conditions, $str1, $str2, $conType, $conValue);
                        $this->addOperate($this->username, '新增', "机器人添加", "新增一条规则", '', $log2);
                        break;
                    case $this->config->ruleType->liveRoomRobotOut://机器人退出
                        if ($conType == 2) {//直接减少
                            $log2 = "直接减少,减少" . $value . "%的机器人";
                        } elseif ($conType == 3) {//按比例减少
                            $log2 = "按比例减少," . $conValue . "分钟减少" . $value . "%";
                        } else {//等待
                            $log2 = "等待" . $value . "秒";
                        }
                        $this->addOperate($this->username, '删除', "机器人添加", "删除一条规则", '', $log2);
                        break;
                }


                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
        } catch (\Exception $e) {
            $this->errLog('addOneRule error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    /*
     * 修改规则
     * @param $id 规则id
     * @param $conditions 条件  1：或 2：且
     * @param $value 设置的值
     * @param $str1 符号（大于号、等于号、小于号..）的字符串集   格式为1,4,5 
     * @param $str2 符号对应的值的字符串集  格式为8,11,3
     */

    public function editOneRule($id, $conditions = 0, $value = '', $str1 = '', $str2 = '', $conType = 1, $conValue = '') {
        try {
            $info = \Micro\Models\InvRule::findfirst($id);
            if ($info != false) {
                $oldstr1 = '';
                $oldstr2 = '';
                $oldval = $info->value;
                $oldconditions = $info->conditions;
                $oldconType = $info->conType;
                $oldconValue = $info->conValue;
                //冲突判断//here by//////
                //。。。。。。。。
                //删除原来的记录
                if ($info->rids) {
                    $rinfo = \Micro\Models\InvRuleLog::find("id in ({$info->rids})");
                    foreach ($rinfo as $r) {
                        $oldsymbol[] = $r->symbol;
                        $oldvalue[] = $r->value;
                        $r->delete();
                    }
                    $oldstr1 = implode(',', $oldsymbol);
                    $oldstr2 = implode(',', $oldvalue);
                }
                $rids = '';
                if ($str1) {
                    $rids = $this->addRuleLog($str1, $str2); //添加到规则记录表中
                }
                $info->conditions = $conditions;
                $info->value = $value;
                $info->rids = $rids;
                $info->conType = $conType;
                $info->conValue = $conValue;
                $result = $info->save();
                if ($result) {
                    //添加操作日志
                    switch ($info->type) {
                        case $this->config->ruleType->anchorBonus://主播分成比例
                            $log1 = $this->getRuleString($info->type, $oldval, $oldconditions, $oldstr1, $oldstr2); //旧值
                            $log2 = $this->getRuleString($info->type, $value, $conditions, $str1, $str2); //新值
                            $this->addOperate($this->username, '修改', "主播默认分成比例", "修改一条规则", $log1, $log2);
                            break;
                        case $this->config->ruleType->familyBonus://家族分成比例
                            $log1 = $this->getRuleString($info->type, $oldval, $oldconditions, $oldstr1, $oldstr2); //旧值
                            $log2 = $this->getRuleString($info->type, $value, $conditions, $str1, $str2); //新值
                            $this->addOperate($this->username, '修改', "默认家族分成比例", "修改一条规则", $log1, $log2);
                            break;
                        case $this->config->ruleType->liveRoomRobotIn://机器人添加
                            $log1 = $this->getRuleString($info->type, $oldval, $oldconditions, $oldstr1, $oldstr2, $oldconType, $oldconValue); //旧值
                            $log2 = $this->getRuleString($info->type, $value, $conditions, $str1, $str2, $conType, $conValue); //新值
                            $this->addOperate($this->username, '修改', "机器人添加", "修改一条规则", $log1, $log2);
                            break;
                        case $this->config->ruleType->liveRoomRobotOut://机器人退出
                            if ($conType == 2) {//直接减少
                                $log2 = "直接减少,减少" . $value . "%的机器人";
                            } elseif ($conType == 3) {//按比例减少
                                $log2 = "按比例减少," . $conValue . "分钟减少" . $value . "%";
                            } else {//等待
                                $log2 = "等待" . $value . "秒";
                            }
                            if ($oldconType == 2) {//直接减少
                                $log1 = "直接减少,减少" . $oldval . "%的机器人";
                            } elseif ($oldconType == 3) {//按比例减少
                                $log1 = "按比例减少," . $oldconValue . "分钟减少" . $oldval . "%";
                            } else {//等待
                                $log1 = "等待" . $oldval . "秒";
                            }
                            $this->addOperate($this->username, '修改', "机器人退出", "修改一条规则", $log1, $log2);
                            break;
                    }

                    return $this->status->retFromFramework($this->status->getCode('OK'));
                }
            }
        } catch (\Exception $e) {
            $this->errLog('editOneRule error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    //拼接规则字符串，用于写入日志用
    public function getRuleString($type, $value, $conditions, $str1, $str2, $conType = 1, $conValue = 0) {
        $arr1 = explode(',', $str1);
        $arr2 = explode(',', $str2);

        switch ($type) {
            case $this->config->ruleType->anchorBonus://主播分成比例
                $anchorLevel = $this->getAnchorLevel(); //主播等级
                $newLevelArray = array();
                foreach ($anchorLevel as $an) {
                    $newLevelArray[$an['id']] = $an['name'];
                }
                foreach ($arr1 as $k1 => $v1) {
                    $logdata[] = "主播等级" . $this->config->ruleSymbol->$v1 . $newLevelArray[$arr2[$k1]];
                }
                $dataflag = "分成比例";
                $value = $value . "%";
                $log = '规则为：';
                break;
            case $this->config->ruleType->familyBonus://家族分成比例
                foreach ($arr1 as $k1 => $v1) {
                    $logdata[] = "用户消费" . $this->config->ruleSymbol->$v1 . $arr2[$k1] . "人民币";
                }
                $dataflag = "分成比例";
                $value = $value . "%";
                $log = '规则为：';
                break;
            case $this->config->ruleType->liveRoomRobotIn://机器人添加
                foreach ($arr1 as $k1 => $v1) {
                    $logdata[] = "用户数" . $this->config->ruleSymbol->$v1 . $arr2[$k1] . "人";
                }
                $dataflag = "规则";
                $log = '条件为：';

                if ($conType == 1) {//1代N
                    $values = "1代N," . $conValue . "个用户增加" . $value . "个机器人";
                } else {//直接增加
                    $values = "直接增加,每" . $conValue . "分钟加" . $value . "个机器人";
                }
                $value = $values;
                break;
        }
        $str = '';
        if ($conditions == 1) {
            $str = "或";
        } else if ($conditions == 2) {
            $str = "且";
        }
        $log.= implode($str, $logdata);
        $log.="," . $dataflag . "为：" . $value;
        return $log;
    }

    //修改规则排序-- 上移、下移
    public function setRuleSort($id, $moveType = 'up', $type = 1) {
        try {
            $info = \Micro\Models\InvRule::findfirst($id);
            if ($info == false) {
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            if ($moveType == 'down') {//下移
                $OtherInfo = \Micro\Models\InvRule::findfirst(
                                array(
                                    "conditions" => "type=" . $type . " and sort> " . $info->sort,
                                    "order" => "sort asc"
                                )
                );
            } else {//上移
                $OtherInfo = \Micro\Models\InvRule::findfirst(
                                array(
                                    "conditions" => "type=" . $type . " and sort< " . $info->sort,
                                    "order" => "sort desc"
                                )
                );
            }

            if ($OtherInfo != false) {
                $sort = $info->sort;
                $info->sort = $OtherInfo->sort;
                $OtherInfo->sort = $sort;
                $info->save();
                $OtherInfo->save();

                //添加日志记录
                if ($info->conType == 2) {//直接减少
                    $log1 = "直接减少,减少" . $info->value . "%的机器人";
                } elseif ($info->conType == 3) {//按比例减少
                    $log1 = "按比例减少," . $info->conValue . "分钟减少" . $info->value . "%";
                } else {//等待
                    $log1 = "等待" . $info->value . "秒";
                }
                if ($moveType == 'down') {//下移
                    $this->addOperate($this->username, '修改', "机器人退出", "下移规则", $log1, '');
                } else {//上移
                    $this->addOperate($this->username, '修改', "机器人退出", "上移规则", $log1, '');
                }
                return $this->status->retFromFramework($this->status->getCode('OK'));
            }
        } catch (\Exception $e) {
            $this->errLog('setRuleSort error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
    }

    //添加到规则记录表中
    private function addRuleLog($str1, $str2) {
        try {
            $arr1 = explode(',', $str1); //字符串转成数组
            $arr2 = explode(',', $str2); //字符串转成数组
            foreach ($arr1 as $k1 => $v1) {
                $model = new \Micro\Models\InvRuleLog();
                $model->symbol = $v1;
                $model->value = $arr2[$k1];
                $result = $model->save();
                if ($result) {
                    $insertId[] = $model->id;
                }
            }

            $ids = implode(',', $insertId);
            return $ids;
        } catch (\Exception $e) {
            $this->errLog('addRuleLog error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

    /*
     * 按时间区间统计数据
     * $type 类型  hour/month/day/week 
     * $result 数据库查询结果
     * $startDate 开始日期
     * $endDate 结束日期 2015-04-14
     */

    public function getDataByDates($type = 'day', $result, $startDate, $endDate) {
        $return = array();
        $newResult = array();
        if (is_array($result)) {
            $newResult = $result;
        } else {
            if ($result != false) {
                foreach ($result as $v) {//转为以时间为键值的数组
                    $newResult[$v->time] = $v->sum;
                }
            }
        }

        switch ($type) {
            case 'hour'://按时统计 
                $dateList = $this->getHour(); //查询这天的24小时
                foreach ($dateList as $val) {
                    $data['time'] = $val;
                    $data['sum'] = !empty($newResult[$val]) ? $newResult[$val] : 0;
                    array_push($return, $data);
                }
                break;
            case 'week'://按周统计 
                $dateList = $this->getWeeks($startDate, $endDate); //查询出两个日期之间的自然周
                foreach ($dateList as $key => $val) {
                    $data['time'] = date("m/d", strtotime($val[0])) . "-" . date("m/d", strtotime($val[1]));
                    $data['sum'] = !empty($newResult[$key]) ? $newResult[$key] : 0;
                    array_push($return, $data);
                }
                break;
            case 'month'://按月统计
                $dateList = $this->getMonths($startDate, $endDate); //查询出两个日期之间的自然月
                foreach ($dateList as $key => $val) {
                    $data['time'] = date("m/d", strtotime($val[0])) . "-" . date("m/d", strtotime($val[1]));
                    $data['sum'] = !empty($newResult[$key]) ? $newResult[$key] : 0;
                    array_push($return, $data);
                }
                break;
            default ://按天统计
                $dateList = $this->getDates($startDate, $endDate); //查询出两个日期之间的天
                foreach ($dateList as $val) {
                    $data['time'] = date("m/d", strtotime($val));
                    $data['sum'] = !empty($newResult[$val]) ? $newResult[$val] : 0;
                    array_push($return, $data);
                }
        }
        return $return;
    }

    //获取一天之内24小时
    public function getHour() {
        for ($i = 0; $i < 24; $i++) {
            $result[] = date('H', strtotime(date('Y-m-d')) + 60 * 60 * $i);
        }
        return $result;
    }

    //获得两个日期之间所有天
    public function getDates($start, $end) {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        do {
            $result[] = date('Ymd', $dt_start);
        } while (($dt_start += 86400) <= $dt_end);
        return $result;
    }

    /**
     * 获取指定日期之间的自然周
     */
    function getWeeks($start, $end) {
        //先把两个日期转为时间戳        
        $startdate = strtotime($start);
        $enddate = strtotime($end);
        //判断是第几周
        $week = date('YW', $startdate);

        $end_date = strtotime("next monday", $enddate);
        if (date("w", $startdate) == 1) {
            $start_date = $startdate;
        } else {
            $start_date = strtotime("last monday", $startdate);
        }
        //计算时间差多少周         
        $countweek = ($end_date - $start_date) / (7 * 24 * 3600);
        for ($i = 0; $i < $countweek; $i++) {
            $sd = date("Y-m-d", $start_date);
            $ed = strtotime("+ 6 days", $start_date);
            $eed = date("Y-m-d", $ed);
            //判断是第几周
            $weeknumber = date('YW', $start_date);
            $arr[$weeknumber] = array($sd, $eed);
            $start_date = strtotime("+ 1 day", $ed);
        }
        $startdate > strtotime($arr[$week][0]) && $arr[$week][0] = date("Y-m-d", $startdate);
        $end < $arr[$weeknumber][1] && $arr[$weeknumber][1] = date("Y-m-d", $enddate);
        return $arr;
    }

    //获得两个日期之间的自然月
    function getMonths($date1, $date2) {
        //获得年月日
        list($y1, $m1, $d1) = explode('-', $date1);
        list($y2, $m2, $d2) = explode('-', $date2);
        //总月数
        $math = ($y2 - $y1) * 12 + $m2 - $m1;
        $my_arr = array();
        //同一个月
        if ($y1 == $y2 && $m1 == $m2) {
            if ($m1 < 10) {
                $m1 = intval($m1);
                $m1 = '0' . $m1;
            }
            if ($m2 < 10) {
                $m2 = intval($m2);
                $m2 = '0' . $m2;
            }
            $my_arr[$y1 . $m1] = array($date1, $date2);
            return $my_arr;
        }
        $p = $m1;
        $x = $y1;
        for ($i = 0; $i <= $math; $i++) {
            if ($p > 12) {
                $x = $x + 1;
                $p = $p - 12;
                if ($p < 10) {
                    $p = intval($p);
                    $p = '0' . $p;
                }
                //月的第一天、最后一天
                $firstday = date('Y-m-01', strtotime($x . $p . '01'));
                $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
                $my_arr[$x . $p] = array($firstday, $lastday);
            } else {
                if ($p < 10) {
                    $p = intval($p);
                    $p = '0' . $p;
                }
                //月的第一天、最后一天
                $firstday = date('Y-m-01', strtotime($x . $p . '01'));
                $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
                $my_arr[$x . $p] = array($firstday, $lastday);
            }
            $p = $p + 1;
        }

        if ($m1 < 10) {
            $m1 = intval($m1);
            $m1 = '0' . $m1;
        }
        $p = $p - 1;
        if ($p < 10) {
            $p = intval($p);
            $p = '0' . $p;
        }
        strtotime($date1) > strtotime($my_arr[$y1 . $m1][0]) && $my_arr[$y1 . $m1][0] = date("Y-m-d", strtotime($date1));
        strtotime($date2) < $my_arr[$x . $p][1] && $my_arr[$x . $p][1] = date("Y-m-d", strtotime($date2));
        return $my_arr;
    }

    //秒转化为小时/分
    function secToTime($sec) {
        $sec = floor($sec / 60);
        if ($sec >= 60) {
            $hour = floor($sec / 60);
            $min = $sec % 60;
            $res = $hour . ' 小时 ';
            $min != 0 && $res .= $min . ' 分钟';
        } else {
            $res = $sec . ' 分钟';
        }
        return $res;
    }

    //判断对象是否为空
    public function is_empty($object) {
        $array = $object->toArray();
        if (!$array) {
            return true;
        }
        if (!$array[0]) {
            return true;
        }
        foreach ($array[0] as $key => $value) {
            if (!$value) {
                unset($array[0][$key]);
            }
        }
        if (empty($array[0])) {
            return true;
        }
        return false;
    }

    //获得用户底薪
    public function getAnchorBasicSalary($uid, $income) {
        try {
            $basicSalary = \Micro\Models\BasicSalary::findfirst('uid = ' . $uid);
            if ($basicSalary != false) {
				return $basicSalary->money;
               /*  if ($basicSalary->type == 1) {//保底薪资
                    if (!$basicSalary->expirationTime || $basicSalary->expirationTime > time()) {//未过期
                        if ($income < $basicSalary->condition) {//满足发放条件
                            return $basicSalary->money;
                        }
                    }
                } elseif ($basicSalary->type == 2) {//固定薪资
                    if (!$basicSalary->expirationTime || $basicSalary->expirationTime > time()) {//未过期
                        return $basicSalary->money;
                    }
                } */
            }
        } catch (\Exception $e) {
            $this->errLog('getAnchorBasicSalary error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return 0;
    }

    //查询待处理申请数量
    public function getApplyNum($type) {
        try {
            if ($type == 5) {//待结算
                $count = \Micro\Models\InvAccountsLog::count("status=0");
            } else if($type == 2) {//待处理
                $count = \Micro\Models\InvSettleLog::count('(type = ' . $this->config->changeType[2]['type'] . ' or type = ' . $this->config->changeType[3]['type'] . ') and status = 0');
            } else if($type == 4){//主播
                $count = \Micro\Models\ApplyLog::count("type = {$this->config->applyType->sign} and status = " . $this->config->applyStatus->ing);
            } else if($type == 6){//主播封面待审核
                $count = \Micro\Models\AnchorPoster::count("isShow=1 and status=0");
            }else {//家族
                $count = \Micro\Models\ApplyLog::count("type = " . $this->config->applyType->createFamily . " and status=" . $this->config->applyStatus->ing);
            }
            return $count ? $count : 0;
        } catch (\Exception $e) {
            $this->errLog('getApplyNum error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
        }
        return 0;
    }

    //登录日志
    public function addLoginLog($uid, $type) {
        try {
            $log = new \Micro\Models\InvLoginLog();
            $log->uid = $uid;
            $log->ip = $this->get_client_ip();
            $log->loginType = $type;
            $log->createTime = time();
            $result = $log->save();
            if ($result) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->errLog('addLoginLog error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = false) {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip[$type];
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos)
                    unset($arr[$pos]);
                $ip = trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    // 操作日志
    public function addOperate($uid, $operaType, $operaObject, $operaDesc, $log1 = '', $log2 = '') {
        try {
            $operateLog = new \Micro\Models\InvOperationLog();
            $operateLog->uid = $uid;
            $operateLog->operaObject = $operaObject;
            $operateLog->operaType = $operaType;
            $operateLog->operaDesc = $operaDesc;
            $operateLog->createTime = time();
            $operateLog->log1 = $log1;
            $operateLog->log2 = $log2;
            $res = $operateLog->save();
            if ($res) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->errLog('addOperate error username=' . $this->username . ' errorMessage = ' . $e->getMessage());
            return;
        }
    }

    //导出excel
    public function getExcel($fileName, $headArr, $data, $ex = 'excel2007') {
        //   $headArr = array("第一列", "第二列", "第三列");
        //  $data = array(array(1, 2), array(1, 3), array(5, 7));

        $date = date("Y_m_d", time());
        //$fileName .= "_{$date}";

        //创建新的PHPExcel对象 
        $objPHPExcel = $this->di->get("phpExcel");
        //设置表头    
        $key = ord("A");
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach ($data as $key => $rows) { //行写入         
            $span = ord("A");
            foreach ($rows as $keyName => $value) {// 列写入         
                $j = chr($span);
                $objActSheet->setCellValue($j . $column, $value);
                $span++;
            }
            $column++;
        }

        //$fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表   
        $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表   
		
		
		$this->_saveExcelObj($objPHPExcel, $fileName, $ex);
    }
	
	/**
	 * 导出excel
	 * @param string $fileName 保存的文件名
	 * @param array $data 保存的用户数据数组
	 * @param string $ex 保存的数据格式
	 * @return void();
	 */
    public function getAnchorExcels($fileName, $data, $ex = 'excel2007') {

        $date = date("Y_m_d", time());
        $fileName .= "_{$date}";

        //创建新的PHPExcel对象 
        $objPHPExcel = $this->di->get("phpExcel");
		
		$excelIndex = 0;
		foreach($data as $member){
			
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($excelIndex);		//激活当前Tab
			$objActSheet = $objPHPExcel->getActiveSheet();				//获取当前对象
			
			$objActSheet->setCellValue('A1', '真实姓名');
			$objActSheet->setCellValue('B1', 'UID');
			$objActSheet->setCellValue('C1', '昵称');
			$objActSheet->setCellValue('D1', '电话');
			$objActSheet->setCellValue('E1', '底薪方式');
			$objActSheet->setCellValue('F1', '底薪金额');
			
			$objActSheet->setCellValue('A2', $member['realName']);
			$objActSheet->setCellValue('B2', $member['uid']);
			$objActSheet->setCellValue('C2', $member['nickName']);
			$objActSheet->setCellValue('D2', $member['telephone']);			
			$objActSheet->setCellValue('E2', $member['type']);
			$objActSheet->setCellValue('F2', $member['money']);
			
			$objActSheet->setTitle('结算用户'.$member['nickName']);
			
			$excelIndex++;
		}
		
		$this->_saveExcelObj($objPHPExcel, $fileName, $ex);
    }
	
	//查询主播在一段时间内的工作情况
	public function getPeriodExcel($fileName, $data, $ex = 'excel2007') {
		
		$objActSheet = null;
		
		$objPHPExcel = $this->_getExcelObj($objActSheet);
		
		$objActSheet->setCellValue('A1', $fileName);
		$objActSheet->mergeCells('A1:F1');
		$objActSheet->getStyle('A1')->getAlignment()->setHorizontal('center');
		
		//日期	播出起止时间	播出时长	时长总计	收益(聊币)	收益总计
		
		$objActSheet->setCellValue('A2', '日期');
		$objActSheet->setCellValue('B2', '播出起止时间');
		$objActSheet->setCellValue('C2', '播出时长');
		$objActSheet->setCellValue('D2', '时长总计');
		// $objActSheet->setCellValue('E2', '收益(聊币)');
		// $objActSheet->setCellValue('F2', '收益总计');
		
		$excelIndex = 3;
		
		$baseTime = strtotime(date('Y-m-d'));
		
		foreach($data['data'] as $day => $list){
			
			$objActSheet->setCellValue('A'.$excelIndex, $day);
            // $objActSheet->setCellValue('D'.$excelIndex, date('H小时i分钟',$list['sumLength']+$baseTime));
			$objActSheet->setCellValue('D'.$excelIndex, $this->translateTime($list['sumLength']));
			// $objActSheet->setCellValue('F'.$excelIndex, $list['income']);
			
			$tmpToIndex = (count($list['list']) > 1) ? ((count($list['list'])-1)+$excelIndex) : $excelIndex;
			
			$objActSheet->mergeCells('A'.$excelIndex.':A'.$tmpToIndex);
			$objActSheet->mergeCells('D'.$excelIndex.':D'.$tmpToIndex);
			// $objActSheet->mergeCells('F'.$excelIndex.':F'.$tmpToIndex);
			
			$objActSheet->getStyle('A'.$excelIndex)->getAlignment()->setVertical('center');
			$objActSheet->getStyle('D'.$excelIndex)->getAlignment()->setVertical('center');
			// $objActSheet->getStyle('F'.$excelIndex)->getAlignment()->setVertical('center');
				
			foreach($list['list'] as $tmp){
				$objActSheet->setCellValue('B'.$excelIndex, $tmp['publicTime'].'-'.$tmp['endTime']);
                // $objActSheet->setCellValue('C'.$excelIndex, date('H小时i分钟',$tmp['length']+$baseTime));
				$objActSheet->setCellValue('C'.$excelIndex, $this->translateTime($tmp['length']));
				// $objActSheet->setCellValue('E'.$excelIndex, $tmp['income']);
					
				$excelIndex++;
			}
			
		}	
		$objActSheet->setCellValue('C'.$excelIndex, '总计');
        // $objActSheet->setCellValue('D'.$excelIndex, date('d天H小时i分钟',$data['sumLength']+$baseTime));
        $objActSheet->setCellValue('D'.$excelIndex, $this->translateTime($data['sumLength']));
  //       $objActSheet->setCellValue('B'.$excelIndex, $data['sumLength1']);
		// $objActSheet->setCellValue('E'.$excelIndex, $data['sumLength2']);
		// $objActSheet->setCellValue('F'.$excelIndex, $data['income']);
		
		
		/*
		foreach($data['list'] as $member){
			
			//$objPHPExcel->createSheet();
				
			//date_default_timezone_set('PRC'); //设置中国时区 
			$objActSheet->setCellValue('A'.$excelIndex, $member['dayTime']);
			$objActSheet->setCellValue('B'.$excelIndex, $member['publicTime'].'-'.$member['endTime']);
			$objActSheet->setCellValue('C'.$excelIndex, date('H小时i分钟',$member['length']+$baseTime));
			$objActSheet->setCellValue('D'.$excelIndex, $member['income']);				
			
			$excelIndex++;
		}		
		
		$objActSheet->setCellValue('A'.$excelIndex, '');
		$objActSheet->setCellValue('B'.$excelIndex, '总计');
		$objActSheet->setCellValue('C'.$excelIndex, date('H小时i分钟',$data['sumLength']+$baseTime));
		$objActSheet->setCellValue('D'.$excelIndex, $data['income']);	
		*/
	
		$objActSheet->setTitle('工作情况');
	
		$this->_saveExcelObj($objPHPExcel, $fileName, $ex);
    }

    public function translateTime($time){
        if($time >= 3600){
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
        return $hours . '小时' . $minutes . '分钟';
    }
	
	//待结算 工资 excel
	public function getSalaryExcel($fileName, $data, $ex = 'excel2007'){
		
		$objPHPExcel = $this->_getExcelObj($objActSheet);
		
		$objActSheet->setCellValue('A1', 'uid');
		$objActSheet->setCellValue('B1', '姓名');
		$objActSheet->setCellValue('C1', '电话');
		$objActSheet->setCellValue('D1', '身份证');
		$objActSheet->setCellValue('E1', '底薪');
		$objActSheet->setCellValue('F1', '分成');
		$objActSheet->setCellValue('G1', '奖励');
		$objActSheet->setCellValue('H1', '总工资');
		$objActSheet->setCellValue('I1', '工资时长');
		$objActSheet->setCellValue('J1', '备注');
		
		$excelIndex = 2;
		
		foreach($data['list'] as $val){

			$objActSheet->setCellValue('A'.$excelIndex, $val['uid']);
			$objActSheet->setCellValue('B'.$excelIndex, $val['realName']);
			$objActSheet->setCellValue('C'.$excelIndex, $val['telephone']);
			$objActSheet->setCellValue('D'.$excelIndex, $val['idCard']);				
			$objActSheet->setCellValue('E'.$excelIndex, $val['basicSalary']);				
			$objActSheet->setCellValue('F'.$excelIndex, $val['rmb']);				
			$objActSheet->setCellValue('G'.$excelIndex, '');				
			$objActSheet->setCellValue('H'.$excelIndex,  $val['basicSalary']+$val['rmb']);				
			$objActSheet->setCellValue('I'.$excelIndex, $val['duration']);				
			$objActSheet->setCellValue('J'.$excelIndex, '');				
			
			$excelIndex++;
		}		
		$objActSheet->setCellValue('A'.$excelIndex, '');
		$objActSheet->setCellValue('B'.$excelIndex, '');
		$objActSheet->setCellValue('C'.$excelIndex, '');
		$objActSheet->setCellValue('D'.$excelIndex, '');
		$objActSheet->setCellValue('E'.$excelIndex, '');
		$objActSheet->setCellValue('F'.$excelIndex, '');
		$objActSheet->setCellValue('G'.$excelIndex, '总计');
		$objActSheet->setCellValue('H'.$excelIndex, $data['wage']);
		$objActSheet->setCellValue('I'.$excelIndex,$data['length'] );
		$objActSheet->setCellValue('J'.$excelIndex,'');	
	
		$objActSheet->setTitle('工资表');
	
		$this->_saveExcelObj($objPHPExcel, $fileName, $ex);
	}
	
	//待结算表
	public function getWaitExcel($fileName, $data, $ex = 'excel2007'){
		$objPHPExcel = $this->_getExcelObj($objActSheet);
		$objActSheet->setCellValue('A1', 'ID');
		$objActSheet->setCellValue('B1', '姓名');
		$objActSheet->setCellValue('C1', '电话');
		$objActSheet->setCellValue('D1', '身份证');
		$objActSheet->setCellValue('E1', '底薪');
		$objActSheet->setCellValue('F1', '分成');
		$objActSheet->setCellValue('G1', '奖励');
		$objActSheet->setCellValue('H1', '总工资');
		$objActSheet->setCellValue('I1', '开户行');
		$objActSheet->setCellValue('J1', '卡号');
		
		$excelIndex = 2;
		
		foreach($data['list'] as $val){

			$objActSheet->setCellValue('A'.$excelIndex, $val['uid']);
			$objActSheet->setCellValue('B'.$excelIndex, $val['name']);
			$objActSheet->setCellValue('C'.$excelIndex, $val['telephone']);
			$objActSheet->setCellValue('D'.$excelIndex, $val['idCard']);				
			$objActSheet->setCellValue('E'.$excelIndex, $val['basicSalary']);				
			$objActSheet->setCellValue('F'.$excelIndex, $val['rmb']);				
			$objActSheet->setCellValue('G'.$excelIndex, '');				
			$objActSheet->setCellValue('H'.$excelIndex, $val['basicSalary']+$val['rmb']);				
			$objActSheet->setCellValue('I'.$excelIndex, $val['bank']);				
			$objActSheet->setCellValue('J'.$excelIndex, $val['cardNumber']);				
			
			$excelIndex++;
		}		
		$objActSheet->setCellValue('A'.$excelIndex, '');
		$objActSheet->setCellValue('B'.$excelIndex, '');
		$objActSheet->setCellValue('C'.$excelIndex, '');
		$objActSheet->setCellValue('D'.$excelIndex, '');
		$objActSheet->setCellValue('E'.$excelIndex, '');
		$objActSheet->setCellValue('F'.$excelIndex, '');
		$objActSheet->setCellValue('G'.$excelIndex, '总计');
		$objActSheet->setCellValue('H'.$excelIndex, $data['wage']);
		$objActSheet->setCellValue('I'.$excelIndex, '');
		$objActSheet->setCellValue('J'.$excelIndex,'');	
	
		$objActSheet->setTitle('待结算工资表');
	
		$this->_saveExcelObj($objPHPExcel, $fileName, $ex);
	}

    //待结算表
    public function getSettleExcel($fileName, $data, $ex = 'excel2007'){
        $objPHPExcel = $this->_getExcelObj($objActSheet);
        $objActSheet->setCellValue('A1', 'ID');
        $objActSheet->setCellValue('B1', '姓名');
        $objActSheet->setCellValue('C1', '电话');
        $objActSheet->setCellValue('D1', '身份证');
        $objActSheet->setCellValue('E1', '单号');
        $objActSheet->setCellValue('F1', '主播UID');
        $objActSheet->setCellValue('G1', '');
        $objActSheet->setCellValue('H1', '总工资');
        $objActSheet->setCellValue('I1', '开户行');
        $objActSheet->setCellValue('J1', '卡号');
        
        $excelIndex = 2;
        
        foreach($data['list'] as $val){

            $objActSheet->setCellValue('A'.$excelIndex, $val['id']);
            $objActSheet->setCellValue('B'.$excelIndex, $val['name']);
            $objActSheet->setCellValue('C'.$excelIndex, $val['telephone']);
            $objActSheet->setCellValue('D'.$excelIndex, $val['idCard']);                
            $objActSheet->setCellValue('E'.$excelIndex, $val['orderNum']);               
            $objActSheet->setCellValue('F'.$excelIndex, $val['uid']);               
            $objActSheet->setCellValue('G'.$excelIndex, '');                
            $objActSheet->setCellValue('H'.$excelIndex, $val['rmb']);               
            $objActSheet->setCellValue('I'.$excelIndex, $val['bank']);              
            $objActSheet->setCellValue('J'.$excelIndex, $val['cardNumber']);                
            
            $excelIndex++;
        }       
        $objActSheet->setCellValue('A'.$excelIndex, '');
        $objActSheet->setCellValue('B'.$excelIndex, '');
        $objActSheet->setCellValue('C'.$excelIndex, '');
        $objActSheet->setCellValue('D'.$excelIndex, '');
        $objActSheet->setCellValue('E'.$excelIndex, '');
        $objActSheet->setCellValue('F'.$excelIndex, '');
        $objActSheet->setCellValue('G'.$excelIndex, '总计');
        $objActSheet->setCellValue('H'.$excelIndex, $data['wage']);
        $objActSheet->setCellValue('I'.$excelIndex, '');
        $objActSheet->setCellValue('J'.$excelIndex,''); 
    
        $objActSheet->setTitle('待处理申请表');
    
        $this->_saveExcelObj($objPHPExcel, $fileName, $ex);
    }
	
	/**
	 * 获取Excel对象
	 * @param object $objActSheet 当前操作Table
	 * @return object(PHPExcel);
	 */
	private function _getExcelObj(&$objActSheet){
		//创建新的PHPExcel对象 
        $objPHPExcel = $this->di->get("phpExcel");
		
		$objPHPExcel->setActiveSheetIndex(0);		//激活当前Tab
		
		$objActSheet = $objPHPExcel->getActiveSheet();				//获取当前对象
		
		return $objPHPExcel;
	}
	
	/**
	 * 输出Excel文件
	 * @param object $objPHPExcel Excel对象
	 * @param string $fileName 保存的文件名
	 * @param string $ex 保存的Excel兼容版本[一般默认]
	 * @return void;
	 */
	private function _saveExcelObj($objPHPExcel,$fileName,$ex){
		
		$objPHPExcel->setActiveSheetIndex(0);
	
        //将输出重定向到一个客户端web浏览器    
        if ($ex == 'excel5') {//excel2003
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        } else {//excel2007
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$fileName\".xlsx");
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        }
        $objWriter->save('php://output'); //文件通过浏览器下载 
        exit;
	}

    //检查主播的底薪方式是否需要变更
    public function checkAnchorChange($anchorId){
        try {
            $basicSalary = \Micro\Models\BasicSalary::findfirst('uid = '.$anchorId . ' ORDER BY id DESC');
            if(!empty($basicSalary)){
                //判断是否已经过期
                if($basicSalary->affectTime > 0 && $basicSalary->affectTime <= time()){
                    $changeInfo = unserialize($basicSalary->changeInfo);
                    $basicSalary->changeInfo = '';
                    $basicSalary->type = $changeInfo['type'];
                    $basicSalary->expirationTime = $changeInfo['expirationTime'];
                    $basicSalary->affectTime = 0;
                    $basicSalary->money = $changeInfo['money'];
                    $basicSalary->status = $changeInfo['status'];
                    $basicSalary->save(); 

                    return \Micro\Models\BasicSalary::findfirst('uid = '.$anchorId . ' ORDER BY id DESC');
                }elseif($basicSalary->affectTime == 0){
                    if($basicSalary->status == 0 && $basicSalary->expirationTime <= time()){
                        $basicSalary->type = 0;
                        $basicSalary->expirationTime = 0;
                        // $basicSalary->affectTime = 0;
                        $basicSalary->money = 0;
                        $basicSalary->status = 0;
                        $basicSalary->save();
                        return \Micro\Models\BasicSalary::findfirst('uid = '.$anchorId . ' ORDER BY id DESC');
                    }
                        
                }
                return false;
            }

        } catch (\Exception $e) {
            $this->errLog('checkAnchorChange error errorMessage = ' . $e->getMessage());
        }
    }

    //举报
    public function addInform($addData){
        try {
            $user = new \Micro\Models\Inform();
            $user->uid = $addData['uid'];
            $user->targetId = $addData['targetId'];
            $user->type = $addData['type'];
            $user->content = $addData['content'];
            $user->addTime = time();
            $user->pic1 = $addData['pic1'];
            $user->pic2 = $addData['pic2'];
            $user->pic3 = $addData['pic3'];
            $user->status = 0;
            $user->save();
            return true;
        } catch (\Exception $e) {
            $this->errLog('addRoomUserStaus errorMessage = '.$e->getMessage());
            return false;
        }
    }

    //举报
    public function addSuggestion($addData){
        try {
            $Suggestions = new \Micro\Models\Suggestions();
            $Suggestions->uid = $addData['uid'];
            $Suggestions->type = $addData['type'];
            $Suggestions->content = $addData['content'];
            $Suggestions->pic1 = $addData['pic1'];
            $Suggestions->pic2 = $addData['pic2'];
            $Suggestions->pic3 = $addData['pic3'];
            $Suggestions->log = $addData['log'];
            $Suggestions->mobile = $addData['mobile'];
            $Suggestions->email = $addData['email'];
            $Suggestions->qq = $addData['qq'];
            $Suggestions->devinfo = $addData['devInfo'];
            $Suggestions->addTime = time();
            $Suggestions->status = 0;
            $Suggestions->devInfo = $addData['devInfo'];
            $Suggestions->save();
            return true;
        } catch (\Exception $e) {
            $this->errLog('addRoomUserStaus errorMessage = '.$e->getMessage());
            return false;
        }
    }	
	
}
