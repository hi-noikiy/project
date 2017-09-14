<?php
namespace Micro\Frameworks\Logic\User\UserData;

use Phalcon\DI\FactoryDefault;

class UserFocusDb extends UserDataBase
{
    protected $collection;

    public function __construct($uid)
    {
        parent::__construct($uid);

        $this->mongo = $this->di->get('mongo');
        $this->collectionFollow = $this->mongo->collection('follow');
        $this->collectionOwnFollow = $this->mongo->collection('ownfollow');

        $indexes = $this->collectionFollow->listIndexes();
        /*if(!$this->isIndexExist($indexes))
        {   
            // $this->collectionFollow->indexes(function($index){
            //     $index->create(array('userid' => 1));
            // });               
        }*/
    }

    protected function isIndexExist($indexes)
    {
        foreach ($indexes as $key => $value) {
            foreach ($value as $k => $v) {
                if( $k == 'name' && $v == 'userid_1' )
                    return true;
            }
        }
        return false;
    }

    protected function checkIsOwnOper($operUid) {
        if ($this->uid === $operUid)
            return true;
        return false;
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 被关注集合，操作接口
    //
    ////////////////////////////////////////////////////////////////////////

    /**
     * 添加被关注记录
     * @param uid 被关注的用户uid
     * @param fid 关注的用户uid
     * @param extFid 关注的用户在外部模块的uid
     * @param cohesion 亲密度，默认为0
     * @return 返回操作结果
     */
    protected function addFollowDb($uid, $fid, $extFid, $time, $cohesion=0) {
        // $result = mongoInstance()->update(TAB_FOLLOW_COLLECTION, 
        //                             array("uid" => $uid), 
        //                             array('$addToSet' => array("fids"=> array("fid" => $fid, 
        //                                                                       "extfid" => $extFid,
        //                                                                       "cohesion" => $cohesion,
        //                                                                       "time" => $time))),
        //                             array('upsert'=>1));

        $result = $this->collectionFollow->update( function($query) use($uid, $fid, $extFid, $time, $cohesion){
            $query->upsert();
            $query->where('uid', $uid)
                  ->set('uid', $uid)
                  ->addToSet( 'fids', array('fid'       =>$fid,
                                            'extfid'    =>$extFid,
                                            'cohesion'  =>$cohesion,
                                            'time'      =>$time));
        });

        if ($result) {
            return $this->status->getCode('OK');
        }
        return $this->status->getCode('DB_OPER_ERROR');
    }

    /**
     * 删除被关注记录
     * @param uid 当前被关注的用户uid
     * @param fid 关注的用户uid
     * @return 返回操作结果$FollowErrorCode[]
     */
    protected function delFollowDb($uid, $fid) {
        // $result = mongoInstance()->update(TAB_FOLLOW_COLLECTION, 
        //                                 array("uid" => $uid), 
        //                                 array('$pull' => array("fids"=>array("fid" => $fid))));

        $result = $this->collectionFollow->update( function($query) use($uid, $fid){
        $query->where('uid', $uid)
              ->pull('fids', array("fid" => $fid));
        });
        if ($result) {
            return $this->status->getCode('OK');
        }
        return $this->status->getCode('DB_OPER_ERROR');
    }

    /**
     * 获取被关注人数
     * @param uid 当前被关注的用户uid
     * @return int 数量
     */
    protected function getFollowCountDb($uid) {
        //$result = mongoInstance()->find_one(TAB_FOLLOW_COLLECTION, array("uid"=>$uid));
        $result = $this->collectionFollow->findOne(function($query) use($uid) {
            $query->where("uid", $uid);
        });
        $count = 0;
        if ($result != NULL) {
            $count = count($result["fids"]);
        }
        return $count;
    }

    /**
     * 根据时间获取被关注人数
     * @param uid 当前被关注的用户uid
     * @param time 大于或者小于该时间
     * @return int 数量
     */
    protected function getFollowCountByTimeDb($uid, $time, $isBefore=false) {
        $result = $this->collectionFollow->findOne(function($query) use($uid, $time, $isBefore) {
            if ($isBefore) {
                $query->where("uid", $uid)->andWhere("fids.time", array('$lt' => $time));
            }
            else {
                $query->where("uid", $uid)->andWhere("fids.time", array('$gt' => $time));
            }
        });
        $count = 0;
        if ($result != NULL) {
            $count = count($result["fids"]);
        }
        return $count;
    }

    /**
     * 根据时间间隔获取被关注人数
     * @param uid 当前被关注的用户uid
     * @param beginTime 大于该时间
     * @param endTime 小于该时间
     * @return int 数量
     */
    protected function getFollowCountBetweenTimeDb($uid, $beginTime, $endTime) {
        $result = $this->collectionFollow->findOne(function($query) use($uid, $beginTime, $endTime) {
            $query->where("uid", $uid)->andWhere("fids.time", array('$gt' => $beginTime, '$lt' => $endTime));
        });
        $count = 0;
        if ($result != NULL) {
            $count = count($result["fids"]);
        }
        return $count;
    }

    /**
     * 获取所有的被关注的信息
     */
    /*protected function getAllFollowListDb($beginTime, $endTime) {
        $result = array();
        $cursor = $this->collectionFollow->find(function($query) use($beginTime, $endTime) {
            $query->where("fids.time", array('$gt' => $beginTime, '$lt' => $endTime));
        });
        while($ret=$cursor->getNext()){
            //$result[] = $ret;
            array_push($result, $ret);
        }
        //$result = $this->collectionFollow->findOne();

        $resultData = array();
        if ($result != NULL) {
            if (count($result) > 0) {
                for ($i=0; $i<count($result); $i++) {
                    $data['accountId'] = $result[$i]['uid'];
                    $data['count'] = count($result[$i]['fids']);
                    $resultData[$data['accountId']] = $data;
                }
            }
        }
        return $resultData;
    }*/

    /**
     * 获取当前用户被关注的信息列表
     * @param uid 用户id
     * @return json字符串，uid和关注者id信息列表
     */
    protected function getFollowListDb($uid) {
        //$arrayResult = mongoInstance()->find(TAB_FOLLOW_COLLECTION, array("uid"=>$uid), array());
        $arrayResult = $this->collectionFollow->findOne(function($query) use($uid) {
            $query->where("uid", $uid);
        });
        return $arrayResult;
    }

    /**
     * 根据时间获取被关注信息列表
     * @param uid 用户id
     * @param timesort 按时间排序方式 1 正序，-1 倒序
     * @param skip 分页用，跳过指定个数
     * @param limit 分页用，获取指定个数记录
     * @return 返回mongodb聚合方式得到的结果
     */
    protected function getFollowListExDb($uid, $timesort='', $skip='', $limit='')
    {
        $cond = array( array('$match' => array('uid' => $uid )),
                       array('$unwind'=>'$fids'));
        if ($timesort !== '') {
            array_push($cond, array('$sort' => array('fids.time' => $timesort)));
        }
        if ($skip !== '') {
            array_push($cond, array('$skip' => $skip));
        }
        if ($limit !== '') {
            array_push($cond, array('$limit' => $limit));
        }

        //$arrayResult = mongoInstance()->aggregate(TAB_FOLLOW_COLLECTION, $cond);
        $arrayResult = $this->collectionFollow->aggregate($cond);
        return $arrayResult;
    }

    /**
     * 判断当前用户是否已被fid关注
     * @param uid 当前被关注的用户uid
     * @param fid 关注的用户uid
     * @return bool 返回操作结果是否存在
     */
    protected function isFollowDb($uid, $fid) {
// $result = mongoInstance()->find_one(TAB_FOLLOW_COLLECTION, 
//                                 array("uid"=>$uid, "fids.fid" =>array('$in' => array($fid))), 
//                                 array());
        $result = $this->collectionFollow->findOne(function($query) use($uid, $fid) {
            $query->where("uid", $uid)->andWhere("fids.fid", array('$in' => array($fid)));
        });

        $count = count($result);
        if ($count > 0)
            return true;

        return false;
    }

    ////////////////////////////////////////////////////////////////////////
    //
    // 关注集合，操作接口
    //
    ////////////////////////////////////////////////////////////////////////

    /**
     * 添加关注记录
     * @param uid 关注的用户uid
     * @param fid 被关注的用户uid
     * @return 返回操作结果$FollowErrorCode[]
     */
    protected function addOwnFollowDb($uid, $fid, $extFid, $time, $eachFollow=0, $focus=0) {
        // $result = mongoInstance()->update(TAB_OWN_FOLLOW_LIST_COLLECTION, 
        //                             array("uid" => $uid), 
        //                             array('$addToSet' => array("fids"=> array("fid" => $fid, 
        //                                                                       "extfid" => $extFid,
        //                                                                       "focus" => $focus,
        //                                                                       "eachfollow" => $eachFollow,
        //                                                                       "userdata" => "",
        //                                                                       "time" => $time))),
        //                             array('upsert'=>1));

        $result = $this->collectionOwnFollow->update( function($query) use($uid, $fid, $extFid, $focus, $eachFollow, $time){
            $query->upsert();
            $query->where('uid', $uid)
                  ->set('uid', $uid)
                  ->addToSet( 'fids', array('fid'       =>$fid,
                                            'extfid'    =>$extFid,
                                            'focus'     =>$focus,
                                            'eachfollow'=>$eachFollow,
                                            "userdata" => "",
                                            'time'      =>$time));
        });

        if ($result) {
            return $this->status->getCode('OK');
        }
        return $this->status->getCode('DB_OPER_ERROR');
    }

    /**
     * 删除关注记录
     * @param uid 当前关注的用户uid
     * @param fid 被关注的用户uid
     * @return 返回操作结果$FollowErrorCode[]
     */
    protected function delOwnFollowDb($uid, $fid) {
        // $result = mongoInstance()->update(TAB_OWN_FOLLOW_LIST_COLLECTION, 
        //                                 array("uid" => $uid), 
        //                                 array('$pull' => array("fids"=>array("fid" => $fid))));
        $result = $this->collectionOwnFollow->update( function($query) use($uid, $fid){
        $query->where('uid', $uid)
              ->pull('fids', array("fid" => $fid));
        });
        if ($result) {
            return $this->status->getCode('OK');
        }
        return $this->status->getCode('DB_OPER_ERROR');
    }

    /**
     * 判断当前用户是否已关注fid
     * @param uid 当前关注的用户uid
     * @param fid 被关注的用户uid
     * @return bool 返回操作结果是否存在
     */
    protected function isOwnFollowDb($uid, $fid) {
        // $result = mongoInstance()->find_one(TAB_OWN_FOLLOW_LIST_COLLECTION, 
        //                                 array("uid"=>$uid, "fids.fid" =>array('$in' => array($fid))), 
        //                                 array());
        $result = $this->collectionOwnFollow->findOne(function($query) use($uid, $fid) {
            $query->where("uid", $uid)->andWhere("fids.fid", array('$in' => array($fid)));
        });
        $count = count($result);
        if ($count > 0)
            return true;

        return false;
    }

    /**
     * 获取关注人数
     * @param uid 当前用户uid
     * @return int 数量
     */
    protected function getOwnFollowCountDb($uid) {
        //$result = mongoInstance()->find_one(TAB_OWN_FOLLOW_LIST_COLLECTION, array("uid"=>$uid));
        $result = $this->collectionOwnFollow->findOne(function($query) use($uid) {
            $query->where("uid", $uid);
        });
        $count = 0;
        if ($result != NULL) {
            $count = count($result["fids"]);
        }
        return $count;
    }

    /**
     * 根据时间获取关注人数
     * @param uid 当前被关注的用户uid
     * @param time 大于该时间
     * @return int 数量
     */
    protected function getOwnFollowCountByTimeDb($uid, $time) {
        $result = $this->collectionOwnFollow->findOne(function($query) use($uid, $time) {
            $query->where("uid", $uid)->andWhere("fids.time", array('$lt' => $time));
        });
        $count = 0;
        if ($result != NULL) {
            $count = count($result["fids"]);
        }
        return $count;
    }

    /**
     * 获取当前用户关注的信息列表
     * @param uid 用户id
     * @return json字符串，uid和关注者id信息列表
     */
    protected function getOwnFollowListDb($uid) {
        //$arrayResult = mongoInstance()->find(TAB_OWN_FOLLOW_LIST_COLLECTION, array("uid"=>$uid), array());
        $arrayResult = $this->collectionOwnFollow->findOne(function($query) use($uid) {
            $query->where("uid", $uid);
        });
        return $arrayResult;
    }

    /**
     * 根据时间获取当前用户关注的信息列表
     * @param uid 用户id
     * @param timesort 按时间排序方式 1 正序，-1 倒序
     * @param skip 分页用，跳过指定个数
     * @param limit 分页用，获取指定个数记录
     * @return 返回mongodb聚合方式得到的结果
     */
    protected function getOwnFollowListExDb($uid, $timesort='', $skip='', $limit='') {
        $matchCond['uid'] = $uid;
        $cond = array( array('$match' => $matchCond),
                       array('$unwind'=>'$fids'));

        if ($timesort !== '') {
            array_push($cond, array('$sort' => array('fids.time' => $timesort)));
        }
        if ($skip !== '') {
            array_push($cond, array('$skip' => $skip));
        }
        if ($limit !== '') {
            array_push($cond, array('$limit' => $limit));
        }

        //$arrayResult = mongoInstance()->aggregate(TAB_OWN_FOLLOW_LIST_COLLECTION, $cond);
        $arrayResult = $this->collectionOwnFollow->aggregate($cond);
        return $arrayResult;
    }

    /**
     * 更新当前用户所关注的fid为是否重点关注
     * @param uid 用户id
     * @param fid 关注的用户id
     * @param focus 是否重点关注 1 是 0 否
     * @return bool 返回修改结果成功失败
     */
    protected function updateOwnFollowFocusDb($uid, $fid, $focus) {
        // $result = mongoInstance()->update(TAB_OWN_FOLLOW_LIST_COLLECTION, 
        //                         array("uid" => $uid, "fids.fid" =>array('$in' => array($fid))), 
        //                         array('$set' => array("fids.$.focus"=>$focus)));
        $result = $this->collectionOwnFollow->update(function($query) use($uid, $fid, $focus){
            $query->upsert();
            $query->where('uid', $uid)
                  ->andWhere("fids.fid", array('$in' => array($fid)))
                  ->set( array("fids.$.focus"=>$focus) );                            
        }); 

        return $result;
    }

    /**
     * 更新当前用户所关注的fid为是否为互相关注
     * @param uid 用户id
     * @param fid 关注的用户id
     * @param focus 是否互相关注 1 是 0 否
     * @return bool 返回修改结果成功失败
     */
    protected function updateOwnFollowEachFollowDb($uid, $fid, $eachFollow) {
        // $result = mongoInstance()->update(TAB_OWN_FOLLOW_LIST_COLLECTION, 
        //                         array("uid" => $uid, "fids.fid" =>array('$in' => array($fid))), 
        //                         array('$set' => array("fids.$.eachfollow"=>$eachFollow)));
        $result = $this->collectionOwnFollow->update(function($query) use($uid, $fid, $eachFollow){
            $query->upsert();
            $query->where('uid', $uid)
                  ->andWhere("fids.fid", array('$in' => array($fid)))
                  ->set( array("fids.$.eachfollow"=>$eachFollow) );                            
        }); 
        return $result;
    }

    /**
     * 更新当前用户所关注的fid的UserData
     * @param uid 用户id
     * @param fid 关注的用户id
     * @param userdata 需要修改的内容
     * @return bool 返回修改结果成功失败
     */
    protected function updateOwnFollowUserDataDb($uid, $fid, $userdata) {
        // $result = mongoInstance()->update(TAB_OWN_FOLLOW_LIST_COLLECTION, 
        //                         array("uid" => $uid, "fids.fid" =>array('$in' => array($fid))), 
        //                         array('$set' => array("fids.$.userdata"=>$userdata)));
        $result = $this->collectionOwnFollow->update(function($query) use($uid, $fid, $userdata){
            $query->upsert();
            $query->where('uid', $uid)
                  ->andWhere("fids.fid", array('$in' => array($fid)))
                  ->set( array("fids.$.userdata"=>$userdata) );                            
        }); 
        return $result;
    }
}