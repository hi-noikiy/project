<?php

namespace Micro\Frameworks\Logic\Investigator;

use Phalcon\DI\FactoryDefault;

//客服后台--基础类--无权限限制
class InvMgrBase {

    protected $di;
    protected $config;

    public function __construct() {

        $this->di = FactoryDefault::getDefault();
        $this->config = $this->di->get('config');
    }

    //登录
    public function login($username, $password) {
        $invLogin = new InvLogin();
        return $invLogin->login($username, $password);
    }

    //退出登录
    public function loginOut() {
        $invLogin = new InvLogin();
        return $invLogin->loginOut();
    }

    //检查登录
    public function checkLogin($return = 1) {
        $invBase = new InvBase();
        return $invBase->checkLogin($return);
    }

    //获得主播列表
    public function getAnchor($isSign, $isFamily, $namelike, $order, $currentPage, $pageSize) {
        $invAnchor = new InvAnchor();
        return $invAnchor->getAnchorList($isSign, $isFamily, $namelike, $order, $currentPage, $pageSize);
    }

    //修改密码
    public function editPassword($password, $type) {
        $invUser = new InvUser();
        if ($type == 1) {//判断旧密码是否正确
            return $invUser->checkPassword($password);
        } else {//修改新密码
            return $invUser->editPassword($password);
        }
    }

    //获得模块
    public function getModule() {
        $invModule = new InvModule();
        return $invModule->getModuleList();
    }

    //首页--统计信息
    public function getIndexCount() {
        $invIndex = new InvIndex();
        return $invIndex->getIndexStatistics();
    }

    //主页 ## 全部房间
    public function getAllRooms($type,$userName,$page,$pageSize,$order=0){
        $invIndex = new InvIndex();
        return $invIndex->getAllRooms($type,$userName,$page,$pageSize,$order);

    }

    //首页--待处理申请列表
    public function getPendingApply($currentPage, $pageSize) {
        $invIndex = new InvIndex();
        return $invIndex->getPendingApplyList($currentPage, $pageSize);
    }

    //主播等级
    public function getAnchorLevel() {
        $invBase = new InvBase();
        $result = $invBase->getAnchorLevel();
        if ($result) {
            return array('code' => 'OK', 'data' => $result);
        }
        return array('code' => 'DB_OPER_ERROR', 'data' => '');
    }

    //规则-符号
    public function getSymbol() {
        $invBase = new InvBase();
        return $invBase->getSymbol();
    }

    //查询某条规则
    public function getOneRule($id) {
        $invBase = new InvBase();
        return $invBase->getOneRule($id);
    }

    //查询某条例外
    public function getOneExceptin($id) {
        $invAnchor = new InvAnchor();
        return $invAnchor->getOneExceptin($id);
    }

    //查询用户列表
    public function getInvUsers() {
        $invUser = new InvUser();
        return $invUser->getUserList();
    }

    //添加用户
    public function addInvUser($username, $password, $roleId) {
        $invUser = new InvUser();
        return $invUser->addUser($username, $password, $roleId);
    }

    //修改用户信息
    public function editInvUser($uid, $info) {
        $invUser = new InvUser();
        return $invUser->editInfo($uid, $info);
    }

    //查询主播分成比例
    public function getAnchorRuleResult($uid) {
        $invBase = new InvBase();
        return $invBase->checkRuleByOne($this->config->ruleType->anchorBonus, $uid);
    }

    //查询待处理申请数
    public function getApplyNum($type) {
        $invBase = new InvBase();
        return $invBase->getApplyNum($type);
    }

}
