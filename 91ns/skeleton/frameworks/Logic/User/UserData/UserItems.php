<?php

namespace Micro\Frameworks\Logic\User\UserData;

use Micro\Frameworks\Logic\User\UserFactory;
use Micro\Models\CarLog;
use Micro\Models\GuardLog;
use Micro\Models\RoomUserStatus;
use Phalcon\DI\FactoryDefault;
use Micro\Models\UserItem;
use Micro\Models\VipConfigs;
use Micro\Models\CarConfigs;
use Micro\Models\Rooms;
use Micro\Models\GuardConfigs;
use Micro\Models\TypeConfig;
use Micro\Models\GuardList;
use Micro\Models\UserProfiles;
use Micro\Models\OnlineGift;
use Micro\Models\RicherConfigs;
use Micro\Frameworks\Logic\User\UserAuth\UserReg as UserReg;

class UserItems extends UserDataBase {

    protected $itemType = array(
        'vip' => 1,
        'car' => 2,
        'gift' => 3,
        'item' => 4,
    );

    public function __construct($uid) {
        parent::__construct($uid);
    }

    /**
     * @param type : 0-all,1-normal,2-car,3-guard
     */
    public function getItemList($type) {
        try {
            $result = array();
            switch ($type) {
                case 0: {
                        $result['vipList'] = $this->getVipItemList();
                        $result['carList'] = $this->getCarItemList();
                        $result['guardList'] = $this->getGuardList();
                        $result['hornList'] = $this->getUserItemList($this->config->itemConfigType->horn); //喇叭
                        $result['badgeList'] = $this->getUserItemList($this->config->itemConfigType->badge); //徽章
                        break;
                    }
                case 1: {
                        $result['vipList'] = $this->getVipItemList();
                        $result['hornList'] = $this->getUserItemList($this->config->itemConfigType->horn); //喇叭
                        break;
                    }

                case 2: {
                        $result['carList'] = $this->getCarItemList();
                        break;
                    }

                case 3: {
                        $res = $this->getGuardListNew();
                        if($res['code'] == $this->status->getCode('OK')){
                            $result['guardList'] = $res['data']['data'];
                        }else{
                            $result['guardList'] = array();
                        }
                        $result['badgeList'] = $this->getUserItemList($this->config->itemConfigType->badge); //徽章
                        break;
                    }
                case 5: {
                        $result['hornList'] = $this->getUserItemList($this->config->itemConfigType->horn); //喇叭
                        $result['badgeList'] = $this->getUserItemList($this->config->itemConfigType->badge); //徽章
                        $result['showList'] = $this->getUserItemList($this->config->itemConfigType->show); //节目卡
                        break;
                    }
                default:
                    $result['data'] = "type = " . $type;
                    return $this->status->retFromFramework($this->status->getCode('PARAM_ERROR'), $result);
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    private function getVipItemList() {
        $items = \Micro\Models\UserProfiles::findfirst("uid=" . $this->uid);
        $itemList = array();
        $vipConfigs = \Micro\Models\VipConfigs::find();
        foreach ($vipConfigs as $v) {
            $newVipConfigs[$v->level]['id'] = $v->level;
            $newVipConfigs[$v->level]['description'] = $v->description;
        }

        if ($items->level1) {//普通vip
            $itemData['itemId'] = $newVipConfigs[1]['id'];    //无用
            $itemData['itemCount'] = 1;
            $itemData['itemExpireTime'] = $items->vipExpireTime;
            $itemData['vipLevel'] = 1;
            $itemData['description'] = $newVipConfigs[1]['description'];
            array_push($itemList, $itemData);
        }

        if ($items->level6) {//至尊vip
            $itemData['itemId'] = $newVipConfigs[2]['id'];    //无用
            $itemData['itemCount'] = 1;
            $itemData['itemExpireTime'] = $items->vipExpireTime2;
            $itemData['vipLevel'] = 2;
            $itemData['description'] = $newVipConfigs[2]['description'];
            array_push($itemList, $itemData);
        }

        return $itemList;
    }


    // 获取未过期道具【vip、喇叭、徽章】=>type：1-徽章2-喇叭3-vip4-节目
    public function getPropsList($page = 1, $pageSize = 20){
        try {
            $limit = ($page - 1) * $pageSize;
            $nowTime = time();
            // 获取徽章和喇叭
            $phql = "select i.id,i.createTime,i.itemId,i.itemCount,i.status,c.name,c.configName,c.description,i.itemExpireTime,c.type from \Micro\Models\UserItem i"
                . " inner join \Micro\Models\ItemConfigs c on i.itemId=c.id"
                . " where uid= " . $this->uid . ' and itemType = ' . $this->itemType['item'] . ' and (itemExpireTime >= ' . $nowTime . " or itemExpireTime = 0) and i.itemCount > 0";
            $query = $this->modelsManager->createQuery($phql);
            $items = $query->execute();
            $itemList = array();
            if($items->valid()){
                foreach ($items as $key => $item) {
                    $itemData['itemId'] = $item->itemId;
                    $itemData['itemCount'] = $item->itemCount;
                    $itemData['name'] = $item->name;
                    $itemData['configName'] = $item->configName;
                    $itemData['description'] = $item->description;
                    $itemData['createTime'] = $item->createTime;
                    if($itemData['itemId'] == 3){//签到徽章
                        $itemData['isSignBadge'] = 1;
                    }else{
                        $itemData['isSignBadge'] = 0;
                    }
                    if ($item->itemExpireTime && ($item->itemExpireTime - $nowTime) <= 604800) {//是否即将到期
                        $expireIng = 1;
                    } else {
                        $expireIng = 0;
                    }
                    $itemData['expireIng'] =$expireIng;
                    // 下发类型
                    $itemData['type'] = 1;
                    $itemData['isFee'] = 0;
                    if(in_array($itemData['itemId'], array(1,2))){
                        $itemData['type'] = 2;
                    }else if($item->type == 3){
                        $itemData['type'] = 4;
                    }
                    $itemData['itemExpireTime'] = $item->itemExpireTime ? date('Y/m/d',$item->itemExpireTime) : '永久';
                    $sort[] = $item->createTime;
                    
                    array_push($itemList, $itemData);
                    unset($itemData);
                }
            }

            // 获取vip
            $vipItems = \Micro\Models\UserProfiles::findfirst("uid=" . $this->uid);
            $vipConfigs = \Micro\Models\VipConfigs::find();
            foreach ($vipConfigs as $v) {
                $newVipConfigs[$v->level]['id'] = $v->level;
                $newVipConfigs[$v->level]['description'] = $v->description;
            }
            if ($vipItems->level1 && $vipItems->vipExpireTime >= $nowTime) {//普通vip
                $itemData['itemId'] = $newVipConfigs[1]['id'];    //无用
                $itemData['itemCount'] = 1;
                $itemData['name'] = '普通VIP';
                $itemData['configName'] = 'VIP1';
                $itemData['itemExpireTime'] = $vipItems->vipExpireTime ? date('Y/m/d',$vipItems->vipExpireTime) : 0;
                $itemData['createTime'] = 3;
                $itemData['vipLevel'] = 1;
                $itemData['description'] = $newVipConfigs[1]['description'];
                $itemData['isSignBadge'] = 0;
                $itemData['type'] = 3;
                $itemData['isFee'] = 1;
                if (($vipItems->vipExpireTime - $nowTime) <= 604800) {//是否即将到期
                    $expireIng = 1;
                } else {
                    $expireIng = 0;
                }
                array_push($itemList, $itemData);
                $sort[] = 3;
            }
            if ($vipItems->level6 && $vipItems->vipExpireTime2 >= $nowTime) {//至尊vip
                $itemData['itemId'] = $newVipConfigs[1]['id'];    //无用
                $itemData['itemCount'] = 1;
                $itemData['name'] = '至尊VIP';
                $itemData['configName'] = 'VIP2';
                $itemData['itemExpireTime'] = $vipItems->vipExpireTime2 ? date('Y/m/d',$vipItems->vipExpireTime2) : 0;
                $itemData['createTime'] = 2;
                $itemData['vipLevel'] = 2;
                $itemData['description'] = $newVipConfigs[1]['description'];
                $itemData['isSignBadge'] = 0;
                $itemData['type'] = 3;
                $itemData['isFee'] = 2;
                if (($vipItems->vipExpireTime2 - $nowTime) <= 604800) {//是否即将到期
                    $expireIng = 1;
                } else {
                    $expireIng = 0;
                }
                $sort[] = 1;
                array_push($itemList, $itemData);
            }

            //数据排序
            array_multisort($sort, SORT_ASC, $itemList);

            //数组分页
            $returnArr = array_slice($itemList, $limit, $pageSize);

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$returnArr, 'count'=>count($itemList)));

        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取过期座驾和徽章
    public function getExpiredItems($page = 1, $pageSize = 20){
        try {
            $limit = ($page - 1) * $pageSize;
            $nowTime = time();
            // 获取座驾和徽章
            $expiredItems = \Micro\Models\UserItem::find(
                'uid= ' . $this->uid . ' and (itemExpireTime < ' . $nowTime .' and itemExpireTime > 0) '
                //排除金银喇叭
                . ' and (itemType = ' . $this->itemType['car'] . ' or (itemType = ' . $this->itemType['item'] . ' and itemId != 1 and itemId != 2)) '
                . ' order by itemExpireTime desc limit ' . $limit . ',' . $pageSize
            );

            // 座驾配置
            $sql = 'select cc.id,cc.price,cc.name,cc.configName,tc.sellStatus,tc.name as tName from \Micro\Models\CarConfigs as cc '
                . ' left join \Micro\Models\TypeConfig as tc on cc.typeId = tc.typeId and tc.parentTypeId = ' . $this->config->dbTypeConfigName->car[1];
            $query = $this->modelsManager->createQuery($sql);
            $carData = $query->execute();
            $carConfigs = array();
            foreach ($carData as $key => $val) {
                $tmp['id'] = $val->id;
                $tmp['name'] = $val->name;
                $tmp['configName'] = $val->configName;
                $tmp['sellStatus'] = intval($val->sellStatus);
                $tmp['price'] = $val->price;
                $carConfigs[$val->id] = $tmp;
                unset($tmp);
            }
            
            // 徽章配置
            $itemConfigs = array();
            $itemData = \Micro\Models\ItemConfigs::find('type = ' . $this->config->itemConfigType->badge);
            foreach ($itemData as $key => $val) {
                $tmp['id'] = $val->id;
                $tmp['name'] = $val->name;
                $tmp['configName'] = $val->configName;
                $tmp['price'] = 0.000;
                $tmp['sellStatus'] = 0;
                $itemConfigs[$val->id] = $tmp;
                unset($tmp);
            }
            $itemList = array();
            $count = 0;
            if(!empty($expiredItems)){
                foreach ($expiredItems as $k => $v) {
                    $tmp['id'] = $v->id;
                    if($v->itemType == $this->itemType['car']){
                        $tmp['configName'] = $carConfigs[$v->itemId]['configName'];
                        $tmp['name'] = $carConfigs[$v->itemId]['name'];
                        $tmp['price'] = $carConfigs[$v->itemId]['price'];
                        $tmp['sellStatus'] = $carConfigs[$v->itemId]['sellStatus'] ? 1 : 0;
                    }else{
                        $tmp['configName'] = $itemConfigs[$v->itemId]['configName'];
                        $tmp['name'] = $itemConfigs[$v->itemId]['name'];
                        $tmp['price'] = $itemConfigs[$v->itemId]['price'];
                        $tmp['sellStatus'] = 0;
                    }
                    $tmp['itemType'] = $v->itemType;
                    $tmp['itemId'] = $v->itemId;
                    $tmp['itemExpireTime'] = $v->itemExpireTime ? date('Y/m/d',$v->itemExpireTime) : 0;
                    // $tmp['createTime'] = $v->createTime ? date('Y/m/d',$v->createTime) : 0;
                    array_push($itemList, $tmp);
                    unset($tmp);
                }
                $count = \Micro\Models\UserItem::count(
                    'uid= ' . $this->uid . ' and (itemExpireTime < ' . $nowTime .' and itemExpireTime > 0) '
                    //排除金银喇叭
                    . ' and (itemType = ' . $this->itemType['car'] . ' or (itemType = ' . $this->itemType['item'] . ' and itemId != 1 and itemId != 2))'
                );
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$itemList, 'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    // 获取座驾  非过期
    public function getCarItemListNew($page = 1, $pageSize = 20){
        try {
            $limit = ($page - 1) * $pageSize;
            $nowTime = time();
            $sql = 'select a.id,a.itemId,c.sellStatus,a.status,a.itemCount,a.createTime,a.itemExpireTime,b.price,b.name as carName,b.description,b.configName,c.name as carTypeName,c.typeId,c.description as carTypeDescription '
                . ' from \Micro\Models\UserItem as a '
                . ' left join \Micro\Models\CarConfigs as b on a.itemId = b.id '
                . ' left join \Micro\Models\TypeConfig as c on b.typeId = c.typeId and c.parentTypeId = ' . $this->config->dbTypeConfigName->car[1] 
                . ' where a.itemType = ' . $this->itemType['car'] . ' and a.uid = ' . $this->uid . ' and a.itemExpireTime >= ' . $nowTime
                . ' order by a.createTime asc';
            $limitSql = ' limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql . $limitSql);
            $items = $query->execute();

            $itemList = array();
            $count = 0;

            // $buyConfigs = $this->config->buyConfigs;
            if ($items->valid()) {
                foreach ($items as $key => $item) {
                    $itemData['id'] = $item->id;
                    $itemData['itemId'] = $item->itemId;
                    $itemData['itemCount'] = $item->itemCount;
                    $itemData['itemExpireTime'] = $item->itemExpireTime ? date('Y/m/d',$item->itemExpireTime) : 0;
                    /*if ($item->itemExpireTime < $nowTime) {//如果已到期，状态为关闭
                        $status = 1;
                    } else {
                        $status = 0;
                    }*/
                    if (($item->itemExpireTime - $nowTime) <= 604800) {//是否即将到期
                        $expireIng = 1;
                    } else {
                        $expireIng = 0;
                    }
                    $itemData['sellStatus'] =$item->sellStatus;
                    $itemData['status'] =$item->status;
                    $itemData['expireIng'] =$expireIng;
                    $itemData['price'] = $item->price;
                    $itemData['carName'] = $item->carName;
                    $itemData['description'] = $item->description;
                    $itemData['configName'] = $item->configName;
                    $itemData['createTime'] = $item->createTime ? date('Y/m/d',$item->createTime) : 0;

                    $itemData['carTypeName'] = $item->carTypeName;
                    $itemData['carTypeId'] = $item->typeId;
                    $itemData['carTypeDescription'] = $item->carTypeDescription;
                    /*$itemData['buyConfigs'] = array();
                    foreach ($buyConfigs as $k => $value) {
                        // $data['buyConfigs'][$k] = $value;
                        // $data['buyConfigs'][$k]['ttlPrice'] = intval($data['price']) * intval($value['num']);
                        $tmp = array();
                        array_push($tmp, $value['days']);
                        array_push($tmp, $itemData['price'] * $value['num']);
                        array_push($tmp, $value['num']);
                        $itemData['buyConfigs'][$value['num']] = $tmp;
                        unset($tmp);
                    }*/

                    array_push($itemList, $itemData);
                }
                $count = \Micro\Models\UserItem::count(
                    'itemType = ' . $this->itemType['car'] . ' and uid = ' . $this->uid . ' and itemExpireTime >= ' . $nowTime
                );
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$itemList, 'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function deleteExpiredItems($itemIds = 0){
        try {
            $sql = 'delete from \Micro\Models\UserItem where id in (' . $itemIds . ')';
            $query = $this->modelsManager->createQuery($sql);
            $items = $query->execute();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    public function getCarItemList() {
        $phql = "SELECT a.*, b.*, c.* FROM \Micro\Models\UserItem a, \Micro\Models\CarConfigs b, \Micro\Models\TypeConfig c WHERE a.itemType = '" . $this->itemType['car'] .
                "' AND a.itemId = b.id AND b.typeId = c.typeId AND a.uid = " . $this->uid . " AND c.parentTypeId = " . $this->config->dbTypeConfigName->car[1];
        $query = $this->modelsManager->createQuery($phql);
        $items = $query->execute();

        $itemList = array();
        $buyConfigs = $this->config->buyConfigs;
        if ($items->valid()) {
            //$userItem = userItem::findFirst('uid = '.$this->uid);
            foreach ($items as $key => $item) {
                $itemData['id'] = $item->a->id;
                $itemData['itemId'] = $item->a->itemId;
                $itemData['itemCount'] = $item->a->itemCount;
                $itemData['itemExpireTime'] = $item->a->itemExpireTime;
                if ($item->a->itemExpireTime < time()) {//如果已到期，状态为关闭
                    $status = 0;
                } else {
                    $status = $item->a->status;
                }
                $itemData['status'] =$status ;
                $itemData['price'] = $item->b->price;
                $itemData['carName'] = $item->b->name;
                $itemData['description'] = $item->b->description;
                $itemData['configName'] = $item->b->configName;

                $itemData['carTypeName'] = $item->c->name;
                $itemData['carTypeId'] = $item->c->typeId;
                $itemData['carTypeDescription'] = $item->c->description;

                $itemData['buyConfigs'] = array();
                foreach ($buyConfigs as $k => $value) {
                    // $data['buyConfigs'][$k] = $value;
                    // $data['buyConfigs'][$k]['ttlPrice'] = intval($data['price']) * intval($value['num']);
                    $tmp = array();
                    array_push($tmp, $value['days']);
                    array_push($tmp, $itemData['price'] * $value['num']);
                    array_push($tmp, $value['num']);
                    $itemData['buyConfigs'][$value['num']] = $tmp;
                    unset($tmp);
                }

                // array_push($itemList, $itemData);

                //验证是否有被该用户购买过
                /* if(in_array($itemData['itemId'],(array)$userItem)){
                  $items[$key]['hascar'] = 1;
                  }
                  $itemData['hascar'] = $items[$key]['hascar'];
                 */
                array_push($itemList, $itemData);
            }
        }
        return $itemList;
    }

    // 获取守护
    public function getGuardListNew($page = 1, $pageSize = 20){
        try {
            $nowTime = time();
            $limit = ($page - 1) * $pageSize;
            $sql = 'select a.id,a.beGuardedUid,a.addTime,a.guardLevel,a.expireTime,b.name,b.description,b.carId,c.avatar,c.nickName ' 
                . ' from \Micro\Models\GuardList as a '
                . ' left join \Micro\Models\GuardConfigs as b on a.guardLevel = b.level '
                . ' left join \Micro\Models\UserInfo as c on a.beGuardedUid = c.uid '
                // . ' left join \Micro\Models\UserProfiles as up on up.uid = c.uid '
                . ' where a.guardUid = ' . $this->uid . ' and a.expireTime >= ' . $nowTime 
                . ' order by a.addTime asc ';//' limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql);
            $items = $query->execute();

            $itemList = array();
            $count = 0;
            $uidArr = array();
            if ($items->valid()) {
                foreach ($items as $k => $item) {
                    if(in_array($item->beGuardedUid, $uidArr)){
                        if ($item->guardLevel == 3) {//铂金守护
                            $uidArr[$item->beGuardedUid] = $item->beGuardedUid;
                        } else {
                            if ($item->guardLevel == 1) {//黄金守护
                                $uidArr[$item->beGuardedUid] = $item->beGuardedUid;
                            } else {
                                continue;
                            }
                        }
                    } else {
                        $uidArr[$item->beGuardedUid] = $item->beGuardedUid;
                    }
                    $itemData['id'] = $item->id;
                    $itemData['beGuardedUid'] = $item->beGuardedUid;
                    $itemData['level'] = $item->guardLevel;
                    $itemData['expireTime'] = $item->expireTime;// ? date('Y/m/d',$item->expireTime) : 0;
                    $itemData['addTime'] = $item->addTime;// ? date('Y/m/d',$item->addTime) : 0;
                    $itemData['guardName'] = $item->name;
                    $itemData['description'] = $item->description;
                    $itemData['carId'] = $item->carId;
                    $itemData['avatar'] = $item->avatar;
                    $itemData['beGuardedName'] = $item->nickName;

                    // array_push($itemList, $itemData); 
                    $itemList[$item->beGuardedUid] = $itemData;
                }
                /*$count = \Micro\Models\GuardList::count(
                    'guardUid = ' . $this->uid . ' and expireTime >= ' . $nowTime
                );*/
            }

            $count = count($itemList);

            $list = array_slice($itemList, $limit, $pageSize);
            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$list, 'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    private function getGuardList() {
//        $phql = "SELECT a.*, b.*, c.* FROM \Micro\Models\GuardList a, \Micro\Models\GuardConfigs b, \Micro\Models\UserInfo c WHERE a.guardUid = " . $this->uid .
//                " AND a.guardLevel = b.level AND a.beGuardedUid = c.uid"; 
        $phql = "SELECT a.*, b.*, c.* FROM \Micro\Models\GuardList a LEFT JOIN \Micro\Models\GuardConfigs b ON a.guardLevel = b.level LEFT JOIN  \Micro\Models\UserInfo c ON a.beGuardedUid = c.uid WHERE a.guardUid = " . $this->uid ;
        $query = $this->modelsManager->createQuery($phql);
        $items = $query->execute();

        $itemList = array();
        if ($items->valid()) {
            foreach ($items as $item) {
                $itemData['id'] = $item->a->id;
                $itemData['beGuardedUid'] = $item->a->beGuardedUid; //getNickName();
                $itemData['level'] = $item->a->guardLevel;
                $itemData['expireTime'] = $item->a->expireTime;

                $itemData['guardName'] = $item->b->name;
                $itemData['description'] = $item->b->description;
                $itemData['carId'] = $item->b->carId;
                $itemData['avatar'] = $item->c->avatar;
                $itemData['beGuardedName'] = $item->c->nickName;

                array_push($itemList, $itemData);
            }
        }
        return $itemList;
    }

    //获得用户的物品列表
    public function getUserItemList($type) {
        $phql = "select i.id,i.itemId,i.itemCount,i.status,c.name,c.configName,c.description,i.itemExpireTime from \Micro\Models\UserItem i"
         //        $phql = "select i.*,c.* from \Micro\Models\UserItem i"
                . " inner join \Micro\Models\ItemConfigs c on i.itemId=c.id"
                . " where uid= " . $this->uid . " and i.itemCount>0 and itemType=".$this->config->itemType->item." and type= " . $type;
        $query = $this->modelsManager->createQuery($phql);
        $items = $query->execute();

        $itemList = array();
        if ($items->valid()) {
            foreach ($items as $key => $item) {
                $itemData['id'] = $item->id;
                $itemData['itemId'] = $item->itemId;
                $itemData['itemCount'] = $item->itemCount;
                $itemData['name'] = $item->name;
                $itemData['configName'] = $item->configName;
                $itemData['description'] = $item->description;
                if($itemData['itemId']==3){//签到徽章
                     $itemData['isSignBadge']=1;
                }else{
                    $itemData['isSignBadge']=0;
                }
                $itemData['itemExpireTime'] = $item->itemExpireTime;
                
                array_push($itemList, $itemData);
            }
        }
        return $itemList;
    }

    //获取守护者昵称
    public function getNickName() {
        $data = array();
        $GuardList = GuardList::findFirst('guardUid = ' . $this->uid);
        if ($GuardList) {
            $userInfo = userInfo::findfirst('uid = ' . $GuardList->beGuardedUid);
            $data['nickName'] = $userInfo->nickName;
        }
        return $data;
    }

    public function getGuardData($beGuardedUid) {
        $parameters = array(
            "guardUid" => $this->uid,
            "beGuardedUid" => $beGuardedUid,
            "time" => time(),
        );
        $guardData = GuardList::findFirst(array(
                    "guardUid = :guardUid: AND beGuardedUid = :beGuardedUid: AND expireTime > :time: ",
                    "order" => "guardLevel",
                    "bind" => $parameters,
        ));

        if ($guardData) {
            $resultData = array();

            $resultData['guardUid'] = $guardData->guardUid;
            $resultData['beGuardedUid'] = $guardData->beGuardedUid;
            $resultData['level'] = $guardData->guardLevel;
            $resultData['expireTime'] = $guardData->expireTime;

            // 目前暂时不需要将守护的配置也查询出来
            return $resultData;
        }

        return NULL;
    }

    public function getGuardLevel($uid =0, $beUid = 0) {
        $resBo = \Micro\Models\GuardList::findFirst(
            'guardUid = ' . $uid . ' and beGuardedUid = ' . $beUid . ' and guardLevel = 3 and expireTime > ' . time()
        );
        if(!empty($resBo)){
            return 3;
        }

        $resGold = \Micro\Models\GuardList::findFirst(
            'guardUid = ' . $uid . ' and beGuardedUid = ' . $beUid . ' and guardLevel = 1 and expireTime > ' . time()
        );
        if(!empty($resGold)){
            return 1;
        }

        $resSilver = \Micro\Models\GuardList::findFirst(
            'guardUid = ' . $uid . ' and beGuardedUid = ' . $beUid . ' and guardLevel = 2 and expireTime > ' . time()
        );
        if(!empty($resSilver)){
            return 2;
        }

        return 0;
    }

    // 获取主播的守护列表
    public function getBeGuardedListNew($page = 1, $pageSize = 20){
        try {
            $nowTime = time();
            $limit = ($page - 1) * $pageSize;
            $sql = 'select gl.guardLevel,ui.avatar,ui.nickName,up.level3,ui.uid ' 
                . ' from \Micro\Models\GuardList as gl left join \Micro\Models\UserInfo as ui on gl.guardUid = ui.uid '
                . ' left join \Micro\Models\UserProfiles as up on up.uid = ui.uid '
                . ' where gl.beGuardedUid = ' . $this->uid . ' and gl.expireTime >= ' . $nowTime;
            $conditions = ' order by gl.guardLevel asc,up.level3 desc';//' desc limit ' . $limit . ',' . $pageSize;
            $query = $this->modelsManager->createQuery($sql . $conditions);
            $res = $query->execute();
            $count = 0;
            $data = array();
            $uidArr = array();
            if($res->valid()){
                foreach ($res as $k => $v) {
                    if(in_array($v->uid, $uidArr)){
                        if ($v->guardLevel == 3) {//铂金守护
                            $uidArr[$v->uid] = $v->uid;
                        } else {
                            if ($v->guardLevel == 1) {//黄金金守护
                                $uidArr[$v->uid] = $v->uid;
                            } else {
                                continue;
                            }
                        }
                    }else{
                        $uidArr[$v->uid] = $v->uid;
                    }
                    $tmp = array();
                    $tmp['uid'] = $v->uid;
                    $tmp['nickName'] = $v->nickName;
                    $tmp['richerLevel'] = $v->level3;
                    $tmp['guardLevel'] = $v->guardLevel;
                    $tmp['avatar'] = $v->avatar;
                    if (empty($tmp['avatar'])) {
                        $tmp['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                    }
                    $data[$v->uid] = $tmp;
                    unset($tmp);
                }
                /*//统计总数
                $count = \Micro\Models\GuardList::count(
                    'beGuardedUid = ' . $this->uid . ' and expireTime >= ' . $nowTime
                );*/
            }
            $count = count($data);

            $list = array_slice($data, $limit, $pageSize);

            return $this->status->retFromFramework($this->status->getCode('OK'), array('data'=>$list, 'count'=>$count));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    /**
     * 获取本人被守护的信息列表
     */
    public function getBeGuardedList($topCount = 6) {
        /* $phql = "SELECT a.uid, sum(a.amount) as total, b.nickName, b.avatar, c.level3 ".
          " FROM \Micro\Models\ConsumeLog a, \Micro\Models\UserInfo b, \Micro\Models\UserProfiles c, \Micro\Models\GuardList d".
          " WHERE a.uid = b.uid AND b.uid = c.uid AND c.uid = d.beGuardedUid AND d.guardUid = ".$this->uid.
          " AND d.expireTime > ".time().
          " group by a.uid having(total>0) order by total desc, a.uid desc limit ".$topCount; */
        $phql = "SELECT a.uid, sum(a.amount) as total, b.nickName, b.avatar, c.level3,d.guardLevel " .
                " FROM \Micro\Models\ConsumeLog a, \Micro\Models\UserInfo b, \Micro\Models\UserProfiles c, \Micro\Models\GuardList d" .
                " WHERE a.uid = b.uid AND b.uid = c.uid AND c.uid = d.guardUid AND d.beGuardedUid = " . $this->uid . " AND a.anchorId = " . $this->uid .
                " AND d.expireTime > " . time() .
                " group by a.uid having(total>0) order by total desc, a.uid desc limit " . $topCount;
        $query = $this->modelsManager->createQuery($phql);
        $listDatas = $query->execute();

        $resultData = array();
        if ($listDatas->valid()) {
            foreach ($listDatas as $val) {
                $data['uid'] = $val->uid;
                $data['amount'] = $val->total;
                $data['nickName'] = $val->nickName;
                $data['avatar'] = $val->avatar;
                if (empty($data['avatar'])) {
                    $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }

                $data['richerLevel'] = $val->level3;
                $data['guardLevel'] = $val->guardLevel;
                array_push($resultData, $data);
            }
        }

        return $resultData;
    }

    /**
     * 获取本人被守护的信息列表
     */
    public function getNewBeGuardedList($p = 1, $perCount = 6) {
        if($p > 1){
            $offset = ($p - 1) * $perCount;
        }else{
            $offset = 0;
        }

        $phql = "SELECT a.uid, sum(a.amount) as total, b.nickName, b.avatar, c.level3,d.guardLevel " .
            " FROM pre_consume_detail_log a, pre_user_info b, pre_user_profiles c, (select min(guardLevel) as guardLevel,guardUid,beGuardedUid,expireTime from pre_guard_list group by guardUid,beGuardedUid) d" .
            " WHERE a.uid = b.uid AND b.uid = c.uid AND c.uid = d.guardUid AND d.beGuardedUid = " . $this->uid . " AND a.receiveUid = " . $this->uid . " and a.type in (3,4,5) ".
            " AND d.expireTime > " . time() .
            " group by a.uid having(total>0) order by total desc, a.uid desc limit $offset, $perCount";
//        $query = $this->modelsManager->createQuery($phql);
//        $listDatas = $query->execute();
        $conn = $this->di->get('db');
        $listDatas = $conn->fetchAll($phql);
        $resultData = array();
        if ($listDatas) {
            foreach ($listDatas as $val) {
                $data['uid'] = $val['uid'];
                $data['amount'] = $val['total'];
                $data['nickName'] = $val['nickName'];
                $data['avatar'] = $val['avatar'];
                if (empty($data['avatar'])) {
                    $data['avatar'] = $this->pathGenerator->getFullDefaultAvatarPath();
                }

                $data['richerLevel'] = $val['level3'];
                $data['guardLevel'] = $val['guardLevel'];
                array_push($resultData, $data);
            }
        }

        // 获得总数
        $phql = "SELECT a.uid, sum(a.amount) as total, b.nickName, b.avatar, c.level3,d.guardLevel " .
            " FROM pre_consume_detail_log a, pre_user_info b, pre_user_profiles c, (select min(guardLevel) as guardLevel,guardUid,beGuardedUid,expireTime from pre_guard_list group by guardUid,beGuardedUid) d" .
            " WHERE a.uid = b.uid AND b.uid = c.uid AND c.uid = d.guardUid AND d.beGuardedUid = " . $this->uid . " AND a.receiveUid = " . $this->uid .
            " AND d.expireTime > " . time() .
            " group by a.uid having(total>0)";
        $conn = $this->di->get('db');
        $res = $conn->fetchAll($phql);
//        $query = $this->modelsManager->createQuery($phql);
//        $res = $query->execute();
        $total = count($res);
//        $total = $this->getBeGuardedCount();
        $result = array(
            'data' => $resultData,
            'count' => $total
        );

        return $result;
    }

    /**
     * 获取本人被守护的个数
     */
    public function getBeGuardedCount() {
        $count = GuardList::find("beGuardedUid = " . $this->uid . " AND expireTime > " . time() . " group by beGuardedUid,guardUid");
        return count($count);
    }

    public function getActiveCarData() {
        $phql = "SELECT a.*, b.*, c.* FROM \Micro\Models\UserItem a, \Micro\Models\CarConfigs b, \Micro\Models\TypeConfig c WHERE a.itemType = '" . $this->itemType['car'] .
                "' AND a.itemId = b.id AND b.typeId = c.typeId AND a.status = 1 AND a.uid = " . $this->uid . " AND a.itemExpireTime >= " . time() . " LIMIT 1";
        $query = $this->modelsManager->createQuery($phql);
        $items = $query->execute();

        if ($items->valid()) {
            foreach ($items as $item) {
                $itemData = array();

                $itemData['id'] = $item->a->id;
                $itemData['itemId'] = $item->a->itemId;
                $itemData['itemCount'] = $item->a->itemCount;
                $leftTime = $item->a->itemExpireTime - time();
                $itemData['itemLeftTime'] = $leftTime > 0 ? $leftTime : 0;

                $itemData['carName'] = $item->b->name;
                $itemData['description'] = $item->b->description;
                $itemData['configName'] = $item->b->configName;
                $itemData['price'] = $item->b->price;
                $itemData['hasBigCar'] = $item->b->hasBigCar;
                $itemData['positionX1'] = $item->b->positionX1;
                $itemData['positionY1'] = $item->b->positionY1;
                $itemData['positionX2'] = $item->b->positionX2;
                $itemData['positionY2'] = $item->b->positionY2;
                $itemData['sort'] = $item->b->sort;
                $itemData['appSpecial'] = $item->b->appSpecial;

                $itemData['carTypeName'] = $item->c->name;
                $itemData['roomAnimate'] = $item->c->roomAnimate;
                $itemData['typeId'] = $item->c->typeId;

                //array_push($itemList, $itemData);
                return $itemData;
            }
        }

        return NULL;
    }

    //座驾-开关
    public function updateCarStatus($carId , $status , $itemType = 'car') {
        $type = $this->itemType[$itemType];
        $car = UserItem::findfirst("uid={$this->uid} AND itemId={$carId} AND itemType='{$type}'");
        if (empty($car)) {
            return $this->status->retFromFramework($this->status->getCode('NO_FIND_THIS_CAR'));
        }
        if (time() > $car->itemExpireTime) {
            return $this->status->retFromFramework($this->status->getCode('CAR_EXPIRE_TIME_OUT'));
        }
        if ($status == $car->status) {
            return $this->status->retFromFramework($this->status->getCode('CAR_STATUS_ALREADY_BEEN'));
        }
                
        $car->status = $status;
        $result = $car->save();

        if (!$result) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }

        //关闭其他
        if (1 == $status) {
            $sql = "UPDATE \Micro\Models\UserItem SET status = 0 WHERE uid=" . $this->uid . " AND itemType='" . $type . "' AND itemId<>" . $carId;
            $query = $this->modelsManager->createQuery($sql);
            $query->execute();
        }
        return $this->status->retFromFramework($this->status->getCode('OK'));
    }

    /*
     * 购买vip
     * $buyType 1:普通vip,2:至尊vip
     * $buyTime 购买天数  
     * $receiveUid 接收的人的uid
     */
    public function buyVip($buyTime,$buyType,$receiveUid=0,$smsCode='') {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
                
        //验证参数
        $postData['buytype'] = $buyType;
        $postData['type'] = $buyTime;
        $isValid = $this->validator->validate($postData);
        if (!$isValid) {
            $errorMsg = $this->validator->getLastError();
            return $this->status->retFromFramework($this->status->getCode('VALID_ERROR'), $errorMsg);
        }
        $consumeType = $this->config->consumeType->buyVip;
        $userObject = $user;

        //如果是赠送给别人
        if ($receiveUid) {
            //如果是赠送给别人，判断手机是否绑定
            $phoneInfo = $user->getUserInfoObject()->getUserInfo();
            if (!$phoneInfo['telephone']) {
                return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
            }
            //判断赠送者的uid是否存在
            $receiveUser = UserFactory::getInstance($receiveUid);
            $receiveUserInfo = $receiveUser->getUserInfoObject()->getUserAccountInfo();
            if ($receiveUserInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('UID_ERROR'));
            }
            //判断是否是赠送给本人
            if ($receiveUid == $this->uid) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_OPER_OWNER'));
            }
            //判断验证码是否正确
//            $smsCode_right = $this->session->get($this->config->websiteinfo->user_give_vip_phone_sms);
//            $telephone = $this->session->get($this->config->websiteinfo->user_give_vip_phone);
//            $time = $this->session->get($this->config->websiteinfo->user_give_vip_phone_time);
//            if (!$smsCode_right || !$telephone || time() - $time > 180) {
//                return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//            }
//            if (!$smsCode || $smsCode_right != $smsCode) {
//                return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//            }
           
            //验证验证码是否输入正确
            //改为从数据库验证 edit by 2015/10/20
            $smsCheckResult = UserReg::checkSmsCaptcha($phoneInfo['telephone'], $this->config->sms_template->giveCode, $smsCode);
            if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
                return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
            }

            $consumeType = $this->config->consumeType->giveVip;
            $userObject = $receiveUser;

            $receiveData = $receiveUser->getUserInfoObject()->getUserInfo();
            $receiveNickname = $receiveData['nickName'];
        }else{
            $receiveNickname = '';
        }

        //判断聊币是否够
        $uid = $user->getUid();
        $userProfile = UserProfiles::findfirst("uid=" . $uid);
        $credits = $userProfile->cash;  //聊币
        //获取购买配置信息
        $vipCashConfig = $this->config->buyVipConfig[$buyType][$buyTime];
        $dataTime = $vipCashConfig[0];
        $vipcash = $vipCashConfig[1];
        if ($credits < $vipcash) {
            return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));
        }

        //更新消费记录
        // $consumeLog=$user->getUserConsumeObject()->addConsumeLog($consumeType, $vipcash);


        //获取用户信息
        $richerLevel = $userProfile->level3;                //富豪等级
        $anchorLevel = $userProfile->level2;                //主播等级
        //如果是赠送给别人
        if ($receiveUid) {
            $receiveUserProfile = UserProfiles::findfirst("uid=" . $receiveUid);
            $vipLevel = $receiveUserProfile->level1;                   //普通vip等级
            $vipExpireTime = $receiveUserProfile->vipExpireTime;         //vip过期时间
            $vipLevel2 = $receiveUserProfile->level6;                   //至尊vip
            $vipExpireTime2 = $receiveUserProfile->vipExpireTime2;      //至尊vip过期时间
        } else {
            $vipLevel = $userProfile->level1;                   //普通vip等级
            $vipExpireTime = $userProfile->vipExpireTime;         //vip过期时间
            $vipLevel2 = $userProfile->level6;                   //至尊vip
            $vipExpireTime2 = $userProfile->vipExpireTime2;      //至尊vip过期时间
        }

        if ($buyType == 1) {//普通vip
            //更新过期时间
            if (!$vipExpireTime||$vipExpireTime<time()) {
                $vipExpireTime = time();
            }
            $newExpire = strtotime("+ $dataTime day", $vipExpireTime);
            //如果是赠送给别人
            if ($receiveUid) {
                $receiveUserProfile->vipExpireTime = $newExpire;
                $receiveUserProfile->level1 = 1;
                $receiveUserProfile->save();
            } else {
                $userProfile->vipExpireTime = $newExpire;
                $userProfile->level1 = 1;
            }
            $returnTime = $newExpire;
        } elseif ($buyType == 2) {//至尊vip
            //更新过期时间
            if (!$vipExpireTime2||$vipExpireTime2<time()) {
                $vipExpireTime2 = time();
            }
            $newExpire2 = strtotime("+ $dataTime day", $vipExpireTime2);
            //如果是赠送给别人
            if ($receiveUid) {
                $receiveUserProfile->vipExpireTime2 = $newExpire2;
                $receiveUserProfile->level6 = 1;
                $receiveUserProfile->save();
            } else {
                $userProfile->vipExpireTime2 = $newExpire2;
                $userProfile->level6 = 1;
            }
            $returnTime = $newExpire2;
        }

        //判断是否是第一次购买
        if ($vipLevel > 0 || $vipLevel2 > 0) {
            $first = 0;
            if (!$vipLevel2 && $buyType == 2) {//第一次购买至尊vip
                $first = 1;
            }
        } else {
            $first = 1;
            if ($richerLevel == 0) {    // 富豪等级直升至农民1（若已经达到农民1，则无效果）
                $userProfile->level3 = 1;
            }
            if ($anchorLevel == 0) {    //主播等级直升至哨1（若已经达到哨1，则无效果）
                $userProfile->level2 = 1;
            }
        }


        //判断vip 是否送座驾
        $vipConfig = VipConfigs::findfirst('level = ' . $buyType);
        $carId = $vipConfig->carId;
        if ($carId > 0) {
            $carTime = $dataTime * 86400;//座驾赠送时间
            $userObject->getUserItemsObject()->giveCar($carId, $carTime);
        }

        $userProfile->save();
        // 获得vip过期时间
        $expireTime = $userObject->getUserInfoObject()->getUserVipExpireTime();
        // 原有vip富豪专属座驾，过期的要重新激活
        if (!$receiveUid) {
            $richerConList = \Micro\Models\RicherConfigs::find(" level<=" . $userProfile->level3);
            if ($richerConList) {
                foreach ($richerConList as $k => $v) {
                    if ($v->carId) {

                        $userObject->getUserItemsObject()->giveCar($v->carId, $expireTime - time(), 1);
                        //给用户发送通知
                        $carInfo = \Micro\Models\CarConfigs::findfirst($v->carId);
                        $content = $userObject->getUserInformationObject()->getInfoContent($this->config->informationCode->rich, array(0 => $v->name, 1 => $carInfo->name));
                        $userObject->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
                    }
                }
            }
        }



        $userDetail = $user->getUserInfoObject()->getData();
        $nickName = $userDetail['nickName'];
        $isTuo = $userDetail['internalType'] == 2 ? 1 : 0;

        // 处理消费数据
#调用存储过程Start#
        //富豪数据
        $richerData = array(
            'richer' => $user,
            'richerCash' => $vipcash,
            'richerExp' => $vipcash
        );
        //主播数据
        $anchorData = array();
        $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, $anchorData, 0, 0, 0);
        if($resCode['code'] != 'OK'){
            return $resCode;
        }
#调用存储过程End#

        $consumeLog = $user->getUserConsumeObject()->addConsumeDetailLog($consumeType, $vipcash, 0, $buyType, $buyTime, $receiveUid, $receiveNickname, 0, $nickName, $isTuo);


        //如果是赠送给别人
        if ($receiveUid) {
            //写入赠送日志表
            $this->addUserGiveLog($receiveUid, $this->config->giveType->vip, $buyType, $buyTime,$consumeLog->id);
            //给接收者发送通知
            if ($buyType == 1) {//普通vip
                $itemName = '普通vip';
            } else {
                $itemName = '至尊vip';
            }
            $paramArray = array(0 => $nickName, 1 => $uid, 2 => $itemName, 3 => $dataTime . "天");
            $content = $receiveUser->getUserInformationObject()->getInfoContent($this->config->informationCode->giveVip, $paramArray);
            $receiveUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
            //移除session
            // $this->session->remove($this->config->websiteinfo->user_give_vip_phone_sms);
            //$this->session->remove($this->config->websiteinfo->user_give_vip_phone);
            //$this->session->remove($this->config->websiteinfo->user_give_vip_phone_time);
        }


        //广播vip购买
        $roomBase = new \Micro\Frameworks\Logic\Room\RoomBase();
        $roomBase->sendBroadcastInUserRooms($userObject, 'buyvip');
        // 更新数据库数据,清除用户禁言踢人操作
        $sql = "update Micro\Models\RoomUserStatus set forbid=0,kick=0 where uid={$uid}";
        $query = $this->modelsManager->createQuery($sql);
        $query->execute();

//        $accountId = $user->getUserInfoObject()->getAccountId();
//        $roomList = $this->roomModule->getRoomMgrObject()->getUsersWhereIn($uid);
//        if($roomList){
//            foreach($roomList as $roomVal){
//                $this->comm->forbidTalk($roomVal['roomid'], $accountId, $ArraySubData);
//            }
//        }

        $data = array(
            'time' => date('Y年m月d日', $returnTime),
            'first' => $first,
            'month' => $dataTime,
        );
        $result['data'] = $data;
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    /*
     * $carId 座驾ID
     *
     */

    public function buyCar($carId,$buyTime=1,$receiveUid=0,$smsCode='') {

        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        
        $consumeType = $this->config->consumeType->buyCar;
        $userObject = $user;
        
          //如果是赠送给别人
        if ($receiveUid) {
            //如果是赠送给别人，判断手机是否绑定
            $phoneInfo = $user->getUserInfoObject()->getUserInfo();
            if (!$phoneInfo['telephone']) {
                return $this->status->retFromFramework($this->status->getCode('NO_BIND_TELEPHONE'));
            }
            //判断赠送者的uid是否存在
            $receiveUser = UserFactory::getInstance($receiveUid);
            $receiveUserInfo = $receiveUser->getUserInfoObject()->getUserAccountInfo();
            if ($receiveUserInfo == false) {
                return $this->status->retFromFramework($this->status->getCode('UID_ERROR'));
            }
            //判断是否是赠送给本人
            if ($receiveUid == $this->uid) {
                return $this->status->retFromFramework($this->status->getCode('CANNOT_OPER_OWNER'));
            }
//            //判断验证码是否正确
//            $smsCode_right = $this->session->get($this->config->websiteinfo->user_give_car_phone_sms);
//            $telephone = $this->session->get($this->config->websiteinfo->user_give_car_phone);
//            $time = $this->session->get($this->config->websiteinfo->user_give_car_phone_time);
//            if (!$smsCode_right || !$telephone || time() - $time > 180) {
//                return $this->status->retFromFramework($this->status->getCode('SMSCODE_IS_TIME_OUT'));
//            }
//            if (!$smsCode || $smsCode_right != $smsCode) {
//                return $this->status->retFromFramework($this->status->getCode('SECURITY_CODE_ERROR'));
//            }
            
            //验证验证码是否输入正确
            //改为从数据库验证 edit by 2015/10/20
                
            $smsCheckResult = UserReg::checkSmsCaptcha($phoneInfo['telephone'], $this->config->sms_template->giveCode, $smsCode);
            if ($smsCheckResult['code'] != $this->status->getCode('OK')) {
                return $this->status->retFromFramework($smsCheckResult['code'], $smsCheckResult['data']);
            }
            
            
            $consumeType = $this->config->consumeType->giveCar;
            $userObject = $receiveUser;

            $receiveData = $receiveUser->getUserInfoObject()->getUserInfo();
            $receiveNickname = $receiveData['nickName'];
        }else{
            $receiveNickname = '';
        }

        //获取座驾信息
        $carConfig = CarConfigs::findFirst($carId);

        //验证该座驾是否存在
        if (!$carConfig) {
            return $this->status->retFromFramework($this->status->getCode('CAR_NOT_EXIST'));
        }
        $carPrice = $carConfig->price;              //座驾价格
        $carPrice=$carPrice*$buyTime;

        //获取用户信息
        $userProfile = UserProfiles::findFirst("uid = " . $this->uid);
        $credits = $userProfile->cash;              //聊币
        //验证聊币是否足够
        if ($credits < $carPrice) {
            return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));
        }

        //更新消费记录
        // $consumeLog = $user->getUserConsumeObject()->addConsumeLog($consumeType, $carPrice);

        

        $carTime=$buyTime*2592000;//30*24*60*60
        
        //如果是赠送给别人
        if ($receiveUid) {
            $newExpire = $receiveUser->getUserItemsObject()->giveCar($carId,$carTime);
        } else {
            $newExpire = $this->giveCar($carId,$carTime);
        }

        // 处理消费数据
#调用存储过程Start#
        //富豪数据
        $richerData = array(
            'richer' => $user,
            'richerCash' => $carPrice,
            'richerExp' => $carPrice
        );
        //主播数据
        $anchorData = array();
        $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, $anchorData, 0, 0, 0);
        if($resCode['code'] != 'OK'){
            return $resCode;
        }
#调用存储过程End#
        /*$res_code = $user->getUserConsumeObject()->processConsumeData($user, $carPrice, $carPrice);    //这个函数包含扣除聊币
        if($res_code['code'] != 'OK'){
            return $res_code;//$this->status->retFromFramework($this->status->getCode('OPER_USER_MONEY_ERROR'));
        }*/

        $userDetail = $user->getUserInfoObject()->getData();
        $nickName = $userDetail['nickName'];
        $isTuo = $userDetail['internalType'] == 2 ? 1 : 0;

        $consumeLog = $user->getUserConsumeObject()->addConsumeDetailLog($consumeType, $carPrice, 0, $carId, $buyTime, $receiveUid, $receiveNickname, 0, $nickName, $isTuo);
        //添加购车记录
        /*$carLog = new CarLog();
        $carLog->carId = $carId;
        $carLog->consumeLogId = $consumeLog->id;
        $carLog->save();*/
        

        //如果是赠送给别人
        if ($receiveUid) {
            //写入赠送日志表
            $this->addUserGiveLog($receiveUid, $this->config->giveType->car, $carId, $buyTime,$consumeLog->id);
            //给接收者发送通知
            // $userDetail = $user->getUserInfoObject()->getUserInfo();
            $itemName = $carConfig->name;
            $expireDay = $buyTime*30;
            $paramArray = array(0 => $nickName, 1 => $this->uid, 2 => $itemName, 3 => $expireDay . "天");
            $content = $receiveUser->getUserInformationObject()->getInfoContent($this->config->informationCode->giveCar, $paramArray);
            $receiveUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);
            //移除session
            $this->session->remove($this->config->websiteinfo->user_give_car_phone_sms);
            $this->session->remove($this->config->websiteinfo->user_give_car_phone);
            $this->session->remove($this->config->websiteinfo->user_give_car_phone_time);
        }

                

        $data = array(
            'carID' => $carId,
            'time' => date('Y年m月d日', $newExpire),
        );
        $result['data'] = $data;
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    /*
     *  $guardId 购买信息索引
     *  $GuardedUid 要守护的ID
     */

    public function buyGuard($guardId, $GuardedUid, $type) {
                
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        //不能守护自己
        if ($GuardedUid == $this->uid) {
            return $this->status->retFromFramework($this->status->getCode('CANNOT_OPER_OWNER'));
        }

        //获取用户信息
        $userProfile = UserProfiles::findFirst("uid = " . $this->uid);
        $userData = $user->getUserInfoObject()->getData();
        $cash = $userProfile->cash;             //聊币
        //获取守护购买配置信息1是黄金2是白银3是铂金
        $dataTime = 0;
        $cashPrice = 0;
        if ($type == 1) {
            $guardtype = $this->config->goldGuard[$guardId];
            $dataTime = $guardtype[0] + $guardtype[2];                //时间 、天
            $cashPrice = $guardtype[1];         //购买价格,聊币
        }
        if ($type == 2) {
            $guardtype = $this->config->silverGuard[$guardId];
            $dataTime = $guardtype[0] + $guardtype[2];                //时间 、天
            $cashPrice = $guardtype[1];         //购买价格,聊币
        }

        if ($type == 3) {
            $guardtype = $this->config->boGuard[$guardId];
            $dataTime = $guardtype[0] + $guardtype[2];                //时间 、天
            $cashPrice = $guardtype[1];         //购买价格,聊币
        } 

        $cashIncome = floor($cashPrice * 0.5);

        //判断
        if($userData['internalType'] == 1){//条件：送礼者为推广员且主播收益方式为为保底底薪
            //检查推广员当日送礼额度是否足够
            $checkRes = $this->roomModule->getRoomOperObject()->checkDayCounsume($cashPrice);
            if(!$checkRes){
                return $this->status->retFromFramework($this->status->getCode('DAY_LIMIT_IS_NOT_ENOUGH'));
            }
            $salaryNum = $this->roomModule->getRoomOperObject()->checkAnchorType($GuardedUid);
            if($salaryNum === false || $salaryNum <= 0){
                return $this->status->retFromFramework($this->status->getCode('TUO_CANNOT_SEND_TO_ANCHOR'));
            }else{   
                $leftBonus = $this->roomModule->getRoomOperObject()->checkAnchorDayIncome($GuardedUid, $salaryNum);
                if($leftBonus < 0 || $leftBonus < $cashPrice){
                    return $this->status->retFromFramework($this->status->getCode('LEFT_BONUS_NOT_ENOUGH'));
                }
            }
                
        }
 
        //验证聊币是否足够
        if ($cash < $cashPrice) {
            return $this->status->retFromFramework($this->status->getCode('CASH_NOT_ENOUGH'));
        }
        
        
        $guarList = \Micro\Models\GuardList::findFirst('guardUid = ' . $this->uid . ' AND beGuardedUid = ' . $GuardedUid . 'AND guardLevel = 1'); //黄金守护
        //如果有黄金守护 不能买白银守护
        /*if ($type == 2 && $guarList != false && $guarList->expireTime > time()) {
            return $this->status->retFromFramework($this->status->getCode('ALREADY_GOLDEN_GUARDIAN'));
        }*/

#调用存储过程Start#
        //富豪数据
        $richerData = array(
            'richer' => $user,
            'richerCash' => $cashPrice,
            'richerExp' => $cashPrice
        );
        //主播数据
        $anchor = UserFactory::getInstance($GuardedUid);
        $anchorData = array(
            'anchor' => $anchor,
            'anchorCash' => $cashIncome,
            'anchorExp' => $cashIncome
        );

        $roomData= \Micro\Models\Rooms::findfirst("uid=".$GuardedUid);
        $resCode = $user->getUserConsumeObject()->dealConsumeData($richerData, $anchorData, $roomData->roomId, 0, 0);
        if($resCode['code'] != 'OK'){
            return $resCode;
        }
#调用存储过程End#

        //更新消费记录
        $income = $cashIncome;
        $familyId = 0;
        $familyResult = $this->familyMgr->getFamilyInfoByUid($GuardedUid);
        if ($familyResult['code'] == $this->status->getCode("OK")) {
            $familyId = $familyResult['data']['id'];
        }
        // $consumeLog = $user->getUserConsumeObject()->addConsumeLog($this->config->consumeType->buyGuard, $cashPrice, $income, $GuardedUid, $familyId);
        $receiveData = $anchor->getUserInfoObject()->getData();
        $receiveNickName = $receiveData['nickName'];
        $isTuo = $userData['internalType'] == 2 ? 1 : 0;
        $consumeLog = $user->getUserConsumeObject()->addConsumeDetailLog($this->config->consumeType->buyGuard, $cashPrice, $income, $type, $guardtype[3], $GuardedUid, $receiveNickName, $familyId, $userData['nickName'], $isTuo);

        //添加购买守护记录
        /*$guardLog = new GuardLog();
        $guardLog->guardType = $type;
        $guardLog->consumeLogId = $consumeLog->id;
        $guardLog->save();*/

        //该用户已经守护过
        $newExpire = strtotime(" + $dataTime day", time());
        $guarList2 = \Micro\Models\GuardList::findFirst('guardUid = ' . $this->uid . ' AND beGuardedUid = ' . $GuardedUid . ' AND guardLevel = 2');  //白银守护

        $guarList3 = \Micro\Models\GuardList::findFirst('guardUid = ' . $this->uid . ' AND beGuardedUid = ' . $GuardedUid . ' AND guardLevel = 3');  //铂金守护
        
        $first=1;//是否首次购买

        if ($type == 1) {
            // 判断是否有黄金守护
            // 如果有黄金，计算过期时间，保存
            if ($guarList) {
                $first=0;
                $oldExpire = $guarList->expireTime;
                if ($oldExpire < time()) {
                    $oldExpire = time();
                }

                $newExpire = strtotime("+ $dataTime day", $oldExpire);
                $guarList->expireTime = $newExpire;

                $guarList->save();
            } else {
                 // 如果没有黄金，新增一个黄金守护，保存
                $this->_getSave($GuardedUid, $type, $newExpire);
            }

            // 如果有白银守护并且未过期，要修改过期时间
           /**if ($guarList2) {
                $first=0;
                if ($guarList2->expireTime > time()) {
                    //
                    //$guarList2->expireTime = $dataTime + $guarList2->expireTime;
                    $guarList2->expireTime = strtotime("+ $dataTime day", $guarList2->expireTime);
                    $guarList2->save();
                }
            }**/
            //购买白银
        } else if ($type == 2) {

            
            // 如果没有黄金，判断是否有购买白银守护，
            // 如果有白银，计算过期时间，保存
            if ($guarList2) {
                $first=0;
                $oldExpire = $guarList2->expireTime;
                if ($oldExpire < time()) {
                    $oldExpire = time();
                }
                //$newExpire = $dataTime + $oldExpire;
                $newExpire = strtotime("+ $dataTime day", $oldExpire);
                $guarList2->expireTime = $newExpire;
                $guarList2->save();
            } else {
                // 如果没有白银，新增一个白银守护，保存
                $this->_getSave($GuardedUid, $type, $newExpire);
            }
        }else if($type == 3){
            if ($guarList3) {
                $first=0;
                $oldExpire = $guarList3->expireTime;
                if ($oldExpire < time()) {
                    $oldExpire = time();
                }
                //$newExpire = $dataTime + $oldExpire;
                $newExpire = strtotime("+ $dataTime day", $oldExpire);
                $guarList3->expireTime = $newExpire;
                $guarList3->save();
            } else {
                // 如果没有白银，新增一个白银守护，保存
                $this->_getSave($GuardedUid, $type, $newExpire);
            }
        }

        $guardConfigs = GuardConfigs::findFirst('level = ' . $type);
        $carId = $guardConfigs->carId;
        if ($carId) {
            $user->getUserItemsObject()->giveCar($carId, 86400 * $dataTime);
        }

        /*$user->getUserConsumeObject()->processConsumeData($user, $cashPrice, $cashPrice);    //这个函数包含扣除聊币
        // 更新主播收益
        $anchor = UserFactory::getInstance($GuardedUid);
        $user->getUserConsumeObject()->processIncomeData($anchor, $cashPrice, $cashPrice, '', $this->uid);*/
        
         //广播守护购买
        $roomData = Rooms::findFirst("uid=" . $GuardedUid);
        if ($roomData) {
            //$userData = $user->getUserInfoObject()->getData();
            $roomModule = $this->di->get('roomModule');
            $userData = $roomModule->getRoomOperObject()->setBroadcastParam($user,$GuardedUid);
            //更新userdata
            $this->comm->updateUserData($roomData->roomId, $this->uid, json_encode($userData));
            $broadData['userdata'] = $userData;
            // $broadData['guardLevel'] = $type;
            $ArraySubData['controltype'] = "guard";
            $ArraySubData['data'] = $broadData;
            //广播
            $this->comm->roomBroadcast($roomData->roomId, $ArraySubData);
            
        }


        
        /*//查询用户在哪些房间，修改UserData
        $mongo = $this->di->get('mongo');
        $collection = $mongo->collection('rooms');
        $accountId = $user->getUserInfoObject()->getAccountId();
        $userRoom = $collection->find(function($query) use($accountId) {
            $query->where('uid', $accountId);
        });
        if ($userRoom) {
            $userData = $user->getUserInfoObject()->getData();
                
            $nodejsUserData = array();
            // accountId
            // 昵称、头像
            $nodejsUserData['userId'] = $user->getUid();
            $nodejsUserData['name'] = $userData['nickName'];
            $nodejsUserData['avatar'] = $userData['avatar'];

            //是否超级管理员
            $nodejsUserData['manageType'] = $userData['manageType'];
            
            // 进入房间的用户的主播富豪等级、VIP等级
            $nodejsUserData['vipLevel'] = $userData['vipLevel'];
            $nodejsUserData['anchorLevel'] = $userData['anchorLevel'];
            $nodejsUserData['richerLevel'] = $userData['richerLevel'];
            //平台信息
            $nodejsUserData['platform'] = $this->di->get('roomModule')->getRoomOperObject()->getPlatform();
            
            // 座驾信息
            $carInfo = $user->getUserItemsObject()->getActiveCarData();
            if ($carInfo) {
                $nodejsUserData['carInfo'] = $carInfo;
            }
            foreach ($userRoom as $val) {
                 // 获取是否禁言状态
                $roomBase = new \Micro\Frameworks\Logic\Room\RoomBase();
                $nodejsUserData['isForbid'] = $roomBase->checkUserIsForbidden($val['roomid'], $user->getUid());
                // 获取守护信息
                $roomData = \Micro\Models\Rooms::findfirst($val['roomid']);
                $guardData = $user->getUserItemsObject()->getGuardData($roomData->uid);
                if ($guardData != NULL) {
                    $nodejsUserData['guardLevel'] = $guardData['level'];
                } else {
                    $nodejsUserData['guardLevel'] = '';
                }
                //用户家族信息
                $nodejsUserData['isFamilyLeader'] = $this->di->get('userMgr')->checkUserIsHeader($roomData->uid, $user->getUid());
                $this->comm->updateUserData($val['roomid'], $accountId, json_encode($nodejsUserData));
            }
        }*/
                

        //给用户发送通知
        $userInfo = \Micro\Models\UserInfo::findfirst($this->uid);
        $guardeUser = UserFactory::getInstance($GuardedUid);
        $content = $guardeUser->getUserInformationObject()->getInfoContent($this->config->informationCode->guard, array(0 => $userInfo->nickName));
        $guardeUser->getUserInformationObject()->addUserInformation($this->config->informationType->system, $content);


       //查询聊币
        $newUserProfiles= $user->getUserInfoObject()->getUserProfiles();
        $data = array(
            'buyGuard' => $GuardedUid,
            'uid' => $this->uid,
            'gcId' => $type,
            'time' => date('Y年m月d日', $newExpire),
            'first'=>$first,
            'cash'=>$newUserProfiles['cash'],
            'coin'=>$newUserProfiles['coin'],
        );
        $result['data'] = $data;
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    //
    private function _getSave($GuardedUid, $level, $newExpire) {
        $GuardList = new GuardList();
        $itemExpireTime = $newExpire;
        $GuardList->guardUid = $this->uid;
        $GuardList->beGuardedUid = $GuardedUid;
        $GuardList->guardLevel = $level;
        $GuardList->addTime = time();
        $GuardList->expireTime = $itemExpireTime;
        $GuardList->save();
    }

    /**
     * 给座驾或vip
     * @param $carId 座驾Id
     * @param $duration 购买的时长,默认一次购买都是30天60*60*24*30 =
     */
    public function giveCar($carId, $duration = 2592000, $force = 0 , $itemType = 'car') {
        // 判断当前座驾是否已购买
        $parameters = array(
            "itemType" => $this->itemType[$itemType],
            "uid" => $this->uid,
            "itemId" => $carId,
        );
        $itemData = UserItem::findFirst(array(
                    "itemType = :itemType: AND uid = :uid: AND itemId = :itemId:",
                    "bind" => $parameters,
        ));

        $currentTime = time();
        $itemExpireTime = 0;
        if ($itemData) {
            $itemExpireTime = $itemData->itemExpireTime;
            if ($itemExpireTime > $currentTime) {
                $itemExpireTime += $duration;
            } else {
                $itemExpireTime = $currentTime + $duration;
            }

            if($force > 0){
                $itemExpireTime = $currentTime + $duration;
            }

            $itemData->itemExpireTime = $itemExpireTime;
            $itemData->save();
        } else {
            $itemExpireTime = $currentTime + $duration;

            $item = new UserItem();
            $item->uid = $this->uid;
            $item->itemType = $this->itemType[$itemType];
            $item->itemId = $carId;
            $item->itemCount = 1;
            $item->itemExpireTime = $itemExpireTime;
            $item->createTime = $currentTime;
            $item->status = 0;
            $item->save();
        }

        $this->updateCarStatus($carId, 1,$itemType);
        return $itemExpireTime;
    }

    //获取在线礼物（红包或者魅力）
    public function getOnlineGift() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        //默认为0
        $leftCount = 0;
        $count = 0;
        //间隔时间
        $giftConfig = $this->getOnlineGiftConfig();
        $timeInterval = $giftConfig['interval'];
        $limit = $giftConfig['limit'];

        //获取在线礼物数量
        $gift = OnlineGift::findFirst("uid=" . $user->getUid());
        if ($gift) {
            $leftCount = $gift->leftCount;
            $count = $gift->count;
            $updateTime = $gift->updateTime;
            //如果为第二天，则重置魅力数
            $nowDate = date("ymd", time());
            $oldDate = date("ymd", $updateTime);
            if ($nowDate != $oldDate) {
                $count = 0;
                $leftCount = 0;
                $updateTime = time();
            }
            $deltTime = (time() - $updateTime);
            //计算获取到的魅力数
            $getCharm = intval($deltTime / $timeInterval);
            if ($getCharm > 0) {
                $leftCount+=$getCharm;
                $count+=$getCharm;
                //限制为10个
                if ($count > $limit) {
                    $leftCount-=($count - $limit);
                    $count-=($count - $limit);
                }
                $gift->leftCount = $leftCount;
                $gift->count = $count;
                $gift->updateTime = $updateTime + $timeInterval * $getCharm;
                $gift->save();
            }

            $leftTime = intval($deltTime % $timeInterval);
        } else {
            $gift = new OnlineGift();
            $gift->uid = $user->getUid();
            $gift->type = 1;
            $gift->leftCount = 0;
            $gift->count = 0;
            $gift->updateTime = time();
            $gift->save();
            $leftTime = 0;
        }


        $result['leftTime'] = intval($timeInterval - $leftTime);
        $result['leftCount'] = intval($leftCount);
        $result['timeInterval'] = intval($timeInterval);
        $result['hasGetCount'] = intval($gift->count);
        $result['count'] = intval($limit);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    //赠送在线礼物
    //@param $roomId 房间id
    public function sendOnlineGift($roomId) {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        $room = Rooms::findFirst("roomId=" . $roomId);
        if (!$room) {
            return $this->status->retFromFramework($this->status->getCode('ROOM_NOT_EXIST'));
        }
        $leftCount = 0;
        $count = 0;
        $limit=10;
        $userId = $user->getUid();
        //获取用户的魅力
        $gift = OnlineGift::findFirst("uid=" . $userId);
        if ($gift) {
            $leftCount = $gift->leftCount;
            $count = $gift->count;
            $updateTime = $gift->updateTime;
            //间隔时间
            $giftConfig = $this->getOnlineGiftConfig();
            $timeInterval = $giftConfig['interval'];
            $limit = $giftConfig['limit'];
            //如果为第二天，则重置魅力数
            $nowDate = date("ymd", time());
            $oldDate = date("ymd", $updateTime);
            if ($nowDate != $oldDate) {
                $count = 0;
                $leftCount = 0;
            }
            //计算获取到的魅力数
            $getCharm = intval((time() - $updateTime) / $timeInterval);
            if ($getCharm > 0) {
                $leftCount+=$getCharm;
                $count+=$getCharm;
                //限制为10个
                if ($count > $limit) {
                    $leftCount-=($count - $limit);
                    $count-=($count - $limit);
                }
                $gift->leftCount = $leftCount;
                $gift->count = $count;
                $gift->updateTime = $updateTime + $timeInterval * $getCharm;
            }
        }
    
        $result['leftCount'] = $leftCount;
        $result['count'] = $count;
        //扣除魅力值
        $userProfiles = $user->getUserInfoObject()->getUserProfiles();
        if ($leftCount <= 0) {
            if ($limit > $count) {//如果还未到上限
                return $this->status->retFromFramework($this->status->getCode('CHARM_IS_INSCREASING'),$result);
            } else if ($userProfiles['vipLevel'] >= 6) {//如果是vip大于等于6
                return $this->status->retFromFramework($this->status->getCode('CHARM_NOT_ENOUGH'),$result);
            } else {
                return $this->status->retFromFramework($this->status->getCode('CHARM_NOT_ENOUGH_NEED_VIP'),$result);
            }
        }
        $leftCount--;
        $gift->leftCount = $leftCount;
        $gift->save();
        //赠送会产生一点 富豪经验
       // $user->getUserConsumeObject()->processConsumeData($user, 0, 1, $roomId);
                
        $result['leftCount'] = $leftCount;
                
        //增加主播的粉丝经验
        $anchor = UserFactory::getInstance($room->uid);
        if (!$anchor) {
            return $this->status->retFromFramework($this->status->getCode('USER_NOT_EXIST'));
        }
        $anchorProfiles = UserProfiles::findFirst("uid=" . $room->uid);
        $fansExp = $anchorProfiles->exp5;
        if (!$fansExp)
            $fansExp = 0;
        $fansExp+=1;
        $anchorProfiles->exp5 = $fansExp;
        $anchorProfiles->save();

       
       
        ///广播

        $ArraySubData['controltype'] = 'onlineGift';
        $broadData['count'] = $fansExp;
        /**$guardData = $this->getGuardData($room->uid);
        $operuserData = $user->getUserInfoObject()->getData();
        $operuserData['guardLevel'] = $guardData['level'];
        if ($operuserData['vipExpireTime'] < time()) { //vip过期
            $operuserData['vipLevel'] = 0;
        }
        $broadData['operuserdata'] = $operuserData;**/
        $broadData['createTime'] = date("H:i");
        $roomModule = $this->di->get('roomModule');
        $userData = $roomModule->getRoomOperObject()->setBroadcastParam($user, $room->uid);
        $broadData['userdata'] = $userData;
        $ArraySubData['data'] = $broadData;
        $this->comm->roomBroadcast($roomId, $ArraySubData);

        //日常任务-送魅力星
        $taskRes = $this->taskMgr->setUserTask($userId, $this->config->taskIds->charm);
        if ($taskRes['code'] == $this->status->getCode("OK") && isset($taskRes['data']['hasreward'])) {//完成任务
            //领取奖励
            $taskRewardRes = $this->taskMgr->getNewTaskReward($this->config->taskIds->charm);
            if ($taskRewardRes['code'] == $this->status->getCode("OK")) {
                $result['taskReward'] = $taskRewardRes['data'];
            }
        }

        $userProfiles = $user->getUserInfoObject()->getUserProfiles();
        $result['richerExp'] = $userProfiles['richerExp'];
        $richerConfig = RicherConfigs::findFirst('level = ' . $userProfiles['richerLevel']);
        $result['richerHigher'] = $richerConfig ? $richerConfig->higher + 1 : 0;
        $result['richerLower'] = $richerConfig ? $richerConfig->lower : 0;
        $result['points']=$userProfiles['points'];

        //删除直播间送礼记录缓存
        $normalLib = $this->di->get('normalLib');
        $cacheKey = 'room_send_gift_' . $roomId;
        $normalLib->delCache($cacheKey);
        

        //判断是否满足增加富豪经验的条件
        $richerExp = $this->setConsumeCountLog(2, 1);
        if ($richerExp) {
            // 富豪等级经验值，是否升级，有升级要广播
            $res_code = $user->getUserConsumeObject()->dealRicherExp($user, $userId, $richerExp, $roomId);
            if($res_code['code'] != 'OK'){
                return $res_code;
            }
            // $res_code = $user->getUserConsumeObject()->processConsumeData($user, 0, $richerExp, $roomId,false);
        }

        $receiveUid = $room->uid;
        $receive =UserFactory::getInstance($receiveUid);
        $receiveData = $receive->getUserInfoObject()->getData();
        $receivenickName = $receiveData['nickName'];
        $operuserData = $user->getUserInfoObject()->getData();
        $nickName = $operuserData['nickName'];
        $isTuo = $operuserData['internalType'] == 2 ? 1 : 0;
        $consumeLog = $user->getUserConsumeObject()->addConsumeDetailLog($this->config->consumeType->sendStar, 0, 0, 0, 1, $receiveUid, $receivenickName, 0, $nickName, $isTuo);
        


        return $this->status->retFromFramework($this->status->getCode("OK"), $result);
    }

    //重置在线礼物时间
    public function resetOnlineGiftTime() {
        // 用户必须登录
        $user = $this->userAuth->getUser();
        if (!$user) {
            return $this->status->retFromFramework($this->status->getCode('SESSION_HASNOT_LOGIN'));
        }
        //获取在线礼物数量
        $gift = OnlineGift::findFirst("uid=" . $user->getUid());
        if ($gift) {
            $updateTime = $gift->updateTime;
            $count = $gift->count;
            $leftCount = $gift->leftCount;
            $nowDate = date("ymd", time());
            $oldDate = date("ymd", $updateTime);
            //隔天清理
            if ($nowDate != $oldDate) {
                $count = 0;
                $leftCount = 0;
                $updateTime = time();
            }
            $gift->count = $count;
            $gift->leftCount = $leftCount;
            $gift->updateTime = $updateTime;
            $gift->save();
        }
    }

    //获取在线礼物的相关配置（上限 limit;间隔时间 interval）
    private function getOnlineGiftConfig() {
        $giftConfig = array();
        $giftConfig['limit'] = 10;
        $giftConfig['interval'] = 600;

        $user = $this->userAuth->getUser();
        if ($user) {
            $userInfo = $user->getUserInfoObject();
            $vipLevel = $userInfo->getVipLevel();
            switch ($vipLevel) {
                case 0:
                    break;
                case 1://普通VIP在线5分钟可获取1点魅力星，每日领取上限升至30点
                    $giftConfig['limit'] = 30;
                    $giftConfig['interval'] = 300;
                    break;
                case 2://至尊VIP在线5分钟可获取1点魅力星，每日领取上限升至50点
                    $giftConfig['limit'] = 50;
                    $giftConfig['interval'] = 300;
                    break;
            }
        }
        return $giftConfig;
    }

    /*
     * 给礼物
     * giftId:礼物id,giftCount数量
     */

    public function giveGift($giftId, $giftCount = 1) {
        try {
            // 判断当前礼物数据已存在
            $parameters = array(
                "itemType" => $this->itemType['gift'],
                "uid" => $this->uid,
                "itemId" => $giftId,
            );
            $itemData = UserItem::findFirst(array(
                        "itemType = :itemType: AND uid = :uid: AND itemId = :itemId:",
                        "bind" => $parameters,
            ));
            if ($itemData) {//修改数量
                $itemData->itemCount += $giftCount;
                $itemData->save();
            } else {//新增数据
                $item = new UserItem();
                $item->uid = $this->uid;
                $item->itemType = $this->itemType['gift'];
                $item->itemId = $giftId;
                $item->itemCount = $giftCount;
                $item->itemExpireTime = 0;
                $item->createTime = time();
                $item->status = 1;
                $item->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //给物品
    public function giveItem($itemId, $itemCount = 1,$duration = 0,$roomId=0) {
        try {
            //如果是送经验
            if ($itemId == 15) {
                $userObject = UserFactory::getInstance($this->uid);
                $result = $userObject->getUserConsumeObject()->dealRicherExp($userObject, $this->uid, $itemCount, $roomId, 0);
                return $result;
            }

            // 判断当前物品数据已存在
            $parameters = array(
                "itemType" => $this->itemType['item'],
                "uid" => $this->uid,
                "itemId" => $itemId,
            );
            $itemData = UserItem::findFirst(array(
                        "itemType = :itemType: AND uid = :uid: AND itemId = :itemId:",
                        "bind" => $parameters,
            ));
            $currentTime = time();
            $itemExpireTime=0;
            if ($itemData) {//修改数量
                if ($duration) {
                    $itemExpireTime = $itemData->itemExpireTime;
                    if ($itemExpireTime > $currentTime) {
                        $itemExpireTime += $duration;
                    } else {
                        $itemExpireTime = $currentTime + $duration;
                    }
                }
                if ($itemId < 4 || in_array($itemId, array(17,18,19,20,21))) {//签到徽章或喇叭数量可累计或者节目卡
                    $itemData->itemCount += $itemCount;
                } else {
                    $itemData->itemCount = 1;
                }
                $itemData->itemExpireTime = $itemExpireTime;
                $itemData->save();
            } else {//新增数据
                if($duration){
                     $itemExpireTime = $currentTime + $duration;
                }
                $item = new UserItem();
                $item->uid = $this->uid;
                $item->itemType = $this->itemType['item'];
                $item->itemId = $itemId;
                $item->itemCount = $itemCount;
                $item->itemExpireTime = $itemExpireTime;
                $item->createTime = time();
                $item->status = 1;
                $item->save();
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //撤销徽章
    public function cancelItem($itemId = 0){
        try {
            // 判断当前物品数据已存在
            $parameters = array(
                "itemType" => $this->itemType['item'],
                "uid" => $this->uid,
                "itemId" => $itemId,
            );
            $itemData = UserItem::findFirst(array(
                "itemType = :itemType: AND uid = :uid: AND itemId = :itemId:",
                "bind" => $parameters,
            ));

            if ($itemData) {
                $sql = 'delete from pre_user_item where uid = ' . $this->uid . ' and itemId = ' . $itemId . ' and itemType = ' . $this->itemType['item'];
                $connection = $this->di->get('db');
                $connection->execute($sql);
            }

            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //会员领取金玫瑰
    public function getVipRose() {
        $roseId = 35; //金玫瑰的id
        $roseCount = 0; //金玫瑰的数量
        try {
            //查询是否是vip
            $userInfo = \Micro\Models\UserProfiles::findfirst("uid=" . $this->uid);
            if (($userInfo->level1 > 0 && $userInfo->vipExpireTime > time()) || ($userInfo->level6 > 0 && $userInfo->vipExpireTime2 > time())) {//是vip
                $task = new \Micro\Frameworks\Logic\Task\TaskData();
                if ($userInfo->level6 > 0 && $userInfo->vipExpireTime2 > time()) {//至尊VIP每日可免费领取专属礼物“金玫瑰”20个
                    $taskInfo2 = $task->getOneTaskStatus($this->config->taskIds->vipReward2, $this->uid);
                    if ($taskInfo2['status'] == $this->config->taskStatus->done) {//未领取
                        $roseCount += 20;
                        $taskres=$task->editUserTask($this->uid, $this->config->taskIds->vipReward2, $this->config->taskStatus->received); //修改任务状态
                        if ($taskres['code'] != $this->status->getCode("OK")) {//已领取
                             return $this->status->retFromFramework($taskres['code'],$taskres['data']);
                        }
                    }
                }
                if ($userInfo->level1 > 0 && $userInfo->vipExpireTime > time()) {//普通VIP每日可免费领取专属礼物“金玫瑰”10个
                    $taskInfo1 = $task->getOneTaskStatus($this->config->taskIds->vipReward1, $this->uid);
                    if ($taskInfo1['status'] == $this->config->taskStatus->done) {//未领取
                        $roseCount += 10;
                        $taskres = $task->editUserTask($this->uid, $this->config->taskIds->vipReward1, $this->config->taskStatus->received); //修改任务状态
                        if ($taskres['code'] != $this->status->getCode("OK")) {//已领取
                            return $this->status->retFromFramework($taskres['code'], $taskres['data']);
                        }
                    }
                }
                if ($roseCount) {
                    $userItem = \Micro\Models\UserItem::findfirst("uid=" . $this->uid . " and itemType=" . $this->itemType['gift'] . " and itemId=" . $roseId);
                    if ($userItem != false) {
                        $userItem->itemCount += $roseCount;
                        $userItem->createTime = time();
                        $userItem->save();
                    } else {
                        $item = new UserItem();
                        $item->uid = $this->uid;
                        $item->itemType = $this->itemType['gift'];
                        $item->itemId = $roseId;
                        $item->itemCount = $roseCount;
                        $item->itemExpireTime = 0;
                        $item->createTime = time();
                        $item->status = 1;
                        $item->save();
                    }
                    $return['count'] = $roseCount;
                    $return['name'] = "金玫瑰";
                    $return['configName'] = "jmg";
                    $return['id'] = $roseId;
                    $return['type'] = $this->config->itemType->gift;
                    return $this->status->retFromFramework($this->status->getCode('OK'), $return);
                } else {
                    return $this->status->retFromFramework($this->status->getCode('HAS_GET_REWARD'));
                }
            } else {//不是vip
                return $this->status->retFromFramework($this->status->getCode('NOT_ENOUGH_VIPLEVEL'));
            }
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //记录聊币、魅力星数量,判断是否可以涨对应经验值
    public function setConsumeCountLog($type, $count) {
        $equalCash = 0;
        if (!$type || !$count || !$this->uid) {
            return $equalCash;
        }
        $remainCount = $count;
        $array = array('1' => array('colume' => 'sendCoin', 'limit' => 100, 'equalCash' => 10), //每送出100个聊豆，用户富豪经验相应增长（相当于消费10聊币）
            '2' => array('colume' => 'sendStar', 'limit' => 100, 'equalCash' => 1000), //每送出100个魅力，用户富豪经验相应增长（相当于消费1000聊币）
            '3' => array('colume' => 'receiveCoin', 'limit' => 100, 'equalCash' => 5)); //每收到100个聊豆，主播经验相应增长（相当用户为该主播消费5聊币）
        $consumeCountLog = \Micro\Models\ConsumeCountLog::findfirst("uid=" . $this->uid);
        if ($consumeCountLog == false) {//新增记录
            if ($count >= $array[$type]['limit']) {//达到可送经验值的条件
                $equalCash = floor($count / $array[$type]['limit']) * $array[$type]['equalCash']; //换算成对应的聊币
                $remainCount = $count - floor($count / $array[$type]['limit']) * $array[$type]['limit'];
            }
            //写入数据库
            $new = new \Micro\Models\ConsumeCountLog();
            $new->uid = $this->uid;
            $new->$array[$type]['colume'] = $remainCount;
            $new->save();
            return $equalCash;
        } else {//修改记录
            $count = $consumeCountLog->$array[$type]['colume'] + $count; //加上原来的数量
            $remainCount = $count;
            if ($count >= $array[$type]['limit']) {//达到可送经验值的条件
                $equalCash = floor($count / $array[$type]['limit']) * $array[$type]['equalCash']; //换算成对应的聊币
                $remainCount = $count - floor($count / $array[$type]['limit']) * $array[$type]['limit'];
            }
            $consumeCountLog->$array[$type]['colume'] = $remainCount;
            $consumeCountLog->save();
            return $equalCash;
        }
    }
    
    //给用户礼包
    public function giveGiftPackage($packageId,$roomId=0) {
        try {
            $packageConfigInfo = \Micro\Models\GiftPackageConfigs::findfirst($packageId);
            if ($packageConfigInfo != false) {
                $items = json_decode($packageConfigInfo->items, true); //物品
                $uid = $this->uid;
                foreach ($items as $vi) {
                    switch ($vi['type']) {
                        case $this->config->itemType->vip://给vip
                            $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
                            $userCash->addUserVipTime($vi['id'], $vi['validity'], $uid);
                            break;
                        case $this->config->itemType->gift://给礼物
                            $this->giveGift($vi['id'], $vi['num']);
                            break;
                        case $this->config->itemType->car://给座驾
                            $this->giveCar($vi['id'], $vi['validity']);
                            break;
                        case $this->config->itemType->item://给道具
                            $this->giveItem($vi['id'], $vi['num'], $vi['validity'],$roomId);
                            break;
                        default :
                            $userCash = new \Micro\Frameworks\Logic\User\UserData\UserCash();
                            //送聊币
                            if (isset($vi['cash'])) {
                                $userCash->addUserCash($vi['cash'], $uid);
                                //添加聊币记录
                                $userCash->addCashLog($vi['cash'], $this->config->cashSource->gitfPackage, $packageId, $uid);
                            }
                            //送聊豆
                            isset($vi['coin']) && $userCash->sendUserCoin($vi['coin'], $uid);
                            //送积分
                            if (isset($vi['points'])) {
                                $user = UserFactory::getInstance($uid);
                                $user->getUserItemsObject()->addPoints($vi['points'], $this->config->pointsType->reg);
                            }
                            break;
                    }
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }
    
    //查询用户徽章情况
    public function getUserBadge($itemId=0) {
        try {
            $return = array();
            $time = time();
            $sql = "select ic.id,ui.itemCount,ic.configName,ic.name "
                    . "from \Micro\Models\UserItem ui "
                    . "inner join \Micro\Models\ItemConfigs ic on ui.itemId=ic.id "
                    . "where ui.uid=" . $this->uid . " and  ui.itemType=" . $this->config->itemType->item . " and ic.type=2 ";
            $sql.=" and ( ui.itemExpireTime=0 or ui.itemExpireTime>" . $time . ")";
            if ($itemId) {
                $sql.=" and ui.itemId=" . $itemId;
            }
            $query = $this->modelsManager->createQuery($sql);
            $list = $query->execute();
            if ($list->valid()) {
                 foreach ($list as $key => $val) {
                     $data['name'] = $val->name;
                     if ($val->id == 3) {//签到专属徽章
                         $data['configName'] = $val->configName.$val->itemCount;
                    } else {
                        $data['configName'] = $val->configName;
                    }
                    array_push($return, $data);
                }
            }
            return $this->status->retFromFramework($this->status->getCode('OK'), $return);
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

    //查询用户签到徽章等级
    public function getUserSignLevel() {
        $signInfo = \Micro\Models\UserItem::findfirst("uid=" . $this->uid . " and itemType=" . $this->config->itemType->item . " and itemId=3");
        $signLevel = $signInfo != false ? $signInfo->itemCount : 0;
        return $signLevel;
    }
    
    //添加赠送日志
    public function addUserGiveLog($receiveUid, $type, $itemId, $itemTime = '', $consumeLogId = 0) {
        $log = new \Micro\Models\UserGiveLog();
        $log->uid = $this->uid;
        $log->type = $type;
        $log->itemId = $itemId;
        $log->itemTime = $itemTime;
        $log->receiveUid = $receiveUid;
        $log->createTime = time();
        $log->consumeLogId = $consumeLogId;
        $log->save();
        return;
    }
    
 
    
    //给用户增加积分 add by 2015/11/30
    public function addPoints($getPoints=0,$type=1) {
        try {
            //更新积分
            $upPointsSql = 'update pre_user_profiles set points = points + ' . $getPoints . ' where uid = ' . $this->uid;
            $connection = $this->di->get('db');
            $connection->execute($upPointsSql);
            //积分日志
            $newPoints = new \Micro\Models\PointsLog();
            $newPoints->uid = $this->uid;
            $newPoints->points = $getPoints;
            $newPoints->type = $type;
            $newPoints->createTime = time();
            $newPoints->save();
            return $this->status->retFromFramework($this->status->getCode('OK'));
        } catch (\Exception $e) {
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'), $e->getMessage());
        }
    }

}
