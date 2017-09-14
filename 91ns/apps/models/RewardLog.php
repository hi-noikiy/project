<?php

namespace Micro\Models;

class RewardLog extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $uid;

    /**
     *
     * @var integer
     */
    public $rewardId;

    /**
     *
     * @var integer
     */
    public $num;

    /**
     *
     * @var integer
     */
    public $addTime;

    /**
     *
     * @var integer
     */
    public $status;

   

    public function getSource()
    {
        return 'pre_reward_log';
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'uid' => 'uid',
            'rewardId' => 'rewardId',
            'num' => 'num',
            'addTime' => 'addTime',
            'status' => 'status', 
        );
    }

}
