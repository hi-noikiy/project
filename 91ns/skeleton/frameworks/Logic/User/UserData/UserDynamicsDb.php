<?php
namespace Micro\Frameworks\Logic\User\UserData;

use Phalcon\DI\FactoryDefault;

class UserDynamicsDb extends UserDataBase
{
    protected $collection;
    protected $collectionDynamics;
    public function __construct($uid)
    {
        parent::__construct($uid);

        $this->mongo = $this->di->get('mongo');
        $this->collectionDynamics = $this->mongo->collection('dynamics');
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
//        $result = $this->collectionDynamics->update( function($query) use($uid, $pid, $content, $forward, $reply, $praise, $praiseList, $forwardList , $picList, $pos, $addtime){
//            $query->upsert();
//            $query->where('uid', $uid)->andWhere('pid', $pid)
//                  ->set(array(
//                      'uid' => $uid,
//                      'pid' => $pid,
//                  ))
//                  ->push(array(
//                      'content' => $content,
//                      'forward' => $forward,
//                      'reply' => $reply,
//                      'praise' => $praise,
//                      'picList'=> $picList,
//                      'praiseList' => $praiseList,
//                      'forwardList' => $forwardList,
//                      'pos' => $pos,
//                      'addtime' => $addtime
//                  ));
//        });

        $data = array(
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
        );

        $result = $this->collectionDynamics->insert($data);
        if ($result) {
            return $this->status->getCode('OK');
        }

        return $this->status->getCode('DB_OPER_ERROR');
    }

    /**
     * 动态评论
     *
     * @param $uid
     * @param $pid
     * @param $content
     * @param $pos
     * @param $addtime
     * @return mixed
     */
    protected function replyDynamicsDb($uid, $pid, $content, $pos, $addtime) {
        $result = $this->collectionDynamics->update( function($query) use($uid, $pid, $content, $pos, $addtime){
            $query->upsert();
            $query->where('uid', $uid)->andWhere('pid', $pid)
                ->set(array(
                    'uid' => $uid,
                    'pid' => $pid,
                ))
                ->push(array(
                    'content' => $content,
                    'pos' => $pos,
                    'addtime' => $addtime
                ));

            $query->where('_id', $pid)->set(array('reply' => array('$inc' => array('reply' => 1))));
        });

        if ($result) {
            return $this->status->getCode('OK');
        }

        return $this->status->getCode('DB_OPER_ERROR');
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
        $result = $this->collectionDynamics->update( function($query) use($uid, $pid, $forwardTag, $content, $pos, $addtime){
            $query->upsert();
            $query->where('uid', $uid)->andWhere('pid', $pid)
                ->set(array(
                    'uid' => $uid,
                    'pid' => $pid,
                ))
                ->push(array(
                    'content' => $content,
                    'pos' => $pos,
                    'forwardTag' => $forwardTag,
                    'addtime' => $addtime
                ));

            $query->where('_id', $pid)->set(array('forward' => array('$inc' => array('forward' => 1))));
        });

        if ($result) {
            return $this->status->getCode('OK');
        }

        return $this->status->getCode('DB_OPER_ERROR');
    }

    /**
     * 赞动态
     *
     * @param $uid
     * @param $pid
     * @return mixed
     */
    protected function praiseDynamicsDb($uid, $id) {
        $result = $this->collectionDynamics->update( function($query) use($uid, $id){
            $query->upsert();
            $query->where('_id', $id)->set(array('praise' => array('$inc' => array('praise' =>  1))))
                ->addToSet('praiseList', $uid);
        });

        if ($result) {
            return $this->status->getCode('OK');
        }

        return $this->status->getCode('DB_OPER_ERROR');
    }

    /**
     * 获得用户动态列表
     *
     * @param $uid
     * @return mixed
     */
    protected function getDynamicsListDb($uidList, $original = 0,$timeStart, $timeEnd){
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
                $query->where($cond);
            });

            while($res = $cursor->getNext()){
                $result[] = $res;
            }
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }


        return $result;
    }



}