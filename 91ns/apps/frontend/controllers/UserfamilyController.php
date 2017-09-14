<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

class UserFamilyController extends UserController
{
    public function initialize()
    {
        parent::initialize();
        if(!$this->request->isAjax()) {
            $this->view->ns_title = '家族';
            $this->view->ns_type = 'userfamily';
        }
    }

    public function indexAction(){
        $this->view->visitor = FALSE;
        $familyId = $this->request->get('fid');//checkFamilyStatus

        if(!empty($familyId)){
            $result = $this->familyMgr->getFamilyInfo($familyId);
            $this->pageCheckSuccess($result);
        }else{
            $user = $this->userAuth->getUser();
            if (!$user) {
                return $this->redirect('/');
            }
            $result = $this->familyMgr->getFamilyInfoByUid($user->getUid());
            $this->pageCheckSuccess($result);
        }

        if(!$this->familyMgr->checkFamilyAvailable($result['data']['id'])){
            $this->pageError();
        }

        //绑定家族信息
        $this->view->familyInfo = $result['data'];

        //是否家族长
        if($user){
            if($user->getUid() == $result['data']['creatorUid']){
                $this->view->familyCreator = TRUE;
            }else{
                $this->view->familyCreator = FALSE;
            }
        }else{
            $this->view->familyCreator = FALSE;
            $this->view->visitor = TRUE;
        }


        //家族长信息
        $creatorInfo = $this->familyMgr->getFamilyCreatorInfo($result['data']['creatorUid']);
        if ($creatorInfo['code'] == $this->status->getCode('OK')) {
            $this->view->familyCreatorInfo = $creatorInfo['data'];
        }else{
            $this->view->familyCreatorInfo = array();
        }

        //家族成员
        $familyMember = $this->familyMgr->getFamilyMemberInfo($result['data']['id']);
        if ($familyMember['code'] == $this->status->getCode('OK')) {
            $this->view->familyMembers = $familyMember['data']['data'];
        }else{
            $this->view->familyMembers = array();
        }
    }

    public function incomeFamilyAction(){
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->redirect('');
        }
        $result = $this->familyMgr->getFamilyInfoByUid($user->getUid());

