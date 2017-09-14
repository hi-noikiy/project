<?php
namespace Micro\Frameworks\Logic\Dynamics;

use Phalcon\DI\FactoryDefault;
use League\Monga;

class UserDynamicsDb
{
    protected $collection;
    protected $collectionDynamics;
    protected $comments;
    protected $di;
    protected $status;
    protected $baseCode;
    public function __construct()
    {
        $this->di = FactoryDefault::getDefault();
        $this->mongo = $this->di->get('mongo');
        $this->status = $this->di->get('status');
        $this->baseCode = $this->di->get('baseCode');
        $this->collectionDynamics = $this->mongo->collection('dynamics');
        $this->collectionComments = $this->mongo->collection('comments');
    }

    /**
     * 添加动态
     *
     * @param $uid
     * @param $pid
     * @param $content
     * @param $forward
     * @param $reply
     * @param $praise
     * @param $praiseList
     * @param $forwardList
     * @param $picList
     * @param $pos
     * @param $addtime
     * @return mixed
     */
    protected function addDynamicsDb($uid, $pid, $content, $forward, $reply, $praise, $praiseList, $forwardList , $picList, $pos, $addtime) {
        $result = $this->collectionDynamics->insert( $data = array(
            'uid' => $uid,
            'pid' => $pid,
            'content' => $content,
            'forward' => $forward,
            'reply' => $reply,
            'praise' => $praise,
            'picList'=> $picList,
            'praiseList' => $praiseList,
            'forwardList' => $forwardList,
            'pos' => $pos,
            'addtime' => $addtime
        ));

        if ($result) {
            return $result;
        }

        return FALSE;
    }

