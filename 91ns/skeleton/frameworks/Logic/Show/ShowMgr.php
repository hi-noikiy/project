<?php

namespace Micro\Frameworks\Logic\Show;

use Phalcon\DI\FactoryDefault;
use Micro\Frameworks\Logic\User\UserFactory;

class ShowMgr {

    protected $di;
    protected $status;
    protected $config;
    protected $validator;
    protected $comm;
    protected $userAuth;
    protected $modelsManager;
    protected $db;
    protected $roomModule;
    protected $normalLib;
    protected $logger;
    protected $pathGenerator;
    protected $url;

    public function __construct() {
        $this->di = FactoryDefault::getDefault();
        $this->status = $this->di->get('status');
        $this->config = $this->di->get('config');
        $this->validator = $this->di->get('validator');
        $this->comm = $this->di->get('comm');
        $this->userAuth = $this->di->get('userAuth');
        $this->db = $this->di->get('db');
        $this->modelsManager = $this->di->get('modelsManager');
        $this->roomModule = $this->di->get('roomModule');
        $this->normalLib = $this->di->get('normalLib');
        $this->logger = $this->di->get('logger');
        $this->pathGenerator = $this->di->get('pathGenerator');
        $this->url = $this->di->get('url');
    }

    public function errLog($errInfo) {
        $this->logger->error('【ShowMgr】 error : '.$errInfo);
    }

