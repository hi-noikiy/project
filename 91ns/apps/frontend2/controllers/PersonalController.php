<?php

namespace Micro\Controllers;

use Phalcon\DI\FactoryDefault;

use Micro\Frameworks\Logic\User\UserFactory;

class personalController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $personalBaseInfo = $this->getPersonalBaseInfo();
        $this->view->familyHeader = $this->userMgr->checkIsFamilyHeader();
        $this->view->personalBaseInfo = $personalBaseInfo;
//        var_dump($personalBaseInfo);die;
    }

    public function getPersonalBaseInfo(){
        $showData = array();

        $user = $this->userAuth->getUser();
        if($user != NULL){
            $uid = $user->getUid();
            $userInfo = $user->getUserInfoObject()->getData();
            $result = $this->userMgr->getUserLevelInfo($userInfo['uid']);
            if($result['code'] == $this->status->getCode('OK')){
                $userInfo['levelInfo'] = $result['data'];
            }

            $item = $user->getUserItemsObject();
            $result = $item->getItemList(0);
            $itemCount = 0;
            $fansCount = 0;
            $focusCount = 0;
            if($result['code'] == $this->status->getCode('OK')){
                $list = $result['data'];
                if($list){
                    foreach($list as $val){
                        if($val){
                            $itemCount += count($val);
                        }
                    }
                }
            }

            $result = $this->userMgr->getFansCount($uid);
            if($result['code'] == $this->status->getCode('OK')){
                $data = $result['data'];
                $fansCount = $data['totalNum'];
            }

            $result = $this->userMgr->getFocusCount();
            if($result['code'] == $this->status->getCode('OK')){
                $data = $result['data'];
                $focusCount = $data['count'];
            }

            $result = $item->getUserBadge();
            if ($result['code'] == $this->status->getCode('OK')) {
                $showData['badge'] = $result['data'];
            }else{
                $showData['badge'] = array();
            }

            // 获得家族信息
            $result = $this->familyMgr->getFamilyInfoByUid($uid);
            if ($result['code'] == $this->status->getCode('OK')) {
                $showData['family'] = $result['data']['shortName'];
            }else{
                $showData['family'] = '';
            }

            // 获取守护数量
            $guardRes = $this->userMgr->getGuardCount($uid);
            if ($guardRes['code'] == $this->status->getCode('OK')) {
                $showData['guardNum'] = $guardRes['data']['count'];
            }else{
                $showData['guardNum'] = 0;
            }

            // 获取审核数量
            $auditRes = $this->userMgr->getAuditingNum();
            if ($auditRes['code'] == $this->status->getCode('OK')) {
                $showData['auditNum'] = $auditRes['data']['count'];
            }else{
                $showData['auditNum'] = 0;
            }

            //获取是否是主播
            $showData['isAnchor'] = $this->userMgr->checkIsAnchor($uid);
            //获取直播间封面
            $showData['roomPic'] = $this->userMgr->getRoomPic($uid);

            $showData['uid'] = $userInfo['uid'];
            $showData['nickName'] = $userInfo['nickName'];
            $showData['avatar'] = $userInfo['avatar'];
            $showData['cash'] = $userInfo['cash'];
            $showData['coin'] = $userInfo['coin'];
            $showData['levelInfo'] = $userInfo['levelInfo'];
            $showData['itemCount'] = $itemCount;
            $showData['fansCount'] = $fansCount;
            $showData['focusCount'] = $focusCount;
        }

        return $showData;
    }

    /*
     * 获取我的通知下：消息、申请、审批 数量
     */
    public function getMsgNumbersAction()
    {
        if ($this->request->isPost()) {
            $tipsArr = array(
                'messageNum' => 0,
                'applyNum' => 0,
                'auditing' => 0
            );

            $tipNum = $this->userMgr->getUndoTipNumber();
            if($tipNum['code'] == $this->status->getCode('OK')){
                $tipsArr['applyNum'] = $tipNum['data']['apply'];
                $tipsArr['auditing'] = $tipNum['data']['auditing'];
            }

            $num = $this->userMgr->getUnReadInfoNum(0);
            if($num['code'] == $this->status->getCode('OK')){
                $tipsArr['messageNum'] = $num['data'];
            }

            $this->status->ajaxReturn($this->status->getCode('OK'), $tipsArr);
        }
        $this->proxyError();
    }

    public function testAction()
    {
    	$this->view->ns_static =true;
    }

    //个人资料
    public function infoAction()
    {
        $this->view->ns_static =true;
        $this->view->ns_active = 'info';
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->pageError();
        }
        $uinfo = array();
        $result = $user->getUserInfoObject()->getUserAccountInfo();
        $uinfo['userName'] = $result['userName'];
        $uinfo['canSetUserName'] = $result['canSetUserName'];
        $inforesult = $user->getUserInfoObject()->getUserInfo();
        $uinfo['signature'] = $inforesult['signature'];
        $uinfo['city'] = $inforesult['city'];


        $this->view->uinfo = $uinfo;
    }

    public function getPropsListAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->pageError();
            }

            $page = intval($this->request->getPost('page'));
            $pageSize = intval($this->request->getPost('pageSize'));

            $item = $user->getUserItemsObject();

            $result = $item->getPropsList($page, $pageSize);
            $result['data']['config'] = $this->config->buyVipConfig;

            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }
        $this->proxyError();
    }

    // 获取过期列表
    public function getExpiredItemsAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->pageError();
            }

            $page = intval($this->request->getPost('page'));
            $pageSize = intval($this->request->getPost('pageSize'));

            $item = $user->getUserItemsObject();

            $result = $item->getExpiredItems($page, $pageSize);

            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }
        $this->proxyError();
    }

    // 获取座驾列表
    public function getCarItemListNewAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->pageError();
            }

            $page = intval($this->request->getPost('page'));
            $pageSize = intval($this->request->getPost('pageSize'));

            $item = $user->getUserItemsObject();

            $result = $item->getCarItemListNew($page, $pageSize);

            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }
        $this->proxyError();
    }

    // 获取守护列表
    public function getGuardListNewAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->pageError();
            }

            $page = intval($this->request->getPost('page'));
            $pageSize = intval($this->request->getPost('pageSize'));

            $item = $user->getUserItemsObject();

            $result = $item->getGuardListNew($page, $pageSize);

            $result['data']['config']['gold'] = $this->config->goldGuard;
            $result['data']['config']['silver'] = $this->config->silverGuard;
            $result['data']['config']['bo'] = $this->config->boGuard;

            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }
        $this->proxyError();
    }

    // 获取主播的守护列表
    public function getBeGuardedListNewAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->pageError();
            }

            $page = intval($this->request->getPost('page'));
            $pageSize = intval($this->request->getPost('pageSize'));

            $item = $user->getUserItemsObject();
            $result = $item->getBeGuardedListNew($page, $pageSize);

            $result['data']['config']['gold'] = $this->config->goldGuard;
            $result['data']['config']['silver'] = $this->config->silverGuard;

            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }
        $this->proxyError();
    }

    // 删除过期道具
    public function deleteExpiredItemsAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->pageError();
            }

            $itemIds = $this->request->getPost('itemIds');
            $itemIds && $itemIds = substr($itemIds, -1) == ',' ? substr($itemIds, 0, -1) : $itemIds;

            if(!empty($itemIds)){
                $item = $user->getUserItemsObject();
                $result = $item->deleteExpiredItems($itemIds);

                if ($result['code'] == $this->status->getCode('OK')) {
                    $this->status->ajaxReturn($this->status->getCode('OK'));
                }

                $this->status->ajaxReturn($result['code'], $result['data']);
            }
        }
        $this->proxyError();
    }



    public function PropsAction()
    {
        //获取类型
        $pageType = $this->request->get('action');
        if(!$pageType){
            $pageType = 'all';//设置默认类型
        }

        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->pageError();
        }

        $item = $user->getUserItemsObject();
        $typeArry = array(
            'all' => 0,
            'normal' => 1,
            'car' => 2,
            'guard' => 3,
            'badge' => 4,
        );

        $result = $item->getItemList($typeArry[$pageType]);
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->view->allData = $result['data'];
        }else{
            $this->view->allData = array();
        }

        $this->view->severTime = time();
        $this->view->ns_sonType = $pageType;
        $this->view->ns_static =true;
        $this->view->ns_active = 'props';
    }
    public function  ConcernAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = 'concern';
    }
    public function MyfansAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = 'myfans';
    }
    //我的消息
    public function mynewsAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = 'mynews';
    }
    //我的申请
    public function myapplyAction()
    {
        $this->view->ns_static=true;
        $this->view->ns_active="myapply";
    }

    //我的审批
    public function myapprovalAction()
    {
        $this->view->ns_static=true;
        $this->view->ns_active = 'myapproval';
    }

    //绑定手机
    public function BindingAction()
    {
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->pageError();
        }

        $userName = $user->getUserInfoObject()->getData()['userName'];
        $result = $this->userMgr->getSecure($userName);
        if ($result['code'] == $this->status->getCode('OK') && !empty($result['data']['telephone'])) {
            $this->view->ns_telephone = preg_replace('/(1{1}[0-9][0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$result['data']['telephone']);
        }

        $this->view->ns_static=true;
         $this->view->ns_active = "binding";
    }
    //安全问题
    public function savequestionAction()
    {
        $user = $this->userAuth->getUser();
        if(!$user){
            return $this->pageError();
        }

        $userName = $user->getUserInfoObject()->getData()['userName'];
        $result = $this->userMgr->getSecure($userName);
        if ($result['code'] == $this->status->getCode('OK') && !empty($result['data']['issues'])) {
            $this->view->ns_issues = $result['data']['issues'];
        }

        //获取安全问题集合
        $result = $this->configMgr->getQuestionsConfigs();
        if ($result['code'] == $this->status->getCode('OK')) {
            $this->view->issues = $result['data'];
        }

        $this->view->ns_static=true;
         $this->view->ns_active = "savequestion";
    }
    //修改密码
    public function changepswAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "changepsw";
    }
    //找回用户名
    public function findusernameAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "findusername";
    }
    //收到礼物
    public function getPresentsAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "getpresents";
    }
    //送出礼物
    public function sentPresentsAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "sentpresents";
    }
    //充值记录
    public function payrecordAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "payrecord";
    }
    //消费记录
    public function consumeAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "consume";
    }

    //我的房管
    public function roomManageAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "roommanage";
    }
    //相关编辑
    public function abouteditAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "aboutedit";
    }
    //我的家族
    public function myfamilAction()
    {
        $this->view->ns_static=true;
        $this->view->ns_active="myfamil";
    }
   
    //直播数据
    public function livedataAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "livedata";
    }
    //直播须知
    public function livenotesAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "livenotes";
    }



    //我的通知
    public function myinformAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "myinform";
    }

    //安全中心
    public function savecenterAction()
    {
        $this->view->ns_static=true;
        $this->view->ns_active="savecenter";
        $sinfo=array();
        $result = $this->userMgr->getUserSecurityInfo();
        if ($result['code'] == $this->status->getCode('OK')) {
            $sinfo = $result['data'];
        }
        $this->view->sinfo = $sinfo;
        //获取安全问题集合
        $questionResult = $this->configMgr->getQuestionsConfigs();
        if ($questionResult['code'] == $this->status->getCode('OK')) {
            $this->view->issues = $questionResult['data'];
        }
    }
    //我的账单
    public function mybillAction()
    {
        $this->view->ns_static=true;
        $this->view->ns_active="mybill";
    }

    //我的收益
    public function myincomeAction()
    {
        // 获得签约信息
        $singAccountInfo = $this->familyMgr->getAccountBankInfo();
        $this->view->singAccountInfo = $singAccountInfo['code'] == $this->status->getCode('OK') ? $singAccountInfo['data'] : array();
        $this->view->ns_static=true;
        $this->view->ns_active="myincome";
    }
    //我是主播
    public function imanchorAction()
    {
        $this->view->ns_static=true;
        $this->view->ns_active="imanchor";
        
        $this->view->visitor = FALSE;
        $familyId = $this->request->get('fid');//checkFamilyStatus

        if(!empty($familyId)){
            $result = $this->familyMgr->getFamilyInfo($familyId);
//            $this->pageCheckSuccess($result);
        }else{
            $user = $this->userAuth->getUser();
            if (!$user) {
                return $this->redirect('/');
            }
            $result = $this->familyMgr->getFamilyInfoByUid($user->getUid());
//            $this->pageCheckSuccess($result);
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

        // 获取推广链接
        $recData = $this->userMgr->getRecData($user->getUid());
        $this->view->recData = $recData;

        //swf版本
        
        $content = $this->config->url->swfUrl;
        $this->view->roomFileName = $content ? $content : 'room';

        // 直播间限制设置
        $roomLimits = $this->userMgr->getRoomLimits();
        if($roomLimits['code'] == $this->status->getCode('OK')){
            $this->view->roomLimits = $roomLimits['data'];
        }else{
            $this->view->roomLimits = array();
        }
        // 获取富豪等级配置
        $richerRanks = $this->userMgr->getRicherRanks();
        if($richerRanks['code'] == $this->status->getCode('OK')){
            $this->view->richerRanks = $richerRanks['data'];
        }else{
            $this->view->richerRanks = array();
        }

        //节目单介个配置
        $this->view->showPrice = $this->config->showConfigs->showPrice;


    }
    
    public function indexAction()
    {
        $this->redirect('personal/info');
    }
    
    //获得用户等级信息 add by 2015/08/24
    public function getLevelInfoAction() {
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if ($user != NULL) {
                $result = $this->userMgr->getUserLevelInfo($user->getUid());
                $this->status->ajaxReturn($result['code'], $result['data']);
            }
            $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $this->proxyError();
    }

    public function familyheaderAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "familyheader";

        $familyId = $this->request->get('fid');//checkFamilyStatus

        if(!empty($familyId)){
            $result = $this->familyMgr->getFamilyInfo($familyId);
//            $this->pageCheckSuccess($result);
        }else{
            $user = $this->userAuth->getUser();
            if (!$user) {
                return $this->redirect('/');
            }
            $result = $this->familyMgr->getFamilyInfoByUid($user->getUid());
//            $this->pageCheckSuccess($result);
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
    }

    public function familymanageAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "familymanage";
    }

    public function memberauditAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "memberaudit";
    }
    //我是家族长--直播数据
    public function livedatafamilyAction()
    {
        $this->view->ns_static=true;
         $this->view->ns_active = "livedatafamily";
    }

    // 获取家族旗下主播直播数据
    public function getLiveDataAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $familyId = $this->request->getPost('familyId');
            $startTime = $this->request->getPost('startTime');
            if($startTime){
                $startTime = strtotime($startTime);
            }else{
                $startTime = strtotime(date('Y-m-d',strtotime('-1 days')));
            }
            // $endTime = $this->request->getPost('endTime');
            /*if($endTime){
                $endTime = strtotime($endTime) + 86400;
            }else{
                $endTime = strtotime(date('Y-m-d'));
            }*/
            $endTime = $startTime + 86400;
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');

            $result = $this->userMgr->getLiveData($familyId, $startTime, $endTime, $page, $pageSize);
            $this->status->ajaxReturn($result['code'], $result['data']);
            
        }
        $this->proxyError();
    }

    // 获取家族旗下主播列表
    public function getFamilyAnchorsAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if ($user == NULL) {
                $this->status->ajaxReturn($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }

            $familyId = $this->request->getPost('familyId');
            $search = $this->request->getPost('search');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');

            $result = $this->userMgr->getFamilyAnchorNew($familyId, $page, $pageSize, $search);
            $this->status->ajaxReturn($result['code'], $result['data']);
            
        }
        $this->proxyError();
    }

    // 删除家族旗下主播
    public function delAnchorAction(){
        if($this->request->isPost()){
            $id = $this->request->getPost('id');
            $result = $this->userMgr->delAnchorNew($id);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 获取家族旗下主播信息
    public function getFamilyAnchorInfoAction(){
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $result = $this->userMgr->getFamilyAnchorInfo($uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    // 获取主播推荐用户
    public function getRecDetailListAction(){
        if ($this->request->isPost()) {
            $user = $this->userAuth->getUser();
            if(!$user){
                return $this->pageError();
            }

            $page = intval($this->request->getPost('page'));
            $pageSize = intval($this->request->getPost('pageSize'));

            $result = $this->userMgr->getRecDetailList($page, $pageSize);

            $this->status->ajaxReturn($this->status->getCode('OK'), $result['data']);
        }
        $this->proxyError();
    }

    // 获取抽成记录列表
    public function getBonusListAction(){
        if ($this->request->getPost()) {

            $startTime = $this->request->getPost('startTime');
            if($startTime){
                $startTime = strtotime($startTime);
            }else{
                $startTime = 0;
            }
            $endTime = $this->request->getPost('endTime');
            if($endTime){
                $endTime = strtotime($endTime) + 86399;
            }else{
                $endTime = time();
            }

            $search = $this->request->getPost('search');
            $page = $this->request->getPost('page');
            $pageSize = $this->request->getPost('pageSize');

            $result = $this->userMgr->getBonusList($startTime, $endTime, $search, $page, $pageSize);

            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }
    
    //修改用户所在地
    public function updateCityAction(){
        if($this->request->isPost()){
            $city = $this->request->getPost('city');
            $result = $this->userMgr->updateUserCity($city);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    //获取用户名片【20151104】
    public function getUserCardInfoAction(){
        if($this->request->isPost()){
            $uid = $this->request->getPost('uid');
            $result = $this->userMgr->getUserCardInfo($uid);
            return $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

}