    /**
     * 动态评论comments
     *
     * @param $uid
     * @param $toUid
     * @param $did
     * @param $content
     * @param $pos
     * @param $addtime
     * @return mixed
     */
    protected function replyDynamicsDb($uid, $did, $toUid, $content, $pos, $addtime) {
        $result = $this->collectionComments->insert( array(
            'uid' => $uid,
            'toUid' => $toUid,
            'did' => $did,
            'content' => $content,
            'pos' => $pos,
            'addtime' => $addtime,
        ));

        if($result){
            $result = $this->collectionDynamics->update( function($query) use($did){
                $query->whereId($did)->increment('reply');
            });

            if ($result) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * 获得动态评论
     *
     * @param $did
     */
    protected function getReplyList($did){
        $result = array();
        $cond = array();
        $cond['did'] = $did;
        try{
            $cursor = $this->collectionComments->find(function($query) use($cond) {
                $query->where($cond)->orderBy('addtime', 'desc');
            });

            while($res = $cursor->getNext()){
                $result[] = $res;
            }
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }


        return $result;
    }

    /**
     * 获得动态赞
     *
     * @param $did
     */
    protected function getPraiseList($did){
        $result = $this->getDynamicsSingle($did);
        if($result){
            return $result['praiseList'];
        }

        return $result;
    }

    /**
     * 获得动态转发
     *
     * @param $did
     */
    protected function getForwardsList($did){
        $result = $this->getDynamicsSingle($did);
        if($result){
            return $result['forwardList'];
        }

        return $result;
    }

    /**
     * 转发动态
     *
     * @param $uid
     * @param $pid
     * @param $content
     * @param $forwardTag
     * @param $pos
     * @param $addtime
     * @return mixed
     */
    protected function forwardDynamicsDb($uid, $pid, $content, $forwardTag, $pos, $addtime) {
//        $res = $this->getDynamicsSingle($pid);
//        if(!$res){
//            return $this->status->getCode('NOT_EXIST_DYNAMICS');
//        }

        $result = $this->collectionDynamics->insert(array(
            'uid' => $uid,
            'pid' => $pid,
            'content' => $content,
            'pos' => $pos,
            'forwardTag' => $forwardTag,
            'addtime' => $addtime
        ));

        if($result){
            $result = $this->collectionDynamics->update( function($query) use($uid, $pid){
                $query->whereId($pid)
                    ->increment('forward')
                    ->addToSet('forwardList', array(
                        'uid' => $uid,
                        'time' => time()
                    ));
            });

            if ($result) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * 赞动态
     *
     * @param $uid
     * @param $pid
     * @return mixed
     */
    protected function praiseDynamicsDb($uid, $id) {
        $uids = array();
        $res = $this->getDynamicsSingle($id);
        if($res){
            $uids = isset($res['praiseList'][0]) ? $res['praiseList'][0] : array();
        }

        if($uids && is_array($uids)){
            if(in_array($uid, $uids)){
                return $this->status->getCode('REPEAT_PRAISE');
            }
        }

        $result = $this->collectionDynamics->update( function($query) use($uid, $id){
            $query->whereId($id)->increment('praise')->addToSet('praiseList', array(
                'uid' => $uid,
                'time' => time()
            ));
        });

        if ($result) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 删除动态
     *
     * @param $id
     * @return mixed
     */
    protected function delDynamicsDb($id){
//        $res = $this->getDynamicsSingle($id);
//        if(!$res){
//            return $this->status->getCode('NOT_EXIST_DYNAMICS');
//        }

        $result = $this->collectionDynamics->remove(array('_id' => Monga::id($id)));
        if ($result) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 删除动态评论
     *
     * @param $id
     * @param $uid
     * @param $addtime
     * @return mixed
     */
    protected function delDynamicsReplyDb($id){
        $result = $this->collectionComments->update( function($query) use($id){
            $query->remove(array('_id' => Monga::id($id)));
        });

        if ($result) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 更新动态热值
     *
     * @param $id
     * @param $hot
     * @return mixed
     */
    public function updateDynamicsHotDb($id, $hot){
        $result = $this->collectionDynamics->update( function($query) use($id, $hot){
            $query->whereId($id)->set(array('hot' => $hot));
        });

        if ($result) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 获得最高的统计
     *
     * @param $type 类型，1为获得最高评论数，2为最高转发，3为最高赞
     */
    public function getDynamicsCountDb($field){
        if($field){
            $cond = array( '$group' => array('_id' => '', 'num_tutorial' => array('$max' => '$' . $field)));
            $result = $this->collectionDynamics->aggregate($cond);
            if ($result) {
                return $result['result'][0]['num_tutorial'];
            }

            return 0;
        }

        return 0;
    }

    protected function getDynamicsSingle($did){
        $res = $this->collectionDynamics->findOne(function($query) use($did) {
            $query->whereId($did);
        });

        if($res){
            return $res;
        }

        return FALSE;
    }

    /**
     * 获得用户动态列表
     *
     * @param $uid
     * @return mixed
     */
    protected function getDynamicsListDb($uidList, $original = 0, $timeStart = 0, $timeEnd = 0){
        $result = array();
        $cond = array();
        if(!empty($uidList) && is_array($uidList)){
            $cond['uid'] = array('$in' => $uidList);
        }

        if($original > 0){
            $cond['pid'] = 0;
        }

        if($timeStart > 0 && $timeEnd > 0){
            $cond['addtime'] = array('$gt' => $timeStart, '$lt' => $timeEnd);
        }

        try{
            $cursor = $this->collectionDynamics->find(function($query) use($uidList, $cond) {
                $query->where($cond)->orderBy('addtime', 'desc');;
            });

            while($res = $cursor->getNext()){
                $result[] = $res;
            }
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    protected function getUserPraiseListDb($uid){
        // 获得用户所有动态
        $praiseList = array();
        $list = $this->getDynamicsListDb(array($uid));
        if($list){
            foreach($list as &$val){
                if(isset($val['praiseList']) && !empty($val['praiseList'])){
//                    $val['praiseList'][0]['did'] = $val['_id']->{'$id'};
                    foreach($val['praiseList'] as &$v){
                        $v['did'] = $val['_id']->{'$id'};
                    }

                    $praiseList = array_merge($praiseList, $val['praiseList']);
                }
            }
        }

        // 按时间排序
        $praiseList = $this->baseCode->arrayMultiSort($praiseList, 'addtime', TRUE);
        return $praiseList;
    }

    protected function getUserReplyListDb($uid){
        $result = array();
        $cond = array();
        if(!empty($uid)){
            $cond['toUid'] = $uid;
        }

        try{
            $cursor = $this->collectionComments->find(function($query) use($cond) {
                $query->where($cond)->orderBy('addtime', 'desc');
            });

            while($res = $cursor->getNext()){
                $result[] = $res;
            }
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        return $result;
    }

    protected function getUserForwardListDb($uid){
        // 获得用户所有动态
        $forwardList = array();
        $list = $this->getDynamicsListDb(array($uid));
        if($list){
            foreach($list as &$val){
                if(isset($val['forwardList']) && !empty($val['forwardList'])){
                    foreach($val['forwardList'] as &$v){
                        $v['did'] = $val['_id']->{'$id'};
                    }

                    $forwardList = array_merge($forwardList, $val['forwardList']);
                }
            }
        }

        $forwardList = $this->baseCode->arrayMultiSort($forwardList, 'addtime', TRUE);
        return $forwardList;
    }

}