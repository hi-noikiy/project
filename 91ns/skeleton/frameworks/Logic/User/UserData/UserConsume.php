<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Micro\Frameworks\Logic\User\UserFactory;
use Phalcon\DI\FactoryDefault;
use Micro\Models\ConsumeLog;
use Micro\Models\ConsumeDetailLog;
use Micro\Models\UserProfiles;
use Micro\Models\RicherConfigs;
use Micro\Models\AnchorConfigs;
use Micro\Models\CashLog;
use Micro\Models\GrabLog;
use Micro\Models\Order;
use Micro\Models\Rooms;
use Micro\Models\SignAnchor;

// 用户收入和支出对象

class UserConsume extends UserDataBase {

    public function __construct($uid) {
        parent::__construct($uid);
    }

    /**
     * 消费流程
     * @param $richData
     * @param $anchorData
     * @param $roomId
     * @param $isCoin
     * @param $isFree 
     */
    public function dealConsumeData($richerData, $anchorData = array(), $roomId = 0, $isCoin = 0, $isFree = false){
        //判断参数是否合理
        if(!$richerData || $richerData['richerCash'] < 0){
            return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }
        //调用存储过程
        $richer = $richerData['richer'];
        $richerId = $richer->getUid();
        $richerCash = $richerData['richerCash'];
        $richerExp = $richerData['richerExp'];

        $familyId = 0;
        $isFamily = 0;
        if($anchorData){
            $anchor = $anchorData['anchor'];
            $anchorId = $anchor->getUid();
            $anchorCash = $anchorData['anchorCash'];
            $anchorExp = $anchorData['anchorExp'];
            $signAnchor = SignAnchor::findFirst('uid = ' . $anchorId . ' and familyId > 0');
            if ($signAnchor) {
                $familyId = $signAnchor->familyId;
                $isFamily = 1;
            }
        }else{
            $anchorId = 0;
            $anchorCash = 0;
            $anchorExp = 0;
        }

        //富豪与主播收益变动存储过程
        // if($richerCash > 0){//消费金额大于0才执行
            try {
                $connection = $this->di->get('db');
                $sql = "call ".$this->config->mysql->dbname.".consume({$richerId},{$richerCash},{$richerExp},{$anchorId},{$anchorCash},{$anchorExp},{$isFamily},{$isCoin},@resCode);select @resCode;";
                // echo $sql;
                $resCode = $connection->fetchOne($sql);
                if($resCode['resCode'] != 0){
                    return $this->status->retFromFramework($this->status->getCode('OPER_USER_MONEY_ERROR'));
                }
            } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), 'errorMessage = ' . $e->getMessage());
            }
        // }

        //富豪经验处理
        $richerRes = $this->dealRicherExp($richer, $richerId, $richerExp, $roomId);
        if($richerRes['code'] != 'OK'){
            return $richerRes;
        }

        //主播经验处理
        if($anchorData){
            $anchorRes = $this->dealAnchorExp($anchor, $anchorId, $anchorExp, $roomId);
            if($anchorRes['code'] != 'OK'){
                return $anchorRes;
            }
        }            

        return $this->status->retFromFramework($this->status->getCode('OK'));

    }

    public function dealRicherExp($richer, $richerId, $richerExp, $roomId,$isRichExpRatio=true){
        try {
            //富豪经验
            $isRicherUp = 0;
            if($richerExp > 0){//富豪经验是否需要更新
                $userProfiles = UserProfiles::findFirst("uid = " . $richerId);
                
                //富豪经验增长倍数 add by 2015/11/16
                if ($isRichExpRatio) {
                    $richRatio = $userProfiles->richRatio;
                    $richerExp = floor($richerExp * $richRatio); //向下取整
                }

                $userExp = $userProfiles->exp3;
                $userExpNew = $userExp + $richerExp;
                $userRichLevel = $userProfiles->level3;

                //判断富豪是否升级
                $richerConfigs = \Micro\Models\RicherConfigs::findfirst('higher >= ' . $userExpNew . ' and lower <= ' . $userExpNew);
                if($richerConfigs && $richerConfigs->level > $userRichLevel){
                    $isRicherUp = 1;
                    $newRicherLevel = $richerConfigs->level;
                    $carId = $richerConfigs->carId;
                    $levelName = $richerConfigs->name;
                    $hornNum = $richerConfigs->hornNum;//赠送金喇叭数量
                }else{
                    $newRicherLevel = $userRichLevel;
                    $carId = 0;
                    $levelName = '';
                    $hornNum=0;
                }
                //增加富豪经验
                $richerUpSql = 'update pre_user_profiles set exp3 = exp3 + ' . $richerExp;
                $richerUpSql .= ($isRicherUp ? ',level3 = ' . $newRicherLevel : '');
                $richerUpSql .= ' where uid = ' . $richerId;
                $connection = $this->di->get('db');
                $connection->execute($richerUpSql);
            }
            //判断是否升级
            if ($isRicherUp) {
                if ($roomId) {   //广播
                    /*$userAccount = $richer->getUserInfoObject()->getUserAccountInfo();
                    $userInfo = $richer->getUserInfoObject()->getUserInfo();
                    $userProfiles = $richer->getUserInfoObject()->getUserProfiles();*/

                    // 广播用户当前房间升级特效
                    $roomData = \Micro\Models\Rooms::findfirst($roomId);
                    $broadData = $this->di->get('roomModule')->getRoomOperObject()->setBroadcastParam($richer, $roomData->uid);

                    // $broadData['uid'] = $richerId;
                    $broadData['roomId'] = $roomId;
                    $broadData['oldLevel'] = $userRichLevel;
                    $broadData['newLevel'] = $newRicherLevel;

                    // 查询下一等级是否存在
                    $broadData['nextLevel'] = $newRicherLevel;
                    $count = RicherConfigs::count("level = " . $newRicherLevel + 1);
                    if ($count) {
                        $broadData['nextLevel'] = $newRicherLevel + 1;
                    }

                    // $broadData['nickName'] = $userInfo['nickName'];
                    // $broadData['avatar'] = $userInfo['avatar'];

                    //是否超级管理员
                    // $nodejsUserData['manageType'] = $userAccount['manageType'];
                    $ArraySubData['controltype'] = "richerLevel";
                    $ArraySubData['data'] = $broadData;
                    $this->comm->roomBroadcast($roomId, $ArraySubData);
                    // 判断是否达到系统广播的条件
                    $roomModule = $this->di->get('roomModule');
                    if($newRicherLevel >= $this->config->minLevelUpBroad->richerMinLevel){
                        $levelUpAllRoom['controltype'] = "richerLevelAllRoom";
                        $broadData['runNum'] = 3;
                        $levelUpAllRoom['data'] = $broadData;
                        $roomModule->getRoomOperObject()->allRoomBroadcast($levelUpAllRoom);
                    }

                    // 更新用户所在直播间的userdata
                    $roomModule->getRoomOperObject()->updateUserdataInRooms($richer);
                }

                //送座驾
                if ($carId) {
                    $isSendCar = 1;
                    //查询原来富豪座驾
                    $richerConfigs = \Micro\Models\RicherConfigs::findfirst('higher >= ' . $userExp . ' and lower <= ' . $userExp);
                    if ($richerConfigs) {
                        $oldCarId = $richerConfigs->carId;
                        //查询是否已拥有该旧座驾
                        $itemInfo = \Micro\Models\UserItem::findfirst("uid=" . $richerId . " and itemType=" . $this->config->itemType->car . " and itemId=" . $oldCarId);
                        if ($itemInfo) {
                            $isSendCar = 0;
                            //升级为新座驾
                            $itemInfo->itemExpireTime = $this->config->richerConfigs->carExpireTime;
                            $itemInfo->itemId = $carId;
                            $itemInfo->save();
                        }
                        //赠送新富豪等级座驾
                        if ($isSendCar) {
                            $richer->getUserItemsObject()->giveCar($carId, $this->config->richerConfigs->carExpireTime - time(), 1);
                        }
                    }
                    //给用户发送通知
                    $carInfo = \Micro\Models\CarConfigs::findfirst($carId);
                    $content = $richer->getUserInformationObject()->getInfoContent($this->config->informationCode->rich, array(0 => $levelName, 1 => $carInfo->name));
                    $richer->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                }
                
                //赠送金喇叭
                if ($hornNum) {
                    if ($newRicherLevel - $userRichLevel == 1) {//富豪等级只升了1级
                        $richer->getUserItemsObject()->giveItem(2, $hornNum); //送喇叭
                        //给用户发送通知
                        $content = $richer->getUserInformationObject()->getInfoContent($this->config->informationCode->richerHorn, array(0 => $levelName, 1 => $hornNum));
                        $richer->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    } else {//富豪等级跨级
                        $richerConList = \Micro\Models\RicherConfigs::find("level>" . $userRichLevel . " and level<=" . $newRicherLevel);
                        foreach ($richerConList as $v) {
                            if ($v->hornNum) {
                                $richer->getUserItemsObject()->giveItem(2, $v->hornNum); //送喇叭
                                //给用户发送通知
                                $content = $richer->getUserInformationObject()->getInfoContent($this->config->informationCode->richerHorn, array(0 => $levelName, 1 => $v->hornNum));
                                $richer->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                            }
                        }
                    }
                 }
                
                
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), 'errorMessage = ' . $e->getMessage());
        }
            
    }

    public function dealAnchorExp($anchor, $anchorId, $anchorExp, $roomId){
        try {
            //主播经验
            $isAnchorUp = 0;
            if($anchorExp > 0){
                $anchorProfiles = UserProfiles::findFirst("uid = " . $anchorId);
                $anchExp = $anchorProfiles->exp2;
                $anchExpNew = $anchExp + $anchorExp;
                $anchLevel = $anchorProfiles->level2;

                //判断主播是否升级
                $anchorConfigs = \Micro\Models\AnchorConfigs::findfirst('higher >= ' . $anchExpNew . ' and lower <= ' . $anchExpNew);
                if($anchorConfigs && $anchorConfigs->level > $anchLevel){
                    $isAnchorUp = 1;
                    $newAnchorLevel = $anchorConfigs->level;
                }else{
                    $newAnchorLevel = $anchLevel;
                }

                $anchorUpSql = 'update pre_user_profiles set exp2 = exp2 + ' . $anchorExp;
                $anchorUpSql .= ($isAnchorUp ? ',level2 = ' . $newAnchorLevel : '');
                $anchorUpSql .= ' where uid = ' . $anchorId;
                $connection = $this->di->get('db');
                $connection->execute($anchorUpSql);
            }
                
            //广播主播经验信息
            if ($roomId) {
                if ($isAnchorUp) {
                    // $anchorInfo = $anchor->getUserInfoObject()->getUserInfo();
                    $roomData = \Micro\Models\Rooms::findfirst($roomId);
                    $broadData = $this->di->get('roomModule')->getRoomOperObject()->setBroadcastParam($anchor, $roomData->uid);
                    // $broadData['uid'] = $anchorId;
                    $broadData['roomId'] = $roomId;
                    $broadData['oldLevel'] = $anchLevel;
                    $broadData['newLevel'] = $newAnchorLevel;

                    // 查询下一等级是否存在
                    $broadData['nextLevel'] = $newAnchorLevel;
                    $count = AnchorConfigs::count("level = " . $newAnchorLevel + 1);
                    if ($count) {
                        $broadData['nextLevel'] = $newAnchorLevel + 1;
                    }

                    // $broadData['nickName'] = $anchorInfo['nickName'];
                    // $broadData['avatar'] = $anchorInfo['avatar'];
                    $ArraySubData['controltype'] = "anchorLevel";
                    $ArraySubData['data'] = $broadData;
                    // $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
                    $this->comm->roomBroadcast($roomId, $ArraySubData);

                    // 判断是否达到系统广播的条件
                    $roomModule = $this->di->get('roomModule');
                    if($newAnchorLevel >= $this->config->minLevelUpBroad->anchorMinLevel){
                        $levelUpAllRoom['controltype'] = "anchorLevelAllRoom";
                        $broadData['runNum'] = 3;
                        $levelUpAllRoom['data'] = $broadData;
                        $roomModule->getRoomOperObject()->allRoomBroadcast($levelUpAllRoom);
                    }

                    // 更新用户所在直播间的userdata
                    $roomModule->getRoomOperObject()->updateUserdataInRooms($anchor);
                }

                if($anchorExp > 0){
                    $levelData = AnchorConfigs::findFirst("level = " . $newAnchorLevel);
                    if (!empty($levelData)) {
                        $broadExpData['uid'] = $anchorId;
                        $broadExpData['anchorExp'] = $anchExpNew;
                        $broadExpData['anchorHigher'] = $levelData->higher+1;
                        $broadExpData['anchorLower'] = $levelData->lower;

                        $ArraySubData['controltype'] = "anchorExp";
                        $ArraySubData['data'] = $broadExpData;
                        $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
                    }
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), 'errorMessage = ' . $e->getMessage());
        }
    }

    /**
     * 消费数据处理
     * @param $user     传入user对象的目的是为了可以快速的直接访问user的其他信息
     * @param $consume  消费的数量
     * @param $exp   增长的经验
     * @param $roomId   房间Id，如果为空，则表示不是在房间中处理，不需要做升级广播
     * @param $isCoin   是否为聊豆消费，默认为false
     * @param $isFree   是否为免费礼物，默认为false
     */
    public function processConsumeData($user, $consume, $exp, $roomId = null, $isCoin = false, $isFree = false) {

        if ($consume < 0) {
          return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'));
        }

        // 保存新数据到数据库中
        if (!$isFree) {//如果不是免费礼物，则扣除对应的聊豆或聊币
            if ($isCoin) {
              try {
                $connection = $this->di->get('db');
                //$connection->execute("LOCK TABLES  ".$this->config->mysql->dbname.".pre_user_profiles WRITE;");
                $sql = "call ".$this->config->mysql->dbname.".money({$this->uid},{$consume},1,0,0,@res_code);select @rescode;";
                $res_code = $connection->fetchOne($sql);
                //$connection->execute("UNLOCK TABLES;");
                // $connection->close();

                if($res_code['res_code'] != 0){
                  return $this->status->retFromFramework($this->status->getCode('OPER_USER_MONEY_ERROR'));
                }
              } catch (\Exception $e) {
                return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), 'errorMessage = ' . $e->getMessage());
              }
            } else {
                try {
                  $connection = $this->di->get('db');
                  //$connection->execute("LOCK TABLES  ".$this->config->mysql->dbname.".pre_user_profiles WRITE;");
                  $sql = "call ".$this->config->mysql->dbname.".money({$this->uid},{$consume},0,0,0,@res_code);select @rescode;";
                  $res_code = $connection->fetchOne($sql);
                  //$connection->execute("UNLOCK TABLES;");

                  if($res_code['res_code'] != 0){
                    return $this->status->retFromFramework($this->status->getCode('OPER_USER_MONEY_ERROR'));
                  }
                } catch (\Exception $e) {
                  return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), 'errorMessage = ' . $e->getMessage());
                }
            }
        }

        // 更新用户信息表，将消费扣掉
        $userData = UserProfiles::findFirst("uid = " . $this->uid);

        $isLevelUp=0;
        $carId = 0;
        $newRicherExp = $userData->exp3;
        if (!$isCoin) {//聊豆不产生富豪经验
            // 更新富豪经验
            $newRicherExp = $userData->exp3 + $exp;
			$userAccount = $user->getUserInfoObject()->getUserAccountInfo();
            $userInfo = $user->getUserInfoObject()->getUserInfo();
            $userProfiles = $user->getUserInfoObject()->getUserProfiles();
            $currentRicherLevel = $userProfiles['richerLevel'];
            // 判断富豪是否升级
            $phql = "SELECT level, carId,name FROM \Micro\Models\RicherConfigs WHERE higher >= " . $newRicherExp . " AND lower <= " . $newRicherExp . " LIMIT 1";
            $query = $this->modelsManager->createQuery($phql);
            $richerConfigs = $query->execute();
            $newLevel = $currentRicherLevel;
            if ($richerConfigs->valid()) {
                foreach ($richerConfigs as $configData) {
                    $newLevel = $configData->level;
                    $carId = $configData->carId;
                    $levelName = $configData->name;
                    break;
                }
            }

            $isLevelUp = 0;
            if ($newLevel > $currentRicherLevel) {
                $isLevelUp = 1;
                /////////by 2015/07/03 //////////
                //$userData->level3 = $newLevel;
             }
        }

        /////////by 2015/07/03 /////////////$userData->exp3 = $newRicherExp;
        /////////by 2015/07/03 /////////////$userData->save();
        /////////by 2015/07/03 /////////////
        $updatesql = "update \Micro\Models\UserProfiles set exp3=exp3+" . $exp;
        $isLevelUp && $updatesql.=",level3=" . $newLevel;
        $updatesql.=" where uid=" . $this->uid;
        $query = $this->modelsManager->createQuery($updatesql);
        $query->execute();

        // 判断富豪等级如果有升级，则需要做的操作
        if ($isLevelUp) {
            if ($roomId) {   //广播
                $broadData['uid'] = $this->uid;
                $broadData['roomId'] = $roomId;
                $broadData['oldLevel'] = $currentRicherLevel;
                $broadData['newLevel'] = $newLevel;

                // 查询下一等级是否存在
                $broadData['nextLevel'] = $newLevel;
                $count = RicherConfigs::count("level = " . $newLevel + 1);
                if ($count) {
                    $broadData['nextLevel'] = $newLevel + 1;
                }

                $broadData['nickName'] = $userInfo['nickName'];
                $broadData['avatar'] = $userInfo['avatar'];
                // 为什么要加守护和座驾的信息
                // 'guard' => $guard,
                // 'hasCar' => $carInfo ? 1 : 0,

                //是否超级管理员
                $nodejsUserData['manageType'] = $userAccount['manageType'];

                $ArraySubData['controltype'] = "richerLevel";
                $ArraySubData['data'] = $broadData;
                $this->comm->roomBroadcast($roomId, $ArraySubData);
                
                $nodejsUserData = array();
                // accountId
                // 昵称、头像
                $nodejsUserData['userId'] = $this->uid;
                $nodejsUserData['name'] = $userInfo['nickName'];
                $nodejsUserData['avatar'] = $userInfo['avatar'];
                // 进入房间的用户的主播富豪等级、VIP等级
                $nodejsUserData['vipLevel'] = $userProfiles['vipLevel'];
                $nodejsUserData['anchorLevel'] = $userProfiles['anchorLevel'];
                $nodejsUserData['richerLevel'] = $userProfiles['richerLevel'];
                //平台信息
                $nodejsUserData['platform'] = $this->di->get('roomModule')->getRoomOperObject()->getPlatform();
                // 座驾信息
                $carInfo = $user->getUserItemsObject()->getActiveCarData();
                if ($carInfo) {
                    $nodejsUserData['carInfo'] = $carInfo;
                }
                $accountId = $user->getUserInfoObject()->getAccountId();
                // 获取是否禁言状态
                $roomBase = new \Micro\Frameworks\Logic\Room\RoomBase();
                $nodejsUserData['isForbid'] = $roomBase->checkUserIsForbidden($roomId, $this->uid);
                // 获取守护信息
                $roomData = \Micro\Models\Rooms::findfirst($roomId);
                $guardData = $user->getUserItemsObject()->getGuardData($roomData->uid);
                if ($guardData != NULL) {
                    $nodejsUserData['guardLevel'] = $guardData['level'];
                } else {
                    $nodejsUserData['guardLevel'] = '';
                }
                //用户家族信息
                $nodejsUserData['isFamilyLeader'] = $this->di->get('userMgr')->checkUserIsHeader($roomData->uid, $this->uid);
                //查询用户属于哪个军团
                $groupres = $this->di->get('groupMgr')->checkUserGroup($this->uid);
                if ($groupres['code'] == $this->status->getCode('OK') && $groupres['data']) {
                    $nodejsUserData['group'] = $groupres['data'];
                }
                

                $this->comm->updateUserData($roomId, $accountId, json_encode($nodejsUserData));
            }

            //送座驾
            if ($carId) {
                //判断是否vip
                $vipLevel = $user->getUserInfoObject()->getVipLevel();
                if ($vipLevel > 0) {
                    if ($newLevel - $currentRicherLevel == 1) {//富豪等级只升了1级
                        $user->getUserItemsObject()->giveCar($carId);
                        //给用户发送通知
                        $carInfo = \Micro\Models\CarConfigs::findfirst($carId);
                        $content = $user->getUserInformationObject()->getInfoContent($this->config->informationCode->rich, array(0 => $levelName, 1 => $carInfo->name));
                        $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    } else {//富豪等级跨级
                        $richerConList = \Micro\Models\RicherConfigs::find("level>" . $currentRicherLevel . " and level<=" . $newLevel);
                        foreach ($richerConList as $k => $v) {
                            if ($v->carId) {
                                $user->getUserItemsObject()->giveCar($v->carId);
                                //给用户发送通知
                                $carInfo = \Micro\Models\CarConfigs::findfirst($v->carId);
                                $content = $user->getUserInformationObject()->getInfoContent($this->config->informationCode->rich, array(0 => $v->name, 1 => $carInfo->name));
                                $user->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                            }
                        }
                    }
                }
            }
        }
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /**
     * 收入数据处理
     * @param $anchor 传入anchor对象的目的是为了可以快速的直接访问anchor的其他信息
     * @param $money 收益，需要根据主播等级来进行比例计算
     * @param $exp 经验
     * @param $roomId   房间Id，如果为空，则表示不是在房间中处理，不需要做升级广播
     * @param $uid 为主播消费的用户id
     */
    public function processIncomeData($anchor, $money, $exp, $roomId = null, $uid = 0) {
        $anchorUid = $anchor->getUid(); //主播id
        // 判断该收入是属于家族收入还是属于主播个人收入
        $familyId = 0;
        $signAnchor = SignAnchor::findFirst('uid = ' . $anchorUid . ' and familyId > 0');
        if ($signAnchor) {
            $familyId = $signAnchor->familyId;
        }

        try {
          if($familyId == 0){
            $consume = $money;// * $this->getIncomeRate($anchorUid);
            $isFamily = 0;
            $lockTable = 'pre_user_profiles';
          }else{
            $consume = $money;
            $isFamily = 1;
            $lockTable = 'pre_sign_anchor';
          }
          // var_dump($isFamily);
          // die;]
          $connection = $this->di->get('db');//LOCK TABLES  ".$this->config->mysql->dbname.".pre_user_profiles WRITE;
          //$connection->execute("LOCK TABLES  ".$this->config->mysql->dbname.".".$lockTable." WRITE;");
          $sql = "call ".$this->config->mysql->dbname.".money({$anchorUid},{$consume},0,1,{$isFamily},@res_code);select @rescode;";
          $res_code = $connection->fetchOne($sql);
          //$connection->execute("UNLOCK TABLES;");

          if($res_code['res_code'] != 0){
            return $this->status->retFromFramework($this->status->getCode('OPER_USER_MONEY_ERROR'));
          }
        } catch (\Exception $e) {
          return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), 'errorMessage = ' . $e->getMessage());
        }

        // 更新用户信息表，将收益添加
        $userData = UserProfiles::findFirst("uid = " . $anchorUid);
        // 更新主播等级
        $exp = $exp;
        $newAnchorExp = $userData->exp2 + $exp;
        $userInfo = $anchor->getUserInfoObject()->getUserInfo();
        $userProfiles = $anchor->getUserInfoObject()->getUserProfiles();
        $currentAnchorLevel = $userProfiles['anchorLevel'];

        // 判断主播是否升级
        $phql = "SELECT level FROM \Micro\Models\AnchorConfigs WHERE higher > " . $newAnchorExp . " AND lower < " . $newAnchorExp . " LIMIT 1";
        $query = $this->modelsManager->createQuery($phql);
        $richerConfigs = $query->execute();
        $newLevel = $currentAnchorLevel;
        if ($richerConfigs->valid()) {
            foreach ($richerConfigs as $configData) {
                $newLevel = $configData->level;
                break;
            }
        }

       
        $isLevelUp = 0;
        if ($newLevel > $currentAnchorLevel) {
            $isLevelUp = 1;
            //////by 2015/07/03////////
            // $userData->level2 = $newLevel;
         }

        //判断是否是托账号
        /*if ($uid) {
            $info = \Micro\Models\Users::findfirst($uid);
            if ($info->internalType == $this->config->userInternalType->tuo) {//托账号的消费 不计入收益
                $money = 0;
            }
        }*/

        // 保存新数据到数据库中
        //$money=$money*$this->getIncomeRate($uid);
        /* if ($familyId == 0) {
          // 不在家族里面的主播，收到的收益是换算的而结果
          $userData->money += ($money * $this->getIncomeRate($anchorUid)); //收益只加到money字段
          } */



        ///////by 2015/07/03///////// $userData->exp2 = $newAnchorExp;
        ////////by 2015/07/03//////// $userData->save();
          //////by 2015/07/03//////////
        $updatesql = "update \Micro\Models\UserProfiles set exp2=exp2+" . $exp;
        $isLevelUp&&$updatesql.=",level2=" . $newLevel;
        $updatesql.=" where uid=" . $anchorUid;
        $updatequery = $this->modelsManager->createQuery($updatesql);
        $updatequery->execute();


        /*if ($familyId != 0) {
            //该主播在家族中，收益算法另外算
            $signAnchor->money += $money;
            $signAnchor->save();
        }*/

        // 判断主播等级如果有升级，则需要做的操作
        if ($isLevelUp) {
            if ($roomId) {   //广播
                $broadData['uid'] = $anchorUid;
                $broadData['roomId'] = $roomId;
                $broadData['oldLevel'] = $currentAnchorLevel;
                $broadData['newLevel'] = $newLevel;

                // 查询下一等级是否存在
                $broadData['nextLevel'] = $newLevel;
                $count = AnchorConfigs::count("level = " . $newLevel + 1);
                if ($count) {
                    $broadData['nextLevel'] = $newLevel + 1;
                }

                $broadData['nickName'] = $userInfo['nickName'];
                $broadData['avatar'] = $userInfo['avatar'];
                $ArraySubData['controltype'] = "anchorLevel";
                $ArraySubData['data'] = $broadData;
                $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
             }
        }
        //广播主播经验信息
        if ($roomId) {
            $levelData = AnchorConfigs::findFirst("level=" . $newLevel);
            if (!empty($levelData)) {
                $broadExpData['uid'] = $anchorUid;
                $broadExpData['anchorExp'] = $newAnchorExp;
                $broadExpData['anchorHigher'] = $levelData->higher+1;
                $broadExpData['anchorLower'] = $levelData->lower;

                $ArraySubData['controltype'] = "anchorExp";
                $ArraySubData['data'] = $broadExpData;
                $result = $this->comm->roomBroadcast($roomId, $ArraySubData);
            }
        }
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /**
     * 消费日志记录
     *
     *
     **/
    public function addConsumeDetailLog($type, $amount, $income, $itemId, $count, $receiveUid, $remark, $familyId = 0, $nickName = '', $isTuo = 0) {
        $log = new ConsumeDetailLog();
        $log->uid = $this->uid;
        $log->type = $type;
        $log->amount = $amount;
        $log->income = $income;
        $log->itemId = $itemId;
        $log->count = $count;
        $log->receiveUid = $receiveUid;
        $log->remark = $remark;
        $log->familyId = $familyId;
        $log->nickName = $nickName;
        $log->isTuo = $isTuo;
        $log->createTime = time();
        $log->save();
        return $log;
    }

    /**
     * 添加用户自身的消费记录
     * @param type 消费类型
     * @param amount 消费数量
     * @param income 收益，需要根据主播等级来进行比例计算
     * @param anchorId 主播id
     * @param familyId 家族id
     * @return ConsumeLog
     */
    public function addConsumeLog($type, $amount, $income = 0, $anchorId = 0, $familyId = 0) {
        /*$log = new ConsumeDetailLog();
        $log->uid = $this->uid;
        $log->type = $type;
        $log->anchorId = $anchorId;
        $log->familyId = $familyId;
        $log->amount = $amount;*/
        /*//判断当日消费表是否存在
        $connection = $this->di->get('db');
        $tableName = 'pre_consume_log_' . date('Ymd');
        $exists = $connection->tableExists($tableName);
        if(!$exists){
            $sql = "CREATE TABLE `".$tableName."` ( "
                   . " `id` int(11) NOT NULL AUTO_INCREMENT, "
                   . " `uid` int(11) DEFAULT NULL, "
                   . " `type` int(11) DEFAULT NULL, "
                   . " `anchorId` int(11) DEFAULT NULL, "
                   . " `familyId` int(11) DEFAULT NULL, "
                   . " `amount` decimal(32,3) DEFAULT NULL, "
                   . " `createTime` int(11) DEFAULT NULL, "
                   . " `income` decimal(32,3) DEFAULT '0.000' COMMENT '收益记录', "
                   . " `ratio` smallint(3) DEFAULT '0' COMMENT '分成比例', "
                   . " PRIMARY KEY (`id`), KEY `Index_uid` (`uid`) "
                   . " ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            $connection->execute($sql);
        }*/
        $log = new ConsumeLog();
        $log->uid = $this->uid;
        $log->type = $type;
        $log->anchorId = $anchorId;
        $log->familyId = $familyId;
        $log->amount = $amount;
        // if ($familyId != 0 || $anchorId == 0) {
            $log->income = $income;
        // } else {
            // $log->income = $income * $this->getIncomeRate($anchorId);
        // }

        $log->ratio = 100;//intval(100 * $this->getIncomeRate($anchorId));

        $log->createTime = time();
        $log->save();
        return $log;
    }

    /*
     * 统计用户收益
     * */

    public function countConsume($inFamily, $timeBegin, $timeEnd, $page, $pageSize) {
        $pageBegin = $page * $pageSize;
        $sum = 0;
        $data = array();
        try {
            //获取主播房间ID
            $room = Rooms::findFirst("uid={$this->uid}");
            if (empty($room)) {
                return array("sum" => 0, "data" => 0);
            }
             /**if ($inFamily) {//家族收益
                $sql = "SELECT f.id,f.name,f.logo,sum(cl.income)income, cl.createTime " .
                  " FROM \Micro\Models\ConsumeLog cl " .
                  " LEFT JOIN \Micro\Models\Family f ON f.id = cl.familyId" .
                  " WHERE cl.anchorId = {$this->uid}" .
                  " AND cl.familyId = f.id AND cl.type < " . $this->config->consumeType->coinType .
                  " AND cl.createTime BETWEEN " . $timeBegin . " AND " . $timeEnd .
                  " GROUP BY from_unixTime(cl.createTime, '%Y%M%D'),cl.familyId ORDER BY cl.createTime DESC LIMIT {$pageBegin},{$pageSize}";
                  $query = $this->modelsManager->createQuery($sql);
                  $records = $query->execute();

                  if ($records->valid()) {//家族可能需要判断roomlog的familyid判断该场次所在家族
                  foreach ($records as $record) {
                  $sum += $record['income'];

                  $dayTimeBegin = strtotime(date('Y-m-d', $record['createTime']));
                  $dayTimeEnd = $dayTimeBegin + 60 * 60 * 24 - 1;
                  $sql2 = "SELECT sum(rle.endTime - rlp.publicTime - IFNULL(({$dayTimeBegin} - rlop.publicTime),0) - IFNULL((rlop.endTime - {$dayTimeBegin}),0))playTime " .
                  " FROM \Micro\Models\RoomLog rle " .
                  " LEFT JOIN \Micro\Models\RoomLog rlp ON rlp.id=rle.id" .
                  " LEFT JOIN \Micro\Models\RoomLog rloe ON rloe.id=rle.id AND !(rloe.endTime BETWEEN {$dayTimeBegin} AND {$dayTimeEnd})" .
                  " LEFT JOIN \Micro\Models\RoomLog rlop ON rlop.id=rle.id AND !(rlop.publicTime BETWEEN {$dayTimeBegin} AND {$dayTimeEnd})" .
                  " WHERE rle.roomId={$room->roomId} AND rle.endTime > rle.publicTime " .
                  " AND ((rle.publicTime BETWEEN {$dayTimeBegin} AND {$dayTimeEnd}) OR (rle.endTime BETWEEN {$dayTimeBegin} AND {$dayTimeEnd}))";
                  $query = $this->modelsManager->createQuery($sql2);
                  $time = $query->execute();
                  $record['playTime'] = 0;
                  if ($time->valid()) {
                  $playTime = $time->toArray();
                  if (!empty($playTime)) {
                  $record['playTime'] = $playTime[0]['playTime'];
                  }
                  }
                  $data[] = $record;
                  }
                  } 
            } else {//非家族收益(个人)关播时间点为准，收益为当天的，不关心是否直播。时长准确，不关心是否正在直播
                /*                 * $sql = "SELECT sum(cl.income)income, cl.createTime " .
                  ' FROM \Micro\Models\ConsumeLog cl ' .
                  ' WHERE cl.anchorId = ' . $this->uid .
                  ' AND cl.familyId = 0 AND cl.type < ' . $this->config->consumeType->coinType .
                  ' AND cl.createTime BETWEEN ' . $timeBegin . ' AND ' . $timeEnd .
                  " GROUP BY from_unixTime(cl.createTime, '%Y%M%D') ORDER BY cl.createTime DESC LIMIT {$pageBegin},{$pageSize}";

                  $query = $this->modelsManager->createQuery($sql);
                  $records = $query->execute();

                  $data = array();
                  if ($records->valid()) {
                  foreach ($records as $record) {
                  $sum += $record['income'];

                  $dayTimeBegin = strtotime(date('Y-m-d', $record['createTime']));
                  $dayTimeEnd = $dayTimeBegin + 60 * 60 * 24 - 1;
                  $sql2 = "SELECT sum(rle.endTime - rlp.publicTime - IFNULL(({$dayTimeBegin} - rlop.publicTime),0) - IFNULL((rlop.endTime - {$dayTimeBegin}),0))playTime " .
                  " FROM \Micro\Models\RoomLog rle " .
                  " LEFT JOIN \Micro\Models\RoomLog rlp ON rlp.id=rle.id" .
                  " LEFT JOIN \Micro\Models\RoomLog rloe ON rloe.id=rle.id AND !(rloe.endTime BETWEEN {$dayTimeBegin} AND {$dayTimeEnd})" .
                  " LEFT JOIN \Micro\Models\RoomLog rlop ON rlop.id=rle.id AND !(rlop.publicTime BETWEEN {$dayTimeBegin} AND {$dayTimeEnd})" .
                  " WHERE rle.roomId={$room->roomId} AND rle.endTime > rle.publicTime" .
                  " AND ((rle.publicTime BETWEEN {$dayTimeBegin} AND {$dayTimeEnd}) OR (rle.endTime BETWEEN {$dayTimeBegin} AND {$dayTimeEnd}))";
                  $query = $this->modelsManager->createQuery($sql2);
                  $time = $query->execute();
                  $record['playTime'] = 0;
                  if ($time->valid()) {
                  $playTime = $time->toArray();
                  if (!empty($playTime)) {
                  $record['playTime'] = $playTime[0]['playTime'];
                  }
                  }
                  $data[] = $record;
                  }
                  }
             }* */
            
             //计算总收益
            $sql = "SELECT sum(cl.income)income, cl.createTime " .
                    ' FROM \Micro\Models\ConsumeLog cl ' .
                    ' WHERE cl.anchorId = ' . $this->uid .
                    ' AND cl.type < ' . $this->config->consumeType->coinType .
                    ' AND cl.createTime BETWEEN ' . $timeBegin . ' AND ' . $timeEnd;
            if ($inFamily) {//家族收益
                $sql.=" and cl.familyId<>0";
            }else{//非家族收益
                $sql.=" and cl.familyId=0";
            }
            $query = $this->modelsManager->createQuery($sql);
            $incomeResult = $query->execute();
            $sum = $incomeResult[0]['income'];

            $roomModule = $this->di->get('roomModule');
            $timeresult = $roomModule->getRoomOperObject()->getAnchorBroadcastTime($room->roomId, $timeBegin, $timeEnd, $page, $pageSize);
            $newTimeResult = array();
            if ($timeresult['code'] == $this->status->getCode('OK')) {
                $familyName='';
                if ($inFamily) {//家族
                //查询家族名
                $fsql="select f.name from \Micro\Models\SignAnchor s inner join \Micro\Models\Family f on s.familyId=f.id where s.uid=".$this->uid;
                $fquery = $this->modelsManager->createQuery($fsql);
                $fresult= $fquery->execute();
                $familyName=$fresult[0]['name'] ? $fresult[0]['name'] : '';
                }
                foreach ($timeresult['data'] as $key => $val) {
                    $arr['playTime'] = $val['sum'];
                    $arr['createTime'] = strtotime($val['time']);
                    $valsql = "SELECT sum(cl.income)income, cl.createTime " .
                            ' FROM \Micro\Models\ConsumeLog cl ' .
                            ' WHERE cl.anchorId = ' . $this->uid .
                            ' AND cl.type < ' . $this->config->consumeType->coinType .
                            ' AND cl.createTime BETWEEN ' . $val['publicTime'] . ' AND ' . $val['endTime'];
                    if ($inFamily) {//家族收益
                        $valsql.=" and cl.familyId<>0";
                        $arr['familyName']=$familyName;
                    } else {//非家族收益
                        $valsql.=" and cl.familyId=0";
                    }
                    $valquery = $this->modelsManager->createQuery($valsql);
                    $valincomeResult = $valquery->execute();
                    $arr['income'] = $valincomeResult[0]['income'] ? $valincomeResult[0]['income'] : 0;
                    
            
                    
                    array_push($data, $arr);
                }
            }

            $result['sum'] = $sum ? $sum : 0;
            $result['data'] = $data;
            return $result;
        } catch (\Exception $e) {
            return array("sum" => 0, "data" => 0);
        }
    }

    //按天获取特定类型的收益
    public function getConsumeDayByDay($type, $timeBegin, $timeEnd, $page, $pageSize){
      $periodTotal = $this->getPeriodIncome($timeBegin, $timeEnd, false);
      try {
        $familyLogs = \Micro\Models\FamilyLog::find('uid = ' . $this->uid . ' order by joinTime DESC');// . ' and joinTime >= ' . $timeBegin . ' and joinTime <= ' . $timeEnd
        if($familyLogs->valid()){
          if($type == 'inFamily'){
            return array('sum' => 0, 'data' => 0, 'count' => 0);
          }
          //主播一直都是非家族
          $tmpStartDate = date('Y-m-d',$timeBegin);
          $tmpStartTime = strtotime($tmpStartDate);
          $tmpEndDate = date('Y-m-d',$timeEnd);
          $tmpEndTime = strtotime($tmpEndDate);
          $days = (strtotime($tmpEndDate) - strtotime($tmpStartDate)) / 86400 + 1;
          $list = array();

          for($i = 0; $i < $days; $i++){
            $tmpStart = $tmpEndTime - $i * 86400;
            $tmpEnd = $tmpEndTime - 86400 * ($i - 1) - 1;
            $tmpIncomeRes = $this->getDayIncome($tmpStart, $tmpEnd, false);
            $tmpDate = date('Y-m-d',$tmpStart);
            if($tmpIncomeRes['myIncome'] <= 0){
              continue;
            }
            $list[$tmpDate] = $tmpIncomeRes['myIncome'];
            /*array(
              'myIncome' => $tmpIncomeRes['myIncome']
            );*/
          }

          $result['sum'] = $periodTotal[0]['income'];
          $result['data'] = $list;
          $result['count'] = count($list);
          return $result;
        }
        if($type == 'inFamily'){

          $familyList = $this->getFamilyIncome($familyLogs, $timeBegin, $timeEnd);

          $result['sum'] = $familyList['sum'];
          $result['data'] = $familyList['familyList'];
          $result['count'] = count($familyList['familyList']);
          return $result;
        }else{
          $familyList = $this->getFamilyIncome($familyLogs, $timeBegin, $timeEnd)['familyList'];
          $anchorList = array();
          $dayIncomes = $this->getPeriodIncome($timeBegin, $timeEnd, true);

          foreach ($dayIncomes as $key => $dayIncome) {
            $anchorList[$dayIncome['time']] = $dayIncome['income']- (array_key_exists($dayIncome['time'], $familyList) ? $familyList[$dayIncome['time']]['myIncome'] : 0);
            if($anchorList[$dayIncome['time']] <= 0){
              unset($anchorList[$dayIncome['time']]);
            }
          }

          $result['sum'] = $periodTotal[0]['income'] - $this->getFamilyIncome($familyLogs, $timeBegin, $timeEnd)['sum'];
          $result['data'] = $anchorList;
          $result['count'] = count($anchorList);
          return $result;
        }
      } catch (\Exception $e) {
        return array('sum' => 0, 'data' => 0, 'count' => 0);
      }
    }

    //获取某段时间的总收益
    private function getPeriodIncome($timeBegin, $timeEnd, $type = true){
      $sql = "select sum(cl.income) as income, FROM_UNIXTIME(cl.createTime, '%Y-%m-%d') as time FROM \Micro\Models\ConsumeDetailLog cl " . 
            "where cl.createTime between " . $timeBegin . " and " . $timeEnd . " AND cl.receiveUid = " . $this->uid;
      if($type){
        $sql .= " group by time" . " order by cl.createTime DESC";
      }
       
      $query = $this->modelsManager->createQuery($sql);
      $result = $query->execute();
      return $result->toArray();
    }

    //获取某段时间的家族收益
    private function getFamilyIncome($familyLogs, $timeBegin, $timeEnd){
      $familyList = array();
      $sum = 0;
      foreach ($familyLogs as $k => $familyLog) {
        // if($familyLog->joinTime < $timeBegin && $familyLog->outOfTime < $timeBegin){
        //   continue;
        // }
        $tmpJoinTime = $familyLog->joinTime < $timeBegin ? $timeBegin : $familyLog->joinTime;
        $tmpOutOfTime = is_null($familyLog->outOfTime) ? time() : $familyLog->outOfTime;
        $tmpOutOfTime = $tmpOutOfTime > $timeEnd ? $timeEnd : $tmpOutOfTime;
        $tmpjoinDate = date('Y-m-d',$tmpJoinTime);
        $tmpjoinDateTime = strtotime($tmpjoinDate);
        $tmpOutDate = date('Y-m-d',$tmpOutOfTime);
        $tmpOutDateTime = strtotime($tmpOutDate);
        $days = (strtotime($tmpOutDate) - strtotime($tmpjoinDate)) / 86400 + 1;
        $tmpArr = array();
        
        for($i = 0; $i < $days; $i++){
          if($i == ($days - 1)){
            $tmpStart = $tmpJoinTime;
            $tmpEnd = $tmpOutDateTime - 86400 * ($i - 1) - 1;
          }elseif($i == 0){
            $tmpStart = $tmpOutDateTime - $i * 86400;
            $tmpEnd = $tmpOutOfTime;
          }else{
            $tmpStart = $tmpOutDateTime - $i * 86400;
            $tmpEnd = $tmpOutDateTime - 86400 * ($i - 1) - 1;
          }
          // echo date('Y-m-d H:i:s',$tmpStart).'--'.$familyLog->familyId.'--'.date('Y-m-d H:i:s',$tmpEnd).'===';
          $tmpIncomeRes = $this->getDayIncome($tmpStart, $tmpEnd, $familyLog->familyId);
          $tmpDate = date('Y-m-d',$tmpStart);
          if($tmpIncomeRes['myIncome'] <= 0){
            continue;
          }
          $tmpArr[$tmpDate] =array(
            'name' => $tmpIncomeRes['name'],
            'myIncome' => $tmpIncomeRes['myIncome'],
            'familyIncome' => $tmpIncomeRes['familyIncome'],
            'ratio' => $tmpIncomeRes['familyIncome'] > 0 ? floor(($tmpIncomeRes['myIncome'] / $tmpIncomeRes['familyIncome']) * 100) . '%' : '0%'
          );
          $sum += $tmpIncomeRes['myIncome'];
        }
        $familyList = $familyList+$tmpArr;
        // var_dump($familyList);
        // die;
      }
      return array('sum' => $sum,'familyList' => $familyList);
    }

    //获取当天收益
    public function getDayIncome($timeBegin, $timeEnd, $familyId){
      if($familyId){//家族
        $sqlFamily = 'SELECT sum(cl.income) as income, cl.createTime, f.name FROM \Micro\Models\ConsumeDetailLog cl left join \Micro\Models\Family as f on f.id = cl.familyId ' . ' WHERE ' .
            ' cl.type < ' . $this->config->consumeType->coinType . ' AND cl.createTime BETWEEN ' . $timeBegin . ' AND ' . $timeEnd . ' AND cl.familyId = ' . $familyId;
        $sqlAnchor = $sqlFamily . ' AND cl.receiveUid = ' . $this->uid;

        $queryFamily = $this->modelsManager->createQuery($sqlFamily);
        $familyResult = $queryFamily->execute();

        $queryAnchor = $this->modelsManager->createQuery($sqlAnchor);
        $anchorResult = $queryAnchor->execute();

        return array(
          'myIncome' => is_null($anchorResult[0]['income']) ? 0 : $anchorResult[0]['income'],//intval($anchorResult[0]['income']),
          'familyIncome' => is_null($familyResult[0]['income']) ? 0 : $familyResult[0]['income'],//intval($familyResult[0]['income'])//
          'name' => is_null($familyResult[0]['name']) ? 0 : $familyResult[0]['name'],//intval($familyResult[0]['income'])//
        );

      }else{//非家族

        $sqlAnchor = 'SELECT sum(cl.income) as income, cl.createTime FROM \Micro\Models\ConsumeDetailLog cl ' . ' WHERE ' .
            ' cl.type < ' . $this->config->consumeType->coinType . ' AND cl.createTime BETWEEN ' . $timeBegin . ' AND ' . $timeEnd . ' AND cl.receiveUid = ' . $this->uid;
        $queryAnchor = $this->modelsManager->createQuery($sqlAnchor);
        $anchorResult = $queryAnchor->execute();

        return array(
          'myIncome' => is_null($anchorResult[0]['income']) ? 0 : $anchorResult[0]['income']//intval($anchorResult[0]['income'])
        );
      }
    }

    //我的账单
    public function consumeList($type, $times) {
        //$times = strtotime($times);
        $y = date("Y", $times);
        $m = date("m", $times);
        $d = date("d", $times);
        $start = mktime(0, 0, 0, $m, $d, $y);  //开始时间戳
        $end = mktime(23, 59, 59, $m, $d, $y);  //结束时间戳
        $amountSum = 0;
        $result = array();

        /*//获取礼物的配置信息
        $carConfigs = \Micro\Models\CarConfigs::find();
        $guardConfigs = \Micro\Models\GuardConfigs::find();
        $giftConfigs = \Micro\Models\GiftConfigs::find();
        $configs = array(
            $this->config->consumeType->sendGift => $giftConfigs->toArray(),
            $this->config->consumeType->buyGuard => $guardConfigs->toArray(),
            $this->config->consumeType->buyGuard => $carConfigs->toArray()
        );*/

        switch ($type) {

            case "getGift":
                //收到的礼物

                /*$sql = "select a.uid,a.anchorId,a.amount,a.createTime,b.nickName,c.count,d.name,d.configName from " .
                       " \Micro\Models\ConsumeLog a,\Micro\Models\UserInfo b,\Micro\Models\GiftLog c,\Micro\Models\GiftConfigs d " .
                       " where  a.uid=b.uid AND a.id = c.consumeLogId AND c.giftId = d.id AND a.createTime > '$start' AND a.createTime <= '$end' AND anchorId =" . $this->uid .
                       " ORDER BY a.createTime DESC";*/

                /*$sql = 'select cl.id,cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,cl.type from \Micro\Models\ConsumeLog as cl ' .
                       ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.uid ' . 
                       ' where (cl.type = ' . $this->config->consumeType->sendGift . ' or cl.type = ' . $this->config->consumeType->grabSeat . ' or cl.type = ' . $this->config->consumeType->buyGuard . ') ' .
                       ' and cl.createTime > ' . $start . ' and cl.createTime <= ' . $end . ' and cl.anchorId = ' . $this->uid . ' ORDER BY cl.createTime DESC';
                $query = $this->modelsManager->createQuery($sql);
                $tempData = $query->execute();
                $data = $tempData->toArray();
                foreach ($data as $k => &$v) {
                  $v['process'] = 1;
                  $v['createTime'] = date('H:i:s', $v['createTime']);
                  if ($v['type'] == $this->config->consumeType->sendGift) {
                    $sql_ = 'select from \Micro\Models\GiftLog as gl left join \Micro\Models\GiftConfigs as gc on gl.giftId = gc.id where gl.consumeLogId = ' . $v['id'];
                  } else if ($v['type'] == $this->config->consumeType->grabSeat) {
                    $sql_ = 'select from \Micro\Models\GrabLog as gl where gl.consumeLogId = ' . $v['id'];
                  } else {
                    $sql_ = 'select from \Micro\Models\GuardLog as gl left join \Micro\Models\GuardConfigs as gc on gl.giftId = gc.id where gl.consumeLogId = ' . $v['id'];
                  }
                  // $sql_ = ' limit 0,1';
                  $query_ = $this->modelsManager->createQuery($sql_);
                  $tempData_ = $query_->execute();
                  $data_ = $tempData_->toArray();
                }
                var_dump($data);die;*/
                $sqlGift = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gl.count,gc.name,gc.configName from \Micro\Models\ConsumeLog as cl '.
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
                $where = ' and cl.createTime > ' . $start . ' and cl.createTime <= ' . $end . ' and cl.anchorId = ' . $this->uid . ' ORDER BY cl.createTime DESC';
                $data = array();
                $sort = array();

                $queryGift = $this->modelsManager->createQuery($sqlGift . $where);
                $tempData = $queryGift->execute();
                $giftData = $tempData->toArray();
                foreach ($giftData as $k => &$v1) {
                  $v1['type'] = 1;
                  $v1['process'] = 1;
                  $v1['createTime'] = date('H:i:s', $v1['createTime']);
                  $data[] = $v1;
                  $sort[] = $v1['createTime'];
                }
                $querySeat = $this->modelsManager->createQuery($sqlSeat . $where);
                $tempData = $querySeat->execute();
                $seatData = $tempData->toArray();
                foreach ($seatData as $k => &$v2) {
                  $v2['type'] = 2;
                  $v2['process'] = 1;
                  $v2['createTime'] = date('H:i:s', $v2['createTime']);
                  $v2['name'] = '沙发';
                  $v2['configName'] = 'qz';
                  $data[] = $v2;
                  $sort[] = $v2['createTime'];
                }
                $queryGuard = $this->modelsManager->createQuery($sqlGuard . $where);
                $tempData = $queryGuard->execute();
                $guardData = $tempData->toArray();
                foreach ($guardData as $k => $v3) {
                  $v3['type'] = 3;
                  $v3['process'] = 1;
                  $v3['count'] = 1;
                  $v3['createTime'] = date('H:i:s', $v3['createTime']);
                  $data[] = $v3;
                  $sort[] = $v3['createTime'];
                }

                array_multisort($sort, SORT_DESC, $data);



                /*$ConsumeLog = ConsumeLog::find(
                  "createTime > '$start' AND createTime <= '$end' AND uid = " . $this->uid .
                  " AND (type = " . $this->config->consumeType->buyGuard . " OR type = " . $this->config->consumeType->grabSeat . " OR type = " . $this->config->consumeType->sendGift . ")"
                );
                $ConsumeLog = $ConsumeLog->toArray();
                $finalresult = array();
                if (!empty($ConsumeLog)) {
                  foreach ($ConsumeLog as $k => $v) {
                    if($v['type'] == $this->config->consumeType->buyGuard){
                      $sql_ = 'select consumeLogId, guardType FROM \Micro\Models\GuardLog as gl  WHERE consumeLogId';
                    }
                  }
                }
                $query = $this->modelsManager->createQuery($sql);
                $tempData = $query->execute();
                $data = $tempData->toArray();
                foreach ($data as $k => $v) {
                    $data[$k]['process'] = 1;
                    $data[$k]['createTime'] = date('H:i:s', $v['createTime']);
                }*/

                $user = UserProfiles::findFirst('uid = ' . $this->uid);
                $result['coin'] = $user->coin;
                $result['cash'] = $user->cash;
                $result['list'] = $data;

                break;
            case "sendGift":
                //送出的礼物
                /*$sql = "select a.uid,a.anchorId,a.amount,a.createTime,b.nickName,c.count,d.name,d.configName from \Micro\Models\ConsumeLog a,\Micro\Models\UserInfo b,\Micro\Models\GiftLog c,\Micro\Models\GiftConfigs d where  a.anchorId=b.uid AND a.id = c.consumeLogId AND c.giftId = d.id AND a.createTime > '$start' AND a.createTime <= '$end'  AND  a.type in(" . $this->config->consumeType->sendGift . "," . $this->config->consumeType->sendGiftByCoin . ") AND a.uid = " . $this->uid . " ORDER BY a.createTime DESC";
                $query = $this->modelsManager->createQuery($sql);
                $tempData = $query->execute();
                $data = $tempData->toArray();
                foreach ($data as $k => $v) {
                    $data[$k]['process'] = 1;
                    $data[$k]['createTime'] = date('H:i:s', $v['createTime']);
                }*/
                $normalLib = $this->di->get('normalLib');
                $configs = $normalLib->getConfigs();
                $consumeData = \Micro\Models\ConsumeDetailLog::find(
                    'uid = ' . $this->uid . ' and (type = ' . $this->config->consumeType->grabSeat . ' or type = ' . $this->config->consumeType->sendGift . ' or type = ' . $this->config->consumeType->buyGuard . ') ' . 
                    ' and createTime > ' . $start . ' and createTime <= ' . $end . ' ORDER BY createTime DESC'
                );
                $typeArr = array(
                    $this->config->consumeType->sendGift => '1',
                    $this->config->consumeType->grabSeat => '2',
                    $this->config->consumeType->buyGuard => '3'
                );
                $data = array();
                if(!empty($consumeData)){
                    foreach ($consumeData as $v) {
                        $tmp['type'] = $typeArr[$v->type];
                        $tmp['process'] = 1;
                        $tmp['count'] = ($v->type == $this->config->consumeType->buyGuard) ? 1 : $v->count;
                        $tmp['createTime'] = date('H:i:s', $v->createTime);
                        $tmp['nickName'] = $v->remark;
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
                /*die;
                $sqlGift = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gl.count,gc.name,gc.configName from \Micro\Models\ConsumeLog as cl '.
                       ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.anchorId '.
                       ' left join \Micro\Models\GiftLog as gl on gl.consumeLogId = cl.id left join \Micro\Models\GiftConfigs as gc on gl.giftId = gc.id ' .
                       ' where cl.type = ' . $this->config->consumeType->sendGift;
                $sqlSeat = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gl.count from \Micro\Models\ConsumeLog as cl '.
                       ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.anchorId '.
                       ' left join \Micro\Models\GrabLog as gl on gl.consumeLogId = cl.id ' .
                       ' where cl.type = ' . $this->config->consumeType->grabSeat;
                $sqlGuard = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gc.name,gl.guardType from \Micro\Models\ConsumeLog as cl '.
                       ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.anchorId '.
                       ' left join \Micro\Models\GuardLog as gl on gl.consumeLogId = cl.id left join \Micro\Models\GuardConfigs as gc on gl.guardType = gc.level ' .
                       ' where cl.type = ' . $this->config->consumeType->buyGuard;
                $where = ' and cl.createTime > ' . $start . ' and cl.createTime <= ' . $end . ' and cl.uid = ' . $this->uid . ' ORDER BY cl.createTime DESC';
                $data = array();
                $sort = array();

                $queryGift = $this->modelsManager->createQuery($sqlGift . $where);
                $tempData = $queryGift->execute();
                $giftData = $tempData->toArray();
                foreach ($giftData as $k => &$v1) {
                  $v1['type'] = 1;
                  $v1['process'] = 1;
                  $v1['createTime'] = date('H:i:s', $v1['createTime']);
                  $data[] = $v1;
                  $sort[] = $v1['createTime'];
                }
                $querySeat = $this->modelsManager->createQuery($sqlSeat . $where);
                $tempData = $querySeat->execute();
                $seatData = $tempData->toArray();
                foreach ($seatData as $k => &$v2) {
                  $v2['type'] = 2;
                  $v2['process'] = 1;
                  $v2['createTime'] = date('H:i:s', $v2['createTime']);
                  $v2['name'] = '沙发';
                  $data[] = $v2;
                  $sort[] = $v2['createTime'];
                }
                $queryGuard = $this->modelsManager->createQuery($sqlGuard . $where);
                $tempData = $queryGuard->execute();
                $guardData = $tempData->toArray();
                foreach ($guardData as $k => $v3) {
                  $v3['type'] = 3;
                  $v3['process'] = 1;
                  $v3['count'] = 1;
                  $v3['createTime'] = date('H:i:s', $v3['createTime']);
                  $data[] = $v3;
                  $sort[] = $v3['createTime'];
                }

                array_multisort($sort, SORT_DESC, $data);*/

                $user = UserProfiles::findFirst('uid = ' . $this->uid);
                $result['coin'] = $user->coin;
                $result['cash'] = $user->cash;// + $user->money;
                $result['list'] = $data;
                break;
            case "recharge":
                //充值记录 

                $time = strtotime('-30 day', time());

                $sql = "select b.createTime,b.totalFee,b.cashNum,b.payType,b.status,b.orderId,ui.nickName as target,ui.uid as targetUid "
                        . " from \Micro\Models\Order b "
                        . " left join \Micro\Models\UserInfo ui on ui.uid=b.receiveUid and b.receiveUid>0 "
                        . "where  b.createTime >= '{$time}' AND b.uid = " . $this->uid . " ORDER BY b.createTime DESC";
            
                $query = $this->modelsManager->createQuery($sql);
                $tempData = $query->execute();
                $data = $tempData->toArray();
                foreach ($data as $key => $val) {
                    $data[$key]['createTime'] = date('Y-m-d', $val['createTime']);
					if($val['status'] == $this->config->payStatus->success){
						$amountSum += $val['cashNum'];
					}
                    
                }

                //充值总记录
                $sum = \Micro\Models\Order::sum(
                        array("column" => "cashNum", "conditions" => " uid = " . $this->uid." and status =".$this->config->payStatus->success));
                $sum = $sum ? $sum : 0;

                $user = UserProfiles::findFirst('uid = ' . $this->uid);
                $result['coin'] = $user->coin;
                $result['amountSum'] = $amountSum;
                $result['sum'] = $sum;
                $result['cash'] = $user->cash;
                $result['list'] = $data;
                break;
            case "consumer":
                //消费记录 
                $normalLib = $this->di->get('normalLib');
                $configs = $normalLib->getConfigs();
                $consumeData = \Micro\Models\ConsumeDetailLog::find(
                    'uid = ' . $this->uid . ' and type < ' . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar . 
                    ' and createTime > ' . $start . ' and createTime <= ' . $end . ' ORDER BY createTime DESC'
                );

                $data = array();
                // $amountSum = 0;
                if(!empty($consumeData)){
                    foreach ($consumeData as $v) {
                        $tmp['type'] = $v->type;
                        $tmp['count'] = ($v->type == $this->config->consumeType->buyGuard) ? 1 : $v->count;
                        $tmp['createTime'] = date('H:i:s', $v->createTime);
                        $tmp['target'] = $v->remark;
                        $tmp['targetUid'] = $v->receiveUid;
                        $tmp['uid'] = $v->uid;
                        $tmp['amount'] = $v->amount;
                        $tmp['guardType'] = $v->itemId;
                        // $amountSum += $v->amount;
                        $tmp['name'] = $configs[$v->type][$v->itemId]['name'];
                        $tmp['configName'] = $configs[$v->type][$v->itemId]['configName'];
                        $data[] = $tmp;
                        unset($tmp);
                    }
                }

                /*$ConsumeLog = ConsumeLog::find("createTime > '$start' AND createTime <= '$end' AND uid = " . $this->uid . " AND type < " . $this->config->consumeType->coinType);
                $ConsumeLog = $ConsumeLog->toArray();
                $finalresult = array();
                if (!empty($ConsumeLog)) {
                    $data = array();
                    $amountSum = 0;
                    foreach ($ConsumeLog as $k => $v) {
                        $arr['id'] = $v['id'];
                        $arr['amount'] = $v['amount'];
                        $arr['type'] = $v['type'];
                        $arr['createTime'] = date('H:i:s', $v['createTime']);
                        $arr['anchorId'] = $v['anchorId'];
                        $amountSum += $v['amount'];
                        $data[$v['type']][$v['id']] = $arr;
                        array_push($finalresult, $arr);
                    }
                    //print_R($data);exit;
                    foreach ($data as $k => $v) {
                        $id = array();
                        foreach ($v as $key => $val) {
                            $id[] = $key;
                        }
                        $array = implode(',', $id);

                        //VIP/金喇叭/银喇叭
                        if (($k == $this->config->consumeType->buyVip) ||
                                ($k == $this->config->consumeType->sendRoomBroadcast) ||
                                ($k == $this->config->consumeType->sendAllRoomBroadcast)
                        ) {
                            foreach ($finalresult as $k => $v) {
                                if (($v['type'] == $this->config->consumeType->buyVip) ||
                                        ($v['type'] == $this->config->consumeType->sendRoomBroadcast) ||
                                        ($v['type'] == $this->config->consumeType->sendAllRoomBroadcast)
                                ) {
                                    // 这里的数量只会有一种，所以前端自己做
                                    $finalresult[$k]['count'] = 1;
                                }
                            }
                        }

                        //buyCar
                        if ($k == $this->config->consumeType->buyCar) {
                            $sql = "select a.consumeLogId,b.name,b.configName from \Micro\Models\CarLog a,\Micro\Models\CarConfigs b where  a.carId = b.id  AND a.consumeLogId in(" . $array . ")";
                            $query = $this->modelsManager->createQuery($sql);
                            $tempData = $query->execute();
                            $resultTemp = $tempData->toArray();
                            foreach ($resultTemp as $kr => $vr) {
                                $newresult[$vr['consumeLogId']]['name'] = $vr['name'];
                                $newresult[$vr['consumeLogId']]['configName'] = $vr['configName'];
                            }
                            foreach ($finalresult as $k => $v) {
                                if ($v['type'] == $this->config->consumeType->buyCar) {
                                    $finalresult[$k]['name'] = $newresult[$v['id']]['name'];
                                    $finalresult[$k]['configName'] = $newresult[$v['id']]['configName'];
                                    $finalresult[$k]['count'] = 1;
                                }
                            }
                        }

                        //守护
                        if ($k == $this->config->consumeType->buyGuard) {
                            $sql = "SELECT consumeLogId, guardType FROM \Micro\Models\GuardLog WHERE consumeLogId IN (" . $array . ")";
                            $query = $this->modelsManager->createQuery($sql);
                            $tempData = $query->execute();
                            $resultTemp = $tempData->toArray();

                            foreach ($resultTemp as $kr => $vr) {
                                $newresult[$vr['consumeLogId']]['guardType'] = $vr['guardType'];
                            }
                            foreach ($finalresult as $k => $v) {
                                if ($v['type'] == $this->config->consumeType->buyGuard) {
                                    $finalresult[$k]['guardType'] = $newresult[$v['id']]['guardType'];
                                    $finalresult[$k]['count'] = 1;
                                }
                            }
                        }

                        //抢坐grabSeat
                        if ($k == $this->config->consumeType->grabSeat) {
                            $sql = "select a.count,a.seatPos,a.consumeLogId from \Micro\Models\GrabLog a where a.consumeLogId in(" . $array . ")";
                            $query = $this->modelsManager->createQuery($sql);
                            $tempData = $query->execute();
                            $resultTemp = $tempData->toArray();
                            foreach ($resultTemp as $kr => $vr) {
                                $newresult[$vr['consumeLogId']]['seatPos'] = $vr['seatPos'];
                                $newresult[$vr['consumeLogId']]['count'] = $vr['count'];
                            }
                            foreach ($finalresult as $k => $v) {
                                if ($v['type'] == $this->config->consumeType->grabSeat) {
                                    $finalresult[$k]['seatPos'] = $newresult[$v['id']]['seatPos'];
                                    $finalresult[$k]['count'] = $newresult[$v['id']]['count'];
                                }
                            }
                        }

                        //
                        //礼物
                        if ($k == $this->config->consumeType->sendGift) {//
                            $sql = "select a.consumeLogId,b.configName,b.name,a.count from \Micro\Models\GiftLog a,\Micro\Models\GiftConfigs b where  a.giftId = b.id  AND a.consumeLogId in(" . $array . ")";
                            $query = $this->modelsManager->createQuery($sql);
                            $tempData = $query->execute();
                            $resultTemp = $tempData->toArray();
                            foreach ($resultTemp as $kr => $vr) {
                                $newresult[$vr['consumeLogId']]['name'] = $vr['name'];
                                $newresult[$vr['consumeLogId']]['configName'] = $vr['configName'];
                                $newresult[$vr['consumeLogId']]['count'] = $vr['count'];
                            }

                            foreach ($finalresult as $k => $v) {
                                if ($v['type'] == $this->config->consumeType->sendGift) {
                                    $finalresult[$k]['name'] = $newresult[$v['id']]['name'];
                                    $finalresult[$k]['configName'] = $newresult[$v['id']]['configName'];
                                    $finalresult[$k]['count'] = $newresult[$v['id']]['count'];
                                }
                            }
                        }
                        
                        //赠送VIP
                        if ($k == $this->config->consumeType->giveVip) {
                            $sql = "select a.consumeLogId,c.nickName as target,a.receiveUid as targetUid from \Micro\Models\UserGiveLog a,\Micro\Models\UserInfo c where c.uid=a.receiveUid AND a.consumeLogId>0 AND a.consumeLogId in(" . $array . ")";
                            $query = $this->modelsManager->createQuery($sql);
                            $tempData = $query->execute();
                            $resultTemp = $tempData->toArray();
                            if ($resultTemp) {
                                foreach ($resultTemp as $kr => $vr) {
                                    $newresult[$vr['consumeLogId']]['targetUid'] = $vr['targetUid'];
                                    $newresult[$vr['consumeLogId']]['target'] = $vr['target'];
                                }
                                foreach ($finalresult as $k => $v) {
                                    if ($v['type'] == $this->config->consumeType->giveVip) {
                                        // 这里的数量只会有一种，所以前端自己做
                                        $finalresult[$k]['count'] = 1;
                                        $finalresult[$k]['targetUid'] = $newresult[$v['id']]['targetUid'];
                                        $finalresult[$k]['target'] = $newresult[$v['id']]['target'];
                                    }
                                }
                            }
                        }
                        
                        //赠送座驾
                        if ($k == $this->config->consumeType->giveCar) {
                            $sql = "select a.consumeLogId,b.name,b.configName,c.nickName as target,a.receiveUid as targetUid from \Micro\Models\UserGiveLog a,\Micro\Models\CarConfigs b,\Micro\Models\UserInfo c where  a.itemId = b.id AND c.uid=a.receiveUid AND a.consumeLogId>0  AND a.consumeLogId in(" . $array . ")";
                            $query = $this->modelsManager->createQuery($sql);
                            $tempData = $query->execute();
                            $resultTemp = $tempData->toArray();
                            if ($resultTemp) {
                                foreach ($resultTemp as $kr => $vr) {
                                    $newresult[$vr['consumeLogId']]['name'] = $vr['name'];
                                    $newresult[$vr['consumeLogId']]['configName'] = $vr['configName'];
                                    $newresult[$vr['consumeLogId']]['targetUid'] = $vr['targetUid'];
                                    $newresult[$vr['consumeLogId']]['target'] = $vr['target'];
                                }
                                foreach ($finalresult as $k => $v) {
                                    if ($v['type'] == $this->config->consumeType->giveCar) {
                                        $finalresult[$k]['name'] = $newresult[$v['id']]['name'];
                                        $finalresult[$k]['configName'] = $newresult[$v['id']]['configName'];
                                        $finalresult[$k]['targetUid'] = $newresult[$v['id']]['targetUid'];
                                        $finalresult[$k]['target'] = $newresult[$v['id']]['target'];
                                        $finalresult[$k]['count'] = 1;
                                    }
                                }
                            }
                        }
                     }
                }*/
                /* $sql = "select a.amount,a.createTime,d.name,c.count,d.configName from \Micro\Models\ConsumeLog a,\Micro\Models\GiftLog c,\Micro\Models\GiftConfigs d where  a.id = c.consumeLogId AND c.giftId = d.id AND a.createTime > '$start' AND a.createTime <= '$end' AND a.uid = ".$this->uid;
                  $query = $this->modelsManager->createQuery($sql);

                  $tempData = $query->execute();
                  $data = $tempData->toArray();
                  foreach($data as $k => $v){
                  $data[$k]['createTime'] = date('H:i:s',$v['createTime']);
                  } */

                //消费总记录
                // $sum = \Micro\Models\ConsumeLog::sum(
                //                 array("column" => "amount", "conditions" => "uid = " . $this->uid . " AND type < " . $this->config->consumeType->coinType));
                $sum = \Micro\Models\ConsumeDetailLog::sum(
                                array("column" => "amount", "conditions" => "uid = " . $this->uid . " AND type < " . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar));
                $sum = $sum ? $sum : 0;
                $amountSum = \Micro\Models\ConsumeDetailLog::sum(
                                array("column" => "amount", "conditions" => "uid = " . $this->uid . " AND type < " . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar . ' and createTime > ' . $start . ' and createTime <= ' . $end));
                $amountSum = $amountSum ? $amountSum : 0;
                /*$sum = \Micro\Models\ConsumeDetailLog::find(
                    'uid = ' . $this->uid . ' and type < ' . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar . 
                    ' and createTime > ' . $start . ' and createTime <= ' . $end . ' ORDER BY createTime DESC'
                );*/

                $user = UserProfiles::findFirst('uid = ' . $this->uid);
                $result['amountSum'] = $amountSum;
                $result['sum'] = $sum;
                $result['coin'] = $user->coin;
                $result['cash'] = $user->cash;// + $user->money;
                $result['list'] = $data;//$finalresult;
                break;
            default:
            //
        }
        return $result;
    }

    //我的账单
    public function newconsumeList($type, $start = 0, $end = 0, $status = '', $p = 1, $limit = 10 ) {
        $amountSum = 0;
        $result = array();
        $offset = 0;
        switch ($type) {
            case "getGift":
                $where = '';
                if($start > 0){
                    $where .= " AND createTime > " . $start;
                }

                if($end > 0){
                    $where .= " AND createTime <= " . $end;
                }

                if($p > 0){
                    $offset = ($p - 1) * $limit;
                }

                $normalLib = $this->di->get('normalLib');
                $configs = $normalLib->getConfigs();
                $consumeData = \Micro\Models\ConsumeDetailLog::find(
                    'receiveUid = ' . $this->uid . ' and (type = ' . $this->config->consumeType->grabSeat . ' or type = ' . $this->config->consumeType->sendGift . ' or type = ' . $this->config->consumeType->buyGuard . ') ' .
                    $where . ' ORDER BY createTime DESC limit ' . $offset . ',' . $limit
                );
                $count = \Micro\Models\ConsumeDetailLog::count(
                    array('column' => 'id',
                        'conditions' => 'receiveUid = ' . $this->uid .
                            ' and (type = ' . $this->config->consumeType->grabSeat . ' or type = ' . $this->config->consumeType->sendGift . ' or type = ' . $this->config->consumeType->buyGuard . ')' .
                            $where
                    ));
                $count = $count ? $count : 0;
                $typeArr = array(
                    $this->config->consumeType->sendGift => '1',
                    $this->config->consumeType->grabSeat => '2',
                    $this->config->consumeType->buyGuard => '3'
                );
                $data = array();
                if(!empty($consumeData)){
                    foreach ($consumeData as $v) {
                        $tmp['type'] = $typeArr[$v->type];
                        $tmp['count'] = ($v->type == $this->config->consumeType->buyGuard) ? 1 : $v->count;
                        $tmp['createTime'] = date('Y-m-d H:i:s', $v->createTime);
                        $tmp['nickName'] = $v->nickName ? $v->nickName : '';
                        $tmp['guardType'] = $v->itemId;
                        $tmp['uid'] = $v->uid;
                        $tmp['anchorId'] = $v->receiveUid;
                        $tmp['amount'] = $v->amount;
                        $tmp['name'] = $configs[$v->type][$v->itemId]['name'];
                        // var_dump($configs[$v->type][$v->itemId]);
                        $tmp['configName'] = $configs[$v->type][$v->itemId]['configName'];
                        $data[] = $tmp;
                        unset($tmp);
                    }
                }

                $user = UserProfiles::findFirst('uid = ' . $this->uid);
                $result['coin'] = $user->coin;
                $result['cash'] = $user->cash;
                $result['list'] = $data;
                $result['count'] = $count;
                break;
            case "sendGift":
                $where = '';
                if($start > 0){
                    $where .= " AND createTime > " . $start;
                }

                if($end > 0){
                    $where .= " AND createTime <= " . $end;
                }

                if($p > 0){
                    $offset = ($p - 1) * $limit;
                }

                $normalLib = $this->di->get('normalLib');
                $configs = $normalLib->getConfigs();
                $consumeData = \Micro\Models\ConsumeDetailLog::find(
                    'uid = ' . $this->uid . ' and (type = ' . $this->config->consumeType->grabSeat . ' or type = ' . $this->config->consumeType->sendGift . ' or type = ' . $this->config->consumeType->buyGuard . ') ' .
                    $where . " ORDER BY createTime DESC limit $offset, $limit"
                );
                $typeArr = array(
                    $this->config->consumeType->sendGift => '1',
                    $this->config->consumeType->grabSeat => '2',
                    $this->config->consumeType->buyGuard => '3'
                );
                $data = array();
                if(!empty($consumeData)){
                    foreach ($consumeData as $v) {
                        $tmp['type'] = $typeArr[$v->type];
                        $tmp['process'] = 1;
                        $tmp['count'] = ($v->type == $this->config->consumeType->buyGuard) ? 1 : $v->count;
                        $tmp['createTime'] = date('Y-m-d H:i:s', $v->createTime);
                        $tmp['nickName'] = $v->remark;
                        $tmp['guardType'] = $v->itemId;
                        $tmp['uid'] = $v->uid;
                        $tmp['anchorId'] = $v->receiveUid;
                        $tmp['amount'] = $v->amount;
                        $tmp['name'] = $configs[$v->type][$v->itemId]['name'];
                        // var_dump($configs[$v->type][$v->itemId]);
                        $tmp['configName'] = $configs[$v->type][$v->itemId]['configName'];
                        $data[] = $tmp;
                        unset($tmp);
                    }
                }

                $count = \Micro\Models\ConsumeDetailLog::count(
                    array('column' => 'id',
                        'conditions' => 'uid = ' . $this->uid .
                            ' and (type = ' . $this->config->consumeType->grabSeat . ' or type = ' . $this->config->consumeType->sendGift . ' or type = ' . $this->config->consumeType->buyGuard . ')' .
                            $where
                    )
                );
                $count = $count ? $count : 0;

//                $sqlGift = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gl.count,gc.name,gc.configName from \Micro\Models\ConsumeLog as cl '.
//                    ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.anchorId '.
//                    ' left join \Micro\Models\GiftLog as gl on gl.consumeLogId = cl.id left join \Micro\Models\GiftConfigs as gc on gl.giftId = gc.id ' .
//                    ' where cl.type = ' . $this->config->consumeType->sendGift;
//                $sqlSeat = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gl.count from \Micro\Models\ConsumeLog as cl '.
//                    ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.anchorId '.
//                    ' left join \Micro\Models\GrabLog as gl on gl.consumeLogId = cl.id ' .
//                    ' where cl.type = ' . $this->config->consumeType->grabSeat;
//                $sqlGuard = 'select cl.uid,cl.anchorId,cl.amount,cl.createTime,ui.nickName,gc.name,gl.guardType from \Micro\Models\ConsumeLog as cl '.
//                    ' left join \Micro\Models\UserInfo as ui on ui.uid = cl.anchorId '.
//                    ' left join \Micro\Models\GuardLog as gl on gl.consumeLogId = cl.id left join \Micro\Models\GuardConfigs as gc on gl.guardType = gc.level ' .
//                    ' where cl.type = ' . $this->config->consumeType->buyGuard;
//                $where = $where . ' and cl.uid = ' . $this->uid . " ORDER BY cl.createTime DESC";
//                $data = array();
//                $sort = array();
//
//                $queryGift = $this->modelsManager->createQuery($sqlGift . $where);
//                $tempData = $queryGift->execute();
//                $giftData = $tempData->toArray();
////                $count = ConsumeLog::count($where1 . ' and uid = ' . $this->uid);
//                foreach ($giftData as $k => &$v1) {
//                    $v1['type'] = 1;
//                    $v1['process'] = 1;
//                    $v1['createTime'] = date('Y-m-d H:i:s', $v1['createTime']);
//                    $data[] = $v1;
//                    $sort[] = $v1['createTime'];
//                }
//
//                $querySeat = $this->modelsManager->createQuery($sqlSeat . $where);
//                $tempData = $querySeat->execute();
//                $seatData = $tempData->toArray();
//                foreach ($seatData as $k => &$v2) {
//                    $v2['type'] = 2;
//                    $v2['process'] = 1;
//                    $v2['createTime'] = date('Y-m-d H:i:s', $v2['createTime']);
//                    $v2['name'] = '沙发';
//                    $data[] = $v2;
//                    $sort[] = $v2['createTime'];
//                }
//
//                $queryGuard = $this->modelsManager->createQuery($sqlGuard . $where);
//                $tempData = $queryGuard->execute();
//                $guardData = $tempData->toArray();
//                foreach ($guardData as $k => $v3) {
//                    $v3['type'] = 3;
//                    $v3['process'] = 1;
//                    $v3['count'] = 1;
//                    $v3['createTime'] = date('Y-m-d H:i:s', $v3['createTime']);
//                    $data[] = $v3;
//                    $sort[] = $v3['createTime'];
//                }
//
//                array_multisort($sort, SORT_DESC, $data);
//                $res = array();
//                if($data){
//                    $res = array_slice($data, $offset, $limit);
//                }

                $user = UserProfiles::findFirst('uid = ' . $this->uid);
                $result['coin'] = $user->coin;
                $result['cash'] = $user->cash;
                $result['list'] = $data;
                $result['count'] = $count;
                break;
            case "recharge":
                //充值记录
                $where = $where1 = ' 1=1 ';
                if($start > 0){
                    $where .= " AND b.createTime > " . $start;
                    $where1 .= " AND createTime > " . $start;
                }

                if($end > 0){
                    $where .= " AND b.createTime <= " . $end;
                    $where1 .= " AND createTime <= " . $end;
                }

                /*if($status != ''){
                    $where .= " AND b.status =" . intval($status);
                    $where1 .= " AND status = " . intval($status);
                }*/

                if($p > 0){
                    $offset = ($p - 1) * $limit;
                }

                $sql = "select b.id,b.createTime,b.totalFee,b.cashNum,b.payType,b.status,b.orderId,ifnull(ui.nickName, '') as target,ifnull(ui.uid, 0) as targetUid "
                        . " from \Micro\Models\Order b "
                        . " left join \Micro\Models\UserInfo ui on ui.uid=b.receiveUid and b.receiveUid>0 "
                        . " where  {$where} AND (b.isDelete = 0 or b.isDelete is null) AND b.uid = " . $this->uid . " ORDER BY b.createTime DESC limit $offset,$limit";

                $query = $this->modelsManager->createQuery($sql);
                $tempData = $query->execute();
                $data = $tempData->toArray();
                $payType = array();
                foreach($this->config->payType as $val){
                    $payType[$val['id']] = $val['name'];
                }

                $closeTime = time() - 48 * 3600;
                foreach ($data as $key => $val) {
                    $data[$key]['createTime'] = date('Y-m-d H:i:s', $val['createTime']);
//                    if($val['status'] == $this->config->payStatus->success){
//                        $amountSum += $val['cashNum'];
//                    }

                    $data[$key]['payType'] = $payType[$val['payType']];
                    // 0-48小时之内，1-48小时之外
                    $data[$key]['isClose'] = $val['createTime'] < $closeTime ? 1 : 0;
                    $data[$key]['unit'] = $val['payType'] == 1001 ? '元宝' : '元';
                }

                $count = \Micro\Models\Order::count($where1 . " AND (isDelete = 0 or isDelete is null) AND uid = " . $this->uid);
                //充值总记录
                $sum = \Micro\Models\Order::sum(
                    array("column" => "cashNum", "conditions" => " uid = " . $this->uid." and status =".$this->config->payStatus->success));

                $amountSum = \Micro\Models\Order::sum(
                    array("column" => "cashNum", "conditions" => $where1 . " AND (isDelete = 0 or isDelete is null) AND uid = " . $this->uid." AND status =".$this->config->payStatus->success));

                $sum = $sum ? $sum : 0;
                $amountSum = $amountSum ? $amountSum : 0;
                $user = UserProfiles::findFirst('uid = ' . $this->uid);
                $result['coin'] = $user->coin;
                $result['amountSum'] = $amountSum;
                $result['sum'] = $sum;
                $result['cash'] = $user->cash;
                $result['list'] = $data;
                $result['count'] = $count;
                break;
            case "consumer":
                //消费记录
                $where = '';
                if($start > 0){
                    $where .= " AND createTime > " . $start;
                }

                if($end > 0){
                    $where .= " AND createTime <= " . $end;
                }

                if($p > 0){
                    $offset = ($p - 1) * $limit;
                }

                //消费记录
                $normalLib = $this->di->get('normalLib');
                $configs = $normalLib->getConfigs();
                $consumeData = \Micro\Models\ConsumeDetailLog::find(
                    'uid = ' . $this->uid . ' and type < ' . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar .
                    $where . " ORDER BY createTime DESC limit $offset,$limit"
                );

                $data = array();
                // $amountSum = 0;
                if(!empty($consumeData)){
                    foreach ($consumeData as $v) {
                        $tmp['type'] = $v->type;
                        $tmp['count'] = ($v->type == $this->config->consumeType->buyGuard) ? 1 : $v->count;
                        $tmp['createTime'] = date('Y-m-d H:i:s', $v->createTime);
                        $tmp['target'] = $v->remark;
                        $tmp['targetUid'] = $v->receiveUid;
                        $tmp['uid'] = $v->uid;
                        $tmp['amount'] = $v->amount;
                        $tmp['price'] = ($v->type < $this->config->consumeType->coinType ? $configs[$v->type][$v->itemId]['price'] : $configs[$v->type][$v->itemId]['coin'] );
                        // $tmp['unit'] = ($v->type < $this->config->consumeType->coinType ? '聊币' : '聊豆');
                        $tmp['guardType'] = $v->itemId;
                        // $amountSum += $v->amount;
                        $tmp['name'] = $configs[$v->type][$v->itemId]['name'];
                        $tmp['configName'] = $configs[$v->type][$v->itemId]['configName'];
                        $data[] = $tmp;
                        unset($tmp);
                    }
                }

                //消费总记录
                $sum = \Micro\Models\ConsumeDetailLog::sum(
                    array("column" => "amount", "conditions" =>  'uid = ' . $this->uid . ' and type < ' . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar));
                $sum = $sum ? $sum : 0;
                $amountSum = \Micro\Models\ConsumeDetailLog::sum(
                                array("column" => "amount", "conditions" => "uid = " . $this->uid . " AND type < " . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar . $where));
                $amountSum = $amountSum ? $amountSum : 0;
//                $res = array_slice($finalresult, $offset, $limit);
                $count = \Micro\Models\ConsumeDetailLog::count(
                    'uid = ' . $this->uid . ' and type < ' . $this->config->consumeType->coinType . ' and type != ' . $this->config->consumeType->sendStar .
                    $where
                );

                $user = UserProfiles::findFirst('uid = ' . $this->uid);
                $result['amountSum'] = $amountSum;
                $result['sum'] = $sum;
                $result['coin'] = $user->coin;
                $result['cash'] = $user->cash;
                $result['list'] = $data;
                $result['count'] = $count;
                break;
            default:
                //
        }
        return $result;
    }

    /*
     * 获取指定主播的收益比例
     * 收入分成
     * 1.新晋主播收益比例为25%
     * 2.主播等级达到口红2(12)，收益比例提升为30%。
     * 3.主播等级达到口红4(14)，收益比例提升为35%。
     * 4.主播等级达到口红5(15)，收益比例提升为40%。
     * (主播获得收益会存在小数点，保留小数点后三位）
     * @param anchorId 主播id
     * @return rate 收益比例
     */

    private function getIncomeRate($anchorId) {
        //调用gm后台设置的收益比例
        $invMgrBase = new \Micro\Frameworks\Logic\Investigator\InvMgrBase();
        $result = $invMgrBase->getAnchorRuleResult($anchorId);
        return $result / 100;

//        $rate=0.25;
//        $user = UserFactory::getInstance($anchorId);
//        if($user)
//        {
//            $userProfiles=$user->getUserInfoObject()->getUserProfiles();
//            $anchorLevel=$userProfiles['anchorLevel'];
//            if($anchorLevel>=15)
//            {
//                $rate=0.4;
//            }else if($anchorLevel>=14)
//            {
//                $rate=0.35;
//            }else if($anchorLevel>=12){
//                $rate=0.3;
//            }
//        }
//        return $rate;
    }

    /*
     * 主播结算记录列表
     * @param $uid用户id
     * @param @timeType时间类型 1：本月 2上月 3所有
     */

    // public function getUserAccountList($uid, $timeType = 1, $currentPage = 1, $pageSize = 20) {
    public function getUserAccountList($uid, $startTime, $endTime, $currentPage = 1, $pageSize = 20) {
        try {
            $list = array();
            $newResult = array();
            $count = 0; //总数
            $isend = 1; //是否最后一页
            $exp = "l.uid=" . $uid . " and l.type=1 and l.status=1";
            /*if ($timeType == 1) {//本月
                $thisMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
                $exp.=" and l.auditTime>=" . $thisMonth;
            } elseif ($timeType == 2) {//上月
                $lastMonth = strtotime(date('Y-m-01', strtotime('-1 month')));
                $thisMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
                $exp.=" and l.auditTime>=" . $lastMonth . " and l.auditTime<" . $thisMonth;
            }*/
            $exp.=" and l.auditTime>=" . $startTime . " and l.auditTime<" . ($endTime + 86400);
            $limit = $pageSize * ( $currentPage - 1);
            $field = "l.cash,l.auditTime";
            $table = "\Micro\Models\InvAccountsLog l ";
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $exp . " ORDER BY l.auditTime desc limit " . $limit . ", " . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $result = $query->execute();
            if ($result->valid()) {
                foreach ($result as $val) {
                    $data['createTime'] = date("Y年m月d日", $val->auditTime);
                    $data['notSettledCash'] = $val->cash; //可结算收益
                    $data['settledCash'] = $val->cash; //已结算收益
                    $data['remainCash'] = 0.000; //收益余额
                    array_push($list, $data);
                }
                $count = \Micro\Models\InvAccountsLog::count("uid=" . $uid . " and type=1 and status=1");
                if ($currentPage * $pageSize < $count) {
                    $isend = 0;
                }
            }
            $newResult['list'] = $list;
            $newResult['currentPage'] = $currentPage;
            $newResult['isend'] = $isend;
            return $this->status->retFromFramework($this->status->getCode('OK'), $newResult);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), 'errorMessage = ' . $e->getMessage());
        }
    }

}