        //是否家族长
        if ($result['code'] == $this->status->getCode('OK') && $user->getUid() == $result['data']['creatorUid']) {

            $this->view->familyInfo = $result['data'];

            //获得本周数据
            $thisWeekAnchorConsume = $this->familyMgr->getThisWeekAnchorConsume($result['data']['id']);
            if($thisWeekAnchorConsume['code'] == $this->status->getCode('OK')){
                $this->view->thisWeekConsume = $thisWeekAnchorConsume['data'];
            }else{
                $this->view->thisWeekConsume = FALSE;
            }

            //获得上周数据
            $lastWeekAnchorConsume = $this->familyMgr->getLastWeekAnchorConsume($result['data']['id']);
            if($lastWeekAnchorConsume['code'] == $this->status->getCode('OK')){
                $this->view->lastWeekConsume = $lastWeekAnchorConsume['data'];
            }else{
                $this->view->lastWeekConsume = FALSE;
            }

            //获得本月排行
            $thisMonthAnchorConsume = $this->familyMgr->getThisMonthAnchorConsume($result['data']['id']);
            if($thisMonthAnchorConsume['code'] == $this->status->getCode('OK')){
                $this->view->thisMonthConsume = $thisMonthAnchorConsume['data'];
            }else{
                $this->view->thisMonthConsume = FALSE;
            }

            //获得上月排行
            $lastMonthAnchorConsume = $this->familyMgr->getLastMonthAnchorConsume($result['data']['id']);
            if($lastMonthAnchorConsume['code'] == $this->status->getCode('OK')){
                $this->view->lastMonthConsume = $lastMonthAnchorConsume['data'];
            }else{
                $this->view->lastMonthConsume = FALSE;
            }

            //获得总排行
            $totalAnchorConsume = $this->familyMgr->getTotalAnchorConsume($result['data']['id']);
            if($totalAnchorConsume['code'] == $this->status->getCode('OK')){
                $this->view->totalConsumes = $totalAnchorConsume['data'];
            }else{
                $this->view->totalConsumes = FALSE;
            }

        }else{
            return $this->redirect('');
        }
    }

    public function myIncomeAction(){
        $CreatorGetConsume = FALSE;
        //获得本周排行
        $thisWeekAnchorConsume = $this->familyMgr->getThisWeekAnchorConsume($CreatorGetConsume);
        if($thisWeekAnchorConsume['code'] == $this->status->getCode('OK')){
            $this->view->thisWeekConsume = $thisWeekAnchorConsume['data'];
        }else{
            $this->view->thisWeekConsume = FALSE;
        }

        //获得上周排行
        $lastWeekAnchorConsume = $this->familyMgr->getLastWeekAnchorConsume($CreatorGetConsume);
        if($lastWeekAnchorConsume['code'] == $this->status->getCode('OK')){
            $this->view->lastWeekConsume = $lastWeekAnchorConsume['data'];
        }else{
            $this->view->lastWeekConsume = FALSE;
        }

        //获得本月排行
        $thisMonthAnchorConsume = $this->familyMgr->getThisMonthAnchorConsume($CreatorGetConsume);
        if($thisMonthAnchorConsume['code'] == $this->status->getCode('OK')){
            $this->view->thisMonthConsume = $thisMonthAnchorConsume['data'];
        }else{
            $this->view->thisMonthConsume = FALSE;
        }

        //获得上月排行
        $lastMonthAnchorConsume = $this->familyMgr->getLastMonthAnchorConsume($CreatorGetConsume);
        if($lastMonthAnchorConsume['code'] == $this->status->getCode('OK')){
            $this->view->lastMonthConsume = $lastMonthAnchorConsume['data'];
        }else{
            $this->view->lastMonthConsume = FALSE;
        }

        //获得总排行
        $totalAnchorConsume = $this->familyMgr->getTotalAnchorConsume($CreatorGetConsume);
        if($totalAnchorConsume['code'] == $this->status->getCode('OK')){
            $this->view->totalConsumes = $totalAnchorConsume['data'];
        }else{
            $this->view->totalConsumes = FALSE;
        }
    }

    public function incomeAction(){
    }

    /*
     * 查看家族成员详细信息
     * */
    public function anchorInfoInFamilyAction(){
        if($this->request->isPost()){
            $anchorId = $this->request->getPost('anchorId');

            $result = $this->familyMgr->anchorInfoInFamily($anchorId);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 签约、解约状态
     * */
    public function applySigningAction(){
        if($this->request->isPost()){
            $result = $this->familyMgr->getApplySignStatus();
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 签约
     * */
    public function applySigningGoAction(){
        if($this->request->isPost()){
            $user['sex'] = $this->request->getPost('sex');
            $user['birthYear'] = $this->request->getPost('birthYear');
            $user['birthMonth'] = $this->request->getPost('birthMonth');
            $user['birthDay'] = $this->request->getPost('birthDay');
            $user['realName'] = $this->request->getPost('realName');
            $user['birth'] = $this->request->getPost('birth');
            $user['idCard'] = $this->request->getPost('idCard');
            $user['telephone'] = $this->request->getPost('telephone');
            $user['address'] = $this->request->getPost('address');
            $user['accountName'] = $this->request->getPost('accountName');
            $user['cardNumber'] = $this->request->getPost('cardNumber');
            $user['bank'] = $this->request->getPost('bank');
            $user['qq'] = $this->request->getPost('qq');
            $user['city'] = $this->request->getPost('city');

            $result = $this->familyMgr->SignAnchorApply($user);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 签约上传图片
     * */
    public function applySigningPicAction(){
        if ($this->request->hasFiles()) {
            $type = $this->request->get('type');
            $id = $this->request->get('id');
            foreach ($this->request->getUploadedFiles() as $file) {
                $result = $this->familyMgr->SignAnchorApplyPic($file, $type, $id);

                if ($result['code'] == $this->status->getCode('OK')) {
                    $revert = $this->status->ajaxGetReturnData($this->status->getCode('OK'), $result['data']);
                }else{
                    $revert = $this->status->ajaxGetReturnData($result['code'], $result['data']);
                }
            }
        }else{
            $revert = $this->status->ajaxGetReturnData($this->status->getCode('UPLOADFILE_ERROR'));
        }

        echo '<!DOCTYPE html><html><head><title></title></head><body></body><script type="text/javascript">window.parent.uploadImageCallback('.$revert.',"'.$type.'",'.$id.');</script>';exit;
    }

    /*
     * 创建家族请求
     * */
    public function createFamilyRequestAction(){
        if($this->request->isPost()){
            $result = $this->familyMgr->createFamilyRequest();
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 冻结账户
     * */
    public function applyFamilyFrozenAction(){
        if($this->request->isPost()){
            $result = $this->familyMgr->applyFamilyFrozen();
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 上传家族海报
     * */
    public function uploadFamilyPosterAction(){
        $file = $this->request->getPost("fileUp");

        $result = $this->familyMgr->uploadFamilyPoster($file);

        if ($result['code'] == $this->status->getCode('OK')) {
            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }
        $this->status->ajaxReturn($result['code'], $result['data']);
    }

    /*
     * 申请创建家族
     * */
    public function createFamilyAction(){
        if($this->request->isPost()){
            $family['name'] = $this->request->getPost('name');//家族名称
            $family['shortName'] = $this->request->getPost('shortName');//家族微章
            $family['address'] = $this->request->getPost('address');//族长地址
            $family['companyName'] = $this->request->getPost('companyName');//族长地址

            $result = $this->familyMgr->applyCreateFamily($family);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 获取家族信息
     * */
    public function getFamilyInfoAction(){
        if($this->request->isPost()){
            $user = $this->userAuth->getUser();
            if (!$user) {
                return $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'), '');
            }
            $result = $this->familyMgr->getFamilyInfoByUid($user->getUid());
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 修改家族公告
     * */
    public function updateFamilyAnnouncementAction(){
        if($this->request->isPost()){
            $content = $this->request->getPost('content');

            $result = $this->familyMgr->updateAnnouncement($content);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 修改家族介绍
     * */
    public function updateFamilyDescriptionAction(){
        if($this->request->isPost()){
            $content = $this->request->getPost('content');
            $result = $this->familyMgr->updateFamilyDescription($content);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 退出家族
     * */
    public function exitFamilyAction(){
        if($this->request->isPost()){
            $result = $this->familyMgr->exitFamily();
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 申请加入家族(判断)
     * */
    public function applyFamilyPreAction(){
        if($this->request->isPost()){
            $result = $this->familyMgr->applyFamilyPre();
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 申请加入家族
     * */
    public function applyJoinFamilyAction(){
        if($this->request->isPost()){
            $data = $this->request->getPost();

            $result = $this->familyMgr->applyToJoinFamily($data);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 检测家族名字
     * */
    public function checkFamilyNameAction(){
        if($this->request->isPost()){
            $name = $this->request->getPost('name');

            $result = $this->familyMgr->checkFamilyName($name);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    /*
     * 检测家族名字
     * */
    public function checkFamilyShortNameAction(){
        if($this->request->isPost()){
            $shortName = $this->request->getPost('shortName');

            $result = $this->familyMgr->checkFamilyShortName($shortName);
            if ($result['code'] == $this->status->getCode('OK')) {
                $this->status->ajaxReturn($this->status->getCode('OK'));
            }
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
}