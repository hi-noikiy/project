<?php
namespace Micro\Controllers;

use Micro\Models\VipConfigs;
use Micro\Models\HostConfigs;
use Micro\Models\RicherConfigs;
use Micro\Models\FansConfigs;
use Micro\Models\GiftConfigs;
use Micro\Models\GuardConfigs;

class ConfigmgrController extends ControllerBase
{
    public function initialize() {
        parent::initialize();
    }

    public function indexAction() {
        return $this->forward("configmgr/vip");
    }

    public function giftTypeAction() {}

    public function getGiftTypeAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getTypeConfigList($this->config->dbTypeConfigName->gift[0], $skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateGiftTypeAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['name'];
                    $typeId = $post['typeId'];
                    $description = $post['description'];
                    $roomAnimate = empty($post['roomAnimate']) ? 0 : $post['roomAnimate'] ;
                    $result = $this->configMgr->addTypeConfig($this->config->dbTypeConfigName->gift[0], $name, $typeId, $description, $roomAnimate);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delTypeConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateTypeConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getTypeConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function carTypeAction() {}

    public function getCarTypeAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getTypeConfigList($this->config->dbTypeConfigName->car[0], $skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateCarTypeAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['name'];
                    $typeId = $post['typeId'];
                    $description = $post['description'];
                    $roomAnimate = empty($post['roomAnimate']) ? 0 : $post['roomAnimate'] ;
                    $result = $this->configMgr->addTypeConfig($this->config->dbTypeConfigName->car[0], $name, $typeId, $description, $roomAnimate);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delTypeConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateTypeConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getTypeConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function foodTypeAction() {}

    public function getFoodTypeAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getTypeConfigList($this->config->dbTypeConfigName->food[0], $skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateFoodTypeAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['name'];
                    $typeId = $post['typeId'];
                    $result = $this->configMgr->addTypeConfig($this->config->dbTypeConfigName->food[0], $name, $typeId);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delTypeConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateTypeConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getTypeConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function vipAction() {
        // 获得特权列表
        $res = $this->configMgr->getVipRightList(0, 1000);
        if($res['code'] == $this->status->getCode('OK')){
            $this->view->rightlist = $res['data']['list'];
        }else{
            $this->view->rightlist = array();
        }
    }

    public function getVipAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getVipConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateVipAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $level = $post['level'];
                    $lower = $post['lower'];
                    $higher = $post['higher'];
                    $description = $post['description'];
                    $carId = $post['carId'];
                    $rightlist = $post['rightlist'];
                    $result = $this->configMgr->addVipConfig($level, $lower, $higher, $description, $carId, $rightlist);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delVipConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateVipConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getVipConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function anchorAction() {}

    public function getAnchorAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getAnchorConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateAnchorAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['name'];
                    $higher = $post['higher'];
                    $lower = $post['lower'];
                    $level = $post['level'];
                    $roomLimitNum = $post['roomLimitNum'];
                    $result = $this->configMgr->addAnchorConfig($name, $higher, $lower, $level, $roomLimitNum);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delAnchorConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateAnchorConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getAnchorConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function richerAction() {}

    public function getRicherAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getRicherConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateRicherAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['name'];
                    $higher = $post['higher'];
                    $lower = $post['lower'];
                    $level = $post['level'];
                    $carId = $post['carId'];
                    $result = $this->configMgr->addRicherConfig($name, $higher, $lower, $level, $carId);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delRicherConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateRicherConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getRicherConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function fansAction() {}

    public function getFansAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getFansConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateFansAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['name'];
                    $higher = $post['higher'];
                    $lower = $post['lower'];
                    $level = $post['level'];
                    $result = $this->configMgr->addFansConfig($name, $higher, $lower, $level);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delFansConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateFansConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getFansConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function guardAction() {
        $res = $this->configMgr->getGuardRightList(0, 1000);
        if($res['code'] == $this->status->getCode('OK')){
            $this->view->rightlist = $res['data']['list'];
        }else{
            $this->view->rightlist = array();
        }
    }

    public function getGuardAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getGuardConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateGuardAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $level = $post['level'];
                    $name = $post['name'];
                    $carId = $post['carId'];
                    $description = $post['description'];
                    $rightlist = $post['rightlist'];
                    $result = $this->configMgr->addGuardConfig($level, $name, $carId, $description, $rightlist);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delGuardConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateGuardConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getGuardConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function giftAction() { }

    public function getGiftAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getGiftConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateGiftAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $typeId = $post['typeId'];
                    $name = $post['name'];
                    $coin = $post['coin'];
                    $cash = $post['cash'];
                    $recvCoin = $post['recvCoin'];
                    $vipLevel = $post['vipLevel'];
                    $richerLevel = $post['richerLevel'];
                    $discount = $post['discount'];
                    $freeCount = $post['freeCount'];
                    $littleFlag = $post['littleFlag'];
                    $orderType = 0;
                    $guardFlag=$post['guardFlag'];
                    $configName = $post['configName'];
                    $description = $post['description'];
                    $result = $this->configMgr->addGiftConfig($typeId, $name, $coin, $cash, $recvCoin, $vipLevel, $richerLevel, 
                                                              $discount, $freeCount, $littleFlag, $orderType, $guardFlag, $configName, $description);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delGiftConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateGiftConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getGiftConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function carAction() {}

    public function getCarAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getCarConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateCarAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $typeId = $post['typeId'];
                    $name = $post['name'];
                    $price = $post['price'];
                    $description = $post['description'];
                    $orderType = 0; //后台暂时不用该字段的输入
                    $status = $post['status'];
                    $configName = $post['configName'];
                    $hasBigCar = $post['hasBigCar'];
                    $sort = $post['sort'];
                    $positionX1 = $post['positionX1'];
                    $positionY1 = $post['positionY1'];
                    $positionX2 = $post['positionX2'];
                    $positionY2 = $post['positionY2'];

                    $result = $this->configMgr->addCarConfig($typeId, $name, $price, $description, 
                                                              $orderType, $status, $configName, $hasBigCar, $sort, $positionX1, $positionY1, $positionX2, $positionY2);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delCarConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateCarConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getCarConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function foodAction() {}

    public function getFoodAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getFoodConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateFoodAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $typeId = $post['typeId'];
                    $name = $post['name'];
                    $price = $post['price'];
                    $description = $post['description'];
                    $orderType = $post['orderType'];
                    $status = $post['status'];
                    $result = $this->configMgr->addFoodConfig($typeId, $name, $price, $description, $orderType, $status);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delFoodConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateFoodConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getFoodConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function noticeAction() {}

    public function getNoticeAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getNoticeConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function updateNoticeAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $title = $post['title'];
                    $contents = $post['contents'];
                    $image = isset($post['image']) ? $post['image'] : '';
                    $result = $this->configMgr->addNoticeConfig($title, $contents, $image);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delNoticeConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateNoticeConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getNoticeConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function giftCarAction(){

    }

    public function getGiftCarAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');

            $skip = $pageIndex*$numPerPage;
            $limit = $numPerPage;

            $result = $this->configMgr->getGiftCarList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function setShowStatusAction($id) {
        if ($this->request->isPost()) {
            $showStatus = $this->request->getPost('showStatus');

            $result = $this->configMgr->setShowStatus($id, $showStatus);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function setSellStatusAction($id) {
        if ($this->request->isPost()) {
            $sellStatus = $this->request->getPost('sellStatus');

            $result = $this->configMgr->setSellStatus($id, $sellStatus);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }
        $this->proxyError();
    }

    public function getBannerAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');
            $skip = $pageIndex * $numPerPage;
            $limit = $numPerPage;
            $result = $this->configMgr->getBannerConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

    public function bannerAction(){

    }

    public function updateBannerAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $bannerurl = $post['bannerurl'];
                    $backgroundcolor = $post['backgroundcolor'];
                    $extracontent = $post['extracontent'];
                    $description = $post['description'];
                    $status = $post['status'];
                    $btype = $post['btype'];
                    $order = $post['border'];
                    $time = $post['time'];
                    $title = $post['title'];
                    $result = $this->configMgr->addBannerConfig($bannerurl, $backgroundcolor, $extracontent, $description, $status,$btype, $time, $order, $title);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delBannerConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateBannerConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getBannerConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function uploadMessageImgAction(){
        if($this->request->isPost()){
//            $file = json_encode($_FILES);
//            echo '<script>';
//            echo "window.parent.alert('{$file}')";
//            echo '</script>';die;
            $result = $this->configMgr->uploadBannerImg();
            if($result['code'] == $this->status->getCode('OK')){
                echo '<script>';
                echo "window.parent.uploadImgReturn('{$result['data']}')";
                echo '</script>';die;
            }else{
                echo '<script>';
                echo "window.parent.alert('{$result['data']}')";
                echo '</script>';die;
            }
//            return $this->status->ajaxReturn($result['code'], $result['data']);
        }

        return $this->proxyError();
    }

    public function uploadVipImgAction(){
        if($this->request->isPost()){
            $result = $this->configMgr->uploadVipImg();
            if($result['code'] == $this->status->getCode('OK')){
                echo '<script>';
                echo "window.parent.uploadImgReturn('{$result['data']}')";
                echo '</script>';die;
            }else{
                echo '<script>';
                echo "window.parent.alert('{$result['data']}')";
                echo '</script>';die;
            }

        }

        return $this->proxyError();
    }

    public function viprightAction(){

    }

    public function updateVipRightAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['rightname'];
                    $des = $post['des'];
                    $img = $post['img'];
                    $result = $this->configMgr->addVipRightConfig($name, $des, $img);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delVipRightConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateVipRightConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getVipRightConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function getVipRightAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');
            $skip = $pageIndex * $numPerPage;
            $limit = $numPerPage;
            $result = $this->configMgr->getVipRightConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

    public function uploadGuardImgAction(){
        if($this->request->isPost()){
            $result = $this->configMgr->uploadGuardImg();
            if($result['code'] == $this->status->getCode('OK')){
                echo '<script>';
                echo "window.parent.uploadImgReturn('{$result['data']}')";
                echo '</script>';die;
            }else{
                echo '<script>';
                echo "window.parent.alert('{$result['data']}')";
                echo '</script>';die;
            }

        }

        return $this->proxyError();
    }

    public function guardrightAction(){

    }

    public function updateGuardRightAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $name = $post['rightname'];
                    $des = $post['des'];
                    $type = $post['type'];
                    $img = $post['img'];
                    $result = $this->configMgr->addGuardRightConfig($name, $des, $img, $type);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delGuardRightConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateGuardRightConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getGuardRightConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }
        $this->proxyError();
    }

    public function getGuardRightAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');
            $skip = $pageIndex * $numPerPage;
            $limit = $numPerPage;
            $result = $this->configMgr->getGuardRightConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

    public function getEventAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');
            $skip = $pageIndex * $numPerPage;
            $limit = $numPerPage;
            $result = $this->configMgr->getEventConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

    public function eventAction(){

    }

    public function updateEventAction($id = ''){
        if ($this->request->isPost()) {
            $msg = '';
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $bannerurl = $post['bannerurl'];
//                    $backgroundcolor = $post['backgroundcolor'];
                    $extracontent = $post['extracontent'];
                    $description = $post['description'];
                    $status = $post['status'];
                    $title = $post['title'];
                    $eventstarttime = strtotime($post['eventstarttime']);
                    $eventendtime = strtotime($post['eventendtime']);
                    $etype = $post['etype'];
                    $order = $post['eorder'];
                    $result = $this->configMgr->addEventConfig($bannerurl, $extracontent, $description, $status, $title, $eventstarttime, $eventendtime, $etype, $order);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delEventConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $result = $this->configMgr->updateEventConfig($id, $post);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getEventConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }

        $this->proxyError();
    }

    public function uploadEventImgAction(){
        if($this->request->isPost()){
            $result = $this->configMgr->uploadEventImg();
            if($result['code'] == $this->status->getCode('OK')){
                echo '<script>';
                echo "window.parent.uploadImgReturn('{$result['data']}')";
                echo '</script>';die;
            }else{
                echo '<script>';
                echo "window.parent.alert('{$result['data']}')";
                echo '</script>';die;
            }

        }

        return $this->proxyError();
    }


    public function anchorRecommendAction(){

    }

    public function getAnchorRecommendAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');
            $skip = $pageIndex * $numPerPage;
            $limit = $numPerPage;
            $result = $this->configMgr->getAnchorRecommendConfigList($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

    public function updateAnchorRecommendAction($id = ''){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $uid = intval($post['uid']);
                    $pos = intval($post['pos']);
                    $result = $this->configMgr->addAnchorRecommendConfig($uid, $pos);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delAnchorRecommendConfig($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $uid = intval($post['uid']);
                    $pos = intval($post['pos']);
                    $result = $this->configMgr->updateAnchorRecommendConfig($uid, $pos);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getAnchorRecommendConfigInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }

        $this->proxyError();
    }

    public function appConfigAction(){

    }

    public function getAppDownloadAction() {
        if ($this->request->isPost()) {
            $pageIndex = $this->request->getPost('page');
            $numPerPage = $this->request->getPost('numPerPage');
            $skip = $pageIndex * $numPerPage;
            $limit = $numPerPage;
            $result = $this->configMgr->getAppDownload($skip, $limit);
            $this->status->ajaxReturn($result['code'], $result['data']);
        }

        $this->proxyError();
    }

    public function updateAppDownloadAction($id = ''){
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $action = $post['action'] ? $post['action'] : '';
            switch($action){
                case 'add':
                    $updateContent = $post['updateContent'];
                    $size = $post['size'];
                    $version = $post['version'];
                    $status = $post['status'];
                    $device = $post['device'];
                    $result = $this->configMgr->addAppDownload($version, $size, $updateContent, $status, $device);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'del':
                    $result = $this->configMgr->delAppDownload($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                case 'update':
                    $updateContent = $post['updateContent'];
                    $size = $post['size'];
                    $version = $post['version'];
                    $status = $post['status'];
                    $device = $post['device'];
                    $result = $this->configMgr->updateAppDownload($id, $version, $size, $updateContent, $status, $device);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;

                default:
                    $result = $this->configMgr->getAppDownloadInfo($id);
                    $this->status->ajaxReturn($result['code'], $result['data']);
                    break;
            }
        }

        $this->proxyError();
    }
}