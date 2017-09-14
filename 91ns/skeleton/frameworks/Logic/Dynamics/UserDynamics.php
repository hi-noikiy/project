<?php
namespace Micro\Frameworks\Logic\Dynamics;

use Phalcon\DI\FactoryDefault;

class UserDynamics extends UserDynamicsDb
{
    protected $collection;

    protected $user;

    public function __construct()
    {
        parent::__construct();
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
    public function addDynamics($uid, $pid, $content, $forward, $reply, $praise, $praiseList, $forwardList , $picList, $pos, $addtime){
        $result = $this->addDynamicsDb($uid, $pid, $content, $forward, $reply, $praise, $praiseList, $forwardList , $picList, $pos, $addtime);
        if($result){
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }else{
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    public function getDynamicsInfo($did){
        $result = $this->getDynamicsSingle($did);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function getDynamicsList($uidList, $original = 0,$timeStart, $timeEnd){
        $result = $this->getDynamicsListDb($uidList, $original,$timeStart, $timeEnd);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function replyDynamics($uid, $did, $toUid, $content, $pos, $addtime){
        $result = $this->replyDynamicsDb($uid, trim($did), $toUid, $content, $pos, $addtime);
        if($result){
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }else{
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    public function getDynamicsReply($did){
        $result = $this->getReplyList($did);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function getDynamicsPraise($did){
        $result = $this->getPraiseList($did);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function getDynamicsForwards($did){
        $result = $this->getForwardsList($did);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function praiseDynamics($uid, $did){
        $result = $this->praiseDynamicsDb($uid, $did);
        if($result){
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }else{
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    public function delDynamics($did){
        $result = $this->delDynamicsDb($did);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function forwardDynamics($uid, $did, $content, $pos){
        $result = $this->forwardDynamicsDb($uid, $did, $content, 1, $pos, time());
        if($result){
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }else{
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    public function getDynamicsCount($field){
        $result = $this->getDynamicsCountDb($field);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function updateDynamicsHot($id, $hot){
        $result = $this->updateDynamicsHotDb($id, $hot);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function getUserPraiseList($uid){
        $result = $this->getUserPraiseListDb($uid);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function getUserReplyList($uid){
        $result = $this->getUserReplyListDb($uid);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }

    public function getUserForwardList($uid){
        $result = $this->getUserForwardListDb($uid);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }
}