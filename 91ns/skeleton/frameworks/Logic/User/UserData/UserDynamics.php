<?php
namespace Micro\Frameworks\Logic\User\UserData;

use Phalcon\DI\FactoryDefault;

use Micro\Models\ConsumeLog;

use Micro\Frameworks\Logic\User\UserFactory;

class UserDynamics extends UserDynamicsDb
{
    protected $collection;

    protected $user;

    public function __construct($uid, $user)
    {
        parent::__construct($uid);
        $this->user = $user;
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
        if($result == $this->status->getCode('OK')){
            return $this->status->retFromFramework($this->status->getCode('OK'), $result);
        }else{
            return $this->status->retFromFramework($this->status->getCode('DB_OPER_ERROR'));
        }
    }

    public function getDynamicsList($uidList, $original = 0,$timeStart, $timeEnd){
        $result = $this->getDynamicsListDb($uidList, $original,$timeStart, $timeEnd);
        return $this->status->retFromFramework($this->status->getCode('OK'), $result);
    }
}