    /**
     * 获取主播节目单列表
     */
    public function getShowList($uid = 0){
        if($uid){
            $isValid = $this->validator->validate(array('uid'=>$uid));
            if (!$isValid) {
                return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
            }
        }else{
            $user = $this->userAuth->getUser();
            if ($user == NULL) {// 用户必须登录
                return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
            }
            $uid = $user->getUid();
        }
        try {
            //排序按照价格和名称升序排
            // convert(showName using gbk) asc
            $showRes = \Micro\Models\ShowList::find('uid = ' . $uid . ' and status = 0 order by showPrice asc, createTime asc');

            $showData = array();
            $num = 0;
            if(!empty($showRes)){
                foreach ($showRes as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['showName'] = $v->showName;
                    $tmp['showPrice'] = $v->showPrice;
                    array_push($showData, $tmp);
                }
                $num = count($showData);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('showList'=>$showData, 'count'=>$num)); 

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 添加节目
     */
    public function addShow($showName = '', $showPrice = 500){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $isValid = $this->validator->validate(array('showName'=>$showName));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        if(!in_array($showPrice, $this->config->showConfigs->showPrice->toArray())){
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $new = new \Micro\Models\ShowList();
            $new->uid = $uid;
            $new->showName = $showName;
            $new->showPrice = $showPrice;
            $new->showType = 1;
            $new->createTime = time();
            $new->updateTime = time();
            $new->status = 0;
            $new->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 编辑节目
     */
    public function editShow($id = 0, $showName = '', $showPrice = 500){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $isValid = $this->validator->validate(array('id'=>$id, 'showName'=>$showName));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        if(!in_array($showPrice, $this->config->showConfigs->showPrice->toArray())){
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $showRes = \Micro\Models\ShowList::findfirst('id = ' . $id . ' and uid = ' . $uid);

            if(empty($showRes)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $showRes->showName = $showName;
            $showRes->showPrice = $showPrice;
            $showRes->updateTime = time();
            $showRes->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 编辑节目
     */
    public function delShow($id = 0){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();
        $isValid = $this->validator->validate(array('id'=>$id));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $showRes = \Micro\Models\ShowList::findfirst('id = ' . $id . ' and uid = ' . $uid);

            if(empty($showRes)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            $showRes->status = 1;
            $showRes->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取主播节目单列表
     */
    public function getBuyShowList($uid = 0){
        $isValid = $this->validator->validate(array('uid'=>$uid));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            //排序按照价格和名称升序排
            $sql = 'select bs.id,bs.buyUid,bs.showName,bs.showPrice,bs.isDelete,bs.status,ui.nickName,bs.createTime '
                . ' from \Micro\Models\BuyShowLog as bs left join \Micro\Models\UserInfo as ui on ui.uid = bs.buyUid '
                . ' where bs.uid = ' . $uid . ' and bs.isDelete = 0 order by bs.status desc, bs.createTime desc ';
            $query = $this->modelsManager->createQuery($sql);
            $res = $query->execute();

            $buyData = array();
            $num = 0;
            $time = time();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    $tmp = array();
                    $tmp['id'] = $v->id;
                    $tmp['buyUid'] = $v->buyUid;
                    $tmp['showName'] = $v->showName;
                    $tmp['showPrice'] = $v->showPrice;
                    // $tmp['isDelete'] = $v->isDelete;
                    $tmp['status'] = $v->status;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['createTime'] = $this->dealTime($time, $v->createTime);
                    array_push($buyData, $tmp);
                }
                $num = count($buyData);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'), array('buyList'=>$buyData, 'count'=>$num)); 

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    private function dealTime($time, $createTime){
        $intervalTime = $time - $createTime;
        if($intervalTime < 60){
            return '刚刚';
        }else if($intervalTime < 3600){
            return floor($intervalTime / 60) . '分钟前';
        }else if($intervalTime < 86400){
            return floor($intervalTime / 3600) . '小时前';
        }else{
            return floor($intervalTime / 86400) . '天前';
        }
    }

    /**
     * 用户点节目
     */
    public function buyShow($uid = 0, $showType = 1, $showName = '', $showPrice = 500){//, $id = 0, $buyMethod = 1
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $buyUid = $user->getUid();

        $isValid = $this->validator->validate(array('showName'=>$showName));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }

        try {
            //判断是否开播
            $roomInfo = \Micro\Models\Rooms::findfirst('uid = ' . $uid);
            if(empty($roomInfo)){
                return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
            }
            if($roomInfo->liveStatus != 1 && $roomInfo->liveStatus != 3){
                return $this->status->retFromFramework($this->status->getCode('CURRENT_ROOM_IS_NOT_PUBLISHED'));
            }
            $roomId = $roomInfo->roomId;

            if($showType == 1){//节目单
                //判断节目是否存在and id = ' . $id . ' 
                $showData = \Micro\Models\ShowList::findfirst(
                    'status = 0 and uid = ' . $uid . ' and showPrice = ' . $showPrice . ' and showName = "' . $showName . '"'
                );
                if(empty($showData)){
                    return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
                }
                $showId = $showData->id;
            }else{//自选节目
                //价格写死5000
                $showPrice = $this->config->showConfigs->optionShow;
                $showId = 0;
            }

            //检查是否有对应价格的节目卡
            $cardSql = 'select ifnull(ui.itemCount, 0) as num,ui.id from \Micro\Models\UserItem as ui left join \Micro\Models\ItemConfigs as ic on ic.id = ui.itemId '
                . ' where ui.itemType = 4 and ic.type = 3 and ui.uid = ' . $buyUid . ' and ic.cash = ' . $showPrice . ' limit 1';
            $query = $this->modelsManager->createQuery($cardSql);
            $cardRes = $query->execute();
            if($cardRes->valid() && !empty($cardRes) && $cardRes->toArray()[0]['num'] >= 1){//使用节目卡
                $userItemId = $cardRes->toArray()[0]['id'];

                $updateCardSql = 'update pre_user_item set itemCount = itemCount - 1 where id = ' . $userItemId . ' and uid = ' . $buyUid;
                $this->db->execute($updateCardSql);

                $buyMethod = 2;
            }else{//使用聊币
                $userData = \Micro\Models\UserProfiles::findfirst('uid = ' . $buyUid);
                if(empty($userData) || $userData->cash < $showPrice){
                    return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_CASH'));
                }

                //增加富豪经验
                $richerData = array(
                    'richer' => $user,
                    'richerCash' => $showPrice,
                    'richerExp' => $showPrice
                );
                $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, array(), $roomId, 0, 0);

                if($resCode['code'] != 'OK'){
                    return $resCode;
                }

                $buyMethod = 1;
            }

            //添加节目单
            $log = new \Micro\Models\BuyShowLog();
            $log->uid = $uid;
            $log->buyUid = $buyUid;
            $log->showId = $showId;
            $log->showName = $showName;
            $log->showPrice = $showPrice;
            $log->showType = $showType;
            $log->buyMethod = $buyMethod;
            $log->createTime = time();
            $log->status = 1;
            $log->isDelete = 0;
            $log->save();

            $userInfo = \Micro\Models\UserProfiles::findfirst('uid = ' . $buyUid);
            $myCash = $userInfo->cash;
            $richerExp = $userInfo->exp3;
            $levelData = \Micro\Models\RicherConfigs::findFirst("level=" . $userInfo->level3);
            $richerHigher = $levelData->higher;
            $richerLower = $levelData->lower;

            $return = array(
                'buyMethod' => $buyMethod,
                'showPrice' => $showPrice,
                'myCash' => $myCash,
                'richerExp' => $richerExp,
                'richerHigher' => $richerHigher,
                'richerLower' => $richerLower,
            );

            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
            
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 主播审核节目
     */
    public function verifyBuyShow($id = 0, $status = 0){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        $isValid = $this->validator->validate(array('id'=>$id));
        if (!$isValid || ($status != 0 && $status != 2)) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $buyData = \Micro\Models\BuyShowLog::findfirst(
                'id = ' . $id . ' and isDelete = 0 and uid = ' . $uid
            );
            if(empty($buyData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }
            if($buyData->status != 1){
                return $this->status->retFromFramework($this->status->getCode('OPER_NOT_AFFACT'));
            }

            $buyUid = $buyData->buyUid;
            $richer = UserFactory::getInstance($buyUid);
            $showPrice = $buyData->showPrice;
            $showName = $buyData->showName;
            $anchorCash = floor($showPrice / 2);

            $roomInfo = \Micro\Models\Rooms::findfirst('uid = ' . $uid);
            $roomId = $roomInfo->roomId;

            $itemId = $this->config->showConfigs->showCardId->toArray()[$showPrice];

            if($status == 2){
                //富豪经验
                $richerData = array(
                    'richer' => $richer,
                    'richerCash' => 0,
                    'richerExp' => 0
                );
                //主播经验
                $anchorData = array(
                    'anchor' => $user,
                    'anchorCash' => $anchorCash,
                    'anchorExp' => $anchorCash
                );

                $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, $anchorData, $roomId, 0, 0);

                if($resCode['code'] != 'OK'){
                    return $resCode;
                }

                $receiveData = $user->getUserInfoObject()->getData();
                $receivenickName = $receiveData['nickName'];
                $familyId = 0;
                $familyResult = $this->di->get('familyMgr')->getFamilyInfoByUid($uid);
                if ($familyResult['code'] == $this->status->getCode("OK")) {
                    $familyId = $familyResult['data']['id'];
                }
                $sendData = $richer->getUserInfoObject()->getData();
                $nickName = $sendData['nickName'];
                $isTuo = $sendData['internalType'] == 2 ? 1 : 0;
                $type = $this->config->consumeType->buyShow;
                $consumeLog = $richer->getUserConsumeObject()->addConsumeDetailLog($type, $showPrice, $anchorCash, $itemId, 1, $uid, $receivenickName, $familyId, $nickName, $isTuo);
            }else{
                $richer->getUserItemsObject()->giveItem($itemId, 1);
            }

            $buyData->status = $status;
            $buyData->save();

            //广播
            $showBroad = array();
            $showBroad['controltype'] = "anchorVerifyShow";
            $data = array();
            $nickName = '';
            $userInfo = \Micro\Models\UserInfo::findFirst('uid = ' . $buyUid);
            if($userInfo){
                $nickName = $userInfo->nickName;
            }
            $data['buyUid'] = $buyUid;
            $data['buyNickName'] = $nickName;
            $data['type'] = $status;
            $data['showPrice'] = $showPrice;
            $data['showName'] = $showName;
            $showBroad['data'] = $data;
            $this->comm->roomBroadcast($roomId, $showBroad);

            return $this->status->retFromFramework($this->status->getCode('OK'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 主播删除已点节目
     */
    public function delBuyShow($id = 0){
        $user = $this->userAuth->getUser();
        if ($user == NULL) {// 用户必须登录
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $uid = $user->getUid();

        $isValid = $this->validator->validate(array('id'=>$id));
        if (!$isValid) {
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'));
        }
        try {
            $buyData = \Micro\Models\BuyShowLog::findfirst(
                'id = ' . $id . ' and isDelete = 0 and uid = ' . $uid
            );
            if(empty($buyData)){
                return $this->status->retFromFramework($this->status->getCode('DATA_IS_NOT_EXISTED'));
            }

            if($buyData->status == 1){
                return $this->status->retFromFramework($this->status->getCode('OPER_NOT_AFFACT'));
            }

            $buyData->isDelete = 1;
            $buyData->save();

            return $this->status->retFromFramework($this->status->getCode('OK'));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}